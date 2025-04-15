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
   $dom     = isset($_POST['username']) ? $_POST['username'] : false;
   $email   = isset($_POST['email']) ? $_POST['email'] : false;

   if ($dom === false || $email === false) {
      $DB->Logoff();
      $data = array( 
         'status'    => false,
         'cod'       => 'PBE_116',
         'message'   => MOD_Error::ErrorCode('PBE_116')
      );
      echo json_encode($data);
      exit();
   }

   # valida que dom contenga la '@'
   if (strpos($dom, '@') === false) {
      $DB->Logoff();
      $data = array( 
         'status'    => false,
         'cod'       => 'PBE_118',
         'message'   => MOD_Error::ErrorCode('PBE_118')
      );
      echo json_encode($data);
      exit;
   }

   $info             = explode('@', $dom);
   $username         = $info[0];
   $dominio_usuario  = $info[1];

   require_once 'BDominio.php';
   require_once 'BGrupo.php';
   require_once 'BUsuario.php';
   require_once 'SEmail.php';

   $Dominio    = new BDominio();
   $Grupo      = new BGrupo();
   $Usuario    = new BUsuario();

   # verifica que exista dominio
   if ($Dominio->verificaDominioUsuario($dominio_usuario, $DB) === false) {
      $DB->Logoff();
      $data = array( 
         'status'    => false,
         'cod'       => 'PBE_132',
         'message'   => MOD_Error::ErrorCode('PBE_132')
      );
      echo json_encode($data);
      exit;
   }

   # dominio debe estar activo
   if (intval($Dominio->esta_cod) !== 1) {
      $DB->Logoff();
      $data = array( 
         'status'    => false,
         'cod'       => 'PBE_133',
         'message'   => MOD_Error::ErrorCode('PBE_133')
      );
      echo json_encode($data);
      exit;
   }

   # busca usuario con su usuario y email
   if ($Usuario->buscaCloudEmail($username, $email, $DB) === false) {
      $DB->Logoff();
      $data = array( 
         'status'    => false,
         'cod'       => 'PBE_126',
         'message'   => MOD_Error::ErrorCode('PBE_126')
      );
      echo json_encode($data);
      exit;
   }

   # busca contact center asociado
   if ($Grupo->busca($Usuario->group_cod, $DB) === false) {
      $DB->Logoff();
      $data = array( 
         'status'    => false,
         'cod'       => 'PBE_126',
         'message'   => MOD_Error::ErrorCode('PBE_126')
      );
      echo json_encode($data);
      exit;
   }

   # contact debe estar activo
   if (intval($Grupo->esta_cod) !== 1) {
      $DB->Logoff();
      $data = array( 
         'status'    => false,
         'cod'       => 'PBE_117',
         'message'   => MOD_Error::ErrorCode('PBE_117')
      );
      echo json_encode($data);
      exit;
   }

   # dominios deben ser iguales
   if (intval($Dominio->dom_cod) !== intval($Grupo->dom_cod)) {
      $DB->Logoff();
      $data = array( 
         'status'    => false,
         'cod'       => 'PBE_126',
         'message'   => MOD_Error::ErrorCode('PBE_126')
      );
      echo json_encode($data);
      exit;
   }

   $DB->Logoff();

   SEmail::resetPasswordUser($Usuario->nombre, $dom, $email, $Usuario->busua_cod, $username, $dominio_usuario);

   $data = array( 'status'    => true,
                  'message'   => 'Correo enviado. Por favor, revise su bandeja de entrada.' );
   echo json_encode($data);

?>