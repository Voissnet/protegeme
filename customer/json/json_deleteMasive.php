<?
   require_once 'MOD_Error.php';
   require_once 'Parameters.php';
   require_once 'BConexion.php';
   require_once 'BUsuarioRV.php';

   $UsuarioRV  = new BUsuarioRV;
   $DB         = new BConexion;

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

   $errores = false;
   $message = '';
   $DB2     = new BConexion();

   if (!isset($_POST)) {
      $errores = true;
      $message = 'Error: No se registran datos - cod: 00';
      goto result;
   }

   # datos
   $data    = json_decode(file_get_contents('php://input'), true);
   $users   = isset($data['users']) ? $data['users'] : false;
   
   if ($users === false) {
      $errores = true;
      $message = 'Error: No se registran datos - cod: 01';
      goto result;
   }

   # Clases
   require_once 'BGateway.php';
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
   require_once 'BLog.php';

   $Gateway       = new BGateway();
   $GatewayNumero = new BGatewayNumero();
   $NumeroReal    = new BGLBLnumeroReal();
   $Dominio       = new BDominio();
   $Grupo         = new BGrupo();
   $Usuario       = new BUsuario();
   $Boton         = new BBoton();
   $Tracker       = new BTracker();
   $Otros         = new BOtrosProductos();
   $ContactoLlama = new BContactosLlamada();
   $ContactoSMS   = new BContactosSMS();
   $Log           = new BLog();

   $path_log = Parameters::PATH . '/log/site_adm.log';
   $Log->CreaLogTexto($path_log);

   for ($i = 0; $i < count($users); $i++) { 
      
      $busua_cod = $users[$i];

      # Busca al Usuario
      if ($Usuario->buscaUser($busua_cod, $DB) === false) {
         $errores = true;
         $message = 'Error: No se registra Usuario - cod: 02';
         goto result;
      }

      # Busca al grupo
      if ($Grupo->busca($Usuario->group_cod, $DB) === false) {
         $errores = true;
         $message = 'Error: No se registra grupo del Usuario - cod: 03';
         goto result;
      }

      # Busca dominio segun el grupo
      if ($Dominio->busca($Grupo->dom_cod, $DB) === false) {
         $errores = true;
         $message = 'Error: No se registra dominio del Usuario - cod: 04';
         goto result;
      }

      # Busca Adaptador SOS
      if ($Gateway->buscaGatewaySOS($Dominio->gate_cod, $DB) === false) {
         $errores = true;
         $message = 'Error: No se registra Servicio De Emergencia - cod: 05';
         goto result;
      }

      $statService = $Boton->buscaUserActivo($Usuario->busua_cod, $DB);

      while ($statService) {

         $sip_username = '';

         switch ($Boton->tipo_cod) {
            case '1':
               $sip_username = Parameters::generaSipUsername($Boton->sip_username, strlen($Boton->sip_username), 1);
               break;
            case '2':
               $sip_username = Parameters::generaSipUsername($Boton->sip_username, strlen($Boton->sip_username), 2);
               break;
            case '6':
               $sip_username = Parameters::generaSipUsername($Boton->sip_username, strlen($Boton->sip_username), 3);
               break;
         }

         # busca Numero SOS
         if ($GatewayNumero->buscaNumSOS('533' . $Gateway->gate_cod . $sip_username, $Gateway->gate_cod, $DB2) === true) {
            
            # eliminamos numero interno
            if ($GatewayNumero->delete(2, $DB2) === false) {
               $errores = true;
               $message = 'Error: No se pudo eliminar Servicio De Emergencia - cod: 06';
               goto result;
            }

            # liberamos numero real
            if ($NumeroReal->ActualizaInterno($GatewayNumero->numero_real, '', '', $DB2) === false) {
               $errores = true;
               $message = 'Error: No se pudo eliminar Servicio De Emergencia - cod: 07';
               goto result;
            }

         }

         if ($Boton->delete($DB2) === false) {
            $errores = true;
            $message = 'Error: No se pudo eliminar el boton - cod: 08';
            goto result;
         }
         
         $statService = $Boton->siguienteUserActivo($DB);

      }

       # Consulta si el Usuario tenia servicio de tracker
       if ($Tracker->busca($Usuario->busua_cod, $DB) === true) {

         if ($Tracker->actualizaEstado(3, $DB) === false) {
            $errores = true;
            $message = 'Error: No se pudo eliminar el tracker - cod: 09';
            goto result;
         }

      }

      if ($Usuario->delete($DB) === false) {
         $errores = true;
         $message = 'Error: No se pudo eliminar Usuario - cod: 10';
         goto result;
      }

      # elimina contatcos de emrgencia llamadas
      $stat = $ContactoLlama->buscaContactos($Usuario->busua_cod, $DB);
   
      while ($stat) {
         if ($ContactoLlama->deleteAll($DB2) === false) {
            $errores = true;
            $message = 'Error: No se pudo eliminar los contatcos de emergencia - Llamadas - cod: 11';
            goto result;
         }
         $stat = $ContactoLlama->siguiente($DB);
      }
   
      # elimina contactos de emergencia SMS
      $stat2 = $ContactoSMS->buscaContactosD($Usuario->busua_cod, $DB);
   
      while ($stat2) {
         if ($ContactoSMS->deleteAll($DB2) === false) {
            $errores = true;
            $message = 'Error: No se pudo eliminar los contactos de emergencia - SMS - cod: 12';
            goto result;
         }
         $stat2 = $ContactoSMS->siguiente($DB);
      }

      # elimina otros productos
      $stat3 = $Otros->buscaProducto($Usuario->busua_cod, $DB);

      while ($stat3) {
         if ($Otros->actualizaEstado(3, $DB2) === false) {
            $errores = true;
            $message = 'Error: No se pudo eliminar otros servicios - cod: 13';
            goto result;
         }
         $stat3 = $Otros->siguienteProducto($DB);
      }

      $Log->RegistraLinea('ADM: USUARIO ELIMINADO | BUSUA_COD: ' . ($Usuario->busua_cod) . ' | DOM_COD: ' . ($Dominio->dom_cod)  . ' | GROUP_COD: ' . ($Usuario->group_cod));

   }

result:
   if ($errores === true) {

      $data = array( 'status'    => 'error',
                     'message'   => $errores );
      echo json_encode($data);

   } else {

      $data = array( 'status'    => 'success',
                     'message'   => 'Usuarios y servicios <span class="text-primary">eliminados</span> del dominio',
                     'busua_cod' => $busua_cod,
                     'group_cod' => $Grupo->group_cod,
                     'dom_cod'   => $Grupo->dom_cod );
      echo json_encode($data);
   }
   $DB->Logoff();
   $DB2->Logoff();
?>