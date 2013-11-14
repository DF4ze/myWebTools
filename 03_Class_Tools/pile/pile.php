<?php

// bug PHP reconnu : déclaration de la class doit se trouver au dessus du code.
class pile implements ArrayAccess
{
	private $pile = array();
	private $taille;
	
	public function __construct( $taille ){ 
		//$this->pile = func_get_args();
		$this->taille = $taille;
	}
	
	//////////////////////////////////////////////////
	// Méthodes obligatoire a réimplémenter			//
	public function offsetExists($offset) { 		// En cas d'appel de la fonction isset()
		return isset($this->pile[$offset]);
	}
	public function offsetGet($offset) { 			// En cas d'appel de la valeur
		if( isset( $this->pile[$offset] ) )
			return $this->pile[$offset];
		else
			return null;
	}
	public function offsetSet($offset, $value) { 	// En cas d'affectation de la valeur
		return $this->pile[$offset] = $value;
	}
	public function offsetUnset($offset) { 			// En cas d'appel de la fonction unset()
		unset($this->pile[$offset]);
	}
	//////////////////////////////////////////////////
	
	public function __toString(){ 					// En cas d'un ECHO
		$buff='';
		for( $i=0; $i < count($this->pile); $i++ ){
			$buff .= $this->pile[$i];
		} 
		return $buff;
	}
	
	public function add( $char ){					// ajoute un item dans la pile, supprime le plus vieux si on a atteind $this->taille.
		$kicked='';
		// si on est en dessous du maximum de la pile ... on empile
		if( !$this->is_full() ){
			$this->pile[count( $this->pile )] = $char;
		}else{
			// sinon ... on décalle la pile pour inserer le nouveau char.
			$kicked=$this->pile[0];
			
			for( $i=0; $i < count( $this->pile )-1; $i++ ){
				$this->pile[$i] = $this->pile[$i+1];
			} 
			$this->pile[$i] = $char;
		}
		return $kicked;
	}
	public function is_full(){						// a-t-on atteind la $this->taille?
		$full=false;
		if( count( $this->pile ) >= $this->taille )
			$full = true;
		return $full;
	}
	public function find( $item, $i=0 ){			// retourne l'offset de l'item recherché.
		while( $i < count($this->pile ))
		{
			if( $this->pile[$i] == $item )
				return $i;
			$i ++;
		}
		
		$i = -1;
		return $i;
	}
	public function clear(){						// supprime toute les valeurs
		$this->pile = array();
	}
}

?>