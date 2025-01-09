<?
   require_once 'Parameters.php';
   require_once 'MOD_Error.php';
   require_once 'BConexion.php';
   require_once 'MOD_ReCaptcha.php';

   $DB         = new BConexion();

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

   // verificar que oper_cod exista
   $iv         = base64_decode(str_replace(['-','_'], ['+','/'], $data['iv']));
   $oper_cod   = openssl_decrypt($data['uc_crypt'], 'aes-256-cbc', 'jdhg567yhjd389kjd45j5j4kmdhnr45k', 0, $iv);
   $token      = $data['token'];

   require_once 'BOperador.php';
   require_once 'BHistoriaCambiaClaveOperador.php';

   $Operador         = new BOperador();
   $HistorialClave   = new BHistoriaCambiaClaveOperador();

   if ($Operador->Busca($oper_cod, $DB) === false) {
      $message = MOD_Error::ErrorCode('PBE_117');
      $cod = 'PBE_117';
      $error = true;
      goto result;
   }

   /* verificar que haya solicitado cambio de clave en las ultimas 24 horas */

   if ($HistorialClave->BuscaValido($oper_cod, $token, $DB ) === false) {
      $message = MOD_Error::ErrorCode('PBE_118');
      $cod = 'PBE_118';
      $error = true;
      goto result;
   }

   /* verificar que datos del link coincidan con BD */
   if ($HistorialClave->iv != $data['iv'] || $HistorialClave->uc_crypt != $data['uc_crypt']) {
      $message = MOD_Error::ErrorCode('PBE_119');
      $cod = 'PBE_119';
      $error = true;
      goto result;
   }
   
   /* verificar estado */   
   if ($HistorialClave->esta_cod != '1') {
      $message = MOD_Error::ErrorCode('PBE_120');
      $cod = 'PBE_120';
      $error = true;
      goto result;
   }

   /* verificar passwords iguales */
   if ($data['password'] != $data['password_v']) {
      $message = MOD_Error::ErrorCode('PBE_121');
      $cod = 'PBE_121';
      $error = true;
      goto result;
   }

   /* modificar password */
   if ($Operador->ModificaPassword($data['password'], $DB) === false) {
      $message = MOD_Error::ErrorCode('PBE_122');
      $cod = 'PBE_122';
      $error = true;
      goto result;
   }

result:
   if ($error === true) {
      $data = array( 'status'    => 'error',
                     'cod'       => $cod,
                     'message'   => $message );
      echo json_encode($data);
   } else {
      $HistorialClave->DesactivaToken($DB);
      $data = array( 'status'    => 'success',
                     'cod'       => $cod,
                     'message'   => $message );
      echo json_encode($data);
   }
   $DB->Logoff();
?>