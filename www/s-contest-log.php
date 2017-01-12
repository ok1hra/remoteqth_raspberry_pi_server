<!doctype html>
<html>
<head>
	<title><? include '../cfg/s-login-note';?> | Simple WEB contest LOG form</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="styles.css" rel="stylesheet" type="text/css">
	<script src="netteForms.js"></script>
</head>
<body scroll="no">
<div id="obsah">
<!-- #################### Obsah #################### -->
<span style="position: absolute; top: 10px; left: 400px; z-index: 0"><img src="log.png"></span>
<p class="text2">More about settings in <a href="http://remoteqth.com/wiki/index.php?page=Simple+WEB+contest+LOG" target="_blank" class="external">Wiki</a></p>

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
$rigctld = rxfile("s-rigctld-on");
date_default_timezone_set('UTC');
$date = date('Y-m-d-', time());

$form = new Form('druhy');
$form->setMethod('POST');
// generovani formulare

if ( rxfile("s-rigctld-on") == "0" ) {
	echo '<p class="warn">RIGctld disable - <a href="s-rigctld.php">settings</a></p>';
}else{
	// formular
	$form->addGroup("New contest");
	$form->addText('name', 'Contest name:')
		->setRequired('Enter contest name')
		->addRule(Form::MIN_LENGTH, 'Name must have at least %d characters', 3)
		->addRule(Form::MAX_LENGTH, 'Name can have maximum %d characters', 12)
		->addRule(Form::PATTERN, 'Name alow characters only letters and digits', '[A-Za-z0-9\-]*');
	$form->addText('call', 'Callsign:')
		->setRequired('Enter your callsign')
		->addRule(Form::MIN_LENGTH, 'Callsign must have at least %d characters', 3)
		->addRule(Form::MAX_LENGTH, 'Callsign can have maximum %d characters', 12)
		->addRule(Form::PATTERN, 'Callsign alow characters only letters and digits', '[A-Za-z0-9]*');
	$form->addText('exch', 'TX Exchange:')
		->setOption('description', 'NR for QSO number')
		->setRequired('Enter contest TX exchange')
		->addRule(Form::MIN_LENGTH, 'Exchange must have at least %d characters', 1)
		->addRule(Form::MAX_LENGTH, 'Exchange can have maximum %d characters', 6)
		->addRule(Form::PATTERN, 'Exchange alow characters only letters and digits', '[A-Za-z0-9#]*');
	// nacteni default (predchozich) hodnot
	// jmena poli se naplni hodnotou pole
	//$form->setDefaults(array(
	//	'name' => $name, 'call' => $call, 'exch' => $exch,
	//));
	$form->addSubmit('submit', 'Add');
}
if ($form->isSuccess()) {
	$warn = "";

	$values = $form->getValues();
	// ulozeni promennych

	rxfile("contest-table");
	//if (file_exists("$path.'contest-table'")) { } else {       // if adif dont exist, create
	//	file_put_contents("$path.'contest-table'", '');
	//	$valuex = file("$path.'contest-table'");
	//}
	$name = strtoupper($values->name);
	$call = strtoupper($values->call);
	$exch = strtoupper($values->exch);
	file_put_contents('../cfg/contest-table', '<tr>'."\n\t".'<td>'.$date.$name.'</td>'."\n\t".'<td>'.$call.'</td>'."\n\t".'<td class="center">'.$exch.'</td>'."\n\t".'<td><a href="../log/'.$date.$name.'.txt">.txt</a></td>'."\n\t".'<td><a href="../log/'.$date.$name.'.adif">.adif</a></td>'."\n\t".'<td class="center"><? if (file_exists("../log/'.$date.$name.'.txt")) { echo qso("../log/'.$date.$name.'.txt");} else { echo "-";}?></td>'."\n\t".'<td class="center"><a href="om.php?s=run&log='.$date.$name.'&call='.$call.'&exch='.$exch.'" onclick="window.open( this.href, this.href, \'width=550,height=300,left=0,top=0,menubar=no,location=no,status=no\' ); return false;"  title="SCL"><img src="split.png" alt="split window"></a></td>'."\n".'</tr>'."\n", FILE_APPEND);  // add contest to table

	//txfile("contest-table", $values->model);
	//exec('../script/udev.sh > /etc/udev/rules.d/99-remoteqth.rules');
} ?>
<? echo $form; ?>


<h1>Already created contest</h1>
<table  class="rot">
<tr class="prvni">
	<th>Date-Name</th>
	<th>Call</th>
	<th>Exch</th>
	<th colspan="2">LOG</th>
	<th>#QSO</th>
	<th>Open Log</th>
</tr>
<? 
if (file_exists('../cfg/contest-table')) {
	include '../cfg/contest-table';
}?>
</table>

<p class="warn"><?php echo $warn ?></p>

<!-- <form action="s-rigctld.php" method="POST"><input type="submit" name="restart" value="Restart rigctld"></form>
<? if (isset($_POST['restart'])) {
	exec('sudo ../script/rig.sh restart');
} ?> -->

<p class="next"><a href="s-ser2net.php"><img src="previous.png" alt="previous page"></a><a href="s-band-decoder.php"><img src="next.png" alt="next page"></a></p>

<!-- #################### /Obsah #################### -->
</div>
<div id="header"><?php include 'header.html';?></div>
</div><div id="leftmenu"><?php include 'menu.html';?></div>
</body>
</html>
