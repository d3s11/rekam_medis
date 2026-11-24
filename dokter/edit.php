
<?php
include '../koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = mysqli_prepare($conn,
    "SELECT * FROM dokter WHERE id_dokter = ?");

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Data dokter tidak ditemukan.");
}

$error = '';

if (isset($_POST['update'])) {

    $nama = trim($_POST['nama_dokter']);
    $spesialisasi = trim($_POST['spesialisasi']);

    if ($nama == '' || $spesialisasi == '') {
        $error = "Semua kolom wajib diisi.";
    } else {

        $update = mysqli_prepare($conn,
            "UPDATE dokter
             SET nama_dokter = ?, spesialisasi = ?
             WHERE id_dokter = ?");

        mysqli_stmt_bind_param(
            $update, "ssi", $nama, $spesialisasi, $id
        );

        if (mysqli_stmt_execute($update)) {
            header("Location: index.php?pesan=edit");
            exit;
        } else {
            $error = "Gagal memperbarui data dokter.";
        }
    }

    $data['nama_dokter'] = $nama;
    $data['spesialisasi'] = $spesialisasi;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Dokter | MedCare</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="layout">

    <aside class="sidebar">
        <div class="brand">
            <div class="brand-icon">✚</div>
            <div><h2>MedCare</h2><small>REKAM MEDIS</small></div>
        </div>

        <p class="menu-title">MENU UTAMA</p>
        <a href="../index.php" class="nav-link">⌂ Dashboard</a>
        <a href="../pasien/index.php" class="nav-link">♙ Data Pasien</a>
        <a href="index.php" class="nav-link active">⚕ Data Dokter</a>
        <a href="../obat/index.php" class="nav-link">✚ Data Obat</a>

        <p class="menu-title">PEMERIKSAAN</p>
        <a href="../rekam_medis/index.php" class="nav-link">▤ Rekam Medis</a>
    </aside>

    <main class="main-content">

        <div class="topbar">
            <div>
                <h1>Edit Dokter</h1>
                <p>Perbarui informasi data dokter.</p>
            </div>
            <span>✚ MedCare</span>
        </div>

        <div class="page-title">
            <h2>Form Edit Dokter</h2>
            <p>Ubah informasi dokter sesuai kebutuhan.</p>
        </div>

        <div class="form-card">

            <?php if ($error != ''): ?>
                <div class="alert"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST">

                <div class="form-group">
                    <label>Nama Dokter</label>
                    <input type="text" name="nama_dokter"
                           maxlength="100" required
                           value="<?= htmlspecialchars($data['nama_dokter']) ?>">
                </div>

                <div class="form-group">
                    <label>Spesialisasi</label>
                    <input type="text" name="spesialisasi"
                           maxlength="50" required
                           value="<?= htmlspecialchars($data['spesialisasi']) ?>">
                </div>

                <button type="submit" name="update" class="btn">
                    Update Data
                </button>

                <a href="index.php" class="btn btn-secondary">
                    Kembali
                </a>

            </form>
        </div>

    </main>
</div>

</body>
</html>