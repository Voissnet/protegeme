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

   if (!isset($_GET)) {
      $message = MOD_Error::Error('PBE_117');
      $error = true;
      goto result;
   }

   $busua_cod = isset($_GET['busua_cod']) ? $_GET['busua_cod'] : false;          // identificador del usuario

   if ($busua_cod === false) {
      $message = MOD_Error::Error('PBE_117');
      $error = true;
      goto result;
   }

   require_once 'BUsuario.php';
   require_once 'BTracker.php';

   $Usuario = new BUsuario();
   $Tracker = new BTracker();

   if ($Usuario->buscaUser($busua_cod, $DB) === false) {
      $message = 'Usuario sin servicios (Tracker)';
      $error = true;
      goto result;
   }

   if ($Tracker->busca($Usuario->busua_cod, $DB) === false) {
      $message = 'Usuario sin servicios (Tracker)';
      $error = true;
      goto result;
   }

   if (intval($Tracker->esta_cod) === 3) {
      $error = true;
      $mensaje = 'Usuario sin servicios (Tracker)';
      goto result;
   }

result:
   if ($error === true) {

      $data = array( 'status'  => 'err',
                     'message' => $message );
      echo json_encode($data);

   } else {

      $data = array( 'status'                => 'success',
                     'message'               => 'Datos encontrados',
                     'busua_cod'             => $Tracker->busua_cod,
                     'tipo_cod'              => $Tracker->tipo_cod,
                     'gps_uid'               => $Tracker->gps_uid,
                     'causa'                 => $Tracker->causa,
                     'esta_cod'              => $Tracker->esta_cod,
                     'estado'                => $Tracker->estado,
                     'tipo_cod'              => $Tracker->tipo_cod
      );
      echo json_encode($data);

   }

   $DB->Logoff();
?>