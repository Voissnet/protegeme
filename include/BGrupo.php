<?
   class BGrupo
   {
      var $group_cod;
      var $nombre;
      var $dom_cod;
      var $numeros;
      var $esta_cod;

      // busca un grupo dado
      function busca($group_cod, &$DB)
      {
         $retval                 = false;

         $valores['group_cod']   = $group_cod;

         $sql                    = "SELECT a.group_cod,
                                          a.nombre,
                                          a.dom_cod,
                                          a.numeros,
                                          a.esta_cod
                                    FROM BP.BP_GRUPO a
                                    WHERE group_cod = :group_cod";
         
         if($DB->Query($sql, $valores) === true)
         {
            $this->group_cod     = $DB->Value("GROUP_COD");
            $this->nombre        = $DB->Value("NOMBRE");
            $this->dom_cod       = $DB->Value("DOM_COD");
            $this->numeros       = $DB->Value("NUMEROS");
            $this->esta_cod      = $DB->Value("ESTA_COD");
            $retval              = true;
         }
         $DB->Close();
         return $retval;
      }

      // busca group
      function verificaGroup($dom_cod, $group_cod, &$DB)
      {
         $retval              = false;

         $valores['dom_cod']  = $dom_cod;
         $valores['group_cod']= $group_cod;

         $sql                 = "SELECT a.group_cod,
                                       a.nombre,
                                       a.dom_cod,
                                       a.numeros,
                                       a.esta_cod
                                 FROM BP.BP_GRUPO a
                                 WHERE a.dom_cod = :dom_cod
                                 AND a.group_cod = :group_cod";
         
         if ($DB->Query($sql, $valores))
         {
            $this->group_cod  = $DB->Value("GROUP_COD");
            $this->nombre     = $DB->Value("NOMBRE");
            $this->dom_cod    = $DB->Value("DOM_COD");
            $this->numeros    = $DB->Value("NUMEROS");
            $this->esta_cod   = $DB->Value("ESTA_COD");
            $retval           = true;
         }
         $DB->Close();
         return $retval;
      }

      // trae el primer registro
      function Primero($usua_cod, &$DB)
      {
         $retval                 = false;

         $valores['usua_cod']    = $usua_cod;
         $valores['tiga_cod']    = 102;

         $sql                    = "SELECT a.group_cod,
                                          a.nombre,
                                          a.dom_cod,
                                          a.numeros,
                                          a.esta_cod
                                    FROM  BP.BP_GRUPO a,
                                          BP.BP_DOMINIO b,
                                          FG.FC_GATEWAY c,
                                          FG.FC_USUARIO d
                                    WHERE a.dom_cod = b.dom_cod
                                          AND b.gate_cod = c.gate_cod
                                          AND c.usua_cod = d.usua_cod
                                          AND d.usua_cod = :usua_cod
                                          AND c.admin_esta_cod = 1
                                          AND c.tiga_cod = :tiga_cod
                                          AND d.esta_cod = 1
                                          AND a.esta_cod = 1";

         if($DB->Query($sql, $valores) === true)
         {
            $this->group_cod     = $DB->Value("GROUP_COD");
            $this->nombre        = $DB->Value("NOMBRE");
            $this->dom_cod       = $DB->Value("DOM_COD");
            $this->numeros       = $DB->Value("NUMEROS");
            $this->esta_cod      = $DB->Value("ESTA_COD");
            $retval              = true;
         }
         else
            $DB->Close();
         return $retval;
      }

      // trae el siguiente registro
      function Siguiente(&$DB)
      {
         $retval                 = false;

         if($DB->Next())
         {
            $this->group_cod     = $DB->Value("GROUP_COD");
            $this->nombre        = $DB->Value("NOMBRE");
            $this->dom_cod       = $DB->Value("DOM_COD");
            $this->numeros       = $DB->Value("NUMEROS");
            $this->esta_cod      = $DB->Value("ESTA_COD");
            $retval              = true;
         }
         else
            $DB->Close();
         return $retval;
      }
   
      // busca el grupo segun dominio dado
      function buscaDom($dom_cod, &$DB)
      {
         $retval              = false;

         $valores['dom_cod']  = $dom_cod;

         $sql                 = "SELECT a.group_cod,
                                       a.nombre,
                                       a.dom_cod,
                                       a.numeros,
                                       a.esta_cod
                                 FROM BP.BP_GRUPO a
                                 WHERE a.dom_cod = :dom_cod
                                 AND a.esta_cod = 1";
         
         if ($DB->Query($sql, $valores))
         {
            $this->group_cod  = $DB->Value("GROUP_COD");
            $this->nombre     = $DB->Value("NOMBRE");
            $this->dom_cod    = $DB->Value("DOM_COD");
            $this->numeros    = $DB->Value("NUMEROS");
            $this->esta_cod   = $DB->Value("ESTA_COD");
            $retval           = true;
         }
         else
            $DB->Close();
         return $retval;
      }
      
      // inserta un nuevo registro
      function insert($nombre, $dom_cod, $numeros, &$DB)
      {
         $retval                 = false;

         $valores['nombre']      = $nombre;
         $valores['dom_cod']     = $dom_cod;
         $valores['numeros']     = $numeros;

         $sql                    = "INSERT INTO BP.BP_GRUPO (nombre, dom_cod, numeros)
                                    VALUES (:nombre, :dom_cod, :numeros)
                                    RETURNING group_cod";

         $this->group_cod        = $DB->ExecuteReturning($sql, $valores);
         $result                 = $this->group_cod ===  false ? false : true;

         if($result) 
         {
            $retval              = true;
         }
         return $retval;
      }

      // busca group
      function verificaNombre($dom_cod, $nombre, &$DB)
      {
         $retval                 = false;

         $valores['dom_cod']     = $dom_cod;
         $valores['nombre']      = $nombre;

         $sql                    = "SELECT a.group_cod,
                                          a.nombre,
                                          a.dom_cod,
                                          a.numeros,
                                          a.esta_cod
                                    FROM BP.BP_GRUPO a
                                    WHERE a.dom_cod = :dom_cod
                                    AND a.nombre = :nombre
                                    AND a.esta_cod = 1";
         
         if ($DB->Query($sql, $valores))
         {
            $this->group_cod     = $DB->Value("GROUP_COD");
            $this->nombre        = $DB->Value("NOMBRE");
            $this->dom_cod       = $DB->Value("DOM_COD");
            $this->numeros       = $DB->Value("NUMEROS");
            $this->esta_cod      = $DB->Value("ESTA_COD");
            $retval              = true;
         }
         $DB->Close();
         return $retval;
      }

      // actualiza nombre de un grupo dado
      function actualizaNombre($nombre, &$DB)
      {
         $retval                 = false;

         $valores['group_cod']   = $this->group_cod;
         $valores['nombre']      = $nombre;

         $sql                    = "UPDATE BP.BP_GRUPO a
                                    SET a.nombre = :nombre
                                    WHERE a.group_cod = :group_cod";

         if ($DB->Execute($sql, $valores))
         {
            $this->nombre        = $nombre;
            $retval              = true;
         }
         return $retval;
      }

      // actualiza numeros del grupo
      function actualizaNumeros($numeros, &$DB)
      {
         $retval                 = false;

         $valores['group_cod']   = $this->group_cod;
         $valores['numeros']     = $numeros;

         $sql                    = "UPDATE BP.BP_GRUPO a
                                    SET a.numeros = :numeros
                                    WHERE a.group_cod = :group_cod";

         if ($DB->Execute($sql, $valores))
         {
            $this->numeros       = $numeros;
            $retval              = true;
         }
         return $retval;
      }

      // actualiza numeros del grupo
      function actualizaEstado($group_cod, $esta_cod, &$DB)
      {
         $retval                 = false;

         $valores['group_cod']   = $group_cod;
         $valores['esta_cod']    = $esta_cod;

         $sql                    = "UPDATE BP.BP_GRUPO a
                                    SET a.esta_cod = :esta_cod
                                    WHERE a.group_cod = :group_cod";

         if ($DB->Execute($sql, $valores))
         {
            $this->group_cod     = $group_cod;
            $this->esta_cod      = $esta_cod;
            $retval              = true;
         }
         return $retval;
      }
   }
?>