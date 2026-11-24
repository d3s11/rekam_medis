
<?php
include '../koneksi.php';

$query = mysqli_query($conn, "
    SELECT 
        rekam_medis.*,
        pasien.nama_pasien,
        pasien.no_rm,
        dokter.nama_dokter,
        obat.nama_obat
    FROM rekam_medis
    JOIN pasien ON rekam_medis.id_pasien = pasien.id_pasien
    JOIN dokter ON rekam_medis.id_dokter = dokter.id_dokter
    JOIN obat ON rekam_medis.id_obat = obat.id_obat
    ORDER BY rekam_medis.id_rekam DESC
");

function e($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekam Medis | MedCare</title>
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
        <a href="../index.php" class="back-link">
        ← Kembali ke Halaman Utama
        </a>
        <div class="topbar">
            <div>
                <p class="page-subtitle">MEDCARE / DATA PEMERIKSAAN</p>
                <h1 class="page-title">Rekam Medis</h1>
                <p>Kelola riwayat pemeriksaan pasien.</p>
            </div>
        </div>

        <?php if (isset($_GET['pesan'])): ?>
            <div class="alert">
                <?php
                if ($_GET['pesan'] == 'tambah') {
                    echo 'Data rekam medis berhasil ditambahkan!';
                } elseif ($_GET['pesan'] == 'edit') {
                    echo 'Data rekam medis berhasil diperbarui!';
                } elseif ($_GET['pesan'] == 'hapus') {
                    echo 'Data rekam medis berhasil dihapus!';
                }
                ?>
            </div>
        <?php endif; ?>

        <section class="content-card">
            <div class="card-header">
                <div>
                    <h2>Riwayat Pemeriksaan</h2>
                    <p>Daftar seluruh pemeriksaan pasien.</p>
                </div>
                <a href="tambah.php" class="btn">+ Tambah Rekam Medis</a>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Tanggal</th>
                            <th>No. RM</th>
                            <th>Nama Pasien</th>
                            <th>Dokter</th>
                            <th>Obat</th>
                            <th>Keluhan</th>
                            <th>Diagnosa</th>
                            <th>Tindakan</th>
                            <th>Resep</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($query) > 0): ?>
                            <?php $no = 1; ?>
                            <?php while ($data = mysqli_fetch_assoc($query)): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= e($data['tanggal_periksa']); ?></td>
                                    <td><?= e($data['no_rm']); ?></td>
                                    <td><?= e($data['nama_pasien']); ?></td>
                                    <td><?= e($data['nama_dokter']); ?></td>
                                    <td><?= e($data['nama_obat']); ?></td>
                                    <td><?= e($data['keluhan']); ?></td>
                                    <td><?= e($data['diagnosa']); ?></td>
                                    <td><?= e($data['tindakan']); ?></td>
                                    <td><?= e($data['resep']); ?></td>
                                    <td>
                                        
<div class="action-buttons">
    <a href="edit.php?id=<?= $data['id_rekam']; ?>"
       class="action-btn action-edit">
        ✎ Edit
    </a>

    <button type="button"
        class="action-btn action-delete"
        onclick="bukaModal(<?= $data['id_rekam']; ?>)">
        🗑 Hapus
    </button>
</div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="11" class="empty-state">
                                    Belum ada data rekam medis.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>

<div class="modal-overlay" id="modalHapus">
    <div class="modal-box">
        <div class="modal-icon">!</div>
        <h3>Hapus Rekam Medis?</h3>
        <p>Apakah kamu yakin ingin menghapus data pemeriksaan ini?</p> 
<form action="hapus.php" method="POST">
    <input type="hidden" name="id" id="idHapus">

    <div class="modal-actions">
        <button type="button"
            class="modal-btn modal-cancel"
            onclick="tutupModal()">
            Batal
        </button>

        <button type="submit" class="modal-btn modal-confirm">
            Ya, Hapus
        </button>
    </div>
</form>

    </div>
</div>

<script>
function bukaModal(id) {
    document.getElementById('idHapus').value = id;
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
