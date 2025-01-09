<?
   class BGatewayNumero 
   {

      var $numero;
      var $gate_cod;
      var $puertas;
      var $descripcion;
      var $privado;
      var $user_esta_cod;
      var $admin_esta_cod;
      var $prefijo1;
      var $prefijo2;
      var $voicemail;
      var $forward;
      var $orden;
      var $cent_cod;
      var $fecha_asig_centro;
      var $numero_real;
      var $zona;
      var $monto;
      var $conj_cod;
      var $cantidad;
      var $adicional;
      var $fecha_libre;

      /* trae el primer registro */
      function primero(&$DB)
      {
         $retval                       = FALSE;

         $sql                          = "SELECT a.numero,
                                                a.gate_cod,
                                                a.puertas,
                                                a.descripcion,
                                                a.privado,
                                                a.user_esta_cod,
                                                a.admin_esta_cod,
                                                a.prefijo1,
                                                a.prefijo2,
                                                a.voicemail,
                                                a.forward,
                                                a.orden,
                                                a.cent_cod,
                                                a.fecha_asig_centro
                                          FROM FG.FC_GATEWAY_NUMERO a
                                          ORDER BY a.numero ASC";

         if($DB->Query($sql))
         {
            $this->numero              = $DB->Value("NUMERO");
            $this->gate_cod            = $DB->Value("GATE_COD");
            $this->puertas             = $DB->Value("PUERTAS");
            $this->descripcion         = $DB->Value("DESCRIPCION");
            $this->privado             = $DB->Value("PRIVADO");
            $this->user_esta_cod       = $DB->Value("USER_ESTA_COD");
            $this->admin_esta_cod      = $DB->Value("ADMIN_ESTA_COD");
            $this->prefijo1            = $DB->Value("PREFIJO1");
            $this->prefijo2            = $DB->Value("PREFIJO2");
            $this->voicemail           = $DB->Value("VOICEMAIL");
            $this->forward             = $DB->Value("FORWARD");
            $this->orden               = $DB->Value("ORDEN");
            $this->cent_cod            = $DB->Value("CENT_COD");
            $this->fecha_asig_centro   = $DB->Value("FECHA_ASIG_CENTRO");
            $retval                    = TRUE;
         }
         else
            $DB->Close();
         return $retval;
      }

      /* trae el siguiente registro */
      function siguiente(&$DB)
      {
         $retval                       = FALSE;

         if($DB->Next())
         {
            $this->numero              = $DB->Value("NUMERO");
            $this->gate_cod            = $DB->Value("GATE_COD");
            $this->puertas             = $DB->Value("PUERTAS");
            $this->descripcion         = $DB->Value("DESCRIPCION");
            $this->privado             = $DB->Value("PRIVADO");
            $this->user_esta_cod       = $DB->Value("USER_ESTA_COD");
            $this->admin_esta_cod      = $DB->Value("ADMIN_ESTA_COD");
            $this->prefijo1            = $DB->Value("PREFIJO1");
            $this->prefijo2            = $DB->Value("PREFIJO2");
            $this->voicemail           = $DB->Value("VOICEMAIL");
            $this->forward             = $DB->Value("FORWARD");
            $this->orden               = $DB->Value("ORDEN");
            $this->cent_cod            = $DB->Value("CENT_COD");
            $this->fecha_asig_centro   = $DB->Value("FECHA_ASIG_CENTRO");
            $this->cantidad            = $DB->Value("CANTIDAD");
            $this->numero_real         = $DB->Value("NUMERO_REAL");
            $this->zona                = $DB->Value("ZONA");
            $this->monto               = $DB->Value("MONTO");
            $this->conj_cod            = $DB->Value("CONJ_COD");
            $this->adicional           = $DB->Value("ADICIONAL");
            $this->fecha_libre         = $DB->Value("FECHA_LIBRE");
            $retval                    = TRUE;
         }
         else
            $DB->Close();
         return $retval;
      }

      // busca numero SOS
      function buscaNumSOS($numero, $gate_cod, &$DB)
      {
         $retval                          = false;

         $valores['numero']               = $numero;
         $valores['gate_cod']             = $gate_cod;

         $sql                             = "SELECT a.gate_cod,
                                                   b.numero,
                                                   c.numero_real,
                                                   c.conj_cod
                                             FROM FG.FC_GATEWAY a,
                                             FG.FC_GATEWAY_NUMERO b,
                                             FG.GLBL_NUMERO_REAL c
                                             WHERE a.gate_cod = b.gate_cod
                                             AND b.numero = c.numero
                                             AND b.admin_esta_cod IN (1, 2)
                                             AND b.user_esta_cod IN (1, 2)
                                             AND a.gate_cod = :gate_cod
                                             AND b.numero = :numero";
         
         if ($DB->Query($sql, $valores))
         {
            $this->gate_cod               = $DB->Value("GATE_COD");
            $this->numero                 = $DB->Value("NUMERO");
            $this->numero_real            = $DB->Value("NUMERO_REAL");
            $this->conj_cod               = $DB->Value("CONJ_COD");
            $retval                       = true;
         }
         $DB->Close();
         return $retval;
      }

      
      // elimina un numero interno
      function delete($tipo, &$DB)
      {
         $retval                    = false;

         $valores['numero']         = $this->numero;
         $valores['gate_cod']       = $this->gate_cod;

         // TIPO 1: PROCESO NORMAL, TIPO 2: PROCESO PARA CV2, SOS

         if ($tipo === 2) {

            $secuencia              = $DB->GetSequence("FG.SEQ_FC_NUMERO_INTERNO");

            $valores['secuencia']   = $secuencia;
   
            $sql                    = "UPDATE FG.FC_GATEWAY_NUMERO a
                                       SET a.user_esta_cod = 3,
                                          a.admin_esta_cod = 3,
                                          a.numero = :numero || '_' || :gate_cod || '_' || :secuencia
                                       WHERE a.numero = :numero
                                       AND a.gate_cod = :gate_cod";

         } else {

            $sql                    = "UPDATE FG.FC_GATEWAY_NUMERO a
                                       SET a.user_esta_cod = 3,
                                          a.admin_esta_cod = 3
                                       WHERE a.numero = :numero
                                       AND a.gate_cod = :gate_cod";

         }

         if($DB->Execute($sql, $valores))
         {
            $this->numero           = $tipo === 2 ? ($this->numero . '_' . $this->gate_cod . '_' . $secuencia) : $this->numero;
            $retval                 = true;
         }
         return $retval;         
      }

      // asigna numero de cabecera a un adaptador
      function PideAsignado($gate_cod, $conj_cod, $plataforma, $sip_username = 0, &$DB)
      {
         $retval                        = FALSE;

         $Conj                          = new BConjuntoNumero();

         if ($Conj->BuscaConjuntos($conj_cod, $DB)) {

            $valores['conj_cod']        = $conj_cod;

            $sql                        = "SELECT a.numero_real
                                          FROM FG.GLBL_NUMERO_REAL a
                                          WHERE a.conj_cod = :conj_cod
                                          AND a.numero IS NULL
                                          AND a.fecha_libre < SYSDATE
                                          ORDER BY a.fecha_libre";

            if ($DB->Query($sql, $valores)) {

               $numero_real             = $DB->Value("NUMERO_REAL");

               $DB->Close();
               $prefijos                = array( 1    => '778', 
                                                2     => '558', 
                                                3     => '551', 
                                                5     => '557', 
                                                6     => '5111', 
                                                7     => '5112', 
                                                8     => '5103', 
                                                9     => '540', 
                                                10    => '5420', 
                                                13    => '5113', 
                                                14    => '5114', 
                                                15    => '5115', 
                                                16    => '5116', 
                                                17    => '5117', 
                                                18    => '5118',
                                                20    => '533' );

                  $numero              = $prefijos[$plataforma] . $plataforma = 20 ? ($gate_cod . $sip_username) : $DB->GetSequence("FG.SEQ_FC_NUMERO_INTERNO");


               $valores2['numero']     = $numero;
               $valores2['gate_cod']   = $gate_cod;
               $valores2['prefijo1']   = $Conj->prefijo;
               $valores2['prefijo2']   = $Conj->prefijo2;

               $sql2                   = "INSERT INTO FG.FC_GATEWAY_NUMERO (numero, gate_cod, puertas, privado, user_esta_cod, admin_esta_cod, prefijo1, prefijo2)
                                          VALUES (:numero, :gate_cod, 1, 1, 1, 1, :prefijo1, :prefijo2)";

               if ($DB->Execute($sql2, $valores2)) {

                  $valores3['numero']      = $numero;
                  $valores3['numero_real'] = $numero_real;

                  $sql3                    = "UPDATE FG.GLBL_NUMERO_REAL a
                                             SET a.numero = :numero,
                                                a.adicional = 0,
                                                a.fecha_libre = SYSDATE
                                             WHERE a.numero_real = :numero_real";

                  if ($DB->Execute($sql3, $valores3)) {

                     $this->numero        = $numero;
                     $this->numero_real   = $numero_real;
                     $retval              = TRUE;

                  }

               }

            } else {

               $DB->Close();

            }

         }

         return $retval;
      }

   }
   
?>