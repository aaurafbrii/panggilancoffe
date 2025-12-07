<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// --- Koneksi ke database ---
include '../config/db.php';

// Hitung pemasukan harian
$harian = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT SUM(total_price) AS total 
    FROM orders 
    WHERE DATE(order_date) = CURDATE()
"))['total'] ?? 0;

// Hitung pemasukan mingguan
$mingguan = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT SUM(total_price) AS total 
    FROM orders 
    WHERE YEARWEEK(order_date, 1) = YEARWEEK(CURDATE(), 1)
"))['total'] ?? 0;

// Hitung pemasukan bulanan
$bulanan = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT SUM(total_price) AS total 
    FROM orders 
    WHERE MONTH(order_date) = MONTH(CURDATE()) 
    AND YEAR(order_date) = YEAR(CURDATE())
"))['total'] ?? 0;

// --- Query: Top Menu Populer (berdasarkan SUM(quantity)) ---
$popular_query = "
    SELECT m.id, m.name, m.price, m.image, COALESCE(SUM(o.quantity),0) AS total_ordered
    FROM menu m
    LEFT JOIN orders o ON o.menu_id = m.id
    GROUP BY m.id
    ORDER BY total_ordered DESC
    LIMIT 5
";

$popular = mysqli_query($conn, $popular_query);

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Admin | Panggilan Kopi</title>
<link rel="stylesheet" href="assets/style/admin_style.css">
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <img src="https://cdn-icons-png.flaticon.com/512/924/924514.png" alt="Coffee Icon">
    <h2>Panggilan Kopi<br>Admin</h2>
    <div class="menu">
        <a href="menu.php">☕ Kelola Menu</a>
        <a href="orders.php">📦 Lihat Pesanan</a>
        <a href="add_customer.php">👤 Tambah Pelanggan</a>
        <a href="rekapan.php">📊 Rekapan Penjualan</a>
        <a href="../logout.php" class="logout" onclick="return confirm('Anda yakin ingin keluar dari akun?')">🚪 Keluar</a>
    </div>
</div>

<!-- MAIN CONTENT -->
<div class="content">
    <header>
        <h2>Dashboard Admin</h2>
        <div class="user-info">
            👋 Halo, <b><?= htmlspecialchars($_SESSION['user']['name']); ?></b>
        </div>
    </header>

    <div class="dashboard-main">
        <h3>Selamat Datang di Panggilan Kopi</h3>
        <p>Gunakan menu di sebelah kiri untuk mengelola pesanan dan menu kopi Anda.</p>

        <hr style="margin: 30px 0; border: 1px solid #e0d4c8;">

        <h3>📊 Ringkasan Pemasukan</h3>
        <div class="cards">
            <div class="card">
                <h4>💰 Pemasukan Harian</h4>
                <p>Rp <?= number_format($harian, 0, ',', '.'); ?></p>
            </div>
            <div class="card">
                <h4>📆 Pemasukan Mingguan</h4>
                <p>Rp <?= number_format($mingguan, 0, ',', '.'); ?></p>
            </div>
            <div class="card">
                <h4>🗓️ Pemasukan Bulanan</h4>
                <p>Rp <?= number_format($bulanan, 0, ',', '.'); ?></p>
            </div>
        </div>

        <hr style="margin: 30px 0; border: 1px solid #e0d4c8;">

        <h3>🔥 Top Menu Populer</h3>
        <div style="display:flex; gap:18px; flex-wrap:wrap; margin-top:12px;">
            <?php if($popular && mysqli_num_rows($popular) > 0): ?>
                <?php while($p = mysqli_fetch_assoc($popular)): ?>
                    <div class="card" style="min-width:200px; max-width:220px; text-align:left;">
                        <?php if (!empty($p['image']) && file_exists('../uploads/menu/'.$p['image'])): ?>
                            <img src="../uploads/menu/<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" style="width:100%; height:110px; object-fit:cover; border-radius:8px; margin-bottom:8px;">
                        <?php else: ?>
                            <div style="width:100%; height:110px; background:#f2e5d9; display:flex;align-items:center;justify-content:center;border-radius:8px;margin-bottom:8px;">No Image</div>
                        <?php endif; ?>
                        <strong style="display:block; margin-bottom:6px; color:#6f4e37;"><?= htmlspecialchars($p['name']) ?></strong>
                        <div style="font-size:14px; color:#4b3829; margin-bottom:6px;">Terjual: <b><?= (int)$p['total_ordered'] ?></b></div>
                        <div style="color:#2b1d13; font-weight:700;">Rp <?= number_format($p['price'],0,',','.') ?></div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Tidak ada data penjualan.</p>
            <?php endif; ?>
        </div>

    </div>

    <footer>
        &copy; <?= date('Y'); ?> Panggilan Kopi by Aura Febrianti ☕
    </footer>
</div>

</body>
</html>
