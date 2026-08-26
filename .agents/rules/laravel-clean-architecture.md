# Laravel Clean Architecture Guidelines (Action Pattern)

Standar arsitektur dan konvensi kode untuk proyek Laravel menggunakan **Action Pattern** dan **5-Layer Clean Architecture System**.

---

## 1. Aturan Emas Keterbacaan Kode (Golden Rules)

- **Panjang Berkas Maksimal**: Maksimal **150 baris kode** per berkas (termasuk Controller, Model, Action, Request, DTO, Resource, dan Blade file).
- **Panjang Method Maksimal**: Setiap method maksimal **25 baris kode**. Jika lebih panjang, lakukan refactoring ke private helper methods atau Action terpisah.
- **Kompleksitas Kognitif Rendah**: Hindari nested loop, percabangan `if-else` bertingkat dalam, dan query Eloquent/Database inline yang panjang.

---

## 2. Action Pattern (`app/Actions/`)

Semua logika bisnis (business logic) **WAJIB** diisolasi dalam kelas Action tunggal (single-responsibility) di bawah namespace `App\Actions\` dan dikelompokkan berdasarkan fitur (misal: `App\Actions\Auth\`, `App\Actions\Fasting\`, `App\Actions\Safety\`).

### Struktur Kelas Action:
- Penamaan menggunakan kata kerja yang menggambarkan tugas (contoh: `RegisterUserAction`, `StoreFastingLogAction`, `CalculateRiskScoreAction`).
- Setiap Action hanya memiliki **satu method publik**, yaitu `execute()` atau `handle()`.
- Gunakan Dependency Injection pada konstruktor untuk menyuntikkan repository atau service yang dibutuhkan.

```php
<?php

namespace App\Actions\Auth;

use App\Models\User;
use App\Models\MobileProfile;
use App\Models\UserAuthProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterUserAction
{
    public function execute(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'email' => $data['email'],
                'type' => $data['type'],
            ]);

            MobileProfile::create(array_merge($data['profile'], ['user_id' => $user->id]));

            UserAuthProvider::create([
                'user_id' => $user->id,
                'provider' => $data['provider'],
                'provider_id' => $data['email'],
                'password_hash' => Hash::make($data['password']),
            ]);

            return $user;
        });
    }
}
```

---

## 3. Thin Controllers (`app/Http/Controllers/`)

Controller bertindak **hanya** sebagai pengatur lalu lintas (traffic controller). Controller DILARANG berisi logika bisnis, query database manual, atau validasi inline.

### Panduan Controller:
- **Tanpa Validasi Inline**: Selalu gunakan **Form Request** (`app/Http/Requests`) untuk memvalidasi input.
- **Delegasi Logika Bisnis**: Panggil kelas Action untuk mengeksekusi operasi bisnis.
- **Tanpa Query DB Langsung**: Dilarang menjalankan `DB::table()`, Eloquent Builder kompleks, atau transaksi langsung di Controller.
- **API Resource**: Gunakan **API Resource** (`app/Http/Resources`) untuk memformat respons JSON.

```php
<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Actions\Auth\RegisterUserAction;
use App\Utilities\JwtUtility;

class AuthController extends Controller
{
    public function register(RegisterRequest $request, RegisterUserAction $registerAction)
    {
        $user = $registerAction->execute($request->validated());
        
        $token = JwtUtility::generateAccessToken($user);
        $refreshToken = JwtUtility::generateRefreshToken($user);

        return response()->json([
            'success' => true,
            'data' => [
                'user' => new UserResource($user->load('mobileProfile')),
                'token' => $token,
                'refresh_token' => $refreshToken['token'],
            ],
            'message' => 'Registration successful'
        ]);
    }
}
```

---

## 4. Thin Models (`app/Models/`)

Model mewakili struktur tabel database dan relasi antar tabel, bukan proses bisnis.

### Boleh di dalam Model:
- Relasi Eloquent (`belongsTo`, `hasMany`, `belongsToMany`).
- Attribute Casting (`protected $casts`).
- Local Query Scopes (misal: `scopeActive`, `scopeFilter`).
- Accessors dan Mutators.

### DILARANG di dalam Model:
- Query kompleks yang melintasi banyak domain.
- Mengubah tabel/model lain secara langsung.
- Mengirim notifikasi, membuat token eksternal, atau menulis log.

---

## 5. Form Requests (`app/Http/Requests/`)

Gunakan Form Request untuk memisahkan validasi input dari Controller.

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'name' => ['required', 'string', 'max:255'],
        ];
    }
}
```

---

## 6. Alur 5-Layer Clean Architecture System

Untuk operasi dengan parameter kompleks, pemfilteran data, dan pemformatan:

1. **Form Request**: Memvalidasi data masukan dari HTTP request (`app/Http/Requests`).
2. **DTO (Data Transfer Object)**: Membungkus parameter tervalidasi ke dalam strongly-typed class (`app/DTO`).
3. **Action Class**: Mengeksekusi proses bisnis dan mengoordinasikan scope query (`app/Actions`).
4. **Query Scopes**: Mendefinisikan filter Eloquent di dalam Local Scopes Model (`app/Models`).
5. **API Resource / Presenter**: Mengubah hasil Eloquent menjadi bentuk respons JSON yang terstruktur (`app/Http/Resources`).

---

## 7. Arsitektur Views & Blade Partials Pattern (`resources/views/`)

Untuk mencegah berkas Blade menjadi terlalu panjang (aturan maks 150 baris tetap berlaku):

```
resources/views/
├── [feature]/
│   ├── index.blade.php             # Main page template
│   └── partials/                   # Sub-sections
│       ├── _stats.blade.php
│       ├── _filters.blade.php
│       └── _modal-add.blade.php
```

- **Gunakan Partials (`@include`)**: Untuk komponen atau seksi halaman yang kompleks, pisahkan ke dalam folder `partials/` dengan prefix garis bawah (misal: `_filters.blade.php`).
- **Gunakan Blade Components (`<x-...>`)**: Untuk elemen UI yang reusable di berbagai fitur (badge, button, modal skeleton, alert box).
