<!doctype html>
<html>
<head>
	<title><? include '../cfg/s-login-note';?> | VPN</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="styles.css" rel="stylesheet" type="text/css">
	<script src="netteForms.js"></script>
</head>
<body scroll="no">
<div id="obsah">
<!-- #################### Obsah #################### -->
<span style="position: absolute; top: 20px; left: 430px; z-index: auto"><img src="s-vpn.png"></span>
<p class="text2">More about settings in <a href="http://remoteqth.com/wiki/index.php?page=n2n+VPN" target="_blank" class="external">Wiki</a></p>

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

//$nodex = file('../cfg/s-vpn-node'); $node= $nodex[0];
//$nodeportx = file('../cfg/s-vpn-nodeport'); $nodeport= $nodeportx[0];
//$loginx = file('../cfg/s-login-call'); $login= $loginx[0];
//$ipx = file('../cfg/s-vpn-ip'); $ip= $ipx[0];
//$enablex = file('../cfg/s-vpn-on'); $enable= $enablex[0];
$debversion = rxfile2('/etc/debian_version');
$enablen2n = rxfile2("../cfg/s-vpn-on");

$form1 = new Form('prvni');
$form1->setMethod('POST');
$form1->addGroup("n2n VPN");
	$pocetx = array(
		'0' => 'DISABLE',
		'1' => 'ENABLE',
		);
	$form1->addSelect('pocet', '', $pocetx)
		->setAttribute('onchange', 'submit()')
		->setDefaultValue($enablen2n);
	if ($form1->isSuccess()) {
		$values = $form1->getValues();
		$enablen2n = $values->pocet;
		txfile2('../cfg/s-vpn-on', $values->pocet);
	}

$form = new Form;
$form->setMethod('POST');
for ($x = 1; $x < $enablen2n+1; ++$x){
	// jmena poli
	$node = "node";
	$nodeport = "nodeport";
	$login = "login";
	$ip = "ip";
	$community = "community";

	// formular
	$form->addGroup('Configure')
		->setOption('description', 'Virtual private nework Accesses RemoteQTH server without public ip address');
		$form->addText('node', 'n2n Supernode:', 15)
			->setRequired('Choose your supernode')
			->addRule(Form::MAX_LENGTH, 'Supernode address can have maximum %d characters', 15);
		$form->addText('nodeport', 'Supernode port:', 5)
			->setRequired('Add n2n supernode IP port')
			->addRule(Form::INTEGER, 'n2n supernode port must be number')
			->addRule(Form::RANGE, 'n2n supernode IP port value must be from %d to %d', array(1, 65535));
		$form->addText('community', 'Comunity:', 15)
			->setRequired('Choose your community')
			->addRule(Form::MAX_LENGTH, 'Supernode community can have maximum %d characters', 15);
		$form->addPassword('password', 'New password:')
			->setRequired('Choose your password')
			->addRule($form::MIN_LENGTH, 'The password is too short: it must be at least %d characters', 8);
		$form->addPassword('password2', 'Reenter password:')
			->addConditionOn($form['password'], $form::VALID)
				->addRule($form::FILLED, 'Reenter your password')
				->addRule($form::EQUAL, 'Passwords do not match', $form['password']);
		$form->addText('ip', 'Server ip address:', 15)
			->setRequired('Add RemoteQTH server side ip address')
			->addRule(Form::MIN_LENGTH, 'RemoteQTH server side ip address must have at least %d characters', 7)
			->addRule(Form::MAX_LENGTH, 'RemoteQTH server side ip address can have maximum %d characters', 15)
			->addRule(Form::PATTERN, 'RemoteQTH server side ip address must be in range 0.0.0.0 - 255.255.255.255', '\b(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b');

	// nacteni default (predchozich) hodnot
	// jmena poli se naplni hodnotou pole
	$form->setDefaults(array(
		$node => rxfile2("../cfg/s-vpn-node"),
		$nodeport => rxfile2("../cfg/s-vpn-nodeport"),
		$login => rxfile2("../cfg/s-login-call"),
		$ip => rxfile2("../cfg/s-vpn-ip"),
		$community => rxfile2("../cfg/s-vpn-community"),
	));
}
$form->addSubmit('submit', 'Apply');

if ($form->isSuccess()) {
	$warn = "";
	$cesta = getcwd();

	if ( $enablen2n == "1" ) {  //rxfile2("../cfg/s-vpn-on") == "1" ) {
		$values = $form->getValues();
		// ulozeni promennych
		txfile2("../cfg/s-vpn-node", $values->$node);
		txfile2("../cfg/s-vpn-nodeport", $values->$nodeport);
		txfile2("../cfg/s-login-call", $values->$login);
		txfile2("../cfg/s-vpn-ip", $values->$ip);
		txfile2("../cfg/s-vpn-community", $values->$community);
	}

	if ($debversion < 8 ){	// Wheezy
		exec("$cesta/../script/n2n.sh > /etc/init.d/n2n");
		exec('sudo /etc/init.d/n2n restart');
	}
	if ($debversion <= 8 ){	// Jessie
		if ( rxfile2("../cfg/s-vpn-on") == "1" ) {
			exec("$cesta/../script/n2n.sh > /etc/default/n2n");
			exec('sudo /etc/init.d/n2n restart');
		}
		if ( rxfile2("../cfg/s-vpn-on") == "0" ) {
			exec('sudo /etc/init.d/n2n stop');
			exec("$cesta/../script/n2n.sh > /etc/default/n2n");
		}
	}	
}

echo $form1;
if ( rxfile2("../cfg/s-vpn-on") == "1" ) {
	echo $form ;
}
	$xip = implode(".", array_slice(explode(".", rxfile2('../cfg/s-vpn-ip')), 3, 1)) ;
	$xip++ ;
	$newip = implode(".", array_slice(explode(".", rxfile2('../cfg/s-vpn-ip')), 0, 3)) . '.' . $xip ;

if ( rxfile2("../cfg/s-vpn-on") == "1" ) {
	echo '<p class="text2"><strong>Run client with command:</strong></p><p class="text2">Windows</p><pre>edge.exe -l ';
	include '../cfg/s-vpn-node';
	echo ':';
	include '../cfg/s-vpn-nodeport';
	echo ' -c ';
	include '../cfg/s-login-call';
	echo ' -k ';
	include '../cfg/s-vpn-pass';
	echo " -a $newip </pre><p class=\"text2\">Linux</p><pre>sudo edge -d edge0 -l ";
	include '../cfg/s-vpn-node';
	echo ':';
	include '../cfg/s-vpn-nodeport';
	echo ' -c ';
	include '../cfg/s-login-call';
	echo ' -k ';
	include '../cfg/s-vpn-pass';
	echo " -a $newip -f</pre><p class=\"text2\">And connect server on adress <strong>http://";
	include '../cfg/s-vpn-ip';
	echo '</strong><p><b>Process:</b></p>';
	exec('ps aux | grep sbin/[e]dge > /tmp/ps-edge');
	echo '<pre>';
	include '/tmp/ps-edge';
	echo '</pre>';
}?>

<p class="next"><a href="s-net.php"><img src="previous.png" alt="previous page"></a><a href="s-relay.php"><img src="next.png" alt="next page"></a></p>


<!-- #################### /Obsah #################### -->
</div>
<div id="header"><?php include 'header.html';?></div>
</div><div id="leftmenu"><?php include 'menu.html';?></div>
</body>
</html>

