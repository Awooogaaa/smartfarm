<?php include "koneksi.php"; ?>
<?php
$error = "";
if (isset($_POST['simpan'])) {
    $kode   = trim($_POST['kode']);
    $nama   = trim($_POST['nama']);
    $satuan = trim($_POST['satuan']);
    $harga  = trim($_POST['harga']);

    // --- VALIDASI BARU: Cek panjang Kode dan Nama ---
    if (strlen($kode) > 20) {
        $error = "Kode produk terlalu panjang! Maksimal 20 karakter.";
    } elseif (strlen($nama) > 100) {
        $error = "Nama produk terlalu panjang! Maksimal 100 karakter.";
    }
    // --- AKHIR VALIDASI BARU ---

    // Validasi harga
    if ($error == "" && $harga < 1) {
        $error = "Harga tidak boleh kurang dari 1!";
    } elseif ($error == "" && strlen($harga) > 10) {
        $error = "Harga terlalu besar!";
    }

    // Cek kode unik
    if ($error == "") {
        $cekKode = mysqli_query($koneksi, "SELECT id FROM produk WHERE kode='$kode'");
        if (mysqli_num_rows($cekKode) > 0) {
            $error = "Kode produk sudah ada, gunakan kode lain!";
        }
    }

    // Validasi gambar
    $gambar = $_FILES['gambar']['name'];
    if ($error == "" && $gambar != "") {
        $tmp    = $_FILES['gambar']['tmp_name'];
        $ukuran = $_FILES['gambar']['size'];
        $ext    = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif'];

        if (!in_array($ext, $allowed)) {
            $error = "Hanya file gambar (JPG, JPEG, PNG, GIF) yang diperbolehkan!";
        } elseif ($ukuran > 2 * 1024 * 1024) { // 2MB
            $error = "Ukuran gambar maksimal 2MB!";
        }
    }

    // Kalau tidak ada error → simpan
    if ($error == "") {
        if ($gambar != "") {
            // Membuat nama file unik untuk menghindari duplikasi
            $gambar_baru = uniqid() . '-' . $gambar;
            move_uploaded_file($tmp, "uploads/" . $gambar_baru);
        } else {
            $gambar_baru = ""; // Jika tidak ada gambar
        }
        
        mysqli_query($koneksi, "INSERT INTO produk (kode, nama, satuan, harga, gambar)
                                VALUES ('$kode', '$nama', '$satuan', '$harga', '$gambar_baru')");
        
        header("Location: index.php?msg=success");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk</title>
    <link href="modul/node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
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
            font-weight: 600;
            text-align: center;
        }
        
        .header-section p {
            color: rgba(255,255,255,0.9);
            margin-bottom: 0;
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
        
        .btn-success {
            background-color: #4caf50;
            border-color: #4caf50;
        }
        
        .btn-success:hover {
            background-color: #388e3c;
            border-color: #388e3c;
        }
        
        .btn-secondary {
            background-color: #90a4ae;
            border-color: #90a4ae;
        }
        
        .btn-secondary:hover {
            background-color: #78909c;
            border-color: #78909c;
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
        
        .preview-container {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            border: 1px solid #e3f2fd;
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
                                <i class="bi bi-plus-circle icon"></i>Tambah Produk Baru
                            </h2>
                            <p class="text-center small mb-0">Masukkan informasi produk dengan lengkap</p>
                        </div>

                        <?php if ($error != ""): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle icon"></i>
                                <?= $error ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form action="" method="POST" enctype="multipart/form-data" id="productForm">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">
                                        <i class="bi bi-upc-scan icon"></i>Kode Produk
                                    </label>
                                    <input type="text" 
                                           name="kode" 
                                           class="form-control" 
                                           placeholder="Maksimal 20 karakter"
                                           value="<?= isset($_POST['kode']) ? htmlspecialchars($_POST['kode']) : '' ?>"
                                           required>
                                    <div class="form-text">Kode harus unik untuk setiap produk</div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">
                                        <i class="bi bi-tag icon"></i>Nama Produk
                                    </label>
                                    <input type="text" 
                                           name="nama" 
                                           class="form-control" 
                                           placeholder="Maksimal 100 karakter"
                                           value="<?= isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : '' ?>"
                                           required>
                                </div>
                            </div>
                            
                            <div class="row">
                               <div class="col-md-6 mb-4">
                                    <label class="form-label">
                                        <i class="bi bi-rulers icon"></i>Satuan
                                    </label>
                                    <select name="satuan" class="form-control" required>
                                        <option value="">-- Pilih Satuan --</option>
                                        <option value="pcs" <?= (isset($_POST['satuan']) && $_POST['satuan']=="pcs") ? "selected" : "" ?>>Pcs</option>
                                        <option value="kg" <?= (isset($_POST['satuan']) && $_POST['satuan']=="kg") ? "selected" : "" ?>>Kilogram (Kg)</option>
                                        <option value="liter" <?= (isset($_POST['satuan']) && $_POST['satuan']=="liter") ? "selected" : "" ?>>Liter</option>
                                        <option value="box" <?= (isset($_POST['satuan']) && $_POST['satuan']=="box") ? "selected" : "" ?>>Box</option>
                                    </select>
                                    <div class="form-text">Pilih satuan produk</div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">
                                        <i class="bi bi-currency-dollar icon"></i>Harga
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="border-radius: 8px 0 0 8px;">Rp</span>
                                        <input type="number" 
                                               name="harga" 
                                               class="form-control" 
                                               min="1" 
                                               placeholder="0"
                                               value="<?= isset($_POST['harga']) ? $_POST['harga'] : '' ?>"
                                               required 
                                               style="border-radius: 0 8px 8px 0;">
                                    </div>
                                    <div class="form-text">Harga minimal Rp 1, maksimal 10 digit</div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="bi bi-cloud-upload icon"></i>Gambar Produk (Opsional)
                                </label>
                                <div class="upload-area" onclick="document.getElementById('gambar').click()">
                                    <i class="bi bi-cloud-upload" style="font-size: 2.5rem; color: #2196f3;"></i>
                                    <p class="mt-2 mb-1">Klik untuk memilih gambar produk</p>
                                    <small class="text-muted">JPG, JPEG, PNG, GIF • Maksimal 2MB</small>
                                </div>
                                <input type="file" 
                                       name="gambar" 
                                       id="gambar"
                                       class="d-none" 
                                       accept="image/*"
                                       onchange="previewImage(event)">
                                
                                <div id="previewContainer" class="preview-container mt-3" style="display:none;">
                                    <div class="text-center">
                                        <p class="text-success small mb-2">
                                            <i class="bi bi-check-circle icon"></i>Preview gambar yang akan diupload:
                                        </p>
                                        <img id="preview" 
                                             class="img-preview" 
                                             width="200" 
                                             height="200" 
                                             style="object-fit: cover;">
                                        <div class="mt-2">
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    onclick="removePreview()">
                                                <i class="bi bi-x-circle"></i> Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="submit" name="simpan" class="btn btn-success me-2">
                                    <i class="bi bi-check-lg icon"></i>Simpan Produk
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // SCRIPT BAWAAN ANDA TETAP SAMA
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar! Maksimal 2MB.');
                    event.target.value = '';
                    return;
                }
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Format file tidak didukung! Gunakan JPG, JPEG, PNG, atau GIF.');
                    event.target.value = '';
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview').src = e.target.result;
                    document.getElementById('previewContainer').style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        }
        function removePreview() {
            document.getElementById('gambar').value = '';
            document.getElementById('previewContainer').style.display = 'none';
        }
        document.querySelector('input[name="harga"]').addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^\d]/g, '');
            if (value.length > 10) {
                value = value.substring(0, 10);
            }
            e.target.value = value;
        });
        document.getElementById('productForm').addEventListener('submit', function(e) {
            const kode = document.querySelector('input[name="kode"]').value.trim();
            const nama = document.querySelector('input[name="nama"]').value.trim();
            const satuan = document.querySelector('select[name="satuan"]').value.trim();
            const harga = document.querySelector('input[name="harga"]').value;
            if (!kode || !nama || !satuan || !harga || harga < 1) {
                e.preventDefault();
                alert('Mohon isi semua field dengan benar!');
                return false;
            }
        });
        const dropArea = document.querySelector('.upload-area');
        const fileInput = document.getElementById('gambar');
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
        });
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, highlight, false);
        });
        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, unhighlight, false);
        });
        function highlight(e) {
            dropArea.style.borderColor = '#1976d2';
            dropArea.style.background = '#e8f4fd';
        }
        function unhighlight(e) {
            dropArea.style.borderColor = '#2196f3';
            dropArea.style.background = '#f3f9ff';
        }
        dropArea.addEventListener('drop', handleDrop, false);
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0) {
                fileInput.files = files;
                previewImage({target: {files: files}});
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('input[name="kode"]').focus();
        });
    </script>
</body>
</html>