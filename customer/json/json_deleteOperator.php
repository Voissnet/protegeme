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

   if ($oper_cod === false) {
      $message = MOD_Error::ErrorCode('PBE_116') . ' - 01';
      $error = true;
      goto result;
   }

   require_once 'BOperador.php';
   require_once 'BLog.php';

   $Operador = new BOperador();
   $Log      = new BLog();

   if ($Operador->busca($oper_cod, $DB) === false) {
      $message = MOD_Error::ErrorCode('PBE_116') . ' - 02';
      $error = true;
      goto result;
   }

   if ($Operador->actualizaEstado(3, $DB) === false) {
      $message = MOD_Error::ErrorCode('PBE_116') . ' - 03';
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
      $Log->RegistraLinea('ADM: ESTADO DE OPERADOR ACTUALIZADO | OPER_COD: ' . ($oper_cod) . ' | NUEVO ESTADO ELIMINADO');

      $data = array( 'status'    => 'success',
                     'message'   => 'Operador <span class="text-primary">eliminado</span>',
                     'oper_cod'  => $oper_cod,
                     'esta_cod'  => 3 );
      echo json_encode($data);

   }

   $DB->Logoff();
?>