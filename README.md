# LAB-WEB-10-2025

Repositori ini adalah tempat pengumpulan tugas-tugas praktikum mulai dari praktikum pertama hingga akhir mata kuliah Praktikum Pemrograman Web 2025.

## Cara Pengumpulan

Berikut tata cara pengumpulan tugas praktikum di repositori ini.

### 1. Fork dan Clone Repositori

> **Note:** Pastikan kalian sudah menginstall dan login pada git.
1. Pada halaman repositori ini, klik tombol **Fork** di kanan atas lalu **Create New Fork**.

   <img src="https://github.com/user-attachments/assets/5e1f73b0-8ea6-4b67-9b33-661e26371eed" alt="tutor-1" width="400">

2. Clone repositori yang telah difork ke dalam device kalian.
   ```bash
   git clone https://github.com/username/LAB-WEB-10-2025.git
   ```

3. Masuk ke folder repositori yang sudah di-clone.
   ```bash
   cd LAB-WEB-10-2025
   ```

### 2. Membuat Branch dengan NIM

4. Buat branch baru menggunakan NIM kalian.
   ```bash
   git checkout -b <NIM>
   ```
   **Contoh:**
   ```bash
   git checkout -b H071231009
   ```

5. Buat folder NIM di dalam repositori.
   ```bash
   mkdir <NIM>
   cd <NIM>
   ```

### 3. Menambahkan Tugas

> **Note:** Pastikan di tahap ini kalian sudah selesai mengasistensikan tugas kalian!
6. Masukkan file tugas kalian ke dalam folder NIM dengan struktur folder:
   ```
   <NIM>/
   ├── Praktikum-1/
   ├── Praktikum-2/
   ├── Praktikum-3/
   └── ...
   ```

   **Contoh struktur:**
   ```
   H071221001/
   ├── Praktikum-1/
   │   ├── index.html
   │   └── style.css
   ├── Praktikum-2/
   │   ├── script.js
   │   └── index.html
   └── Praktikum-3/
       └── app.py
   ```

### 4. Push ke Branch NIM

7. Lakukan commit dan push ke branch NIM kalian.
   ```bash
   git add .
   git commit -m "<Pesan Commit>"
   git push origin <NIM>
   ```

   **Contoh:**
   ```bash
   git add .
   git commit -m "feat: menambahkan Praktikum-1 H071231009"
   git push origin H071231009
   ```

### 5. Membuat Pull Request

> **Note:** Pastikan file tugas sudah ada di repositori GitHub (hasil fork)
8. Buka repositori hasil fork di GitHub
9. Klik tombol **Compare & Pull Request** yang muncul setelah push
10. Pilih base branch: `main` dan compare branch: `<NIM>`
11. Berikan judul Pull Request: `Praktikum-<nomor> - <NIM>`
12. Tambahkan deskripsi singkat tentang tugas yang dikumpulkan
13. Klik **Create Pull Request**

### Format Pesan Commit

Gunakan format yang konsisten untuk pesan commit:

- `feat: menambahkan Praktikum-1 <NIM>`
- `fix: memperbaiki bug pada Praktikum-2 <NIM>`
- `docs: menambahkan dokumentasi Praktikum-3 <NIM>`
- `update: memperbarui file Praktikum-4 <NIM>`

**Contoh:**
```bash
git commit -m "feat: menambahkan Praktikum-1 H071231009"
```

## Catatan Tambahan

- Batas asistensi tugas maksimal **2 pekan** setelah tugas diberikan
- Pesan commit sebaiknya yang bisa dipahami kedepannya
- Setiap mahasiswa bekerja pada branch terpisah sesuai NIM masing-masing
- Pastikan tidak ada conflict saat melakukan pull request
- Jika ada yang ingin ditanyakan silahkan bertanya di **GB Asistensi** / **Private Chat**
- **Semangat kerja praktikumnya!!**

---

**-LAB WEB 10 2025-**
