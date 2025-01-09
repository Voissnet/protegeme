<?
   require_once 'BConexion.php';
   require_once 'MOD_Error.php';
   require_once 'MOD_ReCaptcha.php';
   require_once 'BUsuarioRV.php';
   require_once 'BGateway.php';

   $DB         = new BConexion();
   $UsuarioRV  = new BUsuarioRV();
   $Gateway    = new BGateway();

   $message = '';
   $cod = '';
   $error = false;

   if (!isset($_POST)) {
      $message = MOD_Error::ErrorCode('PBE_109');
      $cod = 'PBE_109';
      $error = true;
      goto result;
   }

   $captcha =  $_GET['recaptcha'];

   if (!MOD_ReCaptcha::Valida($captcha)) {
      $message = MOD_Error::ErrorCode('PBE_102');
      $cod = 'PBE_102';
      $error = true;
      goto result;
   }

   $data = json_decode(file_get_contents('php://input'), true);

   $username = isset($data['us']) ? $data['us'] : false;
   $password = isset($data['pw']) ? $data['pw'] : false;

   if ($UsuarioRV->CompruebaUsername($username, $DB) === false) {
      $message = MOD_Error::ErrorCode('PBE_117');
      $cod = 'PBE_117';
      $error = true;
      goto result;
   }

   if ($UsuarioRV->tipo_cliente !== '10') {
      $message = MOD_Error::ErrorCode('PBE_124');
      $cod = 'PBE_124';
      $error = true;
      goto result;
   }

   if ($UsuarioRV->esta_cod === '8') {
      $message = MOD_Error::ErrorCode('PBE_107');
      $cod = 'PBE_107';
      $error = true;
      goto result;
   }

   if ($Gateway->buscaSOS($UsuarioRV->usua_cod, $DB) === false) {
      $message = 'Estimado/a: Su cuenta esta creada, Redvoiss notificar&aacute; a su correo electr&oacute;nico cuando este activo el servicio.';
      $cod = 'PBE_125';
      $error = true;
      goto result;
   }

   $UsuarioRV->sec_session_start();

   if ($UsuarioRV->Login($username, $password, $DB) === FALSE) {
      $message = MOD_Error::ErrorCode('PBE_121');
      $cod = 'PBE_121';
      $error = true;
      goto result;
   }

result:
   if ($error === true) {
      $data = array( 'status'  => 'error',
                     'message' => $message,
                     'cod'     => $cod );
      echo json_encode($data);
   } else {
      $data = array( 'status'    => 'success',
                     'message'   => 'OK' );
      echo json_encode($data);
   }
   $DB->Logoff();
?>