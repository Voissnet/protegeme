<? require_once 'Parameters.php'; ?>

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
      <meta name="title" content="Web RTC">

      <script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/js/bootstrap.bundle.min.js"></script>
      <script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/js/jquery-3.7.1.min.js" ></script>
      <script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/js/popper.min.js"></script>
      <script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/webrtc/src/js/adapter.min.js" ></script>
      <script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/webrtc/src/js/bootbox.min.js"></script>
      <script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/webrtc/src/js/jquery.blockUI.min.js"></script>
      <script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/webrtc/src/js/toastr.min.js"></script>

      <!-- STYLES css -->
      <link rel="stylesheet" href="<?= Parameters::WEB_PATH ?>/webrtc/src/css/toastr.min.css">
      <link rel="stylesheet" href="<?= Parameters::WEB_PATH ?>/webrtc/src/css/all.min.css" type="text/css">
      <link rel="stylesheet" href="<?= Parameters::WEB_PATH ?>/css/bootstrap.min.css" type="text/css">
      <link rel="stylesheet" href="<?= Parameters::WEB_PATH ?>/css/stylesSite.css?v=2">
      <link rel="stylesheet" href="<?= Parameters::WEB_PATH ?>/webrtc/src/css/styles.css?v=<?= rand() ?>">

      <!-- sweetalert2 -->
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

      <!-- Drive.js -->
      <script src="<?= Parameters::WEB_PATH ?>/webrtc/src/js/driver.js"></script>
      <link rel="stylesheet" href="<?= Parameters::WEB_PATH ?>/webrtc/src/css/driver.css">

   </head>

   <body style="margin: 0; overflow: hidden;">

      <section id="section-contacts">

         <input type="hidden" id="bot_cod" name="bot_cod">
         <input type="hidden" id="busua_cod" name="busua_cod">
         <input type="hidden" id="domain" name="domain">
         
         <div class="container-fluid">

            <!-- TitleUser -->
            <div id="TitleUser" class="row">
               <div class="col-12 d-flex justify-content-between mb-2">
                  <h3 class="fw-normal">Perfil</h3>
                  <img id="question2" src="<?= Parameters::WEB_PATH ?>/webrtc/src/img/icon_question2.png" width="25" height="25" title="Uso de men&uacute;" onclick="use1(2)">
               </div>
            </div>
            
            <!-- MyCard -->
            <div id="myCard" class="row">
               <div class="col">
                  <div id="cardUser" class="card mb-3">
                     <button class="row g-0 accordion">
                        <div class="col-4 d-flex justify-content-center align-items-center">
                           <img src="<?= Parameters::WEB_PATH ?>/webrtc/src/img/contact_icon_red.png" width="80" height="80" class="img-fluid rounded-start" alt="Profile">
                        </div>
                        <div class="col-8 p-2 card-div">
                           <!--Name User-->
                           <h6 id="nameUserCard" class="card-title"></h6>
                           <p class="card-text"><small class="text-body-secondary">Mi perfil</small></p>
                        </div>
                     </button>
                     <div id="dataUser" class="panel"></div>
                  </div>
               </div>
            </div>

            <hr class="spacinghr">

            <!--contactBook -->
            <div id="contactBookUser">
               <div id="titleContacts" class="row">
                  <div class="col-12 d-flex justify-content-between align-items-center mb-3">
                     <!-- TitleContacts -->
                     <h3 class="fw-normal">Contactos</h3>
                     <!--AddContact-->
                     <button id="addContact" class="secondary-btn" onclick="showAddContactModal()">
                        <img src="<?= Parameters::WEB_PATH ?>/webrtc/src/img/icon_add_contact.png" width="30" height="30" alt="Add Contact">
                     </button>
                  </div>
               </div>

               <div class="row">
                  <div class="col wrapper">
                     <div id="contactBook"></div>
                  </div>
               </div>
            </div>

         </div>

         <!--Modal edit contact-->
         <div id="contactDrawer" class="drawer hidden">
            <div class="drawer-content">
               <div class="form-actions d-flex justify-content-between">
                  <button type="button" id="backEditContact" class="secondary-btn" onclick="closeContactDrawer()">
                     <img src="<?= Parameters::WEB_PATH ?>/webrtc/src/img/icon_left_arrow.png" width="30" height="30" alt="Back">
                  </button>
                  <button type="button" class="secondary-btn" onclick="deleteContact()">
                     <img src="<?= Parameters::WEB_PATH ?>/webrtc/src/img/icon_delete.png" width="30" height="30" alt="Delete contact">
                  </button>
               </div>
               <form id="contactForm" class="mb-5" role="form">
                  <input type="hidden" id="num" name="num">
                  <div class="profile-container">
                     <div id="profileNum" class="profile-num" aria-label="N&uacute;mero del contacto"></div>
                     <div id="profileAvatar" class="profile-avatar" aria-label="Avatar del contacto"></div>
                     <div id="profileName" class="profile-name" aria-label="Nombre del contacto"></div>
                     <fieldset class="form-group checkbox-group">
                        <h3 class="fw-normal">Preferencias contacto</h3>
                        <div class="checkbox-options">
                           <!-- Llamadas -->
                           <label for="call" class="checkbox-card">
                              <div id="checkCall" class="check-green div-checks"></div>
                              <button id="call" type="button" class="icon-box">
                                 <img src="<?= Parameters::WEB_PATH ?>/webrtc/src/img/icon_call.png" alt="Call">
                                 <div class="label-text">Llamadas</div>
                              </button>
                           </label>
                           <!-- SMS -->
                           <label for="sms" class="checkbox-card">
                              <div id="checkSMS" class="check-green div-checks"></div>
                              <button id="sms" type="button" class="icon-box">
                                 <img src="<?= Parameters::WEB_PATH ?>/webrtc/src/img/icon_sms.png" alt="SMS">
                                 <div class="label-text">SMS</div>
                              </button>
                           </label>
                           <!-- Escucha -->
                           <label for="listen" class="checkbox-card">
                              <div id="checkListen" class="check-secondary div-checks"></div>
                              <button id="listen" type="button" class="icon-box">
                                 <img src="<?= Parameters::WEB_PATH ?>/webrtc/src/img/icon_listen.png" alt="Listen">
                                 <div class="label-text">Escucha</div>
                              </button>
                           </label>
                        </div>
                     </fieldset>
                  </div>
               </form>
            </div>
         </div>

         <!--Modal edit contact-->
         <div id="contactAddDrawer" class="drawer hidden">
            <div class="drawer-content">
               <div class="form-actions">
                  <button type="button" id="backAddContact" class="secondary-btn" onclick="closeAddContactDrawer()">
                     <img src="<?= Parameters::WEB_PATH ?>/webrtc/src/img/icon_left_arrow.png" width="30" height="30" alt="Back">
                  </button>
               </div>
               <form id="addContactForm" class="d-flex flex-column justify-content-between mb-5" role="form" style="height: 100%;">
                  <div class="profile-container">
                     <div id="addProfileNum" class="profile-num" aria-label="N&uacute;mero del contacto"></div>
                     <div id="addProfileAvatar" class="profile-avatar" aria-label="Avatar del contacto"></div>
                     <div class="profileName" aria-label="Nombre del contacto">
                        <input type="text" class="text-center" id="addProfileNameInput" name="addProfileNameInput" title="Nombre de contacto" placeholder="Nombre de contacto" oninput="capitalizeFirstLetter(this)" autocomplete="off">
                     </div>
                     <fieldset class="form-group checkbox-group">
                        <h3 class="fw-normal">Preferencias contacto</h3>
                        <div class="checkbox-options mb-3">
                           <!-- Llamadas -->
                           <label for="addCall" class="checkbox-card">
                              <div id="checkCallAdd" class="check-green div-checks"></div>
                              <button id="addCall" type="button" class="icon-box">
                                 <img src="<?= Parameters::WEB_PATH ?>/webrtc/src/img/icon_call.png" alt="Add Call">
                                 <div class="label-text">Llamadas</div>
                              </button>
                           </label>
                           <!-- SMS -->
                           <label for="addSMS" class="checkbox-card">
                              <div id="checkSMSAdd" class="check-green div-checks"></div>
                              <button id="addSMS" type="button" class="icon-box">
                                 <img src="<?= Parameters::WEB_PATH ?>/webrtc/src/img/icon_sms.png" alt="Add SMS">
                                 <div class="label-text">SMS</div>
                              </button>
                           </label>
                           <!-- Escucha -->
                           <label for="addListen" class="checkbox-card">
                              <div id="checkListenAdd" class="check-secondary div-checks"></div>
                              <button id="addListen" type="button" class="icon-box">
                                 <img src="<?= Parameters::WEB_PATH ?>/webrtc/src/img/icon_listen.png" alt="Add Listen">
                                 <div class="label-text">Escucha</div>
                              </button>
                           </label>
                        </div>
                     </fieldset>
                     <div class="profile-num-add" aria-label="Nombre del contacto">
                        <input type="number" class="text-center" id="addProfileNumInput" name="addProfileNumInput" title="N&uacute;mero de contacto" placeholder="N&uacute;mero de contacto" oninput="handlePhoneInput(this)" onkeydown="onlyNumbers(event)" autocomplete="off">
                     </div>
                     <small style="color: #6b7280; margin-bottom: 25px; margin-top: 5px;">
                        Ej: 912345678
                     </small>
                  </div>

                  <div class="d-flex justify-content-center">
                     <button type="submit" class="boton-rojo-suave w-75">Registrar</button>
                  </div>

               </form>

            </div>

         </div>

      </section>

      <script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/webrtc/src/js/functions_webrtc.js?v=23"></script>

      <script type="text/javascript">

         document.addEventListener("DOMContentLoaded", async function () {

            try {

               const myCard = document.getElementById("myCard");
               const nameUser = document.getElementById("nameUserCard");

               // Validacion de sesion
               const response = await fetchProtectedData();
               if (!response || !response.data) {
                  console.error("Error al obtener los datos del usuario.");
                  return;
               }

               const { data } = response;

               // Asignando nombre de usuario
               if (data.name) {

                  nameUser.textContent = data.name;

               } else {

                  console.warn("El usuario no tiene un nombre registrado.");

               }

               document.getElementById('bot_cod').value = data.bot_cod;
               document.getElementById('busua_cod').value = data.sub;
               document.getElementById('domain').value = data.domain;

               // Obteniendo informacion del usuario
               const contacts = await viewDataUser(data);
               
               // Inicializando acordeon
               await iniAccordion();

               // libreta de contactos
               await contactBook(contacts, data.bot_cod);

            } catch (error) {

               console.error("Error durante la inicialización:", error);

            }

            document.getElementById('addContactForm').addEventListener('submit', async (e) => {

               e.preventDefault();
               
               // usuario
               const busua_cod = parseInt(document.getElementById('busua_cod').value);
               const bot_cod = parseInt(document.getElementById('bot_cod').value);

               // datos contactos
               const name = document.getElementById('addProfileNameInput').value.trim();
               const num = document.getElementById('addProfileNumInput').value.trim();

               // preferencias contacto
               const preCall = document.getElementById('addCall').getAttribute('data-value');
               const preSMS = document.getElementById('addSMS').getAttribute('data-value');
               const preListen = document.getElementById('addListen').getAttribute('data-value');

               if (num.length === 0) {
                  showToastError('Debe ingresar un número válido.');
                  return;
               }

               console.log(num);
               
               const data = {
                  'busua_cod': busua_cod,
                  'name': name,
                  'num': num,
                  'call': preCall,
                  'sms': preSMS,
                  'listen': preListen,
               }

               await addContact(bot_cod, data);

            });

         });


      </script>

   </body>

</html>