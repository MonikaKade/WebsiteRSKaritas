<?php
include("config.php");

// Ambil semua poli
$poli = mysqli_query($conn, "SELECT * FROM poli");

// Ambil semua dokter
$dokter = mysqli_query($conn, 
    "SELECT d.*, p.nama_poli 
     FROM dokter d 
     JOIN poli p ON d.poli_id = p.id");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manajemen Dokter</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }

    h2 {
      font-weight: 600;
    }

    .card {
      border-radius: 10px;
    }

    .card-body {
      padding: 2rem;
    }

    .form-label {
      font-weight: 500;
    }

    .btn {
      border-radius: 6px;
    }

    .table th {
      text-align: center;
    }

    .table td {
      vertical-align: middle;
    }

    /* Bagian header halaman */
    .page-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
      flex-wrap: wrap;
      gap: 10px;
    }

    /* Efek hover pada tombol tabel */
    .table .btn:hover {
      opacity: 0.9;
    }

    /* Spasi antar bagian */
    .section-title {
      margin-top: 60px;
      margin-bottom: 20px;
      font-weight: 600;
      text-align: center;
    }

    /* Responsive fix */
    @media (max-width: 768px) {
      .card-body {
        padding: 1rem;
      }
    }
  </style>
</head>
<body>

<div class="container py-5">
  <div class="page-header">
    <h2>🩺 Manajemen Dokter</h2>
    <a href="dashboard.php" class="btn btn-secondary">⬅ Kembali ke Dashboard</a>
  </div>

  <!-- Form Input Dokter -->
  <div class="card shadow-sm mb-5">
    <div class="card-body">
      <h4 class="mb-4 text-center">Form Input Dokter</h4>
      <form method="POST" action="simpan_dokter.php" enctype="multipart/form-data">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Nama Dokter</label>
            <input type="text" name="nama" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Nomor SIP</label>
            <input type="text" name="izin" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Spesialis</label>
            <input type="text" name="spesialis" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Pilih Poli</label>
            <select name="poli_id" class="form-select" required>
              <option value="">-- Pilih Poli --</option>
              <?php while($row = mysqli_fetch_assoc($poli)) { ?>
                <option value="<?= $row['id'] ?>"><?= $row['nama_poli'] ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Foto Dokter</label>
            <input type="file" name="foto" class="form-control">
          </div>
        </div>

        <hr class="my-4">

        <h5 class="mb-3">Jadwal Praktek</h5>
        <div id="jadwal-container">
          <div class="row jadwal-row align-items-end g-3">
            <div class="col-md-4">
              <label class="form-label">Hari</label>
              <select name="hari[]" class="form-select">
                <option value="">-- Pilih Hari --</option>
                <option value="Senin">Senin</option>
                <option value="Selasa">Selasa</option>
                <option value="Rabu">Rabu</option>
                <option value="Kamis">Kamis</option>
                <option value="Jumat">Jumat</option>
                <option value="Sabtu">Sabtu</option>
                <option value="Minggu">Minggu</option>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Jam Mulai</label>
              <input type="time" name="jam_mulai[]" class="form-control">
            </div>
            <div class="col-md-3">
              <label class="form-label">Jam Selesai</label>
              <input type="time" name="jam_selesai[]" class="form-control">
            </div>
            <div class="col-md-2 text-center">
              <button type="button" class="btn btn-danger btn-remove">❌</button>
            </div>
          </div>
        </div>

        <div class="mt-3">
          <button type="button" class="btn btn-success me-2" id="add-jadwal">➕ Tambah Jadwal</button>
          <button type="submit" class="btn btn-primary">💾 Simpan Dokter</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Daftar Dokter -->
  <h3 class="section-title">📋 Daftar Dokter</h3>
  <div class="card shadow-sm">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-dark">
            <tr>
              <th>Nama</th>
              <th>SIP</th>
              <th>Spesialis</th>
              <th>Poli</th>
              <th>Foto</th>
              <th width="150">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php while($d = mysqli_fetch_assoc($dokter)) { ?>
              <tr>
                <td><?= htmlspecialchars($d['nama']); ?></td>
                <td><?= htmlspecialchars($d['izin']); ?></td>
                <td><?= htmlspecialchars($d['spesialis']); ?></td>
                <td><?= htmlspecialchars($d['nama_poli']); ?></td>
                <td class="text-center">
                  <?php if($d['foto']) { ?>
                    <img src="<?= htmlspecialchars($d['foto']); ?>" width="80" class="rounded shadow-sm">
                  <?php } else { ?>
                    <span class="text-muted">Tidak ada</span>
                  <?php } ?>
                </td>
                <td class="text-center">
                  <a href="edit_dokter.php?id=<?= $d['id'] ?>" class="btn btn-sm btn-warning me-1">✏️ Edit</a>
                  <a href="hapus_dokter.php?id=<?= $d['id'] ?>" 
                     class="btn btn-sm btn-danger"
                     onclick="return confirm('Yakin mau hapus dokter ini?')">🗑 Hapus</a>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Tambah row jadwal baru
document.getElementById('add-jadwal').addEventListener('click', function() {
  let container = document.getElementById('jadwal-container');
  let row = document.querySelector('.jadwal-row').cloneNode(true);

  // kosongkan value input
  row.querySelectorAll('input, select').forEach(el => el.value = "");

  // tambahkan event remove
  row.querySelector('.btn-remove').addEventListener('click', function(){
    row.remove();
  });

  container.appendChild(row);
});

// aktifkan tombol remove untuk row pertama
document.querySelectorAll('.btn-remove').forEach(btn => {
  btn.addEventListener('click', function(){
    btn.closest('.jadwal-row').remove();
  });
});
</script>
</body>
</html>
