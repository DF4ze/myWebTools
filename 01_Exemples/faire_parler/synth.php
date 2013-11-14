<?php

/*
Classe de synthèse vocale PHP
@auteur : Valentin CARRUESCO aka idleman
@Licence : CC by

---

!!!! Installation

* Téléchargez et installez la librairie Espeak sur : http://espeak.sourceforge.net/download.html
* Configurer le chemin vers la librairie dans la variable CHEMIN_ESPEAK
* Lancez la page PHP (n'oubliez pas d'enclencher le son de vos écouteurs)

*/

define('CHEMIN_ESPEAK','C:\Program Files\eSpeak\command_line\espeak.exe');

class Voix{
	public $sexe = 'F';
	public $vitesse = 130;
	public $aigu = 30;
	private $chuchote = false;
	private $voix = '+f3';
	
	public function parle($phrase){
		switch($this->sexe){
				case 'Femme':
					$this->voix = '+f2';
				break;
				case 'Homme':
					$this->voix = '+m3';
				break;
		}
		if($this->chuchote)$this->voix = '+whisper';
		exec('"'.CHEMIN_ESPEAK.'" '.'-v french'.$this->voix.' -p '.$this->aigu.' -s '.$this->vitesse.' "'.$phrase.'"');
	}
	
	public function dis($phrase){
		$this->chuchote = false;
		$this->parle($phrase);
	}
	
	public function chuchote($phrase){
		$this->chuchote = true;
		$this->parle($phrase);
	}
}

//Creation d'une voix homme (Commissaire Biales)
$biales = new Voix();
$biales->sexe = 'Homme';
$biales->aigu = 40;
$biales->vitesse = 150;

//Creation d'une voix femme (Odile de Raie)
$odile = new Voix();
$odile->sexe = 'Femme';
$odile->aigu = 80;
$odile->vitesse = 170;

//Extrait de la cité de la peur (si c'est incohérent comme dialogue, c'est normal :D)
$odile->dis('Et ma première voiture, c\'était une peugeot');
$biales->dis('Je commanderais des clapiottes');
$biales->dis('Voulez vous un whisky Odile?');
$odile->dis('Oh juste un doigt');
$biales->dis('Vous ne voulez pas un whisky d\'abord?');
//A partir de la c'est plus vraiment la cité de la peur, mais je devais montrer le chuchottement
$biales->chuchote('Mais je ne suis pas contre le doigts après!!');

?>