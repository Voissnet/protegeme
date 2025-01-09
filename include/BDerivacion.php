<?
   class BDerivacion
   {
      var $alert_cod;
      var $derv_cod;
      var $descripcion;

      // trae las derivaciones de una alerta
      function busca($alert_cod, &$DB)
      {
         $retval                 = false;

         $valores['alert_cod']   = $alert_cod;

         $sql                    = "SELECT a.alert_cod,
                                          a.derv_cod,
                                          b.descripcion
                                    FROM BP.BP_ALERTA_DERIVACION a,
                                    BP.BP_DERIVACION b
                                    WHERE a.derv_cod = b.derv_cod
                                    AND a.alert_cod = :alert_cod
                                    ORDER BY a.derv_cod ASC";

         if($DB->Query($sql, $valores))
         {
            $this->alert_cod     = $DB->Value("ALERT_COD");
            $this->derv_cod      = $DB->Value("DERV_COD");
            $this->descripcion   = $DB->Value("DESCRIPCION");
            $retval              = true;
         } else {
            $DB->Close();
         }
         return $retval;
      }

      // trae siguiente derivacion
      function siguiente(&$DB)
      {
         $retval                 = false;

         if($DB->Next())
         {
            $this->alert_cod     = $DB->Value("ALERT_COD");
            $this->derv_cod      = $DB->Value("DERV_COD");
            $this->descripcion   = $DB->Value("DESCRIPCION");
            $retval              = true;
         } else {
            $DB->Close();
         }
         return $retval;
      }

      // trae las derivaciones
      function buscaDerivacion(&$DB)
      {
         $retval                 = false;

         $sql                    = "SELECT a.derv_cod,
                                          a.descripcion
                                    FROM BP.BP_DERIVACION a
                                    ORDER BY a.descripcion ASC";

         if ($DB->Query($sql)) {
            $this->derv_cod      = $DB->Value("DERV_COD");
            $this->descripcion   = $DB->Value("DESCRIPCION");
            $retval              = true;
         } else {
            $DB->Close();
         }
         return $retval;
      }

      // siguiente derivacion
      function siguienteDerivacion(&$DB) {
         $retval                 = false;

         if($DB->Next())
         {
            $this->derv_cod      = $DB->Value("DERV_COD");
            $this->descripcion   = $DB->Value("DESCRIPCION");
            $retval              = true;
         } else {
            $DB->Close();
         }
         return $retval;
      }
   }
?>