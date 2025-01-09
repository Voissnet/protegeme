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

   if ($group_cod === false) {
      $message = MOD_Error::Error('PBE_116');
      $error = true;
      goto result;
   }

   require_once 'BUsuario.php';
   require_once 'BGrupo.php';
   require_once 'BLog.php';

   $Usuario = new BUsuario();
   $Grupo   = new BGrupo();
   $Log     = new BLog();

   if ($Grupo->busca($group_cod, $DB) === false) {
      $message = 'No existe Contact Center';
      $error = true;
      goto result;
   }

   // Condiciones:
   // 1. Grupo debe estar vacio (pueden mover los usuarios)
   // 2. Usuarios eliminaos

   $stat = $Usuario->buscaUsuariosGroup($Grupo->group_cod, $DB);

   if ($stat === true) {

      $delete = true;

      // aca recorremos todos los usuario de ese grupo, si encuentra usuarios con esta_cod (1, 2) no se pude eliminar
      while ($stat) {
         
         if ($Usuario->esta_cod !== '3') {

            $delete = false;

         }

         $stat = $Usuario->siguiente($DB);

      }

      if ($delete === false) {
         
         $message = 'No todos los usuarios estan eliminados o movidos del <span class="text-primary">Contact Center</span>';
         $error = true;
         goto result;
      
      } else {

         if ($Grupo->actualizaEstado($Grupo->group_cod, 3, $DB) === false) {

            $message = 'No se pudo eliminar el Contact Center';
            $error = true;
            goto result;

         }

      }

   } else {

      // aca se entiende que esta vacio, asi que pasa al proceso de eliminacion
      if ($Grupo->actualizaEstado($Grupo->group_cod, 3, $DB) === false) {

         $message = 'No se pudo eliminar el Contact Center';
         $error = true;
         goto result;

      }

   }

result:
   if ($error === true) {

      $data = array( 'status'  => 'error',
                     'message' => $message );
      echo json_encode($data);

   } else {

      $path_log = Parameters::PATH . '/log/site_adm.log';
      $Log->CreaLogTexto($path_log);
      $Log->RegistraLinea('ADM: SE ELIMINO CONTACT CENTER | DOM_COD: ' . ($Grupo->dom_cod) . ' | GROUP_COD: ' . ($Grupo->group_cod) . ' | NOMBRE CONTACT CENTER: ' . ($Grupo->nombre) . ' | NUMEROS AGREGADOS: ' . ($Grupo->numeros));

      $data = array( 'status'    => 'success',
                     'message'   => 'Contact center <span class="text-primary">' . $Grupo->nombre . '</span> eliminado' );
      echo json_encode($data);

   }

   $DB->Logoff();
?>