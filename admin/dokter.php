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
</head>
<body class="bg-light">

<div class="container py-5">
  <h2 class="mb-4 text-center">🩺 Form Input Dokter</h2>

  <!-- Form Input -->
  <div class="card shadow-sm mb-5">
    <div class="card-body">
      <form method="POST" action="simpan_dokter.php" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label">Nama Dokter</label>
          <input type="text" name="nama" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Nomor SIP</label>
          <input type="text" name="izin" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Spesialis</label>
          <input type="text" name="spesialis" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Pilih Poli</label>
          <select name="poli_id" class="form-select" required>
            <option value="">-- Pilih Poli --</option>
            <?php while($row = mysqli_fetch_assoc($poli)) { ?>
              <option value="<?= $row['id'] ?>"><?= $row['nama_poli'] ?></option>
            <?php } ?>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Foto Dokter</label>
          <input type="file" name="foto" class="form-control">
        </div>
        <hr>
        <h5 class="mb-3">Jadwal Praktek</h5>
        <div class="row">
          <div class="col-md-4 mb-3">
            <label class="form-label">Hari</label>
            <select name="hari" class="form-select">
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
          <div class="col-md-4 mb-3">
            <label class="form-label">Jam Mulai</label>
            <input type="time" name="jam_mulai" class="form-control">
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">Jam Selesai</label>
            <input type="time" name="jam_selesai" class="form-control">
          </div>
        </div>
        <button type="submit" class="btn btn-primary">💾 Simpan</button>
      </form>
    </div>
  </div>

  <!-- Daftar Dokter -->
  <h2 class="mb-3 text-center">📋 Daftar Dokter</h2>
  <div class="card shadow-sm">
    <div class="card-body">
      <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark">
          <tr>
            <th>Nama</th>
            <th>SIP</th>
            <th>Spesialis</th>
            <th>Poli</th>
            <th>Foto</th>
            <th width="100">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php while($d = mysqli_fetch_assoc($dokter)) { ?>
            <tr>
              <td><?= $d['nama'] ?></td>
              <td><?= $d['izin'] ?></td>
              <td><?= $d['spesialis'] ?></td>
              <td><?= $d['nama_poli'] ?></td>
              <td>
                <?php if($d['foto']) { ?>
                  <img src="<?= $d['foto'] ?>" width="80" class="rounded">
                <?php } else { ?>
                  <span class="text-muted">Tidak ada</span>
                <?php } ?>
              </td>
              <td>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
