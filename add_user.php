<?php
session_start();
require_once('include/db_connection.php');

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'superadmin') {
    header("Location: login.php");
    exit();
}

$pageTitle = "Tambah Pengguna";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $full_name = trim($_POST['full_name']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    $fotoName = null;
    $fotoPath = null;
    $error = '';
    $success = '';
    $compressionRatio = null;

    // Validasi input
    if (empty($username)) {
        $error = 'Username harus diisi.';
    } elseif (empty($full_name)) {
        $error = 'Nama Lengkap harus diisi.';
    } elseif (empty($password)) {
        $error = 'Password harus diisi.';
    } elseif (empty($role)) {
        $error = 'Role harus dipilih.';
    }

    // Proses upload foto jika ada
    if (empty($error) && !empty($_FILES['foto']['name'])) {
        $foto = $_FILES['foto'];
        $fotoName = strtolower(basename($foto['name']));
        $fotoExtension = pathinfo($fotoName, PATHINFO_EXTENSION);
        $fotoBaseName = pathinfo($fotoName, PATHINFO_FILENAME);

        // Ganti spasi dengan "-"
        $fotoName = preg_replace('/\s+/', '-', $fotoBaseName) . '.' . $fotoExtension;
        $fotoPath = 'uploads/users/' . $fotoName;

        // Cek apakah file sudah ada dan tambahkan suffix jika perlu
        $counter = 1;
        while (file_exists($fotoPath)) {
            $fotoName = preg_replace('/\s+/', '-', $fotoBaseName) . '-' . $counter . '.' . $fotoExtension;
            $fotoPath = 'uploads/users/' . $fotoName;
            $counter++;
        }

        // Validasi format dan ukuran file
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($foto['type'], $allowedTypes)) {
            $error = "Format file tidak didukung. Harus JPG, PNG, atau WEBP.";
        } elseif ($foto['size'] > 2 * 1024 * 1024) {
            $error = "Ukuran file terlalu besar. Maksimal 2MB.";
        } else {
            if (move_uploaded_file($foto['tmp_name'], $fotoPath)) {
                $originalSize = filesize($fotoPath);
                compressImage($fotoPath, $fotoPath, 75);
                $compressedSize = filesize($fotoPath);
                $compressionRatio = ($originalSize - $compressedSize) / $originalSize * 100;
            } else {
                $error = "Gagal mengupload foto.";
            }
        }
    }

    if (empty($error)) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, full_name, password, role, foto, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $fotoParam = $fotoName ? $fotoName : null;
        $stmt->bind_param("sssss", $username, $full_name, $passwordHash, $role, $fotoParam);

        if ($stmt->execute()) {
            $success = 'Pengguna berhasil ditambahkan!';
            if ($compressionRatio !== null) {
                $success .= " Gambar telah dikompresi sebesar " . round($compressionRatio, 2) . "%.";
            }
            echo json_encode(['success' => true, 'message' => $success]);
        } else {
            $error = 'Terjadi kesalahan: ' . $stmt->error;
            echo json_encode(['success' => false, 'message' => $error]);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => $error]);
    }
    exit();
}

function compressImage($source, $destination, $quality)
{
    $info = getimagesize($source);

    if ($info['mime'] == 'image/jpeg') {
        $image = imagecreatefromjpeg($source);
        imagejpeg($image, $destination, $quality);
    } elseif ($info['mime'] == 'image/png') {
        $image = imagecreatefrompng($source);
        imagepng($image, $destination, floor($quality / 10));
    } elseif ($info['mime'] == 'image/webp') {
        $image = imagecreatefromwebp($source);
        imagewebp($image, $destination, $quality);
    }
}
?>

<?php include('include/header.php'); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-md-3">
            <?php include('include/sidebar.php'); ?>
        </div>

        <div class="col-12 col-md-9">
            <div class="card shadow">
                <div class="card-body m-4">
                    <h3 class="card-title text-center fw-bold">Tambah Pengguna</h3>
                    <hr class="my-4">
                    <form id="addUserForm" method="POST" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label for="username" class="form-label fw-bold">Username</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-regular fa-user"></i></span>
                                <input type="text" id="username" name="username" class="form-control" placeholder="Username">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="full_name" class="form-label fw-bold">Nama Lengkap</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-regular fa-id-card"></i></span>
                                <input type="text" id="full_name" name="full_name" class="form-control" placeholder="Nama Lengkap">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label fw-bold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-regular fa-lock"></i></span>
                                <input type="password" id="password" name="password" class="form-control" placeholder="Password">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="role" class="form-label fw-bold">Pilih Role</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-regular fa-users"></i></span>
                                <select id="role" name="role" class="form-select">
                                    <option value="" selected disabled>Pilih Role</option>
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="foto" class="form-label fw-bold">Unggah Foto</label>
                            <div class="input-group">
                                <input type="file" id="foto" name="foto" class="form-control" accept="image/*" onchange="previewImage(event)">
                                <span class="input-group-text"><i class="fa-regular fa-cloud-arrow-up"></i></span>
                            </div>
                            <div class="form-text text-muted small">Format gambar yang diperbolehkan: JPG, JPEG, PNG, WEBP. Ukuran maksimal 2MB.</div>
                        </div>
                        <div class="d-flex justify-content-center align-items-center">
                            <img id="imagePreview" src="#" alt="Preview" class="rounded-circle border border-white border-3 shadow" style="display:none; width: 150px; height: 150px; object-fit: cover;">
                        </div>
                        <hr class="my-4">
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-regular fa-user-plus me-2"></i>Tambah Pengguna
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('include/footer.php'); ?>

<script>
    function previewImage(event) {
        const imagePreview = document.getElementById('imagePreview');
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            imagePreview.style.display = 'none';
        }
    }

    document.getElementById('addUserForm').onsubmit = function(event) {
        event.preventDefault();
        const formData = new FormData(this);

        // Validasi input sebelum mengirim
        let isValid = true;
        let errorMessage = '';

        const username = document.getElementById('username').value;
        const full_name = document.getElementById('full_name').value;
        const password = document.getElementById('password').value;
        const role = document.getElementById('role').value;

        if (!username) {
            isValid = false;
            errorMessage += 'Username harus diisi.\n';
        }
        if (!full_name) {
            isValid = false;
            errorMessage += 'Nama Lengkap harus diisi.\n';
        }
        if (!password) {
            isValid = false;
            errorMessage += 'Password harus diisi.\n';
        }
        if (!role) {
            isValid = false;
            errorMessage += 'Role harus dipilih.\n';
        }

        if (!isValid) {
            alert(errorMessage);
            return;
        }

        fetch('add_user.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    window.location.href = 'admin.php';
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
    }
</script>