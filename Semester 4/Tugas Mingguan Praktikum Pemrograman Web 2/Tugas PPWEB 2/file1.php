<?php
$myfile = fopen("data.txt", "w") or die("Tidak bisa membuka file!");
echo "File data.txt telah dibuat!";
fclose($myfile);
?>