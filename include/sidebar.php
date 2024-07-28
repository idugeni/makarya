<!-- Sidebar HTML -->
<div id="sidebar" class="card shadow mb-4 mx-4 d-none d-md-block position-fixed top-0 start-0" style="width: 300px;margin-top: 80px;">
  <div class="card-body text-center">
    <h5 class="card-title fw-bold my-2">
      <?php echo htmlspecialchars($_SESSION['full_name'], ENT_QUOTES, 'UTF-8'); ?>
    </h5>
    <div class="my-2">
      <img src="<?php echo !empty($_SESSION['foto']) ? 'uploads/users/' . htmlspecialchars($_SESSION['foto'], ENT_QUOTES, 'UTF-8') : 'assets/images/logo.png'; ?>"
        class="img-fluid rounded-circle shadow"
        alt="<?php echo htmlspecialchars($_SESSION['full_name'], ENT_QUOTES, 'UTF-8'); ?>"
        style="width: 100px; height: 100px; object-fit: cover;">
    </div>
    <div class="text-center mb-2">
      <span class="fst-italic text-lowercase username small">@<?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?></span>
    </div>
    <hr class="my-2">
    <ul class="list-group list-group-flush">
      <?php if ($_SESSION['role'] === 'superadmin') : ?>
        <li class="list-group-item border-0 px-0">
          <a href="admin.php" class="btn btn-success btn-sm w-100 d-flex align-items-center justify-content-center">
            <i class="fa-solid fa-user-gear me-2"></i>Admin
          </a>
        </li>
      <?php endif; ?>
      <li class="list-group-item border-0 px-0">
        <a href="dashboard.php" class="btn btn-primary btn-sm w-100 d-flex align-items-center justify-content-center">
          <i class="fa-solid fa-tachometer-alt me-2"></i>Dashboard
        </a>
      </li>
      <li class="list-group-item border-0 px-0">
        <a href="add_employee.php" class="btn btn-primary btn-sm w-100 d-flex align-items-center justify-content-center">
          <i class="fa-solid fa-user-plus me-2"></i>Tambah Karyawan
        </a>
      </li>
      <li class="list-group-item border-0 px-0">
        <a href="employee_list.php" class="btn btn-primary btn-sm w-100 d-flex align-items-center justify-content-center">
          <i class="fa-solid fa-users me-2"></i>Data Karyawan
        </a>
      </li>
      <li class="list-group-item border-0 px-0">
        <form id="logoutForm" action="logout.php" method="POST" class="d-inline">
          <button type="button" id="logoutButton" class="btn btn-danger btn-sm w-100 d-flex align-items-center justify-content-center">
            <i class="fa-solid fa-sign-out-alt me-2"></i>Logout
          </button>
        </form>
      </li>
    </ul>
  </div>
</div>

<!-- Sidebar for Mobile -->
<div id="sidebar-mobile" class="card shadow mb-4 d-block d-md-none">
  <div class="card-body text-center">
    <h5 class="card-title fw-bold my-2">
      <?php echo htmlspecialchars($_SESSION['full_name'], ENT_QUOTES, 'UTF-8'); ?>
    </h5>
    <div class="my-2">
      <img src="<?php echo !empty($_SESSION['foto']) ? 'uploads/users/' . htmlspecialchars($_SESSION['foto'], ENT_QUOTES, 'UTF-8') : 'assets/images/logo.png'; ?>"
        class="img-fluid rounded-circle shadow"
        alt="<?php echo htmlspecialchars($_SESSION['full_name'], ENT_QUOTES, 'UTF-8'); ?>"
        style="width: 100px; height: 100px; object-fit: cover;">
    </div>
    <div class="text-center mb-2">
      <span class="fst-italic text-lowercase username small">@<?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?></span>
    </div>
    <hr class="my-2">
    <ul class="list-group list-group-flush">
      <?php if ($_SESSION['role'] === 'superadmin') : ?>
        <li class="list-group-item border-0 px-0">
          <a href="admin.php" class="btn btn-success btn-sm w-100 d-flex align-items-center justify-content-center">
            <i class="fa-solid fa-user-gear me-2"></i>Admin
          </a>
        </li>
      <?php endif; ?>
      <li class="list-group-item border-0 px-0">
        <a href="dashboard.php" class="btn btn-primary btn-sm w-100 d-flex align-items-center justify-content-center">
          <i class="fa-solid fa-tachometer-alt me-2"></i>Dashboard
        </a>
      </li>
      <li class="list-group-item border-0 px-0">
        <a href="add_employee.php" class="btn btn-primary btn-sm w-100 d-flex align-items-center justify-content-center">
          <i class="fa-solid fa-user-plus me-2"></i>Tambah Karyawan
        </a>
      </li>
      <li class="list-group-item border-0 px-0">
        <a href="employee_list.php" class="btn btn-primary btn-sm w-100 d-flex align-items-center justify-content-center">
          <i class="fa-solid fa-users me-2"></i>Data Karyawan
        </a>
      </li>
      <li class="list-group-item border-0 px-0">
        <form id="logoutFormMobile" action="logout.php" method="POST" class="d-inline">
          <button type="button" id="logoutButtonMobile" class="btn btn-danger btn-sm w-100 d-flex align-items-center justify-content-center">
            <i class="fa-solid fa-sign-out-alt me-2"></i>Logout
          </button>
        </form>
      </li>
    </ul>
  </div>
</div>

<!-- JavaScript -->
<script type="text/javascript">
  document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('logoutButton').addEventListener('click', function() {
      Swal.fire({
        title: 'Konfirmasi Logout',
        text: "Apakah Anda yakin ingin logout?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Logout',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById('logoutForm').submit();
        }
      });
    });

    document.getElementById('logoutButtonMobile').addEventListener('click', function() {
      Swal.fire({
        title: 'Konfirmasi Logout',
        text: "Apakah Anda yakin ingin logout?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Logout',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById('logoutFormMobile').submit();
        }
      });
    });
  });
</script>