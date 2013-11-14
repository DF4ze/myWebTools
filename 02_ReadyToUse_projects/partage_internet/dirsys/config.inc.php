<?php
#  +------------------ explorer ---------------------------+
#  |   SCRIPT Entierement Ecrit par Jean Charles MAMMANA   |
#  |   SCRIPT Entierement modifie par Xavier MEDINA        |
#  |   Url : http://www.jbc-explorer.com                   |
#  |   Contact : jc_mammana@hotmail.com                    |
#  |   Contact : xabi62@yahoo.fr                           |
#  |                                                       |
#  |   Tous les scripts utilisé dans ce projet             |
#  |   sont sont en libre utilisation.                     |
#  |   Tous droits de modifications sont autorisé          |
#  |   à condition de m'en informer comme précisé          |
#  |   dans les termes du contrat de la licence GPL        |
#  |                                                       |
#  |   Fichier de configuration de l'explorer              |
#  +-------------------------------------------------------+

# specifie le titre qui apparaitra dans la barre des taches
$CONFIG['MAIN_TITLE'] = "FTP DF4ze";

# langue de lexplorer
# fr = francais
# en = anglais
# de = allemand
$CONFIG['SYS_LANG'] = 'fr';

# largeur de la frame de l'arbre de lexplorer en pixels
$CONFIG['WIDTH_TREE_FRAME'] = 240;

# affichage de la bordure entre les frames (prend "TRUE" ou "FALSE")
$CONFIG['FRAME_BORDER'] = TRUE;

# largeur de la bordure de la frame de l'arbre de lexplorer en pixels
$CONFIG['WIDTH_FRAME_BORDER'] = 1;

# largeur de l'espacement entre les frames de lexplorer en pixels
$CONFIG['WIDTH_FRAME_SPACING'] = 3;

# affichage de l'ascenseur de la frame de lexplorer (prend "YES", "NO", "AUTO")
$CONFIG['SCROLING_TREE_FRAME'] = 'AUTO';

# permet de modifier manuellement la taille des frame (prend "TRUE" ou "FALSE")
$CONFIG['RESIZE_FRAME'] = TRUE;

# chemin (relatif) du repertoire racine de l'explorer.
# '/' : le repertoire racine selectionné est le repertoire dans lequel est le script
# (prend chemin RELATIF PAR RAPPORT A INDEX.PHP)
# ex : $CONFIG['DOCUMENT_ROOT'] = '/dirsys';
# il n'est donc pas possible de definir un root en dessous de là ou est situé l'explorateur (toujours index.php)
# si vous souhaiter limiter lacces a une certaine partie de l'arborescence selon l'utilisateur
# vous pouvez le faire a partir d'ici
$CONFIG['DOCUMENT_ROOT'] = '/donnees';

# nom du dossier systeme
# si vous souhaitez renomer le dossier systeme de l'explorer vous
# devez modifier son nom ici aussi
# par defaut : "dirsys"
# IMPORTANT : il faut aussi modifier la ligne 17 du fichier
# index.php! il faut modifier en remplacant l'ancien nom par 
# le nouveau nom du dossier.
$CONFIG['DIRSYS'] = 'dirsys';

# largeur de la colone de la taille des fichiers en pixels
$CONFIG['WIDTH_TD_SIZE'] = 60;

# largeur de la colone du type des fichiers en pixels
$CONFIG['WIDTH_TD_TYPE'] = 160;

# largeur de la colone de date des fichiers en pixels
$CONFIG['WIDTH_TD_DATE'] = 120;

# Permet de changer le jeu d'icone de l'explorer: personnalisation!
# 1 = windowsXP
# 2 = linux kde crystal
# 3 = linux kde aqua
# 4 = linux kde Gorilla
# 0 = change chaque jour (have fun :) )
$CONFIG['STYLE'] = 0;

# Permet de modifier la feuille de style de l'explorer
# indiquez le fichier que vous voulez charger dans le repertoir '.dirsys/styles/'
# vous pouvez creer vos propres styles (les ajouter dans le repertoir 'styles')

$CONFIG['CSS'] = 'style2.css';

# Definir ici l'espace alloué par l'hebergeur pour le site en MegaOctets
# par defaut : 100 Mo
$CONFIG['TOTALSIZE'] = 500000;

# Permet d'activer un message sous l'arborescence des fichiers
# $CONFIG['activer_Message'] : TRUE pour activer et FALSE pour désactiver 
# $CONFIG['Message'] : écrire votre message html autorisé

$CONFIG['activer_Message'] = false;
$CONFIG['Message'] = 'Mettez un message un lien ...';

# Permet d'afficher la fleche de retour dans la liste des fichiers!
$CONFIG['BACK'] = FALSE;

# Cette option permet d'ecrire les vignettes sur le serveur au lieu
# de les placer en memoire et de devoir les recharger a chaque fois!
# activez cette option si vous ne disposez pas de beaucoup de ressources
# systeme sur le serveur!
$CONFIG['WRITE_TN'] = TRUE;

#-------------------------------------------------------------

#          +---------------+
#          |    MODULES    |
#          +---------------+


# liste des fichiers 'invisible' par l'explorer. (séparer les extensions par des virgules)
# il est conseillé de cacher les fichiers dont lextension est 'php' car ils sont executable par
# le serveur, donc ils peuvent conduire à des resultats farfelu et meme aller jusqu'à
# porter préjudice aux autres scripts.
# 'no' = fichiers dont l'extension n'est pas reconnue par l'explorer
# 'rep' = répertoire
$CONFIG['MASK_TYPE_FILES'] = 'php,php3,php4,php5,pl,db';

#----------

# pour ajouter des extensions il faut editer le fichier 'filelibrary.inc.php'
# et ajouter autant de ligne que necessaire par extension.

#----------

# ce module permet vous permet de savoir en allant sur la page 'information & copyright'
# si une nouvelle version du programme est disponible et de la telecharger a partir de mon
# site!
$CONFIG['CHECK_MAJ'] = TRUE;
#----------

# Active ou desactive le gestionnaire d'image de l'explorer (TRUE ou FALSE)
# chaque image suivant l'image affiché est prechargé afin de ralentir les temps d'attente.
# ce module peut fonctionner independament.
$CONFIG['IMAGE_BROWSER'] = TRUE;

# adapte l'image a la taille de la fenetre
$CONFIG['AUTO_RESIZE'] = TRUE;

#----------

# Active ou desactive les vignettes pour les images (TRUE ou FALSE)
# ce module necessite la librairie GD et ralenti l'execution des scripts
# ce module peut fonctionner independament.
# Le module ne marche uniquement que dans les repertoirs contenant
# des fichiers jpg, bmp et gif.
$CONFIG['IMAGE_TN'] = TRUE;

# permet de specifier quelle fonction de creation des mignatures à utiliser!
# par defaut $CONFIG['GD2']=TRUE
# si vous rencontrez des problemes lors le la creation des vignettes, passez cette fonction à FALSE
# la majorité des hebergeurs gratuits ne propose pas les GD2, il faudra donc passer 'FALSE' pour creer les vignettes.
# la qualité des images ainsi créé si GD2 = FALSE sont en 256couleurs indexé (qualité médiocre)
$CONFIG['GD2'] = TRUE;

# active ou desactive le support jpg (par defaut activé)
$CONFIG['IMAGE_JPG'] = TRUE;

# active ou desactive le support gif
# consulter la prise en charge gif par votre hebergeur pour activer le support gif
$CONFIG['IMAGE_GIF'] = TRUE;

# active ou desactive le support wbmp
# consulter la prise en charge bmp par votre hebergeur pour activer le support wbmp
$CONFIG['IMAGE_BMP'] = FALSE;

# taille max de la vignette en pixel
# la taille de la vignette etant proportionnel a loriginal, cette valeur defini la
# taille maximal du plus grand coté de la vignette.
$CONFIG['IMAGE_TN_SIZE'] = 100;

# qualité (en %) de la vignette jpg
# on peut jouer sur la taille et la qualité de la vignette pour reduire son poids et le nombre de vignettes
# par ligne.
$CONFIG['IMAGE_TN_COMPRESSION'] = 60;

# nombre de vignettes par ligne
$CONFIG['NB_COLL_TN'] = 6;

#----------

# les EXIF sont les informations contenu dans les fichiers JPG pouvant indiquer
# la date de la photo, la focal, l'ouverture, le nom de l'appareil, etc etc etc.
# ce module permet d'afficher ces informations sur les images en disposant.
# IMPORTANT : si le support exif n'est pas activé chez votre hebergeur, la fonction
# sera automatiquement desactivé.
# --- ce module depend de IMAGE_BROWSER ---
$CONFIG['EXIF_READER'] = TRUE;

#----------

# module slideshow, l'intervale de temps entre 2 images est parametrable, les images sont adapté a lecran.
# --- ce module depend de IMAGE_BROWSER ---
$CONFIG['SLIDE_SHOW'] = TRUE;

# intervale entre 2 images (en secondes)
$CONFIG['SLIDE_SHOW_INT'] = 5;

# utilisé pour le debuggage!
$CONFIG['DEBUG'] = true;

# afficher ou non le bloc 'détail' dans l'arbre
$CONFIG['DETAILS'] = FALSE;

# durée de vie des fichiers '.dirinfo' (en jours) utilisé pour le bloc 'détail'
$CONFIG['DIRINFO_LIFE'] = 7;


#-----------------------------------------------------------------------------
#-----------------------------------------------------------------------------
# NE PAS TOUCHER TOUT CE QUI SUIT
if(!include_once(dirname(__FILE__).'/lang.inc.php'));
if(!include_once(dirname(__FILE__).'/functions.inc.php'));
if(!include_once(dirname(__FILE__).'/makeconfig.inc.php'));
if (isset($_SESSION['lang']) && file_exists(dirname(__FILE__).'/lang/lang.'.$_SESSION['lang'].'.ini'))	$CONFIG['SYS_LANG'] = $_SESSION['lang'];
?>