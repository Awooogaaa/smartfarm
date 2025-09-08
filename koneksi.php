<?php
$koneksi = mysqli_connect("localhost", "root", "", "SmartFarm");
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
