'use strict'

import { openEmergency } from './site_module.js?44';              // acciona boton de emergencia
import { openSettings } from './site_module.js?44';               // acciona boton de configuraciones
import { closeContacts } from './site_module.js?44';              // cierra menu de contactos
import { clearHistory } from './site_module.js?44';               // limpia historial
import { spinnerContacts } from './site_module.js?44';            // spinner de carga
import { dataContacts } from './site_module.js?44';               // muestra informacion de contactos de llamadas
import { onlyNumbers } from './site_module.js?44';                // admite solo numeros
import { largeInput } from './site_module.js?44';                 // valida digito ingresado
import { localStorageSave } from './site_module.js?44';           // guarda un dato en el localstorage
import { createContact } from './site_module.js?44';              // agrega un contacto de emergencia llamadas
import { getLocationGPS } from './site_module.js?44';             // location
import { reserTimeOut } from './site_module.js?44';               // refresca tiempo del modal
import { createContactMovil } from './site_module.js?40';         // crea un contacto desde el movil
import { IPCManager } from 'https://cdn.jsdelivr.net/npm/@acrobits/ipc-sdk@0/+esm';

const manager = new IPCManager();

const Toast = Swal.mixin({
   toast: true,
   position: 'top-start',
   iconColor: 'danger',
   customClass: {
      popup: 'colored-toast',
   },
   showConfirmButton: false,
   timer: 2000,
   width: 'auto',
   iconColor: 'white',
   heightAuto: false,
   timerProgressBar: true,
});

let spinner = document.getElementById('spinner-bar');             // spinner de carga
let temp;                                                         // variable para almacenar la funcion de tiempo
let temGPS;

// carga menu
document.addEventListener('DOMContentLoaded', e => {

   const btncontacts = document.getElementById('contacts-div');
   const btnebergency = document.getElementById('emergency-div');
   const btnsettings = document.getElementById('settings-div');

   const plataforma = document.getElementById('plataforma').value;
   const bu = document.getElementById('bu').value;
   const cu = document.getElementById('cu').value;
   const cp = document.getElementById('cp').value;
   const du = document.getElementById('du').value;
   const pcontact = document.getElementById('pcontact').value;

   if (pcontact === '1') {
      btncontacts.onclick = () => openContacts(cu, cp, du);
   }
   
   getLocation();

   //temGPS = setInterval(getLocation, 5000);

   // Eventos touch solo moviles
   // escucha evento click (cuando lo presionan)
   btnebergency.addEventListener('click', e => {
      e.preventDefault();
      openEmergency();
      //delay();
   });

   btnsettings.addEventListener('click', e => {
      e.preventDefault();
      openSettings();
   });

});

// elimina delay
const deleteDelay = () => {
   clearTimeout(temp);
}

// acciona boton de emergencia
const delayFun = () => {
   if (parseInt(JSON.parse(localStorage.getItem('count'))) <= 1) {
      localStorage.removeItem('count');
      deleteDelay();
   }
}

// muestra la posicion
const showPosition = async (position) => {
   await getLocationGPS(document.getElementById('bu').value,
      `${document.getElementById('cu').value}@${document.getElementById('du').value}`,
      document.getElementById('cp').value,
      position.coords.latitude == undefined ? 'N/A' : position.coords.latitude,
      position.coords.longitude == undefined ? 'N/A' : position.coords.longitude,
      document.getElementById('plataforma').value,
      document.getElementById('appversion').value,
      document.getElementById('device').value,
      document.getElementById('appbuild').value
   );
}

const getLocation = async () => {
   let options = {
      enableHighAccuracy: false,
      timeout: 5000,
      maximumAge: 0,
   };
   navigator.geolocation.getCurrentPosition(showPosition, (err) => console.error(`ERROR(${err.code}): ${err.message}`), options);
}

// acciona delay de 1.2 segundos
const delay = () => {
   localStorageSave('count', 0);
   if (parseInt(JSON.parse(localStorage.getItem('count'))) === 0) {
      Toast.fire({
         icon: 'warning',
         html: '<i class="fa-solid fa-lock fa-xl"></i>'
      });
      temp = setTimeout(delayFun, 2000);
      localStorage.removeItem('count');
      localStorage.setItem('count', JSON.stringify(1));
   } else {
      Toast.fire({
         icon: 'warning',
         html: '<i class="fa-solid fa-lock-open fa-xl"></i>'
      });
      localStorage.removeItem('count');
      deleteDelay();
      setTimeout(() => {
         openEmergency();
      }, 500);

   }
}

// cierra modal
window.addEventListener('popstate', e => {
   closeContacts();
});

// formato de numero movil
const inputFormatMovil = (bu, val) => {
   let num = '';
   let x = 3;
   for (let i = 0; i < val.length; i++) {
      let element = val[i];
      if (val[0] === '+') { // si trae +, lo mas probable que venga con "+56"
         if (val[1] === '5' && val[2] === '6') {
            if (val[x] === '0') {
               num += val[x];
            }
            if (Number(val[x])) {
               num += val[x];
            }
            x++;
         }
      } else {
         if (element === '0') {
            num += element;
         }
         if (Number(element)) {
            num += element;
         }
      }
   }
   return num;
}

// selecciona contactos
const selectContacts = async () => {
   const dialog = confirm('Seleccione un contacto desde el móvil para ingresar.');
   if (dialog) {
      reserTimeOut();
      const bu = document.getElementById('bu').value;
      const contacts = await manager.selectContacts(1, 'single', 'uri');
      if (contacts.length) {
         const num = contacts[0]['uri'];
         const matchContacts = await manager.matchContacts(contacts);
         if (matchContacts.length) {
            const displayName = matchContacts[0].displayName.replace(/[^\w a-zA-Z0-9ñáéíóúÁÉÍÓÚÑ]/gi, '');
            createContactMovil(bu, inputFormatMovil(bu, num), `${displayName.trim()}`);
         }
      } else {
         reserTimeOut();
         alert('No se selecciono un contacto, acción cancelada.')
      }
   }
}

const initiateConnection = async () => {
   const context = await manager.determineContext();
   if (context === 'host') {
      await manager.initiateConnection();
   }
   selectContacts();
}

const viewContactMovil = async () => {
   try {
      reserTimeOut();
      await initiateConnection();
   } catch (err) {
      console.error(`Error: ${err}`);
      spinnerContacts(2);
   }
}

// menu de contactos, llamadas o sms
const formContactsEmergency = async (bu) => {
   try {
      return new Promise((resolve, reject) => {
         setTimeout(() => {
            let menu = document.getElementById('form-contacts');
            const appversion = parseFloat(document.getElementById('appversion').value);
            menu.innerHTML = '';
            menu.innerHTML += `
            <div class="col-12 d-flex justify-content-center p-0" id="dom-user">
               <span>${document.getElementById('du').value}</span>
            </div>
            <form id="form-emergency-${bu}" class="row border p-1 mb-2">
               <div class="col-12 d-flex justify-content-between mb-2 px-1">
                  <span id="title-contact-menu" class="fw-medium me-2 d-flex align-content-center">Ingreso</span>
               </div>
               <div class="col-12 mb-2 px-1" id="div-name-contact-${bu}">
                  <input type="text" class="form-control p-1 m-0" id="name-contact-add-${bu}" name="name-contact-add-${bu}" placeholder="Nombre" title="Nombre para el Contacto">
                  <div id="name-contact-add-${bu}-help" name="err-name-contact-add-${bu}" class="form-text fw-light" hidden></div>
               </div>
               <div class="col-12 mb-2 px-1">
                  <div class="input-group mb-3">
                     <span class="input-group-text p-1" id="num-contact-add-addon-${bu}">56</span>
                     <input type="text" class="form-control p-1 m-0" id="num-contact-add-${bu}" name="num-contact-add-${bu}" placeholder="N&uacute;mero" title="N&uacute;mero para el Contacto">
                  </div>
                  <div id="num-contact-add-${bu}-help" name="err-num-contact-add-${bu}" class="form-text fw-light" hidden></div>
               </div>
               <div class="col-12 mb-2 px-1 d-flex justify-content-start">
                  <div class="form-check mx-3">
                     <input class="form-check-input" type="checkbox" id="check-call-add-${bu}" checked>
                     <label class="form-check-label" for="check-call-add-${bu}">
                        Llamadas
                     </label>
                  </div>
                  <div class="form-check mx-3">
                     <input class="form-check-input" type="checkbox" id="check-sms-add-${bu}" checked>
                     <label class="form-check-label" for="check-sms-add-${bu}">
                        SMS
                     </label>
                  </div>
               </div>
               <div class="col-12 mb-2 px-1 d-flex justify-content-start">
                  <div id="check-add-${bu}-help" name="err-check-add-${bu}" class="form-text fw-light w-100" hidden></div>
               </div>
               <div class="col-12 d-flex justify-content-center mb-2 px-1">
                  <button type="submit" id="btn-add-contact-${bu}" class="btn btn-sm buttons disabledp colors-font pfont-size-input w-100">Ingreso de contactos manual</button>
               </div>
               ${appversion > 2.1 ? `
               <div class="col-12 d-flex justify-content-center mb-2 px-1">
                  <button class="btn btn-sm buttons colors-font pfont-size-input w-100" type="button" id="btn-add-news-groups-${bu}">Seleccionar contacto desde el m&oacute;vil</button>
               </div>
               ` : ''}
            </form>
            <hr>
            <div class="row p-0 mb-2">
               <div class="col-12 table-responsive p-0 m-0">
                  <table id="data-table-contacts-${bu}" class="table table-bordered m-0">
                     <thead>
                        <tr>
                           <th class="background-table colors-font fw-light pfont-size p-1">Nombre</th>
                           <th class="background-table colors-font fw-light pfont-size p-1">N&uacute;mero</th>
                           <th class="background-table colors-font fw-light pfont-size p-1 text-center"><i class="fa-solid fa-phone"></i></th>
                           <th class="background-table colors-font fw-light pfont-size p-1 text-center"><i class="fa-solid fa-comment-sms"></i></th>
                           <th class="background-table colors-font fw-light pfont-size p-1 text-center"><i class="fa-solid fa-ear-listen"></i></i></th>
                           <th class="background-table colors-font fw-light pfont-size p-1 text-center" width="10">
                              Eliminar
                           </th>
                        </tr>
                     </thead>
                     <tbody id="tbody-contacts-${bu}"></tbody>
                  </table>
               </div>
            </div>
            `;
            document.getElementById('form-emergency-' + bu).addEventListener('submit', e => {
               e.preventDefault();
               createContact(bu);
            });
            document.getElementById('num-contact-add-' + bu).addEventListener('keydown', e => {
               onlyNumbers(e, bu);
            });
            document.getElementById('num-contact-add-' + bu).addEventListener('keyup', e => {
               largeInput(e, bu);
            });
            document.getElementById('num-contact-add-' + bu).addEventListener('input', e => {
               largeInput(e, bu);
            });
            if (appversion > 2.1) {
               document.getElementById('btn-add-news-groups-' + bu).onclick = () => viewContactMovil();
            }
            resolve(bu);
         }, 300);
      });
   } catch (err) {
      console.error(`Error: ${err}`);
      spinnerContacts(2);
      reject(`Error: ${err}`);
   }
}

// informacion y prepara los menu del formulario
const menuContactsEmergency = async () => {
   try {
      let bu = parseInt(document.getElementById('bu').value);
      await spinnerContacts(1);
      await formContactsEmergency(bu);
      await dataContacts(bu);
      await spinnerContacts(2);
   } catch (err) {
      console.error(`Error: ${err}`);
   }
}

// acciona boton de contactos
const openContacts = async (cu, cp, du) => {
   try {
      setTimeout(() => {
         let modal = document.getElementById('data-contacts');
         modal.innerHTML = '';
         modal.innerHTML += /* html */ `
         <div class="modal fade" id="contacts-users" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="contacts-users-label" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
               <div class="modal-content">
                  <div class="modal-body container-fluid">
                     <div class="container-fluid p-0">
                        <div class="row border-bottom">
                           <div id="closex" class="col-1 py-2 px-0 me-2">
                              <a class="btn-close-contact link-pointer" data-bs-dismiss="modal"><i class="fa-solid fa-circle-arrow-left fa-2xl" title="Close Contacts"></i></a>   
                           </div>
                           <div class="col-10 d-flex justify-content-center pt-1 pb-2">
                              <a class="btn-contacts btn-select" id="menu-contacts" title="Contacts">
                                 <div id="spinner-contacts-emergency" class="spinner-border spinner-border-sm spinner-load mt-2" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                 </div>
                                 <span>Contactos de emergencias</span>
                              </a>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-12" id="form-contacts"></div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         `;
         document.getElementById('closex').onclick = () => clearHistory();
         document.getElementById('menu-contacts').onclick = () => menuContactsEmergency();
         let myModal = new bootstrap.Modal(document.getElementById('contacts-users'));
         myModal.show();
         menuContactsEmergency(1);
         window.history.pushState(null, null, `./index.php?cloud_username=${cu}@${du}&cloud_password=${cp}`);
      }, 300);
   } catch (err) {
      console.error(`Error: ${err}`);
   }
}

localStorage.clear();