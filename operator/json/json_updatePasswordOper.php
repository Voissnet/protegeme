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

   if (!isset($_POST)) {
      $message = MOD_Error::Error('PBE_117');
      $error = true;
      goto result;
   }

   $data             = json_decode(file_get_contents('php://input'), true);
   $password_current = isset($data['password_current']) ? $data['password_current'] : false;
   $password         = isset($data['password']) ? $data['password'] : false;
   $password_v       = isset($data['password_v']) ? $data['password_v'] : false;

   require_once 'BLog.php';
   
   $Log     = new BLog();

   $p_peppered = hash_hmac('sha256', $password_current, Parameters::PEPPER);

   if (password_verify($p_peppered, $Operador->password) === false) {
      $message = MOD_Error::ErrorCode('PBE_128');
      $error = true;
      goto result;
   }

   if ($password !== $password_v) {
      $message = MOD_Error::ErrorCode('PBE_118');
      $error = true;
      goto result;
   }

   if ($Operador->modificaPassword($password_v, $DB) === false) {
      $message = MOD_Error::ErrorCode('PBE_124');
      $error = true;
      goto result;
   }

result:
   if ($error === true) {

      $data = array( 'status'  => 'error',
                     'message' => $message );
      echo json_encode($data);

   } else {

      $path_log = Parameters::PATH . '/log/site_adm.log';
      $Log->CreaLogTexto($path_log);
      $Log->RegistraLinea('OPER: OPER_COD: ' . ($Operador->oper_cod) . ' | REALIZO CAMBIO DE CONTRASEÑA');

      $data = array( 'status'    => 'success',
                     'message'   => 'Modificaci&oacute;n de contrase&ntilde;a exitosa' );
      echo json_encode($data);

   }

   $DB->Logoff();
?>