<?php
/*
CheckAuth() retournera
-1 si le fichier auth.inc.php nexiste pas (il faut alors se logger pour le creer automatiquement
0  si l'acces est refusé
1  si l'acces est autorisé
*/
@session_start() or die('Impossible de creer de session!<br><b>Si vous le script est hebergé chez FREE, il est necessaire de creer un dossier \'sessions\' à la racine de votre site!</b>');

include('../config.inc.php');
include('auth/func.inc.php');
include('lang.inc.php');

function RecursiveSuppr($dir)
{
	$nb = 0;
	$h = opendir($dir);
	while(FALSE !== ($fp = readdir($h)))
	{
		$link = $dir.'/'.$fp;
		if($fp != '.' && $fp != '..')
		{
			if (is_dir($link))
				RecursiveSuppr($link);
				else
				{
					if ($fp == '.dirinfo')
					{
						$result = @unlink($link);
						if ($result)
							$nb++;#$msg .= 'Suppression de "'.$link.'<br>';
							else
								return -1;
					}						
				}
		}
	}
	closedir($h);
	return $nb;
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>ClearTempTn</title>
<link href="../styles/<?php echo $CONFIG['CSS'] ?>" rel="stylesheet" type="text/css">
<link href="cleartemptn/style.css" rel="stylesheet" type="text/css">
</head>
<body>
    
<table border="0" cellpadding="1" cellspacing="0" width="100%" >
  <tr class="bande"> 
    <td>[ <?php echo $LANGUE['modules']['cleartemptn']['nom_module'] ?> ]</td>
  </tr>
</table>
<?php
        $status = '';

        if (CheckAuth('auth/auth.inc.php')!==1){  
                $status = $LANGUE['divers']['accesrf'];
        }

        if(empty($status))
                $handle_source = @opendir('../temp') or
                        $status = $LANGUE['modules']['cleartemptn']['impossible_ouvrir'];

        if(empty($status))
        {
            # On supprime les vignettes mises en cache
            if(isset($_GET['supprCache']))
            {
                    while (false !== ($zone_source = readdir($handle_source))){
                            if($zone_source[0] != '.') @unlink('../temp/'.$zone_source) or
                                  $status = $LANGUE['modules']['cleartemptn']['impossible_suppr'];
                            
                                  $status = $LANGUE['modules']['cleartemptn']['suppr_ok'].'<br /><br /><br /><br /><br /><br /><br /><br />';
                            }
            
            }
            # On supprime les .dirinfo
            if (isset($_GET['supprInfo']))
            {
            	$val = RecursiveSuppr(RemoveLastSlashes($CONFIG['ROOT']).RemoveLastSlashes($CONFIG['DOCUMENT_ROOT']));
            	if ($val == -1)
            		$status = $LANGUE['modules']['cleartemptn']['impossible_suppr'];
            		else
            			$status = $LANGUE['modules']['cleartemptn']['suppr_ok'].'<br /><br /><br /><br /><br /><br /><br /><br />';
            }
        }

        $i = 0;
        if(empty($status)){
                while (false !== ($zone_source = readdir($handle_source))){
                        if($zone_source[0] != '.') $i++;
                }

                echo '<br /><br /><br /><br /><br />';
                echo '<div align="center" class="titre1">'.$LANGUE['modules']['cleartemptn']['nb_fic_deb'].' '.$i.' '.$LANGUE['modules']['cleartemptn']['nb_fic_fin'].' ('.convertUnits(RecursiveSize('../temp')).')</div>';
        }

?>
<br><br><br>
<?php
        if(empty($status)){
?>
<form method="get" action="">
 <center>
  <input type="submit" value="<?php echo $LANGUE['modules']['cleartemptn']['actionCache'] ?>" name="supprCache" class="form">
  <br><br>
  <input type="submit" value="<?php echo $LANGUE['modules']['cleartemptn']['actionInfo'] ?>" name="supprInfo" class="form">
  </center>
</form>
<?php
        }
?>
<br><br><div align="center" class="titre1"><?php echo $status ?></div><br><br>
<?php
if (CheckAuth('auth/auth.inc.php')==1)
{
?>
<table  border="0" align="center" width="600" class="tn" >
 <tr> 
  <td>
   <p align="left">
    <font size="2" face="Arial, Helvetica, sans-serif">
    <?php echo $LANGUE['modules']['cleartemptn']['message']; ?>
    </font>
   </p>
  </td>
 </tr>
</table>
<?php } ?>
</body>
</html>