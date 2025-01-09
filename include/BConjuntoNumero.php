<?
   class BConjuntoNumero
   {
      var $conj_cod;
      var $descripcion;
      var $fecha_creacion;
      var $prefijo;
      var $prefijo2;
      var $plataforma;
      var $autoprov;

      /* trae el primer dato */
      function Primero(&$DB)
      {
         $retval                    = FALSE;

         $sql                       = "SELECT a.conj_cod,
                                             a.descripcion,
                                             a.fecha_creacion,
                                             a.prefijo,
                                             a.prefijo2,
                                             a.plataforma,
                                             a.autoprov
                                       FROM FG.FC_CONJUNTO_NUMERO a
                                       ORDER BY a.descripcion ASC";
         
         if($DB->Query($sql))
         {
            $this->conj_cod         = $DB->Value("CONJ_COD");
            $this->descripcion      = $DB->Value("DESCRIPCION");
            $this->fecha_creacion   = $DB->Value("FECHA_CREACION");
            $this->prefijo          = $DB->Value("PREFIJO");
            $this->prefijo2         = $DB->Value("PREFIJO2");
            $this->plataforma       = $DB->Value("PLATAFORMA");
            $this->autoprov         = $DB->Value("AUTOPROV");
            $retval                 = TRUE;
         }
         else
            $DB->Close();
         return $retval;
      }

      /* trae el siguiente dato */
      function Siguiente(&$DB)
      {
         $retval                    = FALSE;

         if($DB->Next())
         {
            $this->conj_cod         = $DB->Value("CONJ_COD");
            $this->descripcion      = $DB->Value("DESCRIPCION");
            $this->fecha_creacion   = $DB->Value("FECHA_CREACION");
            $this->prefijo          = $DB->Value("PREFIJO");
            $this->prefijo2         = $DB->Value("PREFIJO2");
            $this->plataforma       = $DB->Value("PLATAFORMA");
            $this->autoprov         = $DB->Value("AUTOPROV");
            $retval                 = TRUE;
         }
         else
            $DB->Close();
         return $retval;
      }

      /* obtiene zona segun numero dado */
		function ObtenerZona($n, &$DB)
		{
			$retval							= FALSE;

			if (substr($n, 0, 4) == "5644")
         {
            $this->conj_cod 			= "220";
            $retval 						= TRUE;
         }
			else
			{
            if (substr($n, 0, 3) == "562")
            {
               $this->conj_cod      = "239";
               $retval              = TRUE;
            }
            else
            {
               if (substr($n, 0, 3) == "569")
               {
                  $this->conj_cod 		= "256";
                  $retval 					= TRUE;
               }
               else
               {
                  if(substr($n, 0, 5) == "56800" || substr($n, 0, 5) == "56600" || substr($n, 0, 5) == "56609" || substr($n, 0, 5) == "56606")
                  {
                     $valores['prefijo2'] = substr($n, 2, 3);

                     $sql  					= "SELECT a.conj_cod 
                                             FROM FG.FC_CONJUNTO_NUMERO a
                                             WHERE a.prefijo = '56'
                                             AND a.prefijo2 = :prefijo2";

                     if ($DB->Query($sql, $valores))
                     {
                        $this->conj_cod 	= $DB->Value("CONJ_COD");
                        $retval 				= TRUE;
                     }
                     else
                        $DB->Close();
                  }
                  else
                  {
                     $valores2['prefijo2'] = substr($n, 2, 2);

                     $sql2 					= "SELECT a.conj_cod 
                                             FROM FG.FC_CONJUNTO_NUMERO a
                                             WHERE a.prefijo = '56' 
                                             AND a.prefijo2 = :prefijo2";

                     if($DB->Query($sql2, $valores2))
                     {
                        $this->conj_cod 	= $DB->Value("CONJ_COD");
                        $retval 				= TRUE;
                     }
                     else
                        $DB->Close();
                  }
               }
            }
			}
			return $retval;
		}

      /* busca segun conj_cod dado */
      function BuscaConjuntos($conj_cod, &$DB) 
      {
         $retval                          = FALSE;

         $valores['conj_cod']             = $conj_cod;

         $sql                             = "SELECT a.prefijo,
                                                   a.prefijo2,
                                                   a.descripcion
                                             FROM FG.FC_CONJUNTO_NUMERO a
                                             WHERE a.conj_cod = :conj_cod";

         if($DB->Query($sql, $valores)) 
         {
            $this->conj_cod               = $conj_cod;
            $this->prefijo                = $DB->Value("PREFIJO");
            $this->prefijo2               = $DB->Value("PREFIJO2");
            $this->descripcion            = $DB->Value("DESCRIPCION");
            $retval                       = TRUE;
         }
         else
            $DB->Close();
         return $retval;
      }
   }
?>