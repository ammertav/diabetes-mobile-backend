# Guidelines for Testing, Git Workflow, & Security

Standar pengujian (Testing), aturan manajemen versi (Git), dan praktik keamanan (Security) untuk proyek Laravel ini.

---

## 🏛️ 1. Standar Pengujian (Testing Standard)

Setiap fitur baru atau perbaikan bug **WAJIB** dilengkapi dengan pengujian terotomatisasi (Pest PHP / PHPUnit).

### A. Lokasi & Struktur Test
- **Unit Tests (`tests/Unit/`)**: Untuk menguji Action class, DTO, Utilities, dan logic murni tanpa ketergantungan HTTP/Database jika memungkinkan.
- **Feature Tests (`tests/Feature/`)**: Untuk menguji endpoint API, alur HTTP request -> validation -> action -> DB transaction -> response resource.

### B. Aturan Penulisan Test:
1. **Refresh Database**: Selalu gunakan trait `Illuminate\Foundation\Testing\RefreshDatabase` atau `DatabaseTransactions` pada Feature Test.
2. **Arrange-Act-Assert (AAA)**:
   - *Arrange*: Siapkan mock, factory data, dan payload.
   - *Act*: Panggil endpoint API atau jalankan Action.
   - *Assert*: Verifikasi status HTTP, struktur JSON, dan kondisi record database (`assertDatabaseHas`).
3. **Mocking HTTP / Service Eksternal**:
   - DILARANG melakukan panggilan jaringan (network request) sungguhan saat testing.
   - Gunakan `Http::fake()` untuk memalsukan respons API eksternal.
4. **Cakupan Skenario**:
   - Uji skenario sukses (Happy Path).
   - Uji skenario kegagalan validasi (Validation Errors / 422).
   - Uji skenario otorisasi & autentikasi (Unauthenticated / 401 & Unauthorized / 403).

---

## 🔀 2. Git Workflow & Commit Standard

Gunakan standar **Conventional Commits** untuk menjaga riwayat git tetap rapi dan dapat ditelusuri.

### Format Pesan Commit:
`<type>(<scope>): <short description>`

### Jenis Commit (`type`):
- `feat`: Penambahan fitur baru (misal: `feat(auth): add google OAuth login`).
- `fix`: Perbaikan bug (misal: `fix(fasting): correct blood glucose threshold calculation`).
- `refactor`: Perubahan struktur kode tanpa mengubah fungsi luar (misal: `refactor(patient): extract filter query scope to DTO`).
- `test`: Penambahan atau perbaikan unit/feature test (misal: `test(fgb): add test for fgb log submission`).
- `docs`: Perubahan dokumentasi (misal: `docs(readme): update API setup instructions`).
- `chore`: Tugas pemeliharaan, build config, atau update dependency.

### Aturan Git Tambahan:
- Buat commit secara atomik (satu commit mewakili satu perubahan logis).
- Jangan sertakan berkas sensitif (`.env`, secret keys) dalam commit.

---

## 🔒 3. Keamanan (Security Guidelines)

- **Proteksi Mass Assignment**: Selalu definisikan `$fillable` secara eksplisit pada setiap Model Eloquent. DILARANG menggunakan `$guarded = []`.
- **Pencegahan SQL Injection**: Gunakan parameter binding Eloquent atau PDO binding. Hindari concatenation string pada query mentah (`DB::raw`).
- **Sanitasi & Validasi Input**: Jangan pernah memercayai input pengguna. Semua data dari HTTP Request harus divalidasi melalui Form Request.
- **Manajemen Kunci & Rahasia**:
  - Semua credentials (API Key, JWT Secret, DB Password) harus disimpan di `.env` dan diakses melalui `config()`.
  - DILARANG menuliskannya secara hardcoded di dalam kode.
- **Autentikasi & Otorisasi**:
  - Gunakan middleware autentikasi (misal: `auth:sanctum` atau JWT middleware) untuk endpoint privat.
  - Gunakan Policy atau Gate untuk mengecek hak akses pengguna terhadap resource.
- **Respons Bebas Sensitive Leak**: Pastikan exception di environment production tidak membocorkan stack trace atau kredensial database kepada klien (`APP_DEBUG=false`).
