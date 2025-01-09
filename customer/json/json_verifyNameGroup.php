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
   $error   = false;

   if (!isset($_GET)) {
      $message = MOD_Error::ErrorCode('PBE_116');
      $error = true;
      goto result;
   }

   $dom_cod = isset($_GET['dom_cod']) ? $_GET['dom_cod'] : false;
   $nombre  = isset($_GET['nombre']) ? $_GET['nombre'] : false;

   if ($dom_cod === false) {
      $message = MOD_Error::ErrorCode('PBE_116');
      $error = true;
      goto result;
   }

   if ($dom_cod === false || $nombre === false) {
      $message = MOD_Error::ErrorCode('PBE_116');
      $error = true;
      goto result;
   }

   require_once 'BGrupo.php';

   $Grupo = new BGrupo();

   if ($Grupo->verificaNombre($dom_cod, $nombre, $DB) === true) {
      $message = 'Nombre de Contact Center:  <span class="text-primary">' . $nombre . '</span> existe en el dominio';
      $error = true;
      goto result;
   }

result:
   if ($error === true) {

      $data = array( 'status'  => 'error',
                     'message' => $message );
      echo json_encode($data);

   } else {

      $data = array( 'status'  => 'success',
                     'message' => 'Nombre disponible' );
      echo json_encode($data);
         
   }
   $DB->Logoff();
?>