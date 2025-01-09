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

   $data    = json_decode(file_get_contents('php://input'), true);
   $users   = isset($data['users']) ? $data['users'] : false;

   if ($users === false) {
      $error   = true;
      $message = 'Error: No se registran datos - cod: 001';
      goto result;
   }

   require_once 'BUsuario.php';
   require_once 'BGrupo.php';
   require_once 'BDominio.php';
   require_once 'SEmail.php';
   require_once 'BLog.php';

   $Usuario    = new BUsuario();
   $Grupo      = new BGrupo();
   $Dominio    = new BDominio();
   $Log        = new BLog();

   $dataInfo   = [];

   $path_log = Parameters::PATH . '/log/site_adm.log';

   foreach ($users as $key => $busua_cod) {

      // busca usuario
      if ($Usuario->buscaUser($busua_cod, $DB) === false) {
         $error   = true;
         $message = 'Error: No se encuentra usuario - cod: 002';
         goto result;
      }

      // busca grupo
      if ($Grupo->busca($Usuario->group_cod, $DB) === false) {
         $error   = true;
         $message = 'Error: No se grupo del usuario - cod: 003';
         goto result;
      }

      // busca dominio
      if ($Dominio->busca($Grupo->dom_cod, $DB) === false) {
         $error   = true;
         $message = 'Error: No se dominio del usuario - cod: 004';
         goto result;
      }

      $cloud_password = Parameters::generaPasswordSIP(7);
      $p_peppered    = hash_hmac('sha256', $cloud_password, Parameters::PEPPER);
      $encriptado    = password_hash($p_peppered, PASSWORD_BCRYPT);

      // actualiza fecha de notificacion
      if ($Usuario->actualizaNotifica($DB) === false) {
         $error   = true;
         $message = 'Error: No se pudo actualizar password - cod: 005';
         goto result;
      }

      // actualiza cloud_password
      if ($Usuario->actualizaCloudPassword($encriptado, $DB) === false) {
         $error   = true;
         $message = 'Error: No se pudo actualizar password - cod: 006';
         goto result;
      }

      $name    = $Usuario->nombre;
      $user    = $Usuario->cloud_username . '@' . $Dominio->dominio_usuario;
      $address = $Usuario->email;
      
      SEmail::MailSOSNotificaCred($Dominio->dom_cod, $name, $user, $cloud_password, $address, $Dominio->contacto);

      array_push($dataInfo, [
         'busua_cod'          => $busua_cod,
         'fecha_creacion'     => $Usuario->fecha_creacion,
         'notifica'           => $Usuario->notifica,
         'fecha_notificacion' => $Usuario->fecha_notificacion
      ]);

      $Log->CreaLogTexto($path_log);
      $Log->RegistraLinea('ADM: USUARIO NOTIFICADO BUSUA_COD: ' . ($busua_cod));

   }

result:
   if ($error === true) {

      $data = array( 'status'    => 'error',
                     'message'   => $message );
      echo json_encode($data);

   } else {

      $data = array( 'status'    => 'success',
                     'message'   => 'Usuarios <span class="text-primary">Notificados</span>',
                     'dataInfo'  => $dataInfo );
      echo json_encode($data);

   }

   $DB->Logoff();
?>