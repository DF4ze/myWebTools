<?php
/*******************************************************************************************
*   Script du module d�connexion r�alis� par XaV pour le projet JBC Explorer               *
*   Site web du projet : http://www.jbc-explorer.com/                                      *
*   Mon mail : xabi62@yahoo.fr                                                             *
*                                                                                          *
*   Le script permet de se d�connecter apr�s s'�tre identifer.                             *
*                                                                                          *
*******************************************************************************************/

if(isset($CONFIG))
switch($CONFIG['SYS_LANG'])
{
    default:
    case 'fr' :
        $ModuleTitle = "D&eacute;connexion";
        break;
    case 'en' :
        $ModuleTitle = "Log out";
        break;
    case 'de' :
        $ModuleTitle = "Unlog";
        break;
}
$ModuleIco = "authout/ico.gif";
$EnableModule = true;
$AdminModule = true;                                    // module visible que pour l'administrateur

if (dirname(__FILE__)==getcwd())
   include('authout/index_authout.php');

?>