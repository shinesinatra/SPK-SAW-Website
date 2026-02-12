<?php
    include "C:/xampp/htdocs/dinamis/connection.php";
    include "C:/xampp/htdocs/dinamis/fpdf/fpdf.php";
    session_start();
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
            $this->Image('/xampp/htdocs/dinamis/gambar/sds.jpg', 10, 5, 30);
            $this->SetFont('Times','',12);
            $this->Cell(0, 5, 'Jl.Latuharhary No.14 RT/RW. 7/7,', 0, 1, 'C');
            $this->Cell(0, 5, 'Kelurahan Menteng, Kecamatan Menteng,', 0, 1, 'C');
            $this->Cell(0, 5, 'Kota Jakarta Pusat, Daerah Khusus Ibukota Jakarta 10310', 0, 1, 'C');
            $this->Cell(0, 5, 'Telepon: (021) 3915488   	Fax: (021) 3915488', 0, 1, 'C');
            $this->Cell(0, 5, 'Email: ignatius_sd@yahoo.co.id', 0, 1, 'C');
            $this->SetLineWidth(0.7);
            $this->Line(10, 40, 200, 40);
            $this->Ln(10);
        }
        function Footer() {
            $this->SetY(-15);
            $this->SetFont('Times','B',14);
            $this->Cell(0, 10, 'SDS SANTO IGNATIUS', 0, 0, 'C');
            $this->SetFont('Times','B',8);
            $this->Cell(0,10, $this->PageNo().'/{nb}',0,0,'R');
        }
    }
    // Objek PDF
    $pdf = new PDF('P','mm','A4');
    $pdf->AliasNbPages();
    $pdf->AddPage();
    // Tampilan judul
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,10,'Data Siswa SPK Siswa Berprestasi ' .date('Y'),0,1,'C');
    $pdf->Ln(10);
    // Tampilan tabel
    $pdf->SetFont('Times','B',12);
    $pdf->Cell(25, 7, 'NISN', 1, 0, 'C');
    $pdf->Cell(18, 7, 'KELAS', 1, 0, 'C');
    $pdf->Cell(42, 7, 'NAMA', 1, 0, 'C');
    $pdf->Cell(40, 7, 'TANGGAL LAHIR', 1, 0, 'C');
    $pdf->Cell(40, 7, 'JENIS KELAMIN', 1, 0, 'C');
    $pdf->Cell(25, 7, 'ALAMAT', 1, 0, 'C');
    $pdf->Cell(10, 7, '', 0, 1);
    // Tampilan data
    $pdf->SetFont('Times','',12);
    $sql = mysqli_query($koneksi, "SELECT * FROM siswa ORDER BY FIELD(kelas, 'I', 'II', 'III', 'IV', 'V', 'VI'), nama ASC");
    while ($row = mysqli_fetch_array($sql)) {
        $pdf->Cell(25, 7, $row['nisn'], 1, 0, 'C');
        $pdf->Cell(18, 7, $row['kelas'], 1, 0, 'C');
        $pdf->Cell(42, 7, $row['nama'], 1, 0, 'C');
        $pdf->Cell(40, 7, date('d-m-Y', strtotime($row['tanggal_lahir'])), 1, 0, 'C');
        $pdf->Cell(40, 7, $row['jenis_kelamin'], 1, 0, 'C');
        $pdf->Cell(25, 7, $row['alamat'], 1, 1, 'L');
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
    $pdf->Cell(0, 5, 'Sr. Gabriella Gowa Keytimu', 0, 0, 'L');
    $pdf->SetX(150);
    $pdf->Cell(0, 5, $full_name, 0, 1, 'L');
    $pdf->Output();
?>