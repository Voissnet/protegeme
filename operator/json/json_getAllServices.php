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

   require_once 'BTipoBoton.php';

   $TipoBoton     = new BTipoBoton();
   $arr_services  = [];

   // guarda todos los servicios
   $stat = $TipoBoton->primero($DB);

   if ($stat === false) {
      $error = true;
      $message = 'Error: No se registran servicios - cod: 00';
      goto result;
   }

   while ($stat) {

      array_push($arr_services, [
         $TipoBoton->tipo_cod,
         $TipoBoton->tipo
      ]);

      $stat = $TipoBoton->siguiente($DB);
      
   }

   // array_push($arr_services, [
   //    -1,
   //    'Tracker'
   // ]);  

result:
   if ($error === true) {

      $data = array( 'status'  => 'err',
                     'message' => $message );
      echo json_encode($data);

   } else {

      $data = array( 'status'    => 'success',
                     'message'   => 'Datos encontrados',
                     'services'  => $arr_services );
      echo json_encode($data);

   }

   $DB->Logoff();
?>