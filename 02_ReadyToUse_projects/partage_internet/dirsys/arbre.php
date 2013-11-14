<?php
#  +------------------ explorer ---------------------------+
#  |   SCRIPT Entierement Ecrit par Jean Charles MAMMANA   |
#  |   SCRIPT Entierement modifie par Xavier MEDINA        |
#  |   Url : http://www.jbc-explorer.com                   |
#  |   Contact : jc_mammana@hotmail.com                    |
#  |                                                       |
#  |   Tous les scripts utilisé dans ce projet             |
#  |   sont en libre utilisation.                          |
#  |   Tous droits de modifications sont autorisé          |
#  |   à condition de m'en informer comme précisé          |
#  |   dans les termes du contrat de la licence GPL        |
#  |                                                       |
#  +-------------------------------------------------------+

@session_start();

include_once('./config.inc.php');
include_once('./lang.inc.php');
include_once('./listing.inc.php');

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

# Bloc détails
if ($CONFIG['DETAILS'])
{
	$pass = substr($CONFIG['DOCUMENT_ROOT'], 0, -1);
	foreach ($_GET as $k => $v) {
	        #if(!is_numeric($k)) continue;
	        $pass = $pass.'/'.$_GET[$k];
	}
	$info = file_library($pass);
	if (!file_exists($pass.'/.dirinfo')) DirInfoTime ($pass,'0');
}


$auth = false;
$fileListToHide = file('hide.php');

if(file_exists('modules/auth/func.inc.php')){
        include_once('./modules/auth/func.inc.php');
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
 <title>tree</title>
 <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
 <link href="styles/<?php echo $CONFIG['CSS'] ?>" rel="stylesheet" type="text/css">
<script type="text/javascript">
    <!--
    function TestFrame(){
            <?php
                    $path = '';
                    if(!empty($_GET)){
                            $path = "?".http_build_query($_GET,'');
                    }
            ?>
            if(!(parent.frames["tree"] && parent.frames["main"])){
                    location.replace("../index.php<?php echo $path ?>");
            }
    }
    
    /*************************************************
    ** Permet d'afficher ou non une portion de page **
    *************************************************/
    function close_open(id)
    {  if (document.getElementById(id).style.display == "")
          document.getElementById(id).style.display = "none";
       else
          document.getElementById(id).style.display = "";
    }
    
    //TestFrame();
    
    //-->
 </script>
 <style type="text/css">
    <!--
    body {
        margin-top: 10px;
    }
    -->
 </style>
</head>
<body class="bback">
 <table width="204" align="center" border="0" cellpadding="0" cellspacing="0">
  <tr> 
   <td height="23" colspan="3" class='Ctitre2' onclick="close_open('liste');" style="cursor:pointer">
    &nbsp;&nbsp;&nbsp;<?php echo $LANGUE['arbre_rubrique']['liste'] ?>
   </td>
  </tr>
 </table>
 <div id="liste">
  <table width="204" border="0" align="center" cellpadding="0" cellspacing="0">
   <tr>
    <td class='cadre'><img src="img/onepix.gif" width="1" height="1" alt=""></td>
    <td class='Ctitre2back'>
     <table width="100%"  border="0" cellspacing="0" cellpadding="4">
      <tr>
       <td>
        <table border="0" cellpadding="1" cellspacing="0">
         <tr>
          <td class="Ctitre2back"> <a href="arbre.php" onClick="open('list.php','main','')" ><img src="<?php echo $CONFIG['ICO_FOLDER'] ?>/disk.gif" alt="<?php echo $LANGUE['divers']['serveur']; ?>" title="<?php echo $LANGUE['divers']['serveur']; ?>" class="ico">&nbsp;<strong><?php echo $_SERVER['SERVER_NAME'] ?></strong></a><br>
          <?php listing($CONFIG['DOCUMENT_ROOT'],$_GET,$CONFIG,$LANGUE); ?></td>
         </tr>
        </table>
       </td>
      </tr>
     </table>
    </td>
    <td class="cadre"><img src="img/onepix.gif" width="1" height="1" alt=""></td>
   </tr>
   <tr class="cadre">
    <td height="1"></td>
    <td height="1" width="202"></td>
    <td height="1"></td>
   </tr>
  </table>
 </div>
 <br>
 <?php
if ($CONFIG['DETAILS'])
{
?>
 <table width="204" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
   <td height="23" colspan="3" class="Ctitre3" onclick="close_open('details');" style="cursor:pointer">&nbsp;&nbsp;&nbsp;<?php echo $LANGUE['arbre_rubrique']['detail'] ?></td>
  </tr>
 </table>
 <div id="details">
  <table width="204" border="0" align="center" cellpadding="0" cellspacing="0">
   <tr>
    <td class="cadre"><img src="img/onepix.gif" width="1" height="1" alt=""></td>
    <td class="Ctitre3back">
     <table width="100%"  border="0" cellspacing="0" cellpadding="4">
      <tr>
       <td>
        <table border="0" cellpadding="1" cellspacing="0">
         <tr>
          <td colspan="2"><b><?php echo basename($pass).'</b>'; ?></td>
         </tr>
         <tr>
          <td><?php
               if ($pass == substr($CONFIG['DOCUMENT_ROOT'], 0, -1))
                  echo $LANGUE['arbre_detail']['racine'];
                  else
                    echo $info['type']; ?>
          </td>
         </tr>
         <tr>
          <td>&nbsp;</td>
         </tr><?php
               if(is_dir($pass))
               {
                  if ($pass == substr($CONFIG['DOCUMENT_ROOT'], 0, -1))
                  {
                     include($pass.'/.dirinfo');
                     $ValueSize = substr($FolderSize, -2);
                     switch ($ValueSize)
                     {
                        case 'Ko':
                          $Pourcent = (int)($CONFIG['TOTALSIZE'] - (int)(substr($FolderSize, 0, -2)*1024));
                          break;
                        case 'Mo':
                          $Pourcent = (int)($CONFIG['TOTALSIZE'] - (int)(substr($FolderSize, 0, -2)*1024*1024));
                          break;
                        case 'Go':
                          $Pourcent = (int)($CONFIG['TOTALSIZE'] - (int)(substr($FolderSize, 0, -2)*1024*1024*1024));
                          break;
                        case 'To':
                          $Pourcent = (int)($CONFIG['TOTALSIZE'] - (int)(substr($FolderSize, 0, -2)*1024*1024*1024*1024));
                          break;
                     }
                     $Pourcent = (int)(($Pourcent / $CONFIG['TOTALSIZE'])*100);

                     $val =  DirInfoTime($pass, '1');
                     echo '<tr>';
                     echo ' <td>'.$LANGUE['arbre_detail']['contenu'].' : '.$val['count'].'</td>';
                     echo '</tr>';
                     echo '<tr>';
                     echo ' <td>'.$LANGUE['arbre_detail']['taille'].' : '.$val['size'];
                     echo '&nbsp;'.$Pourcent.'% '.$LANGUE['arbre_detail']['libre'].'</td>';
                     echo '</tr>';
                 }else
                    {
                       $val = DirInfoTime($pass, '1');
                       echo '<tr>';
                       echo '<td>'.$LANGUE['arbre_detail']['contenu'].' : '.$val['count'].'</td>';
                       echo '</tr>';
                       echo '<tr>';
                       echo '<td>'.$LANGUE['arbre_detail']['taille'].' : '.$val['size'].'</td>';
                       echo '</tr>';
                    }
               }

               if(is_file($pass) && ($info['ico']=='jpg' || $info['ico']=='bmp' || $info['ico']=='gif'))
               {
                 $getdim = getimagesize($pass);
                 $dim = $LANGUE['arbre_detail']['dimension'].' : '.$getdim['0'].'px x '.$getdim['1'].'px';
                 echo '<tr><td>'.$dim.'</td></tr>';
               }
?>
         <tr>
          <td><?php echo $LANGUE['arbre_detail']['datemod'].' : '.datefix($pass); ?></td>
         </tr>
         <?php if(is_file($pass)) { echo'<tr><td>'.$LANGUE['arbre_detail']['taille'].' : '.$info['size'].'</td></tr>';}
               if(CheckAuth('modules/auth/auth.inc.php')===1)
               {
                  echo'<tr><td>'.$LANGUE['arbre_detail']['attributs'].' : '.substr(sprintf('%o', fileperms($pass)), -4).'</td></tr>';
               }
         ?>
        </table>
       </td>
      </tr>
     </table>
    </td>
    <td class="cadre"><img src="img/onepix.gif" width="1" height="1" alt=""></td>
   </tr>
   <tr class="cadre">
    <td height="1"></td>
    <td height="1" width="202"></td>
    <td height="1"></td>
   </tr>
  </table>
 </div>
 <br>
 <!-- Fin de Définition du chemin du dossier -->
<?php
}
?>
 <table width="204"  border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
   <td height="23" colspan="3" class="Ctitre3" onclick="close_open('modules');" style="cursor:pointer">&nbsp;&nbsp;&nbsp;<?php echo $LANGUE['arbre_rubrique']['module'] ?></td>
  </tr>
 </table>
 <div id="modules">
  <table width="204" border="0" align="center" cellpadding="0" cellspacing="0">
   <tr>
    <td width="1" class='cadre'><img src="img/onepix.gif" width="1" height="1" alt=""></td>
    <td width="190" class='Ctitre3back'>
     <table width="100%"  border="0" cellspacing="0" cellpadding="4">
      <tr>
       <td>
        <table>
         <tr>
          <td>
          <?php ListModules();?>
          </td>
         </tr>
        </table>
       </td>
      </tr>
     </table>
    </td>
    <td width="1" class='cadre'><img src="img/onepix.gif" width="1" height="1" alt=""></td>
   </tr>
   <tr class='cadre'>
    <td height="1"></td>
    <td height="1" width="202"></td>
    <td height="1"></td>
   </tr>
  </table>
 </div>
 <!-- Pour supprimer le lien "Information & Aide", supprimez simplement le fichier "info.php" :) //-->
<?php if((file_exists('info.php')) || ($CONFIG['activer_Message'])){ ?>
 <br>
 <table width="204" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
   <td height="23" colspan="3" class="Ctitre3" onclick="close_open('info');" style="cursor:pointer">&nbsp;&nbsp;&nbsp;<?php echo $LANGUE['arbre_rubrique']['info'] ?></td>
  </tr>
 </table>
 <div id="info">
  <table width="204" border="0" align="center" cellpadding="0" cellspacing="0">
 <?php if (file_exists("info.php")){ ?>
   <tr>
    <td class="cadre"><img src="img/onepix.gif" width="1" height="1" alt=""></td>
    <td class="Ctitre3back">
     <table width="100%" border="0" cellspacing="0" cellpadding="4">
      <tr>
       <td>
        <table>
         <tr>
          <td><a href="info.php" target="main"><img src="<?php echo $CONFIG['ICO_FOLDER'] ?>/hlp.gif" alt="<?php echo $LANGUE['divers']['infocopy']; ?>" title="<?php echo $LANGUE['divers']['infocopy']; ?>" class="ico" > <?php echo $LANGUE['divers']['infocopy'] ?></a></td>
         </tr>
        </table>
       </td>
      </tr>
     </table>
    </td>
    <td class="cadre"><img src="img/onepix.gif" width="1" height="1" alt=""></td>
   </tr>
<?php } ?>
<?php if ($CONFIG['activer_Message']){ ?>
   <tr>
    <td class="cadre"><img src="img/onepix.gif" width="1" height="1" alt=""></td>
    <td class="Ctitre3back">
     <table width="100%" border="0" cellspacing="0" cellpadding="4">
      <tr>
       <td>
        <table>
         <tr>
          <td><?php echo $CONFIG['Message']; ?></td>
         </tr>
        </table>
       </td>
      </tr>
     </table>
    </td>
    <td class="cadre"><img src="img/onepix.gif" width="1" height="1" alt=""></td>
   </tr>
<?php } ?>
  <tr class="cadre">
   <td height="1"></td>
   <td height="1" width="202"></td>
   <td height="1"></td>
  </tr>
 </table>
</div>
<?php } ?>

<p>&nbsp;</p>
</body>
</html>