<!doctype html>
<html>
<head>
	<title><? include '../cfg/s-login-note';?> | CW set</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="styles.css" rel="stylesheet" type="text/css">
	<script src="netteForms.js"></script>
</head>
<body scroll="no">
<div id="obsah">
<!-- #################### Obsah #################### -->
<span style="position: absolute; top: 70px; left: 380px; z-index: 0"><img src="s-cw.png"></span>
<p class="text2">More about settings in <a href="http://remoteqth.com/wiki/index.php?page=CW" target="_blank" class="external">Wiki</a></p>
<p class="text2"><a href="s-usb.php" onclick="window.open( this.href, this.href, 'width=800,height=400,left=0,top=0,menubar=no,location=no,status=no' ); return false;"  title="i2c">Find USB id's and sn <img src="split.png" alt="split window"></a></p>

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
$cwidv = 'cwidv';
$cwidp = 'cwidp';
$cwsn = 'cwsn';
$cwcli = 'cwcli';
$cwd = rxfile('s-cw-cwd');
$memories = rxfile('s-cw-mem');

$form1 = new Form('prvni');
$form1->setMethod('POST');
$form1->addGroup("CW device");
	$pocetx = array(
		'0' => 'DISABLE',
		'1' => 'CW daemon',
		'2' => 'Arduino K3NG CLI',
		);
	$form1->addSelect('pocet', 'Select', $pocetx)
		->setAttribute('onchange', 'submit()')
		->setDefaultValue($cwd);
	if ($form1->isSuccess()) {
		$values = $form1->getValues();
		$cwd = $values->pocet;
		txfile('s-cw-cwd', $values->pocet);
	}

$form2 = new Form('druhy');
$form2->setMethod('POST');
	if ( $cwd == "1" ) {
		$form2->addGroup('CW daemon serial USB device')
			->setOption('description', 'Daemon also listens on UDP port 6789.');
			$form2->addText('cwidv', 'USB Vendor id:', 4)
				->setRequired('Add USB Vendor ID')
				->addRule(Form::LENGTH, 'USB Vendor ID must have at %d characters', 4);
			$form2->addText('cwidp', 'USB Product id:', 4)
				->setRequired('Add USB Product ID')
				->addRule(Form::LENGTH, 'USB Product ID must have at %d characters', 4);
			$form2->addText('cwsn', 'USB sn:', 8)
				->setRequired('Add USB sn')
				->addRule(Form::MIN_LENGTH, 'USB sn must have at least %d characters', 1)
				->addRule(Form::MAX_LENGTH, 'USB sn can have maximum %d characters', 8);
	}
	if ( $cwd == "2" ) {
		$form2->addGroup('Arduino K3NG CLI')
			->setOption('description', ' Listens on UDP port 7890.');
			$form2->addText('cwcli', 'TCP port:')
				->setOption('description', 'Connect on usb, accessible via ser2net - see SER2NET settings.')
				->setRequired('Add TCP port')
				->addRule(Form::INTEGER, 'TCP port must be number')
				->addRule(Form::RANGE, 'TCP port value must be from %d to %d', array(101, 65535));
	}
if ( $cwd != "0" ) {

	$form2->addGroup("CW memory")
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
			txfile('s-cw-mem', $values->memories);
		}

	// generovani formulare
	for ($mem = 1; $mem < $memories+1; ++$mem){
		// jmena poli
		$cwmem = "cwmem".$mem;
		$cwmemc = "cwmem".$mem."c";
		// formular
			$form2->addText($cwmem, "Mem$mem name:")
				->setRequired(TRUE)
				->addRule(Form::MAX_LENGTH, "CW mem $mem name can have maximum %d characters", 6);
			$form2->addText($cwmemc, "Mem$mem content:")
				->setRequired(TRUE)
				->addRule(Form::MAX_LENGTH, "CW mem $mem content can have maximum %d characters", 60);
		// nacteni default (predchozich) hodnot
		// jmena poli se naplni hodnotou pole
		$form2->setDefaults(array(
			$cwmem => rxfile("s-cw-mem{$mem}"),
			$cwmemc => rxfile("s-cw-mem{$mem}c"),
			$cwidv => rxfile('s-cw-idv'),
			$cwidp => rxfile('s-cw-idp'),
			$cwsn => rxfile('s-cw-sn'),
			$cwcli => rxfile('s-cw-cwcli'),
		));
	}
	$form2->addSubmit('submit', 'Apply');
}

if ($form2->isSuccess()) {
	if ( $cwd == "1" ) {
		$warn = "For detection new USB2serial interface repluged your usb device or reboot.<br>
		You can observe the result on the <a href=\"index.php\">Recognized USB devices.</a></p>";

		$values = $form2->getValues();
			$cwidv = 'cwidv';
			$cwidp = 'cwidp';
			$cwsn = 'cwsn';
		txfile("s-cw-idv", $values->$cwidv);
		txfile("s-cw-idp", $values->$cwidp);
		txfile("s-cw-sn", $values->$cwsn);
		exec('../script/udev.sh > /etc/udev/rules.d/99-remoteqth.rules');
		exec('sudo udevadm control --reload-rules');
	}
	if ( $cwd == "2" ) {
		$warn = "For detection new USB2serial interface repluged your usb device or reboot.<br>
		You can observe the result on the <a href=\"index.php\">Recognized USB devices.</a></p>";

		$values = $form2->getValues();
			$cwcli = 'cwcli';
		txfile("s-cw-cwcli", $values->$cwcli);
		exec('../script/udev.sh > /etc/udev/rules.d/99-remoteqth.rules');
		exec('sudo udevadm control --reload-rules');
	}
	$values = $form2->getValues();
	for ($mem = 1; $mem < $memories+1; ++$mem) {
		// jmena poli
		$cwmem = "cwmem{$mem}" ;
		$cwmemc = "cwmem{$mem}c";
		// ulozeni promennych
		txfile("s-cw-mem{$mem}", $values->$cwmem);
		txfile("s-cw-mem{$mem}c", $values->$cwmemc);
	}
}

echo $form1;
echo $form2;

?>

<p class="warn"><?php echo $warn ?></p>
<p class="next"><a href="s-rot2.php"><img src="previous.png" alt="previous page"></a><a href="s-fsk.php"><img src="next.png" alt="next page"></a></p>


<!-- #################### /Obsah #################### -->
</div>
<div id="header"><?php include 'header.html';?></div>
</div><div id="leftmenu"><?php include 'menu.html';?></div>
</body>
</html>

