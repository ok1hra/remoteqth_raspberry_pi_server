<!doctype html>
<html>
<head>
	<title><? include '../cfg/s-login-note';?> | ser2net</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="styles.css" rel="stylesheet" type="text/css">
	<script src="netteForms.js"></script>
</head>
<body scroll="no">
<div id="obsah">
<!-- #################### Obsah #################### -->
<span style="position: absolute; top: 30px; left: 300px; z-index: 0"><img src="s-ser2net.png"></span>
<p class="text2">More about settings in <a href="http://remoteqth.com/wiki/index.php?page=Ser2Net" target="_blank" class="external">Wiki</a></p>

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

$path = "../cfg/";
$warn = '';
$pocetcomu = rxfile2("../cfg/s-ser2net-coms");

$form1 = new Form('prvni');
$form1->setMethod('POST');
$form1->addGroup("Ser2net");
	$pocetx = array(
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
	$form1->addSelect('pocet', 'Number of COM\'s', $pocetx)
		->setAttribute('onchange', 'submit()')
		->setDefaultValue($pocetcomu);
	if ($form1->isSuccess()) {
		$values = $form1->getValues();
		$pocetcomu = $values->pocet;
		txfile('s-ser2net-coms', $values->pocet);
	}

$form = new Form('druhy');
$form->setMethod('POST');
// generovani formulare
for ($com = 1; $com < $pocetcomu+1; ++$com){
	// jmena poli
	$comname = "c".$com."name";
	$comport = "c".$com."port";
	$combaud = "c".$com."baud";
	$comdata = "c".$com."data";
	$comstop = "c".$com."stop";
	$comparity = "c".$com."parity";
	$comidv = "c".$com."idv";
	$comidp = "c".$com."idp";
	$comsn = "c".$com."sn";
	$comdev = "c".$com."dev";
	$comdevpath = "c".$com."devpath";

	// formular
	$form->addGroup("com$com");
	$form->addText($comname, 'Connected device name:')
		->setRequired(TRUE)
		->setRequired("Add name com$com")
		->addRule(Form::MAX_LENGTH, "com$com device name can have maximum %d characters", 25);
	$form->addText($comport, 'Export on IP port:')
		->setRequired(TRUE)
		->setRequired("Add Export on IP port com$com")
		->addRule(Form::INTEGER, "Export on IP port com$com must be number")
		->addRule(Form::RANGE, "IP port com$com value must be from %d to %d", array(101, 65535));
	$c1baudx = array(
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
		$form->addSelect($combaud, 'Baudrate:', $c1baudx);
	$c1datax = array(
		'7DATABITS' => '7',
		'8DATABITS' => '8',
		);
		$form->addSelect($comdata, 'Databits:', $c1datax);
	$c1stopx = array(
		'1STOPBIT' => '1',
		'2STOPBITS' => '2',
		);
		$form->addSelect($comstop, 'Stopbit:', $c1stopx);
	$c1parityx = array(
		'EVEN' => 'Even',
		'ODD' => 'Odd',
		'NONE' => 'None',
		);
		$form->addSelect($comparity, 'Parity:', $c1parityx);
	$c1devx = array(
		'0' => 'exactly by IDs (recommended)',
		'1' => 'by relative Device path',
		);
		if ( rxfile2("../cfg/s-ser2net-c{$com}dev") == "0" ) {
			$label = Html::el()->setHtml('<a href="s-usb.php" onclick="window.open( this.href, this.href, \'width=800,height=600,left=0,top=0,menubar=no,location=no,status=no\' ); return false;"  title="i2c">Find USB by id\'s and sn <img src="split.png" alt="split window"></a>');
		}
		elseif ( rxfile2("../cfg/s-ser2net-c{$com}dev") == "1" ) {
			$label = Html::el()->setHtml('<a href="s-usb2.php" onclick="window.open( this.href, this.href, \'width=400,height=300,left=0,top=0,menubar=no,location=no,status=no\' ); return false;"  title="i2c">Find USB by bus path <img src="split.png" alt="split window"></a>');
		}else{$label = '';}
		$form->addSelect($comdev, 'Detect device:', $c1devx)
			->setOption('description', $label)
			->setAttribute('onchange', 'submit()');
//			->setDefaultValue($comdev);
		if ($form->isSuccess()) {
			$values = $form->getValues();
			//$comdev = $values->pocet;
			txfile2("../cfg/s-ser2net-c{$com}dev", $values->$comdev);
		}
	if ( rxfile2("../cfg/s-ser2net-c{$com}dev") == "0" ) {
		$form->addText($comidv, 'USB Vendor id:')
			->setRequired(TRUE)
			->setRequired("Add USB Vendor ID for com$com")
			->addRule(Form::LENGTH, "USB Vendor ID for com$com must have at %d characters", 4);
		$form->addText($comidp, 'USB Product id:')
			->setRequired(TRUE)
			->setRequired("Add USB Product ID for com$com")
			->addRule(Form::LENGTH, "USB Product ID for com$com must have at %d characters", 4);
		$form->addText($comsn, 'USB sn:')
			->setRequired(TRUE)
			->setRequired("Add USB sn for com$com")
			->addRule(Form::MIN_LENGTH, "USB sn for com$com must have at least %d characters", 1)
			->addRule(Form::MAX_LENGTH, "USB sn for com$com can have maximum %d characters", 20);
	}
	if ( rxfile2("../cfg/s-ser2net-c{$com}dev") == "1" ) {
		$label2 = Html::el()->setHtml('<span style="color:#f00">device path is relative - depends on the overall configuration of a USB hub, after <b>change any part</b> can be changed!</span>');
		$form->addText($comdevpath, 'Device name:')
			->setRequired(TRUE)
			->setOption('description', $label2)
			->addRule(Form::PATTERN, "com$com device name must be in format 'ttyUSB.#-#.(#|#:#).#'", 'ttyUSB\.[0-9]\-[0-9]\.([0-9]|[0-9]\-[0-9])\.[0-9]');
			//(Example: SKxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx)', '[A-Z]{2}+[0-9]{1,32}'
	}else{$label = '';}

	// nacteni default (predchozich) hodnot
	// jmena poli se naplni hodnotou pole
	$form->setDefaults(array(
		$comname => rxfile2("../cfg/s-ser2net-c{$com}name"),
		$comport => rxfile2("../cfg/s-ser2net-c{$com}port"),
		$combaud => rxfile2("../cfg/s-ser2net-c{$com}baud"),
		$comdata => rxfile2("../cfg/s-ser2net-c{$com}data"),
		$comstop => rxfile2("../cfg/s-ser2net-c{$com}stop"),
		$comparity => rxfile2("../cfg/s-ser2net-c{$com}parity"),
		$comidv => rxfile2("../cfg/s-ser2net-c{$com}idv"),
		$comidp => rxfile2("../cfg/s-ser2net-c{$com}idp"),
		$comsn => rxfile2("../cfg/s-ser2net-c{$com}sn"),
		$comdev => rxfile2("../cfg/s-ser2net-c{$com}dev"),
		$comdevpath => rxfile2("../cfg/s-ser2net-c{$com}devpath"),
	));
}

$form->addSubmit('submit', 'Apply');

if ($form->isSuccess()) {
	txfile('s-ser2net-coms', $pocetcomu);

	$warn = "For detection new USB2serial interface repluged your usb device or reboot.<br>
	You can observe the result on the <a href=\"index.php\">Recognized USB devices.</a></p>";

	$values = $form->getValues();
	for ($com = 1; $com < $pocetcomu+1; ++$com) {
		// jmena poli
		$comname = "c".$com."name";
		$comport = "c".$com."port";
		$combaud = "c".$com."baud";
		$comdata = "c".$com."data";
		$comstop = "c".$com."stop";
		$comparity = "c".$com."parity";
		$comidv = "c".$com."idv";
		$comidp = "c".$com."idp";
		$comsn = "c".$com."sn";
		$comdev = "c".$com."dev";
		$comdevpath = "c".$com."devpath";
		// ulozeni promennych
		txfile2("../cfg/s-ser2net-c{$com}name", $values->$comname);
		txfile2("../cfg/s-ser2net-c{$com}port", $values->$comport);
		txfile2("../cfg/s-ser2net-c{$com}baud", $values->$combaud);
		txfile2("../cfg/s-ser2net-c{$com}data", $values->$comdata);
		txfile2("../cfg/s-ser2net-c{$com}stop", $values->$comstop);
		txfile2("../cfg/s-ser2net-c{$com}parity", $values->$comparity);
		if ( rxfile2("../cfg/s-ser2net-c{$com}dev") == "0" ) {
			txfile2("../cfg/s-ser2net-c{$com}idv", $values->$comidv);
			txfile2("../cfg/s-ser2net-c{$com}idp", $values->$comidp);
			txfile2("../cfg/s-ser2net-c{$com}sn", $values->$comsn);
		}
		txfile2("../cfg/s-ser2net-c{$com}dev", $values->$comdev);
		if ( rxfile2("../cfg/s-ser2net-c{$com}dev") == "1" ) {
			txfile2("../cfg/s-ser2net-c{$com}devpath", $values->$comdevpath);
		}
	}
	exec('../script/udev.sh > /etc/udev/rules.d/99-remoteqth.rules');
	exec('../script/ser2net.sh > /etc/ser2net.conf');
	exec('sudo /etc/init.d/ser2net restart');
	exec('sudo udevadm control --reload-rules');
} ?>
<p class="warn"><?php echo $warn ?></p>

<? echo $form1;
echo $form; ?>

<p class="warn"><?php echo $warn ?></p>
<p class="next"><a href="s-fsk.php"><img src="previous.png" alt="previous page"></a><a href="s-rigctld.php"><img src="next.png" alt="next page"></a></p>

<!-- #################### /Obsah #################### -->
</div>
<div id="header"><?php include 'header.html';?></div>
</div><div id="leftmenu"><?php include 'menu.html';?></div>
</body>
</html>
