<?php
/*************  ✨ Codeium Command ⭐  *************/
/**
 * Menghitung harga setelah diskon diberikan harga awal dan persentase diskon.
 *
 * @param float $harga_awal Harga awal sebelum diskon.
 * @param float $persentase_diskon Persentase diskon yang akan diterapkan (0-100).
 * @return string Harga setelah diskon dalam format angka dengan dua desimal, 
 *                atau pesan error jika input tidak valid.
 */

/******  80914056-f93f-4def-93dd-a729a02a8019  *******/
function hitungDiskon($harga_awal, $persentase_diskon) {
    if ($harga_awal <= 0) {
        return "Error: Harga awal tidak valid.";
    }
    if ($persentase_diskon < 0 || $persentase_diskon > 100) {
        return "Error: Persentase diskon tidak valid.";
    }
    $harga_setelah_diskon = $harga_awal - ($harga_awal * $persentase_diskon / 100);
    return number_format($harga_setelah_diskon, 2);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $harga_awal = $_POST['harga_awal'];
    $persentase_diskon = $_POST['persentase_diskon'];
    $hasil = hitungDiskon($harga_awal, $persentase_diskon);
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hitung Diskon</title>
</head>

<body>
    <h1>Hitung Diskon Harga Produk</h1>
    <form method="POST">
        <label for="harga_awal">Harga Awal:</label>
        <input type="number" name="harga_awal" id="harga_awal" required><br><br>

        <label for="persentase_diskon">Persentase Diskon:</label>
        <input type="number" name="persentase_diskon" id="persentase_diskon" required><br><br>

        <input type="submit" value="Hitung"><br><br>
    </form>

    <?php if (isset($hasil)) { ?>
    <h3>Harga Setelah Diskon: Rp <?= $hasil ?></h3>
    <?php } ?>
</body>

</html>