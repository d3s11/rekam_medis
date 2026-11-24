
<?php
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id']) || !ctype_digit($_POST['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int) $_POST['id'];

$stmt = mysqli_prepare($conn, "DELETE FROM rekam_medis WHERE id_rekam = ?");
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    header("Location: index.php?pesan=hapus");
    exit;
}

echo "Data rekam medis gagal dihapus.";
echo '<br><a href="index.php">Kembali</a>';