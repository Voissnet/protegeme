<?
   require_once 'Parameters.php';
   require_once 'MOD_Error.php';
   require_once 'BConexion.php';
   require_once 'MOD_ReCaptcha.php';

   $DB         = new BConexion();

   $message    = '';
   $cod        = '';
   $error      = false;

   if (!isset($_POST)) {
      $message = MOD_Error::ErrorCode('PBE_101');
      $cod = 'PBE_101';
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

   $data    = json_decode(file_get_contents('php://input'), true);
   $dom     = isset($data['username']) ? $data['username'] : false;
   $email   = isset($data['email']) ? $data['email'] : false;

   if ($dom === false || $email === false) {
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

   require_once 'BDominio.php';
   require_once 'BOperador.php';
   require_once 'BHistoriaCambiaClaveOperador.php';
   require_once 'SEmail.php';

   $Dominio       = new BDominio();
   $Operador      = new BOperador();
   $HistoriaClave = new BHistoriaCambiaClaveOperador();

   if ($Dominio->verificaDominioUsuario($dominio_usuario, $DB) === false) {
      $message = MOD_Error::ErrorCode('PBE_133');
      $cod = 'PBE_133';
      $error = true;
      goto result;
   }

   if ($Operador->BuscaUsername($username, $Dominio->dom_cod, $DB) === false) {
      $message = MOD_Error::ErrorCode('PBE_117');
      $cod = 'PBE_117';
      $error = true;
      goto result;
   }

   if ($Operador->email !== strtoupper(trim($email))) {
      $message = MOD_Error::ErrorCode('PBE_114');
      $cod = 'PBE_114';
      $error = true;
      goto result;
   }

   /* genera token para recuperar clave */
   $HistoriaClave->GeneraTokenCambioClave($Operador->oper_cod, $DB);

   if ($HistoriaClave->token != '' && $HistoriaClave->iv != '' && $HistoriaClave->uc_crypt != '') {
      if (SEmail::MailRecuperaPasswordOper($dom, $Operador->nombre, $Operador->email, $HistoriaClave->token, $HistoriaClave->iv, $HistoriaClave->uc_crypt) === false) {
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
                     'message'   => 'Operador encontrado',
                     'email'     => $email
                  );
      echo json_encode($data);
      
   }

   $DB->Logoff();
?>