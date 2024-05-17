<?php
// Koneksi ke database
$koneksi = mysqli_connect("localhost", "root", "", "gempa_db");

// Pastikan koneksi berhasil
if (mysqli_connect_errno()) {
    echo "Koneksi database gagal: " . mysqli_connect_error();
    exit();
}

// Ambil data gempa baru dari API BMKG
// Lakukan pengambilan data dari API BMKG disini

// Simpan data gempa baru ke dalam database
$tanggal_gempa_baru = 'tanggal_gempa_baru'; // Isi dengan tanggal gempa baru dari API BMKG
$jam_gempa_baru = 'jam_gempa_baru'; // Isi dengan jam gempa baru dari API BMKG

// Query untuk memeriksa apakah data gempa dengan tanggal dan jam yang sama sudah ada di database
$query = "SELECT COUNT(*) AS total FROM gempa WHERE tanggal = '$tanggal_gempa_baru' AND jam = '$jam_gempa_baru'";
$result = mysqli_query($koneksi, $query);

// Periksa hasil query
if ($result) {
    // Ambil jumlah total gempa dengan tanggal dan jam yang sama dari hasil query
    $row = mysqli_fetch_assoc($result);
    $total = $row['total'];

    // Jika tidak ada gempa dengan tanggal dan jam yang sama
    if ($total == 0) {
        // Kirim notifikasi ke bot Telegram
        // Lakukan pengiriman notifikasi ke bot Telegram disini

        // Simpan data gempa baru ke dalam database
        // Lakukan penyimpanan data gempa baru ke dalam database disini
    } else {
        // Jika sudah ada gempa dengan tanggal dan jam yang sama, abaikan pembaruan
        echo "Data gempa sudah ada di database";
    }
} else {
    // Jika query gagal dieksekusi
    echo "Error: " . mysqli_error($koneksi);
}

// Tutup koneksi ke database
mysqli_close($koneksi);
?>
