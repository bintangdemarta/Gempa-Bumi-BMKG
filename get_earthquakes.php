<?php
$servername = "localhost";
$username = "root"; // ganti dengan username MySQL Anda
$password = ""; // ganti dengan password MySQL Anda
$dbname = "gempa_db";

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$sql = "SELECT * FROM gempa ORDER BY id DESC";
$result = $conn->query($sql);

$earthquakes = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $earthquakes[] = $row;
    }
}

echo json_encode($earthquakes);

$conn->close();
?>
