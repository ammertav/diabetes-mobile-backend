# Dokumentasi REST API - Diabetes App (v1)

Dokumentasi lengkap REST API Diabetes App mencakup spesifikasi endpoint, header, parameter query/body, tipe data, aturan validasi (*rules*), serta contoh respons sukses (*success*) dan respons gagal (*error*).

---

## 📑 Daftar Isi
1. [Ketentuan Umum & Autentikasi](#1-ketentuan-umum--autentikasi)
2. [Format Respons Standar](#2-format-respons-standar)
3. [Daftar Enum & Referensi Nilai](#3-daftar-enum--referensi-nilai)
4. [Endpoint: Authentication & Profile](#4-authentication--profile)
   - [POST /api/v1/auth/register](#post-apiv1authregister)
   - [POST /api/v1/auth/login](#post-apiv1authlogin)
   - [GET /api/v1/auth/refresh-token](#get-apiv1authrefresh-token)
   - [GET /api/v1/users/profile](#get-apiv1usersprofile)
   - [PUT /api/v1/users/profile](#put-apiv1usersprofile)
5. [Endpoint: Fasting Protocols](#5-fasting-protocols)
   - [GET /api/v1/protocols](#get-apiv1protocols)
   - [POST /api/v1/protocols/select](#post-apiv1protocolsselect)
   - [GET /api/v1/protocols/active](#get-apiv1protocolsactive)
6. [Endpoint: Fasting Logs](#6-fasting-logs)
   - [GET /api/v1/fasting-logs](#get-apiv1fasting-logs)
   - [POST /api/v1/fasting-logs/confirm](#post-apiv1fasting-logsconfirm)
   - [PATCH /api/v1/fasting-logs/{id}/end](#patch-apiv1fasting-logsidend)
7. [Endpoint: Fasting Blood Glucose (FBG)](#7-fasting-blood-glucose-fbg)
   - [POST /api/v1/fgb](#post-apiv1fgb)
   - [GET /api/v1/fgb](#get-apiv1fgb)
8. [Endpoint: Dashboard](#8-dashboard)
   - [GET /api/v1/dashboard](#get-apiv1dashboard)
   - [GET /api/v1/dashboard/fbg-trend](#get-apiv1dashboardfbg-trend)
   - [GET /api/v1/dashboard/export](#get-apiv1dashboardexport)
9. [Endpoint: Safety Alerts](#9-safety-alerts)
   - [GET /api/v1/safety-alerts](#get-apiv1safety-alerts)
   - [PATCH /api/v1/safety-alerts/{id}/acknowledge](#patch-apiv1safety-alertsidacknowledge)
   - [GET /api/v1/safety-alerts/settings](#get-apiv1safety-alertssettings)
   - [PUT /api/v1/safety-alerts/settings](#put-apiv1safety-alertssettings)
10. [Endpoint: Educational & Spiritual Content](#10-educational--spiritual-content)
    - [GET /api/v1/content](#get-apiv1content)
11. [Endpoint: Streaks](#11-streaks)
    - [GET /api/v1/streaks](#get-apiv1streaks)
12. [Endpoint: Notifications](#12-notifications)
    - [GET /api/v1/notifications/settings](#get-apiv1notificationssettings)
    - [PUT /api/v1/notifications/settings](#put-apiv1notificationssettings)
    - [PATCH /api/v1/notifications/fcm-token](#patch-apiv1notificationsfcm-token)
    - [GET /api/v1/notifications/history](#get-apiv1notificationshistory)
    - [PATCH /api/v1/notifications/history/{id}/read](#patch-apiv1notificationshistoryidread)

---

## 1. Ketentuan Umum & Autentikasi

### Base URL
```
http://localhost:8000/api/v1
```
*(Atau sesuaikan dengan host staging/production server Anda).*

### Default Request Headers
Kecuali untuk endpoint autentikasi (`register` dan `login`), semua endpoint privat memerlukan token autentikasi JWT:
```http
Accept: application/json
Content-Type: application/json
Authorization: Bearer <access_token>
```

### Mekanisme Autentikasi
- Menggunakan skema **JWT (JSON Web Token)** custom dengan validasi versi token (`token_version`).
- Access token memiliki masa berlaku pendek, sedangkan refresh token digunakan untuk memperbarui token via `GET /api/v1/auth/refresh-token` dengan menyertakan token pada header `Authorization: Bearer <refresh_token>`.

---

## 2. Format Respons Standar

### Respons Sukses (HTTP 200 / 201)
Format respons payload terbungkus dalam properti `data` (dan opsional `message` / `pagination` / `summary`):
```json
{
  "data": { ... },
  "message": "Deskripsi sukses (opsional)"
}
```

### Respons Validasi Gagal (HTTP 422 Unprocessable Entity)
```json
{
  "message": "Pesan error pertama yang gagal divalidasi",
  "errors": {
    "field_name": [
      "Pesan error spesifik untuk field tersebut"
    ]
  }
}
```

### Respons Konflik / Duplikasi (HTTP 409 Conflict)
Contoh saat email sudah terdaftar pada registrasi atau log puasa sudah dikonfirmasi:
```json
{
  "message": "The email has already been taken.",
  "errors": {
    "email": [
      "The email has already been taken."
    ]
  }
}
```

### Respons Tidak Diotorisasi (HTTP 401 Unauthorized)
```json
{
  "message": "Token tidak ditemukan"
}
```

---

## 3. Daftar Enum & Referensi Nilai

| Enum Class | Field Terkait | Nilai yang Diizinkan |
| :--- | :--- | :--- |
| `Gender` | `gender` | `male`, `female` |
| `DiabetesStatus` | `diabetes_status` | `t2dm`, `prediabetes`, `healthy` |
| `FgbContextTag` | `context_tag` | `before_meal`, `after_meal`, `end_of_fast`, `morning`, `other` |
| `AlertType` | `alert_type` / `type` | `hypo_severe` (<70 mg/dL), `hypo_mild` (<80 mg/dL), `hyper_severe` (>250 mg/dL), `hyper_mild` (>180 mg/dL) |
| `FastingLogMood` | `mood` | `good`, `neutral`, `bad` |
| `FastingLogStatus` | `status` | `planned`, `completed`, `missed`, `skipped` |
| `FbgTrendPeriod` | `period` | `7d`, `30d`, `90d` |
| `CmsContentType` | `content_type` | `motivation`, `reflection`, `education`, `nutrition`, `safety_guide` |
| `CmsDayContext` | `day_context` | `monday`, `thursday`, `general` |
| `ProtocolType` | `type` | `sunnah`, `intermittent`, `custom` |
| `NotificationType` | `type` | `niat`, `sahur`, `fbg_reminder`, `safety`, `motivation` |

---

## 4. Authentication & Profile

### POST /api/v1/auth/register
Mendaftarkan akun pasien mobile baru beserta profil medis awal.

- **Autentikasi**: Publik (Tanpa token)
- **Headers**: `Content-Type: application/json`, `Accept: application/json`

#### Request Body
| Field | Tipe Data | Wajib/Opsional | Aturan Validasi (*Rules*) | Keterangan |
| :--- | :--- | :--- | :--- | :--- |
| `email` | `string` | **Wajib** | `required`, `email`, `max:255`, `unique:users,email` | Alamat email unik |
| `password` | `string` | **Wajib** | `required`, `string`, `min:8`, kombinasi huruf & angka (`regex:/^(?=.*[A-Za-z])(?=.*\d).+$/`) | Kata sandi aman |
| `name` | `string` | **Wajib** | `required`, `string`, `min:2`, `max:100` | Nama lengkap pasien |
| `age` | `integer` | **Wajib** | `required`, `integer`, `between:18,120` | Usia pasien (18-120 tahun) |
| `gender` | `string` | **Wajib** | `required`, `in:male,female` | Jenis kelamin |
| `diabetes_status` | `string` | **Wajib** | `required`, `in:t2dm,prediabetes,healthy` | Kondisi diabetes |
| `bmi` | `numeric` | Opsional | `nullable`, `numeric`, `between:10,70` | Indeks massa tubuh |
| `disclaimer_accepted` | `boolean` | **Wajib** | `required`, `boolean`, `accepted` | Persetujuan persetujuan medis (harus `true`) |

#### Contoh Request
```json
{
  "email": "pasien@example.com",
  "password": "Password123",
  "name": "Ahmad Fauzi",
  "age": 35,
  "gender": "male",
  "diabetes_status": "t2dm",
  "bmi": 24.5,
  "disclaimer_accepted": true
}
```

#### Respons Sukses (200 OK)
```json
{
  "success": true,
  "data": {
    "user": {
      "id": "9d901614-722a-43cf-824b-9d41d13f185d",
      "email": "pasien@example.com",
      "auth_provider": ["email"],
      "created_at": "2026-09-05T14:40:00.000000Z",
      "updated_at": "2026-09-05T14:40:00.000000Z",
      "name": "Ahmad Fauzi",
      "age": 35,
      "gender": "male",
      "diabetes_status": "t2dm",
      "bmi": 24.5
    },
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
    "refresh_token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
  },
  "message": "Registration successful"
}
```

#### Respons Gagal
- **409 Conflict** (Email sudah digunakan):
```json
{
  "message": "The email has already been taken.",
  "errors": {
    "email": ["The email has already been taken."]
  }
}
```
- **422 Unprocessable Entity** (Validasi input gagal):
```json
{
  "message": "The password field format is invalid.",
  "errors": {
    "password": ["The password field format is invalid."]
  }
}
```

---

### POST /api/v1/auth/login
Masuk menggunakan kredensial email dan password.

- **Autentikasi**: Publik (Tanpa token)

#### Request Body
| Field | Tipe Data | Wajib/Opsional | Aturan Validasi (*Rules*) | Keterangan |
| :--- | :--- | :--- | :--- | :--- |
| `email` | `string` | **Wajib** | `required`, `email` | Alamat email terdaftar |
| `password` | `string` | **Wajib** | `required` | Kata sandi akun |

#### Contoh Request
```json
{
  "email": "pasien@example.com",
  "password": "Password123"
}
```

#### Respons Sukses (200 OK)
```json
{
  "data": {
    "user": {
      "id": "9d901614-722a-43cf-824b-9d41d13f185d",
      "email": "pasien@example.com",
      "auth_provider": ["email"],
      "created_at": "2026-09-05T14:40:00.000000Z",
      "updated_at": "2026-09-05T14:40:00.000000Z",
      "name": "Ahmad Fauzi",
      "age": 35,
      "gender": "male",
      "diabetes_status": "t2dm",
      "bmi": 24.5
    },
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
    "refresh_token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
  }
}
```

#### Respons Gagal (401 Unauthorized)
```json
{
  "message": "Invalid credentials"
}
```

---

### GET /api/v1/auth/refresh-token
Memperbarui access token dan me-rotasi refresh token.

- **Autentikasi**: Bearer Token (Kirimkan **refresh token** di header `Authorization`)
- **Header**: `Authorization: Bearer <refresh_token>`

#### Respons Sukses (200 OK)
```json
{
  "data": {
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...(new_access_token)",
    "refresh_token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...(new_refresh_token)"
  }
}
```

#### Respons Gagal (401 Unauthorized)
```json
{
  "error": "Refresh token required"
}
```

---

### GET /api/v1/users/profile
Mengambil informasi detail profil user yang sedang login.

- **Autentikasi**: Bearer Access Token

#### Respons Sukses (200 OK)
```json
{
  "data": {
    "id": "9d901614-722a-43cf-824b-9d41d13f185d",
    "email": "pasien@example.com",
    "auth_provider": ["email"],
    "created_at": "2026-09-05T14:40:00.000000Z",
    "updated_at": "2026-09-05T14:40:00.000000Z",
    "name": "Ahmad Fauzi",
    "age": 35,
    "gender": "male",
    "diabetes_status": "t2dm",
    "bmi": 24.5
  }
}
```

---

### PUT /api/v1/users/profile
Memperbarui data profil pasien mobile.

- **Autentikasi**: Bearer Access Token

#### Request Body
| Field | Tipe Data | Wajib/Opsional | Aturan Validasi (*Rules*) | Keterangan |
| :--- | :--- | :--- | :--- | :--- |
| `name` | `string` | **Wajib** | `required`, `string`, `min:2`, `max:100` | Nama lengkap |
| `age` | `integer` | **Wajib** | `required`, `integer`, `between:18,120` | Usia |
| `bmi` | `numeric` | Opsional | `nullable`, `numeric`, `between:10,70` | Indeks massa tubuh |
| `diabetes_status` | `string` | **Wajib** | `required`, `in:t2dm,prediabetes,healthy` | Kondisi diabetes |

#### Contoh Request
```json
{
  "name": "Ahmad Fauzi Rahman",
  "age": 36,
  "bmi": 23.8,
  "diabetes_status": "prediabetes"
}
```

#### Respons Sukses (200 OK)
```json
{
  "data": {
    "id": "9d901614-722a-43cf-824b-9d41d13f185d",
    "email": "pasien@example.com",
    "auth_provider": ["email"],
    "created_at": "2026-09-05T14:40:00.000000Z",
    "updated_at": "2026-09-05T15:10:00.000000Z",
    "name": "Ahmad Fauzi Rahman",
    "age": 36,
    "gender": "male",
    "diabetes_status": "prediabetes",
    "bmi": 23.8
  },
  "message": "Profile updated successfully"
}
```

---

## 5. Fasting Protocols

### GET /api/v1/protocols
Mendapatkan daftar seluruh protokol puasa medis yang tersedia.

- **Autentikasi**: Bearer Access Token

#### Respons Sukses (200 OK)
```json
{
  "data": [
    {
      "id": 1,
      "name": "Puasa Senin & Kamis",
      "type": "sunnah",
      "start_time": "18:00",
      "end_time": "10:00",
      "fasting_days": [1, 4],
      "duration_hours": 14,
      "description": "Protokol puasa sunnah dua hari seminggu (Senin dan Kamis)."
    },
    {
      "id": 2,
      "name": "Intermittent Fasting 16/8",
      "type": "intermittent",
      "start_time": "20:00",
      "end_time": "12:00",
      "fasting_days": [1, 2, 3, 4, 5, 6, 7],
      "duration_hours": 16,
      "description": "Jendela makan 8 jam dan puasa 16 jam setiap hari."
    }
  ]
}
```

---

### POST /api/v1/protocols/select
Memilih protokol puasa aktif dan otomatis membuat jadwal (*planned fasting logs*) selama 4 minggu ke depan.

- **Autentikasi**: Bearer Access Token

#### Request Body
| Field | Tipe Data | Wajib/Opsional | Aturan Validasi (*Rules*) | Keterangan |
| :--- | :--- | :--- | :--- | :--- |
| `protocol_id` | `integer` | **Wajib** | `required`, `exists:fasting_protocols,id` | ID protokol puasa |
| `start_date` | `string (date)` | **Wajib** | `required`, `date`, `after_or_equal:today` | Tanggal mulai (format: `YYYY-MM-DD`, tidak boleh tanggal lampau) |

#### Contoh Request
```json
{
  "protocol_id": 1,
  "start_date": "2026-09-07"
}
```

#### Respons Sukses (200 OK)
```json
{
  "message": "Protocol selected"
}
```

#### Respons Gagal (422 Unprocessable Entity - Protokol Sama Sedang Aktif)
```json
{
  "message": "Protokol puasa ini sudah aktif.",
  "data": {
    "protocol_id": 1,
    "start_date": "2026-09-01",
    "end_date": null,
    "status": "active"
  }
}
```

---

### GET /api/v1/protocols/active
Mengambil rincian protokol puasa yang saat ini sedang aktif diikuti oleh user beserta rasio kepatuhannya (*adherence rate*).

- **Autentikasi**: Bearer Access Token

#### Respons Sukses (200 OK)
```json
{
  "user_protocol_id": 3,
  "protocol": {
    "id": 1,
    "name": "Puasa Senin & Kamis",
    "type": "sunnah",
    "duration_hours": 14,
    "description": "Protokol puasa sunnah dua hari seminggu."
  },
  "start_date": "2026-09-01",
  "status": "active",
  "adherence_rate": 0.88
}
```

#### Respons Gagal (404 Not Found)
Jika pengguna belum memilih/memiliki protokol puasa yang berstatus `active`:
```json
{
  "message": "No query results for model [App\\Models\\UserProtocol]."
}
```

---

## 6. Fasting Logs

### GET /api/v1/fasting-logs
Mengambil riwayat log puasa dengan filter, kalkulasi ringkasan kepatuhan, dan cursor pagination.

- **Autentikasi**: Bearer Access Token

#### Query Parameters
| Parameter | Tipe Data | Wajib/Opsional | Aturan Validasi (*Rules*) | Keterangan |
| :--- | :--- | :--- | :--- | :--- |
| `start_date` | `string (date)` | Opsional | `nullable`, `date` | Filter tanggal awal (`YYYY-MM-DD`) |
| `end_date` | `string (date)` | Opsional | `nullable`, `date` | Filter tanggal akhir (`YYYY-MM-DD`) |
| `status` | `string` | Opsional | `nullable`, `in:completed,skipped,pending` | Filter status log puasa |
| `limit` | `integer` | Opsional | `nullable`, `integer`, `max:50` | Jumlah record per halaman (default: 20, max: 50) |
| `cursor` | `integer` | Opsional | `nullable`, `integer` | ID log terakhir untuk pagination |

#### Respons Sukses (200 OK)
```json
{
  "data": [
    {
      "id": 15,
      "planned_date": "2026-09-04",
      "is_completed": true,
      "actual_duration_min": 840,
      "mood": "good",
      "server_timestamp": "2026-09-05T21:40:00.000Z"
    }
  ],
  "summary": {
    "total": 8,
    "completed": 7,
    "skipped": 1,
    "adherence_rate": 0.875
  },
  "pagination": {
    "has_next": true,
    "next_cursor": 12
  }
}
```

---

### POST /api/v1/fasting-logs/confirm
Konfirmasi penyelesaian atau pembatalan puasa pada hari ini.

- **Autentikasi**: Bearer Access Token

#### Request Body
| Field | Tipe Data | Wajib/Opsional | Aturan Validasi (*Rules*) | Keterangan |
| :--- | :--- | :--- | :--- | :--- |
| `planned_date` | `string (date)` | **Wajib** | `required`, `date`, `in:<today>` | Harus sama dengan tanggal hari ini (`YYYY-MM-DD`) |
| `is_completed` | `boolean` | **Wajib** | `required`, `boolean` | `true` jika berpuasa, `false` jika dilewati |
| `skip_reason` | `string` | Bersyarat | `nullable`, `string`, `required_if:is_completed,false` | Alasan jika puasa dilewati/batal |
| `mood` | `string` | Opsional | `nullable`, `in:good,neutral,bad` | Kondisi perasaan/energi saat berpuasa |
| `notes` | `string` | Opsional | `nullable`, `string`, `max:500` | Catatan tambahan |

#### Contoh Request (Selesai Berpuasa)
```json
{
  "planned_date": "2026-09-05",
  "is_completed": true,
  "mood": "good",
  "notes": "Tubuh terasa segar dan tidak mengalami pusing."
}
```

#### Respons Sukses (200 OK)
```json
{
  "message": "Fasting log confirmed",
  "data": {
    "id": 16,
    "planned_date": "2026-09-05",
    "is_completed": true,
    "mood": "good",
    "notes": "Tubuh terasa segar dan tidak mengalami pusing.",
    "confirmed_at": "2026-09-05T18:05:00.000000Z"
  }
}
```

#### Respons Gagal
- **409 Conflict** (Log puasa tanggal tersebut sudah pernah dikonfirmasi sebelumnya):
```json
{
  "message": "Already confirmed",
  "data": {
    "id": 16,
    "planned_date": "2026-09-05",
    "is_completed": true,
    "mood": "good",
    "notes": "Tubuh terasa segar...",
    "confirmed_at": "2026-09-05T18:05:00.000000Z"
  }
}
```
- **404 Not Found** (Tidak ada jadwal puasa pada tanggal tersebut):
```json
{
  "message": "No planned fasting"
}
```

---

### PATCH /api/v1/fasting-logs/{id}/end
Mengakhiri sesi puasa yang sedang berlangsung dan mencatat durasi puasa aktual (`actual_duration_min`).

- **Autentikasi**: Bearer Access Token
- **URL Parameter**: `id` (`integer`, ID log puasa)

#### Respons Sukses (200 OK)
```json
{
  "message": "Fasting ended"
}
```

#### Respons Gagal
- **400 Bad Request** (Status log bukan `completed`):
```json
{
  "message": "Invalid state"
}
```
- **409 Conflict** (Puasa sudah pernah diakhiri sebelumnya):
```json
{
  "message": "Already ended"
}
```

---

## 7. Fasting Blood Glucose (FBG)

### POST /api/v1/fgb
Mencatat kadar gula darah puasa (FBG) sekaligus memicu deteksi otomatis bahaya hipoglikemia / hiperglikemia (*Safety Alert*).

- **Autentikasi**: Bearer Access Token

#### Request Body
| Field | Tipe Data | Wajib/Opsional | Aturan Validasi (*Rules*) | Keterangan |
| :--- | :--- | :--- | :--- | :--- |
| `value_mg_dl` | `numeric` | **Wajib** | `required`, `numeric`, `between:40,600` | Nilai kadar gula darah (40 s/d 600 mg/dL) |
| `context_tag` | `string` | **Wajib** | `required`, `in:before_meal,after_meal,end_of_fast,morning,other` | Konteks saat pengukuran gula darah |
| `fasting_log_id` | `string (UUID)` | Opsional | `nullable`, `uuid`, `exists:fasting_logs,id` | Relasi ke log puasa terkait |
| `client_timestamp` | `string (date-time)`| **Wajib** | `required`, `date` | Waktu lokal pengukuran di perangkat HP |

#### Contoh Request
```json
{
  "value_mg_dl": 65,
  "context_tag": "morning",
  "client_timestamp": "2026-09-05T06:30:00+07:00"
}
```

#### Respons Sukses (201 Created)
```json
{
  "data": {
    "id": "e63a1290-7d72-4632-bd32-84191c7849e2",
    "value_mg_dl": 65,
    "context_tag": "morning",
    "is_fasting_day": true,
    "server_timestamp": "2026-09-05T06:30:05.123456Z",
    "alert": {
      "triggered": true,
      "type": "hypo_severe",
      "message": "FBG Anda 65 mg/dL — terlalu rendah (berbahaya).",
      "action": "Segera konsumsi gula dan hubungi tenaga medis."
    }
  }
}
```

*Catatan: Jika gula darah dalam batas aman, properti `alert.triggered` bernilai `false`.*

---

### GET /api/v1/fgb
Mengambil seluruh daftar riwayat pengukuran gula darah pengguna secara descending.

- **Autentikasi**: Bearer Access Token

#### Respons Sukses (200 OK)
```json
{
  "data": [
    {
      "id": "e63a1290-7d72-4632-bd32-84191c7849e2",
      "value_mg_dl": 110,
      "context_tag": "morning",
      "is_fasting_day": true,
      "server_timestamp": "2026-09-05T06:30:05.000000Z",
      "alert": {
        "triggered": false,
        "type": null,
        "message": null,
        "action": null
      }
    }
  ]
}
```

---

## 8. Dashboard

### GET /api/v1/dashboard
Mengambil kumpulan metrik beranda: status puasa hari ini, rata-rata FBG (mingguan & bulanan), data streak, rasio kepatuhan, serta kata motivasi harian.

- **Autentikasi**: Bearer Access Token

#### Respons Sukses (200 OK)
```json
{
  "data": {
    "today": {
      "is_fasting_day": true,
      "fasting_status": "completed",
      "protocol_name": "Puasa Senin & Kamis"
    },
    "fbg_summary": {
      "weekly_avg": 112.4,
      "monthly_avg": 118.2,
      "last_value": 105.0,
      "last_measured_at": "2026-09-05T06:30:00.000Z",
      "min_30d": 92.0,
      "max_30d": 145.0,
      "fasting_day_avg": 108.5,
      "non_fasting_day_avg": 122.1
    },
    "streak": {
      "current": 4,
      "best": 8,
      "total_fasting_days": 18
    },
    "adherence": {
      "this_week": 1.0,
      "this_month": 0.875,
      "overall": 0.857
    },
    "motivation": {
      "title": "Semangat hari Sabtu!",
      "body": "Tetap konsisten, catat hasil puasa, dan teruskan perjalanan kesehatanmu hari ini."
    }
  }
}
```

---

### GET /api/v1/dashboard/fbg-trend
Mengambil data titik tren FBG untuk visualisasi grafik.

- **Autentikasi**: Bearer Access Token

#### Query Parameters
| Parameter | Tipe Data | Wajib/Opsional | Aturan Validasi (*Rules*) | Keterangan |
| :--- | :--- | :--- | :--- | :--- |
| `period` | `string` | Opsional | `nullable`, `in:7d,30d,90d` | Rentang waktu grafik (default: `30d`) |

#### Respons Sukses (200 OK)
```json
{
  "data": [
    {
      "date": "2026-09-01",
      "value_mg_dl": 115.0,
      "is_fasting_day": true,
      "context_tag": "morning"
    },
    {
      "date": "2026-09-03",
      "value_mg_dl": 108.0,
      "is_fasting_day": true,
      "context_tag": "morning"
    }
  ]
}
```

---

### GET /api/v1/dashboard/export
Mengekspor riwayat kesehatan dalam format file CSV (*streamed file*).

- **Autentikasi**: Bearer Access Token

#### Query Parameters
| Parameter | Tipe Data | Wajib/Opsional | Aturan Validasi (*Rules*) | Keterangan |
| :--- | :--- | :--- | :--- | :--- |
| `start_date` | `string (date)` | **Wajib** | `required`, `date_format:Y-m-d` | Format: `YYYY-MM-DD` |
| `end_date` | `string (date)` | **Wajib** | `required`, `date_format:Y-m-d`, `after_or_equal:start_date` | Tanggal akhir |
| `include` | `string` | Opsional | `nullable`, `string` | Pilihan bagian data dipisahkan koma: `fbg,fasting_logs,alerts` (default: menyertakan ketiganya) |

#### Respons Sukses (200 OK)
- **Headers**: `Content-Type: text/csv`, `Content-Disposition: attachment; filename="dashboard-export-2026-09-01-2026-09-05.csv"`
- Berisi file CSV berformat tabel multi-section (FBG Records, Fasting Logs, Safety Alerts).

---

## 9. Safety Alerts

### GET /api/v1/safety-alerts
Mengambil daftar peringatan keselamatan (hipo/hiperglikemia) pasien.

- **Autentikasi**: Bearer Access Token

#### Query Parameters
| Parameter | Tipe Data | Wajib/Opsional | Aturan Validasi (*Rules*) | Keterangan |
| :--- | :--- | :--- | :--- | :--- |
| `alert_type` | `string` | Opsional | `nullable`, `in:hypo_severe,hypo_mild,hyper_severe,hyper_mild` | Filter tipe peringatan |
| `acknowledged` | `boolean` | Opsional | `nullable`, `boolean` | Filter status konfirmasi |
| `limit` | `integer` | Opsional | `nullable`, `integer` | Batas jumlah data (default: 20) |

#### Respons Sukses (200 OK)
```json
{
  "data": [
    {
      "id": "7a309e32-9c17-48f1-86c3-1d02c63ef299",
      "type": "hypo_severe",
      "message": "FBG Anda 65 mg/dL — terlalu rendah (berbahaya).",
      "action": "Segera konsumsi gula dan hubungi tenaga medis.",
      "created_at": "2026-09-05T06:30:05.000000Z"
    }
  ]
}
```

---

### PATCH /api/v1/safety-alerts/{id}/acknowledge
Mengonfirmasi bahwa pasien telah membaca dan mengambil tindakan atas peringatan keselamatan.

- **Autentikasi**: Bearer Access Token
- **URL Parameter**: `id` (`string / UUID`, ID alert)

#### Request Body
| Field | Tipe Data | Wajib/Opsional | Aturan Validasi (*Rules*) | Keterangan |
| :--- | :--- | :--- | :--- | :--- |
| `action_taken` | `string` | Opsional | `nullable`, `string` | Tindakan yang dilakukan oleh pasien |

#### Contoh Request
```json
{
  "action_taken": "Sudah meminum teh manis hangat dan beristirahat 15 menit."
}
```

#### Respons Sukses (200 OK)
```json
{
  "message": "Alert acknowledged successfully",
  "data": {
    "id": "7a309e32-9c17-48f1-86c3-1d02c63ef299",
    "type": "hypo_severe",
    "message": "FBG Anda 65 mg/dL — terlalu rendah (berbahaya).",
    "acknowledged_at": "2026-09-05T06:45:00.000000Z",
    "action_taken": "Sudah meminum teh manis hangat dan beristirahat 15 menit."
  }
}
```

#### Respons Gagal (422 Unprocessable Entity - Sudah Dikonfirmasi)
```json
{
  "message": "Alert already acknowledged"
}
```

---

### GET /api/v1/safety-alerts/settings
Mengambil konfigurasi batas nilai ambang bahaya (*threshold*) gula darah pasien.

- **Autentikasi**: Bearer Access Token

#### Respons Sukses (200 OK)
```json
{
  "data": {
    "hypo_severe": 70,
    "hypo_mild": 80,
    "hyper_severe": 250,
    "hyper_mild": 180
  }
}
```

---

### PUT /api/v1/safety-alerts/settings
Memperbarui batas ambang peringatan keselamatan gula darah pasien.

- **Autentikasi**: Bearer Access Token

#### Request Body
| Field | Tipe Data | Wajib/Opsional | Aturan Validasi (*Rules*) | Keterangan |
| :--- | :--- | :--- | :--- | :--- |
| `hypo_severe` | `integer` | Opsional | `nullable`, `integer`, `min:30`, `max:80` | Ambang hipoglikemia berat (mg/dL) |
| `hypo_mild` | `integer` | Opsional | `nullable`, `integer`, `min:50`, `max:100`, `gt:hypo_severe`, `lt:hyper_mild` | Ambang hipoglikemia ringan |
| `hyper_mild` | `integer` | Opsional | `nullable`, `integer`, `min:140`, `max:300`, `gt:hypo_mild`, `lt:hyper_severe` | Ambang hiperglikemia ringan |
| `hyper_severe` | `integer` | Opsional | `nullable`, `integer`, `min:200`, `max:500`, `gt:hyper_mild` | Ambang hiperglikemia berat |

#### Contoh Request
```json
{
  "hypo_severe": 65,
  "hypo_mild": 75,
  "hyper_mild": 190,
  "hyper_severe": 260
}
```

#### Respons Sukses (200 OK)
```json
{
  "message": "Settings updated successfully",
  "data": {
    "hypo_severe": 65,
    "hypo_mild": 75,
    "hyper_severe": 260,
    "hyper_mild": 190
  }
}
```

---

## 10. Educational & Spiritual Content

### GET /api/v1/content
Mengambil konten artikel edukasi medis, tips nutrisi, panduan keselamatan, atau refleksi spiritual yang telah dipublikasikan.

- **Autentikasi**: Bearer Access Token

#### Query Parameters
| Parameter | Tipe Data | Wajib/Opsional | Aturan Validasi (*Rules*) | Keterangan |
| :--- | :--- | :--- | :--- | :--- |
| `content_type` | `string` | Opsional | `nullable`, `in:motivation,reflection,education,nutrition,safety_guide` | Tipe konten |
| `day_context` | `string` | Opsional | `nullable`, `in:monday,thursday,general` | Konteks hari puasa sunnah |
| `limit` | `integer` | Opsional | `nullable`, `integer`, `min:1`, `max:100` | Batas per halaman |

#### Respons Sukses (200 OK)
```json
{
  "data": [
    {
      "id": 1,
      "content_type": "education",
      "day_context": "monday",
      "title": "Manfaat Puasa Sunnah untuk Sensitivitas Insulin",
      "body": "Puasa intermiten atau puasa sunnah secara ilmiah terbukti membantu sel tubuh meregulasi glukosa..."
    }
  ]
}
```

---

## 11. Streaks

### GET /api/v1/streaks
Mengambil ringkasan konsistensi puasa pasien.

- **Autentikasi**: Bearer Access Token

#### Respons Sukses (200 OK)
```json
{
  "data": {
    "current_streak": 4,
    "best_streak": 9,
    "total_fasting_days": 21,
    "last_fasting_date": "2026-09-04"
  }
}
```

---

## 12. Notifications

### GET /api/v1/notifications/settings
Mendapatkan konfigurasi preferensi pengingat push notification pengguna.

- **Autentikasi**: Bearer Access Token

#### Respons Sukses (200 OK)
```json
{
  "data": {
    "niat_puasa_enabled": true,
    "niat_puasa_time": "20:00",
    "sahur_enabled": true,
    "sahur_time": "03:30",
    "fbg_reminder_enabled": true,
    "fbg_reminder_time": "06:00",
    "motivation_enabled": true
  }
}
```

---

### PUT /api/v1/notifications/settings
Mengubah preferensi jadwal pengingat dan aktivasi notifikasi.

- **Autentikasi**: Bearer Access Token

#### Request Body
| Field | Tipe Data | Wajib/Opsional | Aturan Validasi (*Rules*) | Keterangan |
| :--- | :--- | :--- | :--- | :--- |
| `niat_puasa_enabled` | `boolean` | Opsional | `nullable`, `boolean` | Aktifkan pengingat niat puasa malam hari |
| `niat_puasa_time` | `string (time)` | Opsional | `nullable`, `date_format:H:i` | Jam pengingat niat (format `HH:mm`, cth: `20:30`) |
| `sahur_enabled` | `boolean` | Opsional | `nullable`, `boolean` | Aktifkan pengingat sahur |
| `sahur_time` | `string (time)` | Opsional | `nullable`, `date_format:H:i` | Jam pengingat sahur (format `HH:mm`) |
| `fbg_reminder_enabled` | `boolean` | Opsional | `nullable`, `boolean` | Aktifkan pengingat ukur gula darah |
| `fbg_reminder_time` | `string (time)` | Opsional | `nullable`, `date_format:H:i` | Jam pengingat ukur gula (format `HH:mm`) |
| `motivation_enabled` | `boolean` | Opsional | `nullable`, `boolean` | Aktifkan pesan motivasi harian |

#### Contoh Request
```json
{
  "niat_puasa_enabled": true,
  "niat_puasa_time": "21:00",
  "sahur_enabled": true,
  "sahur_time": "03:45",
  "fbg_reminder_enabled": true,
  "fbg_reminder_time": "06:15",
  "motivation_enabled": true
}
```

#### Respons Sukses (200 OK)
```json
{
  "data": {
    "niat_puasa_enabled": true,
    "niat_puasa_time": "21:00",
    "sahur_enabled": true,
    "sahur_time": "03:45",
    "fbg_reminder_enabled": true,
    "fbg_reminder_time": "06:15",
    "motivation_enabled": true
  }
}
```

---

### PATCH /api/v1/notifications/fcm-token
Mendaftarkan atau memperbarui Firebase Cloud Messaging (FCM) device token untuk push notification.

- **Autentikasi**: Bearer Access Token

#### Request Body
| Field | Tipe Data | Wajib/Opsional | Aturan Validasi (*Rules*) | Keterangan |
| :--- | :--- | :--- | :--- | :--- |
| `fcm_token` | `string` | **Wajib** | `required`, `string` | Token registrasi FCM dari perangkat |
| `platform` | `string` | Opsional | `nullable`, `string`, `max:50` | Platform OS perangkat (cth: `android`, `ios`) |

#### Contoh Request
```json
{
  "fcm_token": "fN4uQ-2sQyG9...dKl90a:APA91bF...",
  "platform": "android"
}
```

#### Respons Sukses (200 OK)
```json
{
  "success": true,
  "message": "FCM token registered successfully"
}
```

---

### GET /api/v1/notifications/history
Mengambil riwayat notifikasi yang dikirimkan ke perangkat pengguna dengan cursor pagination.

- **Autentikasi**: Bearer Access Token

#### Query Parameters
| Parameter | Tipe Data | Wajib/Opsional | Aturan Validasi (*Rules*) | Keterangan |
| :--- | :--- | :--- | :--- | :--- |
| `type` | `string` | Opsional | `nullable`, `in:niat,sahur,fbg_reminder,safety,motivation` | Filter kategori notifikasi |
| `read` | `boolean` | Opsional | `nullable`, `boolean` | `true` untuk notifikasi sudah dibaca, `false` untuk belum dibaca |
| `limit` | `integer` | Opsional | `nullable`, `integer`, `min:1`, `max:100` | Jumlah data per halaman (default: 20) |

#### Respons Sukses (200 OK)
```json
{
  "data": [
    {
      "id": 102,
      "type": "safety",
      "title": "Peringatan Gula Darah Rendah",
      "body": "Gula darah Anda tercatat 65 mg/dL. Segera konsumsi sumber glukosa.",
      "data": {
        "alert_id": "7a309e32-9c17-48f1-86c3-1d02c63ef299",
        "value": 65
      },
      "read_at": null,
      "created_at": "2026-09-05T06:30:10.000000Z"
    }
  ]
}
```

---

### PATCH /api/v1/notifications/history/{id}/read
Menandai suatu notifikasi telah dibaca oleh pengguna.

- **Autentikasi**: Bearer Access Token
- **URL Parameter**: `id` (`integer`, ID notifikasi)

#### Respons Sukses (200 OK)
```json
{
  "data": {
    "id": 102,
    "type": "safety",
    "title": "Peringatan Gula Darah Rendah",
    "body": "Gula darah Anda tercatat 65 mg/dL. Segera konsumsi sumber glukosa.",
    "data": {
      "alert_id": "7a309e32-9c17-48f1-86c3-1d02c63ef299",
      "value": 65
    },
    "read_at": "2026-09-05T07:00:00.000000Z",
    "created_at": "2026-09-05T06:30:10.000000Z"
  }
}
```

#### Respons Gagal (404 Not Found)
Jika notifikasi dengan ID tersebut tidak ditemukan atau bukan milik pengguna yang sedang login:
```json
{
  "message": "No query results for model [App\\Models\\UserNotification]."
}
```
