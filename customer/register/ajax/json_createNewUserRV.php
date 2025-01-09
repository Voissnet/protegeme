<?
   require_once 'MOD_Error.php';
   require_once 'MOD_ReCaptcha.php';
   require_once 'BUsuarioRV.php';
   require_once 'BConexion.php';
   require_once 'SEmail.php';

   $DB        = new BConexion();
   $UsuarioRV = new BUsuarioRV();

   $message = '';
   $error = false;

   $data       = json_decode(file_get_contents('php://input'), true);
   
   if (!isset($data)) {
      $message = MOD_Error::ErrorCode('PBE_117');
      $error = true;
      goto result;
   }

   $captcha =  $_GET['recaptcha'];

   if (!MOD_ReCaptcha::Valida($captcha)) {
      $message = MOD_Error::ErrorCode('PBE_102');
      $error = true;
      goto result;
   }

   if ($data['password'] !== $data['password_v']) {
      $message = MOD_Error::ErrorCode('PBE_103');
      $error = true;
      goto result;
   }

   $data['enviar_email'] == '1' ? ''  : $data['enviar_email'] = 0;

   $rut_empresa = str_replace('.', '', $data['rut_empresa']);
   $rut = str_replace('.', '', $data['rut']);

   $DB->Begintrans();
   if($UsuarioRV->Inserta($data['username'], $data['password'], $data['nombre'], $data['apellidos'], $data['empresa'], $data['email'], 38, $data['enviar_email'], $data['razon_social'],  $rut_empresa,  $rut,  $data['cargo'],  $data['telefono_celular'],  $data['telefono_fijo'], $data['rub_cod'], $data['med_cod'], $DB) === false) {
      $DB->Rollback();
      $message = MOD_Error::ErrorCode('PBE_104');
      $error = true;
      goto result;
   }

   if ($UsuarioRV->ObtieneNumeroSMS($UsuarioRV->usua_cod, $DB) === false) {
      $DB->Rollback();
      $message = MOD_Error::ErrorCode('PBE_105');
      $error = true;
      goto result;
   }

   // se hace commit antes de mandar el mail
   $DB->Commit();

   if (!SEmail::MailInscripcion($data['username'], $data['nombre'], $data['apellidos'], $data['email'], $UsuarioRV->sesion_check)) {
      $message = MOD_Error::Error('PBE_106');
      $error = true;
      goto result;
   }
   if (!SEmail::notificaUserRV($data, $UsuarioRV->usua_cod)) {
      $message = MOD_Error::Error('PBE_106');
      $error = true;
      goto result;
   }

result:
   if ($error === true) {
      $data = array( 'status'  => 'error',
                     'message' => $message );
      echo json_encode($data);
   } else {
      $data = array( 'status'    => 'success',
                     'message'   => 'OK' );
      echo json_encode($data);
   }
   $DB->Logoff();
?>