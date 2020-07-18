<?php
if (!extension_loaded('imagick')){
    echo 'Imagick not installed';
    exit;
}

if (isset($_GET['src'])) {
    $pdf = $_GET['src'];
    if (!file_exists($pdf)) {echo 'Source file not found';exit;}
} else {
    echo 'No source file stated';
    exit;
}

if (isset($_GET['page'])) $p = intval($_GET['page']); else $p = 0;


$fp_pdf = fopen($pdf, 'rb');

$img = new imagick();

# Check how many pages are in pdf
$img->pingImage($pdf);
$nopages = $img->getNumberImages();

if ($p<0 || $p > $nopages) $p = 0;


$img->setResolution(120,120);

$img->setImageBackgroundColor('#ffffff');
$img->readImage($pdf."[$p]");

$img->setImageFormat( "png" );

$img->setImageUnits(imagick::RESOLUTION_PIXELSPERINCH);

$data = $img->getImageBlob(); 
header("Content-Type: image/png");
echo $data;

?>
