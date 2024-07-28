<?php
session_start();
require_once('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mengambil input dan melakukan sanitasi
    $username = trim($_POST['username']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validasi input
    if (empty($username) || empty($new_password) || empty($confirm_password)) {
        echo json_encode(['status' => 'error', 'message' => 'Semua field harus diisi.']);
        exit;
    }

    // Cek kesesuaian password
    if ($new_password !== $confirm_password) {
        echo json_encode(['status' => 'error', 'message' => 'Kata sandi baru dan konfirmasi kata sandi tidak cocok.']);
        exit;
    }

    // Cek role pengguna
    $stmt = $conn->prepare("SELECT role FROM users WHERE username = ?");
    if ($stmt === false) {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyiapkan statement.']);
        exit;
    }

    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->bind_result($role);
    $stmt->fetch();
    $stmt->close();

    // Periksa apakah role adalah 'superadmin' atau 'admin'
    if ($role === 'superadmin' || $role === 'admin') {
        echo json_encode(['status' => 'error', 'message' => 'Tidak dapat mereset password untuk role "superadmin" atau "admin".']);
        exit;
    }

    // Enkripsi password
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    // Update password di database
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
    if ($stmt === false) {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyiapkan statement.']);
        exit;
    }

    $stmt->bind_param('ss', $hashed_password, $username);

    if ($stmt->execute()) {
        $stmt->close();
        echo json_encode(['status' => 'success', 'message' => 'Password berhasil diperbarui.']);
        // Redirect ke halaman login
        header("Location: /");
        exit();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui password.']);
    }

    $stmt->close();
}
