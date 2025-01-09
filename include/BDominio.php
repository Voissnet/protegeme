<?
   class BDominio
   {

      var $dom_cod;
      var $dominio;
      var $gate_cod;
      var $dominio_usuario;
      var $esta_cod;
      var $callback;
      var $notificado;
      var $contacto;
      var $tipo_login;
      var $demo;
      var $cantidad_usuario;
      var $cantidad;

      // trae el primer registro
      function primero(&$DB)
      {
         $retval                       = false;

         $sql                          = "SELECT a.dom_cod,
                                                a.dominio,
                                                a.gate_cod,
                                                a.dominio_usuario,
                                                a.esta_cod,
                                                a.callback,
                                                a.notificado,
                                                a.contacto,
                                                a.tipo_login,
                                                a.demo,
                                                a.cantidad_usuario
                                          FROM BP.BP_DOMINIO a
                                          WHERE a.esta_cod = 1
                                          ORDER BY a.dominio_usuario ASC";

         if ($DB->Query($sql)) 
         {
            $this->dom_cod             = $DB->Value("DOM_COD");
            $this->dominio             = $DB->Value("DOMINIO");
            $this->gate_cod            = $DB->Value("GATE_COD");
            $this->dominio_usuario     = $DB->Value("DOMINIO_USUARIO");
            $this->esta_cod            = $DB->Value("ESTA_COD");
            $this->callback            = $DB->Value("CALLBACK");
            $this->notificado          = $DB->Value("NOTIFICADO");
            $this->contacto            = $DB->Value("CONTACTO");
            $this->tipo_login          = $DB->Value("TIPO_LOGIN");
            $this->demo                = $DB->Value("DEMO");
            $this->cantidad_usuario    = $DB->Value("CANTIDAD_USUARIO");
            $retval                    = true;
         } else {
            $DB->Close();
         }
         return $retval;
      }

      // trae el siguiente registro
      function siguiente(&$DB)
      {
         $retval                       = false;

         if ($DB->Next())
         {
            $this->dom_cod             = $DB->Value("DOM_COD");
            $this->dominio             = $DB->Value("DOMINIO");
            $this->gate_cod            = $DB->Value("GATE_COD");
            $this->dominio_usuario     = $DB->Value("DOMINIO_USUARIO");
            $this->esta_cod            = $DB->Value("ESTA_COD");
            $this->callback            = $DB->Value("CALLBACK");
            $this->notificado          = $DB->Value("NOTIFICADO");
            $this->contacto            = $DB->Value("CONTACTO");
            $this->tipo_login          = $DB->Value("TIPO_LOGIN");
            $this->demo                = $DB->Value("DEMO");
            $this->cantidad_usuario    = $DB->Value("CANTIDAD_USUARIO");
            $retval                    = true;
         } else {
            $DB->Close();
         }
         return $retval;
      }

      // busca deominio segun el codigo
      function busca($dom_cod, &$DB)
      {
         $retval                       = false;

         $valores['dom_cod']           = $dom_cod;

         $sql                          = "SELECT a.dom_cod,
                                                a.dominio,
                                                a.gate_cod,
                                                a.dominio_usuario,
                                                a.esta_cod,
                                                a.callback,
                                                a.notificado,
                                                a.contacto,
                                                a.tipo_login,
                                                a.demo,
                                                a.cantidad_usuario
                                          FROM BP.BP_DOMINIO a
                                          WHERE a.dom_cod = :dom_cod
                                          AND a.esta_cod = 1";

         if ($DB->Query($sql, $valores))
         {
            $this->dom_cod             = $DB->Value("DOM_COD");
            $this->dominio             = $DB->Value("DOMINIO");
            $this->gate_cod            = $DB->Value("GATE_COD");
            $this->dominio_usuario     = $DB->Value("DOMINIO_USUARIO");
            $this->esta_cod            = $DB->Value("ESTA_COD");
            $this->callback            = $DB->Value("CALLBACK");
            $this->notificado          = $DB->Value("NOTIFICADO");
            $this->contacto            = $DB->Value("CONTACTO");
            $this->tipo_login          = $DB->Value("TIPO_LOGIN");
            $this->demo                = $DB->Value("DEMO");
            $this->cantidad_usuario    = $DB->Value("CANTIDAD_USUARIO");
            $retval                    = true;
         }
         $DB->Close();
         return $retval;
      }

      // verifica si el gate_cod
      function verificaGateCod($gate_cod, &$DB)
      {
         $retval                       = false;

         $valores['gate_cod']          = $gate_cod;

         $sql                          = "SELECT a.dom_cod,
                                                a.dominio,
                                                a.gate_cod,
                                                a.dominio_usuario,
                                                a.esta_cod,
                                                a.callback,
                                                a.notificado,
                                                a.contacto,
                                                a.tipo_login,
                                                a.demo,
                                                a.cantidad_usuario
                                          FROM BP.BP_DOMINIO a
                                          WHERE a.gate_cod = :gate_cod";
         
         if ($DB->Query($sql, $valores))
         {
            $this->dom_cod             = $DB->Value("DOM_COD");
            $this->dominio             = $DB->Value("DOMINIO");
            $this->gate_cod            = $DB->Value("GATE_COD");
            $this->dominio_usuario     = $DB->Value("DOMINIO_USUARIO");
            $this->esta_cod            = $DB->Value("ESTA_COD");
            $this->callback            = $DB->Value("CALLBACK");
            $this->notificado          = $DB->Value("NOTIFICADO");
            $this->contacto            = $DB->Value("CONTACTO");
            $this->tipo_login          = $DB->Value("TIPO_LOGIN");
            $this->demo                = $DB->Value("DEMO");
            $this->cantidad_usuario    = $DB->Value("CANTIDAD_USUARIO");
            $retval                    = true;
         }
         $DB->Close();
         return $retval;
      }

      // verifica dominio
      function verificaDominio($dominio, &$DB)
      {
         $retval                       = false;

         $valores['dominio']           = $dominio;

         $sql                          = "SELECT a.dom_cod,
                                                a.dominio,
                                                a.gate_cod,
                                                a.dominio_usuario,
                                                a.esta_cod,
                                                a.callback,
                                                a.notificado,
                                                a.contacto,
                                                a.tipo_login,
                                                a.demo,
                                                a.cantidad_usuario
                                          FROM BP.BP_DOMINIO a
                                          WHERE a.dominio = :dominio";
         
         if ($DB->Query($sql, $valores))
         {
            $this->dom_cod             = $DB->Value("DOM_COD");
            $this->dominio             = $DB->Value("DOMINIO");
            $this->gate_cod            = $DB->Value("GATE_COD");
            $this->dominio_usuario     = $DB->Value("DOMINIO_USUARIO");
            $this->esta_cod            = $DB->Value("ESTA_COD");
            $this->callback            = $DB->Value("CALLBACK");
            $this->notificado          = $DB->Value("NOTIFICADO");
            $this->contacto            = $DB->Value("CONTACTO");
            $this->tipo_login          = $DB->Value("TIPO_LOGIN");
            $this->demo                = $DB->Value("DEMO");
            $this->cantidad_usuario    = $DB->Value("CANTIDAD_USUARIO");
            $retval                    = true;
         }
         $DB->Close();
         return $retval;
      }

      // veridica dominio usuario
      function verificaDominioUsuario($dominio_usuario, &$DB)
      {
         $retval                       = false;

         $valores['dominio_usuario']   = $dominio_usuario;

         $sql                          = "SELECT a.dom_cod,
                                                a.dominio,
                                                a.gate_cod,
                                                a.dominio_usuario,
                                                a.esta_cod
                                          FROM BP.BP_DOMINIO a
                                          WHERE a.dominio_usuario = :dominio_usuario";
         
         if ($DB->Query($sql, $valores))
         {
            $this->dom_cod             = $DB->Value("DOM_COD");
            $this->dominio             = $DB->Value("DOMINIO");
            $this->gate_cod            = $DB->value("GATE_COD");
            $this->dominio_usuario     = $DB->Value("DOMINIO_USUARIO");
            $this->esta_cod            = $DB->Value("ESTA_COD");
            $retval                    = true;
         }
         $DB->Close();
         return $retval;
      }

      // inserta un nuevo registro
      function insert($dominio, $gate_cod, $dominio_usuario, &$DB)
      {
         $retval                       = false;

         $valores['dominio']           = $dominio;
         $valores['gate_cod']          = $gate_cod;
         $valores['dominio_usuario']   = $dominio_usuario;

         $sql                          = "INSERT INTO BP.BP_DOMINIO (dominio, gate_cod, dominio_usuario)
                                          VALUES (:dominio, :gate_cod, :dominio_usuario)
                                          RETURNING dom_cod";

         $this->dom_cod                = $DB->ExecuteReturning($sql, $valores);
         $result                       = $this->dom_cod ===  false ? false : true;

         if($result) 
         {
            $retval						   = true;
         }
         return $retval;
      }

      // elimina el dominio
      function delete($dom_cod, &$DB)
      {
         $retval                       = false;

         $secuencia              = $DB->GetSequence("FG.SEQ_FC_NUMERO_INTERNO");
         $valores['dom_cod']     = $dom_cod;
         $valores['secuencia']   = $secuencia;


         $sql                    = "UPDATE BP.BP_DOMINIO a
                                    SET a.dominio = a.dominio || '_' || :secuencia,
                                       a.dominio_usuario = a.dominio_usuario || '_' || :secuencia,
                                       a.esta_cod = 3
                                    WHERE a.dom_cod = :dom_cod";

         if ($DB->Execute($sql, $valores))
         {
            $this->esta_cod      = 3;
            $retval              = true;
         }
         return $retval;

      }

      // busca dominio de un usuario
      function buscaDominioUsuario($busua_cod, &$DB)
      {
         $retval                 = true;

         $valores['busua_cod']   = $busua_cod;

         $sql                    = "SELECT c.dom_cod,
                                          c.gate_cod
                                    FROM BP.BP_USUARIO a,
                                    BP.BP_GRUPO b,
                                    BP.BP_DOMINIO c
                                    WHERE a.group_cod = b.group_cod
                                    AND b.dom_cod = c.dom_cod
                                    AND a.esta_cod IN (1, 2)
                                    AND b.esta_cod IN (1, 2)
                                    AND c.esta_cod IN (1, 2)
                                    AND a.busua_cod = :busua_cod";

         if($DB->Query($sql, $valores))
         {
            $this->dom_cod       = $DB->value("DOM_COD");
            $this->gate_cod      = $DB->value("GATE_COD");
            $retval              = true;
         }
         $DB->Close();
         return $retval;
      }

      // obtiene cantidad de usuarios actual
      function cantidadUsuarios(&$DB)
      {
         $retval                 = true;

         $valores['dom_cod']     = $this->dom_cod;

         $sql                    = "SELECT COUNT(c.busua_cod) cantidad
                                    FROM BP.BP_DOMINIO a,
                                    BP.BP_GRUPO b,
                                    BP.BP_USUARIO c
                                    WHERE a.dom_cod = b.dom_cod
                                    AND b.group_cod = c.group_cod
                                    AND a.dom_cod = :dom_cod
                                    AND a.esta_cod = 1
                                    AND b.esta_cod = 1
                                    AND c.esta_cod IN (1, 2)";

         if($DB->Query($sql, $valores))
         {
            $this->cantidad      = $DB->Value("CANTIDAD");
            $retval              = true;
         }
         $DB->Close();
         return $retval;
      }
      
   }
?>