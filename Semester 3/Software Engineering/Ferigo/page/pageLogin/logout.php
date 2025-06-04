<?php
// mulai sesi
    session_start();
// hapus sesi
    $_SESSION=[];
    session_unset();
    session_destroy();
/* hapus cookie dengan cara mengosongkan key yang berisi tadi dan set waktu cookie menjadi mines
   yang berarti cookie sudah habis masanya 10 detik yang lalu
*/
// '/' agar berlaku ke seluruh direktori yang ada 
    setcookie('id','', time()-10, '/');
    setcookie('aman','',time()-10, '/');

    header("Location: login.php");
?>