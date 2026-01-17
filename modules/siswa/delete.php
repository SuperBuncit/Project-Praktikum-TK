<?php
session_start();
require_once '../../config/config.php';
require_once '../../helpers/functions.php';

check_login();
check_role(['admin']);

$id = $_GET['id'];

if (mysqli_query($conn, "DELETE FROM data_siswa WHERE id = $id")) {
    flash_msg('success', 'Data siswa berhasil dihapus!');
} else {
    flash_msg('danger', 'Gagal menghapus data: ' . mysqli_error($conn));
}

redirect('modules/siswa/index.php');
?>