<?php
@session_start();
@include_once('./modules/gestfiles/gestfiles.func.php'); //~~<HACK_MODULE_GESTFILES>~~
include_once('./config.inc.php');
include_once('./list.inc.php');
	
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
<table width="100%" border="0" cellpadding="1" cellspacing="0"><tr class="bande" ><td class="miniatureliste" >[<b> <a href="list.php?<?php echo $chaine_post ?>" ><?php echo $LANGUE['mode']['liste']; ?></a> </b>] </td></tr></table>
<table width="100%" border="0" cellpadding="1" cellspacing="1">
<?php if (isset($hack_module['GestFiles']) and $gf_isauth) gf_print_header(); //~~<HACK_MODULE_GESTFILES>~~ ?>
<tr>
<?php
        $i = 0;
        for($indextab=0;$indextab<count($tabg);$indextab++){
                while (@list($key,$val) = each($tabg[$indextab])) {
                        $zone_source = $val;
                        $info = file_library($link.$zone_source);


//------------------------------------------------------------
                        $show_tn_val = SelectAffichType($link,$zone_source,$CONFIG);
//------------------------------------------------------------


                        $TYPE_FILES_FORBIDDEN = FALSE;
                        while (list($MASK_TYPE_FILES_TABLE_KEY,$MASK_TYPE_FILES_TABLE_VAL) = each($CONFIG['MASK_TYPE_FILES_TABLE'])) {
                                if($info['ext'] == $MASK_TYPE_FILES_TABLE_VAL) $TYPE_FILES_FORBIDDEN = TRUE;
                        }
                        reset($CONFIG['MASK_TYPE_FILES_TABLE']);
                        if($TYPE_FILES_FORBIDDEN) continue;

                        if($CONFIG['IMAGE_TN'] && $show_tn_val) $file_to_open = 'showtn.php'; else $file_to_open = 'list.php';

                        $chaine_post_final = $chaine_post.'last='.rawurlencode($zone_source);

                        if(is_dir($link.$zone_source)) {
                                //$chaine_post_final = $chaine_post.'last='.rawurlencode($zone_source);
                                $request = "<a href=\"$file_to_open?$chaine_post_final\" onClick=\"open('arbre.php?$chaine_post_final','tree','')\" >$zone_source</a>";
                                $request_link = "<a href=\"$file_to_open?$chaine_post_final\" onClick=\"open('arbre.php?$chaine_post_final','tree','')\" >";
                        }

                        if(is_file($link.$zone_source)) {

                                $ExplorerPath = $CONFIG['ROOT'];
                                $a =  SoustractPath($CONFIG['DOCUMENT_ROOT'],$ExplorerPath);
                                $b = SoustractPath($link,$CONFIG['DOCUMENT_ROOT']);
                                 
                                $chaine_url_final = EncodeForUrl('..'.RemoveLastSlashes($a).$b.$zone_source);
                                $request = '<a href="'.$chaine_url_final.'">'.$zone_source.'</a>';
                                $request_link = '<a href="'.$chaine_url_final.'">';
                        }

                        
                        # Gestion des extensions � g�rer diff�rement (par defaut .jpg, .bmp, .gif et .ln)
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
								$file_name_resized = resize_text($ext_manager['nom']);
							}
							if ($info['ext'] == 'ln')
								$file_link = true;
							else
								$file_link = false;
                        }else 
                        {
                        	$file_name_resized = resize_text($zone_source);
                        }

                        if($CONFIG['WRITE_TN'] && file_exists('temp/'.@checkFile(resolvePath($link).'/'.$zone_source).'.jpg'))
                        	$urlforimage = 'temp/'.checkFile(resolvePath($link).'/'.$zone_source).'.jpg';
                        else
                        	$urlforimage = 'tn.php?'.$chaine_post_final;

                        if(parseListToHide($fileListToHide,$link.$zone_source,$CONFIG)) continue;

                        if( ($i/$CONFIG['NB_COLL_TN']) == ceil( $i/$CONFIG['NB_COLL_TN'] )  && ($i > 0) ) { echo '</tr><tr>'; }

                        $pathicon = '';
?>
<td class="nomimage" align="center">

<table border="0" class="tn" width="<?php echo $CONFIG['IMAGE_TN_SIZE'] ?>"><tr><td height="<?php echo $CONFIG['IMAGE_TN_SIZE'] ?>">
<?php
                        if(($info['ext'] != 'ln') && (($info['ico']=='jpg' && $CONFIG['IMAGE_JPG']) || ($info['ico']=='bmp' && $CONFIG['IMAGE_BMP']) || ($info['ico']=='gif' && $CONFIG['IMAGE_GIF'])))
                        {
                                echo $request_link;
?>
<img align="middle" src="<?php echo $urlforimage ?>" alt="<?php echo $zone_source ?>&nbsp;&nbsp;<?php echo $info['size'] ?>" title="<?php echo $zone_source ?>&nbsp;&nbsp;<?php echo $info['size'] ?>" ></a>
<?php
                        }
                        else {
                                echo $request_link;

                                if(file_exists($CONFIG['ICO_FOLDER'].'/'.$info['ico'].'_big.png')) {
                                   $pathicon = $CONFIG['ICO_FOLDER'].'/'.$info['ico'].'_big.png';
                                }
                                else if(file_exists($CONFIG['ICO_FOLDER'].'/'.$info['ico'].'_big.gif')) {
                                        $pathicon = $CONFIG['ICO_FOLDER'].'/'.$info['ico'].'_big.gif';
                                }
                                else if(file_exists($CONFIG['ICO_FOLDER'].'/'.$info['ico'].'.png')) {
                                        $pathicon = $CONFIG['ICO_FOLDER'].'/'.$info['ico'].'.png';
                                }
                                else if(file_exists($CONFIG['ICO_FOLDER'].'/'.$info['ico'].'.gif')){
                                        $pathicon = $CONFIG['ICO_FOLDER'].'/'.$info['ico'].'.gif';
                                }

?>
<img align="middle" src="<?php echo $pathicon ?>" alt="<?php echo $zone_source ?>&nbsp;&nbsp;<?php echo $info['size'] ?>" title="<?php echo $zone_source ?>&nbsp;&nbsp;<?php echo $info['size'] ?>" ></a>
<?php
                        }
?>
</td></tr></table>
<div class="nom" style="width:<?php echo $CONFIG['IMAGE_TN_SIZE'] ?>px;"><?php echo $request_link.$file_name_resized; ?></a></div>
<div class='nom'><?php if (isset($hack_module['GestFiles']) and $gf_isauth) gf_print_checkbox_tn($link.$val); //~~<HACK_MODULE_GESTFILES>~~ ?></div>
</td>
<?php
                        $i++;
                        unset($info);
                }
        }
 ?>
 </tr></table>
<?php if (isset($hack_module['GestFiles']) and $gf_isauth) gf_print_footer(); //~~<HACK_MODULE_GESTFILES>~~ ?>
</body>
</html>