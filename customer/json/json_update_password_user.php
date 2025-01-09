<?
   require_once 'MOD_Error.php';
   require_once 'Parameters.php';
   require_once 'BConexion.php';

   $DB         = new BConexion();
   $message    = '';
   $error      = false;

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
   
   $data             = json_decode(file_get_contents('php://input'), true);
   $new_password     = isset($data['new_password']) ? $data['new_password'] : false;
   $new_password_v   = isset($data['new_password_v']) ? $data['new_password_v'] : false;
   $bu               = isset($data['bu']) ? $data['bu'] : false;

   if ($new_password === false || $new_password_v === false || $bu === false) {
      $message = MOD_Error::ErrorCode('PBE_124');
      $error = true;
      goto result;
   }

   if ($new_password !== $new_password_v) {
      $message = MOD_Error::ErrorCode('PBE_124');
      $error = true;
      goto result;
   }

   require_once 'BUsuario.php';

   $Usuario    = new BUsuario();

   if ($Usuario->busca($bu, $DB) === false) {
      $message = MOD_Error::ErrorCode('PBE_124');
      $error = true;
      goto result;
   }

   $p_peppered    = hash_hmac('sha256', $new_password, Parameters::PEPPER);
   $encriptado    = password_hash($p_peppered, PASSWORD_BCRYPT);

   if ($Usuario->actualizaCloudPassword($encriptado, $DB) === false) {
      $message = MOD_Error::ErrorCode('PBE_124');
      $error = true;
      goto result;
   }

result:
   if ($error === true) {

      $data = array( 'status'    => 'error',
                     'message'   => $message );
      echo json_encode($data);

   } else {

      $data = array( 'status'    => 'success',
                     'message'   => 'Contraseña cambiada' );
      echo json_encode($data);

   }

   $DB->Logoff();

?>