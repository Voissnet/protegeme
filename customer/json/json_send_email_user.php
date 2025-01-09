<?
   require_once 'MOD_Error.php';
   require_once 'Parameters.php';
   require_once 'BConexion.php';

   $DB         = new BConexion();
   $message    = '';
   $error      = false;

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
   
   $data       = json_decode(file_get_contents('php://input'), true);
   $user       = isset($data['username-reset']) ? $data['username-reset'] : false;
   $email      = isset($data['email-reset']) ? $data['email-reset'] : false;

   if ($user === false || $email === false) {
      $message = MOD_Error::ErrorCode('PBE_124');
      $error = true;
      goto result;
   }

   require_once 'BDominio.php';
   require_once 'BGrupo.php';
   require_once 'BUsuario.php';
   require_once 'SEmail.php';

   $Dominio    = new BDominio();
   $Grupo      = new BGrupo();
   $Usuario    = new BUsuario();

   $data             = explode('@', $user);
   $cloud_username   = $data[0];
   $dominio_usuario  = $data[1];

   // busca dominio usuario
   if ($Dominio->verificaDominioUsuario($dominio_usuario, $DB) === false) {
      $message = MOD_Error::ErrorCode('PBE_117');
      $error = true;
      goto result;
   }

   // dominio debe estar activo
   if ($Dominio->esta_cod !== '1') {
      $message = MOD_Error::ErrorCode('PBE_117');
      $error = true;
      goto result;
   }

   // buscamos email
   if ($Usuario->buscaCloudEmail($cloud_username, $email, $DB) == false) {
      $message = MOD_Error::ErrorCode('PBE_126');
      $error = true;
      goto result;
   }

   // busca grupo
   if ($Grupo->busca($Usuario->group_cod, $DB) === false) {
      $message = MOD_Error::ErrorCode('PBE_126');
      $error = true;
      goto result;
   }

   // grupo debe estar activo
   if ($Grupo->esta_cod !== '1') {
      $message = MOD_Error::ErrorCode('PBE_117');
      $error = true;
      goto result;
   }

   // dominio deben ser iguales
   if ($Dominio->dom_cod !== $Grupo->dom_cod) {
      $message= MOD_Error::ErrorCode('PBE_126');
      $error = true;
      goto result;
   }

result:
   if ($error === true) {

      $data = array( 'status'    => 'error',
                     'message'   => $message );
      echo json_encode($data);

   } else {

      SEmail::resetPasswordUser($Usuario->nombre, $user, $email, $Usuario->busua_cod, $cloud_username, $dominio_usuario);
      $data = array( 'status'    => 'success',
                     'message'   => 'Correo enviado, revisar su bandeja' );
      echo json_encode($data);

   }

   $DB->Logoff();
?>