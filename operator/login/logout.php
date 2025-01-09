<?
   require_once 'MOD_Error.php';
   require_once 'BConexion.php';
   require_once 'BOperador.php';

   $Operador  = new BOperador;
   $DB        = new BConexion;

   $Operador->sec_session_start();
   if ($Operador->VerificaLogin($DB) === FALSE)
   {
      $DB->Logoff();
      MOD_Error::Error("PBE_112");
      exit;
   }

   $Operador->Logout();
   $DB->Logoff();