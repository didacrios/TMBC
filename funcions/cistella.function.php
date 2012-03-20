<?php

/*
 *	Funcions per a treballar amb botigues online
 *
 * 	ts_cistella
 * 		// afegir i treure productes de la cistella de compra
 *
 *
 * 	ts_totalitzar_cistella
 * 		// totalitzador per als productes que hi ha a la cistella
 *
 * */

function ts_cistella($identificador, $elnom, $elpreu, $qtt, $printer_image, $prod_iva) {
    // id producte, nom producte, preu producte, qtt producte, imatge, % iva
    // sample afegir: ts_cistella(1555,'kyocera nose ke', 15545.434, 2, 'http://wwww....jpg', 18);
    // sample esborrar un article: unset($_SESSION['cistella'][$idproducte]);
    // sample esborrar carrito: unset($_SESSION['cistella']);

        
    $cistella=$_SESSION['ts_cistella'];
    
    if ($identificador) {
			
       if (!isset($cistella)) {
				 
          $cistella[$identificador]['id_producte']=$identificador;
          $cistella[$identificador]['nom']=$elnom;
          $cistella[$identificador]['quantitat']=$qtt;
          $cistella[$identificador]['preu']=$elpreu;
          $cistella[$identificador]['imatge']=$printer_image;
          $cistella[$identificador]['iva']=$prod_iva;
          
       } else { 
          foreach($cistella as $we => $brb) { 
             if ($identificador==$we) {
									$cistella[$we]['quantitat']+=$qtt;									
									$trobat=1;
                 if ($cistella[$we]['quantitat'] <= 0) {
									 unset($cistella[$we]);
								 }
             } 
          }
            if (!isset($trobat)) {
							$cistella[$identificador]['id_producte']=$identificador;
              $cistella[$identificador]['nom']=$elnom;
              $cistella[$identificador]['quantitat']=$qtt;
              $cistella[$identificador]['preu']=$elpreu;
              $cistella[$identificador]['imatge']=$printer_image;
              $cistella[$identificador]['iva']=$prod_iva;
            }
       } 
    } 
    $_SESSION['ts_cistella']=$cistella;
}

function ts_totalitzar_cistella() {

	if (isset($_SESSION['ts_cistella'])) {

		foreach($_SESSION['ts_cistella'] as $cadun => $brb) {

				//$desflos = theivas($_SESSION['ts_cistella'][$cadun]['preu'], $_SESSION['ts_cistella'][$cadun]['iva']);

				$iva_cadun = ( ($_SESSION['ts_cistella'][$cadun]['preu'] * $_SESSION['ts_cistella'][$cadun]['iva']) / 100);
				
				$total_noiva[] = $_SESSION['ts_cistella'][$cadun]['quantitat'] * $_SESSION['ts_cistella'][$cadun]['preu'];
				$total_iva[] = $_SESSION['ts_cistella'][$cadun]['quantitat'] * $iva_cadun;
		}

		//Debug($total_noiva);
		//ebug($total_iva);


		$retornu['total_noiva'] = array_sum($total_noiva);
		$retornu['iva'] = array_sum($total_iva);
		$retornu['total'] = $retornu['total_noiva'] + $retornu['iva'];
		$retornu['total_productes'] = count($_SESSION['ts_cistella']);
		
	} else {

		$retornu['total_noiva'] = 0;
		$retornu['iva'] = 0;
		$retornu['total'] = 0;
		$retornu['total_productes'] = 0;
		
	}


	return $retornu;
	
}
?>
