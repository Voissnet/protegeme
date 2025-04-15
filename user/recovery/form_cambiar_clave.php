<? 
   require_once 'Parameters.php';
   require_once 'MOD_Error.php';

   if (!isset($_GET)) {
      MOD_Error::Error('PBE_117', 2);
      exit;
   }

   $token   = isset($_GET['token']) ? Parameters::openCypher('decrypt', $_GET['token']) : false;
   $bu      = isset($_GET['bu']) ? Parameters::openCypher('decrypt', $_GET['bu']) : false;
   $cu      = isset($_GET['cu']) ? Parameters::openCypher('decrypt', $_GET['cu']) : false;
   $du      = isset($_GET['du']) ? Parameters::openCypher('decrypt', $_GET['du']) : false;

   if ($token !== 'SResDvO2!9$32#01widJys56!?1ads') {
      MOD_Error::Error('PBE_117', 2);
      exit;
   }

   require_once 'BConexion.php';
   require_once 'BDominio.php';
   require_once 'BGrupo.php';
   require_once 'BUsuario.php';

   $DB      = new BConexion();
   $Dominio = new BDominio();
   $Grupo   = new BGrupo();
   $Usuario = new BUsuario();

   // verifica dominio usuario
   if ($Dominio->verificaDominioUsuario($du, $DB) === false) {
      $DB->Logoff();
      MOD_Error::Error('PBE_126', 2);
      exit;
   }

   // dominio debe estar activo
   if ($Dominio->esta_cod !== '1') {
      $DB->Logoff();
      MOD_Error::Error('PBE_124', 2);
      exit;
   }

   // buscamos usuario
   if ($Usuario->busca($bu, $DB) == false) {
      $DB->Logoff();
      MOD_Error::Error('PBE_126', 2);
      exit;
   }

   // busca grupo
   if ($Grupo->busca($Usuario->group_cod, $DB) === false) {
      $DB->Logoff();
      MOD_Error::Error('PBE_126', 2);
      exit;
   }

   // grupo debe estar activo
   if ($Grupo->esta_cod !== '1') {
      $DB->Logoff();
      MOD_Error::Error('PBE_118', 2);
      exit;
   }

   // dominio deben ser iguales
   if ($Dominio->dom_cod !== $Grupo->dom_cod) {
      $DB->Logoff();
      MOD_Error::Error('PBE_119', 2);
      exit;
   }

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
                           <h1 class="title-login">Cambiar contrase&ntilde;a:</h1>
                        </div>
                     </div>
                     <form id="form-reset-pass" class="row needs-validation" novalidate>
                        <div class="col-12 mb-3">
                           <div class="form-floating">
                              <input type="password" class="form-control" id="new-password" name="new-password" minlength="1" maxlength="255" aria-describedby="new-password" placeholder="Nueva contrase&ntilde;a" title="Nueva contrase&ntilde;a" autocomplete="off" onkeydown="onlySpace(event)">
                              <label for="new-password" class="col-form-label-sm">Contrase&ntilde;a</label>
                           </div>
                        </div>
                        <div class="col-12 mb-3">
                           <div class="form-floating">
                              <input type="password" class="form-control" id="new-password-v" name="new-password-v" minlength="1" maxlength="255" aria-describedby="new-password-v" placeholder="Verificar contrase&ntilde;a" title="Repita contrase&ntilde;a" autocomplete="off" onkeydown="onlySpace(event)">
                              <label for="new-password-v" class="col-form-label-sm">Verificar Contrase&ntilde;a</label>
                              <div class="invalid-feedback">
                                 Contrase&ntilde;as no coinciden
                              </div>
                           </div>
                        </div>
                        <div class="col-12 d-flex flex-column align-items-center justify-content-center mb-3" id="div-capt">
                           <div id="cap-reset" class="g-recaptcha" data-sitekey="<?= MOD_ReCaptcha::RECAPTCHA_PUBLIC_KEY ?>"></div>
                           <div class="invalid-feedback text-center">
                              Validar Captcha
                           </div>
                        </div>
                        <div id="r-error" class="col-12 text-center mb-2 d-none">
                           <span class="text-danger fs-5"></span>
                           <br>
                           <span class="text-danger fs-6"></span>
                        </div>
                        <div class="col-12 mb-2">
                           <button type="submit" class="btn btn-sm btn-danger w-100" id="btn-recovery-user" name="btn-recovery-user"> 
                              <div id="spinner-btn-recovery-user" class="spinner-border spinner-border-sm text-white" role="status" hidden>
                                 <span class="visually-hidden">Loading...</span>
                              </div>
                              <span id="textbtn-btn-recovery-user">Cambiar contrase&ntilde;a</span>
                           </button>
                        </div>
                        <input type="hidden" id="bu" value="<?= $bu ?>">
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
            document.getElementById('form-reset-pass').addEventListener('submit', async (e) => {

               e.preventDefault();

               try {

                  const texterror = document.getElementById('r-error');
                  texterror.classList.add('d-none');

                  await spinnerOpenBtn('btn-recovery-user');
                  await validateFormReset();
                  const rta = await updatePasswordUser();

                  grecaptcha.reset();

                  if (!rta.status) {
                     texterror.classList.remove('d-none');
                     texterror.querySelectorAll('span')[0].innerHTML = rta.message;
                     texterror.querySelectorAll('span')[1].innerHTML = `COD: ${rta.cod}`;
                     return;
                  }

                  alert(rta.message);

                  window.location.href = `${document.location.origin}/user/login/index.php`;

               } catch (error) {

                  console.error('Error:', error);

               } finally {

                  spinnerCloseBtn('btn-recovery-user', 'Cambiar contrase&ntilde;a');

               }

            });
         </script>
      </body>
   </html>