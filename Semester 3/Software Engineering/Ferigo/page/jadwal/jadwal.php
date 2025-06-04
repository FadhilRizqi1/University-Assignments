<?php
    session_start();
    require_once '../../functions/functions.php';
    require_once '../../config.php';

    if(!isset($_SESSION['nickname'])){
        header('Location: '.BASEURL);
    }
    else {
    $nickname = $_SESSION['nickname'];
    $asal = $_SESSION['asal'];
    $tujuan = $_SESSION['tujuan'];
    $layanan = $_SESSION['layanan'];
    $tanggal = $_SESSION['tanggal'];
    $jam = $_SESSION['jam'];
    $penumpang = $_SESSION['penumpang'];
    $data = query("SELECT * FROM jadwal WHERE pelabuhan_asal = '$asal' AND pelabuhan_tujuan = '$tujuan' AND layanan = '$layanan' AND jadwal_masuk = '$tanggal' AND jam_masuk = '$jam' AND umur = '$penumpang'");
    $_SESSION['harga'] = $data[0]['harga'];
    // var_dump($data);
    }

    if(isset($_POST['submit'])) {
        $_SESSION['booking'] = true;
        header('Location: Pembayaran/pembayaran.php');
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Jadwal & Pesan Tiket Kapal</title>
    <link rel="shortcut icon" href="<?=BASEURL; ?>/img/icon.png" type="image/png">
    <style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap');

    body {
        font-family: 'Arial', sans-serif;
        background-image: url('../../img/ulasan.png');
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
        background-color: #f4f4f4;
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
        color: #000000;
    }

    nav ul li a.brand {
        img {
            width: 150px;
        }
    }

    .gap {
        width: 320px;
    }

    .gap2 {
        width: 100px;
    }

    .gap3 {
        margin-bottom: 60px;
    }

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

    .container-jadwal {
        max-width: 600px;
        margin: 0 auto;
        background-color: rgba(255, 255, 255, 0.5);
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        -webkit-backdrop-filter: blur(10px);
        backdrop-filter: blur(10px);
    }

    h1,
    h2 {
        text-align: center;
        color: #333;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .button {
        background-color: #369f9f;
        color: white;
        padding: 10px 15px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        border-radius: 5px;
        border: none;
        cursor: pointer;
        width: 100%;
        margin-top: 20px;
    }

    .return {
        background-color: black;
    }

    .button:hover {
        background-color: #369f9f;
        opacity: 40%;
    }

    .return:hover {
        background-color: black;
        opacity: 40%;
    }

    .ticket-details {
        margin-top: 20px;
    }

    .ticket-details p {
        margin: 8px 0;
    }

    .important-note {
        background-color: rgb(107, 207, 207);
        padding: 15px;
        border-radius: 5px;
        font-size: 14px;
        margin-top: 20px;
    }

    .message {
        padding: 15px;
        background-color: #f0f0f0;
        border-radius: 5px;
        margin-bottom: 20px;
        text-align: center;
    }

    footer {
        background-color: #369f9f;
        color: #f4f4f4;
        padding: 50px;
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

    .footer-left {
        display: flex;
        grid-template-columns: repeat(2, 1fr);
        gap: 40px;
    }


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

    select.pembayaran {
        width: 100%;
        /* Atur lebar penuh */
        padding: 10px;
        /* Spasi dalam */
        border: 1px solid #ccc;
        /* Warna border */
        border-radius: 8px;
        /* Sudut melingkar */
        background-color: #f9f9f9;
        /* Warna latar belakang */
        font-size: 16px;
        /* Ukuran font */
        color: #333;
        /* Warna teks */
        appearance: none;
        /* Hilangkan tampilan default browser */
        -webkit-appearance: none;
        /* Tambahkan untuk Safari */
        -moz-appearance: none;
        /* Tambahkan untuk Firefox */
    }

    /* Tambahkan ikon panah ke select */
    select.pembayaran::after {
        content: '▼';
        /* Karakter panah */
        position: absolute;
        right: 10px;
        pointer-events: none;
    }

    /* Styling untuk saat select difokuskan */
    select.pembayaran:focus {
        outline: none;
        /* Hilangkan outline default */
        border-color: #007BFF;
        /* Ganti warna border saat difokuskan */
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        /* Tambahkan efek bayangan */
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
                    <li><a href="beranda" id="beranda">Beranda</a></li>
                    <li><a></a></li>
                    <li><a></a></li>
                    <li><a href="#pemesanan">Booking</a></li>
                    <li><a></a></li>
                    <li><a></a></li>
                    <li><a href="#notif" id="notif">Riwayat</a></li>
                    <li><a></a></li>
                    <li><a></a></li>
                    <li><a href="#footer">Tentang kami</a></li>
                    <li class="gap2"><a></a></li>
                </ul>
            </nav>

            <a href="../../pageLogin/logout.php"><button class="login-btn">Log out</button></a>
        </div>
    </header>
    <div class="gap3" id="beranda">
        <br><br><br>
    </div>
    <div class="container-jadwal">
        <h1>Jadwal Tersedia</h1>
        <!-- <br><br>
        <p style="align:center">Informasi keberangkatan untuk memeriksa ketersediaan jadwal.</p> -->
        <!-- Menampilkan hasil pencarian jadwal -->
        <div class="ticket-details">
            <table>
                <?php foreach($data as $d) : ?>
                <tr>
                    <td>
                        <p><strong>Nama Kapal </strong></p>
                    </td>
                    <td>
                        <p>: KM Laut Biru</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><strong>Jenis Kapal</strong></p>
                    </td>
                    <td>
                        <p>: Feri Penumpang</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><strong>Tanggal Keberangkatan</strong></p>
                    </td>
                    <td>
                        <p>: <?= $d['jadwal_masuk'] ?></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><strong>Jam Keberangkatan</strong></p>
                    </td>
                    <td>
                        <p>: <?= $d['jam_masuk'] ?></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><strong>Pelabuhan Asal</strong></p>
                    </td>
                    <td>
                        <p>: <?= $d['pelabuhan_asal'] ?></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><strong>Pelabuhan Tujuan</strong></p>
                    </td>
                    <td>
                        <p>: <?= $d['pelabuhan_tujuan'] ?></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><strong>Jenis Layanan</strong></p>
                    </td>
                    <td>
                        <p>: <?= $d['layanan']?></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><strong>Harga Tiket</strong></p>
                    </td>
                    <td>
                        <p>: Rp. <?= $d['harga']?></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="departure-port">
                            <p><strong>Pilih Metode Pembayaran</strong></p>
                        </label>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <select id="pembayaran" class="pembayaran" name="asal" required>
                            <option value="OTS">E-money</option>
                            <option value="NULL" disabled>--</option>
                        </select>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>

            <!-- Opsi untuk memesan tiket -->
            <input type="hidden" name="ship_name" value="KM Laut Biru">
            <input type="hidden" name="departure_date" value="15 Oktober 2024">
            <input type="hidden" name="departure_time" value="08:30">
            <input type="hidden" name="departure_port" value="Pelabuhan Tanjung Perak">
            <input type="hidden" name="destination_port" value="Pelabuhan Gilimanuk">
            <form action="" method="post">
                <a href="Pembayaran/pembayaran.php"><button type="submit" class="button" name="submit">Pesan
                        Tiket</button></a>
            </form>
            <a href=""><button type="submit" class="button return">Kembali</button></a>
        </div>

        <!-- Pesan penting -->
        <div class="important-note">
            <p><strong>Catatan Penting:</strong> Harap tiba 30 menit sebelum waktu keberangkatan untuk keperluan
                check-in. Penumpang wajib membawa tiket dan identitas resmi saat boarding.</p>
        </div>
    </div>
    <br><br><br>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    var baseUrl = "<?= BASEURL; ?>"; // Mendefinisikan BASEURL dalam JavaScript
    // Pilih elemen dengan id 'beranda' dan 'riwayat'
    const elements = document.querySelectorAll('#beranda, #notif');

    // Tambahkan event listener untuk setiap elemen
    elements.forEach(element => {
        element.addEventListener('click', function() {
            let redirectUrl;
            if (this.id === 'beranda') {
                redirectUrl = baseUrl; // Ganti dengan URL untuk 'beranda'
            } else if (this.id === 'notif') {
                redirectUrl = baseUrl + '/page/riwayat/riwayat.php'; // Ganti dengan URL untuk 'riwayat'
            }
            Swal.fire({
                title: "Apakah anda yakin?",
                text: "Aktivitas tidak akan disimpan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yakin!",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to a specific page after confirmation
                    window.location.href = redirectUrl; // Ganti dengan URL yang diinginkan
                }
            });
        });
    });

    Swal.fire({
        title: "Jadwal ditemukan!",
        text: "Jadwal penyebrangan tersedia!",
        icon: "success"
    });

    document.querySelectorAll('nav a').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });
    </script>

</html>