<?
   function plantillaBP($dom_cod, $names, $user, $password, $address, $contacto)
   {	
      $url_img       = 'https://pbe.redvoiss.net/img';

      $text_contact  = $contacto == 1 ? '<p>También puedes agregar o eliminar contactos en tu página personal del servicio protégeme en <a href="https://pbe.redvoiss.net/user/login/index.php" target="_blank">https://pbe.redvoiss.net/user/login/index.php</a> usando las mismas credenciales. En esta página podrás además encontrar información sobre el servicio e ingresar otros datos personales.</p>' : '';
                        
      $mensaje       = '';

         switch ($dom_cod) {
            case '23':
               // DEMO - DOMINIO redvoiss.net
               $mensaje = <<< EOF
                        <section id="section-mail-cred" style="background-color: #dbdbdb;">
                           <div style="margin: 0 auto; width: 600px; background-color: #ffffff; padding: 16px;">
                              <p style="width: 100%;">
                                 <img src="$url_img/logo-customer-site.png" width="100" height="100">
                              </p>
                              <p>Estimado/a $names,</p>
                              <p>Bienvenido a la demo del servicio de protección <img src="$url_img/protegeme.png">. <strong>Redvoiss</strong> te ha creado una cuenta para que puedas probar el servicio.</p>
                              <p>Ahora puedes contar con una aplicación de botón de pánico en tu teléfono con el que podrás reportar emergencias al centro de ayuda, familiares y amigos.</p>
                              <p>Para comenzar a utilizar la aplicación descarga en tu móvil la aplicación desde Apple Store o Google Play buscándola con la palabra 'protegeme'. Descarga la aplicación con el logo de nuestro servicio.</p>
                              <p>Una vez instalada, la aplicación te pedirá las siguientes credenciales para acceder, (respetando mayúsculas y minúsculas):</p>
                              &nbsp;&nbsp;&nbsp;&nbsp;<strong>Nombre de usuario:</strong> $user <br>
                              &nbsp;&nbsp;&nbsp;&nbsp;<strong>Contraseña:</strong> $password</p>
                              <p>Aparecerá la siguiente pantalla: <br><br> <img src="$url_img/background-app-protegeme.png" width="80" height="120"> <br><br> Cuando presiones el botón la aplicación hará de inmediato una llamada al contact center de demo de Redvoiss. Te responderá una grabación. En un caso real de responderá el Call Center de emergencias y recibirá también tu ubicación.</p>
                              <p>En el botón libreta: <br><br> <img src="$url_img/contact-app-protegeme.png" width="60" height="60"> <br><br> puedes ingresar una lista de contactos a los que se les llamará y se les enviará un mensaje SMS de alarma con un link para mostrar en un mapa tu ubicación. Puedes agregar o eliminar contactos a voluntad. También puedes definir si la alarma es una llamada, un mensaje o ambas. Si no marcas ninguna alternativa, ese contacto no recibirá alarmas cuando presiones el botón.</p>
                              $text_contact
                              <p>Nota: El botón de pánico requiere que estés en una zona con cobertura de internet para operar.</p>
                              <p>En PROTEGEME, estamos comprometidos en crear redes de apoyo ante emergencias para tu comunidad.</p>
                              <br>
                              <p>Somos REDVOISS, comunicaciones como servicio</p>
                           </div>
                        </section>
                        EOF;
               break;
            case '161':
               // DOMINIO - colun.cl
               $mensaje = <<< EOF
                        <section id="section-mail-cred" style="background-color: #dbdbdb;">
                           <div style="margin: 0 auto; width: 600px; background-color: #ffffff; padding: 16px;">
                              <p style="width: 100%;">
                                 <img src="$url_img/logo-customer-site.png" width="100" height="100">
                              </p>
                              <p>Estimado/a $names,</p>
                              <p>Bienvenido al servicio de protección <img src="$url_img/protegeme.png">. Te hemos creado una cuenta para que puedas ingresar al servicio.</p>
                              <p>Ahora puedes contar con una aplicación de botón de pánico en tu teléfono con el que podrás reportar emergencias al centro de ayuda y dar aviso a contactos.</p>
                              <p>Para comenzar a utilizar la aplicación descarga en tu móvil la aplicación desde Apple Store o Google Play buscándola con la palabra 'protegeme'. Descarga la aplicación con el logo de nuestro servicio.</p>
                              <p>Una vez instalada, la aplicación te pedirá las siguientes credenciales para acceder, (respetando mayúsculas y minúsculas):</p>
                              &nbsp;&nbsp;&nbsp;&nbsp;<strong>Nombre de usuario:</strong> $user <br>
                              &nbsp;&nbsp;&nbsp;&nbsp;<strong>Contraseña:</strong> $password</p>
                              <p>Aparecerá la siguiente pantalla: <br><br> <img src="$url_img/background-app-protegeme.png" width="80" height="120"> <br><br> Cuando presiones el botón la aplicación hará de inmediato una llamada al centro de emergencias que recibirá también tu ubicación.</p>
                              $text_contact
                              <p>Nota: El botón de pánico requiere que estés en una zona con cobertura de internet para operar.</p>
                              <p>En PROTEGEME, estamos comprometidos en crear redes de apoyo ante emergencias para tu comunidad.</p>
                              <br>
                              <p>Somos REDVOISS, comunicaciones como servicio</p>
                           </div>
                        </section>
                        EOF;
               break;
            default:
               // NO DEMO MENSAJE NORMAL
               $mensaje = <<< EOF
                        <section id="section-mail-cred" style="background-color: #dbdbdb;">
                           <div style="margin: 0 auto; width: 600px; background-color: #ffffff; padding: 16px;">
                              <p style="width: 100%;">
                                 <img src="$url_img/logo-customer-site.png" width="100" height="100">
                              </p>
                              <p>Estimado/a $names,</p>
                              <p>Bienvenido al servicio de protección <img src="$url_img/protegeme.png">. <strong>Redvoiss</strong> te ha creado una cuenta para que puedas probar el servicio.</p>
                              <p>Ahora puedes contar con una aplicación de botón de pánico en tu teléfono con el que podrás reportar emergencias al centro de ayuda, familiares y amigos.</p>
                              <p>Para comenzar a utilizar la aplicación descarga en tu móvil la aplicación desde Apple Store o Google Play buscándola con la palabra 'protegeme'. Descarga la aplicación con el logo de nuestro servicio.</p>
                              <p>Una vez instalada, la aplicación te pedirá las siguientes credenciales para acceder, (respetando mayúsculas y minúsculas):</p>
                              &nbsp;&nbsp;&nbsp;&nbsp;<strong>Nombre de usuario:</strong> $user <br>
                              &nbsp;&nbsp;&nbsp;&nbsp;<strong>Contraseña:</strong> $password</p>
                              <p>Aparecerá la siguiente pantalla: <br><br> <img src="$url_img/background-app-protegeme.png" width="80" height="120"> <br><br> Cuando presiones el botón la aplicación hará de inmediato una llamada al contact center. Te responderá una grabación. En un caso real de responderá el Call Center de emergencias y recibirá también tu ubicación.</p>
                              <p>En el botón libreta: <br><br> <img src="$url_img/contact-app-protegeme.png" width="60" height="60"> <br><br> puedes ingresar una lista de contactos a los que se les llamará y se les enviará un mensaje SMS de alarma con un link para mostrar en un mapa tu ubicación. Puedes agregar o eliminar contactos a voluntad. También puedes definir si la alarma es una llamada, un mensaje o ambas. Si no marcas ninguna alternativa, ese contacto no recibirá alarmas cuando presiones el botón.</p>
                              $text_contact
                              <p>Nota: El botón de pánico requiere que estés en una zona con cobertura de internet para operar.</p>
                              <p>En PROTEGEME, estamos comprometidos en crear redes de apoyo ante emergencias para tu comunidad.</p>
                              <br>
                              <p>Somos REDVOISS, comunicaciones como servicio</p>
                           </div>
                        </section>
                        EOF;
               break;
         }
      
      return $mensaje;
   }
?>