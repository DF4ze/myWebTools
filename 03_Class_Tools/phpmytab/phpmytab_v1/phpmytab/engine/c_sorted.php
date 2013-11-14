<?php


class sorted extends filtred{
	// va permettre de faire des liens sur les entetes de colonne de facon a faire un tri sur cette colonne.
	
	private $sorted; // Permet de savoir si on gère le tri ou pas.
	private $asc; // gère les tri ASCENDANT, = colonne sinon = false;
	private $desc; // gère les tri DESCENDANT, = colonne sinon = false;
	
	public function __construct( $_bddParams = '', $_tableParams = '', $_viewParams = '' ){
		parent::__construct( $_bddParams, $_tableParams, $_viewParams );
		
		if( $_viewParams != '' ){		
			if( isset( $_viewParams['sorted'] ) )
				$this->set_sorted( $_viewParams['sorted'] );
			else
				$this->set_sorted( false );
				
			// seulement si le tri est géré qu'on va parametrer ASC et DESC
			if( $this->get_sorted() ){
				if( isset( $_viewParams['asc'] ) )
					$this->set_asc( $_viewParams['asc'] );
				else
					$this->set_asc( false );
					
				if( isset( $_viewParams['desc'] ) )
					$this->set_desc( $_viewParams['desc'] );
				else
					$this->set_desc( false );	
			}
		}
	}
	
	protected function set_asc( $asc ){
		$this->asc = $asc;
	}
	protected function set_desc( $desc ){
		$this->desc = $desc;
	} 
	protected function set_sorted( $sorted ){
		$this->sorted = $sorted;
	} 
	
	protected function get_asc(){
		return $this->asc;
	}
	protected function get_desc(){
		return $this->desc;
	} 
	protected function get_sorted(){
		return $this->sorted;
	} 
	
	// Redef de read_head
	protected function read_head( $infile = false ){
		// Va afficher juste le head (sans les balises TABLE)
		$buff = '';
		
		// doit-on écrire dans un fichier?
		if( $infile ){
			// oui alors pas besoin d'afficher le tri.
			$buff = parent::read_head( $infile );
		}else{
			$buff = '<tr>';
			// On récupère les params
			$head = $this->get_head();
			
			// if( $this->get_debug() )
				// echo "Count Head : ".count( $head )."<br/>";
				
			// On fait le tour des params HEAD
			for( $i=0; $i < count( $head ); $i++ ){
				$buff .= '<th class="'.$this->get_div_line_head().'" >';
				
				// gestion du tri
				if( $this->get_sorted() ){
					// si nous sommes sur une colonne de type 'BDD' et non une colonne personnalisée.
					if( isset( $head[$i]['nom_colonne'] ) ){
						$buff .= '<a href="'.$this->get_page_html().'?';
						if( $this->get_asc() == $head[$i]['nom_colonne'] )
							$buff .= 'desc='.$head[$i]['nom_colonne'];
						else
							$buff .= 'asc='.$head[$i]['nom_colonne'];
					// sinon colonne de type personnalisée
					}else if( isset( $head[$i]['sort_by'] ) ){
						$buff .= '<a href="'.$this->get_page_html().'?';
						if( $this->get_asc() === "$i" ) // ATTENTION au === car get_asc peut retourner FALSE...
							$buff .= 'desc='.$i;
						else
							$buff .= 'asc='.$i;
					}
						
 					// Si on est en mode recherche... et qu'on peut afficher le lien sur cette entete, alors il faut reposter les params.
					if( $this->get_input_text_value() != false and( isset( $head[$i]['nom_colonne'] ) or isset( $head[$i]['sort_by'] )))
						$buff .= '&'.$this->get_getline_forfind(); 
						
					if( isset( $head[$i]['nom_colonne'] ) or isset( $head[$i]['sort_by'] ))
						$buff .= '">';
				}
				
				// Affichage du texte
				$buff .= $head[$i]['description'];
				
				// fin de gestion du tri
				if( $this->get_sorted() )
					if( isset( $head[$i]['nom_colonne'] ) or isset( $head[$i]['order_by'] ))
						$buff .= '</a>';
				
				$buff .= '</th>';
			} 
			$buff .= '</tr>';
		}
		
		return $buff;
	} 
	// redef de make_req
	protected function make_req(){
		$buff = parent::make_req();
		
		if( $this->get_sorted() ){
			if( $this->get_asc() !== false )
				// si c'est numéric, alors il s'agit d'une colonne spéciale, il faut donc retrouver par quelle colonne BDD on tri.
				if( is_numeric( $this->get_asc() ) ){
					// On récupère les parametres du HEAD.
					$head = $this->get_head();
					// On créé la requete avec le bon nom de colonne
					$buff .= ' ORDER BY '.mysql_real_escape_string($head[$this->get_asc()]['sort_by']).' ASC ';
				}else
					$buff .= ' ORDER BY '.mysql_real_escape_string($this->get_asc()).' ASC ';
			else if( $this->get_desc() !== false )				
				// si c'est numéric, alors il s'agit d'une colonne spéciale, il faut donc retrouver par quelle colonne BDD on tri.
				if( is_numeric( $this->get_desc() ) ){
					// On récupère les parametres du HEAD.
					$head = $this->get_head();
					// On créé la requete avec le bon nom de colonne
					$buff .= ' ORDER BY '.mysql_real_escape_string($head[$this->get_desc()]['sort_by']).' DESC ';
				}else
					$buff .= ' ORDER BY '.mysql_real_escape_string($this->get_desc()).' DESC ';
		}
		
		return $buff;
	}
	// redef du manage_get pour qu'il prenne en compte les derniers GET
	protected function manage_get( $get ){
		parent::manage_get( $get );
		
		if( isset( $get['asc'] ) )
			$this->set_asc( $get['asc'] );
		else if( isset( $get['desc'] ) )
			$this->set_desc( $get['desc'] );
		
	} 
	protected function get_getline_forsorted(){
		$buff = '';
		if( $this->get_sorted() ){
			if( $this->get_asc() !== false )
				$buff .= 'asc='.$this->get_asc();
			else if( $this->get_desc() !== false )
				$buff .= 'desc='.$this->get_desc();
		}
		return $buff;
	}
}//php2uml 


?>