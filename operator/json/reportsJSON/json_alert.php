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

   // preguntamos si viene algo por GET
   if (!isset($_GET)) {
      $message = 'No se registran datos - cod: 00';
      $error = true;
      goto result;
   }

   // alert_cod
   $alert_cod  = isset($_GET['alert_cod']) ? intval($_GET['alert_cod']) : false;

   require_once 'BAlerta.php';
   require_once 'BUsuario.php';
   require_once 'BCausa.php';
   require_once 'BDerivacion.php';

   $Alerta     = new BAlerta();
   $Usuario    = new BUsuario();
   $Causa      = new BCausa();
   $Derivacion = new BDerivacion();
   $data_alert = [];
   $data_derivacion = [];

   if ($Alerta->busca($alert_cod, $DB) === false) {
      $message = 'No se registran datos - cod: 01';
      $error = true;
      goto result;
   }

   if ($Usuario->busca($Alerta->busua_cod, $DB) === false) {
      $message = 'No se registran datos - cod: 02';
      $error = true;
      goto result;
   }

   if ($Causa->busca($Alerta->causa_cod, $DB) === false) {
      $message = 'No se registran datos - cod: 03';
      $error = true;
      goto result;
   }

   $stat = $Derivacion->busca($Alerta->alert_cod, $DB);

   while ($stat) {
      array_push($data_derivacion, [
         'derv_cod'     => $Derivacion->derv_cod,
         'descripcion'  => $Derivacion->descripcion
      ]);
      $stat = $Derivacion->siguiente($DB);
   }

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

   // direccion
   $arrContextOptions=array(
      'ssl'=>array(
         'verify_peer'  => false,
         'verify_peer_name'   => false,
      ),
  );  
  
  $coordenadas = explode(';', $Alerta->posicion);

  $response = file_get_contents('https://' . $_SERVER['HTTP_HOST'] . ':9025/api/CoordenadaADireccion?lat=' . $coordenadas[0] . '&lon=' . $coordenadas[1], false, stream_context_create($arrContextOptions));
  
  $obj = json_decode($response);

   array_push($data_alert, [
      'busua_cod'       => $Alerta->busua_cod,
      'cloud_username'  => $Usuario->cloud_username,
      'nombre'          => $Usuario->nombre,
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
      'causa'           => $Causa->descripcion,
      'address'         => $obj->address,
      'amenity'         => $obj->amenity,
      'data_derivacion' => $data_derivacion
   ]);

result:
   if ($error === true) {

      $data = array( 'status'    => 'error',
                     'message'   => $message );
      echo json_encode($data);

   } else {

      $data = array( 'status'       => 'success',
                     'message'      => 'Data OK',
                     'data_alert'   => $data_alert );
      echo json_encode($data);

   }

   $DB->Logoff();
?>