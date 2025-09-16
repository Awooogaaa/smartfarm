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
      background-color: #EDEDED;
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

    /* Modern Search Container */
    .search-container {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      padding: 2rem;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }

    /* Enhanced Search Box */
    .search-box {
      position: relative;
      max-width: 400px;
      width: 100%;
    }

    .search-input {
      background: #ffffffff;
      color: #ccccccff;
      border: 2px solid #e4e4e4ff;
      border-radius: 50px;
      padding: 10px 56px 10px 16px;
      font-size: 15px;
      height: 46px;
      /* lebih tinggi */
    }

    .search-input:focus {
      outline: none;
      border-color: #2AF598;
      box-shadow: 0 0 0 3px rgba(42, 245, 152, 0.1), 0 4px 20px rgba(0, 0, 0, 0.1);
      background: #ffffff;
      color: #495057;
    }

    .search-input::placeholder {
      color: #adb5bd;
      font-weight: 400;
    }

    /* Modern Search Button */
    .btn-search {
      position: absolute;
      top: 50%;
      right: 4px;
      transform: translateY(-50%);
      width: 40px;
      height: 40px;
      background: linear-gradient(135deg, #2AF598 0%, #009EFD 100%);
      border: none;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s ease;
    }

    .btn-search svg {
      width: 18px;
      height: 18px;
    }

    .btn-search:hover {
      transform: translateY(-50%) scale(1.05);
      box-shadow: 0 4px 20px rgba(42, 245, 152, 0.4);
      background: linear-gradient(135deg, #009EFD 0%, #2AF598 100%);
    }

    .btn-search:active {
      transform: translateY(-50%) scale(0.95);
    }

    /* Modern Reset Button */
    .btn-reset {
      background: #dc3545;
      color: #fff;
      border: none;
      border-radius: 25px;
      padding: 0 18px;
      font-size: 15px;
      height: 40px;
      /* sejajar input */
      display: flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      transition: 0.3s;
    }

    .btn-reset:hover {
      background: #bb2d3b;
      color: #fff;
    }

    .btn-reset:active {
      transform: translateY(0);
    }

    /* Search Form Layout */
    .search-form {
      display: flex;
      align-items: center;
      gap: 16px;
      flex-wrap: wrap;
      justify-content: center;
    }

    /* Responsive Design */
    @media (max-width: 576px) {
      .search-container {
        margin: 0 1rem;
        padding: 1.5rem;
      }

      .search-form {
        flex-direction: column;
        gap: 12px;
      }

      .search-box {
        max-width: 100%;
      }
    }

    /* Search Icon Animation */
    .search-icon {
      transition: transform 0.3s ease;
    }

    .btn-search:hover .search-icon {
      transform: rotate(90deg);
    }

    /* Reset Icon */
    .reset-icon {
      width: 16px;
      height: 16px;
    }

    .btn-custom .hover-state {
      background-color: #000000ff;
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
  <nav class="navbar navbar-expand-lg py-2 px-3 shadow-sm mb-5 mx-5 my-3 rounded-pill" style="background-color:#ffffff;">
    <div class="container-fluid">
      <div>
        <a class="navbar-brand fw-bold fs-4 text-black" href="index.php">Manajemen Produk</a>
      </div>
      <form method="GET" action="" class="search-form">
        <div class="d-flex align-items-center gap-2">
          <!-- Input + Search -->
          <div class="search-box position-relative" style="flex:1;">
            <input type="text"
              class="form-control search-input"
              name="cari"
              placeholder="Cari produk..."
              value="<?= isset($_GET['cari']) ? htmlspecialchars($_GET['cari']) : '' ?>">
            <button type="submit" class="btn-search">
              <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
              </svg>
            </button>
          </div>

          <!-- Reset -->
          <a href="index.php" class="btn-reset">
            Reset
          </a>
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