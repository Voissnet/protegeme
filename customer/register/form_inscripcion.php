<?
   require_once 'BConexion.php';
   require_once 'Parameters.php';
   require_once 'BMedidaEmpresa.php';
   require_once 'BRubro.php';
   require_once 'MOD_ReCaptcha.php';

   $v = rand();
   
   $DB = new BConexion();
?>
<!DOCTYPE html>
<html lang="es">
   <header>

      <!-- Required meta tags -->
      <meta http-equiv="Pragma" content="no-cache">
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      
      <!-- title -->
      <title>Inscripci&oacute;n · Prot&eacute;geme</title>
      <meta name="title" content="Register Protegeme">
      
      <!-- Bootstrap CSS -->
      <link rel="stylesheet" href="<?= Parameters::WEB_PATH ?>/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
      
      <!-- CAPTCHA -->
      <script src='<?= MOD_ReCaptcha::API_CAPTCHA ?>'></script>
      
      <!-- sweetalert2 -->
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

      <!-- STYLES css -->
      <link rel="stylesheet" href="<?= Parameters::WEB_PATH ?>/css/default.css?v<?= $v ?>">
      <link rel="stylesheet" href="<?= Parameters::WEB_PATH ?>/css/stylesRegister.css?v<?= $v ?>">

   </header>
   <body>
      <section id="register-client">
         <div class="container">
            <div class="row logo">
               <div class="col-12 d-flex justify-content-center">
                  <img src="<?= Parameters::WEB_PATH ?>/img/logo-protegeme.webp" width="150" height="120">
               </div>
            </div>
            <div class="row justify-content-center">
               <div id="div-form-register" class="col-12 border">
                  <div class="row my-2">
                     <div class="d-flex justify-content-center">
                        <h2 id="title-register">Inscripci&oacute;n Protegeme</h2>
                     </div>
                  </div>
                  <form id="form-register-client" name="form-register-client"class="row needs-validation" novalidate autocomplete="off">
                     <div class="my-3">
                        <div class="position-relative mx-4">
                           <div class="progress" role="progressbar" aria-label="Progress" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="height: 1px;">
                              <div class="progress-bar" id="progress-register" style="width: 0%; background-color: #dc3545 !important;"></div>
                           </div>
                           <button id="btn-register-op1" type="button" class="position-absolute top-0 start-0 translate-middle btn btn-sm btn-danger rounded-pill" style="width: 2rem; height:2rem;" data-bs-toggle="tooltipRegister" data-bs-html="true" data-bs-title="1. Datos empresa" tile="1. Datos empresa">1</button>
                           <button id="btn-register-op2" type="button" class="position-absolute top-0 progress_2 translate-middle btn btn-sm btn-secondary rounded-pill" style="width: 2rem; height:2rem;" data-bs-toggle="tooltipRegister" data-bs-html="true" data-bs-title="2. Datos contacto" tile="2. Datos contacto">2</button>
                           <button id="btn-register-op3" type="button" class="position-absolute top-0 progress_3 translate-middle btn btn-sm btn-secondary rounded-pill" style="width: 2rem; height:2rem;" data-bs-toggle="tooltipRegister" data-bs-html="true" data-bs-title="3. Datos cuenta" tile="3. Datos cuenta">3</button>
                           <button id="btn-register-op4" type="button" class="position-absolute top-0 start-100 translate-middle btn btn-sm btn-secondary rounded-pill" style="width: 2rem; height:2rem;" data-bs-toggle="tooltipRegister" data-bs-html="true" data-bs-title="4. Acuerdo" tile="4. Acuerdo">4</button>
                        </div>
                     </div>
                     <div class="col-12 mb-2 mt-2" name="op1">
                        <span class="sub-title-register">1. DATOS EMPRESA</span>
                     </div>
                     <div class="col-lg-6 mb-2" name="op1">
                        <label for="enterprise" class="form-label col-form-label-sm">(*) Empresa:</label>
                        <input type="text" class="form-control form-control-sm" id="enterprise" name="enterprise" minlength="2" maxlength="60" pattern="<?= Parameters::PATTERN_ALFANUMERICO ?>" aria-describedby="empresa-client" title="<?= Parameters::TEXT_ALFANUMERICO ?>" placeholder="Nombre de empresa" required autocomplete="off" onkeydown="onlySpaceInput(event)" onkeyup="validateInputsText(event, 2, 60)">
                        <div class="invalid-feedback">
                           <?= Parameters::TEXT_ALFANUMERICO ?>, m&iacute;nimo 2 caracteres.
                        </div>
                     </div>
                     <div class="col-lg-6 mb-2" name="op1">
                        <label for="reason_social" class="form-label col-form-label-sm">(*) Raz&oacute;n social:</label>
                        <input type="text" class="form-control form-control-sm" id="reason_social" name="reason_social" pattern="<?= Parameters::PATTERN_ALFANUMERICO_200 ?>" aria-describedby="razon-social-empresa" title="<?= Parameters::TEXT_ALFANUMERICO ?>" placeholder="Raz&oacute;n social" required autocomplete="off" onkeydown="onlySpaceInput(event)" onkeyup="validateInputsText(event, 5, 200)">
                        <div class="invalid-feedback">
                           <?= Parameters::TEXT_ALFANUMERICO ?>, m&iacute;nimo 5 caracteres.
                        </div>
                     </div>
                     <div class="col-lg-6 mb-2" name="op1">
                        <label class="form-label col-form-label-sm" for="rut_enterprise">(*) Rut:</label>
                        <input type="text" class="form-control form-control-sm" id="rut_enterprise" name="rut_enterprise" placeholder="99.999.999-9" title="Rut de la Empresa" aria-describedby="rut-empresa" required autocomplete="off" onkeydown="validateRut(event, 'err-status-rut-empresa')">
                        <div class="invalid-feedback">
                           Formato rut empresa incorrecto
                        </div>
                     </div>
                     <div class="col-lg-6 mb-2" name="op1">
                        <label for="med_cod" class="form-label col-form-label-sm">(*) Tama&ntilde;o:</label>
                        <select class="form-select form-select-sm" id="med_cod" name="med_cod" title="Tama&ntilde;o de la Empresa" aria-describedby="med-cod-empresa" onchange="validateInputSelect(this)" required>
                           <option value="-1">---SELECCIONAR</option>
                           <?
                           $Tamano  = new BMedidaEmpresa();
                           $stat    = $Tamano->Primero($DB);
                           while ($stat) {
                           ?>
                              <option value="<?= $Tamano->med_cod ?>"><?= $Tamano->descripcion ?></option>
                           <?
                              $stat = $Tamano->Siguiente($DB);
                           }
                           ?>
                        </select>
                        <div class="invalid-feedback">
                           Seleccione tama&ntilde;o
                        </div>
                     </div>
                     <div class="col-12 mb-2" name="op1">
                        <label for="rub_cod" class="form-label col-form-label-sm">(*) Rubro:</label>
                        <select class="form-select form-select-sm" id="rub_cod" name="rub_cod" title="Rubro de la Empresa" aria-describedby="rub-cod-empresa" onchange="validateInputSelect(this)" required>
                           <option value="-1">---SELECCIONAR</option>
                           <?
                           $Rubro  = new BRubro();
                           $stat    = $Rubro->Primero($DB);
                           while ($stat) {
                           ?>
                              <option value="<?= $Rubro->rub_cod ?>"><?= $Rubro->descripcion ?></option>
                           <?
                              $stat = $Rubro->Siguiente($DB);
                           }
                           ?>
                        </select>
                        <div class="invalid-feedback">
                           Seleccione rubro
                        </div>
                     </div>
                     <div class="col-12 mb-2" name="op1">
                        <button type="button" class="btn btn-sm btn-danger w-100" id="btn-op1" name="btn-op1" onclick="verifyForm1()"> 
                           <div id="spinner-btn-op1" class="spinner-border spinner-border-sm text-white" role="status" hidden>
                              <span class="visually-hidden">Loading...</span>
                           </div>
                           <span id="textbtn-btn-op1">Siguiente</span>
                        </button>
                     </div>
                     <div class="col-12 mb-2 mt-2 d-none" name="op2">
                        <span class="sub-title-register">2. DATOS CONTACTO</span>
                     </div>
                     <div class="col-lg-6 mb-2 d-none" name="op2">
                        <label for="names" class="form-label col-form-label-sm">(*) Nombres:</label>
                        <input type="text" class="form-control form-control-sm" id="names" name="names" aria-describedby="nombres-contact" minlength="1" maxlength="40" pattern="<?= Parameters::PATTERN_NAMES ?>" title="<?= Parameters::TEXT_NAMES ?>" placeholder="Nombres contacto" required autocomplete="off" onkeydown="onlySpaceInput(event)" onkeyup="validateInputsText(event, 1, 40)">
                        <div class="invalid-feedback">
                           Ingrese nombres
                        </div>
                     </div>
                     <div class="col-lg-6 mb-2 d-none" name="op2">
                        <label for="last_names" class="form-label col-form-label-sm">(*) Apellidos:</label>
                        <input type="text" class="form-control form-control-sm" id="last_names" name="last_names" aria-describedby="last-name-contact" minlength="1" maxlength="40" pattern="<?= Parameters::PATTERN_NAMES ?>" title="<?= Parameters::TEXT_NAMES ?>" placeholder="Apellidos contacto" required autocomplete="off" onkeydown="onlySpaceInput(event)" onkeyup="validateInputsText(event, 1, 40)">
                        <div class="invalid-feedback">
                           Ingrese apellidos
                        </div>
                     </div>
                     <div class="col-lg-6 mb-2 d-none" name="op2">
                        <label class="form-label col-form-label-sm" for="rut">(*) Rut:</label>
                        <input type="text" class="form-control form-control-sm" id="rut" name="rut" aria-describedby="rut-contact" placeholder="99.999.999-9" required autocomplete="off" onkeydown="validateRut(event, 'err-status-rut-contact')">
                        <div class="invalid-feedback">
                           Formato rut contacto incorrecto
                        </div>
                     </div>
                     <div class="col-lg-6 mb-2 d-none" name="op2">
                        <label for="post" class="form-label col-form-label-sm">(*) Cargo:</label>
                        <input type="text" class="form-control form-control-sm" id="post" name="post" aria-describedby="post-contact" minlength="2" maxlength="60" pattern="<?= Parameters::PATTERN_ALFANUMERICO ?>" title="<?= Parameters::TEXT_ALFANUMERICO ?>" placeholder="Cargo" required autocomplete="off" onkeydown="onlySpaceInput(event)" onkeyup="validateInputsText(event, 2, 60)">
                        <div class="invalid-feedback">
                           <?= Parameters::TEXT_ALFANUMERICO ?>, m&iacute;nimo 2 caracteres.
                        </div>
                     </div>
                     <div class="col-lg-6 mb-2 d-none" name="op2">
                        <label for="phone" class="form-label col-form-label-sm">(*) Tel&eacute;fono celular:</label>
                        <div class="input-group input-group-sm">
                           <span class="input-group-text" id="phone-contact">+56</span>
                           <input type="text" class="form-control form-control-sm" id="phone" name="phone" aria-describedby="phone-contact" pattern="<?= Parameters::PATTERN_TELEFONO ?>" title="<?= Parameters::TEXT_TELEFONO ?>" placeholder="Tel&eacute;fono celular" autocomplete="off" onkeydown="validatePhone(event)" onkeyup="largePhone(event, 9)" maxlength="9">
                           <div class="invalid-feedback">
                              Formato celular incorrecto, m&iacute;nimo 9 d&iacute;gitos.
                           </div>
                        </div>
                     </div>
                     <div class="col-lg-6 mb-2 d-none" name="op2">
                        <label for="telephone" class="form-label col-form-label-sm">Tel&eacute;fono fijo:</label>
                        <div class="input-group input-group-sm">
                           <span class="input-group-text" id="telephone-contact">+56</span>
                           <input type="text" class="form-control form-control-sm" id="telephone" name="telephone" aria-describedby="telephone-contact" pattern="<?= Parameters::PATTERN_TELEFONO ?>" placeholder="Tel&eacute;fono fijo" autocomplete="off" onkeydown="validatePhone(event)" onkeyup="largePhone(event, 10)" maxlength="10">
                           <div class="invalid-feedback">
                              Formato tel&eacute;fono incorrecto, m&iacute;nimo 10 d&iacute;gitos.
                           </div>
                        </div>
                     </div>
                     <div class="col-12 mb-2 d-none" name="op2">
                        <label for="email" class="form-label col-form-label-sm">(*) Correo electr&oacute;nico:</label>
                        <input type="email" class="form-control form-control-sm" id="email" name="email" aria-describedby="email-contact" title="<?= Parameters::TEXT_EMAIL ?>" placeholder="Correo electr&oacute;nico" required autocomplete="off" onkeydown="onlySpace(event)" onkeyup="validateEmailUser(this.value, 'email', 'err-status-email-contact')">
                        <div class="invalid-feedback">
                           Formato correo incorrecto
                        </div>
                     </div>
                     <div class="col-12 mb-2 d-none" name="op2">
                        <button type="button" class="btn btn-sm btn-danger w-100" id="btn-op2" name="btn-op2" onclick="verifyForm2()"> 
                           <div id="spinner-btn-op2" class="spinner-border spinner-border-sm text-white" role="status" hidden>
                              <span class="visually-hidden">Loading...</span>
                           </div>
                           <span id="textbtn-btn-op2">Siguiente</span>
                        </button>
                     </div>
                     <div class="col-12 mb-2 mt-2 d-none" name="op3">
                        <span class="sub-title-register">3. DATOS CUENTA</span>
                     </div>
                     <div class="col-12 mb-2 d-none" name="op3">
                        <label class="form-label col-form-label-sm" for="username">(*) Username:</label>
                        <input type="text" class="form-control form-control-sm" id="username" name="username" aria-describedby="username-account" minlength="7" maxlength="40" pattern="<?= Parameters::PATTERN_USERNAME ?>" title="<?= Parameters::TEXT_USERNAME ?>" required placeholder="Nombre de usuario" onkeydown="onlySpace(event)" onkeyup="buscaUsuarioValido(this.value)" autocomplete="off">
                        <div class="valid-feedback">¡Nombre de usuario disponible!</div>
                        <div id="validationServerUsuario" class="invalid-feedback">Nombre de usuario no disponible.</div>
                     </div>
                     <div class="col-12 mb-2 d-none" name="op3">
                        <label class="form-label col-form-label-sm" for="password">(*) Contrase&ntilde;a:</label>
                        <input type="password" class="form-control form-control-sm" id="password" name="password" aria-describedby="password-account" minlength="8" maxlength="60" pattern="<?= Parameters::PATTERN_PASSWORD ?>" title="<?= Parameters::TEXT_PASSWORD ?>" placeholder="Contrase&ntilde;a"  onkeydown="onlySpace(event)" onkeyup="verifyPassword()" required autocomplete="off">
                        <div class="invalid-feedback">
                           <?= Parameters::TEXT_PASSWORD ?>
                        </div>
                     </div>
                     <div class="col-12 mb-2 d-none" name="op3">
                        <label class="form-label col-form-label-sm" for="password_v">(*) Verifique Contrase&ntilde;a:</label>
                        <input type="password" class="form-control form-control-sm" id="password_v" name="password_v" aria-describedby="password-v-account" minlength="8" maxlength="60" pattern="<?= Parameters::PATTERN_PASSWORD ?>" title="<?= Parameters::TEXT_PASSWORD ?>" placeholder="Verifique contrase&ntilde;a"  onkeydown="onlySpace(event)" onkeyup="verifyPassword()" required autocomplete="off">
                        <div id="validationPassword" class="invalid-feedback">La contrase&ntilde;a de  verificaci&oacute;n no coincide con la dada.</div>
                     </div>
                     <div class="col-12 mb-2 d-none" name="op3">
                        <button type="button" class="btn btn-sm btn-danger w-100" id="btn-op3" name="btn-op3" onclick="verifyForm3()"> 
                           <div id="spinner-btn-op3" class="spinner-border spinner-border-sm text-white" role="status" hidden>
                              <span class="visually-hidden">Loading...</span>
                           </div>
                           <span id="textbtn-btn-op3">Siguiente</span>
                        </button>
                     </div>
                     <div class="col-12 mb-2 mt-2 d-none" name="op4">
                        <span class="sub-title-register">4. ACUERDO</span>
                     </div>
                     <div class="col-12 mb-2 d-none" name="op4">
                        <textarea id="agreement" aria-describedby="agreement-account" title="Acuerdo Redvoiss" class="form-control form-control-sm" rows="10" readonly="readonly">
                        <?
                           require_once 'inc_acuerdo.php';
                        ?>
                        </textarea>
                     </div>
                     <div class="col-12 mb-2 d-none" name="op4">
                        <div class="form-check">
                           <input class="form-check-input" type="checkbox" id="conditions" name="conditions" aria-describedby="conditions" title="Aceptaci&oacute;n del acuerdo Redvoiss">
                           <label class="form-check-label" for="conditions">
                              Acepto condiciones de acuerdo
                           </label>
                        </div>
                     </div>
                     <div class="col-12 mb-2 d-none" name="op4">
                        <div class="form-check">
                           <input class="form-check-input" type="checkbox" id="send_email" name="send_email" aria-describedby="send-email-account" value="1" checked title="Enviar promociones">
                           <label class="form-check-label" for="send_email">
                              Deseo recibir informaci&oacute;n sobre promociones o productos nuevos
                           </label>
                        </div>
                     </div>
                     <div class="col-12 d-flex flex-column align-items-center justify-content-center mb-2 d-none" name="op4">
                        <div id="cap-reset-register" class="g-recaptcha" data-sitekey="<?= MOD_ReCaptcha::RECAPTCHA_PUBLIC_KEY ?>"></div>
                        <div class="invalid-feedback text-center">
                           Validar Captcha
                        </div>
                     </div>
                     <div class="col-12 mb-2 d-none" name="op4">
                        <button type="submit" class="btn btn-sm btn-danger w-100" id="btn-op4" name="btn-op4"> 
                           <div id="spinner-btn-op4" class="spinner-border spinner-border-sm text-white" role="status" hidden>
                              <span class="visually-hidden">Loading...</span>
                           </div>
                           <span id="textbtn-btn-op4">Inscribirse</span>
                        </button>
                     </div>
                     <div id="div-before" class="col-12 text-center d-none mb-2">
                        <a id="btn-before" class="link-pointer" data-value="op1" onclick="beforeForm()">Volver</a>
                     </div>
                     <div class="col-12 mb-2">
                        <a href="<?= Parameters::WEB_PATH ?>/customer/login/index.php" class="text-decoration-none btn-perso">Volver al inicio</a>
                     </div>
                     <div class="col-12 text-center mb-2">
                        <a id="condition-link" class="link-pointer" target="_blank" href="<?= Parameters::WEB_PATH ?>/customer/register/condiciones.php">Condiciones de uso de protegeme</a>
                     </div>
                     <input type="hidden" id="err-status-email-contact" value="1">
                     <input type="hidden" id="err-status-rut-empresa" value="1">
                     <input type="hidden" id="err-status-rut-contact" value="1">
                     <input type="hidden" id="err-status-username" value="1">
                     <input type="hidden" id="err-status-pass" value="1">
                     <input type="hidden" id="err-status-pass-v" value="1">
                     <input type="hidden" id="err-status-conditions" value="1">
                     <input type="hidden" id="form-act" value="op1">
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
      <script src="<?= Parameters::WEB_PATH ?>/js/jquery.rut.js"></script>
      <script src="<?= Parameters::WEB_PATH ?>/js/functions.js?v<?= $v ?>"></script>
      <script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/customer/js/screens.js?v<?= $v ?>"></script>
      <script type="text/javascript">
         document.addEventListener("DOMContentLoaded", (e) => {
            tooltipSystem(`tooltipRegister`);
         });
         document.getElementById('form-register-client').addEventListener('submit', async e => {
            try {
               e.preventDefault();
               await spinnerOpenBtn('btn-op4', '');
               await verifyForm4();
               const { data, responseCap } = await verifyForm();
               await registerUser(data, responseCap);
               await spinnerCloseBtn('btn-op4', 'Inscribirse');
            } catch (error) {
               await spinnerCloseBtn('btn-op4', 'Inscribirse');
               console.error(`Error: ${error}`);
            }
         });
      </script>
   </body>
</html>