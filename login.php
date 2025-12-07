<?php
session_start();
include 'config/db.php';

$error = '';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $pass  = md5($_POST['password']);

    $q = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND password='$pass'");
    $user = mysqli_fetch_assoc($q);

    if ($user) {
        $_SESSION['user'] = $user;
        if ($user['role'] == 'admin') {
            header("Location: admin/index.php");
            exit;
        } else {
            header("Location: customer/index.php");
            exit;
        }
    } else {
        $error = "Email atau Password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login | Panggilan Kopi</title>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600&display=swap');

    body {
        font-family: 'Quicksand', sans-serif;
        background: linear-gradient(to right, #4b2e05, #8b5a2b);
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    .login-container {
        background: #f8f5f1;
        padding: 50px 40px;
        border-radius: 25px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        text-align: center;
        width: 380px;
        animation: fadeIn 1s ease-in-out;
    }

    .login-container img {
        width: 85px;
        margin-bottom: 20px;
        filter: drop-shadow(0 2px 3px rgba(0,0,0,0.25));
    }

    .login-container h2 {
        color: #6f4e37;
        margin-bottom: 25px;
        font-size: 26px;
        letter-spacing: 0.5px;
    }

    form {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .login-container input,
    .login-container button {
        width: 100%;
        max-width: 300px;
        box-sizing: border-box;
    }

    .login-container input {
        padding: 12px 15px;
        margin-bottom: 15px;
        border: 2px solid #b08968;
        border-radius: 10px;
        font-size: 15px;
        outline: none;
        background-color: #fff;
        transition: 0.3s;
    }

    .login-container input:focus {
        border-color: #6f4e37;
        box-shadow: 0 0 6px rgba(111, 78, 55, 0.3);
    }

    .login-container button {
        padding: 12px;
        background-color: #6f4e37;
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 16px;
        cursor: pointer;
        transition: 0.3s;
        margin-top: 5px;
    }

    .login-container button:hover {
        background-color: #5c3d2e;
    }

    .error-message {
        color: #5c3d2e;
        font-weight: 600;
        margin-top: 10px;
        background-color: #ffe9d6;
        padding: 8px;
        border-radius: 8px;
        max-width: 300px;
        margin-left: auto;
        margin-right: auto;
    }

    .login-container p {
        margin-top: 18px;
        font-size: 14px;
        color: #3c2f2f;
    }

    .login-container a {
        color: #6f4e37;
        font-weight: 600;
        text-decoration: none;
    }

    .login-container a:hover {
        text-decoration: underline;
    }

    @keyframes fadeIn {
        from {opacity: 0; transform: translateY(20px);}
        to {opacity: 1; transform: translateY(0);}
    }
</style>
</head>
<body>

<div class="login-container">
    <!-- Logo kopi -->
    <img src="https://cdn-icons-png.flaticon.com/512/924/924514.png" alt="Coffee Cup Icon">
    <h2>Selamat Datang di <br><strong>Panggilan Kopi</strong></h2>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Masuk</button>
    </form>

    <?php if (!empty($error)): ?>
        <div class="error-message"><?= $error; ?></div>
    <?php endif; ?>

    <p>Belum punya akun? <a href="register.php">Daftar Sekarang</a></p>
</div>

</body>
</html>
