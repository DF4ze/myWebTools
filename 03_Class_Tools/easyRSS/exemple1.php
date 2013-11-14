<?php
// ------------------------------------------------------------------------- //
// exemple1.php - cr�er un fichier RSS 0.91 affichant des news par exemple   //
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

// cr�er l'instance
$myrss = new easyRSS();

// on d�clare le channel avec ses �l�ments title, link, description, language, copyright, webMaster
$myrss -> channel("PHPSPIRIT.com", "http://www.phpspirit.com/",
                  "PHPSPIRIT : des scripts PHP de qualit�.", "fr",
                  "� 2002, Philippe RODIER", "webmaster@phpspirit.com");
                  
// on d�clare l'�l�ment image avec title, url, link, width, height, description
$myrss -> image("PHPSPIRIT.com", "http://www.phpspirit.com/images/logobidon.gif",
		"http://www.phpspirit.com/", 88, 31, "Scripts et applications PHP de qualit�");
		
// on d�clare les items avec title, link
$myrss -> add_item("Ceci est la news 1", "http://www.phpspirit.com/?go=news1");	 
$myrss -> add_item("Ceci est la news 2", "http://www.phpspirit.com/?go=news2");
$myrss -> add_item("Ceci est la news 3", "http://www.phpspirit.com/?go=news3");
$myrss -> add_item("Ceci est la news 4", "http://www.phpspirit.com/?go=news4");

// on d�clare l'�l�ment textinput avec title, description, name, link
$myrss -> textinput("Rechercher", "Rechercher sur le site :", "requete",
		    "http://www.phpspirit.com/?go=recherche");
		    
// on cr�er le fichier rss
$myrss -> save("news_phpspirit.rss");
echo "fichier cr��";
exit();

// Bien s�r, on peut extraire les infos d'une bdd contenant les news du site
// et cr�er le fichier rss avec et le de mettre � disposition des personnes
// qui souhaitent afficher vos news sur leur site. C'est le but de la classe easyRSS

?>