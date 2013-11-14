<?php

function file_library($zone){
                global $CONFIG, $LANGUE;
                if(@is_dir($zone)){
                        $info['ico'] = 'rep';
                        $info['size'] = '';
                        $info['type'] = $LANGUE['type']['dossier'];
                        $info['date'] = date($LANGUE['divers']['format_date_heure'], filemtime($zone));
                        $info['ext'] = 'rep';
                        return $info;
                }

                if(@is_file($zone)){
                        $info['size'] = convertUnits(filesize($zone));

                        $zone_explode = explode('.', $zone);
                        $zone_explode_len = count($zone_explode);
                        $ext = $zone_explode[$zone_explode_len-1];

                        $ico_info = ext_library(strtolower($ext));

                        $info['ico'] = $ico_info['ico'];
                        $info['type'] = $ico_info['type'];
                        $info['date'] = date($LANGUE['divers']['format_date_heure'], filemtime($zone));
                        $info['ext'] = $ico_info['ext'];
                        return $info;
                }
                return $info = false;
}


function ext_library($ext) {
                global $CONFIG, $LANGUE;
                $info['ico'] = 'no';
                $info['type'] = $LANGUE['type']['_no'];
                $info['ext'] = $ext;

# association des extentions
                if($ext == 'jpg') { $info['ico'] = 'jpg' ; $info['type'] = $LANGUE['type']['jpg']; }
                if($ext == 'raw') { $info['ico'] = 'bmp' ; $info['type'] = $LANGUE['type']['raw']; }
                if($ext == 'bmp') { $info['ico'] = 'bmp' ; $info['type'] = $LANGUE['type']['bmp']; }
                if($ext == 'gif') { $info['ico'] = 'gif' ; $info['type'] = $LANGUE['type']['gif']; }
                if($ext == 'dll') { $info['ico'] = 'dll' ; $info['type'] = $LANGUE['type']['dll']; }
                if($ext == 'vxd') { $info['ico'] = 'dll' ; $info['type'] = $LANGUE['type']['vxd']; }
                if($ext == 'sys') { $info['ico'] = 'dll' ; $info['type'] = $LANGUE['type']['sys']; }
                if($ext == 'doc') { $info['ico'] = 'doc' ; $info['type'] = $LANGUE['type']['doc']; }
                if($ext == 'pdf') { $info['ico'] = 'pdf' ; $info['type'] = $LANGUE['type']['pdf']; }
                if($ext == 'exe') { $info['ico'] = 'exe' ; $info['type'] = $LANGUE['type']['exe']; }
                if($ext == 'hlp') { $info['ico'] = 'hlp' ; $info['type'] = $LANGUE['type']['hlp']; }
                if($ext == 'html') { $info['ico'] = 'html' ; $info['type'] = $LANGUE['type']['html']; }
                if($ext == 'htm') { $info['ico'] = 'html' ; $info['type'] = $LANGUE['type']['htm']; }
                if($ext == 'ini') { $info['ico'] = 'ini' ; $info['type'] = $LANGUE['type']['ini']; }
                if($ext == 'avi') { $info['ico'] = 'mpg' ; $info['type'] = $LANGUE['type']['avi']; }
                if($ext == 'mpg') { $info['ico'] = 'mpg' ; $info['type'] = $LANGUE['type']['mpg']; }
                if($ext == 'mpeg') { $info['ico'] = 'mpg' ; $info['type'] = $LANGUE['type']['mpeg']; }
                if($ext == 'asf') { $info['ico'] = 'mpg' ; $info['type'] = $LANGUE['type']['asf']; }
                if($ext == 'mp3') { $info['ico'] = 'mpg' ; $info['type'] = $LANGUE['type']['mp3']; }
                if($ext == 'wav') { $info['ico'] = 'mpg' ; $info['type'] = $LANGUE['type']['wav']; }
                if($ext == 'mid') { $info['ico'] = 'mpg' ; $info['type'] = $LANGUE['type']['mid']; }
                if($ext == 'rmi') { $info['ico'] = 'mpg' ; $info['type'] = $LANGUE['type']['rmi']; }
                if($ext == 'wma') { $info['ico'] = 'mpg' ; $info['type'] = $LANGUE['type']['wma']; }
                if($ext == 'wmv') { $info['ico'] = 'mpg' ; $info['type'] = $LANGUE['type']['wmv']; }
                if($ext == 'drv') { $info['ico'] = 'dll' ; $info['type'] = $LANGUE['type']['drv']; }
                if($ext == 'js') { $info['ico'] = 'php' ; $info['type'] = $LANGUE['type']['js']; }
                if($ext == 'asp') { $info['ico'] = 'php' ; $info['type'] = $LANGUE['type']['asp']; }
                if($ext == 'php') { $info['ico'] = 'php' ; $info['type'] = $LANGUE['type']['php']; }
                if($ext == 'php3') { $info['ico'] = 'php' ; $info['type'] = $LANGUE['type']['php3']; }
                if($ext == 'php4') { $info['ico'] = 'php' ; $info['type'] = $LANGUE['type']['php4']; }
                if($ext == 'php5') { $info['ico'] = 'php' ; $info['type'] = $LANGUE['type']['php5']; }
                if($ext == 'css') { $info['ico'] = 'php' ; $info['type'] = $LANGUE['type']['css']; }
                if($ext == 'reg') { $info['ico'] = 'reg' ; $info['type'] = $LANGUE['type']['reg']; }
                if($ext == 'ttf') { $info['ico'] = 'ttf' ; $info['type'] = $LANGUE['type']['ttf']; }
                if($ext == 'fon') { $info['ico'] = 'ttf' ; $info['type'] = $LANGUE['type']['fon']; }
                if($ext == 'txt') { $info['ico'] = 'txt' ; $info['type'] = $LANGUE['type']['txt']; }
                if($ext == 'c') { $info['ico'] = 'txt' ; $info['type'] = $LANGUE['type']['c']; }
                if($ext == 'cpp') { $info['ico'] = 'txt' ; $info['type'] = $LANGUE['type']['cpp']; }
                if($ext == 'c++') { $info['ico'] = 'txt' ; $info['type'] = $LANGUE['type']['c++']; }
                if($ext == 'jpeg') { $info['ico'] = 'jpg' ; $info['type'] = $LANGUE['type']['jpeg']; }
                if($ext == 'jpe') { $info['ico'] = 'jpg' ; $info['type'] = $LANGUE['type']['jpe']; }
                if($ext == 'png') { $info['ico'] = 'jpg' ; $info['type'] = $LANGUE['type']['png']; }
                if($ext == 'xls') { $info['ico'] = 'xls' ; $info['type'] = $LANGUE['type']['xls']; }
                if($ext == 'dat') { $info['ico'] = 'txt' ; $info['type'] = $LANGUE['type']['dat']; }
                if($ext == 'log') { $info['ico'] = 'txt' ; $info['type'] = $LANGUE['type']['log']; }
                if($ext == 'zip') { $info['ico'] = 'zip' ; $info['type'] = $LANGUE['type']['zip']; }
                if($ext == 'tar') { $info['ico'] = 'zip' ; $info['type'] = $LANGUE['type']['tar']; }
                if($ext == 'gz') { $info['ico'] = 'zip' ; $info['type'] = $LANGUE['type']['gz']; }
                if($ext == 'ace') { $info['ico'] = 'zip' ; $info['type'] = $LANGUE['type']['ace']; }
                if($ext == 'rar') { $info['ico'] = 'zip' ; $info['type'] = $LANGUE['type']['rar']; }
                if($ext == 'inf') { $info['ico'] = 'ini' ; $info['type'] = $LANGUE['type']['inf']; }

                #if($ext == "extension.du.fichier") { $info['ico'] = 'nom.du.fichier.icone' ; $info['type'] = "information.sur.le.type.de.fichier"; }

                return $info;
}


function exif($file) {
    global $CONFIG, $LANGUE;
    $err = FALSE;
    $string_output = '';
    $exif_array = @exif_read_data($file) or $err = TRUE;
    if(empty($exif_array['Model'])) $err = TRUE;

    if(!$err) {

            if(!isset($exif_array['Flash'])) $exif_array['Flash'] = $LANGUE['exif']['inconnu'];
            else if($exif_array['Flash']==0) $exif_array['Flash'] = $LANGUE['exif']['Flash0'];
            else if($exif_array['Flash']==1) $exif_array['Flash'] = $LANGUE['exif']['Flash1'];
            else if($exif_array['Flash']==5) $exif_array['Flash'] = $LANGUE['exif']['Flash5'];
            else if($exif_array['Flash']==7) $exif_array['Flash'] = $LANGUE['exif']['Flash7'];
            else $exif_array['Flash'] = $LANGUE['exif']['inconnu'];

            if(!isset($exif_array['LightSource'])) $exif_array['LightSource'] = $LANGUE['exif']['inconnu'];
            else if($exif_array['LightSource']==0) $exif_array['LightSource'] = $LANGUE['exif']['indetermine'];
            else if($exif_array['LightSource']==1) $exif_array['LightSource'] = $LANGUE['exif']['LightSource1'];
            else if($exif_array['LightSource']==2) $exif_array['LightSource'] = $LANGUE['exif']['LightSource2'];
            else if($exif_array['LightSource']==3) $exif_array['LightSource'] = $LANGUE['exif']['LightSource3'];
            else if($exif_array['LightSource']==10) $exif_array['LightSource'] = $LANGUE['exif']['LightSource10'];
            else if($exif_array['LightSource']==17) $exif_array['LightSource'] = $LANGUE['exif']['LightSource17'];
            else if($exif_array['LightSource']==18) $exif_array['LightSource'] = $LANGUE['exif']['LightSource18'];
            else if($exif_array['LightSource']==19) $exif_array['LightSource'] = $LANGUE['exif']['LightSource19'];
            else if($exif_array['LightSource']==20) $exif_array['LightSource'] = $LANGUE['exif']['LightSource20'];
            else if($exif_array['LightSource']==21) $exif_array['LightSource'] = $LANGUE['exif']['LightSource21'];
            else if($exif_array['LightSource']==22) $exif_array['LightSource'] = $LANGUE['exif']['LightSource22'];
            else $exif_array['LightSource'] = $LANGUE['exif']['inconnu'];

            if(!isset($exif_array['ExposureProgram'])) $exif_array['ExposureProgram'] = $LANGUE['exif']['inconnu'];
            else if($exif_array['ExposureProgram']==1) $exif_array['ExposureProgram'] = $LANGUE['exif']['ExposureProgram1'];
            else if($exif_array['ExposureProgram']==2) $exif_array['ExposureProgram'] = $LANGUE['exif']['ExposureProgram2'];
            else if($exif_array['ExposureProgram']==3) $exif_array['ExposureProgram'] = $LANGUE['exif']['ExposureProgram3'];
            else if($exif_array['ExposureProgram']==4) $exif_array['ExposureProgram'] = $LANGUE['exif']['ExposureProgram4'];
            else if($exif_array['ExposureProgram']==5) $exif_array['ExposureProgram'] = $LANGUE['exif']['ExposureProgram5'];
            else if($exif_array['ExposureProgram']==6) $exif_array['ExposureProgram'] = $LANGUE['exif']['ExposureProgram6'];
            else if($exif_array['ExposureProgram']==7) $exif_array['ExposureProgram'] = $LANGUE['exif']['ExposureProgram7'];
            else if($exif_array['ExposureProgram']==8) $exif_array['ExposureProgram'] = $LANGUE['exif']['ExposureProgram8'];
            else $exif_array['ExposureProgram'] = $LANGUE['exif']['inconnu'];

            if(!isset($exif_array['MeteringMode'])) $exif_array['MeteringMode'] = $LANGUE['exif']['inconnu'];
            else if($exif_array['MeteringMode']==1) $exif_array['MeteringMode'] = $LANGUE['exif']['indetermine'];
            else if($exif_array['MeteringMode']==2) $exif_array['MeteringMode'] = $LANGUE['exif']['MeteringMode2'];
            else if($exif_array['MeteringMode']==3) $exif_array['MeteringMode'] = $LANGUE['exif']['MeteringMode3'];
            else if($exif_array['MeteringMode']==4) $exif_array['MeteringMode'] = $LANGUE['exif']['MeteringMode4'];
            else if($exif_array['MeteringMode']==5) $exif_array['MeteringMode'] = $LANGUE['exif']['MeteringMode5'];
            else if($exif_array['MeteringMode']==6) $exif_array['MeteringMode'] = $LANGUE['exif']['MeteringMode6'];
            else if($exif_array['MeteringMode']==7) $exif_array['MeteringMode'] = $LANGUE['exif']['MeteringMode7'];
            else $exif_array['MeteringMode'] = $LANGUE['exif']['inconnu'];

            if(!empty($exif_array['FileName'])) $string_output .= '<b>'.$LANGUE['exif']['FileName'].' : </b>'.$exif_array['FileName'].'<br />';
            if(!empty($exif_array['DateTime']))
            {
            	$tempo = explode(' ', $exif_array['DateTime']);
            	$ladate = explode(':', $tempo[0]);
            	$lheure = explode(':', $tempo[1]);
            	
            	$annee = $ladate[0];
            	$mois = $ladate[1];
            	$jour = $ladate[2];
            	$heure = $lheure[0];
            	$minute = $lheure[1];
            	$seconde = $lheure[2];
            	$string_output .= '<b>'.$LANGUE['exif']['DateTime'].' : </b>'.date($LANGUE['divers']['format_date_heure_full'], mktime($heure, $minute, $seconde, $mois, $jour, $annee)).'<br />';
            }
            if(!empty($exif_array['ImageDescription'])) $string_output .= '<b>'.$LANGUE['exif']['ImageDescription'].' : </b>'.$exif_array['ImageDescription'].'<br />';
            if(!empty($exif_array['Make'])) $string_output .= '<b>'.$LANGUE['exif']['Make'].' : </b>'.$exif_array['Make'].'<br />';
            if(!empty($exif_array['Model'])) $string_output .= '<b>'.$LANGUE['exif']['Model'].' : </b>'.$exif_array['Model'].'<br />';
            if(!empty($exif_array['ExifImageWidth'])) $string_output .= '<b>'.$LANGUE['exif']['ExifImageWidth'].' : </b>'.$exif_array['ExifImageWidth'].'*'.$exif_array['ExifImageLength'].'<br />';
            if(!empty($exif_array['ExposureTime'])) $string_output .= '<b>'.$LANGUE['exif']['ExposureTime'].' : </b>'.$exif_array['ExposureTime'].'sec<br />';
            if(!empty($exif_array['FocalLength'])) $string_output .= '<b>'.$LANGUE['exif']['FocalLength'].' : </b>'.$exif_array['FocalLength'].'mm<br />';
            if(!empty($exif_array['FocalLengthIn35mmFilm'])) $string_output .= '<b>'.$LANGUE['exif']['FocalLengthIn35mmFilm'].': </b>'.$exif_array['FocalLengthIn35mmFilm'].'mm<br />';
            if(!empty($exif_array['FNumber'])) $string_output .= '<b>'.$LANGUE['exif']['FNumber'].''.$exif_array['FNumber'].'</b><br />';
            if(!empty($exif_array['ISOSpeedRatings'])) $string_output .= '<b>'.$LANGUE['exif']['ISOSpeedRatings'].' : </b>'.$exif_array['ISOSpeedRatings'].'<br />';
            if(!empty($exif_array['Flash'])) $string_output .= '<b>'.$LANGUE['exif']['Flash'].' '.$exif_array['Flash'].'</b><br />';
            if(!empty($exif_array['ExposureProgram'])) $string_output .= '<b>'.$LANGUE['exif']['ExposureProgram'].' : </b>'.$exif_array['ExposureProgram'].'<br />';
            if(!empty($exif_array['LightSource'])) $string_output .= '<b>'.$LANGUE['exif']['LightSource'].' : </b>'.$exif_array['LightSource'].'<br />';
            if(!empty($exif_array['MeteringMode'])) $string_output .= '<b>'.$LANGUE['exif']['MeteringMode'].' : </b>'.$exif_array['MeteringMode'].'<br />';

    }
    else $string_output = FALSE;
    return $string_output;
}


function iSort(&$input) {

                if(!is_array($input)) return false;

                $sort = '';
                $output = '';

                for($i = 0; $i < count($input); $i++) $sort[$i] = strtolower($input[$i]);

                asort($sort);
                reset($sort);

                while(list($key,$val) = each($sort)) $output[] = $input[$key];
                $input =  $output;
                return true;
}


function resize_text($texte) {
        if (strlen($texte)>12){
                $texte = substr($texte, 0, 12);
                $texte = $texte.'...';
        }
        return $texte;
}


function convertUnits($size) {
	global $LANGUE;
        $kb = 1024;        // Kilobyte
        $mb = 1024 * $kb;  // Megabyte
        $gb = 1024 * $mb;  // Gigabyte
        $tb = 1024 * $gb;  // Terabyte
        //if($size==0) return "0 octets";
        if($size < $kb) {
        	return $size.' '.$LANGUE['divers']['unit'];
        }
        else if($size < $mb) {
                return round($size/$kb,1).' K'.$LANGUE['divers']['unit'];
        } 
        else if($size < $gb) {
                return round($size/$mb,2).' M'.$LANGUE['divers']['unit'];
        }
        else if($size < $tb) {
                return round($size/$gb,2).' G'.$LANGUE['divers']['unit'];
        }
        else{
                return round($size/$tb,2).' T'.$LANGUE['divers']['unit'];
        }
}


function RecursiveSize($dir){
        $h = opendir($dir);
        if(!isset($size)) $size = 0;
        while(FALSE !== ($fp = readdir($h))){
                $link = $dir.'/'.$fp;
                if($fp != '.' && $fp != '..'){
                        if(is_dir($link)) $size+=RecursiveSize($link);
                        else $size+=filesize($link);
                }
        }
        closedir($h);
        return $size;  // in bytes
}


function SelectAffichType($link,$zone_source,$CONFIG){
#  $zone_source : nom du repertoir a scanner
#  $info[] : information sur le repertoir a scanner (donc un repertoir)
#  $link : path du repertoir courant ouvert.
#  $scan_rep : path du repertoir a scanner.
#  $scan_rep_ask : nom du fichier ou repertoir en cours de scan.
        $show_tn_val = FALSE;
        if($CONFIG['IMAGE_TN'] && is_dir($link.$zone_source)) {
                $scan_rep = AddLastSlashes($link).$zone_source;                    // creation du path du repertoir a scanner
                $handle_scan_rep = opendir($scan_rep);             // ouverture du path du repertoir a scanner

                while (false !== ($scan_rep_ask = readdir($handle_scan_rep))){   // boucle de recherche de fichier
                        if($scan_rep_ask[0] != '.'){
                                //echo $scan_rep.$scan_rep_ask;
                                $info_ask = file_library(AddLastSlashes($scan_rep).$scan_rep_ask);
                                //echo AddLastSlashes($scan_rep).$scan_rep_ask;
                                //print_r($info_ask);
                                if(($info_ask['ico']=='jpg' && $CONFIG['IMAGE_JPG']) || ($info_ask['ico']=='bmp' && $CONFIG['IMAGE_BMP']) || ($info_ask['ico']=='gif' && $CONFIG['IMAGE_GIF'])) {
                                        $show_tn_val = TRUE;
                                        break;
                                }
                        }
                }
        }
        return $show_tn_val;
}


function RemoveLastSlashes($d){
        if(empty($d)) return false;
        if(($d[strlen($d)-1] == '\\') || ($d[strlen($d)-1] == '/')) $d = substr($d,0 , -1);
        return $d;
}


function AddLastSlashes($d){
        if(empty($d)) return false;
        if(!(($d[strlen($d)-1] == '\\') || ($d[strlen($d)-1] == '/'))) $d = $d.'/';
        return $d;
}


function AddFirstSlashes($d){
        if(empty($d)) return '/';
        if(!(($d[0] == '\\') || ($d[0] == '/'))) $d = '/'.$d;
        return $d;
}


function RemoveFirstChar($d,$c){
        if(empty($d)) return false;
        if($d[0] == $c) $d = substr($d,1);
        return $d;
}


if(!function_exists('fnmatch')){
	function fnmatch($pattern, $file){
	        for($i=0; $i<strlen($pattern); $i++) {
	                if($pattern[$i] == '*') {
	                        for($c=$i; $c<max(strlen($pattern), strlen($file)); $c++) {
	                                if(fnmatch(substr($pattern, $i+1), substr($file, $c))) {
	                                        return true;
	                                }
	                        }
	                        return false;
	                }
	                if($pattern[$i] == '[') {
	                        $letter_set = array();
	                        for($c=$i+1; $c<strlen($pattern); $c++) {
	                                if($pattern[$c] != ']') {
	                                        array_push($letter_set, $pattern[$c]);
	                                }
	                                else break;
	                        }
	                        foreach ($letter_set as $letter) {
	                                if(my_fnmatch($letter.substr($pattern, $c+1), substr($file, $i))) {
	                                        return true;
	                                }
	                        }
	                        return false;
	                }
	                if($pattern[$i] == '?') {
	                        continue;
	                }
	                if($pattern[$i] != $file[$i]) {
	                        return false;
	                }
	        }
	        return true;
	}
}


function FindRecursiv($dir,$match,$casesensitive){
        global $CONFIG;
        $fileListToHide = file('../hide.php');
        $dir = RemoveLastSlashes($dir);
        $h = opendir($dir);
        static $tab = array();
        while(FALSE !== ($fp = readdir($h))) {
                $link = $dir.'/'.$fp;
                if($fp[0] != '.' && $fp != '..' && !parseListToHide($fileListToHide,$fp,$CONFIG)){
                        if($casesensitive) { $match = strtolower($match); $fp = strtolower($fp);}
                        if (fnmatch($match, $fp)) {
                                  $tab[] = $link;
                        }
                        if(is_dir($link)) FindRecursiv($link,$match,$casesensitive);
                }
        }
        closedir($h);
        return $tab;
}


function String2Array($str){
        $l = strlen($str);
        for($i=0;$i<$l;$i++){
                $t[$i] = $str[$i];
        }
        return $t;
}


function Array2String($t){
        $l = sizeof($t)+1;
        for($i=0;$i<$l;$i++){
                $str .= $t[$i];
        }
        return $str;
}


function Win2UnixShlash($s){
        return strtr($s, '\\', '/');
}


function EncodeForUrl($uri) {
        $parts = explode('/', $uri);
        for ($i = 0; $i < count($parts); $i++) {
                $parts[$i] = rawurlencode($parts[$i]);
        }
        return implode('/', $parts);
}

if (!function_exists('http_build_query')){
	function http_build_query(&$a,$pref,$f='',$idx=''){
	        $ret = '';
	        foreach ($a as $i => $j){
	                if ($idx != '') $i = $idx."[$i]";
	                if (is_array($j)) $ret .= http_build_query($j,'',$f,$i);
	                else{
	                        $j=rawurlencode(stripslashes($j));
	                        if (is_int($i)) $ret .= "$f$pref$i=$j";
	                        else $ret .= "$f$i=$j";
	                }
	                $f='&';
	        }
	        return $ret;
	}
}

function ListModules(){
        global $CONFIG,$LANGUE;
        $tListModules = array();
        $dir =  Win2UnixShlash(AddLastSlashes(dirname(__FILE__))).'modules/';

        $handle = opendir($dir);
        while (false !== ($file = readdir($handle)))
        {
            if(is_file($dir.$file))
            {
                 if ($file != 'index.php')
                 {
                     $file_explode = explode('.', $file);
                     $file_explode_len = count($file_explode);
                     $ext = $file_explode[$file_explode_len-1];
                     if ($ext == 'php' || $ext == 'php3' || $ext == 'php4' || $ext == 'php5')
                        $tListModules[] = $file;
                 }
            }
        }
        closedir($handle);
        iSort($tListModules);
        
        $isAuth = false;
        if (file_exists('modules/auth/func.inc.php') && CheckAuth('modules/auth/auth.inc.php')==1)
           $isAuth = true;


        echo ' <table>'."\r\n";
        for($i=0;$i<count($tListModules);$i++){
                $AdminModule = true;
                $EnableModule = true;

                include_once($dir.$tListModules[$i]);

                if(!$EnableModule) continue;

                if(!(!$isAuth && $AdminModule))
                {   echo '            <tr>'."\r\n";
                    echo '             <td class="titre1">'."\r\n";
                    echo '              <a href="modules/'.$tListModules[$i].'" target="main"><img src="modules/'.$ModuleIco.'" alt="'.$ModuleTitle.'" title="'.$ModuleTitle.'" class="ico"> '.$ModuleTitle.'</a>'."\r\n";
                    echo '             </td>'."\r\n";
                    echo '            </tr>'."\r\n";
                }
        }
        echo '           </table>'."\r\n";
}

function parseListToHide($t,$path,$CONFIG){
        $output = array();

        while(list($k,$v) = each($t)){
                $v = trim($v);
                if(empty($v)) continue;
                if($v[0] == "<") continue;
                if($v[0] == "#") continue;
                $output[] = $v;

        }

        $SiCeciEstVraisAlorsLeFichierEstCache = false;
        while(list($klth,$vlth) = each($output)){
                $comparonsca = strtolower($CONFIG['DOCUMENT_ROOT'].RemoveFirstChar(RemoveLastSlashes(Win2UnixShlash($vlth)),'/'));
                $avecca = strtolower(Win2UnixShlash($path));
                if($comparonsca == $avecca) {
                        $SiCeciEstVraisAlorsLeFichierEstCache = true;
                        break;
                }
        }
        reset($output);
        return ($SiCeciEstVraisAlorsLeFichierEstCache);
}


// cette fonction prends un chemin 'sale' et le nettoie!
// il retourne un chemin canonique.
function resolvePath($path){
        $t = explode('/',RemoveLastSlashes($path));
        $unix = false;
        if($t[0]===''){
                $unix = true;
        }
        while(list($i,)=each($t)) if(trim($t[$i])==='' || $t[$i] === '.') unset($t[$i]);
        reset($t);
        delDblPlot($t);
        if($unix) return '/'.implode('/',$t);
        else return implode('/',$t);

}


// fonction interne! non utile
// sert pour resolvePath()
function delDblPlot(&$t){
        $t = array_values($t);
        while(list($i,)=each($t)) if($t[$i] == '..'){
                unset($t[$i-1]);
                unset($t[$i]);
                delDblPlot($t);
                return false;
        }
}


// prends un fichier et generer une clee md5 pseudo unique
function checkFile($f){
        if(!file_exists($f)) return false;

        //return md5_file($f);
        return md5($f." ".sizeof($f));
}


// prends un tableau (en general $_GET) et le transforme en chemin (url)
// retourne un tableau a 3 index :
// - path               : contient le chemin complet
// - last               : contient le dernier element du chemin
// - pathwithoutlast    : contient le chemin sans le dernier element
function makePath($get){
        $buff = array();
        $out = array();
        $i =0;
        if(!isset($get)) return false;
        while(list($k,) = each($get)){
                if( ereg("^\.\.*\.$",$get[$k]) || ereg("/",$get[$k])) return false;
                $buff[] = rawurldecode($get[$k]);
                //echo $get[$k]." // ".$buff[$i++]."<br>";
        }
        $out['path'] = '/';
        $out['last'] = '/';
        $out['pathwithoutlast'] = '/';
        for($i=0;$i<count($buff);$i++){
                $out['path'] .= $buff[$i].'/';
                if($i<(count($buff)-1))
                        $out['pathwithoutlast'] .= $buff[$i].'/';
        }
        if(isset($buff[count($buff)-1]))
                $out['last'] = $buff[count($buff)-1];
        return $out;
}


// cette fonction soustrait $path2 à $path1 ( $path1 - $path2 )
// et retourne la difference sous forme d'un chemin relatif pour
// aller a $path1 en partant de $path2
function SoustractPath($path1,$path2){

        $out = '/';
        $t1 = explode('/',RemoveFirstChar(RemoveLastSlashes($path1),'/'));
        $t2 = explode('/',RemoveFirstChar(RemoveLastSlashes(Win2UnixShlash($path2)),'/'));

        $s1 = count($t1);
        $s2 = count($t2);

        $sMax = ($s1>$s2)?$s1:$s2;
        $sMin = ($s1<=$s2)?$s1:$s2;

        for($i=0;$i<$sMin;$i++){
                if($t1[$i] == $t2[$i]){
                        unset($t1[$i]);
                        unset($t2[$i]);
                }
        }
        $t1 = array_values($t1);
        $t2 = array_values($t2);
        
        $s1 = count($t1);
        $s2 = count($t2);

        for($i=0;$i<$s2;$i++) $out .= '../';
        for($i=0;$i<$s1;$i++) $out .= $t1[$i].'/';
        return $out;
}


/**
 * Echape tous les caractères d'une chaine
 *
 * echapChaine('abcd') = '\a\b\c\d';
 * 
 * @param string $str
 * @return string
 */
function echapChaine($str)
{
	$strNew = '';
	$long = strlen($str);
	for ($i=0; $i<$long; $i++)
		$strNew = $strNew.'\\'.substr($str, $i, 1);
	return $strNew;
}
/**
    * Calcul recursif du nombre de fichiers et dossiers contenus dans un dossier
    * @Private
    * @Param string $DIR        Chemin du fichier
    * @Param int $CORE      Ajout d'un nom de dossier au chemin
    * @return string        retourne en littéral X fichier(s) et Y dossier(s).
*/

$FOL = 0;
$FIL = 0;

function CountF($DIR, $CORE){
    global $FOL;
    global $FIL;
        if (is_dir($DIR)){
    if ($ODIR = opendir($DIR)){
        while ($FILE = readdir($ODIR)){
            if ( ($FILE != '.') && ($FILE != '..') && ($FILE != 'dirsys') && ($FILE != 'Thumbs.db') && ($FILE != '.dirinfo')){
                $TMP = $DIR.'/'.$FILE ;
                if (is_dir($TMP)){
            $FOL++;
                    CountF($TMP, $FILE) ;}
                else{$FIL++;}}}}
$rtn = $FIL.'|'.$FOL;
return $rtn;}}

/**
    * Convertion de la date de modification du fichier en Français
    * @Private
    * @Param string $pass       Chemin du fichier
    * @echo             Affiche directement "jour en littéral jour en chiffre mois et année" exemple "lundi 5 mars 2005".
*/

function datefix($pass)
{ global $CONFIG, $LANGUE;

	$datemod = filemtime($pass);
	$day = date ('w', $datemod);
	$day = str_replace('1', $LANGUE['jour'][1], $day);
	$day = str_replace('2', $LANGUE['jour'][2], $day);
	$day = str_replace('3', $LANGUE['jour'][3], $day);
	$day = str_replace('4', $LANGUE['jour'][4], $day);
	$day = str_replace('5', $LANGUE['jour'][5], $day);
	$day = str_replace('6', $LANGUE['jour'][6], $day);
	$day = str_replace('0', $LANGUE['jour'][0], $day);

	$month = date ('m', $datemod);
	$month = str_replace('01', $LANGUE['mois'][1], $month);
	$month = str_replace('02', $LANGUE['mois'][2], $month);
	$month = str_replace('03', $LANGUE['mois'][3], $month);
	$month = str_replace('04', $LANGUE['mois'][4], $month);
	$month = str_replace('05', $LANGUE['mois'][5], $month);
	$month = str_replace('06', $LANGUE['mois'][6], $month);
	$month = str_replace('07', $LANGUE['mois'][7], $month);
	$month = str_replace('08', $LANGUE['mois'][8], $month);
	$month = str_replace('09', $LANGUE['mois'][9], $month);
	$month = str_replace('10', $LANGUE['mois'][10], $month);
	$month = str_replace('11', $LANGUE['mois'][11], $month);
	$month = str_replace('12', $LANGUE['mois'][12], $month);
	
	$format = $LANGUE['divers']['format_date_full'];
	$format = str_replace('lib_jour', echapChaine($day), $format);
	$format = str_replace('lib_mois', echapChaine($month), $format);
	
	$ladate = date($format, $datemod).' '.date($LANGUE['divers']['format_heure'], $datemod);
	
	return $ladate;
}

/**
    * Création et lecture d'un fichier .dirinfo contenant le nombre de fichiers et dossiers ainsi que la taille d'un dossier
    * @Private
    * @Param string $pass       Chemin du fichier
    * @Param int $return        Afficher ou non le résultat: 1 pour afficher 0 pour ne pas afficher.
    * @return string        Renvoi deux lignes d'un tableau avec le contenu des dossiers.
*/

function DirInfoTime ($pass,$return) {
    global $CONFIG,$LANGUE;
    $val = 86400; // 24 heures en secondes
    $comp = $CONFIG['DIRINFO_LIFE']*$val;
    if (!is_file($pass)){
        if (!file_exists($pass.'/.dirinfo') || ((time() - filemtime($pass.'/.dirinfo')) >= $comp)) {
            $fp=@fopen($pass.'/.dirinfo','w');
            $countF = CountF($pass, '/');
            $sizeF = convertUnits(RecursiveSize($pass));
            $buff = '<?php'."\r\n";
            $buff .= '$FolderCount = \'' . $countF . '\';'."\r\n";
            $buff .= '$FolderSize = \'' . $sizeF . '\';'."\r\n";
            $buff .= '?>';
            fwrite($fp,$buff);
            @fclose($fp);
        }
        if ($return == '1') {
           include($pass.'/.dirinfo');
           $count = explode('|', $FolderCount);

           if ($count[0] < 2)
              $chaine_file = $count[0].' '.$LANGUE['arbre_detail']['fichier'];
              else
                $chaine_file = $count[0].' '.$LANGUE['arbre_detail']['fichiers'];
           if ($count[1] < 2)
              $chaine_folder = $count[1].' '.$LANGUE['arbre_detail']['dossier'];
              else
                $chaine_folder = $count[1].' '.$LANGUE['arbre_detail']['dossiers'];

           $contents['count'] = $chaine_file.', '.$chaine_folder;
           $contents['size'] = $FolderSize;
           return $contents;
           }
    }
}


// depreciated

function RemovShash($d){
        $debug = debug_backtrace();
        echo '<br /><b>Notice</b>:  Function Depreciated: '.$debug[0]['function'].'() ! Use RemoveLastSlashes(String $path) in <b>'.$debug[0]['file'].'</b> on line <b>'.$debug[0]['line'].'</b><br />';
        return RemoveLastSlashes($d);
}

function AddShash($d){
        $debug = debug_backtrace();
        echo '<br /><b>Notice</b>:  Function Depreciated: '.$debug[0]['function'].'() ! Use AddLastSlashes(String $path) in <b>'.$debug[0]['file'].'</b> on line <b>'.$debug[0]['line'].'</b><br />';
        return AddLastSlashes($d);
}

function AddFirstShash($d){
        $debug = debug_backtrace();
        echo '<br /><b>Notice</b>:  Function Depreciated: '.$debug[0]['function'].'() ! Use AddFirstSlashes(String $path) in <b>'.$debug[0]['file'].'</b> on line <b>'.$debug[0]['line'].'</b><br />';
        return AddFirstSlashes($d);
}

function SetRelativPath($rm, $abs){
        $debug = debug_backtrace();
        echo '<br /><b>Warning</b>:  Function Modified: '.$debug[0]['function'].'() ! Use SoustractPath(String $path1,String $path2) in <b>'.$debug[0]['file'].'</b> on line <b>'.$debug[0]['line'].'</b><br />';
        return SoustractPath($rm,$abs);
}

function TranslateUri($uri){
        $debug = debug_backtrace();
        echo '<br /><b>Notice</b>:  Function Depreciated: '.$debug[0]['function'].'() ! Use EncodeForUrl(String $path) in <b>'.$debug[0]['file'].'</b> on line <b>'.$debug[0]['line'].'</b><br />';
        return EncodeForUrl($uri);
}

?>