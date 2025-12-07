<?php
session_start();
session_destroy(); // Hapus semua data session
header("Location: login.php"); // Kembali ke halaman login
exit();
?>
