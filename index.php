<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>MAKARYA - Management Data Karyawan</title>
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
  </style>
</head>

<body class="d-flex justify-content-center align-items-center min-vh-100">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6 col-md-8 col-sm-10 col-12">
        <div class="card glass-bg shadow-lg border-0 rounded-3">
          <div class="card-body p-4">
            <h1 class="card-title text-center fw-bold mb-2">MAKARYA</h1>
            <p class="text-center mb-4">Management Data Karyawan</p>
            <form action="login.php" method="POST" id="loginForm" novalidate>
              <div class="mb-4">
                <div class="input-group">
                  <span class="input-group-text"><i class="fa-regular fa-user"></i></span>
                  <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                  <div id="username-status" class="input-group-text d-none"></div>
                  <div class="invalid-feedback">Please enter your username.</div>
                </div>
              </div>
              <div class="mb-4">
                <div class="input-group">
                  <span class="input-group-text"><i class="fa-regular fa-lock"></i></span>
                  <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                  <div id="password-status" class="input-group-text d-none"></div>
                  <div class="invalid-feedback">Please enter your password.</div>
                </div>
              </div>
              <div class="d-grid gap-2 mb-2">
                <button type="submit" class="btn btn-primary">Masuk</button>
                <div class="d-flex align-items-center">
                  <hr class="flex-grow-1">
                  <span class="mx-2">atau</span>
                  <hr class="flex-grow-1">
                </div>
                <a href="register.php" class="btn btn-success">Daftar</a>
              </div>
              <div class="text-center mb-2">
                <a href="reset_password.php">Lupa kata sandi?</a>
              </div>
            </form>
            <div class="text-center mb-2">
              <small class="d-inline">Username: <strong>admin</strong></small>
              <small class="d-inline mx-2">|</small>
              <small class="d-inline">Password: <strong>admin123</strong></small>
            </div>
            <div class="text-center mb-2">
              <small class="d-inline">Username: <strong>user</strong></small>
              <small class="d-inline mx-2">|</small>
              <small class="d-inline">Password: <strong>user123</strong></small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/font-awesome.min.js"></script>
  <script src="assets/js/sweetalert2.js"></script>
  <script src="assets/js/main.js"></script>
  <script>
    document.getElementById('username').addEventListener('input', function() {
      const username = this.value.toLowerCase();
      const statusElement = document.getElementById('username-status');

      if (username.length > 0) {
        fetch(`include/check_username.php?username=${encodeURIComponent(username)}`)
          .then(response => response.json())
          .then(data => {
            if (data.exists) {
              statusElement.innerHTML = '<i class="fa-regular fa-check text-success"></i>';
              statusElement.classList.remove('d-none');
            } else {
              statusElement.innerHTML = '<i class="fa-regular fa-times text-danger"></i>';
              statusElement.classList.remove('d-none');
            }
          })
          .catch(error => {
            statusElement.innerHTML = '<i class="fa-regular fa-times text-danger"></i>';
            statusElement.classList.remove('d-none');
          });
      } else {
        statusElement.classList.add('d-none');
      }
    });

    document.getElementById('loginForm').addEventListener('submit', function(event) {
      event.preventDefault();
      const username = document.getElementById('username').value;
      const password = document.getElementById('password').value;

      if (!username) {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Nama pengguna harus diisi!',
        });
        return;
      }

      if (!password) {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Kata sandi harus diisi!',
        });
        return;
      }

      this.submit();
    });
  </script>
</body>

</html>