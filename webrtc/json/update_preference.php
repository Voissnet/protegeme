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
      $bot_cod = htmlspecialchars(strip_tags($json['bot_cod'] ?? ''));
      $num     = htmlspecialchars(strip_tags($json['num'] ?? ''));
      $service = htmlspecialchars(strip_tags($json['service'] ?? ''));
      $state   = htmlspecialchars(strip_tags($json['state'] ?? ''));

   } else {

      # Fallback si llegara por POST tradicional
      $bot_cod = htmlspecialchars(strip_tags($_POST['bot_cod'] ?? ''));
      $num     = htmlspecialchars(strip_tags($json['num'] ?? ''));
      $service = htmlspecialchars(strip_tags($_POST['service'] ?? ''));
      $state   = htmlspecialchars(strip_tags($_POST['state'] ?? ''));

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
   $ContactoLL    = new BContactosLlamada();
   $ContactoSMS   = new BContactosSMS();

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

   # nos centramos en el estado 1, si es 1 es porque debe ir inactivo, si es distinto lo activamos
   switch (intval($service)) {
      case 1:

         if (!$ContactoLL->actualiza($Usuario->busua_cod, $num, ($state == 1 ? 2 : 1), $DB)) {
            $DB->Logoff();
            sendError("Estado llamda no pudo ser actualizado.", "INVALID_UPDATE_CALL", 400);
         }
         
         if ($state == 1) {

            if (!$ContactoLL->actualizaEscucha($Usuario->busua_cod, $num, 2, $DB)) {
               $DB->Logoff();
               sendError("Estado escucha no pudo ser actualizado", "INVALID_UPDATE_LISTEN_2", 400);
            }
         }

         break;
      case 2:
         
         if (!$ContactoSMS->actualiza($Usuario->busua_cod, $num, ($state == 1 ? 2 : 1), $DB)) {
            $DB->Logoff();
            sendError("Estado sms no pudo ser actualizado", "INVALID_UPDATE_SMS", 400);
         }

         break;
      case 3:
         
         # validar que tenga activado las llamadas
         if (!$ContactoLL->busca($Usuario->busua_cod, $num, $DB)) {
            $DB->Logoff();
            sendError("No se encuentra usuario", "NOT_FOUND_USER_CALL", 404);
         }

         if (intval($ContactoLL->esta_cod) === 2) {
            $DB->Logoff();
            sendError("Debe tener habilitado 'Llamadas' para tener esta opción", "NOT_FOUND_USER_CALL", 404);
         }

         # si es escucha se debe validar la cantidad, solo puede ser 2
         # en este caso me centro cuando quieran activar un escucha, recuerda es inverso
         if ($state == 0 || $state == 2) {
            if (intval($ContactoLL->cantidadEscucha($Usuario->busua_cod, $DB)) > 1 ) {
               $DB->Logoff();
               sendError("Ha alcanzado el límite permitido de contactos con preferencia 'Escucha'.", "MAX_LIMIT", 400);
            } 
         }

         # actualiza escuche
         if (!$ContactoLL->actualizaEscucha($Usuario->busua_cod, $num, ($state == 1 ? 0 : 1), $DB)) {
            $DB->Logoff();
            sendError("Estado escucha no pudo ser actualizado", "INVALID_UPDATE_LISTEN", 400);
         }

         break;    
   }

   # busca los datos nuevos
   if (!$ContactoLL->buscaContacto($Usuario->busua_cod, $num, $DB)) {
      $DB->Logoff();
      sendError("No registra el contacto.", "INVALID_CONTACT", 400);
   }

   $contact = [
      'num'          => $ContactoLL->numero,
      'name'         => $ContactoLL->nombre,
      'state_call'   => $ContactoLL->estado_llamada,
      'state_sms'    => $ContactoLL->estado_sms,
      'state_listen' => $ContactoLL->estado_escucha
   ];

   $DB->Logoff();

   sendResponse([
      "success"   => true,
      "contact"  => $contact
   ]);

?>