<?php 

$text = $_GET['text'];

$image = imagecreatetruecolor(100, 100);
$bgColor = imagecolorallocate($image, 204, 255, 255);
$textColor = imagecolorallocate($image, 153, 204, 204); 

$fontFile = __DIR__.'/fonts/Lobster-Regular.ttf';

if (!file_exists($fontFile)) {
    echo 'Файл со шрифтом не найден!';
    exit;
}

imagefill($image, 0, 0, $bgColor);
imagettftext($image, 24, 30, 20, 80, $textColor, $fontFile, $text);

header('Content-type: image/png');
imagepng($image);
imagedestroy($image);