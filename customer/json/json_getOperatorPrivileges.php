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

   if (!isset($_GET)) {
      $message = MOD_Error::ErrorCode('PBE_117');
      $error = true;
      goto result;
   }

   // identificador del usuario boton
   $oper_cod = isset($_GET['oper_cod']) ? intval($_GET['oper_cod']) : false;

   if ($oper_cod === false) {
      $message = MOD_Error::ErrorCode('PBE_116');
      $error = true;
      goto result;
   }

   require_once 'BOperador.php';
   require_once 'BOperadorAccion.php';

   $Operador   = new BOperador();
   $Permiso    = new BOperadorAccion();

   if ($Operador->busca($oper_cod, $DB) === false) {
      $message = MOD_Error::ErrorCode('PBE_116');
      $error = true;
      goto result;
   }

   $stat = $Permiso->busca($oper_cod, $DB);
   $arr_permiso = array();

   while ($stat) {

      array_push($arr_permiso, [
         'acci_cod'  => $Permiso->acci_cod,
         'accion'    => $Permiso->accion,
         'nivel'     => $Permiso->nivel
      ]);

      $stat = $Permiso->siguiente($DB);

   }

result:
   if ($error === true) {
      $data = array( 'status'    => 'error',
                     'message'   => $message );
      echo json_encode($data);
   } else {
      $data = array( 'status'          => 'success',
                     'message'         => 'Ok',
                     'oper_cod'        => $Operador->oper_cod,
                     'username'        => $Operador->username,
                     'dominio_usuario' => $Operador->dominio_usuario,
                     'permisos'        => $arr_permiso );
      echo json_encode($data);
   }
   $DB->Logoff();
?>