<?
   require_once 'Parameters.php';
   require_once 'BConexion.php';
   require_once 'BUsuarioRV.php';
   require_once 'BUsuario.php';

   $UsuarioRV  = new BUsuarioRV;
   $DB         = new BConexion;

   $UsuarioRV->sec_session_start();
   if ($UsuarioRV->VerificaLogin($DB) === FALSE)
   {
      $DB->Logoff();
      header("Location: " . Parameters::WEB_PATH . "/customer/login/");
      exit;
   }
   $Usuario = new BUsuario;
   if ($Usuario->inserta($_POST["cloud_username"], $_POST["password"], $_POST["user_phone"], $_POST["email"], $_POST["nombre"], $_POST["group_cod"], $DB) === TRUE)
      echo "Insertado";
   else
      echo "ERROR";
   $DB->Logoff();
?>