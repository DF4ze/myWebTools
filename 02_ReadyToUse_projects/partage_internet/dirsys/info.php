<?php
@session_start();

        include('./config.inc.php');

        $uptodate = false;
	$version = 721;  // Indicateur de version! ne pas toucher!
        if($CONFIG['CHECK_MAJ']){
                $t = @file('http://www.jbc-explorer.info/system/version');
                if($t != false)
                if($t[0] > $version) $uptodate = true;
        }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>INFO</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="styles/<?php echo $CONFIG['CSS'] ?>" rel="stylesheet" type="text/css">
</head>
<body>
<br>
<div class="center"><img src="img/jbc_explorer.gif" alt="web explorer"><p></p><p class="center"><b>Version <?php echo substr($version,0,1).'.'.substr($version,1); ?></b></p>
<div class="borderinfo"><b><?php echo $LANGUE['info']['MASK_TYPE_FILES']; ?> :</b>
  <div><?php echo $CONFIG['MASK_TYPE_FILES'] ?></div>
  <p></p>
  <div><b><?php echo $LANGUE['info']['CONFIG'] ?> :</b></div>
  <div><?php
          if($CONFIG['IMAGE_BROWSER']) echo $LANGUE['info']['IMAGE_BROWSER_ON'];
          else echo $LANGUE['info']['IMAGE_BROWSER_OFF'];
          ?></div>
<div><?php
          echo $LANGUE['info']['LANG'].' : '.$LANGUE['lang'][$CONFIG['SYS_LANG']];
          ?></div>
<div><?php
          if($CONFIG['IMAGE_TN']) echo $LANGUE['info']['IMAGE_TN_ON'];
          else echo $LANGUE['info']['IMAGE_TN_OFF'];
          ?></div>
<div><?php
          if($CONFIG['EXIF_READER']) echo $LANGUE['info']['EXIF_READER_ON'];
          else echo $LANGUE['info']['EXIF_READER_OFF'];
          ?></div>
<div><?php
          if($CONFIG['SLIDE_SHOW']) echo $LANGUE['info']['SLIDE_SHOW_ON'].', '.$LANGUE['info']['SLIDE_SHOW_INT'].' : '.$CONFIG['SLIDE_SHOW_INT'].' sec';
          else echo $LANGUE['info']['SLIDE_SHOW_OFF'];
          ?></div>
<div><?php
          if($CONFIG['GD2']) echo $LANGUE['info']['GD2_ON'];
          else echo $LANGUE['info']['GD2_OFF'];
          ?></div>
<div>
    <?php if($CONFIG['WRITE_TN']) echo $LANGUE['info']['WRITE_TN_ON'];
          else echo $LANGUE['info']['WRITE_TN_OFF'];
          ?></div>
<div>
    <?php if($CONFIG['IMAGE_JPG']) echo $LANGUE['info']['IMAGE_JPG_ON'];
          else echo $LANGUE['info']['IMAGE_JPG_OFF'];
          ?></div>
<div>
    <?php if($CONFIG['IMAGE_GIF']) echo $LANGUE['info']['IMAGE_GIF_ON'];
          else echo $LANGUE['info']['IMAGE_GIF_OFF'];
          ?></div>
<div>
    <?php if($CONFIG['IMAGE_BMP']) echo $LANGUE['info']['IMAGE_BMP_ON'];
          else echo $LANGUE['info']['IMAGE_BMP_OFF'];
          ?></div>
<div>
    <?php if($CONFIG['DEBUG']) echo $LANGUE['info']['DEBUG_ON'];
          else echo $LANGUE['info']['DEBUG_OFF'];
          ?></div>
<div>
    <?php if($CONFIG['CHECK_MAJ']) { echo $LANGUE['info']['CHECK_MAJ_ON'];if ($uptodate) echo ' [<b> '.$LANGUE['info']['CHECK_MAJ_OK'].' </b> ]';else echo ' [<b> '.$LANGUE['info']['CHECK_MAJ_KO'].' </b> ]';}
          else echo $LANGUE['info']['CHECK_MAJ_OFF'];
          ?></div>
<div>
    <?php
        echo $LANGUE['info']['lang_dispo'].' : ';
        $handle = opendir('./lang/');
        while (false !== ($file = readdir($handle)))
        {
           if (is_file('./lang/'.$file))
           {
              if ((substr($file, 0, 5) == 'lang.') && (substr($file,7) == '.ini'))
              {  $lang = substr($file, 5, 2);
                 if (file_exists('./img/lang.'.$lang.'.gif'))
                    echo '<a href="../index.php?lang='.$lang.'" target="_parent"><img src="img/lang.'.$lang.'.gif" title="'.$LANGUE['lang'][$lang].'" alt="'.$LANGUE['lang'][$lang].'"></a>&nbsp;&nbsp;';
              }
           }
        }
        closedir($handle);
          ?></div>
</div>
<p></p>
<div class="borderinfo">
<?php
        echo '<div><b>'.$LANGUE['info']['SIZE'].' :</b></div>';

        $TotalSize = $CONFIG['TOTALSIZE'];
        $UsedSize = RecursiveSize($CONFIG['DOCUMENT_ROOT']);
        if($TotalSize < $UsedSize) $TotalSize = $UsedSize;
        $FreeSize = $TotalSize - $UsedSize;

        echo '<div>'.$LANGUE['info']['SIZE_TOTAL'].' :   '.convertUnits($TotalSize).'</div>';
        echo '<div>'.$LANGUE['info']['SIZE_USED'].' : '.convertUnits($UsedSize).'</div>';
        echo '<div>'.$LANGUE['info']['SIZE_FREE'].' :   '.convertUnits($FreeSize).'</div>';

        $taille = 450;
        echo '<div class="center">'."\r\n";
        echo '<img src="img/size_red_start.gif" height="9" alt="">';
        echo '<img src="img/size_red_middle.gif" width="'.(int)($UsedSize/$TotalSize*$taille).'" height="9" alt="">';
        if ($UsedSize == $TotalSize)
           echo '<img src="img/size_red_end.gif" height="9" alt="">';
           else
           {  echo '<img src="img/size_bleu_middle.gif"  width="'.(int)($FreeSize/$TotalSize*$taille).'" height="9" alt="">';
              echo '<img src="img/size_bleu_end.gif" height="9" alt="">';
           }
        echo "\r\n</div>\r\n";
?>
</div>
<p></p>
<div class="borderinfo">
 <div><b><?php echo $LANGUE['info']['aide'] ?> :</b></div>
 <div><?php echo $LANGUE['info']['faq'] ?></div>
 <div><?php echo $LANGUE['info']['telechargement'] ?> : <a href="http://www.jbc-explorer.com/?action=download&download=last"><img src="<?php echo $CONFIG['ICO_FOLDER'] ?>/disk.gif" alt="<?php echo $LANGUE['divers']['click'] ?>" align="absmiddle" title="<?php echo $LANGUE['divers']['click'] ?>" ></a></div>
</div>
<p></p>
<div class="borderinfo"><?php echo $LANGUE['info']['auteur'] ?> : MAMMANA Jean Charles & MEDINA Xavier<br />
<?php echo $LANGUE['info']['site_perso'] ?> Jean-Charles : <a href="http://jc.mammana.free.fr" target="_blank">http://jc.mammana.free.fr/</a><br />
<?php echo $LANGUE['info']['site_perso'] ?> Xavier : <a href="http://www.zone-cine.com" target="_blank">http://www.zone-cine.com</a><br />
<?php echo $LANGUE['info']['site_explorer'] ?> : <a href="http://www.jbc-explorer.com" target="_blank">http://www.jbc-explorer.com/</a><br />
</div>
<?php
if ($uptodate) echo "<script language=\"javascript\">var v = confirm('Une Nouvelle version du script est disponible.\\ncliquez sur ok pour la telecharger.');if(v) open('http://www.jbc-explorer.com/?action=download&download=last','_self','');</script>";
?>
<p></p></body>
</html>