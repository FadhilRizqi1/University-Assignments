<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_kursus_daring";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>