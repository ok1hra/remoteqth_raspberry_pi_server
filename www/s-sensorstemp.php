<!doctype html>
<html>
<head>
	<title><? include '../cfg/s-login-note';?> | Sensors set</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="styles.css" rel="stylesheet" type="text/css">
	<script src="netteForms.js"></script>
</head>
<body scroll="no">
<div id="obsah">
<!-- #################### Obsah #################### -->
<span style="position: absolute; top: 15px; left: 400px; z-index: auto"><img src="s-sensors.png"></span>
<p class="text2">More about settings in <a href="http://remoteqth.com/wiki/index.php?page=Sensors" target="_blank" class="external">Wiki</a></p>

<?php
require 'function.php';
require 'Nette/loader.php';
use Nette\Forms\Form,
//	Nette\Diagnostics\Debugger,
	Nette\Utils\Html;
////debugger::enable();
//$configurator = new Nette\Config\Configurator; 
//$configurator->setDebugMode(TRUE);
//$configurator->enableDebugger(__DIR__ . '/../log');

$path = "../cfg/";
$warn = '';
$label1 = Html::el()->setHtml('<a href="s-sensors-i2c.php" onclick="window.open( this.href, this.href, \'width=550,height=270,left=0,top=0,menubar=no,location=no,status=no\' ); return false;"  title="i2c">Find device <img src="split.png" alt="split window"></a>');
$label2 = Html::el()->setHtml('shown in control section');


$pocettemp = rxfile("s-sensors-temps");

$formpocettemp = new Form('pocettemp');
$formpocettemp->setMethod('POST');
$formpocettemp->addGroup("Thermometers");
	$pocettempx = array(
		'0' => 'DISABLE',
		'1' => 'One',
		'2' => 'Two',
		'3' => 'Three',
		'4' => 'Four',
		'5' => 'Five',
		'6' => 'Six',
		'7' => 'Seven',
		'8' => 'Eight',
		);
	$formpocettemp->addSelect('pocettemp', 'Number of temp sensor', $pocettempx)
		->setAttribute('onchange', 'submit()')
		->setDefaultValue($pocettemp);
	if ($formpocettemp->isSuccess()) {
		$values = $formpocettemp->getValues();
		$pocettemp = $values->pocettemp;
		txfile('s-sensors-temps', $values->pocettemp);
	}

$formtemp = new Form('temp');
$formtemp->setMethod('POST');
// generovani formulare
for ($temp = 1; $temp < $pocettemp+1; ++$temp){
	// jmena poli
	$tempid = "temp".$temp;
	$tempn = "temp".$temp."n";
	// formular
	$formtemp->addGroup("Temp$temp");
		$formtemp->addText($tempid, 'I2C adress:', 2)
			->setOption('description', $label1)
			->setRequired('Add Temp i2c adress')
			//->addRule(Form::LENGTH, 'Temp1 i2c address must be %d characters', 3)
			->addRule(Form::PATTERN, 'Temp i2c adress value must be Hexadecimal or n/a for Off', '[0-9a-fA-F]{2}|n/a');
		$formtemp->addText($tempn, "Temp{$temp} name:", 8, 25)
			->setRequired(TRUE)
			->setOption('description', $label2)
			->addRule(Form::MAX_LENGTH, 'Device name for Temp 1 can have maximum %d characters', 25);
	// nacteni default (predchozich) hodnot
	// jmena poli se naplni hodnotou pole
	$formtemp->setDefaults(array(
		$tempid => rxfile("s-sensors-temp{$temp}"),
		$tempn => rxfile("s-sensors-temp{$temp}n"),
	));
}

$formtemp->addSubmit('submit', 'Apply');

if ($formtemp->isSuccess()) {
	txfile('s-sensors-temps', $pocettemp);

	$values = $formtemp->getValues();
	for ($temp = 1; $temp < $pocettemp+1; ++$temp) {
		// jmena poli
		$tempid = "temp".$temp;
		$tempn = "temp".$temp."n";
		// ulozeni promennych
		txfile("s-sensors-temp{$temp}", $values->$tempid);
		txfile("s-sensors-temp{$temp}n", $values->$tempn);
	}
}
 
echo $formpocettemp;
echo $formtemp;
?>

<p class="next"><a href="s-relay.php"><img src="previous.png" alt="previous page"></a><a href="s-sensorsad.php"><img src="next.png" alt="next page"></a></p>

<!-- #################### /Obsah #################### -->
</div>
<div id="header"><?php include 'header.html';?></div>
</div><div id="leftmenu"><?php include 'menu.html';?></div>
</body>
</html>

