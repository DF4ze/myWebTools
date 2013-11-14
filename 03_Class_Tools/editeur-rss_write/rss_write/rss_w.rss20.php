<?php
// format rss 2.0
// format non strict (pas de limitation du nombre de caractères)
// template version beta 0.4 modifié Alain Sichel lignes 17 et 54
// en-tete
$rss_content .= 
	'<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/">'.$n;

// channel
$rss_content .=
	'<channel>'.$n.
	element ('title', $this -> rss_channel['title']).
	element ('link', $this -> rss_channel['link']).
	element ('description', $this -> rss_channel['description']).
	element ('language', $this -> rss_data['language']).
	element_op ('copyright', $this -> rss_channel['copyright']).
	element_op ('dc:creator', $this -> rss_channel['webmaster']).
	element_date1 ('pubDate', $this -> rss_channel['pubdate']).
	element ('generator', 'fil_LE rss_write V'.$this -> class_version);
	
// image si existe
if(!empty($this -> rss_image)) {
	$rss_content .=
	'<image>'.$n.
	element ('url', $this -> rss_image['url']);
	
	if (!empty($this -> rss_image['title'])) {
		$rss_content .= element ('title', $this -> rss_image['title']);
	} else {
		$rss_content .= element ('title', $this -> rss_channel['title']);
	}
	
	if (!empty($this -> rss_image['link'])) {
		$rss_content .= element ('link', $this -> rss_image['link']);
	} else {
		$rss_content .= element ('link', $this -> rss_channel['link']);
	}
	
	$rss_content .=
	element_op ('width', $this -> rss_image['width']).
	element_op ('height', $this -> rss_image['height']).
	element_op ('description', $this -> rss_image['description']).
	'</image>'.$n;
}

// items
for($i = 0; $i <= $this -> itemcount; $i++)
{
	$rss_content .=
	'<item>'.$n.
	element ('title', $this -> rss_items[$i]['title']).
	element ('link', $this -> rss_items[$i]['link']).
	element_op ('description', $this -> rss_items[$i]['description']).
	element_op ('dc:creator', $this -> rss_items[$i]['author']).
	element_op ('category', $this -> rss_items[$i]['category']).
	element_date1 ('pubDate', $this -> rss_items[$i]['pubdate']).
	'</item>'.$n;
}
		
$rss_content .=
	'</channel>'.$n.
	'</rss>';
?>