<!doctype html>
<html>
<head>
	<title><? include '../cfg/s-login-note';?> | Watchdog ping</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="styles.css" rel="stylesheet" type="text/css">
	<script src="netteForms.js"></script>
</head>
<body scroll="no">
<div id="obsah">
<!-- #################### Obsah #################### -->
<span style="position: absolute; top: 25px; left: 400px; z-index: 0"><img src="s-band.png"></span>
<p class="text2">More about settings in <a href="http://remoteqth.com/wiki/index.php?page=Band+decoder" target="_blank" class="external">Wiki</a></p>

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
$rigctld = rxfile("s-rigctld-on");
$bandon = rxfile("s-band-on");
$nrgpio = '15';

//if ( $rigctld == "0" ) {
//	echo '<br><h1>Band decoder</h1><p class="warn">Must first configure <a href="s-rigctld.php">rigctld.</a>';
//}
//else if ( $rigctld == "1" ) {
	$label1 = Html::el()->setHtml('Shown only disable relay (with <b>n/a</b> name in <a href="s-relay.php">relay settings</a>)');
	$label2 = Html::el()->setHtml('Set after <a href="s-rigctld.php">rigctld</a> shown <a href="freq.php" onclick="window.open( this.href, this.href, \'width=450,height=70,left=0,top=0,menubar=no,location=no,status=no\' ); return false;"  title="freq">frequency <img src="split.png" alt="split window"></a>.<br><br>');
	$label3 = Html::el()->setHtml('kHz');
	$label4 = Html::el()->setHtml('m');

	$form1 = new Form('prvni');
	$form1->setMethod('POST');
	$form1->addGroup("Band decoder daemon (read from rigctld)")
		->setOption('description', $label2);
		$pocetx = array(
			'0' => 'DISABLE',
			'1' => 'ENABLE',
			);
		$form1->addSelect('pocet', '', $pocetx)
			->setOption('description', $label1)
			->setAttribute('onchange', 'submit()')
			->setDefaultValue($bandon);
		if ($form1->isSuccess()) {
			$values = $form1->getValues();
			$bandon = $values->pocet;
			txfile('s-band-on', $values->pocet);
			if ( $bandon == "0" ) {
				exec('../script/band.sh stop > /dev/null 2>&1 &');
			}
		}

	if ( rxfile("s-band-on") == "1" ) {	
		$form = new Form('druhy');
		$form->setMethod('POST');

		// generovani formulare
		for ($gpio = 1; $gpio < $nrgpio+1; ++$gpio){
			if ( rxfile("s-relay-{$gpio}") == "n/a" ) {	
				// jmena poli
				$name = "freq".$gpio."name";
				$enable = "freq".$gpio."enable";
				$freqfrom = "freq".$gpio."from";
				$freqto = "freq".$gpio."to";
				// formular
				$form->addGroup("Relay$gpio");
				$form->addCheckbox($enable, 'Enable for band decoder')
					->setAttribute('onchange', 'submit()');

					if ($form->isSuccess()) {
						$values = $form->getValues();
						txfile("s-band{$gpio}-on", $values->$enable);
					}
				if ( rxfile("s-band{$gpio}-on") == "1" ) {
					$form->addText($name, "Displays band $gpio:")
						->setRequired("Band $gpio")
						->setOption('description', $label4)
						->addRule(Form::INTEGER, "Band $gpio must be number")
						->addRule(Form::RANGE, "Band $gpio value must be from %d to %d", array(0, 160));
					$form->addText($freqfrom, 'From freq:')
						->setOption('description', $label3)
						->setRequired("Add from  freq relay$gpio")
						->addRule(Form::INTEGER, "Freq relay$gpio must be number")
						->addRule(Form::RANGE, "Freq relay$gpio value must be from %d to %d", array(1, 999999999));
					$form->addText($freqto, 'To freq:')
						->setOption('description', $label3)
						->setRequired("Add to freq relay$gpio")
						->addRule(Form::INTEGER, "Freq relay$gpio must be number")
						->addRule(Form::RANGE, "Freq relay$gpio value must be from %d to %d", array(1, 999999999));
			
					// nacteni default (predchozich) hodnot
					// jmena poli se naplni hodnotou pole
					$form->setDefaults(array(
						$name => rxfile("s-band{$gpio}-name"),
						$enable => rxfile("s-band{$gpio}-on"),
						$freqfrom => rxfile("s-band{$gpio}-from"),
						$freqto => rxfile("s-band{$gpio}-to"),
					));
				}
			}
		}
		
		$form->addSubmit('submit', 'Apply form & restart daemon');
	
		if ($form->isSuccess()) {
			//$warn = "For detection new USB2serial interface repluged your usb device or reboot.<br>";
		
			$values = $form->getValues();
			for ($gpio = 1; $gpio < $nrgpio+1; ++$gpio){
				// jmena poli
				$name = "freq".$gpio."name";
				//$enable = "freq".$gpio."enable";
				$freqfrom = "freq".$gpio."from";
				$freqto = "freq".$gpio."to";
				// ulozeni promennych
				txfile("s-band{$gpio}-name", $values->$name);
				//txfile("s-band{$gpio}-on", $values->$enable);
				txfile("s-band{$gpio}-from", $values->$freqfrom);
				txfile("s-band{$gpio}-to", $values->$freqto);
			}
			exec('../script/band.sh restart > /dev/null 2>&1 &');
		}
	}
//} ?>

<p class="warn"><?php echo $warn ?></p>

<? echo $form1;
echo $form; ?>

<p class="warn"><?php echo $warn ?></p>

<!-- <form action="s-rigctld.php" method="POST"><input type="submit" name="restart" value="Restart rigctld"></form>
<? if (isset($_POST['restart'])) {
	exec('sudo ../script/rig.sh restart');
} ?> -->

<p class="next"><a href="s-rigctld.php"><img src="previous.png" alt="previous page"></a><a href="s-webcam.php"><img src="next.png" alt="next page"></a></p>

<!-- #################### /Obsah #################### -->
</div>
<div id="header"><?php include 'header.html';?></div>
</div><div id="leftmenu"><?php include 'menu.html';?></div>
</body>
</html>
