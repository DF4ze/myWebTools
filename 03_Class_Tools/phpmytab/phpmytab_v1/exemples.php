<?php
require_once( "phpmytab/phpmytab.php" );



////////////////////////
// Init de la classe 

///// BDD
$_BddParams['user'] = 'root';
$_BddParams['pwd'] = '';
$_BddParams['srv'] = 'localhost';
$_BddParams['base'] = 'jdb_v1_11_7';
$_BddParams['table'] = 'jdb_evenements';

///// Colonne du tableau
// colonne "Spéciale"
$offset = 0;
$_TabParams['head'][$offset]['description'] = 'Suppr.'; 									// Nom sous lequel apparaitra la colonne
$_TabParams['head'][$offset]['spec'] = '<a href="test.php?suppr=[id]">Suppr [id] ? </a> '; 	// [id] sera remplacé par la valeur dans la colonne 'id'
// $_TabParams['head'][$offset]['sort_by'] = 'id'; 											// lors de la demande de tri, ID sera le repère. // si est manquant, le tri est desactivé sur la colonne.
$_TabParams['foot'][$offset] = 'Options';													// Nom du pied pour cette colonne // je viens de voir un bug : si on commente cette ligne.... le footer de la colonne suivante n'est aps affiché
// colonne "normale"
$offset++;
$_TabParams['head'][$offset]['description'] = 'Nom';					// Nom sous lequel apparaitra la colonne	
$_TabParams['head'][$offset]['nom_colonne'] = 'auteur';					// colonne BDD qui sera affichée
$_TabParams['foot'][$offset] = 'Personne qui a composé l\'evènement';	// Nom du pied pour cette colonne
// Xeme colonne
$offset++;
$_TabParams['head'][$offset]['description'] = 'Description :';			// ...
$_TabParams['head'][$offset]['nom_colonne'] = 'description';
// $_TabParams['foot'][$offset] = '';
// Xeme colonne
$offset++;
$_TabParams['head'][$offset]['description'] = 'pkpas';
$_TabParams['head'][$offset]['spec'] = '[id]';
$_TabParams['head'][$offset]['sort_by'] = 'id'; 						
// $_TabParams['foot'][$offset] = '';


///// Vue
// Nom des div des lignes du tableau
$_ViewParams['div_line_head'] = 'div_head';								// Div qui est inséré dans les cellules TH du tableau : <tr><hd><div class="div_head">$blabla</div></hd></tr>
$_ViewParams['div_line_pair'] = 'div_pair';								// Div qui est inséré dans les cellules TD du tableau : <tr><td><div class="div_pair">$blabla</div></td></tr>
$_ViewParams['div_line_unpair'] = 'div_unpair';							// Div qui est inséré dans les cellules TD du tableau : <tr><td><div class="div_pair">$blabla</div></td></tr>
$_ViewParams['div_line_foot'] = 'div_foot';								// Div qui est inséré dans les cellules TH du tableau : <tr><hd><div class="div_head">$blabla</div></hd></tr>
// Nom des div qui seront utilisé pour les insertions 
$_ViewParams['div_insert_top'] = 'div_insert_top';						// Au dessus du tableau
$_ViewParams['div_insert_title'] = 'div_insert_title';					// dans le titre .... en dessous du Texte titre.
$_ViewParams['div_insert_bottom'] = 'div_insert_bottom';				// en dessous du tableau

// Réinsertion des header lors de l'affichage du tableau
$_ViewParams['reinsert_header'] = 10;

// Nb items par page
$_ViewParams['pagine'] = 10;											// Defini le nombre d'items dans une page. si a FALSE, l'option de pagination est retirée
// Nom des balises/classes pour les chiffres représentant le N° des pages
$_ViewParams['span_current'] = "page_encours";							// balise span autour de la page en cours
$_ViewParams['span_other'] = "page_autre";								// balise span autour des autres pages que la page en cours
$_ViewParams['div_text'] = "div_text";									// div qui entoure tout le texte qui concerne la numérotation des pages.
// On coupe l'affiche du nombre de page à 1 page autour de la page courrante
$_ViewParams['page_contractor'] = ' ... '; 								// caractère qui sera utilisé pour contracter le nombre de page s'il y en a trop.
$_ViewParams['limit_page_affiche'] = 2;									// nombre de page au dessous et au dessus de la page en cours, qui seront affichés.
// Nom de la variable passée en paramètre
$_ViewParams["nameget_newpage"] = 'page';								// nom de la variable qui sera posté en GET
// Caractère délimitant le numéro des pages.
$_ViewParams['page_delimiter'] = ' , ';									// caractère qui délimite le numéro des pages
// Options de pagination : Afficher les fleches précédents et/ou suivant.
$_ViewParams['page_foreward'] = '<img src="http://pinup.spiecom.com/modules/Printing/printbutton.gif"/>';			// symbole qui permettra de passer a la page suivante.
$_ViewParams['page_backward'] = '< '; //'<img src="http://pinup.spiecom.com/modules/Printing/printbutton.gif"/>';	// symbole qui permettra de passer a la page précédente.
// Options de pagination : Afficher les fleches 1ere et/ou derniere.
$_ViewParams['page_first'] = '<< '; //'<img src="http://pinup.spiecom.com/modules/Printing/printbutton.gif"/>';		// symbole qui permettra de passer a la 1ere page.
$_ViewParams['page_last'] = ' >>'; //'<img src="http://pinup.spiecom.com/modules/Printing/printbutton.gif"/>';		// symbole qui permettra de passer a la derniere page.
// Afficher les pages en haut et en bas :
$_ViewParams['textpages_top'] = true;									// affichera le texte pour le choix des pages en haut
$_ViewParams['textpages_title'] = true;									// affichera le texte pour le choix des pages au milieu
$_ViewParams['textpages_bottom'] = true;								// affichera le texte pour le choix des pages en haut
																		// on peut contourner ces options en faisant un $tab->add_insert_top( $tab->get_text_pages() );
// Un titre pour le tableau
$_ViewParams['title'] = "Un titre pour mon tableau :)";					// Texte qui sera inseré dans le titre du tableau
$_ViewParams['span_title'] = "title_span";								// span qui entoure le texte

// tri du tableau
$_ViewParams['sorted'] = true;											// les entete de colonnes sont clicables et tri par ordre croissant ou décroissant

// recherche :
$_ViewParams['find_input_submit'] = true;								// affiche ou non le bouton submit lorsqu'on appelle $tab->show_find_field()
$_ViewParams['find_reset_button'] = true;								// affiche ou non le bouton reset lorsq...
$_ViewParams['find_input_submit_value'] = 'Research';					// texte du bouton submit lorsqu'on appelle ....
$_ViewParams['reset_button_value'] = "Réinitialiser la recherche";		// texte du bouton reset ... bug lors du show_reset_filter() qd reset_button == false...
$_ViewParams['input_text_name'] = 'fitn';								// nom de l'input text
//$_ViewParams['input_text_value'] = 'test';								// Lance automatiquement une recherche // ca fou la merde surtout :)
$_ViewParams['input_submit_name'] = 'fisn';								// nom de l'input submit

// Filtrage
$_ViewParams['filter_input_submit'] = false;							// affiche ou non le bouton submit lorsqu'on appelle $tab->show_filters()
$_ViewParams['filter_reset_button'] = true;								// affiche ou non le bouton reset lorsq...
$_ViewParams['filter_button_add_value'] = 'Ajouter';					// texte du bouton qui ajoute uen ligne de filtre.
$_ViewParams['filter_submit_value'] = 'Filtrer';						// texte du bouton submit.
//$_ViewParams['reset_button_value'] = "Réinitialiser les filtres";		// texte du bouton reset ... bug lors du show_reset_filter() qd reset_button == false...
$_ViewParams['idFormulaire'] = 'idFormulaire';							// Id que portera le formulaire
$_ViewParams['idBouton'] = 'idBouton';									// Id que portera le bouton
	
// Extract
$_ViewParams['extract'] = true; 										// permet de lancer la génération auto des extracts
$_ViewParams['extract_filename'] = 'extract.csv'; 						// extract.csv par defaut
$_ViewParams['extract_filepath'] = '.'; 								// !! Le chemin doit exister ... et pas de / à la fin du dossier;)     '.' par defaut
$_ViewParams['extract_icone'] = '<br/>Extract'; 						// peut contenir une balise image ou  tout autre code HTML   // 'Télécharger' par defaut
$_ViewParams['extract_icone_pos'] = 'top'; 								// 3 valeurs : 'top' 'title' ou 'bottom'    // NULL par defaut --> possibilité de générer une extract mais de ne pas le proposer au webuser.(Intérêt lors traitement par script par exemple...)
																		// La ... il n'est possible d'inserer qu'un seul lien vers l'extract ... mais il est possible d'en ajouter une fois la classe instantiée.



///////////////////////////
// Actions

$tab = new phpmytab( $_BddParams, $_TabParams, $_ViewParams );
echo "<br/><br/><br/><br/><br/>";

// On affiche le champ de recherche... dessous du titre.
$tab->add_insert_title( $tab->show_find_field() );
// et le champ filtrage
$tab->add_insert_title( $tab->show_filters() );
// Ainsi que le bouton de reset de la recherche
$tab->add_insert_bottom( $tab->show_reset_filter() );
$tab->add_insert_bottom( $tab->get_extract_icone_linked() );


// et on affiche le tableau
echo $tab->read_table();









//////////
// test des fonctionnalités


?>