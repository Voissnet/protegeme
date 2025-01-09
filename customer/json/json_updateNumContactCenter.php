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
   
   if (!isset($_POST)) {
      $message = MOD_Error::Error('PBE_116');
      $error = true;
      goto result;
   }

   $group_cod  = isset($_POST['group_cod']) ? intval($_POST['group_cod']) : false;
   $num        = isset($_POST['num']) ? $_POST['num'] : false;

   if ($group_cod === false || $num === false) {
      $message = MOD_Error::Error('PBE_116');
      $error = true;
      goto result;
   }

   require_once 'BGrupo.php';
   require_once 'BLog.php';

   $Grupo   = new BGrupo();
   $Log     = new BLog();

   if ($Grupo->busca($group_cod, $DB) === false) {
      $message = MOD_Error::Error('PBE_137');
      $error = true;
      goto result;
   }

   if ($Grupo->actualizaNumeros($num . ';', $DB) === false) {
      $message = MOD_Error::Error('PBE_138');
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
         $Log->RegistraLinea('ADM: SE MODIFICO NUMERO CONTACT CENTER | DOM_COD: ' . ($Grupo->dom_cod) . ' | GROUP_COD: ' . ($Grupo->group_cod) . ' | NOMBRE CONTACT CENTER: ' . ($Grupo->nombre) . ' | NUMERO AGREGADOS: ' . ($Grupo->numeros));
   
         $data = array( 'status'    => 'success',
                        'message'   => 'N&uacute;mero Contact center <span class="text-primary">Modificado</span>',
                        'num'       => $num );
         echo json_encode($data);
   
      }
   
      $DB->Logoff();
   ?>