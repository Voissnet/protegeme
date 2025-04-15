<?
   require_once 'Parameters.php';
   
   # Funcion para decodificar Base64URL
   function base64url_decode($data) {
      $padding = strlen($data) % 4;
      if ($padding) {
         $data .= str_repeat('=', 4 - $padding);
      }
      return base64_decode(strtr($data, '-_', '+/'));
   }

   # Leer los datos enviados en la solicitud
   $data = json_decode(file_get_contents("php://input"), true);

   # Verificar si el token está presente
   if (!isset($data['token'])) {
      http_response_code(401); # Codigo de estado para no autorizado
      echo json_encode([
         "success"   => false,
         "message"   => "Codigo de estado para no autorizado",
         "error"     => "session_expired1"
      ]);
      exit();
   }
   
   $jwt     = $data['token'];                                  # token
   $secret  = 'ViOzY0f/uxmmfEeGU89jQn0+CTIsLIgH8e8MtrQxDSs=';  # secret

   // Dividir el JWT en partes
   list($header_encoded, $payload_encoded, $signature_encoded) = explode('.', $jwt);

   // Recalcular la firma
   $signature = base64url_decode($signature_encoded);
   $valid_signature = hash_hmac('sha256', "$header_encoded.$payload_encoded", $secret, true);

   // Verificar la firma
   if (!hash_equals($signature, $valid_signature)) {
      http_response_code(401); # Firma invalida
      echo json_encode([
         "success"   => false,
         "message"   => "Firma invalida",
         "error"     => "session_expired2"
      ]);
      exit();
   }

   // Decodificar el payload
   $payload = json_decode(base64url_decode($payload_encoded), true);

   // Validar la expiración (exp)
   if (isset($payload['exp']) && time() > $payload['exp']) {
      http_response_code(401); # Token expirado
      echo json_encode([
         "success"   => false,
         "message"   => "Token expirado",
         "error"     => "invalid_session"
      ]);
      exit();
   }

   // Desencriptar los valores
   foreach ($payload as $clave => $valor) {
      if (is_string($valor)) {
         $payload[$clave] = Parameters::openCypher('decrypt', $valor);
      }
   }


   # Si todo es válido, se da acceso
   http_response_code(200); # paso
   echo json_encode(["success" => true, "message" => "Acceso autorizado", "data" => $payload]);


