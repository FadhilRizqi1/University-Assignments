<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volume Kubus</title>
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: rgb(255, 203, 215);
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


    <h1>VOLUME KUBUS</h1>

    <?php
        $sisi = 10;
        $volume_kubus = $sisi * $sisi * $sisi;
        echo "<b>Sisi = $sisi</b> <br>";
        echo "<b>Volume kubus = $volume_kubus cm</b>";
        echo "<br>";

?>

</body>

</html>