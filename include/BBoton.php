<?
   class BBoton
   {
      var $bot_cod;
      var $sip_username;
      var $sip_password;
      var $sip_display_name;
      var $esta_cod;
      var $busua_cod;
      var $tipo_cod;
      var $tipo;
      var $localizacion;
      var $coordenadas;
      var $fecha_creacion;
      var $fecha_notificacion;
      var $cloud_username;
      var $email;
      var $nombre;

      /* para boton estático */
      var $mac;
      var $numero; /* se debe considerar el primero numero telefónico del string obtenido */
      var $dominio;

      function BuscaBoton($busua_cod, &$DB)
      {
         $retval                       = false;

         $valores['busua_cod']         = $busua_cod;

         $sql                          = "SELECT a.bot_cod,
                                                a.sip_username,
                                                a.sip_password,
                                                a.sip_display_name,
                                                a.esta_cod,
                                                a.busua_cod,
                                                a.tipo_cod,
                                                a.localizacion,
                                                a.coordenadas,
                                                a.mac
                                          FROM BP.BP_BOTON a
                                          WHERE a.busua_cod = :busua_cod";
         
         if($DB->Query($sql, $valores) === TRUE)
         {
            $this->bot_cod             = $DB->Value("BOT_COD");
            $this->sip_username        = $DB->Value("SIP_USERNAME");
            $this->sip_password        = $DB->Value("SIP_PASSWORD");
            $this->sip_display_name    = $DB->Value("SIP_DISPLAY_NAME");
            $this->esta_cod            = $DB->Value("ESTA_COD");
            $this->busua_cod           = $busua_cod;
            $this->tipo_cod            = $DB->Value("TIPO_COD");
            $this->localizacion        = $DB->Value("LOCALIZACION");
            $this->coordenadas         = $DB->Value("COORDENADAS");
            $this->mac                 = $DB->Value("MAC");
            $retval                    = true;
         }
         $DB->Close();
         return $retval;
      }

      // busca un usuario dado activo
      function busca($bot_cod, &$DB)
      {
         $retval                    = false;

         $valores['bot_cod']        = $bot_cod;

         $sql                       = "SELECT a.bot_cod,
                                             a.sip_username,
                                             a.sip_password,
                                             a.sip_display_name,
                                             a.esta_cod,
                                             a.busua_cod,
                                             a.tipo_cod,
                                             b.tipo,
                                             a.localizacion,
                                             a.mac
                                       FROM BP.BP_BOTON a,
                                       BP.BP_TIPO_BOTON b
                                       WHERE a.tipo_cod = b.tipo_cod
                                       AND a.bot_cod = :bot_cod
                                       AND a.esta_cod in (1, 2)";
         
         if ($DB->Query($sql, $valores))
         {
            $this->bot_cod          = $DB->Value("BOT_COD");
            $this->sip_username     = $DB->Value("SIP_USERNAME");
            $this->sip_password     = $DB->Value("SIP_PASSWORD");
            $this->sip_display_name = $DB->Value("SIP_DISPLAY_NAME");
            $this->esta_cod         = $DB->Value("ESTA_COD");
            $this->busua_cod        = $DB->Value("BUSUA_COD");
            $this->tipo_cod         = $DB->Value("TIPO_COD");
            $this->tipo             = $DB->Value("TIPO");
            $this->localizacion     = $DB->Value("LOCALIZACION");
            $this->mac              = $DB->Value("MAC");
            $retval                 = true;
         }
         $DB->Close();
         return $retval;
      }

      function Aprovisiona($sip_username, $sip_password, $sip_display_name, $busua_cod, $tipo_cod, $localizacion, $gate_cod, &$DB)
      {
         $retval                       = false;

         $valores1["sip_username"]     = $sip_username;
         $valores1["sip_password"]     = $sip_password;
         $valores1["sip_display_name"] = $sip_display_name;
         $valores1["busua_cod"]        = $busua_cod;
         $valores1["tipo_cod"]         = $tipo_cod;
         $valores1["localizacion"]     = $localizacion;


         $arrContextOptions=array(
            "ssl" => array(
               "verify_peer"=>false,
               "verify_peer_name"=>false,
            ),
         );

         /* obtenemos coordenadas de acuerdo a direccion */
         $response = file_get_contents("https://pbe.redvoiss.net:9025/api/DireccionACoordenada?direccion=" . rawurlencode($localizacion), false, stream_context_create($arrContextOptions));
         $obj = json_decode($response);
         $valores1["coordenadas"] = $obj->lat . ";" . $obj->lon;

         $sql1                         = "INSERT INTO BP.BP_BOTON(sip_username, sip_password, sip_display_name, busua_cod, tipo_cod, localizacion, coordenadas)
                                          VALUES(:sip_username, :sip_password, :sip_display_name, :busua_cod, :tipo_cod, :localizacion, :coordenadas)
                                          RETURNING bot_cod";

         $DB->BeginTrans();

         $this->bot_cod = $DB->ExecuteReturning($sql1, $valores1);
         if ($this->bot_cod !== '' && $this->bot_cod !== FALSE)
         {
            $valores2["numero"]        = "533" . $gate_cod . $sip_username;
            $valores2["gate_cod"]      = $gate_cod;

            $sql2                      = "INSERT INTO FG.FC_GATEWAY_NUMERO(numero, gate_cod, puertas, privado, user_esta_cod, admin_esta_cod, prefijo1, prefijo2)
                                          VALUES (:numero, :gate_cod, 1, 1, 1, 1, '89', '9')";
            if ($DB->Execute($sql2, $valores2) === TRUE)
            {
               $valores3["conj_cod"]   = 2;
               $sql3 = "SELECT numero_real
                        FROM FG.GLBL_NUMERO_REAL
                              WHERE conj_cod = :conj_cod
                                    AND numero IS NULL
                                    AND fecha_libre < SYSDATE
                        ORDER BY fecha_libre";
               if ( $DB->Query($sql3, $valores3) === TRUE )
               {
                  $valores4["numero"]        = $valores2["numero"];
                  $valores4["numero_real"]   = $DB->Value("NUMERO_REAL");
                  
                  $sql4                = "UPDATE FG.GLBL_NUMERO_REAL
                                          SET numero = :numero,
                                             adicional = 0,
                                             fecha_libre = SYSDATE
                                          WHERE numero_real = :numero_real";
                                          
                  if( $DB->Execute($sql4, $valores4) === TRUE)
                  {
                     $DB->Commit();
                     $retval = TRUE;
                  }
                  else
                     $DB->Rollback();
               }
               else
                  $DB->Rollback();
            }
            else
               $DB->Rollback();
         }
         else
            $DB->Rollback();

         return $retval;
      }

      
      // elimina boton
      function delete(&$DB)
      {
         $retval                    = false;

         $valores['bot_cod']        = $this->bot_cod;

         $sql                       = "UPDATE BP.BP_BOTON a
                                       SET a.esta_cod = 3,
                                          a.mac = NULL
                                       WHERE a.bot_cod = :bot_cod";
         
         if ($DB->Execute($sql, $valores))
         {
            $retval                 = true;
         }
         return $retval;
      }

      // trae cantidad de botones activos
      function countActivosBP($dom_cod, &$DB)
      {
         $retval                    = false;

         $valores['dom_cod']        = $dom_cod;

         $sql                       = "SELECT COUNT(d.bot_cod) cantidad
                                       FROM BP.BP_DOMINIO a,
                                          BP.BP_GRUPO b,
                                          BP.BP_USUARIO c,
                                          BP.BP_BOTON d
                                       WHERE a.dom_cod = b.dom_cod
                                       AND b.group_cod = c.group_cod 
                                       AND c.busua_cod = d.busua_cod
                                       AND a.dom_cod = :dom_cod
                                       AND c.esta_cod IN (1, 2)
                                       AND d.esta_cod IN (1, 2)";

         if($DB->Query($sql, $valores))
         {
            $retval                 = $DB->Value("CANTIDAD");
         }
         $DB->Close();
         return $retval;
      }

      // verifica un username dentro de un dominio
      function verificaUserBoton($sip_username, $dom_cod, &$DB)
      {
         $retval                    = false;

         $valores['sip_username']   = $sip_username;
         $valores['dom_cod']        = $dom_cod;

         $sql                       = "SELECT a.bot_cod,
                                             a.sip_username
                                       FROM BP.BP_BOTON a,
                                       BP.BP_USUARIO b,
                                       BP.BP_GRUPO c,
                                       BP.BP_DOMINIO d
                                       WHERE a.busua_cod = b.busua_cod
                                       AND b.group_cod = c.group_cod
                                       AND c.dom_cod = d.dom_cod
                                       AND b.esta_cod IN (1, 2)
                                       AND a.esta_cod IN (1, 2)
                                       AND a.sip_username = :sip_username
                                       AND d.dom_cod = :dom_cod";
         
         if ($DB->Query($sql, $valores)) 
         {
            $retval                 = true;
         }
         $DB->Close();
         return $retval;
      }
      
      // registra un boton
      function insert($sip_username, $sip_password, $sip_display_name, $busua_cod, $tipo_cod, $localizacion, $coordenadas, $mac, &$DB)
      {
         $retval                       = false;

         $valores['sip_username']      = $sip_username;
         $valores['sip_password']      = $sip_password;
         $valores['sip_display_name']  = $sip_display_name;
         $valores['busua_cod']         = $busua_cod;
         $valores['tipo_cod']          = $tipo_cod;
         $valores['localizacion']      = $localizacion;
         $valores['coordenadas']       = $coordenadas;
         $valores['mac']               = $mac;

         $sql                          = "INSERT INTO BP.BP_BOTON (sip_username, sip_password, sip_display_name, busua_cod, tipo_cod, localizacion, coordenadas, mac)
                                          VALUES (:sip_username, :sip_password, :sip_display_name, :busua_cod, :tipo_cod, :localizacion, :coordenadas, :mac)
                                          RETURNING bot_cod";

         $this->bot_cod                = $DB->ExecuteReturning($sql, $valores);

         if ($this->bot_cod !== false) 
         {
            $retval                 = true;
         }
         return $retval;
      }

      // actualiza los datos del boton de panico
      function actualiza($sip_username, $sip_password, $sip_display_name, &$DB)
      {
         $retval                       = false;

         $valores['bot_cod']           = $this->bot_cod;
         $valores['sip_username']      = $sip_username;
         $valores['sip_password']      = $sip_password;
         $valores['sip_display_name']  = $sip_display_name;

         $sql                       = "UPDATE BP.BP_BOTON a
                                       SET a.sip_username = :sip_username,
                                          a.sip_password = :sip_password,
                                          a.sip_display_name = :sip_display_name
                                       WHERE a.bot_cod = :bot_cod";

         if ($DB->Execute($sql, $valores))
         {
            $this->sip_username     = $sip_username;
            $this->sip_password     = $sip_password;
            $this->sip_display_name = $sip_display_name;
            $retval                 = true;
         }
         return $retval;
      }

      // busca registros de un boton segun usuario dado
      function buscaUserTipo($busua_cod, $tipo_cod, &$DB)
      {
         $retval                    = false;

         $valores['busua_cod']      = $busua_cod;
         $valores['tipo_cod']       = $tipo_cod;

         $sql                       = "SELECT a.bot_cod,
                                             a.sip_username,
                                             a.sip_password,
                                             a.sip_display_name,
                                             a.esta_cod,
                                             a.busua_cod,
                                             a.tipo_cod,
                                             a.localizacion,
                                             a.mac
                                       FROM BP.BP_BOTON a
                                       WHERE a.busua_cod = :busua_cod
                                       AND a.tipo_cod = :tipo_cod";
         
         if ($DB->Query($sql, $valores))
         {
            $this->bot_cod          = $DB->Value("BOT_COD");
            $this->sip_username     = $DB->Value("SIP_USERNAME");
            $this->sip_password     = $DB->Value("SIP_PASSWORD");
            $this->sip_display_name = $DB->Value("SIP_DISPLAY_NAME");
            $this->esta_cod         = $DB->Value("ESTA_COD");
            $this->busua_cod        = $DB->Value("BUSUA_COD");
            $this->tipo_cod         = $DB->Value("TIPO_COD");
            $this->localizacion     = $DB->Value("LOCALIZACION");
            $this->mac              = $DB->Value("MAC");
            $retval                 = true;
         }
         $DB->Close();
         return $retval;
      }

      // busca un usuario dado activo
      function buscaUserActivo($busua_cod, &$DB)
      {
         $retval                    = false;

         $valores['busua_cod']      = $busua_cod;

         $sql                       = "SELECT a.bot_cod,
                                             a.sip_username,
                                             a.sip_password,
                                             a.sip_display_name,
                                             a.esta_cod,
                                             a.busua_cod,
                                             a.tipo_cod,
                                             b.tipo,
                                             a.localizacion,
                                             a.mac,
                                             TO_CHAR(a.fecha_creacion, 'MM-DD-YYYY HH24:MI:SS') fecha_creacion,
                                             TO_CHAR(a.fecha_notificacion, 'MM-DD-YYYY HH24:MI:SS') fecha_notificacion
                                       FROM BP.BP_BOTON a,
                                       BP.BP_TIPO_BOTON b
                                       WHERE a.tipo_cod = b.tipo_cod
                                       AND a.busua_cod = :busua_cod
                                       AND a.esta_cod in (1, 2)";
         
         if ($DB->Query($sql, $valores))
         {
            $this->bot_cod             = $DB->Value("BOT_COD");
            $this->sip_username        = $DB->Value("SIP_USERNAME");
            $this->sip_password        = $DB->Value("SIP_PASSWORD");
            $this->sip_display_name    = $DB->Value("SIP_DISPLAY_NAME");
            $this->esta_cod            = $DB->Value("ESTA_COD");
            $this->busua_cod           = $DB->Value("BUSUA_COD");
            $this->tipo_cod            = $DB->Value("TIPO_COD");
            $this->tipo                = $DB->Value("TIPO");
            $this->localizacion        = $DB->Value("LOCALIZACION");
            $this->mac                 = $DB->Value("MAC");
            $this->fecha_creacion      = $DB->Value("FECHA_CREACION");
            $this->fecha_notificacion  = $DB->Value("FECHA_NOTIFICACION");
            $retval                    = true;
         } else {
            $DB->Close();
         }
         return $retval;
      }

      // siguiente botones activos/inactivos
      function siguienteUserActivo(&$DB)
      {
         $retval                    = false;

         if ($DB->Next())
         {
            $this->bot_cod             = $DB->Value("BOT_COD");
            $this->sip_username        = $DB->Value("SIP_USERNAME");
            $this->sip_password        = $DB->Value("SIP_PASSWORD");
            $this->sip_display_name    = $DB->Value("SIP_DISPLAY_NAME");
            $this->esta_cod            = $DB->Value("ESTA_COD");
            $this->busua_cod           = $DB->Value("BUSUA_COD");
            $this->tipo_cod            = $DB->Value("TIPO_COD");
            $this->tipo                = $DB->Value("TIPO");
            $this->localizacion        = $DB->Value("LOCALIZACION");
            $this->mac                 = $DB->Value("MAC");
            $this->fecha_creacion      = $DB->Value("FECHA_CREACION");
            $this->fecha_notificacion  = $DB->Value("FECHA_NOTIFICACION");
            $retval                    = true;
         } else {
            $DB->Close();
         }
         return $retval;
      }

      // actualiza boton
      function actualizaEstado($esta_cod, &$DB)
      {
         $retval                 = true;

         $valores['bot_cod']     = $this->bot_cod;
         $valores['busua_cod']   = $this->busua_cod;
         $valores['esta_cod']    = $esta_cod;

         $sql                    = "UPDATE BP.BP_BOTON a
                                    SET a.esta_cod = :esta_cod
                                    WHERE a.busua_cod = :busua_cod
                                    AND a.bot_cod = :bot_cod";
         
         if ($DB->Execute($sql, $valores))
         {
            $retval                 = true;
         }
         return $retval;
      }

      // actualiza tipo de boton
      function actualizaTipo($tipo_cod, $status, &$DB)
      {
         $retval                    = false;

         $valores['bot_cod']        = $this->bot_cod;
         $valores['tipo_cod']       = $tipo_cod;

         $sql                       = "UPDATE BP.BP_BOTON a
                                       SET a.tipo_cod = :tipo_cod ";

         if ($status === true) {
            $sql                    .= " , a.localizacion = NULL,
                                          a.coordenadas = NULL,
                                          a.mac = NULL";   
         }

         $sql                       .= " WHERE a.bot_cod = :bot_cod";
         
         if ($DB->Execute($sql, $valores))
         {
            $this->tipo_cod         = $tipo_cod;
            $retval                 = true;
         }
         return $retval;       
      }

      // actualiza la localizacion
      function actualizaLocalizacion($localizacion, $coordenadas, &$DB)
      {
         $retval                    = false;

         $valores['bot_cod']        = $this->bot_cod;
         $valores['localizacion']   = $localizacion;
         $valores['coordenadas']    = $coordenadas;

         $sql                       = "UPDATE BP.BP_BOTON a
                                       SET a.localizacion = :localizacion,
                                          a.coordenadas = :coordenadas
                                       WHERE a.bot_cod = :bot_cod
                                       AND a.tipo_cod = 2";

         if($DB->Execute($sql, $valores))
         {
            $this->localizacion     = $localizacion;
            $this->coordenadas      = $coordenadas;
            $retval                 = true;
         }
         return $retval;
      }

      function CompruebaMac($mac, &$DB)
      {
         $retval           = FALSE;

         $valores['mac']   = $mac;

         $sql              = "SELECT a.sip_username,
                                    a.sip_password,
                                    REGEXP_SUBSTR (c.numeros, '(\d*)') numero,
                                    d.dominio
                              FROM BP.BP_BOTON a,
                                 BP.BP_USUARIO b,
                                 BP.BP_GRUPO c,
                                 BP.BP_DOMINIO d
                              WHERE a.busua_cod = b.busua_cod
                                    AND b.group_cod = c.group_cod
                                    AND c.dom_cod = d.dom_cod
                                    AND a.esta_cod = 1
                                    AND b.esta_cod = 1
                                    AND a.tipo_cod = 2
                                    AND c.esta_cod = 1
                                    AND d.esta_cod = 1
                                    AND a.mac = :mac";
         
         if($DB->Query($sql, $valores) === TRUE)
         {
            $this->mac           = $mac;
            $this->sip_username  = $DB->Value("SIP_USERNAME");
            $this->sip_password  = $DB->Value("SIP_PASSWORD");
            $this->numero        = $DB->Value("NUMERO");
            $this->dominio       = $DB->Value("DOMINIO");
            $retval              = TRUE;
         }
         $DB->Close();
         return $retval;
      }

      // verifica mac
      function verificaMac($mac, &$DB)
      {
         $retval                    = false;

         $valores['mac']            = $mac;

         $sql                       = "SELECT a.mac
                                       FROM BP.BP_BOTON a
                                       WHERE a.mac = :mac";

         if ($DB->Query($sql, $valores))
         {
            $this->mac              = $DB->Value("MAC");
            $retval                 = true;
         }
         $DB->Close();
         return $retval;
      }

      // actualiza boton
      function actualizaMac($mac, &$DB)
      {
         $retval                 = true;

         $valores['bot_cod']     = $this->bot_cod;
         $valores['mac']         = $mac;

         $sql                    = "UPDATE BP.BP_BOTON a
                                    SET a.mac = :mac
                                    WHERE a.bot_cod = :bot_cod";
         
         if ($DB->Execute($sql, $valores))
         {
            $this->mac           = $DB->Value("MAC");
            $retval              = true;
         }
         return $retval;
      }

      // trae servicios
      function buscaServicios($dom_cod, $tipo_cod, &$DB)
      {
         $retval                    = false;

         $valores['dom_cod']        = $dom_cod;
         
         if ($tipo_cod !== 0) {
            $valores['tipo_cod']    = $tipo_cod;
         }

         # Primera consulta (sin BP_OTROS_PRODUCTOS)
         $sql                       = "SELECT d.bot_cod AS bot_cod,
                                             a.busua_cod,
                                             a.cloud_username || '@' || c.dominio_usuario AS cloud_username,
                                             a.email,
                                             a.nombre,
                                             d.tipo_cod,
                                             e.tipo,
                                             TO_CHAR(d.fecha_creacion, 'YYYY-MM-DD HH24:MI:SS') AS fecha_creacion,
                                             TO_CHAR(d.fecha_notificacion, 'YYYY-MM-DD HH24:MI:SS') AS fecha_notificacion
                                       FROM BP.BP_USUARIO a
                                       JOIN BP.BP_GRUPO b ON b.group_cod = a.group_cod
                                       JOIN BP.BP_DOMINIO c ON c.dom_cod = b.dom_cod AND c.dom_cod = :dom_cod
                                       JOIN BP.BP_BOTON d ON a.busua_cod = d.busua_cod
                                       JOIN BP.BP_TIPO_BOTON e ON d.tipo_cod = e.tipo_cod
                                       WHERE a.esta_cod = 1
                                       AND b.esta_cod = 1
                                       AND c.esta_cod = 1
                                       AND d.esta_cod = 1 ";

         if ($tipo_cod !== 0) {
            $sql                    .= " AND d.tipo_cod = :tipo_cod";
         }

         $sql                       .= " UNION ALL ";

         $sql                       .= " -- Segunda consulta (con BP_OTROS_PRODUCTOS)
                                       SELECT i.prod_cod AS bot_cod,
                                             f.busua_cod,
                                             f.cloud_username || '@' || h.dominio_usuario AS cloud_username,
                                             f.email,
                                             f.nombre,
                                             j.tipo_cod,
                                             j.tipo,
                                             TO_CHAR(i.fecha_creacion, 'YYYY-MM-DD HH24:MI:SS') AS fecha_creacion,
                                             TO_CHAR(i.fecha_notificacion, 'YYYY-MM-DD HH24:MI:SS') AS fecha_notificacion
                                       FROM BP.BP_USUARIO f
                                       JOIN BP.BP_GRUPO g ON g.group_cod = f.group_cod
                                       JOIN BP.BP_DOMINIO h ON h.dom_cod = g.dom_cod AND h.dom_cod = :dom_cod
                                       JOIN BP.BP_OTROS_PRODUCTOS i ON i.busua_cod = f.busua_cod
                                       JOIN BP.BP_TIPO_BOTON j ON j.tipo_cod = i.tipo_cod
                                       WHERE f.esta_cod = 1
                                       AND g.esta_cod = 1
                                       AND h.esta_cod = 1
                                       AND i.esta_cod = 1";

         if ($tipo_cod !== 0) {
            $sql                    .= " AND i.tipo_cod = :tipo_cod";
         }

         if ($tipo_cod === 5) {
            
            $sql                    .= " UNION ALL
                                          SELECT o.busua_cod AS bot_cod,
                                                p.busua_cod,
                                                p.cloud_username || '@' || q2.dominio_usuario AS cloud_username,
                                                p.email,
                                                p.nombre,
                                                r.tipo_cod,
                                                r.tipo,
                                                'No aplica' AS fecha_creacion,
                                                'No aplica' AS fecha_notificacion
                                          FROM BP.BP_USUARIO p
                                          JOIN BP.BP_GRUPO q ON q.group_cod = p.group_cod
                                          JOIN BP.BP_DOMINIO q2 ON q2.dom_cod = q.dom_cod AND q2.dom_cod = 1
                                          JOIN BP.BP_TRACKER o ON o.busua_cod = p.busua_cod
                                          JOIN BP.BP_TIPO_BOTON r ON r.tipo_cod = 5
                                          WHERE p.esta_cod = 1
                                          AND q.esta_cod = 1
                                          AND q2.esta_cod = 1
                                          AND o.esta_cod = 1
                                          AND r.tipo_cod = 5 ";
         }

         $sql                       .= " ORDER BY tipo_cod, busua_cod ASC";

         if ($DB->Query($sql, $valores)) {
            $this->bot_cod             = $DB->value("BOT_COD");
            $this->busua_cod           = $DB->Value("BUSUA_COD");
            $this->cloud_username      = $DB->Value("CLOUD_USERNAME");
            $this->email               = $DB->Value("EMAIL");
            $this->nombre              = $DB->Value("NOMBRE");
            $this->tipo_cod            = $DB->Value("TIPO_COD");
            $this->tipo                = $DB->Value("TIPO");
            $this->fecha_creacion      = $DB->Value("FECHA_CREACION");
            $this->fecha_notificacion  = $DB->Value("FECHA_NOTIFICACION");
            $retval                    = true;
         } else {
            $DB->Close();
         }
         return $retval;
      }

      // siguiente servicios
      function siguienteServicios(&$DB)
      {
         $retval                       = false;

         if ($DB->Next()) {
            $this->bot_cod             = $DB->value("BOT_COD");
            $this->busua_cod           = $DB->Value("BUSUA_COD");
            $this->cloud_username      = $DB->Value("CLOUD_USERNAME");
            $this->email               = $DB->Value("EMAIL");
            $this->nombre              = $DB->Value("NOMBRE");
            $this->tipo_cod            = $DB->Value("TIPO_COD");
            $this->tipo                = $DB->Value("TIPO");
            $this->fecha_creacion      = $DB->Value("FECHA_CREACION");
            $this->fecha_notificacion  = $DB->Value("FECHA_NOTIFICACION");
            $retval                    = true;
         } else {
            $DB->Close();
         }
         return $retval;
      }

      // actualiza notificacion
      function actualizaNotificacion(&$DB)
      {
         $retval                       = false;

         $valores['bot_cod']           = $this->bot_cod;

         $sql                          = "UPDATE BP.BP_BOTON a
                                          SET a.fecha_notificacion = SYSDATE
                                          WHERE a.bot_cod = :bot_cod";

         if ($DB->Execute($sql, $valores)) {
            $this->fecha_notificacion  = date('m-d-Y H:i:s');
            $retval                    = true;
         }
         return $retval;
      }

   }

?>