<?php
include("config.php");

$id = $_GET['id'];

// Ambil data dokter
$dokter = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM dokter WHERE id=$id"));

// Ambil semua poli
$poli = mysqli_query($conn, "SELECT * FROM poli");

// Ambil jadwal dokter
$jadwal = mysqli_query($conn, "SELECT * FROM jadwal_dokter WHERE dokter_id=$id");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Dokter</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
  <h2>Edit Dokter</h2>
  <form method="POST" action="update_dokter.php" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $dokter['id'] ?>">
    <input type="hidden" name="foto_lama" value="<?= $dokter['foto'] ?>">

    <div class="mb-3">
      <label class="form-label">Nama Dokter</label>
      <input type="text" name="nama" class="form-control" value="<?= $dokter['nama'] ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Nomor SIP</label>
      <input type="text" name="izin" class="form-control" value="<?= $dokter['izin'] ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Spesialis</label>
      <input type="text" name="spesialis" class="form-control" value="<?= $dokter['spesialis'] ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Poli</label>
      <select name="poli_id" class="form-select" required>
        <?php while($p = mysqli_fetch_assoc($poli)) { ?>
          <option value="<?= $p['id'] ?>" <?= ($p['id']==$dokter['poli_id']) ? 'selected' : '' ?>>
            <?= $p['nama_poli'] ?>
          </option>
        <?php } ?>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Foto Dokter</label><br>
      <?php if($dokter['foto']) { ?>
        <img src="<?= $dokter['foto'] ?>" width="100" class="mb-2"><br>
      <?php } ?>
      <input type="file" name="foto" class="form-control">
    </div>

    <hr>
    <h5>Jadwal Praktek</h5>
    <div id="jadwal-container">
      <?php while($j = mysqli_fetch_assoc($jadwal)) { ?>
        <div class="row jadwal-row mb-2">
          <div class="col-md-4">
            <select name="hari[]" class="form-select">
              <option value="">-- Pilih Hari --</option>
              <?php 
              $hariList = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
              foreach($hariList as $h) {
                $selected = ($j['hari']==$h) ? 'selected' : '';
                echo "<option value='$h' $selected>$h</option>";
              }
              ?>
            </select>
          </div>
          <div class="col-md-3">
            <input type="time" name="jam_mulai[]" class="form-control" value="<?= $j['jam_mulai'] ?>">
          </div>
          <div class="col-md-3">
            <input type="time" name="jam_selesai[]" class="form-control" value="<?= $j['jam_selesai'] ?>">
          </div>
          <div class="col-md-2">
            <button type="button" class="btn btn-danger btn-remove">❌</button>
          </div>
        </div>
      <?php } ?>
    </div>
    <button type="button" class="btn btn-success mb-3" id="add-jadwal">➕ Tambah Jadwal</button>

    <button type="submit" class="btn btn-primary">💾 Update</button>
  </form>
</div>

<script>
// Tambah row jadwal baru
document.getElementById('add-jadwal').addEventListener('click', function() {
  let container = document.getElementById('jadwal-container');
  let row = document.createElement('div');
  row.classList.add('row','jadwal-row','mb-2');
  row.innerHTML = `
    <div class="col-md-4">
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
      <input type="time" name="jam_mulai[]" class="form-control">
    </div>
    <div class="col-md-3">
      <input type="time" name="jam_selesai[]" class="form-control">
    </div>
    <div class="col-md-2">
      <button type="button" class="btn btn-danger btn-remove">❌</button>
    </div>
  `;
  row.querySelector('.btn-remove').addEventListener('click', () => row.remove());
  container.appendChild(row);
});

// Aktifkan tombol remove
document.querySelectorAll('.btn-remove').forEach(btn => {
  btn.addEventListener('click', function(){
    btn.closest('.jadwal-row').remove();
  });
});
</script>
</body>
</html>
