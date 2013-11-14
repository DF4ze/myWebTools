<?php 
/********************************************************************\ 
	ca.ortiz 25/07/2012
	error_mgmt.php
	v1
 *********************************************************************
 Gestion des erreurs
 Liste, affiche ou retourne les erreurs/warning de l'application.
 
 \*******************************************************************/
 
 class error_mgmt
 {
	// détient le listing des erreurs
	private $tab_error;
	const ERROR_NOTFOUND = 0;
	
	// Soit pas de debug, soit debug erreurs uniquement, soit debug erreur+warning.
	private $debug_mode;
	const DEBUG_NO = 0;
	const DEBUG_ERROR = 1;
	const DEBUG_WARNING = 2;
	
	// Les erreurs sont affichées ou retournée en parametre.
	private $howtogeterror;
	const ERROR_SHOW = 0;
	const ERROR_ARG = 1;
	
	public function __construct(){
		$this->set_debug_mode( DEBUG_ERROR );
		$this->set_howtogeterror( ERROR_SHOW );
		
		$this->set_tab_error();
	}
		
	public function set_debug_mode( $debug_mode ){
		$this->debug_mode = $debug_mode;
	}
	public function set_howtogeterror( $howtogeterror ){
		$this->howtogeterror = $howtogeterror;
	}
	
	public function set_error( $error_id, $specification = '' ){
		// Si debug mode activé pour les erreurs?
		if( $this->debug_mode >= DEBUG_ERROR){
			// On cherche l'error_id dans le tableau
			for( $i = 0; $i < count( $this->tab_error ); $i++ ){
				if( $this->tab_error[$i][0] == $error_id ){
					// On ajoute la spécification dans le message initial
					$temp_tab_error[0] = $this->tab_error[$i][0];
					$temp_tab_error[1] = $this->formatting_error_msg( "Error ".$this->tab_error[$i][0]." : ".$this->tab_error[$i][1], $specification );
					$temp_tab_error[2] = $this->tab_error[$i][2];
					$temp_tab_error[3] = $specification;
					
					// Est-ce qu'on affiche ... ou on retourne?
					if( $this->howtogeterror == ERROR_SHOW )
						echo $this->formatting_error_msg( "Error ".$this->tab_error[$i][0]." : ".$this->tab_error[$i][1], $specification );
					
					return $temp_tab_error;
					
				}
			}
			// Si on n'a pas trouvé le numéro d'erreur demandé.
			if( $i >= count( $this->tab_error ) ){
				if( $this->howtogeterror == ERROR_SHOW ){
					echo $this->formatting_error_msg( "Error ".$this->tab_error[0][0]." : ".$this->tab_error[0][1], $error_id  );
				}else{
					// On ajoute la spécification dans le message initial
					$temp_tab_error[0] = $this->tab_error[$i][0];
					$temp_tab_error[1] = $this->formatting_error_msg( "Error ".$this->tab_error[0][0]." : ".$this->tab_error[0][1], $error_id );
					$temp_tab_error[2] = $this->tab_error[$i][2];
					$temp_tab_error[3] = $specification;
					
					return $temp_tab_error;
				}
			}
		}
		return false;
	}
	public function set_warning( $warning_id, $specification = '' ){
		// Si debug mode activé pour les erreurs?
		if( $this->debug_mode >= DEBUG_WARNING){
			// On cherche l'warning_id dans le tableau
			for( $i = 0; $i < count( $this->tab_error ); $i++ ){
				if( $this->tab_error[$i][0] == $warning_id ){
					// Est-ce qu'on affiche ... ou on retourne?
					if( $this->howtogeterror ){
						echo $this->formatting_error_msg( "Warning N°".$warning_id." : ".$this->tab_error[$i][1], $specification );
					}else{
						// On ajoute la spécification dans le message initial
						$temp_tab_error[0] = $this->tab_error[$i][0];
						$temp_tab_error[1] = $this->formatting_error_msg( "Warning N°".$warning_id." : ".$this->tab_error[$i][1], $specification );
						$temp_tab_error[2] = $this->tab_error[$i][2];
						$temp_tab_error[3] = $specification;
						
						return $temp_tab_error;
					}
				}
			}
		}
		return false;
	}
	
	// Fonction qui va lister les erreurs et leur message.
	// Si {} apparait dans le msg d'erreur, il pourra etre remplacé par une précision sur l'incident.
	// Doit etre redef dans chaque class s'il y a besoin d'ajouter un msg d'erreur.
	protected function set_tab_error(){
		$temp_error[0] = ERROR_NOTFOUND;
		$temp_error[1] = "Le code erreur demandé {} n'existe pas";
		$temp_error[2] = "ERROR_NOTFOUND";
		$this->add_error_code( $temp_error );
	}
	protected function add_error_code( $tab ){
		// echo "Set_Tab_Error de error_mgmt<br/>";
			$this->tab_error[ count( $this->tab_error ) ] = $tab;
	}
	private function formatting_error_msg( $error_msg, $specification ){
		return str_replace( "{}", $specification, $error_msg )."<br/>";
	}

	public function show_tab_error(){
		foreach( $this->tab_error as $key => $value ){
			echo "<strong>Code erreur : </strong>".$value[0]." <strong>Message : </strong>".$value[1]." <strong>Var : </strong>".$value[3]."<br/>";
		}
	}
	
 }
 
 ?>