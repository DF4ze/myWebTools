<?php

class filtred extends find{
	private $idFormulaire;				// @String : id du formulaire de filtrage
	private $idBouton;					// @String : id du submit
	private $filter_button_add_value;	// @String : Texte dy bouton ADD
	private $filter_submit_value;		// @String : Texte dy bouton submit
	private $filter_reset_button;		// @Bool : afficher le bouton reset?
	private $filter_input_submit; 		// @Bool : afficher le bouton submit?
	private $filtering; 				// @Bool : en train de filtrer?
	private $filters; 					// @array[x]['filter'] = text
										//	  	 	['select'] = nom de la colonne BDD.
	
	public function __construct($_bddParams = '', $_tableParams = '', $_viewParams = ''){
		parent::__construct($_bddParams, $_tableParams, $_viewParams );
		
		if( $_viewParams != '' ){
			if( isset( $_viewParams['idFormulaire'] ) )
				$this->set_idFormulaire( $_viewParams['idFormulaire'] );
			else
				$this->set_idFormulaire( 'idFormulaire' );
				
			if( isset( $_viewParams['idBouton'] ) )
				$this->set_idBouton( $_viewParams['idBouton'] );
			else
				$this->set_idBouton( 'idBouton' );
				
			if( isset( $_viewParams['filter_reset_button'] ) )
				$this->set_filter_reset_button( $_viewParams['filter_reset_button'] );
			else
				$this->set_filter_reset_button( false );
			
			if( isset( $_viewParams['filter_input_submit'] ) )
				$this->set_filter_input_submit( $_viewParams['filter_input_submit'] );
			else
				$this->set_filter_input_submit( true );
				
			if( isset( $_viewParams['filter_button_add_value'] ) )
				$this->set_filter_button_add_value( $_viewParams['filter_button_add_value'] );
			else
				$this->set_filter_button_add_value( 'Ajouter' );
				
			if( isset( $_viewParams['filter_submit_value'] ) )
				$this->set_filter_submit_value( $_viewParams['filter_submit_value'] );
			else
				$this->set_filter_submit_value( 'Valider' );
				
		}
		
		$this->filters = array();
		$this->filtering = false;
	} 

	protected function set_idBouton( $idBouton ){
		$this->idBouton = $idBouton;
	}
	protected function set_idFormulaire( $idFormulaire ){
		$this->idFormulaire = $idFormulaire;
	}
	protected function set_filter_reset_button( $filter_reset_button ){
		$this->filter_reset_button = $filter_reset_button;
	}
	protected function set_filter_input_submit( $filter_input_submit ){
		$this->filter_input_submit = $filter_input_submit;
	}
	protected function set_filter_button_add_value( $filter_button_add_value ){
		$this->filter_button_add_value = $filter_button_add_value;
	}
	protected function set_filter_submit_value( $filter_submit_value ){
		$this->filter_submit_value = $filter_submit_value;
	}
	protected function get_idBouton(  ){
		return $this->idBouton;
	}
	protected function get_idFormulaire( ){
		return $this->idFormulaire;
	}
	protected function get_filter_reset_button( ){
		return $this->filter_reset_button;
	}
	protected function get_filter_input_submit( ){
		return $this->filter_input_submit;
	}
	protected function get_filter_button_add_value( ){
		return $this->filter_button_add_value;
	}
	protected function get_filter_submit_value( ){
		return $this->filter_submit_value;
	}
	
	protected function add_filter( $text, $nom_colonne ){
		$this->filters[] = array( "filter" => $text, "select" => $nom_colonne );
		// echo "ADD $text, $nom_colonne ";
	}
	
	public function show_filters(){
		// On affiche le javascript
		$buff = $this->show_javascript();
		// On affiche le formulaire des filtres
		$buff .= $this->show_html_form_filters();
		return $buff;
	}
	public function show_javascript(){
		$buff = '<script type=\'text/JavaScript\'>
			i='.count( $this->filters ).';
            function newLigne()
            {  
                var elForm = document.getElementById("idFormulaire");
                var btn = document.getElementById("idBouton");
                var input;
                var select;
                var br;
				
                input = document.createElement("input");
                input.type = "text";
                input.name = "filter"+i;
                
				select = document.createElement("select");
				select.name = "select"+i;';
		
		$head = $this->get_head();
		$j=0;
		for( $i=0; $i < count( $head ) ; $i++ ){
			if( isset( $head[$i]['description'] )and( isset( $head[$i]['nom_colonne'] )or isset( $head[$i]['sort_by'] ))){
				$buff .= 'select.options['.$j.'] = document.createElement("option");';
				$buff .= 'select.options['.$j.'].text = "'.$head[$i]['description'].'";';
				if( isset( $head[$i]['nom_colonne'] ) )
					$buff .= 'select.options['.$j.'].value = "'.$head[$i]['nom_colonne'].'";';
				else if( isset( $head[$i]['sort_by'] ) )
					$buff .= 'select.options['.$j.'].value = "'.$head[$i]['sort_by'].'";';
				$j++;
			}
		} 

		
        $buff .='br = document.createElement("br");
		
				elForm.insertBefore(input, btn );
				elForm.insertBefore(select, btn );
                elForm.insertBefore(br, btn );
                
                i++;
            }
        </script>';
	
		return $buff;
	}
	public function show_html_form_filters(){
		$buff = '<form method="GET" action="'.$this->get_page_html().'" id="idFormulaire">';
		
		// si on est en mode tri : alors on réaffiche les inputs
		if( $this->filtering ){
			// On fait le tour des filtres
			for( $i=0; $i < count( $this->filters ); $i++ ){
				if( $this->filters[$i]['filter'] != '' ){
					// On affiche l'INPUT TEXT
					$buff .= '<input type="text" name="filter'.$i.'" value="'.urldecode($this->filters[$i]['filter']).'" />';
					// Le SELECT
					$buff .= '<select name="select'.$i.'" >';
					// Décortivage du HEAD pour retrouver les OPTION
					$head = $this->get_head();
					for( $j=0; $j < count( $head ) ; $j++ ){
						// Affiche d'un OPTION
						$buff .= '<option ';
						if( isset( $head[$j]['nom_colonne'] ) and isset( $head[$j]['description'] ) ){
							$buff .= 'value="'.$head[$j]['nom_colonne'].'" ';
							if( $head[$j]['nom_colonne'] ==  $this->filters[$i]['select'] )
								$buff .= 'SELECTED ';
						}else if( isset( $head[$j]['sort_by'] ) and isset( $head[$j]['description'] ) ){
							$buff .= 'value="'.$head[$j]['sort_by'].'" ';
							if( $head[$j]['sort_by'] ==  $this->filters[$i]['select'] )
								$buff .= 'SELECTED ';
						}
						$buff .= ' >'.$head[$j]['description'].'</option>';
					}
					$buff .= '</select> <br/>';
				}
			} 
		}
		
		$buff .= '<input type="button" value="'.$this->get_filter_button_add_value().'" id="'.$this->get_idBouton().'" onclick="newLigne();"/>';
		// affichage optionnel du bouton submit
		if($this->get_filter_input_submit()  )
			$buff .= '<input type="submit" value="'.$this->get_filter_submit_value().'" name="submit_filtred" />';
		
		// affichage optionnel du bouton reset
		if( $this->get_filter_reset_button() )
			$buff .= $this->show_reset_filter();

		$buff .= '</form>';
		return $buff;
	}

	// redef de la fontion make_req pour qu'elle réalise la recherche.
	protected function make_req(){
		$reqLine = parent::make_req();
		
		// si on est en train de filtrer
		if( $this->filtering ){
			$reqLine .= ' WHERE ';
			
			// on rajoute le filtrage sur les champs.
			for( $i=0; $i < count( $this->filters ); $i++  ){
				$reqLine .= $this->filters[$i]['select']." LIKE '%".mysql_real_escape_string(urldecode($this->filters[$i]['filter']))."%' ";
				if( $i < count( $this->filters ) -1 )
					$reqLine .= 'AND ';
			} 
		}
		
		return $reqLine;
	}

	// redef du manage_get pour qu'il prenne en compte les derniers GET
	protected function manage_get( $get ){
		parent::manage_get( $get );
		
		// si on a appuyé sur le bouton VALIDER
		if( isset( $get['submit_filtred']) ){
			// On met tous les GET qui nous intéresse dans notre tableau de filtre.
			for( $i=0; isset( $get['filter'.$i] ) and isset( $get['select'.$i] ); $i++ ){
				$this->add_filter( $get['filter'.$i], $get['select'.$i] );
				$this->filtering = true;
			}
			if( $i == 0 )
				$this->filtering = false;
				
			if( $this->get_debug() )
			foreach( $this->filters as $key => $value  ){
				echo "Value : $value<br/>";
				foreach( $value as $key2 => $value2 ){
					echo "- Key : $key2 = $value2 <br/>";
				} 
			} 
		}
	}	
}//php2uml

?>