<!doctype html>
<html>
<body>

<?php
require 'function.php';
require 'Nette/loader.php';
use Nette\Forms\Form,
Nette\Utils\Html;
?>

<!--------------------------------------------------------- sensors -->
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


<div class="splitwindow">
<form action="<?echo actualpage();?>" method="POST">
<div id="wrap">
<?
$ant = isset($_GET['ant']) ? $_GET['ant'] : 0;
// definice barev
$ON = '#d00';
$OFF = '#444';
$THROW = '#090';
$lines = "0" ;
$nrant = "0" ;
//$path = "../cfg/";
$saverelay = rxfile2("../cfg/s-relay-save");
settype($name, "array");
settype($color, "array");
gethw();
if ( $hw == "PI" ) {
	$rev = rpi2rev();
	//if ($rev == '000[2347d]' ) {  // B+ detection
	if (preg_match('/00(02|03|0[456]|0[789]|0[def]|11)/', $rev)){
		$nrgpio = '15';
	}else{
		$nrgpio = '24';
	}
}elseif ( $hw == "BBB" ) {
	$nrgpio = '28';
}else 	{
	$nrgpio = '0';
}

// cyklus pro X sensoru
for($rel=1; $rel<$nrgpio+1; $rel++){
	$name[$rel] = rxfile2("../cfg/s-relay-$rel");
	$switch[$rel] = rxfile2("../cfg/s-relay-sw-{$rel}");
	$aof[$rel] = rxfile("../cfg/s-relay-aof-{$rel}");
	if ( $name[$rel] != "n/a" ) { $lines++ ;
		if ($ant==$rel) {
			if ($switch[$rel] != "1") { //multi throw? = all OFF
				/*for($offrel=1; $offrel<$nrgpio+1; $offrel++){
					$offswitch = rxfile2("../cfg/s-relay-sw-{$offrel}");	// Changeover switch (BANK-1)
					if ($offswitch == "1") { // gpio OFF
						$bd = rxfile2("../cfg/s-band{$offrel}-on");
						if ($bd == "1") { // if banddecoder on, 
							// nic
						} else {
							txfile2("../cfg/gpio{$offrel}", '0');
						}
					}
					if ( $saverelay == "1" ) { // if save ON
						if ( $offswitch == "1" ) { // save sw OFF
							txfile2("../cfg/s-relay-{$offrel}-save", '0');
						}
					}
				}*/
				$gpio = rxfile2("../cfg/gpio{$rel}");
				settype($gpio, "integer");
				if ($gpio == "1") {
					txfile2("../cfg/gpio{$rel}", '0'); // actual gpio OFF
				} else {
					txfile2("../cfg/gpio{$rel}", '1'); // actual gpio ON
					if ($aof[$rel] == "1") { // auto-off
						sleep(1);
						txfile("../cfg/gpio{$rel}", '0');
					}
				}
			} else { // $rel ON
				txfile2("../cfg/gpio{$rel}", '1');
			}
			if ( $saverelay == "1" ) {
				txfile2("../cfg/s-relay-{$rel}-save", '1');
			}
		}
	}
}

for($rel=1; $rel<$nrgpio+1; $rel++){
	$aof[$rel] = rxfile("../cfg/s-relay-aof-{$rel}");
	if ( $name[$rel] != "n/a" ) {
		$gpio = rxfile2("../cfg/gpio$rel");
		settype($gpio, "integer");
                                if ($gpio == "1") {
					$status[$rel] = " ON" ;
				} else {
					$status[$rel] = " OFF" ; }
		if ($switch[$rel] != "1") { $nrant++;
			echo '<a href="'.actualpage().'?ant='.$rel.'">';
			echo '<div class="box"><div class="innerContent" ';
				if ($gpio == "1") { // ON?
					echo 'style="background: #36f; color: #fa0;"' ;
				}
			echo '><div id="table"><div id="label">'.$name[$rel];
				if ($aof[$rel] == "1") { // auto-off
				echo '<span style="color: #000; font-size: small"><br>Auto-OFF</span>';
				}
			//echo "$nrant";
			//echo '<b>'.$status[$rel].'</b>' ;
			echo '</div></div></div></div></a>'."\n" ;
		}
	}
} ?>

</div></div>

</body>
<head>
	<title>Antenna control</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<style type="text/css">
BODY {
	margin: 0px;
	border: 0px;
	padding: 0px;
	font-family: Tahoma, Helvetica, Arial, sans-serif;
	font-size: 10pt;
	background: #000;
	font-weight: bold;
}
a:link a:visited a:hover {
	text-decoration : none
	}
#wrap	{
	overflow: hidden;
}
.box	{
<?	if ($nrant <= "2") { echo "\t".'width: 50%;'."\n\t".'padding-bottom: 50%;'."\n"; }
	if ($nrant >= "3") { echo "\t".'width: 33%;'."\n\t".'padding-bottom: 33%;'."\n"; }
	if ($nrant >= "7") { echo "\t".'width: 25%;'."\n\t".'padding-bottom: 25%;'."\n"; }
	if ($nrant >= "13") { echo "\t".'width: 20%;'."\n\t".'padding-bottom: 20%;'."\n"; }
	if ($nrant >= "25") { echo "\t".'width: 12.5%;'."\n\t".'padding-bottom: 12.5%;'."\n"; } ?>
	color: #FFF;
	position: relative;
	float: left;
	}
#table {
	display: table;
	width: 100%;
	height: 100%;
}
#label {
	display: table-cell;
	text-align: center;
	vertical-align: middle;
}
.innerContent {
	font-size: 4em;
	position: absolute;
	left: 3px;
	right: 3px;
	top: 3px;
	bottom: 3px;
	background: #666;
	padding: 10px;
	border-radius: 12px;
	-webkit-border-radius: 12px;
	-moz-border-radius: 12px;
	-khtml-border-radius: 12px;
    }
.innerContent:hover  {
	color : #fa0;
	background-color: #888;
	text-decoration : none;
	}
@media only screen and (max-width: 1080px) {
   body { font-size: 75%; }
}
@media only screen and (max-width: 800px) {
   body { font-size: 50%; }
}
@media only screen and (max-width: 320px) {
   body { font-size: 25%; }
}

.graph {
position: relative; /* IE is dumb */
width: 100%;
border: 1px solid #888;
padding: 2px;
margin: 0px 0px -1px 0px;
border-radius: 4px;
-webkit-border-radius: 4px;
-moz-border-radius: 4px;
-khtml-border-radius: 4px;
}
.graph .bar {
display: block;
position: relative;
background: #ddd;
height: 1em;
color: #000;
line-height: 1em;
text-align: left;
padding: 1px 0px 1px 5px;
font-family: Tahoma, Helvetica, Arial, sans-serif;
}
.graph .bar span { position: absolute; left: 1em; }


  </style>
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
</script>
	<meta http-equiv="refresh" content="50; url=http://<? echo $_SERVER['SERVER_ADDR'];?>/c-relay-pi-mike.php?ant=0">
</head>

</html>

