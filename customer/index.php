<?php
session_start();
include '../config/db.php';

// Cek apakah user login dan role = customer
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'customer') {
    header('Location: ../login.php');
    exit;
}

$uid = $_SESSION['user']['id'];

// ---- Tambah ke keranjang ----
if (isset($_POST['add_to_cart'])) {
    $menu_id = intval($_POST['menu_id']);
    $quantity = intval($_POST['quantity']);

    // Cek apakah menu sudah ada di keranjang
    $check = mysqli_query($conn, "SELECT * FROM cart WHERE user_id='$uid' AND menu_id='$menu_id'");
    if (mysqli_num_rows($check) > 0) {
        mysqli_query($conn, "UPDATE cart SET quantity = quantity + $quantity WHERE user_id='$uid' AND menu_id='$menu_id'");
    } else {
        mysqli_query($conn, "INSERT INTO cart (user_id, menu_id, quantity) VALUES ('$uid', '$menu_id', '$quantity')");
    }

    echo "<script>alert('Menu berhasil ditambahkan ke keranjang!');window.location='index.php';</script>";
    exit;
}

// ---- Filter kategori ----
$where = '';
if (isset($_GET['filter']) && $_GET['filter'] != '') {
    $filter = mysqli_real_escape_string($conn, $_GET['filter']);
    $where = "WHERE category='$filter'";
}

$menu = mysqli_query($conn, "SELECT * FROM menu $where ORDER BY id DESC");

// ---- Ambil pesanan ----
$orders = mysqli_query($conn, "
    SELECT o.*, m.name 
    FROM orders o 
    JOIN menu m ON o.menu_id = m.id 
    WHERE o.user_id='$uid' 
    ORDER BY o.id DESC
");

// ---- Hitung jumlah keranjang ----
$cart_count = 0;
$cart_query = mysqli_query($conn, "SELECT SUM(quantity) AS total FROM cart WHERE user_id='$uid'");
if ($cart_query && mysqli_num_rows($cart_query) > 0) {
    $cart_data = mysqli_fetch_assoc($cart_query);
    $cart_count = $cart_data['total'] ?? 0;
}

// ---- Query Top Menu Populer untuk customer (ambil top 5) ----
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
    <title>Dashboard Pelanggan | Panggilan Kopi</title>
    <link rel="stylesheet" href="../assets/style/customer.css">
</head>
<body>

    <div class="header">
        <h2>Dashboard Pelanggan - <?= htmlspecialchars($_SESSION['user']['name']) ?></h2>
        <div class="header-buttons">
            <a href="cart.php" class="cart-btn">
                🛒 Keranjang<?= $cart_count ? " ($cart_count)" : "" ?>
            </a>
            <a href="../logout.php" class="logout" onclick="return confirm('Anda yakin ingin keluar dari akun?')">
                Keluar
            </a>
        </div>
    </div>

    <div class="tabs">
        <div class="tab active" onclick="showTab('menu')">🍴 Daftar Menu</div>
        <div class="tab" onclick="showTab('orders')">🧾 Pesanan Saya</div>
    </div>

    <!-- POPULAR SECTION -->
    <div class="content" style="max-width:1000px; width:90%; margin-top:25px;">
        <h3>🔥 Menu Populer</h3>
        <div class="menu-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap:16px;">
            <?php if ($popular && mysqli_num_rows($popular) > 0): ?>
                <?php while($p = mysqli_fetch_assoc($popular)): ?>
                    <div class="menu-card">
                        <div class="menu-image">
                            <?php if (!empty($p['image']) && file_exists('../uploads/menu/'.$p['image'])): ?>
                                <img src="../uploads/menu/<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
                            <?php else: ?>
                                <div class="no-image">Tidak ada gambar</div>
                            <?php endif; ?>
                        </div>
                        <div class="menu-info">
                            <h4><?= htmlspecialchars($p['name']) ?></h4>
                            <p class="price">Rp<?= number_format($p['price'],0,',','.') ?></p>
                            <div style="margin-top:6px; font-size:13px; color:#4b3829;">Terjual: <b><?= (int)$p['total_ordered'] ?></b></div>
                            <form method="POST" style="margin-top:10px;">
                                <input type="hidden" name="menu_id" value="<?= (int)$p['id'] ?>">
                                <input type="number" name="quantity" value="1" min="1" style="width:50px;text-align:center;">
                                <button type="submit" name="add_to_cart">+ Keranjang</button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Tidak ada menu populer saat ini.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- TAB MENU (daftar semua menu) -->
    <div class="content" id="menuTab">
        <h3><center>Daftar Menu</center></h3>

        <form method="GET" class="filter-form">
            <select name="filter" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                <option value="Makanan" <?= (isset($_GET['filter']) && $_GET['filter']=='Makanan')?'selected':''; ?>>Makanan</option>
                <option value="Minuman" <?= (isset($_GET['filter']) && $_GET['filter']=='Minuman')?'selected':''; ?>>Minuman</option>
            </select>
        </form>

        <div class="menu-grid">
            <?php while($r = mysqli_fetch_assoc($menu)) { ?>
                <div class="menu-card">
                    <div class="menu-image">
                        <?php if(!empty($r['image'])) { ?>
                            <img src="../uploads/menu/<?= htmlspecialchars($r['image']) ?>" alt="<?= htmlspecialchars($r['name']) ?>">
                        <?php } else { ?>
                            <div class="no-image">Tidak ada gambar</div>
                        <?php } ?>
                    </div>
                    <div class="menu-info">
                        <h4><?= htmlspecialchars($r['name']) ?></h4>
                        <span class="category"><?= htmlspecialchars($r['category']) ?></span>
                        <p class="price">Rp<?= number_format($r['price'], 0, ',', '.') ?></p>
                        <form method="POST" style="margin-top:10px;">
                            <input type="hidden" name="menu_id" value="<?= $r['id'] ?>">
                            <input type="number" name="quantity" value="1" min="1" style="width:50px;text-align:center;">
                            <button type="submit" name="add_to_cart">+ Keranjang</button>
                        </form>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- TAB PESANAN -->
    <div class="content" id="ordersTab" style="display:none;">
        <h3>Pesanan Saya</h3>
        <table>
            <tr>
                <th>Makanan</th>
                <th>Jumlah</th>
                <th>Total</th>
                <th>Tanggal</th>
                <th>Status</th>
            </tr>
            <?php while($r = mysqli_fetch_assoc($orders)) { ?>
            <tr>
                <td><?= htmlspecialchars($r['name']) ?></td>
                <td><?= $r['quantity'] ?></td>
                <td>Rp<?= number_format($r['total_price'], 0, ',', '.') ?></td>
                <td><?= $r['order_date'] ?></td>
                <td><span class="status <?= $r['status'] ?>"><?= htmlspecialchars($r['status']) ?></span></td>
            </tr>
            <?php } ?>
        </table>
    </div>

    <footer>&copy; <?= date('Y'); ?> Aura Febrianti</footer>

    <script>
        function showTab(tabName) {
            const menuTab = document.getElementById('menuTab');
            const ordersTab = document.getElementById('ordersTab');
            const tabs = document.querySelectorAll('.tab');

            menuTab.style.display = tabName === 'menu' ? 'block' : 'none';
            ordersTab.style.display = tabName === 'orders' ? 'block' : 'none';

            tabs.forEach(t => t.classList.remove('active'));
            if (tabName === 'menu') tabs[0].classList.add('active');
            else tabs[1].classList.add('active');
        }
    </script>

</body>
</html>
