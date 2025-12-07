<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];

// --- Tambah ke keranjang ---
if (isset($_POST['add_to_cart'])) {
    $menu_id = $_POST['menu_id'];
    $quantity = $_POST['quantity'];

    $check = mysqli_query($conn, "SELECT * FROM cart WHERE user_id='$user_id' AND menu_id='$menu_id'");
    if (mysqli_num_rows($check) > 0) {
        mysqli_query($conn, "UPDATE cart SET quantity = quantity + $quantity WHERE user_id='$user_id' AND menu_id='$menu_id'");
    } else {
        mysqli_query($conn, "INSERT INTO cart (user_id, menu_id, quantity) VALUES ('$user_id', '$menu_id', '$quantity')");
    }
    header("Location: cart.php");
    exit();
}

// --- Hapus item dari keranjang ---
if (isset($_GET['remove'])) {
    $id = $_GET['remove'];
    mysqli_query($conn, "DELETE FROM cart WHERE id='$id'");
    header("Location: cart.php");
    exit();
}

// --- Buat pesanan ---
if (isset($_POST['checkout'])) {
    $payment_method = $_POST['payment_method'];

    $cart_items = mysqli_query($conn, "SELECT * FROM cart WHERE user_id='$user_id'");
    $order_date = date('Y-m-d H:i:s');
    $status = 'pending';

    while ($item = mysqli_fetch_assoc($cart_items)) {
        $menu_id = $item['menu_id'];
        $quantity = $item['quantity'];
        $menu = mysqli_fetch_assoc(mysqli_query($conn, "SELECT price FROM menu WHERE id='$menu_id'"));
        $price = $menu['price'];
        $total = $price * $quantity;

        mysqli_query($conn, "INSERT INTO orders (user_id, menu_id, quantity, total_price, order_date, status, payment_method)
                             VALUES ('$user_id', '$menu_id', '$quantity', '$total', '$order_date', '$status', '$payment_method')");
    }

    mysqli_query($conn, "DELETE FROM cart WHERE user_id='$user_id'");
    header("Location: index.php?order_success=1");
    exit();
}

// --- Ambil isi keranjang ---
$result = mysqli_query($conn, "
    SELECT c.id, m.name, m.price, c.quantity, (m.price * c.quantity) AS subtotal 
    FROM cart c 
    JOIN menu m ON c.menu_id = m.id 
    WHERE c.user_id = '$user_id'
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keranjang Saya</title>
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background-color: #f7f3ef;
            color: #3c2f2f;
            margin: 0;
            padding: 40px;
        }
        h2 { text-align: center; margin-bottom: 30px; color:#6f4e37; }
        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 10px; overflow: hidden; }
        th, td { padding: 14px 18px; border-bottom: 1px solid #e0d7d1; }
        th { background: #6f4e37; color: #fff; }
        tr:hover { background-color: #f2ebe6; }
        a.remove { color: #c0392b; font-weight: bold; text-decoration: none; }
        .total { text-align: right; font-weight: bold; margin-top: 25px; font-size: 18px; }
        .btn-container { text-align: center; margin-top: 40px; }
        .btn {
            background: #6f4e37; color: #fff; padding: 12px 26px; border-radius: 8px;
            font-size: 15px; cursor: pointer; border: none; font-weight: bold;
        }
        .btn:hover { background: #5a3f2f; }
    </style>
</head>
<body>

<h2>🛒 Keranjang Pesanan Anda</h2>

<table>
    <tr>
        <th>Nama Menu</th>
        <th>Harga</th>
        <th>Jumlah</th>
        <th>Subtotal</th>
        <th>Aksi</th>
    </tr>
    <?php
    $total = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $total += $row['subtotal'];
        echo "
        <tr>
            <td>{$row['name']}</td>
            <td>Rp " . number_format($row['price'], 0, ',', '.') . "</td>
            <td>{$row['quantity']}</td>
            <td>Rp " . number_format($row['subtotal'], 0, ',', '.') . "</td>
            <td><a href='cart.php?remove={$row['id']}' class='remove'>Hapus</a></td>
        </tr>";
    }
    ?>
</table>

<p class="total">Total: Rp <?= number_format($total, 0, ',', '.'); ?></p>

<div class="btn-container">
    <form method="post">
        <select name="payment_method" required style="padding:8px; border-radius:6px; margin-right:15px;">
            <option value="kasir">Bayar di Kasir</option>
        </select>
        <button type="submit" name="checkout" class="btn">✅ Buat Pesanan</button>
    </form>
</div>

</body>
</html>
