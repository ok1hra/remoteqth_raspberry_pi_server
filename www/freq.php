<!doctype html>
<html>
<head>
<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<link href="styles.css" rel="stylesheet" type="text/css">
	<link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:300italic,400italic,700italic,400,700,300&subset=latin-ext' rel='stylesheet' type='text/css'>
<script>
var xmlhttp, freq = -1, target = -1, centerx, centery, r;
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
function numberWithCommas(freq) {
    return freq.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function updateQrg()
{
    loadXMLDoc("get.php?q=1", function()
    {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            freq = parseInt(xmlhttp.responseText); 
	    //freq = zeroPad(freq2, 8);
	    freq = freq.toString().replace(/([0-9]*)([0-9]{3})/, '$1.$2');
	    freq = freq.toString().replace(/([0-9]*)([0-9]{3})/, '$1 $2');
            document.getElementById("freq").innerHTML = '<span class="lcd">' + freq + '</span> <span class="khz">kHz</span>';
        }
    });
}
function start(){
    setInterval('updateQrg()', 1000);
}

window.onload = start;

</script>
</head>
<body bgcolor="#FAAC58">
<span id="freq">connected...</span>
</body>
</html>
