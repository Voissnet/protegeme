<?
   class BUsuarioRV
   {
      var $usua_cod;
      var $pais_cod;
      var $username;
      var $password;
      var $nombre;
      var $apellidos;
      var $email;
      var $fecha_inscripcion;
      var $fecha_validacion;
      var $fecha_ultimo_login;
      var $esta_cod;
      var $saldo;
      var $sesion;
      var $sesion_check;
      var $enviar_mail;
      var $referenciadopor;
      var $compra_online;
      var $ultimo_ip;
      var $list_cod;
      var $notifica_saldo;
      var $es_postpago;
      var $es_mayorista;
      var $pin_numerico;
      var $perf_cod;
      var $tipo_cliente;
      var $empresa;
      var $reseller;
      var $fecha_asignacion_reseller;
      var $factura_electronica;
      var $categorizacion;
      var $hizo_abono;
      var $traspaso_online;
      var $vend_cod;
      var $moneda;
      var $sms_list_cod;
      var $gate_cod_sippy;
      var $limite_credito;
      var $solo_destinos_seguros;
      var $umbral_notificacion;
      var $iva_retenido;
      var $porcentaje_descuento;
      var $sms_perf_cod;
      var $monto_asegurable;

      /* solo para registro */
      var $rut;
      var $cargo;
      var $telefono_celular;
      var $telefono_fijo;
      var $rut_empresa;
      var $razon_social;
      var $rub_cod;
      var $med_cod;
      /* solo para registro */
      var $es_contact_center;
      var $es_internacional;
      /* envio sms */
      var $envio_sms;

      function CompruebaUsername($username, &$DB)
      {
         $retval              = false;
         $valores["username"] = $username;
         $sql                 = "SELECT a.usua_cod,
                                       a.username,
                                       a.esta_cod,
                                       a.tipo_cliente
                                 FROM FG.FC_USUARIO a
                                 WHERE a.username = :username" ;
         if ($DB->Query($sql, $valores) === TRUE)
         {
            $this->usua_cod = $DB->Value("USUA_COD");
            $this->username = $DB->Value("USERNAME");
            $this->esta_cod = $DB->Value("ESTA_COD");
            $this->tipo_cliente = $DB->Value("TIPO_CLIENTE");
            $retval = TRUE;
         }
         return $retval;
      }

      function Inserta($username, $password, $nombre, $apellidos, $empresa, $email, $pais_cod, $enviar_email = "0", $razon_social, $rut_empresa, $rut, $cargo, $telefono_celular, $telefono_fijo, $rub_cod, $med_cod, &$DB)
      {
         $retval = false;

         $valores1["username"] = $username;

         $sql1 = "SELECT username
                  FROM FG.FC_USUARIO
                  WHERE username = :username";

         /* Si no encuentra usuario entonces lo puede agregar*/
         if($DB->Query($sql1, $valores1) === false)
         {
            $DB->Close();
            if( ( $this->sesion = $DB->GetSequence("FG.SEQ_FC_SESION")) !== false )
            {
               /* NUEVO ENCRYPT*/
               require_once 'Parameters.php';
               $p_peppered                   = hash_hmac('sha256', $password, Parameters::PEPPER);
               $password                     = password_hash($p_peppered, PASSWORD_BCRYPT);
               /* FIN ENCRYPT */

               $valores2["username"]         = $username;
               $valores2["password"]         = $password;
               $valores2["nombre"]           = $nombre;
               $valores2["apellidos"]        = $apellidos;
               $valores2["email"]            = $email;
               $valores2["pais_cod"]         = $pais_cod;
               $valores2["enviar_email"]     = $enviar_email;
               $valores2["sesion"]           = $this->sesion;
               $valores2["empresa"]          = $empresa;
               $valores2["razon_social"]     = $razon_social;
               $valores2["rut_empresa"]      = $rut_empresa;
               $valores2["rut"]              = $rut;
               $valores2["cargo"]            = $cargo;
               $valores2["telefono_celular"] = $telefono_celular;
               $valores2["telefono_fijo"]    = $telefono_fijo;
               $valores2["rub_cod"]          = $rub_cod;
               $valores2["med_cod"]          = $med_cod;

               $sql2 = "INSERT INTO FG.FC_USUARIO (username, password, nombre, apellidos, email, pais_cod, fecha_inscripcion, esta_cod, saldo, enviar_mail, sesion, pin_numerico, tipo_cliente, empresa, razon_social, rut_empresa, rut, cargo, telefono_celular, telefono_fijo, rub_cod, med_cod, sms_perf_cod, envio_sms, list_cod, sms_list_cod)
                        VALUES (:username, :password, UPPER(:nombre), UPPER(:apellidos), UPPER(:email), :pais_cod, SYSDATE, 8, 0, :enviar_email, :sesion, FG.SEQ_FC_PIN_NUMERICO.NEXTVAL || TRUNC(DBMS_RANDOM.VALUE(100000,999999)), 10, UPPER(:empresa), UPPER(:razon_social), UPPER(:rut_empresa), UPPER(:rut), UPPER(:cargo), :telefono_celular, :telefono_fijo, :rub_cod, :med_cod, 6, 1, 312, 67)
                        RETURNING usua_cod";

               if( ( $this->usua_cod = $DB->ExecuteReturning($sql2, $valores2)) !== false)
               {
                  $this->sesion_check = hash('sha512', $this->usua_cod . "-" . $password . "-" . $this->sesion . "-" . $_SERVER['HTTP_USER_AGENT']);
                  $valores3["usua_cod"] = $this->usua_cod;
                  $sql3 = "INSERT INTO FG.FC_HISTORIA_ESTADO_USUARIO(usua_cod, esta_cod, fecha_cambio_estado)
                           VALUES (:usua_cod, 8, SYSDATE)";
                  if ($DB->Execute($sql3, $valores3))
                     $retval = TRUE;
                  else
                     $DB->Close();
               }
            }
         }
         else
            $DB->Close();
         return $retval;
      }

      function ObtieneNumeroSMS($usua_cod, &$DB)
      {
         $retval = false;
         /* Inserta gate_cod */

         $valores1["usua_cod"] = $usua_cod;
         $sql1 = "INSERT INTO FG.FC_GATEWAY (usua_cod, puertas, info, fecha_vencimiento, user_esta_cod, admin_esta_cod, fecha_creacion, tiga_cod)
                  VALUES (:usua_cod, 1, 'NUMERACION FIJA SMS', TO_DATE('31-12-2100','DD-MM-YYYY'), 1, 1, SYSDATE, 101)
                  RETURNING gate_cod";

         $gate_cod = $DB->ExecuteReturning($sql1, $valores1);
         if ($gate_cod !== false)
         {
            /* Secuencia para numero interno */
            $seq = $DB->GetSequence("FG.SEQ_FC_NUMERO_INTERNO");
            if ( $seq !== false)
            {
               $numero                 = "666" . $seq;
               $valores2["numero"]     = $numero;
               $valores2["gate_cod"]   = $gate_cod;

               $sql2 = "INSERT INTO FG.FC_GATEWAY_NUMERO (numero, gate_cod, puertas, descripcion, privado, user_esta_cod, admin_esta_cod, prefijo1, prefijo2)
                        VALUES (:numero, :gate_cod, 1, 'NUMERACION FIJA SMS', 1, 1, 1, '56', '44')";
               
               /* inserta numero interno*/
               if ($DB->Execute($sql2, $valores2) !== false)
               {
                  /* Busca numero 5644 disponible */
                  $sql3 = "SELECT a.numero_real
                           FROM FG.GLBL_NUMERO_REAL a
                           WHERE conj_cod = 220
                                 AND numero IS NULL
                                 AND ROWNUM < 2";

                  if ($DB->Query($sql3) !== false)
                  {
                     /* Actualiza numero real 5644 disbonible */
                     $numero_real = $DB->Value("NUMERO_REAL");
                     $valores4["numero_real"]   = $numero_real;
                     $valores4["numero"]        = $numero;

                     $sql4 = "UPDATE FG.GLBL_NUMERO_REAL
                              SET fecha_libre = SYSDATE,
                                  numero = :numero,
                                  adicional = 0
                              WHERE numero_real = :numero_real";
                  
                     if ($DB->Execute($sql4, $valores4) !== false)
                     {
                        /* actualiza redvoiss.rv_numeracion_rv para que estado sea con numero para sms */
                        $valores5["numero"] = $numero_real;

                        $sql5 = "UPDATE REDVOISS.RV_NUMERACION_RV
                                 SET clie_cod = 1,
                                     contrato = 1,
                                     esta_cod = 4
                                 WHERE numero = :numero";
                        if ($DB->Execute($sql5, $valores5) !== false)
                        {
                           /* ingresa fixed_number*/
                           $valores6["usua_cod"]   = $usua_cod;
                           $valores6["numero"]     = $numero_real;

                           $sql6 = "INSERT INTO FG.FS_SMS_FIXED_NUMBER (usua_cod, numero)
                                    VALUES (:usua_cod, :numero)";

                           if ($DB->Execute($sql6, $valores6) !== false)
                              $retval = TRUE;
                        }
                     }
                  }
               }
            }
         }
         return $retval;
      }



      /* comienza sesion del usuario */
      function sec_session_start()
      {
         require_once ("MOD_Error.php");

         // Configura un nombre de sesión personalizado.
         $session_name = 'sec_session_id';   

         // Esto detiene que JavaScript sea capaz de acceder a la identificación de la sesión.
         $httponly = TRUE;

         // si se manda cookie solo en sitios https
         $secure = false;

         // Obliga a las sesiones a solo utilizar cookies.
         if (ini_set('session.use_only_cookies', 1) === false)
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
         require_once ("MOD_Error.php");
         $retval = true;

         // Configura un nombre de sesión personalizado.
         $session_name = 'sec_session_id';   

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

      /* Modifica contraseña desde sitio personal*/
      function ModificaPassword($password, &$DB)
      {
         $retval = false;

         $p_peppered          = hash_hmac('sha256', $password, Parameters::PEPPER);
         $encrypted_password  = password_hash($p_peppered, PASSWORD_BCRYPT);

         $valores["usua_cod"] = $this->usua_cod;
         $valores["password"] = $encrypted_password;

         $sql = "UPDATE FG.FC_USUARIO a
                 SET a.password = :password
                 WHERE a.usua_cod = :usua_cod";

         if($DB->Execute($sql, $valores))
         {
            //$_SESSION['sesion_token']  = md5($this->usua_cod . "-" . $password . "-" . $this->sesion);
            $_SESSION['sesion_token']  = hash('sha512', $this->usua_cod . "-" . $encrypted_password . "-" . $this->sesion . "-" . $_SERVER['HTTP_USER_AGENT']);
            $this->password            = $encrypted_password;
            $this->sesion_check        = $_SESSION['sesion_token'];
            $retval = TRUE;
         }

         return $retval;
      }


      /* Hace la activación del usuario cuando se inscribe en el sitio */
      function ActivaUsuario($username, $password, $sesion_check, &$DB)
      {
         $retval = false;

         $sesion_check = strtolower($sesion_check);

         $valores["username"] = $username;

         $sql = "SELECT a.usua_cod,
                        a.sesion,
                        a.password
                 FROM FG.FC_USUARIO a
                 WHERE a.username = :username
                       AND a.esta_cod = 8
                       AND a.tipo_cliente = 10";

         if($DB->Query($sql, $valores) === TRUE)
         {
            $this->usua_cod   = $DB->Value("USUA_COD");
            $this->sesion     = $DB->Value("SESION");
            $this->password   = $DB->Value("PASSWORD");

            require_once 'Parameters.php';
            $p_peppered = hash_hmac("sha256", $password, Parameters::PEPPER);

            if (password_verify($p_peppered, $this->password) === false)
            {
               $DB->Close();
               $DB->Logoff();
               echo "no puede recuperarse password";
               exit;
            }
            /* FIN NUEVO ENCRYPT */ 
            $DB->Close();

            if (hash('sha512', $this->usua_cod . "-" . $this->password . "-" . $this->sesion . "-" . $_SERVER['HTTP_USER_AGENT']) == $sesion_check)
            {
               $_SESSION['loggedin']      = true;
               $_SESSION['usua_cod']      = $this->usua_cod;
               $_SESSION['start']         = time();
               $_SESSION['expire']        = $_SESSION['start'] + (24 * 60 * 60);
               $_SESSION['sesion']        = $this->sesion;
               $_SESSION['sesion_token']  = hash('sha512', $this->usua_cod . "-" . $this->password . "-" . $this->sesion . "-" . $_SERVER['HTTP_USER_AGENT']);

               $DB->BeginTrans();
               if ($this->CambiaEstado(1, $DB))
               {
                  $valores2["ultimo_ip"] = $_SERVER["REMOTE_ADDR"];
                  $valores2["usua_cod"]  = $this->usua_cod;

                  $sql = "UPDATE FG.FC_USUARIO
                          SET fecha_validacion = SYSDATE,
                              fecha_ultimo_login = SYSDATE,
                              ultimo_ip = :ultimo_ip
                          WHERE usua_cod = :usua_cod";

                  if($DB->Execute($sql, $valores2))
                  {
                     $sql = "INSERT INTO FG.FC_HISTORIA_LOGIN (usua_cod, fecha_login, ip)
                             VALUES (:usua_cod, SYSDATE, :ultimo_ip)";

                     if ($DB->Execute($sql, $valores2) === TRUE)
                     {
                        $DB->Commit();
                        $retval = TRUE;
                     }
                     else
                     {
                        unset ($SESSION['username']);
                        session_destroy();
                        $DB->Rollback();
                     }
                  }
                  else
                  {
                     unset ($SESSION['username']);
                     session_destroy();
                     $DB->Rollback();
                  }
               }
               else
               {
                  unset ($SESSION['username']);
                  session_destroy();
                  $DB->Rollback();
               }
            }
         }
         else
            $DB->Close();

         return $retval;
      }



      /* Cambia el estado de un usuario al numero dado */
      function CambiaEstado($esta_cod, &$DB)
      {
         $retval = false;

         $valores["esta_cod"] = $esta_cod;
         $valores["usua_cod"] = $this->usua_cod;

         $sql = "UPDATE FG.FC_USUARIO
                 SET esta_cod = :esta_cod
                 WHERE usua_cod = :usua_cod";

         if($DB->Execute($sql, $valores) === TRUE)
         {
            $this->esta_cod = 1;
            $sql = "INSERT INTO FG.FC_HISTORIA_ESTADO_USUARIO(usua_cod, esta_cod, fecha_cambio_estado)
                    VALUES (:usua_cod, :esta_cod, SYSDATE)";
            if ($DB->Execute($sql, $valores) === TRUE)
               $retval = TRUE;
         }
         return $retval;
      }


      /* Verifica que la página donde está el usuario sea segura Se debe usar para todas las páginas protegidas del sitio */
      function VerificaLogin(&$DB)
      {
         $retval = false;

         require_once 'MOD_Error.php';
         if (isset($_SESSION["usua_cod"]) === TRUE)
            $this->usua_cod   = $_SESSION["usua_cod"];
         else
            $this->usua_cod = '';

         if ( isset($_SESSION['loggedin'], $_SESSION['usua_cod'], $_SESSION['sesion_token'] ) && $_SESSION['loggedin'] == TRUE )
         {
            if($this->usua_cod !== '')
            {
               $valores['usua_cod'] = $this->usua_cod;
               if($DB->Query("SELECT a.usua_cod,
                                     a.pais_cod,
                                     a.username,
                                     a.password,
                                     a.nombre,
                                     a.apellidos,
                                     a.email,
                                     TO_CHAR(a.fecha_inscripcion, 'DD-MM-YYYY HH24:MI:SS') fecha_inscripcion,
                                     TO_CHAR(a.fecha_validacion, 'DD-MM-YYYY HH24:MI:SS') fecha_validacion,
                                     TO_CHAR(a.fecha_ultimo_login, 'DD-MM-YYYY HH24:MI:SS') fecha_ultimo_login,
                                     a.esta_cod,
                                     a.saldo,
                                     a.sesion,
                                     a.enviar_mail,
                                     a.referenciadopor,
                                     a.compra_online,
                                     a.list_cod,
                                     a.notifica_saldo,
                                     a.ultimo_ip,
                                     a.es_postpago,
                                     a.es_mayorista,
                                     a.pin_numerico,
                                     a.perf_cod,
                                     a.tipo_cliente,
                                     a.empresa,
                                     a.reseller,
                                     TO_CHAR(a.fecha_asignacion_reseller,'DD-MM-YYYY HH24:MI:SS') fecha_asignacion_reseller,
                                     a.factura_electronica,
                                     a.categorizacion,
                                     a.hizo_abono,
                                     a.traspaso_online,
                                     a.vend_cod,
                                     a.moneda,
                                     a.sms_list_cod,
                                     a.gate_cod_sippy,
                                     a.limite_credito,
                                     a.solo_destinos_seguros,
                                     a.umbral_notificacion,
                                     a.iva_retenido,
                                     a.porcentaje_descuento,
                                     a.sms_perf_cod,
                                     a.monto_asegurable,
                                     a.rut,
                                     a.cargo,
                                     a.telefono_celular,
                                     a.telefono_fijo,
                                     a.rut_empresa,
                                     a.razon_social,
                                     a.rub_cod,
                                     a.med_cod,
                                     a.es_contact_center,
                                     a.es_internacional,
                                     a.envio_sms
                              FROM FG.FC_USUARIO a
                              WHERE a.usua_cod = :usua_cod
                                    AND a.esta_cod = 1", $valores))
               {
                  $this->usua_cod                  =  $DB->Value("USUA_COD");
                  $this->pais_cod                  =  $DB->Value("PAIS_COD");
                  $this->username                  =  $DB->Value("USERNAME");
                  $this->password                  =  $DB->Value("PASSWORD");
                  $this->nombre                    =  $DB->Value("NOMBRE");
                  $this->apellidos                 =  $DB->Value("APELLIDOS");
                  $this->email                     =  $DB->Value("EMAIL");
                  $this->fecha_inscripcion         =  $DB->Value("FECHA_INSCRIPCION");
                  $this->fecha_validacion          =  $DB->Value("FECHA_VALIDACION");
                  $this->fecha_ultimo_login        =  $DB->Value("FECHA_ULTIMO_LOGIN");
                  $this->esta_cod                  =  $DB->Value("ESTA_COD");
                  $this->saldo                     =  $DB->Value("SALDO");
                  $this->sesion                    =  $DB->Value("SESION");
                  $this->enviar_mail               =  $DB->Value("ENVIAR_MAIL");
                  $this->referenciadopor           =  $DB->Value("REFERENCIADOPOR");
                  $this->compra_online             =  $DB->Value("COMPRA_ONLINE");
                  $this->list_cod                  =  $DB->Value("LIST_COD");
                  $this->notifica_saldo            =  $DB->Value("NOTIFICA_SALDO");
                  $this->ultimo_ip                 =  $DB->Value("ULTIMO_IP");
                  $this->es_postpago               =  $DB->Value("ES_POSTPAGO");
                  $this->es_mayorista              =  $DB->Value("ES_MAYORISTA");
                  $this->pin_numerico              =  $DB->Value("PIN_NUMERICO");
                  $this->perf_cod                  =  $DB->Value("PERF_COD");
                  $this->tipo_cliente              =  $DB->Value("TIPO_CLIENTE");
                  $this->empresa                   =  $DB->Value("EMPRESA");
                  $this->reseller                  =  $DB->Value("RESELLER");
                  $this->fecha_asignacion_reseller =  $DB->Value("FECHA_ASIGNACION_RESELLER");
                  $this->factura_electronica       =  $DB->Value("FACTURA_ELECTRONICA");
                  $this->categorizacion            =  $DB->Value("CATEGORIZACION");
                  $this->hizo_abono                =  $DB->Value("HIZO_ABONO");
                  $this->traspaso_online           =  $DB->Value("TRASPASO_ONLINE");
                  $this->vend_cod                  =  $DB->Value("VEND_COD");
                  $this->moneda                    =  $DB->Value("MONEDA");
                  $this->sms_list_cod              =  $DB->Value("SMS_LIST_COD");
                  $this->gate_cod_sippy            =  $DB->Value("GATE_COD_SIPPY");
                  $this->limite_credito            =  $DB->Value("LIMITE_CREDITO");
                  $this->solo_destinos_seguros     =  $DB->Value("SOLO_DESTINOS_SEGUROS");
                  $this->umbral_notificacion       =  $DB->Value("UMBRAL_NOTIFICACION");
                  $this->iva_retenido              =  $DB->Value("IVA_RETENIDO");
                  $this->porcentaje_descuento      =  $DB->Value("PORCENTAJE_DESCUENTO");
                  $this->sms_perf_cod              =  $DB->Value("SMS_PERF_COD");
                  $this->monto_asegurable          =  $DB->Value("MONTO_ASEGURABLE");
                  $this->rut                       =  $DB->Value("RUT");
                  $this->cargo                     =  $DB->Value("CARGO");
                  $this->telefono_celular          =  $DB->Value("TELEFONO_CELULAR");
                  $this->telefono_fijo             =  $DB->Value("TELEFONO_FIJO");
                  $this->rut_empresa               =  $DB->Value("RUT_EMPRESA");
                  $this->razon_social              =  $DB->Value("RAZON_SOCIAL");
                  $this->rub_cod                   =  $DB->Value("RUB_COD");
                  $this->med_cod                   =  $DB->Value("MED_COD");
                  $this->es_contact_center         =  $DB->Value("ES_CONTACT_CENTER");
                  $this->es_internacional          =  $DB->Value("ES_INTERNACIONAL");
                  $this->envio_sms                 =  $DB->Value("ENVIO_SMS");

                  /* registra log */
                  require_once 'Parameters.php';

                  $fd = fopen(Parameters::PATH_LOG . "/verifica_login.log", "a");

                  if (isset($_SERVER["REMOTE_ADDR"]) === TRUE)
                     $remote_addr = $_SERVER["REMOTE_ADDR"];
                  else
                     $remote_addr = "N/A";

                  if (isset($_SERVER["SCRIPT_FILENAME"]) === TRUE)
                     $script_filename = $_SERVER["SCRIPT_FILENAME"];
                  else
                     $script_filename = "N/A";

                  if (isset($_SERVER["HTTP_REFERER"]) === TRUE)
                     $http_referer = $_SERVER["HTTP_REFERER"];
                  else
                     $http_referer = "N/A";

                  fwrite($fd, date("Y-m-d H:i:s") . " " . $remote_addr . " " . $this->usua_cod . " " . $script_filename . " " . $http_referer . "\n");
                  fclose($fd);

                  /* ver que la sesion del php del usuario sea igual a la guardada en la BD */
                  if (  hash('sha512', $this->usua_cod . "-" . $this->password . "-" . $this->sesion . "-" . $_SERVER['HTTP_USER_AGENT']) == $_SESSION['sesion_token'])
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



      /* logea al usuario en el sitio por primera vez o lo manda a página de error  */
      function Login($username, $password, &$DB)
      {
         $retval               = false;
         $valores1['username'] = $username;

         /* ve si está el usuario */
         $DB->BeginTrans();
         if($DB->Query("SELECT usua_cod,
                               password --encrypt
                        FROM FG.FC_USUARIO
                        WHERE username = :username
                              AND esta_cod = 1
                              AND tipo_cliente = 10" , $valores1))
         {
            $this->usua_cod = $DB->Value("USUA_COD");
            $this->password = $DB->Value("PASSWORD");

            require_once 'Parameters.php';
            $p_peppered = hash_hmac("sha256", $password, Parameters::PEPPER);
            if (password_verify($p_peppered, $this->password) === false)
            {
               $DB->Logoff();
               return false;
            }

            /* crea nueva sesion */
            if(($this->sesion = $DB->GetSequence("FG.seq_fc_sesion")) !== false)
            {
               $_SESSION['loggedin']      = true;
               $_SESSION['usua_cod']      = preg_replace("/[^0-9]+/", "", $this->usua_cod);
               $_SESSION['start']         = time();
               $_SESSION['expire']        = $_SESSION['start'] + (24 * 60 * 60);
               $_SESSION['sesion']        = $this->sesion;
               $_SESSION['sesion_token']  = hash('sha512', $this->usua_cod . "-" . $this->password . "-" . $this->sesion . "-" . $_SERVER['HTTP_USER_AGENT']);

               $valores2['sesion']        = $this->sesion;
               $valores2['ultimo_ip']     = $_SERVER["REMOTE_ADDR"];
               $valores2['usua_cod']      = $this->usua_cod;

               /* guarda sesion y ultimo login */
               if($DB->Execute("UPDATE FG.FC_USUARIO
                                SET sesion = :sesion,
                                    fecha_ultimo_login = sysdate,
                                    ultimo_ip = :ultimo_ip
                                WHERE usua_cod = :usua_cod", $valores2))
               {
                  $this->ultimo_ip           = $_SERVER["REMOTE_ADDR"];
                  $valores3['usua_cod']      = intval($this->usua_cod);
                  $valores3['ip']            = strval($_SERVER["REMOTE_ADDR"]);

                  /* si se loguea correctamente guarda la historia del login y reinicia los reintentos */
                  if($DB->Execute("INSERT INTO FG.FC_HISTORIA_LOGIN (usua_cod, fecha_login, ip)
                                   VALUES (:usua_cod, sysdate, :ip)", $valores3) === TRUE)
                  {
                     /* vemos si existe registro para insertar o actualizar */
                     $valores11["usua_cod"]  = intval($this->usua_cod);
                     if ($DB->Query("SELECT 1 cantidad
                                       FROM DUAL
                                       WHERE EXISTS (SELECT a.*
                                                     FROM FG.FC_HISTORIA_INTENTO_LOGIN a
                                                     WHERE a.usua_cod = :usua_cod)", $valores11) === TRUE)
                     {
                        /* si existe se actualiza*/
                        $valores12["usua_cod"]  = intval($this->usua_cod);
                        $valores12["intentos"]  = intval(0);
                        $valores12["ip"]        = strval($_SERVER["REMOTE_ADDR"]);
                        if ($DB->Execute("UPDATE FG.FC_HISTORIA_INTENTO_LOGIN
                                          SET intentos = :intentos,
                                              ip = :ip
                                          WHERE usua_cod = :usua_cod", $valores12) === TRUE)
                           {
                              $retval = TRUE;
                              $DB->Commit();
                           }
                        else
                           $DB->Rollback();
                     }
                     else
                     {
                        $valores12["usua_cod"]  = intval($this->usua_cod);
                        $valores12["intentos"]  = intval(0);
                        $valores12["ip"]        = strval($_SERVER["REMOTE_ADDR"]);
                        /* si no existe se inserta */
                        if ($DB->Execute("INSERT INTO FG.FC_HISTORIA_INTENTO_LOGIN (usua_cod, intentos, ip)
                                          VALUES (:usua_cod, :intentos, :ip )", $valores12) === TRUE)
                        {
                           $DB->Commit();
                           $retval = TRUE;
                        }
                        else
                           $DB->Rollback();
                     }
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
         {
            /* usuario ingresa mal la clave, se incrementa el intento fallido */
            if ($this->CompruebaUsername($username, $DB))
            {
               /* vemos si existe registro para insertar o actualizar */
               $valores10["usua_cod"]  = intval($this->usua_cod);
               if ($DB->Query("SELECT 1 cantidad
                                 FROM DUAL
                                 WHERE EXISTS (SELECT a.*
                                               FROM FG.FC_HISTORIA_INTENTO_LOGIN a
                                               WHERE a.usua_cod = :usua_cod)", $valores10) === TRUE)
               {
                  /* si existe se actualiza*/
                  $valores5["usua_cod"]   = intval($this->usua_cod);
                  $valores5["ip"]         = strval($_SERVER["REMOTE_ADDR"]);
                  if ($DB->Execute("UPDATE FG.FC_HISTORIA_INTENTO_LOGIN
                                    SET intentos = intentos + 1,
                                        ip = :ip
                                    WHERE usua_cod = :usua_cod", $valores5) === TRUE)
                     $DB->Commit();
                  else
                     $DB->Rollback();
               }
               else
               {
                  /* si no existe se inserta */
                  $valores4["usua_cod"]   = intval($this->usua_cod);
                  $valores4["intentos"]   = intval(1);
                  $valores4["ip"]         = strval($_SERVER["REMOTE_ADDR"]);
                  if ($DB->Execute("INSERT INTO FG.FC_HISTORIA_INTENTO_LOGIN (usua_cod, intentos, ip)
                                    VALUES (:usua_cod, :intentos, :ip )", $valores4) === TRUE)
                     $DB->Commit();
                  else
                     $DB->Rollback();
               }

            }
 
         }
         return $retval;
      }


      /* Cierra la sesion del usuario */
      function Logout()
      {
         $_SESSION = array();
         $params = session_get_cookie_params();
         setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
         session_destroy();
         header('Location: ' . Parameters::WEB_PATH . '/customer/login/');
      }


      /* Busca a un usuario Redvoiss dado el username */
      function BuscaUsername($username, &$DB)
      {
         $retval=false;
         $valores['username'] = $username;

         $sql = "SELECT a.usua_cod,
                        a.pais_cod,
                        a.username,
                        a.password,
                        a.nombre,
                        a.apellidos,
                        a.email,
                        TO_CHAR(a.fecha_inscripcion, 'DD-MM-YYYY HH24:MI:SS') fecha_inscripcion,
                        TO_CHAR(a.fecha_validacion, 'DD-MM-YYYY HH24:MI:SS') fecha_validacion,
                        TO_CHAR(a.fecha_ultimo_login, 'DD-MM-YYYY HH24:MI:SS') fecha_ultimo_login,
                        a.esta_cod,
                        a.saldo,
                        a.sesion,
                        a.enviar_mail,
                        a.referenciadopor,
                        a.compra_online,
                        a.list_cod,
                        a.notifica_saldo,
                        a.ultimo_ip,
                        a.es_postpago,
                        a.es_mayorista,
                        a.pin_numerico,
                        a.perf_cod,
                        a.tipo_cliente,
                        a.empresa,
                        a.reseller,
                        TO_CHAR(a.fecha_asignacion_reseller, 'DD-MM-YYYY HH24:MI:SS') fecha_asignacion_reseller,
                        a.factura_electronica,
                        a.categorizacion,
                        a.hizo_abono,
                        a.traspaso_online,
                        a.vend_cod,
                        a.moneda,
                        a.sms_list_cod,
                        a.gate_cod_sippy,
                        a.limite_credito,
                        a.solo_destinos_seguros,
                        a.umbral_notificacion,
                        a.iva_retenido,
                        a.porcentaje_descuento,
                        a.sms_perf_cod,
                        a.monto_asegurable,
                        a.rut,
                        a.cargo,
                        a.telefono_celular,
                        a.telefono_fijo,
                        a.rut_empresa,
                        a.razon_social,
                        a.rub_cod,
                        a.med_cod,
                        a.es_contact_center,
                        a.es_internacional,
                        a.envio_sms
                 FROM FG.FC_USUARIO a
                 WHERE a.esta_cod = 1
                       AND a.username = :username";
         if($DB->Query($sql, $valores))
         {
            $this->usua_cod                  = $DB->Value("USUA_COD");
            $this->pais_cod                  = $DB->Value("PAIS_COD");
            $this->username                  = $DB->Value("USERNAME");
            $this->password                  = $DB->Value("PASSWORD");
            $this->nombre                    = $DB->Value("NOMBRE");
            $this->apellidos                 = $DB->Value("APELLIDOS");
            $this->email                     = $DB->Value("EMAIL");
            $this->fecha_inscripcion         = $DB->Value("FECHA_INSCRIPCION");
            $this->fecha_validacion          = $DB->Value("FECHA_VALIDACION");
            $this->fecha_ultimo_login        = $DB->Value("FECHA_ULTIMO_LOGIN");
            $this->esta_cod                  = $DB->Value("ESTA_COD");
            $this->saldo                     = $DB->Value("SALDO");
            $this->sesion                    = $DB->Value("SESION");
            $this->enviar_mail               = $DB->Value("ENVIAR_MAIL");
            $this->referenciadopor           = $DB->Value("REFERENCIADOPOR");
            $this->compra_online             = $DB->Value("COMPRA_ONLINE");
            $this->list_cod                  = $DB->Value("LIST_COD");
            $this->notifica_saldo            = $DB->Value("NOTIFICA_SALDO");
            $this->ultimo_ip                 = $DB->Value("ULTIMO_IP");
            $this->es_postpago               = $DB->Value("ES_POSTPAGO");
            $this->es_mayorista              = $DB->Value("ES_MAYORISTA");
            $this->pin_numerico              = $DB->Value("PIN_NUMERICO");
            $this->perf_cod                  = $DB->Value("PERF_COD");
            $this->tipo_cliente              = $DB->Value("TIPO_CLIENTE");
            $this->empresa                   = $DB->Value("EMPRESA");
            $this->reseller                  = $DB->Value("RESELLER");
            $this->fecha_asignacion_reseller = $DB->Value("FECHA_ASIGNACION_RESELLER");
            $this->factura_electronica       = $DB->Value("FACTURA_ELECTRONICA");
            $this->categorizacion            = $DB->Value("CATEGORIZACION");
            $this->hizo_abono                = $DB->Value("HIZO_ABONO");
            $this->traspaso_online           = $DB->Value("TRASPASO_ONLINE");
            $this->vend_cod                  = $DB->Value("VEND_COD");
            $this->moneda                    = $DB->Value("MONEDA");
            $this->sms_list_cod              = $DB->Value("SMS_LIST_COD");
            $this->gate_cod_sippy            = $DB->Value("GATE_COD_SIPPY");
            $this->limite_credito            = $DB->Value("LIMITE_CREDITO");
            $this->solo_destinos_seguros     = $DB->Value("SOLO_DESTINOS_SEGUROS");
            $this->umbral_notificacion       = $DB->Value("UMBRAL_NOTIFICACION");
            $this->iva_retenido              = $DB->Value("IVA_RETENIDO");
            $this->porcentaje_descuento      = $DB->Value("PORCENTAJE_DESCUENTO");
            $this->sms_perf_cod              = $DB->Value("SMS_PERF_COD");
            $this->monto_asegurable          = $DB->Value("MONTO_ASEGURABLE");
            $this->rut                       = $DB->Value("RUT");
            $this->cargo                     = $DB->Value("CARGO");
            $this->telefono_celular          = $DB->Value("TELEFONO_CELULAR");
            $this->telefono_fijo             = $DB->Value("TELEFONO_FIJO");
            $this->rut_empresa               = $DB->Value("RUT_EMPRESA");
            $this->razon_social              = $DB->Value("RAZON_SOCIAL");
            $this->rub_cod                   = $DB->Value("RUB_COD");
            $this->med_cod                   = $DB->Value("MED_COD");
            $this->es_contact_center         = $DB->Value("ES_CONTACT_CENTER");
            $this->es_internacional          = $DB->Value("ES_INTERNACIONAL");
            $this->envio_sms                 = $DB->Value("ENVIO_SMS");
            $retval = TRUE;
         }
         $DB->Close();
         return $retval;
      }


      /* Busca a un usuario dado el usua_cod */
      function Busca($usua_cod, &$DB)
      {
         $retval=false;
         $valores['usua_cod'] = $usua_cod;

         $sql = "SELECT a.usua_cod,
                        a.pais_cod,
                        a.username,
                        a.password,
                        a.nombre,
                        a.apellidos,
                        a.email,
                        TO_CHAR(a.fecha_inscripcion, 'DD-MM-YYYY HH24:MI:SS') fecha_inscripcion,
                        TO_CHAR(a.fecha_validacion, 'DD-MM-YYYY HH24:MI:SS') fecha_validacion,
                        TO_CHAR(a.fecha_ultimo_login, 'DD-MM-YYYY HH24:MI:SS') fecha_ultimo_login,
                        a.esta_cod,
                        a.saldo,
                        a.sesion,
                        a.enviar_mail,
                        a.referenciadopor,
                        a.compra_online,
                        a.list_cod,
                        a.notifica_saldo,
                        a.ultimo_ip,
                        a.es_postpago,
                        a.es_mayorista,
                        a.pin_numerico,
                        a.perf_cod,
                        a.tipo_cliente,
                        a.empresa,
                        a.reseller,
                        TO_CHAR(a.fecha_asignacion_reseller, 'DD-MM-YYYY HH24:MI:SS') fecha_asignacion_reseller,
                        a.factura_electronica,
                        a.categorizacion,
                        a.hizo_abono,
                        a.traspaso_online,
                        a.vend_cod,
                        a.moneda,
                        a.sms_list_cod,
                        a.gate_cod_sippy,
                        a.limite_credito,
                        a.solo_destinos_seguros,
                        a.umbral_notificacion,
                        a.iva_retenido,
                        a.porcentaje_descuento,
                        a.sms_perf_cod,
                        a.monto_asegurable,
                        a.rut,
                        a.cargo,
                        a.telefono_celular,
                        a.telefono_fijo,
                        a.rut_empresa,
                        a.razon_social,
                        a.rub_cod,
                        a.med_cod,
                        a.es_contact_center,
                        a.es_internacional,
                        a.envio_sms
                 FROM FG.FC_USUARIO a
                 WHERE a.esta_cod = 1
                       AND a.usua_cod = :usua_cod";
         if($DB->Query($sql, $valores))
         {
            $this->usua_cod                  = $DB->Value("USUA_COD");
            $this->pais_cod                  = $DB->Value("PAIS_COD");
            $this->username                  = $DB->Value("USERNAME");
            $this->password                  = $DB->Value("PASSWORD");
            $this->nombre                    = $DB->Value("NOMBRE");
            $this->apellidos                 = $DB->Value("APELLIDOS");
            $this->email                     = $DB->Value("EMAIL");
            $this->fecha_inscripcion         = $DB->Value("FECHA_INSCRIPCION");
            $this->fecha_validacion          = $DB->Value("FECHA_VALIDACION");
            $this->fecha_ultimo_login        = $DB->Value("FECHA_ULTIMO_LOGIN");
            $this->esta_cod                  = $DB->Value("ESTA_COD");
            $this->saldo                     = $DB->Value("SALDO");
            $this->sesion                    = $DB->Value("SESION");
            $this->enviar_mail               = $DB->Value("ENVIAR_MAIL");
            $this->referenciadopor           = $DB->Value("REFERENCIADOPOR");
            $this->compra_online             = $DB->Value("COMPRA_ONLINE");
            $this->list_cod                  = $DB->Value("LIST_COD");
            $this->notifica_saldo            = $DB->Value("NOTIFICA_SALDO");
            $this->ultimo_ip                 = $DB->Value("ULTIMO_IP");
            $this->es_postpago               = $DB->Value("ES_POSTPAGO");
            $this->es_mayorista              = $DB->Value("ES_MAYORISTA");
            $this->pin_numerico              = $DB->Value("PIN_NUMERICO");
            $this->perf_cod                  = $DB->Value("PERF_COD");
            $this->tipo_cliente              = $DB->Value("TIPO_CLIENTE");
            $this->empresa                   = $DB->Value("EMPRESA");
            $this->reseller                  = $DB->Value("RESELLER");
            $this->fecha_asignacion_reseller = $DB->Value("FECHA_ASIGNACION_RESELLER");
            $this->factura_electronica       = $DB->Value("FACTURA_ELECTRONICA");
            $this->categorizacion            = $DB->Value("CATEGORIZACION");
            $this->hizo_abono                = $DB->Value("HIZO_ABONO");
            $this->traspaso_online           = $DB->Value("TRASPASO_ONLINE");
            $this->vend_cod                  = $DB->Value("VEND_COD");
            $this->moneda                    = $DB->Value("MONEDA");
            $this->sms_list_cod              = $DB->Value("SMS_LIST_COD");
            $this->gate_cod_sippy            = $DB->Value("GATE_COD_SIPPY");
            $this->limite_credito            = $DB->Value("LIMITE_CREDITO");
            $this->solo_destinos_seguros     = $DB->Value("SOLO_DESTINOS_SEGUROS");
            $this->umbral_notificacion       = $DB->Value("UMBRAL_NOTIFICACION");
            $this->iva_retenido              = $DB->Value("IVA_RETENIDO");
            $this->porcentaje_descuento      = $DB->Value("PORCENTAJE_DESCUENTO");
            $this->sms_perf_cod              = $DB->Value("SMS_PERF_COD");
            $this->monto_asegurable          = $DB->Value("MONTO_ASEGURABLE");
            $this->rut                       = $DB->Value("RUT");
            $this->cargo                     = $DB->Value("CARGO");
            $this->telefono_celular          = $DB->Value("TELEFONO_CELULAR");
            $this->telefono_fijo             = $DB->Value("TELEFONO_FIJO");
            $this->rut_empresa               = $DB->Value("RUT_EMPRESA");
            $this->razon_social              = $DB->Value("RAZON_SOCIAL");
            $this->rub_cod                   = $DB->Value("RUB_COD");
            $this->med_cod                   = $DB->Value("MED_COD");
            $this->es_contact_center         = $DB->Value("ES_CONTACT_CENTER");
            $this->es_internacional          = $DB->Value("ES_INTERNACIONAL");
            $this->envio_sms                 = $DB->Value("ENVIO_SMS");
            $retval = TRUE;
         }
         $DB->Close();
         return $retval;
      }

      // modificar email
      function modificaEmail($email, &$DB)
      {
         $retval                             = false;

         $valores['usua_cod']                = $this->usua_cod;
         $valores['email']                   = $email;

         $sql                                = "UPDATE FG.FC_USUARIO a
                                                SET a.email = :email
                                                WHERE a.usua_cod = :usua_cod";

         if($DB->Execute($sql, $valores))
         {
            $this->email                     = $email;
            $retval                          = true;
         }
         return $retval;
      }

      function BuscaUsernameBP($username, &$DB)
      {
         $retval              = false;
         $valores['username'] = $username;

         $sql                 = "SELECT a.usua_cod,
                                       a.username,
                                       a.email
                                 FROM FG.FC_USUARIO a
                                 WHERE a.esta_cod = 1
                                 AND a.username = :username
                                 AND a.tipo_cliente = 10";
         if($DB->Query($sql, $valores))
         {
            $this->usua_cod   = $DB->Value("USUA_COD");
            $this->username   = $DB->Value("USERNAME");
            $this->email      = $DB->Value("EMAIL");
            $retval           = true;
         }
         $DB->Close();
         return $retval;
      }

      // modifica datos
      function Actualiza($nombre, $apellidos, $empresa, $pais_cod, $razon_social, $rut_empresa, $rut, $cargo, $telefono_celular, $telefono_fijo, $rub_cod, $med_cod, &$DB)
      {
         $retval                       = false;

         $valores['usua_cod']          = $this->usua_cod;
         $valores['nombre']            = $nombre;
         $valores['apellidos']         = $apellidos;
         $valores['empresa']           = $empresa;
         $valores['pais_cod']          = $pais_cod;
         $valores['razon_social']      = $razon_social;
         $valores['rut_empresa']       = $rut_empresa;
         $valores['rut']               = $rut;
         $valores['cargo']             = $cargo;
         $valores['telefono_celular']  = $telefono_celular;
         $valores['telefono_fijo']     = $telefono_fijo;
         $valores['rub_cod']           = $rub_cod;
         $valores['med_cod']           = $med_cod;

         $sql                          = "UPDATE FG.FC_USUARIO a
                                          SET a.nombre = UPPER(:nombre),
                                             a.apellidos = UPPER(:apellidos),
                                             a.empresa = UPPER(:empresa),
                                             a.pais_cod = :pais_cod,
                                             a.razon_social = UPPER(:razon_social),
                                             a.rut_empresa = UPPER(:rut_empresa),
                                             a.rut = UPPER(:rut),
                                             a.cargo = UPPER(:cargo),
                                             a.telefono_celular = UPPER(:telefono_celular),
                                             a.telefono_fijo = UPPER(:telefono_fijo),
                                             a.rub_cod = :rub_cod,
                                             a.med_cod = :med_cod
                                          WHERE a.usua_cod = :usua_cod";

         if ($DB->Execute($sql, $valores))
         {
            $retval                    = true;
         }
         return $retval;
      }

   }

?>