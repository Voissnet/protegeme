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
   $busua_cod  = isset($data['busua_cod']) ? intval($data['busua_cod']) : false;
   $esta_cod   = isset($data['esta_cod']) ? intval($data['esta_cod']) : false;

   if ($busua_cod === false || $esta_cod === false) {
      $message = MOD_Error::Error('PBE_116');
      $error = true;
      goto result;
   }

   require_once 'BUsuario.php';
   require_once 'BLog.php';

   $Usuario = new BUsuario();
   $Log     = new BLog();

   if ($Usuario->buscaUser($busua_cod, $DB) === false) {
      $message = 'No existe usuario';
      $error = true;
      goto result;
   }

   if ($Usuario->actualizaEstadoUser($esta_cod, $DB) === false) {
      $message = 'No se logro actualizar estado';
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
      $Log->RegistraLinea('ADM: ESTADO DE USUARIO ACTUALIZADO A ' . ($esta_cod === 1 ? 'ACTIVO' : 'INACTIVO') . ' | BUSUA_COD: ' . ($busua_cod));

      $text = $esta_cod === 1 ? 'Activo' : 'Inactivo';
      $class = $esta_cod === 1 ? 'text-success' : 'text-warning';
      $data = array( 'status'    => 'success',
                     'message'   => 'Estado actualizado <span class="' . $class . '">' . $text . '</span>',
                     'busua_cod' => $busua_cod,
                     'esta_cod'  => $esta_cod );
      echo json_encode($data);

   }
   
   $DB->Logoff();
?>