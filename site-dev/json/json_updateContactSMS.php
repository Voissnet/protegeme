<?
   require_once 'Parameters.php';
   require_once 'BConexion.php';
   require_once 'MOD_Error.php';

   $DB = new BConexion();

   $message = '';
   $error = false;

   if (!isset($_POST)) {
      $message = MOD_Error::Error('PBE_117');
      $error = true;
      goto result;
   }

   // identificador del usuario
   $data       = json_decode(file_get_contents('php://input'), true);
   $busua_cod  = isset($data['bu']) ? intval($data['bu']) : false;
   $numero     = isset($data['num']) ? intval($data['num']) : false;
   $check      = $data['check'];
   $device     = isset($data['device']) ? $data['device'] : false;

   if ($busua_cod === false || $numero === false) {
      $message = MOD_Error::ErrorCode('PBE_117');
      $error = true;
      goto result;
   }

   require_once 'BUsuario.php';
   require_once 'BContactosSMS.php';
   require_once 'BLog.php';

   $Usuario = new BUsuario();
   $SMS     = new BContactosSMS();
   $Log     = new BLog();

   // verificar que exista el usuario
   if ($Usuario->busca($busua_cod, $DB) === false) {
      $message = MOD_Error::ErrorCode('PBE_117');
      $error = true;
      goto result;
   }

   if ($Usuario->esta_cod === '3') {
      $message = MOD_Error::ErrorCode('PBE_120');
      $error = true;
      goto result;
   }

   $DB->BeginTrans();
   if ($SMS->actualiza($Usuario->busua_cod, $numero, $check === true ? 1 : 2, $DB) === false) {
      $DB->Rollback();
      $message = MOD_Error::ErrorCode('PBE_116');
      $error = true;
      goto result;
   }

   $path_log = Parameters::PATH . '/log/site_adm.log';
   $Log->CreaLogTexto($path_log);
   $Log->RegistraLinea(($device === 'APP' ? 'APP' : 'WEB') . ': SMS PARA CONTACTO | USUA_COD: ' . ($Usuario->busua_cod) . ' | NÚMERO CONTACTO: ' . ($numero) . ' | SMS: ' . ($check === true ? 'SI' : 'NO'));

result:
   if ($error === true) {

      $data = array( 'status'  => 'error',
                     'message' => $message );
      echo json_encode($data);

   } else {

      $DB->Commit();
      $data = array( 'status'       => 'success',
                     'message'      => '<span class="fw-medium text-danger">SMS de emergencia <span class="text-primary">' . ($check === true ? 'Activado' : 'Desactivado') . '</span></span>',
                     'busua_cod'    => $busua_cod,
                     'numero'       => $numero );
      echo json_encode($data);

   }
   $DB->Logoff();
?>