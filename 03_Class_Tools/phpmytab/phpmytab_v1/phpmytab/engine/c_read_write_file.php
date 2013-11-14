<?php


class read_write_file extends get_manager{
	private $filename; // nom du fichier qui sera généré.
	private $filepath; // chemin ou générer le fichier.

	public function __construct( $_bddParams = '', $_tableParams = '', $_viewParams = ''  ){
		parent::__construct( $_bddParams, $_tableParams, $_viewParams);
		
		if( $_viewParams != '' ){
			if( isset( $_viewParams['extract_filename'] ) )
				$this->set_extract_filename($_viewParams['extract_filename']);
			else
				$this->set_extract_filename('file.txt');
				
			if( isset( $_viewParams['extract_filepath'] ) )
				$this->set_extract_filepath($_viewParams['extract_filepath']);
			else
				$this->set_extract_filepath('.');
		}
	}

	protected function get_extract_filename(){
		return $this->filename;
	}
	protected function get_extract_filepath(){
		return $this->filepath;
	}
	
	protected function set_extract_filename( $filename ){
		$this->filename = $filename;
	}
	protected function set_extract_filepath( $filepath ){
		$this->filepath = $filepath;
	}	
	
	protected function read_head( $infile = false ){
		// Va afficher juste le head (sans les balises TABLE)
		$buff = '';
		if( $infile ){
			$head = $this->get_head();
						
			for( $i=0; $i < count( $head ); $i++ ){
				$buff .= $head[$i]['description'].';';
			} 
			$buff .= '
';
		}else{
			$buff = parent::read_head();
		}
		
		return $buff;
	} 
	protected function read_foot( $infile = false ){
		// Va afficher juste le foot (sans les balises TABLE)
		$buff = '';
		if( $infile ){
			$foot = $this->get_foot();
			for( $i=0; $i < count( $foot ); $i++ ){
				if( isset( $foot[$i] ) )
					$buff .= $foot[$i].';';
				else
					$buff .= ';';
			} 
			$buff .= '
';
		}else
			$buff = parent::read_foot();
			
		return $buff;		
	} 
	protected function read_body( $infile = false ){
		$buff = '';
		// Si on est connecté et que la classe est initilisée
		if( $this->get_bdd_connected() ){
			if( $infile ){
			$req = $this->make_req();
			
			if( $this->get_debug() )
				echo " Req : $req<br/>";
			
			$result = mysql_query( $req );
			$count_line = 0;
			// On fait le tour de la BDD
			while( $data = mysql_fetch_array( $result ) ){
				// réinsère le header toute les X lignes. (si nous ne sommes pas sur la 1ere ligne)
				if( $this->get_reinsert_header() != false )
					if( $count_line%$this->get_reinsert_header() == 0 and $count_line != 0 )
						$buff .= $this->read_head( $infile );
			
				// On fait le tour des colonnes demandées.
				$head = $this->get_head();
				for( $i=0; $i < count( $head ); $i++ ){
					// a-t-on défini une colonne ds la BDD pour cette colonne?
					if( isset( $head[$i]['nom_colonne'] ) ){
						// si oui alors on affiche le contenu de la BDD
						$colonne = $head[$i]['nom_colonne'];
						// insertion de la bonne classe pour les lignes paires et impaires.
						// $buff .= $data["$colonne"].';';
						$buff .= str_replace( "\r\n", " ", $data["$colonne"]).';';
					}else{
						// sinon, a-t-on un champ spécial pour cette colonne?
						if( isset( $head[$i]['spec'] ) ){
							$pattern = $this->descript_spec( $head[$i]['spec'] );
							
							for( $j=0; $j < count( $pattern ); $j++ ){
								$replace[$j] = $data[ $pattern[$j] ];
							} 
							
							// Formatage des resultat pour que ca preg_replace.
							for( $j=0; $j < count( $pattern ); $j++ ){
								$pattern[$j] = '/\['.$pattern[$j].'\]/';
							} 
							

							$buff .= preg_replace($pattern, $replace, $head[$i]['spec']).';';
						}else // sinon on n'affiche rien.
							$buff .= ';';
					}
				} 

				$buff .= '
';
			}
			}else
				$buff = parent::read_body();
			
			return $buff;
		}else{
			if( $this->get_debug() ){
				if( !$this->get_bdd_connected() )
					echo "Erreur ! Il faut d'abord vous connecter : Soit vous n'avez pas fournis de parametres, soit il y a eu une erreur de connexion. Vérifiez les messages plus haut ;)";
			}
			return -1;
		}
	} 
	public function read_table( $infile = false ){
	// va ouvrir et fermer le TABLE et appeler les head, body et foot
		$tab = '';
		if( $infile ){
			if( $this->get_debug() )
				echo "In file TRUE!!!<br/>";
				
			if( $this->get_title() != false )
				$tab .= $this->get_title().'
';
			$tab .= $this->read_head( $infile );
			$tab .= $this->read_body( $infile );
			$tab .= $this->read_foot( $infile );
		}else{
			if( $this->get_debug() )
				echo "In file FALSE!!!<br/>";
			$tab = parent::read_table();
		}
		return $tab;
	} 

}//php2uml 


?>