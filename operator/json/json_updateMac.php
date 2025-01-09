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
      $message = MOD_Error::Error('PBE_116');
      $error = true;
      goto result;
   }

   $data          = json_decode(file_get_contents('php://input'), true);                  // datos
   $busua_cod     = isset($data['busua_cod']) ? intval($data['busua_cod']) : false;       // busua_cod
   $mac           = isset($data['mac']) ? $data['mac'] : false;                           // mac

   if ($busua_cod === false || $mac === false) {
      $message = 'Error: No se pudo modificar MAC - cod: 001';
      $error   = true;
      goto result;
   }

   // clases
   require_once 'BUsuario.php';
   require_once 'BBoton.php';
   require_once 'BOperadorLog.php';

   $Usuario = new BUsuario();
   $Boton   = new BBoton();
   $Log     = new BOperadorLog();

   if ($Boton->verificaMac($mac, $DB) === true) {
      $message = 'Error: MAC en uso - cod: 002';
      $error   = true;
      goto result;
   }

   if ($Usuario->buscaUser($busua_cod, $DB) === false) {
      $message = 'Error: No se pudo modificar MAC - cod: 003';
      $error   = true;
      goto result;
   }

   if ($Boton->buscaUserActivo($busua_cod, $DB) === false) {
      $message = 'Error: No se pudo modificar MAC - cod: 004';
      $error   = true;
      goto result;
   }

   if ($Boton->actualizaMac($mac, $DB) === false) {
      $message = 'Error: No se pudo modificar MAC - cod: 005';
      $error   = true;
      goto result;
   }

result:
   if ($error === true) {

      $data = array( 'status'       => 'error',
                     'message'      => $message );
      echo json_encode($data);

   } else {

      $desc = 'MAC DE SERVICIO BP MODIFICADO | BUSUA_COD: ' . $Usuario->busua_cod . ' | ID BP: ' . $Boton->bot_cod . ' | NUEVA MAC: ' . $mac;
      $Log->inserta($Operador->oper_cod, $desc, 'operator/json/json_updateMac.php', $DB);

      $data = array( 'status'       => 'success',
                     'message'      => '<span class="text-primary">MAC</span> actualizada',
                     'busua_cod'    => $busua_cod,
                     'mac'          => $mac );
      echo json_encode($data);

   }
$DB->Logoff();
?>