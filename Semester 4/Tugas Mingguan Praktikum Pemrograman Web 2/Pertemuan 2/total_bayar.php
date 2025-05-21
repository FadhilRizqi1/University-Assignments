    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>total_bayar</title>
        <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: rgb(229, 255, 208);
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

        <h1>TOTAL BAYAR</h1>

        <?php
        $harga= 1000;
        $jumlah_beli= 20;
        $total_bayar = $harga * $jumlah_beli;
        echo "<b>Harga = $harga <br>";
        echo "<b>Total bayar = Rp. $total_bayar</b> <br>";
        echo "<br>";

    ?>

    </body>

    </html>