<?php
session_start();
require_once('./include/db_connection.php');

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'superadmin') {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'] ?? '';
if (empty($id)) {
    header("Location: admin.php");
    exit();
}

$stmt = $conn->prepare("SELECT id, username, full_name, role, foto FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->store_result();
$user = [];
$stmt->bind_result($userId, $username, $fullName, $role, $foto);

if ($stmt->fetch()) {
    $user = [
        'id' => $userId,
        'username' => $username,
        'full_name' => $fullName,
        'role' => $role,
        'foto' => $foto
    ];
}
$stmt->close();

if (empty($user)) {
    header("Location: admin.php");
    exit();
}

$pageTitle = "Edit Data " . htmlspecialchars($user['full_name']);
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'] ?? '';
    $role = $_POST['role'] ?? '';
    $foto = $_FILES['foto'];

    $allowedFormats = ['jpg', 'jpeg', 'png', 'gif'];
    $fileSizeLimit = 2 * 1024 * 1024; // 2MB

    // Debugging
    // var_dump($foto);

    if ($role !== 'admin' && $role !== 'user') {
        $error = "Role hanya bisa diubah menjadi Admin atau User.";
    } else {
        // Cek apakah ada perubahan pada full_name, role, atau foto
        $isDataChanged = ($full_name !== $user['full_name']) || ($role !== $user['role']) || !empty($foto['name']);

        if (!$isDataChanged) {
            echo "<script>
                Swal.fire({
                    icon: 'info',
                    title: 'Tidak ada perubahan!',
                    text: 'Tidak ada data yang diubah.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.history.back();
                });
            </script>";
        } else {
            $foto_name = $user['foto']; // Default to old photo if no new photo is uploaded

            if (!empty($foto['name'])) {
                $fotoExtension = strtolower(pathinfo($foto['name'], PATHINFO_EXTENSION));
                if (!in_array($fotoExtension, $allowedFormats)) {
                    $error = "Format gambar tidak valid. Hanya format JPG, JPEG, PNG, dan GIF yang diperbolehkan.";
                } elseif ($foto['size'] > $fileSizeLimit) {
                    $error = "Ukuran gambar maksimal adalah 2MB.";
                } else {
                    // Membuat nama file menjadi lowercase dan mengganti spasi dengan "-"
                    $foto_name = strtolower(pathinfo($foto['name'], PATHINFO_FILENAME));
                    $foto_name = preg_replace('/\s+/', '-', $foto_name) . '.' . $fotoExtension;
                    $target_dir = "./uploads/users/";

                    // Menghapus foto lama jika ada
                    if (!empty($user['foto']) && file_exists($target_dir . $user['foto'])) {
                        unlink($target_dir . $user['foto']);
                    }

                    $target_file = $target_dir . $foto_name;
                    move_uploaded_file($foto['tmp_name'], $target_file);
                }
            }

            if (empty($error)) {
                $updateSql = "UPDATE users SET full_name = ?, role = ?";
                if (!empty($foto['name'])) {
                    $updateSql .= ", foto = ?";
                }
                $updateSql .= " WHERE id = ?";
                $updateStmt = $conn->prepare($updateSql);

                if (!empty($foto['name'])) {
                    $updateStmt->bind_param("sssi", $full_name, $role, $foto_name, $id);
                } else {
                    $updateStmt->bind_param("ssi", $full_name, $role, $id);
                }

                if ($updateStmt->execute()) {
                    $_SESSION['notification'] = "User updated successfully.";
                    header("Location: admin.php");
                    exit();
                } else {
                    $error = "Error updating user: " . $conn->error;
                }
            }
        }
    }
}
?>
<?php include('./include/header.php'); ?>

<div class="container my-4">
    <div class="row">
        <div class="col-12 col-md-3">
            <?php include('./include/sidebar.php'); ?>
        </div>
        <div class="col-12 col-md-9">
            <div class="card p-4">
                <div class="card-body">
                    <h4 class="text-center">Edit Data <?php echo htmlspecialchars($user['full_name']); ?></h4>
                    <hr class="my-4">
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <form id="editUserForm" method="POST" enctype="multipart/form-data">
                        <div class="text-center mb-4">
                            <img src="<?php echo !empty($user['foto']) ? 'uploads/users/' . htmlspecialchars($user['foto']) : './assets/images/logo.png'; ?>" alt="User Photo" class="rounded-circle" width="150" height="150">
                        </div>
                        <div class="mb-4">
                            <label for="username" class="form-label fw-bold">Username</label>
                            <div class="position-relative">
                                <i class="fa-regular fa-user position-absolute" style="left: 10px; top: 10px;"></i>
                                <input type="text" class="form-control ps-5" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="full_name" class="form-label fw-bold">Nama Lengkap</label>
                            <div class="position-relative">
                                <i class="fa-regular fa-id-card position-absolute" style="left: 10px; top: 10px;"></i>
                                <input type="text" class="form-control ps-5" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="role" class="form-label fw-bold">Role</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="admin" <?php echo ($user['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                                <option value="user" <?php echo ($user['role'] === 'user') ? 'selected' : ''; ?>>User</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="foto" class="form-label fw-bold">Ganti Foto Profil</label>
                            <div class="input-group">
                                <input type="file" class="form-control" id="foto" name="foto">
                                <span class="input-group-text"><i class="fa-regular fa-cloud-arrow-up"></i></span>
                            </div>
                            <div class="form-text text-muted small">Format gambar yang diperbolehkan: JPG, JPEG, PNG, GIF. Ukuran maksimal 2MB.</div>
                        </div>
                        <hr class="my-4">
                        <div class="d-flex justify-content-between">
                            <a href="admin.php" class="btn btn-secondary">
                                <i class="fa-regular fa-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-success" id="saveButton">
                                Simpan<i class="fa-regular fa-save ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('./include/footer.php'); ?>

<script type="text/javascript">
    document.getElementById('editUserForm').onsubmit = function(event) {
        event.preventDefault();

        const fullName = document.getElementById('full_name').value;
        const role = document.getElementById('role').value;
        const fileInput = document.getElementById('foto');
        const file = fileInput.files[0];

        // Debugging: Cek file foto
        console.log('Full Name:', fullName);
        console.log('Role:', role);
        console.log('File:', file);

        // Periksa apakah ada perubahan pada data
        const isDataChanged = (fullName !== "<?php echo htmlspecialchars($user['full_name']); ?>") ||
            (role !== "<?php echo htmlspecialchars($user['role']); ?>") ||
            (file !== undefined);

        if (!isDataChanged) {
            Swal.fire({
                icon: 'info',
                title: 'Tidak ada perubahan!',
                text: 'Tidak ada data yang diubah.',
                confirmButtonText: 'OK'
            });
        } else {
            Swal.fire({
                title: 'Konfirmasi',
                text: "Apakah Anda yakin ingin menyimpan perubahan ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, simpan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        }
    };
</script>

<?php
$conn->close();
?>