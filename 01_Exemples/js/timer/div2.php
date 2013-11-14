<script type="text/javascript">
function Suite(lien){
	
	var objet = document.getElementById('popup'); // entre les deux ' tu mes le nom du div que tu veux faire apparaître !
	
	if(objet.style.display == "none" || !objet.style.display){
		
		objet.innerHTML = "Ici le text que tu veux faire apparaître !";
		objet.style.display = "block";
		objet.style.overflow = "hidden"; 
		lien.innerHTML = "-";
       
        var hFinal      =     200;  //Hauteur finale (la hauteur une fois que ça aura fini de déplier !)
        var hActuel     =     0;	 	//Hauteur initiale (la hauteur dès le début !)
       
        var timer;
        var fct =        function ()
        {
                hActuel  +=       20;     //Augmente la hauteur de 20px (tu peux modifier) tous les 40ms !
				
                objet.style.height     =	 hActuel      +     'px';
				
                if( hActuel > hFinal)
                {
                        clearInterval(timer);   //Arrête le timer
                        objet.style.overflow    =   'inherit';
                }
        };
        fct();

        
		timer = setInterval(fct,40);    //Toute les 40 ms
		
	}else if(objet.style.display == "block"){
		
		var hFinal      =     0;  //Hauteur finale (la hauteur une fois que ça aura fini de déplier !)
        var hActuel     =     200;	 	//Hauteur initiale (la hauteur dès le début !)
       
        var timer;
        var fct =        function ()
        {
                hActuel  -=   20;     //Augmente la hauteur de -20px (tu peux modifier) tous les 40ms !
				
                objet.style.height     =	 hActuel      +     'px';
				
                if( hActuel < hFinal)
                {
                        clearInterval(timer);   //Arrête le timer
                        objet.style.overflow    =   'inherit';
						objet.style.display     =   "none";
                }
        };
        fct();

        
		timer = setInterval(fct,40);    //Toute les 40 ms
		

		lien.innerHTML = "+";
		
	}
}
</script>

[<a href="javascript:;" onclick="Suite(this)">+</a>]

<div id="popup" style="display: none; border: #000000 1px solid; width: 300px;">

</div>

<br /><br />Ici :)
<noscript><a href="http://www.editeurjavascript.com/">ajax</a></noscript>