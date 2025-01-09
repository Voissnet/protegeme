<? 
   require_once 'Parameters.php';
   require_once 'MOD_ReCaptcha.php';
   require_once 'BConexion.php';
   require_once 'BUsuarioRV.php';
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
         <meta name="title" content="Register Protegeme">

         <!-- Bootstrap CSS -->
         <link rel="stylesheet" href="<?= Parameters::WEB_PATH ?>/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
         
         <!-- CAPTCHA -->
         <script src='<?= MOD_ReCaptcha::API_CAPTCHA ?>'></script>
         
         <!-- sweetalert2 -->
         <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

         <!-- STYLES css -->
         <link rel="stylesheet" href="<?= Parameters::WEB_PATH ?>/css/default.css?v<?= $v ?>">
         <link rel="stylesheet" href="<?= Parameters::WEB_PATH ?>/css/stylesLogin.css?v<?= $v ?>">

      </head>
      <body>
         <section id="reset-password">
            <div class="container">
               <div class="row logo mb-3">
                  <div class="col-12 d-flex justify-content-center">
                     <img src="<?= Parameters::WEB_PATH ?>/img/logo-protegeme.webp" width="150" height="120">
                  </div>
               </div>
               <div class="row justify-content-center">
                  <div class="col-12 div-form-login border p-3">
                     <div class="row">
                        <div class="d-flex justify-content-center mb-3">
                           <h2 class="title-login">Recuperar contrase&ntilde;a</h2>
                        </div>
                     </div>
                     <form id="form-recovery" class="row" name="form">
                        <div class="col-12 mb-3">
                           <div class="form-floating">
                              <input type="text" class="form-control form-control-sm" id="username" name="username" minlength="1" maxlength="80" aria-describedby="username-client" placeholder="Ingresa nombre de Usuario" title="Nombre usuario" onkeydown="onlySpace(event)" autocomplete="off">
                              <label for="username" class="col-form-label-sm">Nombre de usuario</label>
                              <div class="invalid-feedback">
                                 Ingresar usuario
                              </div>
                           </div>
                        </div>
                        <div class="col-12 mb-3">
                           <div class="form-floating">
                              <input type="email" class="form-control form-control-sm" id="email-reset" name="email-reset" aria-describedby="email-reset-client" placeholder="Ingrese su correo electr&oacute;nico" title="Correo usuario" autocomplete="off" onkeydown="onlySpace(event)" onkeyup="validateEmailUser(this.value, 'email-reset')">
                              <label for="email-reset" class="col-form-label-sm">Correo electr&oacute;nico</label>
                              <div class="invalid-feedback">
                                 Formato incorrecto
                              </div>
                           </div>
                        </div>
                        <div class="col-12 d-flex flex-column align-items-center justify-content-center mb-2" id="div-capt">
                           <div id="captcha-recu-password" class="g-recaptcha" data-sitekey="<?= MOD_ReCaptcha::RECAPTCHA_PUBLIC_KEY ?>"></div>
                        </div>
                        <div id="r-error" class="col-12 text-center mb-3 d-none">
                           <span class="text-danger fs-5"></span>
                           <br>
                           <span class="text-danger fs-6"></span>
                        </div>
                        <div class="col-12 mb-3">
                           <button type="submit" class="btn btn-sm btn-danger w-100" id="btn-recovery-login" name="btn-recovery-login"> 
                              <div id="spinner-btn-recovery-login" class="spinner-border spinner-border-sm text-white" role="status" hidden>
                                 <span class="visually-hidden">Loading...</span>
                              </div>
                              <span id="textbtn-btn-recovery-login">Obtener correo de recuperaci&oacute;n</span>
                           </button>
                        </div>
                        <div class="col-12 mb-3">
                           <a href="<?= Parameters::WEB_PATH ?>/customer/login/index.php" class="text-decoration-none btn-perso">Volver al inicio</a>
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
         <script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/customer/js/screens.js?v<?= $v ?>"></script>
         <script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/js/functions.js?v<?= $v ?>"></script>
         <script type="text/javascript">
            document.getElementById('form-recovery').addEventListener('submit', async e => {
               try {
                  e.preventDefault();
                  let text_error = document.getElementById('r-error');
                  text_error.classList.add('d-none');
                  await spinnerOpenBtn('btn-recovery-login', '');
                  const data = await validateFormLoginAdm();
                  const result = await validateFormRecuPassword(data);
                  if (result.status === 'error') {
                     grecaptcha.reset();
                     text_error.classList.remove('d-none');
                     text_error.querySelectorAll('span')[0].innerHTML = result.message;
                     text_error.querySelectorAll('span')[1].innerHTML = `COD: ${result.cod}`;
                  } else {
                     const html = /* html */ `
                     <setcion id="modal-user-rv">
                        <div class="container-fluid">
                           <div class="row">
                              <div class="d-flex justify-content-center mb-3">
                                 <h3>Recuperaci&oacute;n exitosa</h3>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-12 d-flex justify-content-center mb-3">
                                 Estimado usuario:<br>
                                 Hemos enviado un email con instrucciones a su casilla ${result.email} para la recuperaci&oacute;n de su password.<br><br>
                                 Atte: <br>
                                 Soporte Redvoiss.
                              </div>
                              <div class="col-12 d-flex justify-content-center mb-3">
                                 <a type="button" class="btn btn-sm btn-danger w-100" id="home" name="home" href="${url}/login/index.php">Volver al inicio</a>
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
                        width: 1200,
                     });
                  }
                  await spinnerCloseBtn('btn-recovery-login', 'Obtener correo de recuperaci&oacute;n');
               } catch (error) {
                  await spinnerCloseBtn('btn-recovery-login', 'Obtener correo de recuperaci&oacute;n');
                  console.error(`Error: ${error}`);
               }
            });
         </script>
      </body>
   </html>