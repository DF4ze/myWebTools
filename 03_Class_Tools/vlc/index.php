<?php

require_once( "vlcplayer.class.php" );

$vlc = new VlcPlayer();//"10.210.137.194");

if( isset( $_POST["play"] ) )
	$vlc->play();
else if( isset( $_POST["pause"] ) )
	$vlc->pause();
else if( isset( $_POST["add"] ) )
	$vlc->add('NomChanson', 'D:\donnees\Ma musique\01 Ethna - Ocean And Emotion.mp3');
else if( isset( $_GET["test"] ) )
	echo $_GET["test"];
else if( isset( $_POST["infos"] ) )
	echo var_dump( $vlc->fullstate());
	
	
	echo urlencode( "coucou l'espace!" )."<br/>";
	echo htmlspecialchars( "coucou l'espace!" )."<br/>";
	echo htmlentities( "coucou l'espace!" )."<br/>";
	echo rawurlencode( "coucou l'espace!" )."<br/>";
?>




<form action="index.php" method="POST" >
<input type="submit" value="Play" name="play" >
<input type="submit" value="Pause" name="pause" >
<input type="submit" value="Add" name="add" >
<input type="submit" value="Infos" name="infos" >
</form>

<form action="index.php" method="GET" >
<input type="submit" value="test test" name="test">
</form>
