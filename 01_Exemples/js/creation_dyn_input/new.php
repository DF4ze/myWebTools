<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
    <head>
        <title>Tutoriel Ajax (XHTML + JavaScript + XML)</title>
        <script type='text/JavaScript'>
      
            function newLigne()
            {  
                var elForm = document.getElementById("idFormulaire");
                var btn = document.getElementById("idBouton");
                var input;
                 
                input = document.createElement("input");
                input.type = "text";
                 
                elForm.insertBefore(input, btn );
                 
            }
        </script>
    </head>
    <body>
     
        <form method="post" action="java.html" id="idFormulaire">
             
             
            <input type="button" value="valider" id="idBouton" onclick="newLigne();"/>
                 
        </form>
         
    </body>
</html>