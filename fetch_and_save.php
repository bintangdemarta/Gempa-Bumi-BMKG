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

$apiUrl = "https://data.bmkg.go.id/DataMKG/TEWS/autogempa.json";

$response = file_get_contents($apiUrl);
$data = json_decode($response, true);

if (isset($data['Infogempa']['gempa'])) {
    $gempa = $data['Infogempa']['gempa'];
    $tanggal = $gempa['Tanggal'];
    $jam = $gempa['Jam'];
    $koordinat = $gempa['Coordinates'];
    $magnitude = $gempa['Magnitude'];
    $kedalaman = $gempa['Kedalaman'];
    $wilayah = $gempa['Wilayah'];
    $dirasakan = $gempa['Dirasakan'];
    $shakemap = $gempa['Shakemap'];
    $waktu_terakhir = date('Y-m-d H:i:s');

    $sql = "INSERT INTO gempa (tanggal, jam, koordinat, magnitude, kedalaman, wilayah, dirasakan, shakemap, waktu_terakhir)
            VALUES ('$tanggal', '$jam', '$koordinat', '$magnitude', '$kedalaman', '$wilayah', '$dirasakan', '$shakemap', '$waktu_terakhir')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['message' => 'Data gempa saved to database', 'data' => $gempa]);

        // Send notification to Telegram
        sendTelegramMessage($gempa);
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Error fetching data";
}

$conn->close();

function sendTelegramMessage($gempa) {
    $botToken = '6777441939:AAHuIVXs3C7b9NyVn_lV1sBjLgKxmTkRMak';
    $chatId = '1292347016';

    $message = "Gempa Terkini:\nTanggal: {$gempa['Tanggal']}\nJam: {$gempa['Jam']}\nKoordinat: {$gempa['Coordinates']}\nMagnitude: {$gempa['Magnitude']}\nKedalaman: {$gempa['Kedalaman']}\nWilayah: {$gempa['Wilayah']}\nDirasakan: {$gempa['Dirasakan']}\nShakemap: {$gempa['Shakemap']}";

    $url = "https://api.telegram.org/bot{$botToken}/sendMessage";

    $postData = [
        'chat_id' => $chatId,
        'text' => $message
    ];

    $options = [
        'http' => [
            'method'  => 'POST',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => http_build_query($postData)
        ]
    ];

    $context  = stream_context_create($options);
    file_get_contents($url, false, $context);
}
?>
