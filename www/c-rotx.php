<!doctype html>
<html>
<body bgcolor="#000">
<?php
require 'function.php';
require 'Nette/loader.php';
use Nette\Forms\Form,
//	Nette\Diagnostics\Debugger,
	Nette\Utils\Html;
//debugger::enable();
//$configurator = new Nette\Config\Configurator; 
//$configurator->setDebugMode(TRUE);
//$configurator->enableDebugger(__DIR__ . '/../log');

// nastaveni promennych
$rotator = $_GET['rot'];
$IP = '127.0.0.1' ;
$port = $rotator + 90 ;

$rotname = rxfile("../cfg/s-rot-r{$rotator}name");
$rotl = rxfile("../cfg/s-rot-r{$rotator}from");
$rotr = rxfile("../cfg/s-rot-r{$rotator}to");
$rotget = rxfile("../cfg/s-rot-r{$rotator}get");
$rotset = rxfile("../cfg/s-rot-r{$rotator}set");

$form = new Form;
$form->setMethod('POST');
	$form->addText('azimuth', "$rotname:", 3)
		->setRequired('Choose Azimuth')
		->addRule(Form::MAX_LENGTH, 'Azimuth can have maximum %d characters', 3)
		->addRule(Form::INTEGER, 'Azimuth must be number')
		->addRule(Form::RANGE, 'Azimuth value must be from %d to %d', array(0, 359))
		->addRule($rotl > $rotr ? ~Form::RANGE : Form::RANGE, "Out of rotator range $rotl - $rotr °", array(min($rotl, $rotr), max($rotl, $rotr)));

$form->addSubmit('submit', 'Rotate');
if ($form->isSuccess())
{
	$values = $form->getValues();
	// leading zeros
	$rotate = sprintf('%03d', $values->azimuth);
	// replace \r, \n and # to azimuth
	$raw = txrxtcp($IP, $port, str_replace(
		array('\r', '\n', '#'),
		array("\r", "\n", "$rotate"), $rotset));
	$cut = Trim($raw);
	$az = substr("$cut", 3, 3);
	//$az = $rotate;
	echo "<a href=\"c-rotx.php?rot=$rotator\"><img src=\"img.php?doraz_l={$rotl}&doraz_p={$rotr}&poloha={$az}&ant={$rotname}&cil={$rotate}\" alt=\"azimuth map\" title=\"refresh\"></a>";
}
else {
	// get azimut
	$raw = txrxtcp($IP, $port, str_replace(
		array('\r', '\n'),
		array("\r", "\n"), $rotget));
	$cut = Trim($raw);
	$az = substr("$cut", 3, 3);
	echo "<a href=\"c-rotx.php?rot=$rotator\"><img src=\"img.php?doraz_l={$rotl}&doraz_p={$rotr}&poloha={$az}&ant={$rotname}&cil={$az}\" alt=\"azimuth map\" title=\"refresh\"></a>";
}
echo $form ?>


<form action="<?echo 	actualpage()."?rot=$rotator"; ?>" method="POST" class="center">
	<input type="submit" name="ccw" value="&#8634; CCW"><input type="submit" name="stop" value="STOP"><input type="submit" name="cw" value="CW &#8635;">
	<?
	if (isset($_POST['ccw'])) {
		txtcp($IP, $port, "L\r");
	}
	else if (isset($_POST['stop'])) {
		txtcp($IP, $port, "A\r");
	}
	else if (isset($_POST['cw'])) {
		txtcp($IP, $port, "R\r");
	}?>
</form>



</body>
<head>
	<title><? echo $rotname ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="styles.css" rel="stylesheet" type="text/css">
	<script src="netteForms.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<meta http-equiv="refresh" content="60"> 
</head>
</html>
