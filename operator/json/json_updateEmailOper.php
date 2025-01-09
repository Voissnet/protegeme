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

   $data       = json_decode(file_get_contents('php://input'), true);
   $oper_cod   = isset($data['oper_cod']) ? intval($data['oper_cod']) : false;
   $email      = isset($data['email']) ? $data['email'] : false;
   $password   = isset($data['password']) ? $data['password'] : false;

   require_once 'BLog.php';

   $Log     = new BLog();

   if ($oper_cod !== intval($Operador->oper_cod)) {
      $message = MOD_Error::Error('PBE_117');
      $error = true;
      goto result;
   }

   $p_peppered = hash_hmac('sha256', $password, Parameters::PEPPER);

   if (password_verify($p_peppered, $Operador->password) === false) {
      $message = MOD_Error::ErrorCode('PBE_128');
      $error = true;
      goto result;
   }

   if ($Operador->actualizaEmail($email, $DB) === false) {
      $message = MOD_Error::ErrorCode('PBE_121');
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
      $Log->RegistraLinea('OPER: OPER BP |BUSUA_COD: ' . ($Operador->oper_cod) . ' | REALIZO CAMBIO DE EMAIL | NUEVO EMAIL: ' . $email);

      $data = array( 'status'    => 'success',
                     'message'   => 'Modificaci&oacute;n de email exitosa',
                     'email'     => $email );
      echo json_encode($data);

   }

   $DB->Logoff();
?>