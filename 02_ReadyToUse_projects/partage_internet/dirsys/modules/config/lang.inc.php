<?php
if(!isset($CONFIG['SYS_LANG'])) 
        $CONFIG['SYS_LANG'] = 'fr';

$chemin = dirname(__FILE__);
if (file_exists($chemin.'/lang.'.$CONFIG['SYS_LANG'].'.ini'))
   $LANGUE['modules'] = parse_ini_file($chemin.'/lang.'.$CONFIG['SYS_LANG'].'.ini',true);
   else
   {
      if (file_exists($chemin.'/lang.fr.ini'))
         $LANGUE['modules'] = parse_ini_file($chemin.'/lang.fr.ini',true);
         else
            die('<font size="1" face="MS Sans Serif, Courier, Arial" color="red" ><b>- ERREUR -</b><br />Le fichier de \'lang.fr.ini\' est introuvable !</font>');
   }
?>