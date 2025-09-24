<?php include "koneksi.php"; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Produk</title>
  <link href="modul/node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css" rel="stylesheet">

  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Navbar & Search */
    .search-input {
      background: #ffffffff;
      color: #495057;
      border: 2px solid #e4e4e4ff;
      border-radius: 50px;
      padding: 10px 56px 10px 16px;
      font-size: 15px;
      height: 46px;
    }
    .search-input:focus {
      outline: none;
      border-color: #2AF598;
    }
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
    .btn-search:hover {
        transform: translateY(-50%) scale(1.05);
    }
    .btn-reset {
      background: #dc3545;
      color: #fff;
      border-radius: 25px;
      font-size: 15px;
      height: 40px;
    }

    /* Card & Table */
    .main-card {
      background: #ffffff;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
      overflow: hidden;
    }
    .card-header {
      background: #ffffff !important;
      border-bottom: 1px solid #e9ecef !important;
      padding: 1.5rem;
    }
    .stats-badge {
      background: #e7f3ff;
      color: #0066cc;
      font-weight: 600;
      padding: 8px 16px;
      border-radius: 20px;
      font-size: 0.9rem;
    }

    /* Tombol Tambah Produk (Gaya Asli) */
    .btn-custom .hover-state {
      background: #009EFD;
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
    
    /* Table Styling */
    .table-clean {
        word-wrap: break-word;
    }
    .table-clean thead th {
      white-space: nowrap;
    }
    .product-image {
      width: 60px;
      height: 60px;
      border-radius: 8px;
      object-fit: cover;
    }
    .product-code { background: #fff3cd; color: #856404; padding: 6px 10px; border-radius: 6px; font-family: 'Courier New', monospace; font-size: 0.8rem;}
    .unit-badge { background: #d1ecf1; color: #0c5460; padding: 6px 10px; border-radius: 6px; font-size: 0.8rem;}
    .price-badge { background: #d4edda; color: #155724; padding: 8px 12px; border-radius: 6px; font-weight: 600;}

    /* --- PENYESUAIAN UNTUK MOBILE --- */
    @media (max-width: 768px) {
        .navbar {
            flex-direction: column;
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }
        .search-form {
            width: 100%;
            margin-top: 1rem;
        }
        /* Ukuran font di tabel diperbesar agar lebih jelas */
        .table-clean th, .table-clean td {
            font-size: 0.9rem; 
            padding: 0.85rem 0.6rem; /* Sedikit padding tambahan */
        }
        /* Ukuran font badge juga disesuaikan */
        .product-code, .unit-badge, .price-badge {
            font-size: 0.85rem;
        }
        .product-image {
            width: 50px;
            height: 50px;
        }
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg py-2 px-3 shadow-sm my-3 mx-md-5 mx-3 rounded-pill bg-white">
    <div class="container-fluid">
      <a class="navbar-brand fw-bold fs-4 text-black" href="index.php">Manajemen Produk</a>
      <form method="GET" action="" class="d-flex align-items-center gap-2 search-form" role="search">
        <div class="position-relative flex-grow-1">
          <input type="text"
            class="form-control search-input"
            name="cari"
            placeholder="Cari produk..."
            value="<?= isset($_GET['cari']) ? htmlspecialchars($_GET['cari']) : '' ?>">
          <button type="submit" class="btn-search">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
              <circle cx="11" cy="11" r="8"></circle>
              <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
          </button>
        </div>
        <a href="index.php" class="btn btn-reset d-flex align-items-center">Reset</a>
      </form>
    </div>
  </nav>

  <div class="container-fluid px-4">
    <?php
    // Query produk
    $where = "";
    $searchTerm = "";
    if (isset($_GET['cari']) && trim($_GET['cari']) != "") {
      $searchTerm = trim($_GET['cari']);
      $cari = mysqli_real_escape_string($koneksi, $searchTerm);
      $where = "WHERE kode LIKE '%$cari%' OR nama LIKE '%$cari%' OR satuan LIKE '%$cari%'";
    }

    $result = mysqli_query($koneksi, "SELECT * FROM produk $where ORDER BY id DESC");
    $countAll = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM produk");
    $totalProduk = mysqli_fetch_assoc($countAll)['total'];
    $filteredCount = mysqli_num_rows($result);
    ?>

    <div class="main-card">
      <div class="card-header">
        <div class="row align-items-center gy-3">
          <div class="col-md-8">
            <div class="d-flex align-items-center flex-wrap gap-3">
              <h4 class="mb-0 fw-bold text-dark"><i class="bi bi-grid-3x3-gap me-2 text-primary"></i>Daftar Produk</h4>
              <span class="stats-badge"><i class="bi bi-box me-1"></i><?= $filteredCount ?><?= $searchTerm ? " dari $totalProduk" : "" ?> Produk</span>
              <?php if ($searchTerm): ?>
                <span class="badge bg-secondary"><i class="bi bi-search me-1"></i>"<?= htmlspecialchars($searchTerm) ?>"</span>
              <?php endif; ?>
            </div>
          </div>
          <div class="col-md-4 text-md-end">
             <a href="tambah.php" class="btn btn-custom text-black position-relative overflow-hidden px-3 d-inline-flex align-items-center gap-2" style="background-color:#FFFFFF; text-decoration:none;">
              <span class="default-state d-flex align-items-center gap-2">
                <span>Tambah Produk</span>
              </span>
              <span class="hover-state d-flex align-items-center gap-2 position-absolute top-0 start-0 w-100 h-100 justify-content-center">
                <span class="text-warning">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white" viewBox="0 0 16 16">
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                  </svg>
                </span>
              </span>
            </a>
          </div>
        </div>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-clean align-middle">
            <thead>
              <tr>
                <th scope="col" class="text-center">Aksi</th>
                <th scope="col">Gambar</th>
                <th scope="col">Kode</th>
                <th scope="col">Nama Produk</th>
                <th scope="col">Satuan</th>
                <th scope="col">Harga</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                  echo "<tr>
                        <td class='text-center'>
                          <a href='edit.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm'>
                            Edit
                          </a>
                        </td>
                        <td>
                          <img src='uploads/" . htmlspecialchars($row['gambar']) . "' class='product-image' alt='Gambar " . htmlspecialchars($row['nama']) . "'>
                        </td>
                        <td><span class='product-code'>" . htmlspecialchars($row['kode']) . "</span></td>
                        <td class='fw-bold'>" . htmlspecialchars($row['nama']) . "</td>
                        <td><span class='unit-badge'>" . htmlspecialchars($row['satuan']) . "</span></td>
                        <td><span class='price-badge'>Rp " . number_format($row['harga'], 0, ',', '.') . "</span></td>
                      </tr>";
                }
              } else {
                echo "<tr><td colspan='6' class='text-center p-5'>Belum ada produk.</td></tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <script src="modul/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>