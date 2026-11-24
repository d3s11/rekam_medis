
<?php
include '../koneksi.php';

$error = '';

if (isset($_POST['simpan'])) {

    $nama = trim($_POST['nama_obat']);
    $satuan = trim($_POST['satuan']);
    $stok = $_POST['stok'];

    if ($nama == '' || $satuan == '' || $stok === '') {
        $error = "Semua kolom wajib diisi.";
    } elseif (!ctype_digit($stok)) {
        $error = "Stok harus berupa angka positif atau nol.";
    } else {

        $stok = (int)$stok;

        $stmt = mysqli_prepare($conn,
            "INSERT INTO obat (nama_obat, satuan, stok)
             VALUES (?, ?, ?)");

        mysqli_stmt_bind_param($stmt, "ssi", $nama, $satuan, $stok);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: index.php?pesan=tambah");
            exit;
        } else {
            $error = "Gagal menyimpan data obat.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Obat | MedCare</title>
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
                <h1>Tambah Obat</h1>
                <p>Masukkan informasi obat baru.</p>
            </div>
            <span>✚ MedCare</span>
        </div>

        <div class="page-title">
            <h2>Form Tambah Obat</h2>
            <p>Lengkapi formulir berikut dengan data obat.</p>
        </div>

        <div class="form-card">

            <?php if ($error != ''): ?>
                <div class="alert"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST">

                <div class="form-group">
                    <label>Nama Obat</label>
                    <input type="text" name="nama_obat"
                           placeholder="Contoh: Paracetamol"
                           maxlength="100" required
                           value="<?= htmlspecialchars($_POST['nama_obat'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label>Satuan Obat</label>
                    <input type="text" name="satuan"
                           placeholder="Contoh: Tablet, Kapsul, Sirup"
                           maxlength="30" required
                           value="<?= htmlspecialchars($_POST['satuan'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label>Stok Obat</label>
                    <input type="number" name="stok"
                           placeholder="Masukkan jumlah stok"
                           min="0" required
                           value="<?= htmlspecialchars($_POST['stok'] ?? '') ?>">
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