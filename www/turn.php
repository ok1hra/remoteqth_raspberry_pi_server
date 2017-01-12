<?
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Expires: -1");
header("Expires: -1");

//nastaveni promennych
require 'function.php';
$path = "../cfg/";
$rot = $_GET['rot'];
$server = $_GET['server'];
$IP = $_GET['ip'];
$port =  $_GET['port'];
$az = $_GET['turn'];
$external =  $_GET['external'];

if ( $external = "n" ) {
	$rotget = rxfile("s-rot-r{$rot}get");
	$rotset = rxfile("s-rot-r{$rot}set");
}else{
	$rotget = rxfile("s-rot-s{$server}r{$rot}get");
	$rotset = rxfile("s-rot-s{$server}r{$rot}set");
}

// turn azimut
// leading zeros
$rotate = sprintf('%03d', $az);
// replace \r, \n and # to azimuth
$raw = txrxtcp($IP, $port, str_replace(
	array('\r', '\n', '#'),
	array("\r", "\n", "$rotate"), $rotset));
$cut = Trim($raw);
$az = substr("$cut", 3, 3);
?>
