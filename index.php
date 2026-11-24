<?php
include 'koneksi.php';

// Fungsi menghitung jumlah data
function jumlahData($conn, $tabel) {
    $query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM `$tabel`");
    $data = mysqli_fetch_assoc($query);
    return $data['total'] ?? 0;
}

// Menghitung total data
$totalPasien = jumlahData($conn, 'pasien');
$totalDokter = jumlahData($conn, 'dokter');
$totalObat = jumlahData($conn, 'obat');
$totalRekam = jumlahData($conn, 'rekam_medis');

// Mengambil data pasien
$queryPasien = mysqli_query($conn,
    "SELECT no_rm, nama_pasien FROM pasien ORDER BY id_pasien DESC"
);

// Mengambil data dokter
$queryDokter = mysqli_query($conn,
    "SELECT nama_dokter, spesialisasi FROM dokter ORDER BY id_dokter DESC"
);

// Mengambil data obat
$queryObat = mysqli_query($conn,
    "SELECT nama_obat, satuan, stok FROM obat ORDER BY id_obat DESC"
);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Rekam Medis</title>
    <link rel="stylesheet" href="style.css">>
</head>
<body>

<div class="layout">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-icon">✚</div>
            <div>
                <h2>MedCare</h2>
                <small>REKAM MEDIS</small>
            </div>
        </div>

        <p class="menu-title">MENU UTAMA</p>

        <a href="index.php" class="nav-link active">⌂ Dashboard</a>
        <a href="pasien/index.php" class="nav-link">♙ Data Pasien</a>
        <a href="dokter/index.php" class="nav-link">⚕ Data Dokter</a>
        <a href="obat/index.php" class="nav-link">✚ Data Obat</a>

        <p class="menu-title">PEMERIKSAAN</p>

        <a href="rekam_medis/index.php" class="nav-link">▤ Rekam Medis</a>
    </aside>

    <!-- KONTEN UTAMA -->
    <main class="main-content">

        <div class="topbar">
            <div>
                <h1>Selamat Datang</h1>
                <p>Sistem Informasi Rekam Medis.</p>
            </div>
            <span>✚ MedCare</span>
        </div>

        <!-- RINGKASAN DATA -->
        <div class="page-title">
            <h2>Ringkasan Data</h2>
            <p>Informasi jumlah data yang tersimpan dalam sistem.</p>
        </div>

        <div class="card-container">

            <div class="stat-card">
                <div class="stat-icon">♙</div>
                <p>Total Pasien</p>
                <h2><?= $totalPasien ?></h2>
            </div>

            <div class="stat-card">
                <div class="stat-icon">⚕</div>
                <p>Total Dokter</p>
                <h2><?= $totalDokter ?></h2>
            </div>

            <div class="stat-card">
                <div class="stat-icon">✚</div>
                <p>Total Obat</p>
                <h2><?= $totalObat ?></h2>
            </div>

            <div class="stat-card">
                <div class="stat-icon">▤</div>
                <p>Total Pemeriksaan</p>
                <h2><?= $totalRekam ?></h2>
            </div>

        </div>

        <!-- INFORMASI APLIKASI -->
        <div class="content-card dashboard-intro">
            <h3>Sistem Informasi Rekam Medis</h3>
            <p>
                Aplikasi ini digunakan untuk mengelola data pasien,
                dokter, obat, dan riwayat pemeriksaan secara terstruktur.
                Pilih menu di sebelah kiri untuk mulai mengelola data.
            </p>
        </div>

        <!-- DATA PASIEN -->
        <section class="dashboard-section">
            <div class="section-heading">
                <div>
                    <h2>Data Pasien</h2>
                </div>
                <a href="pasien/index.php" class="btn">Lihat Semua →</a>
            </div>

            <div class="content-card dashboard-table-card">
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nomor Rekam Medis</th>
                                <th>Nama Pasien</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($queryPasien && mysqli_num_rows($queryPasien) > 0): ?>
                                <?php $no = 1; ?>
                                <?php while ($pasien = mysqli_fetch_assoc($queryPasien)): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($pasien['no_rm']) ?></td>
                                        <td>
                                            <strong><?= htmlspecialchars($pasien['nama_pasien']) ?></strong>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="empty-state">
                                        Belum ada data pasien.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- DATA DOKTER -->
        <section class="dashboard-section">
            <div class="section-heading">
                <div>
                    <h2>Data Dokter</h2>
                </div>
                <a href="dokter/index.php" class="btn">Lihat Semua →</a>
            </div>

            <div class="content-card dashboard-table-card">
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Dokter</th>
                                <th>Spesialisasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($queryDokter && mysqli_num_rows($queryDokter) > 0): ?>
                                <?php $no = 1; ?>
                                <?php while ($dokter = mysqli_fetch_assoc($queryDokter)): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td>
                                            <strong><?= htmlspecialchars($dokter['nama_dokter']) ?></strong>
                                        </td>
                                        <td><?= htmlspecialchars($dokter['spesialisasi']) ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="empty-state">
                                        Belum ada data dokter.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- DATA OBAT -->
        <section class="dashboard-section">
            <div class="section-heading">
                <div>
                    <h2>Data Obat</h2>
                </div>
                <a href="obat/index.php" class="btn">Lihat Semua →</a>
            </div>

            <div class="content-card dashboard-table-card">
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Obat</th>
                                <th>Satuan</th>
                                <th>Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($queryObat && mysqli_num_rows($queryObat) > 0): ?>
                                <?php $no = 1; ?>
                                <?php while ($obat = mysqli_fetch_assoc($queryObat)): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td>
                                            <strong><?= htmlspecialchars($obat['nama_obat']) ?></strong>
                                        </td>
                                        <td><?= htmlspecialchars($obat['satuan']) ?></td>
                                        <td><?= (int)$obat['stok'] ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="empty-state">
                                        Belum ada data obat.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

    </main>
</div>

</body>
</html>