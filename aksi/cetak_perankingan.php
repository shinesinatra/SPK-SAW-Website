<?php
    session_start();
    include "C:/xampp/htdocs/spk_saw/connection.php";
    include "C:/xampp/htdocs/spk_saw/fpdf/fpdf.php";
    date_default_timezone_set('Asia/Jakarta');
    $username = $_SESSION['username'];
    $query = "SELECT full_name FROM users WHERE username = '$username'";
    $result = mysqli_query($koneksi, $query);
    $row = mysqli_fetch_assoc($result);
    $full_name = $row['full_name'];
    $hariIndo = array(
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu',
        'Sunday' => 'Minggu'
    );
    $hari = date('l');
    $hari = $hariIndo[$hari];
    $bulan = array(
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );
    $tanggal = date('d') . ' ' . $bulan[(int)date('m')] . ' ' . date('Y');
    class PDF extends FPDF {
        function Header() {
            $this->Image('/xampp/htdocs/spk_saw/gambar/logo.png', 10, 5, 20);
            $this->SetFont('Times','',12);
            $this->Cell(0, 5, 'Alamat', 0, 1, 'C');
            $this->Cell(0, 5, ' ', 0, 1, 'C');
            $this->Cell(0, 5, ' ', 0, 1, 'C');
            $this->Cell(0, 5, 'Telepon: (021)   	Fax: (021) ', 0, 1, 'C');
            $this->Cell(0, 5, 'Email: example@yahoo.co.id', 0, 1, 'C');
            $this->SetLineWidth(0.7);
            $this->Line(10, 40, 200, 40);
            $this->Ln(10);
        }
        
    }
    // Objek PDF
    $pdf = new PDF('P','mm','A4');
    $pdf->AliasNbPages();
    $pdf->AddPage();
    // Tampilan judul
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,10,'Perankingan Siswa Berprestasi Tahun ' .date('Y'),0,1,'C');
    $pdf->Ln(10);
    // Tampilan tabel
    $pdf->SetFont('Times','B',12);
    $pdf->Cell(40, 7, 'PERINGKAT', 1, 0, 'C');
    $pdf->Cell(40, 7, 'NISN', 1, 0, 'C');
    $pdf->Cell(70, 7, 'NAMA', 1, 0, 'C');
    $pdf->Cell(40, 7, 'NILAI', 1, 0, 'C');
    $pdf->Cell(10, 7, '', 0, 1);
    // Tampilan data
    $pdf->SetFont('Times','',12);
    $sql = mysqli_query($koneksi, "SELECT * FROM perankingan ORDER BY nilai DESC");
    $no = 1;
    while ($row = mysqli_fetch_array($sql)) {
        $pdf->Cell(40, 7, $no, 1, 0, 'C');
        $pdf->Cell(40, 7, $row['nisn'], 1, 0, 'C');
        $pdf->Cell(70, 7, $row['nama'], 1, 0, 'L');
        $pdf->Cell(40, 7, number_format($row['nilai'], 3), 1, 1, 'C');
        $no++;
    }
    $pdf->Ln(50);
    $pdf->SetFont('Times','',12);
    $pdf->SetX(135);
    $pdf->Cell(0, 5, 'Jakarta, ' . $hari . ' ' . $tanggal, 0, 1, 'L');
    $pdf->SetX(30);
    $pdf->Cell(0, 5, 'Mengetahui,', 0, 0, 'L');
    $pdf->SetX(150);
    $pdf->Cell(0, 5, 'Dibuat Oleh,', 0, 1, 'L');
    $pdf->SetX(28);
    $pdf->Cell(0, 5, 'Kepala Sekolah', 0, 0, 'L');
    $pdf->SetX(155);
    $pdf->Cell(0, 5, 'Petugas', 0, 0, 'L');
    $pdf->Ln(30);
    $pdf->SetX(18);
    $pdf->Cell(0, 5, 'Nama', 0, 0, 'L');
    $pdf->SetX(150);
    $pdf->Cell(0, 5, $full_name, 0, 1, 'L');
    $pdf->Output();
?>