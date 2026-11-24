
<?php
include '../koneksi.php';

$query = mysqli_query($conn, "SELECT * FROM dokter ORDER BY id_dokter DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Dokter | MedCare</title>
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
        <a href="index.php" class="nav-link active">⚕ Data Dokter</a>
        <a href="../obat/index.php" class="nav-link">✚ Data Obat</a>

        <p class="menu-title">PEMERIKSAAN</p>
        <a href="../rekam_medis/index.php" class="nav-link">▤ Rekam Medis</a>
    </aside>

    <main class="main-content">

        <div class="topbar">
            <div>
                <h1>Data Dokter</h1>
                <p>Kelola informasi dan data dokter.</p>
            </div>
            <span>✚ MedCare</span>
        </div>

        <div class="page-title">
            <h2>Daftar Dokter</h2>
            <p>Seluruh data dokter yang tersimpan dalam sistem.</p>
        </div>

        
<?php
if (isset($_GET['pesan'])):

    $pesan = $_GET['pesan'];

    $judul = '';
    $deskripsi = '';

    if ($pesan == 'tambah') {
        $judul = 'Data Berhasil Disimpan!';
        $deskripsi = 'Data dokter baru berhasil ditambahkan ke sistem.';
    } elseif ($pesan == 'edit') {
        $judul = 'Data Berhasil Diperbarui!';
        $deskripsi = 'Informasi dokter berhasil diperbarui.';
    } elseif ($pesan == 'hapus') {
        $judul = 'Data Berhasil Dihapus!';
        $deskripsi = 'Data dokter berhasil dihapus dari sistem.';
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
                    <h3>Data Dokter</h3>
                    <p style="color:#758780; font-size:12px; margin-top:5px;">
                        Total dokter: <?= mysqli_num_rows($query) ?> data
                    </p>
                </div>

                <a href="tambah.php" class="btn">+ Tambah Dokter</a>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>ID Dokter</th>
                            <th>Nama Dokter</th>
                            <th>Spesialisasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php if (mysqli_num_rows($query) > 0): ?>
                        <?php $no = 1; ?>
                        <?php while ($data = mysqli_fetch_assoc($query)): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= (int)$data['id_dokter'] ?></td>
                            <td>
                                <strong>
                                    <?= htmlspecialchars($data['nama_dokter']) ?>
                                </strong>
                            </td>
                            <td><?= htmlspecialchars($data['spesialisasi']) ?></td>

                            <td>
                                <div class="actions">

                                    <a class="btn btn-edit"
                                       href="edit.php?id=<?= (int)$data['id_dokter'] ?>">
                                        Edit
                                    </a>

                                    <button type="button"
                                        class="btn btn-danger"
                                        onclick="bukaModal(
                                            <?= (int)$data['id_dokter'] ?>,
                                            <?= htmlspecialchars(json_encode($data['nama_dokter']), ENT_QUOTES, 'UTF-8') ?>
                                        )">
                                        Hapus
                                    </button>

                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>

                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="empty-state">
                                Belum ada data dokter.
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

        <h2>Hapus Data Dokter?</h2>

        <p>
            Apakah kamu yakin ingin menghapus data dokter
            <strong id="namaDokter"></strong>?
        </p>

        <small class="modal-warning">
            Data yang sudah dihapus tidak dapat dikembalikan.
        </small>

        <form action="hapus.php" method="POST">

            <input type="hidden" name="id" id="idDokter">

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
// Membuka popup hapus
function bukaModal(id, nama) {
    document.getElementById('idDokter').value = id;
    document.getElementById('namaDokter').textContent = nama;

    document.getElementById('modalHapus').classList.add('show');
}

// Menutup popup hapus
function tutupModal() {
    document.getElementById('modalHapus').classList.remove('show');
}

// Menutup popup jika klik area luar
document.getElementById('modalHapus').addEventListener('click', function(e) {
    if (e.target === this) {
        tutupModal();
    }
});

// Menutup popup dengan tombol Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        tutupModal();
    }
});

// Notifikasi berhasil
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