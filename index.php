<?php include "koneksi.php"; ?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Daftar Produk</title>
  <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    body {
      background-color: #1C1B29;
    }

    .table img {
      border-radius: 8px;
    }

    .search-box {
      max-width: 400px;
    }

    .form-control:focus {
      box-shadow: none !important;
      border-color: #1C1B29;
    }

    .btn-cari {
      background-color: #CCCCCC;
      color: #000;
      transition: all 0.3s ease;
      border: none;
    }

    .btn-cari:hover {
      background-color: #b0b0b0;
      color: #000;
    }

    .btn-cari:active,
    .btn-cari:focus {
      background-color: #b0b0b0 !important;
      color: #000 !important;
      box-shadow: none !important;
    }

    .btn-reset {
      background-color: #E81313;
      color: #fff;
      transition: all 0.3s ease;
      border: none;
    }

    .btn-reset:hover {
      background-color: #b50f0f;
      color: #fff;
    }

    .btn-reset:active,
    .btn-reset:focus {
      background-color: #b50f0f !important;
      color: #fff !important;
      box-shadow: none !important;
    }



    .btn-custom .hover-state {
      background-color: #E81313;
      transform: translateY(100%);
      transition: transform 0.3s ease;
    }

    .btn-custom .default-state {
      transition: transform 0.3s ease;
    }

    .btn-custom:hover .default-state {
      transform: translateY(-100%);
    }

    .btn-custom:hover .hover-state {
      transform: translateY(0%);
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg py-3 px-2 shadow-sm mb-4" style="background-color:#000;">
    <div class="container-fluid">
      <div>
        <img src="logo/logog.png" alt="Logo Gudang" width="35" height="40" class="pb-2 me-1">
        <a class="navbar-brand fw-bold fs-4 text-white" href="index.php">Manajemen Produk</a>
      </div>

      <form method="GET" action="" class="d-flex">
        <div class="input-group">
          <input type="text" class="form-control" name="cari" placeholder="Cari produk..."
            value="<?= isset($_GET['cari']) ? $_GET['cari'] : '' ?>">
          <button class="btn btn-cari" type="submit">Cari</button>
          <a href="index.php" class="btn btn-reset">Reset</a>
        </div>
      </form>
    </div>
  </nav>


  <!-- Tombol Tambah Produk -->
  <div class="container-fluid px-4">
    <div class="d-flex justify-content-end mb-3">
      <a href="tambah.php"
        class="btn btn-custom text-black position-relative overflow-hidden px-3 d-inline-flex align-items-center gap-2"
        style="background-color:#FFFFFF; text-decoration:none; width:auto;">
        <span class="default-state d-flex align-items-center gap-2">
          <span>Tambah Produk</span>
        </span>
        <span
          class="hover-state d-flex align-items-center gap-2 position-absolute top-0 start-0 w-100 h-100 justify-content-center">
          <i class="bi bi-cart text-warning"></i>
          <span class="text-warning">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white"
              viewBox="0 0 16 16">
              <path
                d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
            </svg>
          </span>
        </span>
      </a>
    </div>
  </div>


  <!-- Tabel Produk -->
  <div class="card shadow-lg border-0 mx-4">
    <div class="card-header text-white d-flex justify-content-between align-items-center">
      <h5 class="mb-0 text-black">Daftar Produk</h5>
      <?php
      // hitung total produk (tanpa filter pencarian)
      $countAll = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM produk");
      $totalProduk = mysqli_fetch_assoc($countAll)['total'];
      ?>
      <span class="badge bg-dark py-2"><?= $totalProduk ?> Produk</span>
    </div>

    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped table-hover align-middle text-center">
          <thead class="table-dark">
            <tr>
              <th scope="col">Action</th>
              <th scope="col">Gambar</th>
              <th scope="col">Kode</th>
              <th scope="col">Nama</th>
              <th scope="col">Satuan</th>
              <th scope="col">Harga</th>
            </tr>
          </thead>
          <tbody>
            <?php
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
                        <a href='edit.php?id=" . $row['id'] . "' class='btn btn-sm btn-warning shadow-sm'>
                          ✏️ Edit
                        </a>
                      </td>
                      <td>
                        <img src='uploads/" . htmlspecialchars($row['gambar']) . "' width='60' class='rounded shadow-sm'>
                      </td>
                      <td><span class='fw-semibold'>" . htmlspecialchars($row['kode']) . "</span></td>
                      <td>" . htmlspecialchars($row['nama']) . "</td>
                      <td><span class='badge text-dark px-3 py-2'>" . htmlspecialchars($row['satuan']) . "</span></td>
                      <td><span class='badge text-black fs-6 px-3 py-2'>Rp " . number_format($row['harga'], 0, ',', '.') . "</span></td>
                    </tr>";
              }
            } else {
              echo "<tr>
                    <td colspan='6' class='text-center text-muted py-4'>
                      🚫 Tidak ada produk ditemukan
                    </td>
                  </tr>";
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
    $(document).ready(function() {
      $("table tbody tr").hover(
        function() {
          $(this).addClass("table-active");
        },
        function() {
          $(this).removeClass("table-active");
        }
      );
    });
  </script>
</body>

</html>