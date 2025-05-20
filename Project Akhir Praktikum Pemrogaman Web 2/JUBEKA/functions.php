<?php
include 'auth/koneksi.php';

function set_toast_message($type, $message) {
    if (session_status() == PHP_SESSION_NONE) { session_start(); }
    $_SESSION['toast_message'] = ['type' => $type, 'message' => $message];
}

function get_toast_message() {
    if (session_status() == PHP_SESSION_NONE) { session_start(); }
    $toast_message_data = null;
    if (isset($_SESSION['toast_message'])) {
        $toast_message_data = $_SESSION['toast_message'];
        unset($_SESSION['toast_message']);
    }
    return $toast_message_data;
}

function simpan_jual($user_id, $nama_barang, $deskripsi, $harga, $gambar, $kategori_id = null, $nomor_telepon_penjual, $alamat_penjual) {
    global $conn;
    $sql = "INSERT INTO jual (user_id, nama_barang, deskripsi, harga, kategori_id, gambar, nomor_telepon_penjual, alamat_penjual) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) { error_log("P(sj):(".$conn->errno.")".$conn->error); return false; }
    $hf = (float)$harga;
    $ki = ($kategori_id !== null && $kategori_id !== '') ? (int)$kategori_id : null;
    $stmt->bind_param("issdisss", $user_id, $nama_barang, $deskripsi, $hf, $ki, $gambar, $nomor_telepon_penjual, $alamat_penjual);
    $result = $stmt->execute();
    if(!$result) { error_log("E(sj):(".$stmt->errno.")".$stmt->error); }
    $stmt->close();
    return $result;
}

function upload_gambar($tmp_gambar, $gambar, $is_admin = false) {
     $target_dir = $is_admin ? '../Assets/' : 'Assets/';
    if (!is_dir($target_dir) && !mkdir($target_dir, 0777, true) && !is_dir($target_dir)) { error_log("Failed to create directory: " . $target_dir); return false; }
    $target_file = $target_dir . basename($gambar); return move_uploaded_file($tmp_gambar, $target_file);
}

function tampil_barang_user($user_id) {
    global $conn;
    $query = "SELECT j.id, j.nama_barang, j.harga, j.gambar, j.tanggal_post, k.nama_kategori, j.nomor_telepon_penjual, j.alamat_penjual FROM jual j LEFT JOIN kategori k ON j.kategori_id = k.id WHERE j.user_id = ? ORDER BY j.id DESC";
    $stmt = $conn->prepare($query);
    if (!$stmt) { error_log("P(tbu):(".$conn->errno.")".$conn->error); return false; }
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) { return $stmt->get_result(); }
    else { error_log("E(tbu):(".$stmt->errno.")".$stmt->error); $stmt->close(); return false; }
}

function tampil_semua_barang($search_term = '', $kategori_id = null, $limit = null) {
    global $conn;
    $query = "SELECT j.id, j.user_id, j.nama_barang, j.deskripsi, j.harga, j.gambar, j.tanggal_post, k.nama_kategori, j.nomor_telepon_penjual, j.alamat_penjual, u.username as nama_pemosting FROM jual j LEFT JOIN kategori k ON j.kategori_id = k.id LEFT JOIN users u ON j.user_id = u.id";
    $params = []; $types = ""; $where_clauses = [];
    if (!empty($search_term)) { $where_clauses[] = "(j.nama_barang LIKE ? OR j.deskripsi LIKE ?)"; $sl = "%".$search_term."%"; $params[] = $sl; $params[] = $sl; $types .= "ss"; }
    if ($kategori_id !== null && $kategori_id > 0) { $where_clauses[] = "j.kategori_id = ?"; $params[] = $kategori_id; $types .= "i"; }
    if (!empty($where_clauses)) { $query .= " WHERE " . implode(" AND ", $where_clauses); }
    $query .= " ORDER BY j.id DESC";
    if ($limit !== null && $limit > 0) { $query .= " LIMIT ?"; $params[] = $limit; $types .= "i"; }
    $stmt = $conn->prepare($query);
    if (!$stmt) { error_log("P(tsb):(".$conn->errno.")".$conn->error." SQL:".$query); return false; }
    if (!empty($types)) { $stmt->bind_param($types, ...$params); }
    if ($stmt->execute()) { return $stmt->get_result(); }
    else { error_log("E(tsb):(".$stmt->errno.")".$stmt->error); $stmt->close(); return false; }
}

function hapus_barang($id) {
    global $conn; $img_name = ''; $is_admin_context = strpos($_SERVER['SCRIPT_FILENAME'], DIRECTORY_SEPARATOR . 'Admin' . DIRECTORY_SEPARATOR) !== false;
    $stmt_get_img = $conn->prepare("SELECT gambar FROM jual WHERE id = ?");
    if ($stmt_get_img) { $stmt_get_img->bind_param("i", $id); if($stmt_get_img->execute()){ $result_img = $stmt_get_img->get_result(); if($result_img->num_rows > 0) { $img_data = $result_img->fetch_assoc(); $img_name = $img_data['gambar']; } } else { error_log("E(hs SELECT):(".$stmt_get_img->errno.")".$stmt_get_img->error); } $stmt_get_img->close(); } else { error_log("P(hs SELECT):(".$conn->errno.")".$conn->error); }
    $query = "DELETE FROM jual WHERE id = ?"; $stmt = $conn->prepare($query); if (!$stmt) { error_log("P(hs DELETE):(".$conn->errno.")".$conn->error); return false; }
    $stmt->bind_param("i", $id); $result = $stmt->execute(); $affected_rows = $stmt->affected_rows;
    if(!$result) { error_log("E(hs DELETE):(".$stmt->errno.")".$stmt->error); } else { if ($affected_rows > 0 && !empty($img_name)) { $base_dir = $is_admin_context ? '../Assets/' : 'Assets/'; $file_path = $base_dir . $img_name; if (file_exists($file_path)) { if (is_writable(dirname($file_path)) && is_writable($file_path)) { if (!@unlink($file_path)) { error_log("unlink failed: ".$file_path); } } else { error_log("not writable: ".$file_path); } } else { error_log("not found: ".$file_path); } } }
    $stmt->close(); return $result && $affected_rows > 0;
}

function get_barang_by_id($id, $user_id_for_check = null) {
    global $conn;
    $item = null;
    $query = "SELECT j.*, k.nama_kategori, u.username as nama_pemosting
              FROM jual j
              LEFT JOIN kategori k ON j.kategori_id = k.id
              LEFT JOIN users u ON j.user_id = u.id 
              WHERE j.id = ?";
    $params = [$id];
    $types = "i";

    if ($user_id_for_check !== null) {
        $query .= " AND j.user_id = ?";
        $params[] = $user_id_for_check;
        $types .= "i";
    }
    
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param($types, ...$params);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows === 1) {
                $item = $result->fetch_assoc();
            }
        } else {
            error_log("E(gbbi):(".$stmt->errno.")".$stmt->error);
        }
        $stmt->close();
    } else {
        error_log("P(gbbi):(".$conn->errno.")".$conn->error);
    }
    return $item;
}

function update_barang($id, $nama_barang, $deskripsi, $harga, $gambar_baru, $user_id, $kategori_id = null, $nomor_telepon_penjual, $alamat_penjual) {
    global $conn;
    $sql = "UPDATE jual SET nama_barang = ?, deskripsi = ?, harga = ?, kategori_id = ?, gambar = ?, nomor_telepon_penjual = ?, alamat_penjual = ? WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) { error_log("P(ub):(".$conn->errno.")".$conn->error); return false; }
    $hf = (float)$harga;
    $ki = ($kategori_id !== null && $kategori_id !== '') ? (int)$kategori_id : null;
    $stmt->bind_param("ssdisssii", $nama_barang, $deskripsi, $hf, $ki, $gambar_baru, $nomor_telepon_penjual, $alamat_penjual, $id, $user_id);
    $s = $stmt->execute();
    if (!$s) { error_log("E(ub):(".$stmt->errno.")".$stmt->error); }
    $stmt->close();
    return $s;
}

function check_admin_login() {
    if (session_status() == PHP_SESSION_NONE) { session_start(); }
    if (!isset($_SESSION["admin_login"]) || $_SESSION["admin_login"] !== true) {
        header("Location: ../auth/login.php");
        exit();
    }
}

function get_total_users() {
    global $conn;
    $q = "SELECT COUNT(*) AS total FROM users";
    $r = $conn->query($q);
    return ($r && $r->num_rows > 0) ? $r->fetch_assoc()['total'] : 0;
}

function get_total_items() {
    global $conn;
    $q = "SELECT COUNT(*) AS total FROM jual";
    $r = $conn->query($q);
    return ($r && $r->num_rows > 0) ? $r->fetch_assoc()['total'] : 0;
}

function delete_user_by_username($username) {
    global $conn; $a=0;
    if(session_status()==PHP_SESSION_NONE){session_start();}
    $s=$conn->prepare("DELETE FROM users WHERE username = ?");
    if(!$s){error_log("P(du):(".$conn->errno.")".$conn->error);set_toast_message('danger','Gagal prepare.');return false;}
    $s->bind_param("s", $username);
    if($s->execute()){$a=$s->affected_rows;set_toast_message('success',"User '".htmlspecialchars($username)."' dihapus.");}
    else {error_log("E(du):(".$s->errno.")".$s->error);set_toast_message('danger',"Gagal hapus: ".htmlspecialchars($s->error));}
    $s->close();
    return $a > 0;
}

function search_users($search_term) {
    global $conn;
    $p = "%".$search_term."%";
    $q = "SELECT id, username, email, nomor_telepon, alamat, created_at FROM users WHERE username LIKE ? OR email LIKE ? OR nomor_telepon LIKE ? OR alamat LIKE ? ORDER BY username ASC";
    $s = $conn->prepare($q);
    if(!$s){
        error_log("P(su):(".$conn->errno.")".$conn->error. " Query: " . $q);
        return false;
    }
    $s->bind_param("ssss", $p, $p, $p, $p); 
    if($s->execute()){
        return $s->get_result();
    } else {
        error_log("E(su):(".$s->errno.")".$s->error);
        $s->close();
        return false;
    }
}

function get_admin_username() {
    if(session_status()==PHP_SESSION_NONE){session_start();}
    return $_SESSION['username'] ?? 'Admin';
}

function get_all_kategori() {
    global $conn; $k=[];
    $sql="SELECT id,nama_kategori FROM kategori ORDER BY nama_kategori ASC";
    $r=$conn->query($sql);
    if($r){while($row=$r->fetch_assoc()){$k[]=$row;} $r->free();}
    return $k;
}

function format_tanggal_indonesia($timestamp) {
    if (empty($timestamp)) { return '-'; }
    $bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    try {
        $dt = new DateTime($timestamp, new DateTimeZone('Asia/Jakarta'));
        $tanggal = $dt->format('j');
        $bulan_index = (int)$dt->format('n') - 1;
        $tahun = $dt->format('Y');
        $jam = $dt->format('H:i');
        return $tanggal . ' ' . $bulan[$bulan_index] . ' ' . $tahun . ' - ' . $jam;
    } catch (Exception $e) {
        return '-';
    }
}

function get_user_profile_data($user_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT id, username, email, nomor_telepon, alamat FROM users WHERE id = ?");
    if (!$stmt) {
        error_log("Get User Profile: Prepare failed: (" . $conn->errno . ") " . $conn->error);
        return false;
    }
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            $user_data = $result->fetch_assoc();
            $stmt->close();
            return $user_data;
        }
    } else {
        error_log("Get User Profile: Execute failed: (" . $stmt->errno . ") " . $stmt->error);
    }
    $stmt->close();
    return false;
}

function get_user_profile_data_by_username($username) {
    global $conn;
    $stmt = $conn->prepare("SELECT id, username, email, nomor_telepon, alamat FROM users WHERE username = ?");
    if (!$stmt) {
        error_log("Get User Profile by Username: Prepare failed: (" . $conn->errno . ") " . $conn->error);
        return false;
    }
    $stmt->bind_param("s", $username);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            $user_data = $result->fetch_assoc();
            $stmt->close();
            return $user_data;
        }
    } else {
        error_log("Get User Profile by Username: Execute failed: (" . $stmt->errno . ") " . $stmt->error);
    }
    $stmt->close();
    return false;
}


function update_user_profile($user_id, $username, $email, $nomor_telepon, $alamat, $password = null) {
    global $conn;
    
    $fields_to_update = [];
    $params = [];
    $types = "";

    $fields_to_update[] = "username = ?";
    $params[] = $username;
    $types .= "s";

    $fields_to_update[] = "email = ?";
    $params[] = $email;
    $types .= "s";

    $fields_to_update[] = "nomor_telepon = ?";
    $params[] = $nomor_telepon;
    $types .= "s";

    $fields_to_update[] = "alamat = ?";
    $params[] = $alamat;
    $types .= "s";

    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $fields_to_update[] = "password = ?";
        $params[] = $hashed_password;
        $types .= "s";
    }

    $params[] = $user_id;
    $types .= "i";

    $sql = "UPDATE users SET " . implode(", ", $fields_to_update) . " WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("Update User Profile: Prepare failed: (" . $conn->errno . ") " . $conn->error . " SQL: " . $sql);
        return false;
    }

    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        $stmt->close();
        return true;
    } else {
        error_log("Update User Profile: Execute failed: (" . $stmt->errno . ") " . $stmt->error);
        $stmt->close();
        return false;
    }
}

function get_all_contact_messages() {
    global $conn;
    $query = "SELECT id, nama, email, subjek, pesan, tanggal_kirim FROM contact_messages ORDER BY tanggal_kirim DESC";
    $result = $conn->query($query);
    if (!$result) {
        error_log("Error fetching contact messages: (" . $conn->errno . ") " . $conn->error);
        return false;
    }
    return $result;
}

function delete_contact_message($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM contact_messages WHERE id = ?");
    if (!$stmt) {
        error_log("Prepare delete contact message failed: (" . $conn->errno . ") " . $conn->error);
        set_toast_message('danger', 'Gagal mempersiapkan penghapusan pesan.');
        return false;
    }
    $stmt->bind_param("i", $id);
    $success = false;
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            set_toast_message('success', "Pesan ID #{$id} berhasil dihapus.");
            $success = true;
        } else {
            set_toast_message('warning', "Pesan ID #{$id} tidak ditemukan atau sudah dihapus.");
        }
    } else {
        error_log("Execute delete contact message failed: (" . $stmt->errno . ") " . $stmt->error);
        set_toast_message('danger', "Gagal menghapus pesan ID #{$id}. Error: " . htmlspecialchars($stmt->error));
    }
    $stmt->close();
    return $success;
}

?>