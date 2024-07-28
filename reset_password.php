<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password - MAKARYA</title>
  <link rel="icon" type="image/png" href="./assets/images/logo.png">
  <link rel="apple-touch-icon" href="./assets/images/logo.png">
  <link href="./assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="./assets/css/font-awesome.min.css" rel="stylesheet">
  <link href="./assets/css/jquery-ui.min.css" rel="stylesheet">
  <link href="./assets/css/main.css" rel="stylesheet">
  <link href="./assets/css/fonts.css" rel="stylesheet">
  <style>
    body {
      background-image: url('./assets/images/background.jpg');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
    }

    .glass-bg {
      background-color: rgba(255, 255, 255, 0.8);
      backdrop-filter: blur(10px);
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
    }
  </style>
</head>

<body class="d-flex justify-content-center align-items-center min-vh-100">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6 col-md-8 col-sm-10">
        <div class="card glass-bg shadow-lg border-0 rounded-3">
          <div class="card-body p-4">
            <h1 class="card-title text-center fw-bold mb-4">Reset Password</h1>
            <form id="resetForm" method="POST" action="./include/process_reset.php">
              <div class="mb-4 position-relative">
                <div class="input-group">
                  <span class="input-group-text"><i class="fa-regular fa-user"></i></span>
                  <input type="text" class="form-control" id="username" name="username" placeholder="Username">
                </div>
              </div>
              <div class="mb-4 position-relative">
                <div class="input-group">
                  <span class="input-group-text"><i class="fa-regular fa-lock"></i></span>
                  <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Kata Sandi Baru">
                </div>
              </div>
              <div class="mb-4 position-relative">
                <div class="input-group">
                  <span class="input-group-text"><i class="fa-regular fa-lock-hashtag"></i></span>
                  <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Konfirmasi Kata Sandi">
                </div>
              </div>
              <div class="d-grid gap-1">
                <button type="submit" class="btn btn-danger btn-block">Reset Password</button>
                <div class="d-flex align-items-center">
                  <hr class="flex-grow-1">
                  <span class="mx-2">atau</span>
                  <hr class="flex-grow-1">
                </div>
                <a href="./" class="btn btn-primary btn-block">Kembali ke Halaman Utama</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="./assets/js/bootstrap.bundle.min.js"></script>
  <script src="./assets/js/font-awesome.min.js"></script>
  <script src="./assets/js/sweetalert2.js"></script>
  <script src="./assets/js/main.js"></script>
  <script type="text/javascript">
    document.getElementById('resetForm').addEventListener('submit', async function(event) {
      event.preventDefault();

      const username = document.getElementById('username').value.trim();
      const newPassword = document.getElementById('new_password').value.trim();
      const confirmPassword = document.getElementById('confirm_password').value.trim();

      // Validasi input
      if (!username || !newPassword || !confirmPassword) {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Semua field harus diisi.',
        });
        return;
      }

      if (newPassword !== confirmPassword) {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Kata sandi baru dan konfirmasi kata sandi tidak cocok.',
        });
        return;
      }

      // Konfirmasi reset password
      Swal.fire({
        title: 'Konfirmasi',
        text: 'Apakah Anda yakin ingin mereset password?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, reset!',
        cancelButtonText: 'Batal'
      }).then(async (result) => {
        if (result.isConfirmed) {
          try {
            const response = await fetch(`./include/check_username_role.php?username=${username}`);
            const data = await response.json();

            if (!data.exists) {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Username tidak ditemukan di database.',
              });
            } else if (data.role === 'superadmin') {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Anda tidak dapat mereset password untuk pengguna dengan role "superadmin".',
              });
            } else if (data.role === 'admin') {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Anda tidak dapat mereset password untuk pengguna dengan role "admin".',
              });
            } else {
              // Submit form setelah validasi berhasil
              document.getElementById('resetForm').submit();
              Swal.fire({
                icon: 'success',
                title: 'Sukses',
                text: 'Password berhasil direset.',
                confirmButtonText: 'OK',
              }).then(() => {
                setTimeout(() => {
                  window.location.href = './';
                }, 3000);
              });
            }
          } catch (error) {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Terjadi kesalahan dalam proses reset password.',
            });
          }
        }
      });
    });
  </script>
</body>

</html>