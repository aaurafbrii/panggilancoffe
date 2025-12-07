<?php
session_start();
date_default_timezone_set('Asia/Jakarta');
include '../config/db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'customer') {
    header('Location: ../login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = intval($_GET['id']);
$menu = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM menu WHERE id='$id'"));
if (!$menu) {
    echo "<script>alert('Menu tidak ditemukan!');window.location='index.php';</script>";
    exit;
}

if (isset($_POST['order'])) {
    $qty = intval($_POST['qty']);
    if ($qty < 1) {
        echo "<script>alert('Jumlah pesanan tidak valid!');window.location='order.php?id=$id';</script>";
        exit;
    }

    $total = $qty * $menu['price'];
    $uid = $_SESSION['user']['id'];

    // Simpan ke database
    mysqli_query($conn, "
        INSERT INTO orders (user_id, menu_id, quantity, total_price, order_date, status) 
        VALUES ('$uid', '$id', '$qty', '$total', NOW(), 'pending')
    ");

    echo "<script>alert('Pesanan berhasil dibuat!');window.location='index.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pesan Menu | Panggilan Kopi</title>
<style>
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(to right, #c19a6b, #8b5e3c);
    margin: 0;
    padding: 0;
    color: #3b2f2f;
}
.container {
    background: #fffaf3;
    width: 400px;
    margin: 80px auto;
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    text-align: center;
}
h2 {
    color: #6f4e37;
    margin-bottom: 10px;
}
img {
    width: 180px;
    height: 180px;
    object-fit: cover;
    border-radius: 15px;
    margin-bottom: 15px;
    box-shadow: 0 5px 10px rgba(0,0,0,0.15);
}
.price {
    font-size: 18px;
    color: #5a3e2b;
    font-weight: 600;
    margin-bottom: 15px;
}
input[type="number"] {
    width: 80%;
    padding: 10px;
    border: 2px solid #c19a6b;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 15px;
    background: #fffdf8;
}
button, a.btn {
    background: #8b5e3c;
    color: white;
    border: none;
    padding: 10px 18px;
    border-radius: 8px;
    cursor: pointer;
    text-decoration: none;
    font-weight: 600;
    margin: 5px;
}
button:hover, a.btn:hover {
    background: #6f4e37;
}
a.btn {
    background: #b68b5e;
}
a.btn:hover {
    background: #9c7449;
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
  <h2>Pesan Menu</h2>
  <?php if(!empty($menu['image'])) { ?>
      <img src="../uploads/menu/<?= htmlspecialchars($menu['image']) ?>" alt="<?= htmlspecialchars($menu['name']) ?>">
  <?php } ?>
  <h3><?= htmlspecialchars($menu['name']) ?></h3>
  <p class="price">Harga: Rp<?= number_format($menu['price'], 0, ',', '.') ?></p>

  <form method="POST" onsubmit="return confirm('Anda yakin ingin memesan menu ini?')">
      <input type="number" name="qty" placeholder="Jumlah Pesanan" required min="1">
      <br>
      <button type="submit" name="order">🍴 Pesan Sekarang</button>
      <a href="index.php" class="btn">⬅ Kembali</a>
  </form>
</div>

<footer>
  &copy; <?= date('Y'); ?> Aura Febrianti
</footer>

</body>
</html>
