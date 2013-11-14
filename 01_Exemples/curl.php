<?php

//Upload image
$proxy_user = '';//juste au boulot
$proxy_pass = '';//juste au boulot
$proxy_url = 'myproxy.spie.com:3128'; //juste au boulot
 

$url = 'http://webinfobazar.com/wp-content/uploads/2012/03/PHP1.png';
$img = "jackets/".basename( $url );

$ch = curl_init( $url );
$fp = fopen($img, 'wb');
curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_HEADER, 0);

curl_setopt($ch, CURLOPT_PROXY, $proxy_url);//juste au boulot
curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxy_user.':'.$proxy_pass);//juste au boulot
curl_setopt($ch, CURLOPT_PROXYPORT, 8080);//juste au boulot

curl_exec($ch);
curl_close($ch);
fclose($fp);

?>