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

   // preguntamos si viene algo por GET
   if (!isset($_GET)) {
      $message = 'No se registran datos - cod: 00';
      $error = true;
      goto result;
   }

   // oper_cod
   $oper_cod = isset($_GET['oper_cod']) ? $_GET['oper_cod'] : false;

   if ($Operador->oper_cod !== $oper_cod) {
      $message = 'No se registran datos - cod: 01';
      $error = true;
      goto result;
   }

   $dataOper = [
      'oper_cod'  => $Operador->oper_cod,
      'username'  => $Operador->username,
      'names'     => $Operador->nombre,
      'email'     => $Operador->email
   ];

result:
   if ($error === true) {
      $data = array( 'status'  => 'error',
                     'message' => $message );
      echo json_encode($data);
   } else {
      $data = array( 'status'    => 'success',
                     'message'   => 'OK',
                     'dataOper'  => $dataOper );
      echo json_encode($data);
   }
   $DB->Logoff();
?>