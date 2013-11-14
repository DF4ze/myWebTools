 <?php
function thescandir($dir, $level)
{
	$is_dir = true;
	
	if( !($files = @scandir($dir)) ) // s'il y a une erereur au scandir c'est que c'est un fichier.
	{
		$basename = pathinfo($dir,PATHINFO_BASENAME);
		/*echo '
			<ul class="fichier">
				<li><a href="'.$dir.'" title="téléchagez">'.$basename.'</a></li>
			</ul>
			';
			*/
		$is_dir = false;
	}
	else
	{
		if($dir != "." and $dir != ".." )
		{
			/*echo '
				<ul class="dossier">
					<li>'.$dir.'</li>';
			*/
		}
		$is_dir = true;
		$level ++;
	}
	
	$basename = pathinfo($dir,PATHINFO_BASENAME);
	if(!$is_dir and $basename != "Thumbs.db" )
	{
		$extension = pathinfo($dir,PATHINFO_EXTENSION);
			
		if(!mysql_num_rows( mysql_query("SELECT * FROM files WHERE nom='$basename'")))
		{
			////// Création du lien vers le fichier uploadé.								
			// On récupère l'adresse du serveur ainsi que le chemin+ le nom du fichier actuel
			$CheminComplet = "http://".$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];
			//on supprime le nom du fichier actuel et on le remplace par le nom du dossier dans lequel il y a les fichiers 
			$CheminComplet = str_replace("upload.php","files/",$CheminComplet);
			//On met le nom du fichier qu'on vient de trouver 
			$CheminComplet = $CheminComplet.$basename;
			
			$lien = '<a href="'.$CheminComplet.'" title="'.$basename.'">'.$CheminComplet.'</a>';
			mysql_query("INSERT INTO files VALUES('','FreeUpload', '$basename', '$CheminComplet', '$lien', NULL, NULL)");
			//echo "<br/>créé<br/>";
		}
		else
		{
			mysql_query("UPDATE files SET date=NULL WHERE chemin='$dir'");	
			//echo "<br/>MAJ<br/>";
		}
	}
	
	$i=0;
	while( @$files[$i] )
	{
		if($files[$i] != "." and $files[$i] != ".." and $level < 2 )
		{				
			$newdir = $dir."/".$files[$i];//concatène les noms de dossiers
				
			if($dir == ".")
				$newdir = $files[$i];
				
			thescandir($newdir, $level); 
		}
		
		$i++;
	}
	
	if($is_dir)
		echo '</ul>';
	
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Upload Fichier</title>
    <link rel="stylesheet" media="screen" type="text/css" title="Design" href="design.css" />
</head>

<body> 
	<div id="en_tete">
	</div>
	
	<form action="upload.php" method="post" > 
		<input type="hidden" name="MAJ" value="true" /> 
		<input type="submit" value="Mettre à jour" class="input"/>
	</form>	

	<div class="haut">
	</div>
	
	<div class="corps">
	<p>
		<fieldset>
		<legend>Envoyer un fichier sur le serveur</legend>
			<?php
				// Connexion a la base de données.
				mysql_connect("localhost", "root", "") or die ("Connexion au serveur impossible");
				mysql_select_db("upload") or die ("Connexion a la base impossible");

				//Si on appuy sur le bouton maj =>mise a jour de la base de données avec les files fichier réellement présent sur le serveur.... dans le dossier /files
				if(isset($_POST['MAJ']))
				{
					mysql_query("TRUNCATE TABLE files");
					thescandir("./files", "0");
					echo "Mise à jour avec succès.";
				}
					
					
	
				// S'il n'y a pas de fichier envoyé, on initialise la variable avec un nom de fichier bidon.
				if( !isset($_FILES['monfichier']) )
					$_FILES['monfichier']="http://dtc.com/bienaufond.dtc";
					
				$nomOrigine = $_FILES['monfichier']['name'];
				//$elementsChemin = pathinfo($nomOrigine);
				$extensionFichier = pathinfo($nomOrigine,PATHINFO_EXTENSION);
				//$extensionFichier = $elementsChemin['EXTENSION'];
				$extensionsNonAutorisees = array("php", "php2", "php3", "aspx", "asp", "html", "htm", "xml", "css", "js", "ini", "ink", "exe", "bat", "msi", "cab");
				$cheminupload = "http://".$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];
				$cheminupload = str_replace("upload.php","files/",$cheminupload);
			
			/*	//Pour info
				echo "Nom Origine : ".$nomOrigine."<br/>";
				echo "Nom Destination : ".str_replace(" ", "_" , $nomOrigine)."<br/>";
				echo "ExtensionFichier : ".$extensionFichier."<br/>";
				echo "Server_Name : ".$_SERVER['SERVER_NAME']."<br/>";
				echo "PHP_SELF : ".$_SERVER['PHP_SELF']."<br/>";
				echo "DOCUMENT_ROOT : ".$_SERVER['DOCUMENT_ROOT']."<br/>";
				echo "__FILE__ : ".__FILE__."<br/><br/>";
				echo "Donc le fichier en cours devrait se trouver ici: http://".$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF']."<br/>";	
				echo "et le fichier uploadé devrait se trouver ici: ".$cheminupload."<br/><br/>";
			*/	
				
				//s'il y a un fichier d'envoyé, on traite.
				if($nomOrigine != "" and $_FILES['monfichier']!= "http://dtc.com/bienaufond.dtc")
				{
					// Met l'extension en minuscule de facon a avoir une meilleure comparaison.
					$extensionFichier = strtolower($extensionFichier);
					
					if ((in_array($extensionFichier, $extensionsNonAutorisees)))
					{
						echo "<h3>!! Ce type de fichier ne peut pas être envoyé !!</h3>";
					}
					else 
					{    
						//traitement du nom de fichier de facon à ce qu'il n'ait pas d'espace puis on ajoute le nom de l'utilisateur.
						$nomDestination = str_replace(" ", "_" , $nomOrigine);
						//$nomDestination = $_SESSION['user']."_".$nomDestination;
						
						// Copie dans le repertoire du script avec un nom
						// incluant l'heure a la seconde pres 
						//$nomDestination = "fichier_du_".date("YmdHis").".".$extensionFichier;
						$repertoireDestination = dirname(__FILE__).'/files/';
						
						// Connexion a la base de données.
						//mysql_connect($_SESSION['serveurName'], $_SESSION['serveurUserName'], $_SESSION['serveurUserPwd']) or die ("Connexion au serveur impossible");
						//mysql_select_db($_SESSION['serveurSqlBase']) or die ("Connexion a la base impossible");
							
						//On regarde si le fichier n'aurait pas deja était uploadé
						if(!@mysql_num_rows( mysql_query("SELECT * FROM files WHERE nom='$nomDestination'")))
						{
							//Sinon on l'upload.
							//echo "chemin du fichier temporaire : ".$_FILES["monfichier"]["tmp_name"]."<br/>";
							if (move_uploaded_file($_FILES["monfichier"]["tmp_name"], $repertoireDestination.$nomDestination)) 
							{
								// Création du lien vers le fichier uploadé.
								
								$lien = '<a href="'.$cheminupload.$nomDestination.'" onclick="pmv_click(phpmyvisitesURL, phpmyvisitesSite, \''.$cheminupload.$nomDestination.'\', \''.$nomDestination.'\', \'FILE\')">'.$cheminupload.$nomDestination.'</a>';
								//'<a href="'.$cheminupload.$nomDestination.'" title="'.$nomDestination.'">'.$cheminupload.$nomDestination.'</a>';

								echo "<h3>!! Votre fichier a bien été envoyé !!</h3> Il s'appelle : ".$nomDestination."<br/>";
								echo "Il se trouve à l'adresse suivante : <br/>";
								echo $lien."<br/>";
								
								//creation d'une entrée BD pour le nouveau fichier.
								$user = "FreeUpload";
								if( !@mysql_query("INSERT INTO files VALUES('','$user', '$nomDestination', '$cheminupload', '$lien', NULL, NULL)"))
								{
									echo '<br/><br/>Une erreur s\'est tout de même présentée,<br/>le fichier est sur le serveur, mais il n\'apparaitra pas dans la liste.<br/>Merci de prévenir le <a href="mailto:df4ze@free.fr?subject=erreur d\'enregistrement dans la base du fichier '.$nomDestination.'&body=Bonjour">WebMaster</a>.<br/><br/>';
								}
								else
								{
									//pour test
									//echo "BD OK! <br/><br/>";
									//echo $repertoireDestination."<br/><br/>";
								}	
								
								
								echo "<br/>";
							}
							else
							{
								//Pour test
								//echo $repertoireDestination.$nomDestination."<br/>";
								echo "Le fichier n'a pas été uploadé (doit être < 1Go) ou ".
										"Le déplacement du fichier temporaire a échoué".
										" vérifiez l'existence du répertoire ".$repertoireDestination.
										'<br/>Merci de Merci de prévenir le <a href="mailto:df4ze@free.fr?subject=erreur d\'enregistrement dans la base du fichier '.$nomDestination.'&body=Bonjour">WebMaster</a>.'.
										"<br/><br/>";
							}
						}
						else
						{
							echo "<h3>!! Vous avez déjà envoyé ce fichier !!</h3>";
							
							$requete = mysql_query("SELECT * FROM files WHERE nom='$nomDestination'");
							$donnees = mysql_fetch_array($requete);

							echo "Il s'appelle : ".$donnees['nom']."<br/>";
							echo "Il se trouve à l'adresse suivante : <br/>";
							echo $donnees['lien']."<br/><br/>";	
						}
						
						// On se déconnecte de MySQL
						mysql_close();
						
					}
				}
			?>

			
			<form enctype="multipart/form-data" action="upload.php" method="post" >
				<!--input type="hidden" name="MAX_FILE_SIZE" value="100000000000" /--> <!-- limité à 10 mo-->
				Sélectionnez le fichier à envoyer |<input type="file" name="monfichier" />
				<input type="submit" value="Envoyer"/>
			</form>
		</fieldset>
	</p>
	</div> <!-- Fin de CORPS -->
	<div class="bas">
	</div><!-- Fin de BAS -->

	
	<div class="haut">
	</div><!-- Fin de HAUT -->
	<div class="corps">
		<p>
			<fieldset>
			<legend>Les fichiers envoyés</legend>
			<?php
				//$user = $_SESSION['user'];
				// Connexion a la base de données.
				mysql_connect("localhost", "root", "") or die ("Connexion au serveur impossible");
				mysql_select_db("upload") or die ("Connexion a la base impossible");
									
				// Si pas de fichier deja uploadé
				if(!@mysql_num_rows( mysql_query("SELECT * FROM files")))
				{
					echo "Il n'y a pas de fichier enregistré.";
				}
				else
				{
					
					// On fait la requete pour récuperer les nom defichiers
					$requete = mysql_query("SELECT * FROM files ORDER BY ID DESC LIMIT 0,1000");
					
					
					while ($donnees = mysql_fetch_array($requete) )
					{
						// Début d'affichage des données
						echo '<div class="ombre">';
						echo '<div class="fichiers">';
						
						//Si le fichier est une image alors on affiche une miniature
						$extensionFichier = pathinfo($donnees['nom'],PATHINFO_EXTENSION);
						$extensionFichier = strtolower($extensionFichier); // on met en minuscule de facon a avoir une meilleure comparaison
						$extensionsImages = array("jpg", "jpeg", "gif", "bmp", "jpe", "png");
						$extensionsVideos = array("wmv", "avi", "mpg", "mpeg", "wm", "asx", "mp3", "mp4", "wm", "wma");
						$extensionsArchives = array("zip", "rar", "nrg", "ace", "bin", "cue");

						if(in_array($extensionFichier, $extensionsImages))
						{
							//echo ".";// pour l'affichage
							//permet de retrouver les informations sur l'image
							//echo $donnees['chemin'].'</br>';
							$infos_image = @getImageSize($donnees['chemin']); // info sur la dimension de l'image
							// '@' est placé devant la fonction getImageSize()pour empecher l'affichage
							// des erreurs si l'image est absente.
							
							//dimension 
							$largeur = $infos_image[0]; // largeur de l'image
							$hauteur = $infos_image[1]; // hauteur de l'image
							$type    = $infos_image[2]; // Type de l'image 1 = GIF, 2 = JPG,3 = PNG, 4 = SWF, 5 = PSD,
														// 6 = BMP, 7 = TIFF, 8 = TIFF, 9 = JPC, 10 = JP2, 11 = JPX,
														// 12 = JB2, 13 = SWC, 14 = IFF....
							$html    = $infos_image[3]; // info html de type width="468" height="60"
							
							$l_max = 60; //largeur max
							$h_max = 60; //hauteur max
							if($largeur > $l_max)
							{
								$hauteur = ($hauteur*$l_max)/$largeur;
								$largeur = $l_max;
							}
							if($hauteur >$h_max)
							{
								$largeur = ($h_max*$largeur)/$hauteur;
								$hauteur = $h_max;
							}
							//echo 'hauteur : '.$hauteur.'<br/> Largeur : '.$largeur.'<br/>';
							echo '<a href="'.$donnees['chemin'].'" title="Image" alt="Erreur Sur Image "><img src="'.$donnees['chemin'].'" border="0" style="width:'.$largeur.'px;height:'.$hauteur.'px;float:left;margin-right:10px;margin-top:10px;"></a><br/>';
							
						} 
						else if(in_array($extensionFichier, $extensionsVideos))
						{
							echo '<a href="uploadedfiles/'.$donnees['nom'].'" title="Image" alt="Erreur Sur Image "><img src="images/video.gif" border="0" style="float:left;margin-right:10px;margin-top:10px;"></a><br/>';
						}
						else if(in_array($extensionFichier, $extensionsArchives))
						{
							echo '<a href="uploadedfiles/'.$donnees['nom'].'" title="Image" alt="Erreur Sur Image "><img src="images/archive.gif" border="0" style="float:left;margin-right:10px;margin-top:10px;"></a><br/>';
						}
						/*echo'
							<div class="adroite">
								<form action="upload.php" method="post" >
									<input type="hidden" name="id_file" value="'.$donnees['id'].'" /> 
									<input type="submit" value="Supprimer" class="input"/>
								</form>
							</div>';
						*/
							
						echo $donnees['nom'].'<br/>'.$donnees['lien']."<br/><br/>";
						
						echo '</div>';
						echo '</div>';
						// Fin d'affichage
					}
				}

				// On se déconnecte de MySQL
				mysql_close();
			?>
			</fieldset>
		</p>

	</div><!-- Fin du Corps -->
	<div class="bas">
	</div><!-- Fin de BAS -->
	<br/> Site optimisé pour <a href="http://www.mozilla.fr/">FireFox</a>.
	
</body>
</html>