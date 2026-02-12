<?php
    include 'connection.php';
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
    <title>Laporan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #ffffff; }
        .card { border: none; border-radius: 12px; background-color: #f8f9fa;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover { transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }
        .card-body { text-align: center; padding: 30px; }
        .icon { font-size: 50px; color: #000000; }
        .big-number { font-size: 48px; font-weight: bold; color: #007bff; margin-bottom: 20px; }
        .btn-primary { background-color: #007bff; border: none; padding: 12px 24px;
            border-radius: 8px; font-weight: bold; transition: background-color 0.3s; }
        .btn-icon { margin-right: 8px; }
        .container { max-width: 1200px; }
        @media (max-width: 768px) { .big-number { font-size: 36px; } }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h3 class="text-center mb-4" style="font-weight: bold;">Laporan</h3>
        <div class="row">
            <!-- Data Kriteria -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <p class="big-number"><i class="fas fa-file-alt icon"></i></p>
                        <a href="aksi/cetak_kriteria.php" target="_blank" class="btn btn-success"><i class="fas fa-clipboard-list btn-icon"></i> Cetak Kriteria</a>
                    </div>
                </div>
            </div>
            <!-- Data Siswa -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <p class="big-number"><i class="fas fa-user-graduate icon"></i></p>
                        <a href="aksi/cetak_siswa.php" target="_blank" class="btn btn-success"><i class="fas fa-user-graduate btn-icon"></i> Cetak Siswa</a>
                    </div>
                </div>
            </div>
            <!-- Penilaian -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <p class="big-number"><i class="fas fa-calculator icon"></i></p>
                        <a href="aksi/cetak_penilaian.php" target="_blank" class="btn btn-success"><i class="fas fa-calculator btn-icon"></i> Cetak Penilaian</a>
                    </div>
                </div>
            </div>
            <!-- Perankingan -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <p class="big-number"><i class="fas fa-trophy icon"></i></p>
                        <a href="aksi/cetak_perankingan.php" target="_blank" class="btn btn-success"><i class="fas fa-trophy btn-icon"></i> Cetak Perankingan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>