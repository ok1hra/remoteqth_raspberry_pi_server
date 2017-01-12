<form action="<?echo actualpage();?>" method="POST">
<?
// definice barev
$ON = '#d00';
$OFF = '#444';
$THROW = '#090';
$lines = "0" ;
$path = "../cfg/";
$saverelay = rxfile("s-relay-save");
settype($name, "array");
settype($color, "array");

// cyklus pro 15 sensoru
for($rel=1; $rel<16; $rel++){
	$name[$rel] = rxfile("../cfg/s-relay-$rel");
	$switch[$rel] = rxfile("../cfg/s-relay-sw-{$rel}");
	$aof[$rel] = rxfile("../cfg/s-relay-aof-{$rel}");
	if ( $name[$rel] != "n/a" ) { $lines++ ;
		$zapnout = empty($_POST["puntik$rel"]) ? "" : $_POST["puntik$rel"];
		if ($zapnout=="OFF$rel") {
			txfile("../cfg/gpio{$rel}", '0'); // OFF
			if ( $saverelay == "1" ) {
				txfile("../cfg/s-relay-{$rel}-save", '0');
			}
		}
		if ($zapnout=="ON$rel") {
			if ($switch[$rel] == "1") { //multi throw? = all OFF
				for($offrel=1; $offrel<16; $offrel++){
					$offswitch = rxfile("../cfg/s-relay-sw-{$offrel}");
					if ($offswitch == "1") { // gpio OFF
						txfile("../cfg/gpio{$offrel}", '0');
					}
					if ( $saverelay == "1" ) { // if save ON
						if ( $offswitch == "1" ) { // save sw OFF
							txfile("../cfg/s-relay-{$offrel}-save", '0');
						}
					}
				}
				txfile("../cfg/gpio{$rel}", '1'); // actual gpio ON
			} else { // $rel ON
				txfile("../cfg/gpio{$rel}", '1');
				if ($aof[$rel] == "1") { // auto-off
					sleep(1);
					txfile("../cfg/gpio{$rel}", '0');
				}
			}
			if ( $saverelay == "1") {
				if ( $aof[$rel] != "1"){	
					txfile("../cfg/s-relay-{$rel}-save", '1');
				}else{
					txfile("../cfg/s-relay-{$rel}-save", '0');
				}
			}
		}
	}
}
echo '<table class="black">' ;
for($rel=1; $rel<16; $rel++){
	if ( $name[$rel] != "n/a" ) {
		$gpio = rxfile("../cfg/gpio$rel");
		settype($gpio, "integer");
                                if ($gpio == "1") {
					if ($switch[$rel] != "1") { 
	                                        $colorname[$rel] = $ON ;
	                                        $color[$rel] = $ON ;
						$status[$rel] = " ON" ;
					} else { $color[$rel] = $THROW ;
	                                        $colorname[$rel] = $THROW ;
						$status[$rel] = ' ON &#10148; '.$name[$rel] ; 
}
				} else {
                                        $colorname[$rel] = '#888' ;
                                        $color[$rel] = $OFF ;
					$status[$rel] = " ON" ; }
		echo "<tr>\n\t".'<td class="td1"><span style="color: '.$colorname[$rel].'">'.$name[$rel]."</span></td>\n" ;
		echo "\t".'<td class="td2"><input type="checkbox" name="puntik'.$rel.'" value="ON'.$rel.'" onclick="this.form.submit();"><span style="color: '.$color[$rel] ;
		echo '"><b>'.$status[$rel].'</b></span><span style="color:#444">' ;
			if ($switch[$rel] != "1") { // !multi throw?
				if ($aof[$rel] == "1" && $switch[$rel] != "1"){
					echo "\t".'Auto-OFF';
				}else{
					echo '&nbsp;&nbsp;<input type="radio" name="puntik'.$rel.'" value="OFF'.$rel.'" onclick="this.form.submit();">OFF' ;
				}
			}
		echo '</span></td>'."\n" ;
				if ( $saverelay == "1" ) {
					$save[$rel] = rxfile("../cfg/s-relay-{$rel}-save");
					echo "\t".'<td><span style="color: #08f">'.$save[$rel]."</span>\n" ;
				}
			echo '</tr>';
	}
}
?>
</table>
</form>
<? if ( $saverelay == "1" ) {
	echo '<span style="color: #08f">Stored settings will be set after reboot.</span>';
}?>
