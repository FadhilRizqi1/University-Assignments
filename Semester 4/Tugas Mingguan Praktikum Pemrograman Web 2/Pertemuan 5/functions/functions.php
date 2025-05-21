<?php 
    $conn = mysqli_connect('localhost', 'root', '', 'ferigodb');

    if (!$conn) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }

    function login($query) {
        global $conn;
        $username = htmlspecialchars(strtolower(stripslashes($query["username"])));
        $email = htmlspecialchars($query["email"]);
        $password = mysqli_real_escape_string($conn , $query["password"]);
        $password2 = mysqli_real_escape_string($conn , $query["password2"]);

        if($password !== $password2){
            return "password salah";
        }

        // validasi username sudah dibuat apa belum
        $validasi = mysqli_query($conn, "SELECT username FROM userlog WHERE username='$username'");
        if ( mysqli_fetch_assoc($validasi)){
            return 3;
        }

        // validasi email sudah dibuat apa belum
        $validasi2 = mysqli_query($conn, "SELECT email FROM userlog WHERE email='$email'");
        if ( mysqli_fetch_assoc($validasi2)){
            return 3;
        }
        
        // validasi apakah penulisan alamat email sudah benar apa belum
        $gmailvalid = ['gmail.com'];
        $gmail = explode('@',$email);
        $gmail = strtolower(end($gmail));
        if(!in_array($gmail,$gmailvalid)){
            return 7;
        }

        $userLength = strlen($username);
        $emailLength = strlen($email);
        $passwordLength = strlen($password);
        $userID = uniqid();

        // enkripsi password
        $password = password_hash($password, PASSWORD_BCRYPT);

         // kirim data ke database
         $query = "INSERT INTO userlog VALUES
         (NULL,'$username','$email','$password', '$userID')";
         if(empty($username) || empty($email) || empty($password)){ 
            return false;
            exit;
         }
         elseif ( $userLength < 8 || $userLength > 15 || $passwordLength < 8 || $passwordLength > 15){
            return false;
         }
         else {                
             if (mysqli_query($conn, $query)) {
                return $userID;
             }
              else {
                return false;  // Mengembalikan false jika query gagal
            }
         }   
     
    }
