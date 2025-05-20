<?php
session_start();
include 'koneksi.php';

$error = "";
$success = "";

$username_value = '';
$email_value = '';
$nomor_telepon_value = '';
$alamat_value = '';

if (isset($_SESSION["login"]) && $_SESSION["login"] === true) {
    header("Location: ../index.php");
    exit;
}
if (isset($_SESSION["admin_login"]) && $_SESSION["admin_login"] === true) {
     header("Location: ../admin/dashboard.php");
     exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $nomor_telepon = trim($_POST['nomor_telepon'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    $username_value = $username;
    $email_value = $email;
    $nomor_telepon_value = $nomor_telepon;
    $alamat_value = $alamat;

    if (empty($username) || empty($email) || empty($nomor_telepon) || empty($alamat) || empty($password) || empty($confirmPassword)) {
        $error = "Semua field wajib diisi!";
    } elseif (strlen($username) < 5) {
        $error = "Username harus minimal 5 karakter!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
         $error = "Format email tidak valid!";
    } elseif (!preg_match('/^[0-9]{10,15}$/', $nomor_telepon)) {
        $error = "Format nomor telepon tidak valid! Harus 10-15 digit angka.";
    } elseif (strlen($password) < 8) {
        $error = "Password harus minimal 8 karakter!";
    } elseif ($password !== $confirmPassword) {
        $error = "Konfirmasi password tidak cocok!";
    } else {
        $stmt_check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        if(!$stmt_check) {
            error_log("Prepare check failed: (". $conn->errno.") ".$conn->error);
            $error = "Terjadi kesalahan sistem (1).";
        } else {
            $stmt_check->bind_param("ss", $username, $email);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();

            if ($result_check->num_rows > 0) {
                $existing = $result_check->fetch_assoc();
                 if ($existing['username'] === $username) {
                      $error = "Username sudah digunakan!";
                 } else {
                     $error = "Email sudah terdaftar!";
                 }
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt_insert = $conn->prepare("INSERT INTO users (username, email, password, nomor_telepon, alamat) VALUES (?, ?, ?, ?, ?)");
                 if(!$stmt_insert) {
                    error_log("Prepare insert failed: (". $conn->errno.") ".$conn->error);
                    $error = "Terjadi kesalahan sistem (2).";
                 } else {
                    $stmt_insert->bind_param("sssss", $username, $email, $hashed_password, $nomor_telepon, $alamat);
                    if ($stmt_insert->execute()) {
                        $_SESSION['signup_success'] = "Akun berhasil dibuat! Silakan login.";
                        header("Location: login.php");
                        exit();
                    } else {
                         error_log("Insert execute failed: (". $stmt_insert->errno.") ".$stmt_insert->error);
                        $error = "Gagal mendaftarkan akun. Silakan coba lagi.";
                    }
                    $stmt_insert->close();
                 }
            }
            $stmt_check->close();
        }
    }
}

if(isset($_SESSION['signup_success'])) {
    $success = $_SESSION['signup_success'];
    unset($_SESSION['signup_success']);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - JUBEKA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <style>
    body {
        background-color: #121212;
        color: #e0e0e0;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding-top: 40px;
        padding-bottom: 40px;
    }

    .form-signup-container {
        max-width: 550px;
        width: 100%;
    }

    .form-signup .card {
        background-color: #1f1f1f;
        border: 1px solid #333;
        border-radius: 0.75rem;
    }

    .form-signup .card-title {
        color: rgb(0, 0, 0);
    }

    .form-signup .card-subtitle {
        color: #adb5bd;
    }

    .form-signup label {
        color: #adb5bd;
        margin-bottom: 0.5rem;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .form-signup .form-control,
    .form-signup textarea {
        position: relative;
        height: auto;
        padding: 0.8rem 1rem;
        font-size: 1rem;
        background-color: #2a2a2a;
        border: 1px solid #444;
        color: #ffffff;
    }

    .form-signup textarea {
        min-height: 100px;
    }

    .form-signup .form-control:focus,
    .form-signup textarea:focus {
        z-index: 2;
        background-color: #333;
        border-color: #00aaff;
        box-shadow: 0 0 0 0.25rem rgba(0, 170, 255, 0.25);
        color: #ffffff;
    }

    .form-signup .form-control::placeholder,
    .form-signup textarea::placeholder {
        color: #6c757d;
        font-size: 0.95rem;
    }

    .form-signup .input-group-text {
        background-color: #343a40;
        border: 1px solid #444;
        color: #ced4da;
        border-right: 0;
        border-radius: 0.375rem 0 0 0.375rem;
        width: 40px;
        justify-content: center;
    }

    .form-signup .input-group-text.textarea-icon {
        border-radius: 0.375rem 0 0 0;
        height: 100px;
        align-items: flex-start;
        padding-top: 0.8rem;
    }

    .form-signup .input-group .form-control,
    .form-signup .input-group textarea {
        border-left: 0;
        border-radius: 0 0.375rem 0.375rem 0;
    }

    .form-signup .input-group .form-control:focus,
    .form-signup .input-group textarea:focus {
        border-left: 0;
    }

    .form-signup .btn-signup {
        background-color: #00aaff;
        border-color: #00aaff;
        color: #ffffff;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        font-size: 1.1rem;
        transition: background-color 0.2s ease;
    }

    .form-signup .btn-signup:hover {
        background-color: #0cbfff;
        border-color: #0cbfff;
        color: #ffffff;
    }

    .login-link .text-muted {
        color: #adb5bd !important;
    }

    .login-link a {
        color: #0dcaf0;
        text-decoration: none;
    }

    .login-link a:hover {
        text-decoration: underline;
    }

    .copyright {
        color: #6c757d !important;
        font-size: 0.85em;
    }

    .form-text {
        font-size: 0.8em;
        color: #6c757d !important;
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
    <main class="form-signup-container">
        <div class="card text-white p-4 p-md-5 shadow form-signup">
            <div class="text-center mb-4">
                <h3 class="fw-normal card-title">Buat Akun Baru</h3>
                <p class="card-subtitle">Bergabunglah dengan JUBEKA sekarang!</p>
            </div>

            <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-times-circle me-2"></i> <?php echo $error; ?>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"
                    aria-label="Close"></button>
            </div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> <?php echo $success; ?>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"
                    aria-label="Close"></button>
            </div>
            <?php endif; ?>


            <form action="signup.php" method="POST" novalidate>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user fa-fw"></i></span>
                        <input type="text" class="form-control" id="username" name="username"
                            placeholder="Minimal 5 karakter" required
                            value="<?php echo htmlspecialchars($username_value); ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope fa-fw"></i></span>
                        <input type="email" class="form-control" id="email" name="email" placeholder="contoh@email.com"
                            required value="<?php echo htmlspecialchars($email_value); ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="nomor_telepon" class="form-label">Nomor Telepon (WhatsApp)</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fab fa-whatsapp fa-fw"></i></span>
                        <input type="tel" class="form-control" id="nomor_telepon" name="nomor_telepon"
                            placeholder="Contoh: 081234567890" required
                            value="<?php echo htmlspecialchars($nomor_telepon_value); ?>">
                    </div>
                    <div class="form-text">Nomor telepon aktif yang terhubung dengan WhatsApp.</div>
                </div>

                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat Lengkap</label>
                    <div class="input-group">
                        <span class="input-group-text textarea-icon"><i class="fas fa-map-marker-alt fa-fw"></i></span>
                        <textarea class="form-control" id="alamat" name="alamat"
                            placeholder="Contoh: Jl. Merdeka No. 10, Kel. Sekip Jaya, Kec. Kemuning, Kota Palembang, Sumatera Selatan, 30128"
                            required><?php echo htmlspecialchars($alamat_value); ?></textarea>
                    </div>
                    <div class="form-text">Sertakan nama jalan, nomor rumah, kelurahan, kecamatan, kota, provinsi, dan
                        kode pos (jika ada).</div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock fa-fw"></i></span>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Minimal 8 karakter" required>
                    </div>
                    <div class="form-text">Gunakan kombinasi huruf, angka, dan simbol untuk keamanan.</div>
                </div>

                <div class="mb-4">
                    <label for="confirmPassword" class="form-label">Konfirmasi Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-check-circle fa-fw"></i></span>
                        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword"
                            placeholder="Ulangi password" required>
                    </div>
                </div>

                <button class="btn btn-signup w-100 py-2" type="submit">
                    <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                </button>
            </form>

            <div class="mt-4 text-center login-link">
                <p class="text-muted">Sudah punya akun? <a href="login.php">Login di sini</a></p>
            </div>
            <p class="mt-3 mb-1 text-center copyright">&copy; <?php echo date("Y"); ?> JUBEKA</p>
        </div>
    </main>

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