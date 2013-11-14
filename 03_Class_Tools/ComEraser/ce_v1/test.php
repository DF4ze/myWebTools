<?php 
				$lines = file('test.php');
				/*On parcourt le tableau $lines et on affiche le contenu de chaque ligne précédée de son numéro*/
				foreach ($lines as $lineNumber => $lineContent)
				{
					echo $lineNumber,' ',$lineContent.'<br/>';
				}
?>