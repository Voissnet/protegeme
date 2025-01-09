<?
   class BEstado
   {

      var $esta_cod;
      var $estado;

      // trae el primer registro
      function primero(&$DB)
      {
         $retval              = false;

         $sql                 = "SELECT a.esta_cod,
                                       a.estado
                                 FROM BP.BP_ESTADO a";

         if ($DB->Query($sql))
         {
            $this->esta_cod   = $DB->Value("ESTA_COD");
            $this->estado     = $DB->Value("ESTADO");
            $retval           = true;
         }
         else
            $DB->Close();
         return $retval;
      }

      // trae el siguiente registro
      function siguiente(&$DB)
      {
         $retval              = false;

         if ($DB->Next())
         {
            $this->esta_cod   = $DB->Value("ESTA_COD");
            $this->estado     = $DB->Value("ESTADO");
            $retval           = true;
         }
         else
            $DB->Close();
         return $retval;
      }

      // busca estado dado
      function busca($esta_cod, &$DB)
      {
         $retval              = false;

         $valores['esta_cod'] = $esta_cod;

         $sql                 = "SELECT a.esta_cod,
                                       a.estado
                                 FROM BP.BP_ESTADO a
                                 WHERE a.esta_cod = :esta_cod";
                              
         if ($DB->Query($sql, $valores))
         {
            $this->esta_cod   = $DB->Value("ESTA_COD");
            $this->estado     = $DB->Value("ESTADO");
            $retval           = true;
         }
         $DB->Close();
         return $retval;
      }

   }

?>