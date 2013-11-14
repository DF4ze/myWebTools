<?php
include('config.inc.php');

if(!isset($CONFIG['SYS_LANG'])) 
        $CONFIG['SYS_LANG'] = 'fr';

$chemin = dirname(__FILE__);
if (file_exists($chemin.'/lang/lang.'.$CONFIG['SYS_LANG'].'.ini'))
   $LANGUE = parse_ini_file($chemin.'/lang/lang.'.$CONFIG['SYS_LANG'].'.ini',true);
   else
   {
      if (file_exists($chemin.'/lang/lang.fr.ini'))
         $LANGUE = parse_ini_file($chemin.'/lang/lang.fr.ini',true);
         else
            die('<font size="1" face="MS Sans Serif, Courier, Arial" color="red" ><b>- ERREUR -</b><br />Le fichier de \'lang.fr.ini\' est introuvable !</font>');
   }
   
$LANGUE['lang']['fr'] = 'fran&ccedil;ais';
$LANGUE['lang']['en'] = 'english';

?>