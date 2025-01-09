<?
   require_once 'BConexion.php';
   require_once 'BAutorizacion.php';

   $DB = new BConexion;
   $Auth = new BAutorizacion;

   $outputxml = $Auth->GetXML_FAIL();
   if ($_GET["sip_auth_method"] == "INVITE")
      if($Auth->Autoriza($_GET["user"], $_GET["domain"], $DB) === TRUE)
         if ($Auth->ObtieneCredencialesRV($Auth->cloud_username, $_GET["domain"], $DB) === TRUE)
            $outputxml = $Auth->GetXML_OK();

   header('Content-Type: application/xml');
   echo $outputxml;
   $DB->Logoff();
   
?>