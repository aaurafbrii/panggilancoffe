<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_menucoffe";

// Koneksi ke database
$conn = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Set timezone PHP ke Jakarta
date_default_timezone_set('Asia/Jakarta');

// Set timezone MySQL ke WIB (GMT+7)
mysqli_query($conn, "SET time_zone = '+07:00'");
?>
