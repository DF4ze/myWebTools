<?php
#  +------------------- JBC explorer ----------------------+
#  |   SCRIPT Entierement Ecrit par Jean Charles MAMMANA   |
#  |   SCRIPT Entierement modifie par Xavier MEDINA        |
#  |   Url : http://www.jbc-explorer.com                   |
#  |   Contact : jc_mammana@hotmail.com                    |
#  |   Contact : xabi62@yahoo.fr                           |
#  |                                                       |
#  |   Tous les scripts utilisé dans ce projet             |
#  |   sont en libre utilisation.                          |
#  |   Tous droits de modifications sont autorisé          |
#  |   à condition de nous en informer comme précisé       |
#  |   dans les termes du contrat de la licence GPL.       |
#  |                                                       |
#  +-------------------------------------------------------+

@session_start();
$_SESSION['test_sessions'] = 'ok';

# Partie utilisée pour le module compteur
if (!isset($_SESSION['Arrivee']))
{  # Le visiteur arrive directement par ici, on sauvegarde son referer si il existe
   if (isset($_SERVER['HTTP_REFERER']))
      $_SESSION['HTTP_REFERER'] = $_SERVER['HTTP_REFERER'];
      else
        $_SESSION['HTTP_REFERER'] = 'null';
   $nom_fichier_full = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], '/')+1);
   $nom_fichier = substr($nom_fichier_full, 0, strlen($nom_fichier_full)-4);
   $_SESSION['Arrivee'] = $nom_fichier;
}

# On vérifie si la langue à été modifier
if (isset($_GET['lang']))
{
	$_SESSION['lang'] = $_GET['lang'];
}
	else
		unset($_SESSION['lang']);

# modifier cette ligne selon le nom du dossier systeme
include_once('./dirsys/config.inc.php');

$query = '';
$path = $CONFIG['DOCUMENT_ROOT'];
if(!empty($_GET)){
	
	if (isset($_GET['lang']))	unset($_GET['lang']);

        $query = "?".http_build_query($_GET,'');
        if(($pathT = makePath($_GET)) === false) die($LANGUE['erreur']['Violation']);
        $path = resolvePath($CONFIG['DOCUMENT_ROOT'].$pathT['path']);
}

$showtn = SelectAffichType('',$path,$CONFIG);

if($showtn)
	$fileToOpen = 'showtn.php'.$query;
	else
		$fileToOpen = 'list.php'.$query;
		
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
<title><?php echo $CONFIG['MAIN_TITLE'] ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="robots" content="index, follow">
<meta name="revisit-after" content="1 month">
<meta name="author" content="psykokwak, xav, xabi62">
<meta name="reply-to" content="jc_mammana@hotmail.com,xabi62@yahoo.fr">
<meta name="owner" content="jc_mammana@hotmail.com,xabi62@yahoo.fr">
<meta name="copyright" content="psykokwak, xav, xabi62">
<meta name="nom" content="psykokwak, xav, xabi62">
<meta name="description" content="Explorateur de fichier web">
<meta name="keywords" content="explorateur, web, fichiers, explorer, icones, photos, images, photo, image, classement, classer, dossier, repertoir, systeme, GPL, licence, libre, EXIF, slideshow, psykokwak, jean charles mammana, xav, xabi62, xavier medina">
</head>
<frameset cols="<?php echo $CONFIG['WIDTH_TREE_FRAME'] ?>,*" >
 <frame frameborder="<?php echo $CONFIG['FRAME_BORDER'] ?>" src="<?php echo $CONFIG['DIRSYS']; ?>/arbre.php<?php echo $query ?>" name="tree" scrolling="<?php echo $CONFIG['SCROLING_TREE_FRAME'] ?>" <?php echo $CONFIG['RESIZE_FRAME'] ?> >
 <frame frameborder="<?php echo $CONFIG['FRAME_BORDER'] ?>" src="<?php echo $CONFIG['DIRSYS']; ?>/<?php echo $fileToOpen; ?>" name="main">
 <noframes>
  <body>
  </body>
 </noframes>
</frameset>

</html>