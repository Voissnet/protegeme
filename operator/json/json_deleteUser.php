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
   
   $DB2     = new BConexion();
   $error   = false;
   $message = '';

   if (!isset($_POST)) {
      $message = MOD_Error::Error('PBE_116');
      $error = true;
      goto result;
   }

   # datos
   $busua_cod     = isset($_POST['busua_cod']) ? intval($_POST['busua_cod']) : false;

   if ($busua_cod === false) {
      $error = true;
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
   require_once 'BOperadorLog.php';

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
   $Log           = new BOperadorLog();

   # Busca al Usuario
   if ($Usuario->buscaUser($busua_cod, $DB) === false) {
      $error = true;
      $message = 'No se registra Usuario';
      goto result;
   }

   # Busca al grupo
   if ($Grupo->busca($Usuario->group_cod, $DB) === false) {
      $error = true;
      $message = 'No se registra grupo del Usuario';
      goto result;
   }

   # Busca dominio segun el grupo
   if ($Dominio->busca($Grupo->dom_cod, $DB) === false) {
      $error = true;
      $message = 'No se registra dominio del Usuario';
      goto result;
   }

   # Busca Adaptador SOS
   if ($Gateway->buscaGatewaySOS($Dominio->gate_cod, $DB) === false) {
      $error = true;
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
            $error = true;
            $message = 'Error: No se pudo eliminar Servicio De Emergencia - cod: 06';
            goto result;
         }

         # liberamos numero real
         if ($NumeroReal->ActualizaInterno($GatewayNumero->numero_real, '', '', $DB2) === false) {
            $error = true;
            $message = 'Error: No se pudo eliminar Servicio De Emergencia - cod: 07';
            goto result;
         }

      }

      if ($Boton->delete($DB2) === false) {
         $error = true;
         $message = 'Error: No se pudo eliminar el boton - cod: 08';
         goto result;
      }

      $statService = $Boton->siguienteUserActivo($DB);
   }

   # Consulta si el Usuario tenia servicio de tracker
   if ($Tracker->busca($Usuario->busua_cod, $DB) === true) {

      if ($Tracker->actualizaEstado(3, $DB) === false) {
         $error = true;
         $message = 'Error: No se pudo eliminar el tracker - cod: 09';
         goto result;
      }
      
   }

   if ($Usuario->delete($DB) === false) {
      $error = true;
      $message = 'Error: No se pudo eliminar Usuario - cod: 10';
      goto result;
   }

   # elimina contatcos de emrgencia llamadas
   $stat = $ContactoLlama->buscaContactosD($busua_cod, $DB);

   while ($stat) {
      if ($ContactoLlama->deleteAll($DB2) === false) {
         $error = true;
         $message = 'Error: No se pudo eliminar los contatcos de emergencia - Llamadas - cod: 11';
         goto result;
      }
      $stat = $ContactoLlama->siguiente($DB);
   }
 
   # elimina contactos de emergencia SMS
   $stat2 = $ContactoSMS->buscaContactosD($busua_cod, $DB);

   while ($stat2) {
      if ($ContactoSMS->deleteAll($DB2) === false) {
         $error = true;
         $message = 'Error: No se pudo eliminar los contactos de emergencia - SMS - cod: 12';
         goto result;
      }
      $stat2 = $ContactoSMS->siguiente($DB);
   }
 
   # elimina otros productos
   $stat3 = $Otros->buscaProducto($Usuario->busua_cod, $DB);

   while ($stat3) {
      if ($Otros->actualizaEstado(3, $DB2) === false) {
         $error = true;
         $message = 'Error: No se pudo eliminar otros servicios - cod: 13';
         goto result;
      }
      $stat3 = $Otros->siguienteProducto($DB);
   }

   $desc = 'USUARIO BP ELIMINADO | BUSUA_COD: ' . $busua_cod . ' | CLOUD_USERNAME: ' . $Usuario->cloud_username  . ' | NOMBRE USUARIO: ' . $Usuario->nombre . ' | TELÉFONO USUARIO: ' . $Usuario->user_phone . ' | EMAIL: ' . $Usuario->email;
   
   if ($Log->inserta($Operador->oper_cod, $desc, 'operator/json/json_deleteUser.php', $DB) === false) {
      $error = true;
      $message = 'Error: No se pudo eliminar otros servicios - cod: 14';
      goto result;
   }

result:
   if ($error === true) {

      $data = array( 'status'    => 'error',
                     'message'   => $message );
      echo json_encode($data);

   } else {

      $data = array( 'status'    => 'success',
                     'message'   => 'Usuario y servicios <span class="text-primary">eliminados</span> del dominio',
                     'busua_cod' => $busua_cod,
                     'group_cod' => $Grupo->group_cod,
                     'dom_cod'   => $Grupo->dom_cod );
      echo json_encode($data);

   }
   $DB->Logoff();
   $DB2->Logoff();
?>