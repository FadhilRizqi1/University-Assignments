<?php
$dir = "gambar";
$cek = mkdir($dir);
if ($cek) {
    echo "Direktori <b>$dir</b> berhasil dibuat";
} else {
    echo "Direktori <b>$dir</b> gagal dibuat";
}

$cek2 = rmdir($dir);
if ($cek2) {
    echo "Direktori <b>$dir</b> berhasil dihapus";
} else {
    echo "Direktori <b>$dir</b> gagal dihapus";
}
?>