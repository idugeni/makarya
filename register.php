<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Daftar - MAKARYA</title>
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
            <h1 class="card-title text-center fw-bold mb-4">Daftar</h1>
            <form id="registrationForm" enctype="multipart/form-data">
              <div class="mb-4 input-group">
                <span class="input-group-text"><i class="fa-regular fa-user"></i></span>
                <input type="text" class="form-control" id="username" name="username" placeholder="Username">
              </div>
              <div class="mb-4 input-group">
                <span class="input-group-text"><i class="fa-regular fa-id-card"></i></span>
                <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Nama Lengkap">
              </div>
              <div class="mb-4 input-group">
                <span class="input-group-text"><i class="fa-regular fa-lock"></i></span>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
              </div>
              <div class="mb-4 input-group">
                <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                <span class="input-group-text"><i class="fa-regular fa-image"></i></span>
              </div>
              <div class="d-grid gap-1">
                <button type="submit" class="btn btn-success btn-sm btn-block">Daftar</button>
                <div class="d-flex align-items-center">
                  <hr class="flex-grow-1">
                  <span class="mx-2">atau</span>
                  <hr class="flex-grow-1">
                </div>
                <a href="/" class="btn btn-primary btn-sm btn-block">Masuk</a>
              </div>
            </form>
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
    document.addEventListener('DOMContentLoaded', function() {
      document.getElementById('registrationForm').addEventListener('submit', function(event) {
        event.preventDefault();

        var formData = new FormData(this);
        var username = document.getElementById('username').value.trim();
        var password = document.getElementById('password').value.trim();
        var fullName = document.getElementById('full_name').value.trim();

        if (!username) {
          Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: 'Username harus diisi!',
            showConfirmButton: true
          });
          return;
        }

        if (!password) {
          Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: 'Password harus diisi!',
            showConfirmButton: true
          });
          return;
        }

        if (!fullName) {
          Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: 'Nama lengkap harus diisi!',
            showConfirmButton: true
          });
          return;
        }

        fetch('include/process_register.php', {
            method: 'POST',
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            if (data.status === 'success') {
              Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: data.message,
                showConfirmButton: true
              }).then(result => {
                if (result.isConfirmed) {
                  window.location.href = '/';
                }
              });
            } else {
              let errorMessage = data.message;
              if (data.errors) {
                errorMessage = Object.values(data.errors).join('\n');
              }
              Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: errorMessage,
                showConfirmButton: true
              });
            }
          })
          .catch(error => {
            Swal.fire({
              icon: 'error',
              title: 'Gagal',
              text: 'Terjadi kesalahan. Silakan coba lagi.',
              showConfirmButton: true
            });
          });
      });
    });
  </script>
</body>

</html>