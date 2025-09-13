<?php
$koneksi = mysqli_connect("localhost", "root", "", "smartfarm");
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
