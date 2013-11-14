<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >

<head>

<title>Creation de la table</title>

</head>

<body>

<?php
$host = "localhost";

$user = "root";

$password = "";

$bdd = "test";

mysql_connect($host, $user, $password) or die ("Connexion au serveur impossible");

// on choisit la bonne base
mysql_select_db($bdd) or die ("Connexion a la base impossible");

$query = "CREATE TABLE search (
   lien varchar(128) NOT NULL,
   keyword text,
   titre varchar(128),
   id INT(11),
   PRIMARY KEY (id)
)";

mysql_query($query) or die ("Erreur de modification de  la table");

// on ferme la base
mysql_close();

?>

</body>

</html>