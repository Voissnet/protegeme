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
   
   $message = '';
   $error = false;

   if (!isset($_GET)) {
      $message = MOD_Error::Error('PBE_117');
      $error = true;
      goto result;
   }

   $DB2     = new BConexion();                // segunda conexion
   $error   = false;
   $message = '';

   $group_cod  = isset($_GET['group_cod']) ? $_GET['group_cod'] : false;
   $dom_cod    = isset($_GET['dom_cod']) ? $_GET['dom_cod'] : false;

   if ($group_cod === false || $dom_cod === false) {
      $message = 'Error: No se registran datos - cod: 001';
      $error = true;
      goto result;
   }

   require_once 'BDominio.php';
   require_once 'BGrupo.php';
   require_once 'BUsuario.php';
   require_once 'BEstado.php';
   require_once 'BBoton.php';
   require_once 'BTracker.php';
   require_once 'BTipoServicio.php';

   $Dominio       = new BDominio();
   $Grupo         = new BGrupo();
   $Grupo2        = new BGrupo();
   $Usuario       = new BUsuario();
   $Estado        = new BEstado();
   $EstadoB       = new BEstado();
   $EstadoTR      = new BEstado();
   $Boton         = new BBoton();
   $Tracker       = new BTracker();
   $TipoServicio  = new BTipoServicio();

   if ($group_cod != '0') {
      if ($Grupo->busca($group_cod, $DB) === true) {                                 // traemos el grupo del dominio
         $dom_cod = $Grupo->dom_cod;
      } else {
         $message = 'Error: Grupo no encontrado - cod: 002';
         $error   = true;
         goto result;
      }
   }

   if ($Dominio->busca($dom_cod, $DB) === false) {                                        // busca el dominio
      $message = 'Error: Dominio no encontrado - cod: 003';
      $error = true;
      goto result;
   }

result:
   if ($error === true) {
      
      $data = array( 'status'    => 'error',
                     'message'   => $message
      );
      echo json_encode($data);

   } else {

      $status_users = 'no';

      if ($group_cod == '0') {
         $stat = $Usuario->buscaUsuariosDom($Dominio->dom_cod, $DB);                         // buscamos los usuarios de los dominios
      } else {
         $stat = $Usuario->buscaUsuariosGroup($Grupo->group_cod, $DB);                       // buscamos los usuarios de los dominios
      }

      $i = 0;
      $users;
      $dataBP;
      $dataTR;

      if ($stat === true) {

         while ($stat) {
            
            $statusBoton = false;
            $statusTracker = false;

            $Estado->busca($Usuario->esta_cod, $DB2);                                        // Busca el estado

            if($Boton->BuscaBoton($Usuario->busua_cod, $DB2) === true) {                      // trae datos de un usuario especifico Boton
               
               $EstadoB->busca($Boton->esta_cod, $DB2);                                      // trae el estado del boton
               
               $dataBP = [                                                                   // registramos los datos
                  'bot_cod'            => $Boton->bot_cod,
                  'sip_username'       => $Boton->sip_username,
                  'sip_password'       => $Boton->sip_password,
                  'sip_display_name'   => $Boton->sip_display_name,
                  'esta_codB'          => $Boton->esta_cod,
                  'estadoB'            => $EstadoB->estado,
                  'tipo_codBP'         => $Boton->tipo_cod,
                  'localizacion'       => $Boton->localizacion
               ];

               $statusBoton = true;
               
            } else {

               $dataBP = [];                                                                 // no registramos nada

            }

            if ($Tracker->busca($Usuario->busua_cod, $DB2) === true) {
               
               $EstadoTR->busca($Tracker->esta_cod, $DB2);                             // trae el estado del tracker

               $TipoServicio->busca($Tracker->tipo_cod, $DB2);                         // tipo de tracker

               $dataTR = [                                                             // registramos los datos del tracker
                  'tipo_codTR'      => $Tracker->tipo_cod,
                  'tipoTR'          => $TipoServicio->tipo_servicio,
                  'gps_uid'         => $Tracker->gps_uid,
                  'causa'           => $Tracker->causa,
                  'esta_codTR'      => $Tracker->esta_cod,
                  'estadoTR'        => $EstadoTR->estado
               ];

               $statusTracker = true;

            } else {

               $dataTR = [];

            }

            $nombre_grupo = '';

            if ($group_cod == '0') {
               $Grupo2->busca($Usuario->group_cod, $DB2);
               $nombre_grupo = $Grupo2->nombre;
            }

            $users['data'][$i] = [
               'busua_cod'          => $Usuario->busua_cod,
               'cloud_username'     => $Usuario->cloud_username,
               'cloud_password'     => $Usuario->cloud_password,
               'esta_cod'           => $Usuario->esta_cod,
               'estado'             => $Estado->estado,
               'user_phone'         => $Usuario->user_phone,
               'email'              => $Usuario->email,
               'nombre'             => $Usuario->nombre,
               'fecha_creacion'     => $Usuario->fecha_creacion,
               'notifica'           => $Usuario->notifica,
               'fecha_notificacion' => $Usuario->fecha_notificacion,
               'nombre_grupo'       => $nombre_grupo,
               // parametros boton
               'statusBoton'        => $statusBoton,
               'dataBP'             => $dataBP,
               'statusTracker'      => $statusTracker,
               'dataTR'             => $dataTR
            ];

            $i++;

            $stat = $Usuario->siguiente($DB);
         }

         if (count($users) > 0) {
            $status_users = 'si';
         }

         $data = array( 'status'             => 'success',
                        'message'            => 'Datos encontrados',
                        'dom_cod'            => $Dominio->dom_cod,
                        'dominio'            => $Dominio->dominio,
                        'dom_cod'            => $Dominio->dom_cod,
                        'dominio_usuario'    => $Dominio->dominio_usuario,
                        'group_cod'          => $Grupo->group_cod,
                        'nombre_grupo'       => $Grupo->nombre,
                        'users'              => $users,
                        'status_users'       => $status_users
                     );
         echo json_encode($data);

      } else {

         $data = array( 'status'             => 'success',
                        'message'            => 'Grupo sin usuarios',
                        'dom_cod'            => $Dominio->dom_cod,
                        'dominio'            => $Dominio->dominio,
                        'dom_cod'            => $Dominio->dom_cod,
                        'dominio_usuario'    => $Dominio->dominio_usuario,
                        'group_cod'          => $Grupo->group_cod,
                        'nombre_grupo'       => $Grupo->nombre,
                        'users'              => $status_users === 'no' ? '' : $users,
                        'status_users'       => $status_users
         );
         echo json_encode($data);

      }

   }
   $DB->Logoff();
   $DB2->Logoff();
?>