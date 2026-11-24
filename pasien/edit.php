
<?php
include '../koneksi.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$stmt = mysqli_prepare($conn, "SELECT * FROM pasien WHERE id_pasien = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Data pasien tidak ditemukan.");
}

$error = '';

if (isset($_POST['update'])) {

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

        $cek = mysqli_prepare($conn,
            "SELECT id_pasien FROM pasien
             WHERE no_rm = ? AND id_pasien != ?");
        mysqli_stmt_bind_param($cek, "si", $no_rm, $id);
        mysqli_stmt_execute($cek);
        $hasil = mysqli_stmt_get_result($cek);

        if (mysqli_num_rows($hasil) > 0) {
            $error = "Nomor rekam medis sudah digunakan.";
        } else {

            $update = mysqli_prepare($conn,
                "UPDATE pasien SET
                 no_rm = ?, nama_pasien = ?, jenis_kelamin = ?,
                 tanggal_lahir = ?, alamat = ?
                 WHERE id_pasien = ?");

            mysqli_stmt_bind_param(
                $update, "sssssi",
                $no_rm, $nama, $jk, $tanggal, $alamat, $id
            );

            if (mysqli_stmt_execute($update)) {
                header("Location: index.php?pesan=edit");
                exit;
            } else {
                $error = "Gagal memperbarui data pasien.";
            }
        }
    }

    $data['no_rm'] = $no_rm;
    $data['nama_pasien'] = $nama;
    $data['jenis_kelamin'] = $jk;
    $data['tanggal_lahir'] = $tanggal;
    $data['alamat'] = $alamat;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pasien | MedCare</title>
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
                <h1>Edit Pasien</h1>
                <p>Perbarui informasi data pasien.</p>
            </div>
            <span>✚ MedCare</span>
        </div>

        <div class="page-title">
            <h2>Form Edit Pasien</h2>
            <p>Ubah informasi pasien sesuai kebutuhan.</p>
        </div>

        <div class="form-card">

            <?php if ($error != ''): ?>
                <div class="alert"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST">

                <div class="form-group">
                    <label>Nomor Rekam Medis</label>
                    <input type="text" name="no_rm"
                           value="<?= htmlspecialchars($data['no_rm']) ?>"
                           maxlength="20" required>
                </div>

                <div class="form-group">
                    <label>Nama Pasien</label>
                    <input type="text" name="nama_pasien"
                           value="<?= htmlspecialchars($data['nama_pasien']) ?>"
                           maxlength="100" required>
                </div>

                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" required>
                        <option value="L" <?= $data['jenis_kelamin'] == 'L' ? 'selected' : '' ?>>
                            Laki-laki
                        </option>
                        <option value="P" <?= $data['jenis_kelamin'] == 'P' ? 'selected' : '' ?>>
                            Perempuan
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir"
                           value="<?= htmlspecialchars($data['tanggal_lahir']) ?>"
                           required>
                </div>

                <div class="form-group">
                    <label>Alamat</label>
                    <textarea name="alamat"><?= htmlspecialchars($data['alamat'] ?? '') ?></textarea>
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
