<?
   # Funcion para enviar respuestas exitosas
   function sendResponse($data) {
      echo json_encode($data);
      exit();
   }

   # Funcion para enviar errores con un mensaje y codigo de estado
   function sendError($message, $errorCode, $statusCode = 400) {
      http_response_code($statusCode);
      echo json_encode([
         "success"   => false,
         "errorCode" => $errorCode,
         "message"   => $message
      ]);
      exit();
   }

   # Detectar si el request es JSON (segun encabezado)
   $contentType = $_SERVER["CONTENT_TYPE"] ?? '';

   if (strpos($contentType, 'application/json') !== false) {

      # Leer el cuerpo crudo
      $rawData = file_get_contents("php://input");

      # Decodificar a array asociativo
      $json = json_decode($rawData, true);

      # Asignar los parametros esperados
      $busua_cod  = htmlspecialchars(strip_tags($json['busua_cod'] ?? ''));
      $bot_cod    = htmlspecialchars(strip_tags($json['bot_cod'] ?? ''));
      $num        = htmlspecialchars(strip_tags($json['num'] ?? ''));

   } else {

      # Fallback si llegara por POST tradicional
      $busua_cod  = htmlspecialchars(strip_tags($_POST['busua_cod'] ?? ''));
      $bot_cod    = htmlspecialchars(strip_tags($json['bot_cod'] ?? ''));
      $num        = htmlspecialchars(strip_tags($_POST['num'] ?? ''));

   }

   # clases
   require_once 'BConexion.php';
   require_once 'BBoton.php';
   require_once 'BUsuario.php';
   require_once 'BContactosLlamada.php';
   require_once 'BContactosSMS.php';

   $DB            = new BConexion();
   $Boton         = new BBoton();
   $Usuario       = new BUsuario();
   $Llamada       = new BContactosLlamada();
   $SMS           = new BContactosSMS();

   # busca boton
   if (!$Boton->busca($bot_cod, $DB)) {
      $DB->Logoff();
      sendError("Boton WebRTC no registrado.", "INVALID_BOTON", 400);
   }

   # valida que boton este activo
   if (intval($Boton->esta_cod) !== 1) {
      $DB->Logoff();
      sendError("Boton WebRTC no esta activo.", "INVALID_STATE", 400);
   }

   # valida usuario
   if (!$Usuario->busca($Boton->busua_cod, $DB)) {
      $DB->Logoff();
      sendError("Boton WebRTC no esta activo.", "INVALID_STATE_WEBRTC", 400);
   }

   # valida que usuario este activo
   if (intval($Usuario->esta_cod) !== 1) {
      $DB->Logoff();
      sendError("Boton WebRTC no esta activo.", "INVALID_STATE_USER", 400);
   }

   # id del usuario debe ser igual al que esta llegando
   if ($busua_cod !== $Usuario->busua_cod) {
      $DB->Logoff();
      sendError("Usuario no encontrado.", "USER_NOT_FOUND", 400);
   }

   if ($Llamada->busca($Usuario->busua_cod, $num, $DB)) {

      if (!$Llamada->delete($Usuario->busua_cod, $Llamada->numero, $DB)) {
         $DB->Logoff();
         sendError("Llamadas no eliminadas", "CALL_NOT_DELETE", 400);
      }

   }

   if ($SMS->busca($busua_cod, $num, $DB)) {

      if (!$SMS->delete($Usuario->busua_cod, $SMS->numero, $DB)) {
         $DB->Logoff();
         sendError("Llamadas no eliminadas", "CALL_NOT_DELETE", 400);
      }

   }

   $DB->Logoff();

   sendResponse([
      "success"   => true
   ]);

?>