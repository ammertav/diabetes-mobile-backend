# Panduan Penulisan Rules AI Agents yang Efektif

Dokumen ini berisi panduan komprehensif, konsep teknis, dan template siap pakai untuk menulis **Rules AI Agents** (Antigravity, Gemini 2.0, Cursor, GitHub Copilot).

---

## 💡 1. Konsep Dasar & Cara Kerja Rules AI Agents

Rules adalah petunjuk, batasan arsitektur, dan gaya penulisan kode yang disuntikkan ke dalam konteks AI Agent untuk memastikan AI menghasilkan kode sesuai standar proyek Anda secara otomatis.

### A. Lokasi Discovery & Hirarki Pembacaan Rules
1. **Workspace / Project Rules (Prioritas Tertinggi)**:
   - Berada di direktori `.agents/` atau `.agent/` di root proyek.
   - Contoh berkas: `.agents/AGENTS.md`, `GEMINI.md`, `.agents/rules/*.md`.
   - Di-commit ke Version Control (Git) agar berlaku bagi seluruh tim developer.
2. **Global Rules (Machine-Local)**:
   - Berada di direktori `~/.gemini/config/` (atau `~/.gemini/config/rules/`).
   - Berlaku untuk semua proyek yang Anda buka di komputer lokal Anda.

### B. Progressive Disclosure (Penghematan Context Window)
Untuk mencegah penggunaan token berlebihan:
- **Rule Selalu Aktif (`always_on`)**: Langsung dibaca oleh agent pada setiap percakapan.
- **Rule Berdasar Keputusan Agent (`model_decision`)**: Hanya nama dan deskripsi ringkasnya yang dibaca terlebih dahulu, lalu konten penuh di-load saat agent mengerjakan tugas terkait.

---

## ✍️ 2. Praktik Terbaik Penulisan Rules yang Efektif

1. **Jelas, Spesifik, dan Aktif (Actionable)**:
   - ❌ *Buruk*: "Buat kode yang bersih dan rapi."
   - ✅ *Baik*: "Setiap kelas Action hanya boleh memiliki satu method publik `execute()` atau `handle()`."
2. **Berikan Contoh Positif & Negatif**:
   - Sertakan cuplikan kode contoh yang benar (Do's) dan yang dilarang (Don'ts).
3. **Batasi Panjang & Kompleksitas**:
   - Gunakan aturan kuantitatif yang terukur (misal: "Maksimal 150 baris kode per berkas", "Maksimal 25 baris per method").
4. **Modulitisasi Rules**:
   - Pisahkan aturan berdasarkan topik di dalam folder `.agents/rules/` (misal: `clean-architecture.md`, `testing-git-security.md`) agar mudah dipelihara.

---

## 📋 3. Template Siap Pakai (Boilerplate Templates)

### A. Template Global Rules (`~/.gemini/config/rules/global-agent-rules.md`)
Salin template ini ke konfigurasi global komputer Anda jika ingin mengatur perilaku agent di seluruh proyek.

```markdown
# Global AI Agent Guidelines

## 1. Perilaku General Agent
- Selalu utamakan keamanan data dan kebersihan kode.
- Jangan gunakan `print()` atau debug logging sementara di production code.
- Gunakan pesan commit dengan standar Conventional Commits (`feat:`, `fix:`, `refactor:`).

## 2. Kebersihan Kode (Clean Code)
- Terapkan prinsip Single Responsibility Principle (SRP) pada setiap kelas.
- Hindari magic numbers dan hardcoded string; gunakan konstanta atau enum.
- Sertakan type-hints dan return type declaration secara eksplisit pada fungsi.
```

### B. Template Project Rules (`.agents/rules/project-standard.md`)
Salin template ini ke dalam direktori `.agents/rules/` di proyek Anda.

```markdown
# Project Standards & Architectural Rules

## 1. Aturan Dasar
- Framework: [Nama Framework & Versi]
- Bahasa: [Bahasa Pemrograman & Versi]
- Batas Maksimal Berkas: 150 baris kode.

## 2. Pola Arsitektur
- Detail arsitektur yang dipakai (misal: Clean Architecture, Action Pattern, DDD).
- Contoh struktur folder dan kelas.

## 3. Pengujian & Keamanan
- Lokasi test dan penamaan file test.
- Penanganan variabel lingkungan (.env) dan kredensial.
```
