<?
   require_once 'MOD_Error.php';
   require_once 'BConexion.php';
   require_once 'BUsuarioRV.php';

   $UsuarioRV  = new BUsuarioRV;
   $DB         = new BConexion;

   $UsuarioRV->sec_session_start();
   if ($UsuarioRV->VerificaLogin($DB) === FALSE)
   {
      $DB->Logoff();
      MOD_Error::Error("PBE_112");
      exit;
   }

   $UsuarioRV->Logout();
   $DB->Logoff();