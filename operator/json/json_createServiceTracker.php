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

   $data          = json_decode(file_get_contents('php://input'), true);
   $busua_cod     = isset($data['busua_cod']) ? intval($data['busua_cod']) : false;
   $esta_cod      = isset($data['esta_cod']) ? intval($data['esta_cod']) : false;
   $status        = isset($data['status']) ? intval($data['status']) : false;
   $tipo_cod      = isset($data['tipo_cod']) ? intval($data['tipo_cod']) : false;
   $causa         = isset($data['causa']) ? $data['causa'] : false;
   $type          = isset($data['type']) ? intval($data['type']) : false;

   // Verifica que las variables no sean falsas
   if ($busua_cod === false || $esta_cod === false) {
      $error      = true;
      $message    = 'Error: No se registran datos - cod: 001';
      goto result;
   }

   // Clases
   require_once 'BUsuario.php';
   require_once 'BTracker.php';
   require_once 'BTipoServicio.php';
   require_once 'BOperadorLog.php';

   $Uusario = new BUsuario();
   $Tracker = new BTracker();
   $Tipo    = new BTipoServicio();
   $Log     = new BOperadorLog();

   // Verifica que el usaurio exista
   if ($Uusario->buscaUser($busua_cod, $DB) === false) {
      $error      = true;
      $message    = 'Usuario no encontrado';
      goto result;
   }

   // Vertifica que el usuario este activo
   if ($Uusario->esta_cod !== '1') {
      $error      = true;
      $message    = 'El Usuario no esta <span class="text-primary">Activo</span>';
      goto result;
   }

   // Verifica que tenga servicio de tracker
   // type = 1: ACTIVA/ELIMINA (ESTAMOS MANEJANDO EL INPUT CHECK)
   if ($type === 1) {

      // SI NO EXISTE EL TRACKER LO CREAMOS
      if ($Tracker->busca($busua_cod, $DB) === false) {

         // Si el estado que llega es 3, forza el error
         if ($esta_cod === 3) {

            $error      = true;
            $message    = 'Error: No es posible atender su solicitud, contacte a Sistemas - cod: 003';
            goto result;

         } else {

            if ($esta_cod === 1) { 

               // si el estado es 1 y llega hasta este punto, quiere decir que el cliente no tiene tracker, le creamos uno
               if ($Tracker->insert($busua_cod, $tipo_cod, $causa, $DB) === false) {

                  $error      = true;
                  $message    = 'Error: No es posible agregar servicio de Tracker - cod: 004';
                  goto result;

               }

            } else {

               // Actualiza el estado
               if ($Tracker->actualizaEstado($busua_cod, $esta_cod, $DB) === false) {

                  $error      = true;
                  $message    = 'Error: No es posible atender su solicitud, contacte a Sistemas - cod: 005';
                  goto result;

               }

               // actualiza data
               if ($Tracker->actualiza($busua_cod, $tipo_cod, $causa, $DB) === false) {

                  $error      = true;
                  $message    = 'Error: No es posible atender su solicitud, contacte a Sistemas - cod: 006';
                  goto result;

               }

            }

            $esta_cod   = $esta_cod === 3 ? 'ELIMINO' : 'CREO';
            $info_text  = $esta_cod === 3 ? '' : ' | TIPO_COD: ' . $tipo_cod . ' | CAUSA: ' . $causa;

         }

      } else {

         if ($esta_cod === 3) {

            // Actualiza el estado
            if ($Tracker->actualizaEstado($busua_cod, $esta_cod, $DB) === false) {

               $error      = true;
               $message    = 'Error: No es posible atender su solicitud, contacte a Sistemas - cod: 005';
               goto result;

            }

         } else {

            // Actualiza el estado
            if ($Tracker->actualizaEstado($busua_cod, $esta_cod, $DB) === false) {

               $error      = true;
               $message    = 'Error: No es posible atender su solicitud, contacte a Sistemas - cod: 005';
               goto result;

            }

            // actualiza data
            if ($Tracker->actualiza($busua_cod, $tipo_cod, $causa, $DB) === false) {

               $error      = true;
               $message    = 'Error: No es posible atender su solicitud, contacte a Sistemas - cod: 006';
               goto result;

            }

         }

         $esta_cod   = $esta_cod === 3 ? 'ELIMINO' : 'CREO';
         $info_text  = $esta_cod === 3 ? '' : ' | TIPO_COD: ' . $tipo_cod . ' | CAUSA: ' . $causa;
         
      }
      
   }

result:
   if ($error === true) {

      $data = array( 'status'  => 'error',
                     'message' => $message );
      echo json_encode($data);

   } else {

      $desc = '';

      if ($esta_cod === 'ELIMINO') {
         $Tipo->busca($Tracker->tipo_cod, $DB);
         $desc = 'SERVICIO DE TRACKER ELIMINADO | BUSUA_COD: ' . $busua_cod . ' | TIPO_COD: ' . $Tipo->tipo_cod . ' | DESC_TIPO: ' . $Tipo->tipo_servicio . ' | CAUSA: ' . $Tracker->causa;
      } else {
         $Tipo->busca($tipo_cod, $DB);
         $desc = 'SERVICIO DE TRACKER CREADO | BUSUA_COD: ' . $busua_cod . ' | TIPO_COD: ' . $Tipo->tipo_cod . ' | DESC_TIPO: ' . $Tipo->tipo_servicio . ' | CAUSA: ' . $Tracker->causa;
      }
      
      $Log->inserta($Operador->oper_cod, $desc, 'operator/json/json_createServiceTracker.php', $DB);

      $data = array( 'status'  => 'success',
                     'message' => 'Servicio de Tracker <strong class="text-primary">' . ($esta_cod === 'ELIMINO' ? 'Eliminado' : 'Ingresado') . '</strong>' );
      echo json_encode($data);

   }

   $DB->Logoff();
?>