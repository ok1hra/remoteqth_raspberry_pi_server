<!doctype html>
<html>
<head>
	<title><? include '../cfg/s-login-note';?> | Login</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="styles.css" rel="stylesheet" type="text/css">
	<script src="netteForms.js"></script>
</head>
<body scroll="no">
<div id="obsah">
<!-- #################### Obsah #################### -->
<span style="position: absolute; top: 20px; left: 360px; z-index: auto"><img src="s-login.png"></span>
<p class="text2">More about settings in <a href="http://remoteqth.com/wiki/index.php?page=Login" class="external" target="_blank">Wiki</a></p>

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
// ziskani hodnoty ze soboru
$znacka = file('../cfg/s-login-call');
	// prvni radek [0] dej do $call
	$call= $znacka[0];
$majl = file('../cfg/s-login-mail');
	$email= $majl[0];
$note = rxfile2('../cfg/s-login-note');
$label1 = Html::el()->setHtml('also use as VPN login and shown in header web interface');
$label2 = Html::el()->setHtml('shown right in header web interface');


$form = new Form;
$form->setMethod('POST');
// group login
$form->addGroup('Web login')
	->setOption('description', '');
	$form->addText('call', 'Your callsign:')
		->setOption('description', $label1)
		->setRequired('Enter your callsign')
		->addRule(Form::MIN_LENGTH, 'Callsign must have at least %d characters', 3)
		->addRule(Form::MAX_LENGTH, 'Callsign can have maximum %d characters', 12)
		->addRule(Form::PATTERN, 'Callsign alow characters only letters and digits', '[A-Za-z0-9]*');
	 	// ->setDefaultValue('OK1CDJ');
	$form->addPassword('password', 'New password:')
		->setRequired('Choose your password')
		->addRule($form::MIN_LENGTH, 'The password is too short: it must be at least %d characters', 4);
	$form->addPassword('password2', 'Reenter password:')
		->addConditionOn($form['password'], $form::VALID)
			->addRule($form::FILLED, 'Reenter your password')
			->addRule($form::EQUAL, 'Passwords do not match', $form['password']);
	$form->addText('note', 'Note:')
		->setOption('description', $label2)
		->addRule(Form::MAX_LENGTH, 'Note can have maximum %d characters', 50);
	$form->addText('email', 'Email:')
		->setEmptyValue('@')
		->addCondition($form::FILLED) // conditional rule: if is email filled, ...
			->addRule($form::EMAIL, 'Incorrect email address'); // ... then check email
	$form->addSubmit('submit', 'Apply');
//konec formulare
// hromadne naplneni policek starymi daty
$form->setDefaults(array(
	'call' => $call,
	'email' => $email,
	'note' => $note,
	));
// jeho zpracovani
if ($form->isSuccess()) {
	//echo 'Form set OK :)';
	$values = $form->getValues();
	//echo $values->call;
	$fp = fopen('../cfg/s-login-call', 'w');
	fwrite($fp, $values->call);
	fclose($fp);
	//echo $values->password;
	$fp = fopen('../cfg/s-login-pass', 'w');
	fwrite($fp, $values->password);
	fclose($fp);
	$call = $_POST['call'];
	$password = $_POST['password'];
	txfile("s-login-note", $values->note);
	$path = getcwd();
	exec("htpasswd -cb $path/.htpasswd $call '$password'");
	//print_r($values);
	}

echo $form ?>

<p class="next"><a href="s-net.php"><img src="next.png" alt="next page"></a></p>

<!-- #################### /Obsah #################### -->
</div>
<div id="header"><?php include 'header.html';?></div>
<div id="leftmenu"><?php include 'menu.html';?></div>
</body>
</html>
