    <?php
    session_start();

    $erreurs=array();//tableau qui stocke les erreurs.

    if(!$_POST['Id']){
        $erreurs[]='Id: Veuillez encoder un Id';
    }

    if(!$_POST['Nom']){
        $erreurs[]='Nom: Veuillez encoder un Nom';
    }

    if(count($erreurs)==0)
    {
       
        //ici tu enregistres les valeurs dans la bdd
     
        echo "true";//cette valeur sera traité par ajax est vaut dire que tt passe pour le bien
    }
    else
    {
        echo "<p class='erreur'>";
            for($i=0;$i<count($erreurs);$i++)
            {
            echo "- ".$erreurs[$i].".<br />";
            }
        echo "</p>";
    }
    ?>
