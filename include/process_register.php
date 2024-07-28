<?php
session_start();
require_once('db_connection.php');

header('Content-Type: application/json');

$response = [
    'status' => '',
    'message' => '',
    'errors' => []
];

function resetUserIds($conn)
{
    $result = $conn->query("SELECT * FROM users ORDER BY id");
    $newId = 1;
    while ($row = $result->fetch_assoc()) {
        $conn->query("UPDATE users SET id = $newId WHERE id = {$row['id']}");
        $newId++;
    }
    $conn->query("ALTER TABLE users AUTO_INCREMENT = $newId");
}

// Fungsi untuk mengkompres gambar
function compressImage($source, $destination, $quality)
{
    $info = getimagesize($source);
    if ($info['mime'] == 'image/jpeg') {
        $image = imagecreatefromjpeg($source);
        imagejpeg($image, $destination, $quality);
    } elseif ($info['mime'] == 'image/png') {
        $image = imagecreatefrompng($source);
        imagepng($image, $destination, $quality / 10); // Quality untuk PNG diubah dari 0-9
    } elseif ($info['mime'] == 'image/webp') {
        $image = imagecreatefromwebp($source);
        imagewebp($image, $destination, $quality);
    }
    imagedestroy($image);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $full_name = $_POST['full_name'] ?? '';
    $foto = null;

    // Validasi input
    if (empty($username)) {
        $response['errors']['username'] = 'Username harus diisi!';
    }
    if (empty($password)) {
        $response['errors']['password'] = 'Password harus diisi!';
    }
    if (empty($full_name)) {
        $response['errors']['full_name'] = 'Nama lengkap harus diisi!';
    }

    // Tangani upload foto
    if (!empty($_FILES['foto']['name'])) {
        $target_dir = "uploads/users/";
        $file_name = strtolower(basename($_FILES["foto"]["name"]));
        $target_file = $target_dir . $file_name;
        $file_extension = pathinfo($target_file, PATHINFO_EXTENSION);

        // Validasi ekstensi file dan ukuran
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];
        $max_file_size = 2 * 1024 * 1024; // 2 MB

        if (!in_array($file_extension, $allowed_extensions)) {
            $response['errors']['foto'] = 'Hanya file gambar dengan ekstensi jpg, jpeg, png, dan webp yang diperbolehkan!';
        } elseif ($_FILES["foto"]["size"] > $max_file_size) {
            $response['errors']['foto'] = 'File terlalu besar! Maksimal ukuran adalah 2 MB.';
        } else {
            // Tambahkan angka jika nama file sudah ada
            $counter = 1;
            while (file_exists($target_file)) {
                $file_name_without_ext = pathinfo($file_name, PATHINFO_FILENAME);
                $target_file = $target_dir . $file_name_without_ext . '-' . $counter . '.' . $file_extension;
                $counter++;
            }

            if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                // Kompres gambar
                $compressed_file = $target_dir . 'compressed-' . $file_name;
                compressImage($target_file, $compressed_file, 75); // Kualitas kompresi 75%

                // Hapus file asli jika diinginkan
                unlink($target_file);

                $foto = basename($compressed_file);
            } else {
                $response['errors']['foto'] = 'Gagal mengupload foto.';
            }
        }
    }

    if (empty($response['errors'])) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $response['status'] = 'error';
            $response['message'] = 'Username sudah terdaftar!';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, full_name, password, role, foto) VALUES (?, ?, ?, 'user', ?)");
            $stmt->bind_param("ssss", $username, $full_name, $hashed_password, $foto);

            if ($stmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'Selamat, Anda telah berhasil mendaftar!';
                resetUserIds($conn);
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Error: ' . $stmt->error;
            }
        }
        $stmt->close();
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Terjadi kesalahan.';
    }

    echo json_encode($response);
    exit();
}
