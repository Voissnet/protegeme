<?
   require_once 'Parameters.php';
   require_once 'MOD_Error.php';
   require_once 'BConexion.php';
   require_once 'BUsuarioRV.php';

   $UsuarioRV  = new BUsuarioRV();
   $DB         = new BConexion();
   
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

   $message = '';
   $error = false;
   $DB2 = new BConexion();

   if (!isset($_POST)) {
      $message = MOD_Error::ErrorCode('PBE_116');
      $error = true;
      goto result;
   }

   $data             = json_decode(file_get_contents('php://input'), true);
   $dom_cod          = isset($data['dom_cod']) ? $data['dom_cod'] : false;
   $group_cod        = isset($data['group_cod']) ? $data['group_cod'] : false;
   $busua_cod        = isset($data['busua_cod']) ? $data['busua_cod'] : false;
   $cloud_username   = isset($data['cloud_username']) ? $data['cloud_username'] : false;
   $user_phone       = isset($data['user_phone']) ? $data['user_phone'] : false;
   $email            = isset($data['email']) ? $data['email'] : false;
   $nombre           = isset($data['nombre']) ? $data['nombre'] : false;

   if ($dom_cod === false || $group_cod === false || $busua_cod === false || $cloud_username === false || $user_phone === false || $email === false || $nombre === false) {
      $message = MOD_Error::ErrorCode('PBE_116');
      $error = true;
      goto result;
   }

   require_once 'BDominio.php';
   require_once 'BGrupo.php';
   require_once 'BUsuario.php';
   require_once 'BLog.php';

   $Dominio    = new BDominio();
   $Grupo      = new BGrupo();
   $Usuario    = new BUsuario();
   $Usuario2   = new BUsuario();
   $Log        = new BLog();

   $status_noti   = false;

   if ($Dominio->busca($dom_cod, $DB) === false) {
      $message = 'Dominio no encontrado';
      $error = true;
      goto result;
   }

   if ($Grupo->verificaGroup($Dominio->dom_cod, $group_cod, $DB) === false) {
      $message = 'Grupo no encontrado';
      $error = true;
      goto result;
   }

   if ($Usuario->verificaGrupo($busua_cod, $Grupo->group_cod, $DB) === false) {
      $message = 'Grupo no encontrado';
      $error = true;
      goto result;
   }

   // busca cloud_username en la segunda conexion
   if ($Usuario2->verificaCloudUserDom($cloud_username, $dom_cod, $DB2) === true) {
      // 1. No se puede repetir el cloud_username en un mismo grupo
      // 2. si el cloud_username es el mismo usuario, no se detiene la ejecucion
      if ($Usuario->busua_cod !== $Usuario2->busua_cod) {
         $message = 'Cloud username ya existe en su dominio';
         $error = true;
         goto result;
      }
   }

   if ($Usuario->cloud_username != $cloud_username) {
      $status_noti = true;
   }

   if ($Usuario->email != $email) {
      $status_noti = true;
   }

   if ($Usuario->actualiza($cloud_username, $user_phone, $email, $nombre, $DB) === false) {
      $message = 'No se pudo actualizar los datos';
      $error = true;
      goto result;
   }

   if ($status_noti === true) {

      $Usuario->LiberaNotifica($busua_cod, $DB);

   }

   //Parameters::apiAudio(str_replace(' ', '+', $nombre));

result:
   if ($error === true) {

      $data = array( 'status'  => 'error',
                     'message' => $message );
      echo json_encode($data);

   } else {

      $path_log = Parameters::PATH . '/log/site_adm.log';
      $Log->CreaLogTexto($path_log);
      $Log->RegistraLinea('ADM: DATOS DEL USUARIO ACTUALIZADOS | DOM_COD: ' . ($dom_cod) . ' | GROUP_COD: ' . ($group_cod) . ' | BUSUA_COD: ' . ($busua_cod) . ' | NOMBRE: ' . ($nombre) . ' | CLOUD_USERNAME: ' . ($cloud_username) . ' | USER_PHONE: ' . ($user_phone) . ' | EMAIL: ' . ($email));

      $data = array( 'status'             => 'success',
                     'message'            => 'Datos del usuario actualizados',
                     'busua_cod'          => $Usuario->busua_cod,
                     'group_cod'          => $Grupo->group_cod,
                     'dominio_usuario'    => $Dominio->dominio_usuario,
                     'nombre'             => $nombre,
                     'cloud_username'     => $cloud_username,
                     'email'              => $email,
                     'user_phone'         => $Usuario->user_phone,
                     'fecha_creacion'     => $Usuario->fecha_creacion,
                     'notifica'           => $Usuario->notifica,
                     'fecha_notificacion' => $Usuario->fecha_notificacion,
                     'status_noti'        => $status_noti );
      echo json_encode($data);

   }

   $DB->Logoff();
   $DB2->Logoff();
?>