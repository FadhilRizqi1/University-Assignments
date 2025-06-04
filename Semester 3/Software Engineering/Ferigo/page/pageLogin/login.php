<?php
    session_start();
    require '../../functions/functions.php';

    if(isset($_COOKIE['id']) && isset($_COOKIE['aman'])) {
        $id = $_COOKIE['id'];
        $aman = $_COOKIE['aman'];

        // ambil username sesuai id
        $result = mysqli_query($conn, "SELECT username FROM userlog WHERE id= '$id'");
        $row = mysqli_fetch_assoc($result);
        // cek username
        if($aman===hash('sha256', $row['username'])){
            $_SESSION['nickname'] = $row['username'];
            $_SESSION['login'] = true;
            $_SESSION['id'] = $row['id'];
        }
        // if($_COOKIE['id']===$id && $_COOKIE['aman']===password_verify($)){
        //     $_SESSION['login'] = true;
        //     $_SESSION['nickname'] = $_COOKIE[''];

        // }
    }

    if(isset($_SESSION["login"])){
        header("Location: ../../index.php");
    }
    if(isset($_GET["userID"])){
        $userData = $_GET["userID"];
        $log = userD($userData);
    }
    if(isset($_POST["submit"])){
        // jika user baru registrasi
        // if(isset($log)){
        //     $_SESSION["nickname"] = $log[0]["username"];
        //     if($_POST["email"] != $log[0]["email"] || $_POST["password"] != $log[0]["password"]){
        //         $error = true;
        //     }
        //     else {
        //         $_SESSION["login"]=true;s
        //         $nickname=$_SESSION["nickname"];
        //         header("Location: ../../main.php?username=$nickname"); 
        //     }
        // }
        // jika user pernah registrasi
        $username = isset($_POST["username"]) ? htmlspecialchars($_POST["username"]) : false;
        $email = isset($_POST["email"]) ? htmlspecialchars($_POST["email"]) : false;
        $password = $_POST["password"];

        // cek apakah username/email ada di database
        $validasi = mysqli_query($conn, "SELECT * FROM userlog WHERE username='$username' OR email='$email'");
        if($validasi){
            $row = mysqli_fetch_assoc($validasi);
            // cek apakah ada kembalian atau row = true dan hash password sesuai dengan inputan password
            if($row && password_verify($password,$row["password"])){
                // mulai sesi
                $_SESSION["login"]=true;
                $_SESSION["nickname"] = $row["username"];
                $_SESSION['id'] = $row['id'];
                // $nickname=$_SESSION["nickname"];

                if(isset($_POST["remember"])){
                    setcookie('id', $row['id'], time()+120, '/');
                    setcookie('aman',hash('sha256', $nickname), time()+120, '/');

                }

                header("Location: ../../");
                exit;
            }
            else{
                $error = true;
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="login.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <title>Login</title>
</head>
<body>
     <!-- NAVBAR -->
     <header>
      <div class="container">
        <nav style="height: 87px;">
          <ul style="margin-top: 0px;">
            <li><a class="brand"><img alt src ="../../img/Logo.png" class="logo"></a></li>
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
    
     <!-- CONTENT -->
     <form action="" method="post">
     <div class="content-box">
        <h1 class="text-title">LOGIN</h1>
        <table class="input-table">
            <tr>
                <td><label for="email"><i class="material-icons" style="color: black;">email</i></label></td>
                <td><input type="text" id="email" name="email" placeholder="account" required></td>
                <!-- tambahkan autocomplete="off" jika ingin history pengisian terhapus -->
            </tr>
            <tr>
                <td><label for="password"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"> <g> <path fill="none" d="M0 0h24v24H0z"/> <path d="M18 8h2a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1h2V7a6 6 0 1 1 12 0v1zM5 10v10h14V10H5zm6 4h2v2h-2v-2zm-4 0h2v2H7v-2zm8 0h2v2h-2v-2zm1-6V7a4 4 0 1 0-8 0v1h8z"/> </g> </svg></label></td>
                <td><input type="password" id="password" name="password" placeholder="password" required></td>   
            </tr>        
        </table>
        <div class="grid-feature"><input type="checkbox" name="remember" id="remember" class="checkbox-r"><label for="remember" class="label-checkbox">Remember me</label>
        <a href="" class="password-backup">Forgot password?</a></div>
        <div class="grid-button"><button type="submit" class="button login-button" name="submit">Login</button></div>
        
        <?php if (isset( $error)) : ?>
            <p class="login link-text">*Username atau password salah</p>
        <?php elseif (!isset( $error)) : ?>
        <p class="signup link-text">Don't have an account?<a href="../pageSign/sign.php" class="signup link-create"> Register.</a></p>
        <?php endif; ?>
     </div>
     </form>
</body>
</html>