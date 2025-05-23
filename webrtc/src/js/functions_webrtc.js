'use strict';

// url webrtc
const url_webrtc = `${document.location.origin}/webrtc`;

function waitPopover(op) {
   const observer = new MutationObserver(() => {

      const arrow = document.querySelector('.driver-popover-arrow');

      if (arrow) {

         if (op === 'start') {
            arrow.classList.remove('driver-popover-arrow-align-end');
            arrow.classList.add('driver-popover-arrow-align-start');
         }

         if (op === 'center') {
            arrow.classList.remove('driver-popover-arrow-align-end');
            arrow.classList.remove('driver-popover-arrow-none');
            arrow.classList.add('driver-popover-arrow-align-center');
         }

         observer.disconnect();

      }

   });

   observer.observe(document.body, {
      childList: true,
      subtree: true
   });
}


function use1(op) {

   const driver = window.driver.js.driver;
   let driverObj;

   if (parseInt(op) === 1) {

      driverObj = driver({
         popoverClass: 'driverjs-theme',
         showProgress: true,
         steps: [
            {
               element: '#btn-emergency', popover: { title: `Bot&oacute;n de emergencia`, description: `Inicia autom&aacute;ticamente una llamada al centro de atencii&oacute;n y notifica a los contactos.`, side: "bottom", align: 'center' },
               onHighlightStarted: () => {
                  waitPopover('center');
               }
            },
            {
               element: '#div1', popover: { description: `Centro de atenci&oacute;n y notificaci&oacute;n a contactos de emergencia.`, side: "top", align: "start" },
               onHighlightStarted: () => {
                  waitPopover('start');
               }
            },
            {
               element: "#div2", popover: { description: `Libreta de contactos`, side: "top", align: "center" },
               onHighlightStarted: () => {
                  waitPopover('center');
               }
            },
            { element: "#div3", popover: { description: `Informaci&oacute;n de la aplicaci&oacute;n.`, side: "top", align: "end" } }
         ],
         progressText: `{{current}} de {{total}}`,
         nextBtnText: ">>",
         prevBtnText: "<<",
         doneBtnText: "OK",
      });

   } else if (parseInt(op) === 2) {

      driverObj = driver({
         popoverClass: 'driverjs-theme',
         showProgress: true,
         steps: [
            { element: '#cardUser', popover: { title: 'Mi Perfil', description: 'Ficha personal, con los datos b&aacute;sicos proporcionados por tu proveedor.', side: "bottom", align: 'center' } },
            { element: '#contactBook', popover: { title: 'Libreta de contactos', description: 'Listado completo de contactos registrados en la plataforma.', side: "top", align: 'center' } },
            { element: '#addContact', popover: { title: `Agregar un contacto`, description: `Al presionar el bot&oacute;n '+', podrás incorporar un nuevo contacto al sistema.` } },
         ],
         progressText: `{{current}} de {{total}}`,
         nextBtnText: "siguiente",
         prevBtnText: "anterior",
         doneBtnText: "cerrar",
      });

   }

   driverObj.drive();
}


// mensaje de tipo tost
const Toast = Swal.mixin({
   toast: true,
   position: 'bottom-end',
   showConfirmButton: false,
   timer: 5000,
   timerProgressBar: true,
   width: 'auto',
   didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
   }
});

// error toast
const showToastError = async (text, width = 'auto', timer = 5000) => {
   await Toast.fire({
      icon: 'error',
      text,
      width,
      timer
   });
}

// prepara pregunta toast
const questionSweetAlert = async (text, width = 'auto') => {
   return await Swal.fire({
      html: text,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si',
      cancelButtonText: 'Cancelar',
      position: 'top',
      width
   });
}

// Solo permite numeros y teclas de edición/navegación
function onlyNumbers(e) {
   const key = e.which || e.keyCode;

   const isNumberKey = (key >= 48 && key <= 57) || (key >= 96 && key <= 105);
   const isEditingKey = [8, 9, 46, 37, 38, 39, 40].includes(key);

   if (!isNumberKey && !isEditingKey) {
      e.preventDefault();
   }
}

// valida la sesion jwt
async function fetchProtectedData() {

   try {

      // Obtener todas las cookies
      const cookie = document.cookie;

      // Expresion regular para extraer valor de jwt
      const match = cookie.match(/jwt=([^;]+)/);

      let jwt = '';

      if (match) {

         jwt = match[1];

      } else {

         alert("Error en el procesamiento de la sesión.");
         window.location.href = `${url_webrtc}/views/login.php?error=${encodeURIComponent("no_jwt")}`;
         return null;

      }

      if (!jwt) {

         alert("Error en el procesamiento de la sesión.");
         window.location.href = `${url_webrtc}/views/login.php?error=${encodeURIComponent("no_jwt")}`;
         return null;
      }

      const response = await fetch(`${url_webrtc}/auth/jwtprotected.php`, {
         method: "POST",
         headers: {
            "Content-Type": "application/json"
         },
         body: JSON.stringify({ token: jwt })
      });

      if (!response.ok) {

         console.error("Error en la solicitud:", response.statusText);
         throw new Error("No se pudo validar la sesión");

      }

      const rta = await response.json();

      if (!rta.success) {

         alert("Sesión expirada");

         const errorType = encodeURIComponent(rta.error || "none");
         window.location.href = `${url_webrtc}/views/login.php?error=${errorType}`;

         return null;

      }

      return rta;

   } catch (error) {

      console.error("Error al validar la sesion:", error);
      window.location.href = `${url_webrtc}/views/login.php?error=${encodeURIComponent("server_error")}`;
      return null;

   }

}

// Funcion asincrona para formatear numeros telefonicos chilenos
async function formatPhoneNumber(number) {

   // Elimina todos los caracteres que no sean digitos
   let clean = number.replace(/\D/g, '');

   // Si el numero comienza con el codigo de pais "56", lo eliminamos
   if (clean.startsWith("56")) {

      clean = clean.slice(2);

   }

   // Verificamos si es un numero movil chileno (comienza con 9 y tiene 9 digitos)
   if (clean.startsWith("9") && clean.length === 9) {

      // Formato: +56 9 XXXX XXXX
      return `+56 9 ${clean.slice(1, 5)} ${clean.slice(5)}`;

   }

   // Verificamos si es un numero fijo con codigo de area de 1 digito (por ejemplo, Santiago - 2)
   else if (clean.length === 9) {

      // Formato: +56 X XXXX XXXX
      return `+56 ${clean.slice(0, 1)} ${clean.slice(1, 5)} ${clean.slice(5)}`;

   }
   // Verificamos si es un numero fijo con codigo de area de 2 digitos (por ejemplo, Valparaiso - 32)
   else if (clean.length === 10) {

      // Formato: +56 XX XXXX XXXX
      return `+56 ${clean.slice(0, 2)} ${clean.slice(2, 6)} ${clean.slice(6)}`;

   } else {
      // Si no coincide con ningun formato esperado, retornamos el valor original
      return number;
   }
}

async function formatPhoneAddNumber(number) {

   // Permite conservar el signo '+' para numeros internacionales
   let isInternational = number.trim().startsWith('+');

   let clean = number.replace(/\D/g, '');

   if (!isInternational && clean.startsWith("56")) {
      clean = clean.slice(2);
   }

   // Numeros chilenos locales (se formatean)
   if (!isInternational) {

      if (clean.startsWith("9") && clean.length === 9) {

         return `+56 9 ${clean.slice(1, 5)} ${clean.slice(5)}`;

      } else if (clean.length === 9) {

         return `+56 ${clean.slice(0, 1)} ${clean.slice(1, 5)} ${clean.slice(5)}`;

      } else if (clean.length === 10) {

         return `+56 ${clean.slice(0, 2)} ${clean.slice(2, 6)} ${clean.slice(6)}`;

      } else {

         return number; // No coincide con ningun formato local conocido

      }

   } else {

      // Si es internacional, simplemente lo devuelve sin formatear adicional
      return `+${clean}`;

   }

}


function handlePhoneInput(input) {

   const original = input.value;

   // Llamamos a la funcion asincrona y actualizamos el campo al resolver
   formatPhoneAddNumber(original).then(formatted => {
      document.getElementById('addProfileNum').innerHTML = formatted;
   });

}

// obtiene informacion del boton web rtc
async function fetchDataUser(bot_cod) {
   try {
      const response = await fetch(`${url_webrtc}/json/client_fetch.php?bot_cod=${bot_cod}`, {
         method: "GET",
         headers: {
            "Content-Type": "application/json"
         }
      });
      if (!response.ok) {
         throw new Error(`Error en la solicitud: ${response.status} ${response.statusText}`);
      }
      return await response.json();
   } catch (error) {
      console.error('Error al obtener los datos:', error);
      return null;
   }
}

// prepara Accordion
async function iniAccordion() {

   const acc = document.getElementsByClassName("accordion");

   let i;

   for (i = 0; i < acc.length; i++) {

      acc[i].addEventListener("click", function () {

         document.querySelector(".card-div").classList.toggle("active");

         let panel = this.nextElementSibling;

         if (panel.style.maxHeight) {

            panel.style.maxHeight = null;

         } else {

            panel.style.maxHeight = panel.scrollHeight + "px";

         }

      });

   }

}

// prepara tetxo para mostrar informacion del usuario
async function viewDataUser(data) {

   try {

      const divDataUser = document.getElementById("dataUser");

      if (!divDataUser) {
         console.error("No se encontro el contenedor 'dataUser'.");
         return;
      }

      divDataUser.innerHTML = ""; // Limpiar contenido antes de asignar datos

      // Validar bot_cod
      const bot_cod = parseInt(data.bot_cod);
      if (isNaN(bot_cod)) {
         console.error("Codigo de usuario no válido:", data.bot_cod);
         return;
      }

      // Obtener informacion del usuario
      const { response } = await fetchDataUser(bot_cod);

      if (!response || !response.user || !response.webrtc) {
         console.error("Datos de usuario incompletos o inválidos.");
         return;
      }

      // informacion del usuario
      const { cloud_username, email, name, user_phone } = response.user;
      // informacion del webrtc
      const { sip_username, sip_password, sip_display_name, esta_cod, tipo_cod, tipo, localizacion, mac } = response.webrtc;
      // informacion de los contactos (esto lo devolvemos)
      const contacts = response.contacts;

      // Construccion segura del HTML
      divDataUser.innerHTML = `
      <div class="row">
         <div id="infoUser" class="col-12 py-2" style="font-size: 12px;">
            <span>&#128222; Tel&eacute;fono: ${user_phone ? user_phone : "No disponible"}</span><br>
            <span>&#128231; Correo: ${email ? email : "No disponible"}</span><br>
            <span>&#9729; Usuario: ${cloud_username ? `${cloud_username}@${data.domain}` : "No disponible"}</span>
         </div>
      </div>
      `;

      return contacts;

   } catch (error) {

      alert("Error al obtener la informacion del usuario. Por favor, intente nuevamente.");
      console.error("Error en viewDataUser:", error);

   }

}

// modifica preferencias del contatcos - llamada, sms y escucha
async function updateContactPreference(bot_cod, num, service) {

   try {

      // variables
      const call = document.getElementById('call');
      const sms = document.getElementById('sms');
      const listen = document.getElementById('listen');

      // checks
      const callCheck = document.getElementById('checkCall');
      const smsCheck = document.getElementById('checkSMS');
      const listenCheck = document.getElementById('checkListen');

      let val = 0;

      switch (parseInt(service)) {
         case 1:
            val = parseInt(call.getAttribute('data-value'));
            break;
         case 2:
            val = parseInt(sms.getAttribute('data-value'));
            break;
         case 3:
            val = parseInt(listen.getAttribute('data-value'));
            break;
      }

      // Enviamos los datos al servidor
      const rta = await fetch(`${url_webrtc}/json/update_preference.php`, {
         method: 'POST',
         headers: {
            'Content-Type': 'application/json'
         },
         body: JSON.stringify({
            bot_cod: bot_cod,
            num: num,
            service: service,
            state: val
         })
      });

      // Procesamos la respuesta del servidor
      if (!rta.ok) {

         const { message } = await rta.json();
         showToastError(message);
         return;

      }

      const responseData = await rta.json();

      // Validamos la respuesta estructural del backend
      if (responseData.success) {

         const icons = document.getElementById(`iconsPreferences${num}`);

         // valida insercion de icono llamadas
         const callIcon = parseInt(responseData.contact.state_call) === 1
            ? `<img src="${url_webrtc}/src/img/icon_call.png" class="mx-1" alt="Contacto habilitado para llamar" width="20" height="20">`
            : '';

         // valida insercion de icono sms
         const smsIcon = parseInt(responseData.contact.state_sms) === 1
            ? `<img src="${url_webrtc}/src/img/icon_sms.png" class="mx-1" alt="Contacto habilitado para enviar SMS" width="20" height="20">`
            : '';

         // valida insercion de icono escucha
         const listenIcon = parseInt(responseData.contact.state_listen) === 1
            ? `<img src="${url_webrtc}/src/img/icon_listen.png" class="mx-1" alt="Contacto habilitado para escuchar" width="20" height="20">`
            : '';

         icons.innerHTML = `${callIcon}${smsIcon}${listenIcon}`

         switch (parseInt(service)) {
            case 1:
               call.classList.remove('active-method', 'div-con-sombra', 'inactive-method');
               val === 1 ? call.classList.add('inactive-method') : call.classList.add('active-method', 'div-con-sombra');
               call.dataset.value = (val === 1 ? 2 : 1);
               callCheck.classList.remove('check-green', 'check-secondary');
               val === 1 ? callCheck.classList.add('check-secondary') : callCheck.classList.add('check-green');

               if (val === 1) {
                  listen.classList.remove('active-method', 'div-con-sombra', 'inactive-method');
                  val === 1 ? listen.classList.add('inactive-method') : listen.classList.add('active-method', 'div-con-sombra');
                  listen.dataset.value = (val === 1 ? 0 : 1);
                  listenCheck.classList.remove('check-green', 'check-secondary');
                  val === 1 ? listenCheck.classList.add('check-secondary') : listenCheck.classList.add('check-green');
               }

               break;
            case 2:
               sms.classList.remove('active-method', 'div-con-sombra', 'inactive-method');
               val === 1 ? sms.classList.add('inactive-method') : sms.classList.add('active-method', 'div-con-sombra');
               sms.dataset.value = val === 1 ? 2 : 1;
               smsCheck.classList.remove('check-green', 'check-secondary');
               val === 1 ? smsCheck.classList.add('check-secondary') : smsCheck.classList.add('check-green');
               break;
            case 3:
               listen.classList.remove('active-method', 'div-con-sombra', 'inactive-method');
               val === 1 ? listen.classList.add('inactive-method') : listen.classList.add('active-method', 'div-con-sombra');
               listen.dataset.value = (val === 1 ? 0 : 1);
               listenCheck.classList.remove('check-green', 'check-secondary');
               val === 1 ? listenCheck.classList.add('check-secondary') : listenCheck.classList.add('check-green');
               break;
         }

      } else {

         console.warn("El servidor respondio con un error logico:", responseData.message);
         alert("No se pudo actualizar la preferencia. Intente nuevamente.");

      }

   } catch (error) {

      console.error("Error in updateContactPreference:", error);
      alert("Se produjo un error al actualizar la preferencia. Por favor, intente nuevamente.");

   }

}

// Funcion para abrir el modal y cargar los datos del contacto
async function openContactDrawer(contact, bot_cod) {

   // asigna numero al formulario
   document.getElementById('num').value = contact.num;

   // asigna primera letra del nombre al formulario
   document.getElementById('profileAvatar').innerHTML = (contact.name?.trim?.()[0]?.toUpperCase?.()) || `#`;

   // asigna nombre
   document.getElementById('profileName').innerHTML = (contact.name?.trim()) || `#`;

   // asigna numero a la interfaz
   document.getElementById('profileNum').innerHTML = await formatPhoneNumber(contact.num);

   // estado, asignacion de colores:
   const call = document.getElementById('call');
   const sms = document.getElementById('sms');
   const listen = document.getElementById('listen');

   // checks
   const checkCall = document.getElementById('checkCall');
   const checkSMS = document.getElementById('checkSMS');
   const checkListen = document.getElementById('checkListen');

   // limpia clases de los botones
   call.classList.remove('active-method', 'div-con-sombra', 'inactive-method');
   sms.classList.remove('active-method', 'div-con-sombra', 'inactive-method');
   listen.classList.remove('active-method', 'div-con-sombra', 'inactive-method');

   // limpia los checks
   checkCall.classList.remove('check-green', 'check-secondary');
   checkSMS.classList.remove('check-green', 'check-secondary');
   checkListen.classList.remove('check-green', 'check-secondary');

   // agrega clases definidas en los css
   parseInt(contact.state_call) === 1 ? call.classList.add('active-method', 'div-con-sombra') : call.classList.add('inactive-method');
   parseInt(contact.state_sms) === 1 ? sms.classList.add('active-method', 'div-con-sombra') : sms.classList.add('inactive-method');
   parseInt(contact.state_listen) === 1 ? listen.classList.add('active-method', 'div-con-sombra') : listen.classList.add('inactive-method');

   parseInt(contact.state_call) === 1 ? checkCall.classList.add('check-green') : checkCall.classList.add('check-secondary');
   parseInt(contact.state_sms) === 1 ? checkSMS.classList.add('check-green') : checkSMS.classList.add('check-secondary');
   parseInt(contact.state_listen) === 1 ? checkListen.classList.add('check-green') : checkListen.classList.add('check-secondary');

   // asigna valor actual
   call.setAttribute('data-value', contact.state_call);
   sms.setAttribute('data-value', contact.state_sms);
   listen.setAttribute('data-value', contact.state_listen);

   // agrega evento correspondiente
   call.onclick = () => updateContactPreference(bot_cod, contact.num, 1);
   sms.onclick = () => updateContactPreference(bot_cod, contact.num, 2);
   listen.onclick = () => updateContactPreference(bot_cod, contact.num, 3);

   // Mostrar el modal
   document.getElementById('contactDrawer').classList.add('show');

}

function updateContactPreferenceAdd(service) {

   try {

      // variables
      const call = document.getElementById('addCall');
      const sms = document.getElementById('addSMS');
      const listen = document.getElementById('addListen');

      // checks
      const callCheck = document.getElementById('checkCallAdd');
      const smsCheck = document.getElementById('checkSMSAdd');
      const listenCheck = document.getElementById('checkListenAdd');

      let val = 0;

      switch (parseInt(service)) {
         case 1:
            val = parseInt(call.getAttribute('data-value'));
            break;
         case 2:
            val = parseInt(sms.getAttribute('data-value'));
            break;
         case 3:
            val = parseInt(listen.getAttribute('data-value'));
            break;
      }

      switch (parseInt(service)) {
         case 1:
            call.classList.remove('active-method', 'div-con-sombra', 'inactive-method');
            val === 1 ? call.classList.add('inactive-method') : call.classList.add('active-method', 'div-con-sombra');
            call.dataset.value = (val === 1 ? 2 : 1);
            callCheck.classList.remove('check-green', 'check-secondary');
            val === 1 ? callCheck.classList.add('check-secondary') : callCheck.classList.add('check-green');
            if (val === 1) {
               listen.classList.remove('active-method', 'div-con-sombra', 'inactive-method');
               val === 1 ? listen.classList.add('inactive-method') : listen.classList.add('active-method', 'div-con-sombra');
               listen.dataset.value = (val === 1 ? 0 : 1);
               listenCheck.classList.remove('check-green', 'check-secondary');
               val === 1 ? listenCheck.classList.add('check-secondary') : listenCheck.classList.add('check-green');
            }
            break;
         case 2:
            sms.classList.remove('active-method', 'div-con-sombra', 'inactive-method');
            val === 1 ? sms.classList.add('inactive-method') : sms.classList.add('active-method', 'div-con-sombra');
            sms.dataset.value = val === 1 ? 2 : 1;
            smsCheck.classList.remove('check-green', 'check-secondary');
            val === 1 ? smsCheck.classList.add('check-secondary') : smsCheck.classList.add('check-green');
            break;
         case 3:
            listen.classList.remove('active-method', 'div-con-sombra', 'inactive-method');
            val === 1 ? listen.classList.add('inactive-method') : listen.classList.add('active-method', 'div-con-sombra');
            listen.dataset.value = (val === 1 ? 0 : 1);
            listenCheck.classList.remove('check-green', 'check-secondary');
            val === 1 ? listenCheck.classList.add('check-secondary') : listenCheck.classList.add('check-green');
            break;
      }

   } catch (error) {

      console.error("Error in updateContactPreference:", error);
      alert("Se produjo un error al actualizar la preferencia. Por favor, intente nuevamente.");

   }

}

// cierra modal add
function closeAddContactDrawer() {
   document.getElementById('contactAddDrawer').classList.remove('show');
}


// cierra modal
function closeContactDrawer() {
   document.getElementById('contactDrawer').classList.remove('show');
}

// busca unfirmacion del contacto
async function fetchContact(bot_cod, num) {
   try {
      const response = await fetch(`${url_webrtc}/json/contact_fetch.php?bot_cod=${bot_cod}&num=${num}`, {
         method: "GET",
         headers: {
            "Content-Type": "application/json"
         }
      });
      if (!response.ok) {
         throw new Error(`Error en la solicitud: ${response.status} ${response.statusText}`);
      }
      return await response.json();
   } catch (error) {
      console.error('Error al obtener los datos:', error);
      return null;
   }
}

// abre modal con la info del contacto
async function viewFormContact(bot_cod, num) {
   try {
      // busca estado del numero
      const { response } = await fetchContact(bot_cod, num);
      await openContactDrawer(response, bot_cod);
   } catch (error) {
      console.error("Error al procesar el contacto:", error);
   }
}

// elimina contacto
async function deleteContact() {
   try {

      const question = await questionSweetAlert('Esta acción eliminará el contacto de forma permanente. ¿Desea continuar?');

      if (question.isConfirmed) {

         const busua_cod = parseInt(document.getElementById('busua_cod').value);
         const bot_cod = parseInt(document.getElementById('bot_cod').value);
         const num = parseInt(document.getElementById('num').value);

         const response = await fetch(`${url_webrtc}/json/delete_contact.php`, {
            method: 'POST',
            headers: {
               'Content-Type': 'application/json'
            },
            body: JSON.stringify({
               busua_cod: busua_cod,
               bot_cod: bot_cod,
               num: num,
            })
         });

         // Verifica si la eliminacion fue exitosa
         if (response.ok) {

            // Obtener informacion del usuario
            const data = await fetchDataUser(bot_cod);

            // libreta de contactos
            await contactBook(data.response.contacts, bot_cod);

            // asigna bot_cod y numero al formulario
            document.getElementById('num').value = '';

            // asigna primera letra del nombre al formulario
            document.getElementById('profileAvatar').innerHTML = '';

            // asigna nombre
            document.getElementById('profileName').innerHTML = '';

            // asigna numero a la interfaz
            document.getElementById('profileNum').innerHTML = '';

            // estado, asignacion de colores:
            const call = document.getElementById('call');
            const sms = document.getElementById('sms');
            const listen = document.getElementById('listen');

            // limpia clases de los botones
            call.classList.remove('active-method', 'div-con-sombra', 'inactive-method');
            sms.classList.remove('active-method', 'div-con-sombra', 'inactive-method');
            listen.classList.remove('active-method', 'div-con-sombra', 'inactive-method');

            // asigna valor actual
            call.setAttribute('data-value', '');
            sms.setAttribute('data-value', '');
            listen.setAttribute('data-value', '');

            // agrega evento correspondiente
            call.onclick = null;
            sms.onclick = null;
            listen.onclick = null;

            closeContactDrawer();

         } else {

            console.error(`Failed to delete contact ${num}.`);
            // Manejo de errores segun la respuesta del servidor

         }

      }

   } catch (error) {

      // Captura errores de red u otros fallos inesperados
      console.error(`Error deleting contact:`, error);

   }

}

// Libreta de contactos
async function contactBook(contacts, bot_cod) {

   try {

      // Organiza los nombres por letra inicial
      function groupNamesByLetter(list) {

         const grouped = {};

         list.forEach(contact => {

            const name = contact?.name ?? null; // si nombre no viene asigna null
            const num = contact?.num;  // numero, lo utilizamos como id
            const call = contact?.state_call;  // llamada
            const sms = contact?.state_sms;  // sms
            const listen = contact?.state_listen;  // escucha

            // Determinar letra del grupo
            const letter = (typeof name === 'string' && name.length > 0)
               ? name.charAt(0).toUpperCase()
               : '#'; // Agrupar nombres nulos o vacios bajo '#'

            if (!grouped[letter]) grouped[letter] = []; // Si no existe un grupo para la letra, se inicializa como arreglo vacio

            grouped[letter].push({ name, num, call, sms, listen }); // Se conserva el nombre, aunque sea null

         });

         return grouped;
      }

      // genera libreta de contactos
      function generateContent(groupedLetters) {

         // Obtiene el contenedor donde se mostrará la lista de contactos
         const container = document.getElementById("contactBook");
         container.innerHTML = ""; // Limpia el contenido previo del contenedor

         // Definir el alfabeto en mayusculas para el agrupamiento
         const alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ".split("");

         // Itera sobre cada letra del alfabeto
         alphabet.forEach(letter => {

            // Si existen contactos que empiezan con esta letra, se renderiza su seccion
            if (groupedLetters[letter]) {

               // Crea un contenedor <div> para la seccion correspondiente a la letra
               const section = document.createElement("div");
               section.id = letter; // Asigna el id de la seccion con la letra

               // Genera el HTML para estos contactos, similar a las demas secciones
               const namesHTML = groupedLetters[letter].map(contact => {

                  // valida insercion de icono llamadas
                  const callIcon = parseInt(contact.call) === 1
                     ? `<img src="${url_webrtc}/src/img/icon_call.png" class="mx-1" alt="Contacto habilitado para llamar" width="20" height="20">`
                     : '';

                  // valida insercion de icono sms
                  const smsIcon = parseInt(contact.sms) === 1
                     ? `<img src="${url_webrtc}/src/img/icon_sms.png" class="mx-1" alt="Contacto habilitado para enviar SMS" width="20" height="20">`
                     : '';

                  // valida insercion de icono escucha
                  const listenIcon = parseInt(contact.listen) === 1
                     ? `<img src="${url_webrtc}/src/img/icon_listen.png" class="mx-1" alt="Contacto habilitado para escuchar" width="20" height="20">`
                     : '';

                  return `
                  <hr class="spacingname">
                  <div class="names d-flex justify-content-between link-pointer" id="${contact.num}" onclick="viewFormContact('${bot_cod}', '${contact.num}')">
                     <span>${contact.name}</span>
                     <div id="iconsPreferences${contact.num}">
                        ${callIcon}${smsIcon}${listenIcon}
                     </div>
                  </div>
               `;
               }).join(""); // Se muestra el numero (ID) de cada contacto en lugar del nombre

               // Inserta la letra de la seccion y los contactos generados en el contenedor
               section.innerHTML = `
               <div class="letter-section">${letter}</div>
               ${namesHTML}
               `;

               // Añade la seccion generada al contenedor principal
               container.appendChild(section);
               // Añade una linea de separacion después de cada seccion
               container.appendChild(document.createElement("hr")).classList.add("spacinghr");
            }

         });

         // Agrupa los contactos que no empiezan con una letra (numeros, simbolos, etc.)
         const nonAlphabeticContacts = Object.entries(groupedLetters)
            .filter(([letter]) => !alphabet.includes(letter)) // Filtra las entradas que no son letras
            .flatMap(([_, contacts]) => contacts); // Extrae los contactos de estas entradas

         // Si existen contactos no alfabéticos, se crea una seccion especial para ellos
         if (nonAlphabeticContacts.length > 0) {

            const section = document.createElement("div");
            section.id = "#"; // Asigna un id especial para los contactos no alfabéticos

            // Genera el HTML para estos contactos, similar a las demas secciones
            const namesHTML = nonAlphabeticContacts.map(contact => {

               // valida insercion de icono llamadas
               const callIcon = parseInt(contact.call) === 1
                  ? `<img src="${url_webrtc}/src/img/icon_call.png" class="mx-1" alt="Contacto habilitado para llamar" width="20" height="20">`
                  : '';

               // valida insercion de icono sms
               const smsIcon = parseInt(contact.sms) === 1
                  ? `<img src="${url_webrtc}/src/img/icon_sms.png" class="mx-1" alt="Contacto habilitado para enviar SMS" width="20" height="20">`
                  : '';

               // valida insercion de icono escucha
               const listenIcon = parseInt(contact.listen) === 1
                  ? `<img src="${url_webrtc}/src/img/icon_listen.png" class="mx-1" alt="Contacto habilitado para escuchar" width="20" height="20">`
                  : '';

               return `
                  <hr class="spacingname">
                  <div class="names d-flex justify-content-between link-pointer" id="${contact.num}" onclick="viewFormContact('${bot_cod}', '${contact.num}')">
                     <span>${contact.num}</span>
                     <div id="iconsPreferences${contact.num}">
                        ${callIcon}${smsIcon}${listenIcon}
                     </div>
                  </div>
               `;
            }).join(""); // Se muestra el numero (ID) de cada contacto en lugar del nombre

            // Inserta la seccion para los contactos no alfabéticos
            section.innerHTML = `
            <div class="letter-section">#</div>
            ${namesHTML}
            `;

            // Añade la seccion al contenedor principal
            container.appendChild(section);
            // Añade una linea de separacion después de esta seccion
            container.appendChild(document.createElement("hr")).classList.add("spacinghr");

         }

         // Calcula y muestra el total de contactos al final
         // Filtra y cuenta solo los contactos agrupados bajo letras del abecedario
         const totalContacts = alphabet.reduce((acc, letter) => {
            return acc + (groupedLetters[letter]?.length || 0); // Suma la cantidad de contactos por letra
         }, 0) + nonAlphabeticContacts.length; // Añade los contactos no alfabéticos

         // Crea una seccion para mostrar el total de contactos
         const totalSection = document.createElement("div");
         totalSection.id = "totalContact";
         totalSection.classList.add("mb-3", "pt-2");
         totalSection.innerHTML = `
         <div class="names text-center">${totalContacts} Contactos</div>
         `;

         // Añade la seccion del total al contenedor principal
         container.appendChild(totalSection);

      }


      // Procesa los datos y genera el contenido en pantalla
      const groupedLetters = groupNamesByLetter(contacts);

      generateContent(groupedLetters);

   } catch (error) {

      // Muestra alerta en caso de error y registra en consola
      alert("Error al cargar la libreta de contactos. Por favor, intente nuevamente.");
      console.error("Error en contactBook:", error);

   }

}

// abre modal para agregar contactos
async function showAddContactModal() {

   try {

      // asigna primera letra del nombre al formulario
      document.getElementById('profileAvatar').innerHTML = '';

      // limpia nombre
      document.getElementById('addProfileNameInput').value = '';

      // limpia numero
      document.getElementById('addProfileNumInput').value = '';
      document.getElementById('addProfileNum').innerHTML = '';

      // asigna nombre al avatar
      document.getElementById('addProfileAvatar').innerHTML = '#';

      // asigna nombre
      document.getElementById('profileName').innerHTML = '';

      // asigna numero a la interfaz
      document.getElementById('profileNum').innerHTML = '';

      // estado, asignacion de colores:
      const call = document.getElementById('addCall');
      const sms = document.getElementById('addSMS');
      const listen = document.getElementById('addListen');

      // limpia clases de los botones
      call.classList.remove('active-method', 'div-con-sombra', 'inactive-method');
      sms.classList.remove('active-method', 'div-con-sombra', 'inactive-method');
      listen.classList.remove('active-method', 'div-con-sombra', 'inactive-method');

      // agrega clases definidas en los css
      call.classList.add('active-method', 'div-con-sombra');
      sms.classList.add('active-method', 'div-con-sombra');
      listen.classList.add('inactive-method');

      // asigna valor actual
      call.setAttribute('data-value', '1');
      sms.setAttribute('data-value', '1');
      listen.setAttribute('data-value', '0');

      // // agrega evento correspondiente
      call.onclick = () => updateContactPreferenceAdd(1);
      sms.onclick = () => updateContactPreferenceAdd(2);
      listen.onclick = () => updateContactPreferenceAdd(3);

      // Mostrar el modal
      document.getElementById('contactAddDrawer').classList.add('show');

   } catch (error) {
      // Muestra alerta en caso de error y registra en consola
      alert("Error al cargar vista para agregar contacto. Por favor, intente nuevamente.");
      console.error("Error en showAddContactModal:", error);
   }

}

// controla espacios y formatea la primera letra ingresada mostrandola en el profileAvatar
function capitalizeFirstLetter(inputElement) {

   let rawValue = inputElement.value;

   // 1. Eliminar solo el primer espacio si existe
   if (rawValue.startsWith(' ')) {
      rawValue = rawValue.slice(1);
   }

   // 2. Capitalizar la primera letra sin afectar el resto
   if (rawValue.length > 0) {
      rawValue = rawValue.charAt(0).toUpperCase() + rawValue.slice(1);
   }

   // 3. Actualizar el avatar segun longitud
   const avatarElement = document.getElementById('addProfileAvatar');

   if (avatarElement) {

      if (rawValue.length === 1) {

         avatarElement.innerHTML = rawValue;

      } else if (rawValue.length === 0) {

         avatarElement.innerHTML = '#';

      }

   }

   // 4. Asignar el valor procesado al input
   inputElement.value = rawValue;
}

// agrega contacto
async function addContact(bot_cod, params) {

   try {

      const rta = await fetch(`${url_webrtc}/json/add_contact.php`, {
         method: 'POST',
         headers: {
            'Content-Type': 'application/json'
         },
         body: JSON.stringify(params)
      });

      if (!rta.ok) {
         const data = await rta.json();
         showToastError(data.message);
         return;
      }

      const { message } = await rta.json();

      // obtener informacion del usuario
      const data = await fetchDataUser(bot_cod);

      // libreta de contactos
      await contactBook(data.response.contacts, bot_cod);

      closeAddContactDrawer();

      // success toast
      await Toast.fire({
         icon: 'success',
         title: message,
         width: 'auto',
         timer: 5000
      });


   } catch (error) {

      // Captura errores de red u otros fallos inesperados
      console.error(`Error add contact:`, error);

   }

}