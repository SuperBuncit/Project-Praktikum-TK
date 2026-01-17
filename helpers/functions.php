<?php
require_once __DIR__ . '/../config/config.php';

function base_url($path = '')
{
    return BASE_URL . $path;
}

function query($query)
{
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

function clean_input($data)
{
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return mysqli_real_escape_string($conn, $data);
}

function redirect($url)
{
    echo "<script>window.location.href='" . base_url($url) . "';</script>";
    exit;
}

function check_login()
{
    if (!isset($_SESSION['login'])) {
        redirect('index.php');
        exit;
    }
}

function check_role($roles = [])
{
    if (!in_array($_SESSION['role'], $roles)) {
        echo "<script>alert('Akses ditolak!'); window.history.back();</script>";
        exit;
    }
}

function flash_msg($type, $msg)
{
    $_SESSION['flash'] = [
        'type' => $type,
        'msg' => $msg
    ];
}

function display_flash()
{
    if (isset($_SESSION['flash'])) {
        $type = $_SESSION['flash']['type'];
        $msg = $_SESSION['flash']['msg'];
        unset($_SESSION['flash']);

        $alertType = ($type == 'success') ? 'success' : 'danger';

        echo "<div class='alert alert-$alertType alert-dismissible fade show' role='alert'>
                $msg
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
    }
}

function upload_foto($file, $target_dir)
{
    $fileName = $file['name'];
    $fileTmp = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];

    // Cek error
    if ($fileError === 4) {
        return 'default.png'; // Tidak ada file yang diupload
    }

    // Cek ekstensi
    $validExtensions = ['jpg', 'jpeg', 'png'];
    $fileExt = explode('.', $fileName);
    $fileExt = strtolower(end($fileExt));

    if (!in_array($fileExt, $validExtensions)) {
        return false; // Ekstensi tidak valid
    }

    // Cek ukuran (Max 2MB)
    if ($fileSize > 2000000) {
        return false; // Ukuran terlalu besar
    }

    // Generate nama baru
    $newFileName = uniqid() . '.' . $fileExt;

    // Pastikan folder ada
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Pindahkan file
    if (move_uploaded_file($fileTmp, $target_dir . $newFileName)) {
        return $newFileName;
    } else {
        return false;
    }
}
?>