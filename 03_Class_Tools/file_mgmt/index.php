<?php


 require_once( "file_upload_mgmt.php" );

 $_Params['upfolder'] = 'test/upload';
 $f = new file_upload( $_Params );
 echo "ici?<br/>";
 // echo "Form Name : ".$f->get_form_name()."<br/>";
 if( isset( $_FILES[$f->get_form_name()] ) ){
	// echo "FILES Temp : ".$_FILES[$f->get_form_name()]['tmp_name'].'<br/>';
	// echo "FILES Name : ".$_FILES[$f->get_form_name()]['name'].'<br/>';
	
	$f->set_FILE( $_FILES );
	 echo "ici2?<br/>";
	// $f->set_upfolder( 'test/upload' );
	 echo "ici3?<br/>";
	$f->class_uploaded_file();
	 echo "ici4?<br/>";
	
	$f->treat_file();
	 echo "ici5?<br/>";
	
	echo "file Uploaded : ".$f."<br/>";
}
 
 
 $f->show_form_upload();







?>