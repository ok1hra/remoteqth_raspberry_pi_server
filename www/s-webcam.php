<!doctype html>
<html>
<head>
	<title><? include '../cfg/s-login-note';?> | Webcam</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="styles.css" rel="stylesheet" type="text/css">
	<script src="netteForms.js"></script>
</head>
<body scroll="no">
<div id="obsah">
<!-- #################### Obsah #################### -->
<span style="position: absolute; top: 30px; left: 360px; z-index: auto"><img src="s-webcam.png"></span>
<p class="text2">More about settings in <a href="http://remoteqth.com/wiki/index.php?page=Set+Webcam" target="_blank" class="external">Wiki</a></p>

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

//$path = "../cfg/";
$onx = rxfile2("../cfg/s-webcam-on"); $on= $onx[0];
$note = rxfile2("../cfg/s-webcam-note");
$norm = rxfile2("../cfg/s-webcam-norm");
$hf = rxfile2("../cfg/s-webcam-hf");
$vf = rxfile2("../cfg/s-webcam-vf");
$gpio5 = Html::el()->setHtml("<br><span style=\"color: #f00\">Enable the camera will automatically switches relay 16 (GPIO5) each minute during shooting.</span>");

$form = new Form;
$form->setMethod('POST');
$form->addGroup('RaspiCam')
	->setOption('embedNext', TRUE);
	$onx = array(
		'1' => 'Enable',
		'0' => 'Disable',
		);
		$form->addSelect('on', 'Webcam:', $onx)
		//$form->addCheckbox('dhcp', 'DHCP')
		->addCondition($form::EQUAL, TRUE) // conditional rule: if is checkbox checked...
		->toggle('sendBox'); // toggle div #sendBox
$form->addGroup()
	->setOption('container', Html::el('div')->id('sendBox'));
	$form->addText('note', 'Note:', 50)
		->setRequired(TRUE)
		->setOption('description', $gpio5)
		->addRule(Form::MAX_LENGTH, 'Note can have maximum %d characters', 50);
	$form->addCheckbox('norm', 'Equalize image');
	$form->addCheckbox('hf', Html::el('span')
	        ->setHtml('Horizontal flip &#8596;'));
	$form->addCheckbox('vf', Html::el('span')
	        ->setHtml('Vertical flip &#8597;'));

$form->addGroup('');
$form->addSubmit('submit', 'Apply');

$form->setDefaults(array(
	'on' => $on, 'note' => $note, 'norm' => $norm, 'hf' => $hf, 'vf' => $vf,
	));
if ($form->isSuccess()) {
	$values = $form->getValues();
	txfile2("../cfg/s-webcam-on", $values->on);
	txfile2("../cfg/s-webcam-note", $values->note);
	txfile2("../cfg/s-webcam-norm", $values->norm);
	txfile2("../cfg/s-webcam-hf", $values->hf);
	txfile2("../cfg/s-webcam-vf", $values->vf);
}

echo $form; ?>

<p class="next"><a href="s-band-decoder.php"><img src="previous.png" alt="previous page"></a><a href="s-backup.php"><img src="next.png" alt="next page"></a></p>

<!-- #################### /Obsah #################### -->
</div>
<div id="header"><?php include 'header.html';?></div>
</div><div id="leftmenu"><?php include 'menu.html';?></div>
</body>
</html>

