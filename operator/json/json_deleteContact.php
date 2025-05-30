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
   $device     = isset($data['device']) ? $data['device'] : false;

   if ($busua_cod === false || $numero === false) {
      $message = MOD_Error::ErrorCode('PBE_117');
      $error = true;
      goto result;
   }

   require_once 'BUsuario.php';
   require_once 'BContactosLlamada.php';
   require_once 'BContactosSMS.php';
   require_once 'BOperadorLog.php';

   $Usuario = new BUsuario();
   $Llamada = new BContactosLlamada();
   $SMS     = new BContactosSMS();
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

   if ($Llamada->busca($busua_cod, $numero, $DB) === true) {
      if ($Llamada->delete($Usuario->busua_cod, $Llamada->numero, $DB) === false) {
         $message = MOD_Error::ErrorCode('PBE_116');
         $error = true;
         goto result;
      }
   }

   if ($SMS->busca($busua_cod, $numero, $DB) === true) {
      if ($SMS->delete($Usuario->busua_cod, $SMS->numero, $DB) === false) {
         $message = MOD_Error::ErrorCode('PBE_116');
         $error = true;
         goto result;
      }
   }

result:
   if ($error === true) {

      $data = array( 'status'  => 'error',
                     'message' => $message );
      echo json_encode($data);

   } else {
      
      $desc = 'CONTACTO ELIMINADO | USUA_COD: ' . $Usuario->busua_cod . ' | NÚMERO CONTACTO: ' . $numero;
      $Log->inserta($Operador->oper_cod, $desc, 'operator/json/json_deleteContact.php', $DB);

      $data = array( 'status'       => 'success',
                     'message'      => '<span class="fw-medium text-danger">Contacto de emergencia <span class="text-primary">Eliminado</span></span>',
                     'busua_cod'    => $busua_cod,
                     'numero'       => $numero );
      echo json_encode($data);

   }
   $DB->Logoff();
?>