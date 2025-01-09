<?
   require_once 'Parameters.php';
   require_once 'BConexion.php';
   require_once 'MOD_Error.php';

   $DB = new BConexion();

   $message = '';
   $error = false;

   if (!isset($_POST)) {
      $message = MOD_Error::ErrorCode('PBE_117');
      $error = true;
      goto result;
   }

   $data       = json_decode(file_get_contents('php://input'), true);
   $busua_cod  = isset($data['busua_cod']) ? intval($data['busua_cod']) : false;
   $email      = isset($data['email']) ? $data['email'] : false;
   $password   = isset($data['password']) ? $data['password'] : false;

   require_once 'BUsuario.php';
   require_once 'BLog.php';

   $Usuario    = new BUsuario();
   $Log        = new BLog();

   if ($Usuario->buscaUser($busua_cod, $DB) === false) {
      $message = MOD_Error::ErrorCode('PBE_117');
      $error = true;
      goto result;
   }

   $p_peppered = hash_hmac('sha256', $password, Parameters::PEPPER);

   if (password_verify($p_peppered, $Usuario->cloud_password) === false) {
      $message = MOD_Error::ErrorCode('PBE_121');
      $error = true;
      goto result;
   }

   if ($Usuario->actualizaEmail($Usuario->busua_cod, $email, $DB) === false) {
      $message = MOD_Error::ErrorCode('PBE_121');
      $error = true;
      goto result;
   }

result:
   if ($error === true) {

      $data = array( 'status'    => 'error',
                     'message'   => $message );
      echo json_encode($data);

   } else {

      $path_log = Parameters::PATH . '/log/site_adm.log';
      $Log->CreaLogTexto($path_log);
      $Log->RegistraLinea('WEB USER: USUARIO BP |BUSUA_COD: ' . ($Usuario->busua_cod) . ' | REALIZO CAMBIO DE EMAIL | NUEVO EMAIL: ' . ($Usuario->email));

      $data = array( 'status'    => 'success',
                     'message'   => 'Modificaci&oacute;n de email exitosa',
                     'email'     => $Usuario->email );
      echo json_encode($data);

   }
   $DB->Logoff();
?>