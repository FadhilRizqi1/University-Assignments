<?php
session_start();
include 'auth/koneksi.php';
include 'functions.php';

if (!isset($_SESSION["login"]) || !isset($_SESSION['id'])) { header("Location: auth/login.php"); exit; }

$current_user_id = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['hapus'])) {
    $item_id_to_delete = (int)($_POST['id'] ?? 0);
    if ($item_id_to_delete > 0) {
        $can_delete = false; $stmt_check = $conn->prepare("SELECT user_id FROM jual WHERE id = ?");
        if ($stmt_check) {
            $stmt_check->bind_param("i", $item_id_to_delete);
            if ($stmt_check->execute()) { $result_check = $stmt_check->get_result(); if ($result_check->num_rows === 1) { $item_owner = $result_check->fetch_assoc(); if ($item_owner['user_id'] == $current_user_id) { $can_delete = true; } } } else { error_log("Ownership check execute failed: " . $stmt_check->error); } $stmt_check->close();
        } else { error_log("Ownership check prepare failed: " . $conn->error); }
        if ($can_delete) {
            if (hapus_barang($item_id_to_delete)) { $_SESSION['toast_message'] = ['type' => 'success', 'message' => 'Barang berhasil dihapus.']; }
            else { $_SESSION['toast_message'] = ['type' => 'danger', 'message' => 'Gagal menghapus barang dari database.']; }
        } else { $_SESSION['toast_message'] = ['type' => 'danger', 'message' => 'Anda tidak berhak menghapus barang ini atau barang tidak ditemukan.']; }
    } else { $_SESSION['toast_message'] = ['type' => 'warning', 'message' => 'ID barang tidak valid untuk dihapus.']; }
    header("Location: daftar-barang.php"); exit;
}

$barang_result = tampil_barang_user($current_user_id);
$toast_message_data = null; // Inisialisasi
if (isset($_SESSION['toast_message'])) { // Ambil pesan toast dari session
    $toast_message_data = $_SESSION['toast_message'];
    unset($_SESSION['toast_message']);
}

if ($barang_result === false) { $feedback_message_html = "<div class='alert alert-danger'>Gagal mengambil data barang Anda. Silakan coba lagi nanti.</div>"; $barang_result = null; } // Tampilkan alert biasa jika load data gagal
$item_count = $barang_result ? $barang_result->num_rows : 0;

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barang Saya - JUBEKA</title>
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
        --bs-warning: #ffc107;
        --bs-danger: #dc3545;
        --bs-info: #0dcaf0;
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
        border-color: var(--bs-warning);
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
        color: var(--bs-warning);
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

    .no-items-message {
        background-color: var(--card-bg);
        padding: 4rem;
        border-radius: 0.75rem;
        border: 1px dashed var(--card-border-color);
        color: var(--bs-secondary-color);
    }

    .alert {
        border-left: 5px solid;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        padding: 1rem 1.25rem;
    }

    .alert i {
        font-size: 1.2em;
        margin-right: 0.75rem;
        vertical-align: middle;
    }

    .alert>div {
        vertical-align: middle;
        flex-grow: 1;
    }

    .alert .btn-close {
        filter: none;
        opacity: 0.7;
        transition: opacity 0.15s ease-in-out;
    }

    .alert .btn-close:hover {
        opacity: 1;
    }

    .alert-success {
        border-left-color: var(--bs-success);
    }

    .alert-danger {
        border-left-color: var(--bs-danger);
    }

    .alert-warning {
        border-left-color: var(--bs-warning);
    }

    .alert-info {
        border-left-color: var(--bs-info);
    }

    .alert-success .btn-close,
    .alert-info .btn-close {
        filter: invert(80%) sepia(10%) saturate(1000%) hue-rotate(120deg) brightness(90%) contrast(90%);
    }

    .alert-danger .btn-close {
        filter: invert(30%) sepia(80%) saturate(2000%) hue-rotate(330deg) brightness(90%) contrast(95%);
    }

    .alert-warning .btn-close {
        filter: invert(60%) sepia(90%) saturate(1500%) hue-rotate(10deg) brightness(100%) contrast(100%);
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
                    <li class="nav-item"> <a class="nav-link" href="pembelian.php">Beli Barang</a> </li>
                    <li class="nav-item dropdown"> <a class="nav-link dropdown-toggle active" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">Jual Barang</a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="penjualan.php">Form Jual Barang</a></li>
                            <li><a class="dropdown-item active" aria-current="page" href="daftar-barang.php">Barang
                                    Saya</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"> <a class="nav-link" href="about.php">Tentang Kami</a> </li>
                    <li class="nav-item"> <a class="nav-link" href="contact.php">Kontak</a> </li>
                </ul>
                <form class="d-flex me-3" role="search" action="pembelian.php" method="GET"> <input
                        class="form-control form-control-sm me-2 bg-dark text-white border-secondary" type="search"
                        name="q" placeholder="Cari barang..." aria-label="Search"> <button
                        class="btn btn-outline-info btn-sm" type="submit">Cari</button> </form>
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
                <h1 class="display-5 fw-bold">Barang Jualan Saya</h1>
                <p class="lead">Kelola barang-barang yang Anda jual di JUBEKA.</p> <a href="penjualan.php"
                    class="btn btn-info"><i class="fas fa-plus me-2"></i>Tambah Barang Baru</a>
            </div>
        </section>
        <section class="pb-5">
            <div class="container">
                <?php if (!empty($feedback_message_html)) { echo $feedback_message_html; } ?>
                <div id="item-grid-container" class="row g-4 row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4">
                    <?php if ($barang_result && $item_count > 0): ?>
                    <?php while ($row = $barang_result->fetch_assoc()): ?>
                    <?php
                         $image_path = 'Assets/' . (!empty($row['gambar']) ? $row['gambar'] : 'placeholder.png');
                         $placeholder_path = 'Assets/placeholder.png';
                         $kategori_nama = $row['nama_kategori'] ?? '';
                         $tanggal_formatted = isset($row['tanggal_post']) ? format_tanggal_indonesia($row['tanggal_post']) : '-';
                         $edit_url = "edit-barang.php?id=" . $row['id'];
                    ?>
                    <div class="col d-flex align-items-stretch item-card">
                        <div class="card text-white product-card">
                            <a href="<?php echo $edit_url; ?>" class="img-link">
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
                                    <a href="<?php echo $edit_url; ?>" class="text-white text-decoration-none">
                                        <?php echo htmlspecialchars($row['nama_barang']); ?> </a>
                                </h5>
                                <p class="card-date"><i class="far fa-calendar-alt fa-fw"></i>
                                    <?php echo $tanggal_formatted; ?></p>
                                <div class="action-buttons d-flex gap-2">
                                    <a href="<?php echo $edit_url; ?>"
                                        class="btn btn-outline-warning btn-sm flex-fill"><i
                                            class="fas fa-edit me-1"></i> Ubah</a>
                                    <form method="POST" action="daftar-barang.php" class="flex-fill"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang \'<?php echo htmlspecialchars(addslashes($row['nama_barang'])); ?>\' ini?');">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" name="hapus"
                                            class="btn btn-outline-danger btn-sm w-100"><i
                                                class="fas fa-trash me-1"></i> Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                    <?php elseif ($barang_result): ?>
                    <div class="col-12">
                        <div class="no-items-message text-center mt-4">
                            <i class="fas fa-box-open fa-4x mb-3 text-secondary"></i>
                            <h4 class="text-white">Anda Belum Menjual Barang Apapun</h4>
                            <p>Mulailah menjual barang bekas Anda sekarang!</p>
                            <a href="penjualan.php" class="btn btn-info mt-2"><i class="fas fa-plus me-2"></i>Jual
                                Barang Pertama Anda</a>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if ($barang_result) $barang_result->close(); ?>
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

    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
        <div id="liveToast" class="toast align-items-center border-0" role="alert" aria-live="assertive"
            aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="toast-body-content"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const preloader = document.getElementById('preloader');
        if (preloader) {
            window.addEventListener('load', function() {
                preloader.classList.add('hidden');
            });
        }

        const toastLiveExample = document.getElementById('liveToast');
        const toastMessageData = <?php echo json_encode($toast_message_data ?? null); ?>;

        if (toastMessageData && toastLiveExample) {
            const toastBody = toastLiveExample.querySelector('#toast-body-content');
            let alertType = toastMessageData.type || 'info';
            let message = toastMessageData.message || 'Tidak ada pesan.';
            toastLiveExample.classList.remove('text-bg-success', 'text-bg-danger', 'text-bg-warning',
                'text-bg-info');
            switch (alertType) {
                case 'success':
                    toastLiveExample.classList.add('text-bg-success');
                    break;
                case 'danger':
                    toastLiveExample.classList.add('text-bg-danger');
                    break;
                case 'warning':
                    toastLiveExample.classList.add('text-bg-warning');
                    break;
                default:
                    toastLiveExample.classList.add('text-bg-info');
                    break;
            }
            toastBody.textContent = message;
            const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample, {
                delay: 5000
            });
            toastBootstrap.show();
        }
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>