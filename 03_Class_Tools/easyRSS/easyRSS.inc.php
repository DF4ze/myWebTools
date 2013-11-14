<?php
// ------------------------------------------------------------------------- //
// easyRSS - classe pour créer et parser des fichiers RSS 0.91		     //
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

class easyRSS
{
	var $classversion = 1.3;
	var $rss_version = 0.91;
	
	var $languages = array("af", "sq", "eu", "be", "bg", "ca", "zh-cn", "zh-tw", "hr",
	                       "cs", "da", "nl", "nl-be", "nl-nl", "en", "en-au", "en-bz",
	                       "en-ca", "en-ie", "en-jm", "en-nz", "en-ph", "en-za", "en-tt",
	                       "en-gb", "en-us", "en-zw", "fo", "fi", "fr", "fr-be", "fr-ca",
	                       "fr-fr", "fr-lu", "fr-mc", "fr-ch", "gl", "gd", "de", "de-at",
	                       "de-de", "de-li", "de-lu", "de-ch", "el", "hu", "is", "in", "ga",
	                       "it", "it-it", "it-ch", "ja", "ko", "mk", "no", "pl", "pt", "pt-br",
	                       "pt-pt", "ro", "ro-mo", "ro-ro", "ru", "ru-mo", "ru-ru", "sr", "sk",
	                       "sl", "es", "es-ar", "es-bo", "es-cl", "es-co", "es-cr", "es-do",
	                       "es-ec", "es-sv", "es-gt", "es-hn", "es-mx", "es-ni", "es-pa",
	                       "es-py", "es-pe", "es-pr", "es-es", "es-uy", "es-ve", "sv", "sv-fi",
	                       "sv-se", "tr", "uk");
	
	var $itemprogress = 0;
	var $itemcount = 0;
	                       
	var $rss_channel_title = '';
	var $rss_channel_link = '';
	var $rss_channel_description = '';
	var $rss_channel_language = '';
	var $rss_channel_copyright = '';
	var $rss_channel_webMaster = '';
	var $rss_image_title = '';
	var $rss_image_url = '';
	var $rss_image_link = '';
	var $rss_image_width = '';
	var $rss_image_height = '';
	var $rss_image_description = '';			
	var $rss_item_title = array();
	var $rss_item_link = array();
	var $rss_textinput_title = '';
	var $rss_textinput_description = '';
	var $rss_textinput_name = '';
	var $rss_textinput_link = '';
	 
	var $rss_channel = false;
	var $rss_image = false;
	var $rss_items = false;
	var $rss_textinput = false;
	
	function exist_channel()
	{
		return $this -> rss_channel;
	}
	
	function exist_image()
	{
		return $this -> rss_image;
	}
	
	function exist_items()
	{
		return $this -> rss_items;
	}
	
	function exist_textinput()
	{
		return $this -> rss_textinput;
	}
	
	function easyRSS()
	{
		$this -> itemprogress = 1;
	}
	
	function channel($title, $link, $description, $language, $copyright, $webmaster)
	{
		if(isset($title) && !empty($title) && strlen($title) <= 100)
		{
			$this -> rss_channel_title = $title;
		}
		else
		{
			die("channel : erreur sur le 1er argument <u>title</u> <i>$title</i>");
		}
		
		if(isset($link) && !empty($link) && strlen($link) <= 500)
		{
			$this -> rss_channel_link = $link;
		}
		else
		{
			die("channel : erreur sur le 2nd argument <u>link</u> <i>$link</i>");
		}
		
		if(isset($description) && !empty($description) && strlen($description) <= 500)
		{
			$this -> rss_channel_description = $description;
		}
		else
		{
			die("channel : erreur sur le 3ème argument <u>description</u> <i>$description</i>");
		}
		
		if(isset($language) && !empty($language) && in_array($language, $this -> languages))
		{
			$this -> rss_channel_language = $language;
		}
		else
		{
			die("channel : erreur sue le 4ème argument <u>language</u> <i>$language</i>");
		}
		
		if(isset($copyright) && !empty($copyright) && strlen($copyright) <= 100)
		{
			$this -> rss_channel_copyright = $copyright;
		}
		else
		{
			die("channel : erreur sue le 5ème argument <u>copyright</u> <i>$copyright</i>");
		}
		
		if(isset($webmaster) && !empty($webmaster) && strlen($webmaster) <= 100)
		{
			$this -> rss_channel_webMaster = $webmaster;
			$this -> rss_channel = true;
		}
		else
		{
			die("channel : erreur sur le 6ème argument <u>webmaster</u> <i>$webmaster</i>");
		}
	}
	
	function image($title, $url, $link, $width = 88, $height = 31, $description)
	{
		if(!isset($title) || empty($title))
		{
			$this -> rss_image_title = $this -> rss_channel_title;
		}
		elseif (isset($title) && !empty($title) && strlen($title) <= 100)
		{
			$this -> rss_image_title = $title;
		}
		else
		{
			die("image : erreur sur le 1er argument <u>title</u> <i>$title</i>");
		}
		
		if(isset($url) && !empty($url) && strlen($url) <= 500)
		{
			$this -> rss_image_url = $url;
		}
		else
		{
			die("image : erreur sur le 2nd argument <u>url</u> <i>$url</i>");
		}
		
		if(!isset($link) || empty($link))
		{
			$this -> rss_image_link = $this -> rss_channel_link;
		}
		elseif (isset($link) && !empty($link) && strlen($link) <= 500)
		{
			$this -> rss_image_link = $link;
		}
		else
		{
			die("image : erreur sur le 3ème argument <u>link</u> <i>$link</i>");
		}
		
		if($width <= 144)
		{
			$this -> rss_image_width = $width;
		}
		else
		{
			die("image : erreur sur le 4ème argument <u>width</u> <i>$width</i>");
		}
		
		if($height <= 400)
		{
			$this -> rss_image_height = $height;
		}
		else
		{
			die("image : erreur sur le 5ème argument <u>height</u> <i>$height</i>");
		}
		
		if(isset($description) && !empty($description) && strlen($description) <= 500)
		{
			$this -> rss_image_description = $description;
			$this -> rss_image = true;
		}
		else
		{
			die("image : erreur sur le 6ème argument <u>description</u> <i>$description</i>");
		}
	}
	
	function add_item($title, $link)
	{
		
		if(isset($title) && !empty($title) && strlen($title) <= 100)
		{
			$this -> rss_item_title[$this -> itemprogress] = $title;
		}
		else
		{
			die("add_item n°".$this -> itemprogress." : erreur sur le 1er argument <u>title</u> <i>$title</i>");
		}
		
		if(isset($link) && !empty($link) && strlen($link) <= 500)
		{
			$this -> rss_item_link[$this -> itemprogress] = $link;
			$this -> itemprogress++;
			$this -> itemcount = count($this -> rss_item_title);
			$this -> rss_items = true;
		}
		else
		{
			die("add_item n°".$this -> itemprogress." : erreur sur le 1er argument <u>title</u> <i>$title</i>");
		}
	}
	
	function textinput($title, $description, $name, $link)
	{
		if(isset($title) && !empty($title) && strlen($title) <= 100)
		{
			$this -> rss_textinput_title = $title;
		}
		else
		{
			die("textinput : erreur sur le 1er argument <u>title</u> <i>$title</i>");
		}
		
		if(isset($description) && !empty($description) && strlen($description) <= 500)
		{
			$this -> rss_textinput_description = $description;
		}
		else
		{
			die("textinput : erreur sur le 2nd argument <u>description</u> <i>$description</i>");
		}
		
		if(isset($name) && !empty($name) && strlen($name) <= 20)
		{
			$this -> rss_textinput_name = $name;
		}
		else
		{
			die("textinput : erreur sur le 3ème argument <u>name</u> <i>$name</i>");
		}
		
		if(isset($link) && !empty($link) && strlen($link) <= 500)
		{
			$this -> rss_textinput_link = $link;
			$this -> rss_textinput = true;
		}
		else
		{
			die("textinput : erreur sur le 4ème argument <u>link</u> <i>$link</i>");
		}
	}
	
	function is_correct()
	{
		if($this -> rss_channel == false) die("L'élément <b>channel</b> est manquant !");
		if($this -> rss_image == false) die("L'élément <b>image</b> est manquant !");
		if($this -> rss_items == false) die("Au moins un élément <b>item</b> est nécessaire !");
	}
	
	function generate()
	{
		$this -> is_correct();
		$rss_content = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>\n";
		$rss_content.= "<!DOCTYPE rss PUBLIC \"-//Netscape Communications//DTD RSS ".$this -> rss_version."//EN\"\n";
		$rss_content.= "\"http://my.netscape.com/publish/formats/rss-0.91.dtd\">\n";

		$rss_content.= "<rss version=\"".$this -> rss_version."\">\n";
		$rss_content.= "  <channel>\n";
		$rss_content.= "    <title>".$this -> rss_channel_title."</title>\n";
		$rss_content.= "    <link>".$this -> rss_channel_link."</link>\n";
		$rss_content.= "    <description>".$this -> rss_channel_description."</description>\n";
		$rss_content.= "    <language>".$this -> rss_channel_language."</language>\n";
		$rss_content.= "    <copyright>".$this -> rss_channel_copyright."</copyright>\n";
		$rss_content.= "    <webMaster>".$this -> rss_channel_webMaster."</webMaster>\n";
		$rss_content.= "    <image>\n";
		$rss_content.= "      <title>".$this -> rss_image_title."</title>\n";
		$rss_content.= "      <url>".$this -> rss_image_url."</url>\n";
		$rss_content.= "      <link>".$this -> rss_image_link."</link>\n";
		$rss_content.= "      <width>".$this -> rss_image_width."</width>\n";
		$rss_content.= "      <height>".$this -> rss_image_height."</height>\n";
		$rss_content.= "      <description>".$this -> rss_image_description."</description>\n";
		$rss_content.= "    </image>\n";
		
		for($i=1;$i<=$this -> itemcount;$i++)
		{
			$rss_content.= "    <item>\n";
			$rss_content.= "      <title>".$this -> rss_item_title[$i]."</title>\n";
			$rss_content.= "      <link>".$this -> rss_item_link[$i]."</link>\n";
			$rss_content.= "    </item>\n";
		}
		
		if($this -> rss_textinput == true)
		{
			$rss_content.= "    <textinput>\n";
			$rss_content.= "      <title>".$this -> rss_textinput_title."</title>\n";
			$rss_content.= "      <description>".$this -> rss_textinput_description."</description>\n";
			$rss_content.= "      <name>".$this -> rss_textinput_name."</name>\n";
			$rss_content.= "      <link>".$this -> rss_textinput_link."</link>\n";
			$rss_content.= "    </textinput>\n";
		}
		
		$rss_content.= "  </channel>\n";
		$rss_content.= "</rss>";
		
		return $rss_content;
	}
	
	function as_string()
	{
		return $this -> generate();
	}
	
	function save($filename)
	{
		$fd = fopen($filename, "w");
		fputs($fd, $this -> generate());
		fclose($fd);
	}
	
	function parsefile($filename, $maxitem = 1)
	{
		$lines = file($filename) or die("Le fichier <i>$filename</i> n'est pas accessible !");
		if(!gettype($lines) == "array") return;
		
		reset($lines);
		
		while(list($num, $line) = each($lines))
		{
			$line = trim(chop($line));
			if(preg_match("/<channel>/", $line)) continue;
			if(preg_match("/<\/channel>|<image>|<item>|<textinput>/", $line)) break;
						
			while (list($num, $line) = each($lines))
			{ 
				$this -> rss_channel = true;
        			$line = trim(chop($line));
        			if(empty($line) || $line[0] != "<") continue;
        			if(preg_match("/<\/channel>|<image>|<item>|<textinput>/", $line)) break 2;
        			
        			switch(true)
        			{
        				case preg_match("/<title>/", $line):
        					$this -> rss_channel_title = trim(strip_tags($line));
        					break;
            				case preg_match("/<link>/", $line):
            					$this -> rss_channel_link = trim(strip_tags($line));
        					break;
        				case preg_match("/<description>/", $line):
            					$this -> rss_channel_description = trim(strip_tags($line));
        					break;
        				case preg_match("/<language>/", $line):
            					$this -> rss_channel_language = trim(strip_tags($line));
        					break;
        				case preg_match("/<copyright>/", $line):
            					$this -> rss_channel_copyright = trim(strip_tags($line));
        					break;
        				case preg_match("/<webMaster>/", $line):
            					$this -> rss_channel_webMaster = trim(strip_tags($line));
        					break;
        			}
			}
		}
		
		reset($lines);
		 
		while (list($num, $line) = each($lines))
		{
			$line = trim(chop($line));
		      	if(preg_match("/<\/image>/",  $line)) break;
		      	if(!preg_match("/<image>/", $line)) continue;
		      
		      	while (list($num, $line) = each($lines))
		      	{ 
		      		$this -> rss_image = true;
		        	$line = trim(chop($line));
		        	if(empty($line) || $line[0] != "<") continue;
		                if(preg_match("/<\/image>/", $line)) break 2;
		       
		        	switch(true)
		        	{
		        		case preg_match("/<title>/", $line):
		            			$this -> rss_image_title = trim(strip_tags($line));
		            			break;
		          		case preg_match("/<url>/", $line):
		            			$this -> rss_image_url = trim(strip_tags($line));
		            			break;
		            		case preg_match("/<link>/", $line):
		          			$this -> rss_image_link = trim(strip_tags($line));
		          			break;
		          		case preg_match("/<width>/", $line):
		            			$this -> rss_image_width = trim(strip_tags($line));
		            			break;
		            		case preg_match("/<height>/", $line):
		            			$this -> rss_image_height = trim(strip_tags($line));
		            			break;
		            		case preg_match("/<description>/", $line):
		            			$this -> rss_image_description = trim(strip_tags($line));
		            			break;
		               	}
		               	
		      	}
		      			      	
		}
		
		reset ($lines); 
		
		while (list($num, $line) = each($lines))
		{
			$line = trim(chop($line));
			if(preg_match("/<\/rss>/", $line)) break;
		      	if(preg_match("/<\/item>/", $line)) continue;
		        if(!preg_match("/<item>/", $line)) continue;
		      
		      	if($fin == true) break;
		      
		      	while (list($num, $line) = each($lines))
		      	{ 
		      		$this -> rss_items = true;
		      		$line = trim(chop($line));
		        	if(empty($line) || $line[0] != "<") continue;
		                if(preg_match("/<\/item>/", $line)) break;
		        
		       	        switch(true)
		       	        {
		          		case preg_match("/<link>/", $line):
		            			$this -> rss_item_link[$this -> itemprogress] = trim(strip_tags($line));
		            			break;
		          		case preg_match("/<title>/", $line):
		            			$this -> rss_item_title[$this -> itemprogress] = trim(strip_tags($line)); 
		            			break;
		               	}
		               	
		        	
		      	}
		      	
			$this -> itemprogress++;
			
		      	if($this -> itemprogress > $maxitem)
		      	{
		      		$fin = true;
		        }
		}
		
		$this -> itemcount = count($this -> rss_item_title);
		
		reset($lines);
		 
		while(list($num, $line) = each($lines))
		{
			$line = trim(chop($line));
		      	if(preg_match("/<\/textinput>/", $line)) break;
		      	if(!preg_match("/<textinput>/", $line)) continue;
		      
		      	while(list($num, $line) = each($lines))
		      	{ 
		      		$this -> rss_textinput = true;
				$line = trim(chop($line));
		        	if(empty($line) || $line[0] != "<") continue;
		        	if(preg_match("/<\/textinput>/", $line)) break;
		        
		        	switch(true)
		        	{
		          		case preg_match("/<title>/", $line):
		            			$this -> rss_textinput_title = trim(strip_tags($line));
		            			break;
		          		case preg_match("/<description>/", $line):
		            			$this -> rss_textinput_description = trim(strip_tags($line));
		            			break;
		          		case preg_match("/<name>/", $line):
		            			$this -> rss_textinput_name = trim(strip_tags($line));
		            			break;
		          		case preg_match("/<link>/", $line):
		            			$this -> rss_textinput_link = trim(strip_tags($line));
		            			break;
		        	}
		      	}
		}
	}
	
	function get_channel_title()
	{
		return $this -> rss_channel_title;
	}
	
	function get_channel_link()
	{
		return $this -> rss_channel_link;
	}
	
	function get_channel_description()
	{
		return $this -> rss_channel_description;
	}
	
	function get_channel_language()
	{
		return $this -> rss_channel_language;
	}
	
	function get_channel_copyright()
	{
		return $this -> rss_channel_copyright;
	}
	
	function get_channel_webmaster()
	{
		return $this -> rss_channel_webMaster;
	}
	
	function get_image_title()
	{
		return $this -> rss_image_title;
	}
	
	function get_image_url()
	{
		return $this -> rss_image_url;
	}
	
	function get_image_link()
	{
		return $this -> rss_image_link;
	}
	
	function get_image_width()
	{
		if(empty($this -> rss_image_width)) $this -> rss_image_width = '88';
		return $this -> rss_image_width;
	}
	
	function get_image_height()
	{
		if(empty($this -> rss_image_height)) $this -> rss_image_height = '31';
		return $this -> rss_image_height;
	}
	
	function get_image_description()
	{
		return $this -> rss_image_description;
	}
	
	function get_items_title()
	{
		return $this -> rss_item_title;
	}
	
	function get_items_link()
	{
		return $this -> rss_item_link;
	}
	
	function get_num_items()
	{
		return $this -> itemcount;
	}
	
	function get_textinput_title()
	{
		return $this -> rss_textinput_title;
	}
	
	function get_textinput_description()
	{
		return $this -> rss_textinput_description;
	}
	
	function get_textinput_name()
	{
		return $this -> rss_textinput_name;
	}
	
	function get_textinput_link()
	{
		return $this -> rss_textinput_link;
	}
}

?>