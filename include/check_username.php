<?php
require_once('db_connection.php');

header('Content-Type: application/json');

// Inisialisasi respons default
$response = ['exists' => false, 'error' => ''];

// Validasi dan sanitasi input
if (isset($_GET['username']) && !empty(trim($_GET['username']))) {
    $username = trim($_GET['username']);

    // Menggunakan prepared statement untuk mencegah SQL Injection
    if ($stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE LOWER(username) = LOWER(?)")) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();

        if ($count > 0) {
            $response['exists'] = true;
        } else {
            $response['exists'] = false;
        }

        $stmt->close();
    } else {
        // Menangani kesalahan dalam prepare statement
        $response['error'] = 'Database query preparation error';
    }
} else {
    // Menangani permintaan yang tidak valid
    $response['error'] = 'Invalid request: Username parameter is missing or empty';
}

// Menutup koneksi
$conn->close();

// Mengirimkan respons dalam format JSON
echo json_encode($response);
