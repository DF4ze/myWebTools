<?php
#  +------------------ explorer ---------------------------+
#  |   SCRIPT Entierement Ecrit par Jean Charles MAMMANA   |
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
	        if(!is_numeric($k)) continue;
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>tree</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="styles/<?php echo $CONFIG['CSS'] ?>" rel="stylesheet" type="text/css">
<script language="javascript">
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
function close_open(img, id)
{  if (document.getElementById(id).style.display == "")
  {  document.getElementById(id).style.display = "none";
    img.src = "./icones/win/plus.gif";
  }  else
    {  document.getElementById(id).style.display = "";
      img.src = "./icones/win/moins.gif";
    }
}

TestFrame();

//-->
</script>
<style>
body { margin:10px;}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Document sans titre</title>
</head>

<body style="background-color:#f4f4f2;">
<div id="cadre">
	<!-- début Cadre dossiers -->
<div id="dossier">
<div id="onglets">
<a href="javascript://"
onclick="document.getElementById('dossier').style.display='block';
document.getElementById('modules').style.display='none';
document.getElementById('infos').style.display='none';"><img src="styles/Onglets/DossierB.png" border="none"></a>
<a href="javascript://"
onclick="document.getElementById('dossier').style.display='none';
document.getElementById('modules').style.display='block';
document.getElementById('infos').style.display='none';"><img src="styles/Onglets/ModulesA.png" border="none"></a>
<a href="javascript://"
onclick="document.getElementById('dossier').style.display='none';
document.getElementById('modules').style.display='none';
document.getElementById('infos').style.display='block';"><img src="styles/Onglets/InfosA.png" border="none"></a>
</div>
      <div id="barre"><img src="styles/Onglets/BDossierB.png" width="189" height="6" /></div>
	  <div id="cadre-bkg">&nbsp;<a href="arbre.php" onClick="open('list.php','main','')" ><img src="<?php echo $CONFIG['ICO_FOLDER'] ?>/disk.gif" alt="Serveur" title="Server" class="ico">&nbsp;<strong><?php echo $_SERVER['SERVER_NAME'] ?></strong></a><br>
        <?php listing($CONFIG['DOCUMENT_ROOT'],$_GET,$CONFIG,$LANGUE); ?></div>
	  </div>
	  	<!-- fin Cadre dossiers -->
	
	<!-- début Cadre module -->
		<div id="modules" style="display:none;">
	<div id="onglets"><a href="javascript://"
onclick="document.getElementById('dossier').style.display='block';
document.getElementById('modules').style.display='none';document.getElementById('infos').style.display='none';"><img src="styles/Onglets/DossierA.png" border="none"></a><a href="javascript://"
onclick="document.getElementById('dossier').style.display='none';
document.getElementById('modules').style.display='block';document.getElementById('infos').style.display='none';"><img src="styles/Onglets/ModulesB.png" border="none"></a><a href="javascript://"
onclick="document.getElementById('dossier').style.display='none';
document.getElementById('modules').style.display='none';document.getElementById('infos').style.display='block';"><img src="styles/Onglets/InfosA.png" border="none"></a></div>
	<div id="barre"><img src="styles/Onglets/BModulesB.png" width="189" height="6" /></div>
	<div id="cadre-bkg"><table width="100%">
         <tr>
          <td><?php ListModules();?></td>
         </tr>
        </table></div>
	</div>
	<!-- fin Cadre modules -->
	
	<!-- debut Cadre infos -->
	<div id="infos" style="display:none;">
	<div id="onglets"><a href="javascript://"
onclick="document.getElementById('dossier').style.display='block';
document.getElementById('modules').style.display='none';document.getElementById('infos').style.display='none';"><img src="styles/Onglets/DossierA.png" border="none"></a><a href="javascript://"
onclick="document.getElementById('dossier').style.display='none';
document.getElementById('modules').style.display='block';document.getElementById('infos').style.display='none';"><img src="styles/Onglets/ModulesA.png" border="none"></a><a href="javascript://"
onclick="document.getElementById('dossier').style.display='none';
document.getElementById('modules').style.display='none';document.getElementById('infos').style.display='block';"><img src="styles/Onglets/InfosB.png" border="none"></a></div>
	<div id="barre"><img src="styles/Onglets/BInfosB.png" width="189" height="6" /></div>
	<div id="cadre-bkg"><a href="info.php" target="main"><img src="<?php echo $CONFIG['ICO_FOLDER'] ?>/hlp.gif" alt="Info" title="info" class="ico" ><?php echo $LANGUE['infocopy'] ?></a></div>
	</div>
	<!-- fin Cadre infos -->
	<div id="cadre-bottom"><img src="styles/Onglets/Bottom.png" width="189" height="5" /></div>
<br />
</div>
<div id="details">
<div id="details-header"><img src="styles/Onglets/detail-header.png" /></div>
<div id="details-bkg">
<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="details-table" >
         <tr>
          <td colspan="2"><b><?php echo basename($pass).'</b>'; ?></td>
         </tr>
         <tr>
          <td ><?php
               if ($pass == substr($CONFIG['DOCUMENT_ROOT'], 0, -1))
                  echo 'Dossier racine';
                  else
                    echo $info['type']; ?>
          </td>
         </tr>
         <tr>
          <td>&nbsp;</td>
         </tr><?php
               if(is_dir($pass)) {
                 if ($pass == substr($CONFIG['DOCUMENT_ROOT'], 0, -1)) {
                     include($pass."/.dirinfo");       
                         $ValueSize = substr($FolderSize, -2);
                         if($ValueSize = "Mo") {
                         $Pourcent = (int)(($CONFIG['TOTALSIZE'] - (substr($FolderSize, 0, -2)*1024*1024))/$CONFIG['TOTALSIZE']*100);
                         }
                         else if($ValueSize = "Go") {
                         $Pourcent = (int)(($CONFIG['TOTALSIZE'] - (substr($FolderSize, 0, -2)*1024*1024*1024))/$CONFIG['TOTALSIZE']*100);
                         } 
                         else if($ValueSize = "To") {
                         $Pourcent = (int)(($CONFIG['TOTALSIZE'] - (substr($FolderSize, 0, -2)*1024*1024*1024*1024))/$CONFIG['TOTALSIZE']*100);
                         }
                     echo DirInfoTime ($pass, "1").',&nbsp;'.$Pourcent.'% libre</td></tr>';
                 }else
                   echo DirInfoTime ($pass, "1").'</td></tr>';}
               if(is_file($pass) && ($info['ico']=="jpg" || $info['ico']=="bmp" || $info['ico']=="gif")){
                 $getdim = getimagesize($pass);
                 $dim = "Dimensions: ".$getdim['0']." * ".$getdim['1'];
                 echo "<tr><td>".$dim."</td></tr>";} ?>
         <tr>
          <td>Date de Modification : <?php datefixFRENCH($pass); ?></td>
         </tr>
         <?php if(is_file($pass)) { echo'<tr><td>Taille: '.$info['size'].'</td></tr>';} ?>
         <?php if(CheckAuth('modules/auth/auth.inc.php')===1) { echo'<tr><td>Attributs: '.substr(sprintf('%o', fileperms($pass)), -4).'</td></tr>';} ?>
        </table></div>
		<div id="details-bottom"></div>
</div>

</body>
</html>
