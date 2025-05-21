<?php
session_start();
include 'koneksi.php';

$error = "";

if (isset($_SESSION["login"]) && $_SESSION["login"] === true) {
    header("Location: ../index.php");
    exit;
}
if (isset($_SESSION["admin_login"]) && $_SESSION["admin_login"] === true) {
     header("Location: ../admin/dashboard.php");
     exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = trim($_POST['username']);
        $password = $_POST['password'];

        if (!empty($username) && !empty($password)) {

            if ($username === 'admin2025' && $password === '555666') {
                $_SESSION['admin_login'] = true;
                $_SESSION['username'] = 'Admin';
                header("Location: ../admin/dashboard.php");
                exit();
            }

            $stmt = $conn->prepare("SELECT id, username, password, email FROM users WHERE username = ?");
             if(!$stmt) {
                 error_log("Prepare failed: (". $conn->errno.") ".$conn->error);
                 $error = "Terjadi kesalahan sistem. Silakan coba lagi nanti.";
             } else {
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows === 1) {
                    $user = $result->fetch_assoc();
                    if (password_verify($password, $user['password'])) {
                        session_regenerate_id(true);

                        $_SESSION['id'] = $user['id'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['email'] = $user['email'];
                        $_SESSION["login"] = true;

                        header("Location: ../index.php");
                        exit();
                    } else {
                        $error = "Username atau Password salah!";
                    }
                } else {
                    $error = "Username atau Password salah!";
                }
                $stmt->close();
             }
        } else {
             $error = "Username dan Password tidak boleh kosong!";
        }
    } else {
        $error = "Form tidak lengkap!";
    }

    
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - JUBEKA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <style>
    body {
        background-color: #121212;
        color: rgb(138, 138, 138);
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding-top: 40px;
        padding-bottom: 40px;
    }

    .form-signin-container {
        max-width: 450px;
        width: 100%;
    }

    .form-signin .card {
        background-color: #1f1f1f;
        border: 1px solid #333;
        border-radius: 0.75rem;
    }

    .form-signin label {
        color: rgb(208, 208, 208);
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .form-signin .form-control {
        position: relative;
        height: auto;
        padding: 0.9rem 1rem;
        font-size: 1rem;
        background-color: #2a2a2a;
        border: 1px solid #444;
        color: rgb(215, 215, 215);
    }

    .form-signin .form-control:focus {
        z-index: 2;
        background-color: #333;
        border-color: #00aaff;
        box-shadow: 0 0 0 0.25rem rgba(0, 170, 255, 0.25);
        color: #ffffff;
    }

    .form-signin .form-control::placeholder {
        color: #6c757d;
    }

    .form-signin .input-group-text {
        background-color: #343a40;
        border: 1px solid #444;
        color: #ced4da;
        border-right: 0;
        border-radius: 0.375rem 0 0 0.375rem;
    }

    .form-signin .input-group .form-control {
        border-left: 0;
        border-radius: 0 0.375rem 0.375rem 0;
    }

    .form-signin .input-group .form-control:focus {
        border-left: 0;
    }

    .form-signin .form-check-label {
        color: #adb5bd;
    }

    .form-signin .form-check-input {
        background-color: #2a2a2a;
        border-color: #444;
    }

    .form-signin .form-check-input:checked {
        background-color: #00aaff;
        border-color: #00aaff;
    }


    .form-signin .btn-login {
        background-color: #00aaff;
        border-color: #00aaff;
        color: #ffffff;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        font-size: 1.1rem;
        transition: background-color 0.2s ease;
    }

    .form-signin .btn-login:hover {
        background-color: #0cbfff;
        border-color: #0cbfff;
        color: #ffffff;
    }

    .signup-link .text-muted {
        color: #adb5bd !important;
    }

    .signup-link a {
        color: #0dcaf0;
        text-decoration: none;
    }

    .signup-link a:hover {
        text-decoration: underline;
    }

    .copyright {
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
    <main class="form-signin-container">
        <div class="card text-black p-4 p-md-5 shadow form-signin">
            <div class="text-center mb-4">
                <h3 class="fw-normal card-title" style="color: black;">Selamat Datang Kembali!</h3>
                <p class="card-subtitle" style="color: black;">Login untuk melanjutkan ke JUBEKA</p>
            </div>

            <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i> <?php echo $error; ?>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"
                    aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <form action="login.php" method="POST" novalidate>
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fas fa-user fa-fw"></i></span>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Username"
                        required autofocus>
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fas fa-lock fa-fw"></i></span>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password"
                        required>
                </div>

                <div class="form-check text-start my-3">
                    <input class="form-check-input" type="checkbox" value="remember-me" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        Ingat saya
                    </label>
                </div>

                <button class="btn btn-login w-100 py-2 mt-3" type="submit">
                    <i class="fas fa-sign-in-alt me-2"></i>Login
                </button>
            </form>

            <div class="mt-4 text-center signup-link">
                <p class="text-muted">Belum punya akun? <a href="signup.php">Buat Akun Sekarang</a></p>
            </div>
            <p class="mt-4 mb-1 text-center copyright">&copy; <?php echo date("Y"); ?> JUBEKA</p>
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