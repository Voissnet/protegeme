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
   $bot_cod    = isset($data['bot_cod']) ? intval($data['bot_cod']) : false;
   $busua_cod  = isset($data['busua_cod']) ? intval($data['busua_cod']) : false;
   $mac        = isset($data['mac']) ? $data['mac'] : false;

   if ($bot_cod === false || $busua_cod === false || $mac === false) {
      $message = 'Error: No se pudo modificar MAC - cod: 01';
      $error   = true;
      goto result;
   }

   // clases
   require_once 'BBoton.php';
   require_once 'BLog.php';

   $Boton   = new BBoton();
   $Log     = new BLog();

   # verifica mac
   if (strlen($mac) !== 0) {
      if ($Boton->verificaMac($mac, $DB) === true) {
         $message = 'Error: MAC en uso - cod: 02';
         $error   = true;
         goto result;
      }
   }
   
   # busca boton
   if ($Boton->busca($bot_cod, $DB) === false) {
      $message = 'Error: No se pudo modificar MAC - cod: 03';
      $error   = true;
      goto result;
   }

   # actualiza mac
   if ($Boton->actualizaMac($mac, $DB) === false) {
      $message = 'Error: No se pudo modificar MAC - cod: 04';
      $error   = true;
      goto result;
   }

result:
   if ($error === true) {

      $data = array( 'status'       => 'error',
                     'message'      => $message );
      echo json_encode($data);

   } else {

      $status_mac = strlen($mac) !== 0 ? 'ACTUALIZADO' : 'ELIMINADO';

      $path_log = Parameters::PATH . '/log/site_adm.log';
      $Log->CreaLogTexto($path_log);
      $Log->RegistraLinea('ADM: MAC SERVICIO DE BOTON ' . ($status_mac) . ' | BOT_COD: ' . ($bot_cod) . ' | TIPO_COD: ' . ($Boton->tipo_cod) . ' | DESC_TIPO: ' . ($Boton->tipo) . ' | MAC NUEVA: ' . ($Boton->mac));

      $data = array( 'status'    => 'success',
                     'message'   => '<span class="text-primary">MAC</span> actualizada',
                     'bot_cod'   => $bot_cod,
                     'mac'       => $mac );
      echo json_encode($data);

   }
$DB->Logoff();
?>