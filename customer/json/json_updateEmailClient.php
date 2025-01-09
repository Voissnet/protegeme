<?
   require_once 'MOD_Error.php';
   require_once 'Parameters.php';
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
      $message = MOD_Error::Error('PBE_117');
      $error = true;
      goto result;
   }

   $data       = json_decode(file_get_contents('php://input'), true);
   $dom_cod    = isset($data['dom_cod']) ? intval($data['dom_cod']) : false;
   $usua_cod   = isset($data['usua_cod']) ? intval($data['usua_cod']) : false;
   $email      = isset($data['email']) ? $data['email'] : false;
   $password   = isset($data['password']) ? $data['password'] : false;

   require_once 'BDominio.php';
   require_once 'BGateway.php';
   require_once 'BLog.php';

   $Dominio = new BDominio();
   $Gateway = new BGateway();
   $Log     = new BLog();
   
   if ($Dominio->busca($dom_cod, $DB) === false) {
      $message = MOD_Error::Error('PBE_117');
      $error = true;
      goto result;
   }

   if ($Gateway->buscaGatewaySOS($Dominio->gate_cod, $DB) === false) {
      $message = MOD_Error::Error('PBE_117');
      $error = true;
      goto result;
   }

   if ($usua_cod !== intval($Gateway->usua_cod)) {
      $message = MOD_Error::Error('PBE_117');
      $error = true;
      goto result;
   }

   $p_peppered = hash_hmac('sha256', $password, Parameters::PEPPER);

   if (password_verify($p_peppered, $UsuarioRV->password) === false) {
      $message = MOD_Error::ErrorCode('PBE_139');
      $error = true;
      goto result;
   }

   if ($UsuarioRV->modificaEmail($email, $DB) === false) {
      $message = MOD_Error::ErrorCode('PBE_139');
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
      $Log->RegistraLinea('WEB ADM: CLIENTE BP |BUSUA_COD: ' . ($UsuarioRV->usua_cod) . ' | REALIZO CAMBIO DE EMAIL | NUEVO EMAIL: ' . $email);

      $data = array( 'status'    => 'success',
                     'message'   => 'Modificaci&oacute;n de email exitosa',
                     'email'     => $email );
      echo json_encode($data);

   }

   $DB->Logoff();
?>