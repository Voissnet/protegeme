<?
   class BContactosLlamada
   {
      var $busua_cod;
      var $numero;
      var $esta_cod;
      var $nombre;
      var $escucha;

      // trae informacion desde el primer registro
      function primero($busua_cod, &$DB)
      {
         $retval                 = false;

         $valores["busua_cod"]   = $busua_cod;

         $sql                    = "SELECT a.busua_cod, 
                                          a.numero,
                                          a.esta_cod,
                                          a.nombre,
                                          a.escucha
                                    FROM  BP.BP_CONTACTOS_LLAMADA a
                                    WHERE a.esta_cod = 1
                                    AND a.busua_cod = :busua_cod";

         if($DB->Query($sql, $valores))
         {
            $this->busua_cod     = $DB->Value("BUSUA_COD");
            $this->numero        = $DB->Value("NUMERO");
            $this->esta_cod      = $DB->Value("ESTA_COD");
            $this->nombre        = $DB->Value("NOMBRE");
            $this->escucha       = $DB->Value("ESCUCHA");
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
            $this->nombre        = $DB->Value("NOMBRE");
            $this->escucha       = $DB->Value("ESCUCHA");
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

         $valores["busua_cod"]   = $busua_cod;

         $sql                    = "SELECT a.busua_cod, 
                                          a.numero,
                                          a.esta_cod,
                                          a.nombre,
                                          a.escucha
                                    FROM  BP.BP_CONTACTOS_LLAMADA a
                                    WHERE a.esta_cod IN (1, 2)
                                    AND a.busua_cod = :busua_cod";

         if($DB->Query($sql, $valores))
         {
            $this->busua_cod     = $DB->Value("BUSUA_COD");
            $this->numero        = $DB->Value("NUMERO");
            $this->esta_cod      = $DB->Value("ESTA_COD");
            $this->nombre        = $DB->Value("NOMBRE");
            $this->escucha       = $DB->Value("ESCUCHA");
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
                                          a.nombre,
                                          a.esta_cod,
                                          a.escucha
                                    FROM BP.BP_CONTACTOS_LLAMADA a
                                    WHERE a.busua_cod = :busua_cod
                                    AND a.numero = :numero";

         if($DB->Query($sql, $valores))
         {
            $this->busua_cod     = $busua_cod;
            $this->numero        = $numero;
            $this->nombre        = $DB->Value("NOMBRE");
            $this->esta_cod      = $DB->Value("ESTA_COD");
            $this->escucha       = $DB->Value("ESCUCHA");
            $retval              = true;
         }
         $DB->Close();
         return $retval;
      }

      // busca contactos, llamadas y sms
      function buscaContactos($busua_cod, &$DB)
      {
         $retval                 = false;

         $valores['busua_cod']   = $busua_cod;

         $sql                    = "SELECT a.numero
                                    FROM BP.BP_CONTACTOS_LLAMADA a
                                    WHERE a.busua_cod = :busua_cod
                                    AND a.esta_cod IN (1, 2)
                                    
                                    UNION
                                    
                                    SELECT b.numero
                                    FROM BP.BP_CONTACTOS_SMS b
                                    WHERE b.busua_cod = :busua_cod
                                    AND b.esta_cod IN (1, 2)";
         
         if($DB->Query($sql, $valores))
         {
            $this->numero        = $DB->Value("NUMERO");
            $retval              = true;
         }
         else
            $DB->Close();
         return $retval;
      }
      
      // trae el siguiente registro
      function siguienteContacto(&$DB)
      {
         $retval                 = false;

         if($DB->Next())
         {
            $this->numero        = $DB->Value("NUMERO");
            $retval              = true;
         }
         else
            $DB->Close();
         return $retval;
      }

      // crea un nuevo registro
      function insert($busua_cod, $numero, $nombre, $esta_cod, &$DB)
      {
         $retval                 = false;
         
         $valores['busua_cod']   = $busua_cod;
         $valores['numero']      = $numero;
         $valores['nombre']      = $nombre;
         $valores['esta_cod']    = $esta_cod;

         $sql                    = "INSERT INTO BP.BP_CONTACTOS_LLAMADA (busua_cod, numero, nombre, esta_cod)
                                    VALUES (:busua_cod, :numero, :nombre, :esta_cod)";

         if ($DB->Execute($sql, $valores))
         {
            $this->busua_cod     = $busua_cod;
            $this->numero        = $numero;
            $this->esta_cod      = $esta_cod;
            $this->nombre        = $nombre;
            $this->escucha       = 0;
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

         $sql                    = "UPDATE BP.BP_CONTACTOS_LLAMADA a
                                    SET a.esta_cod = :esta_cod
                                    WHERE a.busua_cod = :busua_cod
                                    AND a.numero = :numero";

         if ($DB->Execute($sql, $valores))
         {
            $this->busua_cod     = $busua_cod;
            $this->numero        = $numero;
            $this->esta_cod      = $esta_cod;
            $retval              = true;
         }
         return $retval;
      }

      // actualiza el nombre de un contacto
      function actualizaNombre($busua_cod, $numero, $nombre, &$DB)
      {
         $retval                 = false;

         $valores['busua_cod']   = $busua_cod;
         $valores['numero']      = $numero;
         $valores['nombre']      = $nombre;

         $sql                    = "UPDATE BP.BP_CONTACTOS_LLAMADA a
                                    SET a.nombre = :nombre
                                    WHERE a.busua_cod = :busua_cod
                                    AND a.numero = :numero";
         
         if($DB->Execute($sql, $valores))
         {
            $this->busua_cod     = $busua_cod;
            $this->numero        = $numero;
            $this->nombre        = $nombre;
            $retval              = true;
         }
         return $retval;
      }
   
      // elimina todos los contactos de emergencia de un usuario dado
      function deleteAll(&$DB)
      {
         $retval                 = false;
         
         $secuencia              = $DB->GetSequence("FG.SEQ_FC_NUMERO_INTERNO");

         $valores['busua_cod']   = $this->busua_cod;
         $valores['secuencia']   = $secuencia;

         $sql                    = "UPDATE BP.BP_CONTACTOS_LLAMADA a
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

      // elimina un registro
      function delete($busua_cod, $numero, &$DB)
      {
         $retval                 = false;
         
         $secuencia              = $DB->GetSequence("FG.SEQ_FC_NUMERO_INTERNO");

         $valores['busua_cod']   = $busua_cod;
         $valores['numero']      = $numero;
         $valores['secuencia']   = $secuencia;

         $sql                    = "UPDATE BP.BP_CONTACTOS_LLAMADA a
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

      // actualiza un registro
      function actualizaEscucha($busua_cod, $numero, $escucha, &$DB)
      {
         $retval                 = false;

         $valores['busua_cod']   = $busua_cod;
         $valores['numero']      = $numero;
         $valores['escucha']     = $escucha;

         $sql                    = "UPDATE BP.BP_CONTACTOS_LLAMADA a
                                    SET a.escucha = :escucha
                                    WHERE a.busua_cod = :busua_cod
                                    AND a.numero = :numero";

         if ($DB->Execute($sql, $valores))
         {
            $this->busua_cod     = $busua_cod;
            $this->numero        = $numero;
            $this->escucha       = $escucha;
            $retval              = true;
         }
         return $retval;
      }

      // trae cantidad actual
      function cantidadEscucha ($busua_cod, &$DB)
      {
         $retval                 = false;

         $valores['busua_cod']   = $busua_cod;

         $sql                    = "SELECT COUNT(a.numero) AS escucha 
                                    FROM BP.BP_CONTACTOS_LLAMADA a
                                    WHERE a.busua_cod = :busua_cod
                                    AND a.esta_cod IN (1, 2)
                                    AND a.escucha = 1";

         if($DB->Query($sql, $valores))
         {
            $retval              = $DB->Value("ESCUCHA");
         }
         $DB->Close();
         return $retval;
      }

   }
?>