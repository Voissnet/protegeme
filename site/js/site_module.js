'use strict'

let formatNum = /^[0-9]{9,10}$/;
let timeModal;

// guarda un dato en localStorage
export const localStorageSave = (cod, val) => {
   if (window.localStorage.getItem(cod) !== undefined && window.localStorage.getItem(cod)) {
      JSON.parse(localStorage.getItem(cod));
   } else {
      localStorage.removeItem(cod);
      localStorage.setItem(cod, JSON.stringify(val));
   }
}

// cierra modal
export const closeContacts = async (cu, cp, du) => {
   try {
      setTimeout(() => {
         Swal.close();
         let myModal = document.getElementById('contacts-users') === null ? null : document.getElementById('contacts-users');
         if (myModal !== null) {
            let modal = bootstrap.Modal.getInstance(myModal)
            modal.hide();
            document.getElementById('data-contacts').innerHTML = '';
         }
      }, 300);
   } catch (err) {
      console.error(`Error: ${err}`);
   }
}

// cierra el modal despues de 2 minutos pasados
const delayFunModalClose = () => {
   closeContacts();
}

// para resetear uso
export const reserTimeOut = () => {
   clearTimeout(timeModal);
   timeModal = setTimeout(delayFunModalClose, 60000);
}

// alerta personalizada
export const resSweetAlert = async (html) => {
   reserTimeOut();
   return Swal.mixin({
      customClass: {
         confirmButton: `btn btn-sm btn-danger mx-2 pfont-size-alert`,
         cancelButton: `btn btn-sm btn-primary mx-2 pfont-size-alert`
      },
      buttonsStyling: false
   }).fire({
      html,
      confirmButtonText: 'Cerrar',
      confirmButtonColor: '#ff0000',
      position: 'center',
      allowEscapeKey: false,
      allowOutsideClick: false,
   });
}

// ayuda a saber el largo de caractres de un campo
export const largeInput = (e, bu) => {
   e.preventDefault();
   let input = document.getElementById(`num-contact-add-${bu}`);
   let btn = document.getElementById(`btn-add-contact-${bu}`);
   let num = '';
   let x = 3;
   for (let i = 0; i < input.value.length; i++) {
      let element = input.value[i];
      if (input.value[0] === '+') { // si trae +, lo mas probable que venga con "+56"
         if (input.value[1] === '5' && input.value[2] === '6') {
            if (input.value[x] === '0') {
               num += input.value[x];
            }
            if (Number(input.value[x])) {
               num += input.value[x];
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
   input.value = num;
   if (num.length > 0) {
      btn.classList.remove('disabledp');
   } else {
      btn.classList.add('disabledp');
   }
}

// solo numeros
export const onlyNumbers = (e, bu) => {
   reserTimeOut();
   let cods = [8, 13, 32, 37, 39, 46, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 96, 97, 98, 99, 100, 101, 102, 103, 104, 105];
   const key = window.e ? e.which : e.keyCode;
   if (!cods.includes(key)) {
      e.preventDefault();
   }
}

// prepara pregunta
const questionSweetAlert = (text, confirmButtonText) => {
   reserTimeOut();
   return Swal.mixin({
      customClass: {
         confirmButton: `btn btn-sm btn-danger mx-2 pfont-size-alert`,
         cancelButton: `btn btn-sm btn-primary mx-2 pfont-size-alert`
      },
      buttonsStyling: false
   }).fire({
      html: `<span class="text-danger">${text}</span>`,
      showCancelButton: true,
      cancelButtonText: 'Cancelar',
      confirmButtonText,
      position: 'center',
      allowEscapeKey: false,
      allowOutsideClick: false,
      reverseButtons: true,
   });
}

// prepara pregunta personalizada llamdas
const questionSweetAlertCall = (bu, num) => {
   reserTimeOut();
   let checkcall = document.getElementById(`check-call-up-${num}`);
   let html = /* html */ `
   <div class="container-fluid">
      <div class="row">
         <div class="col-12 mt-5 mb-3">
            <span class="text-danger">¿${checkcall.checked === true ? 'Activar' : 'Desactivar'} <span class="text-primary">llamadas</span> de emergencia para el contacto?</span>
         </div>
      </div>
   </div>
   `;
   Swal.mixin({
      customClass: {
         confirmButton: `btn btn-sm btn-danger mx-2 pfont-size-alert`,
         cancelButton: `btn btn-sm btn-primary mx-2 pfont-size-alert`
      },
      buttonsStyling: false
   }).fire({
      html,
      position: 'center',
      showCancelButton: true,
      confirmButtonText: `${checkcall.checked === true ? 'Activar' : 'Desactivar'}`,
      cancelButtonText: `Cancelar`,
      allowEscapeKey: false,
      allowOutsideClick: false,
      reverseButtons: true,
   }).then((result) => {
      if (result.isConfirmed) {
         swal.close();
         updateContactCall(bu, num);
      } else if (result.dismiss === Swal.DismissReason.cancel) {
         if (checkcall.checked === true) {
            checkcall.checked = false;
         } else {
            checkcall.checked = true;
         }
         Swal.close();
      }
   });
}

// prepara pregunta personalizada SMS
const questionSweetAlertSMS = (bu, num) => {
   reserTimeOut();
   let checksms = document.getElementById(`check-sms-up-${num}`);
   let html = /* html */ `
   <div class="container-fluid">
      <div class="row">
         <div class="col-12 mt-5 mb-3">
            <span class="text-danger">¿${checksms.checked === true ? 'Activar' : 'Desactivar'} <span class="text-primary">SMS</span> de emergencia para el contacto?</span>
         </div>
      </div>
   </div>
   `;
   Swal.mixin({
      customClass: {
         confirmButton: `btn btn-sm btn-danger mx-2 pfont-size-alert`,
         cancelButton: `btn btn-sm btn-primary mx-2 pfont-size-alert`
      },
      buttonsStyling: false
   }).fire({
      html,
      position: 'center',
      showCancelButton: true,
      confirmButtonText: `${checksms.checked === true ? 'Activar' : 'Desactivar'}`,
      cancelButtonText: `Cancelar`,
      allowEscapeKey: false,
      allowOutsideClick: false,
      reverseButtons: true,
   }).then((result) => {
      if (result.isConfirmed) {
         swal.close();
         updateContactSMS(bu, num);
      } else if (result.dismiss === Swal.DismissReason.cancel) {
         if (checksms.checked === true) {
            checksms.checked = false;
         } else {
            checksms.checked = true;
         }
         Swal.close();
      }
   });
}

// limpia errores de los inpust
const cleanInputError = (id) => {
   reserTimeOut();
   let div_err = document.getElementsByName(id)[0];
   div_err.classList.remove('text-success', 'text-danger');
   div_err.hidden = true;
   div_err.innerHTML = '';
}

const statusMSJ = (id, msj, status, time = true) => {
   reserTimeOut();
   id.classList.remove('text-success', 'text-danger');
   id.hidden = true;
   id.innerHTML = '';
   if (status === false) {
      id.classList.add('text-danger');
      id.innerHTML = /* html */ `<span class="pfont-size">${msj}</span>`;
      id.hidden = false;
      if (time === true) {
         setTimeout(() => {
            id.innerHTML = '';
         }, 6000);
      }
   }
}

// acciona boton de emergencia
export const openEmergency = async () => {
   try {
      setTimeout(() => {
         window.location.href = 'protegeme:*911?dialAction=autoCall';
         // window.location.href = 'protegeme:9227533637?dialAction=autoCall';
      }, 100);
   } catch (err) {
      console.error(`Error: ${err}`);
   }
}

// acciona boton de configuraciones
export const openSettings = async () => {
   try {
      setTimeout(() => {
         window.location.href = 'protegeme:?_cmd=app%3Asettings';
      }, 300);
   } catch (err) {
      console.error(`Error: ${err}`);
   }
}

// limpia historia
export const clearHistory = () => {
   try {
      setTimeout(() => {
         history.back();
      }, 100);
   } catch (err) {
      console.error(`Error: ${err}`);
   }
}

// spinner de carga
export const spinnerContacts = async (op) => {
   try {
      setTimeout(() => {
         reserTimeOut();
         let spinner = document.getElementsByClassName('spinner-load')[0];
         parseInt(op) === 1 ? spinner.hidden = false : spinner.hidden = true;
      }, 300);
   } catch (err) {
      console.error(`Error: ${err}`);
   }
}

// elimina contacto de emergencia
const deleteContact = async (bu, num) => {
   try {
      return new Promise((resolve, reject) => {
         setTimeout(() => {
            reserTimeOut();
            questionSweetAlert(`¿Eliminar contacto de emergencia?`, `Eliminar`).then((result) => {
               if (result.isConfirmed) {
                  const data = {
                     'bu': bu,
                     'num': num,
                     'device': 'APP'
                  }
                  fetch(`../site/json/json_deleteContact.php`, {
                     method: 'POST',
                     headers: {
                        'Content-Type': 'application/json'
                     },
                     body: JSON.stringify(data)
                  })
                     .then(response => response.json())
                     .then((response) => {
                        if (response.status === 'error') {
                           resSweetAlert(response.message);
                           reject(false);
                        } else {
                           dataContacts(response.busua_cod);
                           resolve(true);
                        }
                     })
                     .catch((err) => {
                        console.error(`Error: ${err}`);
                        reject(`Error: ${err}`);
                     });
               }
            });
         });
      }, 300);
   } catch (err) {
      console.error(`Error: ${err}`);
   }
}

// agrega/elimina contacto de emergencia llamdas
const updateContactCall = (bu, num) => {
   try {
      return new Promise((resolve, reject) => {
         setTimeout(() => {
            let checklisten = document.getElementById(`check-listen-up-${num}`);
            let checkcall = document.getElementById(`check-call-up-${num}`);
            reserTimeOut();
            const data = {
               'bu': bu,
               'num': num,
               'check': checkcall.checked,
               'device': 'APP'
            }
            fetch(`../site/json/json_updateContactCall.php`, {
               method: 'POST',
               headers: {
                  'Content-Type': 'application/json'
               },
               body: JSON.stringify(data)
            })
               .then(response => response.json())
               .then((response) => {
                  checklisten.check = false;
                  if (response.status === 'error') {
                     if (checkcall.checked === true) {
                        checkcall.checked = false;
                     } else {
                        checkcall.checked = true;
                     }
                     resSweetAlert(response.message);
                     reject(false);
                  } else {
                     if (checkcall.checked === true) {
                        checkcall.checked = true;
                        checklisten.classList.remove('bg-secondary');
                        checklisten.disabled = false;
                     } else {
                        checkcall.checked = false;
                        checklisten.classList.add('bg-secondary');
                        checklisten.disabled = true;
                        checklisten.checked = false;
                     }
                     resolve(true);
                  }
               })
               .catch((err) => {
                  if (checkcall.checked === true) {
                     checkcall.checked = false;
                  } else {
                     checkcall.checked = true;
                  }
                  console.error(`Error: ${err}`);
                  reject(`Error: ${err}`);
               });
         });
      }, 300);
   } catch (err) {
      console.error(`Error: ${err}`);
   }
}

// agrega/elimina contacto de emergencia SMS
const updateContactSMS = (bu, num) => {
   try {
      return new Promise((resolve, reject) => {
         setTimeout(() => {
            let checksms = document.getElementById(`check-sms-up-${num}`);
            reserTimeOut();
            const data = {
               'bu': bu,
               'num': num,
               'check': document.getElementById(`check-sms-up-${num}`).checked,
               'device': 'APP'
            }
            fetch(`../site/json/json_updateContactSMS.php`, {
               method: 'POST',
               headers: {
                  'Content-Type': 'application/json'
               },
               body: JSON.stringify(data)
            })
               .then(response => response.json())
               .then((response) => {
                  if (response.status === 'error') {
                     if (checksms.checked === true) {
                        checksms.checked = false;
                     } else {
                        checksms.checked = true;
                     }
                     resSweetAlert(response.message);
                     reject(false);
                  } else {
                     if (checksms.checked === true) {
                        checksms.checked = true;
                     } else {
                        checksms.checked = false;
                     }
                     resolve(true);
                  }
               })
               .catch((err) => {
                  if (checksms.checked === true) {
                     checksms.checked = false;
                  } else {
                     checksms.checked = true;
                  }
                  console.error(`Error: ${err}`);
                  reject(`Error: ${err}`);
               });
         });
      }, 300);
   } catch (err) {
      console.error(`Error: ${err}`);
   }
}

// actualiza escucha llamada
const updateListenCall = (bu, num) => {
   try {
      return new Promise((resolve, reject) => {
         setTimeout(() => {
            let checklisten = document.getElementById(`check-listen-up-${num}`);
            reserTimeOut();
            const data = {
               'bu': bu,
               'num': num,
               'check': document.getElementById(`check-listen-up-${num}`).checked,
               'device': 'APP'
            }
            fetch(`../site/json/json_updateListenCall.php`, {
               method: 'POST',
               headers: {
                  'Content-Type': 'application/json'
               },
               body: JSON.stringify(data)
            })
               .then(response => response.json())
               .then((response) => {
                  if (response.status === 'error') {
                     if (checklisten.checked === true) {
                        checklisten.checked = false;
                     } else {
                        checklisten.checked = true;
                     }
                     resSweetAlert(response.message);
                     reject(false);
                  } else {
                     if (checklisten.checked === true) {
                        checklisten.checked = true;
                     } else {
                        checklisten.checked = false;
                     }
                     resolve(true);
                  }
               })
               .catch((err) => {
                  if (checklisten.checked === true) {
                     checklisten.checked = false;
                  } else {
                     checklisten.checked = true;
                  }
                  console.error(`Error: ${err}`);
                  reject(`Error: ${err}`);
               });
         });
      }, 300);
   } catch (err) {
      console.error(`Error: ${err}`);
   }
}

// actualiza nombre contacto
const updateNameContact = (bu, num, val_i) => {
   try {
      return new Promise((resolve, reject) => {
         setTimeout(() => {
            reserTimeOut();
            localStorageSave(`name_contact_${bu}`, val_i);
            let name = document.getElementById(`name-contact-up-${num}`);
            const data = {
               'bu': bu,
               'num': num,
               'name': name.value.trim(),
               'device': 'APP'
            }
            fetch(`../site/json/json_updateNameContact.php`, {
               method: 'POST',
               headers: {
                  'Content-Type': 'application/json'
               },
               body: JSON.stringify(data)
            })
               .then(response => response.json())
               .then((response) => {
                  if (response.status === 'error') {
                     name.value = JSON.parse(localStorage.getItem(`name_contact_${bu}`));
                     resSweetAlert(response.message);
                     reject(false);
                  } else {
                     dataContacts(response.busua_cod);
                     localStorage.removeItem(`name_contact_${bu}`);
                     localStorage.setItem(`name_contact_${bu}`, JSON.stringify(name.value.trim()));
                     resolve(true);
                  }
               })
               .catch((err) => {
                  name.value = JSON.parse(localStorage.getItem(`name_contact_${bu}`));
                  console.error(`Error: ${err}`);
                  reject(`Error: ${err}`);
               });
         });
      }, 300);
   } catch (err) {
      spinnerContacts(2);
      console.error(`Error: ${err}`);
   }
}

// data contactos del usuario Llamadas
export const dataContacts = async (bu) => {
   try {
      return new Promise((resolve, reject) => {
         setTimeout(() => {
            reserTimeOut();
            let table = document.getElementById('data-table-contacts-' + bu);
            let tbody = table.querySelector('#tbody-contacts-' + bu);
            tbody.innerHTML = '';
            fetch(`../site/json/json_getContacts.php?bu=${bu}`, {
               method: 'GET',
               headers: {
                  'Content-Type': 'application/json'
               }
            })
               .then(response => response.json())
               .then((response) => {
                  if (response.status === 'error') {
                     tbody.innerHTML += /* html */ `
                     <tr id="err-contact-${bu}">
                        <td colspan="6" class="text-center pfont-size">
                           ${response.message}
                        </td>
                     </tr>
                     `;
                  } else {
                     response.contacts.forEach((contact) => {
                        tbody.innerHTML += /* html */ `
                        <tr id="contact-${contact.numero}">
                           <td class="text-start pfont-size p-0">
                              <input type="text" class="form-control pfont-size bg-warning-subtle" id="name-contact-up-${contact.numero}" name="name-contact-up-${contact.numero}" value="${(new String(contact.nombre).toString()) === 'null' ? '' : contact.nombre}" title="Nombre del contacto">
                           </td>
                           <td class="text-start pfont-size p-1 pb-0 text-start">${contact.numero}</td>
                           <td class="text-end p-1 pb-0">
                              <div class="form-check d-flex justify-content-center">
                                 <input class="form-check-input" type="checkbox" id="check-call-up-${contact.numero}" ${contact.statuscall === false ? '' : contact.esta_cod_call == 1 ? 'checked' : ''}>
                              </div>
                           </td>
                           <td class="p-1 pb-0">
                              <div class="form-check d-flex justify-content-center">
                                 <input class="form-check-input" type="checkbox" id="check-sms-up-${contact.numero}" ${contact.statusSMS === false ? '' : contact.esta_cod_sms == 1 ? 'checked' : ''}>
                              </div>
                           </td>
                           <td class="p-1 pb-0">
                              <div class="form-check d-flex justify-content-center">
                                 <input class="form-check-input ${contact.statuscall === false ? '' : contact.esta_cod_call == 1 ? '' : 'bg-secondary' }" type="checkbox" id="check-listen-up-${contact.numero}" ${contact.statuscall === false ? '' : contact.esta_cod_call == 1 ? contact.listen_call == 1 ? 'checked' : '' : 'disabled' }>
                              </div>
                           </td>
                           <td class="text-center text-danger p-1 pb-0">
                              <i class="fa-solid fa-trash" id="delete-contact-${contact.numero}"></i>
                           </td>
                        </tr>
                        `;

                     });
                     response.contacts.forEach((contact) => {
                        if (contact.statuscall === true) {
                           document.getElementById(`name-contact-up-${contact.numero}`).onchange = () => updateNameContact(response.busua_cod, contact.numero, (new String(contact.nombre).toString()) === 'null' ? '' : contact.nombre);
                        }
                        document.getElementById(`check-call-up-${contact.numero}`).onchange = () => updateContactCall(response.busua_cod, contact.numero);
                        document.getElementById(`check-sms-up-${contact.numero}`).onchange = () => updateContactSMS(response.busua_cod, contact.numero);
                        document.getElementById(`check-listen-up-${contact.numero}`).onchange = () => updateListenCall(response.busua_cod, contact.numero);
                        document.getElementById(`delete-contact-${contact.numero}`).onclick = () => deleteContact(response.busua_cod, contact.numero);
                     });
                  }
                  resolve(bu);
               })
               .catch((err) => {
                  console.error(`Error: ${err}`);
                  spinnerContacts(2);
                  reject(`Error: ${err}`);
               });
         });
      }, 300);
   } catch (err) {
      spinnerContacts(2);
      console.error(`Error: ${err}`);
   }
}

// agrega un nuevo contacto de emergencia llamada
export const createContact = (bu) => {
   try {
      return new Promise((resolve, reject) => {
         setTimeout(() => {
            reserTimeOut();
            let name = document.getElementById(`name-contact-add-${bu}`).value.trim();
            let num = document.getElementById(`num-contact-add-${bu}`).value.trim().replaceAll(' ', '');
            let checkcall = document.getElementById(`check-call-add-${bu}`);
            let checksms = document.getElementById(`check-sms-add-${bu}`);
            let error = false;
            cleanInputError(`err-name-contact-add-${bu}`);
            cleanInputError(`err-num-contact-add-${bu}`);
            cleanInputError(`err-check-add-${bu}`);
            // validaciones
            // NUMERO
            if (!formatNum.test(num)) {
               error = true;
               statusMSJ(document.getElementsByName(`err-num-contact-add-${bu}`)[0], 'N&uacute;mero: Ingresar entre 9 y 10 n&uacute;meros.', false, true);
            }
            if (error === true) {
               reject(`Error Syntax`);
               return false;
            }
            questionSweetAlert(`¿Ingresar nuevo contacto de emergencia?`, `Ingresar`).then((result) => {
               if (result.isConfirmed) {
                  const data = {
                     'bu': bu,
                     'name': name,
                     'num': num,
                     'checkcall': checkcall.checked,
                     'checksms': checksms.checked,
                     'device': 'APP'
                  }
                  fetch(`../site/json/json_createContact.php`, {
                     method: 'POST',
                     headers: {
                        'Content-Type': 'application/json'
                     },
                     body: JSON.stringify(data)
                  })
                     .then(response => response.json())
                     .then((response) => {
                        if (response.status === 'error') {
                           resSweetAlert(response.message);
                           reject(false);
                        } else {
                           dataContacts(response.busua_cod);
                           document.getElementById(`name-contact-add-${bu}`).value = '';
                           document.getElementById(`num-contact-add-${bu}`).value = '';
                           document.getElementById(`btn-add-contact-${bu}`).classList.add('disabledp');
                           document.getElementById(`check-call-add-${bu}`).checked = true;
                           document.getElementById(`check-sms-add-${bu}`).checked = true;
                           resolve(true);
                        }
                     })
                     .catch((err) => {
                        console.error(`Error: ${err}`);
                        reject(`Error: ${err}`);
                     });
               }
            });
         }, 300)
      });
   } catch (err) {
      console.error(`Error: ${err}`);
      reject(`Error: ${err}`);
   }
}

export const createContactMovil = (bu, num, name) => {
   try {
      return new Promise((resolve, reject) => {
         setTimeout(() => {
            reserTimeOut();
            let checkcall = document.getElementById(`check-call-add-${bu}`);
            let checksms = document.getElementById(`check-sms-add-${bu}`);
            let error = false;
            cleanInputError(`err-name-contact-add-${bu}`);
            cleanInputError(`err-num-contact-add-${bu}`);
            cleanInputError(`err-check-add-${bu}`);
            // validaciones
            if (error === true) {
               reject(`Error Syntax`);
               return false;
            }
            const data = {
               'bu': bu,
               'name': name.trim(),
               'num': num.trim().replaceAll(' ', ''),
               'checkcall': checkcall.checked,
               'checksms': checksms.checked,
               'device': 'APP'
            }
            fetch(`../site/json/json_createContact.php`, {
               method: 'POST',
               headers: {
                  'Content-Type': 'application/json'
               },
               body: JSON.stringify(data)
            })
               .then(response => response.json())
               .then((response) => {
                  if (response.status === 'error') {
                     resSweetAlert(response.message);
                     reject(false);
                  } else {
                     dataContacts(response.busua_cod);
                     document.getElementById(`name-contact-add-${bu}`).value = '';
                     document.getElementById(`num-contact-add-${bu}`).value = '';
                     document.getElementById(`btn-add-contact-${bu}`).classList.add('disabledp');
                     document.getElementById(`check-call-add-${bu}`).checked = true;
                     document.getElementById(`check-sms-add-${bu}`).checked = true;
                     resolve(true);
                  }
               })
               .catch((err) => {
                  console.error(`Error: ${err}`);
                  reject(`Error: ${err}`);
               });
         }, 300)
      });
   } catch (err) {
      console.error(`Error: ${err}`);
      reject(`Error: ${err}`);
   }
}

// manda coordenadas
export const getLocationGPS = async (bu, user, pasw, lat, lon, platform, version, model, appbuild) => {
   try {
      return new Promise((resolve, reject) => {
         setTimeout(() => {
            const data = {
               'bu': bu,
               'user': user,
               'pasw': pasw,
               'lat': lat,
               'lon': lon,
               'platform': platform,
               'version': version,
               'model': model,
               'appbuild': appbuild
            }
            fetch(`../site/json/json_getLocationGPS.php`, {
               method: 'POST',
               headers: {
                  'Content-Type': 'application/json'
               },
               body: JSON.stringify(data)
            })
               .then(response => response.json())
               .then((response) => {
                  resolve(response);
               })
               .catch((err) => {
                  console.error(`Error: ${err}`);
                  reject(`Error: ${err}`);
               });
         }, 300);
      });
   } catch (err) {
      console.error(`Error: ${err}`);
      reject(`Error: ${err}`);
   }
}