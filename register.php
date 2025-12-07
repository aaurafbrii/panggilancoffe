<?php
include 'config/db.php';

if (isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $pass = md5($_POST['password']);

    // Cek apakah email sudah ada
    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('Email sudah terdaftar! Silakan login.');window.location='login.php';</script>";
        exit;
    }

    $insert = mysqli_query($conn, "INSERT INTO users(name,email,password,role) VALUES('$name','$email','$pass','customer')");
    if ($insert) {
        echo "<script>alert('Registrasi berhasil! Silakan login.');window.location='login.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registrasi Akun | FoodOrder</title>
<style>
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(to right, #6f4e37, #d9c6a5);
    margin: 0;
    padding: 0;
    color: #333;
}

/* CONTAINER */
.container {
    background: #fff;
    width: 380px;
    margin: 80px auto;
    padding: 35px;
    border-radius: 20px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    text-align: center;
}

h2 {
    color: #6f4e37;
    margin-bottom: 15px;
    font-weight: 700;
}

input {
    width: 85%;
    padding: 12px;
    margin: 8px 0;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 15px;
    outline: none;
    transition: 0.3s;
}

input:focus {
    border-color: #6f4e37;
    box-shadow: 0 0 5px rgba(255,69,0,0.4);
}

button {
    background: #6f4e37;
    color: white;
    border: none;
    padding: 12px 18px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    margin-top: 10px;
    width: 90%;
    transition: 0.3s;
}

button:hover {
    background: #6f4e37;
    transform: translateY(-2px);
}

a {
    color: #6f4e37;
    text-decoration: none;
    font-weight: 600;
}

a:hover {
    text-decoration: underline;
}

footer {
    text-align: center;
    color: white;
    font-size: 14px;
    margin-top: 40px;
}
</style>
</head>
<body>

<div class="container">
  <h2>Daftar Akun Baru</h2>
  <form method="POST">
      <input type="text" name="name" placeholder="Nama Lengkap" required><br>
      <input type="email" name="email" placeholder="Alamat Email" required><br>
      <input type="password" name="password" placeholder="Password" required><br>
      <button type="submit" name="register">Daftar Sekarang</button>
  </form>
  <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
</div>

<footer>
  &copy; <?= date('Y'); ?> Aura Febrianti
</footer>

</body>
</html>
