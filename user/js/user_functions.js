'use strict';

let url = `${document.location.origin}`;
let url_img = `${document.location.origin}/img`;
let error_system = `Error: Por favor intente m&aacute;s tarde`;
let formatNum = /^[0-9]{3,15}$/;
let formatEmail = /^[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*@[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,5}/;

// guarda un dato en localStorage
const localStorageSave = async (cod, val) => {
   if (window.localStorage.getItem(cod) !== undefined && window.localStorage.getItem(cod)) {
      JSON.parse(localStorage.getItem(cod));
   } else {
      localStorage.removeItem(cod);
      localStorage.setItem(cod, JSON.stringify(val));
   }
}

// mensaje de error
const showError = (text) => {
   Swal.fire({
      showConfirmButton: false,
      showCloseButton: false,
      focusConfirm: false,
      icon: 'error',
      html: `<span class="text-danger">${text}</span>`,
      timer: 10000,
      width: 300,
      height: 300,
      position: 'bottom-end',
   });
}

// funcion para procesar un spinner en los botones
const spinnerOpen = async (id) => {
   let spinner = document.getElementById(id);
   spinner.disabled = true;
   spinner.querySelector('div').hidden = false;
   spinner.querySelectorAll('span')[1].innerHTML = 'Procesando';
}

// funcion para finalizar un spinner en los botones
const spinnerClose = async (id, text) => {
   let spinner = document.getElementById(id);
   spinner.disabled = false;
   spinner.querySelector('div').hidden = true;
   spinner.querySelectorAll('span')[1].innerHTML = text;
}

// abre spinner
const spiner_menu_open = async (id) => {
   setTimeout(() => {
      document.getElementById(id).hidden = false;
   }, 100);

}

//cierra spinner
const spiner_menu_close = async (id) => {
   setTimeout(() => {
      document.getElementById(id).hidden = true;
   }, 100);
}

// maneja del div de error
const statusMSJ = (id, msj, status, time = true) => {
   id.classList.remove('text-success', 'text-danger');
   id.hidden = true;
   id.innerHTML = '';
   if (status === false) {
      id.classList.add('text-danger');
      id.innerHTML = /* html */ `${msj}`;
      id.hidden = false;
      if (time === true) {
         setTimeout(() => {
            id.innerHTML = '';
         }, 6000);
      }
   }
}

// activa un tooltip
const tooltipSystem = (id) => {
   const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="' + id + '"]');
   const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
}

// limpia errores de los inpust
const cleanInputError = (id) => {
   let div_err = document.getElementsByName(id)[0];
   div_err.classList.remove('text-success', 'text-danger');
   div_err.hidden = true;
   div_err.innerHTML = '';
}

// mensaje de tipo tost
const toast = Swal.mixin({
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

// prepara pregunta
const questionSweetAlert = (text, width = 'auto') => {
   return Swal.mixin({
      customClass: {
         confirmButton: `btn btn-sm btn-danger fs-6 mx-1 px-3`,
         cancelButton: `btn btn-sm btn-primary fs-6 mx-1 px-3`
      },
      buttonsStyling: false
   }).fire({
      html: text,
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si',
      cancelButtonText: 'Cancelar',
      position: 'bottom-end',
      width,
      allowEscapeKey: false,
      allowOutsideClick: false,
   });
}

// solo numeros
const onlySpace = (e) => {
   let cods = [32];
   const key = window.e ? e.which : e.keyCode;
   if (cods.includes(key)) {
      e.preventDefault();
   }
}

// solo numeros
const onlyNumbers = (e, bu) => {

   let cods = [8, 13, 17, 86, 32, 37, 39, 46, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 96, 97, 98, 99, 100, 101, 102, 103, 104, 105];
   const key = window.e ? e.which : e.keyCode;
   if (!cods.includes(key)) {
      e.preventDefault();
   }
}

// ayuda a saber el largo de caractres de un campo
const largeInput = (e, bu) => {

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

// arma menu seleccionado
const data_menu = async (menu, busua_cod) => {

   try {

      return new Promise((resolve, reject) => {

         setTimeout(() => {

            let data = document.getElementById('data-result');
            let title = document.getElementById('title-adm-menu');
            let links = document.getElementsByName('links');         // links

            links.forEach(link => {
               link.classList.remove('main-menu__link_select');      // borra la clase que tiene seleccionado el menu
            })

            data.innerHTML = '';

            // detalle del menu
            switch (menu) {
               case 1:
                  title.innerHTML = 'Contactos de emergencia';
                  data.innerHTML += /* html */ `
                  <div class="bg-light border rounded p-3 mb-3">
                     <div id="data-contact-emergency" class="row mb-3"></div>
                  </div>`;
                  document.querySelector('#links-1 a').classList.add('main-menu__link_select');
                  resolve(true);
                  break;
               case 2:
                  title.innerHTML = 'Reportes';
                  data.innerHTML += /* html */ `
                  <div class="bg-light border rounded p-3 mb-3">
                     <div id="data-report" class="row mb-3"></div>
                  </div>`;
                  document.querySelector('#links-2 a').classList.add('main-menu__link_select');
                  resolve(true);
                  break;
               case 3:
                  title.innerHTML = 'Datos personales';
                  data.innerHTML += /* html */ `
                  <div class="bg-light border rounded p-3 mb-3"></div>`;
                  resolve(true);
                  break;
               case 4:
                  title.innerHTML = 'Cambiar contrase&ntilde;a';
                  data.innerHTML += /* html */ `
                  <div class="bg-light border rounded p-3 mb-3">
                     <form id="form-pa-${busua_cod}" class="row" autocomplete="off">
                        <div class="col-12">
                           <h4>Ingrese su contraseña nueva</h4>
                        </div>
                        <div class="row g-3 align-items-center">
                           <div class="col-md-2">
                              <label for="password-current" class="col-form-label">Contrase&ntilde;a actual:</label>
                           </div>
                           <div class="col-md-4">
                              <input type="password" id="password-current" class="form-control" aria-describedby="passwordCurrentHelpInline" minlength="1" maxlength="255" aria-describedby="password-current-text" onkeydown="return onlySpace(event)">
                           </div>
                           <div class="col-auto">
                              <span id="passwordCurrentHelpInline" class="form-text">
                                 Ingrese su contrase&ntilde;a para realizar cambios
                              </span>
                           </div>
                        </div>
                        <div class="row g-3 align-items-center">
                           <div class="col-md-2">
                              <label for="password" class="col-form-label">Contrase&ntilde;a:</label>
                           </div>
                           <div class="col-md-4">
                              <input type="password" id="password" class="form-control" aria-describedby="passwordHelpInline" minlength="1" maxlength="255" aria-describedby="password-text" onkeydown="return onlySpace(event)">
                           </div>
                           <div class="col-auto">
                              <span id="passwordHelpInline" class="form-text">
                                 Ingrese su nueva contrase&ntilde;a
                              </span>
                           </div>
                        </div>
                        <div class="row g-3 align-items-center">
                           <div class="col-md-2">
                              <label for="password-v" class="col-form-label">Verifique contrase&ntilde;a:</label>
                           </div>
                           <div class="col-md-4">
                              <input type="password" id="password-v" class="form-control" aria-describedby="passwordVHelpInline" minlength="1" maxlength="255" aria-describedby="password-v-text" onkeydown="return onlySpace(event)">
                           </div>
                           <div class="col-auto">
                              <span id="passwordVHelpInline" class="form-text">
                                 Ingrese nuevamente su nueva contrase&ntilde;a
                              </span>
                           </div>
                        </div>
                        <div class="row g-3 align-items-center">
                           <div class="col-12">
                              <span class="text-primary">Importante: Deber&eacute;s restrablecer tu aplicaci&oacute;n una vez cambiada la contrase&ntilde;a</span>
                           </div>
                           <div class="col-12 d-flex justify-content-between">
                              <div>
                                 <div id="password-help" name="err-password" class="form-text fs-5 fw-light" hidden></div>
                              </div>
                              <input type="submit" class="btn buttons colors-font" id="btn-update-pass" name="btn-update-pass" value="Modificar">
                           </div>
                        </div>
                     </form>
                     <form id="form-redirect-${busua_cod}" method="POST" action="${url}/user/account/index.php">
                        <input type="hidden" id="username" name="username">
                        <input type="hidden" id="cloud_password" name="cloud_password">
                     </form>
                  </div>`;

                  document.getElementById(`form-pa-${busua_cod}`).addEventListener('submit', e => {
                     e.preventDefault();

                     updatePasswordUser(busua_cod);

                  });
                  resolve(true);
                  break;
               case 5:
                  title.innerHTML = 'Cambiar email';
                  data.innerHTML += /* html */ `
                  <div class="bg-light border rounded p-3 mb-3">
                     <form id="form-email-up-${busua_cod}" class="row" autocomplete="off">
                        <div class="col-12">
                           <h4>Ingrese su nuevo correo</h4>
                        </div>
                        <div class="row g-3 align-items-center">
                           <div class="col-md-2">
                              <label for="email" class="col-form-label">Nueva casilla:</label>
                           </div>
                           <div class="col-md-4">
                              <input type="email" id="email" class="form-control" aria-describedby="emailHelpInline" minlength="1" maxlength="255" aria-describedby="password-current-text" placeholder="Ingresa tu email" onkeydown="return onlySpace(event)">
                           </div>
                           <div class="col-auto">
                              <span id="emailHelpInline" class="form-text">
                                 Ingrese la direcci&oacute;n de correo nueva que desea registrar
                              </span>
                           </div>
                        </div>
                        <div class="row g-3 align-items-center">
                           <div class="col-md-2">
                              <label for="password" class="col-form-label">Contrase&ntilde;a:</label>
                           </div>
                           <div class="col-md-4">
                              <input type="password" id="password" class="form-control" aria-describedby="passwordHelpInline" minlength="1" maxlength="255" aria-describedby="password-text" onkeydown="return onlySpace(event)">
                           </div>
                           <div class="col-auto">
                              <span id="passwordHelpInline" class="form-text">
                                 Ingrese su contrase&ntilde;a para verificación
                              </span>
                           </div>
                        </div>
                        <div class="row g-3 align-items-center">
                           <div class="col-12 d-flex justify-content-between">
                              <div>
                                 <div id="email-help" name="err-email" class="form-text fs-5 fw-light" hidden></div>
                              </div>
                              <input type="submit" class="btn buttons colors-font" id="btn-update-email" name="btn-update-email" value="Modificar">
                           </div>
                        </div>
                     </form>
                  </div>`;
                  document.getElementById(`form-email-up-${busua_cod}`).addEventListener('submit', e => {
                     e.preventDefault();

                     updateEmailUser(busua_cod);

                  });
                  resolve(true);
                  break;
               default:
                  data.innerHTML += /* html */ `
                  <section id="section-error-menu">
                     <div class="container-fluid">
                        <div class="row">
                           <div class="col-12 d-flex border-bottom">
                              <span class="title-menu-user">Error</span>
                           </div>
                        </div>
                     </div>
                  </section>`;
                  reject(false);
                  break;
            }

         }, 300);

      });

   } catch (error) {

      console.error(`Error: ${error}`);
      showError(error_system);

   }

}

// elimina contacto de emergencia
const deleteContact = async (bu, num) => {

   try {

      return new Promise((resolve, reject) => {

         setTimeout(() => {

            questionSweetAlert(`¿Eliminar <span class="text-primary">contacto</span> de emergencia?`, `Eliminar`).then((result) => {

               if (result.isConfirmed) {

                  const data = {
                     'bu': bu,
                     'num': num,
                     'device': 'WEB'
                  }

                  fetch(`../../site/json/json_deleteContact.php`, {
                     method: 'POST',
                     headers: {
                        'Content-Type': 'application/json'
                     },
                     body: JSON.stringify(data)
                  })
                     .then(response => response.json())
                     .then((response) => {

                        if (response.status === 'error') {

                           toast.fire({
                              icon: 'error',
                              title: response.message
                           });
                           reject(false);

                        } else {

                           document.getElementById(`contact-${num}`).remove();

                           let tbody = document.querySelector(`#tbody-contacts-${bu}`);

                           if (tbody.querySelectorAll('tr').length == 0) {
                              tbody.innerHTML = '';
                              tbody.innerHTML += /* html */ `
                              <tr id="err-contact-${bu}">
                                 <td class="text-danger text-center" colspan="6">Usuario sin contactos de emergencia</td>
                              </tr>
                              `;
                           }

                           localStorage.removeItem('name_contact_' + num);

                           toast.fire({
                              icon: 'success',
                              title: 'Contacto de emergencia <span class="text-primary">Eliminado</span>'
                           });

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

// agrega/elimina contacto de emergencia SMS
const updateContactSMS = (bu, num) => {

   try {

      return new Promise((resolve, reject) => {

         setTimeout(() => {

            let check = document.getElementById(`check-sms-up-${num}`);

            const data = {
               'bu': bu,
               'num': num,
               'check': check.checked,
               'device': 'WEB'
            }

            fetch(`../../site/json/json_updateContactSMS.php`, {
               method: 'POST',
               headers: {
                  'Content-Type': 'application/json'
               },
               body: JSON.stringify(data)
            })
               .then(response => response.json())
               .then((response) => {

                  if (response.status === 'error') {

                     if (check.checked === true) {
                        check.checked = false;
                     } else {
                        check.checked = true;
                     }
                     toast.fire({
                        icon: 'error',
                        title: message
                     });
                     reject(false);

                  } else {

                     if (check.checked === true) {
                        check.checked = true;
                     } else {
                        check.checked = false;
                     }
                     toast.fire({
                        icon: 'success',
                        title: `SMS de emergencia <span class="text-primary">${(check.checked === true ? 'Activado' : 'Desactivado')}</span>`
                     });

                     resolve(true);

                  }
               })
               .catch((err) => {

                  console.error(`Error: ${err}`);
                  reject(`Error: ${err}`);

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

            let checkcall = document.getElementById(`check-call-up-${num}`);
            let checklisten = document.getElementById(`check-listen-up-${num}`);

            const data = {
               'bu': bu,
               'num': num,
               'check': checkcall.checked,
               'device': 'WEB'
            }

            fetch(`../../site/json/json_updateContactCall.php`, {
               method: 'POST',
               headers: {
                  'Content-Type': 'application/json'
               },
               body: JSON.stringify(data)
            })
               .then(response => response.json())
               .then((response) => {

                  if (response.status === 'error') {

                     if (checkcall.checked === true) {
                        checkcall.checked = false;
                        checklisten.disabled = false;
                     } else {
                        checkcall.checked = true;
                        checklisten.disabled = true;
                     }
                     toast.fire({
                        icon: 'error',
                        title: message
                     });
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
                     toast.fire({
                        icon: 'success',
                        title: `Llamadas de emergencia <span class="text-primary">${(checkcall.checked === true ? 'Activado' : 'Desactivado')}</span>`
                     });
                     resolve(true);

                  }

               })
               .catch((error) => {

                  if (checkcall.checked === true) {
                     checkcall.checked = false;
                  } else {
                     checkcall.checked = true;
                  }
                  reject(`Error: ${error}`);

               });

         });

      }, 300);

   } catch (error) {

      console.error(`Error: ${error}`);

   }

}

// actualiza nombre contacto
const updateNameContact = (bu, num, val_i) => {

   try {

      return new Promise((resolve, reject) => {

         setTimeout(() => {

            localStorageSave(`name_contact_${num}`, val_i);

            let name = document.getElementById(`name-contact-up-${num}`);

            const data = {
               'bu': bu,
               'num': num,
               'name': name.value.trim(),
               'device': 'WEB'
            }

            fetch(`../../site/json/json_updateNameContact.php`, {
               method: 'POST',
               headers: {
                  'Content-Type': 'application/json'
               },
               body: JSON.stringify(data)
            })
               .then(response => response.json())
               .then((response) => {

                  if (response.status === 'error') {

                     name.value = JSON.parse(localStorage.getItem(`name_contact_${num}`));
                     toast.fire({
                        icon: 'error',
                        title: 'Nombre contacto <span class="text-danger">NO Actualizado</span>'
                     });
                     reject(false);

                  } else {

                     toast.fire({
                        icon: 'success',
                        title: 'Nombre contacto <span class="text-primary">Actualizado</span>'
                     });
                     localStorage.removeItem(`name_contact_${num}`);
                     localStorage.setItem(`name_contact_${num}`, JSON.stringify(name.value.trim()));
                     resolve(true);

                  }

               })
               .catch((err) => {

                  name.value = JSON.parse(localStorage.getItem(`name_contact_${num}`));
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

            const data = {
               'bu': bu,
               'num': num,
               'check': document.getElementById(`check-listen-up-${num}`).checked,
               'device': 'WEB'
            }

            fetch(`../../site/json/json_updateListenCall.php`, {
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
                     toast.fire({
                        icon: 'success',
                        title: `Escuchar <span class="text-primary">${checklisten.checked === true ? 'Activado' : 'Desactivado'}</span>`
                     });
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

// agrega un nuevo contacto de emergencia llamada
const createContact = (bu) => {

   try {

      return new Promise((resolve, reject) => {

         setTimeout(() => {

            let name = document.getElementById(`name-contact-add-${bu}`).value.trim();
            let num = document.getElementById(`num-contact-add-${bu}`).value.trim();
            let checkcall = document.getElementById(`check-call-add-${bu}`);
            let checksms = document.getElementById(`check-sms-add-${bu}`);
            let error = false;

            cleanInputError(`err-name-contact-add-${bu}`);
            cleanInputError(`err-num-contact-add-${bu}`);
            cleanInputError(`err-check-add-${bu}`);

            // NUMERO
            if (!formatNum.test(num)) {
               error = true;
               statusMSJ(document.getElementsByName(`err-num-contact-add-${bu}`)[0], 'N&uacute;mero: Ingresar entre 9 y 10 n&uacute;meros.', false, true);
            }

            if (error === true) {
               reject(`Error Syntax`);
               return false;
            }

            questionSweetAlert(`¿Ingresar nuevo <span class="text-primary">Contacto</span> de emergencia?`, `Ingresar`).then((result) => {

               if (result.isConfirmed) {

                  const data = {
                     'bu': bu,
                     'name': name,
                     'num': num,
                     'checkcall': checkcall.checked,
                     'checksms': checksms.checked,
                     'device': 'WEB'
                  }

                  fetch(`../../site/json/json_createContact.php`, {
                     method: 'POST',
                     headers: {
                        'Content-Type': 'application/json'
                     },
                     body: JSON.stringify(data)
                  })
                     .then(response => response.json())
                     .then((response) => {

                        if (response.status === 'error') {

                           toast.fire({
                              icon: 'error',
                              title: response.message
                           });
                           reject(false);

                        } else {

                           num = '56' + num;

                           let tbody = document.querySelector(`#tbody-contacts-${bu}`);

                           if (tbody.querySelectorAll('tr').length === 1) {
                              if (tbody.querySelectorAll('tr')[0].id.includes(`err-contact-${bu}`)) {
                                 tbody.querySelectorAll('tr')[0].remove();
                              }
                           }

                           tbody.innerHTML += `
                           <tr id="contact-${num}">
                              <td class="text-start pfont-size p-0">
                                 <input type="text" class="form-control pfont-size bg-warning-subtle link-pointer" id="name-contact-up-${num}" name="name-contact-up-${num}" value="${(new String(name).toString()) === 'null' ? '' : name}" title="Nombre del contacto" onchange="updateNameContact(${bu}, ${num}, '${(new String(name).toString())}')">
                              </td>
                              <td class="text-start pfont-size p-1 pb-0 text-start">${num}</td>
                              <td class="text-end pfont-size p-1 pb-0">
                                 <div class="form-check d-flex justify-content-center">
                                    <input class="form-check-input link-pointer" type="checkbox" id="check-call-up-${num}" ${checkcall.checked === false ? '' : 'checked'} onchange="updateContactCall(${bu}, ${num})">
                                 </div>
                              </td>
                              <td class="pfont-size p-1 pb-0">
                                 <div class="form-check d-flex justify-content-center">
                                    <input class="form-check-input link-pointer" type="checkbox" id="check-sms-up-${num}" ${checksms.checked === false ? '' : 'checked'} onchange="updateContactSMS(${bu}, ${num})">
                                 </div>
                              </td>
                              <td class="p-1 pb-0">
                                 <div class="form-check d-flex justify-content-center">
                                    <input class="form-check-input ${checkcall.checked === false ? 'bg-secondary' : ''}" type="checkbox" id="check-listen-up-${num}" ${checkcall.checked === false ? 'disabled' : ''} onchange="updateListenCall(${bu}, ${num})">
                                 </div>
                              </td>
                              <td class="text-center text-danger p-1 pb-0">
                                 <i class="fa-solid fa-trash link-pointer" id="delete-contact-${num}" onclick="deleteContact(${bu}, ${num})"></i>
                              </td>
                           </tr>
                           `;

                           toast.fire({
                              icon: 'success',
                              title: 'Contacto de emergencia <span class="text-primary">Ingresado</span>'
                           });

                           document.getElementById(`name-contact-add-${bu}`).value = '';
                           document.getElementById(`num-contact-add-${bu}`).value = '';
                           document.getElementById(`btn-add-contact-${bu}`).classList.add('disabledp');
                           document.getElementById(`check-call-add-${bu}`).checked = true;
                           document.getElementById(`check-sms-add-${bu}`).checked = true;
                           localStorage.removeItem('name_contact_' + num);
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

// trae formulairo eh informacion de los contactos de emergencia de los usuarios BP
const formContactEmergency = async (busua_cod) => {

   try {

      return new Promise((resolve, reject) => {

         setTimeout(() => {

            let contact = document.getElementById('data-contact-emergency');

            // limpiamos
            contact.innerHTML = '';

            fetch(`../json/json_getContactsEmergencyUser.php?busua_cod=${busua_cod}`, {
               method: 'GET',
               headers: {
                  'Content-Type': 'application/json'
               }
            })
               .then(response => response.json())
               .then((response) => {

                  contact.innerHTML += /* html */ `
                  <form id="form-emergency-${busua_cod}" class="row" autocomplete="off">
                     <div class="col-12 px-1">
                        <h4>Ingreso</h4>
                     </div>
                     <div class="col-12 mb-3 px-1" id="div-name-contact-${busua_cod}">
                        <input type="text" class="form-control" id="name-contact-add-${busua_cod}" name="name-contact-add-${busua_cod}" placeholder="Ingrese un nombre" title="Nombre para el Contacto">
                        <div id="name-contact-add-${busua_cod}-help" name="err-name-contact-add-${busua_cod}" class="form-text fw-light" hidden></div>
                     </div>
                     <div class="col-12 mb-3 px-1">
                        <div class="input-group mb-3">
                           <span class="input-group-text" id="num-contact-add-addon-${busua_cod}">56</span>
                           <input type="text" class="form-control" id="num-contact-add-${busua_cod}" name="num-contact-add-${busua_cod}" placeholder="(*) Ingrese un n&uacute;mero - Ej: 936147801" title="N&uacute;mero para el Contacto" onkeydown="onlyNumbers(event, ${busua_cod})" onkeyup="largeInput(event, ${busua_cod})">
                        </div>
                        <div id="num-contact-add-${busua_cod}-help" name="err-num-contact-add-${busua_cod}" class="form-text fw-light" hidden></div>
                     </div>
                     <div class="col-12 mb-3 px-1 d-flex justify-content-start">
                        <div class="form-check mx-3">
                           <input class="form-check-input link-pointer" type="checkbox" id="check-call-add-${busua_cod}" checked>
                           <label class="form-check-label link-pointer" for="check-call-add-${busua_cod}">
                              Llamadas
                           </label>
                        </div>
                        <div class="form-check mx-3">
                           <input class="form-check-input link-pointer" type="checkbox" id="check-sms-add-${busua_cod}" checked>
                           <label class="form-check-label link-pointer" for="check-sms-add-${busua_cod}">
                              SMS
                           </label>
                        </div>
                     </div>
                     <div class="col-12 mb-3 px-1 d-flex justify-content-start">
                        <div id="check-add-${busua_cod}-help" name="err-check-add-${busua_cod}" class="form-text fw-light w-100" hidden></div>
                     </div>
                     <div class="col-12 d-flex justify-content-center mb-3 px-1">
                        <button type="submit" id="btn-add-contact-${busua_cod}" class="btn buttons colors-font w-100 disabledp">Ingresar</button>
                     </div>
                  </form>
                  <hr>
                  <div class="row mb-3">
                     <div class="col-12">
                        <table id="data-table-contacts-${busua_cod}" class="table table-bordered">
                           <thead>
                              <tr>
                                 <th class="background-all-1 color-font fw-light pfont-size p-1">Nombre</th>
                                 <th class="background-all-1 color-font fw-light pfont-size p-1 text-end">N&uacute;mero</th>
                                 <th class="background-all-1 color-font fw-light pfont-size p-1 text-center"><i class="fa-solid fa-phone"></i></th>
                                 <th class="background-all-1 color-font fw-light pfont-size p-1 text-center"><i class="fa-solid fa-comment-sms"></i></th>
                                 <th class="background-all-1 color-font fw-light pfont-size p-1 text-center"><i class="fa-solid fa-ear-listen"></i></i></th>
                                 <th class="background-all-1 color-font fw-light pfont-size p-1 text-center">
                                    Eliminar
                                 </th>
                              </tr>
                           </thead>
                           <tbody id="tbody-contacts-${busua_cod}"></tbody>
                        </table>
                     </div>
                  </div>
                  `;

                  let body = document.getElementById(`tbody-contacts-${busua_cod}`);

                  if (response.status === 'error') {

                     body.innerHTML = /* html */ `
                     <tr id="err-contact-${busua_cod}">
                        <td class="text-danger text-center" colspan="6">${response.message}</td>
                     </tr>
                     `;

                  } else {

                     const dataContacts = () => {
                        let contacts = '';
                        response.contacts.forEach(contact => {
                           contacts += `
                           <tr id="contact-${contact.numero}">
                              <td class="text-start p-0">
                                 <input type="text" class="form-control bg-warning-subtle link-pointer" id="name-contact-up-${contact.numero}" name="name-contact-up-${contact.numero}" value="${(new String(contact.nombre).toString()) === 'null' ? '' : contact.nombre}" title="Nombre del contacto" onchange="updateNameContact(${response.busua_cod}, ${contact.numero}, '${(new String(contact.nombre).toString())}')">
                              </td>
                              <td class="text-start pb-0 text-start">${contact.numero}</td>
                              <td class="pb-0">
                                 <div class="form-check d-flex justify-content-center">
                                    <input class="form-check-input link-pointer" type="checkbox" id="check-call-up-${contact.numero}" ${contact.statuscall === false ? '' : contact.esta_cod_call == 1 ? 'checked' : ''} onchange="updateContactCall(${busua_cod}, ${contact.numero})">
                                 </div>
                              </td>
                              <td class="pb-0">
                                 <div class="form-check d-flex justify-content-center">
                                    <input class="form-check-input link-pointer" type="checkbox" id="check-sms-up-${contact.numero}" ${contact.statusSMS === false ? '' : contact.esta_cod_sms == 1 ? 'checked' : ''}  onchange="updateContactSMS(${busua_cod}, ${contact.numero})">
                                 </div>
                              </td>
                              <td class="pb-0">
                                 <div class="form-check d-flex justify-content-center">
                                    <input class="form-check-input ${contact.statuscall === false ? '' : contact.esta_cod_call == 1 ? '' : 'bg-secondary'}" type="checkbox" id="check-listen-up-${contact.numero}" ${contact.statuscall === false ? '' : contact.esta_cod_call == 1 ? contact.listen_call == 1 ? 'checked' : '' : 'disabled'} onchange="updateListenCall(${response.busua_cod}, ${contact.numero})">
                                 </div>
                              </td>
                              <td class="text-center text-danger pb-0">
                                 <i class="fa-solid fa-trash link-pointer" id="delete-contact-${contact.numero}" onclick="deleteContact(${busua_cod}, ${contact.numero})"></i>
                              </td>
                           </tr>
                           `;
                        });

                        return contacts;
                     }

                     body.innerHTML = /* html */ `${dataContacts()}`;

                  }

                  document.getElementById('form-emergency-' + busua_cod).addEventListener('submit', e => {
                     e.preventDefault();
                     createContact(busua_cod);
                  });

                  resolve(busua_cod);

               }).catch((error) => {

                  console.error(`Error: ${error}`);
                  showError(error_system);
                  reject(error_system);

               });

         }, 100);

      });

   } catch (error) {

      console.error(`Error: ${error}`);

   }

}

// update pass
const updatePasswordUser = (busua_cod) => {

   try {

      setTimeout(() => {

         let password_current = document.getElementById('password-current');
         let password = document.getElementById('password');
         let password_v = document.getElementById('password-v');

         cleanInputError(`err-password`);

         if (password_current.value.trim().length === 0) {
            password_current.focus();
            statusMSJ(document.getElementsByName(`err-password`)[0], 'Ingrese contrase&ntilde;a actual.', false, false);
            return false;
         }

         if (password.value.trim().length === 0) {
            password.focus();
            statusMSJ(document.getElementsByName(`err-password`)[0], 'Ingrese contrase&ntilde;a.', false, false);
            return false;
         }

         if (password.value.trim() !== password_v.value.trim()) {
            password_v.focus();
            statusMSJ(document.getElementsByName(`err-password`)[0], 'La contrase&ntilde;a y su verificaci&oacute;n no coinciden.', false, false);
            return false;
         }

         const data = {
            'busua_cod': busua_cod,
            'password_current': password_current.value.trim(),
            'password': password.value.trim(),
            'password_v': password_v.value.trim()
         }

         fetch(`${url}/user/json/json_updatePasswordUser.php`, {
            method: 'POST',
            headers: {
               'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
         })
            .then(response => response.json())
            .then((response) => {

               if (response.status === 'error') {

                  statusMSJ(document.getElementsByName(`err-password`)[0], response.message, false, false);

               } else {

                  document.getElementById(`username`).value = `${response.cloud_username}@${document.getElementById(`du`).value}`;
                  document.getElementById(`cloud_password`).value = `${response.cloud_password}`;

                  setTimeout(() => {
                     document.getElementById(`form-redirect-${busua_cod}`).submit();
                  }, 2000);

                  toast.fire({
                     icon: 'success',
                     title: response.message
                  });

               }

            })
            .catch((err) => {

               console.error(`Error: ${err}`);

            });

      }, 300);

   } catch (error) {

      console.error(`Error: ${error}`);
      showError(error_system);

   }

}

// update email
const updateEmailUser = (busua_cod) => {

   try {

      setTimeout(() => {

         let password = document.getElementById('password');
         let email = document.getElementById('email')

         cleanInputError(`err-email`);

         // EMAIL
         if (!formatEmail.test(email.value.trim())) {
            email.focus();
            statusMSJ(document.getElementsByName('err-email')[0], 'Formato email no valido', false, false);
            return false;
         }

         // PASSWORD
         if (password.value.trim().length === 0) {
            password.focus();
            statusMSJ(document.getElementsByName(`err-email`)[0], 'Ingrese contrase&ntilde;a', false, false);
            return false;
         }

         const data = {
            'busua_cod': busua_cod,
            'email': email.value.trim(),
            'password': password.value.trim()
         }

         fetch(`${url}/user/json/json_updateEmail.php`, {
            method: 'POST',
            headers: {
               'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
         })
            .then(response => response.json())
            .then((response) => {

               if (response.status === 'error') {

                  statusMSJ(document.getElementsByName(`err-email`)[0], response.message, false, false);

               } else {

                  document.getElementById(`email`).value = `${response.email}`;
                  document.getElementById(`password`).value = '';

                  toast.fire({
                     icon: 'success',
                     title: response.message
                  });

               }

            })
            .catch((err) => {

               console.error(`Error: ${err}`);

            });

      }, 300);

   } catch (error) {

      console.error(`Error: ${error}`);
      showError(error_system);

   }

}

// primera configuracion
const conf_menu = async (dom_cod) => {

   try {

      return new Promise((resolve, reject) => {

         setTimeout(() => {

            let root = document.getElementById('root');              // info menu
            let links = document.getElementsByName('links');         // links

            links.forEach(link => {
               link.classList.remove('main-menu__link_select');      // borra la clase que tiene seleccionado el menu
            })

            root.innerHTML = '';

            root.innerHTML += /* html */ `
            <section id="section-user-${dom_cod}">
               <div class="container-fluid">
                  <div class="row">
                     <div class="col-12 d-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
                        <div>
                           <span id="title-adm-menu" class="title-menu-adm"></span>
                           <div id="spinner-menu-title" class="spinner-border spinner-border spinner-border-sm text-danger ms-3" role="status" hidden>
                              <span class="visually-hidden">Loading...</span>
                           </div>
                        </div>
                        <div class="d-flex">
                           <div class="row">
                              <div class="btn-group">
                                 <button type="button" class="btn buttons colors-font" id="btn-menu" name="btn-menu"> 
                                    <div id="spinner-btn-menu" class="spinner-border spinner-border-sm text-white" role="status" hidden>
                                       <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <span id="textbtn-btn-menu" class="colors-font"><i class="fa-solid fa-user-large"></i>&nbsp;<strong class="fw-medium">Perfil</strong></span>
                                 </button>
                                 <button type="button" class="btn buttons colors-font dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="visually-hidden">Toggle Dropdown</span>
                                 </button>
                                 <ul class="dropdown-menu">
                                    <li><a class="dropdown-item link-pointer" onclick="main(3)">Datos personales</a></li>
                                    <li><a class="dropdown-item link-pointer" onclick="main(4)">Cambiar contrase&ntilde;a</a></li>
                                    <li><a class="dropdown-item link-pointer" onclick="main(5)">Cambiar email</a></li>
                                 </ul>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div id="data-result" class="col-12"></div>
                  </div>
               </div>
            </section>
            `;

            resolve(true);

         }, 300);

      });

   } catch (error) {

      console.error(`Error: ${error}`);
      showError(error_system);

   }

}


// muestra menu segun valor agregado
const main_menu = async (menu) => {

   try {

      // variables
      let menuf = parseInt(menu);
      let busua_cod = document.getElementById(`busua_cod`).value;
      let dom_cod = document.getElementById(`dom_cod`).value;

      await spiner_menu_open(`spinner-menu-title`);

      // menu
      await data_menu(menuf, busua_cod);

      // spinner de carga - open
      //(window.innerWidth <= 1023 ? `spinner-menu-title` : `spinner-menu-${menuf}`)

      if (menuf === 1) {
         await formContactEmergency(busua_cod);
      }

      //spinner de carga - close
      await spiner_menu_close(`spinner-menu-title`);

   } catch (error) {

      console.error(`Error: ${error}`);
      showError(error_system);

   }

}

// view menus
const main = async (menu = 1) => {

   localStorage.removeItem('main_menu_user');
   localStorage.setItem('main_menu_user', JSON.stringify(menu));
   await main_menu(JSON.parse(localStorage.getItem('main_menu_user'))); // formatea menu

}

// sirve para dar click en los menu que se requiera
const menus = async () => {

   for (let i = 1; i <= document.getElementsByName('links').length; i++) {
      const element = document.getElementById('links-' + i);
      element.onclick = () => main(i);
   }

}

// load menus
document.addEventListener('DOMContentLoaded', async function (event) {

   let dom_cod = document.getElementById(`dom_cod`).value;        // dominio

   await menus();
   await conf_menu(dom_cod);
   await main(JSON.parse(localStorage.getItem('main_menu_user') !== null ? localStorage.getItem('main_menu_user') : 1));

});