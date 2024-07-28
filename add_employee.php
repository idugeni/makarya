<?php
require_once('include/db_connection.php');

// Cek sesi login atau autentikasi pengguna
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}

$pageTitle = "Tambah Karyawan";

// Fungsi untuk memformat nomor HP
function formatPhoneNumber($phone)
{
  // Hapus semua karakter yang bukan angka
  $phone = preg_replace('/\D/', '', $phone);

  // Cek panjang nomor
  if (strlen($phone) >= 10) {
    // Format nomor
    $formatted = '+62 ' . substr($phone, 1, 3) . '-' . substr($phone, 4, 4) . '-' . substr($phone, 8);
    return $formatted;
  }

  return $phone; // Kembalikan nomor tidak valid
}

$nama = $alamat = $jabatan = $phone = $email = "";
$errors = array();

// Proses penanganan form tambah karyawan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addEmployee'])) {
  $nama = $_POST['nama'];
  $alamat = $_POST['alamat'];
  $jabatan = $_POST['jabatan'];
  $phone = formatPhoneNumber($_POST['phone']);
  $email = $_POST['email'];

  // Cek apakah nama sudah ada di database
  $checkNameSql = "SELECT COUNT(*) FROM employees WHERE nama = ?";
  $stmt = $conn->prepare($checkNameSql);
  $stmt->bind_param("s", $nama);
  $stmt->execute();
  $stmt->bind_result($nameExists);
  $stmt->fetch();
  $stmt->close();

  if ($nameExists > 0) {
    $errors['nama'] = "Nama sudah terdaftar!";
  }

  // Cek apakah email sudah ada di database
  $checkEmailSql = "SELECT COUNT(*) FROM employees WHERE email = ?";
  $stmt = $conn->prepare($checkEmailSql);
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->bind_result($emailExists);
  $stmt->fetch();
  $stmt->close();

  if ($emailExists > 0) {
    $errors['email'] = "Email sudah terdaftar!";
  }

  // Cek apakah nomor telepon sudah ada di database
  $checkPhoneSql = "SELECT COUNT(*) FROM employees WHERE phone = ?";
  $stmt = $conn->prepare($checkPhoneSql);
  $stmt->bind_param("s", $phone);
  $stmt->execute();
  $stmt->bind_result($phoneExists);
  $stmt->fetch();
  $stmt->close();

  if ($phoneExists > 0) {
    $errors['phone'] = "Nomor telepon sudah terdaftar!";
  }

  // Proses upload foto
  $targetDir = "uploads/employees/";
  $originalFileName = basename($_FILES["foto"]["name"]);
  $imageFileType = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));

  // Ganti spasi dengan tanda "-"
  $fileName = strtolower(str_replace(' ', '-', pathinfo($originalFileName, PATHINFO_FILENAME))) . '.' . $imageFileType;
  $targetFile = $targetDir . $fileName;
  $uploadOk = 1;

  // Cek apakah file adalah gambar
  $check = getimagesize($_FILES["foto"]["tmp_name"]);
  if ($check === false) {
    $uploadOk = 0;
    $errors['foto'] = "File yang diunggah bukan gambar!";
  }

  // Cek ukuran file (maksimal 2MB)
  if ($_FILES["foto"]["size"] > 2 * 1024 * 1024) {
    $uploadOk = 0;
    $errors['foto'] = "Ukuran file terlalu besar!";
  }

  // Cek format file
  $allowedTypes = ['jpg', 'png', 'jpeg', 'webp'];
  if (!in_array($imageFileType, $allowedTypes)) {
    $uploadOk = 0;
    $errors['foto'] = "Format file tidak didukung!";
  }

  // Jika semua cek berhasil, upload file
  if ($uploadOk == 1) {
    $tempFile = $_FILES["foto"]["tmp_name"];

    // Kompresi gambar
    $compressedFile = compressImage($tempFile, $targetFile, 75); // 75% kualitas

    if (!$compressedFile) {
      $errors['foto'] = "Terjadi kesalahan saat mengompresi gambar!";
      $targetFile = null;
    }
  } else {
    $targetFile = null; // Foto tidak wajib
  }

  if (empty($errors)) {
    // Query untuk menambahkan karyawan ke dalam database
    $sql = "INSERT INTO employees (nama, alamat, jabatan, phone, email, foto) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $nama, $alamat, $jabatan, $phone, $email, $fileName);

    if ($stmt->execute()) {
      // Panggil fungsi untuk mengurutkan ulang ID setelah insert
      reorderIDs($conn);
      $_SESSION['notification'] = 'success';
    } else {
      $_SESSION['notification'] = 'error';
    }
    $stmt->close();

    header("Location: add_employee.php");
    exit();
  }
}

// Fungsi untuk mengurutkan ulang ID
function reorderIDs($conn)
{
  // Ambil semua data
  $sql = "SELECT * FROM employees ORDER BY id ASC";
  $result = $conn->query($sql);
  $data = $result->fetch_all(MYSQLI_ASSOC);
  $result->close();

  // Reset auto increment
  $conn->query("ALTER TABLE employees AUTO_INCREMENT = 1");

  // Ubah ID
  foreach ($data as $index => $row) {
    $newId = $index + 1;
    $sql = "UPDATE employees SET id = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $newId, $row['id']);
    $stmt->execute();
    $stmt->close();
  }
}

// Ambil notifikasi dari sesi dan hapus setelah ditampilkan
$notification = isset($_SESSION['notification']) ? $_SESSION['notification'] : '';
unset($_SESSION['notification']);
?>

<!DOCTYPE html>
<html lang="id">

<?php include('include/header.php'); ?>

<div class="container-fluid">
  <div class="row">
    <div class="col-12 col-md-3">
      <?php include('include/sidebar.php'); ?>
    </div>

    <div class="col-12 col-md-9">
      <!-- Formulir Tambah Karyawan -->
      <div class="card shadow">
        <div class="card-body m-4">
          <h3 class="card-title text-center fw-bold">Tambah Karyawan</h3>
          <hr class="my-4">
          <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" id="employeeForm">
            <!-- Input nama -->
            <div class="mb-4">
              <label for="nama" class="form-label fw-bold">Nama Lengkap</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa-regular fa-user"></i></span>
                <input type="text" class="form-control text-bg-light" id="nama" name="nama" placeholder="Nama Lengkap" required>
              </div>
            </div>

            <!-- Input alamat -->
            <div class="mb-4">
              <label for="alamat" class="form-label fw-bold">Alamat Lengkap</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa-regular fa-map-marker-alt"></i></span>
                <input type="text" class="form-control text-bg-light" id="alamat" name="alamat" placeholder="Alamat Lengkap" required>
              </div>
            </div>

            <!-- Input jabatan -->
            <div class="mb-4">
              <label for="jabatan" class="form-label fw-bold">Jabatan</label>
              <select class="form-select text-bg-light" id="jabatan" name="jabatan" required>
                <option value="" selected disabled>Pilih Jabatan</option>
                <option value="Akuntan">Akuntan</option>
                <option value="Asisten">Asisten</option>
                <option value="HR">HR</option>
                <option value="IT">IT</option>
                <option value="Manager">Manager</option>
                <option value="Marketing">Marketing</option>
                <option value="Sekretaris">Sekretaris</option>
                <option value="Staff">Staff</option>
                <option value="Supervisor">Supervisor</option>
              </select>
            </div>

            <!-- Input nomor HP -->
            <div class="mb-4">
              <label for="phone" class="form-label fw-bold">Nomor HP</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa-regular fa-phone"></i></span>
                <input type="text" class="form-control text-bg-light" id="phone" name="phone" placeholder="Nomor HP" required>
              </div>
            </div>

            <!-- Input email -->
            <div class="mb-4">
              <label for="email" class="form-label fw-bold">Email</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa-regular fa-envelope"></i></span>
                <input type="email" class="form-control text-bg-light" id="email" name="email" placeholder="Email" required>
              </div>
            </div>

            <!-- Input foto -->
            <div class="mb-4">
              <label for="foto" class="form-label fw-bold">Unggah Foto</label>
              <div class="input-group">
                <input type="file" class="form-control text-bg-light" id="foto" name="foto" onchange="previewImage()">
                <label class="input-group-text" for="foto"><i class="fa-regular fa-cloud-arrow-up"></i></label>
              </div>
              <div class="form-text text-muted small">Format gambar yang diperbolehkan: JPG, JPEG, PNG, WEBP. Ukuran maksimal 2MB.</div>
            </div>
            <div class="d-flex justify-content-center">
              <img id="fotoPreview" src="#" alt="Preview Gambar" class="img-fluid rounded-circle border border-3 border-white shadow" style="display: none; width: 200px; height: 200px; object-fit: cover;">
            </div>
            <hr class="my-4">
            <!-- Tombol tambah karyawan -->
            <div class="text-center">
              <button type="submit" class="btn btn-primary" name="addEmployee" onclick="return validateForm()"><i class="fa-regular fa-user-plus me-2"></i>Tambah Karyawan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include('include/footer.php'); ?>

<script type="text/javascript">
  document.addEventListener('DOMContentLoaded', () => {
    const notification = "<?php echo $notification; ?>";

    if (notification === 'success') {
      Swal.fire({
        title: 'Sukses!',
        text: 'Karyawan berhasil ditambahkan!',
        icon: 'success',
        confirmButtonText: 'Ok'
      });
    } else if (notification === 'error') {
      Swal.fire({
        title: 'Error!',
        text: 'Terjadi kesalahan saat menambahkan karyawan!',
        icon: 'error',
        confirmButtonText: 'Ok'
      });
    } else if (notification === 'email_exists') {
      Swal.fire({
        title: 'Error!',
        text: 'Email sudah terdaftar!',
        icon: 'error',
        confirmButtonText: 'Ok'
      });
    } else if (notification === 'phone_exists') {
      Swal.fire({
        title: 'Error!',
        text: 'Nomor telepon sudah terdaftar!',
        icon: 'error',
        confirmButtonText: 'Ok'
      });
    } else if (notification === 'name_exists') {
      Swal.fire({
        title: 'Error!',
        text: 'Nama sudah terdaftar!',
        icon: 'error',
        confirmButtonText: 'Ok'
      });
    }

    $("#alamat").autocomplete({
      source: function(request, response) {
        $.ajax({
          url: "include/lokasi.php",
          dataType: "json",
          data: {
            query: request.term
          },
          success: function(data) {
            response(data);
          },
          error: function(xhr, status, error) {
            console.log("AJAX Error: ", status, error);
            response([]);
          }
        });
      },
      minLength: 1
    });
  });

  function previewImage() {
    const input = document.getElementById('foto');
    const preview = document.getElementById('fotoPreview');
    const file = input.files[0];
    const reader = new FileReader();

    reader.onload = function(e) {
      preview.src = e.target.result;
      preview.style.display = 'block';
    };

    if (file) {
      reader.readAsDataURL(file);
    } else {
      preview.src = '';
      preview.style.display = 'none';
    }
  }

  function validateForm() {
    const nama = document.getElementById('nama').value.trim();
    const alamat = document.getElementById('alamat').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const email = document.getElementById('email').value.trim();
    const jabatan = document.getElementById('jabatan').value;

    if (nama === '' || alamat === '' || phone === '' || email === '' || jabatan === '') {
      Swal.fire({
        title: 'Error!',
        text: 'Semua kolom wajib diisi!',
        icon: 'error',
        confirmButtonText: 'Ok'
      });
      return false;
    }
    return true;
  }
</script>

<?php
$conn->close();
?>