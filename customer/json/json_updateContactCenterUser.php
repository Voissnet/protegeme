<?
   require_once 'Parameters.php';
   require_once 'MOD_Error.php';
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

   $data             = json_decode(file_get_contents('php://input'), true);
   $busua_cod  = isset($data['busua_cod']) ? intval($data['busua_cod']) : false;
   $group_cod  = isset($data['group_cod']) ? intval($data['group_cod']) : false;

   if ($busua_cod === false || $group_cod === false) {
      $message = MOD_Error::Error('PBE_116');
      $error = true;
      goto result;
   }

   require_once 'BUsuario.php';
   require_once 'BGrupo.php';
   require_once 'BLog.php';

   $Usuario = new BUsuario();
   $Grupo   = new BGrupo();
   $Log     = new BLog();

   if ($Usuario->buscaUser($busua_cod, $DB) === false) {
      $message = 'Usuario no encontrado';
      $error = true;
      goto result;
   }

   if ($Grupo->busca($group_cod, $DB) === false) {
      $message = 'Grupo no encontrado';
      $error = true;
      goto result;
   }

   if ($Usuario->actualizaGrupo($Grupo->group_cod, $DB) === false) {
      $message = 'Grupo no encontrado';
      $error = true;
      goto result;
   }

result:
   if ($error === true) {

      $data = array( 'status'  => 'error',
                     'message' => $message );
      echo json_encode($data);

   } else {

      $path_log = Parameters::PATH . '/log/site_adm.log';
      $Log->CreaLogTexto($path_log);
      $Log->RegistraLinea('ADM: CONTACT CENTER DE USUARIO CAMBIADO | BUSUA_COD: ' . ($busua_cod) . ' | GROUP_COD: ' . ($group_cod) . ' | NOMBRE CONTACT CENTER: ' . $Grupo->nombre);

      $data = array( 'status'    => 'success',
                     'message'   => 'Contact Center del usuario actualizado a <span class="text-primary">' . $Grupo->nombre . '</span>',
                     'busua_cod' => $busua_cod,
                     'group_cod' => $Grupo->group_cod,
                     'nombre'    => $Grupo->nombre );
      echo json_encode($data);

   }

   $DB->Logoff();

?>