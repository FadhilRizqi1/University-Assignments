<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luas Lingkaran</title>
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: rgb(255, 252, 177);
        color: #333;
        margin: 20px;
    }

    .identitas {
        text-align: center;
        width: 300px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        border-radius: 10px;
    }

    h1 {
        text-align: center;
        color: #444;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    </style>
</head>

<body>
    <div class="identitas">
        <h3>Nama : Ahmad Fadhil Rizqi</h3>
        <h3>NIM : 09031282328058</h3>
    </div>

    <h1>LUAS LINGKARAN</h1>

    <?php 
        $jari_jari = 10;
        $phi = 3.14;
        $luas_lingkaran = $phi * $jari_jari * $jari_jari;
        echo "<b>Jari-jari = $jari_jari</b> <br>";
        echo "<b>Luas lingkaran = $luas_lingkaran cm</b> <br>";
        echo "<br>";

 ?>

</body>

</html>