<?php
require_once('include/db_connection.php');
session_start();

if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}

$pageTitle = "Dashboard";

// Cek apakah notifikasi sudah ditutup dalam sesi ini
$showNotification = !isset($_SESSION['notification_closed']);

// Ambil informasi pengguna yang login
$username = htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8');

// Ambil semua data karyawan dari database
$sql = "SELECT * FROM employees";
$resultEmployees = $conn->query($sql);
?>


<?php include('include/header.php'); ?>

<div class="container-fluid">
  <div class="row">
    <div class="col-12 col-md-3">
      <?php include('include/sidebar.php'); ?>
    </div>

    <div class="col-12 col-md-9">
      <!-- Notifikasi -->
      <?php if ($showNotification): ?>
        <div id="notification" class="alert alert-success alert-dismissible fade show mb-4 position-relative shadow" role="alert">
          <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
              <i class="fa-regular fa-circle-info text-info fa-2xl me-3"></i>
              <span class="small">Aplikasi ini dibuat dengan <a href="https://www.php.net" target="_blank" rel="noopener noreferrer">PHP</a> dan <a href="https://www.mysql.com" target="_blank" rel="noopener noreferrer">MySQL</a> untuk management data karyawan dengan fitur <a href="https://en.wikipedia.org/wiki/Create,_read,_update_and_delete" target="_blank" rel="noopener noreferrer">CRUD</a>. Menggunakan <a href="https://sweetalert2.github.io" target="_blank" rel="noopener noreferrer">SweetAlert2</a> untuk notifikasi dan <a href="https://getbootstrap.com" target="_blank" rel="noopener noreferrer">Bootstrap</a> untuk antarmuka responsif yang modern.</span>
            </div>
            <button type="button" class="position-absolute end-0 top-50 translate-middle-y me-3 border-0 bg-transparent" data-bs-dismiss="alert" aria-label="Close">
              <i class="fa-regular fa-times fa-lg"></i>
            </button>
          </div>
        </div>
      <?php endif; ?>

      <div class="card shadow mb-4">
        <div class="card-body m-4">
          <h3 class="card-title text-center mb-4">Selamat Datang di Aplikasi Management Data Karyawan</h3>
          <p class="small text-justify">Aplikasi ini dirancang untuk membantu Anda mengelola data karyawan dengan mudah dan efisien. Dengan fitur lengkap, Anda dapat melakukan pengelolaan data seperti menambah, memperbarui, dan menghapus informasi karyawan dalam waktu singkat.</p>
          <h5 class="small-text my-4">Kegunaan Aplikasi:</h5>
          <ul class="small text-justify">
            <li>Menyimpan dan mengelola data karyawan secara terstruktur.</li>
            <li>Menyediakan fitur pencarian untuk memudahkan menemukan data karyawan.</li>
            <li>Menampilkan informasi karyawan dalam format yang jelas dan mudah dibaca.</li>
            <li>Mendukung proses administrasi yang lebih cepat dengan antarmuka yang intuitif.</li>
            <li>Memberikan notifikasi interaktif menggunakan <a href="https://sweetalert2.github.io" target="_blank" rel="noopener noreferrer">SweetAlert2</a>.</li>
          </ul>

          <h5 class="small-text my-4">Aplikasi ini dibangun dengan menggunakan:</h5>
          <ul class="small text-justify">
            <li><a href="https://www.php.net" target="_blank" rel="noopener noreferrer">PHP</a> sebagai bahasa pemrograman server-side.</li>
            <li><a href="https://www.mysql.com" target="_blank" rel="noopener noreferrer">MySQL</a> untuk sistem management basis data.</li>
            <li><a href="https://getbootstrap.com" target="_blank" rel="noopener noreferrer">Bootstrap</a> untuk desain antarmuka yang responsif.</li>
            <li><a href="https://fontawesome.com" target="_blank" rel="noopener noreferrer">Font Awesome</a> untuk ikon yang menarik dan fungsional.</li>
          </ul>

          <p class="small text-justify">Dengan menggunakan aplikasi ini, Anda akan memiliki kendali penuh atas management data karyawan, yang akan membantu meningkatkan produktivitas dan efisiensi kerja di lingkungan Anda.</p>
        </div>
      </div>

      <!-- Tabel Data Karyawan -->
      <div class="card shadow">
        <div class="card-body m-4">
          <h3 class="card-title text-center fw-bold mb-4">Daftar Karyawan</h3>
          <div class="table-responsive">
            <table class="table table-bordered table-hover text-center align-middle table-sm">
              <thead class="table-light">
                <tr class="table-primary">
                  <th class="p-2">ID</th>
                  <th class="p-2">Nama</th>
                  <th class="p-2">Alamat</th>
                  <th class="p-2">Jabatan</th>
                  <th class="p-2">Nomor HP</th>
                  <th class="p-2">Email</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($employee = $resultEmployees->fetch_assoc()) { ?>
                  <tr>
                    <td class="small"><?php echo htmlspecialchars($employee['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td class="small"><?php echo htmlspecialchars($employee['nama'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td class="small"><?php echo htmlspecialchars($employee['alamat'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td class="small"><?php echo htmlspecialchars($employee['jabatan'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td class="small"><?php echo htmlspecialchars($employee['phone'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td class="small"><?php echo htmlspecialchars($employee['email'], ENT_QUOTES, 'UTF-8'); ?></td>
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

<script>
  document.addEventListener('DOMContentLoaded', function() {
    var closeButton = document.querySelector('#notification button[data-bs-dismiss="alert"]');
    if (closeButton) {
      closeButton.addEventListener('click', function() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'include/close_notification.php', true);
        xhr.send();
      });
    }
  });
</script>

<?php
$conn->close();
?>