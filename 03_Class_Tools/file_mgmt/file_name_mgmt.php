<?php 
/********************************************************************\ 
	ca.ortiz 25/07/2012
	file_name_mgmt.php
	v1
 *********************************************************************
 Gestion des noms de fichiers
 - Initialiser un nom
	- modifier le nom selon les critères:
		- Le chemin
		- Le nom simple
		- l'extension
	le tout avec une analyse des séparateurs de dossier ( '\' ou '/')
 \*******************************************************************/
 
 
require_once( "error_mgmt.php" );

class file_simple extends error_mgmt
{
	private $file;
	
	public function __construct( $file = '' ){
		parent::__construct();
		
		$this->set_file( $file );
	}
	public function __ToString(){
		return $this->get_file();
	}
	
	public function get_file(){
		return $this->file;
	}
	public function set_file( $file ){
		return $this->file = $file;
	}
}

class file_info extends file_simple
{
	protected function get_dirseparator(){
		if( $this->get_file() != '' ){
			$x = strpos( $this->get_file(), '/' );
			if( $x === false ){
				$x = strpos( $this->get_file(), '\\' );
				if( $x !== false )
					return '\\';
				else{
					return $this->set_warning( SEPARATOR_NOTFOUND );
				}
			}else
				return '/';
			
		}else{
			return 	$this->set_error( VAR_FILE_NOTSET, 'file' );
		}
	}
}

class file_detail extends file_info
{
/* 	public function __construct( $file ){
		parent::__construct( $file );
	}
 */	
	public function get_basename(){
		if( $this->get_file() != ''  )
			return pathinfo( $this->get_file(), PATHINFO_BASENAME);
		else{
			$this->set_error( VAR_FILE_NOTSET, 'file' );
			return false;
		}
	}
	public function get_filename(){
		if( $this->get_file() != ''  )
			return pathinfo( $this->get_file(), PATHINFO_FILENAME);
		else{
			$this->set_error( VAR_FILE_NOTSET, 'file' );
			return false;
		}
	}
	public function get_dirname(){
		if( $this->get_file() != ''  )
			return pathinfo( $this->get_file(), PATHINFO_DIRNAME);
		else{
			$this->set_error( VAR_FILE_NOTSET, 'file' );
			return false;
		}
	}
	public function get_extension(){
		if( $this->get_file() != ''  )
			return pathinfo( $this->get_file(), PATHINFO_EXTENSION);
		else{
			$this->set_error( VAR_FILE_NOTSET, 'file' );
			return false;
		}
	}
	
	public function set_basename( $basename ){
		return $this->set_file( $this->get_dirname().$this->get_dirseparator().$basename );
	}
	public function set_extension( $extension ){
		return $this->set_file( $this->get_dirname().$this->get_dirseparator().$this->get_filename().'.'.$extension );
	}
	public function set_dirname( $dirname ){
		$temp = new file_name( $dirname );
		return $this->set_file( $dirname.$temp->get_dirseparator().$this->get_basename() );
	}
	public function set_filename( $filename ){
		return $this->set_file( $this->get_dirname().$this->get_dirseparator().$filename.'.'.$this->get_extension() );
	}
}

class file_cleaner extends file_detail
{
	public function clean_file( $txt ) {
		$txt = strtolower($txt);
		$masque = "[?!]";
		$txt = eregi_replace($masque, "", $txt);

		$masque = "[àâåä@]";
		$txt = eregi_replace($masque, "a", $txt);

		$masque = "[éèêë€]";
		$txt = eregi_replace($masque, "e", $txt);

		$masque = "[ïì]";
		$txt = eregi_replace($masque, "i", $txt);

		$masque = "[ôøö]";
		$txt = eregi_replace($masque, "o", $txt);
		
		$masque = "[œ]";
		$txt = eregi_replace($masque, "oe", $txt);
		
		$masque = "[ùûü]";
		$txt = eregi_replace($masque, "u", $txt);

		$masque = "[ç]";
		$txt = eregi_replace($masque, "c", $txt);

		$masque = "[&]";
		$txt = eregi_replace($masque, "et", $txt);

		$masque = " +";
		$txt = eregi_replace($masque, "_", $txt);

		$masque = "['\"`]";
		$txt = eregi_replace($masque, "_", $txt);

		return( $txt );
	}
}





// Classe maitre
class file_name extends file_cleaner
{
	const VAR_FILE_NOTSET = 1;
	const SEPARATOR_NOTFOUND = 2;
	const FILE_NOTMODIFIED = 3;

	protected function set_tab_error(){	
		parent::set_tab_error();
		
		// echo "Set_Tab_Error de FileName<br/>";
		
		$temp_error[0] = VAR_FILE_NOTSET;
		$temp_error[1] = "La variable {} n'est pas définie";
		$temp_error[2] = "VAR_FILE_NOTSET";
		$this->add_error_code( $temp_error );

		$temp_error[0] = SEPARATOR_NOTFOUND;
		$temp_error[1] = "Pas de séparateur de dossier {} dans la chaine fournie";
		$temp_error[2] = "SEPARATOR_NOTFOUND";
		$this->add_error_code( $temp_error );

		$temp_error[0] = FILE_NOTMODIFIED;
		$temp_error[1] = "L'action demandée {} sur le fichier n'a pu être réalisée";
		$temp_error[2] = "FILE_NOTMODIFIED";
		$this->add_error_code( $temp_error );
	}
}
?>