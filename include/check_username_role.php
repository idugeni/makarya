<?php
require_once('./db_connection.php');

// Mengatur header untuk JSON
header('Content-Type: application/json');

// Inisialisasi respons default
$response = array('exists' => false, 'role' => '', 'error' => '');

// Validasi dan sanitasi input
if (isset($_GET['username']) && !empty(trim($_GET['username']))) {
    $username = trim($_GET['username']);

    // Menggunakan prepared statement untuk mencegah SQL Injection
    if ($stmt = $conn->prepare("SELECT role FROM users WHERE username = ?")) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($role);

        // Mengecek hasil query
        if ($stmt->fetch()) {
            $response['exists'] = true;
            $response['role'] = $role;
        }

        $stmt->close();
    } else {
        // Menangani kesalahan dalam prepare statement
        $response['error'] = 'Database query error';
    }
} else {
    // Menangani permintaan yang tidak valid
    $response['error'] = 'Invalid request';
}

// Menutup koneksi
$conn->close();

// Mengirimkan respons dalam format JSON
echo json_encode($response);
