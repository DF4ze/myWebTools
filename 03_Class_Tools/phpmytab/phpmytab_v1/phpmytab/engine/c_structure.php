<?php

class structure extends connect{
	
	// Je fait sauter le tableau BODY :
	// Rien de sert de recopier les données de la BDD dans le BODY... un buffering de plus ne fera que ralentir le processus.
	
	private $head; 	// tableau de la forme tab[colonne][description] (nom a afficher)
					//							 	   [nom_colonne] (nom de la colonne dans la bdd que l'ON VEUT AFFICHER)
					//								   [spec]		 ( permet d'afficher un champ personnalisé : #colonne# pour appeler une colonne de BDD)
	private $foot;	// tableau de la forme tab[colonne] = description (nom a afficher)
	
	// $_bddParams = Params de connexion a la BDD.
	// $_tableParams = Tableau contenant $_headParams, $_bodyParams, $_footerParams
	public function __construct( $_bddParams = '', $_tableParams = '', $_headParams = '', /* $_bodyParams = '', */ $_footParams = '' ){
		parent::__construct( $_bddParams );
		
		if( isset( $_headParams ) ){
			if( $_headParams != '' ){
				$this->set_head( $_headParams );
			}
		}
		if( isset( $_footParams ) ){
			if( $_footParams != '' ){
				$this->set_foot( $_footParams );
			}
		}
		if( isset( $_tableParams ) ){
			if( $_tableParams != '' ){
				if( isset( $_tableParams["head"] ) )
					$this->set_head( $_tableParams["head"] );
				if( isset( $_tableParams["foot"] ) )
					$this->set_foot( $_tableParams["foot"] );
			}
		}
		
	} 
	
	protected function get_head(){
		// si on est connecté.
		if( $this->get_bdd_connected() )
			// si n'est pas parametré
			if( !$this->get_parametred() ){
				// on se base sur la BDD
				$data = mysql_fetch_array( mysql_query( "SELECT * FROM ".$this->get_bdd_table() ) );
				$i=0;
				$tab = array();
				foreach( $data as $key => $value  ){
					if( !is_numeric( $key ) ){
						$tab[$i]['nom_colonne'] = $key;
						$tab[$i]['description'] = $key;
						$i++;
					}
				} 
				$this->set_head( $tab );
			}
		
		return $this->head;
	} 
	protected function get_foot(){
		return $this->foot;
	} 

	
	protected function set_head( $head ){
		$this->head = $head;
	}
	protected function set_foot( $foot	){
		$this->foot = $foot;
	}
	
 	protected function get_parametred(){
 		// On est obligé d'attaquer la variable THIS direct, si on fait appel a GET_HEAD ... on va tourner en boucle
		
		$parametred = false;
		//$head = $this->get_head();
		if( isset( $this->head ) ){
			if( $this->head != '' )
				$parametred = true;
		}
		return $parametred; 
	}  
}//php2uml 

?>