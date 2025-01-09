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
      $message = MOD_Error::Error('PBE_116');
      $error = true;
      goto result;
   }

   $data       = json_decode(file_get_contents('php://input'), true);
   $busua_cod  = isset($data['busua_cod']) ? intval($data['busua_cod']) : false;
   $tipo_cod   = isset($data['tipo_cod']) ? intval($data['tipo_cod']) : false;
   $causa      = isset($data['causa']) ? $data['causa'] : false;

   if ($busua_cod === false || $tipo_cod === false || $causa === false) {
      $message = MOD_Error::Error('PBE_116');
      $error = true;
      goto result;
   }

   require_once 'BUsuario.php';
   require_once 'BTracker.php';
   require_once 'BTipoServicio.php';
   require_once 'BLog.php';

   $Usuario = new BUsuario();
   $Tracker = new BTracker();
   $Tipo    = new BTipoServicio();
   $Log     = new BLog();

   if ($Usuario->buscaUser($busua_cod, $DB) === false) {
      $message = 'Usuario no existe';
      $error = true;
      goto result;
   }

   if ($Tracker->busca($Usuario->busua_cod, $DB) === false) {
      $message = 'Usuario sin servicio de tracker';
      $error = true;
      goto result;
   }

   $DB->BeginTrans();

   if ($Tracker->actualiza($Tracker->busua_cod, $tipo_cod, $causa, $DB) === false) {
      $message = 'No se logro actualizar los datos del Tracker';
      $error = true;
      goto result;
   }

   $Tipo->busca($tipo_cod, $DB);

   $path_log = Parameters::PATH . '/log/site_adm.log';
   $Log->CreaLogTexto($path_log);
   $Log->RegistraLinea('ADM: DATOS DEL TRACKER ACTUALIZADOS | BUSUA_COD: ' . ($busua_cod) . ' | NUEVO TIPO TRACKER: ' . $tipo_cod . ' | DESC_TIPO: ' . ($Tipo->tipo_servicio) . ' | NUEVA CAUSA: ' . $causa);

result:
   if ($error === true) {

      $data = array( 'status'  => 'error',
                     'message' => $message );
      echo json_encode($data);

   } else {

      $DB->Commit();
      $data = array( 'status'    => 'success',
                     'message'   => 'Datos del dervicio de <strong class="text-primary">Tracker</strong> actualizados',
                     'busua_cod' => $busua_cod,
                     'tipo_cod'  => $tipo_cod,
                     'causa'     => $causa
                  );
      echo json_encode($data);

   }

   $DB->Logoff();
?>