# ☕ PanggilanCoffe

![image alt](https://github.com/aaurafbrii/panggilancoffe/blob/2e1a6a047b8e59626c1126046bba4b1c1a34f625/LOGIN.png)
![image alt](https://github.com/aaurafbrii/panggilancoffe/blob/2e1a6a047b8e59626c1126046bba4b1c1a34f625/register.png)
![image alt](https://github.com/aaurafbrii/panggilancoffe/blob/25b31cb071ede0e2dd9ecd3d6da6b72b2ddfa65a/dashboard%20admin.png)
![image alt](https://github.com/aaurafbrii/panggilancoffe/blob/7815174f4565e03215bb075e2975f5d7e70151b2/dashboard%20pelanggan%20aura.png)
![image alt](https://github.com/aaurafbrii/panggilancoffe/blob/2e1a6a047b8e59626c1126046bba4b1c1a34f625/pesanan%20pelanggan%20aura.png)

📍 panggilancoffe adalah aplikasi sistem pemesanan menu di coffee shop yang dirancang untuk memudahkan pelanggan memesan dan staf mengelola pesanan secara cepat dan efisien.

---

## 🧠 Tentang Proyek

Aplikasi ini dibuat sebagai tugas/latihan membuat sistem Order Menu Coffee Shop berbasis website menggunakan teknologi web.  
Fitur yang dibangun mencakup menu, pemesanan, keranjang pesanan, dan proses checkout.

## 🚀 Fitur

✨ Pelanggan
- Lihat daftar menu makanan & minuman
- Tambahkan item ke keranjang
- Lihat total harga
- Checkout pesanan

👨‍💼 Admin
- Kelola menu (tambahkan, edit, hapus)
- Lihat semua pesanan
- Update status pesanan
- Tambah Pelanggan
- Melihat rekapan bulanan

## 🛠️ Teknologi yang Digunakan

- Frontend: HTML, CSS  
- Backend: PHP  
- Database: MySQL  
- Server development: XAMPP

## 📁 Struktur Databse
Databse : db_menucoffe

Tabel:
- users : id, name, email, password
- orders : id, user_id, menu_id, quantity, total_price, order_date, status, payment_method
- menu : id, name, category, price, image, description
- cart : id, user_id, menu_id, quantity

## 📥 Cara Instalasi

1. Clone repository ini
   ```bash
   git clone https://github.com/aaurafbrii/panggilancoffe.git
   Pindahkan folder ke direktori server lokal (misal htdocs di XAMPP)
2. Pindahkan folder ke direktori server lokal (misal htdocs di XAMPP)
3. Import database
   - Buka phpMyAdmin
   - Buat database baru, misal panggilan_coffe
   - Import file SQL (panggilancoffe.sql)
4. Konfigurasi koneksi
   - Buka config/database.php
   - Sesuaikan username, password, dan nama database
5. Akses aplikasi melalui browser:
   http://localhost/panggilancoffe

🧪 Penggunaan
- Jalankan server lokal Anda
- Tambah menu ke keranjang
- Checkout pesanan
- Admin dapat login dan mengelola data


✨ Made with PHP by Aura Febrianti
   
