<?php
session_start();
require_once '../functions.php';

check_admin_login();

if (isset($_GET['delete_user']) && !empty($_GET['delete_user'])) {
    $username_to_delete = $_GET['delete_user'];
    delete_user_by_username($username_to_delete);
    header("Location: dashboard.php");
    exit();
}

if (isset($_GET['delete_message']) && filter_var($_GET['delete_message'], FILTER_VALIDATE_INT)) {
    $message_id_to_delete = (int)$_GET['delete_message'];
    delete_contact_message($message_id_to_delete); // Pastikan fungsi ini ada di functions.php
    header("Location: dashboard.php");
    exit();
}

$toast_message_data = get_toast_message();

$total_users = get_total_users();
$total_items = get_total_items();
$admin_username = get_admin_username();

$search_user = isset($_GET['search_user']) ? trim($_GET['search_user']) : '';
$result_users = search_users($search_user);
if ($result_users === false) {
    $user_fetch_error = "Gagal mengambil data pengguna.";
    $result_users = null;
}
$user_count = $result_users ? $result_users->num_rows : 0;

$contact_messages = get_all_contact_messages(); // Panggil fungsi untuk mengambil pesan
$contact_message_count = $contact_messages ? $contact_messages->num_rows : 0;
$contact_fetch_error = $contact_messages === false ? "Gagal mengambil data pesan kontak." : "";


date_default_timezone_set('Asia/Jakarta');
$nama_hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$nama_bulan = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$now = new DateTime('now');
$hari_index = (int)$now->format('w');
$tanggal = $now->format('d');
$bulan_index = (int)$now->format('n');
$tahun = $now->format('Y');
$tanggal_sekarang = $nama_hari[$hari_index] . ', ' . $tanggal . ' ' . $nama_bulan[$bulan_index] . ' ' . $tahun;

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - JUBEKA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <style>
    :root {
        --bs-body-bg: #121212;
        --bs-body-color: #e0e0e0;
        --bs-secondary-color: #adb5bd;
        --bs-tertiary-bg: #1f1f1f;
        --bs-primary: #00aaff;
        --bs-info: #0dcaf0;
        --bs-light: #f8f9fa;
        --bs-dark: #212529;
        --bs-border-color: #444;
        --bs-border-color-translucent: rgba(255, 255, 255, 0.1);
        --card-bg: #1f1f1f;
        --card-border-color: var(--bs-border-color);
        --link-color: #0dcaf0;
        --bs-success-rgb: 40, 167, 69;
        --bs-danger-rgb: 220, 53, 69;
        --bs-warning-rgb: 255, 193, 7;
        --bs-info-rgb: 13, 202, 240;
        --bs-dark-rgb: 33, 37, 41;
        --bs-light-rgb: 248, 249, 250;
    }

    body {
        background-color: var(--bs-body-bg);
        color: var(--bs-body-color);
        display: flex;
        min-height: 100vh;
        overflow-x: hidden;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .sidebar {
        width: 250px;
        background-color: var(--card-bg);
        padding: 1.5rem 1rem;
        display: flex;
        flex-direction: column;
        flex-shrink: 0;
        border-right: 1px solid var(--bs-border-color);
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        overflow-y: auto;
        z-index: 1000;
    }

    .sidebar .nav-link {
        color: #adb5bd;
        padding: 0.75rem 1rem;
        border-radius: 0.375rem;
        margin-bottom: 0.25rem;
        transition: background-color 0.2s ease, color 0.2s ease;
    }

    .sidebar .nav-link:hover {
        background-color: #343a40;
        color: #fff;
    }

    .sidebar .nav-link.active {
        background-color: var(--bs-primary);
        color: #fff;
        font-weight: 500;
    }

    .sidebar .nav-link i {
        margin-right: 0.75rem;
        width: 20px;
        text-align: center;
    }

    .sidebar .logout-btn {
        margin-top: auto;
    }

    .main-content {
        flex-grow: 1;
        padding: 2.5rem;
        overflow-y: auto;
        margin-left: 250px;
        min-height: 100vh;
    }

    .welcome-header {
        margin-bottom: 2rem;
    }

    .welcome-header .date {
        font-size: 0.9rem;
        color: var(--bs-secondary-color);
    }

    .stat-card-link {
        text-decoration: none;
        color: inherit;
    }

    .stat-card {
        background-color: var(--card-bg);
        border: 1px solid var(--bs-border-color);
        border-radius: 0.75rem;
        color: var(--bs-body-color);
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.25);
        border-color: var(--bs-primary);
    }

    .stat-card .card-body {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.5rem;
    }

    .stat-card .stat-icon {
        font-size: 2.8rem;
        opacity: 0.3;
        transition: opacity 0.3s ease;
    }

    .stat-card:hover .stat-icon {
        opacity: 0.5;
    }

    .stat-card .stat-value {
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--bs-primary);
        line-height: 1;
    }

    .stat-card .card-title {
        font-size: 0.95rem;
        color: #adb5bd;
        text-transform: uppercase;
        margin-bottom: 0.3rem;
        font-weight: 500;
    }

    .card.main-table-card {
        background-color: var(--card-bg);
        border: 1px solid var(--bs-border-color);
        border-radius: 0.75rem;
        margin-bottom: 2.5rem;
    }

    .card-header.main-table-header {
        background-color: #343a40;
        color: #fff;
        border-bottom: 1px solid var(--bs-border-color);
        border-top-left-radius: calc(0.75rem - 1px);
        border-top-right-radius: calc(0.75rem - 1px);
        padding: 1rem 1.5rem;
        font-weight: 500;
    }

    .table-action-bar {
        background-color: #2a2a2a;
        border-bottom: 1px solid var(--bs-border-color);
        padding: 1rem 1.5rem;
    }

    .table {
        color: var(--bs-body-color);
        border-color: var(--bs-border-color);
        margin-bottom: 0;
        font-size: 0.9rem;
    }

    .table th {
        background-color: #343a40;
        color: #fff;
        border-color: var(--bs-border-color);
        font-weight: 500;
        white-space: nowrap;
    }

    .table td {
        border-color: var(--bs-border-color);
        vertical-align: middle;
    }

    .table td.message-col,
    .table td.alamat-col {
        white-space: pre-wrap;
        word-break: break-word;
        min-width: 200px;
        max-width: 250px;
    }


    .table-hover tbody tr:hover {
        background-color: #2c2c2c !important;
        color: #fff;
    }

    .table-striped tbody tr:nth-of-type(odd)>* {
        background-color: rgba(255, 255, 255, 0.03);
    }

    .form-control-dark {
        background-color: #2a2a2a;
        border: 1px solid var(--bs-border-color);
        color: #fff;
    }

    .form-control-dark::placeholder {
        color: #6c757d;
    }

    .form-control-dark:focus {
        background-color: #333;
        border-color: var(--bs-primary);
        box-shadow: 0 0 0 0.25rem rgba(0, 170, 255, 0.25);
        color: #fff;
    }

    .btn-primary {
        background-color: var(--bs-primary);
        border-color: var(--bs-primary);
    }

    .btn-primary:hover {
        filter: brightness(110%);
    }

    .btn-outline-light {
        color: #f8f9fa;
        border-color: #f8f9fa;
    }

    .btn-outline-light:hover {
        color: #121212;
        background-color: #f8f9fa;
        border-color: #f8f9fa;
    }

    .btn-sm {
        padding: 0.3rem 0.6rem;
        font-size: 0.8rem;
    }

    #preloader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: var(--bs-body-bg);
        z-index: 9999;
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 1;
        visibility: visible;
        transition: opacity 1s ease-out, visibility 1s ease-out;
    }

    #preloader.hidden {
        opacity: 0;
        visibility: hidden;
    }

    .box-loader {
        width: 50px;
        height: 60px;
        position: relative;
    }

    .box-lid {
        width: 50px;
        height: 10px;
        background-color: var(--bs-primary);
        position: absolute;
        top: 0;
        left: 0;
        border-radius: 2px 2px 0 0;
        transform-origin: bottom left;
        animation: lid-open 3s ease-in-out infinite;
    }

    .box-container {
        width: 50px;
        height: 50px;
        background-color: #555;
        position: absolute;
        bottom: 0;
        left: 0;
        border-radius: 0 0 3px 3px;
    }

    @keyframes lid-open {

        0%,
        100% {
            transform: rotateX(0deg);
        }

        25%,
        75% {
            transform: rotateX(-90deg);
        }
    }

    .toast-container {
        z-index: 1100;
    }

    .toast {
        background-color: rgba(var(--bs-dark-rgb), 0.85);
        backdrop-filter: blur(5px);
        color: var(--bs-light);
        border: 1px solid var(--bs-border-color);
    }

    .toast-header {
        background-color: rgba(var(--bs-dark-rgb), 0.7);
        color: var(--bs-light);
        border-bottom: 1px solid var(--bs-border-color);
    }

    .toast.text-bg-success {
        background-color: rgba(var(--bs-success-rgb), 0.85) !important;
        color: #fff !important;
        border: 1px solid rgba(var(--bs-success-rgb), 0.9);
    }

    .toast.text-bg-danger {
        background-color: rgba(var(--bs-danger-rgb), 0.85) !important;
        color: #fff !important;
        border: 1px solid rgba(var(--bs-danger-rgb), 0.9);
    }

    .toast.text-bg-warning {
        background-color: rgba(var(--bs-warning-rgb), 0.85) !important;
        color: #000 !important;
        border: 1px solid rgba(var(--bs-warning-rgb), 0.9);
    }

    .toast.text-bg-info {
        background-color: rgba(var(--bs-info-rgb), 0.85) !important;
        color: #000 !important;
        border: 1px solid rgba(var(--bs-info-rgb), 0.9);
    }

    .toast .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    .toast.text-bg-warning .btn-close,
    .toast.text-bg-info .btn-close {
        filter: none;
    }

    .modal-content {
        background-color: var(--card-bg);
        color: var(--bs-body-color);
        border: 1px solid var(--bs-border-color);
    }

    .modal-header {
        border-bottom: 1px solid var(--bs-border-color);
    }

    .modal-header .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    .modal-body {
        white-space: pre-wrap;
        word-break: break-word;
    }

    .modal-footer {
        border-top: 1px solid var(--bs-border-color);
    }
    </style>
</head>

<body>
    <div id="preloader">
        <div class="box-loader">
            <div class="box-lid"></div>
            <div class="box-container"></div>
        </div>
    </div>
    <aside class="sidebar d-flex flex-column">
        <a href="dashboard.php"
            class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none"> <i
                class="fas fa-cogs fa-2x me-2"></i><span class="fs-4">Admin JUBEKA</span> </a>
        <hr style="border-color: var(--bs-border-color);">
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item"><a href="dashboard.php" class="nav-link active" aria-current="page"><i
                        class="fas fa-tachometer-alt fa-fw"></i>Dashboard</a></li>
            <li><a href="manage_items.php" class="nav-link"><i class="fas fa-box-open fa-fw"></i>Manajemen Barang</a>
            </li>
            <li><a href="tambah.php" class="nav-link"><i class="fas fa-user-plus fa-fw"></i>Tambah Pengguna</a></li>
        </ul>
        <hr style="border-color: var(--bs-border-color);">
        <div class="logout-btn"> <span class="d-block text-white-50 small mb-1">Login sebagai:
                <?php echo htmlspecialchars($admin_username); ?></span> <a href="logout.php"
                class="btn btn-danger w-100"><i class="fas fa-sign-out-alt me-2"></i>Logout</a> </div>
    </aside>

    <main class="main-content">
        <div class="welcome-header">
            <h1 class="h2">Selamat Datang, <?php echo htmlspecialchars($admin_username); ?>!</h1>
            <p class="date"><i class="far fa-calendar-alt me-1"></i><?php echo $tanggal_sekarang; ?></p>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-lg-6">
                <div class="stat-card h-100">
                    <div class="card-body">
                        <div>
                            <h5 class="card-title">Total Pengguna</h5> <span
                                class="stat-value"><?= $total_users ?></span>
                        </div>
                        <i class="fas fa-users stat-icon text-primary"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <a href="manage_items.php" class="stat-card-link">
                    <div class="stat-card h-100">
                        <div class="card-body">
                            <div>
                                <h5 class="card-title">Total Barang Dijual</h5> <span
                                    class="stat-value"><?= $total_items ?></span>
                            </div>
                            <i class="fas fa-box-open stat-icon text-info"></i>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <?php if (!empty($user_fetch_error)): ?>
        <div class="alert alert-danger"><?php echo $user_fetch_error; ?></div>
        <?php endif; ?>

        <div class="card main-table-card shadow-sm mt-4">
            <div
                class="card-header main-table-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                <h5 class="mb-0 flex-grow-1"><i class="fas fa-users-cog me-2"></i>Manajemen Pengguna</h5>
                <form method="GET" action="dashboard.php" class="d-flex gap-2" style="max-width: 300px;">
                    <input type="text" name="search_user" class="form-control form-control-sm form-control-dark"
                        placeholder="Cari pengguna..." value="<?= htmlspecialchars($search_user) ?>"
                        aria-label="Cari Pengguna">
                    <button type="submit" class="btn btn-sm btn-primary flex-shrink-0"><i
                            class="fas fa-search"></i></button>
                    <?php if ($search_user != ""): ?>
                    <a href="dashboard.php" class="btn btn-sm btn-outline-light flex-shrink-0"
                        title="Reset Pencarian Pengguna"><i class="fas fa-times"></i></a>
                    <?php endif; ?>
                </form>
                <a href="tambah.php" class="btn btn-sm btn-success flex-shrink-0"><i class="fas fa-user-plus"></i>
                    Tambah</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-striped table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>No. Telepon</th>
                                <th class="alamat-col">Alamat</th>
                                <th>Tgl Daftar</th>
                                <th class="text-center">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result_users && $user_count > 0): ?>
                            <?php while ($row = $result_users->fetch_assoc()):
                                   $tgl_daftar_user = isset($row['created_at']) ? date('d M Y H:i', strtotime($row['created_at'])) : '-';
                                   $nomor_telepon_user = !empty($row['nomor_telepon']) ? htmlspecialchars($row['nomor_telepon']) : '<span class="text-muted fst-italic small">N/A</span>';
                                   $alamat_user = !empty($row['alamat']) ? nl2br(htmlspecialchars($row['alamat'])) : '<span class="text-muted fst-italic small">N/A</span>';
                            ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><?= htmlspecialchars($row['username']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td><?= $nomor_telepon_user ?></td>
                                <td class="alamat-col"><?= $alamat_user ?></td>
                                <td><small><?= $tgl_daftar_user ?></small></td>
                                <td class="text-center">
                                    <a href="edit.php?username=<?= urlencode($row['username']) ?>"
                                        class="btn btn-warning btn-sm" title="Edit Pengguna"><i
                                            class="fas fa-edit"></i></a>
                                    <a href="dashboard.php?delete_user=<?= urlencode($row['username']) ?>"
                                        class="btn btn-danger btn-sm" title="Hapus Pengguna"
                                        onclick="return confirm('Yakin ingin menghapus pengguna \'<?= htmlspecialchars(addslashes($row['username'])) ?>\'?')"><i
                                            class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted p-4">
                                    <?php echo ($search_user != "") ? "Tidak ada pengguna yang cocok dengan pencarian Anda." : "Belum ada pengguna terdaftar."; ?>
                                </td>
                            </tr>
                            <?php endif; ?>
                            <?php if($result_users) $result_users->close(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-dark border-top border-secondary text-muted small py-2 px-3"> Menampilkan
                <?= $user_count ?> pengguna. </div>
        </div>

        <?php if (!empty($contact_fetch_error)): ?>
        <div class="alert alert-danger mt-4"><?php echo $contact_fetch_error; ?></div>
        <?php endif; ?>

        <div class="card main-table-card shadow-sm mt-4">
            <div class="card-header main-table-header">
                <h5 class="mb-0"><i class="fas fa-envelope-open-text me-2"></i>Pesan Kontak Masuk</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-striped table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Subjek</th>
                                <th>Tgl Kirim</th>
                                <th class="text-center">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($contact_messages && $contact_message_count > 0): ?>
                            <?php while ($msg = $contact_messages->fetch_assoc()):
                                $tgl_kirim_pesan = isset($msg['tanggal_kirim']) ? date('d M Y H:i', strtotime($msg['tanggal_kirim'])) : '-';
                            ?>
                            <tr>
                                <td><?= $msg['id'] ?></td>
                                <td><?= htmlspecialchars($msg['nama']) ?></td>
                                <td><a
                                        href="mailto:<?= htmlspecialchars($msg['email']) ?>"><?= htmlspecialchars($msg['email']) ?></a>
                                </td>
                                <td><?= htmlspecialchars($msg['subjek']) ?></td>
                                <td><small><?= $tgl_kirim_pesan ?></small></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#messageModal<?= $msg['id'] ?>" title="Lihat Pesan">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="dashboard.php?delete_message=<?= $msg['id'] ?>"
                                        class="btn btn-danger btn-sm" title="Hapus Pesan"
                                        onclick="return confirm('Yakin ingin menghapus pesan dari \'<?= htmlspecialchars(addslashes($msg['nama'])) ?>\'?')"><i
                                            class="fas fa-trash"></i></a>
                                </td>
                            </tr>

                            <div class="modal fade" id="messageModal<?= $msg['id'] ?>" tabindex="-1"
                                aria-labelledby="messageModalLabel<?= $msg['id'] ?>" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="messageModalLabel<?= $msg['id'] ?>">Pesan dari:
                                                <?= htmlspecialchars($msg['nama']) ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Email:</strong> <?= htmlspecialchars($msg['email']) ?></p>
                                            <p><strong>Subjek:</strong> <?= htmlspecialchars($msg['subjek']) ?></p>
                                            <hr>
                                            <p><?= nl2br(htmlspecialchars($msg['pesan'])) ?></p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Tutup</button>
                                            <a href="mailto:<?= htmlspecialchars($msg['email']) ?>?subject=Re: <?= urlencode($msg['subjek']) ?>"
                                                class="btn btn-primary">Balas Email</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted p-4">
                                    Belum ada pesan kontak yang masuk.
                                </td>
                            </tr>
                            <?php endif; ?>
                            <?php if($contact_messages) $contact_messages->close(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-dark border-top border-secondary text-muted small py-2 px-3"> Menampilkan
                <?= $contact_message_count ?> pesan. </div>
        </div>


    </main>

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="liveToast" class="toast align-items-center border-0" role="alert" aria-live="assertive"
            aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="toast-body-content"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const preloader = document.getElementById('preloader');
        if (preloader) {
            window.addEventListener('load', function() {
                preloader.classList.add('hidden');
            });
        }

        const toastLiveExample = document.getElementById('liveToast');
        const toastMessageData = <?php echo json_encode($toast_message_data ?? null); ?>;

        if (toastMessageData && toastLiveExample) {
            const toastBody = toastLiveExample.querySelector('#toast-body-content');
            let alertType = toastMessageData.type || 'info';
            let message = toastMessageData.message || 'Tidak ada pesan.';
            toastLiveExample.classList.remove('text-bg-success', 'text-bg-danger', 'text-bg-warning',
                'text-bg-info');
            let toastClass = 'text-bg-info';
            if (alertType === 'success') {
                toastClass = 'text-bg-success';
            } else if (alertType === 'danger') {
                toastClass = 'text-bg-danger';
            } else if (alertType === 'warning') {
                toastClass = 'text-bg-warning';
            }
            toastLiveExample.classList.add(toastClass);
            toastBody.textContent = message;
            const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample, {
                delay: 5000
            });
            toastBootstrap.show();
        }
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>