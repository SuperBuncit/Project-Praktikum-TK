<?php
session_start();
require_once '../../config/config.php';
require_once '../../helpers/functions.php';

check_login();
check_role(['admin']);

$id = $_GET['id'];

// Get user_id first to delete account
$guru = query("SELECT user_id FROM data_guru WHERE id = $id")[0];
$user_id = $guru['user_id'];

// Delete Guru (Foreign Key should handle user deletion if set to CASCADE, but logic says delete user normally triggers cascade if reversed. 
// However, in our Schema: fk_guru_user ON DELETE CASCADE.
// So if we delete USER, the GURU data might be gone if we set it that way.
// Let's check schema: CONSTRAINT `fk_guru_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
// So safe way is to delete the USER.

if (mysqli_query($conn, "DELETE FROM users WHERE id = $user_id")) {
    flash_msg('success', 'Data guru dan akun berhasil dihapus!');
} else {
    // Fallback if direct delete fails or relation is different
    if (mysqli_query($conn, "DELETE FROM data_guru WHERE id = $id")) {
        flash_msg('success', 'Data guru berhasil dihapus (Akun mungkin tertinggal)!');
    } else {
        flash_msg('danger', 'Gagal menghapus data: ' . mysqli_error($conn));
    }
}

redirect('modules/guru/index.php');
?>