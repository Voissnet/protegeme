<?
   require_once 'MOD_Error.php';
   require_once 'Parameters.php';
   require_once 'BConexion.php';
   require_once 'BUsuarioRV.php';

   $UsuarioRV  = new BUsuarioRV;
   $DB         = new BConexion;

   if ($UsuarioRV->sec_session_start_ajax() === false) {
      $DB->Logoff();
      MOD_Error::ErrorJSON('PBE_129');
      exit;
   }

   if ($UsuarioRV->VerificaLogin($DB) === false) {
      $DB->Logoff();
      MOD_Error::ErrorJSON('PBE_130');
      exit;
   }

   $error    = false;
   $messages = [];

   if (!isset($_POST)) {
      $error = true;
      $messages = MOD_Error::ErrorCode('PBE_117');
      goto result;
   }
   
   array_push($messages, 'Validando datos');

   $dom_cod             = isset($_POST['dom_cod']) ? intval($_POST['dom_cod']) : false;
   $group_cod           = isset($_POST['group_cod']) ? intval($_POST['group_cod']) : false;
   $nombre              = isset($_POST['nombre']) ? $_POST['nombre'] : false;
   $cloud_username      = isset($_POST['cloud_username']) ? $_POST['cloud_username'] : false;
   $cloud_password      = isset($_POST['cloud_password']) ? $_POST['cloud_password'] : false;
   $user_phone          = isset($_POST['user_phone']) ? intval($_POST['user_phone']) : false;
   $email               = isset($_POST['email']) ? $_POST['email'] : false;
   $services            = isset($_POST['services']) ? $_POST['services'] : false;
   $localizacion        = isset($_POST['localizacion']) ? $_POST['localizacion'] : false;
   $mac                 = isset($_POST['mac']) ? $_POST['mac'] : false;
   $tipo_tracker        = isset($_POST['tipo_tracker']) ? $_POST['tipo_tracker'] : false;
   $causa               = isset($_POST['causa']) ? $_POST['causa'] : false;

   # clases
   require_once 'SEmail.php';
   require_once 'BConjuntoNumero.php';
   require_once 'BGatewayNumero.php';
   require_once 'BDominio.php';
   require_once 'BGrupo.php';
   require_once 'BUsuario.php';
   require_once 'BTipoBoton.php';
   require_once 'BBoton.php';
   require_once 'BTracker.php';
   require_once 'BTipoServicio.php';
   require_once 'BOtrosProductos.php';
   require_once 'BLog.php';
   
   $GatewayNumero = new BGatewayNumero();
   $Dominio       = new BDominio();
   $Grupo         = new BGrupo();
   $Usuario       = new BUsuario();
   $TipoBoton     = new BTipoBoton();
   $Boton         = new BBoton();
   $Tracker       = new BTracker();
   $TipoTracker   = new BTipoServicio();
   $OtroProd      = new BOtrosProductos();
   $Log           = new BLog();

   # estado para saber si se debe notificar
   $status_notify    = false;
   $data_notify      = [];
   $services_desc    = '';

   array_push($messages, '<span class="text-dark">Validando dominio.</span>');

   # busca dominio en BD
   if ($Dominio->busca($dom_cod, $DB) === false) {
      $error = true;
      array_push($messages, '<span class="text-danger">Error: Dominio no registrado - cod: 01.</span>');
      goto result;
   }

   array_push($messages, '<span class="text-success">Dominio <strong>&quot;' . $Dominio->dominio_usuario . '&quot;</strong> OK.</span>');

   # cantidad de usuarios
   $Dominio->cantidadUsuarios($DB);

   array_push($messages, '<span class="text-dark">Validando cantidad de usuarios.</span>');
   if ($Dominio->cantidad >= intval($Dominio->cantidad_usuario)) {
      $error = true;
      array_push($messages, '<span class="text-danger">Error: Cantidad de usuarios excedido - cod: 02.</span>');
      goto result;
   }
   array_push($messages, '<span class="text-success">Cantidad valida</span>');

   # valida contact center
   array_push($messages, '<span class="text-dark">Validando Contact Center.</span>');

   # busca contact center en BD
   if ($Grupo->busca($group_cod, $DB) === false) {
      $error = true;
      array_push($messages, '<span class="text-danger">Error: Contact Center no registrado - cod: 03.</span>');
      goto result;
   }

   # valida que el contact center
   if ($Dominio->dom_cod !== $Grupo->dom_cod) {
      $error = true;
      array_push($messages, '<span class="text-danger">Error: Contact Center no pertenece al dominio - cod: 04.</span>');
      goto result;
   }
   
   array_push($messages, '<span class="text-success">Contact Center <strong>&quot;' . $Grupo->nombre . '&quot;</strong> OK.</span>');

   array_push($messages, '<span class="text-primary">Creando usuario.</span>');

   # cloud_username no se puede repetir
   if ($Usuario->verificaCloudUserDom($cloud_username, $Dominio->dom_cod, $DB) === true) {
      $error = true;
      array_push($messages, '<span class="text-danger">Usuario | CLOUD_USERNAME <strong>&quot;' . $cloud_username . '&quot;</strong> ya existe con servicio - cod: 05</span>');
      goto result;
   }

   # cloud_password
   $p_peppered    = hash_hmac('sha256', $cloud_password, Parameters::PEPPER);
   $encriptado    = password_hash($p_peppered, PASSWORD_BCRYPT);

   # abre transaccion
   $DB->BeginTrans();

   # creacion del usuario
   if ($Usuario->insert($nombre, $cloud_username, $encriptado, $user_phone, $email, $group_cod, $DB) === false) {
      $DB->Rollback();
      $error = true;
      array_push($messages, '<span class="text-danger">Usuario | CLOUD_USERNAME <strong>&quot;' . $cloud_username . ' no se logro registrar - cod: 06</span>');
      goto result;
   }

   array_push($messages, '<span class="text-success">Usuario creado.</span>');

   // array_push($messages, '<span class="text-primary">Creando audio.</span>');
   // $response = Parameters::apiAudio(str_replace(' ', '+', $nombre));
   // array_push($messages, '<span class="text-success">Audio creado - ' . $response['status'] . '.</span>');

   # servicios
   if ($services !== false) {

      $services_desc = 'SI |';

      # TIPO DE BOTON:
      # 1 - Botón de emergencia SIP - Móvil
      # 2 - Botón de emergencia SIP - Estático
      # 3 - Botón de emergencia Estándar
      # 4 - Widget
      # 5 - Tracker
      # Default: Otros productos

      array_push($messages, '<span>Registrando servicios.</span>');

      foreach ($services as $index => $service) {
         
         $service_data     = json_decode($service, true);
         $tipo_cod         = intval($service_data['tipo_cod']);
         $notify_service   = intval($service_data['notify_service']);

         switch ($tipo_cod) {

            case 1:

               # 1 - Botón de emergencia SIP - Móvil

               $services_desc .= ' Botón de emergencia SIP - Móvil | ';

               $bpid = 1;

               array_push($messages, '<span class="text-primary">Bot&oacute;n de emergencia SIP - M&oacute;vil.</span>');

               for ($x = 0; $x < 1; $x++) {

                  $sip_username     = Parameters::generaSipUsername($bpid, strlen($bpid)); # como es nuevo parte en 1
                  $sip_display_name = Parameters::generaSipUsername($bpid, strlen($bpid)); # como es nuevo parte en 1

                  # tenemos que saber cual sip_username esta disponible dentro del dominio
                  if ($Boton->verificaUserBoton($sip_username, $dom_cod, $DB) === false) {

                     $sip_password = Parameters::generaPasswordSIP(7); # codigo generado por Sistema

                     # registra boton
                     if ($Boton->insert($sip_username, $sip_password, $sip_display_name, $Usuario->busua_cod, 1, '', '', '', $DB) === false) {
                        $DB->Rollback();
                        $error = true;
                        array_push($messages, '<span class="text-danger">Error: No se logro crear "Bot&oacute;n de emergencia SIP - M&oacute;vil" - cod: 07</span>');
                        break;
                     }

                     # registro de numero
                     if ($GatewayNumero->PideAsignado($Dominio->gate_cod, 2, 20, $sip_username, $DB) === false) {
                        $DB->Rollback();
                        $error = true;
                        array_push($messages, '<span class="text-danger">Error: No se pudo aprovisionar n&uacute;mero SOS - cod: 08</span>');
                        break;
                     }

                     # notifica servicio
                     if ($notify_service === 1) {

                        $status_notify = true;

                        array_push($data_notify, [
                           'token'           => Parameters::openCypher('encrypt', 'SResDvO2!9$32#01widJys56!?1ads'),
                           'dom_cod'         => Parameters::openCypher('encrypt', $Dominio->dom_cod),
                           'busua_cod'       => Parameters::openCypher('encrypt', $Usuario->busua_cod),
                           'tipo_cod'        => Parameters::openCypher('encrypt', $tipo_cod),
                           'cloud_password'  => Parameters::openCypher('encrypt', $cloud_password)
                        ]);
      
                     }

                     array_push($messages, '<span class="text-success">Servicio OK.</span>');

                  } else {

                     $x--;       # restamos 1 para que se repita nuevamente
                     $bpid++;    # aumentamos 1

                  }
                  
               }
            

               break;
            case 2:

               # 2 - Botón de emergencia SIP - Estático

               $services_desc .= ' Botón de emergencia SIP - Estático | ';

               $bpid = 1;

               array_push($messages, '<span class="text-primary">Bot&oacute;n de emergencia SIP - Est&aacute;tico.</span>');

               array_push($messages, '<span class="text-primary">Verificando mac.</span>');

               # validamos mac ingresada primero
               if ($Boton->verificaMac($mac, $DB) === true) {
                  $DB->Rollback();
                  $error = true;
                  array_push($messages, '<span class="text-danger">Error: Mac "' . $mac . '" existe en Sistemas - cod: 11</span>');
                  break;
               }

               array_push($messages, '<span class="text-success">Mac OK.</span>');

               for ($x = 0; $x < 1; $x++) {
                  
                  $sip_username     = Parameters::generaSipUsernameFIJO($bpid, strlen($bpid)); # como es nuevo parte en 1
                  $sip_display_name = Parameters::generaSipUsernameFIJO($bpid, strlen($bpid)); # como es nuevo parte en 1

                  # tenemos que saber cual sip_username esta disponible dentro del dominio
                  if ($Boton->verificaUserBoton($sip_username, $dom_cod, $DB) === false) {

                     $sip_password = Parameters::generaPasswordSIP(7); # codigo generado por Sistema

                     # registra boton
                     if ($Boton->insert($sip_username, $sip_password, $sip_display_name, $Usuario->busua_cod, 2, $localizacion, Parameters::obtieneCoordenadas($localizacion), $mac, $DB) === false) {
                        $DB->Rollback();
                        $error = true;
                        array_push($messages, '<span>No se logro crear "Bot&oacute;n de emergencia SIP - Est&aacute;tico" - cod: 12</span>');
                        break;
                     }

                     # registro de numero
                     if ($GatewayNumero->PideAsignado($Dominio->gate_cod, 2, 20, $sip_username, $DB) === false) {
                        $DB->Rollback();
                        $error = true;
                        array_push($messages, '<span class="text-danger">Error: No se pudo aprovisionar n&uacute;mero SOS - cod: 13</span>');
                        break;
                     }

                     # notifica servicio
                     if ($notify_service === 1) {

                        $status_notify = true;

                        array_push($data_notify, [
                           'token'           => Parameters::openCypher('encrypt', 'SResDvO2!9$32#01widJys56!?1ads'),
                           'dom_cod'         => Parameters::openCypher('encrypt', $Dominio->dom_cod),
                           'busua_cod'       => Parameters::openCypher('encrypt', $Usuario->busua_cod),
                           'tipo_cod'        => Parameters::openCypher('encrypt', $tipo_cod),
                           'cloud_password'  => Parameters::openCypher('encrypt', $cloud_password)
                        ]);

                     }

                     array_push($messages, '<span class="text-success">Servicio OK.</span>');

                  } else {

                     $x--;       # restamos 1 para que se repita nuevamente
                     $bpid++;    # aumentamos 1

                  }
                  
               }

               break;

            case 4:

               # 4 - Widget

               $services_desc .= ' Widget | ';

               array_push($messages, '<span class="text-primary">Widget</span>');
               
               # registra Widget
               if ($OtroProd->inserta($tipo_cod, $Usuario->busua_cod, $cloud_username, $encriptado, $DB) === false) {
                  $DB->Rollback();
                  $error = true;
                  array_push($messages, '<span class="text-danger">Error: No se pudo aprovisionar servicio Widget - cod: 16</span>');
                  break;
               }

               # notifica servicio
               if ($notify_service === 1) {

                  $status_notify = true;

                  array_push($data_notify, [
                     'token'           => Parameters::openCypher('encrypt', 'SResDvO2!9$32#01widJys56!?1ads'),
                     'dom_cod'         => Parameters::openCypher('encrypt', $Dominio->dom_cod),
                     'busua_cod'       => Parameters::openCypher('encrypt', $Usuario->busua_cod),
                     'tipo_cod'        => Parameters::openCypher('encrypt', $tipo_cod),
                     'cloud_password'  => Parameters::openCypher('encrypt', $cloud_password)
                  ]);

               }

               array_push($messages, '<span class="text-success">Servicio OK.</span>');

               break;

            case 5:

               # 5 - Tracker

               $services_desc .= ' Tracker | ';


               array_push($messages, '<span class="text-primary">Tracker.</span>');

               # registro de Tracker
               if ($Tracker->insert($Usuario->busua_cod, $tipo_tracker, $causa, $DB) === false) {
                  $DB->Rollback();
                  $error = true;
                  array_push($messages, '<span>No se logro crear Tracker - cod: 14</span>');
                  break;
               }

               # notifica servicio
               if ($notify_service === 1) {

                  $status_notify = true;
                  
                  array_push($data_notify, [
                     'token'           => Parameters::openCypher('encrypt', 'SResDvO2!9$32#01widJys56!?1ads'),
                     'dom_cod'         => Parameters::openCypher('encrypt', $Dominio->dom_cod),
                     'busua_cod'       => Parameters::openCypher('encrypt', $Usuario->busua_cod),
                     'tipo_cod'        => Parameters::openCypher('encrypt', $tipo_cod),
                     'cloud_password'  => Parameters::openCypher('encrypt', $cloud_password)
                  ]);

               }

               array_push($messages, '<span class="text-success">Servicio OK.</span>');
   
               break;

            default:

               # Default: Otros productos

               # primero verificamos que el tipo de servicio exista
               if ($TipoBoton->busca($tipo_cod, $DB) === false) {
                  $DB->Rollback();
                  $error = true;
                  $mensaje = 'No se pudo aprovisionar servicio - cod: 15';
                  goto result;
               }

               $services_desc .= ' ' . $TipoBoton->tipo . ' | ';

               array_push($messages, '<span class="text-primary">' . $TipoBoton->tipo . '.</span>');

               # registra otro producto
               if ($OtroProd->inserta($TipoBoton->tipo_cod, $Usuario->busua_cod, $cloud_username, $encriptado, $DB) === false) {
                  $DB->Rollback();
                  $error = true;
                  array_push($messages, '<span class="text-danger">Error: No se pudo aprovisionar servicio Widget - cod: 16</span>');
                  break;
               }

               array_push($messages, '<span class="text-success">Servicio OK.</span>');

               break;
         }
         
      }

   } else {

      $services_desc = 'NO';

   }

   $DB->Commit();

result:
   if ($error === true) {

      $data = array( 'status'    => 'error',
                     'message'   => $messages );
      echo json_encode($data);

   } else {

      $path_log = Parameters::PATH . '/log/site_adm.log';
      $Log->CreaLogTexto($path_log);
      $desc = 'USUARIO CREADO | BUSUA_COD: ' . ($Usuario->busua_cod) . ' | NOMBRE: ' . ($nombre) . ' | CLOUD_USERNAME: ' . ($cloud_username) . ' | USER_PHONE: ' .  ($user_phone) . ' | EMAIL: ' .  ($email);
      $desc .= ' | SERVICIOS: ' . $services_desc;
      $Log->RegistraLinea($desc);

      $data = array( 'status'          => 'success',
                     'message'         => $messages,
                     'status_notify'   => $status_notify,
                     'data_notify'     => $data_notify );
      echo json_encode($data);

   }

   $DB->Logoff();

?>