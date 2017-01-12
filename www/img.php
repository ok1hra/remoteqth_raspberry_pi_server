<?php
// apt-get install php5-gd
$poloha = isset($_GET['poloha']) ? $_GET['poloha'] : 1;
$doraz_l = isset($_GET['doraz_l']) ? $_GET['doraz_l'] : 1;
$doraz_p = isset($_GET['doraz_p']) ? $_GET['doraz_p'] : 1;
$ant = isset($_GET['ant']) ? $_GET['ant'] : 1;
$cil = isset($_GET['cil']) ? $_GET['cil'] : 1;
$a = "160";
$b = "141";

header("Content-type: image/png");
$im = @imagecreatefrompng('azimuth-map.png');

$white = imagecolorallocate($im, 0xFF, 0xFF, 0xFF);
$azur = imagecolorallocate($im, 0, 155, 255);
$gray = imagecolorallocate($im, 100, 100, 100);

if(isset($_GET['ant'])) {
	imagestring($im, 4, 5, 5, $ant.' '.$poloha.' deg', $gray);
} 
if(isset($_GET['doraz_l'])) {
	imagesetthickness  ( $im , 2 );
	$x=$a+$b*cos(deg2rad($doraz_l-90));
	$y=$a+$b*sin(deg2rad($doraz_l-90));
	imageline($im, 160, 160, $x, $y, $gray);
}
if(isset($_GET['doraz_p'])) {
	imagesetthickness  ( $im , 2 );
	$x=$a+$b*cos(deg2rad($doraz_p-90));
	$y=$a+$b*sin(deg2rad($doraz_p-90));
	imageline($im, 160, 160, $x, $y, $gray);
}
if(isset($_GET['cil'])) {
	imagesetthickness  ( $im , 2 );
	$x=$a+$b*cos(deg2rad($cil-90));
	$y=$a+$b*sin(deg2rad($cil-90));
	imageline($im, 160, 160, $x, $y, $white);
}
if(isset($_GET['poloha'])) {
	imagesetthickness  ( $im , 4 );
	$x=$a+$b*cos(deg2rad($poloha-90));
	$y=$a+$b*sin(deg2rad($poloha-90));
	imageline($im, 160, 160, $x, $y, $azur);
}
	imagepng($im);
	imagedestroy($im);
?>
