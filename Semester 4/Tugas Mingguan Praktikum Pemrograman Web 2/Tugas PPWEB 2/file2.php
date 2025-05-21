<?php
$myfile = fopen("data.txt", "w") or die("Tidak bisa membuka file!");
$txt = "Nama    : Ahmad Fadhil Rizqi\n";
fwrite($myfile, $txt);
$txt = "NIM     : 09031282328058\n";
fwrite($myfile, $txt);
$txt = "Kelas   : SIREG 4A\n";
fwrite($myfile, $txt);
fclose($myfile);
echo "Data berhasil ditulis ke dalam file!";
?>