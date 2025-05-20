<?php
session_start();
require 'auth/koneksi.php';
require 'functions.php';

if (!isset($_SESSION["login"]) || !isset($_SESSION['id'])) {
    header("Location: auth/login.php");
    exit;
}

$user_id = $_SESSION['id'];
$user_data = get_user_profile_data($user_id);

if (!$user_data) {
    $_SESSION['profile_message'] = ['type' => 'danger', 'message' => 'Gagal memuat data pengguna.'];
    header("Location: index.php");
    exit;
}

$username_value = $user_data['username'];
$email_value = $user_data['email'];
$nomor_telepon_value = $user_data['nomor_telepon'] ?? '';
$alamat_value = $user_data['alamat'] ?? '';

$feedback_message_html = "";

if (isset($_SESSION['profile_message'])) {
    $type = $_SESSION['profile_message']['type'];
    $message = $_SESSION['profile_message']['message'];
    $icon = ($type == 'success') ? 'fas fa-check-circle' : 'fas fa-times-circle';
    $feedback_message_html = "<div class='alert alert-{$type} alert-dismissible fade show d-flex align-items-center' role='alert'>";
    $feedback_message_html .= "<i class='{$icon} me-2'></i>";
    $feedback_message_html .= "<div>" . htmlspecialchars($message) . "</div>";
    $feedback_message_html .= "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
    $feedback_message_html .= "</div>";
    unset($_SESSION['profile_message']);
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $new_username = trim($_POST['username'] ?? '');
    $new_email = trim($_POST['email'] ?? '');
    $new_nomor_telepon = trim($_POST['nomor_telepon'] ?? '');
    $new_alamat = trim($_POST['alamat'] ?? '');
    $new_password = $_POST['new_password'] ?? '';
    $confirm_new_password = $_POST['confirm_new_password'] ?? '';

    $username_value = $new_username;
    $email_value = $new_email;
    $nomor_telepon_value = $new_nomor_telepon;
    $alamat_value = $new_alamat;

    $error = null;
    if (empty($new_username) || empty($new_email) || empty($new_nomor_telepon) || empty($new_alamat)) {
        $error = "Username, Email, Nomor Telepon, dan Alamat tidak boleh kosong!";
    } elseif (strlen($new_username) < 5) {
        $error = "Username baru harus minimal 5 karakter!";
    } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email baru tidak valid!";
    } elseif (!preg_match('/^[0-9]{10,15}$/', $new_nomor_telepon)) {
        $error = "Format nomor telepon tidak valid! Harus 10-15 digit angka.";
    } else {
        global $conn;
        $stmt_check = $conn->prepare("SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?");
        if (!$stmt_check) {
            error_log("Profile Update: Prepare check failed: (". $conn->errno.") ".$conn->error);
            $error = "Terjadi kesalahan sistem (CUP1).";
        } else {
            $stmt_check->bind_param("ssi", $new_username, $new_email, $user_id);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();

            if ($result_check->num_rows > 0) {
                 $existing = $result_check->fetch_assoc();
                 if ($existing['username'] === $new_username) { $error = "Username '$new_username' sudah digunakan pengguna lain!"; }
                 else { $error = "Email '$new_email' sudah digunakan pengguna lain!"; }
            }
            $stmt_check->close();
        }
    }

    if (!empty($new_password) || !empty($confirm_new_password)) {
        if (strlen($new_password) < 8) {
             $error = ($error ? $error . "<br>" : "") . "Password baru harus minimal 8 karakter!";
        } elseif ($new_password !== $confirm_new_password) {
            $error = ($error ? $error . "<br>" : "") . "Konfirmasi password baru tidak cocok!";
        }
    }

    if ($error) {
        $feedback_message_html = "<div class='alert alert-danger alert-dismissible fade show d-flex align-items-center' role='alert'>";
        $feedback_message_html .= "<i class='fas fa-times-circle me-2'></i>";
        $feedback_message_html .= "<div>" . $error . "</div>";
        $feedback_message_html .= "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
        $feedback_message_html .= "</div>";
    } else {
        if (update_user_profile($user_id, $new_username, $new_email, $new_nomor_telepon, $new_alamat, $new_password)) {
            $_SESSION['username'] = $new_username;
            $_SESSION['email'] = $new_email;
            $_SESSION['nomor_telepon'] = $new_nomor_telepon;
            $_SESSION['alamat'] = $new_alamat;

            $_SESSION['profile_message'] = ['type' => 'success', 'message' => 'Profil berhasil diperbarui.'];
            header("Location: profil.php");
            exit();
        } else {
            $feedback_message_html = "<div class='alert alert-danger alert-dismissible fade show d-flex align-items-center' role='alert'>";
            $feedback_message_html .= "<i class='fas fa-times-circle me-2'></i>";
            $feedback_message_html .= "<div>Gagal memperbarui profil. Silakan coba lagi.</div>";
            $feedback_message_html .= "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
            $feedback_message_html .= "</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profil Saya - JUBEKA</title>
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
        --bs-danger: #dc3545;
        --bs-border-color: #444;
        --card-bg: #1f1f1f;
        --card-border-color: var(--bs-border-color);
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

    .profile-form-section {
        padding-bottom: 5rem;
    }

    .profile-form-card {
        background-color: var(--card-bg);
        border: 1px solid var(--card-border-color);
        border-radius: .75rem;
    }

    .profile-form label {
        color: var(--bs-secondary-color);
        margin-bottom: 0.5rem;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .profile-form .form-control,
    .profile-form textarea {
        background-color: #2a2a2a;
        border: 1px solid var(--bs-border-color);
        color: #fff;
        padding: 0.8rem 1rem;
    }

    .profile-form textarea {
        min-height: 100px;
    }

    .profile-form .form-control:focus,
    .profile-form textarea:focus {
        background-color: #333;
        border-color: var(--bs-primary);
        box-shadow: 0 0 0 0.25rem rgba(0, 170, 255, 0.25);
        color: #fff;
    }

    .profile-form .form-control::placeholder,
    .profile-form textarea::placeholder {
        color: #6c757d;
    }

    .profile-form .input-group-text {
        background-color: #343a40;
        border: 1px solid var(--bs-border-color);
        color: var(--bs-secondary-color);
    }

    .profile-form .input-group-text.textarea-icon {
        height: 100px;
        align-items: flex-start;
        padding-top: 0.8rem;
    }

    .profile-form .btn-submit {
        background-color: var(--bs-primary);
        border-color: var(--bs-primary);
        padding: 0.8rem 1.5rem;
        font-weight: 500;
    }

    .profile-form .btn-submit:hover {
        filter: brightness(1.1);
    }

    .form-text {
        color: #6c757d !important;
        font-size: 0.85em;
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
                    <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="pembelian.php">Beli Barang</a></li>
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
                    <li class="nav-item"><a class="nav-link" href="about.php">Tentang Kami</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Kontak</a></li>
                </ul>
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
                <h1 class="display-5 fw-bold">Profil Saya</h1>
                <p class="lead">Kelola informasi pribadi dan akun Anda.</p>
            </div>
        </section>

        <section class="profile-form-section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <?php if (!empty($feedback_message_html)) { echo $feedback_message_html; } ?>
                        <div class="card profile-form-card shadow">
                            <div
                                class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                                Formulir Edit Profil
                                <small class="text-white-50">ID Pengguna: <?php echo $user_id; ?></small>
                            </div>
                            <div class="card-body p-4 p-md-5">
                                <form method="POST" action="profil.php" class="profile-form">
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-user fa-fw"></i></span>
                                            <input type="text" id="username" name="username" class="form-control"
                                                placeholder="Minimal 5 karakter" required
                                                value="<?php echo htmlspecialchars($username_value); ?>">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-envelope fa-fw"></i></span>
                                            <input type="email" id="email" name="email" class="form-control"
                                                placeholder="contoh@email.com" required
                                                value="<?php echo htmlspecialchars($email_value); ?>">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="nomor_telepon" class="form-label">Nomor Telepon (WhatsApp)</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fab fa-whatsapp fa-fw"></i></span>
                                            <input type="tel" class="form-control" id="nomor_telepon"
                                                name="nomor_telepon" placeholder="Contoh: 081234567890" required
                                                value="<?php echo htmlspecialchars($nomor_telepon_value); ?>">
                                        </div>
                                        <div class="form-text">Nomor telepon aktif yang terhubung dengan WhatsApp.</div>
                                    </div>
                                    <div class="mb-4">
                                        <label for="alamat" class="form-label">Alamat Lengkap</label>
                                        <div class="input-group">
                                            <span class="input-group-text textarea-icon"><i
                                                    class="fas fa-map-marker-alt fa-fw"></i></span>
                                            <textarea class="form-control" id="alamat" name="alamat"
                                                placeholder="Contoh: Jl. Merdeka No. 10, Kel. Sekip Jaya, Kec. Kemuning, Kota Palembang, Sumatera Selatan, 30128"
                                                required><?php echo htmlspecialchars($alamat_value); ?></textarea>
                                        </div>
                                        <div class="form-text">Sertakan nama jalan, nomor rumah, kelurahan, kecamatan,
                                            kota, provinsi, dan kode pos (jika ada).</div>
                                    </div>

                                    <hr class="my-4 border-secondary">
                                    <h5 class="mb-3 text-white-50">Ubah Password (Opsional)</h5>
                                    <div class="mb-3">
                                        <label for="new_password" class="form-label">Password Baru</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-lock fa-fw"></i></span>
                                            <input type="password" id="new_password" name="new_password"
                                                class="form-control"
                                                placeholder="Minimal 8 karakter (kosongkan jika tidak ingin diubah)">
                                        </div>
                                        <div class="form-text">Kosongkan jika Anda tidak ingin mengubah password.</div>
                                    </div>
                                    <div class="mb-4">
                                        <label for="confirm_new_password" class="form-label">Konfirmasi Password
                                            Baru</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i
                                                    class="fas fa-check-circle fa-fw"></i></span>
                                            <input type="password" id="confirm_new_password" name="confirm_new_password"
                                                class="form-control" placeholder="Ulangi password baru">
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end gap-2 pt-3 border-top border-secondary">
                                        <a href="index.php" class="btn btn-secondary"><i
                                                class="fas fa-times me-2"></i>Batal</a>
                                        <button type="submit" name="update_profile"
                                            class="btn btn-primary btn-submit"><i class="fas fa-save me-2"></i>Simpan
                                            Perubahan</button>
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