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
   $dom_cod = isset($_GET['dom_cod']) ? intval($_GET['dom_cod']) : false;

   if ($dom_cod === false) {
      $message = MOD_Error::ErrorCode('PBE_116');
      $error = true;
      goto result;
   }

   require_once 'BOperador.php';

   $Operador = new BOperador();
   $dataOperadores = array();

   $stat = $Operador->buscaOperadores($dom_cod, $DB);

   while ($stat) {
      $estado = '';
      switch ($Operador->esta_cod) {
         case '1':
            $estado = 'Activo';
            break;
         case '2':
            $estado = 'Inactivo';
            break;
         case '3':
            $estado = 'Eliminado';
            break;
         default:
            $estado = 'Error';
            break;
      }
      if ($Operador->esta_cod !== '3') {
         array_push($dataOperadores, [
            'oper_cod'              => $Operador->oper_cod,
            'dom_cod'               => $Operador->dom_cod,
            'username'              => $Operador->username,
            'nombre'                => $Operador->nombre,
            'esta_cod'              => $Operador->esta_cod,
            'estado'                => $estado,
            'fecha_creacion'        => $Operador->fecha_creacion,
            'sesion'                => $Operador->sesion,
            'fecha_ultimo_login'    => $Operador->fecha_ultimo_login,
            'ultima_ip'             => $Operador->ultima_ip,
            'dominio_usuario'       => $Operador->dominio_usuario,
            'email'                 => $Operador->email,
            'notifica'              => $Operador->notifica,
            'fecha_notificacion'    => $Operador->fecha_notificacion
         ]);
      }
      $stat = $Operador->siguientesOperadores($DB);
   }

result:
   if ($error === true) {
      $data = array( 'status'          => 'error',
                     'message'         => $message );
      echo json_encode($data);
   } else {
      $data = array( 'status'          => 'success',
                     'message'         => 'Ok',
                     'dataOperadores'  => $dataOperadores );
      echo json_encode($data);
   }
   $DB->Logoff();
?>