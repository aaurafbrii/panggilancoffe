<?php
include('../config/db.php');
session_start();

// Cek login dan role
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = md5($_POST['password']); // Enkripsi pakai MD5
    $role = 'customer';

    // Cek email apakah sudah ada
    $check = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($check->num_rows > 0) {
        $message = "❌ Email sudah terdaftar!";
    } else {
        $sql = "INSERT INTO users (name, email, password, role)
                VALUES ('$name', '$email', '$password', '$role')";
        if ($conn->query($sql)) {
            $message = "✅ Pelanggan berhasil ditambahkan!";
        } else {
            $message = "❌ Gagal menambahkan pelanggan: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tambah Pelanggan - Admin</title>
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(to right, #6f4e37, #3c2f2f); /* gradasi kopi */
        margin: 0;
        padding: 0;
        color: #2b1d13;
    }

    .header {
        background: #f5f3f0;
        color: #4b2e1e;
        padding: 20px;
        text-align: center;
        position: relative;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }

    .header h2 {
        margin: 0;
        font-size: 26px;
    }

    .btn-container {
        position: absolute;
        right: 25px;
        top: 22px;
        display: flex;
        gap: 10px;
    }

    .btn {
        background: #6f4e37;
        color: white;
        padding: 8px 14px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: background-color 0.3s, transform 0.2s;
    }

    .btn:hover {
        background: #5b3c2e;
        transform: translateY(-2px);
    }

    .logout {
        background: #3c2f2f;
    }

    .logout:hover {
        background: #2b1d13;
    }

    .container {
        background: #fff;
        width: 420px;
        margin: 50px auto;
        padding: 35px 40px;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        text-align: center;
    }

    h3 {
        color: #6f4e37;
        margin-bottom: 20px;
    }

    input {
        width: 100%;
        padding: 10px;
        margin-top: 10px;
        border-radius: 10px;
        border: 1px solid #cbb79d;
        font-size: 15px;
        outline: none;
        transition: 0.2s;
        background: #fdfaf7;
    }

    input:focus {
        border-color: #6f4e37;
        box-shadow: 0 0 4px #bfa380;
    }

    button {
        width: 100%;
        padding: 12px;
        margin-top: 15px;
        background: #6f4e37;
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s;
    }

    button:hover {
        background: #5b3c2e;
        transform: translateY(-2px);
    }

    .msg {
        font-weight: bold;
        margin-bottom: 15px;
        color: #006600;
    }

    .msg.error {
        color: #b30000;
    }

    footer {
        text-align: center;
        padding: 15px;
        color: #f5f3f0;
        font-size: 14px;
    }
</style>
</head>
<body>

<div class="header">
    <h2>👤 Tambah Pelanggan Baru</h2>
    <div class="btn-container">
        <a href="index.php" class="btn">🏠 Dashboard</a>
        <a href="../logout.php" class="btn logout" onclick="return confirm('Anda yakin ingin keluar dari akun?')">Keluar</a>
    </div>
</div>

<div class="container">
    <?php if ($message): ?>
        <p class="msg <?= strpos($message, '❌') !== false ? 'error' : '' ?>">
            <?= $message ?>
        </p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="name" placeholder="Nama Lengkap" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Tambah Pelanggan</button>
    </form>
</div>

<footer>
    &copy; <?= date('Y'); ?> Aura Febrianti
</footer>

</body>
</html>
