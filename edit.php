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
    <title>Edit Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="mb-4">Edit Produk</h2>

        <?php if ($error != ""): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Kode Produk</label>
                <input type="text" name="kode" class="form-control" value="<?= $data['kode'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Nama Produk</label>
                <input type="text" name="nama" class="form-control" value="<?= $data['nama'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Satuan</label>
                <input type="text" name="satuan" class="form-control" value="<?= $data['satuan'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Harga</label>
                <input type="number" name="harga" class="form-control" value="<?= $data['harga'] ?>" min="1" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Foto Lama</label><br>
                <?php if (!empty($data['gambar'])): ?>
                    <img src="uploads/<?= $data['gambar'] ?>" class="img-thumbnail mb-2" width="150">
                <?php else: ?>
                    <p class="text-muted">Belum ada gambar</p>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Ganti Foto Baru</label>
                <input type="file" name="gambar" class="form-control" onchange="previewImage(event)">
                <img id="preview" class="img-thumbnail mt-2" width="150" style="display:none;">
                <div class="form-text">Hanya JPG, JPEG, PNG, GIF. Maks 2MB.</div>
            </div>
            <button type="submit" name="update" class="btn btn-primary">🔄 Update</button>
            <a href="edit.php?delete=<?= $data['id'] ?>" class="btn btn-danger" onclick="return confirm('Yakin hapus produk ini?')">🗑 Hapus</a>
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
