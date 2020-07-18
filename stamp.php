<?php
# FPDF and FPDI-Extension required

# Fpdi-Settings
use setasign\Fpdi\Fpdi;   
require_once('fpdf.php');
require_once('src/autoload.php');

# Parameters
if (isset($_GET['src'])) {
    $src = 'uploads/'.$_GET['src'];
    if (!file_exists($src)) {echo 'Source file not found';exit;}
} else {
    echo 'No source file stated';
    exit;
}

if (isset($_GET['x'])) $x = intval($_GET['x']); else $x = 140;
if (isset($_GET['y'])) $y = intval($_GET['y']); else $y = 10;
if (isset($_GET['text'])) $text = urldecode($_GET['text']); else $text = "STAMP";

# Initiate FPDI
#$pdf = new Fpdi();
$pdf = new FPDI('Portrait','mm',array(210,297));
// add a page
    
// set the source file
$pageCount = $pdf->setSourceFile($src);
// import page 1
    
for ($i=1;$i<=$pageCount;$i++) {
    $pdf->AddPage();
    $tplIdx = $pdf->importPage($i);
    // use the imported page and place it at position 10,10 with a width of 100 mm
    $pdf->useTemplate($tplIdx);

    # Put stamp on first page only
    if ($i==1) {
        $pdf->SetFont('Helvetica');
        $pdf->SetFillColor(255,255,255);
        $pdf->SetDrawColor(255,0,0);
        $pdf->SetTextColor(255, 0, 0);
        $pdf->SetXY($x, $y);
        #$pdf->Cell($text,1);
        $pdf->MultiCell(50,5,$text,1,1,"C",true);
    }
}

$pdf->Output();
    
?>
