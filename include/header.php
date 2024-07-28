<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php
  $siteTitle = "MAKARYA"; // MAKARYA = Management Data Karyawan

  $pageTitle = isset($pageTitle) ? $pageTitle . " - " . $siteTitle : $siteTitle;
  ?>
  <title><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>
  <link rel="icon" type="image/png" href="assets/images/logo.png">
  <link rel="apple-touch-icon" href="assets/images/logo.png">
  <link href="assets/css/bootstrap.min.css?v=<?php echo time(); ?>" rel="stylesheet">
  <link href="assets/css/font-awesome.min.css?v=<?php echo time(); ?>" rel="stylesheet">
  <link href="assets/css/jquery-ui.min.css?v=<?php echo time(); ?>" rel="stylesheet">
  <link href="assets/css/main.css?v=<?php echo time(); ?>" rel="stylesheet">
  <style>
    .nav-link {
      position: relative;
      padding-bottom: 0.5rem;
    }

    .nav-link.active::after {
      content: '';
      display: block;
      width: 100%;
      height: 2px;
      background: #007bff;
      position: absolute;
      left: 0;
      right: 0;
      bottom: 0;
    }

    .navbar-toggler:focus,
    .navbar-toggler:active {
      box-shadow: none;
      outline: none;
    }
  </style>
</head>

<body>
  <!-- Desktop Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow sticky-top d-none d-lg-flex px-2 mb-3">
    <div class="container-fluid">
      <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
        <img src="assets/images/letter-m.png" class="img-fluid rounded me-2" alt="Site Logo" style="height: 40px; width: 40px;">
        <span class="fw-bold"><?php echo htmlspecialchars($siteTitle, ENT_QUOTES, 'UTF-8'); ?></span>
      </a>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'add_employee.php' ? 'active' : ''; ?>" href="add_employee.php">Tambah Karyawan</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'employee_list.php' ? 'active' : ''; ?>" href="employee_list.php">Data Karyawan</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="javascript:void(0);" id="logoutLink">Logout</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Mobile Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light shadow sticky-top d-lg-none mb-4">
    <div class="container-fluid">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMobileContent" aria-controls="navbarMobileContent" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fa-regular fa-bars"></i>
      </button>

      <a class="navbar-brand fw-bold mx-auto" href="dashboard.php"><?php echo htmlspecialchars($siteTitle, ENT_QUOTES, 'UTF-8'); ?></a>

      <div class="collapse navbar-collapse" id="navbarMobileContent">
        <ul class="navbar-nav mt-2 mt-lg-0">
          <li class="nav-item">
            <a class="btn btn-light nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="btn btn-light nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'add_employee.php' ? 'active' : ''; ?>" href="add_employee.php">Tambah Karyawan</a>
          </li>
          <li class="nav-item">
            <a class="btn btn-light nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'employee_list.php' ? 'active' : ''; ?>" href="employee_list.php">Data Karyawan</a>
          </li>
          <li class="nav-item">
            <button class="btn btn-danger w-100" id="logoutLink">
              <i class="fa-regular fa-sign-out-alt"></i> Logout
            </button>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <script type="text/javascript">
    document.getElementById('logoutLink').addEventListener('click', function() {
      Swal.fire({
        title: 'Konfirmasi Logout',
        text: "Apakah Anda yakin ingin logout?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Logout',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = 'logout.php';
        }
      });
    });
  </script>