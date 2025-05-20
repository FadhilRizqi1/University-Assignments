<?php
session_start();
include 'auth/koneksi.php';

$form_message = "";
$nama_value = '';
$email_value = '';
$subjek_value = '';
$pesan_value = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subjek = trim($_POST['subjek'] ?? '');
    $pesan = trim($_POST['pesan'] ?? '');

    $nama_value = $nama;
    $email_value = $email;
    $subjek_value = $subjek;
    $pesan_value = $pesan;

    $error_internal = "";

    if (empty($nama) || empty($email) || empty($subjek) || empty($pesan)) {
        $form_message = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                            Semua field wajib diisi!
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                         </div>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $form_message = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                            Format email tidak valid!
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                         </div>";
    } else {
        global $conn;
        $stmt = $conn->prepare("INSERT INTO contact_messages (nama, email, subjek, pesan) VALUES (?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("ssss", $nama, $email, $subjek, $pesan);
            if ($stmt->execute()) {
                $form_message = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                    Pesan Anda telah terkirim! Terima kasih telah menghubungi kami.
                                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                 </div>";
                $nama_value = '';
                $email_value = '';
                $subjek_value = '';
                $pesan_value = '';
            } else {
                $error_internal = "Gagal menyimpan pesan ke database. Error: " . htmlspecialchars($stmt->error);
            }
            $stmt->close();
        } else {
             $error_internal = "Gagal mempersiapkan statement database. Error: " . htmlspecialchars($conn->error);
        }

        if (!empty($error_internal)) {
            error_log("Contact form submission error: " . $error_internal);
            $form_message = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                                Terjadi kesalahan internal. Silakan coba lagi nanti.
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                             </div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Hubungi Kami - JUBEKA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <style>
    body {
        background-color: #121212;
        color: #e0e0e0;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    main {
        flex: 1;
    }

    .navbar {
        background-color: #1f1f1f !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        border-bottom: 1px solid #444;
    }

    .footer {
        background-color: #1f1f1f;
        color: #adb5bd;
        padding: 3rem 0;
        margin-top: auto;
        border-top: 1px solid #444;
    }

    .footer h5 {
        color: #fff;
        margin-bottom: 1rem;
    }

    .footer a {
        color: #adb5bd;
        text-decoration: none;
    }

    .footer a:hover {
        color: #fff;
        text-decoration: underline;
    }

    .footer .social-icons img {
        opacity: 0.7;
        transition: opacity .2s ease;
    }

    .footer .social-icons img:hover {
        opacity: 1;
    }

    .section-title {
        margin-bottom: 3rem;
        font-weight: 600;
        color: #fff;
    }

    .contact-header {
        padding: 4rem 0;
        background: linear-gradient(rgba(18, 18, 18, 0.7), rgba(18, 18, 18, 0.8)), url('Assets/contact_bg.jpg') no-repeat center center;
        background-size: cover;
        color: #fff;
        border-bottom: 1px solid #444;
    }

    .contact-info-box {
        background-color: #1f1f1f;
        padding: 2rem;
        border-radius: 0.5rem;
        height: 100%;
        border: 1px solid #444;
    }

    .contact-info-box .info-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 1.5rem;
        color: #adb5bd;
    }

    .contact-info-box .info-item i {
        font-size: 1.5rem;
        color: #00aaff;
        margin-right: 1rem;
        width: 30px;
        text-align: center;
    }

    .contact-info-box .info-item p {
        margin-bottom: 0;
        flex: 1;
    }

    .contact-info-box .info-item a {
        color: #0dcaf0;
        text-decoration: none;
    }

    .contact-info-box .info-item a:hover {
        text-decoration: underline;
    }

    .map-container iframe {
        border-radius: 0.5rem;
        height: 100%;
        min-height: 350px;
        width: 100%;
        border: 1px solid #444;
    }

    .contact-form-section {
        background-color: #1f1f1f;
        padding: 3rem 0;
        border: 1px solid #444;
        border-radius: 0.5rem;
    }

    .contact-form label {
        color: #adb5bd;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .contact-form .form-control,
    .contact-form textarea {
        background-color: #2a2a2a;
        border: 1px solid #444;
        color: #fff;
        padding: 0.75rem 1rem;
    }

    .contact-form textarea {
        min-height: 120px;
    }

    .contact-form .form-control:focus,
    .contact-form textarea:focus {
        background-color: #333;
        border-color: #00aaff;
        box-shadow: 0 0 0 0.25rem rgba(0, 170, 255, 0.25);
        color: #fff;
    }

    .contact-form .form-control::placeholder,
    .contact-form textarea::placeholder {
        color: #6c757d;
    }

    .contact-form .btn-submit {
        background-color: #00aaff;
        border-color: #00aaff;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
    }

    .contact-form .btn-submit:hover {
        background-color: #0cbfff;
        border-color: #0cbfff;
    }

    .alert .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    #preloader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: #121212;
        z-index: 9999;
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 1;
        visibility: visible;
        transition: opacity 1s ease-out, visibility 1s ease-out;
    }

    #preloader.hidden {
        opacity: 0;
        visibility: hidden;
    }

    .box-loader {
        width: 50px;
        height: 60px;
        position: relative;
    }

    .box-lid {
        width: 50px;
        height: 10px;
        background-color: #00aaff;
        position: absolute;
        top: 0;
        left: 0;
        border-radius: 2px 2px 0 0;
        transform-origin: bottom left;
        animation: lid-open 3s ease-in-out infinite;
    }

    .box-container {
        width: 50px;
        height: 50px;
        background-color: #555;
        position: absolute;
        bottom: 0;
        left: 0;
        border-radius: 0 0 3px 3px;
    }

    @keyframes lid-open {

        0%,
        100% {
            transform: rotateX(0deg);
        }

        25% {
            transform: rotateX(-90deg);
        }

        75% {
            transform: rotateX(-90deg);
        }
    }
    </style>
</head>

<body>
    <div id="preloader">
        <div class="box-loader">
            <div class="box-lid"></div>
            <div class="box-container"></div>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">JUBEKA</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"> <a class="nav-link" href="index.php">Beranda</a> </li>
                    <li class="nav-item"> <a class="nav-link" href="pembelian.php">Beli Barang</a> </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">Jual Barang</a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="penjualan.php">Form Jual Barang</a></li>
                            <?php if (isset($_SESSION['username'])) { ?>
                            <li><a class="dropdown-item" href="daftar-barang.php">Barang Saya</a></li>
                            <?php } ?>
                        </ul>
                    </li>
                    <li class="nav-item"> <a class="nav-link" href="about.php">Tentang Kami</a> </li>
                    <li class="nav-item"> <a class="nav-link active" aria-current="page" href="contact.php">Kontak</a>
                    </li>
                </ul>
                <form class="d-flex me-lg-2 mb-2 mb-lg-0" role="search" action="pembelian.php" method="GET">
                    <input class="form-control form-control-sm me-2 bg-dark text-white border-secondary" type="search"
                        name="q" placeholder="Cari barang..." aria-label="Search">
                    <button class="btn btn-outline-info btn-sm" type="submit"><i class="fas fa-search"></i></button>
                </form>
                <div class="d-flex align-items-center">
                    <?php if (isset($_SESSION['username']) && $_SESSION['username'] !== 'Admin') { ?>
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white-50" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-1"></i> Halo,
                            <?php echo htmlspecialchars($_SESSION['username']); ?>!
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end">
                            <li><a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'profil.php' ? 'active' : ''); ?>"
                                    href="profil.php"><i class="fas fa-user-edit me-2"></i>Profil Saya</a></li>
                            <li><a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'daftar-barang.php' ? 'active' : ''); ?>"
                                    href="daftar-barang.php"><i class="fas fa-box me-2"></i>Barang Saya</a></li>
                            <li>
                                <hr class="dropdown-divider border-secondary">
                            </li>
                            <li><a class="dropdown-item" href="auth/logout.php"><i
                                        class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </div>
                    <?php } elseif (isset($_SESSION['admin_login']) && $_SESSION['admin_login'] === true && isset($_SESSION['username']) && $_SESSION['username'] === 'Admin') { ?>
                    <a href="admin/dashboard.php" class="btn btn-outline-light btn-sm me-2">Dashboard Admin</a>
                    <a href="auth/logout.php" class="btn btn-outline-danger btn-sm">Logout Admin</a>
                    <?php } else { ?>
                    <a href="auth/login.php" class="btn btn-outline-primary btn-sm me-2">Login</a>
                    <a href="auth/signup.php" class="btn btn-primary btn-sm">Sign up</a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </nav>
    <main>
        <section class="contact-header text-center">
            <div class="container">
                <h1 class="display-4 fw-bold">Hubungi Kami</h1>
                <p class="lead text-white-50 col-lg-8 mx-auto">Punya pertanyaan, saran, atau butuh bantuan? Jangan ragu
                    untuk menghubungi tim JUBEKA.</p>
            </div>
        </section>

        <section class="py-5">
            <div class="container">
                <div class="row g-4 g-lg-5">
                    <div class="col-lg-5 d-flex align-items-stretch">
                        <div class="contact-info-box w-100">
                            <h3 class="text-white mb-4">Informasi Kontak</h3>
                            <div class="info-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <p>Jl. Raya Indralaya - Prabumulih Km. 12,5,<br>Indralaya, Ogan Ilir, Sumatera Selatan
                                </p>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-envelope"></i>
                                <p><a href="mailto:jubeka@gmail.com">jubeka@gmail.com</a></p>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-phone"></i>
                                <p><a href="tel:+6281234567890">0812-3456-7890</a></p>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-clock"></i>
                                <p>Senin - Jumat: 09:00 - 17:00 WIB</p>
                            </div>
                            <hr style="border-color: rgba(255,255,255,0.1);">
                            <h4 class="text-white fs-5 mt-4 mb-3">Terhubung di Media Sosial</h4>
                            <div class="social-icons">
                                <a href="https://www.facebook.com/" target="_blank" rel="noopener noreferrer"
                                    class="me-2"><img src="Assets/facebook.png" width="30" alt="Facebook" /></a>
                                <a href="https://twitter.com/" target="_blank" rel="noopener noreferrer"
                                    class="me-2"><img src="Assets/twitter.png" width="30" alt="Twitter" /></a>
                                <a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer"
                                    class="me-2"><img src="Assets/instagram.png" width="30" alt="Instagram" /></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-7 d-flex align-items-stretch">
                        <div class="map-container w-100">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3984.307619718577!2d104.65308801475659!3d-3.222009997697999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e3b9f6e9b09e7cf%3A0x30158709f589ce7d!2sUniversitas%20Sriwijaya%20Kampus%20Indralaya!5e0!3m2!1sid!2sid!4v1621327143215!5m2!1sid!2sid"
                                allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5 contact-form-section mt-4 mb-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 mx-auto">
                        <h2 class="text-center section-title">Kirim Pesan Langsung</h2>
                        <p class="text-center text-white-50 mb-4">Isi formulir di bawah ini dan kami akan segera
                            merespons
                            Anda.</p>

                        <?php echo $form_message; ?>

                        <form action="contact.php" method="POST" class="contact-form">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="nama" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="nama" name="nama"
                                        placeholder="Masukkan nama Anda" required
                                        value="<?php echo htmlspecialchars($nama_value); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Alamat Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="contoh@email.com" required
                                        value="<?php echo htmlspecialchars($email_value); ?>">
                                </div>
                                <div class="col-12">
                                    <label for="subjek" class="form-label">Subjek Pesan</label>
                                    <input type="text" class="form-control" id="subjek" name="subjek"
                                        placeholder="Tuliskan subjek pesan Anda" required
                                        value="<?php echo htmlspecialchars($subjek_value); ?>">
                                </div>
                                <div class="col-12">
                                    <label for="pesan" class="form-label">Pesan Anda</label>
                                    <textarea class="form-control" id="pesan" name="pesan" rows="5"
                                        placeholder="Tuliskan pertanyaan atau pesan Anda di sini..."
                                        required><?php echo htmlspecialchars($pesan_value); ?></textarea>
                                </div>
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-submit btn-lg">Kirim Pesan <i
                                            class="fas fa-paper-plane ms-2"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="text-uppercase">JUBEKA</h5>
                    <p>Platform online terpercaya untuk transaksi jual beli barang bekas yang mudah dan aman.</p>
                </div>
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="text-uppercase">Hubungi Kami</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-map-marker-alt me-2"></i> Jl. Raya Indralaya - Prabumulih Km. 12,5,
                            Indralaya, Ogan Ilir, Sumatera Selatan</li>
                        <li><i class="fas fa-envelope me-2"></i> <a href="mailto:jubeka@gmail.com">jubeka@gmail.com</a>
                        </li>
                        <li><i class="fas fa-phone me-2"></i> 0812-3456-7890</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5 class="text-uppercase">Ikuti Kami</h5>
                    <div class="social-icons"> <a href="https://www.facebook.com/" target="_blank"
                            rel="noopener noreferrer" class="me-2"><img src="Assets/facebook.png" width="30"
                                alt="Facebook" /></a> <a href="https://twitter.com/" target="_blank"
                            rel="noopener noreferrer" class="me-2"><img src="Assets/twitter.png" width="30"
                                alt="Twitter" /></a> <a href="https://www.instagram.com/" target="_blank"
                            rel="noopener noreferrer" class="me-2"><img src="Assets/instagram.png" width="30"
                                alt="Instagram" /></a> </div>
                </div>
            </div>
            <hr class="my-4" style="border-color: rgba(255,255,255,0.1);">
            <div class="row">
                <div class="col-md-12 text-center">
                    <p class="mb-0">© <?php echo date("Y"); ?> JUBEKA. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script>
    window.addEventListener('load', function() {
        const preloader = document.getElementById('preloader');
        if (preloader) {
            preloader.classList.add('hidden');
        }
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>