<?php
require 'vendor/autoload.php';

$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'absensi_sekolah';

$db = mysqli_connect($hostname, $username, $password, $database);
if (!$db) die("Connection failed: " . mysqli_connect_error());

$user = 'Admin';
$newPass = 'admin123';
$hashed = password_hash($newPass, PASSWORD_DEFAULT);

echo "Resetting Admin password to 'admin123'...\n";
$sql = "UPDATE users SET password = '$hashed' WHERE nomor_induk = '$user'";

if (mysqli_query($db, $sql)) {
    if (mysqli_affected_rows($db) > 0) {
        echo "SUCCESS: Admin password reset to 'admin123'.\n";
    } else {
        echo "WARNING: Admin user not found or password was already 'admin123'.\n";
        // Check if user exists at all
        $res = mysqli_query($db, "SELECT * FROM users WHERE nomor_induk = '$user'");
        if (mysqli_num_rows($res) == 0) {
            echo "FIXING: Admin user missing. Re-inserting...\n";
            mysqli_query($db, "INSERT INTO users (nama, nomor_induk, password, role) VALUES ('Administrator', 'Admin', '$hashed', 'admin')");
            echo "INSERTED Admin user with password 'admin123'.\n";
        }
    }
} else {
    echo "ERROR: " . mysqli_error($db) . "\n";
}

mysqli_close($db);
