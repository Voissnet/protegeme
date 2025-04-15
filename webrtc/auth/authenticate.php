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

   # Función para codificar Base64URL
   function base64url_encode($data) {
      return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
   }

   # Generar un JWT con HS256
   function create_jwt_hs256($payload, $secret) {

      # Crear el header
      $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
      $header_encoded = base64url_encode($header);

      # Crear el payload
      $payload_encoded = base64url_encode(json_encode($payload));

      # Crear la firma con HMAC-SHA256
      $signature = hash_hmac('sha256', "$header_encoded.$payload_encoded", $secret, true);
      $signature_encoded = base64url_encode($signature);

      # Ensamblar el JWT
      return "$header_encoded.$payload_encoded.$signature_encoded";

   }

   # Verificar el método HTTP
   if ($_SERVER["REQUEST_METHOD"] != "POST") {
      sendError("Método de solicitud no permitido.", "METHOD_NOT_ALLOWED", 405);
   }

   # Leer los datos enviados en la solicitud
   $data = json_decode(file_get_contents("php://input"), true);
   $userInput = $data['user'] ?? '';
   $cloud_password = $data['password'] ?? '';

   # Validar que los campos no estén vacíos
   if (empty(trim($userInput)) || empty(trim($cloud_password))) {
      sendError("Todos los campos son obligatorios.", "FIELDS_EMPTY", 400);
   }

   # Dividir el usuario en nombre y dominio
   if (strpos($userInput, '@') === false) {
      sendError("El formato del usuario es inválido. Debe contener un '@' y su dominio.", "INVALID_FORMAT", 400);
   }

   $params = explode('@', trim($userInput)); # Separar nombre y dominio
   $cloud_username = mb_strtolower($params[0]);
   $domain = $params[1];

   # Validar que tanto el nombre como el dominio existan
   if (empty($cloud_username) || empty($domain)) {
      sendError("El formato del usuario es inválido. Falta el nombre o el dominio.", "INVALID_USERNAME_DOMAIN", 400);
   }

   require_once 'BConexion.php';
   require_once 'BUsuario.php';
   require_once 'BBoton.php';

   $DB      = new BConexion();
   $Usuario = new BUsuario();
   $Boton   = new BBoton();

   # Si el usuario es válido
   if ($Usuario->autenticaUsuario($cloud_username, trim($cloud_password), $domain, $DB) === false) {
      $DB->Logoff();
      sendError("Usuario no encontrado.", "USER_NOT_FOUND", 404);
   }

   # valida si es web rtc
   if ($Boton->buscaUserTipo($Usuario->busua_cod, 6, $DB) === false) {
      $DB->Logoff();
      sendError("Servicio no encontrado.", "WEB_RTC_NOT_FOUND", 404);
   }

   # Clave secreta (Base64, al menos 32 bytes)
   $secret = 'ViOzY0f/uxmmfEeGU89jQn0+CTIsLIgH8e8MtrQxDSs=';

   # Datos del payload (contenido del token)
   $payload = [
      'sub'          => Parameters::openCypher('encrypt', $Usuario->busua_cod),                       # ID del usuario
      'name'         => Parameters::openCypher('encrypt', $Usuario->nombre),                          # Nombre del usuario
      'username'     => Parameters::openCypher('encrypt', $Usuario->cloud_username),                  # Nombre de usuario cuenta
      'domain'       => Parameters::openCypher('encrypt', $Usuario->dominio_usuario),                 # Dominio usuario
      'domain_sip'   => Parameters::openCypher('encrypt', $Usuario->dominio),                         # Dominio sip
      'num_cc'       => Parameters::openCypher('encrypt', str_replace(';', '', $Usuario->numeros)),   # Numero contact center
      'bot_cod'      => Parameters::openCypher('encrypt', $Boton->bot_cod),                           # Cod boton WEB RTC
      'sip_username' => Parameters::openCypher('encrypt', $Boton->sip_username),                      # Sip username WEB RTC
      'sip_password' => Parameters::openCypher('encrypt', $Boton->sip_password),                      # Sip password WEB RTC
      'iat'          => time(),                                                                       # Inicio sesion en linux
      'exp'          => time() + 7889400                                                              # Expira en 3 meses
   ];

   # Generar el JWT
   $jwt = create_jwt_hs256($payload, $secret);

   $DB->Logoff();

   # Enviar respuesta con el JWT
   sendResponse([
      "success" => true,
      "token"   => $jwt
   ]);

?>