<?
   require_once 'Parameters.php';
   require_once 'MOD_Error.php';
   require_once 'MOD_ReCaptcha.php';
   require_once 'BConexion.php';
   require_once 'BUsuarioRV.php';
   require_once 'SEmail.php';

   $DB         = new BConexion();
   $UsuarioRV  = new BUsuarioRV();

   $message = '';
   $cod = '';
   $error = false;

   if (!isset($_POST)) {
      $message = MOD_Error::ErrorCode('PBE_109');
      $cod = 'PBE_109';
      $error = true;
      goto result;
   }

   $captcha = $_GET['recaptcha'];

   if (!MOD_ReCaptcha::Valida($captcha)) {
      $message = MOD_Error::ErrorCode('PBE_102');
      $cod = 'PBE_102';
      $error = true;
      goto result;
   }

   $data = json_decode(file_get_contents('php://input'), true);

   $username = isset($data['us']) ? $data['us'] : false;
   $password = isset($data['pw']) ? $data['pw'] : false;
   $sesion_check = isset($data['sesion_check']) ? $data['sesion_check'] : false;

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

   if ($UsuarioRV->ActivaUsuario($username, $password, $sesion_check, $DB) === false) {
      $message = MOD_Error::ErrorCode('PBE_107');
      $cod = 'PBE_107';
      $error = true;
      goto result;
   }

   if($UsuarioRV->VerificaLogin($DB) === false) {
      $message = MOD_Error::ErrorCode('PBE_108');
      $cod = 'PBE_108';
      $error = true;
      goto result;
   }

   SEmail::notificaActivacionUserRV($UsuarioRV->usua_cod, $UsuarioRV->username, $UsuarioRV->nombre . ' ' . $UsuarioRV->apellidos, $UsuarioRV->empresa, $UsuarioRV->razon_social, $UsuarioRV->email);
   
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