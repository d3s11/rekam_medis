
<?php
include '../koneksi.php';

$error = '';

if (isset($_POST['simpan'])) {

    $no_rm = trim($_POST['no_rm']);
    $nama = trim($_POST['nama_pasien']);
    $jk = $_POST['jenis_kelamin'];
    $tanggal = $_POST['tanggal_lahir'];
    $alamat = trim($_POST['alamat']);

    if ($no_rm == '' || $nama == '' || $tanggal == '') {
        $error = "Nomor rekam medis, nama, dan tanggal lahir wajib diisi.";
    } elseif (!in_array($jk, ['L', 'P'], true)) {
        $error = "Jenis kelamin tidak valid.";
    } else {
        $cek = mysqli_prepare($conn, "SELECT id_pasien FROM pasien WHERE no_rm = ?");
        mysqli_stmt_bind_param($cek, "s", $no_rm);
        mysqli_stmt_execute($cek);
        $hasil = mysqli_stmt_get_result($cek);

        if (mysqli_num_rows($hasil) > 0) {
            $error = "Nomor rekam medis sudah digunakan.";
        } else {
            $stmt = mysqli_prepare($conn,
                "INSERT INTO pasien
                (no_rm, nama_pasien, jenis_kelamin, tanggal_lahir, alamat)
                VALUES (?, ?, ?, ?, ?)");

            mysqli_stmt_bind_param(
                $stmt, "sssss",
                $no_rm, $nama, $jk, $tanggal, $alamat
            );

            if (mysqli_stmt_execute($stmt)) {
                header("Location: index.php?pesan=tambah");
                exit;
            } else {
                $error = "Gagal menyimpan data pasien.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pasien | MedCare</title>
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
        <a href="index.php" class="nav-link active">♙ Data Pasien</a>
        <a href="../dokter/index.php" class="nav-link">⚕ Data Dokter</a>
        <a href="../obat/index.php" class="nav-link">✚ Data Obat</a>

        <p class="menu-title">PEMERIKSAAN</p>
        <a href="../rekam_medis/index.php" class="nav-link">▤ Rekam Medis</a>
    </aside>

    <main class="main-content">

        <div class="topbar">
            <div>
                <h1>Tambah Pasien</h1>
                <p>Masukkan informasi pasien baru.</p>
            </div>
            <span>✚ MedCare</span>
        </div>

        <div class="page-title">
            <h2>Form Tambah Pasien</h2>
            <p>Lengkapi formulir berikut dengan data pasien.</p>
        </div>

        <div class="form-card">

            <?php if ($error != ''): ?>
                <div class="alert"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST">

                <div class="form-group">
                    <label>Nomor Rekam Medis</label>
                    <input type="text" name="no_rm"
                           placeholder="Contoh: RM004"
                           maxlength="20" required
                           value="<?= htmlspecialchars($_POST['no_rm'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label>Nama Pasien</label>
                    <input type="text" name="nama_pasien"
                           placeholder="Masukkan nama pasien"
                           maxlength="100" required
                           value="<?= htmlspecialchars($_POST['nama_pasien'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" required>
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" required
                           value="<?= htmlspecialchars($_POST['tanggal_lahir'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label>Alamat</label>
                    <textarea name="alamat"
                              placeholder="Masukkan alamat pasien"><?= htmlspecialchars($_POST['alamat'] ?? '') ?></textarea>
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
