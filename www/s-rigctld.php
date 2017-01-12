<!doctype html>
<html>
<head>
	<title><? include '../cfg/s-login-note';?> | Rig control set</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="styles.css" rel="stylesheet" type="text/css">
	<script src="netteForms.js"></script>
</head>
<body scroll="no">
<div id="obsah">
<!-- #################### Obsah #################### -->
<span style="position: absolute; top: 30px; left: 300px; z-index: 0"><img src="s-ser2net.png"></span>
<p class="text2">More about settings in <a href="http://remoteqth.com/wiki/index.php?page=Rig+control+daemon" target="_blank" class="external">Wiki</a></p>

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

//$path = "../cfg/";
$warn = '';
$rigctld = rxfile2("../cfg/s-rigctld-on");
$model = rxfile2("../cfg/s-rigctld-model");
$baud = rxfile2("../cfg/s-rigctld-baud");
$civ = rxfile2("../cfg/s-rigctld-civ");
$idv = rxfile2("../cfg/s-rigctld-idv");
$idp = rxfile2("../cfg/s-rigctld-idp");
$sn = rxfile2("../cfg/s-rigctld-sn");
$dev = rxfile2("../cfg/s-rigctld-dev");
$devpath = rxfile2("../cfg/s-rigctld-devpath");

$form1 = new Form('prvni');
$form1->setMethod('POST');
$form1->addGroup("Rig Control daemon");
	$pocetx = array(
		'0' => 'DISABLE',
		'1' => 'ENABLE',
		);
	$form1->addSelect('pocet', '', $pocetx)
		->setAttribute('onchange', 'submit()')
		->setDefaultValue($rigctld);
	if ($form1->isSuccess()) {
		$values = $form1->getValues();
		$rigctld = $values->pocet;
		txfile2("../cfg/s-rigctld-on", $values->pocet);
	}

$form = new Form('druhy');
$form->setMethod('POST');
// generovani formulare

if ( rxfile2("../cfg/s-rigctld-on") == "1" ) {
	// formular
	$form->addGroup("RIG");
	$label1 = Html::el()->setHtml('<a href="s-riglist.php" onclick="window.open( this.href, this.href, \'width=800,height=600,left=0,top=0,menubar=no,location=no,status=no\' ); return false;"  title="i2c">List Rig # <img src="split.png" alt="split window"></a>');
	$label2 = Html::el()->setHtml('hexadecimal (CI-V used for models from 300 to 500)');
	$form->addText('model', 'Model:')
		->setOption('description', $label1)
		->setRequired("Add model number")
		->addRule(Form::INTEGER, "Model must be number")
		->addRule(Form::RANGE, "Model value must be from %d to %d", array(1, 2901));
	$baudx = array(
		'300' => '300 baud',
		'1200' => '1200 baud',
		'2400' => '2400 baud',
		'4800' => '4800 baud',
		'9600' => '9600 baud',
		'19200' => '19200 baud',
		'38400' => '38400 baud',
		'57600' => '57600 baud',
		'115200' => '115200 baud',
		);
		$form->addSelect('baud', 'Baudrate:', $baudx);
	$form->addText('civ', 'Icom CI-V adr:')
		->setOption('description', $label2);

	$devx = array(
		'0' => 'exactly by IDs (recommended)',
		'1' => 'by relative Device path',
		);
		if ( rxfile2("../cfg/s-rigctld-dev") == "0" ) {
			$label = Html::el()->setHtml('<a href="s-usb.php" onclick="window.open( this.href, this.href,
				\'width=800,height=600,left=0,top=0,menubar=no,location=no,status=no\' ); return false;"
				title="i2c">Find USB by id\'s and sn <img src="split.png" alt="split window"></a>');
		}
		elseif ( rxfile2("../cfg/s-rigctld-dev") == "1" ) {
			$label = Html::el()->setHtml('<a href="s-usb2.php" onclick="window.open( this.href, this.href,
				\'width=400,height=300,left=0,top=0,menubar=no,location=no,status=no\' ); return false;"
				title="i2c">Find USB by bus path <img src="split.png" alt="split window"></a>');
		}else{$label = '';}
		$form->addSelect('dev', 'Detect device:', $devx)
			->setOption('description', $label)
			->setAttribute('onchange', 'submit()');
		//	->setDefaultValue('dev');
		if ($form->isSuccess()) {
			$values = $form->getValues();
			//$comdev = $values->pocet;
			txfile2("../cfg/s-rigctld-dev", $values->dev);
		}
	
		if ( rxfile2("../cfg/s-rigctld-dev") == "0" ) {
			$form->addText('idv', 'USB Vendor id:')
				->setRequired(TRUE)
				->setRequired("Add USB Vendor ID")
				->addRule(Form::LENGTH, "USB Vendor ID must have at %d characters", 4);
			$form->addText('idp', 'USB Product id:')
				->setRequired(TRUE)
				->setRequired("Add USB Product ID")
				->addRule(Form::LENGTH, "USB Product ID must have at %d characters", 4);
			$form->addText('sn', 'USB sn:')
				->setRequired(TRUE)
				->setRequired("Add USB sn")
				->addRule(Form::MIN_LENGTH, "USB sn must have at least %d characters", 1)
				->addRule(Form::MAX_LENGTH, "USB sn can have maximum %d characters", 8);
		}
		if ( rxfile2("../cfg/s-rigctld-dev") == "1" ) {
			$label2 = Html::el()->setHtml('<span style="color:#f00">device path is relative - depends on the overall configuration of a USB hub, after <b>change any part</b> can be changed!</span>');
			$form->addText('devpath', 'Device name:')
				->setRequired(TRUE)
				->setOption('description', $label2)
				->addRule(Form::PATTERN, "Device name must be in format 'ttyUSB.#-#.(#|#:#).#'", 'ttyUSB\.[0-9]\-[0-9]\.([0-9]|[0-9]\-[0-9])\.[0-9]');
		}
	
	// nacteni default (predchozich) hodnot
	// jmena poli se naplni hodnotou pole
	$form->setDefaults(array(
		'model' => $model, 'baud' => $baud, 'civ' => $civ, 'idv' => $idv, 'idp' => $idp, 'sn' => $sn, 'dev' => $dev, 'devpath' => $devpath,
	));
}

$form->addSubmit('submit', 'Apply');

if ($form->isSuccess()) {
	$warn = "For detection new USB2serial interface repluged your usb device or reboot.<br>
	You can observe the result on the <a href=\"index.php\">Recognized USB devices.</a></p>";

	$values = $form->getValues();
	// ulozeni promennych
	txfile2("../cfg/s-rigctld-model", $values->model);
	txfile2("../cfg/s-rigctld-baud", $values->baud);
	txfile2("../cfg/s-rigctld-civ", $values->civ);
	if ( rxfile2("../cfg/s-rigctld-dev") == "0" ) {
		txfile2("../cfg/s-rigctld-idv", $values->idv);
		txfile2("../cfg/s-rigctld-idp", $values->idp);
		txfile2("../cfg/s-rigctld-sn", $values->sn);
	}
	if ( rxfile2("../cfg/s-rigctld-dev") == "1" ) {
		txfile2("../cfg/s-rigctld-dev", $values->dev);
		txfile2("../cfg/s-rigctld-devpath", $values->devpath);
	}
	exec('../script/udev.sh > /etc/udev/rules.d/99-remoteqth.rules');
	exec('sudo udevadm control --reload-rules');
} ?>

<? echo $form1;
echo $form; ?>

<p class="warn"><?php echo $warn ?></p>

<!-- <form action="s-rigctld.php" method="POST"><input type="submit" name="restart" value="Restart rigctld"></form>
<? if (isset($_POST['restart'])) {
	exec('sudo ../script/rig.sh restart');
} ?> -->

<p class="next"><a href="s-ser2net.php"><img src="previous.png" alt="previous page"></a><a href="s-band-decoder.php"><img src="next.png" alt="next page"></a></p>

<!-- #################### /Obsah #################### -->
</div>
<div id="header"><?php include 'header.html';?></div>
</div><div id="leftmenu"><?php include 'menu.html';?></div>
</body>
</html>
