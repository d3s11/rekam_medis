
<?php
include '../koneksi.php';

$error = '';

if (isset($_POST['simpan'])) {

    $nama = trim($_POST['nama_dokter']);
    $spesialisasi = trim($_POST['spesialisasi']);

    if ($nama == '' || $spesialisasi == '') {
        $error = "Semua kolom wajib diisi.";
    } else {

        $stmt = mysqli_prepare($conn,
            "INSERT INTO dokter (nama_dokter, spesialisasi)
             VALUES (?, ?)");

        mysqli_stmt_bind_param($stmt, "ss", $nama, $spesialisasi);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: index.php?pesan=tambah");
            exit;
        } else {
            $error = "Gagal menyimpan data dokter.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Dokter | MedCare</title>
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
                <h1>Tambah Dokter</h1>
                <p>Masukkan informasi dokter baru.</p>
            </div>
            <span>✚ MedCare</span>
        </div>

        <div class="page-title">
            <h2>Form Tambah Dokter</h2>
            <p>Lengkapi formulir berikut dengan data dokter.</p>
        </div>

        <div class="form-card">

            <?php if ($error != ''): ?>
                <div class="alert"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST">

                <div class="form-group">
                    <label>Nama Dokter</label>
                    <input type="text" name="nama_dokter"
                           placeholder="Contoh: Dr. Budi Santoso"
                           maxlength="100" required
                           value="<?= htmlspecialchars($_POST['nama_dokter'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label>Spesialisasi</label>
                    <input type="text" name="spesialisasi"
                           placeholder="Contoh: Dokter Umum"
                           maxlength="50" required
                           value="<?= htmlspecialchars($_POST['spesialisasi'] ?? '') ?>">
                </div>

                <button type="submit" name="simpan" class="btn">
                    Simpan Data
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