<?php
require_once('include/db_connection.php');
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Periksa apakah parameter 'id' dan 'nama' ada di POST
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$nama = filter_input(INPUT_POST, 'nama', FILTER_SANITIZE_STRING);
$alamat = filter_input(INPUT_POST, 'alamat', FILTER_SANITIZE_STRING);
$jabatan = filter_input(INPUT_POST, 'jabatan', FILTER_SANITIZE_STRING);
$phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

if ($id === false || $nama === null || $alamat === null || $jabatan === null || $phone === null || $email === null) {
    $_SESSION['error'] = "Data tidak valid.";
    header("Location: edit_employee.php?id=$id");
    exit();
}

// Periksa apakah ada perubahan data
$stmt = $conn->prepare("SELECT nama, alamat, jabatan, phone, email FROM employees WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($currentNama, $currentAlamat, $currentJabatan, $currentPhone, $currentEmail);
$stmt->fetch();
$stmt->close();

$changes = [];
if ($nama !== $currentNama) $changes[] = "nama";
if ($alamat !== $currentAlamat) $changes[] = "alamat";
if ($jabatan !== $currentJabatan) $changes[] = "jabatan";
if ($phone !== $currentPhone) $changes[] = "phone";
if ($email !== $currentEmail) $changes[] = "email";

if (empty($changes)) {
    $_SESSION['error'] = "Tidak ada perubahan data.";
    header("Location: edit_employee.php?id=$id");
    exit();
}

// Perbarui data karyawan
$stmt = $conn->prepare("UPDATE employees SET nama = ?, alamat = ?, jabatan = ?, phone = ?, email = ? WHERE id = ?");
$stmt->bind_param("sssssi", $nama, $alamat, $jabatan, $phone, $email, $id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Data karyawan berhasil diperbarui.";
} else {
    $_SESSION['error'] = "Terjadi kesalahan saat memperbarui data karyawan.";
}

$stmt->close();

header("Location: edit_employee.php?id=$id");
exit();
