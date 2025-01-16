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

   $error   = false;
   $message = '';
   $statusCode;

   $data = json_decode(file_get_contents('php://input'), true);
   
   # verificar si el JSON es válido
   if (json_last_error() !== JSON_ERROR_NONE) {
      $error      = true;
      $statusCode = 400;
      $message    = 'Parámetro inválido (0)';
      goto result;
   }
   
   # asignar los datos recibidos
   $token            = $data['token'] ?? null;
   $dom_cod          = $data['dom_cod'] ?? null;
   $busua_cod        = $data['busua_cod'] ?? null;
   $tipo_cod         = $data['tipo_cod'] ?? null;
   $op               = $data['op'] ?? null;
   $password_post    = $data['cloud_password'] ?? null;
   
   # Validar si los datos requeridos están presentes
   if ($token && $dom_cod && $busua_cod && $tipo_cod && $op) {

      # Desencriptar los valores si todo está presente
      $token      = Parameters::openCypher('decrypt', $token);
      $dom_cod    = intval(Parameters::openCypher('decrypt', $dom_cod));
      $busua_cod  = intval(Parameters::openCypher('decrypt', $busua_cod));
      $tipo_cod   = intval(Parameters::openCypher('decrypt', $tipo_cod));
      $op         = intval($op);

   } else {

      $error      = true;
      $statusCode = 400;
      $message    = 'Plantilla no se pudo procesar (1).';
      goto result;

   }

   if ($token !== 'SResDvO2!9$32#01widJys56!?1ads') {
      
      $error      = true;
      $statusCode = 400;
      $message    = 'Plantilla no se pudo procesar (2).';
      goto result;

   }

   # si el servicio es 0 no puede mandar mail
   if ($tipo_cod === 0) {

      $error      = true;
      $statusCode = 400;
      $message    = 'Debe ingresar/seleccionar tipo de servicio (3).';
      goto result;

   }

   if ($op === 1) {

      $password_post = Parameters::openCypher('decrypt', $password_post);

   }

   require_once 'BConexion.php';
   require_once 'SEmail.php';
   require_once 'BUsuario.php';
   require_once 'BGrupo.php';
   require_once 'BDominio.php';
   require_once 'BBoton.php';
   require_once 'BOtrosProductos.php';
   require_once 'BTracker.php';   

   $DB      = new BConexion();
   $Usuario = new BUsuario();
   $Grupo   = new BGrupo();
   $Dominio = new BDominio();
   $Boton   = new BBoton();
   $Otros   = new BOtrosProductos();
   $Tracker = new BTracker();

   # parametros para notificacion
   $nombre_email        = '';
   $usuario_email       = '';
   $password_email      = $password_post;
   $correo_email        = '';
   $subject             = '';

   $tipo_servicio       = '';
   $fecha_notificacion  = '';

   # busca usuario
   if ($Usuario->buscaUser($busua_cod, $DB) === false) {

      $error      = true;
      $statusCode = 400;
      $message    = 'Plantilla no se pudo procesar (4).';
      goto result;

   }

   // busca grupo
   if ($Grupo->busca($Usuario->group_cod, $DB) === false) {

      $error      = true;
      $statusCode = 400;
      $message    = 'Plantilla no se pudo procesar (5).';
      goto result;

   }

   // busca dominio
   if ($Dominio->busca($Grupo->dom_cod, $DB) === false) {

      $error      = true;
      $statusCode = 400;
      $message    = 'Plantilla no se pudo procesar (6).';
      goto result;

   }

   if (intval($Dominio->dom_cod) !== $dom_cod) {

      $error      = true;
      $statusCode = 400;
      $message    = 'Plantilla no se pudo procesar (7).';
      goto result;

   }

   # variable que contiene el nombre de archivo txt
   $nombre_servicio  = Parameters::obtieneNombrePlantilla($tipo_cod);
   $contenido        = Parameters::contenidoPlantilla($Dominio->dom_cod, $nombre_servicio);

   if ($contenido === false) {

      $error      = true;
      $statusCode = 400;
      $message    = 'Solicitud incorrecta. El archivo no se pudo procesar (8).';
      goto result;

   }

   # verifica si hay password
   $status_password  = (stripos($contenido, '{password}') !== false) ? true : false;

   # TIPO DE SERVICIOS:
   # 1 - Botón de emergencia SIP - Móvil
   # 2 - Botón de emergencia SIP - Estático
   # 3 - Botón de emergencia Estándar
   # 4 - Widget
   # 5 - Tracker
   # 6 - Web RTC
   # Default: Otros productos

   switch ($tipo_cod) {

      case 1:

         # Botón de emergencia SIP - Móvil

         $tipo_servicio = 'Botón de emergencia SIP - Móvil';

         # busca boton del usuario
         if ($Boton->buscaUserTipo($Usuario->busua_cod, $tipo_cod, $DB) === false) {

            $error      = true;
            $statusCode = 400;
            $message    = 'Solicitud incorrecta. El archivo no se pudo procesar (9).';
            goto result;

         }

         # servicio debe estar activo
         if (intval($Boton->esta_cod) !== 1) {

            $error      = true;
            $statusCode = 400;
            $message    = 'Solicitud incorrecta. El archivo no se pudo procesar (10).';
            goto result;

         }

         if ($op === 2 && $status_password === true) {

            $cloud_password   = Parameters::generaPasswordSIP(7);
            $p_peppered       = hash_hmac('sha256', $cloud_password, Parameters::PEPPER);
            $encriptado       = password_hash($p_peppered, PASSWORD_BCRYPT);

            # actualiza cloud_password
            if ($Usuario->actualizaCloudPassword($encriptado, $DB) === false) {

               $error      = true;
               $statusCode = 400;
               $message    = 'Solicitud incorrecta. El archivo no se pudo procesar (11).';
               goto result;

            }

            $password_email   = $cloud_password;

         }

         # actualiza fecha de notificacion
         if ($Boton->actualizaNotificacion($DB) === false) {

            $error      = true;
            $statusCode = 400;
            $message    = 'Solicitud incorrecta. El archivo no se pudo procesar (12).';
            goto result;

         }

         $fecha_notificacion  = $Boton->fecha_notificacion;

         # asunto de correo
         $subject = 'Notificación servicio ' . (intval($Dominio->demo) === 1 ? 'demo ' : '') . 'Protegeme';

         break;

      case 2:

         # Botón de emergencia SIP - Estático

         $tipo_servicio = 'Botón de emergencia SIP - Estático';

         # busca boton del usuario
         if ($Boton->buscaUserTipo($Usuario->busua_cod, $tipo_cod, $DB) === false) {

            $error      = true;
            $statusCode = 400;
            $message    = 'Solicitud incorrecta. El archivo no se pudo procesar (13).';
            goto result;

         }

         # servicio debe estar activo
         if (intval($Boton->esta_cod) !== 1) {

            $error      = true;
            $statusCode = 400;
            $message    = 'Solicitud incorrecta. El archivo no se pudo procesar (14).';
            goto result;

         }

         if ($op === 2 && $status_password === true) {
            
            $cloud_password   = Parameters::generaPasswordSIP(7);
            $p_peppered       = hash_hmac('sha256', $cloud_password, Parameters::PEPPER);
            $encriptado       = password_hash($p_peppered, PASSWORD_BCRYPT);

            # actualiza cloud_password
            if ($Usuario->actualizaCloudPassword($encriptado, $DB) === false) {

               $error      = true;
               $statusCode = 400;
               $message    = 'Solicitud incorrecta. El archivo no se pudo procesar (15).';
               goto result;
               
            }

            $password_email   = $cloud_password;

         }

         # actualiza fecha de notificacion
         if ($Boton->actualizaNotificacion($DB) === false) {

            $error      = true;
            $statusCode = 400;
            $message    = 'Solicitud incorrecta. El archivo no se pudo procesar (16).';
            goto result;

         }

         $fecha_notificacion  = $Boton->fecha_notificacion;

         # asunto de correo
         $subject = 'Notificación servicio ' . (intval($Dominio->demo) === 1 ? 'demo ' : '') . 'Protegeme';

         break;

      case 3:

         # Botón de emergencia Estándar

         $tipo_servicio = 'Botón de emergencia Estándar';

         if ($op === 2 && $status_password === true) {
            
         }

         # asunto de correo
         $subject = 'Notificación servicio ' . (intval($Dominio->demo) === 1 ? 'demo ' : '') . 'Protegeme';

         break;

      case 4:

         # Widget

         $tipo_servicio = 'Widget';

         # busca boton del usuario
         if ($Otros->buscaTipo($Usuario->busua_cod, $tipo_cod, $DB) === false) {

            $error      = true;
            $statusCode = 400;
            $message    = 'Solicitud incorrecta. El archivo no se pudo procesar (17).';
            goto result;

         }

         # servicio debe estar activo
         if (intval($Otros->esta_cod) !== 1) {

            $error      = true;
            $statusCode = 400;
            $message    = 'Solicitud incorrecta. El archivo no se pudo procesar (18).';
            goto result;

         }

         if ($op === 2 && $status_password === true) {

         }

         # actualiza fecha de notificacion
         if ($Otros->actualizaFechaNotificacion($DB) === false) {

            $error      = true;
            $statusCode = 400;
            $message    = 'Solicitud incorrecta. El archivo no se pudo procesar (19).';
            goto result;

         }
         
         $fecha_notificacion  = $Otros->fecha_notificacion;

         # asunto de correo
         $subject = 'Notificación servicio ' . (intval($Dominio->demo) === 1 ? 'demo ' : '') . 'Protegeme';

         break;

      case 5:

         # Tracker

         $tipo_servicio = 'Tracker';

         if ($op === 2 && $status_password === true) {
            
         }

         # asunto de correo
         $subject = 'Notificación servicio ' . (intval($Dominio->demo) === 1 ? 'demo ' : '') . 'Protegeme';

         break;

      case 6:

         # Web RTC

         $tipo_servicio = 'Web RTC';

         # busca boton del usuario
         if ($Boton->buscaUserTipo($Usuario->busua_cod, $tipo_cod, $DB) === false) {

            $error      = true;
            $statusCode = 400;
            $message    = 'Solicitud incorrecta. El archivo no se pudo procesar (20).';
            goto result;

         }

         # servicio debe estar activo
         if (intval($Boton->esta_cod) !== 1) {

            $error      = true;
            $statusCode = 400;
            $message    = 'Solicitud incorrecta. El archivo no se pudo procesar (21).';
            goto result;

         }

         if ($op === 2 && $status_password === true) {

            $cloud_password   = Parameters::generaPasswordSIP(7);
            $p_peppered       = hash_hmac('sha256', $cloud_password, Parameters::PEPPER);
            $encriptado       = password_hash($p_peppered, PASSWORD_BCRYPT);

            # actualiza cloud_password
            if ($Usuario->actualizaCloudPassword($encriptado, $DB) === false) {

               $error      = true;
               $statusCode = 400;
               $message    = 'Solicitud incorrecta. El archivo no se pudo procesar (22).';
               goto result;

            }

            $password_email   = $cloud_password;

         }

         # actualiza fecha de notificacion
         if ($Boton->actualizaNotificacion($DB) === false) {

            $error      = true;
            $statusCode = 400;
            $message    = 'Solicitud incorrecta. El archivo no se pudo procesar (23).';
            goto result;

         }

         $fecha_notificacion  = $Boton->fecha_notificacion;

         # asunto de correo
         $subject = 'Notificación servicio ' . (intval($Dominio->demo) === 1 ? 'demo ' : '') . 'Protegeme';

         break;
         
      default:

         # error
         
         $tipo_servicio = 'error';

         break;

   }

   $nombre_email     = $Usuario->nombre;
   $usuario_email    = $Usuario->cloud_username . '@' . $Dominio->dominio_usuario;
   $correo_email     = $Usuario->email;

   # envia correo de notificacion
   $res = Parameters::enviaNotificacion($contenido, $nombre_email, $usuario_email, $password_email, $correo_email, $subject);

   if ($res['status'] === false) {
      $error      = true;
      $statusCode = 400;
      $message    = $res['error'];
      goto result;
   }

result:
   if ($error === true) {

      http_response_code($statusCode); # Bad Request
      echo json_encode([
         'status'    => 'err',
         'message'   => $message,
         'busua_cod' => $busua_cod,
      ]);

   } else {

      http_response_code(200);
      echo json_encode([
         'status'             => 'success',
         'message'            => 'Servicio notificado "' . $tipo_servicio . '"',
         'busua_cod'          => $busua_cod,
         'fecha_notificacion' => $fecha_notificacion
      ]);

   }
?>