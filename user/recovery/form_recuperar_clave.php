<? 
   require_once 'Parameters.php';
   require_once 'MOD_ReCaptcha.php';
   require_once 'BConexion.php';

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
         <title>Recuperar contrase&ntilde;a · Prot&eacute;geme</title>
         <meta name="title" content="Reset Protegeme">
      
         <!-- Bootstrap CSS -->
         <link rel="stylesheet" href="<?= Parameters::WEB_PATH ?>/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
      
         <!-- papaparse -->
         <script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/js/papaparse.min.js"></script>

         <!-- sweetalert2 -->
         <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

         <!-- CAPTCHA -->
         <script src='<?= MOD_ReCaptcha::API_CAPTCHA ?>'></script>
         
         <!-- STYLES css -->
         <link rel="stylesheet" href="<?= Parameters::WEB_PATH ?>/css/default.css?v<?= $v ?>">
         <link rel="stylesheet" href="<?= Parameters::WEB_PATH ?>/css/stylesLogin.css?v<?= $v ?>">
      
      </head>
      <body>
         <section id="soli-reset-password">
            <div class="container">
               <div class="row logo mb-2">
                  <div class="col-12 d-flex justify-content-center">
                     <img src="<?= Parameters::WEB_PATH ?>/img/logo-protegeme.webp" width="150" height="120">
                  </div>
               </div>
               <div class="row justify-content-center">
                  <div class="col-12 div-form-login border p-3">
                     <div class="row">
                        <div class="d-flex justify-content-center mb-2">
                           <h2 class="title-login">Recuperar contrase&ntilde;a</h2>
                        </div>
                     </div>
                     <form id="form-solit-reset-pass" class="row needs-validation" novalidate>
                        <div class="col-12 mb-3">
                           <div class="form-floating">
                              <input type="text" class="form-control form-control-sm" id="username-reset" name="username-reset" minlength="1" maxlength="80" aria-describedby="username-reset-client" placeholder="Ingresa nombre de Usuario" title="Nombre usuario" onkeydown="sinEspacios(event)" autocomplete="off">
                              <label for="username-reset" class="col-form-label-sm">Nombre de usuario</label>
                              <div class="invalid-feedback">
                                 Ingresar usuario
                              </div>
                           </div>
                        </div>
                        <div class="col-12 mb-3">
                           <div class="form-floating">
                              <input type="email" class="form-control form-control-sm" id="email-reset" name="email-reset" aria-describedby="email-reset-client" placeholder="Ingrese su correo electr&oacute;nico" title="Correo usuario" autocomplete="off" onkeydown="sinEspacios(event)" onkeyup="validateEmailUser(this.value, 'email-reset')">
                              <label for="email-reset" class="col-form-label-sm">Correo electr&oacute;nico</label>
                              <div class="invalid-feedback">
                                 Formato incorrecto
                              </div>
                           </div>
                        </div>
                        <div class="col-12 d-flex flex-column align-items-center justify-content-center mb-2" id="div-capt">
                           <div id="cap-reset" class="g-recaptcha" data-sitekey="<?= MOD_ReCaptcha::RECAPTCHA_PUBLIC_KEY ?>"></div>
                           <div class="invalid-feedback text-center">
                              Validar Captcha
                           </div>
                        </div>
                        <div class="col-12 mb-2">
                           <input name="submit" type="submit" value="Obtener correo de recuperaci&oacute;n" class="btn btn-sm btn-danger w-100">
                        </div>
                        <div class="col-12 mb-3">
                           <a href="<?= Parameters::WEB_PATH ?>/user/login/index.php" class="text-decoration-none btn-perso">Volver al inicio</a>
                        </div>
                        <input type="hidden" id="status-email" value="1">
                     </form>
                  </div>
               </div>
            </div>
            <footer class="d-flex justify-content-center">
               <div class="copyright align-self-center">
                  <span class="text-dark">&copy; REDVOISS. TODOS LOS DERECHOS RESERVADOS</span>
               </div>
            </footer>
         </section>
         <script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/js/jquery-3.7.1.min.js"></script>
         <script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
         <script defer src="https://cdn.jsdelivr.net/npm/ua-parser-js@0/dist/ua-parser.min.js"></script>
         <script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/customer/js/screens.js"></script>
         <script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/js/functions.js?v<?= $v ?>"></script>
         <script type="text/javascript">
            document.getElementById('form-solit-reset-pass').addEventListener('submit', async (e) => {

               e.preventDefault();

               try {

                  await validateSoliFormReset();
                  await sendEmailResetPassword();

               } catch (error) {

                  console.error(`Error: ${error}`);

               }

            });
         </script>
      </body>
   </html>