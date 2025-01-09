<?
   class BGLBLnumeroReal
   {

      var $numero_real;
		var $conj_cod;
		var $fecha_libre;
		var $numero;
		var $adicional;



      /* actualiza numero interno de un numero real dado */
		function actualizaInterno($numero_real, $numero, $adicional, &$DB)
		{
			$retval							= false;

			$valores['numero'] 			= $numero;
			$valores['numero_real']    = $numero_real;
			$valores['adicional']		= $adicional;
			
			$sql 								= "UPDATE FG.GLBL_NUMERO_REAL a
													SET a.numero = :numero,
														a.adicional = :adicional,
														a.fecha_libre = SYSDATE
													WHERE a.numero_real = :numero_real";
			if($DB->Execute($sql, $valores))
			{
				$this->numero_real		= $numero_real;
				$this->numero				= $numero;
				$retval						= true;
			}
			return $retval;
		}

   }

?>