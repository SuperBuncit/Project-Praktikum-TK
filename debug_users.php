<?php
require_once 'config/config.php';

echo "Database: " . DB_NAME . "<br>";
$result = mysqli_query($conn, "SELECT id, username, password, role FROM users");

if ($result) {
    echo "<table border='1'><tr><th>ID</th><th>Username</th><th>Password Hash</th><th>Role</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['username'] . "</td>";
        echo "<td>" . substr($row['password'], 0, 20) . "...</td>";
        echo "<td>" . $row['role'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";

    // Test Verify
    $test_pass = 'admin123';
    // Get admin hash
    $admin = mysqli_query($conn, "SELECT password FROM users WHERE username='admin'");
    if ($row = mysqli_fetch_assoc($admin)) {
        echo "<br>Testing password '$test_pass' for admin: ";
        if (password_verify($test_pass, $row['password'])) {
            echo "<strong>VALID</strong>";
        } else {
            echo "<strong>INVALID</strong>";
        }
    }
} else {
    echo "Error: " . mysqli_error($conn);
}
?>