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

   $data       = json_decode(file_get_contents('php://input'), true);
   $group_cod  = isset($data['group_cod']) ? intval($data['group_cod']) : false;
   $nombre     = isset($data['nombre']) ? $data['nombre'] : false;

   if ($group_cod === false || $nombre === false) {
      $message = MOD_Error::Error('PBE_116');
      $error = true;
      goto result;
   }

   require_once 'BGrupo.php';
   require_once 'BOperadorLog.php';

   $Grupo   = new BGrupo();
   $Log     = new BOperadorLog();

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

      $desc = 'NOMBRE CONTACT CENTER MODIFICADO | DOM_COD: ' . $Grupo->dom_cod . ' | ID CONTACT: ' . $Grupo->group_cod . ' | NUEVO NOMBRE CONTACT CENTER: ' . $Grupo->nombre;
      $Log->inserta($Operador->oper_cod, $desc, 'operator/json/json_updateNameContactCenter.php', $DB);

      $data = array( 'status'    => 'success',
                     'message'   => 'Nombre del Contact Center actualizado a <span class="text-primary">' . $nombre . '</span>',
                     'group_cod' => $Grupo->group_cod,
                     'nombre'    => $Grupo->nombre );
      echo json_encode($data);

   }
   
   $DB->Logoff();
?>