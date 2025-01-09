<?
   class BOtrosProductos
   {
      var $prod_cod;
      var $tipo_cod;
      var $tipo;
      var $busua_cod;
      var $username;
      var $password;
      var $esta_cod;
      var $fecha_creacion;
      var $fecha_notificacion;
   
      // busca producto
      function buscaProducto($busua_cod, &$DB)
      {
         $retval                 = false;

         $valores['busua_cod']   = $busua_cod;

         
         $sql                    = "SELECT a.prod_cod,
                                          a.tipo_cod,
                                          a.busua_cod,
                                          a.username,
                                          a.esta_cod,
                                          TO_CHAR(a.fecha_creacion, 'DD-MM-YYYY HH24:MI:SS') fecha_creacion
                                    FROM BP.BP_OTROS_PRODUCTOS a
                                    WHERE a.busua_cod = :busua_cod";
         
         if ($DB->Query($sql, $valores))
         {
            $this->prod_cod         = $DB->value("PROD_COD");
            $this->tipo_cod         = $DB->value("TIPO_COD");
            $this->busua_cod        = $DB->value("BUSUA_COD");
            $this->username         = $DB->value("USERNAME");
            $this->esta_cod         = $DB->value("ESTA_COD");
            $this->fecha_creacion   = $DB->value("FECHA_CREACION");
            $retval                 = true;
         } else {
            $DB->Close();
         }
         return $retval;
      }

      // trae el siguiente producto
      function siguienteProducto(&$DB)
      {
         $retval                    = false;

         if ($DB->Next()) {
            $this->prod_cod         = $DB->value("PROD_COD");
            $this->tipo_cod         = $DB->value("TIPO_COD");
            $this->busua_cod        = $DB->value("BUSUA_COD");
            $this->username         = $DB->value("USERNAME");
            $this->esta_cod         = $DB->value("ESTA_COD");
            $this->fecha_creacion   = $DB->value("FECHA_CREACION");
            $retval                 = true;
         } else {
            $DB->Close();
         }
         return $retval;
      }

      // busca los productos de un usuario
      function buscaProductosUsuario($busua_cod, &$DB)
      {
         $retval                 = false;

         $valores['busua_cod']   = $busua_cod;

         $sql                    = "SELECT a.prod_cod,
                                          a.tipo_cod,
                                          c.tipo,
                                          a.busua_cod,
                                          a.username,
                                          a.esta_cod,
                                          TO_CHAR(a.fecha_creacion, 'DD-MM-YYYY HH24:MI:SS') fecha_creacion,
                                          TO_CHAR(a.fecha_notificacion, 'DD-MM-YYYY HH24:MI:SS') fecha_notificacion
                                    FROM BP.BP_OTROS_PRODUCTOS a,
                                    BP.BP_USUARIO b,
                                    BP.BP_TIPO_BOTON c
                                    WHERE a.busua_cod = b.busua_cod
                                    AND a.tipo_cod = c.tipo_cod
                                    AND a.busua_cod = :busua_cod
                                    AND a.esta_cod IN (1, 2)
                                    AND b.esta_cod IN (1, 2)";

         if ($DB->Query($sql, $valores))
         {
            $this->prod_cod            = $DB->value("PROD_COD");
            $this->tipo_cod            = $DB->value("TIPO_COD");
            $this->tipo                = $DB->Value("TIPO");
            $this->busua_cod           = $DB->value("BUSUA_COD");
            $this->username            = $DB->value("USERNAME");
            $this->esta_cod            = $DB->value("ESTA_COD");
            $this->fecha_creacion      = $DB->value("FECHA_CREACION");
            $this->fecha_notificacion  = $DB->value("FECHA_NOTIFICACION");
            $retval                    = true;
         } else {
            $DB->Close();
         }
         return $retval;
      }

      // trae el siguiente producto de un usuario
      function siguienteProductosUsuario(&$DB)
      {
         $retval                       = false;

         if ($DB->Next()) {
            $this->prod_cod            = $DB->value("PROD_COD");
            $this->tipo_cod            = $DB->value("TIPO_COD");
            $this->tipo                = $DB->Value("TIPO");
            $this->busua_cod           = $DB->value("BUSUA_COD");
            $this->username            = $DB->value("USERNAME");
            $this->esta_cod            = $DB->value("ESTA_COD");
            $this->fecha_creacion      = $DB->value("FECHA_CREACION");
            $this->fecha_notificacion  = $DB->value("FECHA_NOTIFICACION");
            $retval                    = true;
         } else {
            $DB->Close();
         }
         return $retval;
      }

      // busca producto
      function busca($prod_cod, &$DB)
      {
         $retval                    = false;

         $valores['prod_cod']      = $prod_cod;

         $sql                       = "SELECT a.prod_cod,
                                             a.tipo_cod,
                                             b.tipo,
                                             a.busua_cod,
                                             a.username,
                                             a.esta_cod,
                                             TO_CHAR(a.fecha_creacion, 'DD-MM-YYYY HH24:MI:SS') fecha_creacion
                                       FROM BP.BP_OTROS_PRODUCTOS a,
                                       BP.BP_TIPO_BOTON b
                                       WHERE a.tipo_cod = b.tipo_cod
                                       AND a.prod_cod = :prod_cod";

         if ($DB->Query($sql, $valores)) {
            $this->prod_cod         = $DB->value("PROD_COD");
            $this->tipo_cod         = $DB->value("TIPO_COD");
            $this->tipo             = $DB->Value("TIPO");
            $this->busua_cod        = $DB->value("BUSUA_COD");
            $this->username         = $DB->value("USERNAME");
            $this->esta_cod         = $DB->value("ESTA_COD");
            $this->fecha_creacion   = $DB->value("FECHA_CREACION");
            $retval                 = true;
         }
         $DB->Close();
         return $retval;
      }

      // busca producto
      function buscaTipo($busua_cod, $tipo_cod, &$DB)
      {
         $retval                    = false;

         $valores['busua_cod']      = $busua_cod;
         $valores['tipo_cod']       = $tipo_cod;

         $sql                       = "SELECT a.prod_cod,
                                             a.tipo_cod,
                                             b.tipo,
                                             a.busua_cod,
                                             a.username,
                                             a.esta_cod,
                                             TO_CHAR(a.fecha_creacion, 'DD-MM-YYYY HH24:MI:SS') fecha_creacion
                                       FROM BP.BP_OTROS_PRODUCTOS a,
                                       BP.BP_TIPO_BOTON b
                                       WHERE a.tipo_cod = b.tipo_cod
                                       AND a.busua_cod = :busua_cod
                                       AND a.tipo_cod = :tipo_cod";

         if ($DB->Query($sql, $valores)) {
            $this->prod_cod         = $DB->value("PROD_COD");
            $this->tipo_cod         = $DB->value("TIPO_COD");
            $this->tipo             = $DB->Value("TIPO");
            $this->busua_cod        = $DB->value("BUSUA_COD");
            $this->username         = $DB->value("USERNAME");
            $this->esta_cod         = $DB->value("ESTA_COD");
            $this->fecha_creacion   = $DB->value("FECHA_CREACION");
            $retval                 = true;
         }
         $DB->Close();
         return $retval;
      }

      // inserta en la tabla
      function inserta($tipo_cod, $busua_cod, $username, $password, &$DB) {

         $retval                 = false;

         $valores['tipo_cod']    = $tipo_cod;
         $valores['busua_cod']   = $busua_cod;
         $valores['username']    = $username;
         $valores['password']    = $password;

         $sql                    = "INSERT INTO BP.BP_OTROS_PRODUCTOS (tipo_cod, busua_cod, username, password)
                                    VALUES (:tipo_cod, :busua_cod, :username, :password)
                                    RETURNING prod_cod";

         $this->prod_cod = $DB->ExecuteReturning($sql, $valores);

         if($this->prod_cod !== false) {
            $retval              = true;
         }
         
         return $retval;

      }

      // modifica datos
      function actualiza($cloud_username, $cloud_password, $DB)
      {
         $retval                    = false;

         $valores['prod_cod']       = $this->prod_cod;
         $valores['busua_cod']      = $this->busua_cod;
         $valores['cloud_username'] = $cloud_username;
         $valores['cloud_password'] = $cloud_password;

         $sql                    = "UPDATE BP.BP_OTROS_PRODUCTOS a
                                    SET a.username = :cloud_username,
                                    a.password = :cloud_password
                                    WHERE a.prod_cod = :prod_cod
                                    AND a.busua_cod = :busua_cod";
         
         if ($DB->Execute($sql, $valores)) {
            $retval              = true;
         }
         return $retval;
      }

      // modifica el estado
      function actualizaEstado($esta_cod, &$DB)
      {
         $retval                 = false;

         $valores['prod_cod']    = $this->prod_cod;
         $valores['esta_cod']    = $esta_cod;

         $sql                    = "UPDATE BP.BP_OTROS_PRODUCTOS a
                                    SET a.esta_cod = :esta_cod
                                    WHERE a.prod_cod = :prod_cod";

         if ($DB->Execute($sql, $valores)) {
            $retval              = true;
         }
         return $retval;
      }

      # actualiza fecha de creacion
      function actualizaFechaCreacion(&$DB)
      {
         $retval                 = false;

         $valores['prod_cod']    = $this->prod_cod;

         $sql                    = "UPDATE BP.BP_OTROS_PRODUCTOS a
                                    SET a.fecha_creacion =  SYSDATE
                                    WHERE a.prod_cod = :prod_cod";

         if ($DB->Execute($sql, $valores)) {
            $retval              = true;
         }
         return $retval;
      }

      # actualiza fecha de notificacion
      function actualizaFechaNotificacion(&$DB)
      {
         $retval                       = false;

         $valores['prod_cod']          = $this->prod_cod;

         $sql                          = "UPDATE BP.BP_OTROS_PRODUCTOS a
                                          SET a.fecha_notificacion =  SYSDATE
                                          WHERE a.prod_cod = :prod_cod";

         if ($DB->Execute($sql, $valores)) {
            $this->fecha_notificacion  = date('m-d-Y H:i:s');
            $retval                    = true;
         }
         return $retval;
      }

   }
?>