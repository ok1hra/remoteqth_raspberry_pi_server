<!doctype html>
<html>
<head>
	<title><? include '../cfg/s-login-note';?> | Sensors</title>
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
$pocetad = rxfile("s-sensors-ad");
$label1 = Html::el()->setHtml('<a href="s-sensors-i2c.php" onclick="window.open( this.href, this.href, \'width=550,height=270,left=0,top=0,menubar=no,location=no,status=no\' ); return false;"  title="i2c">Find device <img src="split.png" alt="split window"></a>');
$label2 = Html::el()->setHtml('shown in control section (n/a for disable)');
$label3 = Html::el()->setHtml('which multiplied by the raw data');
$label4 = Html::el()->setHtml('for determining the extent of the displayed color');


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
	// formular
	$formad->addGroup("AD$ad");
		$formad->addText($id, 'I2C adress:', 2)
			->setRequired(TRUE)
			->setOption('description', $label1)
			->setRequired('Add Converter1 i2c adress')
			->addRule(Form::LENGTH, 'Converter1 i2c address must be %d characters', 2)
			->addRule(Form::PATTERN, 'Converter1 i2c adress value must be Hexadecimal or -- for Off', '[0-9a-fA-F]{2}|--');
		//ctyri vstupy
		for ($count = 1; $count < 5; ++$count){
			// jmena poli
			$name = "ad".$ad."n".$count;
			$coefficient = "ad".$ad."c".$count;
			$from = "ad".$ad."f".$count;
			$to = "ad".$ad."t".$count;
			// formular 2. cast
			$formad->addText($name, "Sensor{$count} name:")
				->setRequired(TRUE)
				->setOption('description', $label2)
				->setRequired('Choose Sensor name')
				->addRule(Form::MAX_LENGTH, 'Device name for a/d can have maximum %d characters', 25);
			$formad->addText($coefficient, 'Coefficient:', 6)
				->setRequired(TRUE)
				->setOption('description', $label3)
				->addRule(Form::FLOAT, 'coefficient must be number', 6);
			$formad->addText($from, 'From:', 4)
				->setRequired(TRUE)
				->setOption('description', $label4)
				->addRule(Form::FLOAT, 'coefficient must be number', 6);
			$formad->addText($to, 'To:', 4)
				->setRequired(TRUE)
				->setOption('description', $label4)
				->addRule(Form::FLOAT, 'coefficient must be number', 6);
			// nacteni default (predchozich) hodnot
			// jmena poli se naplni hodnotou pole
			$formad->setDefaults(array(
				$id => rxfile("s-sensors-ad{$ad}"),
				$name => rxfile("s-sensors-ad{$ad}n{$count}"),
				$coefficient => rxfile("s-sensors-ad{$ad}c{$count}"),
				$from => rxfile("s-sensors-ad{$ad}f{$count}"),
				$to => rxfile("s-sensors-ad{$ad}t{$count}"),
			));
		}
}

$formad->addSubmit('submit', 'Apply');

if ($formad->isSuccess()) {
	txfile('s-sensors-ad', $pocetad);

	$values = $formad->getValues();
	// dva a/d
	for ($ad = 1; $ad < $pocetad+1; ++$ad) {
		$id = "ad".$ad;
		txfile("s-sensors-ad{$ad}", $values->$id);
		// ctyri vstupy
		for ($count = 1; $count < 5; ++$count){
			// jmena poli
			$name = "ad".$ad."n".$count;
			$coefficient = "ad".$ad."c".$count;
			$from = "ad".$ad."f".$count;
			$to = "ad".$ad."t".$count;
			// ulozeni promennych
			txfile("s-sensors-ad{$ad}n{$count}", $values->$name);
			txfile("s-sensors-ad{$ad}c{$count}", $values->$coefficient);
			txfile("s-sensors-ad{$ad}f{$count}", $values->$from);
			txfile("s-sensors-ad{$ad}t{$count}", $values->$to);
		}
	}
}
 
echo $formpocetad;
echo $formad; 
?>

<p class="next"><a href="s-sensorstemp.php"><img src="previous.png" alt="previous page"></a><a href="s-rot.php"><img src="next.png" alt="next page"></a></p>

<!-- #################### /Obsah #################### -->
</div>
<div id="header"><?php include 'header.html';?></div>
</div><div id="leftmenu"><?php include 'menu.html';?></div>
</body>
</html>

