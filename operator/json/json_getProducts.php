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
      $message = MOD_Error::ErrorCode('PBE_117');
      $error = true;
      goto result;
   }

   // identificador del usuario boton
   $busua_cod = isset($_GET['busua_cod']) ? intval($_GET['busua_cod']) : false;

   if ($busua_cod === false) {
      $error   = true;
      $message = 'Error: No se registran datos - cod: 01';
      goto result;
   }

   require_once 'BOtrosProductos.php';

   $Productos     = new BOtrosProductos();
   $arr_products  = [];

   $stat = $Productos->buscaProductosUsuario($busua_cod, $DB);

   if ($stat === false) {
      $error   = true;
      $message = 'Error: No se registran productos - cod: 02';
      goto result;
   }

   while ($stat) {

      array_push($arr_products, [
         'prod_cod'           => $Productos->prod_cod,
         'tipo_cod'           => $Productos->tipo_cod,
         'tipo'               => $Productos->tipo,
         'busua_cod'          => $Productos->busua_cod,
         'username'           => $Productos->username,
         'esta_cod'           => $Productos->esta_cod,
         'fecha_creacion'     => $Productos->fecha_creacion,
         'fecha_notificacion' => $Productos->fecha_notificacion
      ]);

      $stat = $Productos->siguienteProductosUsuario($DB);

   }

   result:
   if ($error === true) {

      $data = array( 'status'    => 'err',
                     'message'   => $message );
      echo json_encode($data);

   } else {

      $data = array( 'status'                => 'success',
                     'message'               => 'Productos encontrados',
                     'products'              => $arr_products );
      echo json_encode($data);

   }

   $DB->Logoff();
?>