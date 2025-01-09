<?
   class MOD_Funciones
   {
      /* Key para identificar comercio en portal de pagos */
      const CONST_KEY   = "1F2R4SAB5Y80RFD3T74YAF80O124H58";
      const ID_COMERCIO = 1;
      const PP_REQUEST  = "https://pagos.lanube.cl/request/";
      const PP_PAGOS    = "https://pagos.lanube.cl/pagos/";

      /* Obtiene el valor del dolar actual */
      public static function getDolar(&$DB)
      {
         $retval = FALSE;
         
         $valores["uso"] = 3;
         $sql = "SELECT FG.PKG_FC.getDolar(:uso) dolar
                 FROM DUAL";

         if($DB->Query($sql, $valores) === TRUE)
            $retval = $DB->Value("DOLAR");
         $DB->Close();

         return $retval;
      }

      /* dado un monto en pesos, lo pasa a centesimas de centavo de dolar */
      public static function aDolares( $monto, &$DB )
      {
         return floor(($monto / MOD_Funciones::getDolar($DB)) * 10000);
      }
   }