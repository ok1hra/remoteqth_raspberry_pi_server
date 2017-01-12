<?
/* grep -e ARM -e Geode /proc/cpuinfo
model name      : Geode(TM) Integrated Processor by AMD PCS
Processor	: ARMv6-compatible processor rev 7 (v6l)
model name	: ARMv6-compatible processor rev 7 (v6l)
Version: 1
built: echo $(date +%s)-1360000000 | bc
*/
// test HW naplni gobalni proennou $hw
function gethw() {
	global $hw;
//	$cpux = file('/proc/cpuinfo'); $cpu = $cpux[0]; 
//	$pi = substr('Processor	: ARMv6-compatible processor rev 7 (v6l)', 12, 5) ;
	$cpu = php_uname('m');
	$cpu2 = substr(exec('grep Geode /proc/cpuinfo'), 13, 5);
//	if ( substr($cpu, 12, 5) == $pi ) {
	if ( $cpu == 'armv6l' || $cpu == 'armv7l' ) {
		$hw = "PI";
	}
	elseif ( $cpu2 == 'Geode' ) {
		$hw = 'ALIX';
	}
#	Linux remoteqth 3.8.13-bone47 #1 SMP Fri Apr 11 01:36:09 UTC 2014 armv7l
	elseif ( preg_match("/bone(..)/",php_uname(''),$BBB) ) {
		$hw = 'BBB';
		//echo $BBB[0];
	}else {
		$hw = php_uname();
	}
}

// RPI board revision
function rpirev() {
	// HEX
	//$text = file('/proc/cmdline');
	//preg_match("/.boardrev=(....)/",$text[0],$matches);
	//return rtrim($matches[1]);
	// DEC
	$dec = file('/sys/module/bcm2708/parameters/boardrev');
	return trim($dec[0]);
}
function rpi2rev() {
	// nahrada grep
	$cpuinfo = file_get_contents ('/proc/cpuinfo');   // vypis filesystemu do promenne
	$findme   = 'Revision';                           // hledany retezec
	$pos = strpos($cpuinfo, $findme);                 // pozice hledaneho retezce v promenne
	$next = strpos($cpuinfo, 'Serial');               // pozice nasledujiciho retezce (radku)
	$long= $next-$pos-11;                             // rozdil pozic = delka hledaneho retezce
	$revision = trim (substr($cpuinfo, $pos+11, $long));          // hledana promenna je za hledanym retezcem na pozici $pos+11, dlouha $long
	return $revision;
}


function availableUrl($host, $port, $timeout) { 
  $fp = fSockOpen($host, $port, $errno, $errstr, $timeout); 
  return $fp!=false;
}
//Return "true" if the url is available, false if not.
//echo availableUrl("www.google.com");


// precteni souboru
//function rxfile($file) {
//	global $path;
//	$valuex = file($path.$file);
//	return $valuex[0];
//}

// precteni souboru, pokud neexistuje, vytvorit
function rxfile($file) {
	global $path;
	if (file_exists($path.$file)) {
		if ( 0 == filesize( $path.$file ) ) {
		    // file is empty
		} else {
			$valuex = file($path.$file);
			return $valuex[0];
		}
	} else {
		file_put_contents($path.$file, '');
		$valuex = file($path.$file);
		//return $valuex[0];
	}
}

// precteni souboru, pokud neexistuje, vytvorit - bez $path
function rxfile2($file) {
	if (file_exists($file)) {
		if ( 0 == filesize( $file ) ) {
		    // file is empty
		} else {
			$valuex = file($file);
			return $valuex[0];
		}
	} else {
		file_put_contents($file, '');
		$valuex = file($file);
		//return $valuex[0];
	}
}

// zapsani souboru
function txfile($file, $values) {
	global $path;
	$fp = fopen($path.$file, 'w');
	fwrite($fp, $values);
	fclose($fp);
}
// zapsani souboru
function txfile2($file, $values) {
	$fp = fopen($file, 'w');
	fwrite($fp, $values);
	fclose($fp);
}

// txrxIP socket
function txrxtcp($IP, $port, $message) {
	if(!($sock = socket_create(AF_INET, SOCK_STREAM, 0)))
	{
		$errorcode = socket_last_error();
		$errormsg = socket_strerror($errorcode);
		die("Couldn't create socket: [$errorcode] $errormsg \n");
	}
	//echo "Socket created \n";
	//Connect socket to remote server
	if(!socket_connect($sock , $IP , $port))
	{
		$errorcode = socket_last_error();
		$errormsg = socket_strerror($errorcode);
		die("Could not connect: [$errorcode] $errormsg \n");
	}
	//echo "Connection established \n";
	//$message = "C\r";
	//Send the message to the server
	if( ! socket_send ( $sock , $message , strlen($message) , 0))
	{
		$errorcode = socket_last_error();
		$errormsg = socket_strerror($errorcode);
		die("Could not send data: [$errorcode] $errormsg \n");
	}
	//echo "Message send successfully \n";
	//Now receive reply from server
	if(socket_recv ( $sock , $buf , 6 , MSG_WAITALL ) === FALSE)
	{
		$errorcode = socket_last_error();
		$errormsg = socket_strerror($errorcode);
		die("Could not receive data: [$errorcode] $errormsg \n");
	}
	//print the received message
	socket_close($sock);
	return $buf;
}

// txIP socket
function txtcp($IP, $port, $message) {
	if(!($sock = socket_create(AF_INET, SOCK_STREAM, 0)))
	{
		$errorcode = socket_last_error();
		$errormsg = socket_strerror($errorcode);
		die("Couldn't create socket: [$errorcode] $errormsg \n");
	}
	//echo "Socket created \n";
	//Connect socket to remote server
	if(!socket_connect($sock , $IP , $port))
	{
		$errorcode = socket_last_error();
		$errormsg = socket_strerror($errorcode);
		die("Could not connect: [$errorcode] $errormsg \n");
	}
	//echo "Connection established \n";
	//$message = "M$rotate\r";
	//Send the message to the server
	if( ! socket_send ( $sock , $message , strlen($message) , 0))
	{
		$errorcode = socket_last_error();
		$errormsg = socket_strerror($errorcode);
		die("Could not send data: [$errorcode] $errormsg \n");
	}
	//echo "Message send successfully \n";
	socket_close($sock);
}

// txUDP socket
// x=IP y=port z=message
function udpsocket($x, $y, $z)
{
	// SOCK_STREAM = full-duplex, connection-based byte streams
	// SOCK_DGRAM = UDP datagrams
	if(!($sock = socket_create(AF_INET, SOCK_DGRAM, 0)))
	{
		$errorcode = socket_last_error();
		$errormsg = socket_strerror($errorcode);
		die("Couldn't create socket: [$errorcode] $errormsg \n");
	}
	//echo "Socket created \n";
	//Connect socket to remote server
	if(!socket_connect($sock , $x , $y))
	{
		$errorcode = socket_last_error();
		$errormsg = socket_strerror($errorcode);
		die("Could not connect: [$errorcode] $errormsg \n");
	}
	//echo "Connection established \n";
	//$message = "$cwmem\r";
	//Send the message to the server
	if( ! socket_send ( $sock , $z , strlen($z) , 0))
	{
		$errorcode = socket_last_error();
		$errormsg = socket_strerror($errorcode);
		die("Could not send data: [$errorcode] $errormsg \n");
	}
	//echo "Message send successfully \n";
	socket_close($sock);
return true;
}

// actual page file
function actualpage() {
	$currentFile = $_SERVER["PHP_SELF"];
	$parts = Explode('/', $currentFile);
	return $parts[count($parts) - 1];
}

// disk space bar
function diskspace($cesta) {
	global $df, $dp, $du ;
	/* get disk space free (in bytes) */
	$df = disk_free_space("$cesta");
	/* and get disk space total (in bytes)  */
	$dt = disk_total_space("$cesta");
	/* now we calculate the disk space used (in bytes) */
	$du = $dt - $df;
	/* percentage of disk used - this will be used to also set the width % of the progress bar */
	$dp = sprintf('%.2f',($du / $dt) * 100);

	/* and we formate the size from bytes to MB, GB, etc. */
	$df = formatSize($df);
	$du = formatSize($du);
	$dt = formatSize($dt);
}
function formatSize( $bytes ) {
	$types = array( 'B', 'KB', 'MB', 'GB', 'TB' );
	for( $i = 0; $bytes >= 1024 && $i < ( count( $types ) -1 ); $bytes /= 1024, $i++ );
		return( round( $bytes, 1 ) . " " . $types[$i] );
}

function temp($temp, $hw) {
	if ( $hw == "PI" ) {
		$i2cbus = rxfile('../cfg/rpii2cbus');
	}elseif ( $hw == "BBB" ) {
		$i2cbus = rxfile('../cfg/bbbi2cbus');
	}else {
	}
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
	return $data;
}

// GET URL with password
function rxurlpass($ip, $file, $usr, $pass) {
	$url='http://'.$ip.'/'.$file;
	$opts = array('http' =>
	  array(
	    'method'  => 'POST',
	    'header'  => "Content-Type: text/xml\r\n".
	      "Authorization: Basic ".base64_encode("$usr:$pass")."\r\n",
	    'content' => $url, //$body
	    'timeout' => 60
	  )
	);
	$context  = stream_context_create($opts);
	$result = file_get_contents($url, false, $context, -1, 40000);
	return $result;
}

function qso($file) {
	$lines = count(file($file));
	return $lines;
}
?>
