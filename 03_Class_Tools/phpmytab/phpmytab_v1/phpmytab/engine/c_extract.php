<?php


class extract extends option_page_read{
	private $icone;     // icone ... chaine de caract�re utilis�e pour etre clicable.
	private $nameget_extract; // nom de la var $_GET qui sera envoy� lorsqu'on clic sur l'icone d'extract
	
	public function __construct( $_bddParams = '', $_tableParams = '', $_viewParams = '' ){
		parent::__construct( $_bddParams, $_tableParams, $_viewParams );
	
		if( isset( $_viewParams['extract_icone'] ) )
			$this->set_extract_icone( $_viewParams['extract_icone']  );
		else 
			$this->set_extract_icone( 'T�l�charger' );

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
		// Il est donc necessaire de simuler une "non pagination" le temps de l'extract et de rebasculer avec les parametres pr�alablement d�finis.
		
		// R�cup des parametres deja init.
		$pagination = $this->get_nb_items_page();
		// retrait de la pagination
		$this->set_nb_items_page( false );
		
		// on r�cup�re le tableau avec le parametre true pour lui dire qu'on va �crire dans un fichier.
		$table = $this->read_table( true );
		
 		// et on remet les parametres comme ils �taient.
		$this->set_nb_items_page( $pagination );
			
		// On �pure le texte des balises html qui auraient �t� inser�e manuellement.
		$regex = "#<(.+)>#iUs";
		$table = preg_replace($regex, '', $table);

		// On �pure les retour a la ligne
		//$table = str_replace( "\r\n", " ", $table);

		return $table;
	}
	protected function write_file( $text ){
		// if( $this->get_debug() )
			// echo "Chemin du fichier : ".$this->get_extract_filepath()." Nom du fichier : ".$this->get_extract_filename()."<br/>";
			
		$ok = false;
		if( $monfichier = @fopen( $this->get_extract_filefullpathname(), 'w') ){ // Ecriture seulement, cr�er le fichier si n'existe pas.
			fseek($monfichier, 0);
			fputs($monfichier, $text);
			$ok = true;
		}else
			if( $this->get_debug() )
				echo "Erreur �criture du fichier d'extract : V�rifier les droits sur ".$this->get_extract_filefullpathname()."<br/>";
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