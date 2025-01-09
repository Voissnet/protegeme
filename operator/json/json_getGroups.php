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

   if (!isset($_GET)) {
      $message = MOD_Error::Error('PBE_117');
      $error = true;
      goto result;
   }

   // identificador del usuario
   $usua_cod = isset($_GET['usua_cod']) ? intval($_GET['usua_cod']) : false;

   if ($usua_cod === false) {
      $message = MOD_Error::ErrorCode('PBE_117');
      $error = true;
      goto result;
   }
   
   require_once 'BGrupo.php';

   $Grupo   = new BGrupo();

   $stat    = $Grupo->Primero($usua_cod, $DB);

   if ($stat === false) {
      $message = 'No registra Contact Center';
      $error = true;
      goto result;
   }

   $groups;
   $i = 0;

   while ($stat) {
      
      $groups[$i] = [
         'group_cod' => $Grupo->group_cod,
         'nombre'    => $Grupo->nombre,
         'numeros'   => $Grupo->numeros
      ];
      $i++;
      $stat = $Grupo->Siguiente($DB);
   }

   if (count($groups) <= 0) {
      $message = 'No registra Contact Centers';
      $error = true;
      goto result;
   }

result:
   if ($error === true) {

      $data = array( 'status'  => 'error',
                     'message' => $message );
      echo json_encode($data);

   } else {

      $data = array( 'status'    => 'success',
                     'message'   => 'Contactos encontrados',
                     'groups'    => $groups );
      echo json_encode($data);
         
   }
   $DB->Logoff();
?>