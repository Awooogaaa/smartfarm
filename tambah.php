<?php include "koneksi.php"; ?>
<?php
$error = "";
if (isset($_POST['simpan'])) {
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

    // Cek kode unik
    $cekKode = mysqli_query($koneksi, "SELECT id FROM produk WHERE kode='$kode'");
    if (mysqli_num_rows($cekKode) > 0) {
        $error = "Kode produk sudah ada, gunakan kode lain!";
    }

    // Validasi gambar
    $gambar = $_FILES['gambar']['name'];
    $tmp    = $_FILES['gambar']['tmp_name'];
    $ukuran = $_FILES['gambar']['size'];
    $ext    = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','gif'];

    if ($gambar != "") {
        if (!in_array($ext, $allowed)) {
            $error = "Hanya file gambar (JPG, JPEG, PNG, GIF) yang diperbolehkan!";
        } elseif ($ukuran > 2 * 1024 * 1024) { // 2MB
            $error = "Ukuran gambar maksimal 2MB!";
        }
    }

    // Kalau tidak ada error → simpan
    if ($error == "") {
        if ($gambar != "") {
            move_uploaded_file($tmp, "uploads/" . $gambar);
        }
        mysqli_query($koneksi, "INSERT INTO produk (kode, nama, satuan, harga, gambar)
                                VALUES ('$kode', '$nama', '$satuan', '$harga', '$gambar')");
        header("Location: index.php?msg=success");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="mb-4">Tambah Produk</h2>

        <?php if ($error != ""): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Kode Produk</label>
                <input type="text" name="kode" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Nama Produk</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Satuan</label>
                <input type="text" name="satuan" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Harga</label>
                <input type="number" name="harga" class="form-control" min="1" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Gambar Produk</label>
                <input type="file" name="gambar" class="form-control" onchange="previewImage(event)">
                <img id="preview" class="img-thumbnail mt-2" width="150" style="display:none;">
                <div class="form-text">Hanya JPG, JPEG, PNG, GIF. Maks 2MB.</div>
            </div>
            <button type="submit" name="simpan" class="btn btn-success">💾 Simpan</button>
            <a href="index.php" class="btn btn-secondary">❌ Batal</a>
        </form>
    </div>
</div>
<script>
function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function(){
        var output = document.getElementById('preview');
        output.src = reader.result;
        output.style.display = 'block';
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>
</body>
</html>
