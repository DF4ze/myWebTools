<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >

<head>

<title>Exécution du robot</title>

</head>

<body>

<form method="post" action="index.php">

Entrez un mot clé:<br>

<input type="text" name="Mot" size="15">

<input type="submit" value="Rechercher" alt="Lancer la recherche!">

</form>

<?php
function thescandir($dir)
{
	$files = scandir($dir);
	$i=0;
	
	while( @$files[$i] )
	{
		if($files[$i] != "." and $files[$i] != "..")
		{
			if( is_dir( $files[$i] ))
			{
				echo "<strong>".$files[$i]."</strong><br/>";
				thescandir($files[$i]);
			}
			else
			{
				echo $files[$i]."<br/>";
			}		
		}
		
		$i++;
	}
}

thescandir("..");


?>
</body>
</html>