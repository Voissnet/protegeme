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
      <title>Cambio de contrase&ntilde;a · Prot&eacute;geme Operador</title>
      <meta name="title" content="Cambio de contrase&ntilde;a Protegeme Operador">

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
      <section id="form-update-password">
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
                        <h2 class="title-login">Cambiar contrase&ntilde;a:</h2>
                     </div>
                  </div>
                  <form id="form-reset-password-oper" class="row needs-validation">
                     <div class="col-12 mb-3">
                        <label class="form-label col-form-label-sm" for="password">(*) Contrase&ntilde;a:</label>
                        <input type="password" class="form-control form-control-sm" id="password" name="password" aria-describedby="password-account" minlength="8" maxlength="60" pattern="<?= Parameters::PATTERN_PASSWORD ?>" title="<?= Parameters::TEXT_PASSWORD ?>" placeholder="Contrase&ntilde;a"  onkeydown="onlySpace(event)" onkeyup="verifyPassword()" required autocomplete="off">
                        <div class="invalid-feedback">
                           <?= Parameters::TEXT_PASSWORD ?>
                        </div>
                     </div>
                     <div class="col-12 mb-3">
                        <label class="form-label col-form-label-sm" for="password_v">(*) Verifique Contrase&ntilde;a:</label>
                        <input type="password" class="form-control form-control-sm" id="password_v" name="password_v" aria-describedby="password-v-account" minlength="8" maxlength="60" pattern="<?= Parameters::PATTERN_PASSWORD ?>" title="<?= Parameters::TEXT_PASSWORD ?>" placeholder="Verifique contrase&ntilde;a"  onkeydown="onlySpace(event)" onkeyup="verifyPassword()" required autocomplete="off">
                        <div id="validationPassword" class="invalid-feedback">La contrase&ntilde;a de  verificaci&oacute;n no coincide con la dada.</div>
                     </div>
                     <div class="col-12 d-flex justify-content-center mb-3">
                        <div class="g-recaptcha" data-sitekey="<?= MOD_ReCaptcha::RECAPTCHA_PUBLIC_KEY ?>"></div>
                     </div>
                     <div id="r-error" class="col-12 text-center mb-3 d-none">
                        <span class="text-danger fs-5"></span>
                        <br>
                        <span class="text-danger fs-6"></span>
                     </div>
                     <input type="hidden" id="token" name="token" value="<?= str_replace([' '],['+'], $_GET['token']) ?>">
                     <input type="hidden" id="iv" name="iv" value="<?= str_replace([' '],['+'], $_GET['iv']) ?>">
                     <input type="hidden" id="uc_crypt" name="uc_crypt" value="<?= str_replace([' '],['+'], $_GET['uc_crypt']) ?>">
                     <div class="col-12 mb-3">
                        <button type="submit" class="btn btn-sm btn-danger w-100" id="btn-recovery-up-login-oper" name="btn-recovery-up-login-oper"> 
                           <div id="spinner-btn-recovery-up-login-oper" class="spinner-border spinner-border-sm text-white" role="status" hidden>
                              <span class="visually-hidden">Loading...</span>
                           </div>
                           <span id="textbtn-btn-recovery-up-login-oper">Cambiar contrase&ntilde;a</span>
                        </button>
                     </div>
                     <input type="hidden" id="err-status-pass" value="1">
                     <input type="hidden" id="err-status-pass-v" value="1">
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
      <script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/js/functions.js?<?= $v ?>"></script>
      <script type="text/javascript">
         document.addEventListener('DOMContentLoaded', function(event) {
            document.getElementById('form-reset-password-oper').addEventListener('submit', async e => {
               e.preventDefault();
               let text_error = document.getElementById('r-error');
               text_error.classList.add('d-none');
               await spinnerOpenBtn('btn-recovery-up-login-oper', '');
               const data = await validateFormPasswordReset();
               const result = await resetPasswordOper(data);
               await spinnerCloseBtn('btn-recovery-up-login-oper', 'Cambiar contrase&ntilde;a');
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
                              <h3>Cambio contrase&ntilde;a exitoso</h3>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-12 d-flex justify-content-center mb-3">
                              Estimado operador:<br>
                              El cambio de su contraseña ha sido exitoso. Ahora puede ingresar a su cuenta con su nueva contraseña<br><br>
                              Atte: <br>
                              Soporte Redvoiss.
                           </div>
                           <div class="col-12 d-flex justify-content-center mb-3">
                              <a type="button" class="btn btn-sm btn-danger w-100" id="home" name="home" href="${url_operator}/login/index.php">Ir al inicio</a>
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
            });
         });
      </script>
   </body>
</html>