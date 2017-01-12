<!doctype html>
<html>
<head>
	<title><? include '../cfg/s-login-note';?> | Rotator set</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="styles.css" rel="stylesheet" type="text/css">
	<script src="netteForms.js"></script>
</head>
<body scroll="no">
<div id="obsah">
<!-- #################### Obsah #################### -->
<span style="position: absolute; top: 15px; left: 400px; z-index: auto"><img src="s-rot.png"></span>
<p class="text2">More about settings in <a href="http://remoteqth.com/wiki/index.php?page=Rotators" target="_blank" class="external">Wiki</a></p>

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

//$path = "../cfg/";
$warn = '';
$pocetrot = rxfile2('../cfg/s-rot-rots');
$loc = rxfile2('../cfg/s-rot-loc');
$label1 = Html::el()->setHtml('<a href="s-usb.php" onclick="window.open( this.href, this.href, \'width=800,height=400,left=0,top=0,menubar=no,location=no,status=no\' ); return false;"  title="i2c">Find id and sn <img src="split.png" alt="split window"></a>');
$label2 = Html::el()->setHtml('max ccw');
$label3 = Html::el()->setHtml('max cw');
$label4 = Html::el()->setHtml('center azimuth map');

$form1 = new Form('prvni');
$form1->setMethod('POST');
$form1->addGroup("Rotators");
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
	$form1->addSelect('pocet', 'Number of Rotator\'s', $pocetx)
		->setAttribute('onchange', 'submit()')
		->setDefaultValue($pocetrot);
	if ($form1->isSuccess()) {
		$values = $form1->getValues();
		$pocetrot = $values->pocet;
		txfile2('../cfg/s-rot-rots', $values->pocet);
	}

$form = new Form('druhy');
$form->setMethod('POST');
// generovani formulare
for ($rot = 1; $rot < $pocetrot+1; ++$rot){
	// jmena poli
	$rotname = "r".$rot."name";
	$rotidv = "r".$rot."idv";
	$rotidp = "r".$rot."idp";
	$rotsn = "r".$rot."sn";
	$rotfrom = "r".$rot."from";
	$rotto = "r".$rot."to";
	$rotget = "r".$rot."get";
	$rotset = "r".$rot."set";

	// formular
	$form->addGroup("Rot $rot");
	$form->addText($rotname, 'Name:')
		->setRequired(TRUE)
		->setRequired("Add Name for Rot $rot")
		->addRule(Form::MAX_LENGTH, "Device name for Rot $rot can have maximum %d characters", 25);
	$form->addText($rotidv, 'USB Vendor id:')
		->setRequired(TRUE)
		->setOption('description', $label1)
		->setRequired("Add USB Vendor ID for Rot $rot")
		->addRule(Form::LENGTH, "USB Vendor ID for Rot $rot must have at %d characters", 4);
	$form->addText($rotidp, 'USB Product id:')
		->setRequired(TRUE)
		->setRequired("Add USB Product ID for Rot $rot")
		->addRule(Form::LENGTH, "USB Product ID for Rotator 1 must have at %d characters", 4);
	$form->addText($rotsn, 'USB sn:')
		->setRequired(TRUE)
		->setRequired("Add USB sn for Rot $rot")
		->addRule(Form::MIN_LENGTH, "USB sn for Rot $rot must have at least %d characters", 1)
		->addRule(Form::MAX_LENGTH, "USB sn for Rot $rot can have maximum %d characters", 20);
	$form->addText($rotfrom, 'From Azimuth:')
		->setRequired(TRUE)
		->setOption('description', $label2)
		->setRequired("Add FROM azimuth for Rot $rot")
		->addRule(Form::INTEGER, "FROM Rot $rot must be number")
		->addRule(Form::RANGE, "FROM Rot $rot value must be from %d to %d", array(0, 450));
	$form->addText($rotto, 'To Azimuth:')
		->setRequired(TRUE)
		->setOption('description', $label3)
		->setRequired("Add TO azimuth for Rot $rot")
		->addRule(Form::INTEGER, "TO Rot $rot must be number")
		->addRule(Form::RANGE, "TO Rot $rot value must be from %d to %d", array(0, 450));
	$form->addText($rotget, 'Custom GET command:')
		->setRequired(TRUE)
		->setOption('description', 'Default "C\r"')
		->setRequired("Add GET command for Rot $rot")
		->addRule(Form::MAX_LENGTH, "SET command Rot $rot can have maximum %d characters", 10);
	$form->addText($rotset, 'Custom SET command:')
		->setRequired(TRUE)
		->setOption('description', 'Default "C\rM#\r"')
		->setRequired("Add SET command for Rot $rot")
		->addRule(Form::MAX_LENGTH, "SET command Rot $rot can have maximum %d characters", 10)
		->addRule(Form::PATTERN, "SET command Rot $rot must include # = variable azimuth", '.*[#].*');

	// nacteni default (predchozich) hodnot
	// jmena poli se naplni hodnotou pole
	$form->setDefaults(array(
		$rotname => rxfile2("../cfg/s-rot-r{$rot}name"),
		$rotidv => rxfile2("../cfg/s-rot-r{$rot}idv"),
		$rotidp => rxfile2("../cfg/s-rot-r{$rot}idp"),
		$rotsn => rxfile2("../cfg/s-rot-r{$rot}sn"),
		$rotfrom => rxfile2("../cfg/s-rot-r{$rot}from"),
		$rotto => rxfile2("../cfg/s-rot-r{$rot}to"),
		$rotget => rxfile2("../cfg/s-rot-r{$rot}get"),
		$rotset => rxfile2("../cfg/s-rot-r{$rot}set"),
	));
}

$form->addGroup('Azimuth map');
	$form->addText('loc', 'Your locator:')
		->setOption('description', $label4)
		->setRequired('Add Your locator')
		->addRule(Form::LENGTH, 'Locator must be %d characters', 6)
		->addRule(Form::PATTERN, 'Locator format must be {First pair the letters from "A" to "R"}{Second pair the digits from "0" to "9"}{Third pair the letters from "A" to "X"}', '[A-Ra-r]{2}[0-9]{2}[A-Xa-x]{2}');
	$form->setDefaults(array('loc' => $loc));

$form->addSubmit('submit', 'Apply');

if ($form->isSuccess()) {
	txfile2('../cfg/s-rot-rots', $pocetrot);

	$warn = "For detection new USB2serial interface repluged your usb device or reboot.<br>
	You can observe the result on the <a href=\"index.php\">Recognized USB devices.</a></p>";

	$values = $form->getValues();
	for ($rot = 1; $rot < $pocetrot+1; ++$rot) {
		// jmena poli
		$rotname = "r".$rot."name";
		$rotidv = "r".$rot."idv";
		$rotidp = "r".$rot."idp";
		$rotsn = "r".$rot."sn";
		$rotfrom = "r".$rot."from";
		$rotto = "r".$rot."to";
		$rotget = "r".$rot."get";
		$rotset = "r".$rot."set";
		// ulozeni promennych
		txfile2("../cfg/s-rot-r{$rot}name", $values->$rotname);
		txfile2("../cfg/s-rot-r{$rot}idv", $values->$rotidv);
		txfile2("../cfg/s-rot-r{$rot}idp", $values->$rotidp);
		txfile2("../cfg/s-rot-r{$rot}sn", $values->$rotsn);
		txfile2("../cfg/s-rot-r{$rot}from", $values->$rotfrom);
		txfile2("../cfg/s-rot-r{$rot}to", $values->$rotto);
		txfile2("../cfg/s-rot-r{$rot}get", $values->$rotget);
		txfile2("../cfg/s-rot-r{$rot}set", $values->$rotset);
	}
	exec('../script/udev.sh > /etc/udev/rules.d/99-remoteqth.rules');
	exec('../script/ser2net.sh > /etc/ser2net.conf');
	exec('sudo /etc/init.d/ser2net restart');
	exec('sudo udevadm control --reload-rules');
	txfile2('../cfg/s-rot-loc', $values->loc);
	$cesta = getcwd();
	exec("$cesta/../script/azimuth-map.sh && chmod 666 /tmp/azimuth-map.png");
}
?>
<p class="warn"><?php echo $warn ?></p>

<?php echo $form1; ?>
<!--<p class="text2"><a href="rot-calibrate.php" onclick="window.open( this.href, this.href, 'width=600,height=800,left=0,top=0,menubar=no,location=no' ); return false;"  title="arduino setup">Setup arduino controller <img src="split.png" alt="split window"></a></p>-->
<?echo $form; ?>

<p class="text2">Your Locator you can find here
<a href="http://no.nonsense.ee/qthmap/" target="_blank" class="external">1</a>, 
<a href="http://www.levinecentral.com/ham/grid_square.php" target="_blank" class="external">2</a></p>

<p class="warn"><?php echo $warn ?></p>

<h2>Preview</h2>
<p class="text2">Rotators map with actual grayline and position of sun.<br>
The new map can be generated for longer than ten seconds. Wait, eventually refresh this page.</p>

<img class="map" src="azimuth-map.png">
<p class="next"><a href="s-sensorsad.php"><img src="previous.png" alt="previous page"></a><a href="s-rot2.php"><img src="next.png" alt="next page"></a></p>

<!-- #################### /Obsah #################### -->
</div>
<div id="header"><?php include 'header.html';?></div>
</div><div id="leftmenu"><?php include 'menu.html';?></div>
</body>
</html>

