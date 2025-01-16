<?
   # lista de dominios permitidos
   $allowed_origins = [
      'https://newbackoffice.lanube.cl',
      'https://newbackoffice.redvoiss.net'
   ];

   # obtener el dominio de origen de la solicitud
   $origin = $_SERVER['HTTP_ORIGIN'] ?? '';

   # Verificar si el dominio de origen está en la lista permitida
   if (in_array($origin, $allowed_origins)) {
      header('Access-Control-Allow-Origin: ' . $origin);
   }

   # establecer otros encabezados
   header('Access-Control-Allow-Methods: POST');
   header('Access-Control-Allow-Headers: Content-Type');

   require_once 'Parameters.php';

   # verificar si los parámetros 'dom_cod' y 'tipo_cod' están presentes y son válidos
   $dom_cod    = isset($_GET['dom_cod']) ? intval($_GET['dom_cod']) : null;
   $tipo_cod   = isset($_GET['tipo_cod']) ? intval($_GET['tipo_cod']) : null;

   # verificar si los parámetros son válidos
   if ($dom_cod === null || $tipo_cod === null) {
      http_response_code(400);
      echo json_encode([
         'error' => 'Los datos no pudieron ser procesado. El archivo no se pudo procesar.'
      ]);
      exit;
   }

   # si el servicio es 0 no puede mandar mail
   if ($tipo_cod === 0) {
      http_response_code(400);
      echo json_encode([
         'error' => 'Debe ingresar/seleccionar tipo de servicio.'
      ]);
      exit;
   }

   # variable que contiene el nombre de archivo txt
   $servicio  = Parameters::obtieneNombrePlantilla($tipo_cod);

   # ruta final del archivo dominio(cod)+servicio.txt
   $ruta = $dom_cod . '/' . $servicio . '.txt';

   # ruta al archivo de texto en el servidor
   if (file_exists($ruta)) {

      # leer el contenido del archivo
      $contenido = file_get_contents($ruta);
      http_response_code(200);
      echo json_encode([
         'contenido' => $contenido,
         'default'   => 'NO',
      ]);

   } else {

      # esto es en caso de que no exista uno personalizado por el cliente
      # verifica archivo por default.txt

      if (file_exists($servicio . '_default.txt')) {

         # leer el contenido del archivo encontrado
         $contenido = file_get_contents($servicio . '_default.txt');
         http_response_code(200);
         echo json_encode([
            'contenido' => $contenido,
            'default'   => 'SI',
         ]);

      } else {

         # responder con un mensaje de error
         http_response_code(404);
         echo json_encode([
            'error' => 'Solicitud incorrecta. El archivo no se pudo procesar.'
         ]);

      }

   }
?>