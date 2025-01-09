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

   $message = '';
   $error = false;

   if (!isset($_POST)) {
      $message = MOD_Error::ErrorCode('PBE_116');
      $error = true;
      goto result;
   }

   # datos
   $dom_cod    = isset($_POST['dom_cod']) ? intval($_POST['dom_cod']) : false;
   $busua_cod  = isset($_POST['busua_cod']) ? intval($_POST['busua_cod']) : false;
   $tipo_cod   = isset($_POST['tipo_cod']) ? intval($_POST['tipo_cod']) : false;

   if ($busua_cod === false || $dom_cod === false || $tipo_cod === false) {
      $error   = true;
      $message = 'Error: No se registran datos - cod: 01';
      goto result;
   }

   require_once 'BUsuario.php';
   require_once 'BGrupo.php';
   require_once 'BDominio.php';
   require_once 'BBoton.php';
   require_once 'BOtrosProductos.php';
   require_once 'BTracker.php';
   require_once 'BLog.php';
   
   $Usuario = new BUsuario();
   $Grupo   = new BGrupo();
   $Dominio = new BDominio();
   $Boton   = new BBoton();
   $Otros   = new BOtrosProductos();
   $Tracker = new BTracker();
   $Log     = new BLog();

   // busca usuario
   if ($Usuario->buscaUser($busua_cod, $DB) === false) {
      $error   = true;
      $message = 'Error: No se encuentra usuario - cod: 02';
      goto result;
   }

   // busca grupo
   if ($Grupo->busca($Usuario->group_cod, $DB) === false) {
      $error   = true;
      $message = 'Error: No se grupo del usuario - cod: 03';
      goto result;
   }

   // busca dominio
   if ($Dominio->busca($Grupo->dom_cod, $DB) === false) {
      $error   = true;
      $message = 'Error: No se dominio del usuario - cod: 04';
      goto result;
   }

   if (intval($Dominio->dom_cod) !== $dom_cod) {
      $error   = true;
      $message = 'Error: dominio no coincide con usuario - cod: 05';
      goto result;
   }

   switch ($tipo_cod) {

      case 1:

         # Botón de emergencia SIP - Móvil

         if ($Boton->buscaUserTipo($Usuario->busua_cod, $tipo_cod, $DB) === false) {
            $error   = true;
            $message = 'Error: No se encontro servicio del usuario - cod: 06';
            goto result;
         }

         if (intval($Boton->esta_cod) !== 1) {
            $error   = true;
            $message = 'Error: Usuario no esta activo, no se puede notificar - cod: 07';
            goto result;
         }

         break;

      case 2:

         # Botón de emergencia SIP - Estático

         if ($Boton->buscaUserTipo($Usuario->busua_cod, $tipo_cod, $DB) === false) {
            $error   = true;
            $message = 'Error: No se encontro servicio del usuario - cod: 08';
            goto result;
         }

         if (intval($Boton->esta_cod) !== 1) {
            $error   = true;
            $message = 'Error: Usuario no esta activo, no se puede notificar - cod: 09';
            goto result;
         }

         break;

      case 3:

         # Botón de emergencia Estándar
         
         if ($Otros->buscaTipo($Usuario->busua_cod, $tipo_cod, $DB) === false) {
            $error   = true;
            $message = 'Error: Usuario no esta activo, no se puede notificar - cod: 10';
            goto result;
         }

         if (intval($Otros->esta_cod) !== 1) {
            $error   = true;
            $message = 'Error: Usuario no esta activo, no se puede notificar - cod: 11';
            goto result;
         }

         break;

      case 4:

         # Widget

         if ($Otros->buscaTipo($Usuario->busua_cod, $tipo_cod, $DB) === false) {
            $error   = true;
            $message = 'Error: Usuario no esta activo, no se puede notificar - cod: 12';
            goto result;
         }

         if (intval($Otros->esta_cod) !== 1) {
            $error   = true;
            $message = 'Error: Usuario no esta activo, no se puede notificar - cod: 13';
            goto result;
         }

         break;

      case 5:

         # Tracker

         if ($Tracker->busca($Usuario->busua_cod, $DB) === false) {
            $error   = true;
            $message = 'Error: No se encontro servicio del usuario - cod: 14';
            goto result;
         }

         if (intval($Tracker->esta_cod) !== 1) {
            $error   = true;
            $message = 'Error: No se encontro servicio del usuario - cod: 15';
            goto result;
         }

         break;

      default:

         # Servicio no existe
         $error   = true;
         $message = 'Error: Servicio no encontrado - cod: 16';
         goto result;

         break;

   }

   $desc = 'USUARIO NOTIFICADO | BUSUA_COD: ' . $Usuario->busua_cod;
   $path_log = Parameters::PATH . '/log/site_adm.log';
   $Log->CreaLogTexto($path_log);
   $Log->RegistraLinea('ADM: ' . $desc);


result:
   if ($error === true) {

      $data = array( 'status'    => 'err',
                     'message'   => $message );
      echo json_encode($data);

   } else {

      $token   = Parameters::openCypher('encrypt', 'SResDvO2!9$32#01widJys56!?1ads');
      $du      = Parameters::openCypher('encrypt', $Dominio->dom_cod);
      $bu      = Parameters::openCypher('encrypt', $Usuario->busua_cod);
      $ts      = Parameters::openCypher('encrypt', $tipo_cod);

      $data = array( 'status'    => 'success',
                     'token'     => $token,
                     'dom_cod'   => $du,
                     'busua_cod' => $bu,
                     'tipo_cod'  => $ts );
      echo json_encode($data);

   }

   $DB->Logoff();
?>