<?php
@session_start();

# Partie utilis�e pour le module compteur
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

# R�cup�ration du filtre et du sens
if (isset($_GET['filtre']))
	$_SESSION['filtre'] = $_GET['filtre'];
elseif (! isset($_SESSION['filtre']))
    $_SESSION['filtre'] = 'nom';

if (isset($_GET['sens']))
	$_SESSION['sens'] = $_GET['sens'];
elseif (! isset($_SESSION['sens']))
    $_SESSION['sens'] = 'ASC';

# On supprime le filtre et le sens de la variable $_GET pour le fichier 'list.inc.php'
unset($_GET['filtre']);
unset($_GET['sens']);

@include_once('./modules/gestfiles/gestfiles.func.php'); //~~<HACK_MODULE_GESTFILES>~~
include_once('./config.inc.php');
include_once('./list.inc.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Affichage</title>
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

TestFrame();

//-->
</script>
</head>
<body>
<table border="0" cellspacing="0" cellpadding="1" width="100%" >
 <tr class="bande" >
  <td align="left" colspan="2">
<?php
     if ($_SESSION['filtre'] != 'nom')
        echo '<a href="?'.$chaine_post.'filtre=nom&amp;sens=ASC">';
        else
        {
           echo '<a href="?'.$chaine_post.'filtre=nom&amp;';
           if ($_SESSION['sens'] == 'ASC') echo 'sens=DESC">'; else echo 'sens=ASC">';
        }
     echo $LANGUE['liste']['Nom'].'</a> ';

     if ($_SESSION['filtre'] == 'nom')
     {
     	echo '<img src="img/fleche_';
        if ($_SESSION['sens'] == 'ASC') echo 'up'; else echo 'down';
        echo '.gif" alt="';
        if ($_SESSION['sens'] == 'ASC') echo 'up'; else echo 'down';
        echo '">';
     }
?>
   <span class="miniatureliste">[<b> <a href="showtn.php?<?php echo $chaine_post ?>"><?php echo $LANGUE['mode']['miniature'] ?></a> </b>]</span>
  </td>
  <td align="left" width="<?php echo $CONFIG['WIDTH_TD_SIZE'] ?>px">
<?php
     echo '<img src="img/seperator.png" alt="separator"> ';
     if ($_SESSION['filtre'] != 'taille')
        echo '<a href="?'.$chaine_post.'filtre=taille&amp;sens=ASC">';
        else
        {
        	echo '<a href="?'.$chaine_post.'filtre=taille&amp;';
        	if ($_SESSION['sens'] == 'ASC') echo 'sens=DESC">'; else echo 'sens=ASC">';
        }
     echo $LANGUE['liste']['Taille'].'</a> ';
     if ($_SESSION['filtre'] == 'taille')
     {
     	echo '<img src="img/fleche_';
        if ($_SESSION['sens'] == 'ASC') echo 'up'; else echo 'down';
		echo '.gif" alt="';
        if ($_SESSION['sens'] == 'ASC') echo 'up'; else echo 'down';
        echo '">';

     }
?>
  </td>
  <td align="left" style="width:<?php echo $CONFIG['WIDTH_TD_TYPE'] ?>px" >
<?php
     echo '<img src="img/seperator.png" alt="separator"> ';
     if ($_SESSION['filtre'] != 'type')
        echo '<a href="?'.$chaine_post.'filtre=type&amp;sens=ASC">';
        else
        {
        	echo '<a href="?'.$chaine_post.'filtre=type&amp;';
        	if ($_SESSION['sens'] == 'ASC') echo 'sens=DESC">';	else echo 'sens=ASC">';
        }
     echo $LANGUE['liste']['Type'].'</a> ';
     if ($_SESSION['filtre'] == 'type')
     {
     	echo '<img src="img/fleche_';
        if ($_SESSION['sens'] == 'ASC') echo 'up'; else echo 'down';
        echo '.gif" alt="';
        if ($_SESSION['sens'] == 'ASC') echo 'up'; else echo 'down';
        echo '">';

     }
?>
  </td>
  <td align="left" style="width:<?php echo $CONFIG['WIDTH_TD_DATE'] ?>px" >
<?php
     echo '<img src="img/seperator.png" alt="separator"> ';
     if ($_SESSION['filtre'] != 'date')
        echo '<a href="?'.$chaine_post.'filtre=date&amp;sens=ASC">';
        else
        {
        	echo '<a href="?'.$chaine_post.'filtre=date&amp;';
           if ($_SESSION['sens'] == 'ASC') echo 'sens=DESC">'; else echo 'sens=ASC">';
        }
     echo $LANGUE['liste']['Date'].'</a> ';
     if ($_SESSION['filtre'] == 'date')
     {
     	echo '<img src="img/fleche_';
        if ($_SESSION['sens'] == 'ASC') echo 'up'; else echo 'down';
        echo '.gif" alt="';
        if ($_SESSION['sens'] == 'ASC') echo 'up'; else echo 'down';
        echo '">';

     }
?>
  </td>
 </tr>
</table>
<table border="0" cellspacing="0" cellpadding="1" width="100%" >
<?php

$arrayLength = count($arg_table);
$i = 0;
foreach($arg_table as $echo){
     if ($echo != 'ASC' && $echo != 'DESC' && $echo != 'nom' && $echo != 'taille' && $echo != 'type' && $echo != 'date') $back_query[$i] = $echo;
     $i++;}

$back_url_query = '';
if (!empty($back_query))
{
   unset($back_query[(count($back_query)-1)]);
   $back_url_query = '?'.http_build_query($back_query,'');
}

if (!empty($back_url_query) && $CONFIG['BACK'])
{?>
 <tr class="lien">  
  <td style="width:17px"><a href="<?php echo 'list.php'.$back_url_query ?> " onClick="open('<?php echo 'arbre.php'.$back_url_query ?>','tree','') "><img src="<?php echo $CONFIG['ICO_FOLDER'].'/back.gif' ?>" alt="" ></a></td>
  <td <?php if (isset($hack_module['GestFiles']) and $gf_isauth) echo "colspan=2"; //~~<HACK_MODULE_GESTFILES>~~ ?>><a href="<?php echo 'list.php'.$back_url_query ?>" onClick="open('<?php echo 'arbre.php'.$back_url_query ?>','tree','') "><?php echo $LANGUE['liste']['retour']; ?></a></td>
  <td style="width:<?php echo $CONFIG['WIDTH_TD_SIZE'] ?>px" align="right" >&nbsp;</td>
  <td style="width:<?php echo $CONFIG['WIDTH_TD_TYPE'] ?>px" >&nbsp;</td>
  <td style="width:<?php echo $CONFIG['WIDTH_TD_DATE'] ?>px" >&nbsp;</td>
 </tr>
<?php
}

$LeListing = array();
$position_fichier = 0;
$position_dossier = 0;
$fichiers = array();
$dossiers = array();
for ($indextab=0;$indextab<count($tabg);$indextab++)
{
    while (@list($key,$val) = each($tabg[$indextab]))
    {
#          echo '$key : '.$key.' - $val : '.$val.'<br>';
          $zone_source = $val;
          $info = file_library($link.$zone_source);

          $show_tn_val = SelectAffichType($link,$zone_source,$CONFIG);

          $TYPE_FILES_FORBIDDEN = FALSE;
          while (list($MASK_TYPE_FILES_TABLE_KEY,$MASK_TYPE_FILES_TABLE_VAL) = each($CONFIG['MASK_TYPE_FILES_TABLE']))
          {
                if($info['ext'] == $MASK_TYPE_FILES_TABLE_VAL) $TYPE_FILES_FORBIDDEN = TRUE;
          }
          reset($CONFIG['MASK_TYPE_FILES_TABLE']);
          if ($TYPE_FILES_FORBIDDEN)
             continue;

          if ($CONFIG['IMAGE_TN'] && $show_tn_val)
             $file_to_open = 'showtn.php';
             else
               $file_to_open = 'list.php';

          if (is_dir($link.$zone_source))
          {
             $chaine_post_final = $chaine_post.'last='.rawurlencode($zone_source);
             $request = "<a href=\"$file_to_open?$chaine_post_final\" onClick=\"open('arbre.php?$chaine_post_final','tree','')\" >$zone_source</a>";
             $request_link = "<a href=\"$file_to_open?$chaine_post_final\" onClick=\"open('arbre.php?$chaine_post_final','tree','')\" >";
          }

          if (is_file($link.$zone_source))
          {
             $ExplorerPath = $CONFIG['ROOT'];
             $a =  SoustractPath($CONFIG['DOCUMENT_ROOT'],$ExplorerPath);
             $b = SoustractPath($link,$CONFIG['DOCUMENT_ROOT']);

             $chaine_url_final = EncodeForUrl('..'.RemoveLastSlashes($a).$b.$zone_source);

            $request = '<a href="'.$chaine_url_final.'">'.$zone_source.'</a>';
            $request_link = '<a href="'.$chaine_url_final.'">';
          }

          # Gestion des extensions � g�rer diff�rement par defaut (.jpg, .bmp, .gif et .ln)
          if (array_key_exists($info['ext'], $CONFIG['ext']))
          {
          	include('./ext/'.$info['ext'].'.php');
          	$ext_manager = call_user_func('fichier_'.$info['ext'], $link.$zone_source, $chaine_post);
			if ($ext_manager !== false)
			{
	          	$chaine_post_final = $ext_manager['chaine_post_final'];
				$request_link = $ext_manager['request_link'];
				$request = $ext_manager['request'];
				if (isset($ext_manager['ext']))
				{
					$temp = ext_library($ext_manager['ext']);
					$info['ico'] = $temp['ico'];
				}
			}
          }          

          if (parseListToHide($fileListToHide,$link.$zone_source,$CONFIG))
             continue;
                        
          $pathicon = '';

          if (file_exists($CONFIG['ICO_FOLDER'].'/'.$info['ico'].'.png'))
             $pathicon = $CONFIG['ICO_FOLDER'].'/'.$info['ico'].'.png';
             else if (file_exists($CONFIG['ICO_FOLDER'].'/'.$info['ico'].'.gif'))
                     $pathicon = $CONFIG['ICO_FOLDER'].'/'.$info['ico'].'.gif';

      	  $html = ' <tr class="lien">'."\n";
if (isset($hack_module['GestFiles']) and $gf_isauth) $html .= gf_print_checkbox($link.$zone_source); //~~<HACK_MODULE_GESTFILES>~~
          $html = $html.'  <td style="width:17px">'.$request_link.'<img src="'.$pathicon.'" alt="'.$info['type'].'" title="'.$info['type'].'"></a></td>'."\n";
          $html = $html.'  <td align="left">'.$request.'</td>'."\n";
          $html = $html.'  <td style="width:'.$CONFIG['WIDTH_TD_SIZE'].'px" align="right" >'.$info['size'].'&nbsp;</td>'."\n";
          $html = $html.'  <td style="width:'.$CONFIG['WIDTH_TD_TYPE'].'px" >&nbsp;'.$info['type'].'</td>'."\n";
          $html = $html.'  <td style="width:'.$CONFIG['WIDTH_TD_DATE'].'px" >&nbsp;'.$info['date'].'</td>'."\n";
          $html = $html.' </tr>'."\n";

          if (is_file($link.$zone_source))
          {  $fichiers[$position_fichier]['html'] = $html;
             $fichiers[$position_fichier]['ico'] = $info['ico'];
             $fichiers[$position_fichier]['nom'] = strtolower($zone_source);
             if (isset($info['size']))
                $fichiers[$position_fichier]['taille'] = filesize($link.$zone_source);
                else
                  $fichiers[$position_fichier]['taille'] = 0;
             $fichiers[$position_fichier]['type'] = $info['type'];
             $fichiers[$position_fichier]['date'] = $info['date'];
             $position_fichier++;
          }
          if (is_dir($link.$zone_source))
          {  $dossiers[$position_dossier]['html'] = $html;
             $dossiers[$position_dossier]['ico'] = $info['ico'];
             $dossiers[$position_dossier]['nom'] = strtolower($zone_source);
             if (isset($info['size']))
                $dossiers[$position_dossier]['taille'] = filesize($link.$zone_source);
                else
                  $dossiers[$position_dossier]['taille'] = 0;
             $dossiers[$position_dossier]['type'] = $info['type'];
             $_annee = substr($info['date'],6, 4);
             $_mois = substr($info['date'],3, 4);
             $_jour = substr($info['date'],0, 2);
             $_heure = substr($info['date'],11, 2);
             $_minute = substr($info['date'],14, 2);
             $dossiers[$position_dossier]['date'] = $_annee.'/'.$_mois.'/'.$_jour.' '.$_heure.':'.$_minute;
             $position_dossier++;
          }

          unset($info);
          unset($info_ask);
    }
}

switch($_SESSION['sens'])
{  case 'ASC':    $_sens = SORT_ASC;    break;
   case 'DESC':   $_sens = SORT_DESC;    break;
}

switch($_SESSION['filtre'])
{  case 'taille':    $_ordre = SORT_NUMERIC;    break;
   default:          $_ordre = SORT_STRING;     break;
}

if (($_SESSION['sens'] == 'DESC'))
{
   if (count($fichiers) != 0)
   {  # Traitement des fichiers selon le filtre et le sens
      $colonne = array();
      foreach ($fichiers as $key => $row)
         $colonne[$key]  = $row[$_SESSION['filtre']];
      $temp = array_multisort($colonne, $_sens, $_ordre, $fichiers);
      reset($fichiers);
      for ($i=0; $i<$position_fichier; $i++)
          echo $fichiers[$i]['html'];
   }
   if (count($dossiers) != 0)
   {  # Traitement des dossiers selon le filtre et le sens
      $colonne = array();
      foreach ($dossiers as $key => $row)
         $colonne[$key]  = $row[$_SESSION['filtre']];
      $temp = array_multisort($colonne, $_sens, $_ordre, $dossiers);
      reset($dossiers);
      for ($i=0; $i<$position_dossier; $i++)
          echo $dossiers[$i]['html'];
   }
}
else
{
   if (count($dossiers) != 0)
   {  # Traitement des dossiers selon le filtre et le sens
      $colonne = array();
      foreach ($dossiers as $key => $row)
         $colonne[$key]  = $row[$_SESSION['filtre']];
      $temp = array_multisort($colonne, $_sens, $_ordre, $dossiers);
      reset($dossiers);
      for ($i=0; $i<$position_dossier; $i++)
          echo $dossiers[$i]['html'];
   }
   if (count($fichiers) != 0)
   {  # Traitement des fichiers selon le filtre et le sens
      $colonne = array();
      foreach ($fichiers as $key => $row)
         $colonne[$key]  = $row[$_SESSION['filtre']];
      $temp = array_multisort($colonne, $_sens, $_ordre, $fichiers);
      reset($fichiers);
      for ($i=0; $i<$position_fichier; $i++)
          echo $fichiers[$i]['html'];
   }
}
?>

</table>
<?php if (isset($hack_module['GestFiles']) and $gf_isauth) gf_print_footer(); //~~<HACK_MODULE_GESTFILES>~~ ?>
</body>
</html>