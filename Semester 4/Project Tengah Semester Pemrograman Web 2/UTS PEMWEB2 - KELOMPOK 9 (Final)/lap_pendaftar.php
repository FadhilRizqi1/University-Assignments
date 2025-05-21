<?php
require_once 'koneksi.php';
$laporan_pendaftar = [];
$sql_laporan = "SELECT K.nama_kursus, COUNT(Pendaftaran.id_pengguna) AS jumlah_pendaftar
                FROM Kursus K
                LEFT JOIN Pendaftaran Pendaftaran ON K.id_kursus = Pendaftaran.id_kursus
                GROUP BY K.id_kursus, K.nama_kursus
                ORDER BY K.nama_kursus ASC";
$hasil_laporan = $conn->query($sql_laporan);

if ($hasil_laporan && $hasil_laporan->num_rows > 0) {
    while ($baris_laporan = $hasil_laporan->fetch_assoc()) {
        $laporan_pendaftar[] = $baris_laporan;
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Jumlah Pendaftar per Kursus</title>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">Manajemen Kursus Online</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item"><a
                            class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'form_tambah_user.php' ? 'active' : ''; ?>"
                            href="form_tambah_user.php">Tambah Pengguna</a></li>
                    <li class="nav-item"><a
                            class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'form_reg_kursus.php' ? 'active' : ''; ?>"
                            href="form_reg_kursus.php">Pendaftaran Kursus</a></li>
                    <li class="nav-item"><a
                            class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'list_kursus.php' ? 'active' : ''; ?>"
                            href="list_kursus.php">Daftar Kursus</a></li>
                    <li class="nav-item"><a
                            class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'cari_peserta.php' ? 'active' : ''; ?>"
                            href="cari_peserta.php">Cari Peserta</a></li>
                    <li class="nav-item"><a
                            class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'lap_pendaftar.php' ? 'active' : ''; ?>"
                            href="lap_pendaftar.php">Laporan Pendaftar</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <h2>.</h2>
        <h2>Laporan Jumlah Pendaftar per Kursus</h2>
        <?php if (!empty($laporan_pendaftar)): ?>
        <table class="table table-hover table-bordered mt-3">
            <thead class="thead-light">
                <tr>
                    <th>Nama Kursus</th>
                    <th>Jumlah Pendaftar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($laporan_pendaftar as $laporan_item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($laporan_item['nama_kursus']); ?></td>
                    <td><?php echo htmlspecialchars($laporan_item['jumlah_pendaftar']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="alert alert-info mt-3">Data pendaftar kursus tidak ditemukan atau belum ada kursus/pendaftaran.
        </div>
        <?php endif; ?>
    </div>
    <div class="watermark">Kelompok 9 - SIREG 4A</div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
<?php
if (isset($conn)) {
    $conn->close();
}
?>