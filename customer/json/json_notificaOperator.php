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
      $message = MOD_Error::Error('PBE_116') . ' 00';
      $error = true;
      goto result;
   }

   $data       = json_decode(file_get_contents('php://input'), true);
   $oper_cod   = isset($data['oper_cod']) ? intval($data['oper_cod']) : false;

   if ($oper_cod === false) {
      $message = MOD_Error::Error('PBE_116') . ' 01';
      $error = true;
      goto result;
   }

   require_once 'BOperador.php';
   require_once 'BDominio.php';
   require_once 'SEmail.php';
   require_once 'BLog.php';

   $Operador   = new BOperador();
   $Dominio    = new BDominio();
   $Log        = new BLog();

   if ($Operador->busca($oper_cod, $DB) === false) {
      $message = MOD_Error::ErrorCode('PBE_116') . ' 01';
      $error = true;
      goto result;
   }

   // busca dominio
   if ($Dominio->busca($Operador->dom_cod, $DB) === false) {
      $message = MOD_Error::ErrorCode('PBE_116') . ' 02';
      $error = true;
      goto result;
   }

   $password   = Parameters::generaPasswordSIP(7);
   $p_peppered = hash_hmac('sha256', $password, Parameters::PEPPER);
   $encriptado = password_hash($p_peppered, PASSWORD_BCRYPT);

   $DB->BeginTrans();

   // actualiza fecha de notificacion
   if ($Operador->actualizaNotifica($DB) === false) {
      $DB->Rollback();
      $message = MOD_Error::ErrorCode('PBE_116') . ' 03';
      $error = true;
      goto result;
   }

   // actualiza password
   if ($Operador->actualizaPassword($encriptado, $DB) === false) {
      $DB->Rollback();
      $message = MOD_Error::ErrorCode('PBE_116') . ' 04';
      $error = true;
      goto result;
   }

   $name    = $Operador->nombre;
   $user    = $Operador->username . '@' . $Dominio->dominio_usuario;
   $address = $Operador->email;
   
   SEmail::MailSOSNotificaCredOper($name, $user, $password, $address);

   $DB->Commit();

result:
   if ($error === true) {

      $data = array( 'status'    => 'success',
                     'message'   => $message );
      echo json_encode($data);

   } else {

      $path_log = Parameters::PATH . '/log/site_adm.log';
      $Log->CreaLogTexto($path_log);
      $Log->RegistraLinea('ADM: OPERADOR NOTIFICADO: ' . ($Operador->oper_cod));

      $data = array( 'status'             => 'success',
                     'message'            => 'Operador <span class="text-primary">Notificado</span>',
                     'oper_cod'           => $Operador->oper_cod,
                     'fecha_creacion'     => $Operador->fecha_creacion,
                     'notifica'           => $Operador->notifica,
                     'fecha_notificacion' => $Operador->fecha_notificacion );
      echo json_encode($data);

   }

   $DB->Logoff();
?>