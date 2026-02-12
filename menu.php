<?php
    session_start();
    include 'connection.php';
    if (!isset($_SESSION['username'])) {
        header("Location: index.php");
        exit();
    } 
    mysqli_close($koneksi);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SPK Aplikasi | Website</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
      .navbar-brand h2, .nav-link { color: #007bff !important; }
      .dropdown-item { color: #007bff !important; }
      .nav-link:hover, .dropdown-item:hover { color: #ff5733 !important; }
      .container { margin-top: 20px; padding-bottom: 60px; }
      .navbar { position: sticky; top: 0; z-index: 1030; }
      footer { position: fixed; bottom: 0; width: 100%; background-color: #f8f9fa;
        padding: 10px 0; text-align: center; }
    </style>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
          <a class="navbar-brand" href="menu.php">
            <h2><img src="gambar/logo.png" alt="Logo" style="width: 40px; height: 40px; margin-right: 10px;">St. Ignatius Loyola</h2>
          </a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="menu.php"><i class="fas fa-home"></i> Beranda</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="menu.php?page=kriteria"><i class="fas fa-cogs"></i> Data Kriteria</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="menu.php?page=siswa"><i class="fas fa-users"></i> Data Siswa</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="menu.php?page=penilaian"><i class="fas fa-clipboard-check"></i> Penilaian</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="menu.php?page=perankingan"><i class="fas fa-trophy"></i> Perankingan</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="menu.php?page=laporan"><i class="fas fa-file-alt"></i> Laporan</a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="fas fa-user"></i> <?php echo $_SESSION['full_name']; ?>
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
              </li>
            </ul>
          </div>
        </div>
    </nav>
    <!-- Content -->
    <div class="container mt-4">
        <?php
            $page = isset($_GET['page']) ? $_GET['page'] : 'beranda';
            $pages = ['beranda', 'kriteria', 'siswa', 'penilaian', 'perankingan', 'laporan'];
            if (in_array($page, $pages)) {
                include("$page.php");
            } else {
                include('beranda.php');
            }
        ?>
    </div>
    <!-- Footer -->
    <footer>
        <p>&copy; <?php echo date("Y"); ?> St. Ignatius Loyola. All Rights Reserved.</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>