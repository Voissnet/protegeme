<?
   require_once 'BConexion.php';
   require_once 'BUsuarioRV.php';

   $DB      = new BConexion;
   $Usuario = new BUsuarioRV;

   $message = '';
   $error = false;

   if (!isset($_GET)) {
      $message = MOD_Error::ErrorCode('PBE_117');
      $error = true;
      goto result;
   }
   
   $username = isset($_GET['username']) ? $_GET['username'] : false;

   if ($username === false) {
      $message = MOD_Error::ErrorCode('PBE_117');
      $error = true;
      goto result;
   }

   if ($Usuario->CompruebaUsername($username, $DB) === true) {
      $message = 'No se puede utilzar este nombre de usuario';
      $error = true;
      goto result;
   }

result:
   if ($error === true) {

      $data = array( 'status'  => 'error',
                     'message' => $message );
      echo json_encode($data);

   } else {

      $data = array( 'status'    => 'success',
                     'message'   => 'Nombre de usuario disponible'
                  );
      echo json_encode($data);
      
   }

   $DB->Logoff();