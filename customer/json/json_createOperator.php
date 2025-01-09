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

   $DB2        = new BConexion();
   $error      = false;
   $messages   = [];

   array_push($messages, 'Validando datos');

   if (!isset($_POST)) {
      $messages = MOD_Error::ErrorCode('PBE_116') . ' - 00';
      $error = true;
      goto result;
   }

   $data       = json_decode(file_get_contents('php://input'), true);
   $dom_cod    = isset($data['dom_cod']) ? intval($data['dom_cod']) : false;
   $nombre     = isset($data['nombre']) ? $data['nombre'] : false;
   $username   = isset($data['username']) ? $data['username'] : false;
   $password   = isset($data['password']) ? $data['password'] : false;
   $email      = isset($data['email']) ? $data['email'] : false;
   $notifica   = isset($data['check_noti']) ? $data['check_noti'] : false;

   // CLASES
   require_once 'BDominio.php';
   require_once 'BOperador.php';
   require_once 'BAccion.php';
   require_once 'BOperadorAccion.php';
   require_once 'SEmail.php';
   require_once 'BLog.php';

   $Dominio    = new BDominio();
   $Operador   = new BOperador();
   $Accion     = new BAccion();
   $Permiso    = new BOperadorAccion();
   $Log        = new BLog();
   
   // DOMINIO
   array_push($messages, '<span class="text-dark">Validando dominio.</span>');
   if ($dom_cod === false || is_numeric($dom_cod) === false) {
      $error = true;
      array_push($messages, '<span class="text-danger">Error: Dominio no encontrado - cod: 001.</span>');
      goto result;
   }

   if ($Dominio->busca($dom_cod, $DB) === false) {
      $error = true;
      array_push($messages, '<span class="text-danger">Error: Dominio no registrado - cod: 002.</span>');
      goto result;
   }
   array_push($messages, '<span class="text-success">Dominio <strong>&quot;' . $Dominio->dominio_usuario . '&quot;</strong> OK.</span>');

   // 1. Creacion de un operador
   // 1.1 Validacion de operador - username no se puede repetir
   if ($Operador->verificaUsername($Dominio->dom_cod, $username, $DB) === true) {
      $error = true;
      array_push($messages, '<span class="text-danger">USERNAME <strong>&quot;' . $username . '&quot;</strong> | ya existe en su dominio - cod: 003.</span>');
      goto result;
   }
   
   $p_peppered = hash_hmac('sha256', $password, Parameters::PEPPER);
   $encriptado = password_hash($p_peppered, PASSWORD_BCRYPT);

   // 1.2 Creacion del operador
   $DB->BeginTrans();

   if ($Operador->insert($dom_cod, $username, $encriptado, $nombre, $email, $DB) === false) {
      $DB->Rollback();
      $error = true;
      array_push($messages, '<span class="text-danger">Operador no pudo ser creado - cod: 003.</span>');
      goto result;
   }

   if ($notifica === 'SI') {

      array_push($messages, '<span class="text-primary">Notificando nuevo Operador</span>');

      // actualiza fecha de notificacion
      if ($Operador->actualizaNotifica($DB) === false) {
         $DB->Rollback();
         $error   = true;
         array_push($messages, '<span class="text-danger">Error: Operador no pudo ser creado - cod: 004</span>');
         goto result;
      }

      $name    = $nombre;
      $user    = $username . '@' . $Dominio->dominio_usuario;
      $address = $email;

      SEmail::MailSOSNotificaCredOper($name, $user, $password, $address);

      array_push($messages, '<span class="text-success">Operador notificado</span>');

   }

   // PERMISOS
   $stat = $Accion->primero($DB2);
   
   while ($stat) {

      if ($Permiso->insert($Operador->oper_cod, $Accion->acci_cod, $DB) === false) {
         $DB->Rollback();
         $error   = true;
         array_push($messages, '<span class="text-danger">Error: Operador no pudo ser creado - cod: 005</span>');
         goto result;
      }

      $stat = $Accion->siguiente($DB2);
   }
   array_push($messages, '<span class="text-success">Operador creado</span>');

   $DB->Commit();

result:
   if ($error === true) {
      $data = array( 'status'    => 'error',
                     'message'   => $messages );
      echo json_encode($data);

   } else {
      
      $path_log = Parameters::PATH . '/log/site_adm.log';
      $Log->CreaLogTexto($path_log);
      $Log->RegistraLinea('ADM: OPERADOR CREADO | DOM_COD: ' . $Dominio->dom_cod . ' | OPER_COD: ' . ($Operador->oper_cod) . ' | NOTIFICA: ' . ($notifica) . ' | NOMBRE: ' . ($nombre) . ' | USERNAME: ' . ($username) . ' | EMAIL: ' .  ($email));

      $data = array( 'status'             => 'success',
                     'message'            => $messages,
                     'dom_cod'            => $Dominio->dom_cod,
                     'dominio_usuario'    => $Dominio->dominio_usuario,
                     'oper_cod'           => $Operador->oper_cod,
                     'username'           => $Operador->username,
                     'nombre'             => $Operador->nombre,
                     'esta_cod'           => '1',
                     'estado'             => 'Activo',
                     'email'              => $Operador->email,
                     'fecha_creacion'     => $Operador->fecha_creacion,
                     'notifica'           => $notifica === 'SI' ? $Operador->notifica : '0',
                     'fecha_notificacion' => $notifica === 'SI' ? $Operador->fecha_notificacion : null );
      echo json_encode($data);

   }

   $DB->Logoff();
   $DB2->Logoff();
?>