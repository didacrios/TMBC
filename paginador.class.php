<?php
/* =============================================================================
                                                      __                    
                           __                        /\ \                   
 _____      __        __  /\_\     ___       __      \_\ \     ___    _ __  
/\ '__`\  /'__`\    /'_ `\\/\ \  /' _ `\   /'__`\    /'_` \   / __`\ /\`'__\
\ \ \L\ \/\ \L\.\_ /\ \L\ \\ \ \ /\ \/\ \ /\ \L\.\_ /\ \L\ \ /\ \L\ \\ \ \/ 
 \ \ ,__/\ \__/.\_\\ \____ \\ \_\\ \_\ \_\\ \__/.\_\\ \___,_\\ \____/ \ \_\ 
  \ \ \/  \/__/\/_/ \/___L\ \\/_/ \/_/\/_/ \/__/\/_/ \/__,_ / \/___/   \/_/ 
   \ \_\              /\____/                                               
    \/_/              \_/__/                                



   Paginador de resultats

   Última modificació # Dídac Rios # 23-02-2012 15:15:24 # 

   	$pagina = new ts_paginador('ofertes'); 		// creem nou objecte, indicant la taula amb la que treballarem (ha d'haver una connexió mysql oberta)

		$pagina->ts_where($where); 								// si hi ha alguna condicio (buscador, etc..)
		$pagina->ts_order('id DESC'); 						// ordenació 
		$pagina->estableix_resultats_pagina(10); 	// * Establim els resultats per pàgina, per defecte 30 
		$pagina->estableix_varpagina('pagina'); 	// * Establim la variable de la pagina web.php?VARIABLE=1|2|3|4|5|N
		$the_query = $pagina->fem_query(); 				// Fem la consulta

		$pagina->mostrar_links(7);								// Mostrem els links

		* Les instancies amb asterisc (*) no són necessaries, només les cridarem en cas de voler canviar els valors x defecte

   
   ========================================================================== */


class ts_paginador {

	/* definim variables que s'utilitzaran */
	
	var $n_pag = 1; 				// Nombre de pagina actual, per defecte serà 1 
	var $rpp = 30; 					// registres per pagina, per defecte 30
	var $url;								// la pàgina en la que estem, per crear els links correctament
	var $total_registres;		// total de registres de la consulta realitzada
	var $taula;							// la taula amb la que treballarem
	var $numero_pagines;		// numero de pàgines totals que hi haurà
	var $pvar = 'p'; 				// la variable que indicarà el numero de pàgina, per defecte es p ($_GET['p'])
	var $where;							// where
	var $order;							// order by


	function ts_paginador($ts_taula) {
		global $n_pag;
		$this->taula=$ts_taula;
	}


	function ts_where($ts_condicio) {
		$where_pla = count( $ts_condicio ) ? "\nWHERE " . implode( ' AND ', $ts_condicio ) : '';		
		$this->where=$where_pla;
	}

	function ts_order($ts_order) {
		$this->order=$ts_order;
	}

	function total_registres() {
		
		$query="SELECT COUNT(*) AS total FROM ".$this->taula;

		if ($this->where!="") {
			 $query .= $this->where;
		}

		$result=mysql_query($query);

		$row=mysql_fetch_object($result);
		$this->total_registres= $row->total;
		
	}

	function pagina_actual() {
	 if (isset($_GET[$this->pvar])) { $pagina_actual = $_GET[$this->pvar]; } else { $pagina_actual = '1'; } // pàgina actual
	 return($pagina_actual);
	}

		// numeros de pagines totals
	function pagines_totals() {

		$this->total_registres();

		$this->numeros_pagines = (int)($this->total_registres / $this->rpp); // pagines totals que hi haurà
		if(($this->total_registres%$this->rpp) != 0) { $this->numeros_pagines++; }

	}

	function estableix_resultats_pagina($num) {
		$this->rpp=$num;
		$this->pagines_totals();
	}

	function estableix_varpagina($str) {
		$this->pvar = $str;
	}


	function obtenim_url() {
		
		global $_GET;

		$la_pagina = basename($_SERVER['PHP_SELF']);

		while (list ($clave, $val) = each ($_GET)) {
			if($clave != $this->pvar) {
				 $variables .= $clave."=".$val."&";
			}
		}

		 $this->url = $la_pagina."?".$variables;
	}	

	// retorna $result amb el query ja paginat
	function fem_query() {

		if (isset($_GET[$this->pvar])) {
			$this->n_pag=$_GET[$this->pvar];
		} else {
			$this->n_pag=1;
		}		

		$query = "SELECT * FROM ". $this->taula;

		if ($this->where != "") { $query .= $this->where; }
		if($this->order != "") { $query .= " ORDER BY ". $this->order; }

		$limitacio = ($this->n_pag-1) * $this->rpp;

		$query .= " LIMIT ".$limitacio.",".$this->rpp;

		$result = mysql_query($query);

		return($result);

	}


	function mostrar_links($mostrarmax=7) {

			 $pagina_actual = $this->pagina_actual();
			 $this->pagines_totals();
			 $pagtotals = $this->numeros_pagines;


			 if (!$this->url) { $this->obtenim_url(); }


			echo '<nav class=ts_paginacio>';

			if ($pagina_actual == 1) {
				echo '
					<span class=inactiu>
						«
					</span>				
				';
			} else {
				echo '
					<a href="'.$this->url.'" class="paginar" title="Primera">
						1
					</a>		
					<a href="'.$this->url.''.$this->pvar.'='.($pagina_actual-1).'" class="paginar" title="Anterior">
						«
					</a>				
				';
			}

//			echo "<h1>$mostrarmax</h1>";
			
			$pap5 = $pagina_actual + $mostrarmax; // pagina actual + LES QUE VOLGUEM
			$pam5 = $pagina_actual - $mostrarmax; // pagina actual - LES QUE VOLGUEM


			if (($pagina_actual + $mostrarmax) < $pagtotals && ($pagina_actual - $mostrarmax) >= 1) { // 1

				$show_pag = '<span>...</span>';
				
				for ($tvar=$pam5;$tvar<=$pap5;$tvar++) {
					if ($pagina_actual == $tvar) { $activeono = ' active'; } else { $activeono=''; }
					$show_pag = $show_pag.'<a href="'.$this->url.''.$this->pvar.'='.$tvar.'" class="paginar'.$activeono.'">'.$tvar.'</a>';
				}
				
				if ($pap5 != $pagtotals) { $show_pag = $show_pag.'<span>...</span>'; }
				
			} elseif (($pagina_actual + $mostrarmax) >= $pagtotals && ($pagina_actual - $mostrarmax) > 1) { //2

				$show_pag = '<span>...</span>';
				
				for ($tvar=$pam5;$tvar<=$pagtotals;$tvar++) {			
					if ($pagina_actual == $tvar) { $activeono = ' active'; } else { $activeono=''; }			
					$show_pag = $show_pag.'<a href="'.$this->url.''.$this->pvar.'='.$tvar.'" class="paginar'.$activeono.'">'.$tvar.'</a>';
				}
				
				if ($pap5 == $pagtotals) { $show_pag = $show_pag.'<span>...</span>'; }
				
						
			} elseif (($pagina_actual + $mostrarmax) < $pagtotals && ($pagina_actual - $mostrarmax) < 1) { //3
			
				for ($tvar=1;$tvar<=$pap5;$tvar++) {
					if ($pagina_actual == $tvar) { $activeono = ' active'; } else { $activeono=''; }			
					$show_pag = $show_pag.'<a href="'.$this->url.''.$this->pvar.'='.$tvar.'" class="paginar'.$activeono.'">'.$tvar.'</a>';
				}
				$show_pag = $show_pag.'<span>...</span>';
				
			} else { //4
				for ($tvar=1;$tvar<=$pagtotals;$tvar++) {
					if ($pagina_actual == $tvar) { $activeono = ' active'; } else { $activeono=''; }
					$show_pag = $show_pag.'<a href="'.$this->url.''.$this->pvar.'='.$tvar.'" class="paginar'.$activeono.'">'.$tvar.'</a>';
				}
			}			


			echo $show_pag; // mostrem el intermig de numeros


			if ($pagina_actual == $pagtotals) {
				echo '
				<span class="inactiu">
					»
				</span>
				';				
			} else {
				echo '			
					<a href="'.$this->url.''.$this->pvar.'='.($pagina_actual+1).'" class="paginar" title="Següent">»</a>				
					<a href="'.$this->url.''.$this->pvar.'='.$pagtotals.'" class="paginar" title="Última">'.$pagtotals.'</a>
				';				
			}			

			echo "</nav>";

	}	
	
}
?>

