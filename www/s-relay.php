<!doctype html>
<html>
<head>
	<title><? include '../cfg/s-login-note';?> | Relay set</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="styles.css" rel="stylesheet" type="text/css">
	<script src="netteForms.js"></script>
</head>
<body scroll="no">
<div id="obsah">
<!-- #################### Obsah #################### -->
<span style="position: absolute; top: 20px; left: 400px; z-index: auto"><img src="s-relay.png"></span>
<p class="text2">More about settings in <a href="http://remoteqth.com/wiki/index.php?page=Web+relay" target="_blank" class="external">Wiki</a></p>

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

gethw();
if ( $hw == "PI" ) {
	$rev = rpi2rev();
//	if ($rev < 16) {  // B+ detection
	if (preg_match('/00(02|03|0[456]|0[789]|0[def]|11)/', $rev)){
		$nrgpio = '15';
	}else{
		$nrgpio = '24';
	}
	$alert = "<pre class=\"info\">Detecting RPI rev. $rev - <b>$nrgpio GPIO</b></pre>";
}elseif ( $hw == "BBB" ) {
	$nrgpio = '28';
	$alert = "<pre class=\"info\">Detecting BeagleboneBlack <b>$nrgpio GPIO</b></pre>";
}else 	{
	$alert = "Detecting hardware \n$hw" ;
	$nrgpio = '8';
}

#$path = "../cfg/";
$warn = '';
$webcamon = rxfile2("../cfg/s-webcam-on");

$form = new Form('prvni');
$form->setMethod('POST');
$form->addGroup('Web relay\'s Name');
// generovani formulare
for ($gpio = 1; $gpio < $nrgpio+1; ++$gpio){
	// jmena poli
	$relayname = "name".$gpio;
	$switch = "sw".$gpio;
	$switchb = "swb".$gpio;
	$aoff = "aof".$gpio;

	if ( rxfile2("../cfg/s-band-on") == '1' && rxfile2("../cfg/s-band{$gpio}-on") == '1' ) {
		$band = rxfile2("../cfg/s-band{$gpio}-name");
		$label1 = Html::el()->setHtml("<span style=\"color: #f00\">use to <b>{$band}m</b> band</span> - if renaming from <b>n/a</b> decoder disabled");
	}else{
		$label1 = Html::el()->setHtml('<b>n/a</b> for disable or free (available used by Band decoder)');
	}
	if ( $webcamon == "1" && $gpio == "16") {
		$label1 = Html::el()->setHtml("<span style=\"color: #f00\">Webcam enable - the camera will automatically switches this relay each minute during shooting.</span>");
		$form->addText($relayname, "$gpio:")
			->setRequired(TRUE)
			->setOption('description', $label1)
			->addRule(Form::MAX_LENGTH, "Realy{$gpio} name can have maximum %d characters", 25);
	
		// nacteni default (predchozich) hodnot
		// jmena poli se naplni hodnotou pole
		$form->setDefaults(array(
			$relayname => rxfile2("../cfg/s-relay-{$gpio}"),
			$switch => rxfile2("../cfg/s-relay-sw-{$gpio}"),
			$switchb => rxfile2("../cfg/s-relay-swb-{$gpio}"),
			$aoff => rxfile2("../cfg/s-relay-aof-{$gpio}"),
		));
	}else{
		// formular
		$form->addText($relayname, "$gpio:")
			->setRequired(TRUE)
			->setOption('description', $label1)
			->addRule(Form::MAX_LENGTH, "Realy{$gpio} name can have maximum %d characters", 25);
		$form->addCheckbox($switch, 'Changeover switch (BANK-1) - has priority');
		$form->addCheckbox($switchb, 'Changeover switch (BANK-2)');
		$form->addCheckbox($aoff, 'Auto-off (push button)');
	
		// nacteni default (predchozich) hodnot
		// jmena poli se naplni hodnotou pole
		$form->setDefaults(array(
			$relayname => rxfile2("../cfg/s-relay-{$gpio}"),
			$switch => rxfile2("../cfg/s-relay-sw-{$gpio}"),
			$switchb => rxfile2("../cfg/s-relay-swb-{$gpio}"),
			$aoff => rxfile2("../cfg/s-relay-aof-{$gpio}"),
		));
	}
}

$saverelay = rxfile2("../cfg/s-relay-save");

$form2 = new Form('druhy');
$form2->setMethod('POST');
$form2->addGroup("Save relay settings");
	$pocetx = array(
		'0' => 'DISABLE',
		'1' => 'RESTORE',
		);
	$form2->addSelect('pocet', 'Restore the current settings after reboot: ', $pocetx)
		->setAttribute('onchange', 'submit()')
		->setDefaultValue($saverelay);
	if ($form2->isSuccess()) {
		$values = $form2->getValues();
		$saverelay = $values->pocet;
		txfile2('../cfg/s-relay-save', $values->pocet);
	}

$form->addSubmit('submit', 'Apply');

if ($form->isSuccess()) {
	$values = $form->getValues();
	for ($gpio = 1; $gpio < $nrgpio+1; ++$gpio){
		// jmena poli
		$relayname = "name".$gpio;
		$switch = "sw".$gpio;
		$switchb = "swb".$gpio;
		$aoff = "aof".$gpio;
		// ulozeni promennych
		txfile2("../cfg/s-relay-{$gpio}", $values->$relayname);
		txfile2("../cfg/s-relay-sw-{$gpio}", $values->$switch);
		if ( $values->$switch == "1"){			// id bank-1 enable, then bank-2 disable (bank-1 priority)
			txfile2("../cfg/s-relay-swb-{$gpio}", 0);
		}else{
			txfile2("../cfg/s-relay-swb-{$gpio}", $values->$switchb);
		}
		txfile2("../cfg/s-relay-aof-{$gpio}", $values->$aoff);
	}

	$cesta = getcwd();
	// GPIO 21/27 set
	gethw();
	if ( $hw == "PI" ) {
		$rev = rpi2rev();
		//if ( $rev == "0x2" || $rev == "0x3") { //  HEX
		//if ($rev < 4) {                          //  DEC
		if (preg_match('/00(02|03)/', $rev)){
			exec("/bin/ln -sf /sys/class/gpio/gpio21/value $cesta/../cfg/gpio4");
		}
		else {
			exec("/bin/ln -sf /sys/class/gpio/gpio27/value $cesta/../cfg/gpio4");
		}
	}
}
if ( $saverelay == "1" ) {
	$warn = 'Save relay settings will be active,<br>where there is inactive auto-off.';
}
else 	{
}
?>
<p class="text2"><? echo $alert; ?></p>
<? 
echo $form;
echo $form2 ?>
<p class="warn"><?php echo $warn ?></p>

<p class="next"><a href="s-vpn.php"><img src="previous.png" alt="previous page"></a><a href="s-sensorstemp.php"><img src="next.png" alt="next page"></a></p>


<!-- #################### /Obsah #################### -->
</div>
<div id="header"><?php include 'header.html';?></div>
</div><div id="leftmenu"><?php include 'menu.html';?></div>
</body>
</html>

