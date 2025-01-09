<?
   require_once 'Parameters.php';
   require_once 'MOD_Error.php';
   require_once 'MOD_ReCaptcha.php';
   require_once 'BConexion.php';
   require_once 'BUsuarioRV.php';
   require_once 'BHistoriaCambiaClave.php';
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
   $username = isset($data['username']) ? $data['username'] : false;
   $email   = isset($data['email']) ? $data['email'] : false;

   if ($username === false || $email === false) {
      $message = MOD_Error::ErrorCode('PBE_116');
      $cod = 'PBE_116';
      $error = true;
      goto result;
   }

   if ($UsuarioRV->BuscaUsernameBP($username, $DB) === false) {
      $message = MOD_Error::ErrorCode('PBE_113');
      $cod = 'PBE_113';
      $error = true;
      goto result;
   }

   if ($UsuarioRV->email !== strtoupper(trim($email))) {
      $message = MOD_Error::ErrorCode('PBE_114');
      $cod = 'PBE_114';
      $error = true;
      goto result;
   }

   /* genera token para recuperar clave */
   $HistoriaCambiaClave = new BHistoriaCambiaClave();
   $HistoriaCambiaClave->GeneraTokenCambioClave($UsuarioRV->usua_cod, $DB);

   if ( $HistoriaCambiaClave->token != '' && $HistoriaCambiaClave->iv != '' && $HistoriaCambiaClave->uc_crypt != '') {
      if (SEmail::MailRecuperaPassword($UsuarioRV->username, $UsuarioRV->nombre, $UsuarioRV->apellidos, $UsuarioRV->email, $HistoriaCambiaClave->token, $HistoriaCambiaClave->iv, $HistoriaCambiaClave->uc_crypt) === false) {
         $message = MOD_Error::ErrorCode('PBE_115');
         $cod = 'PBE_115';
         $error = true;
         goto result;
      }
   } else {
      $message = MOD_Error::ErrorCode('PBE_116');
      $cod = 'PBE_116';
      $error = true;
      goto result;
   }

result:
   if ($error === true) {

      $data = array( 'status'    => 'error',
                     'cod'       => $cod,
                     'message'   => $message
                  );
      echo json_encode($data);

   } else {

      $data = array( 'status'    => 'success',
                     'message'   => 'Usuario encontrado',
                     'email'     => $email
                  );
      echo json_encode($data);
      
   }

   $DB->Logoff();
?>