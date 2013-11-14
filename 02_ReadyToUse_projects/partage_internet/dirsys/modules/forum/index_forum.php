<?php

include('lang.inc.php');
// ce script va chercher l'url du forum car il est possible qu'a l'avenir le forum
// change d'adresse...

        $t = @file ('http://www.jbc-explorer.info/system/forum');
        if(!empty($t[0]))
                header($t[0]);
                
       echo '<font color="#FF0000">'.$LANGUE['modules']['forum']['message'].'</font>';

?>
