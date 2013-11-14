<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >

<head>

<title>Exécution du robot</title>

</head>

<body>

<form method="post" action="index.php">

Entrez un chemin :<br>

<input type="text" name="dossier" size="15">

<input type="submit" value="Rechercher" alt="Lancer la recherche!">

</form>

<?php
function thescandir($dir)
{
	$is_dir = true;
	
	if( !($files = @scandir($dir)) ) // s'il y a une erereur au scandir c'est que c'est un fichier.
	{
		echo $dir."<br/>";
		$is_dir = false;
	}
	else
	{
		if($dir != "." and $dir != ".." )
			echo "<strong>".$dir."</strong><br/>";
		$is_dir = true;
	}
	
	if($dir != "." and $dir != ".." )
	{
		$extension = pathinfo($dir,PATHINFO_EXTENSION);
		$basename = pathinfo($dir,PATHINFO_BASENAME);
		
		if(!mysql_num_rows( mysql_query("SELECT * FROM liste_fichiers WHERE chemin='$dir'")))
			mysql_query("INSERT INTO liste_fichiers VALUES('',NULL, '$dir', '$basename', '$extension', '$is_dir')");
		else
			mysql_query("UPDATE liste_fichiers SET date=NULL WHERE chemin='$dir'");	
	}
	
	$i=0;
	while( @$files[$i] )
	{
		if($files[$i] != "." and $files[$i] != "..")
		{				
			$newdir = $dir."/".$files[$i];//concatène les noms de dossiers
				
			if($dir == ".")
				$newdir = $files[$i];
				
			thescandir($newdir); 
		}
		
		$i++;
	}
}

if(isset($_POST['dossier']))
{
	$host = "localhost";
	$user = "root";
	$password = "";
	$bdd = "coursphp";

	mysql_connect($host, $user, $password) or die ("Connexion au serveur impossible");
	mysql_select_db($bdd) or die ("Connexion a la base impossible");
	
	thescandir($_POST['dossier']);
	
	mysql_close();	
}


?>
</body>
</html>