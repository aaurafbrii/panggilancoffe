<?php
session_start();
include '../config/db.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

// Ubah status pesanan
if (isset($_GET['update'])) {
    $id = $_GET['update'];
    $status = $_GET['status'];

    mysqli_query($conn, "UPDATE orders SET status='$status' WHERE id='$id'");
    header("Location: orders.php");
    exit;
}

// Ambil data pesanan
$orders = mysqli_query($conn, "
    SELECT o.*, u.name AS customer, m.name AS menu_name 
    FROM orders o 
    JOIN users u ON o.user_id=u.id 
    JOIN menu m ON o.menu_id=m.id
    ORDER BY o.id DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Daftar Pesanan - Admin</title>
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
        margin: 40px auto;
        padding: 30px 40px;
        border-radius: 20px;
        width: 90%;
        max-width: 1000px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }

/* Table styling - Pesanan Saya */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
table th {
    background: #7b4b27;
    color: #fff;
    padding: 12px;
    text-align: center;
}
table td {
    padding: 10px;
    text-align: center;
    border-bottom: 1px solid #ddd;
    color: #3c2f2f;
}

/* Container Pesanan Saya */
#ordersTab {
    background: #fff;
    border-radius: 20px;
    padding: 20px 25px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}


    th {
        background: #6f4e37;
        color: white;
        font-size: 16px;
    }

    tr:nth-child(even) {
        background: #f8f3ef;
    }

    tr:hover {
        background: #f0e5db;
    }

.status {
    font-weight: bold;
    text-transform: capitalize;
    padding: 6px 12px;
    border-radius: 10px;
    display: inline-block;
    font-size: 14px;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
}

/* Pending - merah lembut */
.status.pending {
    background: #ffe5e5;
    color: #b30000;
    border: 1px solid #ff4d4d;
}

/* Diproses - biru kopi dingin */
.status.diproses {
    background: #e0f0ff;
    color: #004080;
    border: 1px solid #3399ff;
}

/* Selesai - hijau matcha tua */
.status.selesai {
    background: #e5ffe5;
    color: #006600;
    border: 1px solid #33cc33;
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
    <h2>📋 Daftar Pesanan Pelanggan</h2>
    <div class="btn-container">
        <a href="index.php" class="btn">🏠 Dashboard</a>
        <a href="../logout.php" class="btn logout" onclick="return confirm('Anda yakin ingin keluar dari akun?')">Keluar</a>
    </div>
</div>

<div class="container">
    <table>
        <tr>
            <th>Pelanggan</th>
            <th>Makanan</th>
            <th>Jumlah</th>
            <th>Total</th>
            <th>Tanggal</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php while($r = mysqli_fetch_assoc($orders)) { ?>
        <tr>
            <td><?= htmlspecialchars($r['customer']) ?></td>
            <td><?= htmlspecialchars($r['menu_name']) ?></td>
            <td><?= $r['quantity'] ?></td>
            <td>Rp<?= number_format($r['total_price'], 0, ',', '.') ?></td>
            <td><?= $r['order_date'] ?></td>
            <td>
                <span class="status <?= $r['status'] ?>">
                    <?= htmlspecialchars($r['status']) ?>
                </span>
            </td>
            <td>
                <?php if ($r['status'] == 'pending') { ?>
                    <a class="btn" href="?update=<?= $r['id'] ?>&status=diproses" onclick="return confirm('Terima pesanan ini?')">Terima</a>
                <?php } elseif ($r['status'] == 'diproses') { ?>
                    <a class="btn" href="?update=<?= $r['id'] ?>&status=selesai" onclick="return confirm('Tandai pesanan selesai?')">Selesai</a>
                <?php } else { ?>
                    ✅ <span style="color:green;font-weight:bold;">Selesai</span>
                <?php } ?>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>

<footer>
    &copy; <?= date('Y'); ?> Aura Febrianti
</footer>

</body>
</html>
