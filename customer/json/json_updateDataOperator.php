<?
   require_once 'Parameters.php';
   require_once 'MOD_Error.php';
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
   
   $message = '';
   $error = false;

   if (!isset($_POST)) {
      $message = MOD_Error::Error('PBE_116') . ' - 00';
      $error = true;
      goto result;
   }

   // variables
   $data       = json_decode(file_get_contents('php://input'), true);
   $oper_cod   = isset($data['oper_cod']) ? intval($data['oper_cod']) : false;
   $username   = isset($data['username']) ? $data['username'] : false;
   $nombre     = isset($data['name']) ? $data['name'] : false;
   $email      = isset($data['email']) ? $data['email'] : false;

   // si alguna variable no llega
   if ($oper_cod === false || $username === false || $nombre === false || $email === false) {
      $message = MOD_Error::Error('PBE_116') . ' - 01';
      $error = true;
      goto result;
   }

   // clases
   require_once 'BOperador.php';
   require_once 'BLog.php';

   $Operador = new BOperador();
   $Log      = new BLog();

   if ($Operador->busca($oper_cod, $DB) === false) {
      $message = MOD_Error::ErrorCode('PBE_116') . ' - 02';
      $error = true;
      goto result;
   }

   // sirve para ver actualizamos la notificacion
   $status_noti = false;

   if ($Operador->username !== $username) {
      $status_noti = true;
   }

   if ($Operador->email !== $email) {
      $status_noti = true;
   }

   if ($Operador->actualizaDatos($username, $nombre, $email, $DB) === false) {
      $message = MOD_Error::ErrorCode('PBE_116') . ' - 03';
      $error = true;
      goto result;
   }

   if ($status_noti === true) {
      if ($Operador->liberaNotificado($DB) === false) {
         $message = MOD_Error::ErrorCode('PBE_116') . ' - 04';
         $error = true;
         goto result;
      }
   }

result:
   if ($error === true) {

      $data = array( 'status'  => 'error',
                     'message' => $message );
      echo json_encode($data);

   } else {

      $path_log = Parameters::PATH . '/log/site_adm.log';
      $Log->CreaLogTexto($path_log);
      $Log->RegistraLinea('ADM: DATOS DEL OPERADOR ACTUALIZADOS | OPER_COD: ' . ($oper_cod) . ' | NUEVO USERNAME: ' . ($username) . ' | NUEVO NOMBRE: ' . $nombre);

      $data = array( 'status'             => 'success',
                     'message'            => 'Datos operador <span class="text-primary">actualizados</span>',
                     'oper_cod'           => $oper_cod,
                     'username'           => $username,
                     'nombre'             => $nombre,
                     'email'              => $email,
                     'fecha_creacion'     => $Operador->fecha_creacion,
                     'fecha_ultimo_login' => $Operador->fecha_ultimo_login,
                     'dominio_usuario'    => $Operador->dominio_usuario,
                     'fecha_notificacion' => $Operador->fecha_notificacion,
                     'notifica'           => $Operador->notifica,
                     'status_noti'        => $status_noti );
      echo json_encode($data);

   }

   $DB->Logoff();
?>