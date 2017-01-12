<!doctype html>
<html>
<body bgcolor="#000">
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

// nastaveni promennych
$path = "../cfg/";
$call= rxfile('s-login-call'); ?>
<p class="mobtitul"><?php include 's-login-note';?></p>
<!--------------------------------------------------------- sensors  -->
<? $pocettemp = rxfile('s-sensors-temps');
$pocetadp = rxfile("s-sensors-ad");
$sensors = $pocettemp + $pocetadp;
//if ($sensors == "0") {
//} else {
	echo '<div class="mob">';
	require 'c-sensors2dark.php'; 
	echo '</div>';
//}
?>
<!--------------------------------------------------------- relays   -->
	<? gethw();
		if ( $hw == "PI" ) {


			echo '<div class="splitwindow">';
			$path = "../cfg/";
			$info = '';
			$server = "0";
			$lines = "0" ;
			$table = 'black';
			// definice barev
			$ON = '#c00';
			$OFF = '#000';
			$THROW = '#090';
			$TEXT = '#eee';

			echo '<span style="color: '.$TEXT.'"><b>'.rxfile('s-login-note').'</b></span>';
			echo '<form action="mob.php" method="POST">';
			require 'c-relay-pi-s.php';
			echo '</form>';
			$nrservers = rxfile('s-rot2-servers');
			if ( $nrservers != "0" ) {
				for ($server = 1; $server < $nrservers+1; ++$server) {
					echo '<span style="color: '.$TEXT.'"><b>'.rxfile("s-rot-s{$server}note").'</b></span>' ;
					echo '<form action="mob.php" method="POST">';
					include 'c-relay-pi-s.php';
					echo '</form>';
				}
			}
			echo '</div>';

/*			echo '<div class="mob3">';
			$info = '';
			$server = "0";
			$lines = "0" ;
			$table = 'black';
			// definice barev
			$ON = '#c00';
			$OFF = '#000';
			$THROW = '#090';
			$TEXT = '#eee';
			echo '<form action="mob.php" method="POST">';
				require 'c-relay-pi-s.php';
			echo '</form>';
			$nrservers = rxfile('s-rot2-servers');
			if ( $nrservers != "0" ) {
				for ($server = 1; $server < $nrservers+1; ++$server) {
					echo '<b>'.rxfile("s-rot-s{$server}note").'</b>' ;
					echo '<form action="mob.php" method="POST">';
					include 'c-relay-pi-s.php';
					echo '</form>';
				}
			}
			$saverelay = rxfile("s-relay-save");
			if ( $saverelay == "1" ) {
				echo '<span style="color: #08f">Stored settings will be set after reboot.</span>';
			}
			echo '</div>'; */
		}
		else 	{
			echo '<div class="mob">';
			require 'c-relay-lpt2dark.php' ;
			echo '</div>';
		}
		//echo "<br>$info" ?>
</div>
<!--------------------------------------------------------- rorators -->
<? $pocetrot = rxfile('s-rot-rots');
$rotselect = rxfile('s-rot-rotselect');

if ($rotselect == "") {
	$rotselect = 1;
}
if ($pocetrot == "0") {
}
else {
	$form1 = new Form('prvni');
	$form1->setMethod('POST');
		$rotselectx = array(
			/* for($rot=1; $rot < $rotselect+1; $rot++) {
			$rot => rxfile("s-rot-r{$rot}name"),
			} */
			'1' => rxfile("s-rot-r1name"),
			'2' => rxfile("s-rot-r2name"),
			'3' => rxfile("s-rot-r3name"),
			'4' => rxfile("s-rot-r4name"),
			'5' => rxfile("s-rot-r5name"),
			'6' => rxfile("s-rot-r6name"),
			'7' => rxfile("s-rot-r7name"),
			'8' => rxfile("s-rot-r8name"),
			);
		$form1->addSelect('rotselect', 'Select Rotator', $rotselectx)
			->setAttribute('onchange', 'submit()')
			->setDefaultValue($rotselect);
		if ($form1->isSuccess()) {
			$values = $form1->getValues();
			$rotselect = $values->rotselect;
			txfile('s-rot-rotselect', $values->rotselect);
		}
	echo $form1 ;

	// nastaveni promennych
	$rotator = $rotselect ; //$_GET['rot'];
	$IP = '127.0.0.1' ;
	$port = $rotator + 90 ;
	$rotname = rxfile("../cfg/s-rot-r{$rotator}name");
	$rotl = rxfile("../cfg/s-rot-r{$rotator}from");
	$rotr = rxfile("../cfg/s-rot-r{$rotator}to");
	$rotget = rxfile("../cfg/s-rot-r{$rotator}get");
	$rotset = rxfile("../cfg/s-rot-r{$rotator}set");

	$form = new Form('druhy');
	$form->setMethod('POST');
		$form->addText('azimuth', "$rotname:", 3)
			->setRequired('Choose Azimuth')
			->addRule(Form::MAX_LENGTH, 'Azimuth can have maximum %d characters', 3)
			->addRule(Form::INTEGER, 'Azimuth must be number')
			->addRule(Form::RANGE, 'Azimuth value must be from %d to %d', array(0, 359))
			->addRule($rotl > $rotr ? ~Form::RANGE : Form::RANGE, "Out of rotator range $rotl - $rotr °", array(min($rotl, $rotr), max($rotl, $rotr)));
	
	$form->addSubmit('submit', 'Rotate');
	if ($form->isSuccess()) {
		$values = $form->getValues();
		// leading zeros
		$rotate = sprintf('%03d', $values->azimuth);
		// replace \r, \n and # to azimuth
		$raw = txrxtcp($IP, $port, str_replace(
			array('\r', '\n', '#'),
			array("\r", "\n", "$rotate"), $rotset));
			$cut = Trim($raw);
		$az = substr("$cut", 3, 3);
		//$az = $rotate;
		echo "<a href=\"mob.php\"><img src=\"img.php?doraz_l={$rotl}&doraz_p={$rotr}&poloha={$az}&ant={$rotname}&cil={$rotate}\" alt=\"azimuth map\" title=\"refresh\"></a>";
	}
	else {
		// get azimut
		$raw = txrxtcp($IP, $port, str_replace(
			array('\r', '\n'),
			array("\r", "\n"), $rotget));
		$cut = Trim($raw);
		$az = substr("$cut", 3, 3);
		echo "<a href=\"mob.php\"><img src=\"img.php?doraz_l={$rotl}&doraz_p={$rotr}&poloha={$az}&ant={$rotname}&cil={$az}\" alt=\"azimuth map\" title=\"refresh\"></a>";
	}
	echo $form ;
} ?>

<!--------------------------------------------------------- cwkeying -->
<?
$IP = '127.0.0.1' ;
$cwd = rxfile('s-cw-cwd');  //cw device 0-disable 1-cwd 2-cwcli
$cwddev = '/dev/ttyUSB.cw'; //cw daemon device
$cwdaemonport = '6789' ;    //cw daemon UDP port
$cwcliport = rxfile('s-cw-cwcli'); //cw CLI TCP port
$memories = rxfile('s-cw-mem');  // number of memories

// CW daemon
if ( $cwd == "1" ) {
	if (file_exists($cwddev)) {
	   	//echo "The file $cwddev exists"; ?>
		<div class="mob"><form action="mob.php" method="POST">
		<p class="text2">
		<?
		// cyklus pro pameti
		for ($mem = 1; $mem < $memories+1; ++$mem){ 
			$value = rxfile("s-cw-mem$mem");
			if ( $value != "n/a" ) { ?>
				<input type="submit" name="<? echo "cw$mem"; ?>" value="<? echo $value; ?>">
				<? if (isset($_POST["cw$mem"])) {
					$cwmem = rxfile("s-cw-mem{$mem}c");
					//echo $cwmem ;
					udpsocket($IP, $cwdaemonport, $cwmem);
				} ?>
		<?	}
		} ?>
		<br>WPM: <input type="submit" name="wpm15" value="15">
		<input type="submit" name="wpm25" value="20">
		<input type="submit" name="wpm25" value="25">
		<input type="submit" name="wpm25" value="30">
		<input type="submit" name="wpm35" value="35">
		<input type="submit" name="plus" value="+">
		<input type="submit" name="minus" value="-">
		<br>Set: <input type="submit" name="stop" value="STOP">
		<? if (isset($_POST['plus'])) {
			udpsocket($IP, $cwdaemonport, '++');
		}
		else if (isset($_POST['minus'])) {
			udpsocket($IP, $cwdaemonport, '--');
		}
		//  http://cqrlog.svn.sourceforge.net/viewvc/cqrlog/trunk/src/uCWKeying.pas?revision=475&view=markup - od řádku 471, Chr(27) je ESC
		// v php je esc \e - ESC 2 25 nastaví rychlost na 25WPM
		else if (isset($_POST['stop'])) {
			udpsocket($IP, $cwdaemonport, "\e4");
		}
		else if (isset($_POST['wpm15'])) {
			udpsocket($IP, $cwdaemonport, "\e215");
		}
		else if (isset($_POST['wpm20'])) {
			udpsocket($IP, $cwdaemonport, "\e220");
		}
		else if (isset($_POST['wpm25'])) {
			udpsocket($IP, $cwdaemonport, "\e225");
		}
		else if (isset($_POST['wpm30'])) {
			udpsocket($IP, $cwdaemonport, "\e230");
		}
		else if (isset($_POST['wpm35'])) {
			udpsocket($IP, $cwdaemonport, "\e235");
		} ?>
		</form>
		<form action="mob.php" method="POST">
			<p class="text2">
			<input type="text" name="cwtext" size="17" maxlength="17">
			<input type="submit" value="Send">
			<? $cwtext = $_POST['cwtext'];
			if (isset($_POST['cwtext'])) {
				//echo $cwtext;
				udpsocket($IP, $cwdaemonport, $cwtext);
			}
			?></p>
		</form><?
		echo '</div>';
	}
}
// Arduino CW CLI
if ( $cwd == "2" ) {
	?><div class="mob"><form action="mob.php" method="POST">
	<p class="text2">
	<?
	// cyklus pro pameti
	for ($mem = 1; $mem < $memories+1; ++$mem){ 
		$value = rxfile("s-cw-mem$mem");
		if ( $value != "n/a" ) { ?>
			<input type="submit" name="<? echo "cw$mem"; ?>" value="<? echo $value; ?>">
			<? if (isset($_POST["cw$mem"])) {
				$cwmem = rxfile("s-cw-mem{$mem}c");
				//echo $cwmem ;
				txtcp($IP, $cwcliport, $cwmem);
			} ?>
	<?	}
	} ?>
	<br>WPM: <input type="submit" name="wpm15" value="15">
	<input type="submit" name="wpm20" value="20">
	<input type="submit" name="wpm25" value="25">
	<input type="submit" name="wpm30" value="30">
	<input type="submit" name="wpm35" value="35"><br>Set: 
	<input type="submit" name="tune" value="Tune">
	<input type="submit" name="x1" value="Tx1">
	<input type="submit" name="x2" value="Tx2">
	<? //  http://blog.radioartisan.com/arduino-cw-keyer/
	if (isset($_POST['tune'])) {txtcp($IP, $cwcliport, '\t'."\r");}
	else if (isset($_POST['x1'])) {txtcp($IP, $cwcliport, '\x1'."\r");}
	else if (isset($_POST['x2'])) {txtcp($IP, $cwcliport, '\x2'."\r");}
	else if (isset($_POST['tone'])) {txtcp($IP, $cwcliport, '\o'."\r");}
	else if (isset($_POST['wpm15'])) {txtcp($IP, $cwcliport, '\w15'."\r");}
	else if (isset($_POST['wpm20'])) {txtcp($IP, $cwcliport, '\w20'."\r");}
	else if (isset($_POST['wpm25'])) {txtcp($IP, $cwcliport, '\w25'."\r");}
	else if (isset($_POST['wpm30'])) {txtcp($IP, $cwcliport, '\w30'."\r");}
	else if (isset($_POST['wpm35'])) {txtcp($IP, $cwcliport, '\w35'."\r");}
}?>	</form><?
if ( $cwd == "1" && $cwd == "2") {?>
	<form action="mob.php" method="POST">
		<p class="text2">
		<input type="text" name="cwtext" size="17" maxlength="17">
		<input type="submit" value="Send">
		<? $cwtext = $_POST['cwtext'];
		if (isset($_POST['cwtext'])) {
			//echo $cwtext;
			txtcp($IP, $cwcliport, $cwtext);
		}
		?></p>
	</form></div>
<?}?>

<!--------------------------------------------------------- band   -->
<?
$bandon = rxfile("s-band-on");
$rigon = rxfile('s-rigctld-on');
if ( $bandon == "1" && $rigon == "1"){
	echo '<div class="mob2"><span id="band2">connected...</span></div>';
}?>
<!--------------------------------------------------------- webcam  -->

<?
$on = rxfile('s-webcam-on');

gethw();
if ( $hw == "PI" ) {
	if ($on == "0") {
	}
	else {
		echo '<img src="cam.jpg" class="mobwebcam">';
	}
} ?>

<!--------------------------------------------------------- own     -->
<? include 'own/mob.php'; ?>
<!--------------------------------------------------------- old     -->
<p class="mobtitul"><? echo exec('uptime'); ?></p>
</body>
<head>
	<title><? echo $call ?> - RemoteQTH server</title>
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link href="styles.css" rel="stylesheet" type="text/css">
	<meta http-equiv="refresh" content="55"> 
	<script>
      function hideAddressBar()
      {
          if(!window.location.hash)
          { 
              if(document.height <= window.outerHeight + 10)
              {
                  document.body.style.height = (window.outerHeight + 50) +'px';
                  setTimeout( function(){ window.scrollTo(0, 1); }, 50 );
              }
              else
              {
                  setTimeout( function(){ window.scrollTo(0, 1); }, 0 ); 
              }
          }
      } 

      window.addEventListener("load", hideAddressBar );
      window.addEventListener("orientationchange", hideAddressBar ); 


// band
var xmlhttp, band2 = -1, target = -1, centerx, centery, r;
function loadXMLDoc(url,cfunc)
{
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=cfunc;
    xmlhttp.open("GET",url,true);
    xmlhttp.send();
}

function updateQrg()
{
    loadXMLDoc("get.php?q=4", function()
    {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            band2 = parseInt(xmlhttp.responseText); 
            document.getElementById("band2").innerHTML = 'Band ' + band2 + 'm';
        }
    });
}
function start(){
    setInterval('updateQrg()', 1000);
}

window.onload = start;



</script>
</head>
</html>
