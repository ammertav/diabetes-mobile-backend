<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UpdateUserProfileAction
{
    public function execute(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            $user->mobileProfile->update([
                'name' => $data['name'],
                'age' => $data['age'],
                'bmi' => $data['bmi'],
                'diabetes_status' => $data['diabetes_status'],
            ]);

            return $user->fresh()->load('mobileProfile');
        });
    }
}
