<?php
session_start();
include 'auth/koneksi.php';
require 'functions.php';

define('ITEMS_PER_PAGE', 12);
define('ITEMS_TO_LOAD', 8);

$search = isset($_GET['q']) ? trim($_GET['q']) : "";
$kategori_filter_id = isset($_GET['kategori']) ? (int)$_GET['kategori'] : null;

$barang_result = tampil_semua_barang($search, $kategori_filter_id);
$kategori_list = get_all_kategori();

if ($barang_result === false) { die("Gagal mengambil data barang."); }
$item_count = $barang_result ? $barang_result->num_rows : 0;

$page_title = "Beli Barang";
if ($search != "") { $page_title = "Hasil Pencarian: " . htmlspecialchars($search); }
elseif ($kategori_filter_id !== null) {
     $kategori_ditemukan = false;
     foreach ($kategori_list as $kat) { if ($kat['id'] == $kategori_filter_id) { $page_title = "Kategori: " . htmlspecialchars($kat['nama_kategori']); $kategori_ditemukan = true; break; } }
     if (!$kategori_ditemukan) { $page_title = "Kategori Tidak Ditemukan"; }
}

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        --bs-info: #0dcaf0;
        --bs-light: #f8f9fa;
        --bs-dark: #212529;
        --bs-border-color: #444;
        --bs-border-color-translucent: rgba(255, 255, 255, 0.1);
        --card-bg: #1f1f1f;
        --card-border-color: var(--bs-border-color);
        --link-color: #0dcaf0;
        --link-hover-color: #3dd5f3;
        --card-img-height: 180px;
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

    .page-header {
        padding: 3rem 0;
        background-color: var(--bs-tertiary-bg);
        margin-bottom: 3rem;
        border-bottom: 1px solid var(--bs-border-color);
    }

    .page-header .lead {
        color: var(--bs-secondary-color);
    }

    .filter-section {
        background-color: var(--card-bg);
        padding: 1.5rem;
        border-radius: 0.75rem;
        margin-bottom: 2.5rem;
        border: 1px solid var(--card-border-color);
    }

    .filter-section .form-label {
        font-size: 0.9em;
        margin-bottom: 0.3rem;
    }

    .filter-section .form-select,
    .filter-section .form-control {
        background-color: #2a2a2a;
        border-color: var(--bs-border-color);
        color: #fff;
        font-size: 0.9rem;
    }

    .filter-section .form-select:focus,
    .filter-section .form-control:focus {
        background-color: #333;
        border-color: var(--bs-primary);
        box-shadow: 0 0 0 0.25rem rgba(0, 170, 255, .25);
    }

    .product-card {
        background-color: var(--card-bg);
        border: 1px solid var(--card-border-color);
        border-radius: 0.75rem;
        transition: all .3s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
        overflow: hidden;
        position: relative;
    }

    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3);
        border-color: var(--bs-primary);
    }

    .product-card .img-link {
        display: block;
        text-decoration: none;
        overflow: hidden;
        background-color: #2a2a2a;
        border-bottom: 1px solid var(--card-border-color);
        aspect-ratio: 4 / 3;
    }

    .product-card .card-img-top {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .3s ease;
    }

    .product-card:hover .card-img-top {
        transform: scale(1.05);
    }

    .product-card .card-body {
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        padding: 1rem;
    }

    /* Kembalikan padding standar */
    .product-card .price {
        font-weight: 700;
        color: var(--bs-primary);
        font-size: 1.2rem;
        margin-bottom: 0.5rem;
    }

    .product-card .card-title {
        color: #fff;
        margin-bottom: 0.4rem;
        font-size: 1rem;
        font-weight: 500;
        line-height: 1.4;
        height: 2.8em;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .product-card .card-title a {
        color: inherit;
        text-decoration: none;
    }

    .product-card .card-title a:hover {
        color: var(--link-color);
        text-decoration: underline;
    }

    .product-card .card-date {
        font-size: 0.75em;
        color: var(--bs-secondary-color);
        margin-bottom: 1rem;
        margin-top: 0;
        flex-grow: 1;
    }

    .product-card .category-badge {
        font-size: 0.7em;
        font-weight: 600;
        position: absolute;
        top: 0.6rem;
        left: 0.6rem;
        z-index: 1;
        padding: 0.35em 0.7em;
        background-color: rgba(0, 0, 0, 0.6) !important;
        backdrop-filter: blur(2px);
    }

    .product-card .action-buttons {
        margin-top: auto;
    }

    .product-card .action-buttons .btn {
        font-size: 0.85rem;
        padding: 0.4rem 0.8rem;
    }

    .search-info {
        background-color: var(--card-bg);
        padding: 1rem 1.5rem;
        border-radius: 0.5rem;
        margin-bottom: 2rem;
        border: 1px solid var(--card-border-color);
    }

    .no-items-message {
        background-color: var(--card-bg);
        padding: 4rem;
        border-radius: 0.75rem;
        border: 1px dashed var(--card-border-color);
        color: var(--bs-secondary-color);
    }

    .item-card.hidden-item {
        display: none;
    }

    #load-more-container {
        margin-top: 2rem;
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
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="index.php">JUBEKA</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"> <a class="nav-link" href="index.php">Beranda</a> </li>
                    <li class="nav-item"> <a class="nav-link active" aria-current="page" href="pembelian.php">Beli
                            Barang</a> </li>
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
                <form class="d-flex me-3" role="search" action="pembelian.php" method="GET">
                    <input class="form-control form-control-sm me-2 bg-dark text-white border-secondary" type="search"
                        name="q" placeholder="Cari barang..." aria-label="Search"
                        value="<?php echo htmlspecialchars($search); ?>">
                    <input type="hidden" name="kategori"
                        value="<?php echo htmlspecialchars($kategori_filter_id ?? ''); ?>">
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
    </nav>
    <main>
        <section class="page-header text-center">
            <div class="container">
                <h1 class="display-5 fw-bold"><?php echo $page_title; ?></h1>
                <p class="lead">Jelajahi berbagai barang bekas berkualitas yang tersedia.</p>
            </div>
        </section>
        <section class="pb-5">
            <div class="container">
                <div class="filter-section shadow-sm">
                    <form action="pembelian.php" method="GET" class="row g-3 align-items-end">
                        <div class="col-md"> <label for="search_q" class="form-label">Cari Barang</label> <input
                                type="text" name="q" id="search_q"
                                class="form-control form-control-sm form-control-dark"
                                placeholder="Masukkan nama barang..." value="<?php echo htmlspecialchars($search); ?>">
                        </div>
                        <div class="col-md-4"> <label for="kategori_filter" class="form-label">Kategori</label> <select
                                name="kategori" id="kategori_filter" class="form-select form-select-sm">
                                <option value="">Semua Kategori</option> <?php foreach($kategori_list as $kat): ?>
                                <option value="<?php echo $kat['id']; ?>"
                                    <?php echo ($kategori_filter_id == $kat['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($kat['nama_kategori']); ?></option> <?php endforeach; ?>
                            </select> </div>
                        <div class="col-md-auto"> <button type="submit" class="btn btn-primary btn-sm w-100"><i
                                    class="fas fa-filter me-1"></i> Filter</button> </div>
                        <?php if ($search != "" || $kategori_filter_id !== null): ?> <div class="col-md-auto"> <a
                                href="pembelian.php" class="btn btn-secondary btn-sm w-100"><i
                                    class="fas fa-times me-1"></i> Reset</a> </div> <?php endif; ?>
                    </form>
                </div>
                <?php if ($search != "" && $kategori_filter_id === null): ?> <div class="search-info mt-4">
                    <p class="mb-0 text-white-50">Menampilkan hasil pencarian untuk: <strong
                            class="text-white"><?php echo htmlspecialchars($search); ?></strong></p>
                </div> <?php endif; ?>

                <div id="item-grid-container" class="row g-4 row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4 mt-3">
                    <?php if ($barang_result && $item_count > 0): ?>
                    <?php $item_index = 0; ?>
                    <?php while ($row = $barang_result->fetch_assoc()): ?>
                    <?php
                            $image_path = 'Assets/' . (!empty($row['gambar']) ? $row['gambar'] : 'placeholder.png');
                            $placeholder_path = 'Assets/placeholder.png';
                            $kategori_nama = $row['nama_kategori'] ?? '';
                            $tanggal_formatted = isset($row['tanggal_post']) ? format_tanggal_indonesia($row['tanggal_post']) : '-';
                            $detail_url = "detail-barang.php?id=" . $row['id'];
                        ?>
                    <div class="col d-flex align-items-stretch item-card">
                        <div class="card text-white product-card">
                            <a href="<?php echo $detail_url; ?>" class="img-link">
                                <?php if($kategori_nama): ?> <span
                                    class="badge rounded-pill text-bg-dark category-badge"><?php echo htmlspecialchars($kategori_nama); ?></span>
                                <?php endif; ?>
                                <img src="<?php echo htmlspecialchars($image_path); ?>" class="card-img-top"
                                    onerror="this.onerror=null; this.src='<?php echo htmlspecialchars($placeholder_path); ?>';"
                                    alt="<?php echo htmlspecialchars($row['nama_barang']); ?>">
                            </a>
                            <div class="card-body">
                                <p class="price mb-1">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
                                <h5 class="card-title mb-1">
                                    <a href="<?php echo $detail_url; ?>" class="text-white text-decoration-none">
                                        <?php echo htmlspecialchars($row['nama_barang']); ?> </a>
                                </h5>
                                <p class="card-date"><i class="far fa-calendar-alt fa-fw"></i>
                                    <?php echo $tanggal_formatted; ?></p>
                                <div class="action-buttons text-center">
                                    <a href="<?php echo $detail_url; ?>" class="btn btn-sm btn-outline-light w-100"><i
                                            class="fas fa-search-plus me-1"></i> Lihat</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                    <?php else: ?>
                    <div class="col-12">
                        <div class="no-items-message text-center mt-4">
                            <i class="fas fa-box-open fa-4x mb-3 text-secondary"></i>
                            <h4 class="text-white">
                                <?php echo ($search != "" || $kategori_filter_id !== null) ? "Barang Tidak Ditemukan" : "Belum Ada Barang Tersedia"; ?>
                            </h4>
                            <?php if ($search != "" || $kategori_filter_id !== null): ?>
                            <p>Coba gunakan kata kunci atau filter kategori yang berbeda.</p>
                            <a href="pembelian.php" class="btn btn-secondary mt-2"><i class="fas fa-list me-2"></i>Lihat
                                Semua Barang</a>
                            <?php else: ?>
                            <p>Silakan cek kembali nanti atau mulai jual barang Anda!</p>
                            <a href="penjualan.php" class="btn btn-info mt-2"><i class="fas fa-plus me-2"></i>Jual
                                Barang</a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if (isset($barang_result) && $barang_result) $barang_result->close();?>
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
                    <p class="mb-0">© <?php echo date("Y", strtotime("today")); ?> JUBEKA. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const preloader = document.getElementById('preloader');
        if (preloader) {
            window.addEventListener('load', function() {
                preloader.classList.add('hidden');
            });
        }
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>