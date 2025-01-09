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
   $nombre     = isset($data['name']) ? $data['name'] : false;
   $device     = isset($data['device']) ? $data['device'] : false;

   if ($busua_cod === false || $numero === false || $nombre === false) {
      $message = MOD_Error::ErrorCode('PBE_117');
      $error = true;
      goto result;
   }

   require_once 'BUsuario.php';
   require_once 'BContactosLlamada.php';
   require_once 'BLog.php';
   
   $Usuario = new BUsuario();
   $Llamada = new BContactosLlamada();
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

   // actualiza nombre del contacto 
   if ($Llamada->busca($Usuario->busua_cod, $numero, $DB) === true) {
      $DB->BeginTrans();
      if ($Llamada->actualizaNombre($Usuario->busua_cod, $Llamada->numero, $nombre, $DB) === false) {
         $DB->Rollback();
         $message = MOD_Error::ErrorCode('PBE_116');
         $error = true;
         goto result;
      }
   }

   $path_log = Parameters::PATH . '/log/site_adm.log';
   $Log->CreaLogTexto($path_log);
   $Log->RegistraLinea(($device === 'APP' ? 'APP' : 'WEB') . ': NOMBRE CONTACTO MODIFICADO | USUA_COD: ' . ($Usuario->busua_cod) . ' | NÚMERO CONTACTO: ' . ('56' . $numero) . ' | NUEVO NOMBRE CONTACTO: ' . (strlen($nombre) === 0 ? 'NO REGISTRA' : $nombre));

result:
   if ($error === true) {

      $data = array( 'status'  => 'error',
                     'message' => $message );
      echo json_encode($data);

   } else {

      $DB->Commit();
      $data = array( 'status'       => 'success',
                     'message'      => '<span class="fw-medium text-danger">Nombre contacto <span class="text-primary">Actualizado</span></span>',
                     'busua_cod'    => $busua_cod,
                     'numero'       => $numero );
      echo json_encode($data);

   }
   $DB->Logoff();
?>