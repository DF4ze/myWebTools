<?php
/*
CheckAuth() retournera
-1 si le fichier auth.inc.php nexiste pas (il faut alors se logger pour le creer automatiquement
0  si l'acces est refusé
1  si l'acces est autorisé
*/

@session_start() or die('Impossible de creer de session!<br><b>Si vous le script est hebergé chez FREE, il est necessaire de creer un dossier \'sessions\' à la racine de votre site!</b>');

include('../../config.inc.php');
include('../auth/func.inc.php');
include('func.inc.php');
include('lang.inc.php');

$OK = '<font color="#00AA00">'.$LANGUE['modules']['config']['trouveok'].'</font>';
$nOK= '<font color="#AA0000"><b>'.$LANGUE['modules']['config']['trouveko'].'</b></font>';

if(!file_exists('../../hide.php')) die('Votre version de l\'explorer ne permet pas de cacher des fichiers/dossiers!<br>Veuillez mettre a jour l\'explorer!');

$t = listToHide(file('../../hide.php'));

$number = count($t);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Config</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../styles/<?php echo $CONFIG['CSS'] ?>" rel="stylesheet" type="text/css">
<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="100%" border="0" cellpadding="1" cellspacing="0">
  <tr class="bande" > 
    <td class="miniatureliste" >[<b> <?php echo $LANGUE['modules']['config']['MENU_HIDE']; ?> </b>]</td>
  </tr>
</table>
<br />
<?php
  if (CheckAuth('../auth/auth.inc.php')!==1)
  {  echo '<br /><br /><br /><br /><div align="center" class="titre1">'.$LANGUE['accesrf'].'</div>'."\r\n";
     echo '</body>'."\r\n".'</html>';
     exit;
  }
?>
<form name="form" method="post" action="postHide.form.php">
 <table border="0" align="center">
  <tr>
   <td align="center" ><?php echo $LANGUE['modules']['config']['PATH'] ?></td>
   <td align="center" ><?php echo $LANGUE['modules']['config']['STATE'] ?></td>
  </tr>
<?php

for($i=0;$i<$number;$i++)
{
	$d = Win2UnixShlash(AddFirstSlashes($t[$i]));
#	echo $d;
?>
  <tr>
   <td><input type="text" class="form" style="width:300px" name="<?php echo $i ?>" value="<?php echo $d ?>"></td>
   <td align="center"><?php if(file_exists(RemoveLastSlashes($CONFIG['DOCUMENT_ROOT']).$d))echo $OK; else echo $nOK; ?></td>
  </tr>
<?php
}
?>
  <tr>
   <td><input type="text" class="form" style="width:300px" name="<?php echo $i ?>" value=""></td>
   <td align="center"><b><?php echo $LANGUE['modules']['config']['ajouter']; ?></b></td>
  </tr>
 </table>
<br />
<center><input type="submit" value="<?php echo $LANGUE['modules']['config']['MAJ']; ?>" class="form"></center>
</form>
<br /><br />
<center>
<table  border="0" align="center" width="600">
 <tr> 
  <td align="left">
    <font size="2" face="Arial, Helvetica, sans-serif">
    <?php echo $LANGUE['modules']['config']['message']; ?>
    </font>
  </td>
 </tr>
</table>
</center>
</body>
</html>