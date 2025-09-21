<?php include "koneksi.php"; ?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Produk</title>
  <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css" rel="stylesheet">

  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Navbar tetap seperti asli */
    .search-box {
      max-width: 400px;
    }

    .form-control:focus {
      box-shadow: none !important;
      border-color: #1C1B29;
    }

    .search-container {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      padding: 2rem;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .search-box {
      position: relative;
      max-width: 400px;
      width: 100%;
    }

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
      box-shadow: 0 0 0 3px rgba(42, 245, 152, 0.1), 0 4px 20px rgba(0, 0, 0, 0.1);
      background: #ffffff;
      color: #495057;
    }

    .search-input::placeholder {
      color: #adb5bd;
      font-weight: 400;
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

    .btn-search svg {
      width: 18px;
      height: 18px;
    }

    .btn-search:hover {
      transform: translateY(-50%) scale(1.05);
      box-shadow: 0 4px 20px rgba(42, 245, 152, 0.4);
      background: linear-gradient(135deg, #009EFD 0%, #2AF598 100%);
    }

    .btn-reset {
      background: #dc3545;
      color: #fff;
      border: none;
      border-radius: 25px;
      padding: 0 18px;
      font-size: 15px;
      height: 40px;
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

    .search-form {
      display: flex;
      align-items: center;
      gap: 16px;
      flex-wrap: wrap;
      justify-content: center;
    }

    /* Enhanced Card & Table - Simple but Clean */
    .main-card {
      background: #ffffff;
      border: 1px solid #e9ecef;
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

    /* Simple Clean Table */
    .table-clean {
      margin: 0;
      table-layout: fixed;
    }

    .table-clean thead th {
      background: #f8f9fa;
      color: #495057;
      border: none;
      border-bottom: 2px solid #dee2e6;
      padding: 1rem 0.8rem;
      font-weight: 600;
      font-size: 0.9rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .table-clean tbody td {
      padding: 1rem 0.8rem;
      border: none;
      border-bottom: 1px solid #f1f3f4;
      vertical-align: middle;
      word-wrap: break-word;
    }

    .table-clean tbody tr {
      transition: background-color 0.2s ease;
    }

    .table-clean tbody tr:hover {
      background-color: #f8f9fc;
    }

    /* Simple Badges */
    .product-code {
      background: #fff3cd;
      color: #856404;
      padding: 6px 12px;
      border-radius: 6px;
      font-family: 'Courier New', monospace;
      font-weight: 600;
      font-size: 0.85rem;
    }

    .unit-badge {
      background: #d1ecf1;
      color: #0c5460;
      padding: 6px 12px;
      border-radius: 6px;
      font-weight: 500;
      font-size: 0.85rem;
    }

    .price-badge {
      background: #d4edda;
      color: #155724;
      padding: 8px 14px;
      border-radius: 6px;
      font-weight: 600;
    }

    .btn-edit {
      background: #40b6ffff;
      border: none;
      color: #000;
      border-radius: 6px;
      padding: 8px 16px;
      font-size: 0.875rem;
      font-weight: 500;
      transition: all 0.2s ease;
    }

    .btn-edit:hover {
      background: #009dffff;
      color: #000;
      transform: translateY(-1px);
    }

    /* Product Image */
    .product-image {
      border-radius: 8px;
      border: 2px solid #f1f3f4;
      transition: all 0.2s ease;
    }

    .product-image:hover {
      border-color: #0d6efd;
      transform: scale(1.05);
    }

    /* Empty State */
    .empty-state {
      padding: 3rem 2rem;
      text-align: center;
      color: #6c757d;
    }

    .empty-state i {
      font-size: 3rem;
      margin-bottom: 1rem;
      opacity: 0.5;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .search-form {
        flex-direction: column;
        gap: 0.75rem;
      }

      .search-box {
        max-width: 100%;
      }

      .card-header {
        padding: 1rem;
      }

      .table-clean thead th,
      .table-clean tbody td {
        padding: 0.75rem 0.5rem;
        font-size: 0.875rem;
      }

      .product-image {
        width: 50px !important;
        height: 50px !important;
      }
    }

    @media (max-width: 576px) {
      .search-container {
        margin: 0 1rem;
        padding: 1.5rem;
      }

      .stats-container {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 1rem;
      }
    }
  </style>
</head>

<body>
  <!-- Navbar tetap seperti asli -->
  <nav class="navbar navbar-expand-lg py-2 px-3 shadow-sm mb-5 mx-5 my-3 rounded-pill" style="background-color:#ffffff;">
    <div class="container-fluid">
      <div>
        <a class="navbar-brand fw-bold fs-4 text-black" href="index.php">Manajemen Produk</a>
      </div>
      <form method="GET" action="" class="search-form">
        <div class="d-flex align-items-center gap-2">
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
          <a href="index.php" class="btn-reset">
            Reset
          </a>
        </div>
      </form>
    </div>
  </nav>

  <!-- Main Content -->
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

    <!-- Enhanced Product Table Card - Simple & Clean -->
    <div class="main-card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-8">
            <div class="d-flex align-items-center flex-wrap gap-3 stats-container">
              <h4 class="mb-0 fw-bold text-dark">
                <i class="bi bi-grid-3x3-gap me-2 text-primary"></i>
                Daftar Produk
              </h4>
              <span class="stats-badge">
                <i class="bi bi-box me-1"></i>
                <?= $filteredCount ?><?= $searchTerm ? " dari $totalProduk" : "" ?> Produk
              </span>
              <?php if ($searchTerm): ?>
                <span class="badge bg-secondary">
                  <i class="bi bi-search me-1"></i>
                  "<?= htmlspecialchars($searchTerm) ?>"
                </span>
              <?php endif; ?>
            </div>
          </div>
          <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="tambah.php"
              class="btn btn-custom text-black position-relative overflow-hidden px-3 d-inline-flex align-items-center gap-2"
              style="background-color:#FFFFFF; text-decoration:none; width:auto;">
              <span class="default-state d-flex align-items-center gap-2">
                <span>Tambah Produk</span>
              </span>
              <span
                class="hover-state d-flex align-items-center gap-2 position-absolute top-0 start-0 w-100 h-100 justify-content-center">
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
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-clean align-middle">
            <thead>
              <tr>
                <th scope="col" class="text-center" style="width: 100px;">
                  <i class="bi bi-gear me-1"></i>Aksi
                </th>
                <th scope="col" style="width: 80px;">
                  <i class="bi bi-image me-1"></i>Gambar
                </th>
                <th scope="col" style="width: 120px;">
                  <i class="bi bi-upc me-1"></i>Kode
                </th>
                <th scope="col" style="width: 200px;">
                  <i class="bi bi-tag me-1"></i>Nama Produk
                </th>
                <th scope="col" style="width: 100px;">
                  <i class="bi bi-rulers me-1"></i>Satuan
                </th>
                <th scope="col" style="width: 120px;">
                  <i class="bi bi-currency-dollar me-1"></i>Harga
                </th>
              </tr>
            </thead>
            <tbody>
              <?php
              if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                  echo "<tr>
                        <td class='text-center'>
                          <a href='edit.php?id=" . $row['id'] . "' 
                             class='btn btn-edit d-inline-flex align-items-center'>
                            <i class='bi bi-pencil-square me-1'></i>
                            Edit
                          </a>
                        </td>
                        <td>
                          <img src='uploads/" . htmlspecialchars($row['gambar']) . "' 
                               width='60' height='60' 
                               class='product-image object-fit-cover'
                               alt='Gambar " . htmlspecialchars($row['nama']) . "'>
                        </td>
                        <td>
                          <span class='product-code'>" . htmlspecialchars($row['kode']) . "</span>
                        </td>
                        <td class='fw-medium text-dark'>" . htmlspecialchars($row['nama']) . "</td>
                        <td>
                          <span class='unit-badge'>" . htmlspecialchars($row['satuan']) . "</span>
                        </td>
                        <td>
                          <span class='price-badge'>
                            Rp " . number_format($row['harga'], 0, ',', '.') . "
                          </span>
                        </td>
                      </tr>";
                }
              } else {
                $emptyMessage = $searchTerm ?
                  "Tidak ada produk yang cocok dengan pencarian \"" . htmlspecialchars($searchTerm) . "\"" :
                  "Belum ada produk yang terdaftar";

                echo "<tr>
                      <td colspan='6' class='empty-state'>
                        <i class='bi bi-inbox'></i>
                        <h5 class='text-muted mb-2'>$emptyMessage</h5>
                        <p class='text-muted mb-3'>
                          " . ($searchTerm ? "Coba gunakan kata kunci yang berbeda" : "Mulai dengan menambah produk pertama Anda") . "
                        </p>
                        " . ($searchTerm ?
                  "<a href='index.php' class='btn btn-outline-primary'>Lihat Semua Produk</a>" :
                  "<a href='tambah.php' class='btn btn-add'>Tambah Produk Sekarang</a>"
                ) . "
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
</body>

</html>