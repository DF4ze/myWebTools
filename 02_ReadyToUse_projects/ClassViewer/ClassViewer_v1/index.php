<?php
	session_start();
	//////////////////////////////////////////////////////////////////////////////////////////////////
	//																								//  
	// 		appli qui a pour but de creer un arbre avec la structure des ClaSs PHP					// 
	//																								//  
	//////////////////////////////////////////////////////////////////////////////////////////////////    

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Class Viewer v0.1</title>
</head>
<body>	
<form method="POST" action="index.php">
<input type="text" name="chemin" value="<?php if( isset( $_POST['chemin'] ) )echo $_POST['chemin']; else echo "."; ?>"/>Indiquez un chemin<br/>
<input type="submit"/>
</form>

<?php



if( isset( $_POST['chemin'] ) ){
	// On reset le listing de fichiers
	$_SESSION['Listing_Fichiers'] = array();
	$ClassTab = array(); //  $ClassTab[$key][$key2][$key3]
						//	$key = 0, 1, 2 --> Num�ro de la classe
						//	$key2 = class_complete, nom, extends, proprietes, fonctions
						//	$key3 = Si 'proprietes' ou 'fonctions' -> 0, 1, 2 donne les diff�rents items.
	$i_class=0;
	
	// On scan le dossier pour retrouver les fichiers.
	thescandir( $_POST['chemin'], 0 );
	
	////////////////////////////////////
	// On fait le tour des fichiers
	///////////////////////////////////////
	foreach( $_SESSION['Listing_Fichiers'] as $key => $value  ){
		// On ouvre le fichier q si ce n'est pas le fichier en cours.
		if( $value != basename ( __FILE__ ) ){
			if (!$fp = fopen($value,"r")) {
				// Si erreur d'ouverture du fichier
				echo "Echec de l'ouverture du fichier";
				exit;
			}else{
				// s'il s'ouvre ... on lit =)
				//echo "<br/>Nom du fichier : $value<br/>";
				
				// On met le curseur au d�but.
				fseek($fp, 0);
				
				$Fichier = '';
				while(!feof($fp)) {
					// On r�cup�re une ligne
					$Ligne = fgets($fp,255);

					// On affiche la ligne
					//echo $Ligne;

					// On stocke l'ensemble des lignes dans une variable
					$Fichier .= $Ligne;
				}
				fclose($fp); // On ferme le fichier
				
				// if( $value == "class_client.php" ){
					// echo '<br/>'.$Fichier.'<br/>' ;
				// }
				
				// On va d'abord r�cup�rer toute les classes ... qu'on va se mettre dans un tableau.
				// Un fois qu'on aura toute les classes, on les d�pouillera pour r�cup�rer le nom, l'h�ritage, les propri�t�s et les m�thodes.
				//if (preg_match_all('#class ([a-zA-Z_]*)( extends ([a-zA-Z_]*))*.*{.*}#', $Fichier, $out)) {
				
				// On r�cup�re les classes complete :
				if (preg_match_all('#class [a-zA-Z0-9_]*(.*?)//php2uml#', $Fichier, $out)) {
					foreach( $out[0] as $key => $value  ){
						//echo "Key : $key = ".$value."<br/>";
						$ClassTab[$i_class]['class_complete'] = $value;
						$i_class++;
					} 
					
				} else {
					echo "Pas de classe dans ce fichier : $value<br/>N'auriez-vous pas oubli� les tag de fin de classe?<br/>Il faut que //php2uml apparaissent � chaque fin de classe.";
				}
			}
		}
	} 
	
	// On a fait le tour des fichiers et on a r�cup�r� leur classes.
	///////////////////////////////////////////////////////////////
	// Maintenant : On fait le tour des classe pour d�pouillage :)
	///////////////////////////////////////////////////////////////
	foreach( $ClassTab as $key => $une_class  ){
	
		// Le nom et l'h�ritage :
		if( preg_match('#class ([a-zA-Z0-9_]*)( extends ([a-zA-Z0-9_]*))*#', $une_class['class_complete'], $out)){
			//echo "<br/><strong>Classe: </strong>".$out[1];
			$ClassTab[$key]['nom'] = $out[1];
			
			if( isset( $out[3] ) ){
				//echo " <strong>Extends : </strong>".$out[3];
				$ClassTab[$key]['extends'] = $out[3];
			}
			//echo '<br/>';
		}
		
		// Les propri�t�s
		if( preg_match_all('#(public|protected|private) ([$_a-zA-Z0-9]*);#', $une_class['class_complete'], $out_prop)){
			//echo "Propri�t�s : <br/>";
			foreach( $out_prop[0] as $key_prop => $value_prop  ){
			//	echo "- key $key_prop : value ".$out_prop[1][$key_prop].' '.$out_prop[2][$key_prop]."<br/>";
				$ClassTab[$key]['proprietes'][$key_prop] = $out_prop[1][$key_prop].' '.$out_prop[2][$key_prop];
			} 
		}
		
		// Les fonctions
		if( preg_match_all('#(public|protected|private) function ([$_a-zA-Z0-9]*\(.*?\))#', $une_class['class_complete'], $out_func)){
			//echo "Fonctions : <br/>";
			foreach( $out_func[0] as $key_func => $value_func  ){
			//	echo "- key $key_func : value ".$out_func[1][$key_func].' '.$out_func[2][$key_func]."<br/>";
				$ClassTab[$key]['fonctions'][$key_func] = $out_func[1][$key_func].' '.$out_func[2][$key_func];
			} 
		}		
	} 
	
	
	//////////////////////////////////////////////
	// Et on affiche
	/////////////////////////////////////////////
	// Affiche tous pr test
	foreach( $ClassTab as $key => $value  ){
		// echo "<strong><br/>Key : $key | Value : $value</strong><br/>";
		
		foreach( $ClassTab[$key] as $key2 => $value2  ){
			// echo "Key$key : $key2 | Value2 : $value2<br/>";
			
			if( $key2 == "proprietes" or $key2 == "fonctions" )
				foreach( $ClassTab[$key][$key2] as $key3 => $value3  ){
					// echo "Key$key : $key3 | Value2 : $value3<br/>";
				} 
		} 
	} 
	
	// On recherche la/les classe de base qui n'a pas d'h�ritage.
	$class_base = array();
	$i=0;
	foreach( $ClassTab as $key => $UneClasse  ){
		if( !isset($UneClasse['extends']) ){
			$class_base[$i] = $UneClasse['nom'];
			$i++;
			//echo "Classe Base : ".$UneClasse['nom'].'<br/>';
		}
	} 
	
	// et on lance la r�curcivit�
	foreach( $class_base as $key => $value  ){
		recure( $value, $ClassTab );
	} 
	
	
}		

function thescandir($dir, $level){
	$is_dir = true;
	
	if( !($files = @scandir($dir)) ) // s'il y a une erreur au scandir c'est que c'est un fichier.
	{
		$basename = pathinfo($dir,PATHINFO_BASENAME);
		$is_dir = false;
		
		// si c'est bien un fichier PHP
		if( substr(strtolower(strrchr(basename($basename), ".")), 1) == 'php' ){
			// On ajoute le fichier au listing
			$offset = count( $_SESSION['Listing_Fichiers'] );
			$_SESSION['Listing_Fichiers'][$offset] = $basename;
		}
	}
	else
	{
		if($dir != "." and $dir != ".." )
		{

		}
		$is_dir = true;
		$level ++;
	}
	
	if($dir != "." and $dir != ".." )
	{
		$extension = pathinfo($dir,PATHINFO_EXTENSION);
		$basename = pathinfo($dir,PATHINFO_BASENAME);
		
	}
	
	// recursivit�
	$i=0;
	while( @$files[$i] )
	{
		if($files[$i] != "." and $files[$i] != "..")
		{				
			$newdir = $dir."/".$files[$i];//concat�ne les noms de dossiers
				
			if($dir == ".")
				$newdir = $files[$i];
				
			thescandir($newdir, $level); 
		}
		
		$i++;
	}

	
}

function recure( $nom, $tab ){
	// on affiche le nom
	echo "<ul>";
	echo "<li>";
	echo '<div class="classe">';
	
	echo '<div class="nom">';
	echo "<h2>$nom </h2>";
	echo '</div class="nom">';
	//echo "<ul>";
	
	// On r�cup�re les proprietes que l'on affiche.
	$offset = find_nom( $nom, $tab );
	if( isset( $tab[$offset]['proprietes'] ) ){
		echo '<div class="proprietes">';
		echo "<strong>Propri�t�s : </strong>";
		echo "<ul>";
		foreach( $tab[$offset]['proprietes'] as $key => $value  ){
			echo "<li>$value</li>";
		} 
		echo "</ul>";	
		echo '</div class="proprietes">';
	}
	
	// On r�cup�re les fonctions que l'on affiche.
	if( isset( $tab[$offset]['fonctions'] ) ){
		echo '<div class="fonctions">';
		echo "<strong>Fonctions : </strong>";
		echo "<ul>";
		foreach( $tab[$offset]['fonctions'] as $key => $value  ){
			echo "<li>$value</li>";
		} 
		echo "</ul>";	
		echo '</div class="fonctions">';
	}
	
	echo '</div class="classe">';
	echo "</li>";
	
	////////////////////////// HERITAGE ///////////////////////
	// On r�cup les classes qui ont celle-ci en extends
	$tab_extends = find_extends( $nom, $tab );
	// S'il y a des h�ritages : on r�cur!
	if( $tab_extends != -1 ){
		// echo "<li><strong>H�ritage : </strong></li>";
		//echo "<ul>";
		foreach( $tab_extends as $key => $value  ){
			recure( $value, $tab );
		} 
		//echo "</ul>";			
	}
	////////////////////////// FIN HERITAGE ///////////////////////
	
	//echo "</ul>";	
	echo "</ul>";	
}

function find_nom( $nom, $tab ){
	foreach( $tab as $key => $value  ){
		if( $value['nom'] == $nom )
			return $key;
	} 
	return -1;
}
function find_extends( $extends, $tab ){
	$tab_extends = array();
	$count = 0;
	foreach( $tab as $key => $value  ){
		if( $value['extends'] == $extends ){
			$tab_extends[ $count ] = $value['nom'];
			$count++;
		}
	} 
	
	if( $count )
		return $tab_extends;
	else
		return -1;
}

?>
<STYLE type="text/css">
.classe
{
	width : 400px;
	margin : 10px;
	box-shadow: 6px 6px 6px grey;
	border-radius: 10px;
	
}
.nom
{
	padding-left : 15px;
	border : 1px black outset;	
	border-radius: 10px 10px 0px 0px;
	background-color: orange;
}
.proprietes
{
	padding-left : 5px;
	border : 1px black outset;	
}
.fonctions
{
	padding-left : 5px;
	border : 1px black outset;	
	border-radius: 0px 0px 10px 10px;
}


</STYLE>

</body>








