<?
   header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
   header('Expires: Sat, 1 Jul 2000 05:00:00 GMT'); // Fecha en el pasado

   require_once 'Parameters.php';
   require_once 'MOD_Error.php';
   require_once 'MOD_ReCaptcha.php';
   require_once 'BConexion.php';
   require_once 'BLog.php';
   require_once 'BBoton.php';
   require_once 'BUsuario.php';

   $Log  = new BLog;
   $path_log = Parameters::PATH . '/log/sites.log';
   $Log->CreaLogTexto($path_log);

   $captcha          = isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : false;
   $username         = isset($_POST['username']) ? $_POST['username'] : false;
   $cloud_password   = isset($_POST['password']) ? $_POST['password'] : false;

   if ($captcha === false || $username === false || $cloud_password === false) {
      $Log->RegistraLinea('ERROR: No se puede conectar al sitio');
      MOD_Error::Error('PBE_101', 2);
      exit;
   }

   if (MOD_ReCaptcha::Valida($captcha) === false) {
      $Log->RegistraLinea('ERROR: No se puede autenticar usuario');
      MOD_Error::Error('PBE_102', 2);
      exit;
   }

   $parts = explode('@', $username);

   if (count($parts) <= 1) {
      $Log->RegistraLinea('ERROR: No se puede autenticar usuario');
      MOD_Error::Error('PBE_109', 2);
      exit;
   }

   $cloud_username   = trim(strtolower($parts[0]));
   $dominio_usuario  = trim(strtolower($parts[1]));

   $DB               = new BConexion();
   $Usuario          = new BUsuario();
   $Boton            = new BBoton();

   if ($Usuario->autenticaUsuario($cloud_username, $cloud_password, $dominio_usuario, $DB) === false) {
      $Log->RegistraLinea('ERROR: No se puede autenticar usuario');
      $DB->Logoff();
      MOD_Error::Error('PBE_103', 2);
      exit;
   }

   if ($Boton->BuscaBoton($Usuario->busua_cod, $DB) === false) {
      $Log->RegistraLinea('ERROR: No se puede encontrar servicio del usuario');
      $DB->Logoff();
      MOD_Error::Error('PBE_110', 2);
      exit;
   }

?>
   <form id="form-login-user-send" method="POST" action="../account/index.php">
      <input type="hidden" id="username" name="username" value="<?= $username ?>">
      <input type="hidden" id="cloud_password" name="cloud_password" value="<?= $cloud_password ?>">
   </form>

   <script type="text/javascript">
      localStorage.clear();
      document.addEventListener('DOMContentLoaded', function(event) {
         document.getElementById('form-login-user-send').submit();
      });
   </script>
   <?
   $DB->Logoff();
?>