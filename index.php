<?php include "koneksi.php"; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Produk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <style>
    body {
        background-color: #f8f9fa;
    }
    .table img {
        border-radius: 8px;
    }
    .search-box {
        max-width: 400px;
    }
  </style>
</head>
<body>

<div class="container mt-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">📦 Manajemen Produk</h2>
    <a href="tambah.php" class="btn btn-primary">➕ Tambah Produk</a>
  </div>

  <!-- Form Pencarian -->
  <form method="GET" action="" class="mb-4">
    <div class="input-group search-box">
      <input type="text" class="form-control" name="cari" placeholder="Cari produk..." 
             value="<?= isset($_GET['cari']) ? $_GET['cari'] : '' ?>">
      <button class="btn btn-outline-secondary" type="submit">Cari</button>
      <a href="index.php" class="btn btn-outline-danger">Reset</a>
    </div>
  </form>

  <!-- Tabel Produk -->
  <div class="card shadow">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-dark">
            <tr>
              <th>Action</th>
              <th>Gambar</th>
              <th>Kode</th>
              <th>Nama</th>
              <th>Satuan</th>
              <th>Harga</th>
            </tr>
          </thead>
          <tbody>
          <?php
          // ambil keyword pencarian kalau ada
          $where = "";
          if (isset($_GET['cari']) && $_GET['cari'] != "") {
              $cari = mysqli_real_escape_string($koneksi, $_GET['cari']);
              $where = "WHERE kode LIKE '%$cari%' OR nama LIKE '%$cari%' OR satuan LIKE '%$cari%'";
          }

          $result = mysqli_query($koneksi, "SELECT * FROM produk $where ORDER BY id DESC");
          if (mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
                  echo "<tr>
                          <td>
                            <a href='edit.php?id=".$row['id']."' class='btn btn-sm btn-warning'>✏️ Edit</a>
                          </td>
                          <td><img src='uploads/".$row['gambar']."' width='60'></td>
                          <td>".$row['kode']."</td>
                          <td>".$row['nama']."</td>
                          <td>".$row['satuan']."</td>
                          <td><span class='badge bg-success'>Rp ".number_format($row['harga'], 0, ',', '.')."</span></td>
                        </tr>";
              }
          } else {
              echo "<tr><td colspan='6' class='text-center text-muted'>Tidak ada produk ditemukan</td></tr>";
          }
          ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Tambahin animasi hover row pake jQuery
  $(document).ready(function(){
    $("table tbody tr").hover(
      function(){ $(this).addClass("table-active"); },
      function(){ $(this).removeClass("table-active"); }
    );
  });
</script>
</body>
</html>
