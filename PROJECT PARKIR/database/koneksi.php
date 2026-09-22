<?php

$host       = "localhost";
$user       = "root";
$password   = "";
$database   = "db_parkiran";

$koneksi = mysqli_connect($host, $user, $password, $database);

 if (!$koneksi) {
    die("koneksi ke database gagal: " . mysqli_connect());
 }
 //echo "Koneksi berhasil!";
 ?>