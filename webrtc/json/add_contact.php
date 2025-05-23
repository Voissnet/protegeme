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

   function formatNum($numero) {

      # Eliminar todos los caracteres no numericos
      $numeroLimpio = preg_replace('/\D/', '', $numero);

      # Si el numero ya incluye el prefijo internacional (56), lo dejamos tal cual
      if (preg_match('/^56\d{6,9}$/', $numeroLimpio)) {
         return $numeroLimpio;
      }

      # Validar largo permitido (ej: 6 a 9 digitos locales)
      if (preg_match('/^\d{6,9}$/', $numeroLimpio)) {
         $numeroConPrefijo = "56" . $numeroLimpio;
         return $numeroConPrefijo;
      }

      return false; # Numero no valido: debe contener entre 6 y 9 digitos.

   }

   function sanitize($value) {
      return htmlspecialchars(strip_tags($value ?? ''), ENT_QUOTES, 'UTF-8');
   }  

   # Detectar si el request es JSON (segun encabezado)
   $contentType = $_SERVER["CONTENT_TYPE"] ?? '';

   if (strpos($contentType, 'application/json') !== false) {

      # Leer el cuerpo crudo
      $rawData = file_get_contents("php://input");

      # Decodificar a array asociativo
      $json = json_decode($rawData, true);

      # Asignar los parametros esperados
      $busua_cod = sanitize($json['busua_cod'] ?? '');
      $name      = sanitize($json['name'] ?? '');
      $num       = sanitize($json['num'] ?? '');
      $call      = sanitize($json['call'] ?? '');
      $sms       = sanitize($json['sms'] ?? '');
      $listen    = sanitize($json['listen'] ?? '');

   } else {

      // Fallback para peticiones tipo application/x-www-form-urlencoded
      $busua_cod = sanitize($_POST['busua_cod'] ?? '');
      $name      = sanitize($_POST['name'] ?? '');
      $num       = sanitize($_POST['num'] ?? '');
      $call      = sanitize($_POST['call'] ?? '');
      $sms       = sanitize($_POST['sms'] ?? '');
      $listen    = sanitize($_POST['listen'] ?? '');

   }

   # clases
   require_once 'BConexion.php';
   require_once 'BUsuarioRV.php';
   require_once 'BGateway.php';
   require_once 'BDominio.php';
   require_once 'BGrupo.php';
   require_once 'BUsuario.php';
   require_once 'BContactosLlamada.php';
   require_once 'BContactosSMS.php';
   require_once 'BLog.php';


   $DB         = new BConexion();
   $UsuarioRV  = new BUsuarioRV();
   $Gateway    = new BGateway();
   $Dominio    = new BDominio();
   $Grupo      = new BGrupo();
   $Usuario    = new BUsuario();
   $Llamada    = new BContactosLlamada();
   $SMS        = new BContactosSMS();
   $Log        = new BLog();

   # Verifica si el usuario existe
   if (!$Usuario->busca($busua_cod, $DB)) {
      $DB->Logoff();
      sendError("El usuario especificado no se encuentra registrado en el sistema.", "INVALID_USER", 400);
   }

   # Valida que el usuario este activo
   if (intval($Usuario->esta_cod) !== 1) {
      $DB->Logoff();
      sendError("El usuario se encuentra inactivo o suspendido. Contacte al administrador.", "INACTIVE_USER", 400);
   }

   # formatea numero 
   $num = formatNum($num);

   if (!$num) {
      $DB->Logoff();
      sendError("Número no cumple el formato adecuado.", "INACTIVE_NUM", 400);
   }

   # Verifica duplicacion del numero de contacto en llamadas
   if ($Llamada->busca($busua_cod, $num, $DB)) {
      $DB->Logoff();
      sendError("El número de contacto ya está registrado para este usuario. (1)", "DUPLICATE_CONTACT", 400);
   }

   # Verifica duplicacion del numero de contacto en SMS
   if ($SMS->busca($busua_cod, $num, $DB) === true) {
      $DB->Logoff();
      sendError("El número de contacto ya está registrado para este usuario. (2)", "DUPLICATE_CONTACT", 400);
   }

   # si es escucha se debe validar la cantidad, solo puede ser 2
   # en este caso me centro cuando quieran activar un escucha, recuerda es inverso
   if (intval($listen) === 1) {
      if (intval($Llamada->cantidadEscucha($Usuario->busua_cod, $DB)) > 1 ) {
         $DB->Logoff();
         sendError("Ha alcanzado el límite permitido de contactos con preferencia 'Escucha'.", "MAX_LIMIT", 400);
      } 
   }

   # valida nombre de contacto
   $nameContact = strlen($name) === 0 ? null : $name;

   $DB->BeginTrans();

   # registra contacto en la tabla de llamada
   if (!$Llamada->insertContact($Usuario->busua_cod, $num, $nameContact, $call, $listen, $DB)) {
      $DB->Rollback();
      $DB->Logoff();
      sendError("No fue posible registrar el contacto. Verifique que la información no esté duplicada o intente nuevamente. (1)", "CONTACT_REGISTRATION_FAILED", 400);
  }
  
   # registra contacto en la tabla sms
   if (!$SMS->insert($Usuario->busua_cod, $num, $sms, $DB)) {
      $DB->Rollback();
      $DB->Logoff();
      sendError("No fue posible registrar el contacto. Verifique que la información no esté duplicada o intente nuevamente. (2)", "CONTACT_REGISTRATION_FAILED", 400);
   }

   # busca Contact Center
   if (!$Grupo->busca($Usuario->group_cod, $DB)) {
      $DB->Rollback();
      $DB->Logoff();
      sendError("No se pudo localizar el centro de contacto asignado.", "CONTACT_CENTER_NOT_FOUND", 400);
   }

   # Contact Center debe estar activo
   if (intval($Grupo->esta_cod) !== 1) {
      $DB->Rollback();
      $DB->Logoff();
      sendError("El centro de contacto asociado se encuentra inactivo.", "CONTACT_CENTER_INACTIVE", 400);
   }

   # Busca Dominio
   if ($Dominio->busca($Grupo->dom_cod, $DB) === false) {
      $DB->Rollback();
      $DB->Logoff();
      sendError("No fue posible recuperar el dominio correspondiente al grupo. Verifique la estructura organizacional.", "DOMAIN_NOT_FOUND", 400);
   }

   # Dominio debe estar activo
   if (intval($Dominio->esta_cod) !== 1) {
      $DB->Rollback();
      $DB->Logoff();
      sendError("El dominio asociado se encuentra inactivo.", "DOMAIN_INACTIVE", 400);
   }

   # Busca datos del servicio de emergencia (Gateway SOS)
   if ($Gateway->buscaGatewaySOS($Dominio->gate_cod, $DB) === false) {
      $DB->Rollback();
      $DB->Logoff();
      sendError("No se encontraron los datos del servicio de emergencia para el dominio especificado.", "SOS_GATEWAY_NOT_FOUND", 400);
   }

   # Busca datos del usuario remoto vinculado al Gateway
   if ($UsuarioRV->Busca($Gateway->usua_cod, $DB) === false) {
      $DB->Rollback();
      $DB->Logoff();
      sendError("No fue posible validar el usuario", "REMOTE_USER_NOT_FOUND", 400);
   }

   $DB->Commit();

   sleep(0.8);

      $mensaje = 'Hola, '. $Usuario->nombre . ' te ha inscrito como contacto para emergencias en Protegeme.
Mas informacion en www.protegeme.cl';
      
      // manda un sms
      file_get_contents('https://micuenta.redvoiss.net/cuenta/productos/sms/url/envio.php?usuario=' . urlencode($UsuarioRV->username) . '&password=' . urlencode($UsuarioRV->password) . '&destino=' . urlencode($num) . '&mensaje=' . urlencode($mensaje));

   $DB->Logoff();

   sendResponse([
      "success"   => true,
      "message"   => "Contacto registrado."
   ]);

?>