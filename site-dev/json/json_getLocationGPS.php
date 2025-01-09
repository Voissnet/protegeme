<?
   require_once 'Parameters.php';
   require_once 'MOD_Error.php';

   $message = '';
   $error = false;

   if (!isset($_POST)) {
      $message = MOD_Error::ErrorCode('PBE_117');
      $error = true;
      goto result;
   }

   require_once 'BConexion.php';
   require_once 'BUsuario.php';
   require_once 'BBoton.php';
   require_once 'BPLog.php';

   // identificador del usuario
   $data             = json_decode(file_get_contents('php://input'), true);
   $busua_cod        = isset($data['busua_cod']) ? $data['busua_cod'] : false;
   $usuario          = isset($data['user']) ? $data['user'] : false;
   $cloud_password   = isset($data['pasw']) ? $data['pasw'] : false;
   $lat              = isset($data['lat']) ? $data['lat'] : false;
   $lon              = isset($data['lon']) ? $data['lon'] : false;
   $platform         = isset($data['platform']) ? $data['platform'] : false;
   $version          = isset($data['version']) ? $data['version'] : false;
   $model            = isset($data['model']) ? $data['model'] : false;
   $appbuild         = isset($data['appbuild']) ? $data['appbuild'] : false;

   $parts            = explode('@', $usuario);
   $cloud_username   = trim(strtolower($parts[0]));
   $dominio_usuario  = trim(strtolower($parts[1]));

   $DB      = new BConexion();
   $Usuario = new BUsuario();
   $Boton   = new BBoton();
   $BPLog   = new BPLog();

   if ($Usuario->autenticaUsuario($cloud_username, $cloud_password, $dominio_usuario, $DB) === false) {
      $message = MOD_Error::ErrorCode('PBE_117');
      $error = true;
      goto result;
   }

   if ($Boton->BuscaBoton($Usuario->busua_cod, $DB) === false) {
      $message = MOD_Error::ErrorCode('PBE_117');
      $error = true;
      goto result;
   }

   if ($BPLog->inserta($Usuario->busua_cod, ($lat . ';' . $lon), $platform, $version, $model, $appbuild, $DB) === false) {
      $message = MOD_Error::ErrorCode('PBE_117');
      $error = true;
      goto result;
   }

result:

   if ($error === true) {

      $data = array( 'status'    => 'err',
                     'message'   => 'No registrado' );
      echo json_encode($data);
      
   } else {

      $data = array( 'status'    => 'success',
                     'message'   => 'Registrado' );
      echo json_encode($data);

   }

   $DB->Logoff();
?>