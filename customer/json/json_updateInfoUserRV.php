<?
   require_once 'MOD_Error.php';
   require_once 'Parameters.php';
   require_once 'BConexion.php';
   require_once 'BUsuarioRV.php';

   $UsuarioRV  = new BUsuarioRV();
   $DB         = new BConexion();

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

   $data          = json_decode(file_get_contents('php://input'), true);

   $p_peppered    = hash_hmac('sha256', $data['password'], Parameters::PEPPER);

   if (password_verify($p_peppered, $UsuarioRV->password) === false) {
      $message = 'PBE_121: ' . MOD_Error::ErrorCode('PBE_121');
      $error = true;
      goto result;
   }

   $rut_empresa = str_replace('.', '', $data['rut_empresa']);
   $rut = str_replace('.', '', $data['rut']);

   if($UsuarioRV->Actualiza($data['nombre'], $data['apellidos'], $data['empresa'], $data['pais_cod'], $data['razon_social'],  $rut_empresa,  $rut,  $data['cargo'],  $data['telefono_celular'],  $data['telefono_fijo'], $data['rub_cod'], $data['med_cod'], $DB) === false) {
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