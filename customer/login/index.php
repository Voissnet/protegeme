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
         <title>Inicia sesi&oacute;n · Prot&eacute;geme Administrador</title>
         <meta name="title" content="Login Protegeme Administrador">
      
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
         <section id="login-client">
            <div class="container">
               <div class="row logo mb-2">
                  <div class="col-12 d-flex justify-content-center">
                     <img src="<?= Parameters::WEB_PATH ?>/img/logo-protegeme.webp" width="150" height="120">
                  </div>
               </div>
               <div class="row justify-content-center">
                  <div class="col-12 div-form-login border">
                     <form id="form-login" class="row" name="form" method="POST">
                        <div class="col-12 text-center mb-2">
                           <h2 class="title-login">Administrador</h2>
                        </div>
                        <div class="col-12 mb-2">
                           <div class="form-floating">
                              <input type="text" class="form-control" id="username" name="username" minlength="1" maxlength="80" aria-describedby="username-client" placeholder="Ingresa nombre de Usuario" title="Nombre usuario" autocomplete="off" onkeydown="onlySpace(event)" required>
                              <label for="username" class="col-form-label-sm">Nombre de usuario</label>
                              <div class="invalid-feedback">
                                 Ingresar usuario
                              </div>
                           </div>
                        </div>
                        <div class="col-12 mb-2">
                           <div class="form-floating">
                              <input type="password" class="form-control" id="password" name="password" minlength="1" maxlength="255" aria-describedby="password-client" placeholder="Ingrese su contrase&ntilde;a" title="Contrase&ntilde;a usuario" autocomplete="off" onkeydown="onlySpace(event)" required>
                              <label for="password" class="col-form-label-sm">Contrase&ntilde;a</label>
                              <div class="invalid-feedback">
                                 Ingresar contrase&ntilde;a
                              </div>
                           </div>
                        </div>
                        <div class="col-12 mb-2 d-flex justify-content-end">
                           <a class="text-decoration-none small link-recovery" href="../register/form_recuperar_clave.php">Recuperar contrase&ntilde;a</a>
                        </div>
                        <div class="col-12 d-flex flex-column align-items-center justify-content-center mb-2" id="div-capt">
                           <div id="captcha-login" class="g-recaptcha" data-sitekey="<?= MOD_ReCaptcha::RECAPTCHA_PUBLIC_KEY ?>"></div>
                        </div>
                        <div id="r-error" class="col-12 text-center mb-2 d-none">
                           <span class="text-danger fs-5"></span>
                           <br>
                           <span class="text-danger fs-6"></span>
                        </div>
                        <div class="col-12 mb-2">
                           <button type="submit" class="btn btn-sm btn-danger w-100" id="btn-login-user" name="btn-login-user"> 
                              <div id="spinner-btn-login-user" class="spinner-border spinner-border-sm text-white" role="status" hidden>
                                 <span class="visually-hidden">Loading...</span>
                              </div>
                              <span id="textbtn-btn-login-user">Iniciar sesi&oacute;n</span>
                           </button>
                        </div>
                        <div class="col-12 mb-2">
                           <a class="text-decoration-none btn-perso" href="<?= Parameters::WEB_PATH ?>/customer/register/form_inscripcion.php">Inscribirse</a>
                        </div>
                        <div class="col-12 d-flex justify-content-center mb-2 d-none">
                           <a href="#" class="btn btn-sm w-100" id="btn-demo">Solicitar una Demo</a>
                        </div>
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
         <script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/js/functions.js?<?= $v ?>"></script>
         <script type="text/javascript">
            document.getElementById('form-login').addEventListener('submit', async e => {
               try {
                  e.preventDefault();
                  let text_error = document.getElementById('r-error');
                  await spinnerOpenBtn('btn-login-user', '');
                  text_error.classList.add('d-none');
                  const status = await iniSession();
                  const retval = await validateSession(status);
                  await spinnerCloseBtn('btn-login-user', 'Iniciar sesi&oacute;n');
                  if (retval === true) {
                     window.location.href = `${url}/cuenta/index.php`;
                  }
               } catch (error) {
                  await spinnerCloseBtn('btn-login-user', 'Iniciar sesi&oacute;n');
                  console.error(`Error: ${error}`);
               }
            });
         </script>
      </body>
   </html>