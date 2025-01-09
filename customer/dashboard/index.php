<?

use Fernet\Fernet;

   require_once 'Parameters.php';
   require_once 'BConexion.php';
   require_once 'BUsuarioRV.php';


   $UsuarioRV  = new BUsuarioRV;
   $DB         = new BConexion;

   $UsuarioRV->sec_session_start();
   if ($UsuarioRV->VerificaLogin($DB) === FALSE)
   {
      $DB->Logoff();
      header("Location: " . Parameters::WEB_PATH . "/customer/login/");
      exit;
   }



?>
   <iframe src="https://pbe.redvoiss.net:9025/dashboard?hash=$2y$10$JtE5WuDB8lPMRmIXGmag3.B4kirExdzl.y3OjEuyTPgRujgBLpNrS&semilla=m4a78t-e" title="description"></iframe>
