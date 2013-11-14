<?php
	 
 	///////////////////////////////////////////////////////
	// Connexion My SQL
	if( @mysql_connect( '10.210.137.248', 'easy-stat', 'easy-stat') ){
		echo "Connexion au Serveur MySQL avec succes.<br/>";
		// Alors on lance la connexion à la base.
		mysql_select_db('class_stats') or die ("Connexion à la base MySql impossible");
		echo "Connexion a la base MySQL avec succes.<br/>";
	}else
		die( "Erreur de connexion au serveur MySql Local." );
	/////////////////////////////////////////////////////// 

	if( isset( $_GET['on'] ) )
		mysql_query("UPDATE variables SET value='1' WHERE name='run_crash'");
	else if( isset( $_GET['off'] )  )
		mysql_query("UPDATE variables SET value='0' WHERE name='run_crash'");
	
	if( isset( $_GET['avance'] ) ){
		mysql_query( "UPDATE variables SET value='".date("Y/m/d")."' WHERE name='date'" ); //"SELECT * FROM variables WHERE name = 'date'");
	}
	
	
	$data = mysql_fetch_array( mysql_query("SELECT * FROM variables WHERE name = 'date'"));
	echo "<br/>Date : ".$data['value']."<br/>";
	
	$data = mysql_fetch_array( mysql_query("SELECT * FROM variables WHERE name = 'run_crash'"));
	echo "Etat : ".$data['value']."<br/>";

	if( $data['value'] == '1' )  
		echo '<a href="?off=true">OFF</a><br/>';
	else
		echo '<a href="?on=true">ON</a><br/>';
		
	echo '<a href="?avance=on"><br/>Avancer date</a>';
?>