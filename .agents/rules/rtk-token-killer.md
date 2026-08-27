# Rust Token Killer (RTK) & Token Efficiency Guidelines

Aturan dan pedoman penggunaan **RTK (Rust Token Killer)** serta efisiensi penggunaan context token untuk AI Agents pada proyek ini.

---

## ⚡ 1. Prinsip Utama RTK (Rust Token Killer)

- **Kompresi Output Tool & Perintah CLI**:
  - Gunakan perkakas/perintah pendukung RTK jika tersedia untuk memfilter output konsol, build log, atau hasil query yang berukuran besar sebelum masuk ke context window.
  - Saat menjalankan perintah CLI (seperti `git`, `composer`, `php artisan`), prioritaskan flag yang membatasi jumlah baris output (misalnya `git log -n <N>`, `grep` spesifik, atau pengabaian verbose output).

- **Strict Token Management**:
  - DILARANG membaca/menampilkan seluruh isi file yang berukuran besar jika hanya membutuhkan bagian tertentu. Gunakan parameter `StartLine` dan `EndLine` pada tool pembaca file secara presisi.
  - Untuk pencarian file atau simbol, utamakan `grep_search` atau `list_dir` bertahap daripada memindai file satu per satu secara manual.

---

## 🏛️ 2. Aturan Praktis AI Agent saat Mengolah Kode

1. **Focused Snippet Inspection**:
   - Hanya panggil `view_file` pada rentang baris yang relevan dengan masalah/fitur yang sedang dikerjakan (misal: baris 1-30 untuk import/header, atau rentang function tertentu).
2. **Batch & Multi-Replace**:
   - Jika terdapat banyak perubahan kecil dalam satu file, gunakan `multi_replace_file_content` daripada melakukan penulisan ulang seluruh file (`write_to_file`) yang memboroskan token.
3. **No Unnecessary Repetition**:
   - Jangan mengulang-ulang penjelasan panjang atau output perintah yang sudah ada di history percakapan. Berikan ringkasan yang padat, singkat, dan tepat sasaran.
