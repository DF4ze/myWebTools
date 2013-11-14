<?php

class myClass {
    public static $classGlobal = "Default Value<br/>" ;

    public function doStuff($inVar) {
        myClass::$classGlobal = $inVar ;
    }
	
	public function get_global(){
		return myClass::$classGlobal;
	}
}

$class = new myClass();
echo $class->get_global();
$class->doStuff( "Not Default Value<br/>" );
echo $class->get_global();

$class2 = new myClass();
echo $class->get_global();

?>