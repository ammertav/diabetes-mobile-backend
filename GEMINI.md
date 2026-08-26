# Laravel Clean Architecture & AI Agent Rules Index

Sistem petunjuk dan standar arsitektur untuk proyek **Laravel Diabetes App** menggunakan **Action Pattern** dan **5-Layer Clean Architecture**.

---

## 📚 Indeks Rules & Pedoman Proyek

Aturan rinci dipisahkan ke dalam direktori `.agents/rules/` untuk kemudahan pemeliharaan:

1. 🏛️ **[Clean Architecture Guidelines](file:///Users/phosphophyllite/Documents/Development/Laravel/laravel-diabetes-app/.agents/rules/laravel-clean-architecture.md)**
   - Action Pattern di `app/Actions/`
   - Golden Rules (Maks 150 baris per file, maks 25 baris per method)
   - Thin Controllers, Form Requests (`app/Http/Requests`), Thin Models, DTOs (`app/DTO`), API Resources (`app/Http/Resources`)
   - Arsitektur Views & Blade Partials Pattern (`resources/views/[feature]/partials/`)

2. 🧪 **[Testing, Git Workflow, & Security](file:///Users/phosphophyllite/Documents/Development/Laravel/laravel-diabetes-app/.agents/rules/testing-git-security.md)**
   - Pengujian terotomatisasi (Pest / PHPUnit) & HTTP Faking
   - Standard Commit Git (Conventional Commits: `feat:`, `fix:`, `refactor:`)
   - Keamanan data (Mass assignment `$fillable`, SQL Injection prevention, sanitasi input, env secrets)

3. 💡 **[Panduan & Template Penulisan Rules AI Agents](file:///Users/phosphophyllite/Documents/Development/Laravel/laravel-diabetes-app/.agents/rules/ai-agent-rules-guide.md)**
   - Cara kerja Discovery & Precedence Rules AI
   - Praktik terbaik penulisan rules yang efektif
   - Template siap pakai untuk Global Rules (`~/.gemini/config/rules/`) dan Project Rules

---

## ⚡ Ringkasan Aturan Utama (Quick Rules Checklist)

- **Thin Controllers**: Controller hanya bertindak sebagai router. Tidak ada query database atau logika bisnis di controller.
- **Single-Responsibility Actions**: Semua logika bisnis diisolasi di `app/Actions/[Feature]/[Name]Action.php` dengan satu method publik `execute()`.
- **Form Requests Required**: Semua HTTP input harus divalidasi via Form Request (`app/Http/Requests`).
- **Thin Models**: Model hanya berisi relasi, scopes, casting, accessor/mutator. Dilarang melakukan transaksi atau manipulasi tabel lain di Model.
- **Golden Limit**: Maksimal **150 baris** per file kode, maksimal **25 baris** per method.
