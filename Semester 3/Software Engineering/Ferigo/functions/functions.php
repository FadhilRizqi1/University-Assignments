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

    function query($query){
        global $conn;
        $result  = mysqli_query($conn, $query); // mengambil lemari
        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;// $rows = [["id"=>"1","nama"=>"fadhil"]]
            }
            return $rows; // outputnya
    }

    function booking($query){
        global $conn;
        if(mysqli_query($conn, $query)){
            return true;
        } 
        else{
            return false;  // Mengembalikan false jika query gagal
        }
    }

    function userD($bebas){
        $sigma = query("SELECT * FROM userlog WHERE userID='$bebas'");
        return $sigma;
    }

    function schedule($query){
        global $conn;

        $asal = $query["asal"];
        $tujuan = $query["tujuan"];
        $layanan = $query["service"];
        $tanggal = $query["tanggal"];
        $jam = $query["jam"];
        $penumpang = $query["penumpang"];

        $_SESSION['asal'] = $asal;
        $_SESSION['tujuan'] = $tujuan;
        $_SESSION['layanan'] = $layanan;
        $_SESSION['tanggal'] = $tanggal;
        $_SESSION['jam'] = $jam;
        $_SESSION['penumpang'] = $penumpang;

        // mengubah format hari
        // b - t - th
        // th - b - t
        // $newtgl = explode('-',$tgl);
        // $tanggal = "$newtgl[0]"."-$newtgl[1]"."-$newtgl[2]";

        // mengubah format jam
        // $hours = date('G', strtotime($jamAMPM));
        // $minutes = date('i', strtotime($jamAMPM));
        // $jam = "$hours".":"."$minutes";

        $mencari = mysqli_query($conn, "SELECT * FROM jadwal WHERE pelabuhan_asal = '$asal' AND pelabuhan_tujuan = '$tujuan' AND layanan = '$layanan' AND jadwal_masuk = '$tanggal' AND jam_masuk = '$jam' AND umur = '$penumpang'");

        if( mysqli_fetch_assoc($mencari) > 0){
            
            header("Location: page/jadwal/jadwal.php?");
        }
        else {
            return 'gagal';
        }
    }
?>