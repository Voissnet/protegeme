<?php
   require_once 'MOD_Error.php';
   require_once 'MOD_ReCaptcha.php';
   require_once 'BUsuarioRV.php';
   require_once 'BConexion.php';
   require_once 'SEmail.php';

   $DB         = new BConexion();
   $UsuarioRV  = new BUsuarioRV();

   $response = [
      'status'  => 'error',
      'message' => ''
   ];

   $data = json_decode(file_get_contents('php://input'), true);

   # Validar si la decodificación fue exitosa
   if (json_last_error() !== JSON_ERROR_NONE) {
      $response['message'] = MOD_Error::ErrorCode('PBE_117');
      echo json_encode($response);
      exit;
   }

   # Validar CAPTCHA
   $captcha = $_GET['recaptcha'] ?? '';
   if (!MOD_ReCaptcha::Valida($captcha)) {
      $response['message'] = MOD_Error::ErrorCode('PBE_102');
      echo json_encode($response);
      exit;
   }

   # Validar contraseñas
   if ($data['password'] !== $data['password_v']) {
      $response['message'] = MOD_Error::ErrorCode('PBE_103');
      echo json_encode($response);
      exit;
   }

   # Validar y limpiar datos
   $data['enviar_email'] = ($data['enviar_email'] == '1') ? 1 : 0;
   $rut_empresa = str_replace('.', '', $data['rut_empresa']);
   $rut = str_replace('.', '', $data['rut']);

   # Iniciar transacción
   $DB->Begintrans();

   try {

      if ($UsuarioRV->Inserta(
         $data['username'],
         $data['password'],
         $data['nombre'],
         $data['apellidos'],
         $data['empresa'],
         $data['email'],
         38,
         $data['enviar_email'],
         $data['razon_social'],
         $rut_empresa,
         $rut,
         $data['cargo'],
         $data['telefono_celular'],
         $data['telefono_fijo'],
         $data['rub_cod'],
         $data['med_cod'],
         $DB
      ) === false) {

         $DB->Rollback();
         throw new Exception(MOD_Error::ErrorCode('PBE_104'));

      }

      if (!$UsuarioRV->ObtieneNumeroSMS($UsuarioRV->usua_cod, $DB)) {
         $DB->Rollback();
         throw new Exception(MOD_Error::ErrorCode('PBE_105'));
      }

      # Confirmar la transacción antes de enviar correos
      $DB->Rollback();

      if (!SEmail::MailInscripcion(
         $data['username'],
         $data['nombre'],
         $data['apellidos'],
         $data['email'],
         $UsuarioRV->sesion_check
      )) {

         throw new Exception(MOD_Error::ErrorCode('PBE_106'));

      }

      if (!SEmail::notificaUserRV($data, $UsuarioRV->usua_cod)) {
         throw new Exception(MOD_Error::ErrorCode('PBE_106'));
      }

      $response['status']  = 'success';
      $response['message'] = 'OK';

   } catch (Exception $e) {

      $response['message'] = $e->getMessage();

   }

   # Cerrar conexión
   $DB->Logoff();
   echo json_encode($response);
?>