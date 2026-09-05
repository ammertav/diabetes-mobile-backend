# Dokumentasi Proyek: Laravel Diabetes & Fasting Monitor API

## Deskripsi Singkat
Aplikasi backend berbasis Laravel untuk mengelola dan memonitor protokol puasa (fasting) serta kadar gula darah harian (FBG - Fasting Blood Glucose) bagi pengguna diabetes.

Aplikasi ini dibagi menjadi 2 peranan utama:
1. **API (Mobile)**: Digunakan oleh **pasien/pengguna diabetes (tipe `MOBILE`)** untuk mengelola profil, mendaftar protokol puasa, mencatat/mengakhiri puasa harian, mencatat kadar gula darah harian, serta menerima notifikasi alert kesehatan secara instan.
   - 📖 **Spesifikasi & Kontrak REST API Lengkap**: Lihat panduan di [API_DOCUMENTATION.md](file:///Users/phosphophyllite/Documents/Development/Laravel/laravel-diabetes-app/API_DOCUMENTATION.md).
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

## 🧪 Pengujian Terotomatisasi (Automated Testing)
Proyek ini dilengkapi dengan unit & feature testing menggunakan Pest/PHPUnit. Untuk menjalankan seluruh rangkaian tes otomatis:
```bash
php artisan test
```

---

## 📡 Panduan Pengujian API & Skenario Testing (Tester / Developer Guide)

Panduan langkah demi langkah berikut dapat digunakan untuk menguji fungsionalitas API dan Web Dashboard secara langsung.

### 1. Tool Pengujian yang Disarankan: Bruno
Di repositori proyek ini sudah tersedia koleksi **Bruno** di folder `bruno/` yang sudah terkonfigurasi dengan penyimpanan token JWT otomatis.
- Unduh & install [Bruno](https://www.usebruno.com/).
- Buka Bruno > **Open Collection** > Pilih folder `bruno` di repositori ini.
- Di pojok kanan atas Bruno, pilih environment **Local** (`baseUrl: http://127.0.0.1:8000/api/v1`).
- *(Alternatif jika menggunakan Postman/cURL: gunakan base URL `http://127.0.0.1:8000/api/v1` dan sertakan Bearer Token pada Header `Authorization: Bearer <token>`)*.

---

### 2. Skenario Pengujian Alur End-to-End

#### Skenario 1: Autentikasi (Mendapatkan Access Token Pasien)
Setiap endpoint API (kecuali login/register) memerlukan otentikasi Bearer Token JWT.
- **Method**: `POST`
- **URL**: `{{baseUrl}}/auth/login` (`http://127.0.0.1:8000/api/v1/auth/login`)
- **Headers**: `Content-Type: application/json`
- **Body**:
  ```json
  {
    "email": "user@app.com",
    "password": "User1234"
  }
  ```
- **Cara Melihat Respon**:
  - Respon sukses berstatus `200 OK`.
  - Simpan nilai `data.token` untuk request selanjutnya (di Bruno, skrip otomatis menyimpannya ke variabel `{{token}}`).

---

#### Skenario 2: Mengelola & Memilih Protokol Puasa

> **Catatan Alur**: Master protokol puasa dibuat oleh Admin (via Web Dashboard), sedangkan Pasien melihat dan memilih protokol yang ingin dijalankan via API Mobile.

1. **Melihat Daftar Protokol Puasa yang Tersedia**:
   - **Method**: `GET`
   - **URL**: `{{baseUrl}}/protocols`
   - **Headers**: `Authorization: Bearer <token>`
   - **Respon `200 OK`**: Mengembalikan daftar protokol (misal: *Puasa Senin-Kamis*, *Puasa Daud*, dsb.) beserta `id`-nya.

2. **Pasien Memilih Protokol**:
   - **Method**: `POST`
   - **URL**: `{{baseUrl}}/protocols/select`
   - **Headers**:
     - `Authorization: Bearer <token>`
     - `Content-Type: application/json`
   - **Body**:
     ```json
     {
       "protocol_id": "1",
       "start_date": "2026-09-06"
     }
     ```
     *(Catatan: `start_date` harus tanggal hari ini atau masa mendatang dalam format `YYYY-MM-DD`)*.
   - **Respon `200 OK`**:
     ```json
     {
       "message": "Protocol selected"
     }
     ```

3. **Mengecek Protokol Aktif Pasien**:
   - **Method**: `GET`
   - **URL**: `{{baseUrl}}/protocols/active`
   - **Headers**: `Authorization: Bearer <token>`
   - **Respon `200 OK`**: Menampilkan rincian protokol yang aktif serta persentase kepatuhan (`adherence_rate`).

---

#### Skenario 3: Mencatat Data Kadar Gula Darah (FGB Records) & Safety Alert

Setiap pencatatan kadar gula darah (FGB) otomatis dianalisis oleh sistem keselamatan medis (*Safety Alert Trigger*).

1. **Input Gula Darah Normal (Contoh: 110 mg/dL)**:
   - **Method**: `POST`
   - **URL**: `{{baseUrl}}/fgb`
   - **Headers**:
     - `Authorization: Bearer <token>`
     - `Content-Type: application/json`
   - **Body**:
     ```json
     {
       "value_mg_dl": 110.0,
       "context_tag": "morning",
       "client_timestamp": "2026-09-05T07:00:00Z"
     }
     ```
     > **Pilihan `context_tag` yang valid**: `morning`, `before_meal`, `after_meal`, `end_of_fast`, `other`.
   - **Respon `201 Created`**:
     ```json
     {
       "data": {
         "id": "uuid-fgb-...",
         "value_mg_dl": 110,
         "context_tag": "morning",
         "is_fasting_day": false,
         "server_timestamp": "2026-09-05T11:00:00.000000Z",
         "alert": null
       }
     }
     ```

2. **Input Gula Darah Bahaya (Contoh Hipoglikemia Berat: 60 mg/dL)**:
   - Ubah `value_mg_dl` menjadi `60.0`.
   - **Respon `201 Created`**: Perhatikan objek `alert` tidak lagi `null`:
     ```json
     {
       "data": {
         "value_mg_dl": 60,
         "alert": {
           "alert_type": "hypo_severe",
           "severity": "critical",
           "message": "FBG Anda 60 mg/dL — terlalu rendah (berbahaya). Segera konsumsi gula cepat serap."
         }
       }
     }
     ```

3. **Melihat Riwayat Data FGB Pasien**:
   - **Method**: `GET`
   - **URL**: `{{baseUrl}}/fgb`
   - **Headers**: `Authorization: Bearer <token>`
   - **Respon `200 OK`**: Mengembalikan daftar riwayat seluruh data FGB milik pasien tersebut.

---

### 3. Membuat Master Protokol Baru (Web Dashboard Admin)
Jika penguji ingin membuat jenis protokol puasa baru selain data bawaan seeder:
1. Buka browser: `http://127.0.0.1:8000/login`
2. Login sebagai Admin: `admin@app.com` / `Admin1234`
3. Navigasi ke menu **Protokol Puasa** (`/fasting-protocols`).
4. Klik **Tambah Protokol** (`/fasting-protocols/create`).
5. Masukkan Nama, Tipe (`sunnah`/`intermittent`), Durasi, dan Jadwal Hari Puasa.
6. Klik Simpan. Protokol baru akan langsung muncul di endpoint API `GET /api/v1/protocols`.

---

### 4. Memverifikasi Hasil di Web Monitoring Dokter/Pengawas
Setelah data FGB dikirimkan melalui API:
1. Tetap login di Web Dashboard Admin (`http://127.0.0.1:8000`).
2. Buka menu **FGB Monitoring** (`/fgb-monitoring`): Grafik tren dan tabel riwayat pasien `user@app.com` akan langsung ter-update dengan data FGB yang baru diinput.
3. Buka menu **Safety Alerts** (`/safety-alerts`): Jika data FGB bernilai bahaya (< 70 atau > 250 mg/dL), peringatan kritis baru akan muncul pada daftar pengawasan untuk dapat di-acknowledge oleh dokter.

---

### 5. Panduan Kode Respon HTTP (Error Reference)
- **`200 OK` / `201 Created`**: Permintaan berhasil diproses.
- **`401 Unauthorized`**: Token JWT belum dikirim atau sudah kedaluwarsa. Lakukan login ulang untuk memperbarui token.
- **`422 Unprocessable Entity`**: Validasi data input gagal (misal: nilai `value_mg_dl` di luar batas 40-600, format tanggal salah, atau protokol sudah aktif). Rincian kesalahan ada di key `errors` pada respon JSON.
- **`404 Not Found`**: Rute endpoint atau data dengan ID tertentu tidak ditemukan di database.

