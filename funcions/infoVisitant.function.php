<?php

/*
 * Funció que retorna certa informació del usuari
 *
 * user agent
 * navegador
 * versió del navegador
 * sistema operatiu
 * pagina web de referencia
 * idiomes del navegador
 * 
 * */

function infoVisitant() { 
    $u_agent = $_SERVER['HTTP_USER_AGENT']; 
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";

	if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) { $laip = $_SERVER['HTTP_X_FORWARDED_FOR']; }
	elseif (isset($_SERVER['HTTP_VIA'])) { $laip = $_SERVER['HTTP_VIA']; }
	elseif (isset($_SERVER['REMOTE_ADDR'])) { $laip = $_SERVER['REMOTE_ADDR']; }
	else { $laip = "Desconeguda"; }    

    // Sistema operatiu
    if (preg_match('/linux/i', $u_agent) && !preg_match('/android/i', $u_agent)) {
        $platform = 'Linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'Mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'Windows';
    } elseif (preg_match('/android/i', $u_agent)) {
        $platform = 'Android';
    } elseif (preg_match('/iphone/i', $u_agent)||preg_match('/ipod/i', $u_agent)) {
        $platform = 'iPhone';
    } elseif (preg_match('/(pre\/|palm os|palm|hiptop|avantgo|plucker|xiino|blazer|elaine)/i',$u_agent)) {
        $platform = 'Palm';    
    } elseif (preg_match('/(iris|3g_t|windows ce|opera mobi|windows ce; smartphone;|windows ce; iemobile)/i',$u_agent)) {
	$platform = 'Windows Smartphone';
    } elseif (preg_match('/blackberry/i',$u_agent)) {
	$platform = 'Blackberry';
    } else {
    	$platform = 'Desconegut';
    }

    
    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) { 
        $bname = 'Internet Explorer'; 
        $ub = "MSIE"; 
    } elseif(preg_match('/Firefox/i',$u_agent)) { 
        $bname = 'Mozilla Firefox'; 
        $ub = "Firefox"; 
    } elseif(preg_match('/Chrome/i',$u_agent)) { 
        $bname = 'Google Chrome'; 
        $ub = "Chrome"; 
    } elseif(preg_match('/Safari/i',$u_agent)) { 
        $bname = 'Apple Safari'; 
        $ub = "Safari"; 
    } elseif(preg_match('/Opera/i',$u_agent)) { 
        $bname = 'Opera'; 
        $ub = "Opera"; 
    } elseif(preg_match('/Netscape/i',$u_agent)) { 
        $bname = 'Netscape'; 
        $ub = "Netscape"; 
    } else {
    	$bname = 'Desconegut';
    }
    
    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
        # no fem
    }
    
    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
            $version= $matches['version'][1];
        }
    }
    else {
        $version= $matches['version'][0];
    }
    
    // check if we have a number
    if ($version==null || $version=="") {$version="?";}

		$retornot['useragent'] = $u_agent;
		$retornot['navegador'] = $bname;
		$retornot['navegador version'] = $version;
		$retornot['sistema operatiu'] = $platform;
		$retornot['referer'] = $_SERVER['HTTP_REFERER'];
		$retornot['idiomes'] = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
		$retornot['ip'] = $laip;
		$retornot['pattern'] = $pattern;
		
    return $retornot;

}
?>
