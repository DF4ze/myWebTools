<html>
 <head>
 <title>ajout d'options dans un select</title>
 <script type="text/javascript">
 function add(){
	 var newOption = document.createElement("option");
	 newOption.setAttribute("value",document.getElementById("valeur").value);
	 newOption.innerHTML=document.getElementById("texte").value;
	 document.getElementById("monselect").appendChild(newOption);
 }
 </script>
 </head>
 <body>
 <input type="text" id="texte" />
 <input type="text" id="valeur" />
 <input type="button" onclick="add();" value="ajouter" />
 <select id="monselect" onchange="alert(this.value);">
 </select>
 </body>
 </html>