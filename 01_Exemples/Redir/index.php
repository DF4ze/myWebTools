<html>
<head>
<?php
$url = 'http://82.234.41.220'; // Ceci est l'url à vérifier.

$test = fopen($url, 'r'); // Nous ouvrons l'url en mode lecture seule (r).

if ($test)  // Si notre test retourne vrai (true), ce site web répond et donc l'url est valide.
{
	// echo "site ".$url." OK";
	echo '<meta http-equiv="refresh" content="0; url=http://82.234.41.220/Albums" />';
    fclose($test); // Fermeture
}
 else
{
	// echo "site ".$url." Non-OK";
	echo '<meta http-equiv="refresh" content="0; url=http://df4ze.free.fr/Lina-Joy/" />';
  // Sinon, notre site web ne répond pas. L'url n'est pas valide.
}
?>
</head>
</html>