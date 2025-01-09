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

   $data          = json_decode(file_get_contents('php://input'), true);
   $bot_cod       = isset($data['bot_cod']) ? intval($data['bot_cod']) : false;
   $localizacion  = isset($data['localizacion']) ? $data['localizacion'] : false;
   $coordenadas   = Parameters::obtieneCoordenadas($localizacion);

   if ($bot_cod === false || $localizacion === false) {
      $message = 'Error: No se registran datos - cod: 001';
      $error   = true;
      goto result;
   }

   // clases
   require_once 'BBoton.php';
   require_once 'BTipoBoton.php';
   require_once 'BLog.php';

   $Boton   = new BBoton();
   $Tipo    = new BTipoBoton();
   $Log     = new BLog();

   if ($Boton->busca($bot_cod, $DB) === false) {
      $message = 'Error: No se registra servicio - cod: 003';
      $error   = true;
      goto result;
   }

   if ($Boton->actualizaLocalizacion($localizacion, $coordenadas, $DB) === false) {
      $message = 'Error: No se pudo modificar la localización - cod: 004';
      $error   = true;
      goto result;
   }

result:
      if ($error === true) {

         $data = array( 'status'       => 'error',
                        'message'      => $message );
         echo json_encode($data);

      } else {

         $path_log = Parameters::PATH . '/log/site_adm.log';
         $Log->CreaLogTexto($path_log);
         $Log->RegistraLinea('ADM: LOCALIZACION SERVICIO DE BOTON ACTUALIZADO | BOT_COD: ' . ($bot_cod) . ' | TIPO_COD: ' . ($Boton->tipo_cod) . ' | DESC_TIPO: ' . ($Boton->tipo) . ' | LOCALIZACION NUEVA: ' . ($Boton->localizacion) . ' | COORDENADAS: ' . ($Boton->coordenadas));

         $data = array( 'status'       => 'success',
                        'message'      => '<span class="text-primary">Localizacion</span> actualizada',
                        'bot_cod'      => $bot_cod,
                        'localizacion' => $localizacion );
         echo json_encode($data);

      }
   $DB->Logoff();
?>