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

?>