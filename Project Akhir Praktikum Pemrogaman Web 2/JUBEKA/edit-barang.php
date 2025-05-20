<?php
session_start();
require 'functions.php';

if (!isset($_SESSION["login"]) || !isset($_SESSION['id'])) {
    header("Location: auth/login.php");
    exit;
}

$current_user_id = $_SESSION['id'];
$feedback_message_html = "";
$kategori_list = get_all_kategori();

if (isset($_SESSION['feedback_message_edit'])) {
    $feedback_message_html = $_SESSION['feedback_message_edit'];
    unset($_SESSION['feedback_message_edit']);
}

if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $_SESSION['toast_message'] = ['type' => 'danger', 'message' => 'ID Barang tidak valid.'];
    header("Location: daftar-barang.php");
    exit;
}
$id = (int)$_GET['id'];

$row = get_barang_by_id($id, $current_user_id);

if (!$row) {
    $_SESSION['toast_message'] = ['type' => 'warning', 'message' => "Barang dengan ID #{$id} tidak ditemukan atau Anda tidak berhak mengeditnya."];
    header("Location: daftar-barang.php");
    exit;
}

$nama_barang_value = $row['nama_barang'];
$deskripsi_value = $row['deskripsi'];
$harga_value = $row['harga'];
$kategori_id_value = $row['kategori_id'];
$gambar_lama = $row['gambar'];
$nomor_telepon_penjual_value = $row['nomor_telepon_penjual'] ?? '';
$alamat_penjual_value = $row['alamat_penjual'] ?? '';


if ($_SERVER['REQUEST_METHOD'] == 'POST'  && isset($_POST['update_barang'])) {
    $nama_barang_post = trim($_POST['nama_barang'] ?? '');
    $deskripsi_post = trim($_POST['deskripsi'] ?? '');
    $harga_post = filter_input(INPUT_POST, 'harga', FILTER_VALIDATE_FLOAT);
    $kategori_id_post = isset($_POST['kategori_id']) && $_POST['kategori_id'] !== '' ? (int)$_POST['kategori_id'] : null;
    $nomor_telepon_penjual_post = trim($_POST['nomor_telepon_penjual'] ?? '');
    $alamat_penjual_post = trim($_POST['alamat_penjual'] ?? '');

    $nama_barang_value = $nama_barang_post;
    $deskripsi_value = $deskripsi_post;
    $harga_value = $harga_post;
    $kategori_id_value = $kategori_id_post;
    $nomor_telepon_penjual_value = $nomor_telepon_penjual_post;
    $alamat_penjual_value = $alamat_penjual_post;

    $error = false; $error_msg = '';

    if (empty($nama_barang_post) || empty($deskripsi_post) || $harga_post === false || $harga_post < 0 || empty($nomor_telepon_penjual_post) || empty($alamat_penjual_post)) {
        $error = true; $error_msg = "Nama Barang, Deskripsi, Harga, Nomor Telepon, dan Alamat Penjual wajib diisi.";
    } elseif (!preg_match('/^[0-9]{10,15}$/', $nomor_telepon_penjual_post)) {
         $error = true; $error_msg = "Format nomor telepon tidak valid! Harus 10-15 digit angka.";
    }

    if ($error) {
         $feedback_message_html = "<div class='alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4' role='alert'><i class='fas fa-times-circle me-2'></i><div>".htmlspecialchars($error_msg)."</div><button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
    } else {
        $gambar_baru = $gambar_lama; $upload_success = true; $new_image_uploaded = false;

        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == UPLOAD_ERR_OK) {
            $gambarInfo = pathinfo($_FILES['gambar']['name']); $gambarExt = strtolower($gambarInfo['extension'] ?? '');
            $allowedExt = ['jpg', 'jpeg', 'png', 'webp']; $maxSize = 2 * 1024 * 1024;
            if (in_array($gambarExt, $allowedExt) && $_FILES['gambar']['size'] <= $maxSize) {
                $gambar_baru_nama_unik = uniqid('img_', true) . '.' . $gambarExt; $tmp_gambar = $_FILES['gambar']['tmp_name'];
                if (upload_gambar($tmp_gambar, $gambar_baru_nama_unik)) { $gambar_baru = $gambar_baru_nama_unik; $new_image_uploaded = true; }
                else { $feedback_message_html = "<div class='alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4' role='alert'><i class='fas fa-times-circle me-2'></i><div>Gagal mengupload gambar baru.</div><button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>"; $upload_success = false; $gambar_baru = $gambar_lama; }
            } else { $feedback_message_html = "<div class='alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4' role='alert'><i class='fas fa-times-circle me-2'></i><div>File gambar baru tidak valid! Format/Ukuran salah.</div><button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>"; $upload_success = false; $gambar_baru = $gambar_lama; }
        } elseif (isset($_FILES['gambar']) && $_FILES['gambar']['error'] != UPLOAD_ERR_NO_FILE && $_FILES['gambar']['error'] != UPLOAD_ERR_OK) {
             $feedback_message_html = "<div class='alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4' role='alert'><i class='fas fa-times-circle me-2'></i><div>Error upload gambar: ".$_FILES['gambar']['error']."</div><button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>"; $upload_success = false; $gambar_baru = $gambar_lama;
        }

        if ($upload_success && empty($feedback_message_html)) {
             if (update_barang($id, $nama_barang_post, $deskripsi_post, $harga_post, $gambar_baru, $current_user_id, $kategori_id_post, $nomor_telepon_penjual_post, $alamat_penjual_post)) {
                 if ($new_image_uploaded && $gambar_lama && $gambar_lama != $gambar_baru && file_exists('Assets/' . $gambar_lama)) { @unlink('Assets/' . $gambar_lama); }
                 $_SESSION['toast_message'] = ['type' => 'success', 'message' => "Barang '".htmlspecialchars($nama_barang_post)."' berhasil diperbarui!"];
                 header("Location: daftar-barang.php"); exit;
             } else {
                 $feedback_message_html = "<div class='alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4' role='alert'><i class='fas fa-times-circle me-2'></i><div>Gagal memperbarui data barang di database.</div><button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
                 if ($new_image_uploaded && file_exists('Assets/' . $gambar_baru)) { @unlink('Assets/' . $gambar_baru); }
             }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Barang - JUBEKA</title>
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

    .edit-form-section {
        padding-bottom: 5rem;
    }

    .edit-form-card {
        background-color: var(--card-bg);
        border: 1px solid var(--card-border-color);
        border-radius: .75rem;
    }

    .edit-form label {
        color: var(--bs-secondary-color);
        margin-bottom: 0.5rem;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .edit-form .form-control,
    .edit-form .form-select,
    .edit-form textarea {
        background-color: #2a2a2a;
        border: 1px solid #444;
        color: #fff;
        padding: 0.75rem 1rem;
    }

    .edit-form textarea {
        min-height: 100px;
    }


    .edit-form .form-control[type="file"] {
        color: var(--bs-secondary-color);
    }

    .edit-form .form-control[type="file"]::file-selector-button {
        background-color: #343a40;
        color: #fff;
        border: none;
        padding: 0.75rem 1rem;
        margin-right: 1rem;
        border-radius: 0.25rem;
        cursor: pointer;
        transition: background-color .15s ease-in-out;
    }

    .edit-form .form-control[type="file"]::file-selector-button:hover {
        background-color: #495057;
    }

    .edit-form .form-control:disabled,
    .edit-form .form-control[readonly] {
        background-color: #3a3a3a;
        opacity: 0.7;
    }

    .edit-form .form-control:focus,
    .edit-form .form-select:focus,
    .edit-form textarea:focus {
        background-color: #333;
        border-color: var(--bs-primary);
        box-shadow: 0 0 0 0.25rem rgba(0, 170, 255, 0.25);
        color: #fff;
    }

    .edit-form .form-control::placeholder,
    .edit-form textarea::placeholder {
        color: #6c757d;
    }

    .edit-form .input-group-text {
        background-color: #343a40;
        border: 1px solid #444;
        color: var(--bs-secondary-color);
    }

    .edit-form .input-group-text.textarea-icon {
        height: 100px;
        align-items: flex-start;
        padding-top: 0.75rem;
    }

    .edit-form .btn-submit {
        padding: 0.75rem 1.5rem;
        font-weight: 500;
    }

    .edit-form .btn-submit:hover {
        filter: brightness(110%);
    }

    .form-text {
        color: #6c757d !important;
        font-size: 0.85em;
    }

    .current-image {
        max-width: 150px;
        height: auto;
        border-radius: 0.375rem;
        border: 1px solid #444;
        background-color: #2a2a2a;
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
        margin-left: 1rem;
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

    .alert-success .btn-close,
    .alert-info .btn-close {
        filter: invert(80%) sepia(10%) saturate(1000%) hue-rotate(120deg) brightness(90%) contrast(90%);
    }

    .alert-danger .btn-close {
        filter: invert(30%) sepia(80%) saturate(2000%) hue-rotate(330deg) brightness(90%) contrast(95%);
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
                    <li class="nav-item"> <a class="nav-link" href="pembelian.php">Beli Barang</a> </li>
                    <li class="nav-item dropdown"> <a
                            class="nav-link dropdown-toggle <?php echo (basename($_SERVER['PHP_SELF']) == 'daftar-barang.php' || basename($_SERVER['PHP_SELF']) == 'edit-barang.php' ? 'active' : ''); ?>"
                            href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> Jual Barang </a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="penjualan.php">Form Jual Barang</a></li>
                            <li><a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'daftar-barang.php' || basename($_SERVER['PHP_SELF']) == 'edit-barang.php' ? 'active' : ''); ?>"
                                    href="daftar-barang.php">Barang
                                    Saya</a></li>
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
                            <li><a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'daftar-barang.php' || basename($_SERVER['PHP_SELF']) == 'edit-barang.php' ? 'active' : ''); ?>"
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
        <section class="page-header text-center">
            <div class="container">
                <h1 class="display-5 fw-bold">Edit Barang</h1>
                <p class="lead">Perbarui detail barang jualan Anda: <strong
                        class="text-warning"><?php echo htmlspecialchars($nama_barang_value); ?></strong></p>
            </div>
        </section>
        <section class="edit-form-section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <?php if (!empty($feedback_message_html)) { echo $feedback_message_html; } ?>
                        <div class="card edit-form-card shadow">
                            <div class="card-header bg-dark text-white"> Formulir Edit Barang </div>
                            <div class="card-body p-4 p-md-5">
                                <form action="edit-barang.php?id=<?php echo $id; ?>" method="POST"
                                    enctype="multipart/form-data" class="edit-form">
                                    <div class="mb-3">
                                        <label for="nama_barang" class="form-label">Nama Barang</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-box fa-fw"></i></span>
                                            <input type="text" class="form-control" id="nama_barang" name="nama_barang"
                                                placeholder="Contoh: Sepatu Lari Bekas Merk X Ukuran 42" required
                                                value="<?php echo htmlspecialchars($nama_barang_value); ?>" />
                                        </div>
                                        <div class="form-text">Gunakan nama yang jelas dan deskriptif.</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="kategori_id" class="form-label">Kategori</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-tags fa-fw"></i></span>
                                            <select class="form-select" id="kategori_id" name="kategori_id">
                                                <option value="">-- Pilih Kategori --</option>
                                                <?php foreach ($kategori_list as $kat): ?>
                                                <option value="<?php echo $kat['id']; ?>"
                                                    <?php echo ($kategori_id_value == $kat['id']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($kat['nama_kategori']); ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="form-text">Pilih kategori yang paling sesuai (opsional).</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="deskripsi" class="form-label">Deskripsi</label>
                                        <div class="input-group">
                                            <span class="input-group-text textarea-icon"><i
                                                    class="fas fa-info-circle fa-fw"></i></span>
                                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="5"
                                                placeholder="Jelaskan kondisi barang, minus (jika ada), alasan jual, dll."
                                                required><?php echo htmlspecialchars($deskripsi_value); ?></textarea>
                                        </div>
                                        <div class="form-text">Semakin detail deskripsi, semakin baik.</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="harga" class="form-label">Harga</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" class="form-control" id="harga" name="harga"
                                                placeholder="Masukkan harga tanpa titik atau koma (contoh: 150000)"
                                                required min="0" step="1"
                                                value="<?php echo htmlspecialchars($harga_value); ?>" />
                                        </div>
                                        <div class="form-text">Masukkan hanya angka.</div>
                                    </div>

                                    <hr class="my-4 border-secondary">
                                    <h5 class="mb-3 text-white-50">Informasi Kontak Penjual & Lokasi Barang</h5>

                                    <div class="mb-3">
                                        <label for="nomor_telepon_penjual" class="form-label">Nomor Telepon
                                            (WhatsApp)</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fab fa-whatsapp fa-fw"></i></span>
                                            <input type="tel" class="form-control" id="nomor_telepon_penjual"
                                                name="nomor_telepon_penjual" placeholder="Contoh: 081234567890" required
                                                value="<?php echo htmlspecialchars($nomor_telepon_penjual_value); ?>">
                                        </div>
                                        <div class="form-text">Nomor yang akan dihubungi pembeli untuk barang ini.</div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="alamat_penjual" class="form-label">Alamat Penjual (Pickup
                                            Point)</label>
                                        <div class="input-group">
                                            <span class="input-group-text textarea-icon"><i
                                                    class="fas fa-map-marker-alt fa-fw"></i></span>
                                            <textarea class="form-control" id="alamat_penjual" name="alamat_penjual"
                                                placeholder="Contoh: Jl. Merdeka No. 10, Sekip, Palembang (Dekat Masjid Agung)"
                                                required><?php echo htmlspecialchars($alamat_penjual_value); ?></textarea>
                                        </div>
                                        <div class="form-text">Alamat tempat barang bisa diambil atau COD.</div>
                                    </div>

                                    <hr class="my-4 border-secondary">

                                    <div class="mb-4">
                                        <label for="gambar" class="form-label">Ganti Gambar Utama (Opsional)</label>
                                        <div class="mb-2">
                                            <span class="form-text">Gambar Saat Ini:</span><br>
                                            <?php if (!empty($gambar_lama) && file_exists('Assets/' . $gambar_lama)): ?>
                                            <img src="Assets/<?php echo htmlspecialchars($gambar_lama); ?>"
                                                alt="Gambar Barang Saat Ini" class="current-image mt-1 img-thumbnail"
                                                onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='inline';">
                                            <span class="text-muted small" style="display: none;">(Gambar tidak
                                                ditemukan)</span>
                                            <?php else: ?>
                                            <span class="text-muted small">(Tidak ada gambar atau gambar tidak
                                                ditemukan)</span>
                                            <?php endif; ?>
                                        </div>
                                        <input type="file" class="form-control" id="gambar" name="gambar"
                                            accept="image/png, image/jpeg, image/jpg, image/webp" />
                                        <div class="form-text">Biarkan kosong jika tidak ingin mengganti gambar. Format:
                                            JPG, PNG, WEBP. Max: 2MB.</div>
                                    </div>
                                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top border-secondary">
                                        <a href="daftar-barang.php"
                                            class="btn btn-secondary d-inline-flex align-items-center justify-content-center"><i
                                                class="fas fa-arrow-left me-2"></i>Batal</a>
                                        <button type="submit" name="update_barang" class="btn btn-primary btn-submit"><i
                                                class="fas fa-save me-2"></i>Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
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
    <script>
    const formEdit = document.querySelector('form.edit-form');
    if (formEdit) {
        const hargaInput = document.getElementById('harga');
        const gambarInput = document.getElementById('gambar');
        const nomorTeleponInput = document.getElementById('nomor_telepon_penjual');
        formEdit.addEventListener('submit', function(event) {
            if (parseFloat(hargaInput.value) < 0) {
                alert('Harga tidak boleh negatif.');
                hargaInput.focus();
                event.preventDefault();
                return;
            }
            const phoneRegex = /^[0-9]{10,15}$/;
            if (!phoneRegex.test(nomorTeleponInput.value)) {
                alert('Format nomor telepon tidak valid! Harus 10-15 digit angka.');
                nomorTeleponInput.focus();
                event.preventDefault();
                return;
            }
            if (gambarInput.files.length > 0) {
                const file = gambarInput.files[0];
                const allowedExtensions = /(\.jpg|\.jpeg|\.png|\.webp)$/i;
                const maxSize = 2 * 1024 * 1024;
                if (!allowedExtensions.exec(file.name)) {
                    alert('Format gambar tidak valid! Gunakan JPG, PNG, atau WEBP.');
                    event.preventDefault();
                    return;
                }
                if (file.size > maxSize) {
                    alert('Ukuran file gambar maksimal adalah 2MB!');
                    event.preventDefault();
                    return;
                }
            }
        });
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>