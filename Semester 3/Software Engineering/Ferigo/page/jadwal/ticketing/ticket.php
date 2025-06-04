<?php 
session_start();
require_once '../../../functions/functions.php';   
require_once '../../../config.php';

$userID = $_SESSION['id'];
$id = $_GET['id']; // Ambil ID dari query string
$data = query("SELECT * FROM riwayat_pemesanan WHERE id = $id");
$dataUser  = query("SELECT email FROM userlog WHERE id = $userID");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>E-Ticket Kapal</title>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 20px;
        background-color: #f0f8ff;
    }

    .ticket-container {
        max-width: 700px;
        margin: auto;
        background-image: url('../../../img/loginPage.jpg');
        /* Ganti dengan URL gambar Anda */
        background-size: cover;
        background-position: center;
        border-radius: 20px;
        /* Membuat ujung oval */
        padding: 30px;
        position: relative;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    }

    .overlay {
        background: rgba(255, 255, 255, 0.7);
        /* Warna putih semi transparan */
        border-radius: 20px;
        /* Bentuk oval */
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .ticket-info,
    .qr-code {
        padding: 10px;
        background: rgba(255, 255, 255, 0.6);
        /* Warna putih kabur */
        border-radius: 20px;
    }

    .ticket-info {
        flex: 1;
        margin-right: 20px;
    }

    .ticket-info p {
        font-size: 18px;
        margin: 5px 0;
    }

    .qr-code img {
        width: 150px;
        height: auto;
    }

    .ticket-footer {
        text-align: center;
        margin-top: 20px;
        font-size: 14px;
        color: black;
    }

    .download-btn {
        display: block;
        width: 100%;
        margin-top: 20px;
        padding: 10px;
        background-color: #1e90ff;
        color: white;
        text-align: center;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }

    .download-btn:hover {
        background-color: #1c86ee;
    }
    </style>
</head>

<body>
    <div class="ticket-container" id="ticket">
        <div class="overlay">
            <div class="ticket-info">
                <table>
                    <?php foreach($data as $d) : ?>
                    <tr>
                        <td><strong>Nama</strong></td>
                        <td>: <?= $d['nickname'] ?></td>
                    </tr>
                    <tr>
                        <td><strong>Email</strong></td>
                        <td>: <?= $dataUser [0]['email'] ?></td>
                    </tr>
                    <tr>
                        <td><strong>Nama Kapal</strong></td>
                        <td>: KM Laut Biru</td>
                    </tr>
                    <tr>
                        <td><strong>Jenis Kapal</strong></td>
                        <td>: Feri Penumpang</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Keberangkatan</strong></td>
                        <td>: <?= $d['tanggal'] ?></td>
                    </tr>
                    <tr>
                        <td><strong>Jam Keberangkatan</strong></td>
                        <td>: <?= $d['jam'] ?></td>
                    </tr>
                    <tr>
                        <td><strong>Pelabuhan Asal</strong></td>
                        <td>: <?= $d['asal'] ?></td>
                    </tr>
                    <tr>
                        <td><strong>Pelabuhan Tujuan</strong></td>
                        <td>: <?= $d['tujuan'] ?></td>
                    </tr>
                    <tr>
                        <td><strong>Jenis Layanan</strong></td>
                        <td>: <?= $d['layanan']?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
            <div class="qr-code">
                <img src="../../../img/ferigofirstweb.png" alt="QR Code">
            </div>
        </div>
        <div class="ticket-footer">
            <p>Harap membawa e-ticket ini saat boarding.</p>
            <p><strong>Catatan Penting:</strong> Harap tiba 30 menit sebelum waktu keberangkatan untuk keperluan
                check-in. Penumpang wajib membawa tiket dan identitas resmi saat boarding.</p>
        </div>
        <button class="download-btn" onclick="downloadTicket()">Download E-Ticket</button>
    </div>
    <script>
    function downloadTicket() {
        const ticketElement = document.getElementById('ticket');
        html2canvas(ticketElement).then(function(canvas) {
            const link = document.createElement('a');
            link.href = canvas.toDataURL('image/png');
            link.download = 'e-ticket.png';
            link.click();
        });
    }
    </script>
</body>

</html>