
<?php
include '../koneksi.php';

$query = mysqli_query($conn, "SELECT * FROM obat ORDER BY id_obat DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Obat | MedCare</title>
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
        <a href="../pasien/index.php" class="nav-link">♙ Data Pasien</a>
        <a href="../dokter/index.php" class="nav-link">⚕ Data Dokter</a>
        <a href="index.php" class="nav-link active">✚ Data Obat</a>

        <p class="menu-title">PEMERIKSAAN</p>
        <a href="../rekam_medis/index.php" class="nav-link">▤ Rekam Medis</a>
    </aside>

    <main class="main-content">

        <div class="topbar">
            <div>
                <h1>Data Obat</h1>
                <p>Kelola informasi dan persediaan obat.</p>
            </div>
            <span>✚ MedCare</span>
        </div>

        <div class="page-title">
            <h2>Daftar Obat</h2>
            <p>Seluruh data obat yang tersimpan dalam sistem.</p>
        </div>

        <?php
        if (isset($_GET['pesan'])):

            $judul = '';
            $deskripsi = '';

            if ($_GET['pesan'] == 'tambah') {
                $judul = 'Data Berhasil Disimpan!';
                $deskripsi = 'Data obat baru berhasil ditambahkan ke sistem.';
            } elseif ($_GET['pesan'] == 'edit') {
                $judul = 'Data Berhasil Diperbarui!';
                $deskripsi = 'Informasi obat berhasil diperbarui.';
            } elseif ($_GET['pesan'] == 'hapus') {
                $judul = 'Data Berhasil Dihapus!';
                $deskripsi = 'Data obat berhasil dihapus dari sistem.';
            }

            if ($judul != ''):
        ?>
            <div class="success-toast" id="successToast">
                <div class="success-icon">✓</div>
                <div class="success-message">
                    <strong><?= htmlspecialchars($judul) ?></strong>
                    <span><?= htmlspecialchars($deskripsi) ?></span>
                </div>
            </div>
        <?php
            endif;
        endif;
        ?>

        <div class="content-card">

            <div style="display:flex; justify-content:space-between;
                        align-items:center; margin-bottom:22px; gap:10px;">
                <div>
                    <h3>Data Obat</h3>
                    <p style="color:#758780; font-size:12px; margin-top:5px;">
                        Total obat: <?= mysqli_num_rows($query) ?> data
                    </p>
                </div>

                <a href="tambah.php" class="btn">+ Tambah Obat</a>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>ID Obat</th>
                            <th>Nama Obat</th>
                            <th>Satuan</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php if (mysqli_num_rows($query) > 0): ?>
                        <?php $no = 1; ?>
                        <?php while ($data = mysqli_fetch_assoc($query)): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= (int)$data['id_obat'] ?></td>
                            <td>
                                <strong>
                                    <?= htmlspecialchars($data['nama_obat']) ?>
                                </strong>
                            </td>
                            <td><?= htmlspecialchars($data['satuan']) ?></td>
                            <td><?= (int)$data['stok'] ?></td>

                            <td>
                                <div class="actions">

                                    <a class="btn btn-edit"
                                       href="edit.php?id=<?= (int)$data['id_obat'] ?>">
                                        Edit
                                    </a>

                                    <button type="button"
                                        class="btn btn-danger"
                                        onclick="bukaModal(
                                            <?= (int)$data['id_obat'] ?>,
                                            <?= htmlspecialchars(json_encode($data['nama_obat']), ENT_QUOTES, 'UTF-8') ?>
                                        )">
                                        Hapus
                                    </button>

                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>

                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="empty-state">
                                Belum ada data obat.
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </main>
</div>

<!-- POPUP KONFIRMASI HAPUS -->
<div class="modal-overlay" id="modalHapus">

    <div class="modal-box">

        <div class="modal-icon">
            <span>✕</span>
        </div>

        <h2>Hapus Data Obat?</h2>

        <p>
            Apakah kamu yakin ingin menghapus data obat
            <strong id="namaObat"></strong>?
        </p>

        <small class="modal-warning">
            Data yang sudah dihapus tidak dapat dikembalikan.
        </small>

        <form action="hapus.php" method="POST">

            <input type="hidden" name="id" id="idObat">

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
// POPUP HAPUS
function bukaModal(id, nama) {
    document.getElementById('idObat').value = id;
    document.getElementById('namaObat').textContent = nama;
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

// NOTIFIKASI SUKSES
const toast = document.getElementById('successToast');

if (toast) {
    setTimeout(() => {
        toast.classList.add('hide');

        setTimeout(() => {
            toast.remove();
        }, 500);
    }, 3500);

    window.history.replaceState(
        {},
        document.title,
        window.location.pathname
    );
}
</script>

</body>
</html>
