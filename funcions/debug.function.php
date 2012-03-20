<?php

/*
 *	Funció per millorar el debug en PHP
 *
 *  Es poden especificar ips en un array, per a que només es vegi quan es treballi en aquella IP
 *
 *  Activat si $DEBUG=1; sino, no es fa el debug
 *
 *  
 * */

function CanDebug() {
 global $DEBUG;
 $ips_permeses = array ('');
 if (in_array ($_SERVER['REMOTE_ADDR'], $ips_permeses)) return $DEBUG;
 else return 0;
}
function Debug($str) {
  //if (!CanDebug()) return;
  echo '<div style="background:#F7D2D2; color:black; border: 1px solid #AA060B; padding: 5px; margin: 5px; white-space: pre;">';
  if (is_string ($str)) echo $str;
  else var_dump ($str);
  echo '</div>';
}
?>
