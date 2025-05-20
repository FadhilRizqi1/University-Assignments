<?php
require_once 'koneksi.php';
$daftar_peserta = [];
$id_kursus_terpilih = null;
$nama_kursus_terpilih = "";
$pesan_error = "";
$list_semua_kursus = [];
$sql_list_kursus = "SELECT id_kursus, nama_kursus FROM Kursus ORDER BY nama_kursus ASC";
$hasil_list_kursus = $conn->query($sql_list_kursus);
if ($hasil_list_kursus && $hasil_list_kursus->num_rows > 0) {
    while ($baris_kursus = $hasil_list_kursus->fetch_assoc()) {
        $list_semua_kursus[] = $baris_kursus;
    }
}

if (isset($_GET['id_kursus']) && !empty($_GET['id_kursus'])) {
    $id_kursus_terpilih = (int)$_GET['id_kursus'];
    $stmt_nama_kursus = $conn->prepare("SELECT nama_kursus FROM Kursus WHERE id_kursus = ?");
    if ($stmt_nama_kursus) {
        $stmt_nama_kursus->bind_param("i", $id_kursus_terpilih);
        $stmt_nama_kursus->execute();
        $hasil_nama_kursus = $stmt_nama_kursus->get_result();
        if ($hasil_nama_kursus->num_rows > 0) {
            $nama_kursus_terpilih = $hasil_nama_kursus->fetch_assoc()['nama_kursus'];
        }
        $stmt_nama_kursus->close();
    }

    $sql_cari_peserta = "SELECT Peng.nama_lengkap, Peng.email, K.nama_kursus AS kursus_didaftarkan
                         FROM Pengguna Peng
                         JOIN Pendaftaran Pendaftaran ON Peng.id_pengguna = Pendaftaran.id_pengguna
                         JOIN Kursus K ON Pendaftaran.id_kursus = K.id_kursus
                         WHERE Pendaftaran.id_kursus = ?";
    $stmt_peserta = $conn->prepare($sql_cari_peserta);
    if ($stmt_peserta) {
        $stmt_peserta->bind_param("i", $id_kursus_terpilih);
        $stmt_peserta->execute();
        $hasil_peserta = $stmt_peserta->get_result();
        if ($hasil_peserta && $hasil_peserta->num_rows > 0) {
            while ($baris_peserta = $hasil_peserta->fetch_assoc()) {
                $daftar_peserta[] = $baris_peserta;
            }
        } else {
            $pesan_error = "Tidak ada peserta yang terdaftar pada kursus '" . htmlspecialchars($nama_kursus_terpilih) . "'.";
        }
        $stmt_peserta->close();
    } else {
        $pesan_error = "Gagal mempersiapkan statement pencarian peserta: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cari Peserta Kursus</title>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">Manajemen Kursus UTS</a>
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
        <h2>Cari Peserta Kursus Tertentu</h2>
        <form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="mb-4 form-inline">
            <div class="form-group mr-2">
                <label for="id_kursus" class="mr-sm-2">Pilih Kursus:</label>
                <select name="id_kursus" id="id_kursus" class="form-control" required>
                    <option value="">-- Pilih Kursus --</option>
                    <?php foreach ($list_semua_kursus as $kursus_item): ?>
                    <option value="<?php echo htmlspecialchars($kursus_item['id_kursus']); ?>"
                        <?php echo ($id_kursus_terpilih == $kursus_item['id_kursus']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($kursus_item['nama_kursus']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-info">Cari Peserta</button>
        </form>
        <?php if ($id_kursus_terpilih && !empty($nama_kursus_terpilih) && empty($pesan_error) && empty($daftar_peserta)): ?>
        <div class="alert alert-warning mt-3">Tidak ada peserta yang terdaftar pada kursus
            '<?php echo htmlspecialchars($nama_kursus_terpilih); ?>'.</div>
        <?php endif; ?>
        <?php if ($pesan_error): ?>
        <div class="alert alert-danger mt-3"><?php echo $pesan_error; ?></div>
        <?php endif; ?>
        <?php if (!empty($daftar_peserta)): ?>
        <h3 class="mt-4">Daftar Peserta untuk Kursus: <?php echo htmlspecialchars($nama_kursus_terpilih); ?></h3>
        <table class="table table-bordered table-striped mt-3">
            <thead class="thead-light">
                <tr>
                    <th>Nama Lengkap Pengguna</th>
                    <th>Email</th>
                    <th>Kursus yang Didaftarkan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($daftar_peserta as $peserta): ?>
                <tr>
                    <td><?php echo htmlspecialchars($peserta['nama_lengkap']); ?></td>
                    <td><?php echo htmlspecialchars($peserta['email']); ?></td>
                    <td><?php echo htmlspecialchars($peserta['kursus_didaftarkan']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>

    <!-- Watermark -->
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