<?php
// ------------------------------------------------------------------------- //
// exemple2.php - parser le fichier des news de Phpinfo.net et afficher      //
// ------------------------------------------------------------------------- //
// Copyright (C) 2001-2002  Philippe RODIER <webmaster@phpspirit.com> 	     //
// ------------------------------------------------------------------------- //
// PHPSPIRIT <http://www.phpspirit.com/>			             //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------- //

// la classe easyRSS
include('easyRSS.inc.php');

// créer l'instance
$phpinfo_rss = new easyRSS();

// parser le fichier news sur phpinfo.net et limiter à 20 news max
// soyez connecté au web :)
//$phpinfo_rss -> parsefile("http://www.phpinfo.net/rss/news-phpinfo.rss", 20);
$phpinfo_rss -> parsefile("news-phpinfo.rss", 20);

// on s'amuse à afficher les infos (cad les news) avec une petite mise en page

echo "<table bgcolor=\"#cccccc\" width=\"300\">
        <tr>
          <td height=\"40\">
            <div align=\"center\">
            <a href=\"".$phpinfo_rss -> get_image_link()."\">
            <img src=\"".$phpinfo_rss -> get_image_url()."\" width=\"".$phpinfo_rss -> get_image_width()."\"
             height=\"".$phpinfo_rss -> get_image_height()."\" border=\"0\" 
             alt=\"".$phpinfo_rss -> get_image_title()."\"></a></div>
          </td>
         </tr>
         <tr>
          <td>";
          
$nbnews = $phpinfo_rss -> get_num_items();
$news_title = $phpinfo_rss -> get_items_title();
$news_link = $phpinfo_rss -> get_items_link();

for($i=1;$i<=$nbnews;$i++)
{
	echo "&middot; <a href=\"".$news_link[$i]."\" target=\"_blank\">".$news_title[$i]."</a><br>";
}

echo "</td>
      </tr>";
      
if($phpinfo_rss -> exist_textinput())
{
	echo "<tr>
	      <td>
	        <br>
	        <form  method=\"get\" action=\"".$phpinfo_rss -> get_textinput_link()."\">
	        ".$phpinfo_rss -> get_textinput_description()."&nbsp;
	        <input type=\"text\" name=\"".$phpinfo_rss -> get_textinput_name()."\" 
	        size=\"10\" maxlength=\"10\">&nbsp;<input type=\"submit\" name=\"Submit\" 
	        value=\"".$phpinfo_rss -> get_textinput_title()."\">
	        </form>
	      </td>
	      </tr>";
}	      
          
echo "</table>";
exit();

?>

