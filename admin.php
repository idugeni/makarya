<?php
session_start();
require_once('include/db_connection.php');

// Cek apakah pengguna sudah login dan memiliki role yang tepat
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'superadmin') {
  header("Location: login.php");
  exit();
}

$pageTitle = "Admin";

// Ambil parameter pencarian dan filter dari URL
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$role_filter = isset($_GET['role_filter']) ? $conn->real_escape_string($_GET['role_filter']) : '';

// Bangun query SQL dengan kondisi pencarian dan filter
$sql = "SELECT * FROM users WHERE username LIKE '%$search%'";

if ($role_filter) {
  $sql .= " AND role = '$role_filter'";
}

$sql .= " ORDER BY id ASC";
$resultUsers = $conn->query($sql);

if (!$resultUsers) {
  die("Error: " . $conn->error);
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
          <h3 class="card-title text-center fw-bold">Daftar Pengguna</h3>
          <hr class="my-4">

          <!-- Form Pencarian dan Filter -->
          <div class="row mb-4">
            <div class="col-12 col-md-6 mb-4 mb-md-0">
              <!-- Form Pencarian -->
              <form class="d-flex" method="GET" action="admin.php">
                <div class="input-group">
                  <input
                    class="form-control"
                    type="search"
                    name="search"
                    placeholder="Cari pengguna..."
                    aria-label="Search"
                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                  <button class="btn btn-primary" type="submit">
                    <i class="fa-regular fa-search"></i> Cari
                  </button>
                </div>
              </form>
            </div>
            <div class="col-12 col-md-6">
              <!-- Form Filter Role -->
              <form method="GET" action="admin.php">
                <div class="input-group">
                  <select class="form-select" name="role_filter" onchange="this.form.submit()">
                    <option value="">Semua Role</option>
                    <option value="admin" <?php echo isset($_GET['role_filter']) && $_GET['role_filter'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                    <option value="user" <?php echo isset($_GET['role_filter']) && $_GET['role_filter'] == 'user' ? 'selected' : ''; ?>>User</option>
                  </select>
                  <button class="btn btn-secondary" type="submit">
                    <i class="fa-regular fa-filter"></i> Filter
                  </button>
                </div>
              </form>
            </div>
          </div>

          <div class="d-flex justify-content-end mb-4">
            <a href="add_user.php" class="btn btn-primary">
              <i class="fa-regular fa-user-plus me-2"></i>Tambah Pengguna
            </a>
          </div>

          <div class="table-responsive">
            <table class="table table-bordered table-hover text-center align-middle table-sm">
              <thead class="table-light">
                <tr class="table-primary">
                  <th>ID</th>
                  <th>Profile</th>
                  <th>Username</th>
                  <th>Nama Lengkap</th>
                  <th>Role</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($user = $resultUsers->fetch_assoc()) { ?>
                  <tr>
                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                    <td>
                      <img src="<?php echo !empty($user['foto']) ? 'uploads/users/' . htmlspecialchars($user['foto']) : 'assets/images/logo.png'; ?>" alt="Foto Pengguna" class="img-fluid rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                    </td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($user['role']); ?></td>
                    <td>
                      <?php if ($user['role'] === 'admin' || $user['role'] === 'user') { ?>
                        <div class="d-flex justify-content-center align-items-center gap-2">
                          <a href="edit_user.php?id=<?php echo urlencode($user['id']); ?>" class="btn btn-warning btn-sm">
                            <i class="fa-regular fa-edit me-2"></i>Edit
                          </a>
                          <button class="btn btn-danger btn-sm delete-btn" data-id="<?php echo urlencode($user['id']); ?>">
                            <i class="fa-regular fa-trash me-2"></i>Hapus
                          </button>
                        </div>
                      <?php } else { ?>
                        <span class="text-muted">Tidak dapat diedit</span>
                      <?php } ?>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>


<?php include('include/footer.php'); ?>

<script src="assets/js/sweetalert2.js"></script>
<script>
  document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function() {
      const userId = this.getAttribute('data-id');
      Swal.fire({
        title: 'Konfirmasi Hapus',
        text: "Apakah Anda yakin ingin menghapus pengguna ini?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          fetch(`delete_user.php?id=${userId}`, {
              method: 'POST'
            })
            .then(response => {
              if (response.ok) {
                Swal.fire(
                  'Dihapus!',
                  'Pengguna telah dihapus.',
                  'success'
                ).then(() => location.reload());
              } else {
                Swal.fire(
                  'Error!',
                  'Terjadi kesalahan saat menghapus pengguna.',
                  'error'
                );
              }
            });
        }
      });
    });
  });
</script>

<?php
$conn->close();
?>