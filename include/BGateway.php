<?
   class BGateway 
   {

      var $gate_cod;
      var $usua_cod;
		var $tiga_cod;
		var $tipo_producto;
		var $cargo_fijo;
		var $cargo_canales_entrantes;
		var $plan_cod;
		var $monto_cuota;
		var $host;
		var $descripcion;

      // trae el primer registro
      function primero (&$DB)
      {
         $retval                 = false;

         $sql                    = "SELECT a.gate_cod,
                                          a.usua_cod
                                    FROM FG.FC_GATEWAY a";
         
         if ($DB->Query($sql)) 
         {
            $this->gate_cod      = $DB->Value("GATE_COD");
            $this->usua_cod      = $DB->Value("USUA_COD");
            $retval              = true;
         }
         else
            $DB->Close();
         return $retval;
      }

      // trae siguiente registro
      function siguiente (&$DB)
      {
         $retval                 = false;

         if ($DB->Next()) 
         {
            $this->gate_cod      = $DB->Value("GATE_COD");
            $this->usua_cod      = $DB->Value("USUA_COD");
            $retval              = true;
         }
         else
            $DB->Close();
         return $retval;
      }

      // busca un adaptador tipo SOS del cliente dado
		function buscaSOS($usua_cod, &$DB)
		{
			$retval                    = false;

			$valores['usua_cod']       = $usua_cod;

			$sql								= "SELECT a.usua_cod,
															a.gate_cod
													FROM FG.FC_GATEWAY a
													WHERE a.usua_cod = :usua_cod
													AND a.tiga_cod = 102
													AND a.admin_esta_cod IN (1, 2)
													AND a.user_esta_cod IN (1, 2)";
			
			if ($DB->Query($sql, $valores)) 
			{
				$this->usua_cod			= $DB->Value("USUA_COD");
				$this->gate_cod			= $DB->Value("GATE_COD");
				$retval						= true;
			}
			$DB->Close();
			return $retval;
		}

      // busca un adaptador tipo SOS del cliente dado
		function verificaSOS($gate_cod, &$DB)
		{
			$retval                    = false;

			$valores['gate_cod']       = $gate_cod;

			$sql								= "SELECT a.usua_cod,
															a.gate_cod
													FROM FG.FC_GATEWAY a
													WHERE a.gate_cod = :gate_cod
													AND a.tiga_cod = 102
													AND a.admin_esta_cod IN (1, 2)
													AND a.user_esta_cod IN (1, 2)";
			
			if ($DB->Query($sql, $valores)) 
			{
				$this->usua_cod			= $DB->Value("USUA_COD");
				$this->gate_cod			= $DB->Value("GATE_COD");
				$retval						= true;
			}
			$DB->Close();
			return $retval;
		}

		// busca adaptador SOS 
		function buscaGatewaySOS($gate_cod, &$DB)
		{
			$retval							= false;

			$valores['gate_cod']			= $gate_cod;

			$sql								= "SELECT a.usua_cod,
															a.gate_cod
													FROM FG.FC_GATEWAY a
													WHERE a.gate_cod = :gate_cod
													AND a.tiga_cod = 102
													AND a.admin_esta_cod IN (1, 2)
													AND a.user_esta_cod IN (1, 2)";
			
			if ($DB->Query($sql, $valores)) 
			{
				$this->usua_cod			= $DB->Value("USUA_COD");
				$this->gate_cod			= $DB->Value("GATE_COD");
				$retval						= true;
			}
			$DB->Close();
			return $retval;
		}

		/* verifica solamente si existe gateway dado */
		function VerificarGateway($gate_cod, &$DB)
		{
			$retval							= false;

			$valores['gate_cod']			= $gate_cod;

			$sql								= "SELECT a.gate_cod,
															a.usua_cod
													FROM FG.FC_GATEWAY a
													WHERE a.gate_cod = :gate_cod
													AND (a.admin_esta_cod = 1 OR a.admin_esta_cod = 2)
													AND (a.user_esta_cod = 1 OR a.user_esta_cod = 2)";
			
			if($DB->Query($sql, $valores))
			{
				$this->gate_cod			= $gate_cod;
				$this->usua_cod			= $DB->Value("USUA_COD");
				$retval						= TRUE;
			}
			$DB->Close();
			return $retval;
		}

		/* trae el tipo de adaptador segun el gate_cod dado */
		function VerificarTipoGateway($gate_cod, &$DB)
		{
			$retval									   = false;

			$valores['gate_cod']						= $gate_cod;

			$sql											= "SELECT a.gate_cod,
																		a.tiga_cod,
																		b.tipo_producto,
																		a.cargo_fijo,
																		a.cargo_canales_entrantes,
																		a.plan_cod,
																		a.monto_cuota,
																		a.host,
																		b.descripcion
															FROM FG.FC_GATEWAY a,
																FG.FC_MAYO_TIPO_GATEWAY b,
																FG.FC_USUARIO c
															WHERE a.gate_cod = :gate_cod
															AND a.tiga_cod = b.tiga_cod
															AND a.usua_cod = c.usua_cod
															AND (a.admin_esta_cod = 1 OR a.admin_esta_cod = 2)
															AND (a.user_esta_cod = 1 OR a.user_esta_cod = 2)";

			if($DB->Query($sql, $valores))
			{
				$this->gate_cod						= $gate_cod;
				$this->tiga_cod						= $DB->Value("TIGA_COD");
				$this->tipo_producto       		= $DB->Value("TIPO_PRODUCTO");
				$this->cargo_fijo						= $DB->Value("CARGO_FIJO");
				$this->cargo_canales_entrantes	= $DB->Value("CARGO_CANALES_ENTRANTES");
				$this->plan_cod						= $DB->Value("PLAN_COD");
				$this->monto_cuota					= $DB->Value("MONTO_CUOTA");
				$this->host								= $DB->Value("HOST");
				$this->descripcion					= $DB->Value("DESCRIPCION");
				$retval 									= true;
			}
			$DB->Close();
			return $retval;
		}

   }

?>