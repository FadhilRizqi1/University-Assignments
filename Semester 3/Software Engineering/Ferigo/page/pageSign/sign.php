<?php 
    session_start();
    require '../../functions/functions.php';
    if(isset($_POST["submit"])){
        $hasil = login($_POST);
        if($hasil<1){
            $debug=true;
        }
        elseif($hasil===7) {
            $validasi1=true;
        }
        elseif($hasil===3) {
            $validasi=true;
        }
        elseif($hasil==="password salah"){
            $confirm=true;
        } 
        else {
            echo "
            <script>
            alert('account sucssesfully registered');
            window.location.href='../pageLogin/login.php'
            </script>";
        }    
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="sign.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <title>Sign</title>
</head>

<body>
    <!-- NAVBAR -->
    <header>
        <div class="container">
            <nav style="height: 87px;">
                <ul style="margin-top: 0px;">
                    <li><a class="brand"><img alt src="../../img/Logo.png" class="logo"></a></li>
                    <li class="gap"><a></a></li>
                    <li><a href="../../index.php">Beranda</a></li>
                    <li><a></a></li>
                    <li><a></a></li>
                    <li><a href="../../index.php #pemesanan">Pemesanan Tiket</a></li>
                    <li><a></a></li>
                    <li><a></a></li>
                    <li><a href="#notif">Notifikasi</a></li>
                    <li class="gap2"><a></a></li>
                </ul>
            </nav>
        </div>
    </header>
    <!-- NAVBAR SELESAI -->
    <!-- CONTENT -->
    <form action="" method="post">
        <div class="content-box">
            <h1 class="text-title">SIGN</h1>
            <table class="input-table">
                <tr>
                    <td><label for="username"><i class="material-icons" style="color: black;">mail</i></label></td>
                    <td><input type="text" id="username" name="username" placeholder="username" required></td>
                    <!-- tambahkan autocomplete="off" jika ingin history pengisian terhapus -->
                </tr>
                <tr>
                    <td><label for="email"><i class="material-icons" style="color: black;">mail</i></label></td>
                    <td><input type="email" id="email" name="email" placeholder="email" required></td>
                    <!-- tambahkan autocomplete="off" jika ingin history pengisian terhapus -->
                </tr>
                <tr>
                    <td><label for="password"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <g>
                                    <path fill="none" d="M0 0h24v24H0z" />
                                    <path
                                        d="M18 8h2a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1h2V7a6 6 0 1 1 12 0v1zM5 10v10h14V10H5zm6 4h2v2h-2v-2zm-4 0h2v2H7v-2zm8 0h2v2h-2v-2zm1-6V7a4 4 0 1 0-8 0v1h8z" />
                                </g>
                            </svg></label></td>
                    <td><input type="password" id="password" name="password" placeholder="password" required></td>
                </tr>
                <tr>
                    <td><label for="password2"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <g>
                                    <path fill="none" d="M0 0h24v24H0z" />
                                    <path
                                        d="M18 8h2a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1h2V7a6 6 0 1 1 12 0v1zM5 10v10h14V10H5zm6 4h2v2h-2v-2zm-4 0h2v2H7v-2zm8 0h2v2h-2v-2zm1-6V7a4 4 0 1 0-8 0v1h8z" />
                                </g>
                            </svg></label></td>
                    <td><input type="password" id="password2" name="password2" placeholder="confirm password" required>
                    </td>
                </tr>
            </table>
            <div class="grid-button"><button type="submit" class="button login-button" name="submit">Create</button>
            </div>
            <?php if (isset( $debug)) : ?>
            <p class="signup link-text">*Nama/Password harus berjumlah 8-15 Karakter</p>
            <?php elseif (isset( $validasi1)) : ?>
            <p class="signup link-text">*Periksa kembali email anda!</p>
            <?php elseif (isset( $validasi)) : ?>
            <p class="signup link-text">*Username/email sudah terdaftar</p>
            <?php elseif (isset( $confirm)) : ?>
            <p class="signup link-text">*Password yang anda masukkan tidak sama</p>
            <?php endif; ?>
            <p class="login link-text">Have an account?<a href="../pageLogin/login.php" class="login link-create">
                    Login.</a></p>
        </div>
    </form>
</body>

</html>