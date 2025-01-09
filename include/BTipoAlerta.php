<?
   class BTipoAlerta
   {
      var $tipoa_cod;
      var $tipo_alerta;

      // trae el primer registro
      function primero(&$DB)
      {
         $retval                 = false;

         $sql                    = "SELECT a.tipoa_cod,
                                          a.tipo_alerta
                                    FROM BP.BP_TIPO_ALERTA a
                                    ORDER BY a.tipoa_cod ASC";

         if($DB->Query($sql))
         {
            $this->tipoa_cod     = $DB->Value("TIPOA_COD");
            $this->tipo_alerta   = $DB->Value("TIPO_ALERTA");
            $retval              = true;
         } else {
            $DB->Close();
         }
         return $retval;
      }

      // trae siguiente registro
      function siguiente(&$DB)
      {
         $retval                 = false;

         if($DB->Next())
         {
            $this->tipoa_cod     = $DB->Value("TIPOA_COD");
            $this->tipo_alerta   = $DB->Value("TIPO_ALERTA");
            $retval              = true;
         } else {
            $DB->Close();
         }
         return $retval;
      }
   }
?>