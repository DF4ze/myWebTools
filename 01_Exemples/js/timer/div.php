<html>
<SCRIPT  language=javascript>
   function Timer() { // ne marche pas sous FF
		var iwidth = document.getElementById('div').style.width;
		iwidth.replace("px","");
		alert('largeur = '+document.getElementById('div').style.width+' width : '+iwidth);
		iwidth++;
		document.getElementById('div').style.width = iwidth+'px';
		if( width < 110 )
			setTimeout("Timer()",100);

/* 		var dt=new Date()
		window.status=dt.getHours()+":"+dt.getMinutes()+":"+dt.getSeconds();
		setTimeout("Timer()",1000);
 */   }
   Timer();
</SCRIPT>

<div style="border: 1px solid black; height:50px; width:100px; position:absolute ; display:inline" id="div" >
<a href="#" onclick="Timer()" > Click ici </a>
</div>
</html>