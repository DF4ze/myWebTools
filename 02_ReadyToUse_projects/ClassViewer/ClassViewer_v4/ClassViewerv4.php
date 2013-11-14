<?php
	session_start();
	$debug = false;
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
	<title>Class Viewer v4</title>
</head>
<body>	
<form method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>">
<input type="text" name="chemin" value="<?php if( isset( $_POST['chemin'] ) )echo $_POST['chemin']; else echo "."; ?>"/>Indiquez un chemin<br/>
<input type="submit"/>
</form>

<?php


// SI un chemin a été communiqué :
// On lance le scan des fichiers et on lance la recherche des classes

if( isset( $_POST['chemin'] ) ){
	// On reset le listing de fichiers
	$_SESSION['Listing_Fichiers'] = array();
	$ClassTab = array(); //  $ClassTab[$key][$key2][$key3]
						//	$key = 0, 1, 2 --> Numéro de la classe
						//	$key2 = class_complete, nom, extends, proprietes, fonctions
						//	$key3 = Si 'proprietes' ou 'fonctions' -> 0, 1, 2 donne les différents items.
	$i_class=0;
	
	// On scan le dossier pour retrouver les fichiers.
	thescandir( $_POST['chemin'], 0 );
	
	////////////////////////////////////
	// On fait le tour des fichiers
	///////////////////////////////////////
	$nb_file_without_class = 0;
	foreach( $_SESSION['Listing_Fichiers'] as $key => $fichier_listed  ){
		// On ouvre le fichier q si ce n'est pas le fichier en cours.
		if( $fichier_listed != basename ( __FILE__ ) ){
			if (!$fp = fopen($fichier_listed,"r")) {
				// Si erreur d'ouverture du fichier
				echo "Echec de l'ouverture du fichier $fichier_listed";
				exit;
			}else{
				// s'il s'ouvre ... on lit =)
				//echo "<br/>Nom du fichier : $fichier_listed<br/>";
				
				// On met le curseur au début. (bug sur certain fichier .... ne commence pas au début....
				/// Mais cette option ne résout pas le probleme..........
				fseek($fp, 0);
				
				$Fichier = '';
				while(!feof($fp)) {
					// On récupère une ligne
					$Ligne = fgets($fp,255);

					// On affiche la ligne
					if( $debug )
						echo $Ligne;

					// On stocke l'ensemble des lignes dans une variable
					$Fichier .= $Ligne;
				}
				fclose($fp); // On ferme le fichier
				
				
				// On va d'abord récupérer toute les classes ... qu'on va se mettre dans un tableau.
				// Un fois qu'on aura toute les classes, on les dépouillera pour récupérer le nom, l'héritage, les propriétés et les méthodes.
				//if (preg_match_all('#class ([a-zA-Z_]*)( extends ([a-zA-Z_]*))*.*{.*}#', $Fichier, $out)) {
				
				// On récupère les classes complete :
				if (preg_match_all('#class [a-zA-Z0-9_]*(.*?)//php2uml#s', $Fichier, $out)) {
					foreach( $out[0] as $key => $value  ){
						//echo "Key : $key = ".$value."<br/>";
						$ClassTab[$i_class]['class_complete'] = $value;
						$i_class++;
					} 
					
				} else {
					echo "Pas de classe dans ce fichier : $fichier_listed<br/>";
					$nb_file_without_class ++;
					//echo "<br/>Fichier : <br/> $Fichier";
				}
			}
		}
	} 
	if( $nb_file_without_class != 0 )
		echo "N'auriez-vous pas oublié les Tags de fin de classe?<br/>Il faut que //php2uml apparaissent à chaque fin de classe.<br/>";
		
	///////////////////////////////////////////////////////////////
	// On fait le tour des classe pour dépouillage :)
	///////////////////////////////////////////////////////////////
	foreach( $ClassTab as $key => $une_class  ){
	
		// Le nom et l'héritage :
		if( preg_match('#class ([a-zA-Z0-9_]*)( extends ([a-zA-Z0-9_]*))*#', $une_class['class_complete'], $out)){
			$ClassTab[$key]['nom'] = $out[1];
			
			if( isset( $out[3] ) ){
				$ClassTab[$key]['extends'] = $out[3];
			}
		}
		
		// Les propriétés
		if( preg_match_all('#(public|protected|private) ([$_a-zA-Z0-9]*);#', $une_class['class_complete'], $out_prop)){
			foreach( $out_prop[0] as $key_prop => $value_prop  ){
				$ClassTab[$key]['proprietes'][$key_prop]['prop_totale'] = $out_prop[1][$key_prop].' '.$out_prop[2][$key_prop];
				$ClassTab[$key]['proprietes'][$key_prop]['prop_nom'] = $out_prop[2][$key_prop];
				$ClassTab[$key]['proprietes'][$key_prop]['prop_visi'] = $out_prop[1][$key_prop];
			} 
		}
		
		// Les fonctions
		if( preg_match_all('#(public|protected|private) function (([$_a-zA-Z0-9]*)\(.*?\))#', $une_class['class_complete'], $out_func)){
			foreach( $out_func[0] as $key_func => $value_func  ){
				$ClassTab[$key]['fonctions'][$key_func]['fonct_totale'] = $out_func[1][$key_func].' '.$out_func[2][$key_func];
				$ClassTab[$key]['fonctions'][$key_func]['fonct_nom'] = $out_func[3][$key_func];
				$ClassTab[$key]['fonctions'][$key_func]['fonct_visi'] = $out_func[1][$key_func];
			} 
		}		
	} 
	
	
	////////////////////////////////////////////
	// Zone de résumé des classes 'Feuilles'
	////////////////////////////////////////////
	//echo '<div style="width:100%">';
	// On récupère les feuilles
	$leaves = find_leaves($ClassTab);
	
	foreach( $leaves as $key => $nom_feuille  ){
		// on récupère l'ID
		$id = find_nom( $nom_feuille, $ClassTab );
		// l'héritage publique
		$publics = get_public_heritage( $ClassTab[$id], $ClassTab );
		/////////////
		// on affiche
		/////////////
		//echo '<div class="classeherit">';
		echo '<div class="classe" style="margin:5px; float:left;">';
			echo '<div class="nom">';
				echo 'Résumé des "Publics" pour la classe :<h2>'.$publics['nom'].'</h2>';
			echo '</div>';
			
			
			echo '<div class="proprietes">';
			echo '<strong>Propriétés : </strong>';
				// Pour toute les classes héritées
				if( isset( $publics['proprietes'] ) ){
				echo '<ul>';
				foreach( $publics['proprietes'] as $class_herit => $tab_prop_publics ){
					echo '<li><strong>'.$class_herit.'</strong></li>';
					// on fait le tour des propriétés
					echo '<ul>';
					foreach( $tab_prop_publics as $key => $nom_prop  ){
						echo '<li>'.$nom_prop.'</li>';
					} 
					echo '</ul>';
				} 
				echo '</ul>';
				}else
					echo "Il n'y a pas de propriétés PUBLIC.";
			echo '</div>';
			
			
			echo '<div class="fonctions">';
			echo '<strong>Fonctions : </strong>';
				// Pour toute les classes héritées
				if( isset( $publics['fonctions'] ) ){
				echo '<ul>';
				foreach( $publics['fonctions'] as $class_herit => $tab_func_publics ){
					echo '<li><strong>'.$class_herit.'</strong></li>';
					// on fait le tour des fonctions
					echo '<ul>';
					foreach( $tab_func_publics as $key => $nom_func  ){
						echo '<li>'.$nom_func.'</li>';
					} 
					echo '</ul>';
				} 
				echo '</ul>';
				}else
					echo "Il n'y a pas de fonctions PUBLIC.";
			echo '</div>';
		echo '</div class="classe">';
		//echo '</div>';
	} 
	//echo "</div>";

	//////////////////////////////////////////////
	// Et on affiche l'arborescence
	/////////////////////////////////////////////
	
	// On recherche la/les classe/s de base qui n'a/ont pas d'héritage.
	$class_base = array();
	$i=0;
	foreach( $ClassTab as $key => $UneClasse  ){
		if( !isset($UneClasse['extends']) ){
			$class_base[$i] = $UneClasse['nom'];
			$i++;
		}
	} 
	
	echo '<div style="width:100%">';
	// et on lance la récurcivité
	$func_herit = array();
	foreach( $class_base as $key => $value  ){
		recure( $value, $ClassTab, $func_herit );
	} 
	
	echo "</div>";
	
	


}		














// Va scanner tous les fichiers et met leur nom/chemin dans un tableau. ($_SESSION['Listing_Fichiers'][$offset] = $basename;)
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
			$_SESSION['Listing_Fichiers'][$offset] = $dir;//$basename;
			//echo "base : ".$basename." | dir : ".$dir."<br/>";
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
	
	// recursivité
	$i=0;
	while( @$files[$i] )
	{
		if($files[$i] != "." and $files[$i] != "..")
		{				
			$newdir = $dir."/".$files[$i];//concatène les noms de dossiers
				
			if($dir == ".")
				$newdir = $files[$i];
				
			thescandir($newdir, $level); 
		}
		
		$i++;
	}

	
}

// Va scanner le tableau CLASSTAB a la recherche des propriétés, etc... et affiche le tout.
function recure( $nom, $tab, $func_herit ){

	echo '<div class="classeherit">';


	////////////////////////////////////////
	//////		DIV CLASSE
	////////////////////////////////////////
	echo '<div class="classe">';	
	echo '<div class="nom" style="text-align : center">';
	// on affiche le nom
	echo '<h2>'.$nom.'</h2>';
	echo '</div class="nom">';
	//echo "<ul>";
	
	// On récupère les proprietes que l'on affiche.
	$offset = find_nom( $nom, $tab );
	if( isset( $tab[$offset]['proprietes'] ) ){
		echo '<div class="proprietes" >';
		echo '<strong>Propriétés : </strong>';
		echo '<ul>';
		foreach( $tab[$offset]['proprietes'] as $key => $value  ){
			if( $value['prop_visi'] == 'public' )
				echo '<li> <span style="color:red">/!\\ PUBLIC /!\\ </span>'.$value['prop_totale'].'</li>';
			else if( $value['prop_visi'] == 'protected' )
				echo '<li> <span style="color:orange">/!\\ PROTECTED /!\\ </span>'.$value['prop_totale'].'</li>';
			else 
				echo '<li>'.$value['prop_totale'].'</li>';
		} 
		echo "</ul>";	
		echo '</div class="proprietes">';
	}
	
	// On récupère les fonctions que l'on affiche.
	if( isset( $tab[$offset]['fonctions'] ) ){
		echo '<div class="fonctions">';
		echo '<strong>Fonctions : </strong>';
		echo '<ul>';
		foreach( $tab[$offset]['fonctions'] as $key => $value  ){
			// On vérif si la fonction ne serait pas deja définie?
			$id_redef = check_if_redef( $value['fonct_nom'], $func_herit );
			//echo '<h1>'.$id_redef.'</h1>';
			if( $id_redef !== false )
				echo '<li style="color:red">'.$value['fonct_totale'].'<br/><em>def dans : '.$func_herit[$id_redef]['classe'].'</em></li>';
			else{
				// si n'est pas redef, alors on donne un code couleur pour la visibilité de la fonction
				if( $value['fonct_visi'] == 'public' )
					echo '<li style="color:green">'.$value['fonct_totale'].'</li>';
				else if( $value['fonct_visi'] == 'protected' )
					echo '<li style="color:grey">'.$value['fonct_totale'].'</li>';
				else if( $value['fonct_visi'] == 'private' )
					echo '<li style="color:orange">'.$value['fonct_totale'].'</li>';
			}
			$count = count( $func_herit );
			$func_herit[$count]['fonction'] = $value['fonct_nom'];
			$func_herit[$count]['classe'] = $nom;
		} 
		echo '</ul>';	
		echo '</div class="fonctions">';
	}
	
	echo '</div class="classe">';
	////////////////////////////////////////
	//////		END DIV CLASSE
	////////////////////////////////////////

	
	
	
	
	
	
	
	
	
	
	echo '<div class="herit">';
	
	////////////////////////// HERITAGE ///////////////////////
	// On récup les classes qui ont celle-ci en extends
	$tab_extends = find_extends( $nom, $tab );
	// S'il y a des héritages : on récur!
	if( $tab_extends != -1 ){
		// echo "<li><strong>Héritage : </strong></li>";
		//echo "<ul>";
		foreach( $tab_extends as $key => $value  ){
			recure( $value, $tab, $func_herit );
		} 
		//echo "</ul>";			
	}
	////////////////////////// FIN HERITAGE ///////////////////////
	
	echo '</div class="herit">';
	echo '</div class="classeherit">';
	
	
	

}
function check_if_redef( $nom, $tab_functions ){
	$memo = false;
	if( isset( $tab_functions ) ){
		foreach( $tab_functions as $key => $value ){
			// echo $value['fonction'].' | '.$nom.'<br/>';
			if( $value['fonction'] == $nom )
				$memo = $key;
		}	
	}
	return $memo;
}
function find_nom( $nom, $tab ){
	foreach( $tab as $key => $value  ){
		if( $value['nom'] == $nom )
			return $key;
	} 
	return -1;
}
// retourne un tableau de nom de classe qui hérite de $extends demandé.
function find_extends( $extends, $tab ){
	$tab_extends = array();
	$count = 0;
	foreach( $tab as $key => $class  ){
		if( @$class['extends'] == $extends ){
			$tab_extends[ $count ] = $class['nom'];
			$count++;
		}
	} 
	
	if( $count )
		return $tab_extends;
	else
		return -1;
}
// retourne un tableau de classes qui ne sont hérités par personne = classe "feuille"
function find_leaves( $tab ){
	$tab_class = array();
		
	$count = 0;
	foreach( $tab as $key => $class ){
		if( find_extends( $class['nom'], $tab ) == -1 ){
			$tab_class[ $count ] = $class['nom'];
			$count++;
		}
	} 
	
	if( $count )
		return $tab_class;
	else
		return -1; // la ya un gros probleme! ==> toute les classes hérite d'une autre ... redondance cyclique?? ou alors on a pas tout les fichiers!... non meme pas ... si on a pas tout les fichier ... il y aura forcément une classe qui n'est héritée par personne... donc c'est bien une redondance cylcique.
}

// retourne la liste des méthodes et propriétés qui sont PUBLIC ... de puis le NOM de la class
function get_publics( $class_name, $tab ){
	$class=array();
	
	for( $i=0; $i < count( $tab ); $i++ ){
		if( $class_name == $tab[$i]['nom'] ){
			$class = $tab[$i];
			$i = count( $tab );
		}
	} 
	
	$publics=array();
	// Récupération des propriété publiques.
	if( isset( $class['proprietes'] ) ){
		$i=0;
		foreach( $class['proprietes'] as $key => $propriete  ){
			if( $propriete['prop_visi'] == 'public' )
				$publics['proprietes'][$class['nom']][$i++] = $propriete['prop_nom'];
		} 
	}
	// Récup des méthodes/functions publics;
	if( isset( $class['fonctions'] ) ){
		$i=0;
		foreach( $class['fonctions'] as $key => $propriete  ){
			if( $propriete['fonct_visi'] == 'public' )
				$publics['fonctions'][$class['nom']][$i++] = $propriete['fonct_nom'];
		} 
		return $publics;
	}
}


// retourne un tableau avec uniquement les méthodes et propriétés PUBLIC de tout l'héritage de la classe donnée.
function get_public_heritage( $class /* il s'agit d'une classe sous forme de tableau comme défini dans if( isset( $_POST['chemin'] ) )*/, $tab_class ){
	$tab_publics = array(); // tab qui sera retourné. de la forme :
							// tab	['nom']
							//		['fonctions'][class_herit][x]['nom']= nom 
							//		['proprietes'][class_herit][x]['nom']= nom 
	$temp_class = $class;		// rétention temporaire de la classe dont on hérite
	
	// Init
	$tab_publics['nom'] = $class['nom'];
	
	do{
		// On récupère les PUBLICS de la classe.
		$temp_publics = get_publics( $temp_class['nom'], $tab_class );
		// on les mets dans notre tab de retour.
		if( isset( $temp_publics['fonctions'] ) )
			$tab_publics['fonctions'][$temp_class['nom']] = $temp_publics['fonctions'][$temp_class['nom']];
		if( isset( $temp_publics['proprietes'] ) )		
			$tab_publics['proprietes'][$temp_class['nom']] = $temp_publics['proprietes'][$temp_class['nom']];
		// On récupère la classe de l'héritage ?
		$herit = false;
		if( isset( $temp_class['extends'] ) ){
			// deja le nom : 
			$name_herit = $temp_class['extends'];
			// son 'id'
			$id = find_nom( $name_herit, $tab_class );
			
			if( $id != -1 ){
				// la class
				$temp_class = $tab_class[$id];
				$herit = true;
			}else
				$herit = false;
		}
	}while( $herit );
	
	return $tab_publics;
}
?>
<STYLE type="text/css">
.classe
{
	width : 400px;
	box-shadow: 6px 6px 6px grey;
	border-radius: 10px;
	
	margin:auto;
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
.classeherit
{
	width : auto;
	/* border : 1px black outset; */	
	float : left;
	/* text-align: center; */
	
	margin : 10px;
	
}
.herit
{
	margin-top : 20px;
	width : auto;
	/* border : 1px black outset; */	
	float : left;

}

</STYLE>

</body>

</html>






