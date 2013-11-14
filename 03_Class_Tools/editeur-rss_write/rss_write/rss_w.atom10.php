<?php
// format atom 1.0
// template version beta 0.4 modifié Alain Sichel ligne 41
// en-tete
$rss_content .= 
	'<feed xmlns="http://www.w3.org/2005/Atom" xml:lang="'.$this -> rss_data['language'].'">'.$n;

// channel
$rss_content .=
	element ('title', $this -> rss_channel['title']).
	'<link rel="alternate" type="text/html" href="'. $this -> rss_channel['link'].'" />'.$n.
	'<link rel="self" type="application/atom+xml" href="'.$this -> rss_channel['url_flux'].'" />'.$n.

	element ('id', $this -> rss_channel['link']).
	element ('subtitle', $this -> rss_channel['description']).
	element_op ('rights', $this -> rss_channel['copyright']).
	element_op ('author', element_op ('name', $this -> rss_channel['webmaster'])).
	element_date2 ('updated', $this -> rss_channel['pubdate']).
	element ('generator', 'fil_LE rss_write V'.$this -> class_version).
	element_op ('logo', $this -> rss_image['url']);

// items
for($i = 0; $i <= $this -> itemcount; $i++)
{
	$rss_content .=
	'<entry xml:lang="'.$this -> rss_data['language'].'">'.$n.
	element ('title', $this -> rss_items[$i]['title']).
	'<link rel="alternate" type="text/html" href="'. $this -> rss_items[$i]['link'].'" />'.$n.
	element ('id', $this -> rss_items[$i]['link']);
	if (!empty($this -> rss_items[$i]['description'])) {
		$rss_content .= 
		'<summary type="html">'. $this -> rss_items[$i]['description'].'</summary>'.$n;
	}
	$rss_content .=
	element_date2 ('published', $this -> rss_items[$i]['pubdate']).
	element_date2 ('updated', $this -> rss_items[$i]['modified']).
	element_op ('author', element_op ('name', $this -> rss_items[$i]['author']));
	
	if (!empty($this -> rss_items[$i]['category'])) {
		$rss_content .=
		'<category term="'.$this -> rss_items[$i]['category'].'" />'.$n;
	}
	
	$rss_content .= '</entry>'.$n;
}
		
$rss_content .= '</feed>';
?>