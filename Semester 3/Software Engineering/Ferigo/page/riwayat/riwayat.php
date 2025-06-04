<?php 
    session_start();
    require_once '../../functions/functions.php';
    require_once '../../config.php';
    
    $nickname = $_SESSION['nickname'];
    $data = query("SELECT * FROM riwayat_pemesanan WHERE nickname = '$nickname' ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pemesanan Tiket</title>
    <link rel="shortcut icon" href="<?=BASEURL; ?>/img/icon.png" type="image/png">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap');

        body {
        font-family: 'Arial', sans-serif;
        background-repeat: no-repeat;
        background-position: center;
        background-size: cover;
        margin: 0;
        padding: 0;
        }

        .container {
        padding-left: 5px;
        padding-right: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #369f9f;
        border-bottom: 1px solid #f4f4f4;
        position: fixed;
        width: 100%;
        font-family: 'DM sans', sans-serif;
        z-index: 1;
        }


        nav ul { 
        margin: 0;
        font-weight: bold;
        list-style: none;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1px 1px;
        z-index: 1;
        }

        nav ul li {
        padding: 0px 0px;
        margin: 0 10px;
        }

        nav ul li a {
        text-decoration: none;
        color: white;
        }

        nav ul li a.brand {
        img{width: 150px;}
        }

        .gap{width: 375px;}

        .gap2{width: 100px;}

        .gap3{margin-bottom: 60px;}

        .login-btn {
        transition: background-color 0.3s ease;
        font-weight: 700;
        padding: 10px 20px;
        background-color: #000;
        color: #f4f4f4;
        border-radius: 40px;
        border: none;
        cursor: pointer;
        margin-right: 20px;
        }

        .login-btn:hover {
        background-color: #369f9f;
        color: #f4f4f4;
        border: #000;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fff;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #369f9f;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .container-history {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .button {
            display: inline-block;
            padding: 10px 15px;
            background-color: #369f9f;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            text-align: center;
        }
        .button:hover {
            background-color: #2a7a7a;
        }
        .download-button {
            display: inline-block;
            padding: 5px 10px;
            background-color: #369f9f; /* Warna sama dengan header tabel */
            color: white;
            text-decoration: none;
            border-radius: 3px;
            text-align: center;
            margin: 0; /* Menghilangkan margin */
            transition: background-color 0.3s;
        }
        .download-button:hover {
            background-color: #2a7a7a; /* Warna saat hover */
        }

/* Footer */
        footer {
            background-color: #369f9f;
            color: #f4f4f4;
            padding: 50px ;
            text-align: left;
            font-size: 14px;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            padding: 0 40px;
            margin-bottom: 40px;
            margin-top: 30px;
        }

        .footer-left{ 
            display: flex;
            grid-template-columns: repeat(2, 1fr);
            gap: 40px;} 
        
        
        .footer-right {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .footer-logo {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .footer-nav {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .footer-nav a {
            color: white;
            text-decoration: none;
        }

        .social-media {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .social-media p {
            margin-right: 10px;
        }

        .social-media a img {
            width: 25px;
            height: 25px;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.5);
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .footer-policy {
            display: flex;
            gap: 20px;
        }

        .footer-policy a {
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
<header>
    <div class="container">
      <nav>
        <ul>
          <li><a class="brand"><img alt src="../../img/logo.png" class="logo"></a></li>
          <li class="gap"><a></a></li>
          <li><a href="<?= BASEURL; ?>" id="beranda">Beranda</a></li>
          <li><a></a></li>
          <li><a></a></li>
          <li><a href="<?= BASEURL; ?>">Booking</a></li>
          <li><a></a></li>
          <li><a></a></li>
          <li><a href="#notif">Riwayat</a></li>
          <li><a></a></li>
          <li><a></a></li>
          <li><a href="#footer">Tentang kami</a></li>
          <li class="gap2"><a></a></li>
        </ul>
      </nav>
      <a href="<?= BASEURL; ?>/page/pageLogin/logout.php"><button class="login-btn">Log out</button></a>
    </div>
</header>
  <div class="gap3" id="beranda">
    <br><br><br>
  </div> 
    <div class="container-history">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Asal</th>
                    <th>Tujuan</th>
                    <th>Layanan</th>
                    <th>Tanggal</th>
                    <th>Jam</th>
                    <th>Penumpang</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <!-- Contoh data, ganti dengan data dinamis dari server -->
                <?php if (count($data) > 0): ?>
                    <?php foreach ($data as $d): ?>
                        <tr>
                            <td><?= $d['id'] ?></td>
                            <td><?= $d['asal'] ?></td>
                            <td><?= $d['tujuan'] ?></td>
                            <td><?= $d['layanan'] ?></td>
                            <td><?= date('d-m-Y', strtotime($d['tanggal'])) ?></td>
                            <td><?= $d['jam'] ?></td>
                            <td><?= $d['penumpang'] ?></td>
                            <td>Rp. <?= number_format($d['harga'], 2, ',', '.') ?></td>
                            <td><a href="<?= BASEURL.'/page/jadwal/ticketing/ticket.php?id='.$d['id']?>" class="download-button">Unduh Tiket</a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" style="text-align: center;">Tidak ada riwayat pemesanan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <br><br><br><br><br><br><br><br><br><br><br><br><br>
<!-- Footer -->
<footer>
  <div class="footer-content" id="footer">
    <div class="footer-left">
      <div class="footer-logo">ferigo</div>

      <nav class="footer-nav">
        <a href="#beranda">Beranda</a>
        <a href="#pemesanan">Pemesanan Tiket</a>
        <a href="#">Riwayat</a>
      </nav>
      <nav class="footer-nav">
        <a href="#pemesanan">Pesan Tiket</a>
        <a href="#testimonial">FAQs</a>
        <a href="#testimonial">Ulasan</a>
      </nav>
    </div>

    <div class="footer-right">
      <div class="social-media">
        <p>Temukan juga Ferigo di:</p>
        <a href=#><img src="../../img/email.png" alt="Email"></a>
        <a href="#"><img src="../../img/twitter.png" alt="Twitter"></a>
        <a href="#"><img src="../../img/instagram.png" alt="Instagram"></a>
      </div>
    </div>
  </div>
  </div>
  <div class="footer-bottom">
    <nav class="footer-policy">
      <a href="#">Syarat Kebijakan</a>
      <a href="#">Kebijakan Pribadi</a>
      <a href="#">Kebijakan Cookie</a>
    </nav>
    <p>© 2024 Ferigo. All Rights Reserved</p>
  </div>
</footer>
</body>
<script>
    document.querySelectorAll('nav a').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        
        // Cek apakah href adalah ID elemen di halaman yang sama
        if (href.startsWith('#')) {
            e.preventDefault(); // Mencegah perilaku default hanya untuk ID
            document.querySelector(href).scrollIntoView({
                behavior: 'smooth'
            });
        }
        // Jika bukan ID, biarkan tautan berfungsi seperti biasa
    });
  });
</script>
</html>