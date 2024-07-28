<?php
require_once('include/db_connection.php');
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}

// Periksa apakah parameter 'id' ada di URL
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id === false || $id === null) {
  // Redirect langsung jika ID tidak valid atau tidak ada
  echo "<script>window.location.href = 'employee_list.php';</script>";
  exit();
}

// Ambil data karyawan berdasarkan id dengan prepared statements
$stmt = $conn->prepare("SELECT id, nama, alamat, jabatan, phone, email, foto FROM employees WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->store_result();

$employee = null;
$stmt->bind_result($resultId, $resultNama, $resultAlamat, $resultJabatan, $resultPhone, $resultEmail, $resultFoto);

if ($stmt->fetch()) {
  $employee = [
    'id' => $resultId,
    'nama' => $resultNama,
    'alamat' => $resultAlamat,
    'jabatan' => $resultJabatan,
    'phone' => $resultPhone,
    'email' => $resultEmail,
    'foto' => $resultFoto
  ];
}

$stmt->close();

if ($employee === null) {
  // Redirect jika karyawan tidak ditemukan
  echo "<script>window.location.href = 'employee_list.php';</script>";
  exit();
}

// Set judul halaman menggunakan nama karyawan
$pageTitle = "Edit Data " . htmlspecialchars($employee['nama'], ENT_QUOTES, 'UTF-8');

// Ambil data karyawan sebelumnya dan selanjutnya
$prevId = $id > 1 ? $id - 1 : null;
$nextId = $id + 1;

$prevEmployee = null;
$nextEmployee = null;

// Query untuk mendapatkan data karyawan sebelumnya
if ($prevId !== null) {
  $stmt = $conn->prepare("SELECT id, nama FROM employees WHERE id = ?");
  $stmt->bind_param("i", $prevId);
  $stmt->execute();
  $stmt->store_result();
  $stmt->bind_result($resultId, $resultNama);

  if ($stmt->fetch()) {
    $prevEmployee = [
      'id' => $resultId,
      'nama' => $resultNama
    ];
  }
  $stmt->close();
}

// Query untuk mendapatkan data karyawan selanjutnya
$stmt = $conn->prepare("SELECT id, nama FROM employees WHERE id = ?");
$stmt->bind_param("i", $nextId);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($resultId, $resultNama);

if ($stmt->fetch()) {
  $nextEmployee = [
    'id' => $resultId,
    'nama' => $resultNama
  ];
}
$stmt->close();
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
      <div class="card shadow">
        <div class="card-body m-4">
          <h3 class="card-title text-center fw-bold">Edit Data Karyawan</h3>
          <hr class="my-4">
          <div class="d-flex flex-column align-items-center">
            <div class="d-grid justify-content-center align-items-center">
              <div class="border border-white border-4 rounded-circle overflow-hidden mb-4 shadow" style="width: 200px; height: 200px;">
                <img src="<?php echo !empty($employee['foto']) ? 'uploads/employees/' . htmlspecialchars($employee['foto'], ENT_QUOTES, 'UTF-8') : 'assets/images/oxfam.png'; ?>" alt="Foto Karyawan" class="img-fluid rounded-circle w-100 h-100 object-fit-cover">
              </div>
            </div>
            <h3 class="text-center font-weight-bold"><?php echo htmlspecialchars($employee['nama'], ENT_QUOTES, 'UTF-8'); ?></h3>
            <p class="text-center fw-bold text-muted"><?php echo htmlspecialchars($employee['jabatan'], ENT_QUOTES, 'UTF-8'); ?></p>
          </div>
          <hr class="mb-4">
          <form method="post" action="update_employee.php" id="editForm">
            <div class="mb-4">
              <label for="nama" class="form-label fw-bold">Nama Lengkap</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa-regular fa-user"></i></span>
                <input type="text" class="form-control text-bg-light" id="nama" name="nama" value="<?php echo htmlspecialchars($employee['nama'], ENT_QUOTES, 'UTF-8'); ?>">
              </div>
            </div>
            <div class="mb-4">
              <label for="alamat" class="form-label fw-bold">Alamat Lengkap</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa-regular fa-location-dot"></i></span>
                <input type="text" class="form-control text-bg-light" id="alamat" name="alamat" value="<?php echo htmlspecialchars($employee['alamat'], ENT_QUOTES, 'UTF-8'); ?>">
              </div>
            </div>
            <div class="mb-4">
              <label for="jabatan" class="form-label fw-bold">Jabatan</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa-regular fa-briefcase"></i></span>
                <select class="form-control text-bg-light" id="jabatan" name="jabatan">
                  <option value="" disabled>Pilih Jabatan</option>
                  <option value="Akuntan" <?php echo $employee['jabatan'] === 'Akuntan' ? 'selected' : ''; ?>>Akuntan</option>
                  <option value="Asisten" <?php echo $employee['jabatan'] === 'Asisten' ? 'selected' : ''; ?>>Asisten</option>
                  <option value="HR" <?php echo $employee['jabatan'] === 'HR' ? 'selected' : ''; ?>>HR</option>
                  <option value="IT" <?php echo $employee['jabatan'] === 'IT' ? 'selected' : ''; ?>>IT</option>
                  <option value="Manager" <?php echo $employee['jabatan'] === 'Manager' ? 'selected' : ''; ?>>Manager</option>
                  <option value="Marketing" <?php echo $employee['jabatan'] === 'Marketing' ? 'selected' : ''; ?>>Marketing</option>
                  <option value="Sekretaris" <?php echo $employee['jabatan'] === 'Sekretaris' ? 'selected' : ''; ?>>Sekretaris</option>
                  <option value="Staff" <?php echo $employee['jabatan'] === 'Staff' ? 'selected' : ''; ?>>Staff</option>
                  <option value="Supervisor" <?php echo $employee['jabatan'] === 'Supervisor' ? 'selected' : ''; ?>>Supervisor</option>
                </select>
              </div>
            </div>
            <div class="mb-4">
              <label for="phone" class="form-label fw-bold">Nomor HP</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa-regular fa-phone"></i></span>
                <input type="tel" class="form-control text-bg-light" id="phone" name="phone" value="<?php echo htmlspecialchars($employee['phone'], ENT_QUOTES, 'UTF-8'); ?>">
              </div>
            </div>
            <div class="mb-4">
              <label for="email" class="form-label fw-bold">Email</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa-regular fa-envelope"></i></span>
                <input type="email" class="form-control text-bg-light" id="email" name="email" value="<?php echo htmlspecialchars($employee['email'], ENT_QUOTES, 'UTF-8'); ?>">
              </div>
            </div>
            <div class="d-flex justify-content-between align-items-center">
              <div class="d-grid">
                <?php if ($prevEmployee !== null): ?>
                  <a href="edit_employee.php?id=<?php echo htmlspecialchars($prevEmployee['id'], ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-secondary">
                    <i class="fa-regular fa-arrow-left me-2"></i><?php echo htmlspecialchars($prevEmployee['nama'], ENT_QUOTES, 'UTF-8'); ?>
                  </a>
                <?php endif; ?>
              </div>
              <div class="d-grid">
                <?php if ($nextEmployee !== null): ?>
                  <a href="edit_employee.php?id=<?php echo htmlspecialchars($nextEmployee['id'], ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-secondary">
                    <?php echo htmlspecialchars($nextEmployee['nama'], ENT_QUOTES, 'UTF-8'); ?><i class="fa-regular fa-arrow-right ms-2"></i>
                  </a>
                <?php endif; ?>
              </div>
            </div>
            <div class="mt-4 text-center">
              <button type="button" class="btn btn-primary" id="btnSave"><i class="fa-regular fa-floppy-disk me-2"></i>Simpan</button>
              <a href="employee_list.php" class="btn btn-danger"><i class="fa-regular fa-xmark me-2"></i>Batal</a>
            </div>
            <input type="hidden" name="editEmployee" value="1">
            <input type="hidden" name="id" value="<?php echo $employee['id']; ?>">
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include('include/footer.php'); ?>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editForm');
    const btnSave = document.getElementById('btnSave');
    const originalData = {
      nama: form.nama.value,
      alamat: form.alamat.value,
      jabatan: form.jabatan.value,
      phone: form.phone.value,
      email: form.email.value
    };

    btnSave.addEventListener('click', function(event) {
      let errors = [];

      if (form.nama.value.trim() === '') {
        errors.push('Nama Lengkap');
      }
      if (form.alamat.value.trim() === '') {
        errors.push('Alamat Lengkap');
      }
      if (form.jabatan.value.trim() === '') {
        errors.push('Jabatan');
      }
      if (form.phone.value.trim() === '') {
        errors.push('Nomor HP');
      }
      if (form.email.value.trim() === '') {
        errors.push('Email');
      }

      if (errors.length > 0) {
        let errorMessage = 'Harap isi: ' + errors.join(', ') + '.';
        Swal.fire('Gagal!', errorMessage, 'error');
      } else {
        let isChanged = false;
        for (let key in originalData) {
          if (originalData[key] !== form[key].value) {
            isChanged = true;
            break;
          }
        }

        if (!isChanged) {
          Swal.fire('Tidak ada perubahan', 'Tidak ada data yang diubah.', 'info');
          return;
        }

        Swal.fire({
          title: 'Konfirmasi',
          text: 'Apakah Anda yakin ingin menyimpan perubahan?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Ya, simpan',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            form.submit();
          }
        });
      }
    });

    <?php if (isset($_SESSION['success'])): ?>
      Swal.fire('Sukses!', '<?php echo $_SESSION['success']; ?>', 'success');
      <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
      Swal.fire('Gagal!', '<?php echo $_SESSION['error']; ?>', 'error');
      <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
  });
</script>
</body>

</html>