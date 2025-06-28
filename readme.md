# DymsTools

**DymsTools** adalah aplikasi web productivity sederhana berbasis PHP yang menyediakan berbagai alat praktis seperti ToDoList, Timer Fokus, Kalkulator, QR Generator, Unit Converter, dan Note Publik (sticky note).  
Semua fitur dapat digunakan tanpa login dan data disimpan di session atau file JSON (kecuali ToDoList yang berbasis session).

---

## ✨ Fitur Utama

- **ToDoList**  
  Tambah, edit, centang, dan hapus tugas harian. Data tersimpan di session browser.

- **Timer Fokus**  
  Atur waktu fokus (Pomodoro) dengan tampilan modern.

- **Kalkulator**  
  Kalkulator sederhana, responsif, dan mudah digunakan.

- **QR Generator**  
  Buat QR code dari teks atau link secara instan.

- **Unit Converter**  
  Konversi satuan panjang, berat, dan suhu.

- **Note Publik**  
  Tempelkan catatan (sticky note) yang bisa dilihat, ditambah, dan dihapus oleh siapa saja. Data disimpan di file `NoteData.json`.

---

## 🚀 Cara Instalasi

1. **Clone repository ini**
    ```
    git clone https://github.com/username/dymstools.git
    cd dymstools
    ```

2. **Jalankan di XAMPP/Laragon/Localhost**
    - Pastikan folder ini berada di dalam `htdocs` (XAMPP) atau web root server lokal kamu.
    - Tidak perlu database (kecuali jika ingin mengembangkan lebih lanjut).

3. **Akses di browser**
    ```
    http://localhost/productivity/
    ```

---

## 📁 Struktur Folder

```
app/
  controller/      # Controller PHP
  model/           # Model (session/file JSON)
  view/            # Tampilan HTML/CSS/JS
  routes/          # Routing sederhana
  middleware/      # Middleware (CSRF, throttle)
data/
  NoteData.json    # Data Note Publik
index.php          # Entry point aplikasi
```

---

## 🛡️ Keamanan

- **CSRF Protection** untuk endpoint penting.
- **Throttle/Rate Limiter** untuk mencegah spam/DDOS ringan.
- Tidak ada fitur login/register (benar-benar publik).

---

## 📝 Catatan

- Untuk Note Publik, semua orang bisa menambah & menghapus catatan.
- Untuk ToDoList, data hanya tersimpan di session browser masing-masing.
- Jika ingin reset Note Publik, hapus file `data/NoteData.json`.

---

## 📦 Lisensi

MIT License

---

**Dibuat dengan ❤️ oleh DymsTools Team**