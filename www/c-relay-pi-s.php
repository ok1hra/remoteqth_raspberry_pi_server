
<?
// switching relay for local and clusters
//////////////////////////////////////////
$saverelay = rxfile("s-relay-save");
$serverip = rxfile("s-rot2-s{$server}ip");
$serverlogin = rxfile("s-rot2-s{$server}login");
$serverpass = rxfile("s-rot2-s{$server}pass");
$THROW = '#090';
$THROWB = '#888';
gethw();
if ( $hw == "PI" ) {
	$rev = rpi2rev();
	//if ($rev < 16) {  // B+ detection
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
	if ( $server == "0" ) {  //local
		$name[$rel] = rxfile("s-relay-$rel");
		$switch[$rel] = rxfile("s-relay-sw-{$rel}");
		$switchb[$rel] = rxfile("s-relay-swb-{$rel}");
		$aof[$rel] = rxfile("s-relay-aof-{$rel}");
	}else{                   //cluster
		$name[$rel] = rxfile("s-rot-s{$server}gpio{$rel}name");
		$switch[$rel] = rxfile("s-rot-s{$server}gpio{$rel}sw");
		$switchb[$rel] = rxfile("s-rot-s{$server}gpio{$rel}swb");
		$aof[$rel] = rxfile("s-rot-s{$server}gpio{$rel}aof");
	}
	if ( $name[$rel] != "n/a" ) { $lines++ ;
		$zapnout = empty($_POST["puntik-{$server}-{$rel}"]) ? "" : $_POST["puntik-{$server}-{$rel}"];
		//echo " {$zapnout}|";
		if ($zapnout=="OFF-{$server}-{$rel}") {
			if ( $server == "0" ) {  //local
				txfile("gpio{$rel}", '0'); // OFF
			}else{                   //cluster
				rxurlpass("$serverip", "get.php?q=sf&file=gpio{$rel}&value=0", $serverlogin, $serverpass);
			}
			if ( $saverelay == "1" ) {
				if ( $server == "0" ) { //local
					txfile("s-relay-{$rel}-save", '0');
				}else{ //cluster
					rxurlpass("$serverip", "get.php?q=sf&file=s-relay-{$rel}-save&value=0", $serverlogin, $serverpass);
				}
			}
		}
		if ($zapnout=="ON-{$server}-{$rel}") {
			//echo "#ON-{$server}-{$rel}#";
			if ($switch[$rel] == "1") { //multi throw? = all OFF
				for($offrel=1; $offrel<$nrgpio+1; $offrel++){
					if ( $server == "0" ) {  //local
						$offswitch = rxfile("s-relay-sw-{$offrel}");
					}else{                   //cluster
						$offswitch = rxurlpass("$serverip", "get.php?q=gf&file=s-relay-sw-{$offrel}", $serverlogin, $serverpass);
					}
					if ($offswitch == "1") { // gpio OFF
						if ( $server == "0" ) { //local
							txfile("gpio{$offrel}", '0');
						}else{ //cluster
							rxurlpass("$serverip", "get.php?q=sf&file=gpio{$offrel}&value=0", $serverlogin, $serverpass);
						}
					}
					if ( $saverelay == "1" ) { // if save ON
						if ( $offswitch == "1" ) { // save sw OFF
							if ( $server == "0" ) { //local
								txfile("s-relay-{$offrel}-save", '0');
							}else{ //cluster
								rxurlpass("$serverip", "get.php?q=sf&file=s-relay-{$offrel}-save&value=0", $serverlogin, $serverpass);
							}
						}
					}
				}
				if ( $server == "0" ) { //local
					txfile("gpio{$rel}", '1'); // actual gpio ON
				}else{ //cluster
					rxurlpass("$serverip", "get.php?q=sf&file=gpio{$rel}&value=1", $serverlogin, $serverpass);
				}
			}else if ($switchb[$rel] == "1") { // BANK-2
				for($offrel=1; $offrel<$nrgpio+1; $offrel++){
					if ( $server == "0" ) {  //local
						$offswitch = rxfile("s-relay-swb-{$offrel}");
					}else{                   //cluster
						$offswitch = rxurlpass("$serverip", "get.php?q=gf&file=s-relay-swb-{$offrel}", $serverlogin, $serverpass);
					}
					if ($offswitch == "1") { // gpio OFF
						if ( $server == "0" ) { //local
							txfile("gpio{$offrel}", '0');
						}else{ //cluster
							rxurlpass("$serverip", "get.php?q=sf&file=gpio{$offrel}&value=0", $serverlogin, $serverpass);
						}
					}
					if ( $saverelay == "1" ) { // if save ON
						if ( $offswitch == "1" ) { // save sw OFF
							if ( $server == "0" ) { //local
								txfile("s-relay-{$offrel}-save", '0');
							}else{ //cluster
								rxurlpass("$serverip", "get.php?q=sf&file=s-relay-{$offrel}-save&value=0", $serverlogin, $serverpass);
							}
						}
					}
				}
				if ( $server == "0" ) { //local
					txfile("gpio{$rel}", '1'); // actual gpio ON
				}else{ //cluster
					rxurlpass("$serverip", "get.php?q=sf&file=gpio{$rel}&value=1", $serverlogin, $serverpass);
				}
			} else { // $rel ON
				if ( $server == "0" ) { //local
					txfile("gpio{$rel}", '1');
				}else{ //cluster
					rxurlpass("$serverip", "get.php?q=sf&file=gpio{$rel}&value=1", $serverlogin, $serverpass);
				}
				if ($aof[$rel] == "1") { // auto-off
					sleep(1);
					if ( $server == "0" ) { //local
						txfile("gpio{$rel}", '0');
					}else{ //cluster
						rxurlpass("$serverip", "get.php?q=sf&file=gpio{$rel}&value=0", $serverlogin, $serverpass);
					}
				}
			}
			if ( $saverelay == "1") {
				if ( $aof[$rel] != "1"){	
					if ( $server == "0" ) { //local
						txfile("s-relay-{$rel}-save", '1');
					}else{ //cluster
						rxurlpass("$serverip", "get.php?q=sf&file=s-relay-{$rel}-save&value=1", $serverlogin, $serverpass);
					}
				}else{
					if ( $server == "0" ) { //local
						txfile("s-relay-{$rel}-save", '0');
					}else{ //cluster
						rxurlpass("$serverip", "get.php?q=sf&file=s-relay-{$rel}-save&value=0", $serverlogin, $serverpass);
					}
				}
			}
		}
	}
}
echo '<div class="border"><table class="'.$table.'">' ;
for($rel=1; $rel<$nrgpio+1; $rel++){
	if ( $name[$rel] != "n/a" ) {
		if ( $server == "0" ) {  //local
			$gpio = rxfile("gpio$rel");
		}else{                   //cluster
			$gpio = rxurlpass("$serverip", "get.php?q=gf&file=gpio{$rel}", $serverlogin, $serverpass);
		}
		settype($gpio, "integer");
                                if ($gpio == "1") {
					if ($switch[$rel] != "1" && $switchb[$rel] != "1") { 
	                                        $colorname[$rel] = $ON ;
	                                        $color[$rel] = $ON ;
						$status[$rel] = " ON" ;
					} else {
						if ($switch[$rel] == "1") {
							$color[$rel] = $THROW ;
	                                        	$colorname[$rel] = $THROW ;
						}
						if ($switchb[$rel] == "1") {
							$color[$rel] = $THROWB ;
	                                        	$colorname[$rel] = $THROWB ;
						}
						$status[$rel] = ' ON &#10148; '.$name[$rel] ; 
					}
				} else {
                                        $colorname[$rel] = $TEXT ;
                                        $color[$rel] = $OFF ;
					$status[$rel] = " ON" ; }
		echo "<tr>\n\t".'<td class="td1"><span style="color: '.$colorname[$rel].'">';
		if ( $server != "0" ) {  //cluster
			echo '<img src="cluster2.png"> ';
		}
		echo $name[$rel]."</span></td>\n" ;
		echo "\t".'<td class="td2"><input type="checkbox" name="puntik-'.$server.'-'.$rel.'" value="ON-'.$server.'-'.$rel.'" onclick="this.form.submit();"><span style="color: '.$color[$rel] ;
		echo '"><b>'.$status[$rel].'</b></span>' ;
			if ($switch[$rel] != "1" && $switchb[$rel] != "1") { // !multi throw?
				if ($aof[$rel] == "1" && $switch[$rel] != "1" && $switchb[$rel] != "1"){
					echo "\t".'Auto-OFF';
				}else{
					echo '&nbsp;&nbsp;<input type="radio" name="puntik-'.$server.'-'.$rel.'" value="OFF-'.$server.'-'.$rel.'" onclick="this.form.submit();">OFF' ;
				}
			}else{
				if ($switch[$rel] == "1") {
					echo "\t"."<span style=\"color: $THROW\"> [bank-1]</span>";
				}
				if ($switchb[$rel] == "1") {
					echo "\t"."<span style=\"color: $THROWB\"> [bank-2]</span>";
				}
			}
		echo '</td>'."\n" ;
				if ( $saverelay == "1" ) {
					if ( $server == "0" ) { //local
						$save[$rel] = rxfile("s-relay-{$rel}-save");
					}else{ //cluster
						rxurlpass("$serverip", "get.php?q=sf&file=s-relay-{$rel}-save&value=0", $serverlogin, $serverpass);
					}
					echo "\t".'<td><span style="color: #08f">'.$save[$rel]."</span>\n" ;
				}
			echo '</tr>';
	}
}
?>
</table></div>
