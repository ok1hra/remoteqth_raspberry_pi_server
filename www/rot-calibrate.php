<!doctype html>
<html>
<head>
	<title><? include '../cfg/s-login-note';?> | Rotator setup</title>
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link href="styles.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor="#fff">
<?php
require 'function.php';
require 'Nette/loader.php';
use Nette\Forms\Form,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html;
////debugger::enable();
$configurator = new Nette\Config\Configurator; 
$configurator->setDebugMode(TRUE);
$configurator->enableDebugger(__DIR__ . '/../log');?>
This tool don't work!
<h1>Seven steps to calibrate K3NG arduino rotator controller</h1>
<p class="calibrate">Before this setup must be Arduino comtroller activate with right firmware.
Especially settings azimuth range must correspond to a given rotator.</p>
<!--------------------------------------------------------- config -->
<p class="status1">1. If it is set to communicate with its interface on <a href="s-rot.php">set page</a></p>
<pre>
Rotator From   To    Note
-------------------------------------
rot1    <?php include '../cfg/s-rot-r1from';?>°  <?php include '../cfg/s-rot-r1to';?>°  <?php include '../cfg/s-rot-r1name';?> 
rot2    <?php include '../cfg/s-rot-r2from';?>°  <?php include '../cfg/s-rot-r2to';?>°  <?php include '../cfg/s-rot-r2name';?> 
rot3    <?php include '../cfg/s-rot-r3from';?>°  <?php include '../cfg/s-rot-r3to';?>°  <?php include '../cfg/s-rot-r3name';?> 
rot4    <?php include '../cfg/s-rot-r4from';?>°  <?php include '../cfg/s-rot-r4to';?>°  <?php include '../cfg/s-rot-r4name';?> 
rot5    <?php include '../cfg/s-rot-r5from';?>°  <?php include '../cfg/s-rot-r5to';?>°  <?php include '../cfg/s-rot-r5name';?> 
rot6    <?php include '../cfg/s-rot-r6from';?>°  <?php include '../cfg/s-rot-r6to';?>°  <?php include '../cfg/s-rot-r6name';?> 
rot7    <?php include '../cfg/s-rot-r7from';?>°  <?php include '../cfg/s-rot-r7to';?>°  <?php include '../cfg/s-rot-r7name';?> 
rot8    <?php include '../cfg/s-rot-r8from';?>°  <?php include '../cfg/s-rot-r8to';?>°  <?php include '../cfg/s-rot-r8name';?> 
</pre>
<!--------------------------------------------------------- USB -->
<p class="status1">2. And system recognized USB devices</p>
<pre>
<? if ($handle = opendir('/dev')) {
	while (false !== ($entry = readdir($handle))) {
		$pozice = strpos($entry, "USB.");
		if( $pozice ) {
			echo substr($entry, $pozice+4, strlen($entry)).' | ';
		}	
	}
	closedir($handle);
} ?></pre>

<!--------------------------------------------------------- CCW -->
<p class="status1">3. Select rotator.</p>

<?
$path = "../cfg/";
$pocetrot = rxfile('s-rot-rots');
$rotselect = rxfile('s-rot-rotselect');

if ($rotselect == "") {
	$rotselect = 1;
}
if ($pocetrot == "0") {
}
else {
	$form1 = new Form('prvni');
	$form1->setMethod('POST');
		$rotselectx = array(
			/* for($rot=1; $rot < $rotselect+1; $rot++) {
			$rot => rxfile("s-rot-r{$rot}name"),
			} */
			'1' => rxfile("s-rot-r1name"),
			'2' => rxfile("s-rot-r2name"),
			'3' => rxfile("s-rot-r3name"),
			'4' => rxfile("s-rot-r4name"),
			'5' => rxfile("s-rot-r5name"),
			'6' => rxfile("s-rot-r6name"),
			'7' => rxfile("s-rot-r7name"),
			'8' => rxfile("s-rot-r8name"),
			);
		$form1->addSelect('rotselect', '', $rotselectx)
			->setAttribute('onchange', 'submit()')
			->setDefaultValue($rotselect);
		if ($form1->isSuccess()) {
			$values = $form1->getValues();
			$rotselect = $values->rotselect;
			txfile('s-rot-rotselect', $values->rotselect);
		}
	echo "<p class=\"calibrate\">$form1</p>" ;
	// nastaveni promennych
	$rotator = $rotselect ; //$_GET['rot'];
	$IP = '127.0.0.1' ;
	$port = $rotator + 90 ;
	$rotname = rxfile("../cfg/s-rot-r{$rotator}name");
	$rotl = rxfile("../cfg/s-rot-r{$rotator}from");
	$rotr = rxfile("../cfg/s-rot-r{$rotator}to");
	$rotget = rxfile("../cfg/s-rot-r{$rotator}get");
	$rotset = rxfile("../cfg/s-rot-r{$rotator}set");

		// get azimut
		$raw = txrxtcp($IP, $port, str_replace(
			array('\r', '\n'),
			array("\r", "\n"), $rotget));
		$cut = Trim($raw);
		$az = substr("$cut", 3, 3);
		echo "<pre>GET azimuth: $az</pre>";?>
<!--------------------------------------------------------- erase -->
<p class="status1">4. Erase eeprom.</p>
<form action="<?echo actualpage();?>" method="POST" class="calibrate">
	This step is not necessary, only if unable to set the next steps. <input type="submit" name="erase" value="Erase"><br>After erase must restart Arduino controller.
	<?
	if (isset($_POST['erase'])) {
		txtcp($IP, $port, "\e\r", $rotset);
	}?>
</form>
<!--------------------------------------------------------- CCW -->
<p class="status1">5. Rotate full CCW manually or with button, and press confirm.</p>
<form action="<?echo actualpage();?>" method="POST" class="calibrate">
	<input type="submit" name="ccw" value="Rotate CCW">
	<input type="submit" name="stop" value="STOP">
	<pre>If rotator fully counter clockwise press <input type="submit" name="confirmccw" value="Confirm"></pre>
	<?
	if (isset($_POST['ccw'])) {
		txtcp($IP, $port, "L\r", $rotset);
	}
	else if (isset($_POST['stop'])) {
		txtcp($IP, $port, "A\r", $rotset);
	}
	else if (isset($_POST['confirmccw'])) {
		txtcp($IP, $port, "O\r\r", $rotset);
	}?>
</form>
<!--------------------------------------------------------- CW -->
<p class="status1">6. Rotate full CW manually or with button, and press confirm.</p>
<form action="<?echo actualpage();?>" method="POST" class="calibrate">
	<input type="submit" name="cw" value="Rotate CW">
	<input type="submit" name="stop" value="STOP">
	<pre>If rotator fully clockwise press <input type="submit" name="confirmcw" value="Confirm"></pre>
	<?
	if (isset($_POST['cw'])) {
		txtcp($IP, $port, "R\r", $rotset);
	}
	else if (isset($_POST['stop'])) {
		txtcp($IP, $port, "A\r", $rotset);
	}
	else if (isset($_POST['confirmcw'])) {
		txtcp($IP, $port, "F\r\r", $rotset);
	}?>
</form>
<!--------------------------------------------------------- rorators -->
<?
	$form = new Form('druhy');
	$form->setMethod('POST');
		$form->addText('azimuth', "$rotname:", 3)
			->setRequired('Choose Azimuth')
			->addRule(Form::MAX_LENGTH, 'Azimuth can have maximum %d characters', 3)
			->addRule(Form::INTEGER, 'Azimuth must be number')
			->addRule(Form::RANGE, 'Azimuth value must be from %d to %d', array(0, 359))
			->addRule($rotl > $rotr ? ~Form::RANGE : Form::RANGE, "Out of rotator range $rotl - $rotr °", array(min($rotl, $rotr), max($rotl, $rotr)));
	
	$form->addSubmit('submit', 'Rotate');
	if ($form->isSuccess()) {
		$values = $form->getValues();
		// leading zeros
		$rotate = sprintf('%03d', $values->azimuth);
		// replace \r, \n and # to azimuth
		$raw = txrxtcp($IP, $port, str_replace(
			array('\r', '\n', '#'),
			array("\r", "\n", "$rotate"), $rotset));
			$cut = Trim($raw);
		$az = substr("$cut", 3, 3);
		//$az = $rotate;
		echo "<a href=\"rot-calibrate.php\"><img src=\"img.php?doraz_l={$rotl}&doraz_p={$rotr}&poloha={$az}&ant={$rotname}&cil={$rotate}\" alt=\"azimuth map\" title=\"refresh\" class=\"calibrate\"></a>";
	}
	else {
		// get azimut
		$raw = txrxtcp($IP, $port, str_replace(
			array('\r', '\n'),
			array("\r", "\n"), $rotget));
		$cut = Trim($raw);
		$az = substr("$cut", 3, 3);
		echo "<a href=\"rot-calibrate.php\"><img src=\"img.php?doraz_l={$rotl}&doraz_p={$rotr}&poloha={$az}&ant={$rotname}&cil={$az}\" alt=\"azimuth map\" title=\"refresh\" class=\"calibrate\"></a>";
	}
	echo $form ;
} ?>

<!--------------------------------------------------------- old     -->
<p class="mobtitul"><? echo exec('uptime'); ?></p>
</body>
</html>
