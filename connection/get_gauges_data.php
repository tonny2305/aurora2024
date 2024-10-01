<?php
header('Content-Type: application/json'); // Mengatur header untuk JSON

// Sambungkan ke database
$conn = new mysqli('localhost', 'root', '', 'speed_course_db');

// Cek koneksi
if ($conn->connect_error) {
    echo json_encode(array("error" => "Connection failed: " . $conn->connect_error));
    exit();
}

// Menggunakan prepared statement
$stmt = $conn->prepare("SELECT sog_knot, sog_kmh, cog_degree, lat, lon FROM gauges ORDER BY id DESC LIMIT 1");
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();

    // Validasi data lat dan lon
    $data['lat'] = filter_var($data['lat'], FILTER_VALIDATE_FLOAT);
    $data['lon'] = filter_var($data['lon'], FILTER_VALIDATE_FLOAT);

    if ($data['lat'] === false || $data['lon'] === false) {
        echo json_encode(array("error" => "Invalid latitude or longitude format"));
    } else {
        echo json_encode($data);
    }
} else {
    echo json_encode(array("error" => "No data found"));
}

$stmt->close();
$conn->close();
?>
