<form action="c-relay.php" method="POST">
<?
$path = "../cfg/";
// definice barev
$ON = '#d00';
$OFF = '#fff';
$THROW = '#090';
$lines = "0" ;
$saverelay = rxfile("s-relay-save");

// cyklus pro 15 sensoru
for($rel=1; $rel<16; $rel++){
	$name[$rel] = rxfile("s-relay-$rel");
	$switch[$rel] = rxfile("s-relay-sw-{$rel}");
	$aof[$rel] = rxfile("s-relay-aof-{$rel}");
	if ( $name[$rel] != "n/a" ) { $lines++ ;
		$zapnout = empty($_POST["puntik$rel"]) ? "" : $_POST["puntik$rel"];
		if ($zapnout=="OFF$rel") {
			txfile("gpio{$rel}", '0'); // OFF
			if ( $saverelay == "1" ) {
				txfile("s-relay-{$rel}-save", '0');
			}
		}
		if ($zapnout=="ON$rel") {
			if ($switch[$rel] == "1") { //multi throw? = all OFF
				for($offrel=1; $offrel<16; $offrel++){
					$offswitch = rxfile("s-relay-sw-{$offrel}");
					if ($offswitch == "1") { // gpio OFF
						txfile("gpio{$offrel}", '0');
					}
					if ( $saverelay == "1" ) { // if save ON
						if ( $offswitch == "1" ) { // save sw OFF
							txfile("s-relay-{$offrel}-save", '0');
						}
					}
				}
				txfile("gpio{$rel}", '1'); // actual gpio ON
			} else { // $rel ON
				txfile("gpio{$rel}", '1');
				if ($aof[$rel] == "1") { // auto-off
					sleep(1);
					txfile("gpio{$rel}", '0');
				}
			}
			if ( $saverelay == "1") {
				if ( $aof[$rel] != "1"){	
					txfile("s-relay-{$rel}-save", '1');
				}else{
					txfile("s-relay-{$rel}-save", '0');
				}
			}
		}
	}
}
echo '<div class="border"><table class="white">' ;
for($rel=1; $rel<16; $rel++){
	if ( $name[$rel] != "n/a" ) {
		$gpio = rxfile("gpio$rel");
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
                                        $colorname[$rel] = '#000' ;
                                        $color[$rel] = $OFF ;
					$status[$rel] = " ON" ; }
		echo "<tr>\n\t".'<td class="td1"><span style="color: '.$colorname[$rel].'">'.$name[$rel]."</span></td>\n" ;
		echo "\t".'<td class="td2"><input type="checkbox" name="puntik'.$rel.'" value="ON'.$rel.'" onclick="this.form.submit();"><span style="color: '.$color[$rel] ;
		echo '"><b>'.$status[$rel].'</b></span>' ;
			if ($switch[$rel] != "1") { // !multi throw?
				if ($aof[$rel] == "1" && $switch[$rel] != "1"){
					echo "\t".'Auto-OFF';
				}else{
					echo '&nbsp;&nbsp;<input type="radio" name="puntik'.$rel.'" value="OFF'.$rel.'" onclick="this.form.submit();">OFF' ;
				}
			}
		echo '</td>'."\n" ;
				if ( $saverelay == "1" ) {
					$save[$rel] = rxfile("s-relay-{$rel}-save");
					echo "\t".'<td><span style="color: #08f">'.$save[$rel]."</span>\n" ;
				}
			echo '</tr>';
	}
}
?>
</table></div>
</form>

