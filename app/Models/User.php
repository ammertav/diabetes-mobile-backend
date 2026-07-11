<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserProtocolStatus;
use App\Enums\UserType;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Guarded(['id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasUuids;

    protected $guarded = ['id'];
    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'type' => UserType::class,
        ];
    }

    public function authProviders()
    {
        return $this->hasMany(UserAuthProvider::class);
    }

    public function adminProfile()
    {
        return $this->hasOne(AdminProfile::class);
    }

    public function mobileProfile()
    {
        return $this->hasOne(MobileProfile::class);
    }

    public function emailProvider()
    {
        return $this->authProviders()
            ->where('provider', \App\Enums\AuthProvider::EMAIL->value)
            ->first();
    }

    public function userProtocol()
    {
        return $this->hasMany(UserProtocol::class);
    }

    public function activeProtocol()
    {
        return $this->hasOne(UserProtocol::class)
            ->where('status', UserProtocolStatus::ACTIVE);
    }

    public function fgbRecords()
    {
        return $this->hasMany(FgbRecord::class);
    }

    public function safetyAlerts()
    {
        return $this->hasMany(SafetyAlert::class);
    }

    public function safetyAlert(string $id)
    {
        return $this->safetyAlerts()->where('id', $id)->firstOrFail();
    }

    public function alertSetting()
    {
        return $this->hasOne(UserAlertSetting::class);
    }

    public function fcmDevices()
    {
        return $this->hasMany(FcmDevice::class);
    }

    public function latestCheckin()
    {
        return $this->hasOne(FgbRecord::class)->latestOfMany('server_timestamp');
    }

    public function getRiskStatusAttribute(): string
    {
        $alerts = $this->safetyAlerts;

        $hasSevere = $alerts->whereNull('acknowledged_at')
            ->whereIn('type', ['hypo_severe', 'hyper_severe'])
            ->isNotEmpty();

        if ($hasSevere) {
            return 'High';
        }

        $hasMild = $alerts->whereNull('acknowledged_at')
            ->whereIn('type', ['hypo_mild', 'hyper_mild'])
            ->isNotEmpty();

        if ($hasMild) {
            return 'Medium';
        }

        return 'Low';
    }

    public function scopeSearch($query, ?string $search)
    {
        if (empty($search)) {
            return $query;
        }

        $searchWildcard = '%' . $search . '%';
        return $query->where(function ($q) use ($searchWildcard) {
            $q->where('email', 'like', $searchWildcard)
              ->orWhereHas('mobileProfile', function ($qp) use ($searchWildcard) {
                  $qp->where('name', 'like', $searchWildcard);
              })
              ->orWhereHas('activeProtocol.protocol', function ($qp) use ($searchWildcard) {
                  $qp->where('name', 'like', $searchWildcard);
              });
        });
    }

    public function scopeFilterByRisk($query, ?string $risk)
    {
        if (empty($risk) || $risk === 'all') {
            return $query;
        }

        $risk = strtolower($risk);
        if ($risk === 'high') {
            return $query->whereHas('safetyAlerts', function ($q) {
                $q->whereNull('acknowledged_at')
                  ->whereIn('type', ['hypo_severe', 'hyper_severe']);
            });
        }

        if ($risk === 'medium') {
            return $query->whereHas('safetyAlerts', function ($q) {
                $q->whereNull('acknowledged_at')
                  ->whereIn('type', ['hypo_mild', 'hyper_mild']);
            })->whereDoesntHave('safetyAlerts', function ($q) {
                $q->whereNull('acknowledged_at')
                  ->whereIn('type', ['hypo_severe', 'hyper_severe']);
            });
        }

        if ($risk === 'low') {
            return $query->whereDoesntHave('safetyAlerts', function ($q) {
                $q->whereNull('acknowledged_at');
            });
        }

        return $query;
    }

    public function scopeFilterByProtocol($query, ?string $protocolId)
    {
        if (empty($protocolId) || $protocolId === 'all') {
            return $query;
        }

        return $query->whereHas('activeProtocol', function ($q) use ($protocolId) {
            $q->where('fasting_protocol_id', $protocolId);
        });
    }

    public function scopeFilterByCheckinDate($query, ?string $date)
    {
        if (empty($date)) {
            return $query;
        }

        return $query->whereHas('latestCheckin', function ($q) use ($date) {
            $q->whereDate('server_timestamp', $date);
        });
    }
}
