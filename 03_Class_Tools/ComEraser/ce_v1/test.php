<?php 
				$lines = file('test.php');
				/*On parcourt le tableau $lines et on affiche le contenu de chaque ligne pr�c�d�e de son num�ro*/
				foreach ($lines as $lineNumber => $lineContent)
				{
					echo $lineNumber,' ',$lineContent.'<br/>';
				}
?>