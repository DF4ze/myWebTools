<?php

if(isset($CONFIG))
switch($CONFIG['SYS_LANG'])
{
    default:
    case 'fr' :
        $ModuleTitle = "Authentification";
        break;
    case 'en' :
        $ModuleTitle = "Authentification";
        break;
    case 'de' :
        $ModuleTitle = "Als echt erkennen";
        break;
}
$ModuleIco = 'auth/ico.gif';
$EnableModule = true;
$AdminModule = false;

if (dirname(__FILE__)==getcwd()){
        include('auth/index_auth.php');
}

?>