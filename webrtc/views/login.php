<?
require_once 'Parameters.php';

$error_message = '';
$succes_message = '';

if (isset($_GET['error'])) {
   switch ($_GET['error']) {
      case 'session_expired1':
         $error_message = 'Tu sesión ha expirado. Por favor, inicia sesión nuevamente. (1)';
         break;
      case 'session_expired2':
         $error_message = 'Tu sesión ha expirado. Por favor, inicia sesión nuevamente. (2)';
         break;
      case 'invalid_session':
         $error_message = 'Tu sesión ha caducado o los datos de la sesión son inválidos. Por favor, inicia sesión nuevamente.';
         break;
      case 'no_jwt':
         $error_message = 'No se pudo encontrar la sesión activa. Por favor, inicia sesión para continuar.';
         break;
      default:
         $error_message = 'Ha ocurrido un error desconocido.';
   }
}

if (isset($_GET['message_success'])) {
   switch ($_GET['message_success']) {
      case 'logout_success':
         $succes_message = 'Has cerrado sesión correctamente';
         break;
      default:
         $succes_message = '';
   }
}

?>
<!DOCTYPE html>
<html lang="es">

<head>

   <!-- Required meta tags -->
   <meta http-equiv="Expires" content="0">
   <meta http-equiv="Last-Modified" content="0">
   <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">

   <meta http-equiv="Pragma" content="no-cache">
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
   
   <!-- title -->
   <title>Aplicaci&oacute;n Web RTC</title>
   <meta name="title" content="Login Protegeme Web RTC">

   <!-- Bootstrap CSS -->
   <link rel="stylesheet" href="<?= Parameters::WEB_PATH ?>/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

   <!-- sweetalert2 -->
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

   <!-- STYLES css -->
   <link rel="stylesheet" href="<?= Parameters::WEB_PATH ?>/css/default.css?v=1">
   <link rel="stylesheet" href="<?= Parameters::WEB_PATH ?>/css/stylesLogin.css?v=1">
   <link rel="stylesheet" href="<?= Parameters::WEB_PATH ?>/webrtc/src/css/styles.css?v=1">

</head>

<body>

   <!-- Login -->
   <section id="login-web-rtc">

      <div class="w-100 d-flex justify-content-center" style="height: 100vh;">

         <div id="div-container">

            <form id="loginForm" class="d-flex flex-column justify-content-center w-100 px-3 my-3">

               <div class="text-center">
                  <img id="icon-protegeme" src="<?= Parameters::WEB_PATH ?>/img/logo-protegeme.webp" width="150" height="120" title="Logo PROTEGEME">
               </div>

               <div class="text-center mt-3">
                  <h1 class="font-semibold text-center">Bienvenido</h1>
               </div>

               <div>
                  <p id="title-cred" class="mt-2">Por favor ingresa tus credenciales:</p>
               </div>

               <hr class="my-1">

               <div class="mt-2">
                  <label class="form-label font-init" for="user">Usuario</label>
                  <input type="user" id="user" name="inputs" class="form-control font-init" placeholder="Usuario" autocomplete="off">
               </div>

               <div class="mt-2">
                  <label class="form-label font-init" for="password">Contrase&ntilde;a</label>
                  <input type="password" id="password" name="inputs" class="form-control font-init" placeholder="Contrase&ntilde;a" autocomplete="off">
               </div>

               <div class="mt-2">
                  <button type="submit" class="btn btn-sm btn-danger w-100 font-init" id="btn-login-user" name="btn-login-user">
                     <div id="spinner-btn-login-user" class="spinner-border spinner-border-sm text-white" role="status" hidden>
                        <span class="visually-hidden">Loading...</span>
                     </div>
                     <span id="textbtn-btn-login-user">Iniciar sesi&oacute;n</span>
                  </button>
               </div>
               <div class="mt-2">
                  <p id="error_message" class="text-danger message"><?= $error_message ?></p>
                  <p id="succes_message" class="text-success message"><?= $succes_message ?></p>
               </div>

            </form>

            <footer class="text-center">
               <div class="copyright">
                  <span class="text-dark">&copy; REDVOISS. TODOS LOS DERECHOS RESERVADOS</span>
               </div>
            </footer>

         </div>

      </div>

   </section>

   <script type="text/javascript">
      const form = document.getElementById('loginForm');
      const message = document.getElementById('message');

      form.addEventListener('submit', async (e) => {
         
         e.preventDefault(); // Evitar que se recargue la página

         // Obtener los datos del formulario
         const user = document.getElementById('user').value;
         const password = document.getElementById('password').value;

         try {
            // Enviar datos al backend usando fetch
            const response = await fetch('../auth/authenticate.php', {
               method: 'POST',
               headers: {
                  'Content-Type': 'application/json'
               },
               body: JSON.stringify({
                  user,
                  password
               })
            });

            const result = await response.json(); // Leer la respuesta en JSON

            if (result.success) {

               localStorage.setItem('jwt', result.token);
               
               // Redirigir al area protegida si el login fue exitoso
               window.location.href = 'main.php';

            } else {

               // Mostrar mensaje de error
               Swal.fire({
                  icon: 'error',
                  title: result.errorCode,
                  text: result.message,
                  confirmButtonText: 'Intentar nuevamente',
                  confirmButtonColor: '#F44336', // Color rojo en el botón
                  background: '#f9f9f9', // Fondo suave
                  iconColor: '#F44336', // Icono rojo
                  showClass: {
                     popup: 'animate__animated animate__fadeInDown'
                  },
                  hideClass: {
                     popup: 'animate__animated animate__fadeOutUp'
                  }
               });

            }
         } catch (error) {

            // Mostrar mensaje de error
            Swal.fire({
               icon: 'error',
               text: 'Error connecting to the server.',
               confirmButtonText: 'Intentar nuevamente',
               confirmButtonColor: '#F44336', // Color rojo en el botón
               background: '#f9f9f9', // Fondo suave
               iconColor: '#F44336', // Icono rojo
               showClass: {
                  popup: 'animate__animated animate__fadeInDown'
               },
               hideClass: {
                  popup: 'animate__animated animate__fadeOutUp'
               }
            });

         }
      });
   </script>

</body>

</html>