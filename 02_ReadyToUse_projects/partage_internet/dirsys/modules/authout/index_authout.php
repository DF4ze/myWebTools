<?php
/*******************************************************************************************
*   Script du module déconnexion réalisé par Xavier MEDINA pour le projet JBC Explorer     *
*   Site web du projet : http://www.jbc-explorer.com/                                      *
*   Mon mail : xabi62@yahoo.fr                                                             *
*                                                                                          *
*   Le script permet de se déconnecter proprement après s'être identifer.                  *
*                                                                                          *
*******************************************************************************************/
@session_start() or die('Impossible de creer de session!<br><b>Si vous le script est hebergé chez FREE, il est necessaire de creer un dossier \'sessions\' à la racine de votre site!</b>');

include('../config.inc.php');

function UnSetAuth()
{
     $_SESSION['authLogin'] = "";
     $_SESSION['authPassword'] = "";
}

UnSetAuth();
?>
<html>
<body>
<?php
echo '<script language="javascript">';
echo "open('../../index.php?lang=".$CONFIG['SYS_LANG']."','_parent','');";
echo '</script>';

?>

</body>
</html>