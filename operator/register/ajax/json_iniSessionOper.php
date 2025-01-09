<?
   require_once 'BConexion.php';
   require_once 'MOD_Error.php';
   require_once 'MOD_ReCaptcha.php';

   $DB         = new BConexion();

   $message = '';
   $cod = '';
   $error = false;

   if (!isset($_POST)) {
      $message = MOD_Error::ErrorCode('PBE_101');
      $cod = 'PBE_101';
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

   $data       = json_decode(file_get_contents('php://input'), true);
   $dom        = isset($data['us']) ? $data['us'] : false;
   $password   = isset($data['pw']) ? $data['pw'] : false;

   if ($dom === false || $password === false) {
      $message = MOD_Error::ErrorCode('PBE_116');
      $cod = 'PBE_116';
      $error = true;
      goto result;
   }

   if (strpos($dom, '@') === false) {
      $message = MOD_Error::ErrorCode('PBE_132');
      $cod = 'PBE_132';
      $error = true;
      goto result;
   }

   $info             = explode('@', $dom);
   $username         = $info[0];
   $dominio_usuario  = $info[1];

   require_once 'BUsuarioRV.php';
   require_once 'BGateway.php';
   require_once 'BDominio.php';
   require_once 'BOperador.php';

   $UsuarioRV  = new BUsuarioRV();
   $Dominio    = new BDominio();
   $Gateway    = new BGateway();
   $Operador   = new BOperador();
   
   if ($Dominio->verificaDominioUsuario(strtolower($dominio_usuario), $DB) === false) {
      $message = MOD_Error::ErrorCode('PBE_133');
      $cod = 'PBE_133';
      $error = true;
      goto result;
   }

   if ($Gateway->verificaSOS($Dominio->gate_cod, $DB) === false) {
      $message = MOD_Error::ErrorCode('PBE_134');
      $cod = 'PBE_134';
      $error = true;
      goto result;
   }

   if ($UsuarioRV->Busca($Gateway->usua_cod, $DB) === false) {
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

   $Operador->sec_session_start();

   if ($Operador->Login($dominio_usuario, $username, $password, $DB) === FALSE) {
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