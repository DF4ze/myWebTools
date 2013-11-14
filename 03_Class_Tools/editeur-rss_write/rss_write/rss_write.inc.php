<?php
// ---------------------------------------------------------
// rss_write - classe pour générer des fichiers RSS/RDF/Atom// Dominique WOJYLAC <http://wojylac.free.fr>// Distribué sous GNU General Public License.// // Version 2.0 beta 0.4
// ---------------------------------------------------------


class rss_write
{
	var $class_version;
	var $rss_data;
	var $rss_channel;
	var $rss_image_exist;
	var $rss_image;
	var $itemcount;	
	var $rss_items;
	var $rss_correct;
	var $class_dir;
	var $xml_head;
	
	function rss_write() {
		$this -> class_version = '2.0 beta 0.4';
		$this -> rss_data = array();
		$this -> rss_channel = array();
		$this -> rss_image = array();
		$this -> itemcount = -1;
		$this -> rss_items = array();
		$this -> rss_correct = false;
		$this -> class_dir = '';
		$this -> xml_head = true;
	}
	
	/*
	** configuration
	*/
	function class_directory($dir = '') {
		$len = strlen ($dir) - 1;
		if ($dir{$len} != '/') {
			$dir .= '/';
		}
		$this -> class_dir = $dir;
	}
	
	function xml_head($mode = true) {
		if ($mode === false) {
			$this -> xml_head = $mode;
		}
		else {
			$this -> xml_head = false;
		}
	}
	
	/*
	** definition fil (encodage et langue)
	*/
	function rss ($encode = '', $langue = '') {
		$this -> rss_data['encoding'] = trim($encode);
		$this -> rss_data['language'] = trim($langue);
	}
	
	function rss_correct (&$error) {
		$erreur = '';
		// encodage obligatoire
		if(!isset($this -> rss_data['encoding']) or empty($this -> rss_data['encoding'])) {
			$error = 'Erreur : rss - encoding';
			return false;
		}
		
		// encodage obligatoire
		if(!isset($this -> rss_data['language']) or empty($this -> rss_data['language'])) {
			$error = 'Erreur : rss - language';
			return false;
		}
		
		return true;
	}
	
	
	/*
	** Channel
	*/
	function channel($title, $link, $description) {
		$this -> rss_channel['title'] = trim($title);
		$this -> rss_channel['link'] = trim($link);
		$this -> rss_channel['description'] = trim($description);
	}// fin channel
	
	function channel_element($element, $value) {
		$channel_elements = array('title', 'link', 'description', 'copyright', 'webmaster', 'pubdate', 'url_flux');
		if (in_array($element, $channel_elements)) {
			$this -> rss_channel[$element] = trim($value);
		}
	}
	
	function channel_correct(&$error) {
		$error = '';
		$to_verif = array('title', 'link', 'description');
		
		for ($i = 0; $i < 3; $i++) {
			$temp = $this -> rss_channel[$to_verif[$i]];
			if(empty($temp)) {
				$error = 'Erreur : - channel '.$to_verif[$i];
				return false;
				break;
			}
		}
		return true;
		
	} // fin channel_correct
	
	/*
	** image
	*/
	function image($url, $title = '', $link = '') {
		$this -> rss_image['url'] = trim($url);
		if (!empty($title)) {
			$this -> rss_image['title'] = trim($title);
		}
		if (!empty($link)) {
			$this -> rss_image['link'] = trim($link);
		}
	} // fin image
	
	function image_element($element, $value) {
		$channel_elements = array('title', 'link', 'url', 'description', 'width', 'height');
		if (in_array($element, $channel_elements)) {
			$this -> rss_image[$element] = trim($value);
		}
	}
	
	function image_correct (&$error) {
		$error = '';
		if (!empty($this -> rss_image) and empty($this -> rss_image['url'])) {
			$error = 'Erreur : - image url';
			return false;
			break;
		}
		return true;
	} // fin image_correct
	
	/*
	** items
	*/
	function item($title, $link, $description='') {
		$this -> itemcount++;
		$current_item = $this -> itemcount;
		$this -> rss_items[$current_item]['title'] = trim($title);
		$this -> rss_items[$current_item]['link'] = trim($link);
		$this -> rss_items[$current_item]['description'] = trim($description);
	}
	
	function item_element($element, $value) {
		$channel_elements = array('title', 'link', 'description', 'author', 'pubdate', 'category', 'modified');
		if (in_array($element, $channel_elements)) {
			$this -> rss_items[$this -> itemcount][$element] = trim($value);
		}
	}
	
	function new_item() {
		$this -> itemcount++;
	}
	
	function items_correct(&$error) {
		$error = '';
		$nb_items = $this -> itemcount + 1;
		$to_verif = array('title', 'link');
		
		for ($i = 0; $i < $nb_items; $i++) {
			for ($j = 0; $j < 2; $j++) {
				$temp = $this -> rss_items[$i][$to_verif[$j]];
				if(empty($temp)) {
					$error = 'Erreur : item '.$i.' - '.$to_verif[$j];
					return false;
					break;
				}
			}
		}
		return true;
	} // fin items_correct
	
	
	
	function generate($format = '',&$error) {
	
		$error = '';
		$n = "\n";
		
		// faire la vérification au besoin une seule fois
		if ($this -> rss_correct == false) {
			if (($this -> rss_correct($error) == false) or ($this -> channel_correct($error) == false) 
				or ($this -> image_correct($error) == false) or ($this -> items_correct($error) == false)) {
				return false;
			}
			$this -> rss_correct = true;
		}
		
		// pour eviter la redéfinition des fonctions
		include_once $this -> class_dir.'rss_w.functions.php';
		$plugin = $this -> class_dir.'rss_w.'.$format.'.php';
		
		if ($this -> xml_head == true) {
			$rss_content = '<?xml version="1.0" encoding="'.$this -> rss_data['encoding'].'" ?>'.$n;
		}
		else {
			$rss_content = '';
		}
		
		if (file_exists($plugin)) {
			include $plugin;
		}
		else {
			$error = 'Erreur : format flux';
			return false;
		}
		
		return $rss_content;
	} // fin generate
	
		
	function save($filename, $format = '', &$erreur) {
		$res = $this -> generate($format, $erreur);
		if ($res) {
			$fd = fopen($filename, "w");
			fputs($fd, $res);
			fclose($fd);
			return true;
		} else {
			return false;
		}
	}
	
}

?>