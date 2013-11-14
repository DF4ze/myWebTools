<?php session_start(); $debug = true; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title><?php echo "Comment Eraser" ?></title>
	<?php
		/* if( isset( $_SESSION['logued'] ) ){
			if( !$_SESSION['logued']  )
				echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL=index.php">';
		}else
			echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL=index.php">';
		 */		
	?>
	<link rel="stylesheet" media="screen" type="text/css" title="Design" href="design.css" />

	  
</head>
<body>	

<form method="POST" action="index.php">
<input type="text" name="chemin" value="<?php if( isset( $_POST['chemin'] ) )echo $_POST['chemin']; else echo "."; ?>"/>Indiquez un chemin<br/>
<input type="submit"/>
</form>


<?php
/* MAIN */
if( isset( $_POST['chemin'] ) ){
	$debug = true;
	
	// On reset le listing de fichiers
	$_SESSION['Listing_Fichiers'] = array();
	$i_class=0;
	
	// On scan le dossier pour retrouver les fichiers.
	thescandir( $_POST['chemin'], 0 );
	
	/////////////////////////////
	// On fait le tour des fichiers
	foreach( $_SESSION['Listing_Fichiers'] as $key => $fichier_listed  ){
		// On ouvre le fichier q si ce n'est pas le fichier en cours.
		if( $fichier_listed != basename ( __FILE__ ) ){
			if (!$fp = fopen($fichier_listed,"r")) {
				// Si erreur d'ouverture du fichier
				echo "Echec de l'ouverture du fichier $fichier_listed";
				exit;
			}else{
				// s'il s'ouvre ... on lit =)
				if( $debug )
					echo "<br/>Nom du fichier : $fichier_listed<br/>";
				
				// On met le curseur au début. (bug sur certain fichier .... ne commence pas au début....
				/// Mais cette option ne résout pas le probleme..........
				fseek($fp, 0);
				
				$Fichier = '';
				
				
				$lines = file($fichier_listed);
				/*On parcourt le tableau $lines et on affiche le contenu de chaque ligne précédée de son numéro*/
				foreach ($lines as $lineNumber => $lineContent)
				{
					echo $lineNumber,' ',$lineContent.'<br/>';
				}
				
				
				
				
				
				
				
				
/* 				while(!feof($fp)) {
					// On récupère une ligne
					$Ligne = fgets($fp, 4096 );

					// On affiche la ligne
					if( $debug )
						echo "Avant Modif : ".$Ligne.'<br/>';

					$double_slash = strpos( $Ligne, '//' );
					if( $double_slash !== false ){
						$Ligne = substr( $Ligne, 0, $double_slash );
						if($debug){
							echo "Apres Modif : ".$Ligne.'<br/>';
						}
					}
					
					// On stocke l'ensemble des lignes dans une variable
					$Fichier .= $Ligne;
				} */
				fclose($fp); // On ferme le fichier
				
/* 				if (preg_match_all('//[a-zA-Z0-9_]*(.*?)//php2uml#', $Fichier, $out)) {
					foreach( $out[0] as $key => $value  ){
						//echo "Key : $key = ".$value."<br/>";
						$ClassTab[$i_class]['class_complete'] = $value;
						$i_class++;
					} 
					
				} else {
					echo "Pas de classe dans ce fichier : $value<br/>N'auriez-vous pas oublié les tag de fin de classe?<br/>Il faut que //php2uml apparaissent à chaque fin de classe.";
				}
 */				
				// if( $value == "class_client.php" ){
					// echo '<br/>'.$Fichier.'<br/>' ;
				// }
				
				// On va d'abord récupérer toute les classes ... qu'on va se mettre dans un tableau.
				// Un fois qu'on aura toute les classes, on les dépouillera pour récupérer le nom, l'héritage, les propriétés et les méthodes.
				//if (preg_match_all('#class ([a-zA-Z_]*)( extends ([a-zA-Z_]*))*.*{.*}#', $Fichier, $out)) {
				
				// On récupère les classes complete :
				/* if (preg_match_all('#class [a-zA-Z0-9_]*(.*?)//php2uml#', $Fichier, $out)) {
					foreach( $out[0] as $key => $value  ){
						//echo "Key : $key = ".$value."<br/>";
						$ClassTab[$i_class]['class_complete'] = $value;
						$i_class++;
					} 
					
				} else {
					echo "Pas de classe dans ce fichier : $value<br/>N'auriez-vous pas oublié les tag de fin de classe?<br/>Il faut que //php2uml apparaissent à chaque fin de classe.";
				} */
			}
		}
	}
}
/* FIN MAIN	 */
	

function thescandir($dir, $level){
	$is_dir = true;
	
	if( !($files = @scandir($dir)) ){ // s'il y a une erreur au scandir c'est que c'est un fichier.
		$basename = pathinfo($dir,PATHINFO_BASENAME);
		$is_dir = false;
		
		// si c'est bien un fichier PHP
		if( substr(strtolower(strrchr(basename($basename), ".")), 1) == 'php' ){
			// On ajoute le fichier au listing
			$offset = count( $_SESSION['Listing_Fichiers'] );
			$_SESSION['Listing_Fichiers'][$offset] = $basename;
		}
	}else{
/* 		if($dir != "." and $dir != ".." ){
		}
 */		$is_dir = true;
		$level ++;
	}
	
	if($dir != "." and $dir != ".." ){
		$extension = pathinfo($dir,PATHINFO_EXTENSION);
		$basename = pathinfo($dir,PATHINFO_BASENAME);
		
	}
	
	// recursivité
	$i=0;
	while( @$files[$i] ){
		if($files[$i] != "." and $files[$i] != ".."){				
			$newdir = $dir."/".$files[$i];//concatène les noms de dossiers
				
			if($dir == ".")
				$newdir = $files[$i];
				
			thescandir($newdir, $level); 
		}
		$i++;
	}
}

?>