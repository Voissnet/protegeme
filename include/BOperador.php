<?
   class BOperador
   {
      var $oper_cod;
      var $dom_cod;
      var $username;
      var $password;
      var $nombre;
      var $esta_cod;
      var $fecha_creacion;
      var $sesion;
      var $fecha_ultimo_login;
      var $ultima_ip;
      var $dominio_usuario;
      var $email;
      var $notifica;
      var $fecha_notificacion;
      var $sesion_check;

      /* comienza sesion del operador */
      function sec_session_start()
      {
         require_once ("MOD_Error.php");

         // Configura un nombre de sesión personalizado.
         $session_name = 'sec_session_op_id';   

         // Esto detiene que JavaScript sea capaz de acceder a la identificación de la sesión.
         $httponly = TRUE;

         // si se manda cookie solo en sitios https
         $secure = FALSE;

         // Obliga a las sesiones a solo utilizar cookies.
         if (ini_set('session.use_only_cookies', 1) === FALSE)
         {
            MOD_Error::Error("PBE_109");
            exit();
         }

         // Obtiene los params de los cookies actuales.
         $cookieParams = session_get_cookie_params();

         session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);

         // Configura el nombre de sesión al configurado arriba.
         session_name($session_name);
         session_start();            // Inicia la sesión PHP.
         session_regenerate_id();    // Regenera la sesión, borra la previa. 

         /* impide cachear la página */
         header("Expires: Tue, 01 Jul 2001 06:00:00 GMT");
         header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
         header("Cache-Control: no-store, no-cache, must-revalidate");
         header("Cache-Control: post-check=0, pre-check=0", false);
         header("Pragma: no-cache");
      }

      /* comienza sesion del usuario */
      function sec_session_start_ajax()
      {
         $retval = true;

         // Configura un nombre de sesión personalizado.
         $session_name = 'sec_session_op_id';   

         // Esto detiene que JavaScript sea capaz de acceder a la identificación de la sesión.
         $httponly = TRUE;

         // si se manda cookie solo en sitios https
         $secure = false;

         // Obliga a las sesiones a solo utilizar cookies.
         if (ini_set('session.use_only_cookies', 1) === false)
         {
            $retval = false;
         }

         // Obtiene los params de los cookies actuales.
         $cookieParams = session_get_cookie_params();

         session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);

         // Configura el nombre de sesión al configurado arriba.
         session_name($session_name);
         session_start();            // Inicia la sesión PHP.
         session_regenerate_id();    // Regenera la sesión, borra la previa. 

         /* impide cachear la página */
         header("Expires: Tue, 01 Jul 2001 06:00:00 GMT");
         header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
         header("Cache-Control: no-store, no-cache, must-revalidate");
         header("Cache-Control: post-check=0, pre-check=0", false);
         header("Pragma: no-cache");
         return $retval;
      }

      /* logea al operador en el sitio por primera vez o lo manda a página de error  */
      function Login($dominio_usuario, $username, $password, &$DB)
      {
         $retval = FALSE;
         $valores1['username']         = $username;
         $valores1['dominio_usuario']  = $dominio_usuario;

         /* ve si está el usuario */
         $DB->BeginTrans();
         if($DB->Query("SELECT a.oper_cod,
                               a.password --encrypt
                        FROM BP.BP_OPERADOR a,
                             BP.BP_DOMINIO b
                        WHERE a.username = :username
                              AND a.esta_cod = 1
                              AND b.dominio_usuario = :dominio_usuario
                              AND a.dom_cod = b.dom_cod" , $valores1))
         {
            $this->oper_cod = $DB->Value("OPER_COD");
            $this->password = $DB->Value("PASSWORD");

            require_once 'Parameters.php';
            $p_peppered = hash_hmac("sha256", $password, Parameters::PEPPER);
            if (password_verify($p_peppered, $this->password) === FALSE)
            {
               $DB->Logoff();
               return FALSE;
            }

            /* crea nueva sesion */
            if(($this->sesion = $DB->GetSequence("BP.SEQ_BP_OPERSESION")) !== FALSE)
            {
               $_SESSION['loggedin']      = true;
               $_SESSION['oper_cod']      = preg_replace("/[^0-9]+/", "", $this->oper_cod);
               $_SESSION['start']         = time();
               $_SESSION['expire']        = $_SESSION['start'] + (24 * 60 * 60);
               $_SESSION['sesion']        = $this->sesion;
               $_SESSION['sesion_token']  = hash('sha512', $this->oper_cod . "-" . $this->password . "-" . $this->sesion . "-" . $_SERVER['HTTP_USER_AGENT']);

               $valores2['sesion']        = $this->sesion;
               $valores2['ultima_ip']     = $_SERVER["REMOTE_ADDR"];
               $valores2['oper_cod']      = $this->oper_cod;

               /* guarda sesion y ultimo login */
               if($DB->Execute("UPDATE BP.BP_OPERADOR
                                SET sesion = :sesion,
                                    fecha_ultimo_login = SYSDATE,
                                    ultima_ip = :ultima_ip
                                WHERE oper_cod = :oper_cod", $valores2))
               {
                  $this->ultima_ip           = $_SERVER["REMOTE_ADDR"];
                  $valores3['oper_cod']      = intval($this->oper_cod);
                  $valores3['ip']            = strval($_SERVER["REMOTE_ADDR"]);

                  /* si se loguea correctamente guarda la historia del login y reinicia los reintentos */
                  if($DB->Execute("INSERT INTO BP.BP_HISTORIA_LOGIN_OPERADOR (oper_cod, fecha_login, ip)
                                   VALUES (:oper_cod, SYSDATE, :ip)", $valores3) === TRUE)
                  {
                     $retval = TRUE;
                     $DB->Commit();
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
         $DB->Rollback();
         return $retval;
      }

      // busca nombre de usuario de un operador
      function BuscaUsername($username, $dom_cod, &$DB)
      {
         $retval                       = false;

         $valores['username']          = $username;
         $valores['dom_cod']           = $dom_cod;

         $sql                          = "SELECT a.oper_cod,
                                                a.username,
                                                a.email
                                          FROM BP.BP_OPERADOR a,
                                             BP.BP_DOMINIO b
                                          WHERE a.dom_cod = b.dom_cod
                                          AND a.dom_cod = :dom_cod
                                          AND a.esta_cod = 1
                                          AND a.username = :username";

         if($DB->Query($sql, $valores))
         {
            $this->oper_cod            = $DB->Value("OPER_COD");
            $this->username            = $DB->Value("USERNAME");
            $this->email               = $DB->Value("EMAIL");
            $retval                    = true;
         }
         $DB->Close();
         return $retval;
      }

      /* Verifica que la página donde está el usuario sea segura Se debe usar para todas las páginas protegidas del sitio */
      function VerificaLogin(&$DB)
      {
         $retval = FALSE;

         require_once 'MOD_Error.php';
         if (isset($_SESSION["oper_cod"]) === TRUE)
            $this->oper_cod   = $_SESSION["oper_cod"];
         else
            $this->oper_cod = '';

         if ( isset($_SESSION['loggedin'], $_SESSION['oper_cod'], $_SESSION['sesion_token'] ) && $_SESSION['loggedin'] == TRUE )
         {
            if($this->oper_cod !== '')
            {
               $valores['oper_cod'] = $this->oper_cod;
               if($DB->Query("SELECT a.oper_cod,
                                    a.dom_cod,
                                    a.username,
                                    a.password,
                                    a.nombre,
                                    a.esta_cod,
                                    TO_CHAR(a.fecha_creacion, 'DD-MM-YYYY HH24:MI:SS') fecha_creacion,
                                    a.sesion,
                                    TO_CHAR(a.fecha_ultimo_login, 'DD-MM-YYYY HH24:MI:SS') fecha_ultimo_login,
                                    a.ultima_ip,
                                    a.email,
                                    a.notifica,
                                    a.fecha_notificacion
                              FROM BP.BP_OPERADOR a
                              WHERE a.oper_cod = :oper_cod
                                    AND a.esta_cod = 1", $valores))
               {
                  $this->oper_cod                  =  $DB->Value("OPER_COD");
                  $this->dom_cod                   =  $DB->Value("DOM_COD");
                  $this->username                  =  $DB->Value("USERNAME");
                  $this->password                  =  $DB->Value("PASSWORD");
                  $this->nombre                    =  $DB->Value("NOMBRE");
                  $this->esta_cod                  =  $DB->Value("ESTA_COD");
                  $this->fecha_creacion            =  $DB->Value("FECHA_CREACION");
                  $this->sesion                    =  $DB->Value("SESION");
                  $this->fecha_ultimo_login        =  $DB->Value("FECHA_ULTIMO_LOGIN");
                  $this->ultima_ip                 =  $DB->Value("ULTIMA_IP");
                  $this->email                     =  $DB->Value("EMAIL");
                  $this->notifica                  =  $DB->Value("NOTIFICA");
                  $this->fecha_notificacion        =  $DB->Value("FECHA_NOTIFICACION");

                  /* ver que la sesion del php del usuario sea igual a la guardada en la BD */
                  if (  hash('sha512', $this->oper_cod . "-" . $this->password . "-" . $this->sesion . "-" . $_SERVER['HTTP_USER_AGENT']) == $_SESSION['sesion_token'])
                  {
                     /* ver que además el tiempo de conexión sea en la ventana */
                     if(time() < $_SESSION['expire'])
                        $retval = TRUE;
                     else
                        session_destroy();
                  }
               }
            }
            else
            {
               MOD_Error::Error("PBE_110");
               exit;
            }
         }
         return $retval;
      }

      // Modifica contraseña desde sitio personal
      function ModificaPassword($password, &$DB)
      {
         $retval              = false;

         $p_peppered          = hash_hmac('sha256', $password, Parameters::PEPPER);
         $encrypted_password  = password_hash($p_peppered, PASSWORD_BCRYPT);

         $valores['oper_cod'] = $this->oper_cod;
         $valores['password'] = $encrypted_password;

         $sql                 = "UPDATE BP.BP_OPERADOR a
                                 SET a.password = :password
                                 WHERE a.oper_cod = :oper_cod";

         if($DB->Execute($sql, $valores))
         {
            //$_SESSION['sesion_token']  = md5($this->oper_cod . "-" . $password . "-" . $this->sesion);
            $_SESSION['sesion_token']  = hash('sha512', $this->oper_cod . "-" . $encrypted_password . "-" . $this->sesion . "-" . $_SERVER['HTTP_USER_AGENT']);
            $this->password            = $encrypted_password;
            $this->sesion_check        = $_SESSION['sesion_token'];
            $retval = TRUE;
         }

         return $retval;
      }

      /* Cierra la sesion del operador */
      function Logout()
      {
         $_SESSION = array();
         $params = session_get_cookie_params();
         setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
         session_destroy();
         header('Location: ' . Parameters::WEB_PATH . '/operator/login/index.php');
      }

      // trae primer registro
      function buscaOperadores($dom_cod, &$DB)
      {
         $retval                       = false;

         $valores['dom_cod']           = $dom_cod;

         $sql                          = "SELECT a.oper_cod,
                                                a.dom_cod,
                                                a.username,
                                                a.nombre,
                                                a.esta_cod,
                                                TO_CHAR(a.fecha_creacion, 'DD-MM-YYYY HH24:MI:SS') fecha_creacion,
                                                a.sesion,
                                                TO_CHAR(a.fecha_ultimo_login, 'DD-MM-YYYY HH24:MI:SS') fecha_ultimo_login,
                                                a.ultima_ip,
                                                b.dominio_usuario,
                                                a.email,
                                                a.notifica,
                                                TO_CHAR(a.fecha_notificacion, 'DD-MM-YYYY HH24:MI:SS') fecha_notificacion
                                          FROM BP.BP_OPERADOR a,
                                          BP.BP_DOMINIO b
                                          WHERE a.dom_cod = b.dom_cod
                                          AND a.dom_cod = :dom_cod";

         if($DB->Query($sql, $valores))
         {
            $this->oper_cod            = $DB->Value("OPER_COD");
            $this->dom_cod             = $DB->Value("DOM_COD");
            $this->username            = $DB->Value("USERNAME");
            $this->nombre              = $DB->Value("NOMBRE");
            $this->esta_cod            = $DB->Value("ESTA_COD");
            $this->fecha_creacion      = $DB->Value("FECHA_CREACION");
            $this->sesion              = $DB->Value("SESION");
            $this->fecha_ultimo_login  = $DB->Value("FECHA_ULTIMO_LOGIN");
            $this->ultima_ip           = $DB->Value("ULTIMA_IP");
            $this->dominio_usuario     = $DB->Value("DOMINIO_USUARIO");
            $this->email               = $DB->Value("EMAIL");
            $this->notifica            = $DB->Value("NOTIFICA");
            $this->fecha_notificacion  = $DB->Value("FECHA_NOTIFICACION");
            $retval                    = true;
         } else {
            $DB->Close();
         }
         return $retval;
      }

      // trae siguiente registro
      function siguientesOperadores(&$DB)
      {
         $retval                       = false;

         if($DB->Next())
         {
            $this->oper_cod            = $DB->Value("OPER_COD");
            $this->dom_cod             = $DB->Value("DOM_COD");
            $this->username            = $DB->Value("USERNAME");
            $this->nombre              = $DB->Value("NOMBRE");
            $this->esta_cod            = $DB->Value("ESTA_COD");
            $this->fecha_creacion      = $DB->Value("FECHA_CREACION");
            $this->sesion              = $DB->Value("SESION");
            $this->fecha_ultimo_login  = $DB->Value("FECHA_ULTIMO_LOGIN");
            $this->ultima_ip           = $DB->Value("ULTIMA_IP");
            $this->dominio_usuario     = $DB->Value("DOMINIO_USUARIO");
            $this->email               = $DB->Value("EMAIL");
            $this->notifica            = $DB->Value("NOTIFICA");
            $this->fecha_notificacion  = $DB->Value("FECHA_NOTIFICACION");
            $retval                    = true;
         } else {
            $DB->Close();
         }
         return $retval;
      }

      // busca un operador
      function busca($oper_cod, &$DB)
      {
         $retval                       = false;

         $valores['oper_cod']          = $oper_cod;

         $sql                          = "SELECT a.oper_cod,
                                                a.dom_cod,
                                                a.username,
                                                a.nombre,
                                                a.esta_cod,
                                                TO_CHAR(a.fecha_creacion, 'DD-MM-YYYY HH24:MI:SS') fecha_creacion,
                                                a.sesion,
                                                TO_CHAR(a.fecha_ultimo_login, 'DD-MM-YYYY HH24:MI:SS') fecha_ultimo_login,
                                                a.ultima_ip,
                                                b.dominio_usuario,
                                                a.email,
                                                a.notifica,
                                                TO_CHAR(a.fecha_notificacion, 'DD-MM-YYYY HH24:MI:SS') fecha_notificacion
                                          FROM BP.BP_OPERADOR a,
                                          BP.BP_DOMINIO b
                                          WHERE a.dom_cod = b.dom_cod
                                          AND a.oper_cod = :oper_cod";
         
         if($DB->Query($sql, $valores))
         {
            $this->oper_cod            = $DB->Value("OPER_COD");
            $this->dom_cod             = $DB->Value("DOM_COD");
            $this->username            = $DB->Value("USERNAME");
            $this->nombre              = $DB->Value("NOMBRE");
            $this->esta_cod            = $DB->Value("ESTA_COD");
            $this->fecha_creacion      = $DB->Value("FECHA_CREACION");
            $this->sesion              = $DB->Value("SESION");
            $this->fecha_ultimo_login  = $DB->Value("FECHA_ULTIMO_LOGIN");
            $this->ultima_ip           = $DB->Value("ULTIMA_IP");
            $this->dominio_usuario     = $DB->Value("DOMINIO_USUARIO");
            $this->email               = $DB->Value("EMAIL");
            $this->notifica            = $DB->Value("NOTIFICA");
            $this->fecha_notificacion  = $DB->Value("FECHA_NOTIFICACION");
            $retval                    = true;
         }
         $DB->Close();
         return $retval;
      }

      // actualiza estado de un operador
      function actualizaEstado($esta_cod, &$DB)
      {
         $retval                       = false;

         $valores['oper_cod']          = $this->oper_cod;
         $valores['esta_cod']          = $esta_cod;

         $sql                          = "UPDATE BP.BP_OPERADOR a
                                          SET a.esta_cod = :esta_cod
                                          WHERE a.oper_cod = :oper_cod";

         if($DB->Execute($sql, $valores))
         {
            $retval                    = true;
         }
         return $retval;
      }

      // actualiza estado de un operador
      function actualizaEmail($email, &$DB)
      {
         $retval                       = false;

         $valores['oper_cod']          = $this->oper_cod;
         $valores['email']             = $email;

         $sql                          = "UPDATE BP.BP_OPERADOR a
                                          SET a.email = UPPER(:email)
                                          WHERE a.oper_cod = :oper_cod";

         if($DB->Execute($sql, $valores))
         {
            $this->email               = $email;
            $retval                    = true;
         }
         return $retval;
      }

      // actualiza estado de un operador
      function actualizaDatos($username, $nombre, $email, &$DB)
      {
         $retval                       = false;

         $valores['oper_cod']          = $this->oper_cod;
         $valores['username']          = $username;
         $valores['nombre']            = $nombre;
         $valores['email']             = $email;

         $sql                          = "UPDATE BP.BP_OPERADOR a
                                          SET a.username = :username,
                                          a.nombre = :nombre,
                                          a.email = UPPER(:email)
                                          WHERE a.oper_cod = :oper_cod";

         if($DB->Execute($sql, $valores))
         {
            $retval                    = true;
         }
         return $retval;
      }

      // actualiza notificacion de operador
      function actualizaNotifica(&$DB)
      {
         $retval                       = false;

         $valores['oper_cod']          = $this->oper_cod;

         $sql                          = "UPDATE BP.BP_OPERADOR a
                                          SET a.notifica = 1,
                                          a.fecha_notificacion = SYSDATE
                                          WHERE a.oper_cod = :oper_cod";
         
         if ($DB->Execute($sql, $valores))
         {
            $this->notifica            = 1;
            $this->fecha_notificacion  = date('m-d-Y H:i:s');
            $retval                    = true;
         }
         return $retval;
      }

      // limpia notificacion operador
      function liberaNotificado(&$DB)
      {
         $retval                       = false;

         $valores['oper_cod']          = $this->oper_cod;

         $sql                          = "UPDATE BP.BP_OPERADOR a
                                          SET a.fecha_notificacion = NULL,
                                          a.notifica = 0
                                          WHERE a.oper_cod = :oper_cod";

         if($DB->Execute($sql, $valores))
         {
            $this->notifica            = 0;
            $this->fecha_notificacion  = null;
            $retval                    = true;
         }
         return $retval;
      }

      // actualiza password
      function actualizaPassword($password, &$DB)
      {
         $retval                    = false;

         $valores['oper_cod']       = $this->oper_cod;
         $valores['password']       = $password;

         $sql                       = "UPDATE BP.BP_OPERADOR a
                                       SET a.password = :password
                                       WHERE a.oper_cod = :oper_cod";
         
         if ($DB->Execute($sql, $valores))
         {
            $this->password         = $password;
            $retval                 = true;
         }
         return $retval;
      }

      // verifica username de un dominio
      function verificaUsername($dom_cod, $username, &$DB)
      {
         $retval                    = false;

         $valores['dom_cod']        = $dom_cod;
         $valores['username']       = $username;

         $sql                       = "SELECT a.username,
                                             a.dom_cod,
                                             b.dominio_usuario
                                       FROM BP.BP_OPERADOR a,
                                       BP.BP_DOMINIO b
                                       WHERE a.dom_cod = b.dom_cod
                                       AND a.dom_cod = :dom_cod
                                       AND a.username = :username
                                       AND a.esta_cod IN (1, 2)";

         if($DB->Query($sql, $valores))
         {
            $this->username         = $DB->Value("USERNAME");
            $this->dom_cod          = $DB->Value("DOM_COD");
            $this->dominio_usuario  = $DB->Value("DOMINIO_USUARIO");
            $retval                 = true;
         }
         $DB->Close();
         return $retval;
      }

      // ingresa in operador
      function insert($dom_cod, $username, $password, $nombre, $email, &$DB)
      {
         $retval                    = false;

         $valores['dom_cod']        = $dom_cod;
         $valores['username']       = $username;
         $valores['password']       = $password;
         $valores['nombre']         = $nombre;
         $valores['email']          = $email;

         $sql                       = "INSERT INTO BP.BP_OPERADOR (dom_cod, username, password, esta_cod, nombre, email)
                                       VALUES (:dom_cod, :username, :password, 1, :nombre, UPPER(:email))
                                       RETURNING oper_cod";

         if(($this->oper_cod = $DB->ExecuteReturning($sql, $valores)) !== false)
         {
            $this->username         = $username;
            $this->nombre           = $nombre;
            $this->email            = $email;
            $this->fecha_creacion   = date('m-d-Y H:i:s');
            $retval                 = true;
         }
         return $retval;
      }

      // actualiza datos de un operador
      function actualiza($nombre, &$DB)
      {
         $retval                    = false;

         $valores['oper_cod']       = $this->oper_cod;
         $valores['nombre']         = $nombre;

         $sql                       = "UPDATE BP.BP_OPERADOR a
                                       SET a.nombre = :nombre
                                       WHERE a.oper_cod = :oper_cod";

         if($DB->Execute($sql, $valores))
         {
            $this->nombre           = $nombre;
            $retval                 = true;
         }
         return $retval;
      }

   }
?>