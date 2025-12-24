# ☕ PanggilanKopi

HALAMAN LOGIN
![image alt](https://github.com/aaurafbrii/panggilancoffe/blob/2e1a6a047b8e59626c1126046bba4b1c1a34f625/LOGIN.png)

HALAMAN REGISTER
![image alt](https://github.com/aaurafbrii/panggilancoffe/blob/2e1a6a047b8e59626c1126046bba4b1c1a34f625/register.png)

HALAMAN UTAMA ADMIN
![image alt](https://github.com/aaurafbrii/panggilancoffe/blob/25b31cb071ede0e2dd9ecd3d6da6b72b2ddfa65a/dashboard%20admin.png)

HALAMAN STATUS PESANAN PELANGGAN ( ADMIN )
![image alt](https://github.com/aaurafbrii/panggilancoffe/blob/c8e35676834455e782ed77e02e6e6b0d013c907b/daftar%20pesanan%20pelanggan.png)

HALAMAN KELOLA MENU ADMIN
![image alt](https://github.com/aaurafbrii/panggilancoffe/blob/c8e35676834455e782ed77e02e6e6b0d013c907b/kelola%20menu%20admin.png)

HALAMAN TAMBAH PELANGGAN BARU ADMIN
![image alt](https://github.com/aaurafbrii/panggilancoffe/blob/c8e35676834455e782ed77e02e6e6b0d013c907b/tambah%20pelanggan%20admin.png)

kelola menu admin
HALAMAN UTAMA PELANGGAN
![image alt](https://github.com/aaurafbrii/panggilancoffe/blob/7815174f4565e03215bb075e2975f5d7e70151b2/dashboard%20pelanggan%20aura.png)

HALAMAN STATUS PESANAN PELANGGAN
![image alt](https://github.com/aaurafbrii/panggilancoffe/blob/2e1a6a047b8e59626c1126046bba4b1c1a34f625/pesanan%20pelanggan%20aura.png)

📍PanggilanKopi adalah aplikasi sistem pemesanan menu di coffee shop yang dirancang untuk memudahkan pelanggan memesan dan staf mengelola pesanan secara cepat dan efisien.

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
- Lihat status pesanan

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
   - Buat database baru, misal coffe_order
   - Import file SQL (coffe_order.sql)
4. Konfigurasi koneksi
   - Buka config/database.php
   - Sesuaikan username, password, dan nama database
5. Akses aplikasi melalui browser:
   http://localhost/coffe_order

🔐 Cara Login
👨‍💼 Login Admin
- username : admin123@gmail.com
- password : admin123
- akses : http://localhost/coffe_order/login.php

👤 Login Customer
- registrasi terlebih dahulu
- masukkan username (email terdaftar) dan password
  
🧪 Penggunaan
- Jalankan server lokal Anda
- Tambah menu ke keranjang
- Checkout pesanan
- Admin dapat login dan mengelola data

✨ Made with PHP by Aura Febrianti ✨
   
