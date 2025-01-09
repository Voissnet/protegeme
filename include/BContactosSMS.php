<?
   class BContactosSMS
   {
      var $busua_cod;
      var $numero;
      var $esta_cod;

      // trae informacion desde el primer registro
      function primero($busua_cod, &$DB)
      {
         $retval                 = false;

         $valores['busua_cod']   = $busua_cod;

         $sql                    = "SELECT a.busua_cod, 
                                          a.numero,
                                          a.esta_cod
                                    FROM  BP.BP_CONTACTOS_SMS a
                                    WHERE a.esta_cod = 1
                                    AND a.busua_cod = :busua_cod";

         if($DB->Query($sql, $valores))
         {
            $this->busua_cod     = $DB->Value("BUSUA_COD");
            $this->numero        = $DB->Value("NUMERO");
            $this->esta_cod      = $DB->Value("ESTA_COD");
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
            $this->busua_cod     = $DB->Value("BUSUA_COD");
            $this->numero        = $DB->Value("NUMERO");
            $this->esta_cod      = $DB->Value("ESTA_COD");
            $retval              = true;
         }
         else
            $DB->Close();
         return $retval;
      }

      // trae informacion desde el primer registro
      function buscaContactosD($busua_cod, &$DB)
      {
         $retval                 = false;

         $valores['busua_cod']   = $busua_cod;

         $sql                    = "SELECT a.busua_cod, 
                                          a.numero,
                                          a.esta_cod
                                    FROM  BP.BP_CONTACTOS_SMS a
                                    WHERE a.esta_cod IN (1, 2)
                                    AND a.busua_cod = :busua_cod";

         if($DB->Query($sql, $valores))
         {
            $this->busua_cod     = $DB->Value("BUSUA_COD");
            $this->numero        = $DB->Value("NUMERO");
            $this->esta_cod      = $DB->Value("ESTA_COD");
            $retval              = true;
         }
         else
            $DB->Close();
         return $retval;
      }

      // busca un registro
      function busca($busua_cod, $numero, &$DB)
      {
         $retval                 = false;

         $valores['busua_cod']   = $busua_cod;
         $valores['numero']      = $numero;

         $sql                    = "SELECT a.busua_cod,
                                          a.numero,
                                          a.esta_cod
                                    FROM BP.BP_CONTACTOS_SMS a
                                    WHERE a.busua_cod = :busua_cod
                                    AND a.numero = :numero";

         if($DB->Query($sql, $valores))
         {
            $this->busua_cod     = $busua_cod;
            $this->numero        = $numero;
            $this->esta_cod      = $DB->Value("ESTA_COD");
            $retval              = true;
         }
         $DB->Close();
         return $retval;
      }

      // inserta un nuevo registro
      function insert($busua_cod, $numero, $esta_cod, &$DB)
      {
         $retval                 = false;
         
         $valores['busua_cod']   = $busua_cod;
         $valores['numero']      = $numero;
         $valores['esta_cod']    = $esta_cod;

         $sql                    = "INSERT INTO BP.BP_CONTACTOS_SMS (busua_cod, numero, esta_cod)
                                    VALUES (:busua_cod, :numero, :esta_cod)";

         if ($DB->Execute($sql, $valores))
         {
            $this->busua_cod     = $busua_cod;
            $this->numero        = $numero;
            $this->esta_cod      = $esta_cod;
            $retval              = true;
         }
         return $retval;
      }

      // elimina un registro
      function delete($busua_cod, $numero, &$DB)
      {
         $retval                 = false;
         
         $secuencia              = $DB->GetSequence("FG.SEQ_FC_NUMERO_INTERNO");

         $valores['busua_cod']   = $busua_cod;
         $valores['numero']      = $numero;
         $valores['secuencia']   = $secuencia;

         $sql                    = "UPDATE BP.BP_CONTACTOS_SMS a
                                    SET a.esta_cod = 3,
                                       a.numero = :numero || '_' || :secuencia
                                    WHERE a.busua_cod = :busua_cod
                                    AND a.numero = :numero";

         if ($DB->Execute($sql, $valores))
         {
            $this->busua_cod     = $busua_cod;
            $this->numero        = $numero;
            $this->esta_cod      = 3;
            $retval              = true;
         }
         return $retval;
      }

      // inserta un nuevo registro
      function deleteAll(&$DB)
      {
         $retval                 = false;
         
         $secuencia              = $DB->GetSequence("FG.SEQ_FC_NUMERO_INTERNO");

         $valores['busua_cod']   = $this->busua_cod;
         $valores['secuencia']   = $secuencia;

         $sql                    = "UPDATE BP.BP_CONTACTOS_SMS a
                                    SET a.esta_cod = 3,
                                       a.numero = a.numero || '_' || :secuencia
                                    WHERE a.busua_cod = :busua_cod";

         if ($DB->Execute($sql, $valores))
         {
            $this->esta_cod      = 3;
            $retval              = true;
         }
         return $retval;
      }

      // actualiza un registro
      function actualiza($busua_cod, $numero, $esta_cod, &$DB)
      {
         $retval                 = false;

         $valores['busua_cod']   = $busua_cod;
         $valores['numero']      = $numero;
         $valores['esta_cod']    = $esta_cod;

         $sql                    = "UPDATE BP.BP_CONTACTOS_SMS a
                                    SET a.esta_cod = :esta_cod
                                    WHERE a.busua_cod = :busua_cod
                                    AND a.numero = :numero";

         if ($DB->Execute($sql, $valores))
         {
            $this->busua_cod     = $busua_cod;
            $this->numero     = $numero;
            $this->esta_cod      = $esta_cod;
            $retval              = true;
         }
         return $retval;
      }

   }

?>