# Pajaken

Website Cek Pajak Kendaraan Modern dan Interaktif.

---

# Cara Clone Project

Clone repository dari GitHub:

```bash
git clone https://github.com/kvinn-dev/pajaken-uts.git
```

Masuk ke folder project:

```bash
cd pajaken-uts
```

---

# Cara Membuat Branch Baru

Jangan langsung coding di branch `main`.

Buat branch baru sesuai fitur yang dikerjakan.

Contoh:

```bash
git checkout -b fitur-reminder/fitur-homepage/fitur-cekpajak
```

Atau:

```bash
git checkout -b fitur-homepage/fitur-reminder/fitur-cekpajak
```

Cek branch aktif:

```bash
git branch
```

---

# Cara Menjalankan Program

Jalankan server PHP di terminal:

```bash
php -S localhost:8000 index.php
```

Buka browser:

```text
http://localhost:8000
```

---

# Menyimpan Perubahan

Tambahkan semua perubahan:

```bash
git add .
```

Commit perubahan:

```bash
git commit -m "Menambahkan fitur reminder"
```

---

# Push Branch ke GitHub

Push branch yang sedang dikerjakan:

```bash
git push -u origin fitur-reminder
```

Contoh lain:

```bash
git push -u origin fitur-homepage
```

---

# Cara Membuat Pull Request

1. Buka repository GitHub:
   
```text
https://github.com/kvinn-dev/pajaken-uts
```

2. Setelah branch berhasil di-push, GitHub biasanya menampilkan tombol:

```text
Compare & pull request
```

3. Klik tombol tersebut.

4. Pastikan:

```text
base: main
compare: fitur-reminder
```

5. Klik:

```text
Create pull request
```

6. Tambahkan judul dan deskripsi perubahan.

7. Klik:

```text
Merge pull request
```

---

# Mengambil Update Terbaru

Sebelum mulai coding, selalu update project:

```bash
git checkout main
git pull origin main
```

Jika ingin update branch fitur dari main:

```bash
git checkout fitur-reminder
git merge main
```

---

# Rules Collaboration

- Jangan langsung push ke `main`
- Gunakan branch masing-masing
- Satu fitur = satu branch
- Selalu commit dengan pesan yang jelas
- Lakukan Pull Request sebelum merge

---

# Contoh Penamaan Branch

| Fitur | Branch |
|---|---|
| Homepage | `fitur-homepage` |
| Reminder | `fitur-reminder` |
| Cek Pajak | `fitur-cekpajak` |
| Pembayaran | `fitur-pembayaran` |

---

# Tech Stack

- PHP Native
- JavaScript
- CSS
- MySQL
- TailwindCSS

---

# Contributors

- Kevin Pamungkas
- Muhammad Rafi Andaresta
- Muhammad Naufal Al Ali
