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

   $error = false;
   $messages = [];

   if (!isset($_POST)) {
      $messages = MOD_Error::Error('PBE_117');
      $error = true;
      goto result;
   }
   
   array_push($messages, 'Validando datos');

   $dom_cod       = isset($_POST['dom_cod']) ? intval($_POST['dom_cod']) : false;
   $group_cod     = isset($_POST['group_cod']) ? intval($_POST['group_cod']) : false;
   $file2upload   = isset($_FILES['file2upload']) ? $_FILES['file2upload'] : false;
   $services      = isset($_POST['services']) ? $_POST['services'] : false;

   # clases
   require_once 'SEmail.php';
   require_once 'BGatewayNumero.php';
   require_once 'BConjuntoNumero.php';
   require_once 'BDominio.php';
   require_once 'BGrupo.php';
   require_once 'BUsuario.php';
   require_once 'BBoton.php';
   require_once 'BTipoBoton.php';
   require_once 'BTracker.php';
   require_once 'BTipoServicio.php';
   require_once 'BOtrosProductos.php';
   require_once 'BLog.php';

   $GatewayNumero = new BGatewayNumero();
   $Dominio       = new BDominio();
   $Grupo         = new BGrupo();
   $Usuario       = new BUsuario();
   $Boton         = new BBoton();
   $TipoBoton     = new BTipoBoton();
   $Tracker       = new BTracker();
   $TipoTracker   = new BTipoServicio();
   $OtroProd      = new BOtrosProductos();
   $Log           = new BLog();

   # estado para saber si se debe notificar
   $status_notify    = false;
   $data_notify      = [];

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

   array_push($messages, '<span class="text-primary">Creando usuarios.</span>');

   # archivo .csv
   array_push($messages, 'Validando formato archivo ' . $file2upload['name']);

   # validando tipo de archivo
   if ($file2upload['type'] != 'text/plain' && $file2upload['type'] != 'application/vnd.ms-excel' &&  $file2upload['type'] != 'text/csv') {
      
      $error  = true;
      array_push($messages, '<span class="text-danger">Archivo <strong>' . $file2upload['name']  . '</strong> no tiene el formato correcto - cod: 05</span>');
      goto result;

   }

   # validando tamano archivo
   if($file2upload['size'] <= 0) {

      $error  = true;
      array_push($messages, '<span class="text-danger">Archivo ' . $file2upload['name']  . ' puede que no tenga datos - cod: 06</span>');
      goto result;

   }
   array_push($messages, '<span class="text-success">Archivo ' . $file2upload['name'] . ' OK.</span>');

   # nombre archivo
   $file_name = date('d_m_Y') . '_' . time() . '_' . $file2upload['name'];

   # guarda respaldo del archivo
   if (is_uploaded_file($file2upload['tmp_name'])) {

      move_uploaded_file($file2upload['tmp_name'], '../csv/' . $file_name);

   }
   
   # lee el archvo guardado
   $file = Parameters::csv_to_multidimension_array('../csv/' . $file_name);

   $cantidad = ($Dominio->cantidad + (count($file)-1));

   # cantidad de usuarios
   if ($cantidad > $Dominio->cantidad_usuario) {

      $error  = true;
      array_push($messages, '<span class="text-danger">Cantidad de usuarios excedido - cod: 07</span>');
      goto result;

   }

   array_push($messages, '<span class="text-primary">Creando usuarios y servicios.</span>');

   $col_localizacion = '';
   $col_mac          = '';
   $col_tipo_tracker = '';
   $col_causa        = '';

   # TIPO DE BOTON:
   # 1 - Botón de emergencia SIP - Móvil
   # 2 - Botón de emergencia SIP - Estático
   # 3 - Botón de emergencia Estándar
   # 4 - Widget
   # 5 - Tracker
   # 6 - Web RTC
   # Default: Otros productos

   # ESTO ES PARA SABER CON EXACTITUD EN QUE COLUMNAS ESTAN LAS VARIABLES
   foreach ($file[0] as $key => $value) {

      // SERVICIOS
      if ($services !== false) {
   
         for ($i = 0; $i < count($services); $i++) {
      
            switch ($services[$i]) {

               case 2:

                  # 2 - Botón de emergencia SIP - Estático

                  if ($value === 'LOCALIZACION' || $value === 'LOCALIZACIÓN' || $value === 'localización' || $value === 'localizacion') {
                     $col_localizacion = $key;
                  }

                  if ($value === 'MAC' || $value === 'mac') {
                     $col_mac = $key;
                  }

                  break;

               case 5:

                  # 5 - Tracker

                  if ($value === 'TIPO_TRACKER' || $value === 'tipo_tracker') {
                     $col_tipo_tracker = $key;
                  }

                  if ($value === 'CAUSA' || $value === 'causa') {
                     $col_causa = $key;
                  }

                  break;

            }

         }

      }

   }

   $path_log = Parameters::PATH . '/log/site_adm.log';
   $Log->CreaLogTexto($path_log);
   $DB->BeginTrans();

   for ($i = 1; $i < count($file); $i++) {

      $nombre           = trim($file[$i][0]);               // nombre del usuario
      $cloud_username   = trim(strtolower($file[$i][1]));   // cloud_username
      $cloud_password   = trim($file[$i][2]);               // cloud_password
      $user_phone       = trim($file[$i][3]);               // user_phone
      $email            = trim($file[$i][4]);               // email
      $services_desc = '';
      
      # cloud_username debe venir sin @
      if (strpos($cloud_username, '@') === false) {

         if ($Usuario->verificaCloudUserDom($cloud_username, $dom_cod, $DB) === false) {

            $p_peppered    = hash_hmac('sha256', $cloud_password, Parameters::PEPPER);
            $encriptado    = password_hash($p_peppered, PASSWORD_BCRYPT);
            
            # creacion del usuario
            if ($Usuario->insert($nombre, $cloud_username, $encriptado, $user_phone, $email, $group_cod, $DB) === false) {

               array_push($messages, '<span class="text-danger">Usuario | CLOUD_USERNAME <strong>&quot;' . $cloud_username . '&quot;</strong> | no se pudo crear - fila ' . $i . ', omitido...</span>');

            } else {

               # servicios
               if ($services !== false) {
                  
                  $services_desc = 'SI | ';

                  foreach ($services as $index => $service) {

                     $service_data     = json_decode($service, true);
                     $tipo_cod         = intval($service_data['tipo_cod']);
                     $notify_service   = intval($service_data['notify_service']);
            
                     switch ($tipo_cod) {

                        case 1:

                           # 1 - Botón de emergencia SIP - Móvil

                           $services_desc .= ' Botón de emergencia SIP - Móvil | ';

                           $bpid = 1;

                           for ($z = 0; $z < 1; $z++) {

                              $sip_username     = Parameters::generaSipUsername($bpid, strlen($bpid), 1); # como es nuevo parte en 1
                              $sip_display_name = Parameters::generaSipUsername($bpid, strlen($bpid), 1); # como es nuevo parte en 1

                              # tenemos que saber cual sip_username esta disponible dentro del dominio
                              if ($Boton->verificaUserBoton($sip_username, $dom_cod, $DB) === false) {

                                 $sip_password = Parameters::generaPasswordSIP(7); # codigo generado por Sistema

                                 # registra boton
                                 if ($Boton->insert($sip_username, $sip_password, $sip_display_name, $Usuario->busua_cod, $tipo_cod, '', '', '', $DB) === false) {
                                    
                                    $DB->Rollback();
                                    array_push($messages, '<span class="text-danger">No se logro crear servicio usuario - cod: 08</span>');
                                    $error = true;
                                    goto result;

                                 }

                                 # registro de numero
                                 if ($GatewayNumero->PideAsignado($Dominio->gate_cod, 2, 20, $sip_username, $DB) === false) {
                                    
                                    $DB->Rollback();
                                    array_push($messages, '<span class="text-danger">Error: No se pudo aprovisionar N&uacute;mero SOS - cod: 09</span>');
                                    $error = true;
                                    goto result;

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

                              } else {

                                 $z--;       # restamos 1 para que se repita nuevamente
                                 $bpid++;    # aumentamos 1

                              }

                           }

                           break;

                        case 2:

                           # 2 - Botón de emergencia SIP - Estático

                           $services_desc .= ' Botón de emergencia SIP - Estático | ';

                           $bpid = 1;

                           # localizacion
                           $localizacion  = $col_localizacion !== '' ? trim($file[$i][$col_localizacion]) : null;
                           $mac           = $col_mac !== '' ? trim($file[$i][$col_mac]) : null;

                           # validamos mac ingresada primero
                           if ($Boton->verificaMac($mac, $DB) === true) {
                              
                              $DB->Rollback();
                              array_push($messages, '<span class="text-danger">Mac "' . $mac . '" existe en Sistemas, usuario | CLOUD_USERNAME <strong>&quot;' . $cloud_username . '&quot;</strong> | no se pudo crear - fila ' . $i . '</span>');
                              $error = true;
                              break;

                           }

                           for ($z = 0; $z < 1; $z++) {
                  
                              $sip_username     = Parameters::generaSipUsername($bpid, strlen($bpid), 2); # como es nuevo parte en 1
                              $sip_display_name = Parameters::generaSipUsername($bpid, strlen($bpid), 2); # como es nuevo parte en 1
            
                              # tenemos que saber cual sip_username esta disponible dentro del dominio
                              if ($Boton->verificaUserBoton($sip_username, $dom_cod, $DB) === false) {
            
                                 $sip_password = Parameters::generaPasswordSIP(7); # codigo generado por Sistema
            
                                 # registra boton
                                 if ($Boton->insert($sip_username, $sip_password, $sip_display_name, $Usuario->busua_cod, $tipo_cod, $localizacion, Parameters::obtieneCoordenadas($localizacion), $mac, $DB) === false) {
                                    
                                    $DB->Rollback();
                                    array_push($messages, '<span class="text-danger">No se logro crear servicio usuario - cod: 10</span>');
                                    $error = true;
                                    goto result;

                                 }
            
                                 # registro de numero
                                 if ($GatewayNumero->PideAsignado($Dominio->gate_cod, 2, 20, $sip_username, $DB) === false) {
                                    
                                    $DB->Rollback();
                                    array_push($messages, '<span class="text-danger">Error: No se pudo aprovisionar N&uacute;mero SOS - cod: 11</span>');
                                    $error = true;
                                    goto result;

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
            
                                 $z--;       // restamos 1 para que se repita nuevamente
                                 $bpid++;    // aumentamos 1
            
                              }
                              
                           }

                           break;
                        case 4:
                           
                           # 4 - Widget

                           $services_desc .= ' Widget | ';

                           if ($OtroProd->inserta(4, $Usuario->busua_cod, $cloud_username, $cloud_password, $DB) === false) {
                              
                              $DB->Rollback();
                              array_push($messages, '<span class="text-danger">Error: No se pudo aprovisionar N&uacute;mero SOS - cod: 12</span>');
                              $error = true;
                              goto result;

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

                           break;

                        case 5:

                           # 5 - Servicio de tracker

                           $services_desc .= ' Tracker | ';

                           $tipo_tracker  = $col_tipo_tracker !== '' ? trim($file[$i][$col_tipo_tracker]) : null;
                           $causa         = $col_causa !== '' ? trim($file[$i][$col_causa]) : null;

                           if ($tipo_tracker === null || $causa === null) {
                              
                              $DB->Rollback();
                              array_push($messages, '<span class="text-danger">No se logro crear servicio de tracker - cod: 13</span>');
                              $error = true;
                              goto result;

                           }

                           if ($Tracker->insert($Usuario->busua_cod, $tipo_tracker, $causa, $DB) === false) {
                              
                              $DB->Rollback();
                              array_push($messages, '<span class="text-danger">No se logro crear servicio de tracker - cod: 14</span>');
                              $error = true;
                              goto result;

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

                           break;

                        case 6:

                           # 6 - Web RTC

                           $services_desc .= ' Web RTC | ';

                           $bpid = 1;

                           for ($z = 0; $z < 1; $z++) {

                              $sip_username     = Parameters::generaSipUsername($bpid, strlen($bpid), 3); # como es nuevo parte en 1
                              $sip_display_name = Parameters::generaSipUsername($bpid, strlen($bpid), 3); # como es nuevo parte en 1

                              # tenemos que saber cual sip_username esta disponible dentro del dominio
                              if ($Boton->verificaUserBoton($sip_username, $dom_cod, $DB) === false) {

                                 $sip_password = Parameters::generaPasswordSIP(7); # codigo generado por Sistema

                                 # registra boton
                                 if ($Boton->insert($sip_username, $sip_password, $sip_display_name, $Usuario->busua_cod, $tipo_cod, '', '', '', $DB) === false) {
                                    
                                    $DB->Rollback();
                                    array_push($messages, '<span class="text-danger">No se logro crear servicio usuario - cod: 15</span>');
                                    $error = true;
                                    goto result;

                                 }

                                 # registro de numero
                                 if ($GatewayNumero->PideAsignado($Dominio->gate_cod, 2, 20, $sip_username, $DB) === false) {
                                    
                                    $DB->Rollback();
                                    array_push($messages, '<span class="text-danger">Error: No se pudo aprovisionar N&uacute;mero SOS - cod: 16</span>');
                                    $error = true;
                                    goto result;

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

                              } else {

                                 $z--;       # restamos 1 para que se repita nuevamente
                                 $bpid++;    # aumentamos 1

                              }

                           }

                           break;

                        default:

                           # Default: Otros productos
            
                           # primero verificamos que el tipo de servicio exista
                           if ($TipoBoton->busca($tipo_cod, $DB) === false) {
                              $DB->Rollback();
                              $error = true;
                              array_push($messages, '<span class="text-danger">No se pudo aprovisionar servicio - cod: 17</span>');
                              goto result;
                           }

                           $services_desc .= ' ' . $TipoBoton->tipo . ' | ';
            
                           array_push($messages, '<span class="text-primary">' . $TipoBoton->tipo . '.</span>');
            
                           # registra otro producto
                           if ($OtroProd->inserta($TipoBoton->tipo_cod, $Usuario->busua_cod, $cloud_username, $encriptado, $DB) === false) {
                              $DB->Rollback();
                              $error = true;
                              array_push($messages, '<span class="text-danger">Error: No se pudo aprovisionar servicio Widget - cod: 18</span>');
                              break;
                           }
            
                           array_push($messages, '<span class="text-success">Servicio OK.</span>');
            
                           break;

                     }

                  }

               } else {

                  $services_desc = 'NO';

               }
               
               $desc = 'ADM: USUARIO CREADO | BUSUA_COD: ' . ($Usuario->busua_cod) . ' | NOMBRE: ' . ($nombre) . ' | CLOUD_USERNAME: ' . ($cloud_username) . ' | USER_PHONE: ' .  ($user_phone) . ' | EMAIL: ' .  ($email);
               $desc .= ' | SERVICIOS: ' . $services_desc;

               $Log->RegistraLinea($desc);

            }

         } else {

            array_push($messages, '<span class="text-danger">Usuario | CLOUD_USERNAME <strong>&quot;' . $cloud_username . '&quot;</strong> | ya existe con servicio, Omitido...</span>');

         }

      } else {

         array_push($messages, '<span class="text-danger">Usuario | CLOUD_USERNAME <strong>&quot;' . $cloud_username . '&quot;</strong> | contiene un "@" , Omitido...</span>');

      }

   }

   if ($error === true) {
      goto result;
   }

   array_push($messages, '<span class="text-success">Usuarios creados - TOTAL: ' . ($i-1) . '</span>');

   $DB->Commit();

result:
   if ($error === true) {

      array_push($messages, '<span class="text-danger">ROLLBACK REALIZADO</span>');
      
      $data = array( 'status'    => 'error',
                     'message'   => $messages );
      echo json_encode($data);

   } else {

      $data = array( 'status'          => 'success',
                     'message'         => $messages,
                     'status_notify'   => $status_notify,
                     'data_notify'     => $data_notify  );
      echo json_encode($data);
      
   }

   $DB->Logoff();

?>