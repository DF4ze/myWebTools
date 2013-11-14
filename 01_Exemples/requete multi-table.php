<?php

 ///////////////////////////////////////////////////////
// Connexion My SQL
if( @mysql_connect( 'localhost', 'root', '') ){
	echo "Connexion au Serveur MySQL avec succes.<br/>";
	// Alors on lance la connexion à la base.
	mysql_select_db('myprojects') or die ("Connexion à la base MySql impossible");
	echo "Connexion a la base MySQL avec succes.<br/>";
}else
	die( "Erreur de connexion au serveur MySql Local." );
/////////////////////////////////////////////////////// 
	 $result = mysql_query( "SELECT * FROM mp_projects, mp_categories, mp_mastercategories 
								WHERE mp_categories.id_cat = mp_projects.id_cat
								AND mp_categories.id_mastercat = mp_mastercategories.id_mastercat
								");
while( $data = mysql_fetch_array( $result ) )
	echo 'proj : '.$data["proj"].' | cat : '.$data["cat"].' | mastercat : '.$data["mastercat"].'<br/>'; 
	
//!\\ n'affichera que les items qui ont une correspondance dans les 3 tables. S'il manque une des correspondance... l'item n'apparaitra pas.
?>