<?
   require_once 'BConexion.php';
   require_once 'Parameters.php';
   require_once 'BLog.php';
   require_once 'BBoton.php';
   require_once 'BUsuario.php';
 
   /*
      $p_peppered = hash_hmac("sha256", "pepito1", Parameters::PEPPER);
      $encriptado = password_hash($p_peppered, PASSWORD_BCRYPT);
      echo $encriptado;
      exit;
   */

   $DB   = new BConexion;
   $Log  = new BLog;
   $path_log = Parameters::PATH . "/log/requires.log";
   $Log->CreaLogTexto($path_log);

   if ( !isset($_GET['cloud_username']) || !isset($_GET['cloud_password']) || !isset($_GET['cloud_id']) || !isset($_GET['initialScreen']) || !isset($_GET['plataforma']) || !isset($_GET['appversion']) || !isset($_GET['appbuild']) || !isset($_GET['device']) || !isset($_GET['versionplataforma']))
   {
      $Log->RegistraLinea("ERROR: Faltan variables");
      echo Parameters::ErrorXML("Faltan variables" );
      $DB->Logoff();
      exit;
   }

   $cloudusername       = trim(strtolower($_GET['cloud_username']));
   $cloudpassword       = $_GET['cloud_password'];
   $cloudId             = strtolower($_GET['cloud_id']);
   $initialScreen       = $_GET['initialScreen'];
   $plataforma          = $_GET['plataforma'];
   $appversion          = $_GET['appversion'];
   $appbuild            = $_GET['appbuild'];
   $device              = $_GET['device'];
   $versionplataforma   = $_GET['versionplataforma'];

   $Log->RegistraLinea("------------- Requerimiento desde servidor ---------------");
   $Log->RegistraLinea("\$cloudUsername = " . $cloudusername . " | " . "\$cloudId = " . $cloudId . " | " . "\$initialScreen = " . $initialScreen . " | " . "\$plataforma = " . $plataforma);
   $Log->RegistraLinea("\$versionplataforma = " . $versionplataforma . " | " . "\$appversion = " . $appversion . " | " . "\$appbuild = " . $appbuild . " | " . "\$device = " . $device);

   /* Detectar si es prueba o real */
   if ($cloudId === 'boton*' || $cloudId === 'be*' || $cloudId === 'be')
   {
      $parts         = explode('@', $cloudusername);
      $cloudusername = $parts[0];
      $cloudId       = $parts[1];
   }

   /* AUTENTICA */
   $Usuario = new BUsuario;
   if ($Usuario->autenticaUsuario($cloudusername, $cloudpassword, $cloudId, $DB) === TRUE)
   {
      $Log->RegistraLinea("Usuario " . $cloudusername . "@" .  $cloudId . " encontrado y autenticado");
      
      /*Busca los datos del botón del usuario. UN USUARIO => UN SOLO BOTÓN */
      $Boton = new BBOton;
      if ($Boton->BuscaBoton($Usuario->busua_cod, $DB) === TRUE)
      {
         if ($Boton->esta_cod === '1')
         {

            $xmlstr = "<account> 
               <title>" . $Boton->sip_display_name . "</title>
               <cloud_username>" . $Usuario->cloud_username . "@" . $Usuario->dominio_usuario . "</cloud_username>
               <cloud_password>" . $_GET['cloud_password'] . "</cloud_password>
               <username>" . $Boton->sip_username . "</username>
               <password>" . $Boton->sip_password . "</password>
               <host>" . $Usuario->dominio . "</host>
               <authUsername>" . $Boton->sip_username . "</authUsername>
               <extProvInterval>0</extProvInterval>
               <transport>tcp</transport>
               <icm>off</icm>
               <requiresRegistrationForOutgoingCalls>0</requiresRegistrationForOutgoingCalls>
            </account>";
            // } else {

            //    $xmlstr = "<account> 
            //       <title>" . $Boton->sip_display_name . "</title>
            //       <cloud_username>" . $Usuario->cloud_username . "@" . $Usuario->dominio_usuario . "</cloud_username>
            //       <cloud_password>" . $_GET['cloud_password'] . "</cloud_password>
            //       <username>" . $Boton->sip_username . "</username>
            //       <password>" . $Boton->sip_password . "</password>
            //       <host>" . $Usuario->dominio . "</host>
            //       <authUsername>" . $Boton->sip_username . "</authUsername>
            //       <extProvInterval>0</extProvInterval>
            //       <transport>udp</transport>
            //       <STUN>stun1.l.google.com:19302</STUN>
            //       <icm>off</icm>
            //       <requiresRegistrationForOutgoingCalls>0</requiresRegistrationForOutgoingCalls>
            //       <outboundProxy_enabled>1</outboundProxy_enabled>
            //       <outboundProxy_host>" . $Usuario->dominio . "</outboundProxy_host>
            //       <outboundProxy_transport>udp</outboundProxy_transport>
            //    </account>";

            // }
               
               $xml = new SimpleXMLElement($xmlstr);
               $Log->RegistraLinea("Se envia XML a servidor XML=" . $xml->asXml() );
               echo $xml->asXml();
               header("Content-type:text/xml");
               $Log->RegistraLinea("Se ha enviado el XML con exito" );
         }
         else
         {
            $Log->RegistraLinea("ERROR: botón no está activo");
            echo Parameters::ErrorXML("ERROR: Botón inactivo");
         }
      }
      else
      {
         $Log->RegistraLinea("ERROR: No se ha podido encontrar botón para el usuario busua_cod = " . $Usuario->busua_cod);
         echo Parameters::ErrorXML("ERROR: No se ha podido encontrar botón para el usuario  " . $Usuario->busua_cod);
      }
      /* GENERANDO XML */
      $Log->RegistraLinea("Generando XML...");
   }
   else
   {
      $Log->RegistraLinea("ERROR: No se ha encontrado usuario " . $cloudusername . " en dominio " . $cloudId . " o password inválido");
      echo Parameters::ErrorXML("No se ha encontrado usuario en dominio " . $cloudId . " o password inválido");
   }
   $DB->logoff();