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

   $message = '';                   // mensaje para los errores
   $error   = false;                // variable para identificar errores

   $DB2 = new BConexion();
   $DB3 = new BConexion();

   // preguntamos si viene algo por GET
   if (!isset($_GET)) {
      $message = 'No se registran datos - cod: 00';
      $error = true;
      goto result;
   }

   $usua_cod         = isset($_GET['usua_cod']) ? intval($_GET['usua_cod']) : false;
   $anio             = isset($_GET['anio']) ? intval($_GET['anio']) : false;
   $tipoa_cod        = isset($_GET['tipoa_cod']) ? intval($_GET['tipoa_cod']) : false;
   $causa_cod        = isset($_GET['causa_cod']) ? intval($_GET['causa_cod']) : false;
   $derivacion_get   = isset($_GET['deriv']) ? intval($_GET['deriv']) : false;

   require_once 'BUsuarioRV.php';
   require_once 'BUsuario.php';
   require_once 'BAlerta.php';
   require_once 'BCausa.php';
   require_once 'BDerivacion.php';

   // inicio
   $UsuarioRV  = new BUsuarioRV();
   $Usuario    = new BUsuario();
   $Alerta     = new BAlerta();
   $Causa      = new BCausa();
   $Derivacion = new BDerivacion();

   if ($UsuarioRV->Busca($usua_cod, $DB) === false) {
      $message = 'Usuario Redvoiss no encontrado - cod: 01';
      $error = true;
      goto result;
   }

   $stat = $Usuario->listadoUsuarios($UsuarioRV->usua_cod, $DB);

   $data_alert    = []; // informacion del tipo de alerta

   while ($stat) {

      $stat2 = $Alerta->buscaAlertaFiltro($Usuario->busua_cod, $anio, $tipoa_cod, $causa_cod, $derivacion_get, $DB2);

      while ($stat2) {

         $data_derivacion = [];

         $Causa->busca($Alerta->causa_cod, $DB3);

         $activa = '';

         switch (intval($Alerta->activa)) {
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
               $activa = 'Error por software';
               break;
         }

         $stat3 = $Derivacion->busca($Alerta->alert_cod, $DB3);

         while ($stat3) {
            array_push($data_derivacion, [
               'derv_cod'     => $Derivacion->derv_cod,
               'descripcion'  => $Derivacion->descripcion
            ]);
            $stat3 = $Derivacion->siguiente($DB3);
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
            'causa_desc'      => $Causa->descripcion,
            'busua_cod'       => $Usuario->busua_cod,
            'cloud_username'  => $Usuario->cloud_username,
            'dominio_usuario' => $Usuario->dominio_usuario,
            'nombre'          => $Usuario->nombre,
            'email'           => $Usuario->email,
            'data_derivacion' => $data_derivacion
         ]);

         unset($data_derivacion);
         $stat2 = $Alerta->siguienteBuscaAlertaFiltro($DB2);
      }
      
      $stat = $Usuario->siguienteListadoUsuarios($DB);
   }

result:
   if ($error === true) {

      $data = array( 'status'  => 'error',
                     'message' => $message );
      echo json_encode($data);

   } else {

      $data = array( 'status'       => 'success',
                     'message'      => 'Data OK',
                     'data_alert'   => $data_alert );
      echo json_encode($data);

   }

   $DB->Logoff();
   $DB2->Logoff();
   $DB3->Logoff();
?>