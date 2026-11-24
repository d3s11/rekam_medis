<?php
$host = "localhost";   
$user = "desinr";        
$pass = "smkn3tbn";            
$nama_db = "db_rekam_medis"; 
$conn = mysqli_connect($host, $user, $pass, $nama_db);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");
?>