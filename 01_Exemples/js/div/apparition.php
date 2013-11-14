     <html>
     <head><title></title>
     <script language="javascript">
     /*
     Développé par Jacques Meunier
     http://www.sesame-ouvre-toi.net
     /\oo/\
     texte de Jacques Meunier(c)
     merci de ne pas effacer ...
     */
     var textfont="arial"
     var thissize=20
     var textcolor="#000000"
     var bgcol="#000000"
     var textfin="#ffffff"
	 
     message = new Array()
     message[1]="Je pleus comme novembre,<br>"
     message[2]="enlis&eacute; dans sa brume,<br>"
     message[3]="froidure que je fume,<br>"
     message[4]="n'arr&ecirc;tant pas d'attendre<br>"
     message[5]="ao&ucirc;t<br>"
     message[6]="et quand<br>"
     message[7]="et comment.<br><br><br>"
	 
     var E=-1
     var ref="0123456789abcdef"
	 
     </script>
     <script language="javascript">
     function varie()
	{
		 E=E+1
		 if (E<256)
		 {
			 v1=Math.floor(E/16)
			 v2=Math.floor(E%16)
			 
			 le_gris=ref.charAt(v1)+ref.charAt(v2)
			 textcolor="#"+le_gris+le_gris+le_gris
			 setTimeout("aff()",50)
		 }
     }
     function aff()
     {
    
		 document.getElementById("ph1").innerHTML="<span style='font-family:"+textfont+";font-size:"+thissize+"px;color:"+textcolor+";background-color:"+bgcol+"'>"+message[1]+"</span>"
		 document.getElementById("ph2").innerHTML="<span style='font-family:"+textfont+";font-size:"+thissize+"px;color:"+textcolor+";background-color:"+bgcol+"'>"+message[2]+"</span>"
		 document.getElementById("ph3").innerHTML="<span style='font-family:"+textfont+";font-size:"+thissize+"px;color:"+textcolor+";background-color:"+bgcol+"'>"+message[3]+"</span>"
		 document.getElementById("ph4").innerHTML="<span style='font-family:"+textfont+";font-size:"+thissize+"px;color:"+textcolor+";background-color:"+bgcol+"'>"+message[4]+"</span>"
		 document.getElementById("ph5").innerHTML="<span style='font-family:"+textfont+";font-size:"+thissize+"px;color:"+textcolor+";background-color:"+bgcol+"'>"+message[5]+"</span>"
		 document.getElementById("ph6").innerHTML="<span style='font-family:"+textfont+";font-size:"+thissize+"px;color:"+textcolor+";background-color:"+bgcol+"'>"+message[6]+"</span>"
		 document.getElementById("ph7").innerHTML="<span style='font-family:"+textfont+";font-size:"+thissize+"px;color:"+textcolor+";background-color:"+bgcol+"'>"+message[7]+"</span>"
		 document.getElementById("copy").innerHTML="<span style='font-family:"+textfont+";font-size:10px;color:"+textfin+";background-color:"+bgcol+"'>"+'&copy; Jacques MEUNIER'+"</span>"
		 //document.close()
		 
		 setTimeout("varie()",5)
     }
     </script>
     </head>
     <body bgcolor="#000000" onLoad="varie()">
     <script language="javascript">
    
     v1='visibility: visible'
    
     v='<table>'
     i=1
     while (i<8)
     {
		 v=v+'<tr><td>'
		 v=v+'<div id="ph'+i+'" style="'+v1+'">'
		 v=v+'<'+'/'+'div>'
		 v=v+'<'+'/'+'td><'+'/'+'tr>'
		 i++
     }
	 
	 v=v+'<tr><td>'
	 v=v+'<div id="copy" style="'+v1+'">'
	 v=v+'<'+'/'+'div>'
	 v=v+'<'+'/'+'td><'+'/'+'tr>'
	 v=v+'</table>'
	 document.write(v)
	</Script>
     </body>
     </html> 