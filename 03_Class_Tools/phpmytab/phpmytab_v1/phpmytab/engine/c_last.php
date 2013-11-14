<?php

class last extends extract{
	public function __construct($_bddParams = '', $_tableParams = '', $_viewParams = '' ){
		// Initialisation de la classe.
		parent::__construct( $_bddParams, $_tableParams, $_viewParams );
		
		// Modularisation en fonction  des parametres $_GET
		$this->auto_manage_get();
		
		// Génération de l'extract si demandé.
		if( isset( $_viewParams['extract'] ) )
			if( $_viewParams['extract'] )
			$this->generate_file_extract();

	}
}

?>