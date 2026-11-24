
<?php
include '../koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = mysqli_prepare($conn,
    "SELECT * FROM obat WHERE id_obat = ?");

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Data obat tidak ditemukan.");
}

$error = '';

if (isset($_POST['update'])) {

    $nama = trim($_POST['nama_obat']);
    $satuan = trim($_POST['satuan']);
    $stok = $_POST['stok'];

    if ($nama == '' || $satuan == '' || $stok === '') {
        $error = "Semua kolom wajib diisi.";
    } elseif (!ctype_digit($stok)) {
        $error = "Stok harus berupa angka positif atau nol.";
    } else {

        $stok = (int)$stok;

        $update = mysqli_prepare($conn,
            "UPDATE obat
             SET nama_obat = ?, satuan = ?, stok = ?
             WHERE id_obat = ?");

        mysqli_stmt_bind_param(
            $update, "ssii", $nama, $satuan, $stok, $id
        );

        if (mysqli_stmt_execute($update)) {
            header("Location: index.php?pesan=edit");
            exit;
        } else {
            $error = "Gagal memperbarui data obat.";
        }
    }

    $data['nama_obat'] = $nama;
    $data['satuan'] = $satuan;
    $data['stok'] = $stok;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Obat | MedCare</title>
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
        <a href="../dokter/index.php" class="nav-link">⚕ Data Dokter</a>
        <a href="index.php" class="nav-link active">✚ Data Obat</a>

        <p class="menu-title">PEMERIKSAAN</p>
        <a href="../rekam_medis/index.php" class="nav-link">▤ Rekam Medis</a>
    </aside>

    <main class="main-content">

        <div class="topbar">
            <div>
                <h1>Edit Obat</h1>
                <p>Perbarui informasi dan stok obat.</p>
            </div>
            <span>✚ MedCare</span>
        </div>

        <div class="page-title">
            <h2>Form Edit Obat</h2>
            <p>Ubah informasi obat sesuai kebutuhan.</p>
        </div>

        <div class="form-card">

            <?php if ($error != ''): ?>
                <div class="alert"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST">

                <div class="form-group">
                    <label>Nama Obat</label>
                    <input type="text" name="nama_obat"
                           maxlength="100" required
                           value="<?= htmlspecialchars($data['nama_obat']) ?>">
                </div>

                <div class="form-group">
                    <label>Satuan Obat</label>
                    <input type="text" name="satuan"
                           maxlength="30" required
                           value="<?= htmlspecialchars($data['satuan']) ?>">
                </div>

                <div class="form-group">
                    <label>Stok Obat</label>
                    <input type="number" name="stok"
                           min="0" required
                           value="<?= htmlspecialchars((string)$data['stok']) ?>">
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
