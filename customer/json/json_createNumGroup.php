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
   $group_cod  = isset($data['group_cod']) ? intval($data['group_cod']) : false;
   $numeros    = isset($data['numeros']) ? $data['numeros'] : false;
   $num_group  = isset($data['num_group']) ? $data['num_group'] : false;
   $evento     = isset($data['evento']) ? $data['evento'] : false;

   if ($group_cod === false || $numeros === false || $num_group === false || $evento === false) {
      $message = MOD_Error::Error('PBE_116');
      $error = true;
      goto result;
   }

   require_once 'BGrupo.php';

   $Grupo = new BGrupo();

   if ($Grupo->busca($group_cod, $DB) === false) {
      $message = 'No existe grupo';
      $error = true;
      goto result;
   }

   if ($Grupo->actualizaNumeros($numeros, $DB) === false) {
      $message = 'No existe grupo';
      $error = true;
      goto result;
   }

result:
   if ($error === true) {

      $data = array( 'status'  => 'error',
                     'message' => $message );
      echo json_encode($data);

   } else {

      $message = ($evento === 'ADD' ? 'Nuevo n&uacute;mero <span class="text-primary">' . $num_group . '</span> agregado al Contact Center' : 'N&uacute;mero <span class="text-primary">' . $num_group . '</span> eliminado del Contact Center');
      $data = array( 'status'    => 'success',
                     'message'   => $message,
                     'group_cod' => $Grupo->group_cod,
                     'numeros'   => $Grupo->numeros,
                     'num_group' => $num_group );
      echo json_encode($data);

   }

   $DB->Logoff();
?>