<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=ISO-8859-1">
	<title>Générateur de flux RSS et Atom</title>
	<style type="text/css" media="screen"><!--
p,table,input,select,textarea   { font-size: 10px; font-family: Verdana, Arial, Helvetica }
a { color: navy; text-decoration: none }
a:hover  { color: #ff4500; text-decoration: underline overline; background-color: #dcdcdc }
.option_visible { visibility: visible }
.option_invisible  { height: 0px; visibility: hidden }
.red {color: #ff0000; background-color: transparent; }
input, textarea {background-color:#d4eaff;}
--></style>
	<script type="text/javascript"><!--
function next_op(the_form, the_op) {
	the_form.op.value = the_op;
	the_form.submit();
	return true;
}
// -->
</script>
</head>
<body>


<?php
function message($txt) {
	echo '<h3 align="center" class="red">',$txt,'</h3>
		<p align="center"><a href="javascript:history.go(-1)">Retour</a></p>';
	exit;
}

function propre($text) {
	$text = html_entity_decode(str_replace('\\','', trim($text)));
	if (ereg("[]%~#`$&|}{^[><]",$text)) {
		message('Un ou plusieurs champs contiennent des caractères interdits !');
	}
	else {
		return $text;
	}
}

function clean_date($date) {
	$date = trim($date);
	if (ereg("[]%~#`$&|}{^[><]",$date)) {
		message('Un ou plusieurs dates contiennent des caractères interdits !');
	}
	else { // si 'AAAA-MM-JJTHH:MM:SS+00:00' ou 'AAAA-MM-JJTHH:MM:SSZ'
		if (ereg("^[0-9]",$date) and  ereg("(([[:digit:]]|-)*)T(([[:digit:]]|:)*)[^[:digit:]].*",$date,$temp)) {
			$date = $temp[1].' '.$temp[3];
		}
		return $date;
	}    	
}

function verif_url($url) {
	if ($url != '') {
		if (preg_match("#^http://.[\w./_-]+$#i",$url,$ret)) {
			return $url;
		}
		else {
			message('Une ou plusieurs URL ne sont pas valides !');
		}
	}
}

function verif_num($num) {
	if ($num != '') {
		if (preg_match("#^[\d]+$#i",$num,$ret)) {
			return $num;
		}
		else {
			message('Les champs width et height ne doivent contenir que des chiffres !');
		}
	}
}

function option_checked ($option) {
	if ($option == true) {
		return 'checked="checked"';
	} else {
		return '';
	}
}

$op = $_POST['op'];

switch ($op) {
case 'generer_flux':
	while (list($var,$val) = each($_POST)) {
		${$var} = $val;
	}
	
	if (empty($format_flux)) {
		message('Choisir au moins un format de flux !');
	}
	
	if (empty($channel['title']) or empty($channel['link']) or empty($channel['description'])) {
		message('Tous les éléments channel en rouge sont obligatoires !');
	}
	
	$nb_items = count($item);
	for ($i = 0; $i < $nb_items; $i++) {
		if (empty($item[$i]['title']) or empty($item[$i]['link'])) {
			message('Les éléments link et title sont obligatoires pour les items !');
		}
	}
	
	include 'rss_write/rss_write.inc.php';

	$maj = date("Y-m-d").'T'.date("H:i:s").'Z';
	$rss = new rss_write();
	
	$rss -> class_directory('rss_write/');
	$rss -> rss('ISO-8859-1','fr');
	$rss -> channel(propre($channel['title']), verif_url($channel['link']), propre($channel['description']));
	$rss -> channel_element('copyright', propre($channel['copyright']));
	$rss -> channel_element('webmaster', propre($channel['author']));
	$rss -> channel_element('pubdate', clean_date($maj));
	$rss -> channel_element('url_flux', verif_url($channel['url_flux']));
	
	$rss -> image(verif_url($image['url']), propre($image['title']), verif_url($image['link']));
	$rss -> image_element('description', propre($image['description']));
	$rss -> image_element('width', verif_num($image['width']));
	$rss -> image_element('height', verif_num($image['height']));
	
	for ($i = 0; $i < $nb_items;$i++) {
		$title = propre($item[$i]['title']);
		$link = verif_url($item[$i]['link']);
		$description = propre($item[$i]['description']);
		$rss -> item($title, $link, $description);
		$rss -> item_element('author', propre($item[$i]['author']));
		$rss -> item_element('pubdate', clean_date($item[$i]['pubdate']));
		$rss -> item_element('category', propre($item[$i]['category'])); 
		$rss -> item_element('modified', clean_date($item[$i]['pubdate']));
	}
	// generation
	
	for ($i = 0; $i < 4; $i++) {
		if (!empty($format_flux[$i])) {
			$format = $format_flux[$i];
			$filename = '../'.$format.'.xml';
			$res = $rss -> save($filename, $format, $erreur);
			echo '<p align="center" class="red">Votre fichier de syndication '.$format.' a &eacute;t&eacute; r&eacute;alis&eacute;.<p>';
		}
	}
	
break;

case 'edit_flux_distant':
	$url_flux_distant ='';
	$url_flux_distant = $_POST['url_flux_distant'];
	
	$url_flux_distant = trim($url_flux_distant);
	if (!empty($url_flux_distant) and ($url_flux_distant != 'http://')) {
		include 'rss_read.inc.php';
		$rss = new rss_read();

		$rss -> parsefile($url_flux_distant);

		if (!$rss) {
			message('Fichier rss incorrect !');
		}

		// recupération des données sur le channel
		
		$channel = $rss -> get_channel();
		
		if ($rss -> exist_image()) {
			$image = $rss -> get_image();
		}
			
		// nombre d'items     
		$nb_items = $rss -> get_num_items();
	
		// recup array des données
		$item = $rss -> get_items();
	}
// on continue
	

default :
	switch ($op) {
	case 'refresh':
		while (list($var,$val) = each($_POST)) {
			$$var = $val;
		}
	break;
	
	case 'raz_tout':
		while (list($var,$val) = each($_POST)) {
			$$var = '';
		}
	break;
	
	case 'nouvel_item':
		while (list($var,$val) = each($_POST)) {
			$$var = $val;
		}
		// on décale les items
		for ($i = $nb_items ; $i >= 0; $i--) {
			$item[$i+1] = $item[$i];
		}
		$nb_items++;
		$item[0] = array();
	break;
	
	case 'supprime_dernier_item':
		while (list($var,$val) = each($_POST)) {
			$$var = $val;
		}
		$nb_items--;
	break;
	
	} // fin switch 2
	
	if (empty($nb_items) or ($nb_items < 1)) {
	$nb_items = 1;
	}
?>
			
<p align="center">&Eacute;ditez ou cr&eacute;ez votre flux rss, vous pourrez ensuite r&eacute;cup&eacute;rer le code pour mettre ce fichier rss sur votre site. Le flux est g&eacute;n&eacute;r&eacute; gr&acirc;ce &agrave; la <a href="doc_rss_write.html" title="Documentation sur rss_write">classe rss_write</a>.</p>
		<form method="post" name="rss" enctype="multipart/form-data">
		<input type="hidden" name="op" value="edit_flux_distant" border="0">
			<table border="1" cellspacing="0" cellpadding="3" align="center">
				<tr>
					<td>Pour <b>&eacute;diter</b> un fichier rss existant, indiquez son url dans le champ ci-dessous.
						<p><b>Url du flux (http://...) :</b> <input type="text" name="url_flux_distant" value="<?php if(isset($url_flux_distant)) {echo $url_flux_distant;}
else {echo '../rss20.xml';} ?>" size="36" border="0" onfocus="this.value='http://'" /></p>
						<p align="center"><input type="button" name="operation" value="Editer Flux Distant" border="0" onclick="next_op(this.form, 'edit_flux_distant')">&nbsp; &nbsp;<input type="button" name="operation" value="Remettre tout &agrave; Z&eacute;ro" border="0" onclick="next_op(this.form, 'raz_tout')"></p>
					</td>
				</tr>
			</table>
		</form>
		<hr>
		<form method="post" name="rss" enctype="multipart/form-data">
			<input type="hidden" name="op" value="refresh" border="0">

			<table width="500" border="1" cellspacing="0" cellpadding="3" align="center">
				<tr>
					<td colspan="2">Pour <b>cr&eacute;er</b> un fichier rss, remplissez les champs du formulaire ci-dessous.<br>
						(Tous les champs marqu&eacute;s en <span class="red">rouge*</span> sont obligatoires, les liens doivent &ecirc;tre des adresses absolues).</td>
				</tr>
				<tr>
					<td colspan="2" bgcolor="#dcdcdc"><b>Format :</b> <input type="checkbox" name="format_flux[0]" value="rss20" border="0" checked="checked" />Rss 2.0 <input type="checkbox" name="format_flux[1]" value="rdf10" border="0" checked="checked" />Rdf 1.0 <input type="checkbox" name="format_flux[3]" value="atom10" border="0" checked="checked" />Atom 1.0 &nbsp;<input type="button" name="operation" value="G&eacute;n&eacute;rer le(s) flux" border="0" onclick="next_op(this.form, 'generer_flux')"></td>
				</tr>
				<tr>
					<td colspan="2"><b>Channel</b> (Lien = url vers le site)
						<p>Options :
						<input type="checkbox" name="channel_option[author]" value="true" border="0" onchange="next_op(this.form, 'refresh')" <?php echo option_checked(($channel_option['author']) or ($channel['author'])); ?>> Webmaster 
						<input type="checkbox" name="channel_option[copyright]" value="true" border="0" onchange="next_op(this.form, 'refresh')" <?php echo option_checked(($channel_option['copyright']) or ($channel['copyright'])); ?>> Copyright 
						<input type="checkbox" name="channel_option[url_flux]" value="true" border="0" onchange="next_op(this.form, 'refresh')" <?php echo option_checked(($channel_option['url_flux']) or ($format_flux[3])); ?>> Url du flux 
						<input type="checkbox" name="channel_option[pubdate]" value="true" border="0" onchange="next_op(this.form, 'refresh')" <?php echo option_checked(($channel_option['pubdate']) or ($channel['pubdate'])); ?>> Date de publication
						<input type="checkbox" name="channel_option[img]" value="true" border="0" onchange="next_op(this.form, 'refresh')" <?php echo option_checked(($channel_option['img']) or ($image['url'])); ?>> Logo   
						</p>
					</td>
				</tr>
				<tr>
					<td align="right" class="red">Titre*</td>
					<td><input type="text" name="channel[title]" value="<?php echo htmlentities($channel['title']); ?>" size="62" border="0"></td>
				</tr>
				<tr>
					<td align="right" class="red">Lien*</td>
					<td><input type="text" name="channel[link]" value="<?php echo $channel['link']; ?>" size="62" border="0"></td>
				</tr>
				<tr>
					<td align="right" class="red">Description*</td>
					<td><textarea name="channel[description]" rows="4" cols="60"><?php echo htmlentities($channel['description']); ?></textarea></td>
				</tr>
				<!-- option webmaster -->
				<?php if(($channel_option['author']) or ($channel['author'])) { ?>
				<tr>
					<td align="right">Webmaster</td>
					<td><input type="text" name="channel[author]" value="<?php echo $channel['author']; ?>" size="60" border="0"></td>
				</tr>
				<?php } ?>
				<!-- option copyright -->
				<?php if(($channel_option['copyright']) or ($channel['copyright'])) { ?>
				<tr>
					<td align="right">Copyright</td>
					<td><input type="text" name="channel[copyright]" value="<?php echo $channel['copyright']; ?>" size="60" border="0"></td>
				</tr>
				<?php } ?>
				<!-- option url_flux -->
				<?php if($channel_option['url_flux']) { ?>
				<tr>
					<td align="right">Url du flux</td>
					<td><input type="text" name="channel[url_flux]" value="<?php echo $channel['url_flux']; ?>" size="60" border="0"></td>
				</tr>
				<?php } ?>
				<!-- option date de publication -->
				<?php if(($channel_option['pubdate']) or ($channel['pubdate'])) { ?>
				<tr>
					<td align="right">Date de publication</td>
					<td><input type="text" name="channel[pubdate]" value="<?php echo $channel['pubdate']; ?>" size="30" border="0">(JJ/MM/AAAA HH:MM)</td>
				</tr>
				<?php } ?>
				<!-- option Logo -->
				<?php
				if (($channel_option["img"]) or ($image['url'])) {
				?>
				<tr>
				  <td colspan="2" bgcolor="#dcdcdc"><b>Image</b> (Logo du site) </td>
				  </tr>
				<tr>
				  <td align="right" class="red">Titre*</td>
				  <td bgcolor="#FFFFFF"><input type="text" name="image[title]" value="<?php echo htmlentities($image['title']); ?>" size="62" maxlength="100" border="0" /></td>
				  </tr>
				<tr>
				  <td align="right" class="red">URL*</td>
					<td><input type="text" name="image[url]" value="<?php echo $image['url']; ?>" size="62" maxlength="100" border="0" /></td>
				  </tr>
				  <tr>
				  <td align="right" class="red">Lien*</td>
					<td><input type="text" name="image[link]" value="<?php echo $image['link']; ?>" size="62" maxlength="100" border="0" /></td>
				  </tr>
				  <tr>
				  <td align="right">Largeur</td>
					<td><input type="text" name="image[width]" value="<?php echo $image['width']; ?>" size="20" maxlength="4" border="0" /></td>
				  </tr>
				  <tr>
				  <td align="right">Hauteur</td>
					<td><input type="text" name="image[height]" value="<?php echo $image['height']; ?>" size="20" maxlength="4" border="0" /></td>
				  </tr>
				  <tr>
				  <td align="right" valign="top">Description</td>
					<td><textarea name="image[description]" rows="1" cols="60"><?php echo htmlentities($image['description']); ?></textarea></td>
				  </tr>
				  <?php } ?>
				<tr>
					<td colspan="2" bgcolor="#dcdcdc"><b>Nombre d'items </b><input type="text" name="nb_items" value="<?php echo $nb_items; ?>" size="3" maxlength="2" border="0" onchange="this.form.op.value='refresh';this.form.submit()"> &nbsp;<input type="button" name="operation" value="Nouvel Item" border="0" onclick="next_op(this.form, 'nouvel_item')"> &nbsp;<input type="button" name="operation" value="Supprimer Dernier Item" border="0" onclick="next_op(this.form, 'supprime_dernier_item')">
					<p>Options :
						<input type="checkbox" name="item_option[pubdate]" value="true" border="0" onchange="next_op(this.form, 'refresh')" <?php echo option_checked(($item_option['pubdate']) or ($item[0]['pubdate'])); ?>> Date 
						<input type="checkbox" name="item_option[category]" value="true" border="0" onchange="next_op(this.form, 'refresh')" <?php echo option_checked(($item_option['category']) or ($item[0]['category'])); ?>> Catégorie 
						<input type="checkbox" name="item_option[author]" value="true" border="0" onchange="next_op(this.form, 'refresh')" <?php echo option_checked(($item_option['author']) or ($item[0]['author'])); ?>> Auteur
						</p></td>
				</tr>
				<?php
				for ($i = 0; $i < $nb_items; $i++) {
				?>
				<tr>
					<td colspan="2" bgcolor="#dcdcdc">Item : <?php echo ($i+1); ?></td>
				</tr>
				<tr>
					<td align="right" class="red">Titre*</td>
					<td><input type="text" name="item[<?php echo $i; ?>][title]" size="62" maxlength="100" border="0" value="<?php echo htmlentities($item[$i]['title']); ?>"></td>
				</tr>
				<tr>
					<td align="right" class="red">Lien*</td>
					<td><input type="text" name="item[<?php echo $i; ?>][link]" size="62" maxlength="100" border="0" value="<?php echo $item[$i]['link']; ?>"></td>
				</tr>
				<!-- option date de publication -->
				<?php if(($item_option['pubdate']) or ($item[$i]['pubdate'])) { ?>
				<tr>
					<td align="right">Date</td>
					<td><input type="text" name="item[<?php echo $i; ?>][pubdate]" size="30" maxlength="100" border="0" value="<?php echo htmlentities($item[$i]['pubdate']); ?>"></td>
				</tr>
				<?php } ?>
				<!-- option catégorie -->
				<?php if(($item_option['category']) or ($item[$i]['category'])) { ?>
				<tr>
					<td align="right">Catégorie</td>
					<td><input type="text" name="item[<?php echo $i; ?>][category]" size="62" maxlength="100" border="0" value="<?php echo htmlentities($item[$i]['category']); ?>"></td>
				</tr>
				<?php } ?>
				<!-- option auteur -->
				<?php if(($item_option['author']) or ($item[$i]['author'])) { ?>
				<tr>
					<td align="right">Auteur</td>
					<td><input type="text" name="item[<?php echo $i; ?>][author]" size="62" maxlength="100" border="0" value="<?php echo htmlentities($item[$i]['author']); ?>"></td>
				</tr>
				<?php } ?>
				<tr>
					<td align="right">Description</td>
					<td><textarea name="item[<?php echo $i; ?>][description]" rows="5" cols="60"><?php echo htmlentities($item[$i]['description']); ?></textarea></td>
				</tr>
				<?php } ?>
			</table>
		</form>
		<?php
		break;
		} // fin switch
	?>
</body>
</html>