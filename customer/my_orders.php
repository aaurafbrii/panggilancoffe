<?php
session_start();
include '../config/db.php';

// Pastikan user sudah login
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}

$uid = $_SESSION['user']['id'];

// Ambil semua pesanan user
$query = "
    SELECT o.*, m.name 
    FROM orders o 
    JOIN menu m ON o.menu_id = m.id 
    WHERE o.user_id = '$uid'
    ORDER BY o.order_date DESC
";
$orders = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pesanan Saya | Panggilan Kopi</title>
<style>
body {
    font-family: 'Poppins', sans-serif;
    background-color: #f8f5f0;
    color: #3b2f2f;
    margin: 0;
    padding: 30px;
}
h2 {
    text-align: center;
    color: #6f4e37;
}

tr:hover {
    background-color: #f2ebe6;
}
.empty {
    text-align: center;
    padding: 20px;
    color: #6f4e37;
}
.btn {
    display: inline-block;
    background: #6f4e37;
    color: #fff;
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    margin-top: 20px;
}
.btn:hover {
    background: #5a3f2f;
}
</style>
</head>
<body>

<h2>📦 Pesanan Saya</h2>

<table>
  <tr>
    <th>Nama Menu</th>
    <th>Jumlah</th>
    <th>Total Harga</th>
    <th>Tanggal Pesan</th>
    <th>Status</th>
  </tr>
  <?php if (mysqli_num_rows($orders) > 0): ?>
      <?php while($r = mysqli_fetch_assoc($orders)): ?>
      <tr>
        <td><?= htmlspecialchars($r['name']) ?></td>
        <td><?= htmlspecialchars($r['quantity']) ?></td>
        <td>Rp <?= number_format($r['total_price'], 0, ',', '.') ?></td>
        <td><?= date('d-m-Y H:i', strtotime($r['order_date'])) ?></td>
        <td><?= htmlspecialchars($r['status']) ?></td>
      </tr>
      <?php endwhile; ?>
  <?php else: ?>
      <tr><td colspan="5" class="empty">Belum ada pesanan 😋</td></tr>
  <?php endif; ?>
</table>

<div style="text-align:center;">
  <a href="index.php" class="btn">⬅ Kembali ke Menu Utama</a>
</div>

</body>
</html>
