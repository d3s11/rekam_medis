
<?php
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

if ($id <= 0) {
    header("Location: index.php");
    exit;
}

$stmt = mysqli_prepare($conn, "DELETE FROM pasien WHERE id_pasien = ?");
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        header("Location: index.php?pesan=hapus");
    } else {
        header("Location: index.php");
    }
    exit;
}

if (mysqli_errno($conn) == 1451) {
    die("Pasien tidak dapat dihapus karena sudah memiliki riwayat pemeriksaan.");
}

die("Gagal menghapus data pasien.");
?>
