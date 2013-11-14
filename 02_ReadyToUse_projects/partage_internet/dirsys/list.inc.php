<?php
        $arg_table = $_GET;
        # Purge des parametres (on vire les param vide)
        while (list($key,$val) = each($arg_table))
		{
			if ($val == '')				unset($arg_table[$key]);
		}
		reset($arg_table);
        $chaine_post = '';
        $chaine_url = '';
        $link = '';
        $fileListToHide = file('hide.php');
        $nb_hide = count($fileListToHide);

        if((isset($arg_table['last'])) && ($arg_table['last'] != ''))
        {
                $size_arg_table = sizeof($arg_table);
                $arg_table[$size_arg_table-1] = $arg_table['last'];
                unset($arg_table['last']);
        }
        if(empty($arg_table))
                $link = $CONFIG['DOCUMENT_ROOT'];
        else
        {
			while (list($key,$val) = each($arg_table))
			{
				if(!is_numeric($key)) continue;
				$val = stripslashes($val);
				$link .= $val.'/';
				
				$val = str_replace('\\', '', $val);
				
				if ( ereg('^\.\.*\.$',$val) || ereg('/',$val) || (substr($val,0,1) == '.'))
				die('<font size="1" face="MS Sans Serif, Courier, Arial" color="#FF0000" >'.$LANGUE['erreur']['Violation'].'</font>');
				$val = rawurlencode($val);
				$chaine_post .= $key.'='.$val.'&';
				$chaine_url .= $val.'/';
			}
			$link = $CONFIG['DOCUMENT_ROOT'].$link;
        }
        reset($arg_table);

        # Pour toutes les lignes du fichier hide.php
        # On regarde si le chemin complet depuis le DOCUMENT_ROOT = le link construit avec les paramètres
        for ($i=0; $i<$nb_hide; $i++)
        {
        	if (RemoveLastSlashes(trim($link)) != RemoveLastSlashes(trim($CONFIG['DOCUMENT_ROOT'])))
            {
               $chemin = RemoveLastSlashes($CONFIG['DOCUMENT_ROOT']).trim(AddFirstSlashes($fileListToHide[$i]));
               if (RemoveLastSlashes(trim($link)) == RemoveLastSlashes(trim($chemin)))
                  die('<font color="#FF000">'.$LANGUE['erreur']['Violation'].'</font>');
            }
        }

		if (isset($hack_module['GestFiles']) and $gf_isauth) gf_do_action($link); //~~<HACK_MODULE_GESTFILES>~~
        $handle_source = @opendir($link) or die('<font size="1" face="MS Sans Serif, Courier, Arial" color="red" >'.$LANGUE['erreur']['Config'].'</font>');
        $tableaufile = '';
        $tableaudir = '';
        $tabg = '';

        while (false !== ($zone_source=readdir($handle_source))){
                if( $zone_source!='..' && $zone_source[0]!='.' ){
                        if(is_file($link.$zone_source)) $tableaufile[]=$zone_source;
                        if(is_dir($link.$zone_source)) $tableaudir[]=$zone_source;
                }
        }
        closedir($handle_source);

        $indextab=0;
        if(is_array($tableaudir)){
                iSort($tableaudir);
                $tabg[$indextab] = $tableaudir;
                $indextab++;
        }
        if(is_array($tableaufile)){
                iSort($tableaufile);
                $tabg[$indextab] = $tableaufile;
                $indextab++;
        }
//print_r($tableau);
?>