<?php
    include 'connection.php';
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['username'])) {
        header("Location: index.php");
        exit();
    }
    // Proses Ubah
    if (isset($_POST['ubah'])) {
        $kode = $_POST['kode'];
        $nama_baru = $_POST['nama'];
        $bobot = $_POST['bobot'];
        $query_nama_lama = "SELECT nama FROM kriteria WHERE kode = ?";
        $stmt_lama = mysqli_prepare($koneksi, $query_nama_lama);
        mysqli_stmt_bind_param($stmt_lama, "s", $kode);
        mysqli_stmt_execute($stmt_lama);
        mysqli_stmt_bind_result($stmt_lama, $nama_lama);
        mysqli_stmt_fetch($stmt_lama);
        mysqli_stmt_close($stmt_lama);
        $kolom_lama = preg_replace('/[^a-zA-Z0-9_]/', '_', strtolower($nama_lama));
        $kolom_baru = preg_replace('/[^a-zA-Z0-9_]/', '_', strtolower($nama_baru));
        if ($kolom_lama !== $kolom_baru) {
            $alter_query = "ALTER TABLE penilaian CHANGE `$kolom_lama` `$kolom_baru` DOUBLE";
            if (!mysqli_query($koneksi, $alter_query)) {
                echo "<script>alert('Gagal mengubah nama kolom penilaian'); window.location.href = 'menu.php?page=kriteria';</script>";
                exit();
            }
        }
        $update_query = "UPDATE kriteria SET nama = ?, bobot = ? WHERE kode = ?";
        $stmt = mysqli_prepare($koneksi, $update_query);
        mysqli_stmt_bind_param($stmt, "sds", $nama_baru, $bobot, $kode);
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Data Diperbarui!'); window.location.href = 'menu.php?page=kriteria';</script>";
        } else {
            echo "<script>alert('Gagal mengubah data');</script>";
        }
    }
    // Proses tambah
    if (isset($_POST['tambah'])) {
        $kode = $_POST['kode'];
        $nama = $_POST['nama'];
        $bobot = $_POST['bobot'];
        $check_query = "SELECT COUNT(*) FROM kriteria WHERE kode = ?";
        $stmt_check = mysqli_prepare($koneksi, $check_query);
        mysqli_stmt_bind_param($stmt_check, "s", $kode);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_bind_result($stmt_check, $count);
        mysqli_stmt_fetch($stmt_check);
        mysqli_stmt_close($stmt_check);
        if ($count > 0) {
            echo "<script>alert('Kode sudah ada!'); window.location.href = 'menu.php?page=kriteria';</script>";
        } else {
            $insert_query = "INSERT INTO kriteria (kode, nama, bobot) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($koneksi, $insert_query);
            mysqli_stmt_bind_param($stmt, "ssd", $kode, $nama, $bobot);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                $nama_kolom = preg_replace('/[^a-zA-Z0-9_]/', '_', strtolower($nama));
                $alter_query = "ALTER TABLE penilaian ADD `$nama_kolom` DOUBLE";
                if (mysqli_query($koneksi, $alter_query)) {
                    echo "<script>alert('Data ditambahkan!'); window.location.href = 'menu.php?page=kriteria';</script>";
                } else {
                    echo "<script>alert('Data ditambahkan, penambahan kolom penilaian gagal'); window.location.href = 'menu.php?page=kriteria';</script>";
                }
            } else {
                echo "<script>alert('Gagal menambah data'); window.location.href = 'menu.php?page=kriteria';</script>";
            }
        }
    }
    // Proses hapus
    if (isset($_GET['hapus'])) {
        $kode = $_GET['hapus'];
        $query_nama = "SELECT nama FROM kriteria WHERE kode = ?";
        $stmt_nama = mysqli_prepare($koneksi, $query_nama);
        mysqli_stmt_bind_param($stmt_nama, "s", $kode);
        mysqli_stmt_execute($stmt_nama);
        mysqli_stmt_bind_result($stmt_nama, $nama_kriteria);
        mysqli_stmt_fetch($stmt_nama);
        mysqli_stmt_close($stmt_nama);
        if ($nama_kriteria) {
            $nama_kolom = preg_replace('/[^a-zA-Z0-9_]/', '_', strtolower($nama_kriteria));
            $delete_query = "DELETE FROM kriteria WHERE kode = ?";
            $stmt = mysqli_prepare($koneksi, $delete_query);
            mysqli_stmt_bind_param($stmt, "s", $kode);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                $alter_drop = "ALTER TABLE penilaian DROP COLUMN `$nama_kolom`";
                if (mysqli_query($koneksi, $alter_drop)) {
                    echo "<script>alert('Data dihapus!'); window.location.href = 'menu.php?page=kriteria';</script>";
                } else {
                    echo "<script>alert('Data dihapus, tapi gagal menghapus kolom penilaian.'); window.location.href = 'menu.php?page=kriteria';</script>";
                }
            } else {
                echo "<script>alert('Gagal menghapus data');</script>";
            }
        } else {
            echo "<script>alert('Data kriteria tidak ditemukan'); window.location.href = 'menu.php?page=kriteria';</script>";
        }
    }
    //input kode otomatis
    $query_kode = "SELECT kode FROM kriteria ORDER BY kode DESC LIMIT 1";
    $result_kode = mysqli_query($koneksi, $query_kode);
    $kode_baru = "C1";
    if ($row_kode = mysqli_fetch_assoc($result_kode)) {
        $last_kode = $row_kode['kode'];
        $last_number = intval(substr($last_kode, 1));
        $next_number = $last_number + 1;
        $kode_baru = "C" . $next_number;
    }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kriteria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body { background-color: #ffffff; }
        .card { border-radius: 15px; }
        h4 { color: #333; font-weight: 600; font-size: 24px; text-align: center; }
        .table th, .table td { text-align: center; padding: 15px; }
        .table thead { background-color: #007bff; color: white; }
        .table tbody tr:hover { background-color: #e9ecef; }
        .btn-custom { background-color: #007bff; color: white; }
        .btn-custom:hover { background-color: #0056b3; }
        .modal-content { border-radius: 10px; }
        .modal-header { background-color: #28a745; color: white; }
        .modal-footer { display: flex; justify-content: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="d-flex justify-content-between mb-4">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#tambahModal">
                <i class="bi bi-plus-circle"></i> Tambah Data
            </button>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>Data Kriteria</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Bobot</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM kriteria";
                            $result = mysqli_query($koneksi, $query);
                            while ($row = mysqli_fetch_assoc($result)):
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($row['kode']); ?></td>
                                <td><?= htmlspecialchars($row['nama']); ?></td>
                                <td><?= htmlspecialchars($row['bobot']); ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#ubahModal"
                                            data-kode="<?= $row['kode']; ?>" data-nama="<?= $row['nama']; ?>" data-bobot="<?= $row['bobot']; ?>">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <a href="kriteria.php?hapus=<?= $row['kode']; ?>" onclick="return confirm('Yakin Hapus Data?')" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash-fill"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Tambah -->
    <div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kriteria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="kriteria.php">
                        <div class="mb-3">
                            <label for="kode" class="form-label">Kode</label>
                            <input type="text" class="form-control" id="kode" name="kode" value="<?= $kode_baru ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="bobot" class="form-label">Bobot</label>
                            <input type="number" step="0.01" class="form-control" id="bobot" name="bobot" required>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="tambah" class="btn btn-success">Tambah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Ubah -->
    <div class="modal fade" id="ubahModal" tabindex="-1" aria-labelledby="ubahModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Kriteria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="kriteria.php">
                        <div class="mb-3">
                            <label for="editKode" class="form-label">Kode</label>
                            <input type="text" class="form-control" id="editKode" name="kode" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="editNama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="editNama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="editBobot" class="form-label">Bobot</label>
                            <input type="number" step="0.01" class="form-control" id="editBobot" name="bobot" required>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="ubah" class="btn btn-success">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        var ubahModal = document.getElementById('ubahModal');
        ubahModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var kode = button.getAttribute('data-kode');
            var nama = button.getAttribute('data-nama');
            var bobot = button.getAttribute('data-bobot');
            document.getElementById('editKode').value = kode;
            document.getElementById('editNama').value = nama;
            document.getElementById('editBobot').value = bobot;
        });
    </script>
</body>
</html>