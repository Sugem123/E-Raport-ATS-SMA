# E-Raport STS (Sumatif Tengah Semester) &mdash; SMAN 1 Prambon Nganjuk

Sistem Informasi Akademik dan Pengelolaan Nilai Asesmen Sumatif Tengah Semester (STS) Kurikulum Merdeka berbasis **Native PHP (OOP Model + MVC-like structure)**, **MySQL/MariaDB**, dan **Bootstrap 5.3**.

Aplikasi dirancang khusus untuk memfasilitasi kebutuhan evaluasi tengah semester di SMA Negeri 1 Prambon Nganjuk dengan integrasi data siswa berbasis Dapodik, monitoring kelengkapan penilaian kelas secara *real-time*, pengisian ketidakhadiran oleh Guru BK / Wali Kelas, serta pencetakan lembar rapor hasil belajar standar siap cetak atau ekspor PDF.

---

## 🌟 Hak Akses & Fitur Berdasarkan Peran

Sistem membagi hak akses ke dalam **4 level pengguna** dengan matriks fungsi sebagai berikut:

### 1. 🛡️ Administrator (Admin)
- **Data Master:**
  - **Data Kelas:** Manajemen 30 rombongan belajar (Fase E kelas X, Fase F kelas XI & XII), penugasan wali kelas, serta pengelolaan anggota kelas (tambah, pindah kelas, hapus siswa) via *floating modal*.
  - **Referensi Mapel:** Daftar induk mata pelajaran resmi kurikulum.
  - **Mapping Mapel:** Pengelompokan kategori mapel (Umum / Pilihan) dan nomor urut cetak rapor per jenjang (10, 11, 12).
  - **Data Guru:** Manajemen 65 tenaga pendidik, sinkronisasi NIP 18-digit, dan akun login.
  - **Penugasan Mengajar:** Pengaturan guru pengampu per mata pelajaran di setiap rombel (484 penugasan pembelajaran).
  - **Data Siswa:** Manajemen 1.049 peserta didik berbasis Dapodik (NIS, NISN, nama, kelas).
- **Monitoring & Cetak:**
  - **Monitor Penilaian:** Dashboard pantau status pengisian nilai seluruh 30 kelas (*Penuh Terisi* vs *Belum Lengkap*, progres persentase, drilldown per mapel dan per siswa).
  - **Ketidakhadiran Siswa:** Pantau dan kelola catatan Sakit, Izin, dan Alpa untuk semua kelas, dilengkapi download template dan upload Excel.
  - **Cetak Rapor STS:** Fasilitas cetak massal rapor per rombel kelas dalam format A4 standar.
- **Konfigurasi Satuan Pendidikan:**
  - **Data Sekolah & Logo:** Profil resmi sekolah, NPSN (20539744), akreditasi A, slogan, alamat, dan upload logo resmi sekolah.
  - **Format Cetak Rapor:** Pengaturan nama kepala sekolah, NIP, tempat/tanggal cetak rapor, dan semester aktif.
  - **Pengaturan Bobot STS:** Persentase bobot Nilai Sumatif Lingkup Materi vs Asesmen STS dan KKM kelulusan.
  - **Kelola Admin:** Manajemen akun pengelola sistem.

### 2. 👨‍🏫 Guru Mata Pelajaran
- **Input Nilai STS:**
  - Input nilai manual langsung pada tabel (Sumatif 1, Sumatif 2, Sumatif 3, Nilai STS).
  - **Download Template Excel Nilai Resmi:** Template berekstensi `.xlsx` bergaris rapi dengan identitas kop kelas & mapel di atas tabel serta nama siswa terdaftar.
  - **Upload Excel Nilai:** Impor massal nilai langsung dari file Excel hasil isian guru.
  - Perhitungan otomatis Rata-rata Sumatif, Nilai Akhir, dan Status Kelulusan (Tercapai / Belum Tercapai) berdasarkan bobot yang ditetapkan.

### 3. 👥 Guru BK (Bimbingan & Konseling)
- **Ketidakhadiran Siswa (BK):**
  - Akses langsung ke menu pengisian ketidakhadiran untuk kelas-kelas binaan bimbingan konseling.
  - Input catatan Sakit (S), Izin (I), dan Tanpa Keterangan / Alpa (A).
  - Download template dan upload presensi format Excel `.xlsx`.
  - Penandaan khusus (*alert*) bagi siswa yang memiliki catatan Alpa untuk tindak lanjut bimbingan.

### 4. 📋 Wali Kelas
- **Perwalian & Rapor:**
  - Ringkasan perwalian kelas, rekap nilai seluruh mapel siswa, dan pengelolaan catatan ketidakhadiran.
  - Cetak lembar e-Rapor STS individual siswa.
- **Monitor Nilai Kelas:**
  - Memantau progres guru mata pelajaran mana saja di kelas perwaliannya yang sudah lengkap atau belum memasukkan nilai.
- **Cetak Rapor Kelas (Cetak Massal):**
  - Cetak massal seluruh siswa dalam kelas perwalian sekaligus (1 lembar A4 per siswa) dengan pembatasan hak akses (*security barrier*) agar hanya dapat mencetak kelasnya sendiri.

### 5. 🎓 Siswa
- **Hasil Belajar Siswa:**
  - Pratinjau capaian nilai Sumatif 1, 2, 3, nilai ATS, nilai akhir per mapel, status kelulusan, dan catatan presensi.
  - Cetak mandiri lembar e-Rapor STS pribadi.

---

## 🔄 Alur Kerja Sistem (Workflow)

```
1. SETUP MASTER (Admin)
   ├── Input/Import Data Kelas & Wali Kelas
   ├── Input/Import Data Guru & Referensi Mapel
   ├── Mapping Mapel per Jenjang (Kategori Umum/Pilihan & Urutan Cetak)
   ├── Penugasan Mengajar (Guru + Mapel + Kelas)
   └── Sinkronisasi Data Siswa (Dapodik: NIS & NISN)

2. AKADEMIK PENILAIAN (Guru Mapel)
   ├── Buka menu Input Nilai STS (pilih rombel/mapel ampuannya)
   ├── Download Template Excel (otomatis memuat kop kelas, mapel, & daftar siswa)
   ├── Isi Nilai Sumatif 1, 2, 3 & Nilai STS
   └── Upload File Excel / Simpan Langsung -> Nilai Akhir terkalkulasi otomatis

3. PRESENSI KETIDAKHADIRAN (Guru BK / Wali Kelas)
   ├── Buka menu Ketidakhadiran Siswa
   ├── Download Template / Input Form (Sakit, Izin, Alpa)
   └── Simpan Batch / Upload Excel -> Otomatis masuk ke lembar rapor

4. MONITORING & VERIFIKASI (Admin & Wali Kelas)
   ├── Admin memantau 30 kelas di "Monitor Penilaian" (tahu kelas mana yang belum tuntas)
   └── Wali Kelas memverifikasi kelengkapan nilai per mapel di kelasnya

5. PENERBITAN RAPOR (Admin / Wali Kelas / Siswa)
   ├── Cetak Massal Rapor per Rombel (1 siswa = 1 halaman A4 portrait)
   └── Simpan sebagai PDF atau cetak fisik ke printer
```

---

## 🗂️ Struktur Direktori

```
eraport-smapra/
├── assets/
│   ├── css/
│   │   └── style.css            # Desain UI modern, Glassmorphism & layout cetak
│   └── js/
│       └── main.js              # Script pendukung interaktivitas UI
├── config/
│   ├── Config.php               # Konfigurasi dasar & deteksi dynamic base_url
│   ├── Database.php             # Class koneksi database mysqli
│   ├── db_raport.sql            # Skema DDL & seed data inisial database
│   ├── SimpleXlsx.php           # Generator & parser Excel .xlsx murni (ZIP/XML)
│   ├── ExcelHelper.php          # Wrapper parser & downloader template Excel
│   └── CsvHelper.php            # Fallback parser CSV
├── controllers/
│   ├── login.php                # Validasi login multi-role
│   ├── logout.php               # Penghancur sesi pengguna
│   ├── grade.php                # CRUD nilai, presensi, & upload excel nilai/presensi
│   ├── kelas.php                # CRUD kelas, tambah/pindah/hapus anggota siswa
│   ├── mapel.php                # CRUD referensi mata pelajaran
│   ├── mapping.php              # Pengaturan urutan & kategori mapel cetak rapor
│   ├── pengampu.php             # Manajemen penugasan mengajar guru
│   ├── student.php              # CRUD data siswa & impor Excel
│   ├── teacher.php              # CRUD data guru & akun
│   ├── setting.php              # Konfigurasi profil sekolah, logo & rapor
│   └── template.php             # Endpoint unduh template Excel resmi
├── models/
│   ├── Bobot.php                # Model kalkulasi bobot sumatif/STS
│   ├── Grade.php                # Model penyimpanan & perhitungan nilai STS
│   ├── Kelas.php                # Model data kelas, anggota, & query monitoring
│   ├── Mapel.php                # Model referensi mata pelajaran
│   ├── MapelMapping.php         # Model pemetaan urutan cetak rapor
│   ├── Pengampu.php             # Model penugasan mengajar guru
│   ├── Setting.php              # Model konfigurasi identitas sekolah & rapor
│   ├── Student.php              # Model data siswa & rekap laporan hasil belajar
│   ├── Teacher.php              # Model data guru
│   └── User.php                 # Model otentikasi & session pengguna
├── uploads/                     # Direktori penyimpanan berkas logo sekolah
│   └── .gitkeep
├── attendance.php               # Antarmuka input & upload ketidakhadiran (BK/Wali)
├── bulk-print.php               # Antarmuka pemilih kelas untuk cetak massal rapor
├── bulk-print-view.php          # Dokumen siap cetak massal rapor A4
├── classes.php                  # Manajemen data kelas & modal anggota siswa
├── grade-monitor.php            # Dashboard monitoring kelengkapan penilaian kelas
├── grade-recap.php              # Formulir & upload penilaian STS guru mapel
├── grade-summary.php            # Tampilan rapor STS untuk akun siswa
├── homeroom.php                 # Modul perwalian & cetak rapor wali kelas
├── landing.php                  # Halaman beranda publik profil sekolah
├── login.php                    # Halaman masuk portal (3D Glassmorphism GEMPITA)
├── school-profile.php           # Panel pengaturan identitas sekolah & unggah logo
├── student-report.php           # Lembar dokumen cetak e-Rapor STS individual
├── index.php                    # Router utama aplikasi
├── router.php                   # Router untuk PHP Built-in Server
└── serve.bat                    # Script satu klik untuk menjalankan server lokal
```

---

## 💻 Kebutuhan Sistem

- **PHP:** Versi 8.0 ke atas (diuji pada PHP 8.1 dan 8.2).
- **Ekstensi PHP Wajib:** `mysqli`, `zip`, `xml`, `mbstring`.
- **Database:** MySQL 5.7+ / MariaDB 10.4+.
- **Peramban Rekomendasi:** Google Chrome, Microsoft Edge, atau Mozilla Firefox versi terbaru.

---

## 🚀 Panduan Instalasi & Menjalankan

### 1. Klon Repositori
```bash
git clone https://github.com/echadarmawan/eraport-sma.git
cd eraport-sma
```

### 2. Impor Database
1. Buka phpMyAdmin atau terminal MySQL:
2. Buat database baru bernama `db_raport`.
3. Impor berkas database:
   ```bash
   mysql -u root -p db_raport < config/db_raport.sql
   ```

### 3. Konfigurasi Koneksi (Jika Diperlukan)
Sesuaikan pengaturan host, username, dan password database di berkas `config/Database.php`:
```php
private $host = "localhost";
private $user = "root";
private $pass = "";
private $db   = "db_raport";
```

### 4. Menjalankan Server Lokal
Aplikasi dapat dijalankan melalui **XAMPP / Laragon** (pindahkan folder ke `htdocs` atau `www`), atau menggunakan **PHP Built-in Server**:

- **Cara Cepat (Windows):** Cukup klik dua kali berkas `serve.bat`.
- **Melalui Terminal:**
  ```bash
  php -S localhost:8000 router.php
  ```
Buka peramban dan akses: [http://localhost:8000](http://localhost:8000)

---

## 🔑 Kredensial Pengujian Default

| Peran | Username | Password | Keterangan |
|---|---|---|---|
| **Admin** | `admin` | `admin` | Administrator Utama Sistem |
| **Wali Kelas** | `198108202009031004` | `Abcde12345@` | SONY SUMARSONO, S.Pd. (Wali Kelas X-1) |
| **Guru BK** | `199112092022211020` | `Abcde12345@` | WAHAYU PUJA UTAMA, S.Pd. (Guru BK X-1 s/d X-6) |
| **Guru Mapel** | `199310222024211006` | `Abcde12345@` | ACHMAD SYAIFUL, S.Pd. (Guru Matematika) |
| **Siswa** | `7176` | `Abcde12345@` | Siswa Kelas X-1 |

---

## 📄 Lisensi & Hak Cipta
Dikembangkan untuk implementasi e-Raport Asesmen Tengah Semester Kurikulum Merdeka pada **SMA Negeri 1 Prambon Nganjuk, Jawa Timur**.
