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
   
   $mensaje = '';
   $error = false;

   if (!isset($_POST)) {
      $mensaje = MOD_Error::Error('PBE_116');
      $error = true;
      goto result;
   }

   $busua_cod  = isset($_POST['busua_cod']) ? intval($_POST['busua_cod']) : false;
   $esta_cod   = isset($_POST['esta_cod']) ? intval($_POST['esta_cod']) : false;
   $id         = isset($_POST['id']) ? intval($_POST['id']) : false;
   $tipo_cod   = isset($_POST['tipo_cod']) ? intval($_POST['tipo_cod']) : false;

   if ($busua_cod === false || $esta_cod === false || $id === false || $tipo_cod === false) {
      $error = true;
      $mensaje = 'Error: No se registran datos - cod: 01';
      goto result;
   }

   require_once 'BUsuario.php';
   require_once 'BBoton.php';
   require_once 'BTracker.php';
   require_once 'BOtrosProductos.php';
   require_once 'BOperadorLog.php';

   $Usuario = new BUsuario();
   $Boton   = new BBoton();
   $Tracker = new BTracker();
   $Otros   = new BOtrosProductos();
   $Log     = new BOperadorLog();

   if ($Usuario->buscaUser($busua_cod, $DB) === false) {
      $error = true;
      $mensaje = 'Error: No se encuentra usuario - cod: 02';
      goto result;
   }

   if ($Usuario->esta_cod === '3') {
      $error = true;
      $mensaje = 'Error: No es posible cambiar estado - cod: 03';
      goto result;
   }

   # TIPO DE BOTON:
   # 1 - Botón de emergencia SIP - Móvil
   # 2 - Botón de emergencia SIP - Estático
   # 3 - Botón de emergencia Estándar
   # 4 - Widget
   # 5 - Tracker
   # 6 -  Web RTC
   # Default: Otros productos

   $tipo = '';

   switch ($tipo_cod) {

      case 1:

         # 1 - Botón de emergencia SIP - Móvil

         $tipo = 'Botón de emergencia SIP - Móvil';

         # Busca boton
         if ($Boton->busca($id, $DB) === false) {
            $error = true;
            $mensaje = 'Error: No fue posible encontrar usuario - cod: 04';
            goto result;
         }
      
         # Si el usuario no es el mismo no pasa
         if ($Boton->busua_cod !== $Usuario->busua_cod) {
            $error = true;
            $mensaje = 'Error: No es posible cambiar estado - cod: 05';
            goto result;
         }

         # Si esta eliminado no actualiza
         if (intval($Boton->esta_cod) === 3) {
            $error = true;
            $mensaje = 'Error: No es posible cambiar estado - cod: 06';
            goto result;
         }
      
         # Cambia estado
         if ($Boton->actualizaEstado($esta_cod, $DB) === false) {
            $error = true;
            $mensaje = 'Error: No es posible cambiar estado - cod: 07';
            goto result;
         }

         break;

      case 2:

         # 2 - Botón de emergencia SIP - Estático
         
         $tipo = 'Botón de emergencia SIP - Estático';

         # Busca boton
         if ($Boton->busca($id, $DB) === false) {
            $error = true;
            $mensaje = 'Error: No fue posible encontrar usuario - cod: 08';
            goto result;
         }
      
         # Si el usuario no es el mismo no pasa
         if ($Boton->busua_cod !== $Usuario->busua_cod) {
            $error = true;
            $mensaje = 'Error: No es posible cambiar estado - cod: 09';
            goto result;
         }
      
         # Si esta eliminado no actualiza
         if (intval($Boton->esta_cod) === 3) {
            $error = true;
            $mensaje = 'Error: No es posible cambiar estado - cod: 10';
            goto result;
         }
      
         # Cambia estado
         if ($Boton->actualizaEstado($esta_cod, $DB) === false) {
            $error = true;
            $mensaje = 'Error: No es posible cambiar estado - cod: 11';
            goto result;
         }

         break;

      case 5:

         # 5 - Servicio de tracker

         $tipo = 'Servicio de tracker';

         # Busca tracker
         if ($Tracker->busca($busua_cod, $DB) === false) {
            $error = true;
            $mensaje = 'Error: No fue posible encontrar usuario - cod: 12';
            goto result;
         }

         # Si esta eliminado no actualiza
         if (intval($Tracker->esta_cod) === 3) {
            $error = true;
            $mensaje = 'Error: No es posible cambiar estado - cod: 13';
            goto result;
         }

         # Cambia estado
         if ($Tracker->actualizaEstado($esta_cod, $DB) === false) {
            $error = true;
            $mensaje = 'Error: No es posible cambiar estado - cod: 14';
            goto result;
         }

         break;

      case 6:

         # 6 - Web RTC

         $tipo = 'Web RTC';

         # Busca boton
         if ($Boton->busca($id, $DB) === false) {
            $error = true;
            $mensaje = 'Error: No fue posible encontrar usuario - cod: 15';
            goto result;
         }
      
         # Si el usuario no es el mismo no pasa
         if ($Boton->busua_cod !== $Usuario->busua_cod) {
            $error = true;
            $mensaje = 'Error: No es posible cambiar estado - cod: 16';
            goto result;
         }

         # Si esta eliminado no actualiza
         if (intval($Boton->esta_cod) === 3) {
            $error = true;
            $mensaje = 'Error: No es posible cambiar estado - cod: 17';
            goto result;
         }
      
         # cambia estado
         if ($Boton->actualizaEstado($esta_cod, $DB) === false) {
            $error = true;
            $mensaje = 'Error: No es posible cambiar estado - cod: 18';
            goto result;
         }

         break;

      default:

         # Default: Otros productos
         
         if ($Otros->busca($id, $DB) === false) {
            $error = true;
            $mensaje = 'Error: No es posible cambiar estado - cod: 19';
            goto result;
         }

         $tipo = $Otros->tipo;

         # Si el usuario no es el mismo no pasa
         if ($Otros->busua_cod !== $Usuario->busua_cod) {
            $error = true;
            $mensaje = 'Error: No es posible cambiar estado - cod: 20';
            goto result;
         }
      
         # Si esta eliminado no actualiza
         if (intval($Otros->esta_cod) === 3) {
            $error = true;
            $mensaje = 'Error: No es posible cambiar estado - cod: 21';
            goto result;
         }
      
         # Cambia estado
         if ($Otros->actualizaEstado($esta_cod, $DB) === false) {
            $error = true;
            $mensaje = 'Error: No es posible cambiar estado - cod: 22';
            goto result;
         }

         break;
   }

   $desc = 'SE MODIFICO ESTADO DE "' . ($tipo) . '" | BUSUA_COD: ' . ($Usuario->busua_cod) . ' | NUEVO ESTA_COD: ' . ($esta_cod === 1 ? 'ACTIVO' : 'INACTIVO');
   $Log->inserta($Operador->oper_cod, $desc, 'operator/json/json_updateStatusButton.php', $DB);

result:
   if ($error === true) {

      $data = array( 'status'  => 'err',
                     'message' => $mensaje );
      echo json_encode($data);

   } else {

      $text = $esta_cod === 1 ? 'Activo' : 'Inactivo';
      $class = $esta_cod === 1 ? 'text-success' : 'text-warning';
      $data = array( 'status'    => 'success',
                     'message'   => 'Estado actualizado <span class="' . $class . '">' . $text . '</span>',
                     'busua_cod' => $busua_cod,
                     'esta_cod'  => $esta_cod );
      echo json_encode($data);

   }

   $DB->Logoff();
?>