<?
   class BCausa
   {
      var $causa_cod;
      var $descripcion;

      function busca($causa_cod, &$DB)
      {
         $retval                 = false;

         $valores['causa_cod']   = $causa_cod;

         $sql                    = "SELECT a.causa_cod,
                                          a.descripcion
                                    FROM BP.BP_CAUSA a
                                    WHERE a.causa_cod = :causa_cod";

         if($DB->Query($sql, $valores))
         {
            $this->causa_cod     = $DB->Value("CAUSA_COD");
            $this->descripcion   = $DB->Value("DESCRIPCION");
            $retval              = true;
         }
         $DB->Close();
         return $retval;
      }

      function primero(&$DB)
      {
         $retval                 = false;

         $sql                    = "SELECT a.causa_cod,
                                          a.descripcion
                                    FROM BP.BP_CAUSA a";

         if($DB->Query($sql))
         {
            $this->causa_cod     = $DB->Value("CAUSA_COD");
            $this->descripcion   = $DB->Value("DESCRIPCION");
            $retval              = true;
         } else {
            $DB->Close();
         }
         return $retval;
      }

      function siguiente(&$DB)
      {
         $retval                 = false;

         if($DB->Next())
         {
            $this->causa_cod     = $DB->Value("CAUSA_COD");
            $this->descripcion   = $DB->Value("DESCRIPCION");
            $retval              = true;
         } else {
            $DB->Close();
         }
         return $retval;
      }
   }
?>