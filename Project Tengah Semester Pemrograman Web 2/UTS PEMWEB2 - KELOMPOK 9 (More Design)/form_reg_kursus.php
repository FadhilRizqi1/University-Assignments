<?php
require_once 'koneksi.php';
$message = "";
$error_message = "";
$selected_courses_data = [];
$users_list = [];
$sql_users = "SELECT id_pengguna, nama_lengkap FROM Pengguna ORDER BY nama_lengkap ASC";
$result_users = $conn->query($sql_users);
if ($result_users && $result_users->num_rows > 0) {
    while ($row_user = $result_users->fetch_assoc()) {
        $users_list[] = $row_user;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pengguna_pilihan = isset($_POST['id_pengguna']) ? (int)$_POST['id_pengguna'] : null;
    if (empty($id_pengguna_pilihan)) {
        $error_message = "Pengguna harus dipilih.";
    } elseif (isset($_POST['kursus_ids']) && !empty($_POST['kursus_ids'])) {
        $selected_kursus_ids = $_POST['kursus_ids'];
        $selected_courses_data = $selected_kursus_ids;
        $stmt_check = $conn->prepare("SELECT id_pendaftaran FROM Pendaftaran WHERE id_pengguna = ? AND id_kursus = ?");
        $stmt_insert = $conn->prepare("INSERT INTO Pendaftaran (id_pengguna, id_kursus) VALUES (?, ?)");

        if (!$stmt_check || !$stmt_insert) {
            $error_message = "Kesalahan dalam persiapan statement: " . $conn->error;
        } else {
            $berhasil_daftar_count = 0;
            $gagal_karena_terdaftar_count = 0;
            foreach ($selected_kursus_ids as $id_kursus) {
                $id_kursus_int = (int)$id_kursus;
                $stmt_check->bind_param("ii", $id_pengguna_pilihan, $id_kursus_int);
                $stmt_check->execute();
                $result_check = $stmt_check->get_result();
                if ($result_check->num_rows == 0) {
                    $stmt_insert->bind_param("ii", $id_pengguna_pilihan, $id_kursus_int);
                    if ($stmt_insert->execute()) {
                        $berhasil_daftar_count++;
                    } else {
                        $error_message .= "Gagal mendaftarkan kursus ID " . htmlspecialchars($id_kursus) . ". Error: " . $stmt_insert->error . "<br>";
                    }
                } else {
                    $gagal_karena_terdaftar_count++;
                }
            }
            $stmt_check->close();
            $stmt_insert->close();
            if ($berhasil_daftar_count > 0) {
                $message = "Berhasil mendaftarkan " . $berhasil_daftar_count . " kursus untuk pengguna yang dipilih.";
                $selected_courses_data = [];
            }
            if ($gagal_karena_terdaftar_count > 0) {
                $error_message .= $gagal_karena_terdaftar_count . " kursus tidak didaftarkan karena pengguna sudah terdaftar sebelumnya di kursus tersebut.";
            }
             if ($berhasil_daftar_count == 0 && $gagal_karena_terdaftar_count == 0 && empty($error_message) && !empty($selected_kursus_ids)) {
                $error_message = "Tidak ada kursus yang berhasil didaftarkan atau gagal karena sudah terdaftar.";
            }
        }
    } else {
        $error_message = "Anda harus memilih minimal satu kursus.";
    }
}

$courses_list = [];
$sql_courses = "SELECT id_kursus, nama_kursus FROM Kursus ORDER BY nama_kursus ASC";
$result_courses = $conn->query($sql_courses);
if ($result_courses && $result_courses->num_rows > 0) {
    while ($row = $result_courses->fetch_assoc()) {
        $courses_list[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pendaftaran Kursus</title>
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
        <h2>Form Pendaftaran Kursus</h2>
        <?php if ($message): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if ($error_message): ?>
        <div class="alert alert-danger"><?php echo nl2br($error_message); ?></div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="form-group">
                <label for="id_pengguna">Pilih Pengguna:</label>
                <select class="form-control" id="id_pengguna" name="id_pengguna" required>
                    <option value="">-- Pilih Pengguna --</option>
                    <?php foreach ($users_list as $user): ?>
                    <option value="<?php echo htmlspecialchars($user['id_pengguna']); ?>"
                        <?php echo (isset($_POST['id_pengguna']) && $_POST['id_pengguna'] == $user['id_pengguna']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($user['nama_lengkap']); ?> (ID:
                        <?php echo htmlspecialchars($user['id_pengguna']); ?>)
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="kursus_ids">Pilih Kursus (bisa lebih dari satu):</label>
                <select multiple class="form-control" id="kursus_ids" name="kursus_ids[]" size="5" required>
                    <?php if (!empty($courses_list)): ?>
                    <?php foreach ($courses_list as $course): ?>
                    <option value="<?php echo htmlspecialchars($course['id_kursus']); ?>"
                        <?php echo in_array($course['id_kursus'], $selected_courses_data) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($course['nama_kursus']); ?>
                    </option>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <option value="" disabled>Tidak ada kursus tersedia</option>
                    <?php endif; ?>
                </select>
                <small class="form-text text-muted">Tahan tombol Ctrl untuk memilih lebih dari satu
                    kursus.</small>
            </div>
            <button type="submit" class="btn btn-primary">Daftar</button>
        </form>
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