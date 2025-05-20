<!-- by Ahmad Fadhil Rizqi -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabel Transaksi Barang</title>
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 20px;
        background-color: rgb(225, 229, 225);
        color: #333;
    }

    .container {
        max-width: 600px;
        margin: auto;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 10px;
        background-color: #fff;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
    }

    h2 {
        text-align: center;
        color: #444;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    table {
        background-color: rgb(237, 255, 236);
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 15px;
        text-align: left;
    }

    th {
        background-color: rgb(53, 112, 0);
        color: white;
        text-align: center;
    }


    tr:hover {
        background-color: #e3f2fd;
    }
    </style>
</head>

<body>
    <div class="container">
        <h2>Tabel Transaksi Barang</h2>
        <?php
            $nama_barang1 = "Laptop";
            $jumlah_beli1 = 3;
            $harga_barang1 = 15700000;
            $total_bayar1 = $jumlah_beli1 * $harga_barang1;

            $nama_barang2 = "Mouse";
            $jumlah_beli2 = 5;
            $harga_barang2 = 299900;
            $total_bayar2 = $jumlah_beli2 * $harga_barang2;

            $nama_barang3 = "Keyboard";
            $jumlah_beli3 = 8;
            $harga_barang3 = 250000;
            $total_bayar3 = $jumlah_beli3 * $harga_barang3;

            $nama_barang4 = "Monitor";
            $jumlah_beli4 = 4;
            $harga_barang4 = 2115000;
            $total_bayar4 = $jumlah_beli4 * $harga_barang4;

            $nama_barang5 = "Printer";
            $jumlah_beli5 = 7;
            $harga_barang5 = 4440000;
            $total_bayar5 = $jumlah_beli5 * $harga_barang5;
        ?>
        <table>
            <tr>
                <th>Nama Barang</th>
                <th>Jumlah Beli</th>
                <th>Harga Barang</th>
                <th>Total Bayar</th>
            </tr>
            <tr>
                <td><?php echo $nama_barang1; ?></td>
                <td><?php echo $jumlah_beli1; ?></td>
                <td>Rp <?php echo number_format($harga_barang1, 0, ',', '.'); ?></td>
                <td>Rp <?php echo number_format($total_bayar1, 0, ',', '.'); ?></td>
            </tr>
            <tr>
                <td><?php echo $nama_barang2; ?></td>
                <td><?php echo $jumlah_beli2; ?></td>
                <td>Rp <?php echo number_format($harga_barang2, 0, ',', '.'); ?></td>
                <td>Rp <?php echo number_format($total_bayar2, 0, ',', '.'); ?></td>
            </tr>
            <tr>
                <td><?php echo $nama_barang3; ?></td>
                <td><?php echo $jumlah_beli3; ?></td>
                <td>Rp <?php echo number_format($harga_barang3, 0, ',', '.'); ?></td>
                <td>Rp <?php echo number_format($total_bayar3, 0, ',', '.'); ?></td>
            </tr>
            <tr>
                <td><?php echo $nama_barang4; ?></td>
                <td><?php echo $jumlah_beli4; ?></td>
                <td>Rp <?php echo number_format($harga_barang4, 0, ',', '.'); ?></td>
                <td>Rp <?php echo number_format($total_bayar4, 0, ',', '.'); ?></td>
            </tr>
            <tr>
                <td><?php echo $nama_barang5; ?></td>
                <td><?php echo $jumlah_beli5; ?></td>
                <td>Rp <?php echo number_format($harga_barang5, 0, ',', '.'); ?></td>
                <td>Rp <?php echo number_format($total_bayar5, 0, ',', '.'); ?></td>
            </tr>
        </table>
    </div>
</body>

</html>