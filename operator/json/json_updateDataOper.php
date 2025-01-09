<?
   require_once 'MOD_Error.php';
   require_once 'Parameters.php';
   require_once 'BConexion.php';
   require_once 'BOperador.php';

   $Operador   = new BOperador();
   $DB         = new BConexion();

   if ($Operador->sec_session_start_ajax() === false) {
      $DB->Logoff();
      MOD_Error::ErrorJSON('PBE_129');
      exit;
   }

   if ($Operador->VerificaLogin($DB) === false) {
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

   $data          = json_decode(file_get_contents('php://input'), true);

   $p_peppered    = hash_hmac('sha256', $data['password'], Parameters::PEPPER);

   if (password_verify($p_peppered, $Operador->password) === false) {
      $message = 'PBE_121: ' . MOD_Error::ErrorCode('PBE_121');
      $error = true;
      goto result;
   }

   if($Operador->actualiza($data['nombre'], $DB) === false) {
      $message = MOD_Error::ErrorCode('PBE_131');
      $error = true;
      goto result;
   }

result:
      if ($error === true) {
         $data = array( 'status'    => 'error',
                        'message'   => $message );
         echo json_encode($data);
      } else {
         $data = array( 'status'    => 'success',
                        'message'   => 'Datos <span class="text-primary">Actualizados</span>' );
         echo json_encode($data);
      }
   $DB->Logoff();
?>