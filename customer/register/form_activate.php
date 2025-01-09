<?
   require_once 'Parameters.php';
   require_once 'MOD_ReCaptcha.php';

   $v = rand();
?>
<!DOCTYPE html>
<html lang="es">
   <head>

      <!-- Required meta tags -->
      <meta http-equiv="Pragma" content="no-cache">
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      
      <!-- title -->
      <title>Activaci&oacute;n de su cuenta · Prot&eacute;geme</title>
      <meta name="title" content="Login Protegeme">
      
      <!-- Bootstrap CSS -->
      <link rel="stylesheet" href="<?= Parameters::WEB_PATH ?>/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
      
		<!-- papaparse -->
		<script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/js/papaparse.min.js"></script>

      <!-- sweetalert2 -->
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

      <script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/js/jquery-3.7.1.min.js"></script>

      <!-- DATA TABLE -->
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css">
      <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>

      <!-- SELECT2 -->
      <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
      <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

      <!-- STYLES css -->
      <link rel="stylesheet" href="<?= Parameters::WEB_PATH ?>/css/default.css?v<?= $v ?>">
      <link rel="stylesheet" href="<?= Parameters::WEB_PATH ?>/css/stylesLogin.css?v<?= $v ?>">

      <script src="<?= MOD_ReCaptcha::API_CAPTCHA ?>"></script>

   </head>
   <body>
      <section id="activate-account">
         <div class="container">
            <div class="row logo mb-3">
               <div class="col-12 d-flex justify-content-center">
                  <img src="<?= Parameters::WEB_PATH ?>/img/logo-protegeme.webp" width="120" height="90">
               </div>
            </div>
            <div class="row justify-content-center">
               <div class="col-12 div-form-login border p-3">
                  <div class="row">
                     <div class="d-flex justify-content-center mb-3">
                        <h3>Formulario de activaci&oacute;n de cuenta</h3>
                     </div>
                     <form id="form-activate" name="form-activate" class="row needs-validation">
                        <div class="col-12 mb-3">
                           <span calss="title-login">Ingrese su contraseña para finalizar la inscripción en nuestro sitio</span>
                        </div>
                        <div class="col-12 mb-3">
                           <label for="username" class="form-label">(*) Username:</label>
                           <input type="text" class="form-control" id="username" name="username" value="<?= $_GET["username"] ?>" disabled autocomplete="off">
                        </div>
                        <div class="col-12 mb-3">
                           <label for="password" class="form-label">(*) Contraseña:</label>
                           <input type="password" class="form-control" id="password" name="password" placeholder="Ingresa tu contraseña" required>
                        </div>
                        <div class="col-12 d-flex justify-content-center mb-3">
                           <input name="sesion_check" type="hidden" id="sesion_check" value="<?= $_GET["sesion_check"] ?>" readonly>
                           <div id="captcha-activate" class="g-recaptcha" data-sitekey="<?= MOD_ReCaptcha::RECAPTCHA_PUBLIC_KEY ?>"></div>
                        </div>
                        <div id="r-error-activate" class="col-12 text-center mb-3 d-none">
                           <span class="text-danger fs-5"></span>
                           <br>
                           <span class="text-danger fs-6"></span>
                        </div>
                        <div class="col-12 mb-3">
                           <button type="submit" class="btn btn-sm btn-danger w-100" id="btn-activate-user" name="btn-activate-user"> 
                              <div id="spinner-btn-activate-user" class="spinner-border spinner-border-sm text-white" role="status" hidden>
                                 <span class="visually-hidden">Loading...</span>
                              </div>
                              <span id="textbtn-btn-activate-user">Activar cuenta</span>
                           </button>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <script src="<?= Parameters::WEB_PATH ?>/js/functions.js?v<?= $v ?>"></script>
      <script text="text/javascript">
         document.getElementById('form-activate').addEventListener('submit', async e => {
            try {
               e.preventDefault();
               let text_error = document.getElementById('r-error-activate');
               await spinnerOpenBtn('btn-activate-user', '');
               text_error.classList.add('d-none');
               const status = await activateUser();
               const retval = await validateActivateUser(status);
               await spinnerCloseBtn('btn-activate-user', 'Activar cuenta');
               if (retval === true) {
                  const html = /* html */ `
                  <setcion id="modal-user-rv-activate">
                     <div class="container-fluid">
                        <div class="row">
                           <div class="col-12 d-flex justify-content-center mb-3">
                              <h3>¡Bienvenido!</h3>
                           </div>
                           <div class="col-12 d-flex justify-content-center mb-3">
                              <span>Estimado usuario:&nbsp;</span>
                              <span>Gracias por suscribirse.</span>
                           </div>
                           <div class="col-12 d-flex justify-content-center mb-3">
                              <span>Su cuenta esta creada, Redvoiss notificar&aacute; a su correo electr&oacute;nico cuando este activo el servicio.</span>
                           </div>
                           <div class="col-12 d-flex justify-content-center">
                              <input class="btn btn-danger w-100" type="button" name="inicio" value="Ir a Portal" id="inicio" onclick="location='${url}/login/'">
                           </div>
                        </div>
                     </div>
                  </setcion>
                  `;
                  Swal.fire({
                     allowEscapeKey: false,
                     allowOutsideClick: false,
                     html,
                     showCloseButton: true,
                     showConfirmButton: false,
                     width: 1400,
                  });
               }
            } catch (error) {
               await spinnerCloseBtn('btn-activate-user', 'Activar cuenta');
               console.error(`Error: ${error}`);
            }
         });
      </script>
   </body>
</html>