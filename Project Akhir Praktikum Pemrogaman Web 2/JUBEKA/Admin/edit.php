<?php
session_start();
require_once '../functions.php';

check_admin_login();

$error_message_html = "";
$user_data = null;
$username_value = '';
$email_value = '';
$nomor_telepon_value = '';
$alamat_value = '';
$original_username = '';


if (!isset($_GET['username']) || empty($_GET['username'])) {
    set_toast_message('warning', 'Username pengguna tidak valid untuk diedit.');
    header("Location: dashboard.php");
    exit();
}
$original_username = trim($_GET['username']);

$user_data = get_user_profile_data_by_username($original_username); 

if (!$user_data) {
    set_toast_message('warning', "Pengguna dengan username '".htmlspecialchars($original_username)."' tidak ditemukan.");
    header("Location: dashboard.php");
    exit();
}

$user_id_to_edit = $user_data['id'];
$username_value = $user_data['username'];
$email_value = $user_data['email'];
$nomor_telepon_value = $user_data['nomor_telepon'] ?? '';
$alamat_value = $user_data['alamat'] ?? '';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = trim($_POST["username"] ?? '');
    $new_email = trim($_POST["email"] ?? '');
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
         $error = "Username harus minimal 5 karakter!";
    } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid!";
    } elseif (!preg_match('/^[0-9]{10,15}$/', $new_nomor_telepon)) {
        $error = "Format nomor telepon tidak valid (10-15 digit angka)!";
    } else {
        global $conn;
        $stmt_check = $conn->prepare("SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?");
         if (!$stmt_check) {
             error_log("Prepare check failed (edit): (". $conn->errno.") ".$conn->error);
             $error = "Terjadi kesalahan sistem (CE).";
         } else {
            $stmt_check->bind_param("ssi", $new_username, $new_email, $user_id_to_edit);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();

            if ($result_check->num_rows > 0) {
                 $existing = $result_check->fetch_assoc();
                 if ($existing['username'] === $new_username) { $error = "Username '$new_username' sudah digunakan user lain!"; }
                 else { $error = "Email '$new_email' sudah digunakan user lain!"; }
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

    if (!$error) {
        if (update_user_profile($user_id_to_edit, $new_username, $new_email, $new_nomor_telepon, $new_alamat, $new_password)) {
            set_toast_message('success', "Data pengguna '".htmlspecialchars($new_username)."' berhasil diperbarui.");
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Gagal mengupdate data pengguna!";
        }
    }


    if ($error) {
        $error_message_html = "<div class='alert alert-danger alert-dismissible fade show d-flex align-items-center' role='alert'>";
        $error_message_html .= "<i class='fas fa-times-circle me-2'></i>";
        $error_message_html .= "<div>" . $error . "</div>";
        $error_message_html .= "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
        $error_message_html .= "</div>";
    }
}

$admin_username_sidebar = get_admin_username();

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengguna: <?php echo htmlspecialchars($original_username); ?> - Admin JUBEKA</title>
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
        --bs-warning: #ffc107;
        --bs-info: #0dcaf0;
        --bs-border-color: #444;
        --bs-border-color-translucent: rgba(255, 255, 255, 0.1);
        --card-bg: #1f1f1f;
        --card-border-color: var(--bs-border-color);
        --link-color: #0dcaf0;
        --link-hover-color: #3dd5f3;
        --bs-dark-rgb: 33, 37, 41;
        --bs-light-rgb: 248, 249, 250;
        --bs-success-rgb: 25, 135, 84;
        --bs-danger-rgb: 220, 53, 69;
        --bs-warning-rgb: 255, 193, 7;
        --bs-info-rgb: 13, 202, 240;
    }

    body {
        background-color: var(--bs-body-bg);
        color: var(--bs-body-color);
        display: flex;
        min-height: 100vh;
        overflow-x: hidden;
    }

    .sidebar {
        width: 250px;
        background-color: var(--card-bg);
        padding: 1.5rem 1rem;
        display: flex;
        flex-direction: column;
        flex-shrink: 0;
        border-right: 1px solid var(--bs-border-color);
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        overflow-y: auto;
        z-index: 1000;
    }

    .sidebar .nav-link {
        color: #adb5bd;
        padding: 0.75rem 1rem;
        border-radius: 0.375rem;
        margin-bottom: 0.25rem;
        transition: background-color 0.2s ease, color 0.2s ease;
    }

    .sidebar .nav-link:hover {
        background-color: #343a40;
        color: #fff;
    }

    .sidebar .nav-link.active {
        background-color: var(--bs-primary);
        color: #fff;
        font-weight: 500;
    }

    .sidebar .nav-link i {
        margin-right: 0.75rem;
        width: 20px;
        text-align: center;
    }

    .sidebar .logout-btn {
        margin-top: auto;
    }

    .main-content {
        flex-grow: 1;
        padding: 2.5rem;
        overflow-y: auto;
        margin-left: 250px;
        min-height: 100vh;
    }

    .form-card {
        background-color: var(--card-bg);
        border: 1px solid var(--bs-border-color);
        border-radius: 0.75rem;
    }

    .form-card .card-header {
        background-color: #343a40;
        color: #fff;
        border-bottom: 1px solid var(--bs-border-color);
        border-top-left-radius: calc(0.75rem - 1px);
        border-top-right-radius: calc(0.75rem - 1px);
        padding: 1rem 1.5rem;
        font-weight: 500;
    }

    .form-control-dark,
    textarea.form-control-dark {
        background-color: #2a2a2a;
        border: 1px solid var(--bs-border-color);
        color: #fff;
        padding: 0.75rem 1rem;
    }

    textarea.form-control-dark {
        min-height: 100px;
    }

    .form-control-dark::placeholder,
    textarea.form-control-dark::placeholder {
        color: #6c757d;
    }

    .form-control-dark:focus,
    textarea.form-control-dark:focus {
        background-color: #333;
        border-color: var(--bs-primary);
        box-shadow: 0 0 0 0.25rem rgba(0, 170, 255, 0.25);
        color: #fff;
    }

    .input-group-text-dark {
        background-color: #343a40;
        border: 1px solid var(--bs-border-color);
        color: #ced4da;
        border-right: 0;
    }

    .input-group-text-dark.textarea-icon {
        height: 100px;
        align-items: flex-start;
        padding-top: 0.75rem;
    }

    .input-group .form-control-dark,
    .input-group textarea.form-control-dark {
        border-left: 0;
    }

    .input-group .form-control-dark:focus,
    .input-group textarea.form-control-dark:focus {
        border-left: 0;
    }

    .form-label {
        color: var(--bs-secondary-color);
        margin-bottom: 0.5rem;
        font-weight: 500;
        font-size: 0.9rem;
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
    <aside class="sidebar d-flex flex-column">
        <a href="dashboard.php"
            class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none"> <i
                class="fas fa-cogs fa-2x me-2"></i><span class="fs-4">Admin JUBEKA</span> </a>
        <hr style="border-color: var(--bs-border-color);">
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item"><a href="dashboard.php" class="nav-link"><i
                        class="fas fa-tachometer-alt fa-fw"></i>Dashboard</a></li>
            <li><a href="manage_items.php" class="nav-link"><i class="fas fa-box-open fa-fw"></i>Manajemen Barang</a>
            </li>
            <li><a href="tambah.php" class="nav-link"><i class="fas fa-user-plus fa-fw"></i>Tambah Pengguna</a></li>
        </ul>
        <hr style="border-color: var(--bs-border-color);">
        <div class="logout-btn"> <span class="d-block text-white-50 small mb-1">Login sebagai:
                <?php echo htmlspecialchars($admin_username_sidebar); ?></span> <a href="logout.php"
                class="btn btn-danger w-100"><i class="fas fa-sign-out-alt me-2"></i>Logout</a> </div>
    </aside>

    <main class="main-content">
        <h1 class="h2 mb-4">Edit Pengguna: <span
                class="text-warning fw-bold"><?php echo htmlspecialchars($original_username); ?></span></h1>

        <div class="card form-card shadow-sm">
            <div class="card-header"> Formulir Edit Pengguna </div>
            <div class="card-body p-4">
                <?php if (!empty($error_message_html)) { echo $error_message_html; } ?>

                <form method="POST" action="edit.php?username=<?php echo urlencode($original_username); ?>">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username Baru</label>
                        <div class="input-group">
                            <span class="input-group-text input-group-text-dark"><i
                                    class="fas fa-user fa-fw"></i></span>
                            <input type="text" id="username" name="username" class="form-control form-control-dark"
                                placeholder="Minimal 5 karakter" required
                                value="<?php echo htmlspecialchars($username_value); ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Baru</label>
                        <div class="input-group">
                            <span class="input-group-text input-group-text-dark"><i
                                    class="fas fa-envelope fa-fw"></i></span>
                            <input type="email" id="email" name="email" class="form-control form-control-dark"
                                placeholder="contoh@email.com" required
                                value="<?php echo htmlspecialchars($email_value); ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="nomor_telepon" class="form-label">Nomor Telepon Baru</label>
                        <div class="input-group">
                            <span class="input-group-text input-group-text-dark"><i
                                    class="fas fa-phone fa-fw"></i></span>
                            <input type="tel" id="nomor_telepon" name="nomor_telepon"
                                class="form-control form-control-dark" placeholder="Contoh: 081234567890" required
                                value="<?php echo htmlspecialchars($nomor_telepon_value); ?>">
                        </div>
                        <div class="form-text">Masukkan 10-15 digit angka.</div>
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat Baru</label>
                        <div class="input-group">
                            <span class="input-group-text input-group-text-dark textarea-icon"><i
                                    class="fas fa-map-marker-alt fa-fw"></i></span>
                            <textarea id="alamat" name="alamat" class="form-control form-control-dark" rows="3"
                                placeholder="Masukkan alamat lengkap pengguna"
                                required><?php echo htmlspecialchars($alamat_value); ?></textarea>
                        </div>
                    </div>

                    <hr class="my-4 border-secondary">
                    <h5 class="mb-3 text-white-50">Ubah Password (Opsional)</h5>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Password Baru</label>
                        <div class="input-group">
                            <span class="input-group-text input-group-text-dark"><i
                                    class="fas fa-lock fa-fw"></i></span>
                            <input type="password" id="new_password" name="new_password"
                                class="form-control form-control-dark"
                                placeholder="Minimal 8 karakter (kosongkan jika tidak ingin diubah)">
                        </div>
                        <div class="form-text">Kosongkan jika Anda tidak ingin mengubah password.</div>
                    </div>
                    <div class="mb-4">
                        <label for="confirm_new_password" class="form-label">Konfirmasi Password Baru</label>
                        <div class="input-group">
                            <span class="input-group-text input-group-text-dark"><i
                                    class="fas fa-check-circle fa-fw"></i></span>
                            <input type="password" id="confirm_new_password" name="confirm_new_password"
                                class="form-control form-control-dark" placeholder="Ulangi password baru">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-4 mt-4 border-top border-secondary">
                        <a href="dashboard.php" class="btn btn-secondary"><i
                                class="fas fa-arrow-left me-2"></i>Kembali</a>
                        <button type="submit" class="btn btn-success"><i class="fas fa-save me-2"></i>Simpan
                            Perubahan</button>
                    </div>
                </form>
            </div>
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