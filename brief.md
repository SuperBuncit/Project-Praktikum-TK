PROJECT BRIEF: SISTEM MANAJEMEN SEKOLAH TK MODERN
1. Identitas Proyek
Nama Aplikasi: Manajemen Sekolah (School Management System)

Platform: Web-Based (Responsive)

Target Pengguna: Administrator Sekolah, Guru/Staff, dan Siswa (Orang Tua).

2. Ringkasan Proyek
Membangun sistem informasi terpadu untuk mendigitalisasi operasional harian sekolah TK. Fokus utama adalah pada kemudahan pengelolaan data akademik, monitoring kedisiplinan (sistem poin), penjadwalan, dan penyajian data yang transparan melalui visualisasi dashboard.

3. Spesifikasi Teknologi
Sistem akan dibangun menggunakan stack teknologi yang stabil dan ringan:

Backend: PHP 7.4+ (Native/Modular)

Database: MySQL 5.7+ (Koneksi MySQLi)

Frontend Framework: Bootstrap 5.1.3

Icons & Visual: Font Awesome 6.0.0 & Chart.js 3.7.0

Scripting: Vanilla JavaScript (tanpa library berat)

Interaktivitas: AJAX untuk pencarian real-time dan notifikasi Toastr.

4. Ruang Lingkup Fitur (Scope of Features)
A. Core Management
Manajemen Siswa: Profil lengkap, upload foto, riwayat kelas, dan fitur pencarian cepat.

Manajemen Guru/Staff: Pendataan tenaga pendidik dan pengaturan hak akses (RBAC).

Manajemen Akademik: Pengelolaan Mata Pelajaran, Pembagian Kelas, dan Penugasan Wali Kelas.

Penjadwalan: Pengaturan sesi jam pelajaran, ruangan, dan jadwal mingguan.

B. Monitoring & Kedisiplinan
Sistem Poin (Gamifikasi): * Prestasi: Pemberian poin untuk pencapaian akademik/non-akademik.

Pelanggaran: Pencatatan perilaku negatif beserta sanksi otomatis.

Absensi: Pencatatan kehadiran harian siswa.

C. Visualisasi & Laporan
Interactive Dashboard: Grafik statistik siswa dan aktivitas sekolah menggunakan Chart.js.

Reporting: Ekspor data ke format cetak/PDF untuk laporan berkala kepada yayasan atau orang tua.

5. Standar Keamanan & Performa
Aplikasi wajib mengimplementasikan lapisan keamanan berikut:

SQL Injection: Wajib menggunakan real_escape_string() dan prepared statements.

XSS Protection: Filtrasi output menggunakan htmlspecialchars().

CSRF Protection: Penggunaan token unik pada setiap pengiriman form.

Session Management: Validasi login yang ketat dan pembatasan akses berdasarkan role.

UX Features: Implementasi Pagination untuk data besar dan Responsive Design untuk akses mobile.

6. Struktur Database (Entitas Utama)
Sistem akan memiliki minimal 11 tabel yang saling berelasi:

User: Kredensial login & role.

Guru: Data detail pendidik.

Siswa: Biodata dan relasi kelas.

Kelas: Informasi ruang dan kapasitas.

Mata Pelajaran: Daftar materi.

Jadwal: Relasi waktu, guru, dan kelas.

Poin (Prestasi & Pelanggaran): Log poin siswa.

Absensi: Log kehadiran.

Log Aktivitas: Catatan audit sistem.

7. Tujuan Akhir (Project Goals)
Meningkatkan efisiensi administrasi sekolah hingga 50%.

Menyediakan data siswa yang akurat dan mudah diakses kapan saja.

Memudahkan wali kelas dalam memantau perkembangan perilaku siswa melalui sistem poin.