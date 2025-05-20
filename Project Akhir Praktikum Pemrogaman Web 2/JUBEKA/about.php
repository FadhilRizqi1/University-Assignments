<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tentang Kami - JUBEKA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <style>
    body {
        background-color: #121212;
        color: #e0e0e0;
    }

    .navbar {
        background-color: #1f1f1f !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
    }

    .footer {
        background-color: #1f1f1f;
        color: #adb5bd;
        padding: 3rem 0;
        margin-top: 5rem;
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

    .sub-section-title {
        margin-top: 2.5rem;
        margin-bottom: 1.5rem;
        font-weight: 500;
        color: #eee;
    }


    .card {
        background-color: #1f1f1f;
        border: none;
        border-radius: 0.5rem;
    }

    .card-img-top {
        object-fit: cover;
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
    }

    .feature-icon {
        font-size: 2.5rem;
        color: #00aaff;
        margin-bottom: 1rem;
        display: inline-block;
        padding: 1rem;
        background-color: rgba(0, 170, 255, 0.1);
        border-radius: 50%;
        width: 70px;
        height: 70px;
        line-height: 40px;
    }

    .testimonial-card {
        background-color: #2a2a2a;
        border-left: 5px solid #00aaff;
        padding: 1.5rem;
        border-radius: 0.5rem;
    }

    .testimonial-card blockquote {
        font-style: italic;
        color: #ccc;
    }

    .testimonial-card footer {
        color: #aaa;
        font-size: 0.9em;
        margin-top: 0.5rem;
    }

    .rating .fa-star {
        color: #ffc107;
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
        <div class="container-fluid">
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
                    <li class="nav-item"> <a class="nav-link active" aria-current="page" href="about.php">Tentang
                            Kami</a> </li>
                    <li class="nav-item"> <a class="nav-link" href="contact.php">Kontak</a> </li>
                </ul>
                <form class="d-flex me-3" role="search" action="pembelian.php" method="GET">
                    <input class="form-control form-control-sm me-2 bg-dark text-white border-secondary" type="search"
                        name="q" placeholder="Cari barang..." aria-label="Search">
                    <button class="btn btn-outline-info btn-sm" type="submit">Cari</button>
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

    <section class="py-5 text-center"
        style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('Assets/bg_about.jpg') no-repeat center center; background-size: cover;">
        <div class="container">
            <img src="Assets/logo.png" alt="Logo JUBEKA" class="img-fluid rounded mb-3"
                style="max-height: 430px; height: auto;" />
            <h1 class="display-4 fw-bold text-white">Mengenal JUBEKA Lebih Dekat</h1>
            <p class="lead text-white-50 col-lg-8 mx-auto">Platform terpercaya Anda untuk menemukan dan menjual barang
                bekas berkualitas dengan mudah dan aman.</p>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <h2 class="sub-section-title">Siapa Kami?</h2>
                    <p class="text-white-50">
                        JUBEKA (Jual Beli Barang Bekas) lahir dari keinginan untuk menciptakan ruang digital yang aman
                        dan nyaman bagi masyarakat, khususnya di area Palembang dan sekitarnya, untuk memberikan
                        kehidupan kedua bagi barang-barang yang sudah tidak terpakai. Kami percaya bahwa setiap barang
                        memiliki nilai dan cerita, dan melalui JUBEKA, kami ingin menghubungkan penjual dan pembeli
                        dalam semangat keberlanjutan dan ekonomi berbagi.
                    </p>

                    <h3 class="sub-section-title fs-4">Visi Kami</h3>
                    <p class="text-white-50"><i class="fas fa-bullseye me-2 text-info"></i> Menjadi platform jual beli
                        barang bekas online terdepan dan paling terpercaya di Sumatera Selatan, yang memberdayakan
                        komunitas dan mendukung gaya hidup berkelanjutan.</p>

                    <h3 class="sub-section-title fs-4">Misi Kami</h3>
                    <ul class="list-unstyled text-white-50">
                        <li><i class="fas fa-check-circle me-2 text-info"></i> Menyediakan platform yang intuitif, aman,
                            dan mudah digunakan.</li>
                        <li><i class="fas fa-check-circle me-2 text-info"></i> Membangun komunitas penjual dan pembeli
                            yang aktif dan saling percaya.</li>
                        <li><i class="fas fa-check-circle me-2 text-info"></i> Mendorong praktik konsumsi yang lebih
                            bertanggung jawab dan ramah lingkungan.</li>
                        <li><i class="fas fa-check-circle me-2 text-info"></i> Terus berinovasi untuk meningkatkan
                            pengalaman pengguna.</li>
                    </ul>
                </div>
                <div class="col-lg-5 text-center">
                    <img src="Assets/bg3.jpg" alt="Ilustrasi Tim JUBEKA" class="img-fluid"
                        style="max-width: 90%; height: auto;" />
                </div>
            </div>

            <h2 class="text-center section-title mt-5">Nilai-Nilai Kami</h2>
            <div class="row text-center g-4">
                <div class="col-md-6 col-lg-3">
                    <span class="feature-icon"><i class="fas fa-shield-alt"></i></span>
                    <h5 class="text-white mt-2">Kepercayaan</h5>
                    <p class="text-white-50 small">Membangun transaksi yang jujur dan transparan antar pengguna.</p>
                </div>
                <div class="col-md-6 col-lg-3">
                    <span class="feature-icon"><i class="fas fa-rocket"></i></span>
                    <h5 class="text-white mt-2">Kemudahan</h5>
                    <p class="text-white-50 small">Menyediakan alur proses jual beli yang simpel dan cepat.</p>
                </div>
                <div class="col-md-6 col-lg-3">
                    <span class="feature-icon"><i class="fas fa-leaf"></i></span>
                    <h5 class="text-white mt-2">Keberlanjutan</h5>
                    <p class="text-white-50 small">Mendukung pengurangan limbah dengan memperpanjang usia pakai barang.
                    </p>
                </div>
                <div class="col-md-6 col-lg-3">
                    <span class="feature-icon"><i class="fas fa-users"></i></span>
                    <h5 class="text-white mt-2">Komunitas</h5>
                    <p class="text-white-50 small">Menjadi wadah interaksi positif bagi para pecinta barang bekas.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-dark">
        <div class="container">
            <h2 class="text-center section-title">Mengapa Memilih JUBEKA?</h2>
            <div class="row text-center g-4">
                <div class="col-lg-4">
                    <span class="feature-icon"><i class="fas fa-search-dollar"></i></span>
                    <h5 class="text-white mt-2">Hemat & Untung</h5>
                    <p class="text-white-50 small">Temukan barang berkualitas dengan harga miring, atau dapatkan
                        penghasilan tambahan dari barang tak terpakai.</p>
                </div>
                <div class="col-lg-4">
                    <span class="feature-icon"><i class="fas fa-check-double"></i></span>
                    <h5 class="text-white mt-2">Pilihan Beragam</h5>
                    <p class="text-white-50 small">Dari elektronik, fashion, hingga hobi, temukan berbagai kategori
                        barang sesuai kebutuhan Anda.</p>
                </div>
                <div class="col-lg-4">
                    <span class="feature-icon"><i class="fas fa-map-marker-alt"></i></span>
                    <h5 class="text-white mt-2">Fokus Lokal</h5>
                    <p class="text-white-50 small">Memudahkan transaksi COD (Cash On Delivery) atau pertemuan langsung
                        di area Palembang dan sekitarnya.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <h2 class="text-center section-title">Tim di Balik Layar</h2>
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="card text-white h-100">
                        <img src="Assets/S1.png" class="card-img-top" alt="Developer Ahmad Fadhil Rizqi"
                            style="height: 340px; object-fit: cover;" />
                        <div class="card-body text-center d-flex flex-column">
                            <h5 class="card-title mb-1">Ahmad Fadhil Rizqi</h5>
                            <p class="card-text text-white-50 small">Full Stack Developer</p>
                            <p class="card-text text-white-50 mt-2 flex-grow-1">
                                Merancang, mengembangkan, dan memastikan JUBEKA berjalan lancar untuk Anda.
                            </p>
                            <div class="mt-3">
                                <a href="https://www.linkedin.com/in/ahmad-fadhil-rizqi/" class="text-white-50 me-2"
                                    target="_blank" rel="noopener noreferrer" title="LinkedIn"><i
                                        class="fab fa-linkedin fa-lg"></i></a>
                                <a href="https://github.com/FadhilRizqi1" class="text-white-50 me-2" target="_blank"
                                    rel="noopener noreferrer" title="GitHub"><i class="fab fa-github fa-lg"></i></a>
                                <a href="#" class="text-white-50" target="_blank" rel="noopener noreferrer"
                                    title="Portfolio"><i class="fas fa-globe fa-lg"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-dark">
        <div class="container">
            <h2 class="text-center section-title">Apa Kata Pengguna Kami?</h2>
            <p class="text-center text-white-50 mb-4">(Beberapa contoh testimoni)</p>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="testimonial-card h-100">
                        <div class="rating mb-2">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                        </div>
                        <blockquote>
                            "Platformnya mudah banget dipakai! Saya berhasil jual lemari lama saya dalam 2 hari.
                            Recommended!"
                        </blockquote>
                        <footer>- Iqbal Rahman, Pengguna JUBEKA</footer>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="testimonial-card h-100">
                        <div class="rating mb-2">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <blockquote>
                            "Senang banget nemu JUBEKA. Bisa cari barang unik dan murah di sekitar Palembang. Udah dapat
                            beberapa koleksi bagus dari sini."
                        </blockquote>
                        <footer>- Gibral Raka, Pengguna JUBEKA</footer>
                    </div>
                </div>
            </div>
        </div>
    </section>


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
                    <div class="social-icons">
                        <a href="https://www.facebook.com/" target="_blank" rel="noopener noreferrer" class="me-2"><img
                                src="Assets/facebook.png" width="30" alt="Facebook" /></a>
                        <a href="https://twitter.com/" target="_blank" rel="noopener noreferrer" class="me-2"><img
                                src="Assets/twitter.png" width="30" alt="Twitter" /></a>
                        <a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer" class="me-2"><img
                                src="Assets/instagram.png" width="30" alt="Instagram" /></a>
                    </div>
                </div>
            </div>
            <hr class="my-4" style="border-color: rgba(255,255,255,0.1);">
            <div class="row">
                <div class="col-md-12 text-center">
                    <p class="mb-0">&copy; <?php echo date("Y"); ?> JUBEKA. All rights reserved.</p>
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