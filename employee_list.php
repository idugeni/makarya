<?php
require_once('include/db_connection.php');
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$pageTitle = "Daftar Karyawan";

// Ambil role pengguna dari session
$userRole = isset($_SESSION['role']) ? $_SESSION['role'] : 'user';

// Konstanta untuk direktori upload
define('UPLOAD_DIR', 'uploads/employees/');

// Proses penghapusan karyawan
if (isset($_GET['delete']) && $userRole == 'superadmin') {
    $id = $_GET['delete'];

    // Ambil nama file foto dari database
    $stmt = $conn->prepare("SELECT foto FROM employees WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($foto);
    $stmt->fetch();
    $stmt->close();

    // Hapus foto dari direktori /uploads/employees
    if ($foto) {
        $fotoPath = UPLOAD_DIR . basename($foto); // Path file yang sama seperti saat upload
        if (file_exists($fotoPath) && !unlink($fotoPath)) {
            $_SESSION['notification'] = 'error';
            $_SESSION['error_message'] = 'Gagal menghapus file foto.';
            header("Location: employee_list.php");
            exit();
        }
    }

    // Hapus data karyawan dari database
    $stmt = $conn->prepare("DELETE FROM employees WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        // Reset auto increment jika diperlukan
        resetAutoIncrement($conn);
        $_SESSION['notification'] = 'success';
    } else {
        $_SESSION['notification'] = 'error';
        $_SESSION['error_message'] = 'Gagal menghapus data karyawan.';
    }
    $stmt->close();
    header("Location: employee_list.php");
    exit();
}

// Fungsi untuk mengatur ulang ID
function resetAutoIncrement($conn)
{
    $result = $conn->query("SELECT id FROM employees ORDER BY id");
    $counter = 1;

    while ($row = $result->fetch_assoc()) {
        $conn->query("UPDATE employees SET id = $counter WHERE id = " . $row['id']);
        $counter++;
    }
    $conn->query("ALTER TABLE employees AUTO_INCREMENT = $counter");
}

// Pencarian dan Pagination
$search = isset($_GET['search']) ? $_GET['search'] : '';
$limit = 10; // Jumlah item per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$sql = "SELECT id, nama, alamat, jabatan, phone, email, foto FROM employees WHERE nama LIKE ? OR alamat LIKE ? LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$searchTerm = "%$search%";
$stmt->bind_param("ssii", $searchTerm, $searchTerm, $limit, $offset);
$stmt->execute();

// Mengambil hasil query dengan bind_result dan fetch
$stmt->bind_result($id, $nama, $alamat, $jabatan, $phone, $email, $foto);

$employees = [];
while ($stmt->fetch()) {
    $employees[] = [
        'id' => $id,
        'nama' => $nama,
        'alamat' => $alamat,
        'jabatan' => $jabatan,
        'phone' => $phone,
        'email' => $email,
        'foto' => $foto
    ];
}
$stmt->close();

// Hitung jumlah total halaman
$resultCount = $conn->query("SELECT COUNT(*) as total FROM employees");
$total = $resultCount->fetch_assoc()['total'];
$totalPages = ceil($total / $limit);

// Ambil notifikasi dari sesi dan hapus setelah ditampilkan
$notification = isset($_SESSION['notification']) ? $_SESSION['notification'] : '';
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
unset($_SESSION['notification'], $_SESSION['error_message']);
?>

<?php include('include/header.php'); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-md-3">
            <?php include('include/sidebar.php'); ?>
        </div>
        <div class="col-12 col-md-9">
            <!-- Tabel Data Karyawan -->
            <div class="card shadow">
                <div class="card-body m-4">
                    <h3 class="card-title text-center fw-bold">Daftar Karyawan</h3>
                    <hr class="my-4">
                    <!-- Pencarian Karyawan -->
                    <form method="GET" class="mb-4">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" value="<?php echo htmlspecialchars($search); ?>" placeholder="Cari karyawan...">
                            <button class="btn btn-primary" type="submit">Cari</button>
                        </div>
                    </form>
                    <div class="table-responsive mb-4" id="employeeTable">
                        <table class="table-neumorphism">
                            <thead class="thead-neumorphism">
                                <tr class="tr-neumorphism">
                                    <th class="th-neumorphism">ID</th>
                                    <th class="th-neumorphism">Profil</th>
                                    <th class="th-neumorphism">Nama</th>
                                    <th class="th-neumorphism">Alamat</th>
                                    <th class="th-neumorphism">Jabatan</th>
                                    <th class="th-neumorphism">Nomor HP</th>
                                    <th class="th-neumorphism">Email</th>
                                    <th class="th-neumorphism">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($employees as $employee) : ?>
                                    <tr class="tr-neumorphism">
                                        <td class="td-neumorphism"><?php echo $employee['id']; ?></td>
                                        <td class="td-neumorphism">
                                            <img src="<?php echo !empty($employee['foto']) ? UPLOAD_DIR . htmlspecialchars($employee['foto']) : 'assets/images/oxfam.png'; ?>" alt="Foto Karyawan" class="img-fluid rounded-circle border border-3 border-white shadow" style="width: 50px; height: 50px; object-fit: cover;">
                                        </td>
                                        <td class="td-neumorphism"><?php echo htmlspecialchars($employee['nama']); ?></td>
                                        <td class="td-neumorphism"><?php echo htmlspecialchars($employee['alamat']); ?></td>
                                        <td class="td-neumorphism"><?php echo htmlspecialchars($employee['jabatan']); ?></td>
                                        <td class="td-neumorphism"><?php echo htmlspecialchars($employee['phone']); ?></td>
                                        <td class="td-neumorphism"><?php echo htmlspecialchars($employee['email']); ?></td>
                                        <td class="td-neumorphism">
                                            <div class="btn-group d-flex gap-2" role="group">
                                                <a href="edit_employee.php?id=<?php echo $employee['id']; ?>" class="btn-neumorphism btn-warning">
                                                    <i class="fa-regular fa-pencil"></i>
                                                </a>
                                                <button class="btn-neumorphism btn-danger" onclick="confirmDelete(<?php echo $employee['id']; ?>)">
                                                    <i class="fa-regular fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm justify-content-center d-flex flex-wrap gap-2">
                            <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?search=<?php echo urlencode($search); ?>&page=1" aria-label="First">
                                    <i class="fa-regular fa-angle-double-left"></i> First
                                </a>
                            </li>
                            <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?search=<?php echo urlencode($search); ?>&page=<?php echo $page - 1; ?>" aria-label="Previous">
                                    <i class="fa-regular fa-angle-left"></i> Previous
                                </a>
                            </li>
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                    <a class="page-link" href="?search=<?php echo urlencode($search); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?search=<?php echo urlencode($search); ?>&page=<?php echo $page + 1; ?>" aria-label="Next">
                                    Next <i class="fa-regular fa-angle-right"></i>
                                </a>
                            </li>
                            <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?search=<?php echo urlencode($search); ?>&page=<?php echo $totalPages; ?>" aria-label="Last">
                                    Last <i class="fa-regular fa-angle-double-right"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('include/footer.php'); ?>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', () => {
        const notification = "<?php echo $notification; ?>";
        const errorMessage = "<?php echo $error_message; ?>";
        if (notification === 'success') {
            Swal.fire({
                title: 'Sukses!',
                text: 'Karyawan berhasil dihapus!',
                icon: 'success',
                confirmButtonText: 'Ok'
            });
        } else if (notification === 'error') {
            Swal.fire({
                title: 'Error!',
                text: errorMessage,
                icon: 'error',
                confirmButtonText: 'Ok'
            });
        } else if (notification === 'forbidden') {
            Swal.fire({
                title: 'Dilarang!',
                text: 'Anda tidak memiliki izin untuk menghapus karyawan!',
                icon: 'warning',
                confirmButtonText: 'Ok'
            });
        }
    });

    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda tidak akan dapat mengembalikan ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('employee_list.php?delete=' + id, {
                        method: 'GET'
                    }).then(response => response.text())
                    .then(data => {
                        // Tindakan setelah penghapusan berhasil
                        location.reload();
                    })
                    .catch(error => console.error('Error:', error));
            }
        });
    }
</script>

<?php
$conn->close();
?>