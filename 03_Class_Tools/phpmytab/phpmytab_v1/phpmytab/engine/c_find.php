<?php


class find extends read_write_file{
	// va implémenter la fonction de recherche.
	private $input_text_name;
	private $input_text_value;
	private $input_submit_name;
	private $input_submit_value;
	private $input_separator;
	private $reset_button_value;
	
	public function __construct( $_bddParams = '', $_tableParams = '', $_viewParams = '' ){
		parent::__construct( $_bddParams, $_tableParams, $_viewParams );
		
		if( $_viewParams != '' ){
			if( isset( $_viewParams['input_text_name'] ) )
				$this->set_input_text_name( $_viewParams['input_text_name'] );
			else
				$this->set_input_text_name( 'input_text_name' );
				
			if( isset( $_viewParams['input_text_value'] ) )
				$this->set_input_text_value( $_viewParams['input_text_value'] );
			else
				$this->set_input_text_value( '' );
				
			if( isset( $_viewParams['find_input_submit'] ) ){
				if( $_viewParams['find_input_submit'] == true )
					$this->set_input_submit_value( $_viewParams['find_input_submit_value'] );
				else
					$this->set_input_submit_value( false );
			}else
				$this->set_input_submit_value( 'Rechercher' );

			if( isset( $_viewParams['input_submit_name'] ) )
				$this->set_input_submit_name( $_viewParams['input_submit_name'] );
			else
				$this->set_input_submit_name( 'input_submit_name' ); 
					
			if( isset( $_viewParams['input_separator'] ) )
				$this->set_input_separator( $_viewParams['input_separator'] );
			else
				$this->set_input_separator( ' ' );
				
			if( isset( $_viewParams['reset_button_value'] ) )
				$this->set_reset_button_value( $_viewParams['reset_button_value'] );
			else
				$this->set_reset_button_value( 'Reset' );
			
			if( isset( $_viewParams['find_reset_button'] ) )
				$this->set_find_reset_button( $_viewParams['find_reset_button'] );
			else
				$this->set_find_reset_button( false );	
		}
	
	} 
	
	public function show_find_field(){
		$buff = '';
		$buff .= '<form action="'.$this->get_page_html().'" method="GET" >';
		$buff .= '<input type="text" name="'.$this->get_input_text_name().'" id="'.$this->get_input_text_name().'" value="'.urldecode($this->get_input_text_value()).'"/>';
		
		if( $this->get_input_submit_value() != false )
			$buff .= $this->get_input_separator().'<input type="submit" name="'.$this->get_input_submit_name().'" id="'.$this->get_input_submit_name().'" value="'.$this->get_input_submit_value().'"/>';
		else
			$buff .= '<input type="hidden" name="'.$this->get_input_submit_name().'" id="'.$this->get_input_submit_name().'" value="true"/>';
		
		if( $this->get_find_reset_button() != false )
			// $buff .= $this->get_input_separator().'<input type="submit" name="reset" value="'.$this->get_reset_button_value().'"/>';
			$buff .= $this->get_input_separator().$this->show_reset_filter();
		
		$buff .= '</form>';
		
		return $buff;
	} 
	public function show_filter_field(){
		$buff = '';
		$buff .= '<form action="'.$this->get_page_html().'" method="GET" >';
		$buff .= '<input type="text" name="'.$this->get_input_text_name().'" id="'.$this->get_input_text_name().'" value="'.$this->get_input_text_value().'">';
		$buff .= $this->get_input_separator();
		$buff .= '<input type="submit" name="'.$this->get_input_submit_name().'" id="'.$this->get_input_submit_name().'" value="'.$this->get_input_submit_value().'">';
		$buff .= '</form>';
		
		return $buff;
	} 
	public function show_reset_filter(){
		$text = '';
		$text .= '<form action="'.$this->get_page_html().'" method="POST">';
		if( $this->get_reset_button_value() == false )
			$text .= '<input type="submit" name="reset" value="Reset"/>';
		else
			$text .= '<input type="submit" name="reset" value="'.$this->get_reset_button_value().'"/>';
			
		$text .= '</form>';
		return $text;
	}
	
	protected function get_input_separator(){
		return $this->input_separator;
	} 
	protected function get_input_submit_value(){
		return $this->input_submit_value;
	} 
	protected function get_input_submit_name(){
		return $this->input_submit_name;
	} 
	protected function get_input_text_value(){
		return $this->input_text_value;
	} 
	protected function get_input_text_name(){
		return $this->input_text_name;	
	} 
	protected function get_reset_button_value(){
		return $this->reset_button_value;
	}
	protected function get_find_reset_button(){
		return $this->find_reset_button;
	}
	
	protected function set_input_separator( $input_separator ){
		$this->input_separator = $input_separator;
	} 
	protected function set_input_submit_value( $input_submit_value ){
		$this->input_submit_value = $input_submit_value;
	} 
	protected function set_input_submit_name( $input_submit_name ){
		$this->input_submit_name = $input_submit_name;
	} 
	protected function set_input_text_value( $input_text_value ){
		$this->input_text_value = $input_text_value;
	} 
	protected function set_input_text_name( $input_text_name ){
		$this->input_text_name = $input_text_name;	
	} 
	protected function set_reset_button_value( $value ){
		$this->reset_button_value = $value;
	}
	protected function set_find_reset_button( $find_reset_button ){
		$this->find_reset_button = $find_reset_button;
	}
	
	// redf de la fontion make_req pour qu'elle réalise la recherche.
	protected function make_req(){
		$reqLine = parent::make_req();
		
		if( $this->get_input_text_value() != '' ){
			$head = $this->get_head();
			
			// Pour etre sur de n'avoir que les colonnes qui ont un 'nom_colonne'
			$temp = array();
			for( $i=0; $i < count( $head ); $i++ ){
				if( isset( $head[$i]['nom_colonne'] ) )
					$temp[] = $head[$i]['nom_colonne'];
			} 
			
			$concat = '';
			for( $i=0; $i < count( $temp ); $i++ ){
				if( isset( $temp[$i] ) ){
					$concat .= $temp[$i];
					
					if( $i < count( $temp ) -1 )
						$concat .= ", ' ', ";
				}
			}
			
			$reqLine .= " WHERE CONCAT_WS( $concat ) LIKE '%".mysql_real_escape_string(urldecode ($this->get_input_text_value()))."%' ";
		}
		
		return $reqLine;
	}

	// redef du manage_get pour qu'il prenne en compte les derniers GET
	protected function manage_get( $get ){
		parent::manage_get( $get );
		if( isset( $get[$this->get_input_submit_name()] ) )
		if( isset( $get[$this->get_input_text_name()] ) )
			$this->set_input_text_value( $get[$this->get_input_text_name()] );
	} 
	protected function get_getline_forfind(){
		return $this->get_input_text_name().'='.$this->get_input_text_value().'&'.$this->get_input_submit_name().'='.$this->get_input_submit_value();
	}
}//php2uml 


?>