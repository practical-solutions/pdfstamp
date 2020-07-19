<?php
# FPDF and FPDI-Extension required

# Fpdi-Settings
use setasign\Fpdi\Fpdi;   
require_once('fpdf/fpdf.php');
require_once('fpdi/src/autoload.php');

# Parameters
if (isset($_GET['src'])) {
    $src = 'uploads/'.$_GET['src'];
    if (!file_exists($src)) {echo 'Source file not found';exit;}
} else {
    echo 'No source file stated';
    exit;
}

if (isset($_GET['x'])) $x = intval($_GET['x']); else $x = 60;
if (isset($_GET['y'])) $y = intval($_GET['y']); else $y = 5;
if (isset($_GET['text'])) $text = urldecode($_GET['text']); else $text = "STAMP";

$text = iconv('UTF-8', 'ISO-8859-1',$text); # Zur Darstellung der Umlaute!

$x = intval(210*($x/100));
$y = intval(297*($y/100));


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
        $pdf->SetFont('Arial');
        $pdf->SetFillColor(255,255,255);
        $pdf->SetDrawColor(65, 126, 191);
        $pdf->SetTextColor(65, 126, 191);
        $pdf->SetXY($x, $y);
        #$pdf->Cell($text,1);
        $pdf->MultiCell(50,5,$text,1,1,"C",true);
        $y = $pdf->GetY();
        $pdf->SetXY($x, $y);
        $pdf->SetFont('Courier','',8);
        $pdf->MultiCell(50,5,date("d.m.Y / H:i:s",time()),1,1,"C",true);
        
    }
}

#$pdf->Output();

$pdf->Output('F',$src);
header('Location: convert.php?msg='.urlencode("Document was marked.").'&src='.urlencode($_GET['src']));
exit;

    
?>
