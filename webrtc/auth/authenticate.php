<?php
session_start();

header('Content-Type: application/json'); # Establecer encabezado JSON

# Constantes
define('SESSION_TIMEOUT', 6 * 30 * 24 * 60 * 60); # 6 meses en segundos
define('COOKIE_DURATION', 6 * 30 * 24 * 60 * 60); # 6 meses

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
$cloud_username = $params[0];
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

# Verificar tiempo de sesión activo
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT)) {
   # La sesión expiró, destruir sesión y redirigir
   setcookie("user_session", "", time() - 3600, "/", "", true, true);
   session_unset();
   session_destroy();
   sendError("Tu sesión ha expirado. Por favor, inicia sesión nuevamente.", "SESSION_EXPIRED", 401);
}

# Si el usuario es válido
if ($Usuario->autenticaUsuario($cloud_username, trim($cloud_password), $domain, $DB) === true) {

   if ($Boton->buscaUserTipo($Usuario->busua_cod, 6, $DB) === true) {

      // Datos adicionales que deseas almacenar
      $userData = [
         'busua_cod'       => Parameters::openCypher('encrypt', $Usuario->busua_cod),
         'cloud_username'  => Parameters::openCypher('encrypt', $Usuario->cloud_username),
         'domain_user'     => Parameters::openCypher('encrypt', $Usuario->dominio_usuario),
         'bot_cod'         => Parameters::openCypher('encrypt', $Boton->bot_cod),
         'sip_username'    => Parameters::openCypher('encrypt', $Boton->sip_username),
         'sip_password'    => Parameters::openCypher('encrypt', $Boton->sip_password),
      ];
      
      // Convertir los datos a JSON
      $userDataJson = json_encode($userData);

      # Establecer cookie y actualizar la última actividad
      setcookie("user_session", $userDataJson, time() + COOKIE_DURATION, "/", "", true, true);
      $_SESSION['last_activity'] = time(); # Registrar la última actividad
      
      # Respuesta exitosa
      sendResponse([
         'success' => true,
         'message' => 'Inicio de sesión exitoso.',
         'data'    => urlencode($userDataJson)
      ]);

   } else {

      $DB->Logoff();
      sendError("Servicio no encontrado.", "WEB_RTC_NOT_FOUND", 404);

   }

} else {

   $DB->Logoff();
   sendError("Usuario no encontrado.", "USER_NOT_FOUND", 404);

}
?>