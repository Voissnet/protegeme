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

   $group_cod  = isset($_POST['group_cod']) ? intval($_POST['group_cod']) : false;
   $nombre     = isset($_POST['nombre']) ? $_POST['nombre'] : false;

   if ($group_cod === false || $nombre === false) {
      $message = MOD_Error::Error('PBE_116');
      $error = true;
      goto result;
   }

   require_once 'BGrupo.php';
   require_once 'BLog.php';

   $Grupo   = new BGrupo();
   $Log     = new BLog();

   if ($Grupo->busca($group_cod, $DB) === false) {
      $message = 'No se logro actualizar nombre del <span class="text-primary">Contact Center</span>';
      $error = true;
      goto result;
   }

   if ($Grupo->verificaNombre($Grupo->dom_cod, $nombre, $DB) === true) {
      $message = 'Nombre ya existe en otro <span class="text-primary">Contact Center</span>';
      $error = true;
      goto result;
   }

   if ($Grupo->actualizaNombre($nombre, $DB) === false) {
      $message = 'No se logro actualizar nombre del <span class="text-primary">Contact Center</span>';
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
      $Log->RegistraLinea('ADM: NOMBRE CONTACT CENTER MODIFICADO | DOM_COD: ' . ($Grupo->dom_cod) . ' | GRUPO_COD: ' . ($Grupo->group_cod) . ' | NUEVO NOMBRE CONTACT CENTER: ' . ($nombre));

      $data = array( 'status'    => 'success',
                     'message'   => 'Nombre del Contact Center actualizado a <span class="text-primary">' . $nombre . '</span>',
                     'group_cod' => $Grupo->group_cod,
                     'nombre'    => $Grupo->nombre );
      echo json_encode($data);

   }
   
   $DB->Logoff();
?>