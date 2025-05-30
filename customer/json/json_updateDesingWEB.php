<?
   require_once 'Parameters.php';
   require_once 'MOD_Error.php';
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
   
   $DB2 = new BConexion();

   if (!isset($_POST)) {
      $message = MOD_Error::Error('PBE_116');
      $error = true;
      goto result;
   }

   $data       = json_decode(file_get_contents('php://input'), true);
   $dom_cod    = isset($data['dom_cod']) ? $data['dom_cod'] : false;
   $fondo      = isset($data['background-web']) ? $data['background-web'] : false;
   $buttons    = isset($data['color-buttons-web']) ? $data['color-buttons-web'] : false;
   $colorL     = isset($data['color-letters-web']) ? $data['color-letters-web'] : false;
   $tamano_web = isset($data['tamano-font']) ? $data['tamano-font'] : false;

   if ($dom_cod === false || $fondo === false || $buttons === false || $colorL === false) {
      $message = MOD_Error::Error('PBE_116');
      $error = true;
      goto result;
   }

   require_once 'BDominio.php';
   require_once 'BDesign.php';
   require_once 'BLog.php';

   $Dominio    = new BDominio();
   $Desin      = new BDesign();
   $Log        = new BLog();

   if ($Dominio->busca($dom_cod, $DB) === false) {
      $message = MOD_Error::Error('PBE_116');
      $error = true;
      goto result;
   }

   if ($Desin->actualizaWEB($Dominio->dom_cod, $fondo, $buttons, $colorL, $tamano_web, $DB) === false) {
      $message = MOD_Error::Error('PBE_116');
      $error = true;
      goto result;
   }

result:
   if ($error === true) {

      $data = array( 'status'  => 'error',
                     'message' => $message );
      echo json_encode($data);

   } else {

      $path_log = Parameters::PATH . '/log/site_adm.log';
      $Log->CreaLogTexto($path_log);
      $Log->RegistraLinea('ADM: DISEÑO ACTUALIZADO WEB | FONDO: ' . $fondo . ' | BOTONES Y TABLAS: ' . $buttons . ' | COLOR DE LETRAS: ' . $colorL . ' | TAMANO DE FUENTE: ' . $tamano_web);

      $data = array( 'status'             => 'success',
                     'message'            => 'Dise&ntilde;o WEB personalizado con exito' );
      echo json_encode($data);

   }

   $DB->Logoff();
?>