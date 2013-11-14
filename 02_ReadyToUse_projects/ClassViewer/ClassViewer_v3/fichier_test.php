<?php

class virtual
{
	private $debug;
	
	public function __construct( $debug = true ){
		$this->debug = $debug;
	} 
	
	public function get_debug(){
		return $this->debug;
	} 
	public function set_debug( $debug ){
		$this->debug = $debug;
	} 

	protected function is_pair( $nombre ){
		$pair = true;
		if ($nombre%2 == 1)
			$pair = false;

		return $pair;
	} 
}//php2uml



// But de cette classe est de simplifier la gestion de l'affichage de base de données.
// - Afficher toute les colonnes avec les données dedans.
// - Trier par ordre croissant ou décroissant l'une des colonnes
// - ne pas afficher toute les colonnes
// - limiter l'affichage à X colonnes et donc faire apparaitre un indicateur de page.
// - Pouvoir tripoter le CSS du tableau ... ;)

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
					echo "ca chie a la connexion!";
			}
		}else{
			$this->bdd_connected = false;
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
	
	protected function connect_bdd(){
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

class structure extends connect{
	
	// Je fait sauter le tableau BODY :
	// Rien de sert de recopier les données de la BDD dans le BODY... un buffering de plus ne fera que ralentir le processus.
	
	private $head; 	// tableau de la forme tab[colonne][description] (nom a afficher)
					//							 	   [nom_colonne] (nom de la colonne dans la bdd que l'ON VEUT AFFICHER)
					//								   [spec]		 ( permet d'afficher un champ personnalisé : #colonne# pour appeler une colonne de BDD)
//	private $_body;	// tableau de la forme tab[ligne][colonne][description] (nom a afficher)
					//							 			  [nom_colonne] (nom de la colonne dans la bdd)
	private $foot;	// tableau de la forme tab[colonne] = description (nom a afficher)
	
	// $_bddParams = Params de connexion a la BDD.
	// $_tableParams = Tableau contenant $_headParams, $_bodyParams, $_footerParams
	public function __construct( $_bddParams = '', $_tableParams = '', $_headParams = '', /* $_bodyParams = '', */ $_footParams = '' ){
		parent::__construct( $_bddParams );
		
		if( isset( $_headParams ) ){
			if( $_headParams != '' ){
				$this->set_head( $_headParams );
			}
		}
		if( isset( $_footParams ) ){
			if( $_footParams != '' ){
				$this->set_foot( $_footParams );
			}
		}
		if( isset( $_tableParams ) ){
			if( $_tableParams != '' ){
				if( isset( $_tableParams["head"] ) )
					$this->set_head( $_tableParams["head"] );
				if( isset( $_tableParams["foot"] ) )
					$this->set_foot( $_tableParams["foot"] );
			}
		}
		
	} 
	
	protected function get_head(){
		// si on est connecté.
		if( $this->get_bdd_connected() )
			// si n'est pas parametré
			if( !$this->get_parametred() ){
				// on se base sur la BDD
				$data = mysql_fetch_array( mysql_query( "SELECT * FROM ".$this->get_bdd_table() ) );
				$i=0;
				$tab = array();
				foreach( $data as $key => $value  ){
					if( !is_numeric( $key ) ){
						$tab[$i]['nom_colonne'] = $key;
						$tab[$i]['description'] = $key;
						$i++;
					}
				} 
				$this->set_head( $tab );
			}
		
		return $this->head;
	} 
	protected function get_foot(){
		return $this->foot;
	} 

	
	protected function set_head( $head ){
		$this->head = $head;
	}
	protected function set_foot( $foot	){
		$this->foot = $foot;
	}
	
 	protected function get_parametred(){
 		// On est obligé d'attaquer la variable THIS direct, si on fait appel a GET_HEAD ... on va tourner en boucle
		
		$parametred = false;
		//$head = $this->get_head();
		if( isset( $this->head ) ){
			if( $this->head != '' )
				$parametred = true;
		}
		return $parametred; 
	}  
}//php2uml

class req_maker extends structure{
// va checker le HEAD pour retrouver les colonnes qui sont a piocher dans la BDD.
// + le champs SPEC qu'il faut dépouiller pour savoir si on irait pas chercher une info dans la BDD également (comme un ID ;)

	protected function make_req(){
		// Préparation de la requete en fonction du head.
		
		// si le head n'est pas défini : on affiche tout
		$head = $this->get_head();
		$req = "SELECT ";
		if( $this->get_parametred() ){
			// on compte le nombre de colonne demandé
			$colonnes = array();
			for( $i=0; $i < count($head); $i++ ){
				if( isset( $head[$i]["nom_colonne"] ) ){
					$colonnes[ $head[$i]["nom_colonne"] ] = true;
				} else if( isset( $head[$i]["spec"] ) ){
					$temp = $this->descript_spec( $head[$i]["spec"] );
					
					for( $j=0; $j < count( $temp ); $j++ ){
						$colonnes[$temp[$j]] = true;
					} 
				} 
			} 
		
			// if( $this->get_debug() )
				// echo "Colonnes de la BDD qu'il faut récup :<br/>";
			$i=0;
			foreach( $colonnes as $key => $value  ){
				$req .= $key;
				if( $i < count( $colonnes )-1 )
					$req .= ', ';
				$i++;
				
				// if( $this->get_debug() )
					// echo "- $key <br/>";
			} 			
		
		}else
			$req .= '*';

		$req .= " FROM ".$this->get_bdd_table();
		
		return $req;
	} 
	
	protected function descript_spec( $spec ){
		// les noms de colonne sont tagués entre []  --> [colonne]
		
		$colonnes = array();
		$regex = "#\[(.+)\]#iUs";
		if( preg_match_all( $regex, $spec, $result ) ){
			/* if( $this->get_debug() ){
				echo "Origine : $spec<br/>Regex: $regex  <br/> Résultats : <br/>";
				for( $i=0; $i < count( $result[1] ); $i++ ){
					echo "- ".$result[1][$i]."<br/>";
				} 
			} */
			
			return $result[1];
		}else{
			if( $this->get_debug() )
				echo "Ca chie :) la regex<br/>";
			return -1;
		}
		
	}
	
}//php2uml

class simpleread extends req_maker{
	// va permettre l'affichage du tableau (titre, head, body, foot)
	
	private $title; // Contient le texte du titre du tableau
	private $title_span; // Contient le nom de la classe qui va "spanner" le Titre.
	private $div_line_head; // Contient le nom de la classe qui va "diver" la ligne de l'entete.
	private $div_line_foot; // Contient le nom de la classe qui va "diver" la ligne du pied de tableau.
	private $div_line_pair; // Contient le nom de la classe qui va "diver" les lignes paires.
	private $div_line_unpair; // Contient le nom de la classe qui va "diver" les lignes impaires.
	private $div_insert_top; // Contient le nom de la classe qui va "diver" les lignes insérées au dessus du tableau.
	private $div_insert_title; // Contient le nom de la classe qui va "diver" les lignes insérées ... dans le titre du tableau.
	private $div_insert_bottom; // Contient le nom de la classe qui va "diver" les lignes impaires insérées sous le tableau..
	private $page_html; // nom de la page html qui detient l'objet. (la page sur laquelle l'user est)
	private $reinsert_header; // réinsère l'entete toute les X lignes. sinon =false.
	private $insert_top; 	// caractères a inserer au dessus du titre.
	private $insert_title; //caractères à inserer entre le titre et le tableau ...; mais dans le titre qd meme ...
	private $insert_bottom; // caractère à inserer en dessous du tableau

	public function __construct( $_bddParams = '', $_tableParams = '', $_viewParams = '' ){
		parent::__construct( $_bddParams, $_tableParams );
		
		if( $_viewParams != '' ){
			if( isset( $_viewParams['title'] ) )
				$this->set_title( $_viewParams['title'] ) ;
			else
				$this->set_title( false );
				
			if( isset( $_viewParams['span_title'] ) )
				$this->set_title_span( $_viewParams['span_title'] ) ;
			else
				$this->set_title_span( 'span_title' );
				
			if( isset( $_viewParams['div_line_pair'] ) )
				$this->set_div_line_pair( $_viewParams['div_line_pair'] ) ;
			else
				$this->set_div_line_pair( 'div_line_pair' );
				
			if( isset( $_viewParams['div_line_unpair'] ) )
				$this->set_div_line_unpair( $_viewParams['div_line_unpair'] ) ;
			else
				$this->set_div_line_unpair( 'div_line_unpair' );
				
			if( isset( $_viewParams['div_line_head'] ) )
				$this->set_div_line_head( $_viewParams['div_line_head'] ) ;
			else
				$this->set_div_line_head( 'div_line_head' );
				
			if( isset( $_viewParams['div_line_foot'] ) )
				$this->set_div_line_foot( $_viewParams['div_line_foot'] ) ;
			else
				$this->set_div_line_foot( 'div_line_foot' );
				
			if( isset( $_viewParams['div_insert_bottom'] ) )
				$this->set_div_insert_bottom( $_viewParams['div_insert_bottom'] ) ;
			else
				$this->set_div_insert_bottom( 'div_insert_bottom' );
				
			if( isset( $_viewParams['div_insert_title'] ) )
				$this->set_div_insert_title( $_viewParams['div_insert_title'] ) ;
			else
				$this->set_div_insert_title( 'div_insert_title' );
				
			if( isset( $_viewParams['div_insert_top'] ) )
				$this->set_div_insert_top( $_viewParams['div_insert_top'] ) ;
			else
				$this->set_div_insert_top( 'div_insert_top' );
				
			if( isset( $_viewParams['reinsert_header'] ) )
				$this->set_reinsert_header( $_viewParams['reinsert_header'] ) ;
			else
				$this->set_reinsert_header( false );
				
			if( isset( $_viewParams['insert_top'] ) )
				$this->set_insert_top( $_viewParams['insert_top'] ) ;
			else
				$this->set_insert_top( false );
				
			if( isset( $_viewParams['insert_title'] ) )
				$this->set_insert_title( $_viewParams['insert_title'] ) ;
			else
				$this->set_insert_title( false );
				
			if( isset( $_viewParams['insert_bottom'] ) )
				$this->set_insert_bottom( $_viewParams['insert_bottom'] ) ;
			else
				$this->set_insert_bottom( false );
		}		
		$this->page_html = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'] ;
	}
	
	protected function get_title(){
		return '<span class="'.$this->title_span.'">'.$this->title.'</span>';
	} 
	protected function get_page_html(){
		return $this->page_html;
	} 
	protected function get_div_line_pair(){
		return $this->div_line_pair;
	} 
	protected function get_div_line_unpair(){
		return $this->div_line_unpair;
	} 
	protected function get_div_line_head(){
		return $this->div_line_head;
	} 
	protected function get_div_line_foot(){
		return $this->div_line_foot;
	} 
	protected function get_div_insert_top(){
		return $this->div_insert_top;
	} 
	protected function get_div_insert_title(){
		return $this->div_insert_title;
	} 
	protected function get_div_insert_bottom(){
		return $this->div_insert_bottom;
	} 
	protected function get_reinsert_header(){
		return $this->reinsert_header;
	} 
	protected function get_insert_top(){
		return $this->insert_top;
	} 
	protected function get_insert_title(){
		return $this->insert_title;
	} 
	protected function get_insert_bottom(){
		return $this->insert_bottom;
	} 
	
	protected function set_title( $title ){
		$this->title = $title;
	} 
	protected function set_title_span( $title_span ){
		$this->title_span = $title_span;
	} 
	protected function set_div_line_pair( $div_line_pair ){
		$this->div_line_pair = $div_line_pair;
	} 
	protected function set_div_line_unpair( $div_line_unpair ){
		$this->div_line_unpair = $div_line_unpair;
	} 
	protected function set_div_line_head( $div_line_head ){
		$this->div_line_head = $div_line_head;
	} 
	protected function set_div_line_foot( $div_line_foot ){
		$this->div_line_foot = $div_line_foot;
	} 
	protected function set_div_insert_top( $div_insert_top ){
		$this->div_insert_top = $div_insert_top;
	} 
	protected function set_div_insert_bottom( $div_insert_bottom ){
		$this->div_insert_bottom = $div_insert_bottom;
	} 
	protected function set_div_insert_title( $div_insert_title ){
		$this->div_insert_title = $div_insert_title;
	} 
	protected function set_reinsert_header( $reinsert_header ){
		$this->reinsert_header = $reinsert_header;
	} 
	protected function set_insert_top( $insert_top ){
		$this->insert_top = $insert_top;
	} 
	protected function set_insert_title( $insert_title ){
		$this->insert_title = $insert_title;
	} 
	protected function set_insert_bottom( $insert_bottom ){
		$this->insert_bottom = $insert_bottom;
	} 
	
	public function add_insert_top( $insert_top ){
		$this->insert_top .= $insert_top;
	} 
	public function add_insert_title( $insert_title ){
		$this->insert_title .= $insert_title;
	} 
	public function add_insert_bottom( $insert_bottom ){
		$this->insert_bottom .= $insert_bottom;
	} 
	
	protected function read_head(){
		// Va afficher juste le head (sans les balises TABLE)
		$buff = '<tr>';
		$head = $this->get_head();
		
		if( $this->get_debug() )
			echo "Count Head : ".count( $head )."<br/>";
			
		for( $i=0; $i < count( $head ); $i++ ){
			$buff .= '<th class="'.$this->get_div_line_head().'"  >'.$head[$i]['description'].'</th>';
		} 
		$buff .= '</tr>';
		
		return $buff;
	} 
	protected function read_foot(){
		// Va afficher juste le foot (sans les balises TABLE)
		$buff = '<tr>';
		
		$foot = $this->get_foot();
		for( $i=0; $i < count( $foot ); $i++ ){
			if( isset( $foot[$i] ) )
				$buff .= '<th class="'.$this->get_div_line_foot().'" >'.$foot[$i].'</th>';
			else
				$buff .= '<th class="'.$this->get_div_line_foot().'" ></th>';
		} 
		$buff .= '</tr>';
		
		return $buff;		
	} 
	protected function read_body(){
		// Si on est connecté et que la classe est initilisée
		if( $this->get_bdd_connected() ){

			$req = $this->make_req();
			
			if( $this->get_debug() )
				echo " Req : $req<br/>";
			
			$result = mysql_query( $req );
			$buff = '';
			$count_line = 0;
			// On fait le tour de la BDD
			while( $data = mysql_fetch_array( $result ) ){
				// réinsère le header toute les X lignes. (si nous ne sommes pas sur la 1ere ligne)
				if( $this->get_reinsert_header() != false )
					if( $count_line%$this->get_reinsert_header() == 0 and $count_line != 0 )
						$buff .= $this->read_head();
			
				$buff .= '<tr >';
				$classe = '';
				// determination de la class a inserer sur cette ligne
				if( $this->is_pair( $count_line++ ) )
					$classe= $this->get_div_line_pair();
				else
					$classe = $this->get_div_line_unpair();				
				
				// On fait le tour des colonnes demandées.
				$head = $this->get_head();
				for( $i=0; $i < count( $head ); $i++ ){
					// a-t-on défini une colonne ds la BDD pour cette colonne?
					if( isset( $head[$i]['nom_colonne'] ) ){
						// si oui alors on affiche le contenu de la BDD
						$colonne = $head[$i]['nom_colonne'];
						// insertion de la bonne classe pour les lignes paires et impaires.
						$buff .= '<td class="'.$classe.'" >'.$data["$colonne"].'</td>';
					}else{
						// sinon, a-t-on un champ spécial pour cette colonne?
						if( isset( $head[$i]['spec'] ) ){
							$pattern = $this->descript_spec( $head[$i]['spec'] );
							
							for( $j=0; $j < count( $pattern ); $j++ ){
								$replace[$j] = $data[ $pattern[$j] ];
							} 
							
							// Formatage des resultat pour que ca preg_replace.
							for( $j=0; $j < count( $pattern ); $j++ ){
								$pattern[$j] = '/\['.$pattern[$j].'\]/';
							} 
							

							$buff .= '<td class="'.$classe.'" >'.preg_replace($pattern, $replace, $head[$i]['spec']).'</td>';
						}else // sinon on n'affiche rien.
							$buff .= '<td class="'.$classe.'" ></td>';
					}
				} 

				$buff .= '</tr>';
			}
			
			return $buff;
		}else{
			if( $this->get_debug() ){
				if( !$this->get_bdd_connected() )
					echo "Erreur ! Il faut d'abord vous connecter : Soit vous n'avez pas fournis de parametres, soit il y a eu une erreur de connexion. Vérifiez les messages plus haut ;)";
			}
			return -1;
		}
	} 
	
	public function read_table(){
	// va ouvrir et fermer le TABLE et appeler les head, body et foot + les slots d'insertion.
		$tab = '';
		if( $this->get_insert_top() != false )
			$tab .= '<div class="'.$this->get_div_insert_top().'">'.$this->get_insert_top()."</div>";
		$tab .= '<table>';
		if( $this->get_title() != false ){
			$tab .= '<caption>';
			$tab .= $this->get_title();
			$tab .= '<div class="'.$this->get_div_insert_title().'">'.$this->get_insert_title()."</div>";
			$tab .= '</caption>';	
		}

		$tab .= $this->read_head();
		$tab .= $this->read_body();
		$tab .= $this->read_foot();
		$tab .= '<table>';
		
		if( $this->get_insert_bottom() != false )
			$tab .= '<div class="'.$this->get_div_insert_bottom().'">'.$this->get_insert_bottom()."</div>";
		
		return $tab;
	} 
}//php2uml

class read_write_file extends simpleread{
	private $filename; // nom du fichier qui sera généré.
	private $filepath; // chemin ou générer le fichier.

	public function __construct( $_bddParams = '', $_tableParams = '', $_viewParams = ''  ){
		parent::__construct( $_bddParams, $_tableParams, $_viewParams);
		
		if( $_viewParams != '' ){
			if( isset( $_viewParams['extract_filename'] ) )
				$this->set_extract_filename($_viewParams['extract_filename']);
			else
				$this->set_extract_filename('extract.csv');
				
			if( isset( $_viewParams['extract_filepath'] ) )
				$this->set_extract_filepath($_viewParams['extract_filepath']);
			else
				$this->set_extract_filepath('.');
		}
	}

	public function get_extract_filename(){
		return $this->filename;
	}
	public function get_extract_filepath(){
		return $this->filepath;
	}
	
	public function set_extract_filename( $filename ){
		$this->filename = $filename;
	}
	public function set_extract_filepath( $filepath ){
		$this->filepath = $filepath;
	}	
	
	protected function read_head( $infile = false ){
		// Va afficher juste le head (sans les balises TABLE)
		$buff = '';
		if( $infile ){
			$head = $this->get_head();
						
			for( $i=0; $i < count( $head ); $i++ ){
				$buff .= $head[$i]['description'].';';
			} 
			$buff .= '
';
		}else{
			$buff = parent::read_head();
		}
		
		return $buff;
	} 
	protected function read_foot( $infile = false ){
		// Va afficher juste le foot (sans les balises TABLE)
		$buff = '';
		if( $infile ){
			$foot = $this->get_foot();
			for( $i=0; $i < count( $foot ); $i++ ){
				if( isset( $foot[$i] ) )
					$buff .= $foot[$i].';';
				else
					$buff .= ';';
			} 
			$buff .= '
';
		}else
			$buff = parent::read_foot();
			
		return $buff;		
	} 
	protected function read_body( $infile = false ){
		$buff = '';
		// Si on est connecté et que la classe est initilisée
		if( $this->get_bdd_connected() ){
			if( $infile ){
			$req = $this->make_req();
			
			if( $this->get_debug() )
				echo " Req : $req<br/>";
			
			$result = mysql_query( $req );
			$count_line = 0;
			// On fait le tour de la BDD
			while( $data = mysql_fetch_array( $result ) ){
				// réinsère le header toute les X lignes. (si nous ne sommes pas sur la 1ere ligne)
				if( $this->get_reinsert_header() != false )
					if( $count_line%$this->get_reinsert_header() == 0 and $count_line != 0 )
						$buff .= $this->read_head( $infile );
			
				// On fait le tour des colonnes demandées.
				$head = $this->get_head();
				for( $i=0; $i < count( $head ); $i++ ){
					// a-t-on défini une colonne ds la BDD pour cette colonne?
					if( isset( $head[$i]['nom_colonne'] ) ){
						// si oui alors on affiche le contenu de la BDD
						$colonne = $head[$i]['nom_colonne'];
						// insertion de la bonne classe pour les lignes paires et impaires.
						// $buff .= $data["$colonne"].';';
						$buff .= str_replace( "\r\n", " ", $data["$colonne"]).';';
					}else{
						// sinon, a-t-on un champ spécial pour cette colonne?
						if( isset( $head[$i]['spec'] ) ){
							$pattern = $this->descript_spec( $head[$i]['spec'] );
							
							for( $j=0; $j < count( $pattern ); $j++ ){
								$replace[$j] = $data[ $pattern[$j] ];
							} 
							
							// Formatage des resultat pour que ca preg_replace.
							for( $j=0; $j < count( $pattern ); $j++ ){
								$pattern[$j] = '/\['.$pattern[$j].'\]/';
							} 
							

							$buff .= preg_replace($pattern, $replace, $head[$i]['spec']).';';
						}else // sinon on n'affiche rien.
							$buff .= ';';
					}
				} 

				$buff .= '
';
			}
			}else
				$buff = parent::read_body();
			
			return $buff;
		}else{
			if( $this->get_debug() ){
				if( !$this->get_bdd_connected() )
					echo "Erreur ! Il faut d'abord vous connecter : Soit vous n'avez pas fournis de parametres, soit il y a eu une erreur de connexion. Vérifiez les messages plus haut ;)";
			}
			return -1;
		}
	} 
	public function read_table( $infile = false ){
	// va ouvrir et fermer le TABLE et appeler les head, body et foot
		$tab = '';
		if( $infile ){
			if( $this->get_debug() )
				echo "In file TRUE!!!<br/>";
				
			if( $this->get_title() != false )
				$tab .= $this->get_title().'
';
			$tab .= $this->read_head( $infile );
			$tab .= $this->read_body( $infile );
			$tab .= $this->read_foot( $infile );
		}else{
			if( $this->get_debug() )
				echo "In file FALSE!!!<br/>";
			$tab = parent::read_table();
		}
		return $tab;
	} 

}//php2uml

class find extends read_write_file{
	// va implémenter la fonction de recherche.
	private $input_text_name;
	private $input_text_value;
	private $input_submit_name;
	private $input_submit_value;
	private $input_separator;
	
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
				
			if( isset( $_viewParams['input_submit_value'] ) )
				$this->set_input_submit_value( $_viewParams['input_submit_value'] );
			else
				$this->set_input_submit_value( 'Rechercher' );
				
			if( isset( $_viewParams['input_submit_name'] ) )
				$this->set_input_submit_name( $_viewParams['input_submit_name'] );
			else
				$this->set_input_submit_name( 'input_submit_name' );
				
			if( isset( $_viewParams['input_separator'] ) )
				$this->set_input_separator( $_viewParams['input_separator'] );
			else
				$this->set_input_separator( ' ' );
		}
	
	} 
	
	public function show_find_field(){
		$buff = '';
		$buff .= '<form action="'.$this->get_page_html().'" method="GET" >';
		$buff .= '<input type="text" name="'.$this->get_input_text_name().'" id="'.$this->get_input_text_name().'" value="'.$this->get_input_text_value().'">';
		$buff .= $this->get_input_separator();
		$buff .= '<input type="submit" name="'.$this->get_input_submit_name().'" id="'.$this->get_input_submit_name().'" value="'.$this->get_input_submit_value().'">';
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

	// redf de la fontion make_req pour qu'elle réalise la recherche.
	protected function make_req(){
		$reqLine = parent::make_req();
		
		if( $this->get_input_text_value() != '' ){
			$head = $this->get_head();
			
			$concat = '';
			for( $i=0; $i < count( $head ); $i++ ){
				if( isset( $head[$i]['nom_colonne'] ) ){
					$concat .= $head[$i]['nom_colonne'];
					
					if( $i < count( $head ) -1 )
						$concat .= ", ' ', ";
				}
			}
			
			$reqLine .= " WHERE CONCAT_WS( $concat )
							LIKE '%".$this->get_input_text_value()."%' ";
		}
		
		return $reqLine;
	}
}//php2uml

class sorted extends find{
	// va permettre de faire des liens sur les entetes de colonne de facon a faire un tri sur cette colonne.
	
	private $sorted; // Permet de savoir si on gère le tri ou pas.
	private $asc; // gère les tri ASCENDANT, = colonne sinon = false;
	private $desc; // gère les tri DESCENDANT, = colonne sinon = false;
	
	public function __construct( $_bddParams = '', $_tableParams = '', $_viewParams = '' ){
		parent::__construct( $_bddParams, $_tableParams, $_viewParams );
		
		if( $_viewParams != '' ){
			if( isset( $_viewParams['sorted'] ) )
				$this->set_sorted( $_viewParams['sorted'] );
			else
				$this->set_sorted( false );
				
			// seulement si le tri est géré qu'on va parametrer ASC et DESC
			if( $this->get_sorted() ){
				if( isset( $_viewParams['asc'] ) )
					$this->set_asc( $_viewParams['asc'] );
				else
					$this->set_asc( false );
					
				if( isset( $_viewParams['desc'] ) )
					$this->set_desc( $_viewParams['desc'] );
				else
					$this->set_desc( false );	
			}
		}
	}
	
	protected function set_asc( $asc ){
		$this->asc = $asc;
	}
	protected function set_desc( $desc ){
		$this->desc = $desc;
	} 
	protected function set_sorted( $sorted ){
		$this->sorted = $sorted;
	} 
	
	protected function get_asc(){
		return $this->asc;
	}
	protected function get_desc(){
		return $this->desc;
	} 
	protected function get_sorted(){
		return $this->sorted;
	} 
	
	// Redef de read_head
	protected function read_head( $infile = false ){
		// Va afficher juste le head (sans les balises TABLE)
		$buff = '';
		
		// doit-on écrire dans un fichier?
		if( $infile ){
			// oui alors pas besoin d'afficher le tri.
			$buff = parent::read_head( $infile );
		}else{
			$buff = '<tr>';
			$head = $this->get_head();
			
			if( $this->get_debug() )
				echo "Count Head : ".count( $head )."<br/>";
				
			for( $i=0; $i < count( $head ); $i++ ){
				$buff .= '<th class="'.$this->get_div_line_head().'" >';
				
				// gestion du tri
				if( $this->get_sorted() ){
					// si nous sommes sur une colonne de type 'BDD' et non une colonne personnalisée.
					if( isset( $head[$i]['nom_colonne'] ) )
						if( $this->get_asc() == $head[$i]['nom_colonne'] ){
							$buff .= '<a href="'.$this->get_page_html().'?desc='.$head[$i]['nom_colonne'];
							// Si on est en mode recherche, alors il faut reposter les params.
							if( $this->get_input_text_value() != false )
								$buff .= '&input_text_name='.$this->get_input_text_value().'&input_submit_name='.$this->get_input_submit_value();
							$buff .= '">';
						}else{
							$buff .= '<a href="'.$this->get_page_html().'?asc='.$head[$i]['nom_colonne'];
							// Si on est en mode recherche, alors il faut reposter les params.
							if( $this->get_input_text_value() != false )
								$buff .= '&input_text_name='.$this->get_input_text_value().'&input_submit_name='.$this->get_input_submit_value();
							$buff .= '">';
						}
				}
				
				// Affichage du texte
				$buff .= $head[$i]['description'];
				
				// fin de gestion du tri
				if( $this->get_sorted() )
					if( isset( $head[$i]['nom_colonne'] ) )
						$buff .= '</a>';
				
				$buff .= '</th>';
			} 
			$buff .= '</tr>';
		}
		
		return $buff;
	} 
	// redef de make_req
	protected function make_req(){
		$buff = parent::make_req();
		
		if( $this->get_sorted() ){
			if( $this->get_asc() != false )
				$buff .= ' ORDER BY '.$this->get_asc().' ASC ';
			else if( $this->get_desc() != false )
				$buff .= ' ORDER BY '.$this->get_desc().' DESC ';
		}
		
		return $buff;
	}

}//php2uml

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
				if( $_viewParams['textpages_top'] )
					$this->add_insert_top($this->get_text_pages());
			
			if( isset( $_viewParams['textpages_title'] ) )
				if( $_viewParams['textpages_title'] )
					$this->add_insert_title($this->get_text_pages());
			
			if( isset( $_viewParams['textpages_bottom'] ) )
				if( $_viewParams['textpages_bottom'] )
					$this->add_insert_bottom($this->get_text_pages());

			
			
			
			
			if( $this->get_debug() )
				echo "Nb Items/Page : ".$_viewParams['pagine']." Nb Pages : ".$this->get_nb_pages().'<br/>';
			
		}
	} 
	
	protected function set_currentpage( $page ){
		// On vérifie si la page demandée est valide?
		if( $page <= $this->get_nb_pages() and $page > 0 )
			$this->currentpage = $page;
		else
			$this->currentpage = 1;
	} 
	protected function set_nb_items_page( $nb_item ){
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
/* 	protected function set_pos_textpages( $textpages_top = '', $textpages_bottom = '' ){
		// si le textpages_top et le textpages_bottom ne sont pas init, on met le textpages_top à true : 
		// s'il y a pagination, il faut obligatoirement que ces N° pages apparaissent quelque part.
		
		// Apres coup ... si on ne veut pas afficher de numéro de page : pourquoi pas.
		// On peut tres bien faire des petits tableau de 5 items par exemple
		// et afficher plusieur fois sur tableau sur une meme page : ainsi on appelle une page différente a chaque fois.
		if( $textpages_top  == '' or $textpages_top == false){
			if( $textpages_bottom == '' or $textpages_bottom  == false )
				$this->pos_textpages['textpages_top'] = false;//true;
			else
				$this->pos_textpages['textpages_top'] = false;
		}else
			$this->pos_textpages['textpages_top'] = $textpages_top;
			
		if( $textpages_bottom  == '')
			$this->pos_textpages['textpages_bottom'] = false;
		else
			$this->pos_textpages['textpages_bottom'] = $textpages_bottom;
	}  */
	protected function set_limit_page_affiche( $limit_page_affiche ){
		$this->limit_page_affiche = $limit_page_affiche;
	} 
	
	protected function get_currentpage(){
		return $this->currentpage;
	} 
	protected function get_nb_items_page(){
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
	protected function get_text_pages(){
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
		if( $this->get_asc() != false or $this->get_desc() != false )
			$text .= '&'.$this->get_stringfororderbyget();
				
		// Attention! si on est sur une rechercher et qu'on......il faut la garder!!!
		if( $this->get_input_text_value() != false )
			$text .= '&'.$this->get_stringforfindget();
					
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
		if( $this->get_asc() != false or $this->get_desc() != false )
			$text .= '&'.$this->get_stringfororderbyget();
				
		// Attention! si on est sur une rechercher et qu'on......il faut la garder!!!
		if( $this->get_input_text_value() != false )
			$text .= '&'.$this->get_stringforfindget();
			
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
		if( $this->get_asc() != false or $this->get_desc() != false )
			$text .= '&'.$this->get_stringfororderbyget();
				
		// Attention! si on est sur une rechercher et qu'on......il faut la garder!!!
		if( $this->get_input_text_value() != false )
			$text .= '&'.$this->get_stringforfindget();
			
		$text .= '">'.$symbole.'</a>';
			
		return $text;
	}
	protected function get_stringforfindget(){
		return 'input_text_name='.$this->get_input_text_value().'&input_submit_name='.$this->get_input_submit_value();
	}
	protected function get_stringfororderbyget(){
		if( $this->get_asc() != false )
			return 'asc='.$this->get_asc();
		else if( $this->get_desc() != false )
			return 'desc='.$this->get_desc();	
	}
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
			
			$req .= $lim_bas.", ".$this->get_nb_items_page();
			}
		// }
		return $req;
	}
	
	// redef de la fonction read_table pour y inserer les N° de pages.
/* 	public function read_table( $infile = false ){
		$tab = '';
		// On récupère l'emplacement du texte concernant le N° de page.
		$pos_textpages = $this->get_pos_textpages();
		// si on est en mode PAGINATION
		if( $this->get_nb_items_page() != false and !$infile ){
			if( $pos_textpages['textpages_top'] )
				$tab .= $this->get_text_pages();
		}
		$tab .= parent::read_table( $infile );
		
		if( $this->get_nb_items_page() != false and !$infile ){
			if( $pos_textpages['textpages_bottom'] )
				$tab .= $this->get_text_pages();
		}
		
		return $tab;
	} */
	
}//php2uml

class extract extends option_page_read{
	private $icone;     // icone ... chaine de caractère utilisée pour etre clicable.
	private $nameget_extract; // nom de la var $_GET qui sera envoyé lorsqu'on clic sur l'icone d'extract
	
	public function __construct( $_bddParams = '', $_tableParams = '', $_viewParams = '' ){
		parent::__construct( $_bddParams, $_tableParams, $_viewParams );
		
		if( isset( $_viewParams['extract'] ) ){ // on met une option : Si 'extract' est défini alors on génère le fichier... qu'importe s'il y a l'affichage ou non de bouton pour télécharger.
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
			
			// On génère le fichier :
			$this->generate_file_extract();
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
	protected function get_extract_icone_linked(){
		// return '<a href="'.$this->get_page_html().'?'.$this->get_nameget_extract().'='.$value.'">'.$this->get_extract_icone().'</a>';
		return '<a href="'.$this->get_extract_filefullpathname().'">'.$this->get_extract_icone().'</a>';
	}
	protected function get_nameget_extract(  ){
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




class phpmytab extends extract{
	// Classe mere.
}//php2uml

?>