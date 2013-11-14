	<script type="text/javascript">
	function auto_calcul( valeur, resultat )
	{
		document.getElementById(resultat).value = document.getElementById(valeur).value.length;
	}
	</script>
	
	<?php	
	echo '<input type="text" name="string" id="string" onKeyup="auto_calcul( \'string\', \'result\' );" style="width:235px;"/>';
	echo '<br/>Il y a <input type="text" name="result" id="result" disabled="disabled" style="width:27px;"/> caractères dans cette chaine.';
	?>