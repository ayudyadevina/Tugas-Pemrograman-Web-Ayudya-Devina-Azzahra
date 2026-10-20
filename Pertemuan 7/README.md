# Lembar: Website Baca Buku di Sore Hari

**Lembar** merupakan website sederhana bertema membaca buku yang terdiri dari tiga halaman, yaitu halaman konten (beranda), halaman login, dan halaman register. Website ini dibuat sebagai tugas proyek latihan Bootstrap untuk menerapkan sistem grid, komponen siap pakai, dan desain yang responsif di berbagai ukuran layar.

Tampilan Lembar mengusung suasana membaca buku di sore hari, dengan warna cokelat hangat, krem, dan aksen oranye seperti langit senja.

## 1. Tujuan

Website ini dibuat untuk:

* menerapkan framework Bootstrap 5 dalam membangun halaman web yang responsif;
* menyediakan halaman konten yang menampilkan rak buku, keunggulan website, dan ajakan untuk mendaftar;
* menyediakan halaman login dan register yang dilengkapi validasi form;
* melatih penggunaan komponen Bootstrap seperti navbar, grid, card, badge, form, dan input group;
* menghasilkan tampilan website yang hangat, nyaman dilihat, dan mudah digunakan.

## 2. Konsep dan Tema

Konsep Lembar adalah tempat kecil yang hangat untuk mencatat bacaan, menyimpan buku favorit, dan menemukan cerita berikutnya. Nama **"Lembar"** dipilih karena merujuk pada selembar halaman buku, sekaligus menjadi kata yang disorot pada judul utama website.

Palet warna yang digunakan:

| Warna            | Kode      | Penggunaan                              |
| ---------------- | --------- | --------------------------------------- |
| Krem             | `#fbf3e6` | Latar halaman                           |
| Krem tua         | `#f3e3cc` | Latar section selang-seling             |
| Cokelat muda     | `#d9b99b` | Garis tepi dan gradasi                  |
| Cokelat          | `#8b5e3c` | Judul section dan elemen pendukung      |
| Cokelat tua      | `#4a3022` | Teks utama dan footer                   |
| Oranye deep      | `#c2571a` | Tombol, badge, dan kata sorotan         |

Jenis huruf yang digunakan adalah **Playfair Display** untuk judul dan **Poppins** untuk teks isi.

## 3. Fitur Website

### A. Halaman Konten (Beranda)

Halaman beranda terdiri dari beberapa bagian berikut:

* **Navbar**: berisi logo Lembar, menu navigasi, serta tombol Masuk dan Daftar. Pada layar kecil, menu berubah menjadi tombol hamburger.
* **Hero**: berisi judul utama "Satu *lembar* lagi, sebelum matahari terbenam", deskripsi singkat, tombol ajakan, dan ilustrasi membaca buku yang menyatu dengan latar.
* **Rak Buku**: menampilkan enam buku dalam bentuk card, lengkap dengan cover, status baca, judul, penulis, dan deskripsi singkat.
* **Kenapa Lembar?**: menjelaskan tiga keunggulan, yaitu Catat Bacaan, Tulis Kesan, dan Target Baca.
* **Ajakan Mendaftar**: tombol untuk membuat akun.
* **Footer**: berisi hak cipta dan tautan ke halaman Masuk dan Daftar.

Buku yang ditampilkan pada rak buku:

| No | Judul                                   | Status         |
| -- | --------------------------------------- | -------------- |
| 1  | Jakarta Sebelum Pagi                    | Sedang dibaca  |
| 2  | Di Tanah Lada                           | Favorit        |
| 3  | Second Sister                           | Daftar baca    |
| 4  | Polaris Musim Dingin                    | Daftar baca    |
| 5  | Kastil Es dan Air Mancur yang Berdansa  | Daftar baca    |
| 6  | Heaven                                  | Daftar baca    |

**Tangkapan layar:**

![Tampilan hero pada halaman beranda](screenshots/beranda-hero.png)
*Gambar 1. Bagian hero dan navbar*

![Tampilan rak buku](screenshots/beranda-rak-buku.png)
*Gambar 2. Bagian rak buku dalam bentuk card*

![Tampilan bagian fitur dan footer](screenshots/beranda-fitur.png)
*Gambar 3. Bagian "Kenapa Lembar?", ajakan mendaftar, dan footer*

### B. Halaman Login

Halaman login digunakan pengguna untuk masuk ke akun. Halaman ini berisi:

* kolom **Email**;
* kolom **Kata Sandi** dengan tombol mata untuk menampilkan atau menyembunyikan kata sandi;
* pilihan **Ingat saya** dan tautan **Lupa kata sandi?**;
* tombol **Masuk**;
* tautan menuju halaman Daftar bagi pengguna yang belum memiliki akun.

Aturan validasi pada halaman login:

| Kolom        | Aturan                                |
| ------------ | ------------------------------------- |
| Email        | Wajib diisi dengan format email valid |
| Kata Sandi   | Wajib diisi, minimal 6 karakter       |

Pesan kesalahan berwarna merah ditampilkan langsung di bawah kolom yang bermasalah.

**Tangkapan layar:**

![Tampilan halaman login](screenshots/login.png)
*Gambar 4. Halaman login*

![Tampilan validasi pada halaman login](screenshots/login-validasi.png)
*Gambar 5. Pesan validasi pada halaman login*

### C. Halaman Register

Halaman register digunakan untuk membuat akun baru. Halaman ini berisi:

* kolom **Nama Lengkap**;
* kolom **Email**;
* kolom **Kata Sandi** dan **Konfirmasi Kata Sandi** yang tersusun berdampingan pada layar besar;
* pilihan **Genre Favorit** (opsional);
* kotak persetujuan **syarat dan ketentuan**;
* tombol **Daftar**;
* tautan menuju halaman Masuk bagi pengguna yang sudah memiliki akun.

Aturan validasi pada halaman register:

| Kolom                  | Aturan                                    |
| ---------------------- | ----------------------------------------- |
| Nama Lengkap           | Wajib diisi                               |
| Email                  | Wajib diisi dengan format email valid     |
| Kata Sandi             | Wajib diisi, minimal 6 karakter           |
| Konfirmasi Kata Sandi  | Wajib diisi dan harus sama dengan kata sandi |
| Genre Favorit          | Opsional                                  |
| Syarat dan Ketentuan   | Wajib disetujui                           |

**Tangkapan layar:**

![Tampilan halaman register](screenshots/register.png)
*Gambar 6. Halaman register*

![Tampilan validasi pada halaman register](screenshots/register-validasi.png)
*Gambar 7. Pesan validasi pada halaman register*

> Halaman login dan register pada proyek ini belum terhubung ke database. Setelah isian dinyatakan valid, website hanya menampilkan notifikasi dan berpindah halaman sebagai simulasi, sehingga cukup untuk keperluan latihan Bootstrap.

### D. Tampilan Responsif

Seluruh halaman menyesuaikan ukuran layar menggunakan sistem grid Bootstrap. Pada layar besar, rak buku tersusun tiga kolom dan ilustrasi hero tampil di samping teks. Pada layar sedang, rak buku menjadi dua kolom. Pada layar kecil, seluruh elemen tersusun ke bawah, menu berubah menjadi hamburger, dan ilustrasi hero disembunyikan agar tampilan tetap nyaman.

**Tangkapan layar:**

![Tampilan beranda pada layar HP](screenshots/responsif-mobile.png)
*Gambar 8. Tampilan beranda pada layar kecil*

## 4. Alur Penggunaan

Alur penggunaan website secara keseluruhan:

```text
Membuka Halaman Beranda
          ↓
Melihat Rak Buku dan Informasi Lembar
          ↓
Klik "Daftar" atau "Mulai Membaca"
          ↓
Mengisi Formulir Register
          ↓
Data Divalidasi
          ↓
Diarahkan ke Halaman Login
          ↓
Mengisi Email dan Kata Sandi
          ↓
Data Divalidasi
          ↓
Kembali ke Halaman Beranda
```

## 5. Struktur Halaman

Website terdiri dari tiga halaman yang saling terhubung:

| Halaman        | Berkas          | Fungsi                                              |
| -------------- | --------------- | --------------------------------------------------- |
| Beranda        | `index.html`    | Menampilkan konten utama website                    |
| Login          | `login.html`    | Masuk ke akun                                       |
| Register       | `register.html` | Membuat akun baru                                   |

Navigasi antarhalaman dilakukan melalui tombol pada navbar, tombol ajakan, tautan pada footer, serta tautan di bawah formulir login dan register.

## 6. Komponen Bootstrap yang Digunakan

| Komponen / Fitur Bootstrap                     | Digunakan pada                                      |
| ---------------------------------------------- | --------------------------------------------------- |
| Navbar (`navbar`, `navbar-toggler`, `collapse`) | Menu navigasi dan tombol hamburger                  |
| Grid (`container`, `row`, `col-*`, `row-cols-*`) | Susunan hero, rak buku, fitur, dan form             |
| Card (`card`, `card-body`, `card-title`)        | Kartu buku dan kartu form login/register            |
| Badge (`badge`)                                 | Label status baca pada kartu buku                   |
| Form (`form-control`, `form-select`, `form-check`) | Kolom isian pada login dan register              |
| Input group (`input-group`)                     | Kolom kata sandi dengan tombol mata                 |
| Validasi form (`needs-validation`, `invalid-feedback`) | Pesan kesalahan pada form                    |
| Tombol (`btn`)                                  | Tombol aksi di seluruh halaman                      |
| Utility class (`py-5`, `mb-3`, `d-grid`, `d-flex`, `d-none`) | Pengaturan jarak, tata letak, dan tampilan responsif |

## 7. Struktur File

```text
Lembar/
├── index.html        # halaman konten (beranda)
├── login.html        # halaman login
├── register.html     # halaman register
├── style.css         # warna tema dan penyesuaian tampilan
├── assets/           # ilustrasi hero dan cover buku
├── screenshots/      # tangkapan layar untuk dokumentasi
└── README.md         # dokumentasi proyek
```

## 8. Teknologi yang Digunakan

Website dikembangkan menggunakan:

* **HTML**: untuk membangun struktur halaman;
* **CSS**: untuk mengatur palet warna, efek ilustrasi yang menyatu dengan latar, dan penyesuaian tampilan di atas Bootstrap;
* **Bootstrap 5.3.3**: untuk sistem grid, komponen, dan tampilan responsif;
* **Bootstrap Icons**: untuk ikon pada navbar, fitur, dan form;
* **JavaScript**: untuk validasi form, pencocokan kata sandi, dan tombol tampil/sembunyikan kata sandi;
* **Google Fonts**: untuk jenis huruf Playfair Display dan Poppins;
* **Vercel**: untuk melakukan deployment website.

## 9. Menjalankan Website

Website dapat dijalankan secara lokal dengan membuka berkas `index.html` langsung melalui browser. Pastikan seluruh berkas dan folder `assets` berada dalam satu folder yang sama, serta perangkat terhubung ke internet karena Bootstrap, ikon, dan font dimuat melalui CDN.

## 10. Deployment

Website di-deploy menggunakan Vercel CLI dari terminal dengan langkah berikut:

```text
Login ke Vercel
     ↓
Masuk ke folder proyek
     ↓
Deploy preview
     ↓
Deploy production
     ↓
Website Dapat Diakses
```

Perintah yang digunakan:

```bash
vercel login
vercel
vercel --prod
```

Website dapat diakses melalui:

**[Lembar](GANTI-DENGAN-LINK-WEBSITE-KAMU)**

## 11. Kredit

* Ilustrasi hero: *(isi nama seniman / sumber ilustrasi)*
* Cover buku: milik masing-masing penerbit dan penulis, digunakan untuk keperluan pembelajaran.