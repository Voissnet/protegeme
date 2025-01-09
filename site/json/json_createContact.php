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
   $call       = isset($data['checkcall']) ? $data['checkcall'] : false;
   $sms        = isset($data['checksms']) ? $data['checksms'] : false;
   $device     = isset($data['device']) ? $data['device'] : false;

   if ($busua_cod === false || $numero === false || $nombre === false) {
      $message = MOD_Error::ErrorCode('PBE_117');
      $error = true;
      goto result;
   }

   require_once 'BUsuarioRV.php';
   require_once 'BGateway.php';
   require_once 'BDominio.php';
   require_once 'BGrupo.php';
   require_once 'BUsuario.php';
   require_once 'BContactosLlamada.php';
   require_once 'BContactosSMS.php';
   require_once 'BLog.php';

   $UsuarioRV  = new BUsuarioRV();
   $Gateway    = new BGateway();
   $Dominio    = new BDominio();
   $Grupo      = new BGrupo();
   $Usuario    = new BUsuario();
   $Llamada    = new BContactosLlamada();
   $SMS        = new BContactosSMS();
   $Log        = new BLog();

   $numero     = '56' . $numero;

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
      $message = 'Contacto ya existe en el registro';
      $error = true;
      goto result;
   }

   if ($SMS->busca($busua_cod, $numero, $DB) === true) {
      $message = 'Contacto ya existe en el registro';
      $error = true;
      goto result;
   }
   
   // Inserta contacto de emergencia
   $DB->BeginTrans();
   if ($Llamada->insert($Usuario->busua_cod, $numero, strlen($nombre) === 0 ? null : $nombre, $call === true ? 1 : 2, $DB) === false) {
      $DB->Rollback();
      $message = MOD_Error::ErrorCode('PBE_116');
      $error = true;
      goto result;
   }

   if ($SMS->insert($Usuario->busua_cod, $numero, $sms === true ? 1 : 2, $DB) === false) {
      $DB->Rollback();
      $message = MOD_Error::ErrorCode('PBE_116');
      $error = true;
      goto result;
   }

   if ($Grupo->busca($Usuario->group_cod, $DB) === false) {
      $DB->Rollback();
      $message = MOD_Error::ErrorCode('PBE_116');
      $error = true;
      goto result;
   }

   if ($Grupo->esta_cod !== '1') {
      $DB->Rollback();
      $message = MOD_Error::ErrorCode('PBE_116');
      $error = true;
      goto result;
   }

   if ($Dominio->busca($Grupo->dom_cod, $DB) === false) {
      $DB->Rollback();
      $message = MOD_Error::ErrorCode('PBE_116');
      $error = true;
      goto result;
   }

   if ($Dominio->esta_cod !== '1') {
      $DB->Rollback();
      $message = MOD_Error::ErrorCode('PBE_116');
      $error = true;
      goto result;
   }

   if ($Gateway->buscaGatewaySOS($Dominio->gate_cod, $DB) === false) {
      $DB->Rollback();
      $message = MOD_Error::ErrorCode('PBE_116');
      $error = true;
      goto result;
   }

   if ($UsuarioRV->Busca($Gateway->usua_cod, $DB) === false) {
      $DB->Rollback();
      $message = MOD_Error::ErrorCode('PBE_116');
      $error = true;
      goto result;
   }

   $path_log = Parameters::PATH . '/log/site_adm.log';
   $Log->CreaLogTexto($path_log);
   $Log->RegistraLinea(($device === 'APP' ? 'APP' : 'WEB') . ': NUEVO CONTACTO CREADO | USUA_COD: ' . ($Usuario->busua_cod) . ' | NÚMERO CONTACTO: ' . $numero . ' | NOMBRE CONTACTO: ' . (strlen($nombre) === 0 ? 'NO REGISTRA' : $nombre) . ' | LLAMADAS: ' . ($call === true ? 'SI' : 'NO') . ' | SMS: ' . ($sms === true ? 'SI' : 'NO'));

result:
   if ($error === true) {

      $data = array( 'status'  => 'error',
                     'message' => $message );
      echo json_encode($data);

   } else {

      $DB->Commit();

      sleep(0.8);

      $mensaje = 'Hola, '. $Usuario->nombre . ' te ha inscrito como contacto para emergencias en Protegeme.
Mas informacion en www.protegeme.cl';
      
      // manda un sms
      file_get_contents('https://micuenta.redvoiss.net/cuenta/productos/sms/url/envio.php?usuario=' . urlencode($UsuarioRV->username) . '&password=' . urlencode($UsuarioRV->password) . '&destino=' . urlencode($numero) . '&mensaje=' . urlencode($mensaje));

      $data = array( 'status'       => 'success',
                     'message'      => '<span class="fw-medium text-danger">Contacto de emergencia <span class="text-primary">Ingresado</span></span>',
                     'busua_cod'    => $busua_cod,
                     'numero'       => $numero,
                     'nombre'       => $nombre );
      echo json_encode($data);

   }
   $DB->Logoff();
?>