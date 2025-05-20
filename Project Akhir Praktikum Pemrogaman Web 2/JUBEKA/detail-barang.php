<?php
session_start();
include 'auth/koneksi.php';
require_once 'functions.php';

$item_id = null;
$item = null;
$error_message = "";
$page_title = 'Detail Barang';
$item_seller_phone = null;
$item_seller_name = 'N/A';
$item_seller_location_city = 'Lokasi tidak detail';


if (isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT) && (int)$_GET['id'] > 0) {
    $item_id = (int)$_GET['id'];
    $item = get_barang_by_id($item_id);

    if (!$item) {
        $error_message = "Barang dengan ID #{$item_id} tidak ditemukan.";
    } else {
        $page_title = htmlspecialchars($item['nama_barang']);
        $item_seller_phone = $item['nomor_telepon_penjual'] ?? null; // Ambil dari tabel 'jual'
        $item_seller_name = $item['nama_pemosting'] ?? 'N/A'; // Nama user yang memposting
        
        if (!empty($item['alamat_penjual'])) { // Alamat dari tabel 'jual'
            $alamat_parts = explode(",", $item['alamat_penjual']);
            if (count($alamat_parts) >= 2) { // Coba ambil bagian sebelum koma terakhir sebagai kota/area utama
                 $last_part_index = count($alamat_parts) -1;
                 $second_last_part_index = count($alamat_parts) -2;
                 $item_seller_location_city = trim($alamat_parts[$second_last_part_index]) . ", " . trim($alamat_parts[$last_part_index]);
                 if (count($alamat_parts) >=3) {
                    $third_last_part_index = count($alamat_parts) -3;
                    $item_seller_location_city = trim($alamat_parts[$third_last_part_index]) . ", " .$item_seller_location_city;
                 }

            } elseif (!empty($alamat_parts)) {
                $item_seller_location_city = trim($alamat_parts[0]);
            }
        }
    }
} else {
    $error_message = "ID Barang tidak valid.";
}

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $page_title; ?> - JUBEKA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <style>
    :root {
        --bs-body-bg: #121212;
        --bs-body-color: #e0e0e0;
        --bs-secondary-color: #adb5bd;
        --bs-tertiary-bg: #1f1f1f;
        --bs-primary: #00aaff;
        --bs-success: #198754;
        --bs-info: #0dcaf0;
        --bs-border-color: #444;
        --bs-border-color-translucent: rgba(255, 255, 255, 0.15);
        --card-bg: #1f1f1f;
        --card-border-color: var(--bs-border-color);
        --link-color: #0dcaf0;
        --link-hover-color: #3dd5f3;
    }

    body {
        background-color: var(--bs-body-bg);
        color: var(--bs-body-color);
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    main {
        flex: 1;
        padding-top: 3rem;
        padding-bottom: 3rem;
    }

    .navbar {
        background-color: var(--bs-tertiary-bg) !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        border-bottom: 1px solid var(--bs-border-color);
    }

    .footer {
        background-color: var(--bs-tertiary-bg);
        color: var(--bs-secondary-color);
        padding: 3rem 0;
        margin-top: auto;
        border-top: 1px solid var(--bs-border-color);
    }

    .footer h5 {
        color: #fff;
        margin-bottom: 1rem;
    }

    .footer a {
        color: var(--bs-secondary-color);
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

    .item-image-large-container {
        background-color: #2a2a2a;
        border-radius: 0.5rem;
        padding: 1rem;
        border: 1px solid var(--card-border-color);
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 300px;
    }

    .item-image-large {
        width: 100%;
        max-width: 100%;
        height: auto;
        max-height: 550px;
        object-fit: contain;
        border-radius: 0.375rem;
    }

    .item-details-card {
        background-color: var(--card-bg);
        padding: 2rem;
        border-radius: 0.5rem;
        border: 1px solid var(--card-border-color);
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .item-details-card h2 {
        font-weight: 600;
        margin-bottom: 0.75rem;
        color: #fff;
    }

    .item-price {
        font-size: 1.8rem;
        font-weight: bold;
        color: var(--bs-primary);
        margin-bottom: 1rem;
    }

    .seller-info {
        font-size: 0.9rem;
        color: var(--bs-secondary-color);
        margin-bottom: 1.5rem;
    }

    .seller-info i {
        margin-right: 0.5rem;
    }

    .seller-info strong {
        color: #e0e0e0;
    }

    .item-category {
        margin-bottom: 1.5rem;
    }

    .item-category .badge {
        font-size: 0.9rem;
    }

    .item-description {
        color: var(--bs-secondary-color);
        line-height: 1.7;
        margin-bottom: 1.5rem;
        flex-grow: 1;
        font-size: 0.95rem;
    }

    .item-description h5 {
        color: #e0e0e0;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .action-buttons {
        margin-top: auto;
    }

    .alert-container {
        margin-bottom: 2rem;
    }

    #preloader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: var(--bs-body-bg);
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
        background-color: var(--bs-primary);
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

        25%,
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
                aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"> <a class="nav-link" href="index.php">Beranda</a> </li>
                    <li class="nav-item"> <a
                            class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'pembelian.php' || basename($_SERVER['PHP_SELF']) == 'detail-barang.php' ? 'active' : ''); ?>"
                            href="pembelian.php">Beli Barang</a> </li>
                    <li class="nav-item dropdown"> <a class="nav-link dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">Jual Barang</a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="penjualan.php">Form Jual Barang</a></li>
                            <?php if (isset($_SESSION['username'])) { ?> <li><a class="dropdown-item"
                                    href="daftar-barang.php">Barang Saya</a></li> <?php } ?>
                        </ul>
                    </li>
                    <li class="nav-item"> <a class="nav-link" href="about.php">Tentang Kami</a> </li>
                    <li class="nav-item"> <a class="nav-link" href="contact.php">Kontak</a> </li>
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

    <main class="container">
        <div class="alert-container">
            <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger text-center"><?php echo $error_message; ?></div>
            <?php endif; ?>
        </div>

        <?php if ($item): ?>
        <div class="row g-4 g-lg-5">
            <div class="col-lg-6 text-center">
                <div class="item-image-large-container">
                    <?php
                         $image_path = 'Assets/' . (!empty($item['gambar']) ? $item['gambar'] : 'placeholder.png');
                         $placeholder_path = 'Assets/placeholder.png';
                     ?>
                    <img src="<?php echo htmlspecialchars($image_path); ?>"
                        alt="<?php echo htmlspecialchars($item['nama_barang']); ?>"
                        onerror="this.onerror=null; this.src='<?php echo htmlspecialchars($placeholder_path); ?>';"
                        class="item-image-large img-fluid">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="item-details-card shadow-sm">
                    <h2><?php echo htmlspecialchars($item['nama_barang']); ?></h2>
                    <p class="item-price">Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?></p>

                    <div class="seller-info">
                        <span><i class="fas fa-user fa-fw text-info"></i> Penjual:
                            <strong><?php echo htmlspecialchars($item_seller_name); ?></strong></span>
                        <br><span><i class="fas fa-map-marker-alt fa-fw text-info"></i> Lokasi Penjual:
                            <?php echo htmlspecialchars($item_seller_location_city); ?></span>
                    </div>

                    <?php if(!empty($item['nama_kategori'])): ?>
                    <div class="item-category">
                        <span class="badge text-bg-secondary"><i class="fas fa-tag me-1"></i>
                            <?php echo htmlspecialchars($item['nama_kategori']); ?></span>
                    </div>
                    <?php endif; ?>

                    <h5 class="mt-3">Deskripsi Barang</h5>
                    <p class="item-description"><?php echo nl2br(htmlspecialchars($item['deskripsi'])); ?></p>

                    <div class="action-buttons mt-auto">
                        <?php
                        $whatsapp_message = "Halo " . htmlspecialchars($item_seller_name) . ", saya tertarik dengan barang Anda '" . htmlspecialchars($item['nama_barang']) . "' yang dijual di JUBEKA dengan harga Rp " . number_format($item['harga'], 0, ',', '.') . ". Apakah barang ini masih tersedia?";
                        $whatsapp_link = "#";
                        if (!empty($item_seller_phone)) {
                            $clean_phone = preg_replace('/[^0-9]/', '', $item_seller_phone);
                            if (substr($clean_phone, 0, 1) === '0') {
                                $clean_phone = '62' . substr($clean_phone, 1);
                            } elseif (substr($clean_phone, 0, 2) !== '62' && strlen($clean_phone) > 5) {
                                $clean_phone = '62' . $clean_phone;
                            }
                            $whatsapp_link = "https://wa.me/" . $clean_phone . "?text=" . urlencode($whatsapp_message);
                        }
                        ?>
                        <?php if (!empty($item_seller_phone)): ?>
                        <a href="<?php echo $whatsapp_link; ?>" target="_blank"
                            class="btn btn-success btn-lg w-100 mb-2"><i class="fab fa-whatsapp me-2"></i>Hubungi
                            Penjual</a>
                        <?php else: ?>
                        <button type="button" class="btn btn-success btn-lg w-100 mb-2" disabled><i
                                class="fab fa-whatsapp me-2"></i>Nomor Penjual Tidak Tersedia</button>
                        <?php endif; ?>
                        <a href="pembelian.php" class="btn btn-outline-secondary w-100"><i
                                class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Barang</a>
                    </div>
                </div>
            </div>
        </div>
        <?php elseif (empty($error_message)): ?>
        <div class="alert alert-warning text-center">Tidak ada detail barang untuk ditampilkan.</div>
        <?php endif; ?>
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
                    <p class="mb-0">© <?php echo date("Y", strtotime("today")); ?> JUBEKA. All rights reserved.</p>
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