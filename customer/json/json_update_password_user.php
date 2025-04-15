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
      $DB->Logoff();
      $data = array( 
         'status'    => false,
         'cod'       => 'PBE_101',
         'message'   => MOD_Error::ErrorCode('PBE_101')
      );
      echo json_encode($data);
      exit;
   }

   # captcha
   $captcha = $_GET['recaptcha'];

   if (MOD_ReCaptcha::Valida($captcha) === false) {
      $DB->Logoff();
      $data = array( 
         'status'    => false,
         'cod'       => 'PBE_102',
         'message'   => MOD_Error::ErrorCode('PBE_102')
      );
      echo json_encode($data);
      exit;
   }

   # parametros
   $new_password     = isset($_POST['new_password']) ? $_POST['new_password'] : false;
   $new_password_v   = isset($_POST['new_password_v']) ? $_POST['new_password_v'] : false;
   $busua_cod        = isset($_POST['busua_cod']) ? $_POST['busua_cod'] : false;

   if ($new_password === false || $new_password_v === false || $busua_cod === false) {
      $DB->Logoff();
      $data = array( 
         'status'    => false,
         'cod'       => 'PBE_116',
         'message'   => MOD_Error::ErrorCode('PBE_116')
      );
      echo json_encode($data);
      exit();
   }

   if ($new_password !== $new_password_v) {
      $DB->Logoff();
      $data = array( 
         'status'    => false,
         'cod'       => 'PBE_103',
         'message'   => MOD_Error::ErrorCode('PBE_103')
      );
      echo json_encode($data);
      exit();
   }

   require_once 'BUsuario.php';

   $Usuario    = new BUsuario();

   if ($Usuario->busca($busua_cod, $DB) === false) {
      $DB->Logoff();
      $data = array( 
         'status'    => false,
         'cod'       => 'PBE_108',
         'message'   => MOD_Error::ErrorCode('PBE_108')
      );
      echo json_encode($data);
      exit();
   }

   $p_peppered    = hash_hmac('sha256', $new_password, Parameters::PEPPER);
   $encriptado    = password_hash($p_peppered, PASSWORD_BCRYPT);

   if ($Usuario->actualizaCloudPassword($encriptado, $DB) === false) {
      $DB->Logoff();
      $data = array( 
         'status'    => false,
         'cod'       => 'PBE_122',
         'message'   => MOD_Error::ErrorCode('PBE_122')
      );
      echo json_encode($data);
      exit();
   }

   $data = array( 'status'    => 'success',
                  'message'   => 'Contraseña cambiada con éxito. Serás redirigido al menú de inicio de sesión.' );
   echo json_encode($data);

   $DB->Logoff();

?>