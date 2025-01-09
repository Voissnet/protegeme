<?
   require_once 'BConexion.php';
   require_once 'BNEContacts.php';

   $DB         = new BConexion;
   $Contacts   = new BNEContacts;

   $contactossms  = '';
   $stat = $Contacts->PrimeroNumeroSMS($_GET["sip_username"], $_GET["dominio"], $DB);
   while($stat)
   {
      $contactossms = $contactossms . $Contacts->numero_sms . ";";
      $stat = $Contacts->SiguienteNumeroSMS($DB);
   }
   $contactossms = rtrim($contactossms, ';');


   $contactosllamada = '';
   $stat = $Contacts->PrimeroNumeroVoz($_GET["sip_username"], $_GET["dominio"], $DB);
   while($stat)
   {
      $contactosllamada = $contactosllamada . $Contacts->numero_voz . ";";
      $stat = $Contacts->SiguienteNumeroVoz($DB);
   }
   $contactosllamada = rtrim($contactosllamada, ';');

   $respuesta = array("contactossms"      => $contactossms,
                      "contactosllamada"  => $contactosllamada);

    http_response_code(200);
    header('Content-type: application/json');
    echo json_encode($respuesta,JSON_UNESCAPED_UNICODE);

    $DB->Logoff();
