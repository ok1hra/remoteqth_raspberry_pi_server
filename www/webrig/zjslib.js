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

function loadXMLDoc2(url,cfunc)
{
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp2=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp2=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp2.onreadystatechange=cfunc;
    xmlhttp2.open("GET",url,true);
    xmlhttp2.send();
}

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) != -1) return c.substring(name.length,c.length);
    }
    return "";
} 

function showhide(id){
    if (document.getElementById){
        obj = document.getElementById(id);
        if (obj.style.display == "none"){
            obj.style.display = "";
        } else {
            obj.style.display = "none";
        }
    }
}

function dump(obj){
    alert(obj.toString() + "\n" + 
        JSON.stringify(obj, 
        function(key, value) { return value;}, 4));
}

function mkwwl(lng, lat, len){
    var ret = "";
	// JN69QR76BB
	//lng = 13.39209;
	//lat = 49.733556;

    lng += 180;
    lat += 90;

    lng = lng % 360;
    lat = lat % 180;
    ret = ret + String.fromCharCode(65 + Math.floor(lng / 20));
    ret = ret + String.fromCharCode(65 + Math.floor(lat / 10));
    if (len <= 2) return ret;

    lng = lng % 20;
    lat = lat % 10;
    ret = ret + String.fromCharCode(48 + Math.floor(lng / 2));
    ret = ret + String.fromCharCode(48 + Math.floor(lat));
    if (len <= 4) return ret;

    lng = lng % 2;
    lat = lat % 1;
    lng *= 12;
    lat *= 24;
    ret = ret + String.fromCharCode(65 + Math.floor(lng));
    ret = ret + String.fromCharCode(65 + Math.floor(lat));
    if (len <= 6) return ret;

    lng = lng % 1;
    lat = lat % 1;
    lng *= 10;
    lat *= 10;
    ret = ret + String.fromCharCode(48 + Math.floor(lng));
    ret = ret + String.fromCharCode(48 + Math.floor(lat));
    if (len <= 8) return ret;
    
    lng = lng % 1;
    lat = lat % 1;
    lng *= 24;
    lat *= 24;
    ret = ret + String.fromCharCode(65 + Math.floor(lng));
    ret = ret + String.fromCharCode(65 + Math.floor(lat));

    return ret;
}

function qth(wwl, latitude){
    wwl = wwl.trim().toUpperCase();
    if (latitude) wwl = wwl.substring(1);

    var d = wwl.charCodeAt(0);
    if (d < 65 || d > 82) return -100;
    var ret = (d - 65 - 9) * Math.PI / 9.0;

    d = wwl.charCodeAt(2);
    if (d < 48 || d > 59) return -101;
    ret += (d - 48) * Math.PI / 90.0;
    
    d = wwl.charCodeAt(4);
    if (d < 65 || d > 88) return -102;
    ret += (d - 65) * Math.PI / 2160.0; 

    ret += Math.PI / 4320.0;
    if (latitude) ret /= 2;
      
    return ret * 180.0 / Math.PI;
}

function debug(text){                
    var d = document.getElementById("debug");

    if (d == null){
        alert(text);
        return;
    }
    if (text === undefined)
        d.innerHTML = "";
    else
        d.innerHTML += text + "<br>";
}

function format2(num){
    var s = num.toString();
    if (s.length < 2) s = '0' + s;
    return s;
}


