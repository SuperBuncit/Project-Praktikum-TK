<?php
require_once 'config/config.php';

$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);

$query = "UPDATE users SET password = '$hash'";
if (mysqli_query($conn, $query)) {
    echo "<h1>Password Reset Successfully</h1>";
    echo "<p>All users (admin, guru1, siswa1, etc.) now have password: <strong>admin123</strong></p>";
    echo "<a href='index.php'>Go to Login</a>";
} else {
    echo "Error updating password: " . mysqli_error($conn);
}
?>