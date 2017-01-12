<!doctype html>
<html>
<head>
	<title><? include '../cfg/s-login-note';?> | Reboot</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="styles.css" rel="stylesheet" type="text/css">
	<script src="netteForms.js"></script>
</head>
<body scroll="no">
<div id="obsah">
<!-- #################### Obsah #################### -->
<span style="position: absolute; top: 90px; left: 420px; z-index: auto"><img src="stop.png"></span>

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

$warn = '';

$form = new Form;
$form->setMethod('POST');
$form->addGroup('Server');

	$form->addSelect('stop', '', array(
		'r' => 'Reboot',
		'h' => 'Stop',
		));
	$form->addText('confirm', 'For confirm insert (Yes)')
		->setRequired('Insert \'YES\'')
		->addRule(Form::PATTERN, 'Must write \'YES\'', '[Yy][Ee][Ss]');

$form->addSubmit('submit', 'Now');

if ($form->isSuccess()) {
	$warn = "Server will be stoped, wait...";
	$values = $form->getValues();

	$stop = $_POST['stop'];
	exec("sudo /sbin/shutdown -$stop now");
	}
?>

<?php echo $form ?>



<p class="warn"><?php echo $warn ?></p>

<p class="next"><a href="s-login.php"><img src="previous.png" alt="previous page"></a><a href="s-vpn.php"><img src="next.png" alt="next page"></a></p>

<!-- #################### /Obsah #################### -->
</div>
<div id="header"><?php include 'header.html';?></div>
</div><div id="leftmenu"><?php include 'menu.html';?></div>
</body>
</html>

