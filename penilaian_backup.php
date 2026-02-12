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
    $query = "SELECT * FROM penilaian WHERE nisn LIKE '%$search_nisn%' ORDER BY FIELD(kelas, 'I', 'II', 'III', 'IV', 'V', 'VI'), nama ASC";
    $result = mysqli_query($koneksi, $query);
    // Proses ubah
    if (isset($_POST['ubah'])) {
        $nisn = $_POST['nisn'];
        $nama = $_POST['nama'];
        $sql_kriteria = "SELECT * FROM kriteria";
        $result_kriteria = $koneksi->query($sql_kriteria);
        $update_parts = [];
        while($row_kriteria = $result_kriteria->fetch_assoc()) {
            $nama_kriteria = $row_kriteria['nama'];
            $kriteria = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '_', $nama_kriteria));
            
            if (isset($_POST[$kriteria])) {
                $nilai = $_POST[$kriteria];
                $update_parts[] = "$kriteria = '" . mysqli_real_escape_string($koneksi, $nilai) . "'";
            }
        }
        $update_string = implode(', ', $update_parts);
        $nama_escaped = mysqli_real_escape_string($koneksi, $nama);
        $update_query = "UPDATE penilaian SET nama = '$nama_escaped', $update_string WHERE nisn = '$nisn'";
        if (mysqli_query($koneksi, $update_query)) {
            echo "<script>alert('Data diubah!'); window.location.href = 'menu.php?page=penilaian';</script>";
        } else {
            echo "<script>alert('Gagal mengubah data: " . mysqli_error($koneksi) . "');</script>";
        }
    }
    // Proses tambah
    if (isset($_POST['tambah'])) {
        $kelas = $_POST['kelas'];
        $nisn = $_POST['nisn'];
        $nama = $_POST['nama'];
        $sql_kriteria = "SELECT * FROM kriteria";
        $result_kriteria = $koneksi->query($sql_kriteria);
        while($row_kriteria = $result_kriteria->fetch_assoc()) {
            $nama_kriteria = $row_kriteria['nama'];
            $kolom_penilaian = strtolower(preg_replace('/[^a-z0-9]+/', '_', $nama_kriteria));
            $kolom_array[] = $kolom_penilaian;
            $pisah_kolom = "'" . implode("', '", $kolom_array) . "'";
        }
        $nilai_array = [];
        foreach ($kolom_array as $kolom) {
            $nilai_array[] = $_POST[$kolom];
        }
        $pisah_nilai = "'" . implode("','", $nilai_array) . "'";
        $insert_query = "INSERT INTO penilaian VALUES ('$kelas', '$nisn', '$nama', $pisah_nilai)";
        if (mysqli_query($koneksi, $insert_query)) {
            echo "<script>alert('Data Ditambahkan!'); window.location.href = 'menu.php?page=penilaian';</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($koneksi) . "'); window.location.href = 'menu.php?page=penilaian';</script>";
        }
    }
    // Proses hapus
    if (isset($_GET['hapus'])) {
        $nisn = $_GET['hapus'];
        $query = "DELETE FROM penilaian WHERE nisn=?";
        if ($stmt = mysqli_prepare($koneksi, $query)) {
            mysqli_stmt_bind_param($stmt, "s", $nisn);
            if (mysqli_stmt_execute($stmt)) {
                echo "<script>alert('Data Dihapus!'); window.location.href='menu.php?page=penilaian';</script>";
            } else {
                echo "<script>alert('Error: " . mysqli_error($koneksi) . "');</script>";
            }
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
                <h4>Penilaian</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Kelas</th>
                                <th>NISN</th>
                                <th>Nama</th>
                                <?php
                                    $sql_kriteria = "SELECT * FROM kriteria";
                                    $result_kriteria = $koneksi->query($sql_kriteria);
                                    while($row_kriteria = $result_kriteria->fetch_assoc()) {
                                        $nama = $row_kriteria['nama'];
                                        echo '<th>'.$nama.'</th>';
                                    }
                                ?>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <?php
                        $columns_result = mysqli_query($koneksi, "SHOW COLUMNS FROM penilaian");
                        $columns = [];
                        while ($col = mysqli_fetch_assoc($columns_result)) {
                            $columns[] = $col['Field'];
                        }
                        $exclude_columns = ['kelas', 'nisn', 'nama'];
                        ?>
                        <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <?php
                                foreach ($exclude_columns as $ex_col) {
                                    echo '<td>' . htmlspecialchars($row[$ex_col]) . '</td>';
                                }
                                foreach ($columns as $col) {
                                    if (!in_array($col, $exclude_columns)) {
                                        echo '<td>' . htmlspecialchars($row[$col]) . '</td>';
                                    }
                                }
                                ?>
                                <td>
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#ubahModal">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <a href="penilaian.php?hapus=<?php echo $row['nisn']; ?>" onclick="return confirm('Yakin Hapus Data?')" class="btn btn-danger btn-sm">
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
                <form action="penilaian.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ubahModalLabel">Ubah Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="nisn_ubah" name="nisn">
                        <div class="mb-3">
                            <label for="nama_ubah" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama_ubah" name="nama" required>
                        </div>
                        <?php
                            $sql_kriteria = "SELECT * FROM kriteria";
                            $result_kriteria = $koneksi->query($sql_kriteria);
                            while($row_kriteria = $result_kriteria->fetch_assoc()) {
                                $nama_kriteria = $row_kriteria['nama'];
                                $kolom = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '_', $nama_kriteria));
                                echo '<div class="mb-3">';
                                echo "<label for='ubah_$kolom' class='form-label'>$nama_kriteria</label>";
                                echo "<input type='number' step='0.01' min='0' class='form-control' id='ubah_$kolom' name='$kolom' required>";
                                echo '</div>';
                            }
                        ?>
                        <div class="modal-footer">
                            <button type="submit" name="ubah" class="btn btn-success">Simpan</button>
                        </div>
                    </div>
                </form>
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
                    <form action="penilaian.php" method="POST">
                        <div class="mb-3">
                            <label for="kelas" class="form-label">Kelas</label>
                            <select class="form-control" id="kelas" name="kelas" required>
                                <option value=""> </option>
                                <?php
                                    $sql_kelas = "SELECT DISTINCT kelas FROM siswa ORDER BY FIELD(kelas, 'I', 'II', 'III' , 'IV' , 'V', 'VI')";
                                    $result_kelas = $koneksi->query($sql_kelas);
                                    while($row_kelas = $result_kelas->fetch_assoc()) {
                                        $kelas = $row_kelas['kelas'];
                                        echo "<option value='$kelas'>$kelas</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="nisn" class="form-label">NISN</label>
                            <select class="form-control" id="nisn" name="nisn" required>
                                <option value=""> </option>
                                <?php
                                    $sql_nisn = "SELECT nisn FROM siswa";
                                    $result_nisn = $koneksi->query($sql_nisn);
                                    while($row_nisn = $result_nisn->fetch_assoc()) {
                                        $nisn = $row_nisn['nisn'];
                                        echo "<option value='$nisn'>$nisn</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <select class="form-control" id="nama" name="nama" required>
                                <option value=""> </option>
                                <?php
                                    $sql_nama = "SELECT nama FROM siswa";
                                    $result_nama = $koneksi->query($sql_nama);
                                    while($row_nama = $result_nama->fetch_assoc()) {
                                        $nama = $row_nama['nama'];
                                        echo "<option value='$nama'>$nama</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <?php
                            $sql_kriteria = "SELECT * FROM kriteria";
                            $result_kriteria = $koneksi->query($sql_kriteria);
                            while($row_kriteria = $result_kriteria->fetch_assoc()) {
                                $nama_kriteria = $row_kriteria['nama'];
                                $nama_kolom = strtolower(preg_replace('/[^a-z0-9]+/', '_', $nama_kriteria));
                                echo '<div class="mb-3">';
                                echo '<label for="'.$nama_kolom.'" class="form-label">'.$nama_kriteria.'</label>';
                                echo '<input type="number" step="0.01" min="0" class="form-control" id="'.$nama_kolom.'" name="'.$nama_kolom.'" required>';
                                echo '</div>';
                            }
                        ?>
                        <div class="modal-footer">
                            <button type="submit" name="tambah" class="btn btn-success">Tambah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ubahButtons = document.querySelectorAll('button[data-bs-target="#ubahModal"]');
            const modal = new bootstrap.Modal(document.getElementById('ubahModal'));
            ubahButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const row = this.closest('tr');
                    const cells = row.querySelectorAll('td');
                    const nisn = cells[1].innerText.trim();
                    const nama = cells[2].innerText.trim();
                    document.getElementById('nisn_ubah').value = nisn;
                    document.getElementById('nama_ubah').value = nama;
                    const inputs = document.querySelectorAll('#ubahModal input[type="number"]');
                    inputs.forEach((input, index) => {
                        const value = cells[3 + index].innerText.trim();
                        input.value = value;
                    });
                });
            });
        });
    </script>
</body>
</html>