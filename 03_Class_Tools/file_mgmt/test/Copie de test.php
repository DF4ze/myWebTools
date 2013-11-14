<?php
 echo "http://".$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];
 
	$relativefolder = "/Upload";
 	$absolutefolder = "http://".$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];
	$absolutefolder = str_replace( __FILE__, $relativefolder, $absolutefolder );
	
	echo "<br/>".$absolutefolder."<br/>".__FILE__.'<br/>';
?>