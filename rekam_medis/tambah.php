
<?php
include '../koneksi.php';

$pasien = mysqli_query($conn, "SELECT id_pasien, no_rm, nama_pasien FROM pasien ORDER BY nama_pasien ASC");
$dokter = mysqli_query($conn, "SELECT id_dokter, nama_dokter FROM dokter ORDER BY nama_dokter ASC");
$obat = mysqli_query($conn, "SELECT id_obat, nama_obat FROM obat ORDER BY nama_obat ASC");

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pasien = $_POST['id_pasien'];
    $id_dokter = $_POST['id_dokter'];
    $id_obat = $_POST['id_obat'];
    $tanggal_periksa = $_POST['tanggal_periksa'];
    $keluhan = trim($_POST['keluhan']);
    $diagnosa = trim($_POST['diagnosa']);
    $tindakan = trim($_POST['tindakan']);
    $resep = trim($_POST['resep']);

    if (
        empty($id_pasien) || empty($id_dokter) || empty($id_obat) ||
        empty($tanggal_periksa) || $keluhan == '' ||
        $diagnosa == '' || $tindakan == '' || $resep == ''
    ) {
        $error = 'Semua data wajib diisi.';
    } else {
        $stmt = mysqli_prepare($conn, "
            INSERT INTO rekam_medis
            (id_pasien, id_dokter, id_obat, tanggal_periksa, keluhan, diagnosa, tindakan, resep)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        mysqli_stmt_bind_param(
            $stmt,
            "iiisssss",
            $id_pasien,
            $id_dokter,
            $id_obat,
            $tanggal_periksa,
            $keluhan,
            $diagnosa,
            $tindakan,
            $resep
        );

        if (mysqli_stmt_execute($stmt)) {
            header("Location: index.php?pesan=tambah");
            exit;
        } else {
            $error = 'Data gagal disimpan. Periksa kembali data yang dimasukkan.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Rekam Medis | MedCare</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="layout">
    <aside class="sidebar">
        <div class="brand">MedCare</div>
        <nav>
            <a href="../index.php" class="nav-link">Dashboard</a>
            <a href="../pasien/" class="nav-link">Data Pasien</a>
            <a href="../dokter/" class="nav-link">Data Dokter</a>
            <a href="../obat/" class="nav-link">Data Obat</a>
            <a href="index.php" class="nav-link active">Rekam Medis</a>
        </nav>
    </aside>

    <main class="main-content">
        <div class="topbar">
            <div>
                <p class="page-subtitle">MEDCARE / REKAM MEDIS</p>
                <h1 class="page-title">Tambah Rekam Medis</h1>
                <p>Masukkan informasi pemeriksaan pasien.</p>
            </div>
        </div>

        <section class="form-card">
            <h2>Formulir Pemeriksaan</h2>
            <p>Lengkapi data pemeriksaan berikut.</p>

            <?php if ($error != ''): ?>
                <div class="alert"><?= htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Nama Pasien</label>
                    <select name="id_pasien" required>
                        <option value="">-- Pilih Pasien --</option>
                        <?php while ($p = mysqli_fetch_assoc($pasien)): ?>
                            <option value="<?= $p['id_pasien']; ?>"
                                <?= (($_POST['id_pasien'] ?? '') == $p['id_pasien']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($p['no_rm'] . ' - ' . $p['nama_pasien']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Dokter Pemeriksa</label>
                    <select name="id_dokter" required>
                        <option value="">-- Pilih Dokter --</option>
                        <?php while ($d = mysqli_fetch_assoc($dokter)): ?>
                            <option value="<?= $d['id_dokter']; ?>"
                                <?= (($_POST['id_dokter'] ?? '') == $d['id_dokter']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($d['nama_dokter']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Obat</label>
                    <select name="id_obat" required>
                        <option value="">-- Pilih Obat --</option>
                        <?php while ($o = mysqli_fetch_assoc($obat)): ?>
                            <option value="<?= $o['id_obat']; ?>"
                                <?= (($_POST['id_obat'] ?? '') == $o['id_obat']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($o['nama_obat']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Tanggal Pemeriksaan</label>
                    <input type="date" name="tanggal_periksa"
                        value="<?= htmlspecialchars($_POST['tanggal_periksa'] ?? date('Y-m-d')); ?>"
                        required>
                </div>

                <div class="form-group">
                    <label>Keluhan Pasien</label>
                    <textarea name="keluhan" rows="3" required><?= htmlspecialchars($_POST['keluhan'] ?? ''); ?></textarea>
                </div>

                <div class="form-group">
                    <label>Diagnosa</label>
                    <textarea name="diagnosa" rows="3" required><?= htmlspecialchars($_POST['diagnosa'] ?? ''); ?></textarea>
                </div>

                <div class="form-group">
                    <label>Tindakan</label>
                    <textarea name="tindakan" rows="3" required><?= htmlspecialchars($_POST['tindakan'] ?? ''); ?></textarea>
                </div>

                <div class="form-group">
                    <label>Resep</label>
                    <textarea name="resep" rows="3" required><?= htmlspecialchars($_POST['resep'] ?? ''); ?></textarea>
                </div>

                <div class="actions">
                    <button type="submit" class="btn">Simpan Data</button>
                    <a href="index.php" class="btn-secondary">Kembali</a>
                </div>
            </form>
        </section>
    </main>
</div>

</body>
</html>
