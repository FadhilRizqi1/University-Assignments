<?php
$filename = "counter.txt";
if (!file_exists($filename)) {
    file_put_contents($filename, 0);
}
$counter = (int) file_get_contents($filename);
$counter++;
file_put_contents($filename, $counter);
echo "Jumlah Pengunjung: " . $counter . "<br>Nama : Ahmad Fadhil Rizqi";
?>