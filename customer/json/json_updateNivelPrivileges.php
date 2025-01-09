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
      $message = MOD_Error::ErrorCode('PBE_116') . ' - 00';
      $error = true;
      goto result;
   }

   $data       = json_decode(file_get_contents('php://input'), true);
   $oper_cod   = isset($data['oper_cod']) ? intval($data['oper_cod']) : false;
   $acci_cod   = isset($data['acci_cod']) ? intval($data['acci_cod']) : false;
   $nivel      = isset($data['nivel']) ? intval($data['nivel']) : false;

   require_once 'BOperador.php';
   require_once 'BOperadorAccion.php';
   require_once 'BLog.php';

   $Operador   = new BOperador();
   $Permiso    = new BOperadorAccion();
   $Log        = new BLog();

   if ($Operador->busca($oper_cod, $DB) === false) {
      $message = MOD_Error::ErrorCode('PBE_116') . ' - 01';
      $error = true;
      goto result;
   }

   if ($Permiso->actualiza($oper_cod, $acci_cod, $nivel, $DB) === false) {
      $message = MOD_Error::ErrorCode('PBE_116') . ' - 02';
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
      $Log->RegistraLinea('ADM: PERMISO MODIFICADO | OPER_COD: ' . ($oper_cod) . ' | ACCI_COD: ' . $acci_cod . ' | NIVEL: ' . $nivel);

      $data = array( 'status'    => 'success',
                     'message'   => 'Nivel <span class="text-primary">actualizado</span>' );
      echo json_encode($data);

   }

   $DB->Logoff();
?>