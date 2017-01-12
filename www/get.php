<?
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Expires: -1");
header("Expires: -1");

require 'function.php';
$path = "../cfg/";
$q = $_GET['q'];

// RPI rev.
if ($q == "0") {
	$rev = rpi2rev();
	echo $rev;
}


// freq
if ($q == "1") {
	$IP = '127.0.0.1';
	$port =  '4532';
	//$raw = txrxtcp($IP, $port, 'f');
	$raw = exec("rigctl -m 2 -r $IP f");
	echo $raw;
}

// QTF
if ($q == "2") {
	//nastaveni promennych
	$rot = $_GET['rot'];
	$server = $_GET['server'];
	$IP = $_GET['ip'];
	$port =  $_GET['port'];
	$external =  $_GET['external'];
	if ( $external = "n" ) {
		$rotget = rxfile("s-rot-r{$rot}get");
		$rotset = rxfile("s-rot-r{$rot}set");
	}else{
		$rotget = rxfile("s-rot-s{$server}r{$rot}get");
		$rotset = rxfile("s-rot-s{$server}r{$rot}set");
	}
	// get azimut
	$raw = txrxtcp($IP, $port, str_replace(
		array('\r', '\n'),
		array("\r", "\n"), $rotget));
	$cut = Trim($raw); //Strip whitespace (or other characters) from the beginning and end of a string
	$azz = substr("$cut", -3);

	if (is_numeric($azz) && $azz < 360 ) {
	        echo "{$azz}";
	    } else {
	        echo 'Err';
	    }
	// bash stress test
	// c=1; while true; do ((c++)); wget --http-user=yourcall --http-passwd=1234 "http://192.168.1.19/get.php?q=2&rot=2&ip=127.0.0.1&port=91&external=n&server=no" -q -O - ; echo -e " $c\r"; done
}

// temp
if ($q == "3") {
	$temp =  $_GET['temp'];
	$i2cbus = rxfile('rpii2cbus');
	$adress = rxfile("s-sensors-temp{$temp}");
	// rawdata z cidla
	$dataraw = exec("sudo i2cget -y $i2cbus 0x$adress 0x00 w");
	// prevod dvou bajtu na dekadicke
	$data = hexdec(substr($dataraw,4,2)) ;
	// bajt s hodnotou 0.5 stupne
	$half = substr($dataraw,2,1);
	if ($half > 1 ) { // pokud je vetsi, pricist
		$data= (0.5+$data);
	}
	if ($data > 128 ) { //detekce zaporneho bitu
		$data= (256-$data)*-1;
	}
	echo $data;
}

// band decoder
if ($q == "4") {
	$path = "";  // zmenena cesta !
	echo rxfile('band-decoder-status');
}

// get file
if ($q == "gf") {
	$file = $_GET['file'];
	echo rxfile("$file");
}
// set file
if ($q == "sf") {
	$file = $_GET['file'];
	$value = $_GET['value'];
	txfile($file, $value);
}

// gpio band decoder status
if ($q == "gbds") {
	$gpio = $_GET['gpio'];
	echo rxfile("gpio$gpio");
}

?>
