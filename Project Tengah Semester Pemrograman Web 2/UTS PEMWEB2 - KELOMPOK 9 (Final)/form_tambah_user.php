<?php
require_once 'koneksi.php';
$message = "";
$error_message = "";
$input_nama_lengkap = "";
$input_email = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_lengkap = trim($_POST['nama_lengkap']);
    $email = trim($_POST['email']);
    $kata_sandi_input = $_POST['kata_sandi'];
    $input_nama_lengkap = $nama_lengkap;
    $input_email = $email;

    if (empty($nama_lengkap) || empty($email) || empty($kata_sandi_input)) {
        $error_message = "Semua field (Nama Lengkap, Email, Password) harus diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Format email tidak valid.";
    } else {
        $hashed_password = password_hash($kata_sandi_input, PASSWORD_DEFAULT);
        $stmt_check_email = $conn->prepare("SELECT id_pengguna FROM Pengguna WHERE email = ?");
        if ($stmt_check_email) {
            $stmt_check_email->bind_param("s", $email);
            $stmt_check_email->execute();
            $result_check_email = $stmt_check_email->get_result();

            if ($result_check_email->num_rows > 0) {
                $error_message = "Email sudah terdaftar. Silakan gunakan email lain.";
            } else {
                $stmt = $conn->prepare("INSERT INTO Pengguna (nama_lengkap, email, kata_sandi) VALUES (?, ?, ?)");
                if ($stmt) {
                    $stmt->bind_param("sss", $nama_lengkap, $email, $hashed_password);
                    if ($stmt->execute()) {
                        $message = "Pengguna baru berhasil ditambahkan!<br>";
                        $message .= "Nama Lengkap: " . htmlspecialchars($nama_lengkap) . "<br>";
                        $message .= "Email: " . htmlspecialchars($email);
                        $input_nama_lengkap = "";
                        $input_email = "";
                    } else {
                        $error_message = "Gagal menambahkan pengguna: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    $error_message = "Gagal mempersiapkan statement insert: " . $conn->error;
                }
            }
            $stmt_check_email->close();
        } else {
             $error_message = "Gagal mempersiapkan statement check email: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Tambah Pengguna</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;600;700&display=swap" rel="stylesheet">
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
        <?php if ($message): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if ($error_message): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <div class="form-box-centered">
            <h2>Form Input Data Pengguna Baru</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="form-group">
                    <label for="nama_lengkap">Nama Lengkap:</label>
                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap"
                        value="<?php echo htmlspecialchars($input_nama_lengkap); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email"
                        value="<?php echo htmlspecialchars($input_email); ?>" required>
                </div>
                <div class="form-group">
                    <label for="kata_sandi">Password:</label>
                    <input type="password" class="form-control" id="kata_sandi" name="kata_sandi" required>
                </div>
                <button type="submit" class="btn btn-primary">Tambah Pengguna</button>
            </form>
        </div>
    </div>
    <div class="watermark">Kelompok 9 - SIREG 4A</div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
<?php
if (isset($conn)) {
    $conn->close();
}
?>