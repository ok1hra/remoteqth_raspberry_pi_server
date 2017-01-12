<!doctype html>
<html>
<head>
	<title><? include '../cfg/s-login-note';?> | FSK set</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="styles.css" rel="stylesheet" type="text/css">
	<script src="netteForms.js"></script>
</head>
<body scroll="no">
<div id="obsah">
<!-- #################### Obsah #################### -->
<span style="position: absolute; top: 45px; left: 380px; z-index: 0"><img src="rtty.png"></span>
<p class="text2">More about settings in <a href="http://remoteqth.com/wiki/index.php?page=RTTY+settings" target="_blank" class="external">Wiki</a></p>

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

$warn = '';
$path = "../cfg/";
//$nrmem = 6;                      //pocet pameti
$fsk2serial = 'fsk2serial';
$fsk = rxfile('s-fsk');
$memories = rxfile('s-fsk-mem');
$label1 = Html::el()->setHtml('<a href="http://remoteqth.com/serial2fsk.php" target="_blank">FSK interface</a> must be connect on usb, and configured in <a href="s-ser2net.php" target="_blank">ser2net<img src="split.png" alt="split window"></a> section.');


$form1 = new Form('prvni');
$form1->setMethod('POST');
$form1->addGroup("FSK device");
	$pocetx = array(
		'0' => 'DISABLE',
		'1' => 'FSK interface for Arduino',
		);
	$form1->addSelect('pocet', 'Select', $pocetx)
		->setAttribute('onchange', 'submit()')
		->setDefaultValue($fsk);
	if ($form1->isSuccess()) {
		$values = $form1->getValues();
		$fsk = $values->pocet;
		txfile('s-fsk', $values->pocet);
	}

$form2 = new Form('druhy');
$form2->setMethod('POST');
	if ( $fsk == "1" ) {
		$form2->addGroup('FSK interface for Arduino')
			->setOption('description', ' Redirect listen UDP port 7891 to ser2net TCP port.');
			$form2->addText('fsk2serial', 'ser2net TCP port:')
				->setOption('description', $label1)
				->setRequired('Add TCP port')
				->addRule(Form::INTEGER, 'TCP port must be number')
				->addRule(Form::RANGE, 'TCP port value must be from %d to %d', array(101, 65535));

		$form2->setDefaults(array(
			$fsk2serial => rxfile('s-fsk2serial'),
		));
	}

if ( $fsk != "0" ) {

	$form2->addGroup("FSK memory")
		->setOption('description', '');
		$form2->addText('memories', "Number of memories:")
			->setRequired('Set the number of memories')
			->addRule(Form::INTEGER, 'Must be number')
			->addRule(Form::RANGE, 'The number of memories value must be from %d to %d', array(1, 16))
			->setAttribute('onchange', 'submit()')
			->setDefaultValue($memories);
		if ($form2->isSuccess()) {
			$values = $form2->getValues();
			$memories = $values->memories;
			txfile('s-fsk-mem', $values->memories);
		}

	// generovani formulare
	for ($mem = 1; $mem < $memories+1; ++$mem){
		// jmena poli
		$fskmem = "fskmem".$mem;
		$fskmemc = "fskmem".$mem."c";
		// formular
			$form2->addText($fskmem, "Mem$mem name:")
				->setRequired(TRUE)
				->addRule(Form::MAX_LENGTH, "FSK mem $mem name can have maximum %d characters", 6);
			$form2->addText($fskmemc, "Mem$mem content:")
				->setRequired(TRUE)
				->addRule(Form::MAX_LENGTH, "FSK mem $mem content can have maximum %d characters", 60);
		// nacteni default (predchozich) hodnot
		// jmena poli se naplni hodnotou pole
		$form2->setDefaults(array(
			$fskmem => rxfile("s-fsk-mem{$mem}"),
			$fskmemc => rxfile("s-fsk-mem{$mem}c"),
		));
	}
	$form2->addSubmit('submit', 'Apply');
}



if ($form2->isSuccess()) {
	if ( $fsk == "1" ) {
		$warn = "For detection new USB2serial interface repluged your usb device or reboot.<br>
		You can observe the result on the <a href=\"index.php\">Recognized USB devices.</a></p>";

		$values = $form2->getValues();
			$fsk2serial = 'fsk2serial';
		txfile("s-fsk2serial", $values->$fsk2serial);
		exec('../script/udev.sh > /etc/udev/rules.d/99-remoteqth.rules');
		exec('sudo udevadm control --reload-rules');
	}
	$values = $form2->getValues();
	for ($mem = 1; $mem < $memories+1; ++$mem) {
		// jmena poli
		$fskmem = "fskmem{$mem}" ;
		$fskmemc = "fskmem{$mem}c";
		// ulozeni promennych
		txfile("s-fsk-mem{$mem}", $values->$fskmem);
		txfile("s-fsk-mem{$mem}c", $values->$fskmemc);
	}
}

echo $form1;
echo $form2;

?>

<p class="warn"><?php echo $warn ?></p>
<p class="next"><a href="s-cw.php"><img src="previous.png" alt="previous page"></a><a href="s-ser2net.php"><img src="next.png" alt="next page"></a></p>


<!-- #################### /Obsah #################### -->
</div>
<div id="header"><?php include 'header.html';?></div>
</div><div id="leftmenu"><?php include 'menu.html';?></div>
</body>
</html>

