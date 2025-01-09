<?
   use PHPMailer\PHPMailer\PHPMailer;
   class SEmail
   {

      public static function Envia($subject, $mensaje, $cabeceras, $to, $names = null)
      {
         require_once("BEmailPHPMailer.php");
         require_once("BEmailSMTP.php");
         require_once("BEmailException.php");

         $mail             = new PHPMailer(true);
         $mail->ClearAllRecipients();
         $mail->IsSMTP();
         $mail->SMTPAuth   = true;
         $mail->SMTPSecure = "tls";
         $mail->Host       = "smtp.office365.com";
         $mail->Port       = 587;
         $mail->Username   = "mensajes@redvoiss.net";
         $mail->Password   = "Pmariano.82";
         $mail->From       = "mensajes@redvoiss.net";
         $mail->FromName   = "Mensaje Redvoiss";
         $mail->CharSet    = 'UTF-8';

         $mail->Subject    = $subject;
         $mail->AltBody    = '';
         $mail->MsgHTML($mensaje);
         //$mail->AddAttachment("adjunto.txt");
         
         $mail->IsHTML(true);
         $mail->AddAddress($to, $names === null ? '' : $names);
         
         $exito            = $mail->Send();

         unset($mail);
         return ($exito);
      }

      /* ESTE EMAIL SE ENVIA CUANDO EL USUARIO SE INSCRIBE EN EL SITIO */
      public static function MailInscripcion($username, $nombre, $apellidos, $to, $session_check)
      {
         require_once 'Parameters.php';
         $subject    = "Activación de cuenta Redvoiss";
         $cabeceras  = 'From: Mensaje Redvoiss <mensajes@redvoiss.net>' . "\r\n" .
                        'Reply-To: mensajes@redvoiss.net' . "\r\n";
         $fono_redvoiss = Parameters::FONO_REDVOISS;
         $path          = Parameters::WEB_PATH;
         $nombre        = strtoupper($nombre);
         $apellidos     = strtoupper($apellidos);
         $mensaje       = <<< EOF
                           <html>
                              <head>
                                 <title>Activación cuenta en sitio Redvoiss.net</title>
                                 <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
                              </head>
                              <body>
                                 <p>Bienvenido(a) $nombre $apellidos<br>
                                    Para completar el proceso de inscripción y activar su cuenta por favor haga click en el siguiente enlace:<br><br>
                                    <a href="$path/customer/register/form_activate.php?username=$username&sesion_check=$session_check">ACTIVAR CUENTA</a><br><br>
                                    Recuerde nuestro teléfono de contacto $fono_redvoiss. Para consultas sobre abonos, marque la opción 1; para asistencia comercial, opción 2 y soporte técnico, opción 3.<br><br>
                                    Este correo es generado de manera automática. Por favor, no lo responda.<br>
                                    Para cualquier consulta, por favor comuníquese con soporte@redvoiss.net o llame al $fono_redvoiss
                                 </p>
                                 <p>En PROTEGEME, estamos comprometidos en crear redes de apoyo ante emergencias para tu comunidad.</p>
                                 <p>Gracias por su preferencia. <br>
                                 Somos REDVOISS, comunicaciones como servicio</p>
                              </body>
                           </html>
                        EOF;
         return SEmail::Envia($subject, $mensaje, $cabeceras, $to);
      }

      /* notifica nuevo usuario RV a redvoiss */
		public static function notificaUserRV($data, $usua_cod)
		{
			$subject    	= 'PBE: Nuevo cliente inscrito en Protegeme';
			$cabeceras 		= 'From: Mensajes Redvoiss <mensajes@redvoiss.net>' . "\r\n" .
									'Reply-To: mensajes@redvoiss.net' . "\r\n" .
									'Content-type:application/html' . "\r\n";

         $mensaje       = <<< EOF
                           <html>
                              <head>
                                 <title>Nuevo cliente inscrito en Protegeme</title>
                                 <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
                              </head>
                              <body>
                                 <section id="section-notify-user-rv">
                                    <p>
                                       Se ha inscrito un nuevo cliente en Protegeme con los siguientes datos:<br>
                                       Usua_cod: $usua_cod<br>
                                       Nombre usuario: {$data['username']}<br>
                                       ---------- <br>
                                       - Datos empresa <br>
                                       Empresa: {$data['empresa']}<br>
                                       Razón social: {$data['razon_social']}<br>
                                       Rut Empresa: {$data['rut_empresa']}<br>
                                       Tamaño: {$data['med_cod_desc']}<br>
                                       Rubro: {$data['rub_cod_desc']}<br>
                                       ---------- <br>
                                       - Datos contactos <br>
                                       Nombre de contacto: {$data['nombre']} {$data['apellidos']}<br>
                                       Rut contacto: {$data['rut']}<br>
                                       Cargo: {$data['cargo']}<br>
                                       Teléfono celular: {$data['telefono_celular']}<br>
                                       Teléfono fijo: {$data['telefono_fijo']}<br>
                                       Correo electrónico: {$data['email']}<br>
                                       <br>
                                       Por favor revisar este proceso
                                    </p>
                                 </section>
                              </body>
                           </html>
                        EOF;
			return SEmail::Envia($subject, $mensaje, $cabeceras, 'sistemas@redvoiss.net', 'Sistemas RV');
		}

      // ESTE EMAIL SE ENVIA CUANDO EL USUARIO QUIERE RECUPERAR LA CONTRASEÑA OLVIDADA
      public static function MailRecuperaPassword($username, $nombres, $apellidos, $to, $token, $iv, $uc_crypt )
      {
         require_once 'Parameters.php';

         $fono_redvoiss = Parameters::FONO_REDVOISS;
         $nombre     = $nombres . " " . $apellidos;
         $subject    = "Recuperación de contraseña para Protégeme";
			$cabeceras 		= 'From: Mensajes Redvoiss <mensajes@redvoiss.net>' . "\r\n" .
									'Reply-To: mensajes@redvoiss.net' . "\r\n" .
									'Content-type: text/html; charset=iso-8859-1' . "\r\n";
         $url        = Parameters::WEB_PATH . "/customer/register/form_cambiar_clave.php?token=" . $token . "&iv=" . $iv . "&uc_crypt=" . $uc_crypt;
         $mensaje    = <<< EOF
                           <html>
                              <head>
                                 <title>Recuperación contraseña para Protégeme</title>
                                 <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
                              </head>
                              <body>
                                 <p>Estimado usuario $nombre <br>
                                    Usted ha solicitado un cambio de contraseña desde el sitio de cliente de Protégeme para el usuario "$username". Por favor ingrese al siguiente enlace y siga las instrucciones.  
                                    <br><br>
                                    <a href="$url">Ingrese aquí</a>
                                    <br><br><br>
                                    Recuerde nuestro teléfono de contacto $fono_redvoiss. Para consultas sobre abonos, marque la opcíon 1; para asistencia comercial, opción 2 y soporte técnico, opción 3.<br><br>
                                    Este correo es generado de manera automática. Por favor, no lo responda.<br>
                                    Para cualquier consulta, por favor comuníquese con soporte@redvoiss.net o llame al $fono_redvoiss
                                 </p>
                                 <p>En PROTEGEME, estamos comprometidos en crear redes de apoyo ante emergencias para tu comunidad.</p>
                                 <p>Gracias por su preferencia. <br>
                                 Somos REDVOISS, comunicaciones como servicio</p>
                              </body>
                           </html>
                        EOF;
         return SEmail::Envia($subject, $mensaje, $cabeceras, $to);
      }

      /* ESTE EMAIL SE ENVIA CUANDO EL USUARIO QUIERE RECUPERAR LA CONTRASEÑA OLVIDADA*/
      public static function MailRecuperaPasswordOper($username, $nombre, $to, $token, $iv, $uc_crypt )
      {
         $fono_redvoiss = Parameters::FONO_REDVOISS;
         $subject       = "Recuperación de contraseña Protégeme";
         $cabeceras 		= 'From: Mensajes Redvoiss <mensajes@redvoiss.net>' . "\r\n" .
                           'Reply-To: mensajes@redvoiss.net' . "\r\n" .
                           'Content-type: text/html; charset=iso-8859-1' . "\r\n";
         $url        = Parameters::WEB_PATH . "/operator/register/form_cambiar_clave.php?token=" . $token . "&iv=" . $iv . "&uc_crypt=" . $uc_crypt;
         $mensaje    = <<< EOF
                           <html>
                              <head>
                                 <title>Recuperación contraseña para Protégeme</title>
                                 <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
                              </head>
                              <body>
                                 <p>Estimado operador $nombre <br>
                                    Usted ha solicitado un cambio de contraseña desde el sitio de cliente de Protégeme para el usuario "$username". Por favor ingrese al siguiente enlace y siga las instrucciones.  
                                    <br><br>
                                    <a href="$url">Ingrese aquí</a>
                                    <br><br><br>
                                    Recuerde nuestro teléfono de contacto $fono_redvoiss. Para consultas sobre abonos, marque la opcíon 1; para asistencia comercial, opción 2 y soporte técnico, opción 3.<br><br>
                                    Este correo es generado de manera automática. Por favor, no lo responda.<br>
                                    Para cualquier consulta, por favor comuníquese con soporte@redvoiss.net o llame al $fono_redvoiss
                                 </p>
                                 <p>En PROTEGEME, estamos comprometidos en crear redes de apoyo ante emergencias para tu comunidad.</p>
                                 <p>Gracias por su preferencia. <br>
                                 Somos REDVOISS, comunicaciones como servicio</p>
                              </body>
                           </html>
                        EOF;
         return SEmail::Envia($subject, $mensaje, $cabeceras, $to);
      }

		/* mail para notificar a los usuarios creados del cliente stretto */
		public static function MailSOSNotificaCred($dom_cod, $names, $user, $password, $address, $contacto)
		{
         require_once 'BPlantilla.php';
         
			$subject    	= 'Notificación de credenciales ' . ($dom_cod === '23' ? 'Demo ' : '') . 'Protegeme';
			$cabeceras 		= 'From: Mensajes Redvoiss <mensajes@redvoiss.net>' . "\r\n" .
									'Reply-To: mensajes@redvoiss.net' . "\r\n" .
									'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				
			$mensaje       = plantillaBP($dom_cod, $names, $user, $password, $address, $contacto);

			return SEmail::Envia($subject, $mensaje, $cabeceras, $address, $names);
		}

      // mail para notificar a los usuarios creados del cliente stretto
		public static function MailSOSNotificaCredOper($names, $user, $password, $address)
		{
			$subject    	= 'Notificación de credenciales operador';
			$cabeceras 		= 'From: Mensajes Redvoiss <mensajes@redvoiss.net>' . "\r\n" .
									'Reply-To: mensajes@redvoiss.net' . "\r\n" .
									'Content-type:application/pdf' . "\r\n";

         $url_img       = Parameters::WEB_PATH . '/img';

			$mensaje       = <<< EOF
         <section id="section-mail-cred" style="background-color: #dbdbdb;">
            <div style="margin: 0 auto; width: 600px; background-color: #ffffff; padding: 16px;">
               <p style="width: 100%;">
                  <img src="$url_img/logo-customer-site.png" width="100" height="100">
               </p>
               <p>Estimado/a $names,</p>
               <p>Bienvenido al servicio de protección Protegeme. Se te asignó una cuenta como <strong>Operador</strong> del servicio, para poder acceder al portal de administración debes ingresar con las siguientes credenciales (respetando mayúsculas y minúsculas): </p>
               &nbsp;&nbsp;&nbsp;&nbsp;<strong>Nombre de usuario:</strong> $user <br>
               &nbsp;&nbsp;&nbsp;&nbsp;<strong>Contraseña:</strong> $password <br>
               &nbsp;&nbsp;&nbsp;&nbsp;<strong>Portal: </strong><a href="https://pbe.redvoiss.net/operator/login/index.php" target="_blank">https://pbe.redvoiss.net/operator/login/index.php</a>
               <p>En PROTEGEME, estamos comprometidos en crear redes de apoyo ante emergencias para tu comunidad.</p>
               <br>
               <p>Somos REDVOISS, comunicaciones como servicio</p>
            </div>
         </section>
         EOF;
			return SEmail::Envia($subject, $mensaje, $cabeceras, $address, $names);
		}

      public static function notificaActivacionUserRV($usua_cod, $username, $nombre, $empresa, $razon_social, $email)
      {
         $subject    	= 'PBE: Usuario activado';
			$cabeceras 		= 'From: Mensajes Redvoiss <mensajes@redvoiss.net>' . "\r\n" .
									'Reply-To: mensajes@redvoiss.net' . "\r\n" .
									'Content-type:application/html' . "\r\n";

                           $mensaje       = <<< EOF
                           <html>
                              <head>
                                 <title>Usuario activado en Protegeme</title>
                                 <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
                              </head>
                              <body>
                                 <section id="section-notify-user-rv">
                                    <p>
                                       Se activo usuario PBE: <br><br>
                                       Usua_cod: $usua_cod <br>
                                       Username: $username<br>
                                       Nombre contacto: $nombre <br>
                                       Empresa: $empresa <br>
                                       Razón social: $razon_social <br>
                                       Email contacto: $email <br><br>
                                       Por favor revisar este proceso
                                    </p>
                                 </section>
                              </body>
                           </html>
                        EOF;
			return SEmail::Envia($subject, $mensaje, $cabeceras, 'sistemas@redvoiss.net', 'Sistemas RV');
      }

      /* mail para notificar a los usuarios creados del cliente stretto */
		public static function resetPasswordUser($names, $user, $address, $busua_cod, $cloud_username, $dominio_usuario)
		{
			$subject    	= 'Recuperar contraseña';
			$cabeceras 		= 'From: Mensajes Redvoiss <mensajes@redvoiss.net>' . "\r\n" .
									'Reply-To: mensajes@redvoiss.net' . "\r\n" .
									'Content-type:application/html' . "\r\n";

         $token         = urlencode(Parameters::openCypher('encrypt', 'SResDvO2!9$32#01widJys56!?1ads'));
         $bu            = urlencode(Parameters::openCypher('encrypt', $busua_cod));
         $cu            = urlencode(Parameters::openCypher('encrypt', $cloud_username));
         $du            = urlencode(Parameters::openCypher('encrypt', $dominio_usuario));
         
         $url           = Parameters::WEB_PATH . '/user/recovery/form_cambiar_clave.php?token=' . $token . '&bu=' . $bu . '&cu=' . $cu . '&du=' . $du;

         $mensaje       = <<< EOF
									<section id="section-reset-passwod" style="background-color: #dbdbdb;">
										<div style="margin: 0 auto; width: 600px; background-color: #ffffff; padding: 16px;">
											<p>Estimado/a $names,</p>
											<br>
											<p>Haz solicitado recuperar contraseña de tu cuenta <strong>PROTEGEME</strong> para tu usuario $user</p>
											<br>
											<p>A continuación, presiona <a href="$url">aquí</a> para crear su nueva contraseña.</p><p><strong>Recuerde que al cambiar la contraseña, debe restablecer la aplicación.</strong></p>
											<br>
											<p>En PROTEGEME, estamos comprometidos en crear redes de apoyo ante emergencias para tu comunidad.</p>
											<br>
											<br>
											<p>Somos REDVOISS, comunicaciones como servicio</p>
										</div>
									</section>
									EOF;
			return SEmail::Envia($subject, $mensaje, $cabeceras, $address, $names);
		}
   }

?>