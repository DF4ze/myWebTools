<?php

// ces 3 variables sont indispensable
$ModuleIco = "forum/icone.gif";
$EnableModule = true;
$AdminModule = false;

if(isset($CONFIG))
switch($CONFIG['SYS_LANG'])
{   default:
    case 'fr' :
        $ModuleTitle = "FAQ & Forum";
        break;
    case 'en' :
        $ModuleTitle = "FAQ & Forum";
        break;
    case 'de' :
        $ModuleTitle = "FAQ & Forum";
        break;
}


//cette condition permet au script de savoir si il dois inclure ou non le fichier du module!
if (dirname(__FILE__)==getcwd())
   include('forum/index_forum.php');

?>
