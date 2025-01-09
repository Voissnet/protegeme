<?
   class BAbono
   {
      var $abon_cod;
      var $usua_cod;
      var $fecha;
      var $monto;
      var $monto_pesos;
      var $meto_cod;
      var $esta_cod;
      var $autorizacion;
      var $tipo_emision;
      var $stat_impresion;
      var $numero_boleta;
      var $ip_cliente;
      var $revisado;
      var $porcentaje_descuento;


      function Inserta($usua_cod, $monto, $monto_pesos, &$DB)
      {
         $retval = FALSE;
      
         /* ip cliente */
         $ip_cliente = $_SERVER["REMOTE_ADDR"];
      
         $valores1["usua_cod"]    = $usua_cod;
         $valores1["monto"]       = $monto;
         $valores1["monto_pesos"] = $monto_pesos;
         $valores1["ip_cliente"]  = $ip_cliente;
         
         $query1 = "INSERT INTO FG.FC_ABONO (usua_cod, fecha, monto, monto_pesos, esta_cod, tipo_emision, stat_impresion, ip_cliente, revisado)
                    VALUES (:usua_cod, SYSDATE, :monto, :monto_pesos, 5, 1, 2, :ip_cliente, 1)
                    RETURNING abon_cod";
      
         $this->abon_cod = $DB->ExecuteReturning($query1, $valores1);
         if( $this->abon_cod !== FALSE)
         {
            $this->fecha            = date("d-m-Y H:i:s");
            $this->monto            = $monto;
            $this->monto_pesos      = $monto_pesos;
            $this->esta_cod         = 5;
            $this->stat_impresion   = 2;
            $this->ip_cliente       = $ip_cliente;
            $retval = TRUE;
         }
         return $retval;
      }

      function InsertaHistoriaAbono($esta_cod, &$DB)
      {
         $retval = FALSE;
      
         $valores["abon_cod"] = $this->abon_cod;
         $valores["esta_cod"] = $esta_cod;
         $query = "INSERT INTO FG.FC_HISTORIA_ESTADO_ABONO (abon_cod, esta_cod, fecha_cambio_estado)
                   VALUES (:abon_cod, :esta_cod, SYSDATE)";

         if ($DB->Execute($query, $valores) === TRUE)
         {
            $this->esta_cod = $esta_cod;
            $retval = TRUE;
         }
         return $retval;
      }



   }
