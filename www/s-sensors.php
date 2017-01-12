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
<span style="position: absolute; top: 50px; left: 400px; z-index: auto"><img src="s-sensors.png"></span>
<p class="text2">More about settings in <a href="http://remoteqth.com/wiki/index.php?page=Sensors" target="_blank" class="external">Wiki</a></p>
<p class="text2"><a href="s-sensors-i2c.php" onclick="window.open( this.href, this.href, 'width=550,height=270,left=0,top=0,menubar=no,location=no,status=no' ); return false;"  title="i2c">Find I2C device address <img src="split.png" alt="split window"></a></p>

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

$pocetad = rxfile("s-sensors-ad");

$formpocetad = new Form('pocetad');
$formpocetad->setMethod('POST');
$formpocetad->addGroup("A/D converter (voltmeters)");
	$pocetx = array(
		'0' => 'DISABLE',
		'1' => 'One',
		'2' => 'Two',
		);
	$formpocetad->addSelect('pocet', 'Number of A/D', $pocetx)
		->setAttribute('onchange', 'submit()')
		->setDefaultValue($pocetad);
	if ($formpocetad->isSuccess()) {
		$values = $formpocetad->getValues();
		$pocetad = $values->pocet;
		txfile('s-sensors-ad', $values->pocet);
	}

$formad = new Form('ad');
$formad->setMethod('POST');
// generovani formulare
for ($ad = 1; $ad < $pocetad+1; ++$ad){
	// jmena poli
	$id = "ad".$ad;
	$name1 = "ad".$ad."n1";
	$name2 = "ad".$ad."n2";
	$name3 = "ad".$ad."n3";
	$name4 = "ad".$ad."n4";
	$coefficient1 = "ad".$ad."c1";
	$coefficient2 = "ad".$ad."c2";
	$coefficient3 = "ad".$ad."c3";
	$coefficient4 = "ad".$ad."c4";
	// formular
	$formad->addGroup("AD$ad");
		$formad->addText($id, 'I2C adress:', 2)
			->setRequired(TRUE)
			->setRequired('Add Converter1 i2c adress')
			->addRule(Form::LENGTH, 'Converter1 i2c address must be %d characters', 2)
			->addRule(Form::PATTERN, 'Converter1 i2c adress value must be Hexadecimal or -- for Off', '[0-9a-fA-F]{2}|--');
		$formad->addText($name1, 'Voltage 1 name:')
			->setRequired(TRUE)
			->addRule(Form::MAX_LENGTH, 'Device name for a/d 1 can have maximum %d characters', 25);
		$formad->addText($coefficient1, 'Volt 1 coefficient:')
			->setRequired(TRUE)
			->addRule(Form::FLOAT, 'coefficient must be number', 10);
		$formad->addText($name2, 'Voltage 2 name:')
			->setRequired(TRUE)
			->addRule(Form::MAX_LENGTH, 'Device name for a/d 1 can have maximum %d characters', 25);
		$formad->addText($coefficient2, 'Volt 2 coefficient:')
			->setRequired(TRUE)
			->addRule(Form::FLOAT, 'coefficient must be number', 10);
		$formad->addText($name3, 'Voltage 3 name:')
			->setRequired(TRUE)
			->addRule(Form::MAX_LENGTH, 'Device name for a/d 1 can have maximum %d characters', 25);
		$formad->addText($coefficient3, 'Volt 3 coefficient:')
			->setRequired(TRUE)
			->addRule(Form::FLOAT, 'coefficient must be number', 10);
		$formad->addText($name4, 'Voltage 4 name:')
			->setRequired(TRUE)
			->addRule(Form::MAX_LENGTH, 'Device name for a/d 1 can have maximum %d characters', 25);
		$formad->addText($coefficient4, 'Volt 4 coefficient:')
			->setRequired(TRUE)
			->addRule(Form::FLOAT, 'coefficient must be number', 10);
	// nacteni default (predchozich) hodnot
	// jmena poli se naplni hodnotou pole
	$formad->setDefaults(array(
		$id => rxfile("s-sensors-ad{$ad}"),
		$name1 => rxfile("s-sensors-ad{$ad}n1"),
		$name2 => rxfile("s-sensors-ad{$ad}n2"),
		$name3 => rxfile("s-sensors-ad{$ad}n3"),
		$name4 => rxfile("s-sensors-ad{$ad}n4"),
		$coefficient1 => rxfile("s-sensors-ad{$ad}c1"),
		$coefficient2 => rxfile("s-sensors-ad{$ad}c2"),
		$coefficient3 => rxfile("s-sensors-ad{$ad}c3"),
		$coefficient4 => rxfile("s-sensors-ad{$ad}c4"),
	));
}

$formad->addSubmit('submit', 'Apply');

if ($formad->isSuccess()) {
	txfile('s-sensors-ad', $pocetad);

	$values = $formad->getValues();
	for ($ad = 1; $ad < $pocetad+1; ++$ad) {
		// jmena poli
		$id = "ad".$ad;
		$name1 = "ad".$ad."n1";
		$name2 = "ad".$ad."n2";
		$name3 = "ad".$ad."n3";
		$name4 = "ad".$ad."n4";
		$coefficient1 = "ad".$ad."c1";
		$coefficient2 = "ad".$ad."c2";
		$coefficient3 = "ad".$ad."c3";
		$coefficient4 = "ad".$ad."c4";
		// ulozeni promennych
		txfile("s-sensors-ad{$ad}", $values->$id);
		txfile("s-sensors-ad{$ad}n1", $values->$name1);
		txfile("s-sensors-ad{$ad}n2", $values->$name2);
		txfile("s-sensors-ad{$ad}n3", $values->$name3);
		txfile("s-sensors-ad{$ad}n4", $values->$name4);
		txfile("s-sensors-ad{$ad}c1", $values->$coefficient1);
		txfile("s-sensors-ad{$ad}c2", $values->$coefficient2);
		txfile("s-sensors-ad{$ad}c3", $values->$coefficient3);
		txfile("s-sensors-ad{$ad}c4", $values->$coefficient4);
	}
}

////////////////////////////////////////////////////////////////////////////////

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
			->setRequired('Add Temp i2c adress')
			//->addRule(Form::LENGTH, 'Temp1 i2c address must be %d characters', 3)
			->addRule(Form::PATTERN, 'Temp i2c adress value must be Hexadecimal or n/a for Off', '[0-9a-fA-F]{2}|n/a');
		$formtemp->addText($tempn, "Temp{$temp} name:", 8, 25)
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
 
echo $formpocetad;
echo $formad; 
echo $formpocettemp;
echo $formtemp;
?>

<p class="next"><a href="s-relay.php"><img src="previous.png" alt="previous page"></a><a href="s-rot.php"><img src="next.png" alt="next page"></a></p>

<!-- #################### /Obsah #################### -->
</div>
<div id="header"><?php include 'header.html';?></div>
</div><div id="leftmenu"><?php include 'menu.html';?></div>
</body>
</html>

