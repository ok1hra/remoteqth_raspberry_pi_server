<!doctype html>
<html>
<head>
	<title><? include '../cfg/s-login-note';?> | Rot</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="styles.css" rel="stylesheet" type="text/css">
	<script src="netteForms.js"></script>
</head>
<body scroll="no">
<div id="obsah">
<!-- #################### Obsah #################### -->
<span style="position: absolute; top: 50px; left: 400px; z-index: auto"><img src="s-rot.png"></span>
<p class="text2">More about settings in <a href="http://remoteqth.com/wiki/index.php?page=Rotators" target="_blank" class="external">Wiki</a></p>
<p class="text2"><a href="s-usb.php" onclick="window.open( this.href, this.href, 'width=800,height=400,left=0,top=0,menubar=no,location=no,status=no' ); return false;"  title="i2c">Find USB id's and sn <img src="split.png" alt="split window"></a></p>

<?php
require 'Nette/loader.php';
use Nette\Forms\Form,
//	Nette\Diagnostics\Debugger,
	Nette\Utils\Html;
////debugger::enable();
//$configurator = new Nette\Config\Configurator; 
//$configurator->setDebugMode(TRUE);
//$configurator->enableDebugger(__DIR__ . '/../log');

$warn = '';
$lokator = file('../cfg/s-rot-loc'); $loc= $lokator[0];

$r1namex = file('../cfg/s-rot-r1name'); $r1name= $r1namex[0];
$r1idvx = file('../cfg/s-rot-r1idv'); $r1idv= $r1idvx[0];
$r1idpx = file('../cfg/s-rot-r1idp'); $r1idp= $r1idpx[0];
$r1snx = file('../cfg/s-rot-r1sn'); $r1sn= $r1snx[0];
$r1fromx = file('../cfg/s-rot-r1from'); $r1from= $r1fromx[0];
$r1tox = file('../cfg/s-rot-r1to'); $r1to= $r1tox[0];

$r2namex = file('../cfg/s-rot-r2name'); $r2name= $r2namex[0];
$r2idvx = file('../cfg/s-rot-r2idv'); $r2idv= $r2idvx[0];
$r2idpx = file('../cfg/s-rot-r2idp'); $r2idp= $r2idpx[0];
$r2snx = file('../cfg/s-rot-r2sn'); $r2sn= $r2snx[0];
$r2fromx = file('../cfg/s-rot-r2from'); $r2from= $r2fromx[0];
$r2tox = file('../cfg/s-rot-r2to'); $r2to= $r2tox[0];

$r3namex = file('../cfg/s-rot-r3name'); $r3name= $r3namex[0];
$r3idvx = file('../cfg/s-rot-r3idv'); $r3idv= $r3idvx[0];
$r3idpx = file('../cfg/s-rot-r3idp'); $r3idp= $r3idpx[0];
$r3snx = file('../cfg/s-rot-r3sn'); $r3sn= $r3snx[0];
$r3fromx = file('../cfg/s-rot-r3from'); $r3from= $r3fromx[0];
$r3tox = file('../cfg/s-rot-r3to'); $r3to= $r3tox[0];

$r4namex = file('../cfg/s-rot-r4name'); $r4name= $r4namex[0];
$r4idvx = file('../cfg/s-rot-r4idv'); $r4idv= $r4idvx[0];
$r4idpx = file('../cfg/s-rot-r4idp'); $r4idp= $r4idpx[0];
$r4snx = file('../cfg/s-rot-r4sn'); $r4sn= $r4snx[0];
$r4fromx = file('../cfg/s-rot-r4from'); $r4from= $r4fromx[0];
$r4tox = file('../cfg/s-rot-r4to'); $r4to= $r4tox[0];

$r5namex = file('../cfg/s-rot-r5name'); $r5name= $r5namex[0];
$r5idvx = file('../cfg/s-rot-r5idv'); $r5idv= $r5idvx[0];
$r5idpx = file('../cfg/s-rot-r5idp'); $r5idp= $r5idpx[0];
$r5snx = file('../cfg/s-rot-r5sn'); $r5sn= $r5snx[0];
$r5fromx = file('../cfg/s-rot-r5from'); $r5from= $r5fromx[0];
$r5tox = file('../cfg/s-rot-r5to'); $r5to= $r5tox[0];

$r6namex = file('../cfg/s-rot-r6name'); $r6name= $r6namex[0];
$r6idvx = file('../cfg/s-rot-r6idv'); $r6idv= $r6idvx[0];
$r6idpx = file('../cfg/s-rot-r6idp'); $r6idp= $r6idpx[0];
$r6snx = file('../cfg/s-rot-r6sn'); $r6sn= $r6snx[0];
$r6fromx = file('../cfg/s-rot-r6from'); $r6from= $r6fromx[0];
$r6tox = file('../cfg/s-rot-r6to'); $r6to= $r6tox[0];

$r7namex = file('../cfg/s-rot-r7name'); $r7name= $r7namex[0];
$r7idvx = file('../cfg/s-rot-r7idv'); $r7idv= $r7idvx[0];
$r7idpx = file('../cfg/s-rot-r7idp'); $r7idp= $r7idpx[0];
$r7snx = file('../cfg/s-rot-r7sn'); $r7sn= $r7snx[0];
$r7fromx = file('../cfg/s-rot-r7from'); $r7from= $r7fromx[0];
$r7tox = file('../cfg/s-rot-r7to'); $r7to= $r7tox[0];

$r8namex = file('../cfg/s-rot-r8name'); $r8name= $r8namex[0];
$r8idvx = file('../cfg/s-rot-r8idv'); $r8idv= $r8idvx[0];
$r8idpx = file('../cfg/s-rot-r8idp'); $r8idp= $r8idpx[0];
$r8snx = file('../cfg/s-rot-r8sn'); $r8sn= $r8snx[0];
$r8fromx = file('../cfg/s-rot-r8from'); $r8from= $r8fromx[0];
$r8tox = file('../cfg/s-rot-r8to'); $r8to= $r8tox[0];

$form = new Form;
//$form->setAction('s-rot2.php');
$form->setMethod('POST');

$form->addGroup('Rotator 1');
	$form->addText('r1name', 'Name:')
		->addRule(Form::MAX_LENGTH, 'Device name for Rotator 1 can have maximum %d characters', 25);
	$form->addText('r1idv', 'USB Vendor id:')
		->setRequired('Add USB Vendor ID for Rotator 1')
		->addRule(Form::LENGTH, 'USB Vendor ID for Rotator 1 must have at %d characters', 4);
	$form->addText('r1idp', 'USB Product id:')
		->setRequired('Add USB Product ID for Rotator 1')
		->addRule(Form::LENGTH, 'USB Product ID for Rotator 1 must have at %d characters', 4);
	$form->addText('r1sn', 'USB sn:')
		->setRequired('Add USB sn for Rotator 1')
		->addRule(Form::MIN_LENGTH, 'USB sn for Rotator 1 must have at least %d characters', 1)
		->addRule(Form::MAX_LENGTH, 'USB sn for Rotator 1 can have maximum %d characters', 8);
	$form->addText('r1from', 'From Azimuth:')
		->setRequired('Add FROM azimuth for Rotator 1')
		->addRule(Form::INTEGER, 'FROM Rotator 1 must be number')
		->addRule(Form::RANGE, 'FROM Rotator 1 value must be from %d to %d', array(0, 450));
	$form->addText('r1to', 'To Azimuth:')
		->setRequired('Add TO azimuth for Rotator 1')
		->addRule(Form::INTEGER, 'TO Rotator 1 must be number')
		->addRule(Form::RANGE, 'TO Rotator 1 value must be from %d to %d', array(0, 450));

$form->addGroup('Rotator 2');
	$form->addText('r2name', 'Name:')
		->addRule(Form::MAX_LENGTH, 'Device name for Rotator 2 can have maximum %d characters', 25);
	$form->addText('r2idv', 'USB Vendor id:')
		->setRequired('Add USB Vendor ID for Rotator 2')
		->addRule(Form::LENGTH, 'USB Vendor ID for Rotator 2 must have at %d characters', 4);
	$form->addText('r2idp', 'USB Product id:')
		->setRequired('Add USB Product ID for Rotator 2')
		->addRule(Form::LENGTH, 'USB Product ID for Rotator 2 must have at %d characters', 4);
	$form->addText('r2sn', 'USB sn:')
		->setRequired('Add USB sn for Rotator 2')
		->addRule(Form::MIN_LENGTH, 'USB sn for Rotator 2 must have at least %d characters', 1)
		->addRule(Form::MAX_LENGTH, 'USB sn for Rotator 2 can have maximum %d characters', 8);
	$form->addText('r2from', 'From Azimuth:')
		->setRequired('Add FROM azimuth for Rotator 2')
		->addRule(Form::INTEGER, 'FROM Rotator 2 must be number')
		->addRule(Form::RANGE, 'FROM Rotator 2 value must be from %d to %d', array(0, 450));
	$form->addText('r2to', 'To Azimuth:')
		->setRequired('Add TO azimuth for Rotator 2')
		->addRule(Form::INTEGER, 'TO Rotator 2 must be number')
		->addRule(Form::RANGE, 'TO Rotator 2 value must be from %d to %d', array(0, 450));

$form->addGroup('Rotator 3');
	$form->addText('r3name', 'Name:')
		->addRule(Form::MAX_LENGTH, 'Device name for Rotator 3 can have maximum %d characters', 25);
	$form->addText('r3idv', 'USB Vendor id:')
		->setRequired('Add USB Vendor ID for Rotator 3')
		->addRule(Form::LENGTH, 'USB Vendor ID for Rotator 3 must have at %d characters', 4);
	$form->addText('r3idp', 'USB Product id:')
		->setRequired('Add USB Product ID for Rotator 3')
		->addRule(Form::LENGTH, 'USB Product ID for Rotator 3 must have at %d characters', 4);
	$form->addText('r3sn', 'USB sn:')
		->setRequired('Add USB sn for Rotator 3')
		->addRule(Form::MIN_LENGTH, 'USB sn for Rotator 3 must have at least %d characters', 1)
		->addRule(Form::MAX_LENGTH, 'USB sn for Rotator 3 can have maximum %d characters', 8);
	$form->addText('r3from', 'From Azimuth:')
		->setRequired('Add FROM azimuth for Rotator 3')
		->addRule(Form::INTEGER, 'FROM Rotator 3 must be number')
		->addRule(Form::RANGE, 'FROM Rotator 3 value must be from %d to %d', array(0, 450));
	$form->addText('r3to', 'To Azimuth:')
		->setRequired('Add TO azimuth for Rotator 3')
		->addRule(Form::INTEGER, 'TO Rotator 3 must be number')
		->addRule(Form::RANGE, 'TO Rotator 3 value must be from %d to %d', array(0, 450));

$form->addGroup('Rotator 4');
	$form->addText('r4name', 'Name:')
		->addRule(Form::MAX_LENGTH, 'Device name for Rotator 4 can have maximum %d characters', 25);
	$form->addText('r4idv', 'USB Vendor id:')
		->setRequired('Add USB Vendor ID for Rotator 4')
		->addRule(Form::LENGTH, 'USB Vendor ID for Rotator 4 must have at %d characters', 4);
	$form->addText('r4idp', 'USB Product id:')
		->setRequired('Add USB Product ID for Rotator 4')
		->addRule(Form::LENGTH, 'USB Product ID for Rotator 4 must have at %d characters', 4);
	$form->addText('r4sn', 'USB sn:')
		->setRequired('Add USB sn for Rotator 4')
		->addRule(Form::MIN_LENGTH, 'USB sn for Rotator 4 must have at least %d characters', 1)
		->addRule(Form::MAX_LENGTH, 'USB sn for Rotator 4 can have maximum %d characters', 8);
	$form->addText('r4from', 'From Azimuth:')
		->setRequired('Add FROM azimuth for Rotator 4')
		->addRule(Form::INTEGER, 'FROM Rotator 4 must be number')
		->addRule(Form::RANGE, 'FROM Rotator 4 value must be from %d to %d', array(0, 450));
	$form->addText('r4to', 'To Azimuth:')
		->setRequired('Add TO azimuth for Rotator 4')
		->addRule(Form::INTEGER, 'TO Rotator 4 must be number')
		->addRule(Form::RANGE, 'TO Rotator 4 value must be from %d to %d', array(0, 450));

$form->addGroup('Rotator 5');
	$form->addText('r5name', 'Name:')
		->addRule(Form::MAX_LENGTH, 'Device name for Rotator 5 can have maximum %d characters', 25);
	$form->addText('r5idv', 'USB Vendor id:')
		->setRequired('Add USB Vendor ID for Rotator 5')
		->addRule(Form::LENGTH, 'USB Vendor ID for Rotator 5 must have at %d characters', 4);
	$form->addText('r5idp', 'USB Product id:')
		->setRequired('Add USB Product ID for Rotator 5')
		->addRule(Form::LENGTH, 'USB Product ID for Rotator 5 must have at %d characters', 4);
	$form->addText('r5sn', 'USB sn:')
		->setRequired('Add USB sn for Rotator 5')
		->addRule(Form::MIN_LENGTH, 'USB sn for Rotator 5 must have at least %d characters', 1)
		->addRule(Form::MAX_LENGTH, 'USB sn for Rotator 5 can have maximum %d characters', 8);
	$form->addText('r5from', 'From Azimuth:')
		->setRequired('Add FROM azimuth for Rotator 5')
		->addRule(Form::INTEGER, 'FROM Rotator 5 must be number')
		->addRule(Form::RANGE, 'FROM Rotator 5 value must be from %d to %d', array(0, 450));
	$form->addText('r5to', 'To Azimuth:')
		->setRequired('Add TO azimuth for Rotator 5')
		->addRule(Form::INTEGER, 'TO Rotator 5 must be number')
		->addRule(Form::RANGE, 'TO Rotator 5 value must be from %d to %d', array(0, 450));

$form->addGroup('Rotator 6');
	$form->addText('r6name', 'Name:')
		->addRule(Form::MAX_LENGTH, 'Device name for Rotator 6 can have maximum %d characters', 25);
	$form->addText('r6idv', 'USB Vendor id:')
		->setRequired('Add USB Vendor ID for Rotator 6')
		->addRule(Form::LENGTH, 'USB Vendor ID for Rotator 6 must have at %d characters', 4);
	$form->addText('r6idp', 'USB Product id:')
		->setRequired('Add USB Product ID for Rotator 6')
		->addRule(Form::LENGTH, 'USB Product ID for Rotator 6 must have at %d characters', 4);
	$form->addText('r6sn', 'USB sn:')
		->setRequired('Add USB sn for Rotator 6')
		->addRule(Form::MIN_LENGTH, 'USB sn for Rotator 6 must have at least %d characters', 1)
		->addRule(Form::MAX_LENGTH, 'USB sn for Rotator 6 can have maximum %d characters', 8);
	$form->addText('r6from', 'From Azimuth:')
		->setRequired('Add FROM azimuth for Rotator 6')
		->addRule(Form::INTEGER, 'FROM Rotator 6 must be number')
		->addRule(Form::RANGE, 'FROM Rotator 6 value must be from %d to %d', array(0, 450));
	$form->addText('r6to', 'To Azimuth:')
		->setRequired('Add TO azimuth for Rotator 6')
		->addRule(Form::INTEGER, 'TO Rotator 6 must be number')
		->addRule(Form::RANGE, 'TO Rotator 6 value must be from %d to %d', array(0, 450));

$form->addGroup('Rotator 7');
	$form->addText('r7name', 'Name:')
		->addRule(Form::MAX_LENGTH, 'Device name for Rotator 7 can have maximum %d characters', 25);
	$form->addText('r7idv', 'USB Vendor id:')
		->setRequired('Add USB Vendor ID for Rotator 7')
		->addRule(Form::LENGTH, 'USB Vendor ID for Rotator 7 must have at %d characters', 4);
	$form->addText('r7idp', 'USB Product id:')
		->setRequired('Add USB Product ID for Rotator 7')
		->addRule(Form::LENGTH, 'USB Product ID for Rotator 7 must have at %d characters', 4);
	$form->addText('r7sn', 'USB sn:')
		->setRequired('Add USB sn for Rotator 7')
		->addRule(Form::MIN_LENGTH, 'USB sn for Rotator 7 must have at least %d characters', 1)
		->addRule(Form::MAX_LENGTH, 'USB sn for Rotator 7 can have maximum %d characters', 8);
	$form->addText('r7from', 'From Azimuth:')
		->setRequired('Add FROM azimuth for Rotator 7')
		->addRule(Form::INTEGER, 'FROM Rotator 7 must be number')
		->addRule(Form::RANGE, 'FROM Rotator 7 value must be from %d to %d', array(0, 450));
	$form->addText('r7to', 'To Azimuth:')
		->setRequired('Add TO azimuth for Rotator 7')
		->addRule(Form::INTEGER, 'TO Rotator 7 must be number')
		->addRule(Form::RANGE, 'TO Rotator 7 value must be from %d to %d', array(0, 450));

$form->addGroup('Rotator 8');
	$form->addText('r8name', 'Name:')
		->addRule(Form::MAX_LENGTH, 'Device name for Rotator 8 can have maximum %d characters', 25);
	$form->addText('r8idv', 'USB Vendor id:')
		->setRequired('Add USB Vendor ID for Rotator 8')
		->addRule(Form::LENGTH, 'USB Vendor ID for Rotator 8 must have at %d characters', 4);
	$form->addText('r8idp', 'USB Product id:')
		->setRequired('Add USB Product ID for Rotator 8')
		->addRule(Form::LENGTH, 'USB Product ID for Rotator 8 must have at %d characters', 4);
	$form->addText('r8sn', 'USB sn:')
		->setRequired('Add USB sn for Rotator 8')
		->addRule(Form::MIN_LENGTH, 'USB sn for Rotator 8 must have at least %d characters', 1)
		->addRule(Form::MAX_LENGTH, 'USB sn for Rotator 8 can have maximum %d characters', 8);
	$form->addText('r8from', 'From Azimuth:')
		->setRequired('Add FROM azimuth for Rotator 8')
		->addRule(Form::INTEGER, 'FROM Rotator 8 must be number')
		->addRule(Form::RANGE, 'FROM Rotator 8 value must be from %d to %d', array(0, 450));
	$form->addText('r8to', 'To Azimuth:')
		->setRequired('Add TO azimuth for Rotator 8')
		->addRule(Form::INTEGER, 'TO Rotator 8 must be number')
		->addRule(Form::RANGE, 'TO Rotator 8 value must be from %d to %d', array(0, 450));


$form->addGroup('Azimuth map');
	$form->addText('loc', 'Your locator:')
		->setRequired('Add Your locator')
		->addRule(Form::LENGTH, 'Locator must be %d characters', 6)
		->addRule(Form::PATTERN, 'Locator format must be {First pair the letters from "A" to "R"}{Second pair the digits from "0" to "9"}{Third pair the letters from "A" to "X"}', '[A-Ra-r]{2}[0-9]{2}[A-Xa-x]{2}');
	$form->addSubmit('submit', 'Apply');
$form->setDefaults(array(
	'loc' => $loc,
	'r1name' => $r1name, 'r1idv' => $r1idv, 'r1idp' => $r1idp, 'r1sn' => $r1sn, 'r1from' => $r1from, 'r1to' => $r1to,
	'r2name' => $r2name, 'r2idv' => $r2idv, 'r2idp' => $r2idp, 'r2sn' => $r2sn, 'r2from' => $r2from, 'r2to' => $r2to,
	'r3name' => $r3name, 'r3idv' => $r3idv, 'r3idp' => $r3idp, 'r3sn' => $r3sn, 'r3from' => $r3from, 'r3to' => $r3to,
	'r4name' => $r4name, 'r4idv' => $r4idv, 'r4idp' => $r4idp, 'r4sn' => $r4sn, 'r4from' => $r4from, 'r4to' => $r4to,
	'r5name' => $r5name, 'r5idv' => $r5idv, 'r5idp' => $r5idp, 'r5sn' => $r5sn, 'r5from' => $r5from, 'r5to' => $r5to,
	'r6name' => $r6name, 'r6idv' => $r6idv, 'r6idp' => $r6idp, 'r6sn' => $r6sn, 'r6from' => $r6from, 'r6to' => $r6to,
	'r7name' => $r7name, 'r7idv' => $r7idv, 'r7idp' => $r7idp, 'r7sn' => $r7sn, 'r7from' => $r7from, 'r7to' => $r7to,
	'r8name' => $r8name, 'r8idv' => $r8idv, 'r8idp' => $r8idp, 'r8sn' => $r8sn, 'r8from' => $r8from, 'r8to' => $r8to,
	));
if ($form->isSuccess()) {
	$warn = "For detection new USB rotator interface repluged your usb device or reboot.<br>
	You can observe the result on the <a href=\"index.php\">Recognized USB devices.</a></p>";

	$values = $form->getValues();
	$fp = fopen('../cfg/s-rot-r1name', 'w'); fwrite($fp, $values->r1name); fclose($fp);
	$fp = fopen('../cfg/s-rot-r1idv', 'w'); fwrite($fp, $values->r1idv); fclose($fp);
	$fp = fopen('../cfg/s-rot-r1idp', 'w'); fwrite($fp, $values->r1idp); fclose($fp);
	$fp = fopen('../cfg/s-rot-r1sn', 'w'); fwrite($fp, $values->r1sn); fclose($fp);
	$fp = fopen('../cfg/s-rot-r1from', 'w'); fwrite($fp, $values->r1from); fclose($fp);
	$fp = fopen('../cfg/s-rot-r1to', 'w'); fwrite($fp, $values->r1to); fclose($fp);

	$fp = fopen('../cfg/s-rot-r2name', 'w'); fwrite($fp, $values->r2name); fclose($fp);
	$fp = fopen('../cfg/s-rot-r2idv', 'w'); fwrite($fp, $values->r2idv); fclose($fp);
	$fp = fopen('../cfg/s-rot-r2idp', 'w'); fwrite($fp, $values->r2idp); fclose($fp);
	$fp = fopen('../cfg/s-rot-r2sn', 'w'); fwrite($fp, $values->r2sn); fclose($fp);
	$fp = fopen('../cfg/s-rot-r2from', 'w'); fwrite($fp, $values->r2from); fclose($fp);
	$fp = fopen('../cfg/s-rot-r2to', 'w'); fwrite($fp, $values->r2to); fclose($fp);

	$fp = fopen('../cfg/s-rot-r3name', 'w'); fwrite($fp, $values->r3name); fclose($fp);
	$fp = fopen('../cfg/s-rot-r3idv', 'w'); fwrite($fp, $values->r3idv); fclose($fp);
	$fp = fopen('../cfg/s-rot-r3idp', 'w'); fwrite($fp, $values->r3idp); fclose($fp);
	$fp = fopen('../cfg/s-rot-r3sn', 'w'); fwrite($fp, $values->r3sn); fclose($fp);
	$fp = fopen('../cfg/s-rot-r3from', 'w'); fwrite($fp, $values->r3from); fclose($fp);
	$fp = fopen('../cfg/s-rot-r3to', 'w'); fwrite($fp, $values->r3to); fclose($fp);

	$fp = fopen('../cfg/s-rot-r4name', 'w'); fwrite($fp, $values->r4name); fclose($fp);
	$fp = fopen('../cfg/s-rot-r4idv', 'w'); fwrite($fp, $values->r4idv); fclose($fp);
	$fp = fopen('../cfg/s-rot-r4idp', 'w'); fwrite($fp, $values->r4idp); fclose($fp);
	$fp = fopen('../cfg/s-rot-r4sn', 'w'); fwrite($fp, $values->r4sn); fclose($fp);
	$fp = fopen('../cfg/s-rot-r4from', 'w'); fwrite($fp, $values->r4from); fclose($fp);
	$fp = fopen('../cfg/s-rot-r4to', 'w'); fwrite($fp, $values->r4to); fclose($fp);

	$fp = fopen('../cfg/s-rot-r5name', 'w'); fwrite($fp, $values->r5name); fclose($fp);
	$fp = fopen('../cfg/s-rot-r5idv', 'w'); fwrite($fp, $values->r5idv); fclose($fp);
	$fp = fopen('../cfg/s-rot-r5idp', 'w'); fwrite($fp, $values->r5idp); fclose($fp);
	$fp = fopen('../cfg/s-rot-r5sn', 'w'); fwrite($fp, $values->r5sn); fclose($fp);
	$fp = fopen('../cfg/s-rot-r5from', 'w'); fwrite($fp, $values->r5from); fclose($fp);
	$fp = fopen('../cfg/s-rot-r5to', 'w'); fwrite($fp, $values->r5to); fclose($fp);

	$fp = fopen('../cfg/s-rot-r6name', 'w'); fwrite($fp, $values->r6name); fclose($fp);
	$fp = fopen('../cfg/s-rot-r6idv', 'w'); fwrite($fp, $values->r6idv); fclose($fp);
	$fp = fopen('../cfg/s-rot-r6idp', 'w'); fwrite($fp, $values->r6idp); fclose($fp);
	$fp = fopen('../cfg/s-rot-r6sn', 'w'); fwrite($fp, $values->r6sn); fclose($fp);
	$fp = fopen('../cfg/s-rot-r6from', 'w'); fwrite($fp, $values->r6from); fclose($fp);
	$fp = fopen('../cfg/s-rot-r6to', 'w'); fwrite($fp, $values->r6to); fclose($fp);

	$fp = fopen('../cfg/s-rot-r7name', 'w'); fwrite($fp, $values->r7name); fclose($fp);
	$fp = fopen('../cfg/s-rot-r7idv', 'w'); fwrite($fp, $values->r7idv); fclose($fp);
	$fp = fopen('../cfg/s-rot-r7idp', 'w'); fwrite($fp, $values->r7idp); fclose($fp);
	$fp = fopen('../cfg/s-rot-r7sn', 'w'); fwrite($fp, $values->r7sn); fclose($fp);
	$fp = fopen('../cfg/s-rot-r7from', 'w'); fwrite($fp, $values->r7from); fclose($fp);
	$fp = fopen('../cfg/s-rot-r7to', 'w'); fwrite($fp, $values->r7to); fclose($fp);

	$fp = fopen('../cfg/s-rot-r8name', 'w'); fwrite($fp, $values->r8name); fclose($fp);
	$fp = fopen('../cfg/s-rot-r8idv', 'w'); fwrite($fp, $values->r8idv); fclose($fp);
	$fp = fopen('../cfg/s-rot-r8idp', 'w'); fwrite($fp, $values->r8idp); fclose($fp);
	$fp = fopen('../cfg/s-rot-r8sn', 'w'); fwrite($fp, $values->r8sn); fclose($fp);
	$fp = fopen('../cfg/s-rot-r8from', 'w'); fwrite($fp, $values->r8from); fclose($fp);
	$fp = fopen('../cfg/s-rot-r8to', 'w'); fwrite($fp, $values->r8to); fclose($fp);
	exec('../script/udev.sh > /etc/udev/rules.d/99-remoteqth.rules');

	$fp = fopen('../cfg/s-rot-loc', 'w'); fwrite($fp, $values->loc); fclose($fp);
	$path = getcwd();
	exec("$path/../script/azimuth-map.sh");
	}
?>
<p class="warn"><?php echo $warn ?></p>

<?php echo $form ?>

<p class="text2">Your Locator you can find here
<a href="http://f6fvy.free.fr/qthLocator/fullScreen.php" target="_blank" class="external">1</a>, 
<a href="http://no.nonsense.ee/qthmap/" target="_blank" class="external">2</a>, 
<a href="http://tk5ep.free.fr/googlemap/carto.php" target="_blank" class="external">3</a></p>

<p class="warn"><?php echo $warn ?></p>

<h2>Preview</h2>
<p class="text2">Rotators map with actual grayline and position of sun.<br>
The new map can be generated for longer than ten seconds. Wait, eventually refresh this page.</p>

<img class="map" src="azimuth-map.png">
<p class="next"><a href="s-sensors.php"><img src="previous.png" alt="previous page"></a><a href="s-cw.php"><img src="next.png" alt="next page"></a></p>

<!-- #################### /Obsah #################### -->
</div>
<div id="header"><?php include 'header.html';?></div>
</div><div id="leftmenu"><?php include 'menu.html';?></div>
</body>
</html>

