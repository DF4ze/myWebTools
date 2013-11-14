<?php

class simpleread extends req_maker{
	// va permettre l'affichage du tableau (titre, head, body, foot)
	
	private $title; // Contient le texte du titre du tableau
	private $title_span; // Contient le nom de la classe qui va "spanner" le Titre.
	private $div_line_head; // Contient le nom de la classe qui va "diver" la ligne de l'entete.
	private $div_line_foot; // Contient le nom de la classe qui va "diver" la ligne du pied de tableau.
	private $div_line_pair; // Contient le nom de la classe qui va "diver" les lignes paires.
	private $div_line_unpair; // Contient le nom de la classe qui va "diver" les lignes impaires.
	private $div_insert_top; // Contient le nom de la classe qui va "diver" les lignes insérées au dessus du tableau.
	private $div_insert_title; // Contient le nom de la classe qui va "diver" les lignes insérées ... dans le titre du tableau.
	private $div_insert_bottom; // Contient le nom de la classe qui va "diver" les lignes impaires insérées sous le tableau..
	private $page_html; // nom de la page html qui detient l'objet. (la page sur laquelle l'user est)
	private $reinsert_header; // réinsère l'entete toute les X lignes. sinon =false.
	private $insert_top; 	// caractères a inserer au dessus du titre.
	private $insert_title; //caractères à inserer entre le titre et le tableau ...; mais dans le titre qd meme ...
	private $insert_bottom; // caractère à inserer en dessous du tableau

	public function __construct( $_bddParams = '', $_tableParams = '', $_viewParams = '' ){
		parent::__construct( $_bddParams, $_tableParams );
		
		if( $_viewParams != '' ){
			if( isset( $_viewParams['title'] ) )
				$this->set_title( $_viewParams['title'] ) ;
			else
				$this->set_title( false );
				
			if( isset( $_viewParams['span_title'] ) )
				$this->set_title_span( $_viewParams['span_title'] ) ;
			else
				$this->set_title_span( 'span_title' );
				
			if( isset( $_viewParams['div_line_pair'] ) )
				$this->set_div_line_pair( $_viewParams['div_line_pair'] ) ;
			else
				$this->set_div_line_pair( 'div_line_pair' );
				
			if( isset( $_viewParams['div_line_unpair'] ) )
				$this->set_div_line_unpair( $_viewParams['div_line_unpair'] ) ;
			else
				$this->set_div_line_unpair( 'div_line_unpair' );
				
			if( isset( $_viewParams['div_line_head'] ) )
				$this->set_div_line_head( $_viewParams['div_line_head'] ) ;
			else
				$this->set_div_line_head( 'div_line_head' );
				
			if( isset( $_viewParams['div_line_foot'] ) )
				$this->set_div_line_foot( $_viewParams['div_line_foot'] ) ;
			else
				$this->set_div_line_foot( 'div_line_foot' );
				
			if( isset( $_viewParams['div_insert_bottom'] ) )
				$this->set_div_insert_bottom( $_viewParams['div_insert_bottom'] ) ;
			else
				$this->set_div_insert_bottom( 'div_insert_bottom' );
				
			if( isset( $_viewParams['div_insert_title'] ) )
				$this->set_div_insert_title( $_viewParams['div_insert_title'] ) ;
			else
				$this->set_div_insert_title( 'div_insert_title' );
				
			if( isset( $_viewParams['div_insert_top'] ) )
				$this->set_div_insert_top( $_viewParams['div_insert_top'] ) ;
			else
				$this->set_div_insert_top( 'div_insert_top' );
				
			if( isset( $_viewParams['reinsert_header'] ) )
				$this->set_reinsert_header( $_viewParams['reinsert_header'] ) ;
			else
				$this->set_reinsert_header( false );
				
			if( isset( $_viewParams['insert_top'] ) )
				$this->set_insert_top( $_viewParams['insert_top'] ) ;
			else
				$this->set_insert_top( false );
				
			if( isset( $_viewParams['insert_title'] ) )
				$this->set_insert_title( $_viewParams['insert_title'] ) ;
			else
				$this->set_insert_title( false );
				
			if( isset( $_viewParams['insert_bottom'] ) )
				$this->set_insert_bottom( $_viewParams['insert_bottom'] ) ;
			else
				$this->set_insert_bottom( false );
		}		
		$this->page_html = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'] ;
	}
	
	protected function get_title(){
		return '<span class="'.$this->title_span.'">'.$this->title.'</span>';
	} 
	protected function get_page_html(){
		return $this->page_html;
	} 
	protected function get_div_line_pair(){
		return $this->div_line_pair;
	} 
	protected function get_div_line_unpair(){
		return $this->div_line_unpair;
	} 
	protected function get_div_line_head(){
		return $this->div_line_head;
	} 
	protected function get_div_line_foot(){
		return $this->div_line_foot;
	} 
	protected function get_div_insert_top(){
		return $this->div_insert_top;
	} 
	protected function get_div_insert_title(){
		return $this->div_insert_title;
	} 
	protected function get_div_insert_bottom(){
		return $this->div_insert_bottom;
	} 
	protected function get_reinsert_header(){
		return $this->reinsert_header;
	} 
	protected function get_insert_top(){
		return $this->insert_top;
	} 
	protected function get_insert_title(){
		return $this->insert_title;
	} 
	protected function get_insert_bottom(){
		return $this->insert_bottom;
	} 
	
	protected function set_title( $title ){
		$this->title = $title;
	} 
	protected function set_title_span( $title_span ){
		$this->title_span = $title_span;
	} 
	protected function set_div_line_pair( $div_line_pair ){
		$this->div_line_pair = $div_line_pair;
	} 
	protected function set_div_line_unpair( $div_line_unpair ){
		$this->div_line_unpair = $div_line_unpair;
	} 
	protected function set_div_line_head( $div_line_head ){
		$this->div_line_head = $div_line_head;
	} 
	protected function set_div_line_foot( $div_line_foot ){
		$this->div_line_foot = $div_line_foot;
	} 
	protected function set_div_insert_top( $div_insert_top ){
		$this->div_insert_top = $div_insert_top;
	} 
	protected function set_div_insert_bottom( $div_insert_bottom ){
		$this->div_insert_bottom = $div_insert_bottom;
	} 
	protected function set_div_insert_title( $div_insert_title ){
		$this->div_insert_title = $div_insert_title;
	} 
	protected function set_reinsert_header( $reinsert_header ){
		$this->reinsert_header = $reinsert_header;
	} 
	protected function set_insert_top( $insert_top ){
		$this->insert_top = $insert_top;
	} 
	protected function set_insert_title( $insert_title ){
		$this->insert_title = $insert_title;
	} 
	protected function set_insert_bottom( $insert_bottom ){
		$this->insert_bottom = $insert_bottom;
	} 
	
	public function add_insert_top( $insert_top ){
		$this->insert_top .= $insert_top;
	} 
	public function add_insert_title( $insert_title ){
		$this->insert_title .= $insert_title;
	} 
	public function add_insert_bottom( $insert_bottom ){
		$this->insert_bottom .= $insert_bottom;
	} 
	
	protected function read_head(){
		// Va afficher juste le head (sans les balises TABLE)
		$buff = '<tr>';
		$head = $this->get_head();
		
		// if( $this->get_debug() )
			// echo "Count Head : ".count( $head )."<br/>";
			
		for( $i=0; $i < count( $head ); $i++ ){
			$buff .= '<th class="'.$this->get_div_line_head().'"  >'.$head[$i]['description'].'</th>';
		} 
		$buff .= '</tr>';
		
		return $buff;
	} 
	protected function read_foot(){
		// Va afficher juste le foot (sans les balises TABLE)
		$buff = '<tr>';
		
		$foot = $this->get_foot();
		for( $i=0; $i < count( $foot ); $i++ ){
			if( isset( $foot[$i] ) )
				$buff .= '<th class="'.$this->get_div_line_foot().'" >'.$foot[$i].'</th>';
			else
				$buff .= '<th class="'.$this->get_div_line_foot().'" ></th>';
		} 
		$buff .= '</tr>';
		
		return $buff;		
	} 
	protected function read_body(){
		// Si on est connecté et que la classe est initilisée
		if( $this->get_bdd_connected() ){

			$req = $this->make_req();
			
			if( $this->get_debug() )
				echo " Req : $req<br/>";
			
			$result = mysql_query( $req );
			$buff = '';
			$count_line = 0;
			// On fait le tour de la BDD
			while( $data = mysql_fetch_array( $result ) ){
				// réinsère le header toute les X lignes. (si nous ne sommes pas sur la 1ere ligne)
				if( $this->get_reinsert_header() != false )
					if( $count_line%$this->get_reinsert_header() == 0 and $count_line != 0 )
						$buff .= $this->read_head();
			
				$buff .= '<tr >';
				$classe = '';
				// determination de la class a inserer sur cette ligne
				if( $this->is_pair( $count_line++ ) )
					$classe= $this->get_div_line_pair();
				else
					$classe = $this->get_div_line_unpair();				
				
				// On fait le tour des colonnes demandées.
				$head = $this->get_head();
				for( $i=0; $i < count( $head ); $i++ ){
					// a-t-on défini une colonne ds la BDD pour cette colonne?
					if( isset( $head[$i]['nom_colonne'] ) ){
						// si oui alors on affiche le contenu de la BDD
						$colonne = $head[$i]['nom_colonne'];
						// insertion de la bonne classe pour les lignes paires et impaires.
						$buff .= '<td class="'.$classe.'" >'.$data["$colonne"].'</td>';
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
							

							$buff .= '<td class="'.$classe.'" >'.preg_replace($pattern, $replace, $head[$i]['spec']).'</td>';
						}else // sinon on n'affiche rien.
							$buff .= '<td class="'.$classe.'" ></td>';
					}
				} 

				$buff .= '</tr>';
			}
			
			return $buff;
		}else{
			if( $this->get_debug() ){
				if( !$this->get_bdd_connected() )
					echo "Erreur ! Il faut d'abord vous connecter : Soit vous n'avez pas fournis de parametres, soit il y a eu une erreur de connexion. Vérifiez les messages plus haut ;)";
			}
			return -1;
		}
	} 
	
	public function read_table(){
	// va ouvrir et fermer le TABLE et appeler les head, body et foot + les slots d'insertion.
		$tab = '';
		if( $this->get_insert_top() != false )
			$tab .= '<div class="'.$this->get_div_insert_top().'">'.$this->get_insert_top()."</div>";
		$tab .= '<table>';
		if( $this->get_title() != false ){
			$tab .= '<caption>';
			$tab .= $this->get_title();
			$tab .= '<div class="'.$this->get_div_insert_title().'">'.$this->get_insert_title()."</div>";
			$tab .= '</caption>';	
		}

		$tab .= $this->read_head();
		$tab .= $this->read_body();
		$tab .= $this->read_foot();
		$tab .= '<table>';
		
		if( $this->get_insert_bottom() != false )
			$tab .= '<div class="'.$this->get_div_insert_bottom().'">'.$this->get_insert_bottom()."</div>";
		
		return $tab;
	} 
}//php2uml 


?>