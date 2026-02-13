<?php
    include "C:/xampp/htdocs/spk_saw/connection.php";
    include "C:/xampp/htdocs/spk_saw/fpdf/fpdf.php";
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
    // Code for generating PDF remains the same
    class PDF extends FPDF {
        function Header() {
            $this->Image('/xampp/htdocs/spk_saw/gambar/logo.png', 10, 5, 20);
            $this->SetFont('Times','',12);
            $this->Cell(0, 5, 'Alamat', 0, 1, 'C');
            $this->Cell(0, 5, ' ', 0, 1, 'C');
            $this->Cell(0, 5, ' ', 0, 1, 'C');
            $this->Cell(0, 5, 'Telepon: (021)    	Fax: (021) ', 0, 1, 'C');
            $this->Cell(0, 5, 'Email: example@yahoo.co.id', 0, 1, 'C');
            $this->SetLineWidth(0.7);
            $this->Line(10, 40, 285, 40);
            $this->Ln(10);
        }
        
    }// Objek PDF
    $pdf = new PDF('L','mm','A4');
    $pdf->AliasNbPages();
    $pdf->AddPage();
    // Tampilan judul
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,10,'Penilaian Siswa Berprestasi',0,1,'C');
    $pdf->Ln(10);
    // Tampilan tabel
    $pdf->SetFont('Times','B',12);
    $pdf->Cell(18, 7, 'KELAS', 1, 0, 'C');
    $pdf->Cell(25, 7, 'NISN', 1, 0, 'C');
    $pdf->Cell(42, 7, 'NAMA', 1, 0, 'C');
    $pdf->Cell(38, 7, 'KRITERIA 1', 1, 0, 'C');
    $pdf->Cell(38, 7, 'KRITERIA 2', 1, 0, 'C');
    $pdf->Cell(38, 7, 'KRITERIA 3', 1, 0, 'C');
    $pdf->Cell(38, 7, 'KRITERIA 4', 1, 0, 'C');
    $pdf->Cell(38, 7, 'KRITERIA 5', 1, 0, 'C');
    $pdf->Cell(10, 7, '', 0, 1);
    // Tampilan data
    $pdf->SetFont('Times','',12);
    $sql_field = "SHOW COLUMNS FROM penilaian WHERE Type LIKE '%double%'";
    $result_field = $koneksi->query($sql_field);
    $kriteria_arr = [];
    while ($row_field = $result_field->fetch_assoc()) {
        $kriteria_arr[] = $row_field['Field'];
    }
    $kriteria = implode(", ", $kriteria_arr);
    $sql = mysqli_query($koneksi, "SELECT * FROM penilaian ORDER BY FIELD(kelas, 'I', 'II', 'III', 'IV', 'V', 'VI'), nama ASC");
    while ($row = mysqli_fetch_array($sql)) {
        $pdf->Cell(18, 7, $row['kelas'], 1, 0, 'C');
        $pdf->Cell(25, 7, $row['nisn'], 1, 0, 'C');
        $pdf->Cell(42, 7, $row['nama'], 1, 0, 'C');
        foreach ($kriteria_arr as $field) {
            $pdf->Cell(38, 7, $row[$field], 1, 0, 'C');
        }
        $pdf->Cell(10, 7, '', 0, 1);
    }
    $pdf->Ln(40);
    $pdf->SetFont('Times','',12);
    $pdf->SetX(200);
    $pdf->Cell(0, 5, 'Jakarta, ' . $hari . ' ' . $tanggal, 0, 1, 'L');
    $pdf->SetX(55);
    $pdf->Cell(0, 5, 'Mengetahui,', 0, 0, 'L');
    $pdf->SetX(215);
    $pdf->Cell(0, 5, 'Dibuat Oleh,', 0, 1, 'L');
    $pdf->SetX(52);
    $pdf->Cell(0, 5, 'Kepala Sekolah', 0, 0, 'L');
    $pdf->SetX(220);
    $pdf->Cell(0, 5, 'Petugas', 0, 0, 'L');
    $pdf->Ln(25);
    $pdf->SetX(40);
    $pdf->Cell(0, 5, 'Nama', 0, 0, 'L');
    $pdf->SetX(215);
    $pdf->Cell(0, 5, $full_name, 0, 1, 'L');
    $pdf->Output();
?>