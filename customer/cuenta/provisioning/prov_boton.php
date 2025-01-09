<?
   require_once 'Parameters.php';
   require_once 'MOD_Error.php';
   require_once 'BConexion.php';
   require_once 'BUsuarioRV.php';
   require_once 'BUsuario.php';
   require_once 'BBoton.php';

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
   
   if ($Usuario->busca($_POST["busua_cod"], $DB) === TRUE)
   {
      if ($Usuario->Pertenece($_POST["busua_cod"], $UsuarioRV->usua_cod, $DB) === TRUE)
      {
         $Boton = new BBoton;
         if ($Boton->Aprovisiona($_POST["sip_username"], $_POST["password"], $_POST["sip_display_name"], $_POST["busua_cod"], $_POST["tipo_cod"], $_POST["localizacion"], $Usuario->gate_cod, $DB) === FALSE)
         {
            MOD_Error::Error("PBE_123");
            $DB->logoff();
            exit;
         }
      }
      else
      {
         MOD_Error::Error("PBE_125");
         $DB->logoff();
         exit;
      }
   }
   else
   {
      MOD_Error::Error("PBE_124");
      $DB->logoff();
      exit;
   }

   $DB->Logoff();
?>
<html lang="es">
   <head>
      <title>Aprovisionamiento exitoso</title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
   </head>
   <body>
      <h2>Aprovisionamiento exitoso:</h2>
      <div>
         ¡El aprovisionamiento del botón de pánico ha sido exitoso!
         <p><input type="button" name="home" value="VOLVER" id="home" Onclick="location='../reporting/reporte_usuarios.php'"></p>
      </div>
   </body>
</html>