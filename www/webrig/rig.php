    <script>

var mx0 = 175;
var my0 = 600;
var mr = 560;
var u1 = -15;
var u2 = +15;
var smeter = [
   {db:00, txt:"S"},
   {db:06, txt:"1"},
   {db:12, txt:""},
   {db:18, txt:"3"},
   {db:24, txt:""},
   {db:30, txt:"5"},
   {db:36, txt:""},
   {db:42, txt:"7"},
   {db:48, txt:""},
   {db:54, txt:"9"},
   {db:60, txt:""},
   {db:66, txt:""},
   {db:72, txt:"+20"},
   {db:78, txt:""},
   {db:84, txt:""},
   {db:90, txt:"+40"},
   {db:96, txt:""},
   {db:102, txt:""},
   {db:108, txt:"+60"},
];
var maxdb = 108;


function dispmeter(){
    var bkg = document.getElementById("meterbkg");
    var ctx = bkg.getContext("2d");
    ctx.clearRect(0, 0, meter.width, meter.height);

    ctx.beginPath();
    ctx.lineWidth = 2;
    ctx.strokeStyle = '#554400';
    ctx.fillStyle = '#554400';

    ctx.font="bold 11px Tahoma, Arial, sans-serif";

    var s9 = u1 + (u2 - u1) * 48 / 78;
    ctx.arc(mx0, my0, (mr-5), (u1+270-0.65)* Math.PI / 180, (u2+270+0.65) * Math.PI / 180);
    ctx.stroke();
    ctx.beginPath();
    ctx.lineWidth = 13;
    for (var i = 0; i < smeter.length; i++){
        var angle = u1 + (u2 - u1) * smeter[i].db / maxdb ;
        var r = 15;
        if (smeter[i].db > 54) r += 10;
        r += 8;
        x = mx0 + (mr+r) * Math.sin(angle * Math.PI / 180);
        y = my0 - (mr+r) * Math.cos(angle * Math.PI / 180);
        var sz = ctx.measureText(smeter[i].txt);
        ctx.fillText(smeter[i].txt, x - sz.width / 2, y);
    }
    
    var first = 1;
    var lev = glevel + 54;
    if (gtx != 0) lev = -1;
    for (var i = 0; i < smeter.length; i++){
        if (lev < smeter[i].db && first){
            first = 0;
            ctx.stroke();
            ctx.beginPath();
            ctx.strokeStyle = '#AA8800';
            ctx.fillStyle = '#AA8800';
        }
        var angle = u1 + (u2 - u1) * smeter[i].db / maxdb ;
        var x = mx0 + (mr) * Math.sin(angle * Math.PI / 180);
        var y = my0 - (mr) * Math.cos(angle * Math.PI / 180);
        ctx.moveTo(x, y);
        var r = 15;
        if (smeter[i].db > 54) r += 10;
        x = mx0 + (mr+r) * Math.sin(angle * Math.PI / 180);
        y = my0 - (mr+r) * Math.cos(angle * Math.PI / 180);
        ctx.lineTo(x, y);
    }
    ctx.stroke();
    
    ctx.beginPath();
    ctx.strokeStyle = '#554400';
    ctx.fillStyle = '#554400';
    lev = glevel * smeter.length;
    if (gtx == 0) lev = -1;
    first = 1;
    for (var i = 0; i < smeter.length; i++){
        if (lev < i && first){
            first = 0;
            ctx.stroke();
            ctx.beginPath();
            ctx.strokeStyle = '#AA8800';
            ctx.fillStyle = '#AA8800';
        }
        var angle = u1 + (u2 - u1) * smeter[i].db / maxdb ;
        var x = mx0 + (mr-10) * Math.sin(angle * Math.PI / 180);
        var y = my0 - (mr-10) * Math.cos(angle * Math.PI / 180);
        ctx.moveTo(x, y);
        x = mx0 + (mr-25) * Math.sin(angle * Math.PI / 180);
        y = my0 - (mr-25) * Math.cos(angle * Math.PI / 180);
        ctx.lineTo(x, y);
    }
    ctx.stroke();
}



function dispqrg(qrg){
    var val = qrg;
    for (i = 0; i <= 8; i++){
        var d = val % 10;
        var img = (i >= 3) ? big[d] : small[d];
        val = Math.floor(val / 10);
        if (val == 0 && d == 0) img = (i >= 3) ? bigspace : smallspace;
        document.getElementById("qrg" + i.toString()).src = img.src;
    }
}

function disprit(rit){
    var val = rit;
    if (val < 0){
        document.getElementById("ritsign").src = smallminus.src;
        document.getElementById("prit").style.display = "none";
        document.getElementById("nrit").style.display = "";
    }else{
        document.getElementById("ritsign").src = smallspace.src;
        document.getElementById("prit").style.display = "";
        document.getElementById("nrit").style.display = "none";
    }
    val = Math.abs(val);
    for (i = 0; i <= 3; i++){
        var d = val % 10;
        var img = small[d];
        val = Math.floor(val / 10);
       // if (val == 0 && d == 0) img = smallspace;
        document.getElementById("rit" + i.toString()).src = img.src;
    }
}

function disptx(tx){
    var txtxt = document.getElementById("txtxt");
    var txled = document.getElementById("txled");
    if (tx != 0){
        txtxt.innerHTML = 'TX';
        txled.style.color = '#800000';
    }else{
        txtxt.innerHTML = 'RX';
        txled.style.color = '#008000';
    }
    dispmeter();
}

function poll(){
    var level = (gtx == 0) ? "STRENGTH" : "RFPOWER";
    loadXMLDoc2("riggetstate.php?q=f+m+v+j+t+l+" + level, function()
    {
        if (xmlhttp2.readyState==4){
            if (xmlhttp2.status==200)
            {
                var a = xmlhttp2.responseText.split("\n");
                gqrg = parseInt(a[0]);
                dispqrg(gqrg); 

                document.getElementById("mode").value = a[1];

                document.getElementById("vfo").innerHTML = a[3];
                document.getElementById("vfo").style.visibility = "visible";

                grit = parseInt(a[4]);
                disprit(grit);

                tx = parseInt(a[5]);
                disptx(tx);


                glevel = parseInt(a[6]);
                dispmeter();
            }
        }
    });

}

function rigstart(){
    document.getElementById("nrit").style.display = "none";

    showhide("divopts");
    big = new Array();
    big["0"] = new Image();
    big["0"].src = "img/68_0.png";
    big["1"] = new Image();
    big["1"].src = "img/68_1.png";
    big["2"] = new Image();
    big["2"].src = "img/68_2.png";
    big["3"] = new Image();
    big["3"].src = "img/68_3.png";
    big["4"] = new Image();
    big["4"].src = "img/68_4.png";
    big["5"] = new Image();
    big["5"].src = "img/68_5.png";
    big["6"] = new Image();
    big["6"].src = "img/68_6.png";
    big["7"] = new Image();
    big["7"].src = "img/68_7.png";
    big["8"] = new Image();
    big["8"].src = "img/68_8.png";
    big["9"] = new Image();
    big["9"].src = "img/68_9.png";
    bigdot = new Image();
    bigdot.src = "img/68_dot.png";
    bigminus = new Image();
    bigminus.src = "img/68_minus.png";
    bigspace = new Image();
    bigspace.src = "img/68_space.png";
    
    small = new Array();
    small["0"] = new Image();
    small["0"].src = "img/46_0.png";
    small["1"] = new Image();
    small["1"].src = "img/46_1.png";
    small["2"] = new Image();
    small["2"].src = "img/46_2.png";
    small["3"] = new Image();
    small["3"].src = "img/46_3.png";
    small["4"] = new Image();
    small["4"].src = "img/46_4.png";
    small["5"] = new Image();
    small["5"].src = "img/46_5.png";
    small["6"] = new Image();
    small["6"].src = "img/46_6.png";
    small["7"] = new Image();
    small["7"].src = "img/46_7.png";
    small["8"] = new Image();
    small["8"].src = "img/46_8.png";
    small["9"] = new Image();
    small["9"].src = "img/46_9.png";
    smalldot = new Image();
    smalldot.src = "img/46_dot.png";
    smallminus = new Image();
    smallminus.src = "img/46_minus.png";
    smallspace = new Image();
    smallspace.src = "img/46_space.png";

    for (i = 0; i <= 8; i++) attachMouse("qrg" + i.toString(), tunewheel);
    for (i = 0; i <= 3; i++) attachMouse("rit" + i.toString(), ritwheel);
    
    document.body.addEventListener("mousedown", mousedown);
    document.body.addEventListener("mousemove", mousemove);
    document.body.addEventListener("mouseup", mouseup);
    

    document.body.addEventListener("touchstart", touchstart);
    document.body.addEventListener("touchmove", touchmove);
    document.body.addEventListener("touchend", touchend);

    dispmeter();
    dispqrg(gqrg);
    disprit(grit);
    disptx(gtx);

    var refresh = getCookie("refresh");
    if (refresh == "") refresh = 2000;
    setCookie("refresh", refresh, 366);
    document.getElementById("refresh").value = refresh;

    clrlow = getCookie('clrlow');
    clrlow = (clrlow == "true");
    document.getElementById('clrlow').value = clrlow;

    poll();
    if (refresh != "OFF") polltimer = setInterval("poll()", refresh);
}

function attachMouse(id, wheelhandler){
    var img = document.getElementById(id);
    var mousewheelevt=(/Firefox/i.test(navigator.userAgent))? "DOMMouseScroll" : "mousewheel" //FF doesn't recognize mousewheel as of FF3.x
    if (img.attachEvent) //if IE (and Opera depending on user setting)
        img.attachEvent("on"+mousewheelevt, wheelhandler)
    else if (img.addEventListener) //WC3 browsers
        img.addEventListener(mousewheelevt, wheelhandler, false);

}

function tune(delta, order){
    if (delta == 0) return;
    gqrg += delta;
    if (gqrg < 1000000) gqrg = 1000000;
    if (gqrg >= 1000000000) gqrg = 1000000000;
    if (clrlow){
        gqrg = Math.floor(gqrg / order) * order;
    }
    dispqrg(gqrg);
    loadXMLDoc("rigctl.php?q=F+" + gqrg.toString(), function(){});
}

function tunewheel(ae){
    if (modal) return;

    var ev=window.event || ae //equalize event object
    var delta=ev.detail ? ev.detail*(-120) : evt.wheelDelta //delta returns +120 when wheel is scrolled up, -120 when scrolled down
    delta /= 360;
    if (ev.target) targ = ev.target;
    else if (ev.srcElement) targ = ev.srcElement;
    if (targ.nodeType == 3) targ = targ.parentNode;// defeat Safari bug
    var nr = targ.id.substring(3);
    tune(delta * Math.pow(10, nr), Math.pow(10, nr));
}


var isqrgdown = false;
var isritdown = false;
var downy = 0;
var starty = 0;
var thr = 10;
var downi = 0;
function mousedown(e){
    if (modal) return;

    if (e.target.id.substring(0, 3) == "qrg"){
        starty = parseInt(e.clientY);
        downy = starty;
        isqrgdown = true;
        downi = parseInt(e.target.id.substring(3));
        e.preventDefault();
    }
    if (e.target.id.substring(0, 3) == "rit"){
        starty = parseInt(e.clientY);
        downy = starty;
        isritdown = true;
        downi = parseInt(e.target.id.substring(3));
        e.preventDefault();
    }
}

function mousemove(e){
    if (modal) return;

    var dist = starty - parseInt(e.clientY);
    if (isqrgdown){
        if (Math.abs(dist) >= thr){
            tune(Math.floor(dist / thr) * Math.pow(10, downi), Math.pow(10, downi));
            starty = parseInt(e.clientY);
        }
        e.preventDefault();
    }
    if (isritdown){
        if (Math.abs(dist) >= thr){
            rit(dist, Math.pow(10, downi));
            starty = parseInt(e.clientY);
        }
        e.preventDefault();
    }
}

function mouseup(e){
    if (modal) return;

    var dist = downy - parseInt(e.clientY);
    if (isqrgdown){
        if (Math.abs(dist) < thr){
            if (e.which == 1) tune(Math.pow(10, downi), Math.pow(10, downi)); // left
            if (e.which == 3) tune(-Math.pow(10, downi), Math.pow(10, downi)); // right
            e.preventDefault();
        }
        isqrgdown = false;
    }
    if (isritdown){
        if (Math.abs(dist) < thr){
            if (e.which == 1) rit(Math.pow(10, downi), Math.pow(10, downi)); // left
            if (e.which == 3) rit(-Math.pow(10, downi), Math.pow(10, downi)); // right
            e.preventDefault();
        }
        isritdown = false;
    }
}

var touchqrgi = -1;
var touchriti = -1;
var touchqrg = 1;
var touchrit = 1;

function touchstart(e){
    if (modal) return;

	var touchobj = e.changedTouches[0];
    starty = parseInt(touchobj.clientY);
    
    if (e.target.id.substring(0, 3) == "qrg"){
        touchqrgi = parseInt(e.target.id.substring(3));
        touchqrg = gqrg;
        e.target.style.backgroundColor = "red";
        e.preventDefault();
        return;
    }else{
        touchqrgi = -1;
    }
    
    if (e.target.id.substring(0, 3) == "rit"){
        touchriti = parseInt(e.target.id.substring(3));
        touchrit = grit;
        e.target.style.backgroundColor = "red";
        e.preventDefault();
        return;
    }else{
        touchriti = -1;
    }
}
    
function touchmove(e){
    if (modal) return;

    var touchobj = e.changedTouches[0];
    var dist = starty - parseInt(touchobj.clientY);

    if (touchqrgi >= 0){
        tune(Math.round(dist / 10) * Math.pow(10, touchqrgi) + touchqrg - gqrg, Math.pow(10, touchqrgi));
        e.preventDefault();
    }

    if (touchriti >= 0){
        if (dist != 0) rit(Math.round(dist / 10) * Math.pow(10, touchriti) + touchrit - grit, Math.pow(10, touchriti));
        e.preventDefault();
    }
}

function touchend(e){
    if (modal) return;

    if (touchqrgi >= 0){
        var img = document.getElementById("qrg" + touchqrgi.toString());
        img.style.backgroundColor = "#d4aa00";
        touchqrgi = -1;
    }
    
    if (touchriti >= 0){
        var img = document.getElementById("rit" + touchriti.toString());
        img.style.backgroundColor = "#d4aa00";
        touchriti = -1;
    }
//    e.preventDefault();
}
    
function rit(delta, order){
    //var dummy = "delta="+delta.toString()+" order=" + order.toString();
    grit += delta;
    if (grit < -9999) grit = -9999;
    if (grit >= 9999) grit = 9999;
    if (clrlow){
        grit = Math.floor(grit / order) * order;
        if (grit < -9000) grit = -9000;
    }
    disprit(grit);
    loadXMLDoc("rigctl.php?q=J+" + grit.toString(), function(){});
}

function ritwheel(ae){
    if (modal) return;

    var ev=window.event || ae //equalize event object
    var delta=ev.detail ? ev.detail*(-120) : evt.wheelDelta //delta returns +120 when wheel is scrolled up, -120 when scrolled down
    delta /= 360;
    if (ev.target) targ = ev.target;
    else if (ev.srcElement) targ = ev.srcElement;
    if (targ.nodeType == 3) targ = targ.parentNode;// defeat Safari bug
    var nr = targ.id.substring(3);
    rit(delta * Math.pow(10, nr), Math.pow(10, nr));
}


function setmode(mode){
    loadXMLDoc("rigctl.php?q=M+" + mode + "+0", function(){});
}

function settx(tx){
    gtx = tx; 
    disptx(gtx);
    loadXMLDoc("rigctl.php?q=T+" + gtx.toString(), function(){});
}

function setvfo(){
    if (gvfo == "VFOA") gvfo = "VFOB";
    else gvfo = "VFOA";
    loadXMLDoc("rigctl.php?q=V+" + gvfo, 
        function(){
        //  if (xmlhttp2.readyState==4 && xmlhttp2.status==200){
        //        console.warn("running poll");
        //        poll();
        //    }
        }
    );
    poll();
}

function setrefresh(){
    var refresh = document.getElementById('refresh').value;
    setCookie('refresh', refresh, 366);
    clearInterval(polltimer);
    if (refresh != "OFF") polltimer = setInterval("poll()", refresh);
}

function setclrlow(){
    clrlow = document.getElementById('clrlow').value;
    setCookie('clrlow', clrlow, 366);
}

    
var big, bigdot, bigminus, bigspace;
var small, smalldot, smallminus, smallspace;
var glevel = -60;
var gqrg = 0;
var grit = 0;
var gtx = 0;
var gvfo = "VFOA";
var polltimer;
var clrlow;
var modal = false;
    
    </script>

<?
function rig(){
?>    
    <span id="meterspan">
      <canvas id="meter" width="350" height="100"></canvas>
      <canvas id="meterbkg" width="350" height="100"></canvas>
    </span><span id="rig1" onclick="setvfo()">        
      <span id="vfo">0000
      </span>
    </span><span id="rig2">
      <span id="modespan">
        <label>
        <select id="mode" onchange="setmode(document.getElementById('mode').value)">
          <option value="USB">USB</option>
          <option value="LSB">LSB</option>
          <option value="CW">CW</option>
          <option value="CWR">CW-r</option>
          <option value="AM">AM</option>
          <option value="FM">FM</option>
          <option value="WFM">WFM</option>
          <option value="RTTY">RTTY</option>
          <option value="RTTY">RTTY-r</option>
        </select>
        </label>
      </span>  
      <span id="spanoptions" onclick="showhide('divopts');modal=true">
        <img id="imgoptions" src="img/options24.png" title="Options" alt="Options"/>▼
      </span>
      <span id="qrg1span">
          <img class="digit" id="qrg8" src="img/68_space.png" alt=x><img 
            class="digit" id="qrg7" src="img/68_space.png" alt=x><img 
            class="digit" id="qrg6" src="img/68_space.png" alt=x><img 
            class="digit" id="qrgdot1" src="img/68_dot.png" alt=x ><img 
            class="digit" id="qrg5" src="img/68_space.png" alt=x><img 
            class="digit" id="qrg4" src="img/68_space.png" alt=x><img 
            class="digit" id="qrg3" src="img/68_space.png" alt=x><img 
            class="digit" id="qrgdot0" src="img/68_dot.png" alt=x>
          <img class="digit" id="qrg2" src="img/68_space.png" alt=x><img 
            class="digit" id="qrg1" src="img/68_space.png" alt=x><img 
            class="digit" id="qrg0" src="img/68_space.png" alt=x>kHz
        
      </span>
    </span><span id="rig3">
      <span id="help" onclick="window.open('http://ok1zia.nagano.cz/wiki/WebRigHelp', '_blank')"><img src="img/help24.png" title="Help" alt="Help"></span>
      <span id="txtxt" onclick="settx(gtx == 0 ? 1 : 0)">RX
      </span>
      <span id="txled" onclick="settx(gtx == 0 ? 1 : 0)">&#x25cf;
      </span>
      <span id="rit">
           <img class="digit" id="ritsign" src="img/46_space.png" alt=x><img 
            class="digit" id="rit3" src="img/46_space.png" alt=x><img 
            class="digit" id="ritdot0" src="img/46_dot.png" alt=x><img 
            class="digit" id="rit2" src="img/46_space.png" alt=x><img 
            class="digit" id="rit1" src="img/46_space.png" alt=x><img 
            class="digit" id="rit0" src="img/46_space.png" alt=x><span 
            id="prit" onclick="grit=0; rit(0, 1);">RIT</span><span id="nrit" onclick="grit=0; rit(0, 1);">RIT</span>
      </span>
    </span>


    <div id="divopts">
      <table id="options">
      <tr><th colspan="2">Options</th></tr>
      <tr><td colspan="2"><hr></td></tr>
      <tr class="odd"><td class="right">Refresh:</td><td>
      <select id="refresh" onchange="setrefresh()">
          <option value="500">500ms</option>
          <option value="700">700ms</option>
          <option value="1000">1s</option>
          <option value="1500">1.5s</option>
          <option value="2000">2s</option>
          <option value="3000">3s</option>
          <option value="10000">10s</option>
          <option value="OFF">Off</option>
      </select>         
      </td></tr>
      <tr><td class="right">Lower orders:</td><td>
      <select id="clrlow" onchange="setclrlow()">
          <option value="false">Keep</option>
          <option value="true">Clear</option>
      </select>         
      </td></tr>
      <tr><td colspan="2"><hr></td></tr>
      <tr><td colspan="2"><button onclick="showhide('divopts');modal=false">Close</button></td></tr>
      </table>
    </div>  
    
    <div id="debug"></div>


<?
}
?>
