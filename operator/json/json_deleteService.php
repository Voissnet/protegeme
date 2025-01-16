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

   $DB2        = new BConexion();
   $errorres   = false;
   $mensaje    = '';

   if (!isset($_POST)) {
      $message = MOD_Error::ErrorCode('PBE_116') . ' - 00';
      $error = true;
      goto result;
   }

   $bot_cod    = isset($_POST['bot_cod']) ? intval($_POST['bot_cod']) : false;
   $busua_cod  = isset($_POST['busua_cod']) ? intval($_POST['busua_cod']) : false;
   $tipo_cod   = isset($_POST['tipo_cod']) ? intval($_POST['tipo_cod']) : false;

   if ($bot_cod === false || $busua_cod === false || $tipo_cod === false) {
      $errorres = true;
      $mensaje = 'Error: No se registran datos - cod: 01';
      goto result;
   }

   require_once 'BGatewayNumero.php';
   require_once 'BGLBLnumeroReal.php';
   require_once 'BDominio.php';
   require_once 'BGrupo.php';
   require_once 'BUsuario.php';
   require_once 'BBoton.php';
   require_once 'BTracker.php';
   require_once 'BOtrosProductos.php';
   require_once 'BContactosLlamada.php';
   require_once 'BContactosSMS.php';
   require_once 'BOperadorLog.php';

   $Numero        = new BGatewayNumero();
   $NumeroReal    = new BGLBLnumeroReal();
   $Dominio       = new BDominio();
   $Grupo         = new BGrupo();
   $Usuario       = new BUsuario();
   $Boton         = new BBoton();
   $Tracker       = new BTracker();
   $OtrosP        = new BOtrosProductos();
   $ContactoLlama = new BContactosLlamada();
   $ContactoSMS   = new BContactosSMS();
   $Log           = new BOperadorLog();

   // busca usuario
   if ($Usuario->buscaUser($busua_cod, $DB) === false) {
      $errorres = true;
      $mensaje = 'Error: Usuario no encontrado - cod: 02';
      goto result;
   }

   # TIPO DE BOTON:
   # 1 - Botón de emergencia SIP - Móvil
   # 2 - Botón de emergencia SIP - Estático
   # 3 - Botón de emergencia Estándar
   # 4 - Widget
   # 5 - Tracker
   # 6 - Web RTC
   # Default: Otros productos

   switch ($tipo_cod) {

      case 1:

         # 1 - Botón de emergencia SIP - Móvil

         # busca boton
         if ($Boton->busca($bot_cod, $DB) === false) {
            $errorres = true;
            $mensaje = 'Error: Servicio no encontrado - cod: 03';
            goto result;
         }

         # si el usuario no es el mismo no pasa
         if (intval($Usuario->busua_cod) !== intval($Boton->busua_cod)) {
            $errorres = true;
            $mensaje = 'Error: Usuario no corresponde al servicio - cod: 04';
            goto result;
         }

         # busca contact center
         if ($Grupo->busca($Usuario->group_cod, $DB) === false) {
            $errorres = true;
            $mensaje = 'Error: Contact center no encontrado - cod: 05';
            goto result;
         }

         # busca dominio
         if ($Dominio->busca($Grupo->dom_cod, $DB) === false) {
            $errorres = true;
            $mensaje = 'Error: Dominio no encontrado - cod: 06';
            goto result;
         }

         $sip_username = Parameters::generaSipUsername($Boton->sip_username, strlen($Boton->sip_username), 1);

         # busca Numero SOS
         if ($Numero->buscaNumSOS('533' . $Dominio->gate_cod . $sip_username, $Dominio->gate_cod, $DB) === true) {

            # elimina numero
            if ($Numero->delete(2, $DB) === false) {
               $errores = true;
               $message = 'Error: No se pudo eliminar Servicio De Emergencia - cod: 07';
               goto result;
            }

            # libera numero real
            if ($NumeroReal->ActualizaInterno($Numero->numero_real, '', '', $DB) === false) {
               $errores = true;
               $message = 'Error: No se pudo eliminar Servicio De Emergencia - cod: 08';
               goto result;
            }

         }

         # elimina boton
         if ($Boton->delete($DB) === false) {
            $errores = true;
            $mensaje = 'Error: No se pudo eliminar el boton - cod: 09';
            goto result;
         }

         $desc = 'SE ELIMINO SERVICO "Botón de emergencia SIP - Móvil" A USUARIO | BUSUA_COD: ' . ($Usuario->busua_cod);
         $Log->inserta($Operador->oper_cod, $desc, 'operator/json/json_deleteService.php', $DB);
   
         $mensaje = 'Servicio Botón de emergencia SIP - Móvil <span class="text-primary">eliminado</span>';

         break;

      case 2:

         # 2 - Botón de emergencia SIP - Estático

         # busca boton
         if ($Boton->busca($bot_cod, $DB) === false) {
            $errorres = true;
            $mensaje = 'Error: Servicio no encontrado - cod: 10';
            goto result;
         }

         # si el usuario no es el mismo no pasa
         if (intval($Usuario->busua_cod) !== intval($Boton->busua_cod)) {
            $errorres = true;
            $mensaje = 'Error: Usuario no corresponde al servicio - cod: 11';
            goto result;
         }

         # busca contact center
         if ($Grupo->busca($Usuario->group_cod, $DB) === false) {
            $errorres = true;
            $mensaje = 'Error: Contact center no encontrado - cod: 12';
            goto result;
         }

         # busca dominio
         if ($Dominio->busca($Grupo->dom_cod, $DB) === false) {
            $errorres = true;
            $mensaje = 'Error: Dominio no encontrado - cod: 13';
            goto result;
         }

         $sip_username = Parameters::generaSipUsername($Boton->sip_username, strlen($Boton->sip_username), 2);

         # busca Numero SOS
         if ($Numero->buscaNumSOS('533' . $Dominio->gate_cod . $sip_username, $Dominio->gate_cod, $DB) === true) {

            # elimina numero
            if ($Numero->delete(2, $DB) === false) {
               $errores = true;
               $message = 'Error: No se pudo eliminar Servicio De Emergencia - cod: 14';
               goto result;
            }

            # libera numero real
            if ($NumeroReal->ActualizaInterno($Numero->numero_real, '', '', $DB) === false) {
               $errores = true;
               $message = 'Error: No se pudo eliminar Servicio De Emergencia - cod: 15';
               goto result;
            }

         }

         # elimina boton
         if ($Boton->delete($DB) === false) {
            $errores = true;
            $mensaje = 'Error: No se pudo eliminar el boton - cod: 16';
            goto result;
         }

         $desc = 'SE ELIMINO SERVICO "Servicio Botón de emergencia SIP - Estático" A USUARIO | BUSUA_COD: ' . ($Usuario->busua_cod);
         $Log->inserta($Operador->oper_cod, $desc, 'operator/json/json_deleteService.php', $DB);

         $mensaje = 'Servicio Botón de emergencia SIP - Estático <span class="text-primary">eliminado</span>';

         break;

      case 4:

         # 4 - Widget

         if ($OtrosP->busca($bot_cod, $DB) === false) {
            $errores = true;
            $message = 'Error: Producto no encontrado - cod: 17';
            goto result;
         }

         if ($OtrosP->actualizaEstado(3, $DB) === false) {
            $errores = true;
            $message = 'Error: Producto no eliminado - cod: 18';
            goto result;
         }

         $desc = 'SE ELIMINO SERVICO "Widget" A USUARIO | BUSUA_COD: ' . ($Usuario->busua_cod);
         $Log->inserta($Operador->oper_cod, $desc, 'operator/json/json_deleteService.php', $DB);

         $mensaje = 'Servicio Widget <span class="text-primary">eliminado</span>';

         break;

      case 5:

         # 5 - Servicio de Tracker

         # verifica que exista el tipo de tracker
         if ($Tracker->busca($Usuario->busua_cod, $DB) === false) {
            $errorres = true;
            $mensaje = 'Error: Servicio de tracker no encontrado - cod: 19';
            goto result;
         }

         # modifica el estado a elimiando
         if ($Tracker->actualizaEstadoTipo(3, $DB) === false) {
            $errorres = true;
            $mensaje = 'Error: No se pudo eliminar servicio de tracker - cod: 20';
            goto result;
         }

         $desc = 'SE ELIMINO SERVICO "Tracker" A USUARIO | BUSUA_COD: ' . ($Usuario->busua_cod);
         $Log->inserta($Operador->oper_cod, $desc, 'operator/json/json_deleteService.php', $DB);

         $mensaje = 'Servicio Tracker <span class="text-primary">eliminado</span>';

         break;

      case 6:

         # 6 - Web RTC

         # busca boton
         if ($Boton->busca($bot_cod, $DB) === false) {
            $errorres = true;
            $mensaje = 'Error: Servicio no encontrado - cod: 21';
            goto result;
         }

         # si el usuario no es el mismo no pasa
         if (intval($Usuario->busua_cod) !== intval($Boton->busua_cod)) {
            $errorres = true;
            $mensaje = 'Error: Usuario no corresponde al servicio - cod: 22';
            goto result;
         }

         # busca contact center
         if ($Grupo->busca($Usuario->group_cod, $DB) === false) {
            $errorres = true;
            $mensaje = 'Error: Contact center no encontrado - cod: 23';
            goto result;
         }

         # busca dominio
         if ($Dominio->busca($Grupo->dom_cod, $DB) === false) {
            $errorres = true;
            $mensaje = 'Error: Dominio no encontrado - cod: 24';
            goto result;
         }

         $sip_username = Parameters::generaSipUsername($Boton->sip_username, strlen($Boton->sip_username), 3);

         # busca Numero SOS
         if ($Numero->buscaNumSOS('533' . $Dominio->gate_cod . $sip_username, $Dominio->gate_cod, $DB) === true) {

            # elimina numero
            if ($Numero->delete(2, $DB) === false) {
               $errores = true;
               $message = 'Error: No se pudo eliminar Servicio De Emergencia - cod: 25';
               goto result;
            }

            # libera numero real
            if ($NumeroReal->ActualizaInterno($Numero->numero_real, '', '', $DB) === false) {
               $errores = true;
               $message = 'Error: No se pudo eliminar Servicio De Emergencia - cod: 26';
               goto result;
            }

         }

         # elimina boton
         if ($Boton->delete($DB) === false) {
            $errores = true;
            $mensaje = 'Error: No se pudo eliminar el boton - cod: 27';
            goto result;
         }

         $desc = 'SE ELIMINO SERVICO "Web RTC" A USUARIO | BUSUA_COD: ' . ($Usuario->busua_cod);
         $Log->inserta($Operador->oper_cod, $desc, 'operator/json/json_deleteService.php', $DB);
   
         $mensaje = 'Servicio Web RTC <span class="text-primary">eliminado</span>';

         break;

      default:

         $errorres = true;
         $mensaje = 'Error: Servicio no encontrado - cod: 28';
         goto result;

         break;
   }

result:
   if ($errorres === true) {

      $data = array( 'status'    => 'err',
                     'message'   => $mensaje );
      echo json_encode($data);

   } else {

      $data = array( 'status'    => 'success',
                     'message'   => $mensaje );
      echo json_encode($data);

   }

   $DB->Logoff();
   $DB2->Logoff();
?>