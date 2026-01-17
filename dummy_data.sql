-- Dummy Data for TK Modern
USE `database`;

-- 1. Users (Admin ID 1 assumed existing or AUTO_INCREMENT)
-- We force IDs to ensure FKs match exactly
INSERT IGNORE INTO `users` (`id`, `username`, `password`, `role`) VALUES
(2, 'guru1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'guru'),
(3, 'guru2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'guru'),
(4, 'siswa1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'siswa'),
(5, 'siswa2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'siswa'),
(6, 'siswa3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'siswa'),
(7, 'siswa4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'siswa'),
(8, 'siswa5', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'siswa');

-- 2. Kelas
INSERT IGNORE INTO `kelas` (`id`, `nama_kelas`, `kapasitas`) VALUES
(1, 'TK-A Matahari', 15),
(2, 'TK-A Bulan', 15),
(3, 'TK-B Bintang', 20),
(4, 'TK-B Pelangi', 20);

-- 3. Data Guru
-- user_id 2 -> guru1, user_id 3 -> guru2
INSERT IGNORE INTO `data_guru` (`id`, `user_id`, `nip`, `nama_lengkap`, `gelar`, `alamat`, `no_hp`) VALUES
(1, 2, '198501012010012001', 'Siti Aminah', 'S.Pd.AUD', 'Jl. Merpati No. 10', '081234567890'),
(2, 3, '199005052015021002', 'Budi Santoso', 'S.Pd', 'Jl. Kenari No. 5', '081298765432');

-- 4. Data Siswa
-- user_id 4-8 -> siswa1-siswa5
INSERT IGNORE INTO `data_siswa` (`id`, `user_id`, `nis`, `nama_lengkap`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `alamat`, `nama_wali`, `no_hp_wali`, `kelas_id`, `poin`) VALUES
(1, 4, '2023001', 'Ahmad Dani', 'Jakarta', '2018-05-10', 'L', 'Jl. Mawar No. 1', 'Dani Saputra', '081311112222', 1, 100),
(2, 5, '2023002', 'Bunga Citra', 'Bandung', '2018-08-17', 'P', 'Jl. Melati No. 2', 'Citra Kirana', '081333334444', 1, 95),
(3, 6, '2023003', 'Caca Marica', 'Surabaya', '2017-12-25', 'P', 'Jl. Anggrek No. 3', 'Marica', '081355556666', 3, 100),
(4, 7, '2023004', 'Doni Tata', 'Yogyakarta', '2017-02-14', 'L', 'Jl. Kamboja No. 4', 'Tata Young', '081377778888', 3, 80),
(5, 8, '2023005', 'Eko Patrio', 'Semarang', '2018-01-01', 'L', 'Jl. Dahlia No. 5', 'Patrio', '081399990000', 2, 100);

-- 5. Mata Pelajaran
INSERT IGNORE INTO `mata_pelajaran` (`id`, `kode_mapel`, `nama_mapel`, `deskripsi`) VALUES
(1, 'MP01', 'Membaca & Menulis', 'Belajar mengenal huruf dan menulis kata sederhana'),
(2, 'MP02', 'Berhitung', 'Pengenalan angka dan operasi matematika dasar'),
(3, 'MP03', 'Menggambar', 'Mengembangkan kreativitas melalui gambar'),
(4, 'MP04', 'Agama & Budi Pekerti', 'Pembentukan karakter dan nilai agama');

-- 6. Jadwal Pelajaran
INSERT IGNORE INTO `jadwal_pelajaran` (`id`, `kelas_id`, `mapel_id`, `guru_id`, `hari`, `jam_mulai`, `jam_selesai`) VALUES
(1, 1, 1, 1, 'Senin', '08:00:00', '09:00:00'),
(2, 1, 2, 2, 'Senin', '09:30:00', '10:30:00'),
(3, 3, 3, 1, 'Selasa', '08:00:00', '09:30:00'),
(4, 2, 4, 2, 'Rabu', '08:00:00', '09:00:00');

-- 7. Log Poin (Match with Siswa IDs created above)
-- Ahmad Dani (ID 1)
-- Bunga Citra (ID 2)
-- Caca Marica (ID 3)
-- Doni Tata (ID 4)
INSERT IGNORE INTO `log_poin` (`id`, `siswa_id`, `jenis`, `jumlah_poin`, `keterangan`, `tanggal`, `dicatat_oleh`) VALUES
(1, 2, 'Pelanggaran', -5, 'Tidak membawa buku gambar', '2023-10-01', 2),
(2, 4, 'Pelanggaran', -20, 'Berkelahi dengan teman', '2023-10-02', 3);

-- 8. Absensi
INSERT IGNORE INTO `absensi` (`id`, `siswa_id`, `tanggal`, `status`, `keterangan`) VALUES
(1, 1, CURDATE(), 'Hadir', ''),
(2, 2, CURDATE(), 'Sakit', 'Demam'),
(3, 3, CURDATE(), 'Hadir', ''),
(4, 4, CURDATE(), 'Izin', 'Acara Keluarga'),
(5, 5, CURDATE(), 'Hadir', '');
