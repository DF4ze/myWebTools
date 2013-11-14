<?php


class option_page_read extends sorted{
	// Classe qui va gérer le paramétrage de l'affichage de la pagination
	private $currentpage; //N° de la page actuelle
	private $nb_pages; // Nombre de pages totale.
	private $nb_items_page; // Nombre d'item par page.
	private $span_current; // nom de la classe HTML pour l'affichage du N° de la page actuelle.
	private $span_other; // nom de la classe HTML pour l'affichage du N° des autres pages.
	private $limit_page_affiche; // nombre de pages autour de la current que l'on va afficher dans le listing des pages. si desactivé =false.
	private $div_text; // nom de la classe HTML pour le div qui va contenir tout le texte de l'affichage des pages.
	private $div_table; // nom de la classe HTML pour le <table>
	private $nameget_newpage; // Permet de définir le nom de la variable GET qui va etre envoyé lorsqu'on demande un changement de page.
	private $page_delimiter; // caractère délimiteur entre le n° des pages.
	private $page_contractor; // caractère qui va s'afficher lorsqu'on ne veut pas afficher toute les pages. Par defaut '...' (du genre : < ... 23, 24, 25 ... >)
	private $page_foreward; // Permet de définir le caractère/grpmt de caractères qui permettra de faire "suivant" sur les pages. si "false" ne sera pas affiché.
	private $page_backward; // Permet de définir le caractère/grpmt de caractères qui permettra de faire "précédent" sur les pages. si "false" ne sera pas affiché.
	private $page_first; // Permet de définir le caractère/grpmt de caractères qui permettra de faire "1ere page" sur les pages. si "false" ne sera pas affiché.
	private $page_last; // Permet de définir le caractère/grpmt de caractères qui permettra de faire "derniere page" sur les pages. si "false" ne sera pas affiché.
	private $pos_textpages; // Tab de la forme : $tab[ textpages_top ] = true/false;
							// 						 [ textpages_title ] = true/false;
							// 						 [ textpages_bottom ] = true/false;
	
	public function __construct( $_bddParams = '', $_tableParams = '', $_viewParams = '' ){
		parent::__construct( $_bddParams, $_tableParams, $_viewParams );
		
		if( $_viewParams != '' ){
			if( isset( $_viewParams['pagine'] ) )
				$this->set_nb_items_page( $_viewParams['pagine'] );
			else
				$this->set_nb_items_page( false );
			
			if( isset( $_viewParams['currentpage'] ) )
				$this->set_currentpage( $_viewParams['currentpage'] );
			else
				$this->set_currentpage( 1 );
							
			if( isset( $_viewParams['nameget_newpage'] ) )
				$this->nameget_newpage = $_viewParams['nameget_newpage'] ;
			else
				$this->nameget_newpage = 'pmt_newpage';
		
			if( isset( $_viewParams['page_foreward'] ) )
				$this->page_foreward = $_viewParams['page_foreward'] ;
			else
				$this->page_foreward = false;
				
			if( isset( $_viewParams['page_backward'] ) )
				$this->page_backward = $_viewParams['page_backward'] ;
			else
				$this->page_backward = false;
				
			if( isset( $_viewParams['page_delimiter'] ) )
				$this->set_page_delimiter( $_viewParams['page_delimiter'] ) ;
			else
				$this->set_page_delimiter( ', ' );
				
			if( isset( $_viewParams['page_contractor'] ) )
				$this->set_page_contractor( $_viewParams['page_contractor'] ) ;
			else
				$this->set_page_contractor( '...' );
				
			if( isset( $_viewParams['span_current'] ) )
				$this->set_span_current( $_viewParams['span_current'] ) ;
			else
				$this->set_span_current( 'span_current' );
				
			if( isset( $_viewParams['span_other'] ) )
				$this->set_span_other( $_viewParams['span_other'] ) ;
			else
				$this->set_span_other( 'span_other' );
				
			if( isset( $_viewParams['div_text'] ) )
				$this->set_div_text( $_viewParams['div_text'] ) ;
			else
				$this->set_div_text( 'div_text' );
				
			if( isset( $_viewParams['div_table'] ) )
				$this->set_div_table( $_viewParams['div_table'] ) ;
			else
				$this->set_div_table( 'div_table' );
				
			
			if( isset( $_viewParams['page_first'] ) )
				$this->set_page_first( $_viewParams['page_first'] ) ;
			else
				$this->set_page_first( false );
			
			if( isset( $_viewParams['page_last'] ) )
				$this->set_page_last( $_viewParams['page_last'] ) ;
			else
				$this->set_page_last( false );
			
			if( isset( $_viewParams['limit_page_affiche'] ) )
				$this->set_limit_page_affiche( $_viewParams['limit_page_affiche'] ) ;
			else
				$this->set_limit_page_affiche( false );
			

			if( isset( $_viewParams['textpages_top'] ) )
				$this->pos_textpages['textpages_top'] = $_viewParams['textpages_top'];
			
			if( isset( $_viewParams['textpages_title'] ) )
				$this->pos_textpages['textpages_title'] = $_viewParams['textpages_title'];

			if( isset( $_viewParams['textpages_bottom'] ) )
				$this->pos_textpages['textpages_bottom'] = $_viewParams['textpages_bottom'];
			
			
			
			
			
			if( $this->get_debug() )
				echo "Nb Items/Page : ".$_viewParams['pagine']." Nb Pages : ".$this->get_nb_pages().'<br/>';
			
		}
	} 
	
	public function set_currentpage( $page ){
		// On vérifie si la page demandée est valide?
		if( $page <= $this->get_nb_pages() and $page > 0 )
			$this->currentpage = $page;
		else
			$this->currentpage = 1;
	} 
	public function set_nb_items_page( $nb_item ){
		$this->nb_items_page = $nb_item;
	} 
	protected function set_span_current( $span_current ){
		$this->span_current = $span_current;
	} 
	protected function set_span_other( $span_other ){
		$this->span_other = $span_other;
	} 
	protected function set_div_text( $div_text ){
		$this->div_text = $div_text;
	} 
	protected function set_div_table( $div_table ){
		$this->div_table = $div_table;
	} 
	protected function set_nameget_newpage( $nameget_newpage ){
		$this->nameget_newpage = $nameget_newpage;
	} 
	protected function set_page_foreward( $page_foreward ){
		$this->page_foreward = $page_foreward;
	} 
	protected function set_page_backward( $page_backward ){
		$this->page_backward = $page_backward;
	} 
	protected function set_page_first( $page_first ){
		$this->page_first = $page_first;
	} 
	protected function set_page_last( $page_last ){
		$this->page_last = $page_last;
	} 
	protected function set_page_delimiter( $page_delimiter ){
		$this->page_delimiter = $page_delimiter;
	} 
	protected function set_page_contractor( $page_contractor ){
		$this->page_contractor = $page_contractor;
	} 
	protected function set_limit_page_affiche( $limit_page_affiche ){
		$this->limit_page_affiche = $limit_page_affiche;
	} 
	
	public function get_currentpage(){
		return $this->currentpage;
	} 
	public function get_nb_items_page(){
		return $this->nb_items_page;
	} 
	protected function get_span_current(){
		return $this->span_current;
	} 
	protected function get_span_other(){
		return $this->span_other;
	} 
	protected function get_div_text(){
		return $this->div_text;
	} 
	protected function get_limit_page_affiche(){
		return $this->limit_page_affiche;
	} 
	protected function get_nameget_newpage(){
		return $this->nameget_newpage;
	} 
	protected function get_page_foreward(){
		return $this->page_foreward;
	} 
	protected function get_page_backward(){
		return $this->page_backward;
	} 
	protected function get_page_first(){
		return $this->page_first;
	} 
	protected function get_page_last(){
		return $this->page_last;
	} 
	protected function get_page_delimiter(){
		return $this->page_delimiter;
	} 
	protected function get_page_contractor(){
		return $this->page_contractor;
	} 
	protected function get_nb_pages(){
		// Si l'option de pagination est activée.
		if( $this->get_nb_items_page() != false ){
			// si on est connecté
			if( $this->get_bdd_connected() ){
				// si on est en mode recherche!
				if( $this->get_input_text_value() != false )
					$count = mysql_num_rows( mysql_query( parent::make_req() ) );
				else
					$count = mysql_num_rows( mysql_query( "SELECT * FROM ".$this->get_bdd_table() ) );
				
				if( $this->get_nb_items_page() != 0 )
					$nb_pages = $count / $this->get_nb_items_page();
				else
					$nb_pages = 1;
				
				//return $this->nb_pages = $i;
				return $this->nb_pages = ceil( $nb_pages ); // ceil retourne la valeur entiere supérieure d'un nombre a virgule.
			}
		}else
			return 1;
	} 
	public function get_text_pages(){
		$text = '';
		if( $this->get_nb_pages() != 1 ){
			$text .= '<div class="'.$this->get_div_text().'">';
			
			// On evite d'afficher le symbole précédent si on est sur la 1ere page ;)
			if( $this->get_currentpage() != 1 ){
				if( $this->get_page_first() != false )
					$text .= $this->get_stringforbackward( 1, $this->get_page_first() );
				if( $this->get_page_backward() != false )
					$text .= $this->get_stringforbackward();
			}
			// Affichage des chiffres
			if( $this->get_limit_page_affiche() != false )
				$text .= $this->get_listing_filtredpages();
			else
				$text .= $this->get_listing_allpages();
			

			// On évite d'affiche le "suivant" si on est sur la derniere page
			if( $this->get_currentpage() != $this->get_nb_pages() ){
				if( $this->get_page_foreward() != false )
					$text .= $this->get_stringforforward();
				if( $this->get_page_last() != false )
					$text .= $this->get_stringforbackward( $this->get_nb_pages(), $this->get_page_last() );
			}
			
			$text .= '</div>';
		}
		return $text;
	}
	protected function get_listing_filtredpages(){
		// va afficher les X N° de pages qu'il y a autour de la page courrante.
		
		//echo "ON EST A LA PAGE ".$this->get_currentpage().'<br/>';
		
		// Dans un 1er temps, on gère la partie avant la page en cours.
		$start = 1;
		$text = '';
		// Avons nous a afficher les '...'?
		if( $this->get_currentpage() - $this->get_limit_page_affiche()  > 1 ){
			$text .= $this->get_page_contractor();
			
			// et on calcule a quelle page on va commencer l'affichage
			$start = $this->get_currentpage() - $this->get_limit_page_affiche();
		}
		
		// Affichage des chiffres avant la page en cours.
		for( $i = $start; $i < $this->get_currentpage() ; $i++ ){
			$text .= $this->get_stringpagenumber( $i );
			
			if( $i < $this->get_currentpage() )
				$text .= $this->get_page_delimiter();
		} 
		
		// Affichage de la page en cours
		$text .= '<span class="'.$this->get_span_current().'">'.$this->get_currentpage().'</span>';
		
		// Affichage de la partie  apres la page en cours.
		$stop = $this->get_nb_pages();
		if( $this->get_currentpage() + $this->get_limit_page_affiche() < $this->get_nb_pages() ){
			$stop = $this->get_currentpage() + $this->get_limit_page_affiche();
		}
		
		for( $i = $this->get_currentpage()+1; $i <= $stop ; $i++ ){ // +1 pour etre sur la page juste apres la courrante.
			if( $i <= $stop )
				$text .= $this->get_page_delimiter();
				
			$text .= $this->get_stringpagenumber( $i );
			
		} 
		
		if( $this->get_currentpage() + $this->get_limit_page_affiche() < $this->get_nb_pages() )
			$text .= $this->get_page_contractor();			
		
		return $text;
	}
	protected function get_listing_allpages(){
		// Listing de toute les pages et affichage différent sur la page encours.
		$text = '';
		for( $i=1; $i <= $this->get_nb_pages(); $i++ ){
			// Si on est sur la page actuelle.
			if( $i == $this->get_currentpage() )
				$text .= '<span class="'.$this->get_span_current().'">'.$i.'</span>';
			else
				$text .= $this->get_stringpagenumber( $i );
			
			if( $i < $this->get_nb_pages() )
				$text .= $this->get_page_delimiter();
		}
		return $text;
	}
	private function get_stringpagenumber( $nb ){
		$text = '<a href="'.$this->get_page_html().'?'.$this->get_nameget_newpage().'='.$nb;
				
		// Attention! si on est sur un tri et qu'on demande a changer de page, il faut garder le tri!!!
		if( $this->get_asc() !== false or $this->get_desc() !== false )
			$text .= '&'.$this->get_getline_forsorted();
				
		// Attention! si on est sur une rechercher et qu'on......il faut la garder!!!
		if( $this->get_input_text_value() != false )
			$text .= '&'.$this->get_getline_forfind();
					
		$text .= '"><span class="'.$this->get_span_other().'">'.$nb.'</span></a>';
		
		return $text;
	}
	private function get_stringforbackward( $page = '', $symbole = '' ){
		if( $page == '' )
			$page = $this->get_currentpage()-1;
		if( $symbole == '' )
			$symbole = $this->get_page_backward();
			
		$text = '<a href="'.$this->get_page_html().'?'.$this->get_nameget_newpage().'='.$page;
		// Attention! si on est sur un tri et qu'on demande a changer de page, il faut garder le tri!!!
		if( $this->get_asc() !== false or $this->get_desc() !== false )
			$text .= '&'.$this->get_getline_forsorted();
				
		// Attention! si on est sur une rechercher et qu'on......il faut la garder!!!
		if( $this->get_input_text_value() != false )
			$text .= '&'.$this->get_getline_forfind();
			
		$text .= '">'.$symbole.'</a>';
		
		return $text;
	}
	private function get_stringforforward( $page = '', $symbole = ''  ){
		if( $page == '' )
			$page = $this->get_currentpage()+1;
		if( $symbole == '' )
			$symbole = $this->get_page_foreward();
	
		$text = '<a href="'.$this->get_page_html().'?'.$this->get_nameget_newpage().'='.$page;
		// Attention! si on est sur un tri et qu'on demande a changer de page, il faut garder le tri!!!
		if( $this->get_asc() !== false or $this->get_desc() !== false )
			$text .= '&'.$this->get_getline_forsorted();
				
		// Attention! si on est sur une rechercher et qu'on......il faut la garder!!!
		if( $this->get_input_text_value() != false )
			$text .= '&'.$this->get_getline_forfind();
			
		$text .= '">'.$symbole.'</a>';
			
		return $text;
	}
/* 	protected function get_stringforfindget(){
		return 'input_text_name='.$this->get_input_text_value().'&input_submit_name='.$this->get_input_submit_value();
	}
	protected function get_stringfororderbyget(){
		if( $this->get_asc() !== false )
			return 'asc='.$this->get_asc();
		else if( $this->get_desc() !== false )
			return 'desc='.$this->get_desc();	
	}*/
 	protected function get_pos_textpages(){
		return $this->pos_textpages;
	} 
	
	// redef de la fonction make_req()
	protected function make_req( /* $get_all = false */ ){
		// On récupère le résultat de la methode parent.
		$req = parent::make_req();
		
		// Possibilité de contourner la limitation par page : pratique pour faire l'extract.
		// if( !$get_all ){
			if( $this->get_nb_items_page() != false ){
			// On y ajoute un LIMIT
			$req .= ' LIMIT ';
			
			// Mais il nous faut savoir si quelle page on est pour savoir a partir d'ou et jusqu'a ou on affiche.
			$lim_bas = ($this->get_currentpage() -1) * $this->get_nb_items_page();
			
			// Si debug on affiche.
			if( $this->get_debug() )
				echo "Limite basse : $lim_bas Pas : ".$this->get_nb_items_page()."<br/>";
			
			$req .= mysql_real_escape_string( $lim_bas.", ".$this->get_nb_items_page());
			}
		// }
		return $req;
	}
	//redef
	public function read_table( $infile = false ){
		if( $this->pos_textpages['textpages_top'] )
			$this->add_insert_top($this->get_text_pages());
		if( $this->pos_textpages['textpages_title'] )
			$this->add_insert_title($this->get_text_pages());
		if( $this->pos_textpages['textpages_bottom'] )
			$this->add_insert_bottom($this->get_text_pages());

		return parent::read_table( $infile );
	}
	
	// redef du manage_get pour qu'il prenne en compte les derniers GET
	protected function manage_get( $get ){
		parent::manage_get( $get );
		
		if( isset( $get[$this->get_nameget_newpage()] ) ){
			$this->set_currentpage( $get[$this->get_nameget_newpage()] );
			//echo "ON EST SUR LA PAGE ".$this->get_currentpage()."<br/>";
		}
	} 
	protected function get_getline_fornewpage(){
		return $this->get_nameget_newpage().'='.$this->get_currentpage();
	}

}//php2uml 


?>