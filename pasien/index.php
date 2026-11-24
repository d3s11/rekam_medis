
<?php
include '../koneksi.php';

$query = mysqli_query($conn, "SELECT * FROM pasien ORDER BY id_pasien DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pasien | MedCare</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="layout">
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-icon">✚</div>
            <div>
                <h2>MedCare</h2>
                <small>REKAM MEDIS</small>
            </div>
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
                <h1>Data Pasien</h1>
                <p>Kelola informasi dan data pasien.</p>
            </div>
            <span>✚ MedCare</span>
        </div>

        <div class="page-title">
            <h2>Daftar Pasien</h2>
            <p>Seluruh data pasien yang tersimpan dalam sistem.</p>
        </div>

        <?php if (isset($_GET['pesan'])): ?>
            <div class="alert">
                <?php
                if ($_GET['pesan'] == 'tambah') echo "Data pasien berhasil ditambahkan!";
                if ($_GET['pesan'] == 'edit') echo "Data pasien berhasil diperbarui!";
                if ($_GET['pesan'] == 'hapus') echo "Data pasien berhasil dihapus!";
                ?>
            </div>
        <?php endif; ?>

        <div class="content-card">

            <div style="display:flex; justify-content:space-between;
                        align-items:center; margin-bottom:22px; gap:10px;">
                <div>
                    <h3>Data Pasien</h3>
                    <p style="color:#758780; font-size:12px; margin-top:5px;">
                        Total pasien: <?= mysqli_num_rows($query) ?> data
                    </p>
                </div>

                <a href="tambah.php" class="btn">+ Tambah Pasien</a>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>No. Rekam Medis</th>
                            <th>Nama Pasien</th>
                            <th>Jenis Kelamin</th>
                            <th>Tanggal Lahir</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php if (mysqli_num_rows($query) > 0): ?>
                        <?php $no = 1; ?>
                        <?php while ($data = mysqli_fetch_assoc($query)): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($data['no_rm']) ?></td>
                            <td><strong><?= htmlspecialchars($data['nama_pasien']) ?></strong></td>
                            <td>
                                <?= $data['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan' ?>
                            </td>
                            <td><?= htmlspecialchars($data['tanggal_lahir']) ?></td>
                            <td><?= htmlspecialchars($data['alamat'] ?? '') ?></td>

                            <td>
                                <div class="actions">
                                    <a class="btn btn-edit"
                                       href="edit.php?id=<?= $data['id_pasien'] ?>">
                                        Edit
                                    </a>

                                    <button type="button"
                                        class="btn btn-danger"
                                        onclick="bukaModal(
                                            <?= (int)$data['id_pasien'] ?>,
                                            <?= htmlspecialchars(json_encode($data['nama_pasien']), ENT_QUOTES, 'UTF-8') ?>
                                        )">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>

                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="empty-state">
                                Belum ada data pasien.
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </main>
</div>

<!-- MODAL KONFIRMASI HAPUS -->
<div class="modal-overlay" id="modalHapus">

    <div class="modal-box">

        <div class="modal-icon">
            <span>✕</span>
        </div>

        <h2>Hapus Data Pasien?</h2>

        <p>
            Apakah kamu yakin ingin menghapus data pasien
            <strong id="namaPasien"></strong>?
        </p>

        <small class="modal-warning">
            Data yang sudah dihapus tidak dapat dikembalikan.
        </small>

        <form action="hapus.php" method="POST">

            <input type="hidden" name="id" id="idPasien">

            <div class="modal-actions">
                <button type="button" class="btn btn-secondary"
                        onclick="tutupModal()">
                    Batal
                </button>

                <button type="submit" class="btn btn-danger">
                    Ya, Hapus
                </button>
            </div>

        </form>

    </div>
</div>

<script>
function bukaModal(id, nama) {
    document.getElementById('idPasien').value = id;
    document.getElementById('namaPasien').textContent = nama;

    document.getElementById('modalHapus').classList.add('show');
}

function tutupModal() {
    document.getElementById('modalHapus').classList.remove('show');
}

document.getElementById('modalHapus').addEventListener('click', function(e) {
    if (e.target === this) {
        tutupModal();
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        tutupModal();
    }
});
</script>

</body>
</html>