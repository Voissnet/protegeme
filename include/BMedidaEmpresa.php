<?
   class BMedidaEmpresa
   {
      var $med_cod;
      var $descripcion;

      function Busca($med_cod, &$DB)
      {
         $retval = FALSE;
         $valores["med_cod"] = $med_cod;
         $sql = "SELECT descripcion
                 FROM FG.FC_MEDIDA_EMPRESA
                 WHERE med_cod = :med_cod";
         
         if($DB->Query($sql, $valores))
         {
            $this->med_cod       = $med_cod;
            $this->descripcion   = $DB->Value("DESCRIPCION");
            $retval              = TRUE;
         }
         $DB->Close();
         return $retval;
      }


      function Primero(&$DB)
      {
         $retval = FALSE;
         $sql = "SELECT med_cod,
                        descripcion
                 FROM FG.FC_MEDIDA_EMPRESA";
         if($DB->Query($sql))
         {
            $this->med_cod       = $DB->Value("MED_COD");
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
            $this->med_cod       = $DB->Value("MED_COD");
            $this->descripcion   = $DB->Value("DESCRIPCION");
            $retval              = TRUE;
         }
         else
            $DB->Close();
         return $retval;
      }
   }