<!doctype html>
<html>
<head>
	<title><? include '../cfg/s-login-note';?> | Backup</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="styles.css" rel="stylesheet" type="text/css">
	<script src="netteForms.js"></script>
</head>
<body scroll="no">
<div id="obsah">
<!-- #################### Obsah #################### -->
<span style="position: absolute; top: 20px; left: 300px; z-index: auto"><img src="s-backup.png"></span>
<p class="text2">More about settings in <a href="http://remoteqth.com/wiki/index.php?page=Backup" target="_blank" class="external">Wiki</a></p>

<?php
require 'function.php';
//include 'nette.php';
require 'Nette/loader.php';
use Nette\Forms\Form,
//	Nette\Diagnostics\Debugger,
	Nette\Utils\Html;
//debugger::enable();
//$configurator = new Nette\Config\Configurator; 
//$configurator->setDebugMode(TRUE);
//$configurator->enableDebugger(__DIR__ . '/../log');

$warn = '';
$path = getcwd();
$form = new Form;
$form->setMethod('POST');
$form->addGroup('Backup configuration');

$form->addSubmit('submit', 'Apply');

if ($form->isSuccess()) {
	$warn = "Now download <a href=\"remoteqth-server.tar.gz\"  class=\"external\">backup file</a></p>";
	$values = $form->getValues();
//	$path = getcwd();
	exec("rm /tmp/remoteqth-server.tar.gz; tar -czf /tmp/remoteqth-server.tar.gz $path/../cfg/");
	}

echo $form; ?>


<p class="warn"><?php echo $warn ?></p>
<p class="next"><a href="s-webcam.php"><img src="previous.png" alt="previous page"></a></p>

<!-- #################### /Obsah #################### -->
</div>
<div id="header"><?php include 'header.html';?></div>
</div><div id="leftmenu"><?php include 'menu.html';?></div>
</body>
</html>

