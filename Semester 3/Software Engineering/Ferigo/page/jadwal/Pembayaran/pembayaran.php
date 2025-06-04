<?php 
    session_start();
    require_once '../../../config.php';
    require_once '../../../functions/functions.php';

    if(!isset($_SESSION['booking'])){
        header('location: '.BASEURL);
    }
    // else {
    //     unset($_SESSION['booking']);
    // }

    if(isset($_POST['submit'])){
        $nickname = $_SESSION['nickname'];
        $asal = $_SESSION['asal'];
        $tujuan = $_SESSION['tujuan'];
        $layanan = $_SESSION['layanan'];
        $tanggal = $_SESSION['tanggal'];
        $jam = $_SESSION['jam'];
        $penumpang = 1;
        $harga = $_SESSION['harga'];

        // Menginput data ke tabel transaksi pemesanan
        $result = booking("INSERT INTO riwayat_pemesanan VALUES(NULL, '$nickname', '$asal', '$tujuan', '$layanan', '$tanggal', '$jam', '$penumpang', '$harga')");
        switch($result) {
            case true :
                $result = 'true';
            break;
            case false :
                $result = 'false';
            break;
        }
    }
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran E-Money</title>
    <link rel="shortcut icon" href="<?=BASEURL; ?>/img/icon.png" type="image/png">
    <style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap');

    body {
        font-family: 'Arial', sans-serif;
        background-image: url('../../../img/ulasan.png');
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

    .container-pembayaran {
        max-width: 500px;
        margin: 150px auto;
        padding: 20px;
        background-color: rgba(255, 255, 255, 0.5);
        -webkit-backdrop-filter: blur(10px);
        backdrop-filter: blur(10px);
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    h1 {
        text-align: center;
        color: #333;
    }

    .form-group {
        margin-bottom: 15px;
    }

    label {
        display: block;
        margin-bottom: 5px;
        color: black;
    }

    input,
    select {
        width: 100%;
        /* Mengurangi lebar untuk padding dan border */
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
        /* Memastikan padding dan border tidak menambah lebar */
        appearance: none;
        /* Hilangkan tampilan default browser */
        -webkit-appearance: none;
        /* Tambahkan untuk Safari */
        -moz-appearance: none;
        /* Tambahkan untuk Firefox */
    }

    input:focus,
    select:focus {
        border-color: #007bff;
        outline: none;
    }

    .btn {
        width: 100%;
        padding: 10px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }

    .btn:hover {
        background-color: #0056b3;
    }

    .message {
        margin-top: 20px;
        text-align: center;
        color: #28a745;
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
    </style>
</head>

<body>
    <header>
        <div class="container">
            <nav>
                <ul>
                    <li><a class="brand"><img alt src="../../../img/logo.png" class="logo"></a></li>
                    <li class="gap"><a></a></li>
                    <li><a href="#beranda" id="beranda">Beranda</a></li>
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
        <br>
    </div>
    <div class="container-pembayaran">
        <h1>Pembayaran Tiket</h1>
        <form action="" method="post" id="payment-form">
            <div class="form-group">
                <label>Pilih Metode Pembayaran</label>
                <select id="payment-method">
                    <option value="mandiri-e-money">Mandiri E-Money</option>
                    <option value="NULL" disabled>--</option>
                </select>
            </div>
            <div class="form-group">
                <label for="card-number">Nomor Rekening</label>
                <input type="text" id="card-number" pattern="[0-9]*" maxlength="15" minlength="10" required>
            </div>
            <button type="submit" class="btn" value="<?= $_SESSION['harga']  ?>" name="submit" id="price">Bayar Rp.
                <?= $_SESSION['harga']  ?></button>
        </form>
    </div>
    <br>
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
                    <a href=#><img src="../../../img/email.png" alt="Email"></a>
                    <a href="#"><img src="../../../img/twitter.png" alt="Twitter"></a>
                    <a href="#"><img src="../../../img/instagram.png" alt="Instagram"></a>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= BASEURL;?>/script.js"></script>
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
document.getElementById('card-number').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, ''); // Menghapus karakter non-angka
});
</script>
<?php if(isset($result)) : ?>
<?php if($result === 'true') : ?>
<script>
// Tampilkan pesan sukses menggunakan SweetAlert
Swal.fire({
    title: "Pembayaran berhasi!",
    text: "Cek riwayat untuk mengunduh e-ticket ",
    icon: "success"
}).then(() => {

    // Reset form
    document.getElementById('payment-form').reset();
    window.location.href = baseUrl;
});
</script>
<?php else : ?>
<script>
Swal.fire({
    icon: "error",
    title: "Pembayaran gagal!",
    text: "Pembayaran gagal dilakukan",
    confirmButtonText: "ok!",
});
</script>
<?php endif; ?>
<?php endif; ?>

</html>