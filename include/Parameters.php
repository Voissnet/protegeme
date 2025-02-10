<?
   class Parameters
   {
      const WEB_NAME                   = "pbe.redvoiss.net";                                                            /* nombre del sitio */
      const WEB_PATH                   = "https://" . self::WEB_NAME;                                                /* Path raiz del sitio */
      const PATH                       = "/var/www/" . self::WEB_NAME . "/htdocs";                                   /* Raiz del sitio en linux */
      const PATH_LOG                   = "/var/www/" . self::WEB_NAME . "/logs";                                     /* Path LOGS UNIX  sitio */

      const PATTERN_ALFANUMERICO       = "^[a-zA-Z0-9ñáéíóúÁÉÍÓÚÑ][a-z A-Z0-9ñáéíóúÁÉÍÓÚÑ'.]{2,60}$";                /*  EXPRESION REGULAR PARA CAMPOS DE TEXTO LARGOS */
      const PATTERN_ALFANUMERICO_200   = "^[a-zA-Z0-9ñáéíóúÁÉÍÓÚÑ][a-z A-Z0-9ñáéíóúÁÉÍÓÚÑ&'.]{5,200}$";              /*  EXPRESION REGULAR PARA CAMPOS DE TEXTO DE EMPRESAS MUY LARGAS & 200 caracteres (datos facturacion)*/
      const PATTERN_NAMES              = "^[a-zA-ZñáéíóúÁÉÍÓÚÑ][a-z A-ZñáéíóúÁÉÍÓÚÑ]{1,40}$";                        /*  EXPRESION REGULAR PARA FORMULARIOS CON CAMPOS NOMBRE APELLIDOS */
      const PATTERN_TELEFONO           = "^[0-9+]{9,14}$";                                                           /*  EXPRESION REGULAR PARA TELEFONOS +56994193746 */
      const PATTERN_USERNAME           = "^[a-zA-Z0-9][a-zA-Z0-9\.@]{7,40}$";                                        /*  EXPRESION REGULAR PARA USERNAMES */
      const PATTERN_PASSWORD           = "(?=.*\d)(?=.*[a-zA-Z]).{8,60}";                                            /*  EXPRESION REGULAR PARA FORMULARIOS QUE SOLICITAN PASSWORD */

      const TEXT_ALFANUMERICO          = "Por favor ingrese solo letras y números";
      const TEXT_NAMES                 = "Por favor ingrese sólo letras y máximo 40 caracteres";
      const TEXT_TELEFONO              = "Por favor ingrese sólo números, mínimo 9 caracteres";
      const TEXT_EMAIL                 = "Debe ingresar una cuenta email válida";
      const TEXT_USERNAME              = "Ingrese un texto de mínimo 8 caracteres";
      const TEXT_PASSWORD              = "Debe contener al menos un número, una letra, y 8 caracteres como mínimo";

      const FONO_REDVOISS              = "56 2 2405 3000";                                                           /* Telefono de soporte */
      const PEPPER                     = "m4a78t-e";                                                                 /* Encriptado */


      static function ErrorXML($error_txt)
      {
         $xmlstr_err = "<?xml version='1.0' encoding='UTF-8'?><error><message>" . $error_txt . "</message></error>";
         $xml_err = new SimpleXMLElement($xmlstr_err);
         $value = $xml_err->asXml();
         //unset($xml_err);
         header("HTTP/1.1 403 Forbidden");
         header("Content-type:text/xml");
         return $value;
      }

      // genera sip_username para boton movil
      public static function generaSipUsername($id, $leght, $op)
      {

         $sip_i = '';

         # largo inicial del sip_username, total 7 de largo
         switch ($op) {
            case 1:
               $sip_i = '10000';
               break;
            case 2:
               $sip_i = '20000';
               break;
            case 3:
               $sip_i = '30000';
               break;
         }

         # largo final
         $result = (substr($sip_i, 0, -$leght) . $id);
         
         return $result;

      }

      // crea audio segun el nombre ingresado
      public static function apiAudio($nombre)
      {
         $curl = curl_init();
         curl_setopt($curl, CURLOPT_URL, 'http://172.16.154.205/tts/creaNombre.php?texto=' . $nombre);
         curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
         $response = json_decode(curl_exec($curl), true);
         curl_close($curl);
         return $response;
      }

      // genera clave password 
      public static function generaPasswordSIP($password2Length = 10) 
      {
         $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
         $password2 = '';
         for ($i = 0; $i <= $password2Length; $i++) {
            $randomNumber = floor((float)rand()/(float)getrandmax() * strlen($chars));
            $password2 .= substr($chars, $randomNumber, 1);
         }
         return $password2;
      }

      // obtiene coorenadas
      public static function obtieneCoordenadas($localizacion)
      {
         $arrContextOptions = array(
            'ssl' => array(
               'verify_peer'        => false,
               'verify_peer_name'   => false,
            ),
         );

         // obtenemos coordenadas de acuerdo a direccion
         $response   = file_get_contents('https://pbe.redvoiss.net:9025/api/DireccionACoordenada?direccion=' . rawurlencode($localizacion), false, stream_context_create($arrContextOptions));
         $obj        = json_decode($response);
         $coodenadas = $obj->lat . ';' . $obj->lon;

         return $coodenadas;
      }

      /* funciones VEX */
      public static function csv_to_multidimension_array($filename = '', $delimiter = ';')
      {
         if (!file_exists($filename) || !is_readable($filename)) {
            return false;
         }
         $header = NULL;
         $data = array();

         if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
               $data[] = $row;
            }
            fclose($handle);
         }
         return $data;
      }

      // encriptacion/descriptacion
      public static function openCypher($action = 'encrypt', $string = false)
      {
         $action = trim($action);
         $output = false;

         $myKey = 'oW%c76+jb2';
         $myIV = 'A)2!u467a^';
         $encrypt_method = 'AES-256-CBC';

         $secret_key = hash('sha256',$myKey);
         $secret_iv = substr(hash('sha256',$myIV),0,16);

         if ( $action && ($action == 'encrypt' || $action == 'decrypt') && $string )
         {
            $string = trim(strval($string));

            if ( $action == 'encrypt' )
            {
               $output = openssl_encrypt($string, $encrypt_method, $secret_key, 0, $secret_iv);
            };

            if ( $action == 'decrypt' )
            {
               $output = openssl_decrypt($string, $encrypt_method, $secret_key, 0, $secret_iv);
            };
         };

         return $output;
      }

      // obtiene nombre plantilla
      public static function obtieneNombrePlantilla($tipo_cod)
      {
         # variable que contiene el nombre de archivo txt
         $servicio = '';

         switch ($tipo_cod) {
            case 1:
               # Botón de emergencia SIP - Móvil
               $servicio = 'smovil';
               break;
            case 2:
               # Botón de emergencia SIP - Estático
               $servicio = 'sfijo';
               break;
            case 3:
               # Botón de emergencia Estándar
               $servicio = 'sestandar';
               break;
            case 4:
               # Widget
               $servicio = 'swidget';
               break;
            case 5:
               # Tracker
               $servicio = 'stracker';
               break;
            case 6:
               # Tracker
               $servicio = 'swebrtc';
               break;
            default:
               # no existe
               $servicio = 'error';
               break;
         }

         return $servicio;

      }

      # obtiene contenido de la plantilla
      public static function contenidoPlantilla($dom_cod, $servicio)
      {
         # contenido del mensaje
         $contenido = false;

         # ruta final del archivo dominio(cod)+servicio.txt
         $ruta = self::PATH . '/plantillas/' . $dom_cod . '/' . $servicio . '.txt';

         # ruta al archivo de texto en el servidor
         if (file_exists($ruta)) {

            # leer el contenido del archivo encontrado
            $contenido = file_get_contents($ruta);

         } else {

            # esto es en caso de que no exista uno personalizado por el cliente
            # verifica archivo por default.txt
            
            if (file_exists(self::PATH . '/plantillas/' . $servicio . '_default.txt')) {

               # leer el contenido del archivo encontrado
               $contenido = file_get_contents(self::PATH . '/plantillas/' . $servicio . '_default.txt');

            }

         }

         return $contenido;

      }

      # envia notificacion de servicio al usuario
      public static function enviaNotificacion($contenido, $nombre, $nombre_usuario, $cloud_password, $correo, $subject)
      {

         $status_nombre    = (stripos($contenido, '{nombre}') !== false) ? true : false;
         $status_usuario   = (stripos($contenido, '{usuario}') !== false) ? true : false;
         $status_password  = (stripos($contenido, '{password}') !== false) ? true : false;
         $status_correo    = (stripos($contenido, '{correo}') !== false) ? true : false;
         
         $status_nombre === false ? str_replace('{nombre}', '', $contenido) : '';
         $status_usuario === false ? str_replace('{usuario}', '', $contenido) : '';
         $status_password === false ? str_replace('{password}', '', $contenido) : '';
         $status_correo === false ? str_replace('{correo}', '', $contenido) : '';

         $variables = [
            'nombre'    => $nombre,
            'usuario'   => $nombre_usuario,
            'password'  => $cloud_password,
            'correo'    => $correo
         ];

         # reemplazar las variables en la plantilla con los valores proporcionados
         foreach ($variables as $clave => $valor) {
            $contenido = str_replace('{' . $clave . '}', $valor, $contenido);
         }

         $curl = curl_init();

         curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://pbe.redvoiss.net:8025/mail',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
               'subject'   => $subject,
               'contenido' => $contenido,
               'to'        => $correo,
               'names'     => $nombre_usuario
            ),
            CURLOPT_HTTPHEADER => array(
               'Authorization: Bearer ij98f403udmf2kn2ljwe3246578olkiu6y5e1'
            ),
            # opciones SSL desactivadas:
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
         ));
         
         $response = json_decode(curl_exec($curl), true);

         curl_close($curl);
      
        return $response;

      }

   }
?>