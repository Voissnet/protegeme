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

   $data             = json_decode(file_get_contents('php://input'), true);
   $dom_cod          = isset($data['dom_cod']) ? intval($data['dom_cod']) : false;
   $arr_nums_groups  = isset($data['arr_nums_groups']) ? $data['arr_nums_groups'] : false;

   if ($dom_cod === false || $arr_nums_groups === false) {
      $message = MOD_Error::Error('PBE_116');
      $error = true;
      goto result;
   }

   require_once 'BDominio.php';
   require_once 'BGrupo.php';
   require_once 'BLog.php';

   $Dominio = new BDominio();
   $Grupo   = new BGrupo();
   $Log     = new BLog();

   $info_groups = [];

   if ($Dominio->busca($dom_cod, $DB) === false) {
      $message = MOD_Error::Error('PBE_116');
      $error = true;
      goto result;
   }

   $path_log = Parameters::PATH . '/log/site_adm.log';
   $Log->CreaLogTexto($path_log);

   for ($i = 0; $i < count($arr_nums_groups); $i++) { 

      $nombre_grupo  = $arr_nums_groups[$i]['name_group'];
      $numeros       = $arr_nums_groups[$i]['nums_groups'];

      if ($Grupo->insert($nombre_grupo, $Dominio->dom_cod, $numeros, $DB) === false) {
         $message = MOD_Error::Error('PBE_116');
         $error = true;
         goto result;
      
      } else {

         $Log->RegistraLinea('ADM: NUEVO CONTACT CENTER CREADO | DOM_COD: ' . $Dominio->dom_cod . ' | GROUP_COD: ' . ($Grupo->group_cod) . ' | NOMBRE CONTACT CENTER: ' . ($nombre_grupo) . ' | NUMEROS AGREGADOS: ' . ($numeros));

         array_push($info_groups, [
            $Grupo->group_cod,
            $nombre_grupo,
            $numeros
         ]);

      }

   }

result:
   if ($error === true) {

      $data = array( 'status'  => 'error',
                     'message' => $message );
      echo json_encode($data);

   } else {

      $data = array( 'status'       => 'success',
                     'message'      => 'Contact Center <span class="text-primary">Ingresado</span>',
                     'info_groups'  => $info_groups );
      echo json_encode($data);

   }

   $DB->Logoff();
?>