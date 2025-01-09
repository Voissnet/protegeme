<?
   require_once 'Parameters.php';
   require_once 'MOD_Error.php';
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

   $message = '';                   // mensaje para los errores
   $error   = false;                // variable para identificar errores
   $DB2     = new BConexion();      // segunda conexion
   $DB3     = new BConexion();      // tercera conexion

   // preguntamos si viene algo por GET
   if (!isset($_GET)) {
      $message = 'No se registran datos - cod: 00';
      $error = true;
      goto result;
   }

   // usua_cod
   $usua_cod   = isset($_GET['usua_cod']) ? intval($_GET['usua_cod']) : false;

   // clases
   require_once 'BUsuarioRV.php';
   require_once 'BUsuario.php';
   require_once 'BAlerta.php';
   require_once 'BCausa.php';
   require_once 'BOperador.php';
   require_once 'BTipoAlerta.php';
   require_once 'BDerivacion.php';

   // inicio
   $UsuarioRV  = new BUsuarioRV();
   $Usuario    = new BUsuario();
   $Alerta     = new BAlerta();
   $Causa      = new BCausa();
   $Operador   = new BOperador();
   $TipoAlerta = new BTipoAlerta();
   $Derivacion = new BDerivacion();

   if ($UsuarioRV->Busca($usua_cod, $DB) === false) {
      $message = 'Usuario Redvoiss no encontrado - cod: 01';
      $error = true;
      goto result;
   }

   $stat          = $Usuario->listadoUsuarios($UsuarioRV->usua_cod, $DB);

   $data_user     = []; // informacion del usuario
   $data_oper     = []; // informacion del operador
   $data_talert   = []; // informacion del tipo de alerta
   $data_causa    = []; // informacion del los tipos de causas
   $data_deriv    = []; // informacion del los tipos de derivacion
   $total_user    = 0;
   $total_alert   = 0;
   $total_oper    = 0;
   $i             = 0;

   while ($stat) {
      $data_alert = []; // informacion de la alerta
      $stat2 = $Alerta->buscaAlerta($Usuario->busua_cod, $DB2);
      while ($stat2) {
         // 0:Alerta No atendida / 1:Escalada  /2:Descartada /3:Llamada de prueba /4:Llamada por error /-1:Error por software
         $Causa->busca($Alerta->causa_cod, $DB3);

         $activa = '';
         switch (intval($Alerta->activa)) {
            case -1:
               $activa = 'Error por software';
               break;
            case 0:
               $activa = 'Alerta No atendida';
               break;
            case 1:
               $activa = 'Escalada';
               break;
            case 2:
               $activa = 'Descartada';
               break;
            case 3:
               $activa = 'Llamada de prueba';
               break;
            case 4:
               $activa = 'Llamada por error';
               break;
            default:
               $activa = 'Error';
               break;
         }

         array_push($data_alert, [
            'alert_cod'       => $Alerta->alert_cod,
            'activa'          => $Alerta->activa,
            'activa_desc'     => $activa,
            'tipoa_cod'       => $Alerta->tipoa_cod,
            'tipo_alerta'     => $Alerta->tipo_alerta,
            'fecha_creacion'  => $Alerta->fecha_creacion,
            'link'            => $Alerta->link,
            'fecha_atencion'  => $Alerta->fecha_atencion,
            'posicion'        => $Alerta->posicion,
            'descripcion'     => $Alerta->descripcion,
            'causa_cod'       => $Alerta->causa_cod,
            'causa_desc'      => $Causa->descripcion
         ]);
         $total_alert++;
         $stat2 = $Alerta->siguienteBuscaAlerta($DB2);
      }
      array_push($data_user, [
         'group_cod'       => $Usuario->group_cod,
         'grupo'           => $Usuario->grupo,
         'busua_cod'       => $Usuario->busua_cod,
         'cloud_username'  => $Usuario->cloud_username,
         'esta_cod'        => $Usuario->esta_cod,
         'user_phone'      => $Usuario->user_phone,
         'email'           => $Usuario->email,
         'nombre'          => $Usuario->nombre,
         'fecha_creacion'  => $Usuario->fecha_creacion,
         'data_alert'      => $data_alert
      ]);
      $total_user++;
      unset($data_alert);
      $stat = $Usuario->siguienteListadoUsuarios($DB);
   }

   $stat4 = $Operador->buscaOperadores($Usuario->dom_cod, $DB);

   while ($stat4) {
      array_push($data_oper, [
         'oper_cod'           => $Operador->oper_cod,
         'username'           => $Operador->username,
         'nombre'             => $Operador->nombre,
         'esta_cod'           => $Operador->esta_cod,
         'fecha_creacion'     => $Operador->fecha_creacion,
         'fecha_ultimo_login' => $Operador->fecha_ultimo_login,
         'ultima_ip'          => $Operador->ultima_ip,
         'email'              => $Operador->email,
         'notifica'           => $Operador->notifica,
         'fecha_notificacion' => $Operador->fecha_notificacion
      ]);
      $total_oper++;
      $stat4 = $Operador->siguientesOperadores($DB);
   }

   $stat5 = $TipoAlerta->primero($DB);

   while ($stat5) {
      array_push($data_talert, [
         'tipoa_cod'          => $TipoAlerta->tipoa_cod,
         'tipo_alerta'        => $TipoAlerta->tipo_alerta
      ]);
      $stat5 = $TipoAlerta->siguiente($DB);
   }

   $stat6 = $Causa->primero($DB);

   while ($stat6) {
      array_push($data_causa, [
         'causa_cod'    => $Causa->causa_cod,
         'descripcion'  => $Causa->descripcion
      ]);
      $stat6 = $Causa->siguiente($DB);
   }

   $stat7 = $Derivacion->buscaDerivacion($DB);

   while ($stat7) {
      array_push($data_deriv, [
         'derv_cod'     => $Derivacion->derv_cod,
         'descripcion'  => $Derivacion->descripcion
      ]);
      $stat7 = $Derivacion->siguienteDerivacion($DB);
   }

result:
   if ($error === true) {

      $data = array( 'status'  => 'error',
                     'message' => $message );
      echo json_encode($data);

   } else {

      $data = array( 'status'          => 'success',
                     'message'         => 'Data OK',
                     'count'           => $total_user,
                     'data_user'       => $data_user,
                     'data_oper'       => $data_oper,
                     'data_talert'     => $data_talert,
                     'data_causa'      => $data_causa,
                     'data_deriv'      => $data_deriv,
                     'total_alert'     => $total_alert,
                     'total_oper'      => $total_oper,
                     'dominio'         => $Usuario->dominio,
                     'dom_cod'         => $Usuario->dom_cod,
                     'dominio_usuario' => $Usuario->dominio_usuario );
      echo json_encode($data);

   }

   $DB->Logoff();
   $DB2->Logoff();
   $DB3->Logoff();
?>