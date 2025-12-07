<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

include '../config/db.php';

// Query total penjualan per bulan
$query = "
    SELECT 
        DATE_FORMAT(order_date, '%Y-%m') AS bulan,
        SUM(total_price) AS total
    FROM orders
    GROUP BY DATE_FORMAT(order_date, '%Y-%m')
    ORDER BY bulan DESC
";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Rekapan Penjualan</title>
<link rel="stylesheet" href="assets/style/admin_style.css">
<style>
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}
table th, table td {
    padding: 12px;
    border: 1px solid #ccc;
}
table th {
    background: #6f4e37;
    color: white;
}
</style>
</head>
<body>

<div class="sidebar">
    <img src="https://cdn-icons-png.flaticon.com/512/924/924514.png" alt="Coffee Icon">
    <h2>Panggilan Kopi<br>Admin</h2>
    <div class="menu">
        <a href="menu.php">☕ Kelola Menu</a>
        <a href="orders.php">📦 Lihat Pesanan</a>
        <a href="add_customer.php">👤 Tambah Pelanggan</a>
        <a href="rekapan.php" class="active">📊 Rekapan Penjualan</a>
        <a href="../logout.php" class="logout">🚪 Keluar</a>
    </div>
</div>

<div class="content">
    <h2>📊 Rekapan Penjualan Per Bulan</h2>

    <table>
        <tr>
            <th>Bulan</th>
            <th>Total Penjualan</th>
        </tr>
        <?php while($r = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= $r['bulan'] ?></td>
            <td>Rp <?= number_format($r['total'], 0, ',', '.') ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

</div>

</body>
</html>
