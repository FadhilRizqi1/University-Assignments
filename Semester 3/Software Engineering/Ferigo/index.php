<?php 
    session_start();
    require 'functions/functions.php';
    require_once 'config.php';
    if(!isset($_SESSION["login"])){
      header('Location: '.BASEURL.'/page/pagelogin/login.php');
      exit;
    }
    // if(isset($_SESSION['nickname'])){
    //   $sucsses = true;
    // }

    if(isset($_POST["cari"])){
      $CariJadwal = schedule($_POST);
      if($CariJadwal === 'gagal' ){
        $JadwalTidakDitemukan = true;
      }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FeriGo</title>
    <link rel="stylesheet" href="style.css" />
    <link rel="shortcut icon" href="img/icon.png" type="image/png">
</head>

<body>
    <!-- Navbar -->
    <header>
        <div class="container">
            <nav>
                <ul>
                    <li><a class="brand"><img alt src="img/logo.png" class="logo"></a></li>
                    <li class="gap"><a></a></li>
                    <li><a href="#beranda">Beranda</a></li>
                    <li><a></a></li>
                    <li><a></a></li>
                    <li><a href="#pemesanan">Booking</a></li>
                    <li><a></a></li>
                    <li><a></a></li>
                    <li><a href="page/riwayat/riwayat.php">Riwayat</a></li>
                    <li><a></a></li>
                    <li><a></a></li>
                    <li><a href="#footer">Tentang kami</a></li>
                    <li class="gap2"><a></a></li>
                </ul>
            </nav>

            <a href="page/pageLogin/logout.php"><button class="login-btn">Log out</button></a>
        </div>
    </header>
    <div class="gap3" id="beranda">
        <br>
    </div>

    <!-- Home Section -->
    <section class="home">
        <div class="home-content">
            <h1>Jelajahi Pesona Nusantara di FeriGo</h1>
            <p>Tempat di mana keajaiban lautan ditemukan</p>
            <a href="#pemesanan"><button class="btn">Pesan Tiket →</button></a>
        </div>
    </section>

    <!-- Why FeriGo Section -->
    <section class="why-ferigo">
        <h2>Mengapa FeriGo?</h2>
        <p>Ciptakan pengalaman seru dengan keunggulan dari layanan kami</p>
        <div class="features">
            <div class="feature">
                <img src="img/ceklis.png" alt="Kemudahan Akses" />
                <h3>Kemudahan Akses</h3>
                <p>Bisa dilakukan secara online melalui situs web resmi.</p>
            </div>
            <div class="feature">
                <img src="img/transaksi.png" alt="Transaksi Cepat & Aman" />
                <h3>Transaksi Cepat & Aman</h3>
                <p>Pembayaran bisa dilakukan lewat e-wallet dan bank transfer.</p>
            </div>
            <div class="feature">
                <img src="img/tiket.png" alt="Tiket Berupa Elektronik" />
                <h3>Tiket Berupa Elektronik</h3>
                <p>
                    Tiket berbentuk digital, memudahkan proses check-in di pelabuhan.
                </p>
            </div>
            <div class="feature">
                <img src="img/jangkauan.png" alt="Jangkauan Luas" />
                <h3>Jangkauan Luas</h3>
                <p>Melayani lebih dari 30 pelabuhan di Indonesia.</p>
            </div>
        </div>
    </section>

    <!-- Booking Section -->
    <section class="booking" id="pemesanan">
        <h2>Pesan Tiket</h2>
        <p>Atur tujuan dan jadwal perjalanan mu disini</p>
        <form action="" method="post">
            <div class="booking-form1">
                <div class="form-group">
                    <label for="departure-port">Pelabuhan Asal</label>
                    <select id="departure-port" name="asal" required>
                        <option value="Bakauheni, Lampung">Bakauheni, Lampung</option>
                        <option value="Merak, Banten">Merak, Banten</option>
                    </select>
                </div>

                <div class="form-group">
                    <img src="img/reverse.png" />
                </div>

                <div class="form-group">
                    <label for="destination-port">Pelabuhan Tujuan</label>
                    <select id="destination-port" name="tujuan" required>
                        <option value="Merak, Banten">Merak, Banten</option>
                        <option value="Bakauheni, Lampung">Bakauheni, Lampung</option>
                    </select>
                </div>
            </div>

            <div class="booking-form">
                <div class="form-group">
                    <label for="service">Layanan</label>
                    <select id="service" name="service" required>
                        <option>Reguler</option>
                        <option>Eksekutif</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="passenger-type">Jenis Pengguna Jasa</label>
                    <input type="text" id="passenger-type" disabled placeholder="Tidak berkendara">
                </div>
                <div class="form-group">
                    <label for="check-in">Jadwal Masuk Pelabuhan (Check-in)</label>
                    <input type="date" id="check-in" name="tanggal" required />
                </div>
                <div class="form-group">
                    <label for="check-in-time">Jam Masuk Pelabuhan</label>
                    <select id="check-in-time" name="jam">
                        <option value="7:00">7:00</option>
                        <option value="12:00">12:00</option>
                        <option value="20:00">20:00</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="passenger-count">Penumpang</label>
                    <select id="passenger-count" name="penumpang">
                        <option value="Di atas 6 tahun">Di atas 6 tahun</option>
                        <option value="Di bawah 2 tahun">Di bawah 2 tahun</option>
                    </select>
                </div>
                <div class="form-submit">
                    <button type="submit" class="submit" name="cari">Cari Jadwal</button>
                </div>
            </div>
        </form>
    </section>

    <!-- Bagian Ticket Order -->
    <section class="ticket-order">
        <div class="ticket-info">
            <h1>Pemesanan Tiket</h1>
            <p>Klik tombol di bawah untuk memastikan dan memantau tiket kapal yang kamu pesan agar perjalananmu menjadi
                lebih
                aman dan efisien hingga di lokasi tujuan.</p>
            <a href="#pemesanan"> <button class="submit2">Cek Tiket →</button></a>
        </div>
        <div class="ticket-img">
            <img src="img/kapal.png" alt="Gambar Kapal" />
        </div>
    </section>

    <!-- Bagian Testimonial -->
    <section class="testimonial-section" id="testimonial">
        <div class="testi-text">
            <h2>Ulasan</h2>
            <p>Intip cerita seru dari mereka yang sudah mencoba layanan kami.</p>
        </div>

        <div class="testimonials">
            <div class="testimonial">
                <img src="img/evan.png" alt="Evan B.">
                <p><strong>Evan B. – Jakarta</strong></p>
                <p>"Web ini bantu banget! Selain karena mudah dipake, pesan tiket kapal juga jadi ga ribet!"</p>
            </div>
            <div class="testimonial">
                <img src="img/maria.png" alt="Maria Christine">
                <p><strong>Maria Christine – Surabaya</strong></p>
                <p>"Ini ngebantu banget plis, pokoknya kalo mau pesan tiket kapal, buka web ini aja langsung!!!"</p>
            </div>
            <div class="testimonial">
                <img src="img/sherina.png" alt="Shenina R.">
                <p><strong>Shenina R. – Pekanbaru</strong></p>
                <p>"Very helpful buat pesan tiket kapal jadi lebih efisien. Highly recommended banget, sih."</p>
            </div>
        </div>
    </section>
    <script src="script.js"></script>
</body>

<!-- Footer -->
<footer>
    <div class="footer-content" id="footer">
        <div class="footer-left">
            <div class="footer-logo">ferigo</div>

            <nav class="footer-nav">
                <a href="#beranda">Beranda</a>
                <a href="#pemesanan">Pemesanan Tiket</a>
                <a href="page/riwayat/riwayat.php">Riwayat</a>
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
                <a href=#><img src="img/email.png" alt="Email"></a>
                <a href="https://x.com/fadhilrizqi2006"><img src="img/twitter.png" alt="Twitter"></a>
                <a href="https://www.instagram.com/fadhilrizqi1/"><img src="img/instagram.png" alt="Instagram"></a>
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
<script src="script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if(isset($JadwalTidakDitemukan)) : ?>
<script>
Swal.fire({
    icon: "error",
    title: "Jadwal tidak ditemukan!",
    text: "Jadwal yang anda pilih tidak tersedia",
    confirmButtonText: "Pilih ulang!",
});
</script>
<?php endif; ?>

</html>