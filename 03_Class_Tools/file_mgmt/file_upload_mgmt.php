<?php

/********************************************************************\ 
	ca.ortiz 25/07/2012
	file_upload_mgmt.php
	v1
 *********************************************************************
 Gestion des uploads
	fournir le fichier receptionner
	- filtrage sur l'extension
	- le déplacera dans le dossier souhaité avec un post/préfixe souhaité.
 \*******************************************************************/
 
require_once( "file_system_mgmt.php" );

// Va permettre de récupérer un fichier uploadé
// et le ranger dans le dossier d'upload.
class up_class extends file_system
{
	private $upfolder; // chemin relatif
	
	public function __construct( $_Params = '' ){
		parent::__construct( $_Params );
		
		if( isset( $_Params['upfolder'] ) )
			$this->set_upfolder( $_Params['upfolder'] );
		else
			$this->set_upfolder( '' );
			
	}
	
	public function set_upfolder( $upfolder ){
		$this->upfolder = $upfolder;
	}
	public function get_upfolder(){
		return $this->upfolder;
	}

}

class class_uploaded extends up_class
{
	private $FILE;
	private $form_name;
	
	public function __construct( $_Params = '' ){
		parent::__construct( $_Params );
			
		if( isset( $_Params['form_name'] ) )
			$this->set_form_name( $_Params['form_name'] );
		else
			$this->set_form_name( 'file_form' );
			
		if( isset( $_Params[$this->get_form_name()] ) )
			$this->FILE = $_Params[ $this->get_form_name() ];
		else
			$this->FILE = '';
	}
	
	public function set_form_name( $form_name ){
		if( $form_name != '' ){
			$this->form_name = $form_name;
			return true;
		}
		return false;
	}
	public function get_form_name( ){
		return $this->form_name;
	}
	
	public function set_FILE( $FILE ){
		if( $FILE != '' ){
			$this->FILE = $FILE[ $this->get_form_name() ];
			return true;
		}
		return false;
	}
	public function get_FILE(){
		return $this->FILE;
	}
	
	public function class_uploaded_file(){
		if( $this->get_upfolder() != '' ){
			if( $this->FILE != '' ){
				// On récupère le définitif du fichier
				$tmp = new file_name( $this->FILE['name'] );
				// On modifie pour qu'il soit dans le dossier "Upload"
				if( $tmp->set_dirname( $this->get_upfolder() ) ){
					// On déplace le fichier.
					if( move_uploaded_file( $this->FILE['tmp_name'], $tmp ) ){
						// On s'attribu le nouveau nom de fichier
						return $this->set_file( $tmp );	
					}else
						return $this->set_error( FILE_NOTMODIFIED, '"move_uploaded_file"' );
				}
			}else
				return $this->set_error( PATH_NOTINIT, '"$_FILES"' );
		}else
			return $this->set_error( PATH_NOTINIT, '"upload"' );
	}
	public function treat_file(){
		// nettoyage du nom du fichier
		$this->clean_file();
		// Application du préfixe
		$this->apply_fix();
	}
	
	public function relative_to_absolute_folder( $relativefolder ){
		if( $this->upfolder != '' ){
			$absolutefolder = new file_name( "http://".$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'] );
			$absolutefolder->set_basename( $relativefolder );
			
			return $absolutefolder;
		}else
			return $this->set_error( PATH_NOTINIT, '"upload"' );
	}

}

class show_html extends class_uploaded
{
	public function show_form_upload(){
		echo '<form enctype="multipart/form-data" action="'.$_SERVER['PHP_SELF'].'" method="post" >';
			//echo '<!--input type="hidden" name="MAX_FILE_SIZE" value="100000000000" /--> <!-- limité à 10 mo-->';
			echo '<input type="file" name="'.$this->get_form_name().'" />';
			echo '<input type="submit" value="Envoyer"/>';
		echo '</form>';
	}
}

// class maitre
class file_upload extends show_html
{
	const PATH_NOTINIT = 4;
	
	protected function set_tab_error(){	
		parent::set_tab_error();
		
		$temp_error[0] = PATH_NOTINIT;
		$temp_error[1] = "Le chemin {} n'a pas été initialisé";
		$temp_error[2] = "PATH_NOTINIT";
		$this->add_error_code( $temp_error );

		// $temp_error[0] = FILE_NOTMODIFIED;
		// $temp_error[1] = "L'action demandée {} sur le fichier n'a pu être réalisée";
		// $temp_error[2] = "FILE_NOTMODIFIED";
		// $this->add_error_code( $temp_error );
	}
	
	public function set_params( $_Params ){
		if( isset( $_Params['file'] ) )
			$this->set_file( $_Params['file'] );
		
	}
}
?>