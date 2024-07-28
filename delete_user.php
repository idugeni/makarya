<?php
session_start();
require_once('./include/db_connection.php');

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'superadmin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_GET['id'];

    // Ambil nama file gambar dari database
    $stmt = $conn->prepare("SELECT foto FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($foto);
    $stmt->fetch();
    $stmt->close();

    // Hapus file gambar dari directory
    if ($foto) {
        $fotoPath = 'uploads/users/' . $foto;
        if (file_exists($fotoPath)) {
            unlink($fotoPath);
        }
    }

    // Hapus pengguna dari database
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        // Mengatur ulang ID
        resetAutoIncrement($conn);
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }

    $stmt->close();
}

// Fungsi untuk mengatur ulang ID
function resetAutoIncrement($conn)
{
    $result = $conn->query("SELECT id FROM users ORDER BY id");
    $counter = 1;

    while ($row = $result->fetch_assoc()) {
        $conn->query("UPDATE users SET id = $counter WHERE id = " . $row['id']);
        $counter++;
    }
    $conn->query("ALTER TABLE users AUTO_INCREMENT = $counter");
}
