<?
   class BUsuario
   {
      var $busua_cod;
      var $cloud_username;
      var $cloud_password;
      var $esta_cod;
      var $estado;
      var $dom_cod;
      var $dominio;
      var $dominio_usuario;
      var $user_phone;
      var $email;
      var $nombre;
      var $group_cod;
      var $fecha_creacion;
      var $notifica;
      var $fecha_notificacion;
      var $grupo;
      var $gate_cod;
      var $contacto;
      var $numeros;

      // trae el primer registro
      function primero(&$DB)
      {
         $retval                       = false;

         $sql                          = "SELECT a.busua_cod,
                                                a.cloud_username,
                                                a.cloud_password,
                                                a.esta_cod,
                                                a.user_phone,
                                                a.email,
                                                a.nombre,
                                                a.group_cod,
                                                TO_CHAR(a.fecha_creacion, 'DD-MM-YYYY HH24:MI:SS') fecha_creacion,
                                                a.notifica,
                                                TO_CHAR(a.fecha_notificacion, 'DD-MM-YYYY HH24:MI:SS') fecha_notificacion
                                          FROM BP.BP_USUARIO a";

         if ($DB->Query($sql)) 
         {
            $this->busua_cod           = $DB->Value("BUSUA_COD");
            $this->cloud_username      = $DB->Value("CLOUD_USERNAME");
            $this->cloud_password      = $DB->Value("CLOUD_PASSWORD");
            $this->esta_cod            = $DB->Value("ESTA_COD");
            $this->user_phone          = $DB->Value("USER_PHONE");
            $this->email               = $DB->Value("EMAIL");
            $this->nombre              = $DB->Value("NOMBRE");
            $this->group_cod           = $DB->Value("GROUP_COD");
            $this->fecha_creacion      = $DB->Value("FECHA_CREACION");
            $this->notifica            = $DB->Value("NOTIFICA");
            $this->fecha_notificacion  = $DB->Value("FECHA_NOTIFICACION");
            $retval                    = true;
         }
         else
            $DB->Close();
         return $retval;
      }

      // trae el siguiente dato
      function siguiente(&$DB)
      {
         $retval                       = false;

         if ($DB->Next()) 
         {
            $this->busua_cod           = $DB->Value("BUSUA_COD");
            $this->cloud_username      = $DB->Value("CLOUD_USERNAME");
            $this->cloud_password      = $DB->Value("CLOUD_PASSWORD");
            $this->esta_cod            = $DB->Value("ESTA_COD");
            $this->user_phone          = $DB->Value("USER_PHONE");
            $this->email               = $DB->Value("EMAIL");
            $this->nombre              = $DB->Value("NOMBRE");
            $this->group_cod           = $DB->Value("GROUP_COD");
            $this->fecha_creacion      = $DB->Value("FECHA_CREACION");
            $this->notifica            = $DB->Value("NOTIFICA");
            $this->fecha_notificacion  = $DB->Value("FECHA_NOTIFICACION");
            $retval                    = true;
         }
         else
            $DB->Close();
         return $retval;
      }

      function autenticaUsuario($cloud_username, $cloud_password, $cloud_id, &$DB)
      {
         $retval = false;

         $valores["cloud_username"] = $cloud_username;
         $valores["cloud_id"]       = $cloud_id;

         $sql = "SELECT a.busua_cod,
                  a.cloud_username,
                  a.cloud_password,
                  a.esta_cod,
                  c.dom_cod,
                  c.dominio,
                  c.dominio_usuario,
                  a.user_phone,
                  a.email,
                  a.nombre,
                  b.group_cod,
                  c.contacto,
                  b.numeros
                  FROM BP.BP_USUARIO a,
                     BP.BP_GRUPO b,
                     BP.BP_DOMINIO c
                  WHERE a.group_cod = b.group_cod
                  AND b.dom_cod = c.dom_cod
                  AND c.dominio_usuario = :cloud_id
                  AND a.cloud_username = :cloud_username
                  AND a.esta_cod = 1";
   
         if($DB->Query($sql, $valores) === true)
         {
            /* Encontró clave unica usuario@cloud_id y busca password */
            require_once("Parameters.php");
            $p_peppered = hash_hmac("sha256", $cloud_password, Parameters::PEPPER);
            if (password_verify($p_peppered, $DB->Value("CLOUD_PASSWORD")) === true)
            {
               $this->busua_cod        = $DB->Value("BUSUA_COD");
               $this->cloud_username   = $DB->Value("CLOUD_USERNAME");
               $this->cloud_password   = $DB->Value("CLOUD_PASSWORD");
               $this->esta_cod         = $DB->Value("ESTA_COD");
               $this->dom_cod          = $DB->Value("DOM_COD");
               $this->dominio          = $DB->Value("DOMINIO");
               $this->dominio_usuario  = $DB->Value("DOMINIO_USUARIO");
               $this->user_phone       = $DB->Value("USER_PHONE");
               $this->email            = $DB->Value("EMAIL");
               $this->nombre           = $DB->Value("NOMBRE");
               $this->group_cod        = $DB->Value("GROUP_COD");
               $this->contacto         = $DB->Value("CONTACTO");
               $this->numeros          = $DB->Value("NUMEROS");
               $retval                 = true;
            }
         }
         $DB->Close();
         return $retval;
      }

      // listado de usaurios creados/inactivos/eliminados
      function listadoUsuarios($usua_cod, &$DB)
      {
         $retval = false;

         $valores['usua_cod'] = $usua_cod;
         $valores['tiga_cod'] = 102;

         $sql = "SELECT a.busua_cod,
                        a.cloud_username,
                        a.cloud_password,
                        a.esta_cod,
                        f.estado,
                        c.dom_cod,
                        c.dominio,
                        c.dominio_usuario,
                        a.user_phone,
                        a.email,
                        a.nombre,
                        a.group_cod,
                        b.nombre grupo,
                        TO_CHAR(a.fecha_creacion, 'DD-MM-YYYY HH24:MI:SS') fecha_creacion
                  FROM  BP.BP_USUARIO a,
                        BP.BP_GRUPO b,
                        BP.BP_DOMINIO c,
                        FG.FC_GATEWAY d,
                        FG.FC_USUARIO e,
                        BP.BP_ESTADO f
                  WHERE a.group_cod = b.group_cod
                        AND b.dom_cod = c.dom_cod
                        AND c.gate_cod = d.gate_cod
                        AND d.usua_cod = e.usua_cod
                        AND a.esta_cod = f.esta_cod
                        AND e.usua_cod = :usua_cod
                        AND d.tiga_cod = :tiga_cod
                        AND e.esta_cod = 1
                        AND d.admin_esta_cod = 1";
         if($DB->Query($sql, $valores) === true)
         {
            $this->busua_cod        = $DB->Value("BUSUA_COD");
            $this->cloud_username   = $DB->Value("CLOUD_USERNAME");
            $this->cloud_password   = $DB->Value("CLOUD_PASSWORD");
            $this->esta_cod         = $DB->Value("ESTA_COD");
            $this->estado           = $DB->Value("ESTADO");
            $this->dom_cod          = $DB->Value("DOM_COD");
            $this->dominio          = $DB->Value("DOMINIO");
            $this->dominio_usuario  = $DB->Value("DOMINIO_USUARIO");
            $this->user_phone       = $DB->Value("USER_PHONE");
            $this->email            = $DB->Value("EMAIL");
            $this->nombre           = $DB->Value("NOMBRE");
            $this->group_cod        = $DB->Value("GROUP_COD");
            $this->grupo            = $DB->Value("GRUPO");
            $this->fecha_creacion   = $DB->Value("FECHA_CREACION");
            $retval                 = true;
         }
         else
            $DB->Close();
         return $retval;
      }

      function siguienteListadoUsuarios(&$DB)
      {
         $retval=false;
         if($DB->Next())
         {
            $this->busua_cod        = $DB->Value("BUSUA_COD");
            $this->cloud_username   = $DB->Value("CLOUD_USERNAME");
            $this->cloud_password   = $DB->Value("CLOUD_PASSWORD");
            $this->esta_cod         = $DB->Value("ESTA_COD");
            $this->estado           = $DB->Value("ESTADO");
            $this->dom_cod          = $DB->Value("DOM_COD");
            $this->dominio          = $DB->Value("DOMINIO");
            $this->dominio_usuario  = $DB->Value("DOMINIO_USUARIO");
            $this->user_phone       = $DB->Value("USER_PHONE");
            $this->email            = $DB->Value("EMAIL");
            $this->nombre           = $DB->Value("NOMBRE");
            $this->group_cod        = $DB->Value("GROUP_COD");
            $this->grupo            = $DB->Value("GRUPO");
            $this->fecha_creacion   = $DB->Value("FECHA_CREACION");
            $retval                 = true;
         }
         else
            $DB->Close();
         return $retval;
      }

      function inserta($cloud_username, $cloud_password, $user_phone, $email, $nombre, $group_cod, &$DB)
      {
         $retval                    = false;

         require_once 'Parameters.php';

         $p_peppered                = hash_hmac('sha256', $cloud_password, Parameters::PEPPER);
         $cloud_password            = password_hash($p_peppered, PASSWORD_BCRYPT);

         $valores["cloud_username"] = $cloud_username;
         $valores["cloud_password"] = $cloud_password;
         $valores["esta_cod"]       = 1;
         $valores["user_phone"]     = $user_phone;
         $valores["email"]          = $email;
         $valores["nombre"]         = $nombre;
         $valores["group_cod"]      = $group_cod;

         $sql                       = "INSERT INTO BP.BP_USUARIO (cloud_username, cloud_password, esta_cod, user_phone, email, nombre, group_cod)
                                       VALUES (:cloud_username, :cloud_password, :esta_cod, :user_phone, UPPER(:email), UPPER(:nombre), :group_cod)
                                       RETURNING busua_cod";

         if( ( $this->busua_cod = $DB->ExecuteReturning($sql, $valores)) !== false)
            $retval                 = true;

         return $retval;
      }

      function insert($nombre, $cloud_username, $cloud_password, $user_phone, $email, $group_cod, &$DB)
      {
         $retval                    = false;

         $valores['cloud_username'] = $cloud_username;
         $valores['cloud_password'] = $cloud_password;
         $valores['user_phone']     = $user_phone;
         $valores['email']          = $email;
         $valores['nombre']         = $nombre;
         $valores['group_cod']      = $group_cod;

         $sql                       = "INSERT INTO BP.BP_USUARIO (cloud_username, cloud_password, user_phone, email, nombre, group_cod)
                                       VALUES (:cloud_username, :cloud_password, :user_phone, :email, :nombre, :group_cod)
                                       RETURNING busua_cod";

         $result                       = $DB->ExecuteReturning($sql, $valores);

         if ($result !== false) 
         {
            $this->busua_cod           = $result;
            $this->cloud_username      = $cloud_username;
            $this->cloud_password      = $cloud_password;
            $this->esta_cod            = 1;
            $this->user_phone          = $user_phone;
            $this->email               = $email;
            $this->nombre              = $nombre;
            $this->group_cod           = $group_cod;
            $retval                    = true;
         }
         return $retval;
      }

      function busca($busua_cod, &$DB)
      {
         $retval                    = false;

         $valores['busua_cod']      = $busua_cod;

         $sql                       = "SELECT a.cloud_username,
                                             a.cloud_password,
                                             a.esta_cod,
                                             a.user_phone,
                                             a.email,
                                             a.nombre,
                                             a.group_cod
                                       FROM BP.BP_USUARIO a
                                       WHERE a.busua_cod = :busua_cod";
         
         if($DB->Query($sql, $valores))
         {
            $this->busua_cod        = $busua_cod;
            $this->cloud_username   = $DB->Value("CLOUD_USERNAME");
            $this->cloud_password   = $DB->Value("CLOUD_PASSWORD");
            $this->esta_cod         = $DB->Value("ESTA_COD");
            $this->user_phone       = $DB->Value("USER_PHONE");
            $this->email            = $DB->Value("EMAIL");
            $this->nombre           = $DB->Value("NOMBRE");
            $this->group_cod        = $DB->Value("GROUP_COD");
            $retval                 = true;
         }
         $DB->Close();
         return $retval;
      }

      /* returna true si busua_cod pertenece a usua_cod */
      function Pertenece($busua_cod, $usua_cod, $DB)
      {
         $retval                    = false;

         $valores['busua_cod']      = $busua_cod;
         $valores['usua_cod']       = $usua_cod;

         $sql                       = "SELECT a.usua_cod,
                                             c.gate_cod
                                       FROM  FG.FC_USUARIO a,
                                             FG.FC_GATEWAY b,
                                             BP.BP_DOMINIO c,
                                             BP.BP_GRUPO d,
                                             BP.BP_USUARIO e
                                       WHERE a.usua_cod = :usua_cod
                                       AND a.usua_cod = b.usua_cod
                                       AND b.gate_cod = c.gate_cod
                                       AND c.dom_cod = d.dom_cod
                                       AND d.group_cod = e.group_cod
                                       AND e.busua_cod = :busua_cod";

         if ($DB->Query($sql, $valores))
         {
            $this->gate_cod         = $DB->Value("GATE_COD");
            $retval                 = true;
         }
         return $retval;
      }

      // trae un registro de un usuario dado
      function buscaUser($busua_cod, &$DB)
      {
         $retval                       = false;

         $valores['busua_cod']         = $busua_cod;

         $sql                          = "SELECT a.busua_cod,
                                                a.cloud_username,
                                                a.cloud_password,
                                                a.esta_cod,
                                                a.user_phone,
                                                a.email,
                                                a.nombre,
                                                a.group_cod,
                                                TO_CHAR(a.fecha_creacion, 'DD-MM-YYYY HH24:MI:SS') fecha_creacion,
                                                a.notifica,
                                                TO_CHAR(a.fecha_notificacion, 'DD-MM-YYYY HH24:MI:SS') fecha_notificacion
                                          FROM BP.BP_USUARIO a
                                          WHERE a.busua_cod = :busua_cod";
         
         if ($DB->Query($sql, $valores))
         {
            $this->busua_cod           = $DB->Value("BUSUA_COD");
            $this->cloud_username      = $DB->Value("CLOUD_USERNAME");
            $this->cloud_password      = $DB->Value("CLOUD_PASSWORD");
            $this->esta_cod            = $DB->Value("ESTA_COD");
            $this->user_phone          = $DB->Value("USER_PHONE");
            $this->email               = $DB->Value("EMAIL");
            $this->nombre              = $DB->Value("NOMBRE");
            $this->group_cod           = $DB->Value("GROUP_COD");
            $this->fecha_creacion      = $DB->Value("FECHA_CREACION");
            $this->notifica            = $DB->Value("NOTIFICA");
            $this->fecha_notificacion  = $DB->Value("FECHA_NOTIFICACION");
            $retval                    = true;
         }
         else
            $DB->Close();
         return $retval;
      }

      // trae a los usuarios de un dominio en particular
      function buscaUsuariosGroup($group_cod, &$DB)
      {
         $retval                       = false;

         $valores['group_cod']         = $group_cod;

         $sql                          = "SELECT a.busua_cod,
                                                a.cloud_username,
                                                a.cloud_password,
                                                a.esta_cod,
                                                a.user_phone,
                                                a.email,
                                                a.nombre,
                                                a.group_cod,
                                                TO_CHAR(a.fecha_creacion, 'DD-MM-YYYY HH24:MI:SS') fecha_creacion,
                                                a.notifica,
                                                TO_CHAR(a.fecha_notificacion, 'DD-MM-YYYY HH24:MI:SS') fecha_notificacion
                                          FROM BP.BP_USUARIO a
                                          WHERE a.group_cod = :group_cod
                                          AND a.esta_cod IN (1, 2)";

         if ($DB->Query($sql, $valores)) 
         {
            $this->busua_cod           = $DB->Value("BUSUA_COD");
            $this->cloud_username      = $DB->Value("CLOUD_USERNAME");
            $this->cloud_password      = $DB->Value("CLOUD_PASSWORD");
            $this->esta_cod            = $DB->Value("ESTA_COD");
            $this->user_phone          = $DB->Value("USER_PHONE");
            $this->email               = $DB->Value("EMAIL");
            $this->nombre              = $DB->Value("NOMBRE");
            $this->group_cod           = $DB->Value("GROUP_COD");
            $this->fecha_creacion      = $DB->Value("FECHA_CREACION");
            $this->notifica            = $DB->Value("NOTIFICA");
            $this->fecha_notificacion  = $DB->Value("FECHA_NOTIFICACION");
            $retval                    = true;
         }
         else
            $DB->Close();
         return $retval;
      }

      // busca usuaros segun dominio dado
      function buscaUsuariosDom($dom_cod, &$DB)
      {
         $retval                       = false;

         $valores['dom_cod']           = $dom_cod;

         $sql                          = "SELECT a.busua_cod,
                                                a.cloud_username,
                                                a.cloud_password,
                                                a.esta_cod,
                                                a.user_phone,
                                                a.email,
                                                a.nombre,
                                                a.group_cod,
                                                TO_CHAR(a.fecha_creacion, 'DD-MM-YYYY HH24:MI:SS') fecha_creacion,
                                                a.notifica,
                                                TO_CHAR(a.fecha_notificacion, 'DD-MM-YYYY HH24:MI:SS') fecha_notificacion
                                          FROM BP.BP_USUARIO a,
                                             BP.BP_GRUPO b,
                                             BP.BP_DOMINIO c
                                          WHERE a.group_cod = b.group_cod
                                          AND b.dom_cod = c.dom_cod
                                          AND c.dom_cod = :dom_cod
                                          AND a.esta_cod IN (1, 2)
                                          ORDER BY a.busua_cod ASC";
         
         if ($DB->Query($sql, $valores)) 
         {
            $this->busua_cod           = $DB->Value("BUSUA_COD");
            $this->cloud_username      = $DB->Value("CLOUD_USERNAME");
            $this->cloud_password      = $DB->Value("CLOUD_PASSWORD");
            $this->esta_cod            = $DB->Value("ESTA_COD");
            $this->user_phone          = $DB->Value("USER_PHONE");
            $this->email               = $DB->Value("EMAIL");
            $this->nombre              = $DB->Value("NOMBRE");
            $this->group_cod           = $DB->Value("GROUP_COD");
            $this->fecha_creacion      = $DB->Value("FECHA_CREACION");
            $this->notifica            = $DB->Value("NOTIFICA");
            $this->fecha_notificacion  = $DB->Value("FECHA_NOTIFICACION");
            $retval                    = true;
         }
         else
            $DB->Close();
         return $retval;
      }

      // busca un usuario segun su identificador y grupo
      function verificaGrupo($busua_cod, $group_cod, &$DB)
      {
         $retval                       = false;

         $valores['busua_cod']         = $busua_cod;
         $valores['group_cod']         = $group_cod;

         $sql                          = "SELECT a.busua_cod,
                                                a.cloud_username,
                                                a.cloud_password,
                                                a.esta_cod,
                                                a.user_phone,
                                                a.email,
                                                a.nombre,
                                                a.group_cod,
                                                TO_CHAR(a.fecha_creacion, 'DD-MM-YYYY HH24:MI:SS') fecha_creacion,
                                                a.notifica,
                                                TO_CHAR(a.fecha_notificacion, 'DD-MM-YYYY HH24:MI:SS') fecha_notificacion
                                          FROM BP.BP_USUARIO a
                                          WHERE a.busua_cod = :busua_cod
                                          AND a.group_cod = :group_cod";

         if ($DB->Query($sql, $valores)) 
         {
            $this->busua_cod           = $DB->Value("BUSUA_COD");
            $this->cloud_username      = $DB->Value("CLOUD_USERNAME");
            $this->cloud_password      = $DB->Value("CLOUD_PASSWORD");
            $this->esta_cod            = $DB->Value("ESTA_COD");
            $this->user_phone          = $DB->Value("USER_PHONE");
            $this->email               = $DB->Value("EMAIL");
            $this->nombre              = $DB->Value("NOMBRE");
            $this->group_cod           = $DB->Value("GROUP_COD");
            $this->fecha_creacion      = $DB->Value("FECHA_CREACION");
            $this->notifica            = $DB->Value("NOTIFICA");
            $this->fecha_notificacion  = $DB->Value("FECHA_NOTIFICACION");
            $retval                    = true;
         }
         $DB->Close();
         return $retval;
      }

      // busca cloud username dentro de un dominio
      function verificaCloudUserDom($cloud_username, $dom_cod, &$DB)
      {
         $retval                       = false;

         $valores['cloud_username']    = $cloud_username;
         $valores['dom_cod']           = $dom_cod;

         $sql                          = "SELECT a.busua_cod,
                                                a.cloud_username,
                                                a.group_cod,
                                                a.email
                                          FROM BP.BP_USUARIO a,
                                             BP.BP_GRUPO b,
                                             BP.BP_DOMINIO c
                                          WHERE a.group_cod = b.group_cod
                                          AND b.dom_cod = c.dom_cod
                                          AND a.esta_cod IN (1, 2)
                                          AND c.dom_cod = :dom_cod
                                          AND a.cloud_username = :cloud_username";

         if ($DB->Query($sql, $valores)) 
         {
            $this->busua_cod           = $DB->Value("BUSUA_COD");
            $this->cloud_username      = $DB->Value("CLOUD_USERNAME");
            $this->group_cod           = $DB->Value("GROUP_COD");
            $this->email               = $DB->Value("EMAIL");
            $retval                    = true;
         }
         $DB->Close();
         return $retval;
      }

      // modifica datos de un usuario
      function actualiza($cloud_username, $user_phone, $email, $nombre, &$DB)
      {
         $retval                       = false;

         $valores['group_cod']         = $this->group_cod;
         $valores['busua_cod']         = $this->busua_cod;
         $valores['cloud_username']    = $cloud_username;
         $valores['user_phone']        = $user_phone;
         $valores['email']             = $email;
         $valores['nombre']            = $nombre;

         $sql                          = "UPDATE BP.BP_USUARIO a
                                          SET a.cloud_username = :cloud_username,
                                             a.user_phone = :user_phone,
                                             a.email = :email,
                                             a.nombre = :nombre
                                          WHERE a.group_cod = :group_cod
                                          AND a.busua_cod = :busua_cod";

         if ($DB->Execute($sql, $valores))
         {
            $this->cloud_username      = $cloud_username;
            $this->user_phone          = $user_phone;
            $this->email               = $email;
            $this->nombre              = $nombre;
            $retval                    = true;
         }
         return $retval;
      }

      // cambia a un usuario de grupo
      function actualizaGrupo($group_cod, &$DB)
      {
         $retval                       = false;

         $valores['busua_cod']         = $this->busua_cod;
         $valores['group_cod']         = $group_cod;

         $sql                          = "UPDATE BP.BP_USUARIO a
                                          SET a.group_cod = :group_cod
                                          WHERE a.busua_cod = :busua_cod";

         if($DB->Execute($sql, $valores))
         {
            $this->group_cod           = $group_cod;
            $retval                    = true;
         }
         return $retval;
      }

      // modifica el estado del usuario
      function actualizaEstadoUser($esta_cod, &$DB)
      {
         $retval                       = false;

         $valores['busua_cod']         = $this->busua_cod;
         $valores['esta_cod']          = $esta_cod;

         $sql                          = "UPDATE BP.BP_USUARIO a
                                          SET a.esta_cod = :esta_cod
                                          WHERE a.busua_cod = :busua_cod";
         
         if ($DB->Execute($sql, $valores))
         {
            $this->esta_cod            = $esta_cod;
            $retval                    = true;
         }
         return $retval;
      }

      // actualiza notifica a null y fecha de notificacion segun anexo_cod
      function LiberaNotifica($busua_cod, &$DB)
      {
         $retval                       = false;

         $valores['busua_cod']         = $busua_cod;

         $sql                          = "UPDATE BP.BP_USUARIO a
                                          SET a.notifica = 0,
                                             a.fecha_notificacion = NULL
                                          WHERE a.busua_cod = :busua_cod";

         if($DB->Execute($sql, $valores))
         {
            $this->busua_cod           = $busua_cod;
            $this->notifica            = 0;
            $this->fecha_notificacion  = null;
            $retval                    = true;
         }
         return $retval;
      }

      // elimina usuario
      function delete(&$DB)
      {
         $retval                       = false;

         $valores['busua_cod']         = $this->busua_cod;

         $sql                          = "UPDATE BP.BP_USUARIO a
                                          SET a.esta_cod = 3
                                          WHERE a.busua_cod = :busua_cod";
         
         if ($DB->Execute($sql, $valores))
         {
            $retval                    = true;
         }
         return $retval;
      }

      // actualiza notificacion
      function actualizaNotifica(&$DB)
      {
         $retval                       = false;

         $valores['busua_cod']         = $this->busua_cod;

         $sql                          = "UPDATE BP.BP_USUARIO a
                                          SET a.notifica = 1,
                                          a.fecha_notificacion = SYSDATE
                                          WHERE a.busua_cod = :busua_cod";
         
         if ($DB->Execute($sql, $valores))
         {
            $this->notifica            = 1;
            $this->fecha_notificacion  = date('m-d-Y H:i:s');
            $retval                    = true;
         }
         return $retval;
      }

      // actualiza notificacion
      function actualizaNotificaCSV($busua_cod, &$DB)
      {
         $retval                       = false;

         $valores['busua_cod']         = $busua_cod;

         $sql                          = "UPDATE BP.BP_USUARIO a
                                          SET a.notifica = 1,
                                          a.fecha_notificacion = SYSDATE
                                          WHERE a.busua_cod = :busua_cod";
         
         if ($DB->Execute($sql, $valores))
         {
            $this->busua_cod           = $busua_cod;
            $this->notifica            = 1;
            $this->fecha_notificacion  = date('m-d-Y H:i:s');
            $retval                    = true;
         }
         return $retval;
      }

      // actualiza cloud password
      function actualizaCloudPassword($cloud_password, &$DB)
      {
         $retval                    = false;

         $valores['busua_cod']      = $this->busua_cod;
         $valores['cloud_password'] = $cloud_password;

         $sql                       = "UPDATE BP.BP_USUARIO a
                                       SET a.cloud_password = :cloud_password
                                       WHERE a.busua_cod = :busua_cod";
         
         if ($DB->Execute($sql, $valores))
         {
            $this->cloud_password   = $cloud_password;
            $retval                 = true;
         }
         return $retval;
      }

      // busca cloud y correo a la ves
      function buscaCloudEmail($cloud_username, $email, &$DB)
      {
         $retval                    = false;

         $valores['cloud_username'] = $cloud_username;
         $valores['email']          = $email;

         $sql                       = "SELECT a.busua_cod,
                                             a.cloud_username,
                                             a.email,
                                             a.group_cod,
                                             a.esta_cod,
                                             a.nombre
                                       FROM BP.BP_USUARIO a
                                       WHERE a.cloud_username = :cloud_username
                                       AND a.email = :email
                                       AND a.esta_cod = 1";

         if ($DB->Query($sql, $valores))
         {
            $this->busua_cod        = $DB->Value("BUSUA_COD");
            $this->cloud_username   = $DB->Value("CLOUD_USERNAME");
            $this->email            = $DB->Value("EMAIL");
            $this->group_cod        = $DB->Value("GROUP_COD");
            $this->esta_cod         = $DB->Value("ESTA_COD");
            $this->nombre           = $DB->Value("NOMBRE");
            $retval                 = true;
         }
         $DB->Close();
         return $retval;
      }

      // actuliza email
      function actualizaEmail($busua_cod, $email, &$DB)
      {
         $retval                    = false;

         $valores['busua_cod']      = $busua_cod;
         $valores['email']          = $email;

         $sql                       = "UPDATE BP.BP_USUARIO a
                                       SET a.email = :email
                                       WHERE a.busua_cod = :busua_cod";
         
         if ($DB->Execute($sql, $valores))
         {
            $this->busua_cod        = $busua_cod;
            $this->email            = $email;
            $retval                 = true;
         }
         return $retval;
      }
   }
?>