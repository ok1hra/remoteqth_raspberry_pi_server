<!doctype html>
<html>
<head>
	<title><? include '../cfg/s-login-note';?> | Rotator</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="styles.css" rel="stylesheet" type="text/css">
	<script src="netteForms.js"></script>
</head>
<body scroll="no">
<div id="obsah">
<!-- #################### Obsah #################### -->
<span style="position: absolute; top: 15px; left: 360px; z-index: auto"><img src="cluster.png"></span>
<p class="text2">More about settings in <a href="http://remoteqth.com/wiki/index.php?page=Cluster+of+Rotators" target="_blank" class="external">Wiki</a></p>

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
$nrservers = rxfile('s-rot2-servers');

$form1 = new Form('prvni');
$form1->setMethod('POST');
$form1->addGroup("Server Cluster");
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
	$form1->addSelect('pocet', 'Number\'s other servers conected', $pocetx)
		->setAttribute('onchange', 'submit()')
		->setDefaultValue($nrservers);
	if ($form1->isSuccess()) {
		$values = $form1->getValues();
		$nrservers = $values->pocet;
		txfile('s-rot2-servers', $values->pocet);
	}

$form = new Form('druhy');
$form->setMethod('POST');
// generovani formulare
for ($server = 1; $server < $nrservers+1; ++$server){
	// jmena poli
	$serverip = "s".$server."ip";
	$serverlogin = "s".$server."login";
	$serverpass = "s".$server."pass";

	// formular
	$form->addGroup("RemoteQTH server$server");
	$form->addText($serverip, 'IP:', 15)
		->setRequired("Choose server$server IP adress")
		->addRule(Form::MIN_LENGTH, 'IP address must have at least %d characters', 7)
		->addRule(Form::MAX_LENGTH, 'IP address can have maximum %d characters', 15)
		->addRule(Form::PATTERN, 'IP address must be in range 0.0.0.0 - 255.255.255.255', '\b(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b');
	$form->addText($serverlogin, 'Login:')
		->setRequired("Enter server$server login")
		->addRule(Form::MIN_LENGTH, 'Callsign must have at least %d characters', 3)
		->addRule(Form::MAX_LENGTH, 'Callsign can have maximum %d characters', 12)
		->addRule(Form::PATTERN, 'Callsign alow characters only letters and digits', '[A-Za-z0-9]*');
	$form->addPassword($serverpass, 'Password:')
		->setRequired("Choose server$server password")
		->addRule($form::MIN_LENGTH, 'The password is too short: it must be at least %d characters', 4);

	// nacteni default (predchozich) hodnot
	// jmena poli se naplni hodnotou pole
	$form->setDefaults(array(
		$serverip => rxfile("s-rot2-s{$server}ip"),
	));
}

if ( $nrservers != "0" ) {
	$form->addSubmit('submit', 'Re detect & store rotators settings');
}

echo $form1;
echo $form;

if ($form->isSuccess()) {
	txfile('s-rot2-servers', $nrservers);

	$values = $form->getValues();
	for ($server = 1; $server < $nrservers+1; ++$server) {
		// jmena poli
		$serverip = "s".$server."ip";
		$serverlogin = "s".$server."login";
		$serverpass = "s".$server."pass";
		// ulozeni promennych
		txfile("s-rot2-s{$server}ip", $values->$serverip);
		txfile("s-rot2-s{$server}login", $values->$serverlogin);
		txfile("s-rot2-s{$server}pass", $values->$serverpass);

		// cteni serveru
		$srotators[$server] = rxurlpass($values->$serverip, 'get.php?q=gf&file=s-rot-rots', $values->$serverlogin, $values->$serverpass);
		$snote[$server] = rxurlpass($values->$serverip, 'get.php?q=gf&file=s-login-note', $values->$serverlogin, $values->$serverpass);
		$srev[$server] = rxurlpass($values->$serverip, 'get.php?q=0', $values->$serverlogin, $values->$serverpass);

		txfile("s-rot-s{$server}rots", $srotators[$server]);
		txfile("s-rot-s{$server}note", $snote[$server]);
		txfile("s-rot-s{$server}rev", $srev[$server]);
	
		for ($rot = 1; $rot < $srotators[$server]+1; ++$rot) {
			$sname[$server][$rot] = rxurlpass($values->$serverip, "get.php?q=gf&file=s-rot-r{$rot}name", $values->$serverlogin, $values->$serverpass);
			$sfrom[$server][$rot] = rxurlpass($values->$serverip, "get.php?q=gf&file=s-rot-r{$rot}from", $values->$serverlogin, $values->$serverpass);
			$sto[$server][$rot] = rxurlpass($values->$serverip, "get.php?q=gf&file=s-rot-r{$rot}to", $values->$serverlogin, $values->$serverpass);
			$sget[$server][$rot] = rxurlpass($values->$serverip, "get.php?q=gf&file=s-rot-r{$rot}get", $values->$serverlogin, $values->$serverpass);
			$sset[$server][$rot] = rxurlpass($values->$serverip, "get.php?q=gf&file=s-rot-r{$rot}set", $values->$serverlogin, $values->$serverpass);
			// ulozeni hodnot
			txfile("s-rot-s{$server}r{$rot}name", $sname[$server][$rot]);
			txfile("s-rot-s{$server}r{$rot}from", $sfrom[$server][$rot]);
			txfile("s-rot-s{$server}r{$rot}to", $sto[$server][$rot]);
			txfile("s-rot-s{$server}r{$rot}get", $sget[$server][$rot]);
			txfile("s-rot-s{$server}r{$rot}set", $sset[$server][$rot]);
		}

		// B+ detection
		if (preg_match('/00(02|03|0[456]|0[789]|0[def]|11)/', $srev[$server])){
			$sgpio[$server] = '15';
		}else{
			$sgpio[$server] = '24';
		}

		for ($gpio = 1; $gpio < $sgpio[$server]+1; ++$gpio) {
			$sname[$server][$gpio] = rxurlpass($values->$serverip, "get.php?q=gf&file=s-relay-{$gpio}", $values->$serverlogin, $values->$serverpass);
			$switch[$server][$gpio] = rxurlpass($values->$serverip, "get.php?q=gf&file=s-relay-sw-{$gpio}", $values->$serverlogin, $values->$serverpass);
			$switchb[$server][$gpio] = rxurlpass($values->$serverip, "get.php?q=gf&file=s-relay-swb-{$gpio}", $values->$serverlogin, $values->$serverpass);
			$aoff[$server][$gpio] = rxurlpass($values->$serverip, "get.php?q=gf&file=s-relay-aof-{$gpio}", $values->$serverlogin, $values->$serverpass);
			// ulozeni hodnot
			txfile("s-rot-s{$server}gpio{$gpio}name", $sname[$server][$gpio]);
			txfile("s-rot-s{$server}gpio{$gpio}sw", $switch[$server][$gpio]);
			txfile("s-rot-s{$server}gpio{$gpio}swb", $switchb[$server][$gpio]);
			txfile("s-rot-s{$server}gpio{$gpio}aof", $aoff[$server][$gpio]);
		}
	}
}

if ( $nrservers != 0 ) {
	echo '<h1>Stored Rotators and Relays settings</h1>';
}
for ($server = 1; $server < $nrservers+1; ++$server) { ?>
	<table  class="rot2">
	<tr class="prvni">
		<th><img src="cluster2.png"> Server <?echo $server; ?></th>
		<th><?php include "../cfg/s-rot-s{$server}note";?></th>
		<th>IP <a href="http://<?php
			$serverip = rxfile("s-rot2-s{$server}ip");
			echo $serverip;?>" target="_blank"><?php echo $serverip;
			if (availableUrl("$serverip", 80, 3) == 1){			  // host, port, timeout
				echo '<span style="color: #080; font-weight: bold;"> ONLINE</span>';
			}else{
				echo '<span style="color: red; font-weight: bold;"> OFFLINE</span>';
			}?>&#8599;</a>
		</th>
		<? $srev[$server] = rxfile("s-rot-s{$server}rev");
		// B+ detection
		if (preg_match('/00(02|03|0[456]|0[789]|0[def]|11)/', $srev[$server])){
			$sgpio[$server] = '15';
		}else{
			$sgpio[$server] = '24';
		}?>
		<th colspan="3">rev.(dec) <?php echo $srev[$server].' | '.$sgpio[$server] ;?></th>
	</tr>
	<tr>
		<th>Rotator [<?php include "../cfg/s-rot-s{$server}rots";?>]</th>
		<th>Name</th>
		<th>From</th>
		<th>To</th>
		<th>Get</th>
		<th>Set</th>
	</tr>
	<?$srotators[$server] = rxfile("s-rot-s{$server}rots");
	for ($rot = 1; $rot < $srotators[$server]+1; ++$rot) { ?>
	<tr>
		<td><?echo $rot; ?></td>
		<td><?php include "../cfg/s-rot-s{$server}r{$rot}name";?></td>
		<td><?php include "../cfg/s-rot-s{$server}r{$rot}from";?>&deg;</td>
		<td><?php include "../cfg/s-rot-s{$server}r{$rot}to";?>&deg;</td>
		<td><?php include "../cfg/s-rot-s{$server}r{$rot}get";?></td>
		<td><?php include "../cfg/s-rot-s{$server}r{$rot}set";?></td>
	</tr><?
	}

	?><tr>
		<th>Relay [15]</th>
		<th>Name</th>
		<th>Changeover<br>[bank]</th>
		<th>Auto-off</th>
		<th></th>
		<th></th>
	</tr><?
	for ($gpio = 1; $gpio < $sgpio[$server]+1; ++$gpio) { ?>
	<tr>
		<td><?echo $gpio; ?></td>
		<td><?php include "../cfg/s-rot-s{$server}gpio{$gpio}name";?></td>
		<td><?php if ( rxfile("s-rot-s{$server}gpio{$gpio}sw") == '1' ) {echo '[1]';} elseif ( rxfile("s-rot-s{$server}gpio{$gpio}swb") == '1' ) {echo '[2]';}else{echo '-';};?></td>
		<td><?php if ( rxfile("s-rot-s{$server}gpio{$gpio}aof") == '1' ) {echo 'Y';}else{echo '-';};?></td>
		<td></td>
		<td></td>
	</tr><?
	}
}
?></table>

<p class="next"><a href="s-rot.php"><img src="previous.png" alt="previous page"></a><a href="s-cw.php"><img src="next.png" alt="next page"></a></p>

<!-- #################### /Obsah #################### -->
</div>
<div id="header"><?php include 'header.html';?></div>
</div><div id="leftmenu"><?php include 'menu.html';?></div>
</body>
</html>

