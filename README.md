# Dokumentasi Proyek: Laravel Diabetes & Fasting Monitor API

## Deskripsi Singkat
Aplikasi backend berbasis Laravel untuk mengelola dan memonitor protokol puasa (fasting) serta kadar gula darah harian (FBG - Fasting Blood Glucose) bagi pengguna diabetes.

Aplikasi ini dibagi menjadi 2 peranan utama:
1. **API (Mobile)**: Digunakan oleh **pasien/pengguna diabetes (tipe `MOBILE`)** untuk mengelola profil, mendaftar protokol puasa, mencatat/mengakhiri puasa harian, mencatat kadar gula darah harian, serta menerima notifikasi alert kesehatan secara instan.
2. **WEB Dashboard**: Digunakan oleh **pengawas/dokter (Supervisors - tipe `ADMIN`)** untuk mengawasi pasien secara real-time, mendeteksi peringatan kritis (severe alert) jika kadar gula darah berada dalam zona bahaya (hipoglikemia/hiperglikemia), serta mengonfirmasi penanganan medis yang diberikan (*acknowledgement*).
   - *Catatan:* Saat ini, sistem dikonfigurasi untuk menggunakan 1 akun pengawas utama (tipe `ADMIN`), sehingga akun administrator tunggal sudah cukup untuk mencakup kebutuhan operasional pengawas.

---

## 🛠 Arsitektur & Pola Desain (Action Pattern)
Proyek ini mengikuti aturan **Action Pattern** untuk memastikan file tetap kecil, mudah dibaca, serta memisahkan tanggung jawab logika bisnis dari lapisan HTTP (Controllers).

- **Aturan Keterbacaan File**:
  - Maksimal baris per file: **150 baris**.
  - Maksimal baris per method: **25 baris**.
- **Action Classes**: Seluruh logika bisnis dipindahkan ke `app/Actions` yang dikelompokkan berdasarkan fiturnya:
  - **`app/Actions/Auth`**: Logika registrasi pasien, otentikasi login, rotasi JWT refresh token, dan pembaruan profil pengguna.
  - **`app/Actions/Fasting`**: Logika pencarian/filter histori puasa, konfirmasi log puasa harian, menghentikan puasa aktif, serta inisiasi rencana puasa 4 minggu.
  - **`app/Actions/Fgb`**: Pencatatan kadar gula darah dan pemicu otomatis sistem peringatan keselamatan.
  - **`app/Actions/Safety`**: Konfirmasi penanganan alert keselamatan medis dan pembaruan batas ambang kadar gula darah.

---

## 👥 Aktor & Akun Default (Seeder)

Data default dapat diinisiasi menggunakan seeder Laravel. Akun bawaan setelah seeding (`php artisan db:seed`):

### 1. Akun Pengawas (Web Dashboard - ADMIN)
*   **Email**: `admin@app.com`
*   **Password**: `Admin1234`
*   **Tipe Akun**: `UserType::ADMIN`

### 2. Akun Pasien / Pengguna Diabetes (Mobile API - MOBILE)
*   **Email**: `user@app.com` dan `user2@app.com`
*   **Password**: `User1234`
*   **Tipe Akun**: `UserType::MOBILE`

---

## 🔒 Sistem Keamanan Kadar Gula Darah (Safety Alert Threshold)
Setiap kali pasien menginput kadar gula darah (FBG Record), sistem secara otomatis akan memvalidasi kadar tersebut berdasarkan batas batas berikut untuk mendeteksi bahaya medis:

| Kadar FBG (mg/dL) | Tipe Peringatan | Pesan Peringatan | Tindakan Medis yang Disarankan |
|---|---|---|---|
| **< 70** | `hypo_severe` (Hipoglikemia Berat) | *FBG Anda {value} mg/dL — terlalu rendah (berbahaya).* | *Segera konsumsi gula cepat serap dan hubungi tenaga medis.* |
| **70 - 79** | `hypo_mild` (Hipoglikemia Ringan) | *FBG Anda {value} mg/dL — di bawah normal.* | *Pertimbangkan konsumsi makanan manis ringan.* |
| **80 - 180** | *Normal (Tidak memicu alert)* | - | - |
| **181 - 250** | `hyper_mild` (Hiperglikemia Ringan) | *FBG Anda {value} mg/dL — di atas normal.* | *Pantau kadar gula darah secara berkala.* |
| **> 250** | `hyper_severe` (Hiperglikemia Berat) | *FBG Anda {value} mg/dL — sangat tinggi.* | *Segera lakukan konsultasi medis/dokter.* |

Peringatan bahaya akan tersimpan di tabel `safety_alerts` sehingga pengawas/admin dapat memantau dan memberikan status konfirmasi penanganan (`acknowledge`) di web dashboard.

---

## 🚀 Panduan Instalasi & Setup Lokal

Ikuti langkah-langkah berikut untuk menjalankan proyek di komputer lokal Anda:

### 1. Kloning & Masuk ke Folder Proyek
```bash
git clone <url-repository>
cd laravel-diabetes-app
```

### 2. Instalasi Dependensi PHP
```bash
composer install
```

### 3. Konfigurasi Environment File
Salin file konfigurasi lingkungan:
```bash
cp .env.example .env
```
Buka file `.env` dan sesuaikan koneksi database MySQL Anda (misal nama database: `laravel_diabetes_app`, username, dan password).

### 4. Membuat Application Key
```bash
php artisan key:generate
```

### 5. Membuat Kunci RSA untuk Token JWT (Penting)
Aplikasi menggunakan token JWT berbasis algoritma `RS256` untuk keamanan otentikasi API mobile. Jalankan perintah berikut untuk membuat direktori kunci dan men-generate kunci privat/publik baru:
```bash
mkdir -p storage/app/private/keys storage/app/public/keys
openssl genrsa -out storage/app/private/keys/private.key 2048
openssl rsa -in storage/app/private/keys/private.key -pubout -out storage/app/public/keys/public.key
```

### 6. Migrasi & Seeding Database
Jalankan migrasi database dan masukkan data awal (termasuk akun administrator/admin dan protokol puasa bawaan):
```bash
php artisan migrate --seed
```

### 7. Jalankan Server Lokal
```bash
php artisan serve
```
Aplikasi Anda akan berjalan di `http://127.0.0.1:8000`.

---

## 🧪 Pengujian Unit & Fitur (Testing)
Proyek ini dilengkapi dengan unit & feature testing menggunakan Pest/PHPUnit. Untuk menjalankan seluruh rangkaian tes otomatis:
```bash
php artisan test
```
