<!doctype html>
<html>
<head>
	<title><? include '../cfg/s-login-note';?> | RemoteQTH server update</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body scroll="no">
<div id="obsah">
<!-- #################### Obsah #################### -->
<?php require 'function.php';
$logdate= date('Y-m-d_H:i') ; ?>

<form action="index.php" method="POST">
<h1>Server updating status</h1>
<p class="warn">We also recommend updating the system Raspbian<br>and firmware Raspberry PI, see to <a href="http://remoteqth.com/wiki/index.php?page=Raspberry+PI+update"  target="_blank" class="external">wiki</a></p>
	<p class="status1"><?
	//vykonani tlacitka update, s kontrolou navratoveho kodu
	$cesta = getcwd();
	echo exec("sudo /usr/bin/rsync -rva --numeric-ids --delete --exclude='/cfg/' --exclude='/log/' --exclude='.htpasswd' rsync://remoteqth.com:55873/server/ $cesta/../ > ../log/$logdate-update.log 2>&1 && echo '<span style=\"color: #444\"> Update done...</span>' || echo '<span style=\"color: #ff0000\"> Update FAIL...</span>'");
	echo exec("sudo $cesta/../script/update.sh >> ../log/$logdate-update.log");
	?>&nbsp;<input type="submit" name="update" value="back"><p>
</form>

<h2>Update log</h2>
<pre><?php include "../log/$logdate-update.log"; ?></pre>

<h2>Changelog</h2>
<div class="changelog">
	<? $file = file_get_contents('http://remoteqth.com/wiki/index.php?page=Changelog');
	//$body = preg_replace("/.*<body[^>]*>|<\/body>.*/si", "", $file);
	$body = preg_replace("/.*download<\/a><\/p[^>]*>|<table.*/si", "", $file);
	echo $body ?>
</div>

<!-- #################### /Obsah #################### -->
</div>
<div id="header"><?php include 'header.html';?></div>
</div><div id="leftmenu"><?php include 'menu.html';?></div>
</body>
</html>

