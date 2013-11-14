<?php

if (!function_exists('fichier_jpg'))
{
	function fichier_jpg($file, $chainepost)
	{
		global $CONFIG;
		
		$pos = strrpos($file, '/');
		$nom = substr($file, $pos+1);
		
		if ($CONFIG['IMAGE_BROWSER'])
		{
			$val['chaine_post_final'] = $chainepost.'last='.rawurlencode($nom);
			$val['request_link'] = '<a href="showpict.php?'.$val['chaine_post_final'].'" onClick="open(\'arbre.php?'.$val['chaine_post_final'].'\',\'tree\',\'\')">';
			$val['request'] = $val['request_link'].$nom.'</a>';
			$val['nom'] = $nom;
			return $val;
		}
	
		return false;
	}
}
?>