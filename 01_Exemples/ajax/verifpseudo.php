<?php
//
// VERIFICATION EN LIVE DU PSEUDO
//

// CONNECION SQL
mysql_connect("localhost", "root", "");
mysql_select_db("membres");

// VERIFICATION
$result = mysql_query("SELECT pseudo FROM logins WHERE user='".$_GET["pseudo"]."'");
if(mysql_num_rows($result)>=1)
echo "1";
else
echo "2";
?>
