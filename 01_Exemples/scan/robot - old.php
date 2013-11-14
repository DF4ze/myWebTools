<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >

<head>

<title>Exécution du robot</title>
<?php  
function ScanleDir($Directory){
$MyDirectory = opendir($Directory);

	while($Entry = readdir($MyDirectory)) {
		echo "<br>entry= $Entry<br>";

		echo "repertoire= $Directory<br>";

		echo "chemin= $Directory/$Entry<br>";

		if(is_dir($Entry)&& $Entry != "." && $Entry != "..") {
			echo "<b><font color=\"red\">$Entry</font>
				  </b> est un repertoire<br>";

			ScanleDir("$Entry/$Directory");

		}
		else {
		if (eregi(".htm",$Entry)) {
			$MetaTags = get_meta_tags($Directory."/".$Entry);

			if ($MetaTags["robots"] == "all") {
				$MetaKey = $MetaTags["keywords"];

				$MetaKey = strtoupper($MetaKey);

				echo "Meta($Directory/$Entry): $MetaKey\n";

				$MetaTitre = $MetaTags["title"];

				echo "Meta($Directory/$Entry): $MetaTitre\n";

			$query = "INSERT INTO search (lien,keyword,titre)
			VALUES(\"$Directory/$Entry\",\"$MetaKey\",\"$MetaTitre\")";

			$mysql_result = mysql_query($query) or die ("Erreur
			de modification de la table par la requete \"$query\"");

			}
		}
		}
	}
closedir($MyDirectory);

}
?>

</head>

<body>

<?php
echo "
<p>\n
<table BGCOLOR=\"#EFF2FB\" BORDER=\"0\"
							CELLSPACING=\"0\"
							CELLPADDING=\"1\"
							WIDTH=\"100%\">\n
<tr><td>\n
<a name=\"#index\"><h2>Indexation du site en cours</h2></a>\n
</td></tr>\n
</table>\n
<p>\n";

$host = "localhost";
$user = "root";
$password="";
$bdd = "test";

/* Connexion avec MySQL */
mysql_connect($host,$user,$password) or die ("Impossible de se connecter au serveur de base de donnees");

mysql_select_db($bdd) or die ("Impossible d'accéder à la base $bdd");

$query = "DELETE FROM search";

mysql_query($query) or die ("Erreur de modification de la table");

$open_basedir=".";

ScanleDir(".");

mysql_close();

?>
</body>
</html>