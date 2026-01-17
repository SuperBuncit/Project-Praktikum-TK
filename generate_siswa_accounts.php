<?php
require_once 'config/config.php';
require_once 'helpers/functions.php';

echo "<h2>Generator Akun Siswa Massal</h2>";
echo "<a href='dashboard.php'>Kembali ke Dashboard</a><hr>";

// Ambil siswa yang belum punya akun (user_id IS NULL or user_id = 0)
$query = "SELECT * FROM data_siswa WHERE user_id IS NULL OR user_id = 0";
$siswa_list = query($query);

if (empty($siswa_list)) {
    echo "<div style='color:green'>Semua siswa sudah memiliki akun login.</div>";
} else {
    echo "Ditemukan " . count($siswa_list) . " siswa belum punya akun.<br><ul>";

    foreach ($siswa_list as $s) {
        $nis = $s['nis'];
        $nama = $s['nama_lengkap'];
        $id_siswa = $s['id'];

        // 1. Cek apakah user dengan NIS ini sudah ada di tabel users?
        $cek_user = query("SELECT * FROM users WHERE username = '$nis'");

        if (count($cek_user) > 0) {
            // User sudah ada, tinggal link saja
            $user_id = $cek_user[0]['id'];
            echo "<li>$nama ($nis): User sudah ada (ID: $user_id). ";
        } else {
            // User belum ada, buat baru
            $password = password_hash($nis, PASSWORD_DEFAULT);
            $insert_user = "INSERT INTO users (username, password, role) VALUES ('$nis', '$password', 'siswa')";

            if (mysqli_query($conn, $insert_user)) {
                $user_id = mysqli_insert_id($conn);
                echo "<li>$nama ($nis): Akun berhasil dibuat (ID: $user_id). ";
            } else {
                echo "<li>$nama ($nis): <span style='color:red'>Gagal buat akun!</span> " . mysqli_error($conn) . "</li>";
                continue; // Skip update siswa
            }
        }

        // 2. Update data_siswa
        $update_siswa = "UPDATE data_siswa SET user_id = $user_id WHERE id = $id_siswa";
        if (mysqli_query($conn, $update_siswa)) {
            echo "<span style='color:green'>Data Siswa Linked!</span></li>";
        } else {
            echo "<span style='color:red'>Gagal Link Siswa!</span></li>";
        }
    }
    echo "</ul>";
}

echo "<hr><h4>Selesai! Password default adalah NIS masing-masing siswa.</h4>";
?>