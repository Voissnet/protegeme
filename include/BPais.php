<?
   class BPais 
   {
      var $pais_cod;
      var $des_espanol;
      var $des_ingles;
      var $des_portugues;

      // trae el primer registro
      function primero(&$DB)
      {
         $retval                 = false;

         $sql                    = "SELECT a.pais_cod,
                                          a.des_espanol,
                                          a.des_ingles,
                                          a.des_portugues
                                    FROM FG.FC_PAIS a
                                    ORDER BY a.des_espanol ASC";

         if($DB->Query($sql))
         {
            $this->pais_cod      = $DB->value("PAIS_COD");
            $this->des_espanol   = $DB->value("DES_ESPANOL");
            $this->des_ingles    = $DB->value("DES_INGLES");
            $this->des_portugues = $DB->value("DES_PORTUGUES");
            $retval              = true;
         }
         else
            $DB->Close();
         return $retval;
      }

      // trae el siguiente registro
      function siguiente(&$DB)
      {
         $retval                 = false;

         if($DB->Next())
         {
            $this->pais_cod      = $DB->value("PAIS_COD");
            $this->des_espanol   = $DB->value("DES_ESPANOL");
            $this->des_ingles    = $DB->value("DES_INGLES");
            $this->des_portugues = $DB->value("DES_PORTUGUES");
            $retval              = true;
         }
         else
            $DB->Close();
         return $retval;
      }
   }
?>