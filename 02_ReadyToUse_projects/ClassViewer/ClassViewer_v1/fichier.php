	<?php


	class base_client extends c_virtual
	{
		//////////////////////////////
		// Gestion du nom est nickname
		//////////////////////////////
		
		protected $nick;
		
		public function __construct( $_Params = '' ){
			if( $_Params != '' ){
				if( isset( $_Params['nom'] ) )
					$this->set_nom( $_Params['nom'] );
			}
		}
		public function __ToString(){
			return $this->nick;
		}
		
		public function set_nom( $nom ){
			// Affectation $this->nom et retrait des caractères spéciaux pour le $this->nick
			if( $nom != '' ){
				parent::set_nom( $nom );
				$this->nick = $this->normalyze_string( $nom );
			}else{
				// Gestion des erreurs si le nom est vide.
				$error['id'] = 2;
				$error['file'] = __FILE__;
				$error['line'] = __LINE__;
				$error['class'] = __CLASS__;
				$error['function'] = __FUNCTION__;
					
				$this->add_error( $error );
			}
		}
		
		public function get_nom(){
			return $this->nom;
		}
		public function get_nick(){
			return $this->nick;
		}
		public function get_CallServiceID(){
			return $this->nom;
		}
		
		// protected function sans_espaces( $chaine = "" ){
			// if( $chaine == "" )
				// $chaine = $this->nom;
			// return str_replace( " ", "_", $chaine);
		// }
		

		public function normalyze_string($txt) {
			$masque = "[?!]";
			$txt = eregi_replace($masque, "", $txt);

			$masque = "[àâä@]";
			$txt = eregi_replace($masque, "a", $txt);

			$masque = "[éèêë€]";
			$txt = eregi_replace($masque, "e", $txt);

			$masque = "[ïì]";
			$txt = eregi_replace($masque, "i", $txt);

			$masque = "[ôö]";
			$txt = eregi_replace($masque, "o", $txt);

			$masque = "[ùûü]";
			$txt = eregi_replace($masque, "u", $txt);

			$masque = "[ç]";
			$txt = eregi_replace($masque, "c", $txt);

			$masque = "[&]";
			$txt = eregi_replace($masque, "et", $txt);

			$masque = " +";
			$txt = eregi_replace($masque, "_", $txt);

			return(strtolower($txt));
		 }
		
		
	}//php2uml

	class bc_horaire extends base_client
	{
		///////////////////////////////////
		// Gestion des heures ouvrées.
		///////////////////////////////////
		protected $h_ouverture;
		protected $h_fermeture;

		public function __construct( $_Params = '' ){
			if( $_Params != '' ){
				// Appel du construct parent.
				parent::__construct( $_Params );
				
				// Affectation personnalisée.
				if( isset( $_Params['h_ouverture'] ) )
					$this->set_h_ouverture( $_Params['h_ouverture'] );
				// Affectation par defaut
				else $this->set_h_ouverture( $_SESSION['h_ouverture'] );
				
				// Affectation personnalisée.
				if( isset( $_Params['h_fermeture'] ) )
					$this->set_h_fermeture( $_Params['h_fermeture'] );
				// Affectation par defaut
				else $this->set_h_fermeture( $_SESSION['h_fermeture'] );
			}
		}

		public function set_h_ouverture( $h_ouverture ){
			// On vérifie que l'heure est bien au format HH:MM:SS
			$h_ouverture = $this->check_format_hour( $h_ouverture );
			// affectation de la valeur a la variable locale.
			$this->h_ouverture = $h_ouverture;
	/* 		// On récupère le nom formaté,
			$client = $this->get_nick();
			// Pour incrire dans la BDD la nouvelle valeur.
			mysql_query("UPDATE clients SET h_ouverture='$h_ouverture'  WHERE client = '$client'") or die(mysql_error());
	 */	}
		public function set_h_fermeture( $h_fermeture ){
			// On vérifie que l'heure est bien au format HH:MM
			$h_fermeture = $this->check_format_hour( $h_fermeture );
			// affectation de la valeur a la variable locale.
			$this->h_fermeture = $h_fermeture;
		}

		public function get_h_ouverture(){
			return $this->h_ouverture;
		}
		public function get_h_fermeture(){
			return $this->h_fermeture;
		}
		
		public function check_format_hour( $hour ){
			$formated_hour = new DateTime( $hour );
			return $formated_hour->format( 'H:i' );
		}
	}//php2uml

	class bc_h_jf_we extends bc_horaire
	{
		////////////////////////////////////////
		// Gestion des Jours Fériés et Week-End
		////////////////////////////////////////
		protected $work_jf;
		protected $work_we;
		protected $exclusion_days;
		
		public function __construct( $_Params = '' ){
			if( $_Params != '' ){
				// Appel du construct parent.
				parent::__construct( $_Params );
				
				// Affectation personnalisée.
				if( isset( $_Params['work_jf'] ) )
					$this->set_work_jf( $_Params['work_jf'] );
				// Affectation par defaut
				else $this->set_work_jf( $_SESSION['work_jf'] );
				
				// Affectation personnalisée.
				if( isset( $_Params['work_we'] ) )
					$this->set_work_we( $_Params['work_we'] );
				// Affectation par defaut
				else $this->set_work_we( $_SESSION['work_we'] );
				
				// Affectation personnalisée.
				if( isset( $_Params['exclusion_days'] ) )
					$this->set_exclusion_days( $_Params['exclusion_days'] );
			}
		}
		
		public function get_work_we(){
			return $this->work_we;
		}
		public function get_work_jf(){
			return $this->work_jf;
		}
		public function get_exclusion_days(){
			return $this->exclusion_days;
		}
		
		public function set_work_we( $work_we ){
			$this->work_we = $work_we;
		}	
		public function set_work_jf( $work_jf ){
			$this->work_jf = $work_jf;
		}
		public function set_exclusion_days( $exclusion_days ){
			$this->exclusion_days = $exclusion_days;
		}

		///////////////////////////////////////////////////////
		// A implémenter lorsqu'il y aura la BDD des JF.
		/////////////////////////////////////////////////////
		public function maj_exclusion_days( $date_debut, $date_fin ){
			// Fonction qui va mettre a jour le tableau des jours à exclure en fonction des 2 dates prédef.
			;
		}
	}//php2uml

	class bc_h_jw_bornes extends bc_h_jf_we
	{
		//////////////////////////////////////////////
		// Gestion des bornes pour le calcul des SLA.
		//////////////////////////////////////////////
		protected $bornes;
		
		public function __construct( $_Params = '' ){
			if( $_Params != '' ){
				parent::__construct( $_Params );
				
				if( isset( $_Params['bornes'] ) ){
					$this->set_bornes( $_Params['bornes'] );
				}else{
					// Si pas de parametres pour les bornes, nous mettons les bornes par defaut.
					$this->bornes['decro'][0] = $_SESSION['borne_min_decro'];
					$this->bornes['decro'][1] = $_SESSION['borne_max_decro'];
					$this->bornes['abandons'][0] = $_SESSION['borne_min_abandons'];
					$this->bornes['abandons'][1] = $_SESSION['borne_max_abandons'];
				}
			}else{
				// Gestion des warnings si le param est vide.
				$error['id'] = 2;
				$error['file'] = __FILE__;
				$error['line'] = __LINE__;
				$error['class'] = __CLASS__;
				$error['function'] = __FUNCTION__;
					
				$this->add_warning( $error );
			}
		}

		public function add_bornes( $type, $value ){
			$error['file'] = __FILE__;
			$error['line'] = __LINE__;
			$error['class'] = __CLASS__;
			$error['function'] = __FUNCTION__;

			// Soit c'est une borne de décroché
			if( $type == "decro" or $type == "décro" or $type == "decroche" or $type == "décroche" or $type == "décroché" ){
				// On check si la borne a ajouter n'existerait pas.
				if( $this->find( $value, $this->bornes['decro'] ) == -1 ){
					// On insère en dernier.
					$this->bornes['decro'][ count( $this->bornes['decro'] ) ] = $value;
					// On réorganise du petit vers le plus grand.
					sort( $this->bornes['decro'] );
					return true;
				}else{
					$error['id'] = 4;			
					$this->add_warning( $error );
					return -2;
				}
			// Soit c'est une borne d'abandons.
			}else if( $type == "abandons" or $type == "ab" or $type == "aband" ){
				// On check si la borne a ajouter n'existerait pas.
				if( $this->find( $value, $this->bornes['abandons'] ) == -1 ){
					// On insère en dernier.
					$this->bornes['abandons'][ count( $this->bornes['abandons'] ) ] = $value;		
					// On réorganise du petit vers le plus grand.
					sort( $this->bornes['abandons'] );
					return true;
				}else{
					$error['id'] = 4;			
					$this->add_warning( $error );
					return -2;
				}		
			// Soit ... c'est une erreur
			}else{
				$error['id'] = 2;			
				$this->add_error( $error );
				return -1;
			}
		}
		public function supp_bornes( $type, $offset ){
			$error['file'] = __FILE__;
			$error['line'] = __LINE__;
			$error['class'] = __CLASS__;
			$error['function'] = __FUNCTION__;
			
			if( $type == "decro" or $type == "décro" or $type == "decroche" or $type == "décroche" or $type == "décroché" ){
				// Vérif de la validité de l'offset donné.
				if( $offset >= 0 and $offset < count( $this->bornes['decro'] ) ){
					// On détruit l'offset demandé
					unset( $this->bornes['decro'][$offset] );
					// On reclasse le tableau
					sort( $this->bornes['decro'] );
				}else{
					$error['id'] = 3;
					$this->add_error( $error );
					return -1;
				}
			}else if( $type == "abandons" or $type == "ab" ){
				// Vérif de la validité de l'offset donné.
				if( $offset >= 0 and $offset < count( $this->bornes['abandons'] ) ){
					// On détruit l'offset demandé
					unset( $this->bornes['abandons'][$offset] );
					// On reclasse le tableau
					sort( $this->bornes['abandons'] );
				}else{
					$error['id'] = 3;
					$this->add_error( $error );
					return -1;
				}
			}else{
				$error['id'] = 2;
				$this->add_error( $error );
				return -1;
			}		
		}
		public function modify_bornes( $type, $offset, $new_value ){
			$error['file'] = __FILE__;
			$error['line'] = __LINE__;
			$error['class'] = __CLASS__;
			$error['function'] = __FUNCTION__;

			if( $type == "decro" or $type == "décro" or $type == "decroche" or $type == "décroche" or $type == "décroché" ){
				// Vérif de la validité de l'offset donné.
				if( $offset >= 0 and $offset < count( $this->bornes['decro'] ) ){
					// On écrase la valeur.
					$this->bornes['decro'][$offset] = $new_value;
					// On tri le tableau au cas ou...
					sort( $this->bornes['decro'] );
					return true;
				}else{
					$error['id'] = 3;
					$this->add_error( $error );
					return -1;
				}
			}else if( $type == "abandons" or $type == "ab" ){
				// Vérif de la validité de l'offset donné.
				if( $offset >= 0 and $offset < count( $this->bornes['abandons'] ) ){
					// On écrase la valeur.
					$this->bornes['abandons'][$offset] = $new_value;
					// On tri le tableau au cas ou...
					sort( $this->bornes['abandons'] );
					return true;
				}else{
					$error['id'] = 3;
					$this->add_error( $error );
					return -1;
				}
			}else{
				$error['id'] = 2;
				$this->add_error( $error );
				return -1;
			}		
		}
		
		public function get_bornes(){
			return $this->bornes;
		}
		public function set_bornes( $tableau = '' ){
			$error['file'] = __FILE__;
			$error['line'] = __LINE__;
			$error['class'] = __CLASS__;
			$error['function'] = __FUNCTION__;

			// SI le tableau n'est pas vide.
			if( $tableau != '' ){
				// S'il y a bien les 2 catégories
				if( isset( $tableau['decro'] ) and isset( $tableau['abandons'] )){
					$this->bornes = $tableau;
					return true;
				}else{
					$error['id'] = 2;			
					$this->add_error( $error );
					return -1;	
				}
			}else{
				$error['id'] = 1;			
				$this->add_error( $error );
				return -1;
			}
		}
		
		public function echo_bornes(){
			$text = "decro : ";
			for( $i = 0; $i < count( $this->bornes['decro'] ); $i++ )
				$text .= $this->bornes['decro'][$i].',';
			
			$text .= "<br/> abandons : ";
			for( $i = 0; $i < count( $this->bornes['abandons'] ); $i++ )
				$text .= $this->bornes['abandons'][$i].',';
			echo $text;
		}
		

	}  //php2uml

	class bc_h_jw_b_options extends bc_h_jw_bornes
	{
		protected $options;
		//							--> X : N° de la colonne ou ordre d'affichage des colonnes.
		// options[x][nom]			--> Nom donné par l'utilisateur (qui s'affichera en entete de colonne)
		//          [type]			--> Type de la colonne : nbappel, nbdecro, nbabandons, personnalisée, etc...
		//          [seuilmoytpscom]--> sert lors de la moyenne de temps de communication, exclura les tps de com < à ce seuil. (si non def = 5s par defaut)
		//          [sens]			--> sert lors de comparaison de borne : inférieur à, supérieur à 
		//          [borne1]		--> Valeur de la borne 
		//          [borne2]		
		//          [format_value]	--> Affichage du résultat : Numérique, Pourcentage, heure etc...
		//          [colonne_ref]	--> INUTILISE --> Colonne de référence pour le calcul des pourcentages par exemple. 
		//          [code]			--> Si le type est "personnalisé", alors le script prendra le code inscript ici.
		//								du genre : [compabs;inf;20;]*[100]/([nbappels]-[compabs;inf;20;]). --> Donne le pourcentage d'appel en absence < 20s par rapport à la base statistique.
		//			[total]			--> Indique le type de total : Moyenne, Somme, maximum, minimum, no_total
		//			[seuil]			--> Indique le seuil pour le calcul du total : Moyenne et Somme
		
		
		public function __construct( $_Params = '' ){
			if( $_Params != '' ){
				parent::__construct( $_Params );
				
				if( isset( $_Params['options'] ) ){
					$this->set_options( $_Params['options'] );
				}else{
					// Si pas de parametres pour les options, nous mettons les options par defaut.

				}
			}else{
				// Gestion des warnings si le param est vide.
				$error['id'] = 2;
				$error['file'] = __FILE__;
				$error['line'] = __LINE__;
				$error['class'] = __CLASS__;
				$error['function'] = __FUNCTION__;
					
				$this->add_warning( $error );
			}
		}

		public function set_options( $options = '' ){
			if( $options != '' ){
				$this->options = $options;
			}else{
				// Gestion des warnings si le param est vide.
				$error['id'] = 1;
				$error['file'] = __FILE__;
				$error['line'] = __LINE__;
				$error['class'] = __CLASS__;
				$error['function'] = __FUNCTION__;
				
				$this->add_error( $error );
			}
		}
		public function get_options(){
			return $this->options;
		}

		public function add_option( $tab_option ){
			// recherche de l'offset ou inserer l'option.
			$offset = count( $this->options );
			// Insertion de la nouvelle option.
			$this->options[$offset] = $tab_option;
		}
		public function supp_option( $offset ){
			// On va creer un nouveau tableau, dans lequel on va ommettre l'offset en parametre.
			$new_tab_options = array();
			
			$j=0;
			for( $i=0; $i < count( $this->options ); $i++ ){
				if( $i != $offset ){
					$new_tab_options[$j] = $this->options[$i];
					$j++;
				}
			}
			
			// Puis on écrase l'ancien tab d'options.
			$this->set_options( $new_tab_options );
		}

	}//php2uml

	class bc_h_jw_b_o_crash extends bc_h_jw_b_options
	{
		// Tableau comprenant tout les crash du client.
		protected $Listing_Crash; 
		// Listing_Crash[$i]['CallTime_begin_crash']
		// Listing_Crash[$i]['CallTime_end_crash']
		// Listing_Crash[$i]['duree']
		// Listing_Crash[$i]['commentaire']
		// Listing_Crash[$i]['condition_crash']
		// Listing_Crash[$i]['select'] --> pour les moyennes de temps d'attente.
			
		public function __construct( $_Params = '' ){
			if( $_Params != '' ){
				parent::__construct( $_Params );
				
				if( isset( $_Params['crash'] ) ){
					$this->set_crash( $_Params['crash'] );
				//	echo "<h1>Crash set Construct</h1>";
				}
			}
		}
		
		public function set_crash( $Crashs ){
			$this->Listing_Crash = $Crashs;
		}
		public function get_crash(  ){
			return $this->Listing_Crash;
		}
		public function add_crash( $Crash ){
			$this->Listing_Crash[ count( $this->Listing_Crash ) ] = $Crash;
		}
		public function supp_crash( $offset ){
			if( isset( $this->Listing_Crash[ $offset ] ) ){
				unset( $this->Listing_Crash[ $offset ] );
				echo "deleted $offset<br/>";
				// Il serait bien de trier le tableau pour qu'il n'y ait pas de trou dans les Offsets...
				sort($this->Listing_Crash);
			}
		}
	}//php2uml

	class bc_h_jw_b_o_c_result extends bc_h_jw_b_o_crash
	{
		protected $result; // $result[ $date ][ $Colonne ] = value;
		protected $totaux; // $totaux[colonne] = value
		
		public function __construct( $_Params ){
			parent::__construct( $_Params );
			
			if( isset( $_Params['result'] ) )
				$this->set_tab_result( $_Params['result'] );
			else
				$this->init_tab_result();
			
			if( isset( $_Params['totaux'] ) )
				;//$this->set_result( $_Params['totaux'] );
			else
				$this->init_tab_totaux();
		}

		public function init_tab_result(){
			$this->result = array();
		}
		public function set_tab_result( $result ){
			$this->result = $result;
		}
		public function get_tab_result(){
			return $this->result;
		}

		public function init_tab_totaux(){
			$this->totaux = array();
		}
		public function set_tab_totaux( $totaux ){
			$this->totaux = $totaux;
		}
		public function get_tab_totaux(){
			return $this->totaux;
		}

		public function init_tab(){
			$this->init_tab_result();
			$this->init_tab_totaux();
		}
		
		// redef de la fonction pour qu'il y ait réinitialisation du tableau de résultat lorsqu'on change les options.
		public function set_options( $options = '' ){
			parent::set_options( $options );
			$this->init_tab_result();
		}
	}//php2uml

	class bc_h_jw_b_o_c_r_checked extends bc_h_jw_b_o_c_result
	{
		// class qui va gérer le fait que le client est checked ou pas...
		// option necessaire pour la class Equipe pour qu'elle sache si on réalise les super totaux de ce client ou pas.
		public $checked;
		
		public function __construct( $_Params = "" ){
			parent::__construct( $_Params );
			
			if( isset( $_Params['checked'] ) )
				$this->checked = $_Params['checked'];
		}
	}//php2uml




	class c_mise_en_cache extends bc_h_jw_b_o_c_r_checked
	{
		// Class qui va gérer la mise en cache.
		
		// On redef la function execute_req_options_bases(), celle qui réalise le calcul du tableau,
		// de facon a vérifier d'abord si les résultats ne seraient pas en cache.
		// sinon ca les calcul et les mets en cache.
		
		//!\ Des qu'une option du client est changée, il est necessaire de faire un reset de sa mise en cache!!!
		
		// En fait la mise en cache est étroitement liée aux options des colonnes.
		// Tant que ces options ne sont pas définies ... il ne peut pas y avoir de création de table de cache.
		
		protected $table; // détient le nom de la table : $this->table = $_SESSION['prefix_cache'].$this->nick;
		
		public function __construct( $_Params = '' ){
			parent::__construct( $_Params );
			
			// Création du nom de la table de cache
			$this->reset_nom_table();
			
			// Création de la table de cache pour le client, si elle n'existe pas... et s'il y a les parametres d'options des colonnes
			$this->create_table_cache();
		}
		
		public function get_nom_table(){
			return $this->table;
		} 
		public function reset_nom_table(){
			$this->table = $_SESSION['prefix_cache'].$this->nick;
		} 
		
		protected function create_table_cache(){
			// si la table n'existe pas et que les colonnes du client ont été définies.
			if( !$this->mysql_table_exists( $this->table, $_SESSION['bd_base'] ) ){
				if( count( $this->get_options() ) != 0 ){
					// Préparation de la requete.
					$req = "CREATE TABLE `".$this->table."` ( `date` VARCHAR( 255 ) NOT NULL , ";
					
					// Boucle sur le nombre de colonne du client.
					foreach( $this->options as $key => $value  ){
						$req .= "`$key` FLOAT NOT NULL , ";
					} 
					
					// Définition du champ DATE comme PRIMARY KEY
					$req .= "PRIMARY KEY ( `date` )) ENGINE = innodb;";
					
					echo "Req : $req <br/>";
					if( mysql_query( $req ) )
						return true;
					else
						return false;
				}else
					echo "Options vides | client : ".$this->get_nom()."<br/>";
			}else
				;//echo "Table existe <br/>";
		} 
		protected function delete_table_cache(){
			// Si la table existe
			if( $this->mysql_table_exists( $this->table, $_SESSION['bd_base'] ) ){
				// On la supprime
				if( mysql_query( "DROP TABLE `".$this->table."`" ) )
					return true;
				else
					return false;
			}
		} 
		protected function reset_table_cache( $date1 = '', $date2 = '' ){
			//if( $this->delete_table_cache() )
			// si on arrive pas a supprimer mais qu'on arrive a creer ... ce n'est pas une erreur,
			// C'est que la table n'était pas créée a l'origine...
			// (du coup il y a eu une erreur à un moment qd meme!)
			
			// S'il n'y a pas de date, on supprime la table et on la recréée.
			if( $date1 == '' and $date2 == '' ){
				$this->delete_table_cache();
				if( $this->create_table_cache() )
						return true;
				return false;			
			
			}else{
			// Sinon, on ne supprime que les données entre les 2 dates.
			
			}
		} 
		
		protected function supp_cache( $date1, $date2 ){
			if( $date1 < $date2 ){
				mysql_query( "DELETE FROM ".$this->table." WHERE date BETWEEN $date1 AND $date2" ); 
			} else
				echo "Date 1 supérieure à Date 2<br/>";
			
		} 
		public function write_cache( $result ){
			$all_ok = true;
			if( count($result) != 0 ){
				foreach( $result as $date => $colonnes  ){
					$req = 'INSERT INTO '.$this->table.' VALUES( '.$date.', ';
					$nb = count( $colonnes );
					$count = 0;
					foreach( $colonnes as $num => $value  ){
						// mysql_query ( );
						// "INSERT INTO tbl_name (a,b,c) VALUES(1,2,3)"
						$req .= $value;
						if( $count < $nb-1 )
							$req .= ', ';
						$count++;
					} 
					$req .= ")";
					echo "Write Cache : req : $req<br/>";
					
					if( !mysql_query( $req ) )
						$all_ok = false;
					
				} 
			}else{
				echo "Write Cache ... Pas de résult??!!<br/>";
				$all_ok = false;
			}
				
			return $all_ok;
		}
		
		
		protected function mysql_table_exists($table , $db) {
			$tables = mysql_list_tables( $db );
			
			while( list( $temp ) = mysql_fetch_array( $tables )){
				//echo "$temp<br/>";
				if( $temp == $table )
					return 1;
			}
			return 0;
		}
		
		
		///////////////////////////////
		// Redefinition de fonctions
		///////////////////////////////
		
		public function set_options( $options = '' ){
			parent::set_options( $options );
			
			// Reset de la table lorsqu'on modifie les options.
			if( $this->reset_table_cache() )
				echo "SET OPTIONS OK<br/>";
			else
				echo "SET OPTIONS NON OK<br/>";
		}
		
		public function set_nom( $nom ){
			// Si on change de nom ... il faudrait changer le nom de la table ...
			// Ici ... on supprime la table de cache et on la recréé avec le bon nom.
			
			$this->delete_table_cache();
			
			parent::set_nom( $nom );
			
			$this->reset_nom_table();
			$this->create_table_cache();
		}

		public function set_tab_result( $result ){
			parent::set_tab_result($result);
			
			// et mise en cache
			$this->write_cache( $result );			
		}
	}//php2uml



	class client extends bc_h_jw_b_o_c_r_checked // ! \\ Attention By Pass de la classe MISE EN CACHE
	{
		// Class Mère.
		// hérite de la derniere class 
	}//php2uml





	class client_old 
	{
		public $nom;
		public $nick;
		public $H_ouverture;
		public $H_fermeture;
		public $compte_we;
		public $compte_jf;
		public $equipe;
		// public $borne_min;
		// public $borne_max;
		// public $borne_min_decro;
		// public $borne_max_decro;
		public $SLA;
		public $couleur;
		public $dispo_min;
		public $dispo_max;
		public $is_select;
		// public $graph_par_jour;
		// public $graph_total;
		// public $graph_total_moyenne;
		
		const END_START_NOT_DISPO = 4;
		const FULL_DISPO = 3;
		const ONLY_END_DISPO = 2;
		const ONLY_START_DISPO = 1;
		const NOT_DISPO = 0;
		const ERROR = -1;
			
		public function __construct( $nom_client, $H_ouverture_client="08:00", $H_fermeture_client="18:00", $equipe_client="NoTeam", $compte_lewe=1, $compte_lesjours_feries=0, $laborne_min = 21, $laborne_max = 30, $laborne_min_decro = 21, $laborne_max_decro = 30,  $la_SLA = 80, $couleur = "000000", $come_from_bdd = 0 ){
			$this->nom = $nom_client;
			$this->nick = $this->sans_espaces( $nom_client ); 
			$this->H_ouverture = $H_ouverture_client;
			$this->H_fermeture = $H_fermeture_client;
			$this->equipe = $equipe_client;
			$this->compte_we = $compte_lewe;
			$this->compte_jf = $compte_lesjours_feries;
			$this->borne_min = $laborne_min;
			$this->borne_max = $laborne_max;
			$this->borne_min_decro = $laborne_min_decro;
			$this->borne_max_decro = $laborne_max_decro;
			$this->SLA = $la_SLA;
			$this->couleur = $couleur;
			$this->is_select = 0;
			$this->graph_par_jour = new ClassArray();
			$this->graph_total = new ClassArray();
			$this->graph_total_moyenne = new ClassArray();
			
			//mise a jour des dates en allant scanner la BDD
			if( $come_from_bdd == 1 )
			{
				$donnees = mysql_fetch_array(mysql_query( "SELECT * FROM dates WHERE client='$nom_client'" ));
				$this->dispo_min = $donnees['date_debut'];
				$this->dispo_max = $donnees['date_fin'];
			}
		}
		public function __toString(){
			return $this->nom;
		}
		public function is_dispo( $date1, $date2 ){
			// $D1_is_dessus = false;
			// $D2_is_dessous = false;
			@$resultat = NOT_DISPO;
			
			if( $date1 <= $date2 )
			{
				// Si 1ere date est dans l'interval
				if( $this->dispo_min <= $date1 and $date1 <= $this->dispo_max )
				{
					// Si 2eme date dans l'interval.
					if( $this->dispo_min <= $date2 and $date2 <= $this->dispo_max )
					{
						// Alors FULL_DISPO
						@($resultat = FULL_DISPO);			
					}
					else if( $date2 > $this->dispo_max )
					{
						// Date 1 OK mais date 2 NOK
						@$resultat = ONLY_START_DISPO;
					}
				}	
				// Si la 1ere date en dessous de l'interval
				else if( $date1 <= $this->dispo_min )
				{
					// Si 2eme date dans l'interval.
					if( $this->dispo_min <= $date2 and $date2 <= $this->dispo_max )
					{
						// Alors ONLY_END_DISPO
						@$resultat = ONLY_END_DISPO;			
					}
					// Si 2eme date au dessus de l'interval
					else if( $date2 > $this->dispo_max )
					{
						//les dates entourent l'interval.
						@$resultat = END_START_NOT_DISPO;
					}
					// Si 2eme date en dessous
					else if( $date2 < $this->dispo_min )
					{
						// Les 2 dates dont en dessous
						@$resultat = NOT_DISPO;
					}
				}
				// SI 1ere date au dessus de l'interval...
				else
					@$resultat = NOT_DISPO;
			}
			else
				@$resultat = ERROR;
			
			return $resultat;

		}
		public function sans_espaces( $chaine = "" ){
			if( $chaine == "" )
				$chaine = $this->nom;
			return str_replace( " ", "_", $chaine);
		}
		public function set_Houverture( $h_ouverture ){
			$this->H_ouverture = $h_ouverture;
			$client = $this->nom;
			mysql_query("UPDATE clients SET h_ouverture='$h_ouverture'  WHERE client = '$client'") or die(mysql_error());
		}
		public function set_Hfermeture( $h_fermeture ){
			$this->H_fermeture = $h_fermeture;
			$client = $this->nom;
			mysql_query("UPDATE clients SET h_fermeture='$h_fermeture'  WHERE client = '$client'") or die(mysql_error());
		}
		public function set_equipe( $equipe ){
			$this->equipe = $equipe;
			$client = $this->nom;
			mysql_query("UPDATE clients SET equipe='$equipe'  WHERE client = '$client'") or die(mysql_error());
		}
		public function set_sla( $sla ){
			$this->SLA = $sla;
			$client = $this->nom;
			mysql_query("UPDATE clients SET sla='$sla'  WHERE client = '$client'") or die(mysql_error());
		}
		public function set_borne_min( $min ){
			$this->borne_min = $min;
			$client = $this->nom;
			mysql_query("UPDATE clients SET min='$min'  WHERE client = '$client'") or die(mysql_error());
		}
		public function set_borne_max( $max ){
			$this->borne_max = $max;
			$client = $this->nom;
			mysql_query("UPDATE clients SET max='$max'  WHERE client = '$client'") or die(mysql_error());
		}
		public function set_jf( $jf ){
			$this->compte_jf = $jf;
			$client = $this->nom;
			mysql_query("UPDATE clients SET compte_jf='$jf'  WHERE client = '$client'") or die(mysql_error());
		}
		public function set_we( $we ){
			$this->compte_we = $we;
			$client = $this->nom;
			mysql_query("UPDATE clients SET compte_we='$we'  WHERE client = '$client'") or die(mysql_error());
		}
	}

	?>