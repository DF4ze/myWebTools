<?php


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
				$req .= mysql_real_escape_string($key);
				if( $i < count( $colonnes )-1 )
					$req .= ', ';
				$i++;
				
				// if( $this->get_debug() )
					// echo "- $key <br/>";
			} 			
		
		}else
			$req .= '*';

		$req .= " FROM ".mysql_real_escape_string($this->get_bdd_table());
		
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

?>