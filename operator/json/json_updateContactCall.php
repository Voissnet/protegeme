<?
   require_once 'MOD_Error.php';
   require_once 'Parameters.php';
   require_once 'BConexion.php';
   require_once 'BOperador.php';

   $Operador   = new BOperador();
   $DB         = new BConexion();

   if ($Operador->sec_session_start_ajax() === false) {
      $DB->Logoff();
      MOD_Error::ErrorJSON('PBE_129');
      exit;
   }

   if ($Operador->VerificaLogin($DB) === false) {
      $DB->Logoff();
      MOD_Error::ErrorJSON('PBE_130');
      exit;
   }
   
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

   if ($busua_cod === false || $numero === false) {
      $message = MOD_Error::ErrorCode('PBE_117');
      $error = true;
      goto result;
   }

   require_once 'BUsuario.php';
   require_once 'BContactosLlamada.php';
   require_once 'BOperadorLog.php';

   $Usuario = new BUsuario();
   $Llamada = new BContactosLlamada();
   $Log     = new BOperadorLog();

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

   if ($Llamada->actualiza($Usuario->busua_cod, $numero, $check === true ? 1 : 2, $DB) === false) {
      $message = MOD_Error::ErrorCode('PBE_116');
      $error = true;
      goto result;
   }

   if ($Llamada->actualizaEscucha($Usuario->busua_cod, $numero, 0, $DB) === false) {
      $message = MOD_Error::ErrorCode('PBE_116');
      $error = true;
      goto result;
   }

result:
   if ($error === true) {

      $data = array( 'status'  => 'error',
                     'message' => $message );
      echo json_encode($data);

   } else {

      $desc = 'LLAMADA PARA CONTACTO | BUSUA_COD: ' . $Usuario->busua_cod . ' | NÚMERO CONTACTO: ' . $numero . ' | LLAMADAS: ' . ($check === true ? 'SI' : 'NO');
      $Log->inserta($Operador->oper_cod, $desc, 'operator/json/json_updateContactCall.php', $DB);
      $data = array( 'status'       => 'success',
                     'message'      => '<span class="fw-medium text-danger">Llamadas de emergencia <span class="text-primary">' . ($check === true ? 'Activado' : 'Desactivado') . '</span></span>',
                     'busua_cod'    => $busua_cod,
                     'numero'       => $numero );
      echo json_encode($data);

   }
   $DB->Logoff();
?>