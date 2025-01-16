<?
   require_once 'MOD_Error.php';
   require_once 'Parameters.php';
   require_once 'BConexion.php';
   require_once 'BOperador.php';

   $Operador   = new BOperador();
   $DB         = new BConexion();

   if ($Operador->sec_session_start_ajax() === false) {
      $DB->Logoff();
      MOD_Error::ErrorJSON('PBE_129');
      exit;
   }

   if ($Operador->VerificaLogin($DB) === false) {
      $DB->Logoff();
      MOD_Error::ErrorJSON('PBE_130');
      exit;
   }

   $error    = false;
   $mensaje  = [];

   if (!isset($_POST)) {
      $mensaje = MOD_Error::Error('PBE_117');
      $error = true;
      goto result;
   }

   $tipo_cod   = isset($_POST['tipo_cod']) ? intval($_POST['tipo_cod']) : false;
   $busua_cod  = isset($_POST['busua_cod']) ? intval($_POST['busua_cod']) : false;

   # si es 2, es Botón de emergencia SIP - Estático
   if ($tipo_cod === 2) {
      $localizacion  = isset($_POST['localizacion']) ? $_POST['localizacion'] : false;
      $coordenadas   = Parameters::obtieneCoordenadas($localizacion);
      $mac           = isset($_POST['mac']) ? $_POST['mac'] : false;
   }

   # si es 5, es Tracker
   if ($tipo_cod === 5) {
      $tipo_tracker  = isset($_POST['tipo_tracker']) ? intval($_POST['tipo_tracker']) : false;
      $causa         = isset($_POST['causa']) ? $_POST['causa'] : false;
   }

   if ($tipo_cod === false || $busua_cod === false) {
      $error = true;
      $mensaje = 'No se registran datos - cod: 01';
      goto result;
   }

   require_once 'BConjuntoNumero.php';
   require_once 'BDominio.php';
   require_once 'BUsuario.php';
   require_once 'BTipoBoton.php';
   require_once 'BBoton.php';
   require_once 'BTracker.php';
   require_once 'BOtrosProductos.php';
   require_once 'BGatewayNumero.php';
   require_once 'BOperadorLog.php';

   $Dominio    = new BDominio();
   $Usuario    = new BUsuario();
   $TipoBoton  = new BTipoBoton();
   $Boton      = new BBoton();
   $Tracker    = new BTracker();
   $OtrosP     = new BOtrosProductos();
   $Numero     = new BGatewayNumero();
   $Log        = new BOperadorLog();

   # busca usuario
   if ($Usuario->buscaUser($busua_cod, $DB) === false) {
      $error = true;
      $mensaje = 'Usuario no encontrado - cod: 02';
      goto result;
   }

   $esta_cod = intval($Usuario->esta_cod);

   # si esta eliminado no pasa
   if ($esta_cod > 2) {
      $error = true;
      $mensaje = 'Usuario esta con estado de eliminado - cod: 03';
      goto result;
   }

   # verificamos dominio del usuario
   if ($Dominio->buscaDominioUsuario($busua_cod, $DB) === false) {
      $error = true;
      $mensaje = 'Dominio del usuario no encontrado - cod: 04';
      goto result;
   }

   $DB->BeginTrans();

   # TIPO DE SERVICIOS:
   # 1 - Botón de emergencia SIP - Móvil
   # 2 - Botón de emergencia SIP - Estático
   # 3 - Botón de emergencia Estándar
   # 4 - Widget
   # 5 - Tracker
   # 6 - Web RTC
   # Default: Otros productos

   switch ($tipo_cod) {

      case 1:

         # 1 - Botón de emergencia SIP - Móvil

         $bpid = 1;

         # si el boton no existe, creo uno nuevo
         if ($Boton->buscaUserTipo($busua_cod, $tipo_cod, $DB) === false) {

            for ($x = 0; $x < 1; $x++) {

               $sip_username     = Parameters::generaSipUsername($bpid, strlen($bpid), 1); # como es nuevo parte en 1
               $sip_display_name = Parameters::generaSipUsername($bpid, strlen($bpid), 1); # como es nuevo parte en 1

               # Tenemos que saber cual sip_username esta disponible dentro del dominio
               if ($Boton->verificaUserBoton($sip_username, $Dominio->dom_cod, $DB) === false) {

                  $sip_password = Parameters::generaPasswordSIP(7); # codigo generado por Sistema

                  # Registro boton
                  if ($Boton->insert($sip_username, $sip_password, $sip_display_name, $Usuario->busua_cod, $tipo_cod, '', '', '', $DB) === false) {
                     $DB->Rollback();
                     $mensaje = 'No se pudo aprovisionar servicio - cod: 05';
                     $error = true;
                     goto result;
                  }

                  # Registro de numero
                  if ($Numero->PideAsignado($Dominio->gate_cod, 2, 20, $sip_username, $DB) === false) {
                     $DB->Rollback();
                     $mensaje = 'No se pudo aprovisionar n&uacute;mero SOS - cod: 06';
                     $error = true;
                     goto result;
                  }

               } else {

                  $x--;       # restamos 1 para que se repita nuevamente
                  $bpid++;    # aumentamos 1

               }
               
            }

         } else {

            #si el boton ya existe, lo tomo y lo formateo
            for ($x = 0; $x < 1; $x++) {
               
               $sip_username     = Parameters::generaSipUsername($bpid, strlen($bpid), 1); # como es nuevo parte en 1
               $sip_display_name = Parameters::generaSipUsername($bpid, strlen($bpid), 1); # como es nuevo parte en 1

               # Tenemos que saber cual sip_username esta disponible dentro del dominio
               if ($Boton->verificaUserBoton($sip_username, $Dominio->dom_cod, $DB) === false) {

                  $sip_password = Parameters::generaPasswordSIP(7); # codigo generado por Sistema

                  # modifica tipo de boton
                  if ($Boton->actualizaTipo($tipo_cod, 0, $DB) === false) {
                     $DB->Rollback();
                     $mensaje = 'No se pudo aprovisionar servicio - cod: 07';
                     $error = true;
                     goto result;
                  }

                  # modifica los campos SIP
                  if ($Boton->actualiza($sip_username, $sip_password, $sip_display_name, $DB) === false) {
                     $DB->Rollback();
                     $mensaje = 'No se pudo aprovisionar servicio - cod: 08';
                     $error = true;
                     goto result;
                  }

                  # modifica boton
                  if ($Boton->actualizaEstado(1, $DB) === false) {
                     $DB->Rollback();
                     $mensaje = 'No se pudo aprovisionar servicio - cod: 09';
                     $error = true;
                     goto result;
                  }

                  # registro de numero
                  if ($Numero->PideAsignado($Dominio->gate_cod, 2, 20, $sip_username, $DB) === false) {
                     $DB->Rollback();
                     $mensaje = 'No se pudo aprovisionar n&uacute;mero SOS - cod: 10';
                     $error = true;
                     goto result;
                  }

               } else {

                  $x--;       # restamos 1 para que se repita nuevamente
                  $bpid++;    # aumentamos 1

               }

            }

         }
         
         $desc = 'SE AGREGO SERVICIO "Servicio creado Botón de emergencia SIP - Móvil" A USUARIO | BUSUA_COD: ' . ($busua_cod) . ' | TIPO_COD: ' . ($tipo_cod);
         $Log->inserta($Operador->oper_cod, $desc, 'operator/json/json_createService.php', $DB);

         $mensaje = 'Servicio creado Botón de emergencia SIP - Móvil';

         break;

      case 2 :

         # 2 - Botón de emergencia SIP - Estático

         $bpid = 1;

         # validamos mac ingresada primero
         if ($Boton->verificaMac($mac, $DB) === true) {
            $DB->Rollback();
            $mensaje = 'Mac ya existe en el Sistema - cod: 11';
            $error = true;
            goto result;
         }

         # si el boton no existe, creo uno nuevo
         if ($Boton->buscaUserTipo($Usuario->busua_cod, $tipo_cod, $DB) === false) {

            for ($x = 0; $x < 1; $x++) {

               $sip_username     = Parameters::generaSipUsername($bpid, strlen($bpid), 2); # como es nuevo parte en 1
               $sip_display_name = Parameters::generaSipUsername($bpid, strlen($bpid), 2); # como es nuevo parte en 1

               # Tenemos que saber cual sip_username esta disponible dentro del dominio
               if ($Boton->verificaUserBoton($sip_username, $Dominio->dom_cod, $DB) === false) {

                  $sip_password = Parameters::generaPasswordSIP(7); # codigo generado por Sistema

                  # inserto boton
                  if ($Boton->insert($sip_username, $sip_password, $sip_display_name, $Usuario->busua_cod, $tipo_cod, $localizacion, $coordenadas, $mac, $DB) === false) {
                     $DB->Rollback();
                     $mensaje = 'No se pudo aprovisionar servicio - cod: 12';
                     $error = true;
                     goto result;
                  }

                  # registro de numero
                  if ($Numero->PideAsignado($Dominio->gate_cod, 2, 20, $sip_username, $DB) === false) {
                     $DB->Rollback();
                     $mensaje = 'No se pudo aprovisionar n&uacute;mero SOS - cod: 13';
                     $error = true;
                     goto result;
                  }

               } else {

                  $x--;       # restamos 1 para que se repita nuevamente
                  $bpid++;    # aumentamos 1

               }
               
            }

         } else {

            #si el boton ya existe, lo tomo y lo formateo
            for ($x = 0; $x < 1; $x++) {
               
               $sip_username     = Parameters::generaSipUsername($bpid, strlen($bpid), 2); # como es nuevo parte en 1
               $sip_display_name = Parameters::generaSipUsername($bpid, strlen($bpid), 2); # como es nuevo parte en 1

               # Tenemos que saber cual sip_username esta disponible dentro del dominio
               if ($Boton->verificaUserBoton($sip_username, $Dominio->dom_cod, $DB) === false) {

                  $sip_password = Parameters::generaPasswordSIP(7); # codigo generado por Sistema
                  
                  # modifica tipo de boton
                  if ($Boton->actualizaTipo($tipo_cod, 0, $DB) === false) {
                     $DB->Rollback();
                     $mensaje = 'No se pudo aprovisionar servicio - cod: 14';
                     $error = true;
                     goto result;
                  }

                  # modifica los campos SIP
                  if ($Boton->actualiza($sip_username, $sip_password, $sip_display_name, $DB) === false) {
                     $DB->Rollback();
                     $mensaje = 'No se pudo aprovisionar servicio - cod: 15';
                     $error = true;
                     goto result;
                  }

                  # modifica campo MAC
                  if ($Boton->actualizaMac($mac, $DB) === false) {
                     $DB->Rollback();
                     $mensaje = 'No se pudo aprovisionar servicio - cod: 16';
                     $error = true;
                     goto result;
                  }

                  # modifica campo localizacion
                  if ($Boton->actualizaLocalizacion($localizacion, $coordenadas, $DB) === false) {
                     $DB->Rollback();
                     $message = 'Error: No se pudo modificar la localización - cod: 17';
                     $error   = true;
                     goto result;
                  }

                  # modifica estado boton
                  if ($Boton->actualizaEstado(1, $DB) === false) {
                     $DB->Rollback();
                     $mensaje = 'No se pudo aprovisionar servicio - cod: 18';
                     $error = true;
                     goto result;
                  }

                  # registro de numero
                  if ($Numero->PideAsignado($Dominio->gate_cod, 2, 20, $sip_username, $DB) === false) {
                     $DB->Rollback();
                     $mensaje = 'No se pudo aprovisionar n&uacute;mero SOS - cod: 19';
                     $error = true;
                     goto result;
                  }

               } else {

                  $x--;       # restamos 1 para que se repita nuevamente
                  $bpid++;    # aumentamos 1

               }

            }

         }

         $desc = 'SE AGREGO SERVICIO "Servicio creado Botón de emergencia SIP - Estático" A USUARIO | BUSUA_COD: ' . ($busua_cod) . ' | TIPO_COD: ' . ($tipo_cod);
         $Log->inserta($Operador->oper_cod, $desc, 'operator/json/json_createService.php', $DB);

         $mensaje = 'Servicio creado Botón de emergencia SIP - Estático';

         break;

      case 5:

         # 5 - Tracker
         
         if ($Tracker->busca($Usuario->busua_cod, $DB) === false) {

            if ($Tracker->insert($Usuario->busua_cod, $tipo_tracker, $causa, $DB) === false) {
               $DB->Rollback();
               $mensaje = 'No se pudo aprovisionar servicio - cod: 20';
               $error = true;
               goto result;
            }

         } else {

            # modifica campos
            if ($Tracker->actualiza($tipo_tracker, $causa, $DB) === false) {
               $DB->Rollback();
               $mensaje = 'No se pudo aprovisionar servicio - cod: 21';
               $error = true;
               goto result;
            }

            # modifica estado
            if ($Tracker->actualizaEstado(1, $DB) === false) {
               $DB->Rollback();
               $mensaje = 'No se pudo aprovisionar servicio - cod: 22';
               $error = true;
               goto result;
            }

         }

         $desc = 'SE AGREGO SERVICIO "Tracker" A USUARIO | BUSUA_COD: ' . ($busua_cod) . ' | TIPO_COD: ' . ($tipo_tracker);
         $Log->inserta($Operador->oper_cod, $desc, 'operator/json/json_createService.php', $DB);

         break;

      case 6:

         # 6 - Web RTC

         $bpid = 1;

         # si el boton no existe, creo uno nuevo
         if ($Boton->buscaUserTipo($busua_cod, $tipo_cod, $DB) === false) {

            for ($x = 0; $x < 1; $x++) {

               $sip_username     = Parameters::generaSipUsername($bpid, strlen($bpid), 3); # como es nuevo parte en 1
               $sip_display_name = Parameters::generaSipUsername($bpid, strlen($bpid), 3); # como es nuevo parte en 1

               # Tenemos que saber cual sip_username esta disponible dentro del dominio
               if ($Boton->verificaUserBoton($sip_username, $Dominio->dom_cod, $DB) === false) {

                  $sip_password = Parameters::generaPasswordSIP(7); # codigo generado por Sistema

                  # Registro boton
                  if ($Boton->insert($sip_username, $sip_password, $sip_display_name, $Usuario->busua_cod, $tipo_cod, '', '', '', $DB) === false) {
                     $DB->Rollback();
                     $mensaje = 'No se pudo aprovisionar servicio - cod: 23';
                     $error = true;
                     goto result;
                  }

                  # Registro de numero
                  if ($Numero->PideAsignado($Dominio->gate_cod, 2, 20, $sip_username, $DB) === false) {
                     $DB->Rollback();
                     $mensaje = 'No se pudo aprovisionar n&uacute;mero SOS - cod: 24';
                     $error = true;
                     goto result;
                  }

               } else {

                  $x--;       # restamos 1 para que se repita nuevamente
                  $bpid++;    # aumentamos 1

               }
               
            }

         } else {

            #si el boton ya existe, lo tomo y lo formateo
            for ($x = 0; $x < 1; $x++) {
               
               $sip_username     = Parameters::generaSipUsername($bpid, strlen($bpid), 3); # como es nuevo parte en 1
               $sip_display_name = Parameters::generaSipUsername($bpid, strlen($bpid), 3); # como es nuevo parte en 1

               # Tenemos que saber cual sip_username esta disponible dentro del dominio
               if ($Boton->verificaUserBoton($sip_username, $Dominio->dom_cod, $DB) === false) {

                  $sip_password = Parameters::generaPasswordSIP(7); # codigo generado por Sistema

                  # modifica tipo de boton
                  if ($Boton->actualizaTipo($tipo_cod, 0, $DB) === false) {
                     $DB->Rollback();
                     $mensaje = 'No se pudo aprovisionar servicio - cod: 25';
                     $error = true;
                     goto result;
                  }

                  # modifica los campos SIP
                  if ($Boton->actualiza($sip_username, $sip_password, $sip_display_name, $DB) === false) {
                     $DB->Rollback();
                     $mensaje = 'No se pudo aprovisionar servicio - cod: 26';
                     $error = true;
                     goto result;
                  }

                  # modifica boton
                  if ($Boton->actualizaEstado(1, $DB) === false) {
                     $DB->Rollback();
                     $mensaje = 'No se pudo aprovisionar servicio - cod: 27';
                     $error = true;
                     goto result;
                  }

                  # registro de numero
                  if ($Numero->PideAsignado($Dominio->gate_cod, 2, 20, $sip_username, $DB) === false) {
                     $DB->Rollback();
                     $mensaje = 'No se pudo aprovisionar n&uacute;mero SOS - cod: 28';
                     $error = true;
                     goto result;
                  }

               } else {

                  $x--;       # restamos 1 para que se repita nuevamente
                  $bpid++;    # aumentamos 1

               }

            }

         }
         
         $desc = 'SE AGREGO SERVICIO "Web RTC" A USUARIO | BUSUA_COD: ' . ($busua_cod) . ' | TIPO_COD: ' . ($tipo_cod);
         $Log->inserta($Operador->oper_cod, $desc, 'operator/json/json_createService.php', $DB);

         $mensaje = 'Web RTC';

         break;

      default:

         # Otros productos

         # primero verificamos que el tipo de servicio exista
         if ($TipoBoton->busca($tipo_cod, $DB) === false) {
            $DB->Rollback();
            $mensaje = 'No se pudo aprovisionar servicio - cod: 29';
            $error = true;
            goto result;
         }

         # busca el tipo de producto
         if ($OtrosP->buscaTipo($Usuario->busua_cod, $TipoBoton->tipo_cod, $DB) === false) {

            if ($OtrosP->inserta($TipoBoton->tipo_cod, $Usuario->busua_cod, $Usuario->cloud_username, $Usuario->cloud_password, $DB) === false) {
               $DB->Rollback();
               $mensaje = 'No se pudo aprovisionar servicio - cod: 30';
               $error = true;
               goto result;
            }

         } else {

            # actualiza los campos
            if ($OtrosP->actualiza($Usuario->cloud_username, $Usuario->cloud_password, $DB) === false) {
               $DB->Rollback();
               $mensaje = 'No se pudo aprovisionar servicio - cod: 31';
               $error = true;
               goto result;
            }

            # modifica el estado
            if ($OtrosP->actualizaEstado(1, $DB) === false) {
               $DB->Rollback();
               $mensaje = 'No se pudo aprovisionar servicio - cod: 32';
               $error = true;
               goto result;
            }

            # modifica fecha de creacion
            if ($OtrosP->actualizaFechaCreacion($DB) === false) {
               $DB->Rollback();
               $mensaje = 'No se pudo aprovisionar servicio - cod: 33';
               $error = true;
               goto result;
            }

         }

         $desc = 'SE AGREGO SERVICIO "' . ($OtrosP->tipo) . '" A USUARIO | BUSUA_COD: ' . ($busua_cod) . ' | TIPO_COD: ' . ($tipo_cod);
         $Log->inserta($Operador->oper_cod, $desc, 'operator/json/json_createService.php', $DB);

         $mensaje = 'Servicio creado ' . $OtrosP->tipo;

         break;

   }

   if ($error === true) {
      goto result;
   }

   $DB->Commit();

result:
   if ($error === true) {

      $data = array( 'status'    => 'err',
                     'message'   => $mensaje );
      echo json_encode($data);

   } else {

      $data = array( 'status'    => 'success',
                     'message'   => $mensaje );
      echo json_encode($data);

   }

   $DB->Logoff();
?>