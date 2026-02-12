<?php
    include 'connection.php';
    if (!isset($_SESSION['username'])) {
        header("Location: index.php");
        exit();
    }
    //Jumlah Kriteria
    $queryKriteria = "SELECT COUNT(*) as total FROM kriteria";
    $resultKriteria = mysqli_query($koneksi, $queryKriteria);
    $dataKriteria = mysqli_fetch_assoc($resultKriteria);
    $totalKriteria = $dataKriteria['total'];
    //Jumlah Data Siswa
    $querySiswa = "SELECT COUNT(*) as total FROM siswa";
    $resultSiswa = mysqli_query($koneksi, $querySiswa);
    $dataSiswa = mysqli_fetch_assoc($resultSiswa);
    $totalSiswa = $dataSiswa['total'];
    //Jumlah Penilaian
    $queryPenilaian = "SELECT COUNT(*) as total FROM penilaian";
    $resultPenilaian = mysqli_query($koneksi, $queryPenilaian);
    $dataPenilaian = mysqli_fetch_assoc($resultPenilaian);
    $totalPenilaian = $dataPenilaian['total'];
    mysqli_close($koneksi);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .container { margin-top: 30px; }
        .card { border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px; }
        .card-header { background-color: #808080; color: #fff; font-weight: bold;
            font-size: 18px; }
        .card-body { background-color: #f8f9fa; text-align: center; }
        .card-body i { font-size: 40px; color: #007bff; }
        .card-footer { background-color: #f8f9fa; border-top: 1px solid #ddd; }
        .card-footer a { color: #000; font-weight: bold; text-decoration: none;
            display: block; text-align: center; }
        .card-footer a:hover { color: #ff5733; }
        .big-number { font-size: 3rem; font-weight: bold; color: #333; color: #000000;
            animation: pulse 2s infinite; }
        .pulse-icon { animation: pulse 2s infinite; }
        .card-body i.fas.fa-chart-line { color: #000000; }
        .card-body i.fas.fa-file { color: #000000; }
        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.8; }
            100% { transform: scale(1); opacity: 1; }
        }
        .card-footer a { text-decoration: none; transition: color 0.3s ease; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-cogs"></i> Data Kriteria
                    </div>
                    <div class="card-body">
                        <h3 class="big-number"><?php echo $totalKriteria; ?></h3>
                    </div>
                    <div class="card-footer">
                        <a href="menu.php?page=kriteria">Lihat Detail</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-users"></i> Data Siswa
                    </div>
                    <div class="card-body">
                        <h3 class="big-number"><?php echo $totalSiswa; ?></h3>
                    </div>
                    <div class="card-footer">
                        <a href="menu.php?page=siswa">Lihat Detail</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-clipboard-check"></i> Data Penilaian
                    </div>
                    <div class="card-body">
                        <h3 class="big-number"><?php echo $totalPenilaian; ?></h3> <!-- Big number with pulse animation -->
                    </div>
                    <div class="card-footer">
                        <a href="menu.php?page=penilaian">Lihat Detail</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-trophy"></i> Perankingan
                    </div>
                    <div class="card-body">
                        <i class="fas fa-chart-line pulse-icon"></i>
                    </div>
                    <div class="card-footer">
                        <a href="menu.php?page=perankingan">Lihat Detail</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-file-alt"></i> Laporan
                    </div>
                    <div class="card-body">
                        <i class="fas fa-file pulse-icon"></i>
                    </div>
                    <div class="card-footer">
                        <a href="menu.php?page=laporan">Lihat Detail</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>