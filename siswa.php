<?php
    include 'connection.php';
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['username'])) {
        header("Location: index.php");
        exit();
    }
    // Pencarian
    $search_nisn = '';
    if (isset($_POST['search_nisn'])) {
        $search_nisn = $_POST['search_nisn'];
    }
    $query = "SELECT * FROM siswa WHERE nisn LIKE '%$search_nisn%' ORDER BY FIELD(kelas, 'I', 'II', 'III', 'IV', 'V', 'VI'), nama ASC";
    $result = mysqli_query($koneksi, $query);
    // Proses ubah
    if (isset($_POST['ubah'])) {
        $nisn = $_POST['nisn'];
        $kelas = $_POST['kelas'];
        $nama = $_POST['nama'];
        $tanggal_lahir = $_POST['tanggal_lahir'];
        $jenis_kelamin = $_POST['jenis_kelamin'];
        $alamat = $_POST['alamat'];
        $update_query = "UPDATE siswa SET kelas = '$kelas', nama = '$nama', tanggal_lahir = '$tanggal_lahir', jenis_kelamin = '$jenis_kelamin', alamat = '$alamat' WHERE nisn = '$nisn'";
        if (mysqli_query($koneksi, $update_query)) {
            echo "<script>alert('Data Diperbarui!'); window.location.href = 'menu.php?page=siswa';</script>";
        } else {
            echo "<script>alert('Error :'); window.location.href = 'menu.php?page=siswa';</script>";
        }
    }
    // Proses tambah
    if (isset($_POST['tambah'])) {
        $nisn = $_POST['nisn'];
        $kelas = $_POST['kelas'];
        $nama = $_POST['nama'];
        $tanggal_lahir = $_POST['tanggal_lahir'];
        $jenis_kelamin = $_POST['jenis_kelamin'];
        $alamat = $_POST['alamat'];
        $check_query = "SELECT COUNT(*) FROM siswa WHERE nisn = '$nisn'";
        $check_result = mysqli_query($koneksi, $check_query);
        $check_row = mysqli_fetch_row($check_result);   
        if ($check_row[0] > 0) {
            echo "<script>alert('Data Sudah Ada!'); window.location.href = 'menu.php?page=siswa';</script>";
        } else {
            $insert_query = "INSERT INTO siswa (nisn, kelas, nama, tanggal_lahir, jenis_kelamin, alamat) VALUES ('$nisn', '$kelas', '$nama', '$tanggal_lahir', '$jenis_kelamin', '$alamat')";
            if (mysqli_query($koneksi, $insert_query)) {
                echo "<script>alert('Data Ditambahkan!'); window.location.href = 'menu.php?page=siswa';</script>";
            } else {
                echo "<script>alert('Error: " . mysqli_error($koneksi) . "'); window.location.href = 'menu.php?page=siswa';</script>";
            }
        }
    }    
    // Proses hapus
    if (isset($_GET['hapus'])) {
        $nisn = $_GET['hapus'];
        mysqli_begin_transaction($koneksi);
        try {
            $query1 = "DELETE FROM penilaian WHERE nisn = ?";
            $stmt1 = mysqli_prepare($koneksi, $query1);
            mysqli_stmt_bind_param($stmt1, "s", $nisn);
            mysqli_stmt_execute($stmt1);
            $query2 = "DELETE FROM siswa WHERE nisn = ?";
            $stmt2 = mysqli_prepare($koneksi, $query2);
            mysqli_stmt_bind_param($stmt2, "s", $nisn);
            mysqli_stmt_execute($stmt2);
            mysqli_commit($koneksi);
            echo "<script>alert('Data berhasil dihapus!'); window.location.href='menu.php?page=siswa';</script>";
        } catch (Exception $e) {
            mysqli_rollback($koneksi);
            echo "<script>alert('Gagal menghapus data: " . $e->getMessage() . "');</script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        .btn-outline-custom { border-color: #007bff; color: #007bff; }
        .btn-outline-custom:hover { background-color: #007bff; color: white; }
        .modal-content { border-radius: 10px; }
        .modal-header { background-color: #28a745; color: white; }
        .table-responsive { overflow-x: auto; }
        .modal-footer { display: flex; justify-content: center; }
        .modal-title { text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="d-flex justify-content-between mb-4">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#tambahModal">
                <i class="bi bi-plus-circle"></i> Tambah Data
            </button>
            <form method="POST" class="d-flex align-items-center" style="max-width: 400px;">
                <input type="text" name="search_nisn" class="form-control me-2" placeholder="Cari NISN..." value="<?php echo $search_nisn; ?>">
                <button type="submit" class="btn btn-outline-primary">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>Data Siswa</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Kelas</th>
                                <th>NISN</th>
                                <th>Nama</th>
                                <th>Tanggal Lahir</th>
                                <th>Jenis Kelamin</th>
                                <th>Alamat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['kelas']); ?></td>
                                    <td><?php echo htmlspecialchars($row['nisn']); ?></td>
                                    <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                    <td><?php echo date('d-m-Y', strtotime($row['tanggal_lahir'])); ?></td>
                                    <td><?php echo htmlspecialchars($row['jenis_kelamin']); ?></td>
                                    <td><?php echo htmlspecialchars($row['alamat']); ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#ubahModal" data-nisn="<?php echo $row['nisn']; ?>" data-kelas="<?php echo $row['kelas']; ?>" data-nama="<?php echo $row['nama']; ?>" data-tanggal_lahir="<?php echo $row['tanggal_lahir']; ?>" data-jenis_kelamin="<?php echo $row['jenis_kelamin']; ?>" data-alamat="<?php echo $row['alamat']; ?>">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <a href="siswa.php?hapus=<?php echo $row['nisn']; ?>" onclick="return confirm('Yakin Hapus Data?')" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
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
    <!-- Modal Ubah Data -->
    <div class="modal fade" id="ubahModal" tabindex="-1" aria-labelledby="ubahModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ubahModalLabel">Ubah Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="siswa.php" method="POST">
                        <input type="hidden" id="nisn" name="nisn">
                        <div class="mb-3">
                            <label for="kelas" class="form-label">Kelas</label>
                            <select class="form-control" id="kelas" name="kelas" required>
                                <option value="I">I</option>
                                <option value="II">II</option>
                                <option value="III">III</option>
                                <option value="IV">IV</option>
                                <option value="V">V</option>
                                <option value="VI">VI</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
                        </div>
                        <div class="mb-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" required></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="ubah" class="btn btn-success">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Tambah Data -->
    <div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahModalLabel">Tambah Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="siswa.php" method="POST">
                        <div class="mb-3">
                            <label for="kelas" class="form-label">Kelas</label>
                            <select class="form-control" id="kelas" name="kelas" required>
                                <option value=" "> </option>
                                <option value="I">I</option>
                                <option value="II">II</option>
                                <option value="III">III</option>
                                <option value="IV">IV</option>
                                <option value="V">V</option>
                                <option value="VI">VI</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="nisn" class="form-label">NISN</label>
                            <input type="number" class="form-control" id="nisn" name="nisn" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
                        </div>
                        <div class="mb-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" required></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="tambah" class="btn btn-success">Tambah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        var ubahModal = document.getElementById('ubahModal');
        ubahModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            document.getElementById('nisn').value = button.getAttribute('data-nisn');
            document.getElementById('kelas').value = button.getAttribute('data-kelas');
            document.getElementById('nama').value = button.getAttribute('data-nama');
            document.getElementById('tanggal_lahir').value = button.getAttribute('data-tanggal_lahir');
            document.getElementById('jenis_kelamin').value = button.getAttribute('data-jenis_kelamin');
            document.getElementById('alamat').value = button.getAttribute('data-alamat');
        });
    </script>
</body>
</html>