<?php


class extract extends option_page_read{
	private $icone;     // icone ... chaine de caractère utilisée pour etre clicable.
	private $nameget_extract; // nom de la var $_GET qui sera envoyé lorsqu'on clic sur l'icone d'extract
	
	public function __construct( $_bddParams = '', $_tableParams = '', $_viewParams = '' ){
		parent::__construct( $_bddParams, $_tableParams, $_viewParams );
	
		if( isset( $_viewParams['extract_icone'] ) )
			$this->set_extract_icone( $_viewParams['extract_icone']  );
		else 
			$this->set_extract_icone( 'Télécharger' );

		if( isset( $_viewParams['extract_icone_pos'] ) ){
			switch( $_viewParams['extract_icone_pos'] ) {
				case 'top' : $this->add_insert_top( $this->get_extract_icone_linked()  ); break;
				case 'title' : $this->add_insert_title( $this->get_extract_icone_linked()  ); break;
				case 'bottom' : $this->add_insert_bottom( $this->get_extract_icone_linked()  ); break;
				default : 	$this->add_insert_top( false  ); 
							$this->add_insert_title( false ); 
							$this->add_insert_bottom( false );
							break;
			}
		}
	}
	
	protected function set_extract_icone( $icone ){
		$this->icone = $icone;
	} 
	protected function set_nameget_extract( $nameget_extract ){
		$this->nameget_extract = $nameget_extract;
	} 
	
	protected function get_extract_icone(){
		return $this->icone;
	} 
	public function get_extract_icone_linked(){
		// return '<a href="'.$this->get_page_html().'?'.$this->get_nameget_extract().'='.$value.'">'.$this->get_extract_icone().'</a>';
		return '<a href="'.$this->get_extract_filefullpathname().'">'.$this->get_extract_icone().'</a>';
	}
	public function get_nameget_extract(  ){
		return $this->nameget_extract;
	} 
	protected function get_extract_filefullpathname(){
		return $this->get_extract_filepath().'/'.$this->get_extract_filename();
	}
	
	protected function generate_text_extract(){
		// Nous voulons une extract totale et non une extract de la page en cours.
		// Il est donc necessaire de simuler une "non pagination" le temps de l'extract et de rebasculer avec les parametres préalablement définis.
		
		// Récup des parametres deja init.
		$pagination = $this->get_nb_items_page();
		// retrait de la pagination
		$this->set_nb_items_page( false );
		
		// on récupère le tableau avec le parametre true pour lui dire qu'on va écrire dans un fichier.
		$table = $this->read_table( true );
		
 		// et on remet les parametres comme ils étaient.
		$this->set_nb_items_page( $pagination );
			
		// On épure le texte des balises html qui auraient été inserée manuellement.
		$regex = "#<(.+)>#iUs";
		$table = preg_replace($regex, '', $table);

		// On épure les retour a la ligne
		//$table = str_replace( "\r\n", " ", $table);

		return $table;
	}
	protected function write_file( $text ){
		// if( $this->get_debug() )
			// echo "Chemin du fichier : ".$this->get_extract_filepath()." Nom du fichier : ".$this->get_extract_filename()."<br/>";
			
		$ok = false;
		if( $monfichier = @fopen( $this->get_extract_filefullpathname(), 'w') ){ // Ecriture seulement, créer le fichier si n'existe pas.
			fseek($monfichier, 0);
			fputs($monfichier, $text);
			$ok = true;
		}else
			if( $this->get_debug() )
				echo "Erreur écriture du fichier d'extract : Vérifier les droits sur ".$this->get_extract_filefullpathname()."<br/>";
		return $ok;
	}
	public function generate_file_extract(){
		$ok = false;
		if( $this->write_file( $this->generate_text_extract() ) )
			$ok = true;
		return $ok;
	}
}//php2uml 


?>