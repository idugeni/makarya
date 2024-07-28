<?php
session_start();
require_once('include/db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT id, username, password, full_name, role, foto FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $stmt->bind_result($id, $username, $hashed_password, $full_name, $role, $foto);

  if ($stmt->fetch()) {
    if (password_verify($password, $hashed_password)) {
      $_SESSION['username'] = $username;
      $_SESSION['full_name'] = $full_name;
      $_SESSION['role'] = $role;
      $_SESSION['foto'] = $foto;
      header("Location: dashboard.php");
      exit();
    } else {
      error_log("Login failed for username: $username - incorrect password");
      $error = "Username atau password salah!";
    }
  } else {
    error_log("Login failed for username: $username - username not found");
    $error = "Username atau password salah!";
  }

  $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Masuk - MAKARYA</title>
  <link rel="icon" type="image/png" href="assets/images/logo.png">
  <link rel="apple-touch-icon" href="assets/images/logo.png">
  <link href="assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/font-awesome.min.css" rel="stylesheet">
  <link href="assets/css/main.css" rel="stylesheet">
  <style>
    body {
      background-image: url('assets/images/background.jpg');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
    }

    .glass-bg {
      background-color: rgba(255, 255, 255, 0.6);
      backdrop-filter: blur(10px);
    }

    .countdown {
      font-weight: bold;
      color: #dc3545;
    }
  </style>
</head>

<body class="d-flex justify-content-center align-items-center min-vh-100">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6 col-md-8 col-sm-10">
        <div class="card glass-bg shadow-lg border-0 rounded-3">
          <div class="card-body text-center p-4">
            <h3 class="card-title mb-4">Login Gagal</h3>
            <p class="text-danger"><?php echo isset($error) ? $error : ''; ?></p>
            <p>Silakan coba lagi atau tunggu <span id="countdown" class="countdown">5 detik</span>.</p>
            <a href="/" class="btn btn-primary">Kembali ke Halaman Utama</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/sweetalert2.js"></script>
  <script type="text/javascript">
    // Set countdown time in seconds
    var countdownTime = 5;
    var countdownElement = document.getElementById('countdown');
    var redirectUrl = "/"; // URL untuk redirect setelah countdown

    function updateCountdown() {
      countdownElement.textContent = countdownTime + ' detik';
      if (countdownTime > 0) {
        countdownTime--;
        setTimeout(updateCountdown, 1000);
      } else {
        // Redirect setelah countdown selesai
        window.location.href = redirectUrl;
      }
    }

    // Menampilkan SweetAlert jika ada kesalahan
    <?php if (isset($error)): ?>
      Swal.fire({
        icon: 'error',
        title: 'Login Gagal',
        text: '<?php echo $error; ?>',
        timer: 5000,
        showConfirmButton: true,
        confirmButtonText: 'Kembali ke Halaman Utama'
      }).then((result) => {
        if (result.isConfirmed) {
          // Redirect jika tombol konfirmasi diklik
          window.location.href = redirectUrl;
        } else {
          // Mulai countdown jika tombol konfirmasi tidak diklik
          updateCountdown();
        }
      });
    <?php else: ?>
      // Jika tidak ada error, langsung mulai countdown
      updateCountdown();
    <?php endif; ?>
  </script>
</body>

</html>