<?
   class BRubro
   {
      var $rub_cod;
      var $descripcion;

      function Busca($rub_cod, &$DB)
      {
         $retval = FALSE;
         $valores["rub_cod"] = $rub_cod;
         $sql = "SELECT descripcion
                 FROM FG.FC_RUBRO
                 WHERE rub_cod = :rub_cod";
         
         if($DB->Query($sql, $valores))
         {
            $this->rub_cod       = $rub_cod;
            $this->descripcion   = $DB->Value("DESCRIPCION");
            $retval              = TRUE;
         }
         $DB->Close();
         return $retval;
      }


      function Primero(&$DB)
      {
         $retval = FALSE;
         $sql = "SELECT rub_cod,
                        descripcion
                 FROM FG.FC_RUBRO";
         if($DB->Query($sql))
         {
            $this->rub_cod       = $DB->Value("RUB_COD");
            $this->descripcion   = $DB->Value("DESCRIPCION");
            $retval              = TRUE;
         }
         else
            $DB->Close();
         return $retval;
      }

      
      function Siguiente(&$DB)
      {
         $retval=FALSE;
         if($DB->Next())
         {
            $this->rub_cod       = $DB->Value("RUB_COD");
            $this->descripcion   = $DB->Value("DESCRIPCION");
            $retval              = TRUE;
         }
         else
            $DB->Close();
         return $retval;
      }
   }