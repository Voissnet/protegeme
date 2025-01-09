<?
require_once 'Parameters.php';
   class MOD_Error
   {
      const ERROR_PATH        = 'https://' . Parameters::WEB_NAME . '/customer/error';  /* RUTA DEL DIRECTORIO DE LA PAGINA DE ERROR */
      const ERROR_PATH_USER   = 'https://' . Parameters::WEB_NAME . '/user/error';  /* RUTA DEL DIRECTORIO DE LA PAGINA DE ERROR USER */
      const ERROR_PATH_SITE   = 'https://' . Parameters::WEB_NAME . '/site/error';  /* RUTA DEL DIRECTORIO DE LA PAGINA DE ERROR USER */

      /* recibe codigo de error y redirige a página de error */
      public static function Error($err_cod, $op = 1) {
         if ($op === 1) {
            header('Location: ' . self::ERROR_PATH . '/index.php?err_cod=' . $err_cod);
         } else if ($op === 2) {
            header('Location: ' . self::ERROR_PATH_USER . '/index.php?err_cod=' . $err_cod);
         } else if ($op === 3) {
            header('Location: ' . self::ERROR_PATH_SITE . '/index.php?err_cod=' . $err_cod);
         }
         return true;
      }

      public static function ErrorCode($err_cod) {
         $retval = false;
         switch ($err_cod) {
            case 'PBE_101':
               $retval = 'No está autorizado a ingresar a esta página.';
               break;
            case 'PBE_102':
               $retval = 'Captcha inválido';
               break;
            case 'PBE_103':
               $retval = 'La contraseña de verificación no coincide con la especificada';
               break;
            case 'PBE_104':
                  $retval = 'No se ha podido efectuar el registro en el sistema. Intente nuevamente más tarde';
                  break;
            case 'PBE_105':
                  $retval = 'Error en la inscripción: No se ha podido ingresar número de mensajería SMS';
                  break;
            case 'PBE_106':
                  $retval = 'Error en la inscripción: No se ha podido enviar mail de activación de cuenta';
                  break;
            case 'PBE_107':
                  $retval = 'No se ha podido activar esta cuenta o encontrar al usuario';
                  break;
            case 'PBE_108':
                  $retval = 'Usuario no ha podido Verificar contraseña';
                  break;
            case 'PBE_109':
                  $retval = 'Error con inicio de sesión (1)';
                  break;
            case 'PBE_110':
                  $retval = 'Error De verificación de Login';
                  break;
            case 'PBE_111':
                  $retval = 'No es posible iniciar sesión';
                  break;
            case 'PBE_112':
                  $retval = 'No es posible cerrar sesión';
                  break;
            case 'PBE_113':
                  $retval = 'Error: No es posible recuperar clave (1). Nombre de usuario no registrado';
                  break;
            case 'PBE_114':
                  $retval = 'Error: No es posible recuperar clave (2). Correo electrónico no registrado';
                  break;
            case 'PBE_115':
                  $retval = 'Error: No se ha podido enviar el mail de recuperación de contraseña';
                  break;
            case 'PBE_116':
                  $retval = 'Error: Solicitud inválida';
                  break;
            case 'PBE_117':
                  $retval = 'Error: No se encuentra usuario';
                  break;
            case 'PBE_118':
                  $retval = 'Solicitud de cambio de clave inválida (1)';
                  break;
            case 'PBE_119':
                  $retval = 'Solicitud de cambio de clave inválida (2)';
                  break;
            case 'PBE_120':
                  $retval = 'Estado de usuario inválido';
                  break;
            case 'PBE_121':
                  $retval = 'Verificación de contraseña inválida';
                  break;
            case 'PBE_122':
                  $retval = 'No fue posible modificar contraseña';
                  break;
            case 'PBE_123':
                  $retval = 'No fue posible aprovisionar botón';
                  break;
            case 'PBE_124':
                  $retval = 'No fue posible encontrar usuario';
                  break;
            case 'PBE_125':
                  $retval = 'Estimado Usuario, aun no tiene habilitado el Servicio De Emergencia';
                  break;
            case 'PBE_126':
                  $retval = 'Los datos no corresponden al Usuario';
                  break;
            case 'PBE_127':
                  $retval = 'Error: El usuario no esta activo, comun&iacute;quese con su adminitrador';
                  break;
            case 'PBE_128':
                  $retval = 'No se han podido modificar su contraseña. Verifique su contraseña actual';
               break;
            case 'PBE_129':
                  $retval = 'No está autorizado para realizar la operación (PBE_129)';
               break;
            case 'PBE_130':
                  $retval = 'No está autorizado para realizar la operación (PBE_130)';
               break;
            case 'PBE_131':
                  $retval = 'No se pudo modificar datos';
               break;
            case 'PBE_132':
                  $retval = 'Error: Dominio no valido (1)';
                  break;
            case 'PBE_133':
                  $retval = 'Error: Dominio no valido (2)';
                  break;
            case 'PBE_134':
               $retval = 'Estimado/a: La cuenta no esta operativa';
               break;
            case 'PBE_135':
                  $retval = 'Error: El monto que desea abonar no es válido. Por favor intente nuevamente.';
                  break;
            case 'PBE_136':
                  $retval = 'Error: No se ha podido registrar el abono. Por favor intente nuevamente.';
                  break;
            case 'PBE_137':
               $retval = 'Error: Contact Center no encontrado.';
               break;
            case 'PBE_138':
               $retval = 'Error: No pudo ser ejecutada la solicitud.';
               break;
            case 'PBE_139':
               $retval = 'No se han podido modificar su correo. Verifique su contraseña actual';
               break;
            default:
                  $retval = 'Se ha producido un error crítico en el sistema, por favor contacte al administrador: soporte@redvoiss.net';
               break;
         }
         return $retval;
      }

      // captura posibles errores
      public static function ErrorJSON($message) {
         $data = array( 'status'  => 'error',
                        'message' => MOD_Error::ErrorCode($message) );
         echo json_encode($data);
      }
   }
?>