<?
   class BAccion
   {
      var $acci_cod;
      var $accion;
      
      // primer registro
      function primero(&$DB)
      {
         $retval              = false;

         $sql                 = "SELECT a.acci_cod,
                                       a.accion
                                 FROM BP.BP_ACCION a
                                 ORDER BY a.acci_cod ASC";

         if($DB->Query($sql))
         {
            $this->acci_cod   = $DB->Value("ACCI_COD");
            $this->accion     = $DB->Value("ACCION");
            $retval           = true;
         } else {
            $DB->Close();
         }
         return $retval;
      }

      // siguiente registro
      function siguiente(&$DB)
      {
         $retval              = false;

         if($DB->Next())
         {
            $this->acci_cod   = $DB->Value("ACCI_COD");
            $this->accion     = $DB->Value("ACCION");
            $retval           = true;
         } else {
            $DB->Close();
         }
         return $retval;
      }
   }
?>