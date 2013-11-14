<?php


// ces 3 variables sont indispensable
$ModuleIco = "cleartemptn/icon.gif";                           // chemin vers l'icone a coté du titre dans larbre
$EnableModule = TRUE;                                   // active ou desactive le module
$AdminModule = true;

if(isset($CONFIG))
switch($CONFIG['SYS_LANG'])
{
    default:
    case 'fr' :
        $ModuleTitle = "Vider le cache";
        break;
    case 'en' :
        $ModuleTitle = "Clear temp";
        break;
    case 'de' :
        $ModuleTitle = "Vider le cache";
        break;
}

if(isset($CONFIG['WRITE_TN']) && $CONFIG['WRITE_TN']===FALSE) $EnableModule = FALSE;
//cette condition permet au script de savoir si il dois inclure ou non le fichier du module!
if(dirname(__FILE__)==getcwd()) include(getcwd().'/cleartemptn/index_cleartemptn.php');

?>
