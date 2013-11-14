<?php
@session_start();
include('func.inc.php');
include('../config.inc.php');
include('lang.inc.php');

$statut = $LANGUE['modules']['auth']['status_initial'];

if(!file_exists('auth/auth.inc.php')) $statut = '<font color="red">'.$LANGUE['modules']['auth']['attention'].'</font>'.$LANGUE['modules']['auth']['nofile'];

if (!empty($_POST['login']) && !empty($_POST['password']))
{  switch(PutAuth($_POST['login'],$_POST['password'],'auth/auth.inc.php'))
   {   case -1:
          if (CreateAuthFile($_POST['login'],$_POST['password'],"auth/auth.inc.php"))
             $statut = $LANGUE['modules']['auth']['status_creation'];
             else
              $status = $LANGUE['modules']['auth']['status_no_droit'];
          break;
       case 0:
          $statut =  $LANGUE['divers']['accesrf'];
          break;
       case 1:
          $statut =  $LANGUE['divers']['accesok'];
          break;
   }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Affichage</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../styles/<?php echo $CONFIG['CSS'] ?>" rel="stylesheet" 
type="text/css">
</head>
<body onload="document.form_auth.login.focus();">
  <table style="width:100%" border="0" cellpadding="1" cellspacing="0">
  <tr class="bande" >
   <td class="miniatureliste" >[<b> <?php echo $LANGUE['modules']['auth']['nom_module'] ?>
</b> ]</td>
  </tr>
</table>
<br /><br /><br /><br />
<form action="" method="post" name="form_auth">
<table border="0" align="center">
  <tr>
   <td><?php echo $LANGUE['modules']['auth']['login'].' :'; ?></td>
   <td><input name="login" type="text"></td>
  </tr>
  <tr>
   <td><?php echo $LANGUE['modules']['auth']['pass'].' :'; ?></td>
   <td><input name="password" type="password"></td>
  </tr>
</table>
<div class="center"><input type="submit" name="log" value="<?php echo $LANGUE['modules']['auth']['valider'] ?>"></div>
</form>
<div class="titre1" align="center"><?php echo $LANGUE['modules']['auth']['status'].' : '.$statut;?></div>
<?php

    if ($statut == $LANGUE['divers']['accesok'])
    {  echo ' <form action="" method="post">';
       echo '  <div class="titre1" align="center">'."\n";
       echo "   <br><br><br>\n";
       echo '   <input type="submit" name="suppr" value="'.$LANGUE['modules']['auth']['suppr'].'" /><br>'."\n";
       echo '  </div>'."\n";
       echo ' </form>';
       echo '<script language="javascript">';
        echo "open('../arbre.php','tree','');";
        echo '</script>';
    }
    #-- On a cliqué sur le bouton suppr
    if ((isset($_POST['suppr'])) && (CheckAuth('./auth/auth.inc.php')==1))
    {  #-- suppr du fichier auth.inc.php
       $val = @unlink('auth/auth.inc.php');
       echo " <br><br><br><br>\n";
       echo ' <div class="titre1" align="center">';
       if ($val)
          echo $LANGUE['modules']['auth']['supprok'];
          else
            echo $LANGUE['modules']['auth']['supprko'];
       echo "</div>\n";
    }
?>

</body>
</html>