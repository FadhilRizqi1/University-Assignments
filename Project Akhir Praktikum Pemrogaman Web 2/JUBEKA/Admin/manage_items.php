<?php
session_start();
require_once '../functions.php';

check_admin_login();

if (isset($_GET['delete_item']) && filter_var($_GET['delete_item'], FILTER_VALIDATE_INT)) {
    $item_id_to_delete = (int)$_GET['delete_item'];
    if (hapus_barang($item_id_to_delete)) {
        set_toast_message('success', "Barang ID #{$item_id_to_delete} berhasil dihapus.");
    } else {
        set_toast_message('danger', "Gagal menghapus barang ID #{$item_id_to_delete}.");
    }
    $redirect_url = strtok($_SERVER["REQUEST_URI"], '?');
    header("Location: " . $redirect_url);
    exit();
}

$toast_message_data = get_toast_message();

$search_item = isset($_GET['search_item']) ? trim($_GET['search_item']) : '';
$filter_kategori_id = isset($_GET['filter_kategori']) ? (int)$_GET['filter_kategori'] : null;

$kategori_list = get_all_kategori();
$result_items = tampil_semua_barang($search_item, $filter_kategori_id); 

if ($result_items === false) {
    die("Gagal mengambil data barang dari database.");
}
$total_item_count = $result_items ? $result_items->num_rows : 0;

$admin_username = get_admin_username();

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Barang - Admin JUBEKA</title>
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

    .card.main-table-card {
        background-color: var(--card-bg);
        border: 1px solid var(--bs-border-color);
        border-radius: 0.75rem;
    }

    .card-header.main-table-header {
        background-color: #343a40;
        color: #fff;
        border-bottom: 1px solid var(--bs-border-color);
        border-top-left-radius: calc(0.75rem - 1px);
        border-top-right-radius: calc(0.75rem - 1px);
        padding: 1rem 1.5rem;
    }

    .filter-form-container {
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
        white-space: nowrap;
        font-weight: 500;
    }

    .table td {
        border-color: var(--bs-border-color);
        vertical-align: middle;
    }

    .table td.alamat-col {
        min-width: 200px;
        max-width: 300px;
        white-space: normal;
        word-break: break-word;
    }

    .table-hover tbody tr:hover {
        background-color: #2c2c2c !important;
        color: #fff;
    }

    .table-striped tbody tr:nth-of-type(odd)>* {
        background-color: rgba(255, 255, 255, 0.03);
    }

    .item-thumbnail {
        width: 45px;
        height: 45px;
        object-fit: cover;
        border-radius: 0.25rem;
        background-color: #333;
        vertical-align: middle;
    }

    .form-control-dark,
    .form-select-dark {
        background-color: #2a2a2a;
        border: 1px solid var(--bs-border-color);
        color: #fff;
        font-size: 0.9rem;
    }

    .form-control-dark::placeholder {
        color: #6c757d;
    }

    .form-control-dark:focus,
    .form-select-dark:focus {
        background-color: #333;
        border-color: var(--bs-primary);
        box-shadow: 0 0 0 0.25rem rgba(0, 170, 255, 0.25);
        color: #fff;
    }

    .form-select-dark {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23adb5bd' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
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

    .action-buttons .btn {
        margin-right: 0.3rem;
    }

    .text-muted {
        color: var(--bs-secondary-color) !important;
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
            <li class="nav-item"><a href="dashboard.php" class="nav-link"><i
                        class="fas fa-tachometer-alt fa-fw"></i>Dashboard</a></li>
            <li><a href="manage_items.php" class="nav-link active" aria-current="page"><i
                        class="fas fa-box-open fa-fw"></i>Manajemen Barang</a></li>
            <li><a href="tambah.php" class="nav-link"><i class="fas fa-user-plus fa-fw"></i>Tambah Pengguna</a></li>
        </ul>
        <hr style="border-color: var(--bs-border-color);">
        <div class="logout-btn"> <span class="d-block text-white-50 small mb-1">Login sebagai:
                <?php echo htmlspecialchars($admin_username); ?></span> <a href="logout.php"
                class="btn btn-danger w-100"><i class="fas fa-sign-out-alt me-2"></i>Logout</a> </div>
    </aside>

    <main class="main-content">
        <h1 class="h2 mb-4">Manajemen Barang</h1>

        <div class="card main-table-card shadow-sm">
            <div class="card-header main-table-header">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Daftar Barang Terposting</h5>
            </div>
            <div class="filter-form-container">
                <form method="GET" action="manage_items.php" class="row g-3 align-items-end">
                    <div class="col-md">
                        <label for="search-input" class="form-label small mb-1">Cari Barang</label>
                        <input type="text" id="search-input" name="search_item"
                            class="form-control form-control-sm form-control-dark" placeholder="Nama atau deskripsi..."
                            value="<?= htmlspecialchars($search_item) ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="filter_kategori" class="form-label small mb-1">Filter Kategori</label>
                        <select name="filter_kategori" id="filter_kategori"
                            class="form-select form-select-sm form-select-dark">
                            <option value="">Semua Kategori</option>
                            <?php foreach($kategori_list as $kat): ?>
                            <option value="<?php echo $kat['id']; ?>"
                                <?php echo ($filter_kategori_id == $kat['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($kat['nama_kategori']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-auto">
                        <button type="submit" class="btn btn-sm btn-primary w-100"><i class="fas fa-filter"></i>
                            Filter</button>
                    </div>
                    <?php if ($search_item != "" || $filter_kategori_id !== null): ?>
                    <div class="col-md-auto">
                        <a href="manage_items.php" class="btn btn-sm btn-outline-light w-100"><i
                                class="fas fa-times"></i> Reset</a>
                    </div>
                    <?php endif; ?>
                </form>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-striped table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th style="width: 60px;">Gambar</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Diposting Oleh</th>
                                <th>No. Telepon</th>
                                <th class="alamat-col">Alamat Barang</th>
                                <th>Tgl Post</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result_items && $total_item_count > 0): ?>
                            <?php while ($item = $result_items->fetch_assoc()): ?>
                            <?php
                                     $image_path = '../Assets/' . (!empty($item['gambar']) ? $item['gambar'] : 'placeholder.png');
                                     $placeholder_path = '../Assets/placeholder.png';
                                     $detail_url = '../detail-barang.php?id=' . $item['id'];
                                     $tanggal_formatted = isset($item['tanggal_post']) ? date('d/m/y H:i', strtotime($item['tanggal_post'])) : '-';
                                     $nama_kategori = $item['nama_kategori'] ?? '<span class="text-muted fst-italic small">N/A</span>';
                                     $nama_pemosting = $item['nama_pemosting'] ?? '<span class="text-muted fst-italic small">N/A</span>';
                                     $nomor_telepon_penjual = $item['nomor_telepon_penjual'] ?? '<span class="text-muted fst-italic small">-</span>';
                                     $alamat_penjual = $item['alamat_penjual'] ?? '<span class="text-muted fst-italic small">-</span>';
                                ?>
                            <tr>
                                <td><?= $item['id'] ?></td>
                                <td><img src="<?= htmlspecialchars($image_path) ?>" alt="" class="item-thumbnail"
                                        onerror="this.onerror=null; this.src='<?= htmlspecialchars($placeholder_path) ?>';">
                                </td>
                                <td><?= htmlspecialchars($item['nama_barang']) ?></td>
                                <td><?= $nama_kategori ?></td>
                                <td>Rp <?= number_format($item['harga'] ?? 0, 0, ',', '.') ?></td>
                                <td><?= $nama_pemosting ?> (ID: <?= $item['user_id'] ?>)</td>
                                <td><?= htmlspecialchars($nomor_telepon_penjual) ?></td>
                                <td class="alamat-col"><?= nl2br(htmlspecialchars($alamat_penjual)) ?></td>
                                <td><small><?= $tanggal_formatted ?></small></td>
                                <td class="text-center action-buttons">
                                    <a href="<?= $detail_url ?>" target="_blank" class="btn btn-info btn-sm"
                                        title="Lihat di Situs"><i class="fas fa-eye"></i></a>
                                    <a href="manage_items.php?delete_item=<?= $item['id'] ?>"
                                        class="btn btn-danger btn-sm" title="Hapus Barang"
                                        onclick="return confirm('Yakin ingin menghapus barang \'<?= htmlspecialchars(addslashes($item['nama_barang'])) ?>\' (ID: <?= $item['id'] ?>)?')"><i
                                            class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center text-muted p-4">
                                    <?php echo ($search_item != "" || $filter_kategori_id !== null) ? "Tidak ada barang yang cocok dengan filter/pencarian Anda." : "Belum ada barang yang terdaftar."; ?>
                                </td>
                            </tr>
                            <?php endif; ?>
                            <?php if($result_items) $result_items->close(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-dark border-top border-secondary text-muted small py-2 px-3"> Menampilkan
                <?= $total_item_count ?> barang. </div>
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