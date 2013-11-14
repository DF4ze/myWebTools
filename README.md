myWebTools
==========

Plusieurs séries d'Outils Web, CodeTools et Exemples


--==============--
--== Exemples ==--
--==============--

Série de scripts que j'ai trouvé sympa,
mais également des tests qui mettent en évidence des points particuliés...




--=========================--
--== ReadyToUse_projects ==--
--=========================--
Dossiers à copier/coller sur un serveur Web.

* Fait Maison : 
-= ClassViewer : Ma petite "fierté".
---------------  copier/coller le source ClassViewer.php à la racine d'un projet PHP qui contient des classes.
                 ClassViewer va lister la totalité des classes et générer un "arbre de classes" 
                 en fonction de leur dépendances.
                 Il fait ressortir les Classes "feuilles" et liste la totalité des propriétés et méthodes publiques.
                 
                 Très pratique pour avoir un visuel global sur l'arborescence d'un projet.
                 
-= CountChar : en java, retourne la taille d'une chaine de caractère ... pratique pour éviter de compter jusqu'a 255 ;)
-------------

-= Up : FTP en mode HTTP très basique, fait maison.
------  - Un formulaire d'envoi des fichiers.
        - Un listing des fichiers sur le serveur avec lien pour les DL.
        

* Pas fait maison :
-= Partage Internet : FTP en mode HTTP, source que j'ai trouvé je ne sais plus où il y a bien longtemps.
-= YURI : CF le site d'Idleman ;) --> reconnaissance vocale


--=================--
--== Class Tools ==--
--=================--
Classes qui permettent de simplifier l'utilisation des diverses choses.

* fait maison
-= phpMyTab : Il est très souvent nécessaire de lister une base de données pour voir les utilisateurs, les groupes, les...
------------  phpMyTab permet de faire ceci pour vous en un minimum de paramètre : Base, Table, Logins.
              Mais si vous avez besoin d'un peu plus de présentation, phpMyTab vous mettra à disposition une série d'options
              vous permettant d'ajouter une pagination, régler l'emplacement de l'affichage des pages, personnaliser cet affichage
              Mais également proposer un champs de recherche, des options de filtrage, etc...
              
              La totalité des options est présente dans l'exemple : exemples.php
              
-= File Management : Simplificateur de gestion de fichier:
-------------------  - Creer
                     - Supprimer
                     - Renommer
                     - Uploader

-= Pile : Un tableau (implémentation de ArrayAccess) qui réagi comme une pile FILO.
--------  On défini une taille de pile lors du construct
          On insère :              $tab->add( $value  ); 
          La pile est pleine? :    $tab->is_full();
          Rechercher une valeur :  $tab->find( $value, $offset=0 );
          
          Toujours la possibilité d'attribuer des valeurs en utilisant les offsets : $tab[$i] = $value;
          
          ==> un Array mais avec des options en plus ;)

-= ComEraser : S'est voulu être un genre d'obfuscateur, c'est à dire, supprimer tout les commentaires d'un source.
-------------  Mais la version n'est pas aboutie...  suivre un jour


* Non fait Maison
-= easyRSS et rss_write : Comme le nom l'indique, il s'agit d'éditeurs de flux RSS. 
------------------------

-= Write Excel : Comme son nom l'indique également, bonne bibliothèque pour faire des fichiers Excel en php.
---------------  Très nombreuses options sont disponibles.
                 Très facile à utiliser.
                 
-= TimThumb : Création rapide de thumb (miniatures) à partir d'une collection d'images.
------------

-= vlc : Classe qui permet de piloter un VLC à distance.
-------  Est la base de mon "RemoteControlVlc" : 
         Avoir une page Web qui pilote plusieurs VLC et dans un esprit domotique, 
         permet de gérer la musique de plusieurs pièces.
         
         

Clément ORTIZ (14/11/2013)
