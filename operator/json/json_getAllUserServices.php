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

   $dom_cod    = isset($_GET['dom_cod']) ? intval($_GET['dom_cod']) : false;
   $tipo_cod   = isset($_GET['tipo_cod']) ? intval($_GET['tipo_cod']) : false;

   if ($dom_cod === false || $tipo_cod === false) {
      $error = true;
      $message = 'Error: No se registran servicios - cod: 01';
      goto result;
   }

   if ($tipo_cod === 0) {
      $error = true;
      $message = 'Error: No se registran servicios - cod: 02';
      goto result;
   }

   require_once 'BDominio.php';
   require_once 'BBoton.php';

   $Dominio       = new BDominio();
   $Boton         = new BBoton();
   $dataService   = [];

   if ($Dominio->busca($dom_cod, $DB) === false) {
      $error = true;
      $message = 'Error: No se registran servicios - cod: 03';
      goto result;
   }

   $stat = $Boton->buscaServicios($Dominio->dom_cod, $tipo_cod, $DB);

   if ($stat === false) {
      $error = true;
      $message = 'Error: No se registran servicios - cod: 04';
      goto result;
   }

   while ($stat) {

      array_push($dataService, [
         'bot_cod'            => $Boton->bot_cod,
         'busua_cod'          => $Boton->busua_cod,
         'cloud_username'     => $Boton->cloud_username,
         'email'              => $Boton->email,
         'nombre'             => $Boton->nombre,
         'tipo_cod'           => $Boton->tipo_cod,
         'tipo'               => $Boton->tipo,
         'fecha_creacion'     => $Boton->fecha_creacion,
         'fecha_notificacion' => $Boton->fecha_notificacion == 0 ? '' : $Boton->fecha_notificacion
      ]);

      $stat = $Boton->siguienteServicios($DB);
   }

result:
   if ($error === true) {

      $data = array( 'status'    => 'err',
                     'message'   => $message );
      echo json_encode($data);

   } else {

      $data = array( 'status'             => 'success',
                     'message'            => 'Datos encontrados',
                     'dataService'        => $dataService );
      echo json_encode($data);

   }

   $DB->Logoff();
?>