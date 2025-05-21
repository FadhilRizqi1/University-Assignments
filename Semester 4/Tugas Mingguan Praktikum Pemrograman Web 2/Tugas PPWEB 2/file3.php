<?php
$myfile = fopen("data.txt", "r") or die("Tidak bisa membuka file!");
echo fread($myfile, 20);
fclose($myfile);
?>