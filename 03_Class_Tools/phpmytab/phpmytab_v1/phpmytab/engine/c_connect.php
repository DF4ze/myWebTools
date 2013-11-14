<?php
class connect extends virtual{

	private $bdd_srv;
	private $bdd_user;
	private $bdd_pwd;
	private $bdd_base;
	private $bdd_table;
	private $bdd_connected;

	public function __construct( $mysql_Params = '' ){
		parent::__construct();
		
		$this->bdd_connected = false;
		
		if( $mysql_Params != "" ){
			$this->set_bdd_base( $mysql_Params['base'] );
			$this->set_bdd_srv( $mysql_Params['srv'] );
			$this->set_bdd_user( $mysql_Params['user'] );
			$this->set_bdd_pwd( $mysql_Params['pwd'] );
			$this->set_bdd_table( $mysql_Params['table'] );
			
			if( $this->connect_bdd() ){
				$this->bdd_connected = true;
			}else{
				if( $this->get_debug() )
					echo "ca chie a la connexion!<br/>";
			}
		}else{
			//$this->bdd_connected = false;
			if( $this->get_debug() )
				echo "Pas de Params...";
		}
		return $this->get_bdd_connected();
	} 
	public function __destroy(){
		mysql_close();
	} 
	
	protected function get_bdd_srv(){
		return $this->bdd_srv;
	} 
	protected function get_bdd_user(){
		return $this->bdd_user;
	} 
	protected function get_bdd_pwd(){
		return $this->bdd_pwd;
	} 
	protected function get_bdd_base(){
		return $this->bdd_base;
	} 
	protected function get_bdd_table(){
		return $this->bdd_table;
	} 
	protected function get_bdd_connected(){
		return $this->bdd_connected;
	} 
	
	protected function set_bdd_srv( $srv ){
		$this->bdd_srv = $srv;
	} 
	protected function set_bdd_user( $user ){
		$this->bdd_user = $user;
	} 
	protected function set_bdd_pwd( $pwd ){
		$this->bdd_pwd = $pwd;
	} 
	protected function set_bdd_base( $base ){
		$this->bdd_base = $base;
	} 
	protected function set_bdd_table( $table ){
		$this->bdd_table = $table;
	} 
	
	public function connect_bdd(){
		$succes = false;
		
		// On vérif si on est pas deja connecté
		if( !$this->bdd_connected ){
			// On ne vérif pas tt les parametres ... mais il faut au moins que le serveur soit renseigner : rien ne sert de se connecter avec des parametres vides ;)
			if( $this->get_bdd_srv() != '' ){
				// Si connexion au serveur OK
				if( @mysql_connect( $this->get_bdd_srv(), $this->get_bdd_user(), $this->get_bdd_pwd() )){
					if( $this->get_debug() ) 
						echo "Connexion au Serveur MySQL ".$this->get_bdd_srv()." avec succes.<br/>";
					// Alors on lance la connexion à la base.
					mysql_select_db( $this->get_bdd_base() ) or die ("Connexion à la base MySql ".$this->get_bdd_base()." impossible");
					if( $this->get_debug() )
						echo "Connexion a la base MySQL ".$this->get_bdd_base()." avec succes.<br/>";
					$succes = true;
				}else
					die( "Erreur de connexion au serveur MySql ".$this->get_bdd_base()."." );
			}
		}
		return $succes;
	} 
	
}//php2uml 
?>