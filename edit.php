<?php include "koneksi.php"; ?>
<?php
$error = "";

// Hapus produk
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $result = mysqli_query($koneksi, "SELECT gambar FROM produk WHERE id=$id");
    $row = mysqli_fetch_assoc($result);
    if ($row && !empty($row['gambar']) && file_exists("uploads/" . $row['gambar'])) {
        unlink("uploads/" . $row['gambar']);
    }
    mysqli_query($koneksi, "DELETE FROM produk WHERE id=$id");
    header("Location: index.php?msg=deleted");
    exit;
}

// Ambil data produk
$id = $_GET['id'];
$result = mysqli_query($koneksi, "SELECT * FROM produk WHERE id=$id");
$data = mysqli_fetch_assoc($result);

// Update produk
if (isset($_POST['update'])) {
    $kode   = trim($_POST['kode']);
    $nama   = trim($_POST['nama']);
    $satuan = trim($_POST['satuan']);
    $harga  = trim($_POST['harga']);

    // Validasi harga
    if ($harga < 1) {
        $error = "Harga tidak boleh kurang dari 1!";
    } elseif (strlen($harga) > 10) {
        $error = "Harga terlalu besar!";
    }

    // Cek kode unik (kecuali kalau kode sama dengan dirinya sendiri)
    $cekKode = mysqli_query($koneksi, "SELECT id FROM produk WHERE kode='$kode' AND id!=$id");
    if (mysqli_num_rows($cekKode) > 0) {
        $error = "Kode produk sudah ada!";
    }

    // Validasi gambar baru
    if ($_FILES['gambar']['name'] != "") {
        $gambar = $_FILES['gambar']['name'];
        $tmp    = $_FILES['gambar']['tmp_name'];
        $ukuran = $_FILES['gambar']['size'];
        $ext    = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif'];

        if (!in_array($ext, $allowed)) {
            $error = "Hanya file gambar (JPG, JPEG, PNG, GIF) yang diperbolehkan!";
        } elseif ($ukuran > 2 * 1024 * 1024) {
            $error = "Ukuran gambar maksimal 2MB!";
        }
    }

    if ($error == "") {
        if ($_FILES['gambar']['name'] != "") {
            if (!empty($data['gambar']) && file_exists("uploads/" . $data['gambar'])) {
                unlink("uploads/" . $data['gambar']);
            }
            move_uploaded_file($tmp, "uploads/" . $gambar);
            $query = "UPDATE produk SET kode='$kode', nama='$nama', satuan='$satuan', harga='$harga', gambar='$gambar' WHERE id=$id";
        } else {
            $query = "UPDATE produk SET kode='$kode', nama='$nama', satuan='$satuan', harga='$harga' WHERE id=$id";
        }
        mysqli_query($koneksi, $query);
        header("Location: index.php?msg=updated");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #ffffff;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .main-card {
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border: 1px solid #e3f2fd;
            background: #ffffff;
        }
        
        .form-control {
            border-radius: 8px;
            border: 1.5px solid #e3f2fd;
            padding: 10px 15px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #2196f3;
            box-shadow: 0 0 0 0.2rem rgba(33, 150, 243, 0.15);
        }
        
        .btn {
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .btn-primary {
            background-color: #2196f3;
            border-color: #2196f3;
        }
        
        .btn-primary:hover {
            background-color: #1976d2;
            border-color: #1976d2;
        }
        
        .btn-danger {
            background-color: #f44336;
            border-color: #f44336;
        }
        
        .btn-danger:hover {
            background-color: #d32f2f;
            border-color: #d32f2f;
        }
        
        .btn-secondary {
            background-color: #90a4ae;
            border-color: #90a4ae;
        }
        
        .btn-secondary:hover {
            background-color: #78909c;
            border-color: #78909c;
        }
        
        .page-title {
            color: #1565c0;
            font-weight: 600;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .header-section {
            background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%);
            margin: -2rem -2rem 2rem -2rem;
            padding: 2rem;
            border-radius: 12px 12px 0 0;
            color: white;
        }
        
        .header-section .page-title {
            color: white;
            margin-bottom: 0;
        }
        
        .header-section p {
            color: rgba(255,255,255,0.9);
            margin-bottom: 0;
        }
        
        .form-label {
            font-weight: 500;
            color: #1565c0;
            margin-bottom: 6px;
        }
        
        .img-preview {
            border-radius: 8px;
            border: 2px solid #e3f2fd;
            transition: all 0.3s ease;
        }
        
        .img-preview:hover {
            border-color: #2196f3;
        }
        
        .alert {
            border-radius: 8px;
            border: none;
        }
        
        .upload-area {
            border: 2px dashed #2196f3;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            background: #f3f9ff;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .upload-area:hover {
            background: #e8f4fd;
            border-color: #1976d2;
        }
        
        .icon {
            margin-right: 6px;
        }
        
        .input-group-text {
            background-color: #e3f2fd;
            border-color: #e3f2fd;
            color: #1565c0;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card main-card">
                    <div class="card-body p-4">
                        <div class="header-section">
                            <h2 class="page-title">
                                <i class="bi bi-pencil-square icon"></i>Edit Produk
                            </h2>
                            <p class="text-center small mb-0">Perbarui informasi produk dengan mudah</p>
                        </div>

                        <?php if ($error != ""): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle icon"></i>
                                <?= $error ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">
                                        <i class="bi bi-upc-scan icon"></i>Kode Produk
                                    </label>
                                    <input type="text" name="kode" class="form-control" 
                                           value="<?= htmlspecialchars($data['kode']) ?>" 
                                           placeholder="Masukkan kode produk" required>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">
                                        <i class="bi bi-tag icon"></i>Nama Produk
                                    </label>
                                    <input type="text" name="nama" class="form-control" 
                                           value="<?= htmlspecialchars($data['nama']) ?>" 
                                           placeholder="Masukkan nama produk" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">
                                        <i class="bi bi-rulers icon"></i>Satuan
                                    </label>
                                    <input type="text" name="satuan" class="form-control" 
                                           value="<?= htmlspecialchars($data['satuan']) ?>" 
                                           placeholder="Contoh: pcs, kg, liter" required>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">
                                        <i class="bi bi-currency-dollar icon"></i>Harga
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="border-radius: 12px 0 0 12px;">Rp</span>
                                        <input type="number" name="harga" class="form-control" 
                                               value="<?= $data['harga'] ?>" min="1" 
                                               placeholder="0" required 
                                               style="border-radius: 0 12px 12px 0;">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="bi bi-image icon"></i>Foto Saat Ini
                                </label>
                                <div class="text-center">
                                    <?php if (!empty($data['gambar'])): ?>
                                        <img src="uploads/<?= htmlspecialchars($data['gambar']) ?>" 
                                             class="img-preview mb-2" width="200" height="200" 
                                             style="object-fit: cover;" alt="Foto Produk">
                                        <p class="text-muted small">
                                            <i class="bi bi-file-image icon"></i>
                                            <?= htmlspecialchars($data['gambar']) ?>
                                        </p>
                                    <?php else: ?>
                                        <div class="p-4" style="background: #f8f9fa; border-radius: 8px;">
                                            <i class="bi bi-image" style="font-size: 3rem; color: #90a4ae;"></i>
                                            <p class="text-muted mt-2 mb-0">Belum ada gambar</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="bi bi-cloud-upload icon"></i>Ganti Foto Baru (Opsional)
                                </label>
                                                                <div class="upload-area" onclick="document.getElementById('gambar').click()">
                                    <i class="bi bi-cloud-upload" style="font-size: 2rem; color: #2196f3;"></i>
                                    <p class="mt-2 mb-1">Klik untuk memilih file</p>
                                    <small class="text-muted">JPG, JPEG, PNG, GIF • Maksimal 2MB</small>
                                </div>
                                <input type="file" name="gambar" id="gambar" class="d-none" 
                                       accept="image/*" onchange="previewImage(event)">
                                
                                <div id="previewContainer" class="mt-3 text-center" style="display:none;">
                                    <p class="text-success small mb-2">
                                        <i class="bi bi-check-circle icon"></i>Preview gambar baru:
                                    </p>
                                    <img id="preview" class="img-preview" width="200" height="200" 
                                         style="object-fit: cover;">
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="submit" name="update" class="btn btn-primary me-2">
                                    <i class="bi bi-check-lg icon"></i>Update Produk
                                </button>
                                <button type="button" class="btn btn-danger me-2" 
                                        onclick="confirmDelete(<?= $data['id'] ?>)">
                                    <i class="bi bi-trash3 icon"></i>Hapus Produk
                                </button>
                                <a href="index.php" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left icon"></i>Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 12px; border: none;">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-danger">
                        <i class="bi bi-exclamation-triangle icon"></i>Konfirmasi Hapus
                    </h5>
                </div>
                <div class="modal-body text-center">
                    <i class="bi bi-trash3 text-danger" style="font-size: 3rem;"></i>
                    <h6 class="mt-3">Yakin ingin menghapus produk ini?</h6>
                    <p class="text-muted">Tindakan ini tidak dapat dibatalkan!</p>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x icon"></i>Batal
                    </button>
                    <a href="#" id="confirmDeleteBtn" class="btn btn-danger">
                        <i class="bi bi-trash3 icon"></i>Ya, Hapus!
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview').src = e.target.result;
                    document.getElementById('previewContainer').style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        }

        function confirmDelete(id) {
            document.getElementById('confirmDeleteBtn').href = 'edit.php?delete=' + id;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }

        // Format ribuan untuk harga
        document.querySelector('input[name="harga"]').addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^\d]/g, '');
            e.target.value = value;
        });
    </script>
</body>
</html>