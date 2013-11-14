<?php
// format rss-rdf 1.0
// template version beta 0.4
// en-tete
$rss_content .= 
	'<rdf:RDF xmlns:dc="http://purl.org/dc/elements/1.1/"'.$n.
	'xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"'.$n.
	'xmlns="http://purl.org/rss/1.0/">'.$n;

// channel
$rss_content .=
	'<channel rdf:about="'.$this -> rss_channel['link'].'">'.$n.
	element ('title', $this -> rss_channel['title']).
	element ('link', $this -> rss_channel['link']).
	element ('description', $this -> rss_channel['description']).
	element ('dc:language', $this -> rss_data['language']).
	element_op ('dc:rights', $this -> rss_channel['copyright']).
	element_op ('dc:creator', $this -> rss_channel['webmaster']).
	element_date2 ('dc:date', $this -> rss_channel['pubdate']).
	element ('dc:source', 'fil_LE rss_write V'.$this -> class_version);

	if(!empty($this -> rss_image)) {
		$rss_content .= '<image rdf:resource="'.$this -> rss_image['url'].'" />'.$n;
	}

$rss_content .= '<items>'.$n.
	'<rdf:Seq>'.$n;
	for($i = 0; $i <= $this -> itemcount; $i++)
	{
		$rss_content .= '<rdf:li rdf:resource="'.$this -> rss_items[$i]['link'].'" />'.$n;
	}
	
	$rss_content .= '</rdf:Seq>'.$n.
		'</items>'.$n.
		'</channel>'.$n;
	
// image si existe
if(!empty($this -> rss_image)) {
	$rss_content .=
	'<image rdf:about="'.$this -> rss_image['url'].'">'.$n.
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
	element_op ('dc:description', $this -> rss_image['description']);
	
	if(!empty($this -> rss_image['width']) and !empty($this -> rss_image['height'])) {
		$format = 'width="'.$this -> rss_image['width'].'" height="'.$this -> rss_image['height'].'"';
		$rss_content .= element ('dc:format', $format);
	}
	
	$rss_content .= '</image>'.$n;
}

// items
	for($i = 0; $i <= $this -> itemcount; $i++)
{
	$rss_content .=
	'<item rdf:about="'.$this -> rss_items[$i]['link'].'">'.$n.
	element ('title', $this -> rss_items[$i]['title']).
	element ('link', $this -> rss_items[$i]['link']).
	element_op ('description', $this -> rss_items[$i]['description']).
	element_op ('dc:creator', $this -> rss_items[$i]['author']).
	element_op ('dc:subject', $this -> rss_items[$i]['category']).
	element_date2 ('dc:date', $this -> rss_items[$i]['pubdate']).
	'</item>'.$n;
}
		
$rss_content .= '</rdf:RDF>';
?>