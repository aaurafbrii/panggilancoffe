<?php
session_start();
include '../config/db.php';

// Pastikan hanya admin yang bisa mengakses
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM menu WHERE id='$id'"));

if (!$data) {
    echo "Menu tidak ditemukan!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Menu</title>
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(to right, #6f4e37, #3c2f2f);
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }
    .box {
        background: #fff;
        padding: 35px 30px;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.25);
        width: 400px;
    }
    h2 {
        text-align: center;
        color: #6f4e37;
        margin-bottom: 25px;
        font-size: 24px;
    }
    label {
        font-weight: 600;
        color: #5b3c2e;
        display: block;
        margin-bottom: 6px;
    }
    input, textarea, select {
        width: 100%;
        box-sizing: border-box;
        padding: 10px;
        border: 2px solid #c4a484;
        border-radius: 8px;
        margin-bottom: 15px;
        font-size: 15px;
    }
    textarea {
        resize: none;
        height: 80px;
    }
    input[type="file"] {
        padding: 6px;
        background-color: #fff;
    }
    button {
        background: #6f4e37;
        color: white;
        border: none;
        padding: 10px;
        border-radius: 8px;
        cursor: pointer;
        width: 100%;
        font-weight: 600;
        font-size: 15px;
        transition: 0.3s;
        box-sizing: border-box;
    }
    button:hover {
        background: #5b3c2e;
        transform: scale(1.02);
    }
    a {
        display: block;
        text-align: center;
        margin-top: 12px;
        color: #6f4e37;
        text-decoration: none;
        font-weight: 500;
    }
    a:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>

<div class="box">
    <h2>Edit Menu</h2>
    <form method="POST" enctype="multipart/form-data" action="menu.php">
        <input type="hidden" name="id" value="<?= $data['id'] ?>">

        <label>Nama Menu</label>
        <input type="text" name="name" value="<?= htmlspecialchars($data['name']) ?>" required>

        <label>Kategori</label>
        <select name="category" required>
            <option value="Makanan" <?= $data['category']=='Makanan'?'selected':'' ?>>Makanan</option>
            <option value="Minuman" <?= $data['category']=='Minuman'?'selected':'' ?>>Minuman</option>
        </select>

        <label>Harga</label>
        <input type="number" name="price" value="<?= $data['price'] ?>" required>

        <label>Deskripsi</label>
        <textarea name="desc"><?= htmlspecialchars($data['description']) ?></textarea>

        <label>Gambar Baru (opsional)</label>
        <input type="file" name="image" accept="image/*">

        <button type="submit" name="edit">Simpan Perubahan</button>
    </form>
    <a href="menu.php"> ⬅ Kembali</a>
</div>

</body>
</html>
