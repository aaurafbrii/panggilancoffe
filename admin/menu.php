<?php
session_start();
include '../config/db.php';

// Pastikan hanya admin yang bisa mengakses
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

// Folder penyimpanan gambar
$uploadDir = '../uploads/menu/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

// Tambah menu baru
if (isset($_POST['add'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $desc = mysqli_real_escape_string($conn, $_POST['desc']);
    $imgName = null;

    if (!empty($_FILES['image']['name'])) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imgName = time() . '_' . uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imgName);
    }

    mysqli_query($conn, "INSERT INTO menu(name, category, price, description, image) 
                         VALUES('$name','$category','$price','$desc','$imgName')");
}

// Hapus menu (langsung hapus juga dari pesanan pelanggan)
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // 🔁 Hapus semua pesanan pelanggan yang menggunakan menu ini
    mysqli_query($conn, "DELETE FROM orders WHERE menu_id='$id'");

    // 🔄 Hapus gambar dari folder upload
    $data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM menu WHERE id='$id'"));
    if ($data && $data['image'] && file_exists($uploadDir . $data['image'])) {
        unlink($uploadDir . $data['image']);
    }

    // 🗑️ Hapus menu dari database
    mysqli_query($conn, "DELETE FROM menu WHERE id='$id'");

    // Kembali ke halaman tanpa alert
    header("Location: menu.php");
    exit;
}

// Edit menu
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $desc = mysqli_real_escape_string($conn, $_POST['desc']);

    $query = "UPDATE menu SET name='$name', category='$category', price='$price', description='$desc'";

    // Jika ada gambar baru diupload
    if (!empty($_FILES['image']['name'])) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imgName = time() . '_' . uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imgName);

        // Hapus gambar lama
        $old = mysqli_fetch_assoc(mysqli_query($conn, "SELECT image FROM menu WHERE id='$id'"));
        if ($old['image'] && file_exists($uploadDir . $old['image'])) {
            unlink($uploadDir . $old['image']);
        }

        $query .= ", image='$imgName'";
    }

    $query .= " WHERE id='$id'";
    mysqli_query($conn, $query);
    header("Location: menu.php");
    exit;
}

// Ambil semua menu
$menu = mysqli_query($conn, "SELECT * FROM menu ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kelola Menu - Admin | FoodOrder</title>
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(to right, #6f4e37, #3c2f2f);
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
    .header h2 { margin: 0; font-size: 26px; }
    .btn-container {
        position: absolute;
        right: 25px;
        top: 22px;
        display: flex;
        gap: 10px;
    }
    .btn {
        background: #6f4e37;
        color: #fff;
        padding: 8px 14px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: 0.3s;
    }
    .btn:hover { background: #5b3c2e; transform: translateY(-2px); }
    .logout { background: #3c2f2f; }
    .logout:hover { background: #2b1d13; }
    .container {
        background: #fff;
        margin: 40px auto;
        padding: 30px 40px;
        border-radius: 20px;
        width: 90%;
        max-width: 950px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }
    h3 { color: #6f4e37; margin-top: 0; }
    form { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 25px; }
    input, textarea, select {
        padding: 10px;
        border: 2px solid #c4a484;
        border-radius: 10px;
        font-size: 15px;
        flex: 1;
        background-color: #fdfcfb;
        transition: 0.3s;
    }
    input:focus, textarea:focus, select:focus {
        border-color: #6f4e37;
        box-shadow: 0 0 6px rgba(111,78,55,0.4);
    }
    textarea { flex: 100%; resize: none; }
    button {
        background: #6f4e37;
        color: white;
        border: none;
        padding: 10px 18px;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s;
    }
    button:hover { background: #5b3c2e; transform: translateY(-2px); }
    table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    th, td { border: 1px solid #d9c6a5; padding: 12px; text-align: center; }
    th { background: #6f4e37; color: white; }
    tr:nth-child(even) { background: #f8f3ef; }
    tr:hover { background: #f0e5db; }
    img {
        width: 80px; height: 80px; object-fit: cover;
        border-radius: 10px; box-shadow: 0 3px 6px rgba(0,0,0,0.15);
    }
    .no-img { color: #888; font-style: italic; }
    .action-btn {
        display: flex;
        justify-content: center;
        gap: 8px;
    }
    .edit, .delete {
        padding: 6px 10px;
        border-radius: 6px;
        text-decoration: none;
        color: white;
        font-size: 14px;
        font-weight: 600;
    }
    .edit { background: #c49b66; }
    .edit:hover { background: #a97f4e; }
    .delete { background: #a83b3b; }
    .delete:hover { background: #812d2d; }
    footer { text-align: center; padding: 15px; color: #f5f3f0; font-size: 14px; }
</style>
</head>
<body>

<div class="header">
    <h2>Kelola Menu Makanan & Minuman</h2>
    <div class="btn-container">
        <a href="index.php" class="btn">🏠 Dashboard</a>
        <a href="../logout.php" class="btn logout" onclick="return confirm('Anda yakin ingin keluar dari akun?')">Keluar</a>
    </div>
</div>

<div class="container">
    <h3>Tambah Menu Baru</h3>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Nama makanan/minuman" required>
        <select name="category" required>
            <option value="">-- Pilih Kategori --</option>
            <option value="Makanan">🍛 Makanan</option>
            <option value="Minuman">🥤 Minuman</option>
        </select>
        <input type="number" name="price" placeholder="Harga" required>
        <textarea name="desc" placeholder="Deskripsi (opsional)"></textarea>
        <input type="file" name="image" accept="image/*">
        <button type="submit" name="add">Tambah</button>
    </form>

    <h3>Daftar Menu</h3>
    <table>
        <tr>
            <th>Gambar</th>
            <th>Nama</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Deskripsi</th>
            <th>Aksi</th>
        </tr>
        <?php while($r = mysqli_fetch_assoc($menu)) { ?>
        <tr>
            <td>
                <?php if ($r['image']) { ?>
                    <img src="../uploads/menu/<?= htmlspecialchars($r['image']) ?>" alt="Gambar <?= htmlspecialchars($r['name']) ?>">
                <?php } else { ?>
                    <span class="no-img">(Tidak ada gambar)</span>
                <?php } ?>
            </td>
            <td><?= htmlspecialchars($r['name']) ?></td>
            <td><?= htmlspecialchars($r['category']) ?></td>
            <td>Rp<?= number_format($r['price'], 0, ',', '.') ?></td>
            <td><?= nl2br(htmlspecialchars($r['description'])) ?></td>
            <td>
                <div class="action-btn">
                    <a href="menu_edit.php?id=<?= $r['id'] ?>" class="edit">✏️ Edit</a>
                    <a href="menu.php?delete=<?= $r['id'] ?>" class="delete" onclick="return confirm('Anda yakin ingin menghapus menu ini?')">🗑️ Hapus</a>
                </div>
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
