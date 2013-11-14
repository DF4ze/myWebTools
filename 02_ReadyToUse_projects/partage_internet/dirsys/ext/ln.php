<?php

if (!function_exists('fichier_ln'))
{
	function fichier_ln($file, $chainepost)
	{
		$fichier = parse_ini_file($file);
		
		if (isset($fichier['nom']))
			$nom = $fichier['nom'];
		else
		{
			$pos = strrpos($file, '/');
			$nom = substr($file, $pos+1);
		}
		
		if (isset($fichier['url']))
			$url = $fichier['url'];
		else
			$url = '../'.$nom;
		
			if (isset($fichier['ext']))
			$ext = $fichier['ext'];
		else
			$ext = 'no';
	
		$val['chaine_post_final'] = $chainepost.'last='.rawurlencode($nom);
		$val['request_link'] = '<a href="'.$url.'" >';
		$val['request'] = $val['request_link'].$nom.'</a>';
		$val['nom'] = $nom;
		$val['ext'] = $ext;

		return $val;
	}
}
?>