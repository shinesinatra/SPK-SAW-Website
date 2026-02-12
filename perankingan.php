<?php
    include "connection.php";
    if (!isset($_SESSION['username'])) {
        header('Location: index.php');
        exit();
    }    
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
        <style>
            body { font-family: Arial, sans-serif; }
            .container { margin-top: 30px; }
            h2 { text-align: center; margin-bottom: 10px; font-size: 28px; font-weight: bold; }
            .table th, .table td { text-align: center; vertical-align: middle; }
            .table thead { background-color: #007bff; color: #fff; }
            .table-striped tbody tr:nth-child(odd) { background-color: #f2f2f2; }
            .btn-custom { background-color: #007bff; color: white; border: none;
                border-radius: 5px; padding: 10px 15px; font-size: 16px;
                transition: background-color 0.3s; }
            .btn-custom:hover { background-color: #0056b3; }
            .icon { font-size: 16px; margin-right: 5px; }
            .table-container { margin-bottom: 30px; }
            .action-btns .btn { margin-right: 5px; }
            .modal-title, .modal-footer { text-align: center; width: 100%; }
            .modal-footer .btn { margin: 0 auto; }
            #bobot { -moz-appearance: textfield; -webkit-appearance: none; }
        </style>
    </head>
    <body>
        <form action="" method="POST">
            <div class="container">
                <h2>Perankingan</h2>
                <div class="form-group d-flex justify-content-center">
                <select class="form-control w-auto " id="kelas" name="kelas">
                    <option value="Pilih Kelas">Pilih Kelas</option>
                    <?php
                        $sql_kelas = "SELECT DISTINCT kelas FROM penilaian ORDER BY FIELD(kelas, 'I', 'II', 'III' , 'IV' , 'V', 'VI')";
                        $result_kelas = $koneksi->query($sql_kelas);
                        while($row_kelas = $result_kelas->fetch_assoc()) {
                            $kelas = $row_kelas['kelas'];
                            echo "<option value='$kelas'>$kelas</option>";
                        }
                    ?>
                </select>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <button type="submit" class="btn btn-primary" name="proses">
                        <i class="fas fa-hourglass-half"></i> Proses SAW
                    </button>
                    <button type="submit" class="btn btn-success" name="reset">
                        <i class="fas fa-sync-alt"></i> Reset Tabel
                    </button>
                </div>
                <div class="table-responsive">
                    <div class="table-container">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Peringkat</th>
                                    <th>NISN</th>
                                    <th>Nama</th>
                                    <th>Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $query = "SELECT * FROM perankingan ORDER BY nilai DESC";
                                    $result = mysqli_query($koneksi, $query);
                                    $no = 1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                <tr>
                                    <td><?php echo $no ; ?></td>
                                    <td><?php echo $row['nisn']; ?></td>
                                    <td><?php echo $row['nama']; ?></td>
                                    <td><?php echo number_format($row['nilai'], 3); ?></td>
                                </tr>
                                <?php $no++; } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </form>
        <?php
            include "connection.php";
            if (isset($_POST['proses'])) {
                $kelas = $_POST['kelas'];
                if ($kelas == "Pilih Kelas") {
                    echo "<script>alert('Silahkan Pilih Kelas');</script>";
                } else {
                    // Ambil kolom bertipe double
                    $sql_field = "SHOW COLUMNS FROM penilaian WHERE Type LIKE '%double%'";
                    $result_field = $koneksi->query($sql_field);
                    $kriteria_arr = [];
                    while ($row_field = $result_field->fetch_assoc()) {
                        $kriteria_arr[] = $row_field['Field'];
                    }
                    $kriteria = implode(", ", $kriteria_arr);
                    // Ambil nilai maks
                    $max_fields = [];
                    foreach ($kriteria_arr as $field) {
                        $max_fields[] = "MAX($field) AS max_$field";
                    }
                    $max_query = "SELECT " . implode(", ", $max_fields) . " FROM penilaian WHERE kelas='$kelas'";
                    $result_max = $koneksi->query($max_query);
                    $max_values = $result_max->fetch_assoc();
                    // Ambil data penilaian
                    $sql_nilai = "SELECT nisn, nama, $kriteria FROM penilaian WHERE kelas='$kelas'";
                    $result_nilai = $koneksi->query($sql_nilai);
                    // Ambil Nilai bobot 
                    $bobot_query = "SELECT bobot FROM kriteria";
                    $result_bobot = $koneksi->query($bobot_query);
                    $bobot_arr = [];
                    while ($row_bobot = $result_bobot->fetch_assoc()) {
                        $bobot_arr[] = $row_bobot['bobot'];
                    }
                    // Proses normalisasi
                    while ($row = $result_nilai->fetch_assoc()) {
                        $nisn = $row['nisn'];
                        $nama = $row['nama'];
                        $normalized_row = [];
                        $nilai_referensi = 0;
                        foreach ($kriteria_arr as $index => $field) {
                            if ($max_values["max_$field"] != 0) {
                                $normalized = $row[$field] / $max_values["max_$field"];
                            } else {
                                $normalized = 0;
                            }
                            $normalized_row[$field] = $normalized;
                            $nilai_referensi += $normalized * $bobot_arr[$index];
                        }
                        // Simpan ke tabel perankingan
                        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                        try {
                            $insert_ranking = "INSERT INTO perankingan VALUES ('$nisn', '$nama', '$nilai_referensi')";
                            mysqli_query($koneksi, $insert_ranking);
                            echo "<script>alert('Perhitungan Berhasil!'); window.location.href = 'menu.php?page=perankingan';</script>";
                        } catch (mysqli_sql_exception $e) {
                            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                                echo "<script>alert('Data Sudah Ada');</script>";
                                break;
                            } else {
                                echo "<script>alert('Perhitungan Gagal: " . $e->getMessage() . "');</script>";
                                break;
                            }
                        }
                    }
                }
            }
            //Proses Reset Tabel
            if (isset($_POST['reset'])) {
                $reset = "truncate perankingan";
                $stmt = mysqli_query($koneksi, $reset);                
                if ($stmt) {
                    echo "<script>alert('Tabel Direset!'); window.location.href='menu.php?page=perankingan';</script>";
                }
            }
        ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>
</html>