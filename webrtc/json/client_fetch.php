<?
   # Funcion para enviar respuestas exitosas
   function sendResponse($data) {
      echo json_encode($data);
      exit();
   }

   # Funcion para enviar errores con un mensaje y código de estado
   function sendError($message, $errorCode, $statusCode = 400) {
      http_response_code($statusCode); # Establecer el código HTTP
      echo json_encode([
         "success"   => false,
         "errorCode" => $errorCode,
         "message"   => $message
      ]);
      exit();
   }

   # verificar si el parametro bot_cod está presente en la URL
   if (!isset($_GET['bot_cod']) || empty($_GET['bot_cod'])) {
      sendError("El parámetro bot_cod es obligatorio.", "FIELDS_EMPTY", 405);
   }

   # Sanitizar la entrada para evitar inyección SQL
   $bot_cod = htmlspecialchars(strip_tags($_GET['bot_cod']));

   require_once 'BConexion.php';
   require_once 'BBoton.php';
   require_once 'BUsuario.php';
   require_once 'BContactosLlamada.php';

   $DB         = new BConexion();
   $Boton      = new BBoton();
   $Usuario    = new BUsuario();
   $Contacto   = new BcontactosLlamada();

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

   $contacts = [];

   $stat = $Contacto->contactosAll($Usuario->busua_cod, $DB);

   while ($stat) {

      array_push($contacts, [
         'num'          => $Contacto->numero,
         'name'         => $Contacto->nombre,
         'state_call'   => $Contacto->estado_llamada,
         'state_sms'    => $Contacto->estado_sms,
         'state_listen' => $Contacto->estado_escucha
      ]);

      $stat = $Contacto->siguienteContactoAll($DB);

   }


   # obtener lista de contactos

   $data = [
      'busua_cod' => $Usuario->busua_cod,
      'user'      => [
         'name'            => $Usuario->nombre,
         'cloud_username'  => $Usuario->cloud_username,
         'user_phone'      => $Usuario->user_phone,
         'email'           => $Usuario->email
      ],
      'webrtc'    => [
         'bot_cod'            => $Boton->bot_cod,
         'sip_username'       => $Boton->sip_username,
         'sip_password'       => $Boton->sip_password,
         'sip_display_name'   => $Boton->sip_display_name,
         'esta_cod'           => $Boton->esta_cod,
         'tipo_cod'           => $Boton->tipo_cod,
         'tipo'               => $Boton->tipo,
         'localizacion'       => $Boton->localizacion,
         'mac'                => $Boton->mac
      ],
      'contacts'  => $contacts
   ];

   $DB->Logoff();

   # Enviar respuesta con el JWT
   sendResponse([
      "success" => true,
      "response" => $data
   ]);

?>