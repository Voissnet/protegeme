'use strict';

const permissions = document.getElementsByName('permissions');

const p1 = parseInt(permissions[0].value);
const p2 = parseInt(permissions[1].value);
const p3 = parseInt(permissions[2].value);
const p4 = parseInt(permissions[3].value);
const p5 = parseInt(permissions[4].value);
const p6 = parseInt(permissions[5].value);

// Función para modificar el contenido de una celda de una fila por su ID
async function modificarCeldaPorId(rowId, colIndex, newVal) {

   const table = $('#table-noti-services').DataTable();

   // Encuentra la fila usando el ID de la fila
   const row = $(`#table-noti-services tbody tr#${rowId}`);

   if (row.length) {

      // Encuentra la celda que deseas modificar dentro de esa fila
      const cell = table.cell(row[0], colIndex); // row[0] es el nodo <tr>, colIndex es el índice de la columna

      // Modifica el contenido de la celda
      cell.data(newVal).draw(); // Actualiza el valor de la celda y redibuja la tabla

      $(cell.node()).addClass('text-dark');

   }

}

// trae informacion del usuario
const dataUser = async (busua_cod) => {
   const res = await fetch(`${url_operator}/json/json_getUser.php?busua_cod=${busua_cod}`, {
      method: 'GET',
      headers: {
         'Content-Type': 'application/json'
      }
   });
   return res.json();
}

// actualiza el estado de un usuario
const updateStatusUser = async (busua_cod, esta_cod) => {
   const data = {
      'busua_cod': busua_cod,
      'esta_cod': esta_cod
   };
   const res = await fetch(`${url_operator}/json/json_updateStatusUser.php`, {
      method: 'POST',
      headers: {
         'Content-Type': 'application/json'
      },
      body: JSON.stringify(data)
   });
   return res.json();
}

// actualiza el estado de un usuario
const checkUpdateStatusUser = async (val_i, busua_cod) => {

   try {

      await localStorageSave(`sUserServiceOper${busua_cod}`, val_i);

      const input = document.getElementById(`status-user-service-${busua_cod}`);
      const inputs = document.getElementsByClassName('status-form-update-service');

      const res = await updateStatusUser(busua_cod, (input.checked === false ? 2 : 1));

      if (res.status === 'error') {

         input.value = JSON.parse(localStorage.getItem(`sUserServiceOper${busua_cod}`));                           // formateamos valor
         input.checked = JSON.parse(localStorage.getItem(`sUserServiceOper${busua_cod}`)) === '1' ? true : false;  // formateamos valor
         await showToastError(res.message);

      } else {

         const row = document.querySelectorAll(`#user-service-${res.busua_cod} td`);                            // traemos la fila afectada

         row[3].innerHTML = res.esta_cod === 1 ? 'Activo' : 'Inactivo';                                         // modificamos el registro
         row[3].classList.remove('text-success', 'text-warning');                                                    // eliminamos las clases
         row[3].classList.add(res.esta_cod === 1 ? 'text-success' : 'text-warning');                            // modificamos a la clase correspondiente

         localStorage.removeItem(`sUserServiceOper${busua_cod}`);                                                    // eliminamos el registro
         localStorage.setItem(`sUserServiceOper${busua_cod}`, JSON.stringify(res.esta_cod));                    // formateamos el valor

         input.value = res.esta_cod;                                                                            // formateamos valor input
         input.checked = res.esta_cod === 1 ? true : false;                                                     // formateamos valor input

         // actualizamos tooltip
         await tooltipUpdateSystem(input.id, `Usuario <b class='${res.esta_cod === 1 ? 'text-success' : 'text-warning'}'>${res.esta_cod === 1 ? 'Activo' : 'Inactivo'}</b>`);

         for (let i = 0; i < inputs.length; i++) {
            res.esta_cod === 1 ? inputs[i].disabled = false : inputs[i].disabled = true;
         }

         await showToastSuccess(res.message);

      }


   } catch (error) {

      input.value = JSON.parse(localStorage.getItem(`sUserServiceOper${busua_cod}`));
      input.checked = JSON.parse(localStorage.getItem(`sUserServiceOper${busua_cod}`)) === '1' ? true : false;
      console.error(`Error: ${error}`);
      await showError(error_system);

   }

}

// actualiza datos del usuario
const updateUserBP = (busua_cod) => {

   try {

      setTimeout(() => {

         // parametros
         const dom_cod = document.getElementById('dom-cod-result-user').value;                                   // dominio al cual pertenece el usuario
         const group_cod = document.getElementById('group-cod-result-user').value;                               // codigo del grupo
         const name = document.getElementById('name-user-service-up').value.trim();                              // nombre del usuario
         const cloud_username = document.getElementById('cloud-username-service-up').value.replaceAll(' ', '').toLowerCase();  // cloud_username del usuario
         const user_phone = document.getElementById('user-phone-service-up').value.replaceAll(' ', '');          // user_phone del usuario
         const email = document.getElementById('email-user-service-up').value.replaceAll(' ', '').toLowerCase(); // email del usuario

         cleanInputError('err-name-user-service-up');                                                          // limpia error name
         cleanInputError('err-cloud-username-service-up');                                                     // limpia error coud_username
         cleanInputError('err-user-phone-service-up');                                                         // limpia error cloud_password
         cleanInputError('err-email-user-service-up');                                                         // limpia error cloud_password

         let error = false;

         // Validaciones
         // NOMBRE
         if (!formatGeneral.test(name)) {
            error = true;
            statusMSJ(document.getElementsByName('err-name-user-service-up')[0], 'Nombre: m&iacute;nimo 3 caracteres, solo letras y n&uacute;meros.', false, false);
         }

         // CLOUD_USERNAME
         if (!formatGeneral.test(cloud_username)) {
            error = true;
            statusMSJ(document.getElementsByName('err-cloud-username-service-up')[0], 'Cloud Username: m&iacute;nimo 3 caracteres, solo letras y n&uacute;meros.', false, false);
         }

         // USER_PHONE
         if (user_phone.length < 9) {
            error = true;
            statusMSJ(document.getElementsByName('err-user-phone-service-up')[0], 'Tel&eacute;fono Usuario: m&iacute;nimo 9 n&uacute;meros.', false, false);
         }

         // EMAIL
         if (!formatEmail.test(email)) {
            error = true;
            statusMSJ(document.getElementsByName('err-email-user-service-up')[0], 'Formato email inv&aacute;lido.', false, false);
         }

         if (error === true) {
            return false;
         }

         // data
         const data = {
            'dom_cod': dom_cod,
            'group_cod': group_cod,
            'busua_cod': busua_cod,
            'cloud_username': cloud_username,
            'user_phone': user_phone,
            'email': email,
            'nombre': name
         };

         spinnerOpen('btn-update-data-user-' + busua_cod);                                                           // abre el spinner

         fetch(`${url_operator}/json/json_updateDataUser.php`, {
            method: 'POST',
            headers: {
               'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
         })
            .then(response => response.json())
            .then((response) => {
               spinnerClose('btn-update-data-user-' + busua_cod, `Actualizar`);                                      // cierra el spinner
               if (response.status === 'error') {

                  showToastError(response.message);

               } else {

                  // actualizamos los valores en el formulario
                  document.getElementById('name-user-service-up').value = response.nombre;                       // nombre del usuario
                  document.getElementById('cloud-username-service-up').value = response.cloud_username;          // cloud_username del usuario
                  document.getElementById('user-phone-service-up').value = response.user_phone;                  // user_phone del usuario
                  document.getElementById('email-user-service-up').value = response.email;                       // email del usuario

                  const td = document.querySelectorAll(`#user-service-${busua_cod} td`);                          // datos del usuario en la tabla

                  td[1].innerHTML = response.nombre;
                  td[2].innerHTML = response.cloud_username + '@' + response.dominio_usuario;
                  td[4].innerHTML = response.email;

                  // notifiacion
                  if (response.status_noti === true) {

                     td[5].innerHTML = '';

                     let noti_text = '';
                     let info_noti_text = `
                     <span>Fecha creaci&oacute;n: <br> ${response.fecha_creacion === null ? 'No registra' : response.fecha_creacion}</span>
                     <br>
                     <span>Fecha notificaci&oacute;n: <br> ${response.fecha_notificacion === null ? 'No registra' : response.fecha_notificacion}</span>
                     `;
                     if (response.notifica === '1') {
                        noti_text = `<span class="text-success link-pointer" data-bs-toggle="tooltipInfoUsers" data-bs-html="true" data-bs-title="${info_noti_text}">Notificado</span>`
                     } else {
                        noti_text = `<span class="text-danger link-pointer" data-bs-toggle="tooltipInfoUsers" data-bs-html="true" data-bs-title="${info_noti_text}">No notificado</span>`
                     }
                     td[5].innerHTML = noti_text;

                     tooltipSystem('tooltipInfoUsers');

                  }

                  showToastSuccess(response.message);

               }
            })
            .catch((error) => {

               console.error(`Error: ${error}`);

            });

      }, 300);

   } catch (error) {

      spinnerClose('btn-update-data-user-' + busua_cod, `Actualizar`);
      console.error(`Error: ${error}`);
      showError(error_system);

   }

}

// actualiza el grupo del usuario
const updateContactCenterUser = async (busua_cod, group_cod) => {
   const data = {
      'busua_cod': busua_cod,
      'group_cod': group_cod
   }
   const res = await fetch(`${url_operator}/json/json_updateContactCenterUser.php`, {
      method: 'POST',
      headers: {
         'Content-Type': 'application/json'
      },
      body: JSON.stringify(data)
   });
   return res.json();
}


// check actualiza el grupo del usuario
const checkUpdateContactCenterUser = async (input, val_i, busua_cod) => {

   try {

      await localStorageSave(`group_user_${busua_cod}`, val_i);

      const question = await questionSweetAlert('¿Estas seguro de modificar el <span class="text-primary">Contact Center</span> del Usuario?');

      if (question.isConfirmed) {

         const res = await updateContactCenterUser(busua_cod, input.value);

         if (res.status === 'error') {

            input.value = JSON.parse(localStorage.getItem(`group_user_${busua_cod}`));
            await showToastError(res.message);

         } else {

            const input_group = document.getElementById('select-group').value;

            // si es distinto a "0" todos
            if (input_group !== '0') {

               let table = $('#table-info-users-service-' + parseInt(localStorage.getItem(`group_user_${busua_cod}`))).DataTable();

               if (input_group !== res.group_cod) {

                  table.row('#user-service-' + res.busua_cod).remove().draw();

               } else {

                  await menuUsers(res.group_cod);

               }

            }

            localStorage.removeItem(`group_user_${res.busua_cod}`);

            await showToastSuccess(res.message);

         }

      } else {

         input.value = JSON.parse(localStorage.getItem(`group_user_${busua_cod}`));

      }

   } catch (error) {

      input.value = JSON.parse(localStorage.getItem(`group_user_${busua_cod}`));
      console.error(`Error: ${error}`);
      await showError(error_system);

   }

}

// elimina un usuario y sus servicios
const deleteUserService = async (busua_cod) => {
   const info = new FormData();
   info.append('busua_cod', busua_cod);
   const data = await fetch(`${url_operator}/json/json_deleteUser.php`, {
      method: 'POST',
      body: info
   });
   return data.json();
}

// check elimina un usuario de un servicio
const chekDeleteUserService = async (busua_cod, op) => {

   try {

      const question = await questionSweetAlert('¿Esta seguro que desea eliminar el <strong class="text-primary">Usuario</strong>, tambi&eacute;n se eliminar&aacute;n los servicios?');

      if (question.isConfirmed) {

         const res = await deleteUserService(busua_cod);

         if (res.status === 'error') {

            await showToastError(res.message);

         } else {

            const table = $('table[name="table-info-users-service"]').DataTable();

            table.row(`#user-service-${res.busua_cod}`).remove().draw();

            if (op === 2) {

               document.getElementById('infoGeneral').classList.remove('show');
               document.querySelector('.offcanvas-backdrop').remove();

               const body = document.querySelector('body');

               body.classList.remove('swal2-shown', 'swal2-height-auto');
               body.style.width = null;
               body.style.height = null;
               body.style.padding = null;
               body.style.overflow = null;

            }

            await showToastSuccess(res.message);

         }

      }

   } catch (error) {

      console.error(`Error: ${error}`);
      await showError(error_system);

   }

}

// formulario de usuario
const formEditUser = async (result) => {

   return new Promise((resolve, reject) => {

      setTimeout(() => {

         const title = document.getElementById('infoGeneralLabel');
         const data = document.getElementById('data-result-adm-service');
         const inputs = document.getElementsByClassName('status-form-update-service');

         title.innerHTML = '';
         data.innerHTML = '';

         // recorre y prepara informacion para el input de los grupos
         const dataGroups = () => {
            let groups = '';
            result.dataGroups.forEach(element => {
               groups += `<option value="${element.group_cod}" ${result.group_cod === element.group_cod ? 'selected' : ''}>${element.nombre}</option>`;
            });
            return groups;
         }

         title.innerHTML += /* html */ `
         <i class="fa fa-user colors-font mx-3" title="icon users"></i>
         <h4 class="offcanvas-title colors-font">Formularios de actualizaci&oacute;n</h4>
         `;

         data.innerHTML += /* html */ `
         <div class="row m-1 p-2 rounded border">
            <div class="col-12 d-flex justify-content-between">
               <div>
                  <h5>Datos del usuarios</h5>
               </div>
               <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" role="switch" id="status-user-service-${result.busua_cod}" value="${result.esta_cod === '1' ? 1 : 2}" ${result.esta_cod === '1' ? 'checked' : ''} data-bs-toggle="tooltipStatusUserService" data-bs-html="true" data-bs-title="Usuario <b class='${result.esta_cod === '1' ? 'text-success' : 'text-warning'}'>${result.estado}</b>" title="Usuario ${result.estado}" ${p1 === 2 ? `onchange="checkUpdateStatusUser(this.value, ${result.busua_cod})"` : 'disabled'}>
               </div>
            </div>
            <div class="col-12 mb-3">
               <form class="row" id="form-update-service-user-${result.busua_cod}" name="form-update-service-user-${result.busua_cod}">
                  <input type="hidden" id="dom-cod-result-user" name="dom-cod-result-user" value="${result.dom_cod}">
                  <input type="hidden" id="group-cod-result-user" name="group-cod-result-user" value="${result.group_cod}">
                  <div class="col-12 mb-2">
                     <label for="name-user-service-up" class="form-label">Nombre:</label>
                     <input type="text" class="form-control status-form-update-service" id="name-user-service-up" minlenght="3" maxlength="120" placeholder="Ingrese un nombre para el Usuario" aria-describedby="name-user-service-up-text" title="Nombre para el Usuario" value="${result.nombre === null ? '' : result.nombre}" autocomplete="off">
                     <div id="name-user-service-up-help" name="err-name-user-service-up" class="form-text" hidden></div>
                  </div>
                  <div class="col-12 mb-2">
                     <label for="cloud-username-service-up" class="form-label">Cloud Username:</label>
                     <div class="input-group">
                        <input type="text" class="form-control status-form-update-service" id="cloud-username-service-up" minlenght="3" maxlength="120" placeholder="Ingrese un Cloud Username" aria-describedby="cloud-username-service-up-text" title="Cloud Username para el Usuario" value="${result.cloud_username === null ? '' : result.cloud_username}" onkeydown="return onlyCloudUsername(event)" autocomplete="off">
                        <span class="input-group-text bg-secondary-subtle" id="domain-service-users-up">@${result.dominio_usuario}</span>
                     </div>
                     <div id="cloud-username-service-up-help" name="err-cloud-username-service-up" class="form-text" hidden></div>
                  </div>
                  <div class="col-12 mb-2">
                     <label for="user-phone-service-up" class="form-label">Tel&eacute;fono Usuario:</label>
                     <div class="input-group">
                        <span class="input-group-text" id="num-tel-up">56</span>
                        <input type="text" class="form-control status-form-update-service" id="user-phone-service-up" minlenght="9" maxlength="11" placeholder="EJ1: 212345678 - EJ2: 912345678" aria-describedby="user-phone-service-up-text" title="Tel&eacute;fono para el Usuario" value="${result.user_phone === 'undefined' ? '' : result.user_phone}" onkeydown="return onlyNumbers(event)" autocomplete="off">
                     </div>
                     <div id="user-phone-service-up-help" name="err-user-phone-service-up" class="form-text" hidden></div>
                  </div>
                  <div class="col-12 mb-2">
                     <label for="email-user-service-up" class="form-label">Email:</label>
                     <input type="text" class="form-control status-form-update-service" id="email-user-service-up" minlenght="3" maxlength="120" aria-describedby="email-user-service-up" placeholder="Ingrese un Email" title="Email para el Usuario" value="${result.email === null ? '' : result.email}" autocomplete="off">
                     <div id="email-user-service-up-help" name="err-email-user-service-up" class="form-text" hidden></div>
                  </div>
                  ${p1 === 2 ? `
                  <div class="col-12 d-flex justify-content-end mb-2">
                     <button type="submit" class="btn btn-sm buttons colors-font status-form-update-service" id="btn-update-data-user-${result.busua_cod}" name="btn-update-data-user-${result.busua_cod}"> 
                        <div id="spinner-update-data-user" class="spinner-border spinner-border-sm text-white" role="status" hidden>
                           <span class="visually-hidden">Loading...</span>
                        </div>
                        <span id="textbtn-update-data-user">Actualizar</span>
                     </button>
                  </div>
                  ` : ``}
               </form>
            </div>
            <hr>
            <div class="col-12 mb-2">
               <label for="select-group-user-${result.busua_cod}" class="form-label">Contact Center:</label>
               <select class="form-select status-form-update-service" id="select-group-user-${result.busua_cod}" name="select-group-user-${result.busua_cod}" ${p1 === 2 ? `onchange="checkUpdateContactCenterUser(this, ${result.group_cod}, ${result.busua_cod})"` : ''}>
                  ${dataGroups()}
               </select>
            </div>
         </div>
         ${p1 === 2 ? `
         <div class="row m-1 p-2 rounded border">
            <div class="col-12 d-flex justify-content-end">
               <img class="link-pointer" src="${url_img}/icon-trash-delete.png" width="20" height="20" id="delete-user-service-${result.busua_cod}" name="delete-user-service-${result.busua_cod}" title="Elimina usuario del dominio" onclick="chekDeleteUserService(${result.busua_cod}, 2)">
            </div>
         </div>
         ` : ``}
         `;

         if (p1 === 2) {
            document.getElementById('form-update-service-user-' + result.busua_cod).addEventListener('submit', function (e) {
               e.preventDefault();
               updateUserBP(result.busua_cod);
            });
         }

         for (let i = 0; i < inputs.length; i++) {
            if (p1 === 2) {
               result.esta_cod === '1' ? inputs[i].disabled = false : inputs[i].disabled = true;
            } else {
               inputs[i].disabled = true;
            }
         }

         tooltipSystem('tooltipStatusUserService');

         resolve(true);

      }, 300);

   });

}

// muestra formulario de edicion de un usuario
const viewFormEdit = async (busua_cod) => {

   try {

      await showLoadingSystem('');

      const result = await dataUser(busua_cod);

      await formEditUser(result);

      await openOffCanvas('infoGeneral');

   } catch (error) {

      console.error(`Error: ${error}`);
      await showToastError(error);

   }

}

// actualiza localizacion
const updateLocalizacion = async (busua_cod, localizacion) => {
   const data = {
      'busua_cod': busua_cod,
      'localizacion': localizacion
   }
   const res = await fetch(`${url_operator}/json/json_updateLocalizacion.php`, {
      method: 'POST',
      headers: {
         'Content-Type': 'application/json'
      },
      body: JSON.stringify(data)
   });
   return res.json();
}

// actualiza la mac 
const updateMac = async (bot_cod, busua_cod, mac) => {
   const info = {
      'bot_cod': bot_cod,
      'busua_cod': busua_cod,
      'mac': mac
   }
   const data = await fetch(`${url_operator}/json/json_updateMac.php`, {
      method: 'POST',
      headers: {
         'Content-Type': 'application/json'
      },
      body: JSON.stringify(info)
   });
   return data.json();
}

// actualiza el estado de tracker
const updateStatusButton = (val_i, busua_cod) => {
   let input = document.getElementById('status-boton-service-' + busua_cod);
   let inputs = document.getElementsByClassName('status-form-update-btn-panic');
   localStorageSave('sButtonServiceOper' + busua_cod, val_i);
   try {
      setTimeout(() => {
         // data
         let data = {
            'busua_cod': busua_cod,
            'esta_cod': input.checked === false ? 2 : 1
         };
         fetch(`${url_operator}/json/json_updateStatusButton.php`, {
            method: 'POST',
            headers: {
               'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
         })
            .then(response => response.json())
            .then((response) => {
               if (response.status === 'error') {
                  input.value = JSON.parse(localStorage.getItem('sButtonServiceOper' + busua_cod));
                  input.checked = JSON.parse(localStorage.getItem('sButtonServiceOper' + busua_cod)) === '1' ? true : false;
                  showToastError(response.message);
               } else {
                  localStorage.removeItem('sButtonServiceOper' + busua_cod);
                  localStorage.setItem('sButtonServiceOper' + busua_cod, JSON.stringify(response.esta_cod));
                  input.value = response.esta_cod;
                  input.checked = response.esta_cod === 1 ? true : false;
                  // actualizamos tooltip
                  tooltipUpdateSystem(input.id, `Bot&oacute;n <b class='${response.esta_cod === 1 ? 'text-success' : 'text-warning'}'>${response.esta_cod === 1 ? 'Activo' : 'Inactivo'}</b>`);
                  for (let i = 0; i < inputs.length; i++) {
                     response.esta_cod === 1 ? inputs[i].disabled = false : inputs[i].disabled = true;
                  }
                  toast.fire({
                     icon: 'success',
                     title: response.message,
                     width: 250
                  });
               }
            }).catch((error) => {
               input.value = JSON.parse(localStorage.getItem('sButtonServiceOper' + busua_cod));
               input.checked = JSON.parse(localStorage.getItem('sButtonServiceOper' + busua_cod)) === '1' ? true : false;
               console.error(`Error: ${error}`);
               showError(error_system);
            });
      }, 100);
   } catch (error) {
      input.value = JSON.parse(localStorage.getItem('sButtonServiceOper' + busua_cod));
      input.checked = JSON.parse(localStorage.getItem('sButtonServiceOper' + busua_cod)) === '1' ? true : false;
      console.error(`Error: ${error}`);
      showError(error_system);
   }
}

// agrega un servicio a la interfaz de UPDATE
const confMenuPreServiceUP = (op, busua_cod) => {

   return new Promise((resolve, reject) => {

      setTimeout(async () => {

         let opservice = 0;

         switch (op) {
            case 1:
               opservice = 2;
               break;
            case 2:
               opservice = 2;
               break;
            case 3:
               opservice = 2;
               break;
            case 4:
               opservice = 3;
               break;
            case 5:
               opservice = 4;
               break;
            case 6:
               opservice = 2;
               break;
         }

         if (validateService(opservice, op, busua_cod) === false) {

            reject('Proceso no valido');

         } else {

            resolve(true);

         }

      }, 300);

   });

}

// muestra los parametros requeridos
const showParamsRequired = (val, busua_cod) => {

   try {

      const tipo_cod = parseInt(val);
      const button = document.getElementById(`inputs-button-${busua_cod}`);
      const tracker = document.getElementById(`inputs-tracker-${busua_cod}`);

      switch (tipo_cod) {
         case 2:
            button.hidden = false;
            tracker.hidden = true;
            break;
         case 5:
            tracker.hidden = false;
            button.hidden = true;
            break;
         default:
            button.hidden = true;
            tracker.hidden = true;
            break;
      }

   } catch (error) {

      console.error(`Error: ${error}`);

   }

}

// obtiene datos del tracker
const dataServiceTracker = async (busua_cod) => {
   const data = await fetch(`${url_operator}/json/json_getTracker.php?busua_cod=${busua_cod}`, {
      method: 'GET',
      headers: {
         'Content-Type': 'application/json'
      }
   });
   return data.json();
}

// data servicios boton
const dataServiceButton = async (busua_cod) => {
   const data = await fetch(`${url_operator}/json/json_getButtonPanic.php?busua_cod=${busua_cod}`, {
      method: 'GET',
      headers: {
         'Content-Type': 'application/json'
      }
   });
   return data.json();
}

// obtiene todos los otros productos de un usuario
const dataOtherProducts = async (busua_cod) => {
   const data = await fetch(`${url_operator}/json/json_getProducts.php?busua_cod=${busua_cod}`, {
      method: 'GET',
      headers: {
         'Content-Type': 'application/json'
      }
   });
   return data.json();
}

// actualiza estado
const updateStatuService = async (input, busua_cod, id, tipo_cod) => {
   const info = new FormData();
   info.append('busua_cod', busua_cod);
   info.append('esta_cod', (input.checked === true ? 1 : 2));
   info.append('id', id);
   info.append('tipo_cod', tipo_cod);
   const data = await fetch(`${url_operator}/json/json_updateStatusButton.php`, {
      method: 'POST',
      body: info
   });
   return data.json();
}

// actualiza el estado de tracker
const checkUpdateStatuService = async (val, val_i, busua_cod, id, tipo_cod) => {

   try {

      await localStorageSave(`service-${id}`, val_i);

      let statusclass = '';
      let idstatus = '';
      let descstatus = '';

      switch (parseInt(tipo_cod)) {

         case 1:

            statusclass = `status-service-button-${id}`;
            idstatus = `status-service-button-${id}`;
            descstatus = ('Servicio bot&oacute;n ' + (val.checked === true ? `<b class='text-success'>Activo</b>` : `<b class='text-warning'>Inactivo</b>`));
            break;

         case 2:

            statusclass = `status-service-button-${id}`;
            idstatus = `status-service-button-${id}`;
            descstatus = ('Servicio bot&oacute;n ' + (val.checked === true ? `<b class='text-success'>Activo</b>` : `<b class='text-warning'>Inactivo</b>`));
            break;

         case 5:

            statusclass = `status-form-update-tracker-${busua_cod}`;
            idstatus = `status-service-tracker-${busua_cod}`;
            descstatus = ('Servicio tracker ' + (val.checked === true ? `<b class='text-success'>Activo</b>` : `<b class='text-warning'>Inactivo</b>`));
            break;

         case 6:

            statusclass = `status-service-button-${id}`;
            idstatus = `status-service-button-${id}`;
            descstatus = ('Servicio bot&oacute;n ' + (val.checked === true ? `<b class='text-success'>Activo</b>` : `<b class='text-warning'>Inactivo</b>`));
            break;

         default:

            statusclass = ``;
            idstatus = `status-service-product-${id}`;
            descstatus = ('Servicio ' + (val.checked === true ? `<b class='text-success'>Activo</b>` : `<b class='text-warning'>Inactivo</b>`));
            break;

      }

      const res = await updateStatuService(val, busua_cod, id, tipo_cod);

      if (res.status === 'err') {

         val.checked = (JSON.parse(localStorage.getItem(`service-${id}`))) === 1 ? true : false;
         await showToastError(res.message);

      } else {

         localStorage.removeItem(`service-${id}`);
         localStorage.setItem(`service-${id}`, JSON.stringify(res.esta_cod));

         switch (parseInt(tipo_cod)) {

            case 2:

               document.getElementById(`localizacion-udp-button-${id}`).disabled = parseInt(res.esta_cod) === 1 ? false : true;
               document.getElementById(`mac-udp-${id}`).disabled = parseInt(res.esta_cod) === 1 ? false : true;

               break;

            case 5:

               document.getElementById(`tipo-tracker-service-${busua_cod}`).disabled = parseInt(res.esta_cod) === 1 ? false : true;
               document.getElementById(`causa-tracker-service-${busua_cod}`).disabled = parseInt(res.esta_cod) === 1 ? false : true;

               break;

            default:

               break;
         }


         await tooltipUpdateSystem(idstatus, descstatus);

         await showToastSuccess(res.message);

      }

   } catch (error) {
      console.error(`Error: ${error}`);
      showToastError(error_system);
   }

}

// elimina un servicio
const deleteService = async (bot_cod, busua_cod, tipo_cod) => {
   const info = new FormData();
   info.append('bot_cod', bot_cod);
   info.append('busua_cod', busua_cod);
   info.append('tipo_cod', tipo_cod);
   const data = await fetch(`${url_operator}/json/json_deleteService.php`, {
      method: 'POST',
      body: info
   });
   return data.json();
}

// check elimina un servicio
const chekDeleteService = async (bot_cod, busua_cod, tipo_cod) => {

   try {

      const question = await questionSweetAlert('¿Estas seguro de <span class="text-primary">eliminar</span> el servicio?');

      if (question.isConfirmed === true) {

         const result = await deleteService(bot_cod, busua_cod, tipo_cod);

         if (result.status === 'success') {

            switch (tipo_cod) {

               case 1:
                  await formButton(await dataServiceButton(busua_cod), busua_cod);
                  break;
               case 2:
                  await formButton(await dataServiceButton(busua_cod), busua_cod);
                  break;
               case 4:
                  await formProducts(await dataOtherProducts(busua_cod), busua_cod);
                  break;
               case 5:
                  await formTracker(await dataServiceTracker(busua_cod), busua_cod);
                  break;
               case 6:
                  await formButton(await dataServiceButton(busua_cod), busua_cod);
                  break;
            }

            await showToastSuccess(result.message);

         } else {

            await showToastError(result.message);

         }

      }

   } catch (error) {

      console.error(`Error: ${error}`);

   }

}

// actualiza la localizacion de un servicio
const checkUpdateLocalizacion = async (val, val_i, busua_cod) => {

   try {

      val_i = (val_i == 'null' ? '' : val_i);

      await localStorageSave(`localizacion-${busua_cod}`, val_i);

      const question = await questionSweetAlert('¿Estas seguro de modificar la <strong class="text-primary">Localizaci&oacute;n</strong> del servicio?');

      if (question.isConfirmed) {

         await showLoadingSystem('');

         const res = await updateLocalizacion(busua_cod, val.value.trim());

         if (res.status === 'err') {

            val.value = JSON.parse(localStorage.getItem(`localizacion-${busua_cod}`));
            await showToastError(res.message);

         } else {

            val.value = res.localizacion;
            localStorage.removeItem(`localizacion-${busua_cod}`);
            await localStorageSave(`localizacion-${busua_cod}`, res.localizacion);
            await showToastSuccess(res.message);

         }

         await Swal.close();

      } else {

         val.value = JSON.parse(localStorage.getItem(`localizacion-${busua_cod}`));

      }

   } catch (error) {

      val.value = JSON.parse(localStorage.getItem(`localizacion-${busua_cod}`));
      console.error(error);
      await showError(error_system);

   }

}

// check actualiza la mac 
const checkUpdateMac = async (input, val_i, bot_cod, busua_cod) => {

   try {

      await localStorageSave(`mac-${busua_cod}`, val_i);

      await cleanInputError(`err-mac-udp-${busua_cod}`);

      if (!formMac.test(input.value.trim())) {
         await statusMSJ(document.getElementsByName(`err-mac-udp-${busua_cod}`)[0], 'Formato Mac inv&aacute;lido.', false, false);
         return false;
      }

      const question = await questionSweetAlert('¿Estas seguro de modificar la <strong class="text-primary">Mac</strong> del servicio?');

      if (question.isConfirmed) {

         const res = await updateMac(bot_cod, busua_cod, input.value.trim());

         if (res.status === 'err') {

            input.value = JSON.parse(localStorage.getItem(`mac-${busua_cod}`));
            await showToastError(res.message);

         } else {

            input.value = res.mac;
            localStorage.removeItem(`mac-${busua_cod}`);
            localStorageSave(`mac-${busua_cod}`, res.mac);
            await showToastSuccess(res.message);

         }

      } else {

         input.value = JSON.parse(localStorage.getItem(`mac-${busua_cod}`));

      }

   } catch (error) {

      input.value = JSON.parse(localStorage.getItem(`mac-${busua_cod}`));
      console.error(error);
      await showError(error_system);

   }

}

// formulario servicios boton
const formButton = async (data, busua_cod) => {

   return new Promise((resolve, reject) => {

      setTimeout(() => {

         const html = document.getElementById(`acc-button-panic-${busua_cod}`);

         html.innerHTML = '';

         if (data.status !== 'err') {

            data.dataBP.forEach((element) => {

               const tipo_cod = parseInt(element.tipo_cod);
               const localizacion = element.localizacion === null ? '' : element.localizacion;
               const mac = element.mac === null ? '' : element.mac;
               const fecha_creacion = new Date(element.fecha_creacion);
               const fecha_notificacion = element.fecha_notificacion === null ? 'No registra' : new Date(element.fecha_notificacion);

               let htmlaccordion = '';
               let info = '';
               let statusdesc = 'Servicio bot&oacute;n ' + (parseInt(element.esta_cod) === 1 ? `<b class='text-success'>Activo</b>` : `<b class='text-warning'>Inactivo</b>`);

               // valida el tipo de servicio (dependiendo del tipo muestra campos localizacion, mac)
               let view_campo = false;
               switch (tipo_cod) {
                  case 2:
                     view_campo = true;
                     break;
                  default:
                     view_campo = false;
                     break;
               }

               info = `
               <div class="row">
                  <div class="col-12 d-flex justify-content-between align-items-center">
                     <div class="link-pointer">
                        <i class="fa-solid fa-envelope-open-text fa-lg px-2" style="color: #198754" data-bs-toggle="tooltipStatusButton" data-bs-html="true" data-bs-title="Notificar" onclick="checkNotiUserService(${parseInt(busua_cod)}, 1, ${tipo_cod}, ${parseInt(element.bot_cod)})"></i>
                     </div>
                     <div class="form-check form-switch" id="status-service-button-${element.bot_cod}" name="status-service-button-${element.bot_cod}" data-bs-toggle="tooltipStatusButton" data-bs-html="true" data-bs-title="${statusdesc}">
                        <input class="form-check-input" type="checkbox" role="switch" onchange="checkUpdateStatuService(this, ${parseInt(element.esta_cod)}, ${busua_cod}, ${element.bot_cod}, ${element.tipo_cod})" ${parseInt(element.esta_cod) === 1 ? 'checked' : ''}>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-lg-3 mb-3">
                     <label for="fecha-creacion-${element.bot_cod}" class="form-label col-form-label-sm mb-1">Fecha de creaci&oacute;n:</label>
                     <input type="text" class="form-control form-control-sm" id="fecha-creacion-${element.bot_cod}" name="fecha-creacion-${element.bot_cod}" value="${dateFormat(fecha_creacion)} a las ${horaFormateada(fecha_creacion)}" title="Fecha de creaci&oacute;n del servicio" disabled>
                  </div>
                  <div class="col-lg-3 mb-3">
                     <label for="fecha-notificacion-${element.bot_cod}" class="form-label col-form-label-sm mb-1">Fecha de notificaci&oacute;n:</label>
                     <input type="text" class="form-control form-control-sm" id="fecha-notificacion-${element.bot_cod}" name="fecha-notificacion-${element.bot_cod}" value="${element.fecha_notificacion === null ? 'No registra' : `${dateFormat(fecha_notificacion)} a las ${horaFormateada(fecha_notificacion)}`}" title="Fecha de notificaci&oacute;n del servicio" disabled>
                  </div>
                  <div class="col-lg-3 mb-3">
                     <label for="sip-username-user-${element.bot_cod}" class="form-label col-form-label-sm mb-1">Sip Username:</label>
                     <input type="text" class="form-control form-control-sm" id="sip-username-user-${element.bot_cod}" name="sip-username-user-${element.bot_cod}" title="Sip Username del servicio bot&oacute;n" value="${element.sip_username}" disabled>
                  </div>
                  <div class="col-lg-3 mb-3">
                     <label for="sip-password-user-${element.bot_cod}" class="form-label col-form-label-sm mb-1">Sip Password:</label>
                     <input type="text" class="form-control form-control-sm" id="sip-password-user-${element.bot_cod}" name="sip-password-user-${element.bot_cod}" title="Sip Password del servicio bot&oacute;n" value="${element.sip_password}" disabled>
                  </div>
                  <div class="col-lg-3 mb-3">
                     <label for="sip-display-name-user-${element.bot_cod}" class="form-label col-form-label-sm mb-1">Sip Display Name:</label>
                     <input type="text" class="form-control form-control-sm" id="sip-display-name-user-${element.bot_cod}" name="sip-display-name-user-${element.bot_cod}" title="Sip Display Name del bot&oacute;n" value="${element.sip_display_name}" disabled>
                  </div>
                  <div class="col-lg-3 mb-3 ${view_campo === true ? '' : 'd-none'}">
                     <label for="localizacion-udp-button-${element.bot_cod}" class="form-label col-form-label-sm mb-1">Localizaci&oacute;n:</label>
                     <input type="text" class="form-control form-control-sm status-service-button-${element.bot_cod}" id="localizacion-udp-button-${element.bot_cod}" placeholder="Ingrese una localizaci&oacute;n" title="Localizaci&oacute;n del del servicio del bot&oacute;n" onchange="checkUpdateLocalizacion(this, '${localizacion}', '${busua_cod}')" value="${localizacion}">
                  </div>
                  <div class="col-lg-3 mb-3 ${view_campo === true ? '' : 'd-none'}">
                     <label for="mac-udp-${element.bot_cod}" class="form-label">Mac:</label>
                     <input type="text" class="form-control status-service-button-${element.bot_cod}" id="mac-udp-${element.bot_cod}" maxlength="12" placeholder="Ingrese una Mac" title="Mac del servicio" onchange="checkUpdateMac(this, '${mac}', ${element.bot_cod}, ${busua_cod})" value="${mac}" pattern="^([0-9A-Fa-f]){12}$" onkeyup="validateMac(event, ${element.bot_cod})">
                     <div id="mac-udp-${element.bot_cod}-help" name="err-mac-udp-${element.bot_cod}" class="form-text" hidden></div>
                  </div>
               </div>
               `;

               htmlaccordion += `
               <div id="button-service-${element.bot_cod}" class="accordion-item" name="accordion-services" data-value="${tipo_cod}">
                  <h2 class="accordion-header d-flex align-items-center">
                     <img class="link-pointer" src="https://${document.domain}/img/icon-trash-delete.png" width="27" height="27" id="delete-button-service-${element.bot_cod}" name="delete-button-service-${element.bot_cod}" onclick="chekDeleteService(${element.bot_cod}, ${busua_cod}, ${tipo_cod})" title="Eliminar servicio">
                     <button id="btn-desc-${element.bot_cod}" class="accordion-button bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#panel-service-up-${tipo_cod}" aria-expanded="true" aria-controls="panel-service-up-${tipo_cod}">
                        ${element.tipo}
                     </button>
                  </h2>
                  <div id="panel-service-up-${tipo_cod}" class="accordion-collapse collapse show">
                     <div class="accordion-body">
                     ${info}
                     </div>
                  </div>
               </div>
               `;

               html.innerHTML += /* html */ `
               ${htmlaccordion}
               `;

            });

         }

         tooltipSystem('tooltipStatusButton');

         resolve(true);

      });

   });

}

// formulario servicios boton
const formProducts = async (data, busua_cod) => {

   return new Promise((resolve, reject) => {

      setTimeout(() => {

         const html = document.getElementById(`acc-other-products-${busua_cod}`);

         html.innerHTML = '';

         if (data.status !== 'err') {

            data.products.forEach(element => {

               let info = '';
               let statusdesc = '';

               const fecha_creacion = new Date(element.fecha_creacion);
               const fecha_notificacion = element.fecha_notificacion === null ? 'No registra' : new Date(element.fecha_notificacion);

               switch (parseInt(element.tipo_cod)) {
                  case 4:
                     info = `
                     <div class="row">
                        <div class="col-lg-3 mb-3">
                           <label for="fecha-creacion-${element.prod_cod}" class="form-label col-form-label-sm mb-1">Fecha de creaci&oacute;n:</label>
                           <input type="text" class="form-control form-control-sm" id="fecha-creacion-${element.prod_cod}" name="fecha-creacion-${element.prod_cod}" value="${dateFormat(fecha_creacion)} a las ${horaFormateada(fecha_creacion)}" title="Fecha de creaci&oacute;n del servicio" disabled>
                        </div>
                        <div class="col-lg-3 mb-3">
                           <label for="fecha-notificacion-${element.prod_cod}" class="form-label col-form-label-sm mb-1">Fecha de notificaci&oacute;n:</label>
                           <input type="text" class="form-control form-control-sm" id="fecha-notificacion-${element.prod_cod}" name="fecha-notificacion-${element.prod_cod}" value="${element.fecha_notificacion === null ? 'No registra' : `${dateFormat(fecha_notificacion)} a las ${horaFormateada(fecha_notificacion)}`}" title="Fecha de notificaci&oacute;n del servicio" disabled>
                        </div>
                     </div>
                     `;
                     break;
                  default:
                     info = `
                     <ul>
                        <li>Fecha creaci&oacute;n: ${element.fecha_creacion}</li>
                     </ul>`;
                     break;
               }

               statusdesc = 'Servicio ' + (parseInt(element.esta_cod) === 1 ? `<b class='text-success'>Activo</b>` : `<b class='text-warning'>Inactivo</b>`);

               html.innerHTML += /* html */ `
               <div class="accordion-item" name="accordion-services" data-value="${element.tipo_cod}">
                  <h2 class="accordion-header d-flex align-items-center">
                     <img class="link-pointer" src="https://${document.domain}/img/icon-trash-delete.png" width="27" height="27" id="delete-button-service-${busua_cod}" name="delete-button-service-${busua_cod}" onclick="chekDeleteService(${element.prod_cod}, ${busua_cod}, 4)" title="Eliminar servicio">
                     <button class="accordion-button bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#panel-service-up-${element.tipo_cod}" aria-expanded="true" aria-controls="panel-service-up-${element.tipo_cod}">
                        ${element.tipo}
                     </button>
                  </h2>
                  <div id="panel-service-up-${element.tipo_cod}" class="accordion-collapse collapse show">
                     <div class="accordion-body">
                        <div class="row">
                           <div class="col-12 d-flex justify-content-between align-items-center">
                              <div class="link-pointer">
                                 <i class="fa-solid fa-envelope-open-text fa-lg px-2" style="color: #198754" data-bs-toggle="tooltip-notify-products" data-bs-html="true" data-bs-title="Notificar" onclick="checkNotiUserService(${parseInt(busua_cod)}, 1, ${element.tipo_cod}, ${parseInt(element.prod_cod)})"></i>
                              </div>
                              <div class="form-check form-switch" id="status-service-product-${element.prod_cod}" data-bs-toggle="tooltip-status-products" data-bs-html="true" data-bs-title="${statusdesc}">
                                 <input class="form-check-input" type="checkbox" id="check-state-${element.prod_cod}" role="switch" onchange="checkUpdateStatuService(this, ${parseInt(element.esta_cod)}, ${busua_cod}, ${element.prod_cod}, ${element.tipo_cod})" ${parseInt(element.esta_cod) === 1 ? 'checked' : ''}>
                              </div>
                           </div>
                        </div>
                        ${info}
                     </div>
                  </div>
               </div>
               `;

            });

         }

         tooltipSystem(`tooltip-status-products`);
         tooltipSystem('tooltip-notify-products');

         resolve(true);

      });

   });

}

// formulario tracker
const formTracker = async (data, busua_cod) => {

   return new Promise((resolve, reject) => {

      setTimeout(() => {

         const html = document.getElementById(`acc-tracker-${busua_cod}`);

         html.innerHTML = '';

         if (data.status !== 'err') {

            let htmlaccordion = '';

            const tipo_cod = parseInt(data.tipo_cod);

            let statusdesc = 'Servicio tracker ' + (parseInt(data.esta_cod) === 1 ? `<b class='text-success'>Activo</b>` : `<b class='text-warning'>Inactivo</b>`);

            htmlaccordion += `
            <div class="accordion-item" name="accordion-services" data-value="5">
               <h2 class="accordion-header d-flex align-items-center">
                  <img class="link-pointer" src="https://${document.domain}/img/icon-trash-delete.png" width="27" height="27" id="delete-tracker-service-${busua_cod}" name="delete-tracker-service-${busua_cod}" onclick="chekDeleteService(${tipo_cod}, ${busua_cod}, 5)" title="Eliminar servicio">
                  <button class="accordion-button bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#panel-service-up--1" aria-expanded="true" aria-controls="panel-service-up--1">
                     Tracker
                  </button>
               </h2>
               <div id="panel-service-up--1" class="accordion-collapse collapse show">
                  <div class="accordion-body">
                     <div class="row">
                        <div class="col-12 d-flex justify-content-between align-items-center">
                           <div class="link-pointer">
                              <i class="fa-solid fa-envelope-open-text fa-lg px-2" style="color: #198754" data-bs-toggle="tooltip-notify-tracker" data-bs-html="true" data-bs-title="Notificar" onclick="checkNotiUserService(${busua_cod}, 1, 5, ${busua_cod})"></i>
                           </div>
                           <div class="form-check form-switch" id="status-service-tracker-${busua_cod}" data-bs-toggle="tooltipStatusTracker" data-bs-html="true" data-bs-title="${statusdesc}">
                              <input class="form-check-input" type="checkbox" role="switch" onchange="checkUpdateStatuService(this, ${parseInt(data.esta_cod)}, ${busua_cod}, 0, 5)" ${parseInt(data.esta_cod) === 1 ? 'checked' : ''}>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-lg-3 mb-3">
                           <label for="tipo-tracker-service-${busua_cod}" class="form-label col-form-label-sm mb-1">Tipo de tracker:</label>
                           <select class="form-select form-select-sm status-form-update-tracker-${busua_cod}" id="tipo-tracker-service-${busua_cod}" name="tipo-tracker-service-${busua_cod}" title="Tipo de tracker" onchange="checkUpdateTypeTracker(this, ${busua_cod}, ${tipo_cod})">
                              <option value="1" ${tipo_cod === 1 ? 'selected' : ''}>1 - Tracker Bot&oacute;n - V&iacute;ctima</option>
                              <option value="2" ${tipo_cod === 2 ? 'selected' : ''}>2 - Tracker Bot&oacute;n - Agresor</option>
                           </select>
                        </div>
                        <div class="col-lg-3 mb-3">
                           <label for="causa-tracker-service-${busua_cod}" class="form-label">Causa:</label>
                           <input type="text" class="form-control status-form-update-tracker-${busua_cod}" id="causa-tracker-service-${busua_cod}" placeholder="Ingrese una causa" aria-describedby="causa-tracker-service-${busua_cod}-text" title="Causa del servicio de tracker" value="${data.causa}" onchange="checkUpdateCausaTracker(this, ${busua_cod}, '${data.causa}')" autocomplete="off">
                           <div id="causa-tracker-service-${busua_cod}-help" name="err-causa-tracker-service-${busua_cod}" class="form-text" hidden></div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            `;

            html.innerHTML += /* html */ `
            ${htmlaccordion}
            `;

         }

         tooltipSystem('tooltipStatusTracker');
         tooltipSystem('tooltip-notify-tracker');

         resolve(true);

      }, 300);

   });

}

// aprovisiona un nuevo servicio
const aprovisionaService = async (tipo_cod, busua_cod) => {

   const info = new FormData();
   info.append('tipo_cod', tipo_cod);
   info.append('busua_cod', busua_cod);

   if (tipo_cod === 2) {

      info.append('localizacion', document.getElementById(`localizacion-service-${busua_cod}`).value);
      info.append('mac', document.getElementById(`mac-service-${busua_cod}`).value);

   }

   if (tipo_cod === 5) {

      info.append('tipo_tracker', document.getElementById(`tipo-tracker-cod-${busua_cod}`).value);
      info.append('causa', document.getElementById(`causa-tracker-${busua_cod}`).value);

   }

   const data = await fetch(`${url_operator}/json/json_createService.php`, {
      method: 'POST',
      body: info
   });
   return data.json();
}

// manejo de formularios de servicios btn/tracker
const formServiceAdd = async (data, busua_cod) => {

   return new Promise((resolve, reject) => {

      setTimeout(() => {

         document.getElementById('title-modal-pbe').innerHTML = /* html */ 'Formularios Servicios De Emergencia';

         document.getElementById('info-modal-pbe').innerHTML = /* html */ `
         <form id="form-add-service-user-${busua_cod}" class="row">
            <div class="col-12 mb-3">
               <span class="fw-semibold fs-5">Id usuario: ${busua_cod}</span>
            </div>
            <div class="col-lg-3 mb-3">
               <select class="form-select" id="tipo-cod-service-up-${busua_cod}" aria-describedby="tipo-cod-service-up-${busua_cod}-text" title="Tipo de Servicio" onchange="showParamsRequired(this.value, ${busua_cod})">
                  <option value="0">Seleccion un tipo de servicio...</option>
               </select>
               <div id="err-tipo-cod-service-add-help" name="err-tipo-cod-service-add" class="form-text" hidden></div>
            </div>
            <div class="col-lg-3 mb-3">
               <button type="submit" class="btn buttons colors-font" id="btn-add-service-${busua_cod}" name="btn-add-service-${busua_cod}"> 
                  <div id="spinner-btn-add-service-${busua_cod}" class="spinner-border spinner-border-sm text-white" role="status" hidden>
                     <span class="visually-hidden">Loading...</span>
                  </div>
                  <span id="textbtn-btn-add-service-${busua_cod}">Ingresar nuevo servicio</span>
               </button>
            </div>
            <div id="inputs-button-${busua_cod}" class="col-12 mb-3" hidden>
               <div class="row">
                  <div class="col-lg-3">
                     <label for="localizacion-service-${busua_cod}" class="form-label">Localización:</label>
                     <input type="text" class="form-control" id="localizacion-service-${busua_cod}" minlenght="3" maxlength="120" placeholder="Ingrese una localizacion" aria-describedby="localizacion-service-${busua_cod}-text" title="Localizacion del botón para el usuario">
                     <div id="localizacion-service-${busua_cod}-help" name="err-localizacion-service-${busua_cod}" class="form-text" hidden></div>
                  </div>
                  <div class="col-lg-3">
                     <label for="mac-service-${busua_cod}" class="form-label">Mac:</label>
                     <input type="text" class="form-control" id="mac-service-${busua_cod}" pattern="^([0-9A-Fa-f]){12}$" maxlength="12" placeholder="Ingrese una mac" aria-describedby="mac-service-${busua_cod}-text" title="Mac del botón para el usuario estático" autocomplete="off" onkeyup="validateMac(event, 0)">
                     <div id="mac-service-${busua_cod}-help" name="err-mac-service-add" class="form-text" hidden></div>
                  </div>
               </div>
            </div>
            <div id="inputs-tracker-${busua_cod}" class="col-12 mb-3" hidden>
               <div class="row">
                  <div class="col-lg-3">
                     <label for="tipo-tracker-cod-${busua_cod}" class="form-label">Tipo Tracker:</label>
                     <select class="form-select" id="tipo-tracker-cod-${busua_cod}" name="tipo-tracker-cod-${busua_cod}" title="Tipo de tracker">
                        <option value="1">Tracker Botón - Víctima</option>
                        <option value="2">Tracker Botón - Agresor</option>
                     </select>
                     <div id="tipo-tracker-cod-${busua_cod}-help" name="err-tipo-tracker-cod-${busua_cod}" class="form-text" hidden></div>
                  </div>
                  <div class="col-lg-3">
                     <label for="causa-tracker-${busua_cod}" class="form-label">Causa:</label>
                     <input type="text" class="form-control" id="causa-tracker-${busua_cod}" placeholder="Ingrese una causa" minlenght="1" aria-describedby="causa-tracker-${busua_cod}-text" title="Causa de tracker" autocomplete="off">
                     <div id="causa-tracker-${busua_cod}-help" name="err-causa-tracker-${busua_cod}" class="form-text" hidden></div>
                  </div>
               </div>
            </div>
         </form>
         <hr>
         <div id="services-button-${busua_cod}" class="row">
            <div class="accordion" id="acc-button-panic-${busua_cod}"></div>
         </div>
         <div id="services-products-${busua_cod}" class="row">
            <div class="accordion" id="acc-other-products-${busua_cod}"></div>
         </div>
         <div id="services-tracker-${busua_cod}" class="row">
            <div class="accordion" id="acc-tracker-${busua_cod}"></div>
         </div>
         `;

         data.services.forEach(element => {
            document.getElementById(`tipo-cod-service-up-${busua_cod}`).innerHTML += /* html */ `
            <option value="${element[0]}">${element[1]}</option>
            `;
         });

         document.getElementById(`form-add-service-user-${busua_cod}`).addEventListener('submit', async e => {

            try {

               e.preventDefault();

               const tipo_cod = parseInt(document.getElementById(`tipo-cod-service-up-${busua_cod}`).value);

               await confMenuPreServiceUP(tipo_cod, busua_cod);

               await spinnerOpen(`btn-add-service-${busua_cod}`);

               const result = await aprovisionaService(tipo_cod, busua_cod);

               if (result.status !== 'err') {

                  switch (tipo_cod) {

                     case 1:
                        await formButton(await dataServiceButton(busua_cod), busua_cod);
                        break;
                     case 2:
                        await formButton(await dataServiceButton(busua_cod), busua_cod);
                        break;
                     case 4:
                        await formProducts(await dataOtherProducts(busua_cod), busua_cod);
                        break;
                     case 5:
                        await formTracker(await dataServiceTracker(busua_cod), busua_cod);
                        break;
                     case 6:
                        await formButton(await dataServiceButton(busua_cod), busua_cod);
                        break;

                  }

                  document.getElementById(`tipo-cod-service-up-${busua_cod}`).value = 0;
                  showParamsRequired(0, busua_cod);
                  showToastSuccess(result.message);

               } else {

                  await statusMSJ(document.getElementsByName('err-tipo-cod-service-add')[0], result.message, false, false);

               }

            } catch (error) {

               console.error(`Error: ${error}`);

            } finally {

               spinnerClose(`btn-add-service-${busua_cod}`, 'Ingresar nuevo servicio');

            }

         });

         $('#modal-pbe').modal('show');

         resolve(true);

      }, 300);

   });

}

// obtiene todos los servicios
const dataServicesAll = async () => {
   const data = await fetch(`${url_operator}/json/json_getAllServices.php`, {
      method: 'GET',
      headers: {
         'Content-Type': 'application/json'
      }
   });
   return data.json();
}

// muestra formulario de servicios
const viewFormService = async (busua_cod) => {

   try {

      await showLoadingSystem('');                                         // abre spinner

      await formServiceAdd(await dataServicesAll(), busua_cod);            // interfaz de los servicios

      await formButton(await dataServiceButton(busua_cod), busua_cod);     // interfaz de los botones

      await formProducts(await dataOtherProducts(busua_cod), busua_cod);   // interfaz de otros productos

      await formTracker(await dataServiceTracker(busua_cod), busua_cod);   // interfaz de tracker

      await Swal.close();                                                  // cierra spinner

   } catch (error) {

      console.error(`Error: ${error}`);
      await showToastError(`Error, por favor intentar de nuevo`);

   }

}

// actualiza nombre contacto
const updateNameContact = (bu, num, val_i) => {
   try {
      return new Promise((resolve, reject) => {
         setTimeout(() => {
            localStorageSave(`name_contact_oper_${num}`, val_i);
            let name = document.getElementById(`name-contact-up-${num}`);
            const data = {
               'bu': bu,
               'num': num,
               'name': name.value.trim()
            }
            fetch(`${url_operator}/json/json_updateNameContact.php`, {
               method: 'POST',
               headers: {
                  'Content-Type': 'application/json'
               },
               body: JSON.stringify(data)
            })
               .then(response => response.json())
               .then((response) => {
                  if (response.status === 'error') {
                     name.value = JSON.parse(localStorage.getItem(`name_contact_oper_${num}`));
                     showToastError('Nombre contacto <span class="text-danger">NO Actualizado</span>');
                     reject(false);
                  } else {
                     toast.fire({
                        icon: 'success',
                        title: 'Nombre contacto <span class="text-primary">Actualizado</span>'
                     });
                     localStorage.removeItem(`name_contact_oper_${num}`);
                     localStorage.setItem(`name_contact_oper_${num}`, JSON.stringify(name.value.trim()));
                     resolve(true);
                  }
               })
               .catch((error) => {
                  name.value = JSON.parse(localStorage.getItem(`name_contact_oper_${num}`));
                  console.error(`Error: ${error}`);
                  reject(`Error: ${error}`);
               });
         });
      }, 300);
   } catch (error) {
      console.error(`Error: ${error}`);
   }
}

// agrega/elimina contacto de emergencia llamdas
const updateContactCall = (bu, num) => {
   try {
      return new Promise((resolve, reject) => {
         setTimeout(() => {
            let checklisten = document.getElementById(`check-listen-up-${num}`);
            let checkcall = document.getElementById(`check-call-up-${num}`);
            const data = {
               'bu': bu,
               'num': num,
               'check': checkcall.checked
            }
            fetch(`${url_operator}/json/json_updateContactCall.php`, {
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
                     showToastError(response.message);
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
                  console.error(`Error: ${error}`);
                  reject(`Error: ${error}`);
               });
         });
      }, 300);
   } catch (error) {
      console.error(`Error: ${error}`);
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
               'check': check.checked
            }
            fetch(`${url_operator}/json/json_updateContactSMS.php`, {
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
                     showToastError(response.message)
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
               .catch((error) => {
                  console.error(`Error: ${error}`);
                  reject(`Error: ${error}`);
               });
         });
      }, 300);
   } catch (error) {
      console.error(`Error: ${error}`);
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
               'check': checklisten.checked
            }
            fetch(`${url_operator}/json/json_updateListenCall.php`, {
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
                     showToastError(response.message);
                     reject(false);
                  } else {
                     if (checklisten.checked === true) {
                        checklisten.checked = true;
                     } else {
                        checklisten.checked = false;
                     }
                     toast.fire({
                        icon: 'success',
                        title: `Escucha emergencia <span class="text-primary">${(checklisten.checked === true ? 'Activado' : 'Desactivado')}</span>`
                     });
                     resolve(true);
                  }
               })
               .catch((error) => {
                  if (checklisten.checked === true) {
                     checklisten.checked = false;
                  } else {
                     checklisten.checked = true;
                  }
                  console.error(`Error: ${error}`);
                  reject(`Error: ${error}`);
               });
         });
      }, 300);
   } catch (error) {
      console.error(`Error: ${error}`);
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
                     'num': num
                  }
                  fetch(`${url_operator}/json/json_deleteContact.php`, {
                     method: 'POST',
                     headers: {
                        'Content-Type': 'application/json'
                     },
                     body: JSON.stringify(data)
                  })
                     .then(response => response.json())
                     .then((response) => {
                        if (response.status === 'error') {
                           showToastError(response.message);
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
                     .catch((error) => {
                        console.error(`Error: ${error}`);
                        reject(`Error: ${error}`);
                     });
               }
            });
         });
      }, 300);
   } catch (error) {
      console.error(`Error: ${error}`);
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
                     'checksms': checksms.checked
                  }
                  fetch(`${url_operator}/json/json_createContact.php`, {
                     method: 'POST',
                     headers: {
                        'Content-Type': 'application/json'
                     },
                     body: JSON.stringify(data)
                  })
                     .then(response => response.json())
                     .then((response) => {
                        if (response.status === 'error') {
                           showToastError(response.message);
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
                                 <input type="text" class="form-control pfont-size bg-warning-subtle link-pointer" id="name-contact-up-${num}" name="name-contact-up-${num}" value="${(new String(name).toString()) === 'null' ? '' : name}" title="Nombre del contacto" onchange="updateNameContact(${bu}, ${num}, '${(new String(name).toString())}')" autocomplete="off">
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
                              <td class="pfont-size p-1 pb-0">
                                 <div class="form-check d-flex justify-content-center">
                                    <input class="form-check-input link-pointer ${checkcall.checked === false ? 'bg-secondary' : ''}" type="checkbox" id="check-listen-up-${num}" ${checkcall.checked === false ? 'disabled' : ''} onchange="updateListenCall(${bu}, ${num})">
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
                           localStorage.removeItem('name_contact_oper_' + num);
                           resolve(true);
                        }
                     })
                     .catch((error) => {
                        console.error(`Error: ${error}`);
                        reject(`Error: ${error}`);
                     });
               }
            });
         }, 300)
      });
   } catch (error) {
      console.error(`Error: ${error}`);
      reject(`Error: ${error}`);
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
            fetch(`${url_operator}/json/json_getContactsEmergencyUser.php?busua_cod=${busua_cod}`, {
               method: 'GET',
               headers: {
                  'Content-Type': 'application/json'
               }
            })
               .then(response => response.json())
               .then((response) => {
                  contact.innerHTML += /* html */ `
                  ${p1 === 2 ? `
                  <form id="form-emergency-${busua_cod}" class="row">
                     <div class="col-12 mb-2 px-1">
                        <h5>Ingreso</h5>
                     </div>
                     <div class="col-12 mb-2 px-1" id="div-name-contact-${busua_cod}">
                        <input type="text" class="form-control p-1 m-0 pfont-size-input" id="name-contact-add-${busua_cod}" name="name-contact-add-${busua_cod}" placeholder="Ingrese un nombre" title="Nombre para el Contacto">
                        <div id="name-contact-add-${busua_cod}-help" name="err-name-contact-add-${busua_cod}" class="form-text fw-light" hidden></div>
                     </div>
                     <div class="col-12 mb-2 px-1">
                        <div class="input-group mb-3">
                           <span class="input-group-text pfont-size-input p-1" id="num-contact-add-addon-${busua_cod}">56</span>
                           <input type="text" class="form-control p-1 m-0 pfont-size-input" id="num-contact-add-${busua_cod}" name="num-contact-add-${busua_cod}" placeholder="(*) Ingrese un n&uacute;mero - Ej: 936147801" title="N&uacute;mero para el Contacto" onkeydown="onlyNumbers(event, ${busua_cod})" onkeyup="largeInput(event, ${busua_cod})">
                        </div>
                        <div id="num-contact-add-${busua_cod}-help" name="err-num-contact-add-${busua_cod}" class="form-text fw-light" hidden></div>
                     </div>
                     <div class="col-12 mb-2 px-1 d-flex justify-content-start">
                        <div class="form-check mx-3">
                           <input class="form-check-input link-pointer" type="checkbox" id="check-call-add-${busua_cod}" checked>
                           <label class="form-check-label pfont-size-input link-pointer" for="check-call-add-${busua_cod}">
                              Llamadas
                           </label>
                        </div>
                        <div class="form-check mx-3">
                           <input class="form-check-input link-pointer" type="checkbox" id="check-sms-add-${busua_cod}" checked>
                           <label class="form-check-label pfont-size-input link-pointer" for="check-sms-add-${busua_cod}">
                              SMS
                           </label>
                        </div>
                     </div>
                     <div class="col-12 mb-2 px-1 d-flex justify-content-start">
                        <div id="check-add-${busua_cod}-help" name="err-check-add-${busua_cod}" class="form-text fw-light w-100" hidden></div>
                     </div>
                     <div class="col-12 d-flex justify-content-center mb-2 px-1">
                        <button type="submit" id="btn-add-contact-${busua_cod}" class="btn btn-sm buttons disabledp colors-font pfont-size-input w-100">Ingresar</button>
                     </div>
                  </form>
                  <hr>
                  ` : ``}
                  <div class="row p-0 mb-2">
                     <div class="col-12 p-0 m-0">
                        <table id="data-table-contacts-${busua_cod}" class="table table-bordered m-0">
                           <thead>
                              <tr>
                                 <th class="background-all-1 color-font pfont-size p-1">Nombre</th>
                                 <th class="background-all-1 color-font pfont-size p-1">N&uacute;mero</th>
                                 <th class="background-all-1 color-font pfont-size p-1 text-center"><i class="fa-solid fa-phone"></i></th>
                                 <th class="background-all-1 color-font pfont-size p-1 text-center"><i class="fa-solid fa-comment-sms"></i></th>
                                 <th class="background-all-1 color-font pfont-size p-1 text-center"><i class="fa-solid fa-ear-listen"></i></th>
                                 ${p1 === 2 ? `
                                 <th class="background-all-1 color-font pfont-size p-1 text-center" width="10">
                                    Eliminar
                                 </th>
                                 ` : ''}
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
                              <td class="text-start pfont-size p-0">
                                 <input type="text" class="form-control pfont-size ${p1 === 2 ? `bg-warning-subtle link-pointer` : ''}" id="name-contact-up-${contact.numero}" name="name-contact-up-${contact.numero}" value="${(new String(contact.nombre).toString()) === 'null' ? '' : contact.nombre}" title="Nombre del contacto" autocomplete="off" ${p1 === 2 ? `onchange="updateNameContact(${response.busua_cod}, ${contact.numero}, '${(new String(contact.nombre).toString())}')"` : 'disabled'}>
                              </td>
                              <td class="text-start pfont-size p-1 pb-0 text-start">${contact.numero}</td>
                              <td class="text-end pfont-size p-1 pb-0">
                                 <div class="form-check d-flex justify-content-center">
                                    <input class="form-check-input link-pointer" type="checkbox" id="check-call-up-${contact.numero}" ${contact.statuscall === false ? '' : contact.esta_cod_call == 1 ? 'checked' : ''} ${p1 === 2 ? `onchange="updateContactCall(${busua_cod}, ${contact.numero})"` : 'disabled'}>
                                 </div>
                              </td>
                              <td class="pfont-size p-1 pb-0">
                                 <div class="form-check d-flex justify-content-center">
                                    <input class="form-check-input link-pointer" type="checkbox" id="check-sms-up-${contact.numero}" ${contact.statusSMS === false ? '' : contact.esta_cod_sms == 1 ? 'checked' : ''}  ${p1 === 2 ? `onchange="updateContactSMS(${busua_cod}, ${contact.numero})"` : 'disabled'}>
                                 </div>
                              </td>
                              <td class="pfont-size p-1 pb-0">
                                 <div class="form-check d-flex justify-content-center">
                                    <input class="form-check-input ${contact.statuscall === false ? '' : contact.esta_cod_call == 1 ? '' : 'bg-secondary'}" type="checkbox" id="check-listen-up-${contact.numero}" ${contact.statuscall === false ? '' : contact.esta_cod_call == 1 ? contact.listen_call == 1 ? 'checked' : '' : 'disabled'} ${p1 === 2 ? `onchange="updateListenCall(${response.busua_cod}, ${contact.numero})"` : 'disabled'}>
                                 </div>
                              </td>
                              ${p1 === 2 ? `
                              <td class="text-center text-danger p-1 pb-0">
                                 <i class="fa-solid fa-trash link-pointer" id="delete-contact-${contact.numero}" onclick="deleteContact(${busua_cod}, ${contact.numero})"></i>
                              </td>   
                              ` : ''}
                           </tr>
                           `;
                        });
                        return contacts;
                     }
                     body.innerHTML = /* html */ `${dataContacts()}`;
                  }
                  if (p1 === 2) {
                     document.getElementById('form-emergency-' + busua_cod).addEventListener('submit', e => {
                        e.preventDefault();
                        createContact(busua_cod);
                     });
                  }
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

// muestra formulario de servicios
const viewFormContactE = async (busua_cod) => {
   let title = document.getElementById('infoGeneralLabel');
   let cloud_username = document.querySelectorAll('#user-service-' + busua_cod + ' td')[2].innerHTML;
   // limpiamos
   title.innerHTML = '';
   title.innerHTML += /* html */ `
   <i class="fa-solid fa-tower-broadcast mx-3 colors-font" title="icon emergency contacts"></i>
   <h4 class="offcanvas-title colors-font">Contactos de emergencia</h4>
   `;
   document.getElementById('data-result-adm-service').innerHTML = /* html */ `
   <div id="username-bp" class="row m-2 p-2 rounded border">
      <div class="col-12">
         <span class="fs-5">Usuario: <strong>${cloud_username}</strong></span>
      </div>
   </div>
   <hr class="mx-2">
   <div class="row m-2 p-2 rounded border">
      <div id="data-contact-emergency" class="col-12"></div>
   </div>
   `;
   await showLoadingSystem('<span class="fs-4">Obteniendo contactos...</span>');
   await formContactEmergency(busua_cod);
   await openOffCanvas('infoGeneral');
   await Swal.close();
}

// historia
const dataHistory = async (bu) => {
   try {
      return fetch(`${url_operator}/json/json_getHistory.php?bu=${bu}`, {
         method: 'GET',
         headers: {
            'Content-Type': 'application/json'
         }
      })
         .then(response => response.json())
         .then(response => {
            const dataLog = response.dataLog;
            const dataAlert = response.dataAlert;
            return { dataLog, dataAlert }
         })
         .catch(error => error);
   } catch (error) {
      console.error(`Error: ${error}`);
      await Swal.close();
   }
}

// configuracion del menu historia
const configFormHistory = async (dataLog, dataAlert, bu) => {
   try {
      return new Promise((resolve, reject) => {
         setTimeout(() => {
            document.getElementById('data-result-adm-service').innerHTML = /* html */ `
            <div id="data-result-service-history"></div>
            `;
            let title = document.getElementById('infoGeneralLabel');
            const cloud_username = document.querySelectorAll(`#user-service-${bu} td`)[2].innerHTML;
            // limpiamos
            title.innerHTML = '';
            title.innerHTML += /* html */ `
            <i class="fa-solid fa-circle-exclamation mx-3 colors-font" title="icon emergency history"></i>
            <h4 class="offcanvas-title colors-font">Historial usuario</h4>
            `;
            let infoLog = '';
            let infoAlert = '';
            if (dataLog.length === 0) {
               infoLog += `
               <tr>
                  <td colspan="3" class="text-center">No registra sesiones</td>
               </tr>`;
            }
            dataLog.forEach((log) => {
               let col = log.coordenadas.split(';')
               infoLog += `
               <tr>
                  <td>${log.fecha}</td>
                  <td class="text-center"><a href="${url_map}mlat=${col[0]}&mlon=${col[1]}&zoom=20" target="_blank">Mapa</a></td>
                  <td>${log.plataforma}</td>
               </tr>`
            });
            if (dataAlert.length === 0) {
               infoAlert += `
               <tr>
                  <td colspan="3" class="text-center">No registra alertas</td>
               </tr>`;
            }
            dataAlert.forEach((alert) => {
               let coa = alert.posicion.split(';')
               infoAlert += `
               <tr>
                  <td>${alert.fecha_creacion}</td>
                  <td class="text-center"><a href="${url_map}mlat=${coa[0]}&mlon=${coa[1]}&zoom=20" target="_blank">Mapa</a></td>
                  <td>${alert.activa_desc}</td>
               </tr>`
            });
            document.getElementById('data-result-service-history').innerHTML = /* html */ `
            <div id="username-bp" class="row m-2 p-2 rounded border">
               <div class="col-12">
                  <span class="fs-5">Usuario: <strong>${cloud_username}</strong></span>
               </div>
            </div>
            <hr class="mx-2">
            <div class="row m-2 p-2 rounded border">
               <div class="col-12 mb-1">
                  <span class="fs-5">Historial de sesiones (&Uacute;ltimas 5)</span>
               </div>
               <div class="col-12 mb-3">
                  <table id="info-sessions" class="table table-bordered m-0">
                     <thead>
                        <tr>
                           <th class="background-all-1 color-font pfont-size p-1">Fecha</th>
                           <th class="background-all-1 color-font pfont-size p-1">Coordenadas</th>
                           <th class="background-all-1 color-font pfont-size p-1">Plataforma</th>
                        </tr>
                     </thead>
                     <tbody>
                        ${infoLog}
                     </tbody>
                  </table>
               </div>
               <hr>
               <div class="col-12 mb-1">
                  <span class="fs-5">Historial de alertas (&Uacute;ltimas 5)</span>
               </div>
               <div class="col-12 mb-3">
                  <table id="info-alerts" class="table table-bordered m-0">
                     <thead>
                        <tr>
                           <th class="background-all-1 color-font pfont-size p-1">Fecha</th>
                           <th class="background-all-1 color-font pfont-size p-1">Coordenadas</th>
                           <th class="background-all-1 color-font pfont-size p-1">Estado</th>
                        </tr>
                     </thead>
                     <tbody>
                        ${infoAlert}
                     </tbody>
                  </table>
               </div>
            </div>
            `;
            resolve(true);
         }, 300);
      });
   } catch (error) {
      console.error(`Error: ${error}`);
      await Swal.close();
   }
}

// historia del usuario
const viewDataHistory = async (bu) => {
   try {
      await showLoadingSystem('<span class="fs-4">Obteniendo servicios...</span>');
      const { dataLog, dataAlert } = await dataHistory(bu);
      await configFormHistory(dataLog, dataAlert, bu);
      await openOffCanvas('infoGeneral');
      await Swal.close();
   } catch (error) {
      showToastError(error_system);
      console.error(`Error: ${error}`);
      await Swal.close();
   }
}

// notifica servicio
const notiUserService = async (busua_cod, dom_cod, tipo_cod) => {
   const data = new FormData();
   data.append('busua_cod', busua_cod);
   data.append('dom_cod', dom_cod);
   data.append('tipo_cod', tipo_cod);
   const res = await fetch(`${url_operator}/json/json_notificaUser.php`, {
      method: 'POST',
      body: data
   });
   return res.json();
}

// elimina usuarios masivamente
const deleteMasive = async (users) => {
   const info = {
      'users': users
   }
   const response = await fetch(`${url_operator}/json/json_deleteMasive.php`, {
      method: 'POST',
      headers: {
         'Content-Type': 'application/json'
      },
      body: JSON.stringify(info)
   });
   return response.json();
}

// trae el detalle de los usuarios
const getUsers = async (group_cod, dom_cod) => {
   const res = await fetch(`${url_operator}/json/json_getUsersGroups.php?group_cod=${group_cod === null ? 0 : group_cod}&dom_cod=${dom_cod}`, {
      method: 'GET',
      headers: {
         'Content-Type': 'application/json'
      }
   });
   return res.json();
}

// usuarios seleccionados
const userSelectDelete = async (table) => {

   return new Promise((resolve, reject) => {

      setTimeout(() => {

         let users = [];
         table.$(`input[name="checksDeleteUsersPBE"]`).each(function () {
            if (this.checked) {
               users.push(this.value);
            }
         });

         if (users.length === 0) {

            showToastError('Debe seleccionar uno o mas usuarios');
            reject('Debe seleccionar uno o mas usuarios');

         } else {

            resolve(users);

         }

      }, 300);

   });

}

// trae el detalle de los usuarios
const menuUsers = async (group_cod = 0) => {

   const spinner = document.getElementById('spinner-load-group');
   const result = document.getElementById('info-users');
   const dom_cod = document.getElementById('dom_cod').value;

   localStorage.removeItem('group_cod_oper_' + dom_cod);
   localStorage.setItem('group_cod_oper_' + dom_cod, group_cod === null ? 0 : group_cod);

   if (spinner !== null) {
      spinner.hidden = false;
   }

   result.innerHTML = '';

   const response = await getUsers(group_cod, dom_cod);

   if (response.status === 'error') {

      await showToastError(response.message);

   } else {

      const infoUser = () => {               // recorre la data de los usuarios

         let user = '';

         response.users.data.forEach(element => {

            let statusUser = '';

            switch (element.esta_cod) {
               case '1':
                  statusUser = 'text-success';
                  break;
               case '2':
                  statusUser = 'text-warning';
                  break;
               default:
                  statusUser = 'text-danger';
                  break;
            }

            user += `
            <tr id="user-service-${element.busua_cod}">
               <td class="text-end">${element.busua_cod}</td>
               <td>${element.nombre === null ? 'No registra' : element.nombre}</td>
               <td>${element.cloud_username}@${response.dominio_usuario}</td>
               <td class="text-center fs-5 ${statusUser}">${element.estado}</td>
               <td>${element.email === null ? '' : element.email}</td>
               <td class="text-center">
                  <button type="button" class="btn btn-sm mb-1 btn-info p-2" id="edit-user-service-${element.busua_cod}" onclick="viewFormEdit(${element.busua_cod})" data-bs-toggle="tooltipInfoUsers" data-bs-html="true" data-bs-title="Editar Usuario" title="Editar Usuario">
                     <i class="fa fa-user" title="icon users"></i>
                  </button>
                  <button type="button" class="btn btn-sm mb-1 btn-warning p-2" id="edit-data-btn-panic-${element.busua_cod}" onclick="viewFormService(${element.busua_cod})" data-bs-toggle="tooltipInfoUsers" data-bs-html="true" data-bs-title="Editar Servicios" title="Editar Servicios">
                     <i class="fa-solid fa-align-justify" title="icon edit service"></i>
                  </button>
                  <button type="button" class="btn btn-sm mb-1 btn-primary p-2" id="edit-data-contact-emergency-${element.busua_cod}" onclick="viewFormContactE(${element.busua_cod})" data-bs-toggle="tooltipInfoUsers" data-bs-html="true" data-bs-title="Contactos de Emergencia" title="Contactos de Emergencia">
                     <i class="fa-solid fa-tower-broadcast" title="icon emergency contacts"></i>
                  </button>
                  <button type="button" class="btn btn-sm mb-1 btn-danger p-2" id="history-userpbe-${element.busua_cod}" onclick="viewDataHistory(${element.busua_cod})" data-bs-toggle="tooltipInfoUsers" data-bs-html="true" data-bs-title="Historia del usuario" title="Historia del usuario">
                     <i class="fa-solid fa-circle-exclamation" title="icon history"></i>
                  </button>
               </td>
               ${p1 === 2 ? `
               <td>
                  <div class="d-flex align-items-center justify-content-around">
                     <input type="checkbox" id="chek-delete-user-${element.busua_cod}" name="checksDeleteUsersPBE" value="${element.busua_cod}" aria-describedby="check" title="Check usuario ${element.cloud_username}@${response.dominio_usuario}">
                     <img class="link-pointer" src="${url_img}/icon-cross-sm-delete.png" id="delete-user-${element.busua_cod}" onclick="chekDeleteUserService(${element.busua_cod}, 1)" title="Elimina usuario">
                  </div>
               </td>
               ` : ``}
            </tr>
            `;
         });

         return user;

      }

      result.innerHTML += /* html */ `
      <div class="col-12 table-responsive">
         <table id="table-info-users-service-${response.group_cod === null ? response.dom_cod : response.group_cod}" name="table-info-users-service" class="table align-middle py-2 m-0 ${response.p_update === 2 ? 'tabla-primary' : ''}">
            <thead>
               <tr class="bg-primary-subtle">
                  <th class="fw-bold">Id</th>
                  <th class="fw-bold">Nombre</th>
                  <th class="fw-bold">Cloud Username</th>
                  <th class="fw-bold">Estado</th>
                  <th class="fw-bold">Correo</th>
                  <th class="fw-bold px-5">Formularios</th>
                  ${p1 === 2 ? `
                  <th>
                     <div class="d-flex align-items-center justify-content-around">
                        <input type="checkbox" class="mx-1" id="select-all-users-pbe-${response.group_cod === null ? response.dom_cod : response.group_cod}" name="select-all-users-pbe-${response.group_cod === null ? response.dom_cod : response.group_cod}" aria-describedby="check masivo" title="check masivo">
                        <button type="button" class="btn btn-sm btn-danger mx-1" id="btn-delete-masivo-${response.group_cod === null ? response.dom_cod : response.group_cod}" name="btn-delete-masivo-${response.group_cod === null ? response.dom_cod : response.group_cod}"> 
                           <div id="spinner-btn-delete-masivo-${response.group_cod === null ? response.dom_cod : response.group_cod}" class="spinner-border spinner-border-sm text-white" role="status" hidden>
                              <span class="visually-hidden">Loading...</span>
                           </div>
                           <span id="textbtn-btn-delete-masivo-${response.group_cod === null ? response.dom_cod : response.group_cod}">Eliminar</span>
                        </button>
                     </div>
                  </th>   
                  ` : ``}
               </tr>
            </thead>
            <tbody class="table-group-divider">
               ${response.status_users === 'si' ? infoUser() : ``}
            </tbody>
         </table>
      </div>
      `;

      const table = $(`#table-info-users-service-${response.group_cod === null ? response.dom_cod : response.group_cod}`).DataTable({
         language: {
            'decimal': '',
            'emptyTable': 'No hay información',
            'info': 'Mostrando _START_ a _END_ de _TOTAL_ Entradas',
            'infoEmpty': 'Mostrando 0 to 0 of 0 Entradas',
            'infoFiltered': '(Filtrado de _MAX_ total entradas)',
            'infoPostFix': '',
            'thousands': ',',
            'lengthMenu': 'Mostrar _MENU_ Entradas',
            'loadingRecords': 'Cargando...',
            'processing': 'Procesando...',
            'search': 'Buscar:',
            'zeroRecords': 'Sin resultados encontrados',
            'paginate': {
               'first': 'Primero',
               'last': 'Ultimo',
               'next': 'Siguiente',
               'previous': 'Anterior'
            }
         },
         lengthMenu: [[10, 50, 100, -1], [10, 50, 100, 'Todo']],
         pageLength: -1
      });

      if (p1 === 2) {

         document.getElementById(`select-all-users-pbe-${response.group_cod === null ? response.dom_cod : response.group_cod}`).addEventListener('click', (e) => {

            if (response.status_users === 'si') {
               document.getElementsByName('checksDeleteUsersPBE').forEach(check => (e.target.checked === true ? check.checked = true : check.checked = false));
            }

         });

         // para eliminacion masiva
         document.getElementById(`btn-delete-masivo-${response.group_cod === null ? response.dom_cod : response.group_cod}`).addEventListener('click', async (e) => {

            try {

               const users = await userSelectDelete(table);

               const question = await questionSweetAlert(`¿Esta seguro que desea eliminar a los <span class="text-primary fw-bold">Usuarios</span> seleccionados, tambi&eacute;n se eliminar&aacute;n todos los servicios asociados?`);

               if (question.isConfirmed) {

                  const res = await deleteMasive(users);

                  if (res.status === 'error') {

                     await showToastError(res.message);

                  } else {

                     users.forEach(user => {
                        table.row(`#user-service-${user}`).remove().draw();
                     });

                     await showToastSuccess(res.message);
                  }

               }

            } catch (error) {

               console.error(`Error: ${error}`);

            }

         });
      }
   }

   if (spinner !== null) {
      spinner.hidden = true;

   }

}

// trae informacion de los contact center
const dataContactCenter = async (usua_cod) => {
   const res = await fetch(`${url_operator}/json/json_getGroups.php?usua_cod=${usua_cod}`, {
      method: 'GET',
      headers: {
         'Content-Type': 'application/json'
      }
   });
   return res.json();
}

// cambia el nombre de un contact center
const updateNameContactCenter = (input, val_i, group_cod) => {
   try {
      localStorageSave('name_group_oper_' + group_cod, val_i);
      setTimeout(() => {
         if (input.value.trim().length < 1) {
            input.value = JSON.parse(localStorage.getItem('name_group_oper_' + group_cod));
            showError('Nombre del Contact Center no puede ser vac&iacute;o');
            return false;
         }
         questionSweetAlert('¿Estas seguro de modificar el <span class="text-primary">Nombre</span> del Contact Center?').then((result) => {
            if (result.isConfirmed) {
               const data = {
                  'group_cod': group_cod,
                  'nombre': input.value.trim()
               }
               fetch(`${url_operator}/json/json_updateNameContactCenter.php`, {
                  method: 'POST',
                  headers: {
                     'Content-Type': 'application/json'
                  },
                  body: JSON.stringify(data)
               })
                  .then(response => response.json())
                  .then((response) => {
                     if (response.status === 'error') {
                        input.value = JSON.parse(localStorage.getItem('name_group_oper_' + group_cod));
                        showToastError(response.message);
                     } else {
                        // actualizamos el input y el placeholder de los numeros
                        input.value = response.nombre;
                        localStorage.removeItem('name_group_oper_' + group_cod);
                        localStorage.setItem('name_group_oper_' + group_cod, JSON.stringify(response.nombre));
                        toast.fire({
                           icon: 'success',
                           title: response.message
                        });
                     }
                  })
                  .catch((error) => {
                     console.error(`Error: ${error}`);
                  });
            }
         });
      }, 300);
   } catch (error) {
      console.error(`Error: ${error}`);
      showError(error_system);
   }
}

// elimina un grupo
const deleteContactCenter = (group_cod, op = 0) => {
   try {
      setTimeout(() => {
         if (op === 0) {
            document.getElementById(`accordion-${group_cod}`).remove();
         } else {
            let groups = document.getElementsByName('accordions-groups');
            if (groups.length === 1) {
               showToastError('No puede quedar sin un contact center');
               return false;
            }
            questionSweetAlert(`
               <span class="fs-5">
                  ¿Esta seguro de eliminar el Contact Center <span class="text-primary">${document.getElementById(`name-group-${group_cod}`).value}</span>?
               </span>`).then((result) => {
               if (result.isConfirmed) {
                  const data = {
                     'group_cod': group_cod
                  }
                  fetch(`${url_operator}/json/json_deleteGroup.php`, {
                     method: 'POST',
                     headers: {
                        'Content-Type': 'application/json'
                     },
                     body: JSON.stringify(data)
                  })
                     .then(response => response.json())
                     .then((response) => {
                        if (response.status === 'error') {
                           showToastError(response.message);
                        } else {
                           document.getElementById('accordion-' + group_cod).remove();
                           toast.fire({
                              icon: 'success',
                              title: response.message
                           });
                        }
                     })
                     .catch((error) => {
                        console.error(`Error: ${error}`);
                     });
               }
            });
         }
      }, 100);
   } catch (error) {
      console.error(`Error: ${error}`);
      showError(error_system);
   }
}

// colsulta informacion de los contact centers
const menuContactCenter = async (data, dom_cod) => {

   return new Promise((resolve, reject) => {

      setTimeout(() => {

         const div_contact_centers = document.getElementById('info-contact-center');

         div_contact_centers.innerHTML = '';

         if (data.status === 'error') {

            div_contact_centers.innerHTML += /* html */ `
            <div class="col-12 p-3 border rounded-1 bg-white">
               <span class="fs-6 text-primary">${data.message}</span>
            </div>
            `;

         } else {

            data.groups.forEach(contact => {

               let num = '';
               let arr_num = contact.numeros.split(';');

               arr_num.forEach(contactnum => {

                  if (contactnum !== '') {
                     num += `
                     <tr id="num-group-${contact.group_cod}-${contactnum}">
                        <td class="text-end">${contactnum}</td>
                     </tr>`;
                  }

               });

               div_contact_centers.innerHTML += /* html */ `
               <div class="accordion col-lg-4 mb-3" id="accordion-${contact.group_cod}" name="accordions-groups">
                  <div class="accordion-item show">
                     <div class="accordion-header d-flex justify-content-between">
                        ${p1 === 2 ? `
                        <div class="col-1 d-flex justify-content-center align-items-center">
                           <img class="link-pointer" src="${url_img}/icon-trash-delete.png" width="15" height="15" id="delete-group-${contact.group_cod}" name="delete-group-${contact.group_cod}" data-bs-toggle="tooltipGroup" data-bs-html="true" data-bs-title="Eliminar Contact Center" title="Eliminar Contact Center" onclick="deleteContactCenter(${contact.group_cod}, 1)">
                        </div>   
                        ` : ``}
                        <div class="col-10">
                           <input type="text" class="form-control form-control-md" id="name-group-${contact.group_cod}" name="name-group-${contact.group_cod}" title="Nombre del Contact Center" value="${contact.nombre}" autocomplete="off" ${p1 === 2 ? `onchange="updateNameContactCenter(this, '${contact.nombre}', ${contact.group_cod})"` : 'disabled'}>
                        </div>
                        <button class="accordion-button p-2" type="button" data-bs-toggle="collapse" data-bs-target="#group_accordion_item_${contact.group_cod}" aria-expanded="true" aria-controls="group_accordion_item_${contact.group_cod}" title="Expande Informaci&oacute;n"></button>
                     </div>
                     <div id="group_accordion_item_${contact.group_cod}" class="accordion-collapse collapse show" data-bs-parent="#accordion-${contact.group_cod}">
                        <div class="row">
                           <div class="col-12 table-responsive">
                              <table id="nums-group-table-${contact.group_cod}" class="w-100 m-0 border-bottom-0">
                                 <thead>
                                    <tr class="bg-warning-subtle">
                                       <th>N&uacute;mero</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    ${num}
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               `;

            });

            const select = document.getElementById('select-group');

            select.innerHTML = '<option value="0">Todos los Contact Center</option>';

            data.groups.forEach(element => {

               select.innerHTML += /* html */ `
               <option value="${element.group_cod}" ${element.group_cod == JSON.parse(localStorage.getItem(`group_cod_oper_${dom_cod}`)) ? 'selected' : ''}>${element.nombre}</option>
               `;

            });

            // usamos niceSelect para que puedan filtrar
            $('#select-group').select2();

         }

         resolve(true);

      }, 300);

   });

}

// configuracion del menu adm
const confAdm = async (dom_cod) => {

   return new Promise((resolve, reject) => {

      setTimeout(() => {

         // grupo
         let groudp_cod_adm = JSON.parse(localStorage.getItem('group_cod_oper_' + dom_cod)) == null ? 0 : JSON.parse(localStorage.getItem('group_cod_oper_' + dom_cod));

         if (dom_cod == null || dom_cod == '' || dom_cod == undefined || dom_cod.leght == 0) {
            if (dom_cod == null || dom_cod == '' || dom_cod == undefined || dom_cod.leght == 0) {

               dom_cod = 0;

            } else {

               dom_cod = dom_cod;
               localStorage.removeItem('group_cod_oper_' + dom_cod);

               localStorage.setItem('group_cod_oper_' + dom_cod, groudp_cod_adm);
            }

         } else {

            localStorage.removeItem('group_cod_oper_' + dom_cod);
            localStorage.setItem('group_cod_oper_' + dom_cod, groudp_cod_adm);

         }

         resolve(true);

      }, 300);

   });
}

// primera configuracion
const conf_menu = async (dom_cod) => {

   return new Promise((resolve, reject) => {

      setTimeout(() => {

         const root = document.getElementById('root');              // info menu
         const links = document.getElementsByName('links');         // links

         links.forEach(link => {
            link.classList.remove('main-menu__link_select');      // borra la clase que tiene seleccionado el menu
         });

         root.innerHTML = '';

         root.innerHTML += /* html */ `
         <section id="section-adm-${dom_cod}">
            <div class="container-fluid">
               <div class="row">
                  <div class="col-12 d-flex align-items-center justify-content-between mb-1">
                     <div>
                        <span id="title-adm-menu" class="title-menu-adm"></span>
                        <div id="spinner-menu-title" class="spinner-border spinner-border spinner-border-sm ms-3" role="status" hidden>
                           <span class="visually-hidden">Loading...</span>
                        </div>
                     </div>
                     <div class="d-flex">
                        <div class="px-1">
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
                                    <li><a class="dropdown-item link-pointer" onclick="main(7)">Datos personales</a></li>
                                    <li><a class="dropdown-item link-pointer" onclick="main(8)">Cambiar contrase&ntilde;a</a></li>
                                    <li><a class="dropdown-item link-pointer" onclick="main(9)">Cambiar email</a></li>
                                 </ul>
                              </div>
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

}

// trae informacion de los contact center
const verifyNameContactCenter = async (dom_cod, name_group) => {
   try {
      return fetch(`${url_operator}/json/json_verifyNameGroup.php?dom_cod=${dom_cod}&nombre=${name_group}`, {
         method: 'GET',
         headers: {
            'Content-Type': 'application/json'
         }
      })
         .catch(error => console.error(`Error: ${error}`))
         .then(response => response.json());
   } catch (error) {
      console.error(`Error: ${error}`);
      showError(error_system);
   }
}

// nombre del grupo
const deleteNumGroup = (id_table, num_group, op = 0) => {
   try {
      setTimeout(() => {
         let tbodyRow = op == 0 ? document.querySelector('#nums-group-table-' + id_table + ' tbody') : document.querySelectorAll('#nums-group-table-' + id_table + ' tbody tr');
         if (op === 0) {
            tbodyRow.querySelectorAll('tr').forEach(element => {
               element.id === `num-group-${id_table}-${num_group}` ? element.remove() : ''
            });
            if (tbodyRow.querySelectorAll('tr').length === 0) {
               tbodyRow.innerHTML += /* html */ `
               <tr id="sin-info-nums-${id_table}">
                  <td class="text-center" colspan="2">Sin números agregados</td>
               </tr>
               `;
            }
         } else {
            if (tbodyRow.length === 1) {
               if (tbodyRow[0].id !== 'sin-info-nums-' + id_table) {
                  showToastError('Contact Center no puede quedar sin <span class="text-primary">N&uacute;meros</span>');
                  return false;
               }
            }
            questionSweetAlert(`¿Estas seguro de eliminar el n&uacute;mero <span class="text-primary">${num_group}</span> al Contact Center?`).then((result) => {
               if (result.isConfirmed) {
                  let numeros = '';
                  tbodyRow.forEach(element => {
                     if (num_group !== element.querySelectorAll('td')[0].innerHTML) {
                        numeros += `${element.querySelectorAll('td')[0].innerHTML};`;
                     }
                  });
                  const data = {
                     'group_cod': id_table,
                     'numeros': numeros,
                     'num_group': num_group,
                     'evento': 'DELETE'
                  }
                  fetch(`${url_operator}/json/json_createNumGroup.php`, {
                     method: 'POST',
                     headers: {
                        'Content-Type': 'application/json'
                     },
                     body: JSON.stringify(data)
                  })
                     .then(response => response.json())
                     .then((response) => {
                        if (response.status === 'error') {
                           showToastError(response.message);
                        } else {
                           toast.fire({
                              icon: 'success',
                              title: response.message
                           });
                           tbodyRow.forEach(element => {
                              element.id === `num-group-${id_table}-${num_group}` ? element.remove() : ''
                           });
                        }
                     })
                     .catch((error) => {
                        console.error(`Error: ${error}`);
                     });
               }
            });
         }
      }, 100);
   } catch (error) {
      console.error(error);
      showError(error_system);
   }
}

// agrega un numero a la pre creacion del contact center
const addNumContactCenterSwal = (id_table) => {
   try {
      spinnerOpen('btn-add-num-contact-swal-' + id_table);
      setTimeout(() => {
         let num_group = document.getElementById('add-num-grupo-swal-' + id_table);
         let tbodyRow = document.querySelector('#nums-group-table-swal-' + id_table + ' tbody');
         let error_nun = document.getElementsByName('err-num-group-swal-' + id_table)[0];
         statusMSJ(error_nun, '', true);
         if (num_group.value.length <= 0 || num_group.value === null || num_group.value === '' || num_group.value === undefined) {
            statusMSJ(error_nun, 'Debe ingresar un n&uacute;mero', false, true);
            spinnerClose('btn-add-num-contact-swal-' + id_table, 'Agregar');
            return false;
         }
         if (num_group.value.length < 3) {
            statusMSJ(error_nun, 'Debe ingresar un largo de 3 n&uacute;meros m&iacute;nimo', false, true);
            spinnerClose('btn-add-num-contact-swal-' + id_table, 'Agregar');
            return false;
         }
         if (!formatNum.test(num_group.value.trim())) {
            statusMSJ(error_nun, 'Debe ingresar solo n&uacute;meros', false, true);
            spinnerClose('btn-add-num-contact-swal-' + id_table, 'Agregar');
            return false;
         }
         // eliminamos el informativo
         if (tbodyRow.querySelectorAll('tr').length === 1) {
            let unic_num = false;
            tbodyRow.querySelectorAll('tr')[0].id === 'sin-info-nums-swal-' + id_table ? tbodyRow.querySelectorAll('tr')[0].remove() : unic_num = true;
            if (unic_num === true) {
               statusMSJ(error_nun, 'Solo puede ingresar un n&uacute;mero', false, true);
               spinnerClose('btn-add-num-contact-swal-' + id_table, 'Agregar');
               return false;
            }
         }
         let num_ocuped = false;
         tbodyRow.querySelectorAll('tr').forEach(element => {
            if (element.id === `num-group-swal-${id_table}-${num_group.value}`) {
               statusMSJ(error_nun, 'Ya tiene este n&uacute;mero agregado', false, true);
               spinnerClose('btn-add-num-contact-swal-' + id_table, 'Agregar');
               num_ocuped = true;
               return false;
            }
         });
         if (num_ocuped === true) {
            return false;
         }
         // agregamos el numero nuevo
         tbodyRow.innerHTML += /* html */ `
         <tr id="num-group-swal-${id_table}-${num_group.value}">
            <td class="text-end">${num_group.value}</td>
            <td class="text-center">
               <img class="link-pointer" src="${url_img}/icon-cross-sm-delete.png" title="Eliminar numero del Contact Center" id="delete-num-group-swal-${id_table}-${num_group.value}" name="delete-num-group-swal" onclick="deleteNumGroup('swal-${id_table}', ${num_group.value})">
            </td>
         </tr>
         `;
         spinnerClose('btn-add-num-contact-swal-' + id_table, 'Agregar');
      }, 100);
   } catch (error) {
      spinnerClose('btn-add-num-contact-swal-' + id_table, 'Agregar');
      console.error(error);
      showError(error_system);
   }
}

// agregar una preview de un contact center
const previewConactCenterSwal = (dom_cod) => {
   try {
      return new Promise((resolve, reject) => {
         spinnerOpen(`btn-add-group-swal-${dom_cod}`);
         setTimeout(async () => {
            let name_group = document.getElementById('name-group-swal-' + dom_cod).value;    // nombre_grupo
            let div_groups = document.getElementById('div-groups-swal-' + dom_cod);          // div para los grupos
            let name_groupF = name_group.toLowerCase().trim().replaceAll(' ', '-');          // nombre grupo con formato aplicado
            let groups = document.getElementsByName('accordions-swal-groups');               // grupos
            let error_name = document.getElementsByName('err-group-' + dom_cod)[0];
            statusMSJ(error_name, '', true);
            // NOMBRE_GRUPO
            if (name_group.length <= 0 || name_group === null || name_group === undefined || name_group === '') {
               statusMSJ(error_name, 'Nombre requerido', false, false);
               spinnerClose(`btn-add-group-swal-${dom_cod}`, 'Pre Ingresar');
               reject('Nombre para el Contact Center no puede estar vacío');
               return false;
            }
            // FORMATO GRUPO
            if (!formatGroup.test(name_group.trim())) {
               statusMSJ(error_name, 'Nombre no cumple el formato, solo letras y n&uacute;meros', false, false);
               spinnerClose(`btn-add-group-swal-${dom_cod}`, 'Pre Ingresar');
               reject('Nombre no cumple el formato, solo letras y números');
               return false;
            }
            if (groups.length !== 0) {
               let unico = true;
               groups.forEach(element => {
                  if (element.id.includes(name_groupF)) {
                     unico = false;
                  }
               });
               if (unico === false) {
                  statusMSJ(error_name, `Nombre de Contact Center: <span class="text-primary">${name_group}</span> esta ingresado`, false, false);
                  spinnerClose('btn-add-group-swal-' + dom_cod, 'Pre Ingresar');
                  reject(`Nombre de Contact Center esta ingresado`);
                  return false;
               }
            }
            // verifica si existe nombre del contact center
            let data = await verifyNameContactCenter(dom_cod, name_group);
            if (data.status === 'error') {
               statusMSJ(error_name, data.message, false, false);
               spinnerClose(`btn-add-group-swal-${dom_cod}`, 'Pre Ingresar');
            } else {
               div_groups.innerHTML += /* html */ `
               <div class="accordion col-lg-4" id="accordion-swal-${name_groupF}" name="accordions-swal-groups">
                  <div class="accordion-item mb-3">
                     <h2 class="accordion-header">
                        <button class="accordion-button btn-bg-danger d-flex align-items-center justify-content-start" type="button" data-bs-toggle="collapse" data-bs-target="#group-accordion-swal-item-${name_groupF}" aria-expanded="true" aria-controls="group-accordion-swal-item-${name_groupF}">
                           <img class="link-pointer me-2" src="${url_img}/icon-trash-delete.png" width="20" height="20" id="delete-group-swal-${name_groupF}" name="delete-group-swal" title="Elimina Contact Center" onclick="deleteContactCenter('swal-${name_groupF}')">
                           Contact Center &nbsp;<strong>${name_group}</strong>
                        </button>
                     </h2>
                     <div id="group-accordion-swal-item-${name_groupF}" class="accordion-collapse collapse show" data-bs-parent="#accordion-${name_groupF}">
                        <form id="form-add-num-contact-center-swal-${name_groupF}" class="row">
                           <div class="col-12 py-2 text-start">
                              <div class="input-group input-group-sm">
                                 <input type="text" class="form-control" id="add-num-grupo-swal-${name_groupF}" name="add-num-grupo-swal" aria-describedby="num-group-text-swal-${name_groupF}" placeholder="Agregar n&uacute;mero al Contact Center &quot;${name_group}&quot;" title="N&uacute;mero para el Contact Center" onkeydown="return onlyNumbers(event)" autocomplete="off">
                                 <button type="submit" class="btn buttons colors-font" id="btn-add-num-contact-swal-${name_groupF}" name="btn-add-num-grupo-swal"> 
                                    <div id="spinner-btn-add-num-contact-swal-${name_groupF}" class="spinner-border spinner-border-sm text-white" role="status" hidden>
                                       <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <span id="textbtn-btn-add-num-contact-swal-${name_groupF}">Agregar</span>
                                 </button>
                              </div>
                              <div name="err-num-group-swal-${name_groupF}" class="form-text px-2"></div>
                           </div>
                        </form>
                        <div class="row">
                           <div class="col-12 table-responsive">
                              <table id="nums-group-table-swal-${name_groupF}" class="border-bottom-0 w-100 m-0">
                                 <thead>
                                    <tr class="bg-warning-subtle">
                                       <th>N&uacute;mero</th>
                                       <th>Eliminar</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <tr id="sin-info-nums-swal-${name_groupF}">
                                       <td class="text-center" colspan="2">Sin n&uacute;meros agregados</td>
                                    </tr>
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               `;
               document.getElementById(`form-add-num-contact-center-swal-${name_groupF}`).addEventListener('submit', e => {
                  e.preventDefault();
                  addNumContactCenterSwal(`${name_groupF}`);
               });
               spinnerClose('btn-add-group-swal-' + dom_cod, 'Ingresar');
            }
            resolve(true);
         }, 100);
      });
   } catch (error) {
      spinnerClose(`btn-add-group-swal-${dom_cod}`, 'Pre Ingresar');
      console.error(error);
      showError(error_system);
   }
}

// agrega grupos con sus numeros
const createNewGroupSwal = (dom_cod) => {
   spinnerOpen('btn-create-group-swal-' + dom_cod);
   try {
      setTimeout(() => {
         let div_groups = document.getElementsByName('accordions-swal-groups');
         let error_groups = document.getElementsByName('err-group-add-' + dom_cod)[0];
         statusMSJ(error_groups, '', true);
         // NUMEROS DEL GRUPO
         if (div_groups.length === 0) {
            statusMSJ(error_groups, 'Debes ingresar un <strong>Contact Center</strong>.', false, false);
            spinnerClose('btn-create-group-swal-' + dom_cod, 'Ingresar');
            return false;
         }
         let sin_num = false;
         div_groups.forEach(element => {
            let [tr] = element.querySelectorAll('table tbody tr');
            if (tr.id.includes('sin-info-nums-') === true) {
               statusMSJ(error_groups, 'Debes ingresar n&uacute;meros a los <strong>Conctact Center</strong>.', false, false);
               spinnerClose('btn-create-group-swal-' + dom_cod, 'Ingresar');
               sin_num = true;
               return false;
            }
         });
         if (sin_num === true) {
            return false;
         }
         let arr_nums_groups = [];
         div_groups.forEach(element => {
            let numGroupsF = '';
            let [name_group] = element.querySelectorAll('button strong');
            let name_groupF = name_group.innerHTML.toLowerCase().trim().replaceAll(' ', '-');
            let [table] = element.querySelectorAll('#nums-group-table-swal-' + name_groupF);
            let tr = table.querySelectorAll('tbody tr');
            tr.forEach(element2 => {
               numGroupsF += element2.id.replaceAll('num-group-swal-' + name_groupF + '-', '') + ';';
            });
            arr_nums_groups.push({
               'name_group': name_group.innerHTML,
               'nums_groups': numGroupsF
            });
         });
         const data = {
            'dom_cod': dom_cod,
            'arr_nums_groups': arr_nums_groups
         };
         fetch(`${url_operator}/json/json_createGroup.php`, {
            method: 'POST',
            headers: {
               'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
         })
            .then(response => response.json())
            .then(async (response) => {
               if (response.status === 'error') {
                  statusMSJ(error_groups, response.message, false, false);
                  spinnerClose('btn-create-group-swal-' + dom_cod, 'Ingresar');
               } else {
                  const data = await dataContactCenter(document.getElementById('usua_cod').value);
                  await menuContactCenter(data);
                  setTimeout(() => {
                     Swal.close();
                     toast.fire({
                        icon: 'success',
                        title: response.message
                     });
                  }, 1000);
               }
            })
            .catch((error) => {
               console.error(`Error: ${error}`);
            });
      }, 100);
   } catch (error) {
      spinnerClose('btn-create-group-swal-' + dom_cod, 'Ingresar');
      console.error(`Error: ${error}`);
      showError(error_system);
   }
}

// crea un nuevo contact center - formulario
const formContactCenter = (dom_cod = 0, op = 0) => {
   if (op === 0) {
      spinnerOpen('btn-add-grupo');                                                                               // abre el spinner
   }
   try {
      setTimeout(() => {
         if (op === 0) {
            let name_group = document.getElementById('name-group').value;                                         // nombre_grupo
            let div_groups = document.getElementById('div-groups');                                               // div para los grupos
            let name_groupF = name_group.toLowerCase().trim().replaceAll(' ', '_');                               // nombre grupo con formato aplicado
            let groups = document.getElementsByName('accordions-groups');                                         // grupos
            // NOMBRE_GRUPO
            if (name_group.length <= 0 || name_group === null || name_group === undefined || name_group === '') {
               showToastError('Nombre requerido');
               spinnerClose('btn-add-grupo', 'Ingresar');
               return false;
            }
            // FORMATO GRUPO
            if (!formatGroup.test(name_group.trim())) {
               showToastError('Nombre no cumple el formato, solo letras y n&uacute;meros');
               spinnerClose('btn-add-grupo', 'Ingresar');
               return false;
            }
            if (groups.length !== 0) {
               let unico = true;
               groups.forEach(element => {
                  if (element.id.includes(name_groupF)) {
                     unico = false;
                  }
               });
               if (unico === false) {
                  showToastError('Nombre para el Contact Center ya existe');
                  spinnerClose('btn-add-grupo', 'Ingresar');
                  return false;
               }
            }
            div_groups.innerHTML += /* html */ `
            <div class="accordion col-lg-4" id="accordion-${name_groupF}" name="accordions-groups">
               <div class="accordion-item mb-3">
                  <h2 class="accordion-header">
                     <button class="accordion-button btn-bg-light d-flex align-items-center justify-content-start" type="button" data-bs-toggle="collapse" data-bs-target="#group_accordion_item_${name_groupF}" aria-expanded="true" aria-controls="group_accordion_item_${name_groupF}">
                     <img class="link-pointer me-2" src="https://${document.domain}/backoffice/assets/img/icon_delete.png" width="20" height="20" id="delete-group-${name_groupF}" name="delete-group-${name_groupF}" title="Elimina Contact Center">
                     Contact Center &nbsp;<strong>${name_group}</strong>
                     </button>
                  </h2>
                  <div id="group_accordion_item_${name_groupF}" class="accordion-collapse collapse show" data-bs-parent="#accordion-${name_groupF}">
                     <div class="row">
                        <div class="col-12 py-2">
                           <div class="input-group input-group-sm">
                              <input type="text" class="form-control" id="add-num-grupo-${name_groupF}" name="add-num-grupo-${name_groupF}" maxlength="15" aria-describedby="num-group-text-${name_groupF}" onkeydown="return onlyNumbers(event)" placeholder="Agregar n&uacute;mero al Contact Center &quot;${name_group}&quot;" title="N&uacute;mero para el Contact Center" autocomplete="off">
                              <button type="button" class="btn btn-primary" id="btn-add-num-grupo-${name_groupF}" name="btn-add-num-grupo-${name_groupF}" onclick="addNewNumGroup('${name_groupF}')"> 
                                 <div id="spinner-btn-add-num-grupo-${name_groupF}" class="spinner-border spinner-border-sm text-white" role="status" hidden>
                                    <span class="visually-hidden">Loading...</span>
                                 </div>
                                 <span id="textbtn-btn-add-num-grupo-${name_groupF}">Agregar</span>
                              </button>
                           </div>
                        </div>
                        <div class="col-12 table-responsive">
                           <table id="nums-group-table-${name_groupF}" class="table m-0 border-bottom-0">
                              <thead>
                                 <tr class="bg-warning-subtle">
                                    <th>N&uacute;mero</th>
                                    <th>Eliminar</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <tr id="sin-info-nums-${name_groupF}">
                                    <td class="text-center" colspan="2">Sin n&uacute;meros agregados</td>
                                 </tr>
                              </tbody>
                           </table>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            `;
            spinnerClose('btn-add-grupo', 'Ingresar');
         } else {
            let html = `
            <div class="container">
               <div class="row">
                  <div class="col-12 text-start">
                     <h2>Defina uno o varios Contact Centers</h2>
                  </div>
               </div>
               <form id="form-add-contact-center-${dom_cod}" class="row align-items-center">
                  <div class="col-lg-4 text-start">
                     <label class="col-form-label-sm" for="name-group-swal-${dom_cod}">* Nombre Contact Center:</label>
                     <div class="input-group input-group-sm">
                        <input type="text" class="form-control form-control-sm" id="name-group-swal-${dom_cod}" name="name-group-swal-${dom_cod}" title="Nombre del nuevo Contact Center" placeholder="Ingrese un nombre para el nuevo Contact Center" autocomplete="off">
                        <button type="submit" class="btn buttons colors-font" id="btn-add-group-swal-${dom_cod}" name="btn-add-group-swal-${dom_cod}" title="Ingresar nuevo Contact Center"> 
                           <div id="spinner-btn-add-group-swal-${dom_cod}" class="spinner-border spinner-border-sm text-white" role="status" hidden>
                              <span class="visually-hidden">Loading...</span>
                           </div>
                           <span id="textbtn-btn-add-group-swal-${dom_cod}">Pre Ingresar</span>
                        </button>
                     </div>
                     <div name="err-group-${dom_cod}" class="form-text text-danger pb-2"></div>
                  </div>
               </form>
               <hr>
               <div class="row" id="div-groups-swal-${dom_cod}"></div>
               <hr>
               <div class="row">
                  <div class="col-12 d-flex justify-content-end">
                     <button type="submit" class="btn buttons colors-font" id="btn-create-group-swal-${dom_cod}" name="btn-create-group-swal-${dom_cod}" onclick="createNewGroupSwal(${dom_cod})" title="Ingresar contact Center"> 
                        <div id="spinner-btn-create-group-swal-${dom_cod}" class="spinner-border spinner-border-sm text-white" role="status" hidden>
                           <span class="visually-hidden">Loading...</span>
                        </div>
                        <span id="textbtn-btn-create-group-swal-${dom_cod}">Ingresar</span>
                     </button>
                  </div>
                  <div name="err-group-add-${dom_cod}" class="form-text text-danger text-start"></div>
               </div>
            </div>
            `;
            Swal.fire({
               allowEscapeKey: false,
               allowOutsideClick: false,
               position: 'top',
               html,
               showCloseButton: true,
               showConfirmButton: false,
               width: 1200,
            });
            document.getElementById(`form-add-contact-center-${dom_cod}`).addEventListener('submit', function (e) {
               e.preventDefault();
               previewConactCenterSwal(dom_cod);
            });
         }
      }, 100);
   } catch (error) {
      if (op === 0) {
         spinnerClose('btn-add-grupo', 'Ingresar');
      }
      console.error(error);
      showError(error_system);
   }
}

// actualiza el email del cliente
const updateEmailClient = () => {
   try {
      setTimeout(() => {
         let password = document.getElementById('password');
         let email = document.getElementById('email-oper')
         cleanInputError(`err-email-oper`);
         // EMAIL
         if (!formatEmail.test(email.value.trim())) {
            email.focus();
            statusMSJ(document.getElementsByName('err-email-oper')[0], 'Formato email no valido', false, false);
            return false;
         }
         // PASSWORD
         if (password.value.trim().length === 0) {
            password.focus();
            statusMSJ(document.getElementsByName(`err-email-oper`)[0], 'Ingrese contrase&ntilde;a', false, false);
            return false;
         }
         const data = {
            'oper_cod': parseInt(document.getElementById('oper_cod').value),
            'email': email.value.trim().toUpperCase(),
            'password': password.value.trim()
         }
         fetch(`${url_operator}/json/json_updateEmailOper.php`, {
            method: 'POST',
            headers: {
               'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
         })
            .then(response => response.json())
            .then((response) => {
               if (response.status === 'error') {
                  statusMSJ(document.getElementsByName(`err-email-oper`)[0], response.message, false, false);
               } else {
                  document.getElementById(`email-oper`).value = '';
                  document.getElementById(`password`).value = '';
                  toast.fire({
                     icon: 'success',
                     title: response.message
                  });
               }
            })
            .catch((error) => {
               console.error(`Error: ${error}`);
            });
      }, 300);
   } catch (error) {
      console.error(`Error: ${error}`);
      showError(error_system);
   }
}

// actualiza clave al cliente
const updatePasswordClient = (dom_cod) => {

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
            'password_current': password_current.value.trim(),
            'password': password.value.trim(),
            'password_v': password_v.value.trim()
         }

         fetch(`${url_operator}/json/json_updatePasswordOper.php`, {
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

                  password_current.value = '';
                  password.value = '';
                  password_v.value = '';
                  toast.fire({
                     icon: 'success',
                     title: response.message
                  });

               }

            })
            .catch((error) => {

               console.error(`Error: ${error}`);

            });

      }, 300);

   } catch (error) {

      console.error(error);
      showError(error_system);

   }

}

// trae todos los usuarios de dominio y servicio dado
const allUserServices = async (dom_cod, tipo_cod) => {
   const data = await fetch(`${url_operator}/json/json_getAllUserServices.php?dom_cod=${dom_cod}&tipo_cod=${tipo_cod}`, {
      method: 'GET',
      headers: {
         'Content-Type': 'application/json'
      }
   });
   return data.json();
}

// notificacion de crendenciales de un usuario
const checkNotiUserService = async (busua_cod, op = 0, valtipo_cod = 0, bot_cod = 0) => {

   try {

      const question = await questionSweetAlert('¿Esta seguro que desea notificar al <span class="text-primary fw-bold">Usuario</span>?');

      if (question.isConfirmed) {

         await showLoadingSystem('');

         const dom_cod = parseInt(document.getElementById('dom_cod').value);
         const tipo_cod = op === 0 ? parseInt(document.getElementById('tipo-cod').value) : valtipo_cod;

         const res = await notiUserService(busua_cod, dom_cod, tipo_cod);

         if (res.status === 'err') {

            await showToastError(res.message);

         } else {

            const resemail = await sendEmail(res.token, res.dom_cod, res.busua_cod, res.tipo_cod, res.cloud_password, 2);

            const fecha = new Date(resemail.fecha_notificacion);

            if (op === 0) {

               if (tipo_cod != 5) {

                  await modificarCeldaPorId(`row-${busua_cod}`, 5, `${dateFormat(fecha)} a las ${horaFormateada(fecha)}`);

               }

            } else {

               if (tipo_cod != 5) {

                  document.getElementById(`fecha-notificacion-${bot_cod}`).value = `${dateFormat(fecha)} a las ${horaFormateada(fecha)}`;

               }

            }


            await showToastSuccess(resemail.message);

         }

      }

   } catch (error) {

      await showToastError(error_system);
      console.error(error);

   }

}

// Función para actualizar la barra de progreso en tiempo real
const updateProgress = async (total, index) => {

   const percentage = (index / total) * 100;

   // Aquí simulamos una espera asincrónica de 100ms, por ejemplo
   await new Promise(resolve => setTimeout(resolve, 100));

   // actualiza el porcentaje
   document.getElementById('percentage-text').innerText = `${Math.round(percentage)}%`;

}

// notificacion masiva
const checkMasive = async (busua_cod, total, index) => {

   try {

      // muestra modal en la primera iteracion
      if (index === 1) {

         $('#progressModal').modal('show');

      }

      const dom_cod = parseInt(document.getElementById('dom_cod').value);
      const tipo_cod = parseInt(document.getElementById('tipo-cod').value);

      // llamada al servicio para notificar al usuario
      const res = await notiUserService(busua_cod, dom_cod, tipo_cod);

      if (res.status === 'err') {

         const errorMessage = res.message || 'Error desconocido al notificar al usuario';
         await showToastError(errorMessage);

      } else {

         // si la notificacion fue exitosa, enviamos un email
         const resemail = await sendEmail(res.token, res.dom_cod, res.busua_cod, res.tipo_cod, res.cloud_password, 2);

         const fecha = new Date(resemail.fecha_notificacion);

         // modificamos la celda con la fecha de notificacion
         await modificarCeldaPorId(`row-${busua_cod}`, 5, `${dateFormat(fecha)} a las ${horaFormateada(fecha)}`);

         // registra en pantalla cada iteracion
         document.getElementById('info-progress-noty').innerHTML += `
         <span>Usuario Id: ${busua_cod} | <span class="${resemail.status === 'success' ? 'text-success' : 'text-danger'}">${resemail.message}</span></span><br>
         `;

      }

      // actualiza el progreso después de cada usuario
      await updateProgress(total, index);

      // cierra el modal cuando todos los usuarios hayan sido procesados
      if (index === total) {

         document.getElementById('spinner-container-progress').classList.add('d-none');
         document.getElementById('btn-close-progress').classList.remove('d-none');
         document.getElementById('info-progress-noty').innerHTML += `
         <div class="col-12 my-3 text-center"><span class="fs-4">Proceso finalizado.</span></div>
         `;

      }

   } catch (error) {

      console.error('Error en checkMasive:', error);
      await showToastError('Hubo un error al procesar la notificacion. Intente nuevamente.');

   }

}

// trae usuarios que tengan servicio dado
const selectTipoServiceNoti = async (select) => {

   try {

      // cod dominio
      const dom_cod = document.getElementById('dom_cod');

      await spinnerOpen(`btn-plantilla-${dom_cod.value}`);

      // table
      const table = $('#table-noti-services').DataTable();

      // check masivo
      const check = document.getElementById('check-masivo');

      // boton masivo
      const btn = document.getElementById('btn-masivo');

      // deshabilita botones masivos
      check.disabled = true;
      btn.classList.add('disabledp');

      // trae fetch
      const res = await allUserServices(parseInt(dom_cod.value), parseInt(select.value));

      if (res.status === 'err') {

         // limpiar los datos actuales
         table.clear().draw();

      } else {

         table.clear().draw();

         let i = 0;

         res.dataService.forEach(element => {

            const fecha = new Date(element.fecha_notificacion);
            let fecha_f = '';

            if (element.tipo_cod == 5) {

               fecha_f = element.fecha_notificacion;

            } else {

               fecha_f = element.fecha_notificacion == 0 ? 'No notificado' : `${dateFormat(fecha)} a las ${horaFormateada(fecha)}`;

            }

            // nuevos datos que deseas cargar
            let newData = [
               element.busua_cod,
               element.nombre,
               element.cloud_username,
               element.email,
               element.tipo,
               fecha_f,
               `
               <div class="d-flex align-items-center justify-content-around">
                  <input type="checkbox" id="chek-noti-user-${element.busua_cod}" name="checksNotiUserPBE" value="${element.busua_cod}" aria-describedby="check" title="Check usuario ${element.cloud_username}" ${p3 === 2 ? '' : 'disabled'}>
                  <i class="fa-solid fa-envelope-open-text fa-xl link-pointer ${p3 === 2 ? 'text-success' : 'text-secondary'}" id="noti-user-${element.busua_cod}" ${p3 === 2 ? `onclick="checkNotiUserService(${parseInt(element.busua_cod)})"` : ''} title="Notificar servicio al usuario"></i>
               </div>
               `,
            ];

            let row = table.row.add(newData).draw();

            let rowNode = row.node();
            $(rowNode).attr('id', `row-${element.busua_cod}`);

            $(row.node()).find('td').eq(0).addClass(`text-end`);
            $(row.node()).find('td').eq(3).addClass(`text-center`);
            $(row.node()).find('td').eq(4).addClass(`text-center`);
            $(row.node()).find('td').eq(5).addClass(`text-center ${element.fecha_notificacion == 0 ? 'text-danger' : ''}`);
            $(row.node()).find('td').eq(6).addClass(`text-center`);

            i++;

         });

         document.getElementById(`check-masivo`).addEventListener('click', (e) => {

            document.getElementsByName('checksNotiUserPBE').forEach(check => (e.target.checked === true ? check.checked = true : check.checked = false));

         });

         // para notificacion masiva
         document.getElementById(`btn-masivo`).addEventListener('click', async (e) => {

            try {

               let users = [];

               table.$(`input[name="checksNotiUserPBE"]`).each(function () {
                  if (this.checked) {
                     users.push(this.value);
                  }
               });

               if (users.length === 0) {
                  await showToastError('<span class="text-danger">Debe seleccionar uno o mas <span class="text-primary">servicios</span></span>');
                  return false;
               }

               const question = await questionSweetAlert(`¿Esta seguro que desea notificar los <span class="text-primary fw-bold">servicios</span> seleccionados?.`);

               if (question.isConfirmed) {

                  document.getElementById('info-progress-noty').innerHTML = '';                       // limpia detalle
                  document.getElementById('spinner-container-progress').classList.remove('d-none');   // muestra spinner
                  document.getElementById('btn-close-progress').classList.add('d-none');              // esconde boton close-modal
                  document.getElementById('percentage-text').innerText = '';                          // limpia porcentaje

                  // procesa cada usuario y actualiza el progreso en tiempo real
                  for (let index = 0; index < users.length; index++) {
                     const user = users[index];
                     await checkMasive(user, users.length, index + 1); // Pasa el índice (1 basado) y el total
                  }

                  await showToastSuccess('Servicios notificados');

               }

            } catch (error) {

               console.error('Error al notificar los servicios:', error);
               await showToastError('Hubo un problema al notificar los servicios. Intente nuevamente.');

            }

         });

         if (p3 === 2) {
            check.disabled = false;
            btn.classList.remove('disabledp');
         }

      }

   } catch (error) {

      console.error(error);
      await showErrorSystems(error_system);

   } finally {

      await spinnerClose(`btn-plantilla-${dom_cod.value}`, 'Plantilla');

   }

}

// muestra plantilla
const getPlantilla = async (op) => {

   try {

      await spiner_menu_open('spinner-tipo-cod-plantilla');

      const domain_user = document.getElementById('du').value;
      const dom_cod = document.getElementById('dom_cod').value;

      // obtiene servicio
      const select_service = document.getElementById('tipo-cod');
      const select_service_pl = document.getElementById('tipo-cod-plantilla');

      let service_text = '';

      if (op === 0) {

         select_service_pl.value = parseInt(select_service.value);
         // texto del servicio seleccionado
         service_text = parseInt(select_service.value) === 0 ? '' : select_service_pl.options[select_service_pl.selectedIndex].text;

      } else {

         if (parseInt(select_service_pl.value) !== 0) {
            service_text = parseInt(select_service.value) === 0 ? '' : select_service_pl.options[select_service_pl.selectedIndex].text;
         }

      }

      // titulo de modal
      document.getElementById('title-modal-pbe').innerHTML = /* html */ `Plantilla ${domain_user} | ${service_text}`;

      // detalle de la plantilla
      const textarea = document.getElementById('plantilla-email');

      textarea.classList.remove('text-danger');

      // Hacer la solicitud con fetch y esperar la respuesta
      const res = await fetch(`${document.location.origin}/plantillas/obtiene_plantilla.php?dom_cod=${dom_cod}&tipo_cod=${select_service_pl.value}`);

      // Verificar si la respuesta fue exitosa
      if (!res.ok) {

         // Si no es exitosa, lanzar un error con el mensaje recibido
         const errorData = await res.json();

         // Si la respuesta fue exitosa, procesar los datos JSON
         textarea.classList.add('text-danger');
         textarea.innerHTML = /* html */ `${errorData.error}`;

         await spiner_menu_close('spinner-tipo-cod-plantilla');

         throw new Error(errorData.error);

      }

      const plantilla = await res.json();


      // Si la respuesta fue exitosa, procesar los datos JSON
      textarea.innerHTML = /* html */ `${plantilla.contenido}`;

      await spiner_menu_close('spinner-tipo-cod-plantilla');

   } catch (error) {

      // Manejar el error
      console.error('Error:', error.message);

   }

}

// muestra plantilla
const viewPlantillaModal = async (dom_cod) => {

   await spinnerOpen(`btn-plantilla-${dom_cod}`);

   // consulta plantilla en PBE
   await getPlantilla(0);

   await $('#modal-pbe').modal('show');

   await spinnerClose(`btn-plantilla-${dom_cod}`, 'Plantilla');

}

// arma menu seleccionado
const dataMenu = async (menu, dom_cod) => {

   try {

      return new Promise((resolve, reject) => {

         setTimeout(() => {

            const data = document.getElementById('data-result');
            const title = document.getElementById('title-adm-menu');
            const links = document.getElementsByName('links');
            const du = document.getElementById('du').value;
            const host = window.location.host;

            links.forEach(link => {
               link.classList.remove('main-menu__link_select');
            })

            data.innerHTML = '';

            // detalle del menu
            switch (menu) {

               case 1:

                  title.innerHTML = 'Usuarios';

                  data.innerHTML += /* html */ `
                  <div class="bg-light border rounded p-3 pb-0 mb-3">
                     ${p1 === 2 ? `
                     <div class="row">
                        <div class="col-12 d-flex align-items-center mb-3">
                           <h4 class="pt-2">Contact Centers</h4>
                           <button type="button" class="btn btn-sm ms-3 buttons colors-font" id="new-contact-center-btn-${dom_cod}" data-bs-toggle="tooltipContactCenters" data-bs-html="true" data-bs-title="Nuevo Contact Center" tile="Nuevo Contact Center" onclick="openModalNewCC(${dom_cod})"><i class="fa-solid fa-plus"></i></button>
                        </div>
                     </div>
                     ` : ``}
                     <div id="info-contact-center" class="row mb-3"></div>
                  </div>
                  <div class="bg-light border rounded p-3 mb-3">
                     <div class="row">
                        <div class="col-12 d-flex align-items-center justify-content-between mb-3">
                           <h4 class="pt-2">Usuarios</h4>
                           <div id="select-contact-center" class="pt-2">
                              <div class="d-flex align-items-center">
                                 <div id="spinner-load-group" class="spinner-border spinner-border-sm text-primary me-2" role="status" hidden>
                                    <span class="visually-hidden">Loading...</span>
                                 </div>
                                 <div>
                                    <select class="form-select w-100" id="select-group" aria-describedby="select_group" onchange="menuUsers(this.value)" title="Contact Centers Activos"></select>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div id="info-users" class="row mb-3"></div>
                  </div>`;

                  document.querySelector('#links-1 a').classList.add('main-menu__link_select');

                  resolve(true);

                  break;
               case 2:

                  title.innerHTML = 'Aprovisionamiento';

                  data.innerHTML += /* html */ `
                  <div class="bg-light border rounded p-3 pb-0 mb-3">
                     <div class="btn-group col-lg-3 mb-3">
                        <button type="button" class="btn buttons colors-font" id="btn-menu-aprov-${dom_cod}" name="btn-menu-aprov-${dom_cod}"> 
                           <div id="spinner-menu-aprov-${dom_cod}" class="spinner-border spinner-border-sm text-white" role="status" hidden>
                              <span class="visually-hidden">Loading...</span>
                           </div>
                           <span id="textbtn-btn-menu-aprov-${dom_cod}"></span>
                        </button>
                        <button type="button" class="btn buttons colors-font dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                           <span class="visually-hidden">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu">
                           <li><a class="dropdown-item link-pointer" onclick="changeFormAddUsers(${dom_cod}, 1)">Formulario un Usuario</a></li>
                           <li><a class="dropdown-item link-pointer" onclick="changeFormAddUsers(${dom_cod}, 0)">Formulario masivo Usuarios .CSV</a></li>
                        </ul>
                     </div>
                     <div class="col-12 mb-3">
                        <table id="table-count-users" class="w-25">
                           <thead>
                              <tr class="background-all-1 colors-font">
                                 <td colspan="2">Usuarios</td>
                              </tr>
                              <tr class="background-all-1 colors-font">
                                 <td>Actuales / Permitido</td>
                                 <td class="text-end" id="count-users-info"></td>
                              </tr>
                           </thead>
                        </table>
                     </div>
                     <div id="content-add-users-bp-${dom_cod}" class="col-12 d-flex mb-3"></div>
                  </div>`;

                  document.querySelector('#links-2 a').classList.add('main-menu__link_select');

                  resolve(true);

                  break;

               case 3:

                  title.innerHTML = 'Notificaciones';

                  data.innerHTML += /* html */ `
                  <div class="bg-light border rounded p-3 mb-3">
                     <div class="row">
                        <div id="div-select-service" class="col-lg-3 mb-3">
                           <div>
                              <label for="tipo-cod" class="col-form-label">SELECCIONE SERVICIO</label>
                           </div>
                           <div class="d-flex align-items-center">
                              <div id="spinner-tipo-cod-service" class="spinner-border spinner-border-sm text-primary me-2" role="status" hidden>
                                 <span class="visually-hidden">Loading...</span>
                              </div>
                              <select class="form-select" id="tipo-cod" name="tipo-cod" onchange="selectTipoServiceNoti(this)" title="Tipo de servicio"></select>
                           </div>
                        </div>
                        <div id="div-plantilla" class="col-lg-3 d-flex align-items-end mb-3">
                           <button type="submit" class="btn buttons colors-font status-form-update-service" id="btn-plantilla-${dom_cod}" name="btn-plantilla-${dom_cod}" onclick="viewPlantillaModal(${dom_cod})" title="Plantilla configurada en pbe.redvoiss.net para el servicio seleccionado">
                              <div id="spinner-update-data-user" class="spinner-border spinner-border-sm text-white" role="status" hidden>
                                 <span class="visually-hidden">Loading...</span>
                              </div>
                              <span id="textbtn-update-data-user">Plantilla</span>
                           </button>
                           <a class="ms-3 mb-2 link-pointer" target="_blank" title="Descarga instructivo" href="${document.location.origin}/plantillas/instructivo_notificaciones.html">Instructivo</a>
                        </div>
                        <div id="div-info-service-notify-${dom_cod}" class="col-12 table-responsive">
                           <table id="table-noti-services" class="table align-middle py-2">
                              <thead>
                                 <tr class="bg-primary-subtle">
                                    <th class="fw-bold">Id</th>
                                    <th class="fw-bold">Nombre</th>
                                    <th class="fw-bold">Cloud Username</th>
                                    <th class="fw-bold">Email</th>
                                    <th class="fw-bold">Servicio</th>
                                    <th class="fw-bold">Fecha notificaci&oacute;n</th>
                                    <th class="fw-bold">
                                       <div class="d-flex align-items-center justify-content-around">
                                          <input type="checkbox" id="check-masivo" name="check-masivo" aria-describedby="check masivo" title="Check masivo notificaci&oacute;n" disabled>
                                          <button type="button" class="btn buttons colors-font disabledp" id="btn-masivo" name="btn-masivo" title="Notificar masivamente"> 
                                             <div id="spinner-btn-masivo" class="spinner-border spinner-border-sm text-white" role="status" hidden>
                                                <span class="visually-hidden">Loading...</span>
                                             </div>
                                             <span id="textbtn-btn-masivo">Notificar</span>
                                          </button>
                                       </div>
                                    </th>
                                 </tr>
                              </thead>
                              <tbody id="table-notify-services-${dom_cod}" class="table-group-divider"></tbody>
                           </table>
                        </div>
                     </div>
                  </div>`;

                  $(`#table-noti-services`).DataTable({
                     language: {
                        'decimal': '',
                        'emptyTable': 'No hay información',
                        'info': 'Mostrando _START_ a _END_ de _TOTAL_ Entradas',
                        'infoEmpty': 'Mostrando 0 to 0 of 0 Entradas',
                        'infoFiltered': '(Filtrado de _MAX_ total entradas)',
                        'infoPostFix': '',
                        'thousands': ',',
                        'lengthMenu': 'Mostrar _MENU_ Entradas',
                        'loadingRecords': 'Cargando...',
                        'processing': 'Procesando...',
                        'search': 'Buscar:',
                        'zeroRecords': 'Sin resultados encontrados',
                        'paginate': {
                           'first': 'Primero',
                           'last': 'Ultimo',
                           'next': 'Siguiente',
                           'previous': 'Anterior'
                        }
                     },
                     lengthMenu: [[10, 50, 100, -1], [10, 50, 100, 'All']],
                     pageLength: -1
                  });

                  document.querySelector('#links-3 a').classList.add('main-menu__link_select');

                  resolve(true);

                  break;

               case 4:

                  // formato de fecha
                  let date3 = dateSet(-14, 1);
                  let now3 = dateSet(0, 1);

                  title.innerHTML = 'Dashboard';

                  data.innerHTML += /* html */ `
                  <div class="bg-light border rounded p-3 pb-0 mb-3">
                     <div class="row mb-3">
                        <div class="col-12">
                           <div class="d-flex justify-content-start">
                              <div class="px-1">
                                 <label for="fini-dashboard">Desde</label>
                                 <input class="form-text" type="date" id="fini-dashboard" name="fini-dashboard" value="${date3}" max="${now3}" title="Fecha inicio filtro">
                              </div>
                              <div class="px-1">
                                 <label for="fend-dashboard">Hasta</label>
                                 <input class="form-text" type="date" id="fend-dashboard" name="fend-dashboard" value="${now3}" max="${now3}" title="Fecha fin filtro">
                              </div>
                              <div class="px-1">
                                 <button type="submit" class="btn btn-sm buttons colors-font" id="button_filter_dahsboard" name="button_filter_dahsboard" onclick="filterInternal('${host}', '${du}', 1)"> 
                                    <div id="spinner_button_filter_dahsboard" class="spinner-border spinner-border-sm text-white" role="status" hidden>
                                       <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <span id="textbtn_button_filter_dahsboard">Filtrar</span>
                                 </button>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div id="iframe-div-dashboard" class="row" style="height:75vh; width:100%;">
                        <iframe title="Dashboard" scrolling="no"></iframe>
                     </div>
                  </div>`;

                  document.querySelector('#links-4 a').classList.add('main-menu__link_select');

                  resolve(true);

                  break;
               case 5:

                  // formato de fecha
                  let date4 = dateSet(-14, 1);
                  let now4 = dateSet(0, 1);

                  title.innerHTML = 'Consola';

                  data.innerHTML += /* html */ `
                  <div class="bg-light border rounded p-3 pb-0 mb-3">
                     <div class="row mb-3">
                        <div class="col-12">
                           <div class="d-flex justify-content-start">
                              <div class="px-1">
                                 <label for="fini-console">Desde</label>
                                 <input class="form-text" type="date" id="fini-console" name="fini-console" value="${date4}" max="${now4}" title="Fecha inicio filtro">
                              </div>
                              <div class="px-1">
                                 <label for="fend-console">Hasta</label>
                                 <input class="form-text" type="date" id="fend-console" name="fend-console" value="${now4}" max="${now4}" title="Fecha fin filtro">
                              </div>
                              <div class="px-1">
                                 <button type="submit" class="btn btn-sm buttons colors-font" id="button_filter_console" name="button_filter_console" onclick="filterInternal('${host}', '${du}', 2)"> 
                                    <div id="spinner_button_filter_console" class="spinner-border spinner-border-sm text-white" role="status" hidden>
                                       <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <span id="textbtn_button_filter_console">Filtrar</span>
                                 </button>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div id="iframe-div-console" class="row" style="height:75vh; width:100%;">
                        <iframe title="Console" scrolling="no"></iframe>
                     </div>
                  </div>`;

                  document.querySelector('#links-5 a').classList.add('main-menu__link_select');

                  resolve(true);

                  break;
               case 6:

                  title.innerHTML = 'Reportes';
                  data.innerHTML += /* html */ `
                  <div class="bg-light border rounded p-3 pb-0 mb-3" id="reports"></div>
                  `;

                  document.querySelector('#links-6 a').classList.add('main-menu__link_select');

                  resolve(true);

                  break;
               case 7:

                  title.innerHTML = 'Datos personales';

                  data.innerHTML += /* html */ `
                  <div class="bg-light border rounded p-3 pb-0 mb-3" id="personal-information"></div>`;
                  resolve(true);

                  break;
               case 8:

                  title.innerHTML = 'Cambiar contrase&ntilde;a';

                  data.innerHTML += /* html */ `
                  <div class="bg-light border rounded p-3 pb-0 mb-3">
                     <form id="form-pa-${dom_cod}" class="row">
                        <div class="col-12">
                           <h4>Ingrese su contraseña nueva</h4>
                        </div>
                        <div class="row g-3 align-items-center">
                           <div class="col-md-2">
                              <label for="password-current" class="col-form-label">Contrase&ntilde;a actual:</label>
                           </div>
                           <div class="col-md-4">
                              <input type="password" id="password-current" class="form-control" aria-describedby="passwordCurrentHelpInline" minlength="1" maxlength="255" aria-describedby="password-current-text" onkeydown="return onlySpace(event)" autocomplete="off">
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
                              <input type="password" id="password" class="form-control" aria-describedby="passwordHelpInline" minlength="1" maxlength="255" aria-describedby="password-text" onkeydown="return onlySpace(event)" autocomplete="off">
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
                              <input type="password" id="password-v" class="form-control" aria-describedby="passwordVHelpInline" minlength="1" maxlength="255" aria-describedby="password-v-text" onkeydown="return onlySpace(event)" autocomplete="off">
                           </div>
                           <div class="col-auto">
                              <span id="passwordVHelpInline" class="form-text">
                                 Ingrese nuevamente su nueva contrase&ntilde;a
                              </span>
                           </div>
                        </div>
                        <div class="row g-3 align-items-center">
                           <div class="col-12 d-flex justify-content-between">
                              <div>
                                 <div id="password-help" name="err-password" class="form-text fs-5 fw-light" hidden></div>
                              </div>
                              <input type="submit" class="btn buttons colors-font" id="btn-update-pass" name="btn-update-pass" value="Modificar">
                           </div>
                        </div>
                     </form>
                  </div>`;

                  document.getElementById(`form-pa-${dom_cod}`).addEventListener('submit', e => {
                     e.preventDefault();
                     updatePasswordClient(dom_cod);
                  });

                  resolve(true);

                  break;
               case 9:

                  title.innerHTML = 'Cambiar email';

                  data.innerHTML += /* html */ `
                  <div class="bg-light border rounded p-3 pb-0 mb-3">
                     <form id="form-email-up-oper-${dom_cod}" class="row">
                        <div class="col-12">
                           <h4>Ingrese su nuevo correo</h4>
                        </div>
                        <div class="row g-3 align-items-center">
                           <div class="col-md-2">
                              <label for="email-oper" class="col-form-label">Nueva casilla:</label>
                           </div>
                           <div class="col-md-4">
                              <input type="email" id="email-oper" class="form-control" aria-describedby="emailHelpInline" minlength="1" maxlength="255" aria-describedby="password-current-text" placeholder="Ingresa tu email" onkeydown="return onlySpace(event)" autocomplete="off">
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
                              <input type="password" id="password" class="form-control" aria-describedby="passwordHelpInline" minlength="1" maxlength="255" aria-describedby="password-text" onkeydown="return onlySpace(event)" autocomplete="off">
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
                                 <div id="email-help" name="err-email-oper" class="form-text fs-5 fw-light" hidden></div>
                              </div>
                              <input type="submit" class="btn buttons colors-font" id="btn-update-email" name="btn-update-email" value="Modificar">
                           </div>
                        </div>
                     </form>
                  </div>`;

                  document.getElementById(`form-email-up-oper-${dom_cod}`).addEventListener('submit', e => {
                     e.preventDefault();
                     updateEmailClient();
                  });

                  resolve(true);

                  break;
               default:

                  data.innerHTML += /* html */ `
                  <section id="section-error-menu">
                     <div class="container-fluid">
                        <div class="row">
                           <div class="col-12 d-flex border-bottom">
                              <span class="title-menu-adm">Error</span>
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
      await showError(error_system);

   }

}

// valida los permisos
const validatePermission = async (menu) => {

   try {

      return new Promise((resolve, reject) => {

         setTimeout(() => {

            let retval = false; // si es false no tiene permiso

            const result = document.getElementById('data-result');

            document.getElementById('title-adm-menu').innerHTML = '';

            switch (menu) {

               case 1:

                  if (p1 > 0) {
                     retval = true;
                  } else {
                     retval = false;
                     result.innerHTML = '<span class="fs-5 text-primary">No tienes acceso a este men&uacute;</span>';
                  }

                  break;

               case 2:

                  if (p2 > 0) {
                     retval = true;
                  } else {
                     retval = false;
                     result.innerHTML = '<span class="fs-5 text-primary">No tienes acceso a este men&uacute;</span>';
                  }

                  break;

               case 3:

                  if (p3 > 0) {
                     retval = true;
                  } else {
                     retval = false;
                     result.innerHTML = '<span class="fs-5 text-primary">No tienes acceso a este men&uacute;</span>';
                  }

                  break;

               case 4:

                  if (p4 > 0) {
                     retval = true;
                  } else {
                     retval = false;
                     result.innerHTML = '<span class="fs-5 text-primary">No tienes acceso a este men&uacute;</span>';
                  }

                  break;

               case 5:

                  if (p5 > 0) {
                     retval = true;
                  } else {
                     retval = false;
                     result.innerHTML = '<span class="fs-5 text-primary">No tienes acceso a este men&uacute;</span>';
                  }

                  break;

               case 6:

                  if (p6 > 0) {
                     retval = true;
                  } else {
                     retval = false;
                     result.innerHTML = '<span class="fs-5 text-primary">No tienes acceso a este men&uacute;</span>';
                  }

                  break;

               case 7:

                  retval = true;

                  break;

               case 8:

                  retval = true;

                  break;

               case 9:

                  retval = true;

                  break;

               default:

                  retval = false;

                  result.innerHTML = '<span class="fs-5 text-primary">No tienes acceso, habla con tu administrador para que te asigne los privilegios en el sistema.</span>';

                  break;

            }

            retval === true ? resolve(true) : reject(false);

         }, 300);

      })

   } catch (error) {

      console.error(`Error: ${error}`);

   }

}

// configura menu aprov
const confAprov = async (dom_cod) => {

   return new Promise((resolve, reject) => {

      setTimeout(() => {

         // grupo
         let menu_aprov = JSON.parse(localStorage.getItem(`menu_aprov_oper_${dom_cod}`)) === null ? 1 : JSON.parse(localStorage.getItem(`menu_aprov_oper_${dom_cod}`));

         if (dom_cod === null || dom_cod === '' || dom_cod === undefined || dom_cod.leght === 0) {

            if (dom_cod === null || dom_cod === '' || dom_cod === undefined || dom_cod.leght === 0) {
               dom_cod = 0;

            } else {

               dom_cod = dom_cod;
               localStorage.removeItem(`menu_aprov_oper_${dom_cod}`);
               localStorage.setItem(`menu_aprov_oper_${dom_cod}`, menu_aprov);

            }

         } else {

            localStorage.removeItem(`menu_aprov_oper_${dom_cod}`);
            localStorage.setItem(`menu_aprov_oper_${dom_cod}`, menu_aprov);

         }

      }, 300);

      resolve(true);

   });
}

// actualiza input de causa
const updateTypeTrackerService = (op) => {
   let div_local = document.getElementById('div-tracker-service-add');
   document.getElementById('causa-tracker-add').value = '';
   div_local.hidden = true;
   if (parseInt(op) > 0) {
      div_local.hidden = false;
   }
}

// Agrega usuarios segun la opcion
const addServiceUsers = async (dom_cod, op) => {

   return new Promise((resolve, reject) => {

      setTimeout(() => {

         // error = false - manejo de errores
         let error = false;

         // contact center seleccionado
         let group_cod = parseInt(document.getElementById('group-cod-service-add').value);

         // info de errores
         let info_err = document.getElementById('info-err-add-service-users');

         // data
         const data = new FormData();
         info_err.innerHTML = '';
         info_err.hidden = true;

         // servicio 2 - requiere localizacion y mac
         let service2 = false;

         // service 3 - requiere tipo tracker y causa
         let service3 = false;

         // accordion de servicios pre definidos
         const accordion = document.querySelectorAll(`#all-service-acc div[name="accordion-services"]`);

         // recorremos todos los servicios pre definidos y se inserta en el array
         let i = 0;

         accordion.forEach(element => {

            // recatamos el tipo de servicio
            const tipo_cod = parseInt(element.getAttribute('data-value'));
            const notify_service = parseInt(document.querySelector(`#notify-service-add-${tipo_cod}`).value);

            data.append(`services[${i}]`, JSON.stringify({
               'tipo_cod': tipo_cod,
               'notify_service': notify_service
            }));

            if (tipo_cod === 2) {
               service2 = true;
            }

            if (tipo_cod === 5) {
               service3 = true;
            }

            i++;

         });


         if (op !== 0) {

            // archivo
            const csv = $(`#csv-file-add-service-users-${dom_cod}`).prop('files');

            cleanInputError('err-csv-add-service-users');
            cleanInputError('err-group-cod-service-add');

            // CSV
            if (csv.length === 0) {
               error = true;
               statusMSJ(document.getElementsByName('err-csv-add-service-users')[0], 'Debe ingresar un CSV.', false, false);
            }

            // GROUP_COD
            if (group_cod === 0) {
               error = true;
               statusMSJ(document.getElementsByName('err-group-cod-service-add')[0], 'Seleccione un Contact Center.', false, false);
            }

            if (error === true) {
               reject('Error, validando datos');
               return false;
            }

            data.append('file2upload', csv[0]);

         } else {

            // variables
            const name = document.getElementById('name-user-service-add').value.trim();                                   // nombre del usuario
            const cloud_username = document.getElementById('cloud-username-service-add').value.replaceAll(' ', '').toLowerCase();   // cloud_username del usuario
            const cloud_password = document.getElementById('cloud-password-service-add').value.replaceAll(' ', '');       // cloud_password del usuario
            const user_phone = document.getElementById('user-phone-service-add').value.replaceAll(' ', '');               // user_phone del usuario
            const email = document.getElementById('email-service-add').value.replaceAll(' ', '').toLowerCase();           // email del usuario

            // service2
            const localizacion = service2 === true ? (document.getElementById('localizacion-service-add').value) : null;  // localizacion
            const mac = service2 === true ? (document.getElementById('mac-service-add').value) : null;                    // mac

            //service3
            const tipo_tracker = service3 === true ? (document.getElementById('tipo-tracker-cod').value) : null;          // tipo_cod
            const causa = service3 === true ? (document.getElementById('causa-tracker').value) : null;                    // causa

            // limpia errores
            cleanInputError('err-name-user-service-add');                                                         // limpia error nombre
            cleanInputError('err-cloud-username-service-add');                                                    // limpia error cloud_username
            cleanInputError('err-cloud-password-service-add');                                                    // limpia error cloud_password
            cleanInputError('err-user-phone-service-add');                                                        // limpia error user_phone
            cleanInputError('err-email-service-add');                                                             // limpia error email
            cleanInputError('err-group-cod-service-add');                                                         // limpia error group

            // NOMBRE
            if (!formatGeneral.test(name)) {
               error = true;
               statusMSJ(document.getElementsByName('err-name-user-service-add')[0], 'Nombre: m&iacute;nimo 3 caracteres, solo letras y n&uacute;meros.', false, false);
            }

            // CLOUD_USERNAME
            if (!formatGeneral.test(cloud_username)) {
               error = true;
               statusMSJ(document.getElementsByName('err-cloud-username-service-add')[0], 'Cloud Username: m&iacute;nimo 3 caracteres, solo letras y n&uacute;meros.', false, false);
            }

            // CLOUD_PASSWORD
            if (cloud_password.length <= 2) {
               error = true;
               statusMSJ(document.getElementsByName('err-cloud-password-service-add')[0], 'Cloud Password: m&iacute;nimo 3 caracteres.', false, false);
            }

            // USER_PHONE
            if (user_phone.length <= 2) {
               error = true;
               statusMSJ(document.getElementsByName('err-user-phone-service-add')[0], 'Tel&eacute;fono Usuario: m&iacute;nimo 9 n&uacute;meros.', false, false);
            }

            // EMAIL
            if (!formatEmail.test(email)) {
               error = true;
               statusMSJ(document.getElementsByName('err-email-service-add')[0], 'Formato email inv&aacute;lido.', false, false);
            }

            // GROUP_COD
            if (group_cod === 0) {
               error = true;
               statusMSJ(document.getElementsByName('err-group-cod-service-add')[0], 'Seleccione un contact center.', false, false);
            }

            // servicio tipo 2 recquiere localizacion y mac
            if (service2) {

               cleanInputError('err-localizacion-service-add');
               cleanInputError('err-group-cod-service-add');

               if (localizacion.length <= 2) {
                  error = true;
                  statusMSJ(document.getElementsByName('err-localizacion-service-add')[0], 'Debe ingresar una localizaci&oacute;n.', false, false);
               }

               if (!formMac.test(mac)) {
                  error = true;
                  statusMSJ(document.getElementsByName('err-mac-service-add')[0], 'Formato Mac inv&aacute;lido.', false, false);
               }

            }

            // servicio tipo 3 requiere tipo tracker y causa
            if (service3) {

               if (causa.length <= 0) {
                  error = true;
                  statusMSJ(document.getElementsByName('err-causa-tracker-add')[0], 'Debe ingresar una causa.', false, false);
               }

            }

            // Si hay error paramos la ejecucion
            if (error === true) {
               reject('Error, validando datos');
               return false;
            }

            data.append('nombre', name);
            data.append('cloud_username', cloud_username);
            data.append('cloud_password', cloud_password);
            data.append('user_phone', user_phone);
            data.append('email', email);
            data.append('localizacion', localizacion);
            data.append('mac', mac);
            data.append('tipo_tracker', tipo_tracker);
            data.append('causa', causa);

         }

         // campos obligatorios
         data.append('dom_cod', dom_cod);
         data.append('group_cod', group_cod);
         data.append('op', op);

         resolve(data);

      }, 300);

   });

}

// aprovisiona usuarios
const aprovisiona = async (info, op) => {
   const data = await fetch(`${url_operator}/json/${op !== 0 ? 'json_createUsersCSV' : 'json_createUser'}.php`, {
      method: 'POST',
      body: info
   });
   return data.json();
}

// trae informacion del dominio
const dataDomain = async (dom_cod) => {
   const res = await fetch(`${url_operator}/json/json_getDomain.php?dom_cod=${dom_cod}`, {
      method: 'GET',
      headers: {
         'Content-Type': 'application/json'
      }
   });
   return res.json();
}

// envia email
const sendEmail = async (token, dom_cod, busua_cod, tipo_cod, cloud_password, op) => {

   const info = {
      'token': token,
      'dom_cod': dom_cod,
      'busua_cod': busua_cod,
      'tipo_cod': tipo_cod,
      'cloud_password': cloud_password,
      'op': op,
   }

   const res2 = await fetch(`${document.location.origin}/plantillas/envia_email.php`, {
      method: 'POST',
      body: JSON.stringify(info)
   });

   return await res2.json();

}

// agrega un usuario
const addNewUserForm = async (dom_cod, op) => {

   const info_err = document.getElementById('info-err-add-service-users');

   const dataservice = await addServiceUsers(dom_cod, op);

   const res = await aprovisiona(dataservice, op);

   if (res.status === 'error') {

      info_err.innerHTML = /* html */`
      <h4 class="text-danger text-uppercase">Proceso con errores</h4>
      ${runMSJSystem(res.message)}
      `;
      info_err.hidden = false;

   } else {

      if (res.status_notify === true) {

         const resemail = await Promise.all(res.data_notify.map((element) =>
            sendEmail(element.token, element.dom_cod, element.busua_cod, element.tipo_cod, element.cloud_password, 1)
         ));

         resemail.forEach((element) => {
            res.message.push(element.message);
         });

      }

      await changeFormAddUsers(dom_cod, op === 1 ? 0 : 1);

      await showRespuestaPerso(res.message);

   }

}

// valida el servicio entregado
const validateService = (op, service, busua_cod = 0) => {

   let id = '';

   switch (op) {
      case 1:
         id = `all-service-acc`;
         break;
      case 2:
         id = `acc-button-panic-${busua_cod}`;
         break;
      case 3:
         id = `acc-other-products-${busua_cod}`;
         break;
      case 4:
         id = `acc-tracker-${busua_cod}`;
         break;
   }

   cleanInputError('err-tipo-cod-service-add');

   if (op !== 1) {
      cleanInputError(`err-causa-tracker-${busua_cod}`);
      cleanInputError(`err-localizacion-service-${busua_cod}`);
      cleanInputError(`err-mac-service-add`);
   }

   if (service === 0) {
      statusMSJ(document.getElementsByName('err-tipo-cod-service-add')[0], 'Seleccione un servicio.', false, false);
      return false;
   }

   const allservice = document.getElementById(id);

   const accordion = allservice.querySelectorAll(`div[name="accordion-services"][data-value="${service}"]`);

   if (accordion.length > 0) {
      statusMSJ(document.getElementsByName('err-tipo-cod-service-add')[0], 'Ya tiene agregado este servicio.', false, false);
      return false;
   }

   switch (service) {

      case 2:

         if (op !== 1) {

            if (document.getElementById(`localizacion-service-${busua_cod}`).value.length < 3) {

               statusMSJ(document.getElementsByName(`err-localizacion-service-${busua_cod}`)[0], 'Debe ingresar una localizaci&oacute;n', false, false);
               return false;

            }

            if (document.getElementById(`mac-service-${busua_cod}`).value.length < 1) {

               statusMSJ(document.getElementsByName(`err-mac-service-add`)[0], 'Formato Mac inv&aacute;lido.', false, false);
               return false;

            }

         }

         break;

      case 5:

         if (allservice.querySelectorAll(`div[name="accordion-services"][data-value="${5}"]`).length > 0) {

            statusMSJ(document.getElementsByName('err-tipo-cod-service-add')[0], 'Este servicio no puede ser agregado.', false, false);
            return false;

         }

         if (op !== 1) {

            if (document.getElementById(`causa-tracker-${busua_cod}`).value.length < 1) {

               statusMSJ(document.getElementsByName(`err-causa-tracker-${busua_cod}`)[0], 'Debe ingrese una causa', false, false);
               return false;

            }

         }

         break;

   }

}

// config menu service
const configMenuService = (newservice, op) => {

   let config = '';

   // formularios
   switch (newservice) {
      case 1:
         config = `
         <div class="d-flex align-items-center justify-content-between pt-2">
            <span class="fw-semibold">Este servicio no necesita una configuraci&oacute;n adicional.</span>
            <div class="row">
               <div class="col-auto">
                  <label class="form-label" for="notify-service-add-${newservice}">Notificar servicio:</label>
               </div>
               <div class="col-auto">
                  <select class="form-select form-select-sm mb-3" id="notify-service-add-${newservice}" name="notify-service-add" title="Notifica servicio">
                     <option value="0" selected>NO</option>
                     <option value="1">SI</option>
                  </select>
               </div>
            </div>
         </div>
         `;
         break;
      case 2:
         if (op === 1) {
            config = `
            <div class="d-flex align-items-center justify-content-between pt-2">
               <span class="fw-semibold">Este servicio necesita los siguientes campos para ser configurado:</span>
               <div class="row">
                  <div class="col-auto">
                     <label class="form-label" for="notify-service-add-${newservice}">Notificar servicio:</label>
                  </div>
                  <div class="col-auto">
                     <select class="form-select form-select-sm mb-3" id="notify-service-add-${newservice}" name="notify-service-add" title="Notifica servicio">
                        <option value="0" selected>NO</option>
                        <option value="1">SI</option>
                     </select>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-lg-3">
                  <label for="localizacion-service-add" class="form-label">Localizaci&oacute;n:</label>
                  <input type="text" class="form-control" id="localizacion-service-add" minlenght="3" maxlength="120" placeholder="Ingrese una localizacion" aria-describedby="localizacion-service-add-text" title="Localizacion del bot&oacute;n para el usuario">
                  <div id="localizacion-service-add-help" name="err-localizacion-service-add" class="form-text" hidden></div>
               </div>
               <div class="col-lg-3">
                  <label for="mac-service-add" class="form-label">Mac:</label>
                  <input type="text" class="form-control" id="mac-service-add" pattern="^([0-9A-Fa-f]){12}$" maxlength="12" placeholder="Ingrese una mac" aria-describedby="mac-service-add-text" title="Mac del bot&oacute;n para el usuario est&aacute;tico" autocomplete="off" onkeyup="validateMac(event, 0)">
                  <div id="mac-service-add-help" name="err-mac-service-add" class="form-text" hidden></div>
               </div>
            </div>
            `;
         }
         if (op === 2) {
            config = `
            <div class="d-flex align-items-center justify-content-between pt-2">
               <span class="fw-semibold">Este servicio necesita los siguientes campos en el CSV:</span>
               <div class="row">
                  <div class="col-auto">
                     <label class="form-label" for="notify-service-add-${newservice}">Notificar servicio:</label>
                  </div>
                  <div class="col-auto">
                     <select class="form-select form-select-sm mb-3" id="notify-service-add-${newservice}" name="notify-service-add" title="Notifica servicio">
                        <option value="0" selected>NO</option>
                        <option value="1">SI</option>
                     </select>
                  </div>
               </div>
            </div>
            <div class="col-lg-3">
               <ul>
                  <li>LOCALIZACI&Oacute;N</li>
                  <li>MAC</li>
               </ul>
            </div>
            `;
         }
         break;
      case 3:
         config = `
         <div class="d-flex align-items-center justify-content-between pt-2">
            <span class="fw-semibold">Este servicio no necesita una configuraci&oacute;n adicional.</span>
            <div class="row">
               <div class="col-auto">
                  <label class="form-label" for="notify-service-add-${newservice}">Notificar servicio:</label>
               </div>
               <div class="col-auto">
                  <select class="form-select form-select-sm mb-3" id="notify-service-add-${newservice}" name="notify-service-add" title="Notifica servicio">
                     <option value="0" selected>NO</option>
                     <option value="1">SI</option>
                  </select>
               </div>
            </div>
         </div>
         `;
         break;
      case 4:
         config = `
         <div class="d-flex align-items-center justify-content-between pt-2">
            <span class="fw-semibold">Este servicio no necesita una configuraci&oacute;n adicional.</span>
            <div class="row">
               <div class="col-auto">
                  <label class="form-label" for="notify-service-add-${newservice}">Notificar servicio:</label>
               </div>
               <div class="col-auto">
                  <select class="form-select form-select-sm mb-3" id="notify-service-add-${newservice}" name="notify-service-add" title="Notifica servicio">
                     <option value="0" selected>NO</option>
                     <option value="1">SI</option>
                  </select>
               </div>
            </div>
         </div>
         `;
         break;
      case 5:
         if (op === 1) {
            config = `
            <div class="d-flex align-items-center justify-content-between pt-2">
               <span class="fw-semibold">Este servicio necesita los siguientes campos para ser configurado:</span>
               <div class="row">
                  <div class="col-auto">
                     <label class="form-label" for="notify-service-add-${newservice}">Notificar servicio:</label>
                  </div>
                  <div class="col-auto">
                     <select class="form-select form-select-sm mb-3" id="notify-service-add-${newservice}" name="notify-service-add" title="Notifica servicio">
                        <option value="0" selected>NO</option>
                        <option value="1">SI</option>
                     </select>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-lg-3">
                  <label for="tipo-tracker-cod" class="form-label">Tipo Tracker:</label>
                  <select class="form-select" id="tipo-tracker-cod" name="tipo-tracker-cod" title="Tipo de tracker">
                     <option value="1">Tracker Bot&oacute;n - V&iacute;ctima</option>
                     <option value="2">Tracker Bot&oacute;n - Agresor</option>
                  </select>
                  <div id="tipo-tracker-cod-help" name="err-tipo-tracker-cod" class="form-text" hidden></div>
               </div>
               <div class="col-lg-3">
                  <label for="causa-tracker" class="form-label">Causa:</label>
                  <input type="text" class="form-control" id="causa-tracker" placeholder="Ingrese una causa" minlenght="1" aria-describedby="causa-tracker-text" title="Causa de tracker" autocomplete="off">
                  <div id="causa-tracker-help" name="err-causa-tracker-add" class="form-text" hidden></div>
               </div>
            </div>
            `;
         }
         if (op === 2) {
            config = `
            <div class="d-flex align-items-center justify-content-between pt-2">
               <span class="fw-semibold">Este servicio necesita los siguientes campos en el CSV:</span>
               <div class="row">
                  <div class="col-auto">
                     <label class="form-label" for="notify-service-add-${newservice}">Notificar servicio:</label>
                  </div>
                  <div class="col-auto">
                     <select class="form-select form-select-sm mb-3" id="notify-service-add-${newservice}" name="notify-service-add" title="Notifica servicio">
                        <option value="0" selected>NO</option>
                        <option value="1">SI</option>
                     </select>
                  </div>
               </div>
            </div>
            <div class="col-lg-3">
               <ul>
                  <li>TIPO_TRACKER (1: V&iacute;ctima, 2:Agresor)</li>
                  <li>CAUSA</li>
               </ul>
            </div>
            `;
         }
         break;
      case 6:
         config = `
         <div class="d-flex align-items-center justify-content-between pt-2">
            <span class="fw-semibold">Este servicio no necesita una configuraci&oacute;n adicional.</span>
            <div class="row">
               <div class="col-auto">
                  <label class="form-label" for="notify-service-add-${newservice}">Notificar servicio:</label>
               </div>
               <div class="col-auto">
                  <select class="form-select form-select-sm mb-3" id="notify-service-add-${newservice}" name="notify-service-add" title="Notifica servicio">
                     <option value="0" selected>NO</option>
                     <option value="1">SI</option>
                  </select>
               </div>
            </div>
         </div>
         `;
         break;
      default:
         config = `
         <div class="mb-3">
            <span class="fw-semibold">Error: Servicio no encontrado.</span>
         </div>
         `;
         break;
   }

   return config;

}

// elimina una pre reserva
const deletePreService = (service) => {

   try {

      const id = service.id.replaceAll('delete-service-', '');
      const allservice = document.getElementById('all-service-acc');
      const accordion = allservice.querySelectorAll(`div[name="accordion-services"][data-value="${id}"]`);
      accordion[0].remove();

   } catch (error) {

      console.error(`Error: ${error}`);
      showToastError(error_system);

   }

}

// agrega un servicio a la interfaz
const confMenuPreService = (op) => {

   return new Promise((resolve, reject) => {

      setTimeout(() => {

         const newservice = document.getElementById('tipo-cod-service-add');  // nuevo servicio
         const allservice = document.getElementById('all-service-acc');       // todos los accordion

         if (validateService(1, parseInt(newservice.value)) === false) {

            reject('Proceso no valido');

         } else {

            allservice.insertAdjacentHTML('beforeend', `
            <div class="accordion-item" name="accordion-services" data-value="${newservice.value}">
               <h2 class="accordion-header d-flex align-items-center">
                  <img class="link-pointer" src="https://${document.domain}/img/icon-trash-delete.png" width="27" height="27" id="delete-service-${newservice.value}" name="delete-service-${newservice.value}" onclick="deletePreService(this)" title="Eliminar servicio">
                  <button class="accordion-button bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#panel-service-${newservice.value}" aria-expanded="true" aria-controls="panel-service-${newservice.value}">
                     ${newservice.querySelector(`option[value="${newservice.value}"]`).innerHTML}
                  </button>
               </h2>
               <div id="panel-service-${newservice.value}" class="accordion-collapse collapse show">
                  <div class="accordion-body">
                  ${configMenuService(parseInt(newservice.value), op)}
                  </div>
               </div>
            </div>
            `);

            resolve(true);

         }

      }, 300);

   });

}

// agrega un nuevo pre servicio
const addPreService = async (op) => {

   try {

      /* 
         1 - Botón de emergencia SIP - Móvil
         2 - Botón de emergencia SIP - Estático
         3 - Botón de emergencia Estándar
         4 - Widget
         5 - Tracker
      */

      await spinnerOpen('btn-add-pre-service');

      await confMenuPreService(op);

   } catch (error) {

      console.warn(`Error: ${error}`);

   } finally {

      await spinnerClose('btn-add-pre-service', 'Agregar servicio');

   }

}

// cambia el tipo de formulario
const addOneUser = async (res, dom_cod) => {

   return new Promise((resolve, reject) => {

      setTimeout(() => {

         const btn_aprov = document.getElementById(`textbtn-btn-menu-aprov-${dom_cod}`);
         const content = document.getElementById(`content-add-users-bp-${dom_cod}`);

         btn_aprov.innerHTML = '';
         content.innerHTML = '';

         content.innerHTML += /*html */ `
         <div class="col-12 border rounded p-3 bg-white">
            <form class="row" id="form-add-service-users-${res.dom_cod}" name="form-add-service-users-${res.dom_cod}" autocomplete="off">
               <div class="col-12">
                  <h4>Formulario un Usuario</h4>
               </div>
               <div class="col-12 mb-3">
                  <span class="fs-5"><u>1. Datos usuario.</u></span>
               </div>
               <div class="col-lg-4 mb-3">
                  <label for="name-user-service-add" class="form-label">Nombre:</label>
                  <input type="text" class="form-control" id="name-user-service-add" minlenght="3" maxlength="120" placeholder="Ingrese un nombre para el Usuario" aria-describedby="name-user-service-add-text" title="Nombre para el Usuario" autocomplete="off">
                  <div id="name-user-service-add-help" name="err-name-user-service-add" class="form-text" hidden></div>
               </div>
               <div class="col-lg-4 mb-3">
                  <label for="cloud-username-service-add" class="form-label">Cloud Username:</label>
                  <div class="input-group">
                     <input type="text" class="form-control" id="cloud-username-service-add" minlenght="3" maxlength="120" placeholder="Ingrese un Cloud Username" aria-describedby="cloud-username-service-text" title="Cloud Username para el Usuario"  onkeydown="return onlyCloudUsername(event)" autocomplete="off">
                     <span class="input-group-text" id="domain-user-service-add">@${res.dominio_usuario}</span>
                  </div>
                  <div id="cloud-username-service-add-help" name="err-cloud-username-service-add" class="form-text" hidden></div>
               </div>
               <div class="col-lg-4 mb-3">
                  <label for="cloud-password-service-add" class="form-label">Cloud Password:</label>
                  <div class="row">
                     <div class="col-7 pe-0">
                        <input type="text" class="form-control" id="cloud-password-service-add" minlenght="3" maxlength="120" aria-describedby="cloud-password-add-text" title="Cloud Password para el Usuario">
                     </div>
                     <div class="col-5 ps-0">
                        <button type="button" class="btn buttons colors-font w-100" id="generate-cloud-password" name="generate-cloud-password" onclick="generateCloudPassword('cloud-password-service-add')">Autogenerar</button>
                     </div>
                  </div>
                  <div id="cloud-password-service-add-help" name="err-cloud-password-service-add" class="form-text" hidden></div>
               </div>
               <div class="col-lg-4 mb-3">
                  <label for="user-phone-service-add" class="form-label">Tel&eacute;fono Usuario:</label>
                  <div class="input-group">
                     <span class="input-group-text" id="num-tel-add">56</span>
                     <input type="text" class="form-control" id="user-phone-service-add" minlenght="9" maxlength="11" placeholder="EJ1: 212345678 - EJ2: 912345678" onkeydown="onlyNumbers(event)" aria-describedby="user-phone-service-add-text" title="Tele&eacute;fono para el Usuario" autocomplete="off">
                  </div>
                  <div id="user-phone-service-add-help" name="err-user-phone-service-add" class="form-text" hidden></div>
               </div>
               <div class="col-lg-4 mb-3">
                  <label for="email-service-add" class="form-label">Correo:</label>
                  <input type="text" class="form-control" id="email-service-add" minlenght="3" maxlength="120" placeholder="Ingrese un Email" aria-describedby="email-service-add-text" title="Email para el Usuario" autocomplete="off">
                  <div id="email-service-add-help" name="err-email-service-add" class="form-text" hidden></div>
               </div>
               <div class="col-lg-4 mb-3">
                  <label for="group-cod-service-add" class="form-label">Contact Center:</label>
                  <select class="form-select" id="group-cod-service-add" aria-describedby="group-cod-service-add-text" title="Grupo del Usuario">
                     <option value="0">Seleccion un Contact Center...</option>
                  </select>
                  <div id="group-cod-service-add-help" name="err-group-cod-service-add" class="form-text" hidden></div>
               </div>
               <hr>
               <div class="col-12 mb-3">
                  <span class="fs-5"><u>2. Servicios.</u></span><span class="fs-6">&nbsp;Opcional</span>
               </div>
               <div class="col-lg-3 mb-3">
                  <select class="form-select" id="tipo-cod-service-add" aria-describedby="tipo-cod-service-add-text" title="Tipo de Servicio">
                     <option value="0">Seleccion un tipo de servicio...</option>
                  </select>
                  <div id="tipo-cod-service-add-help" name="err-tipo-cod-service-add" class="form-text" hidden></div>
               </div>
               <div class="col-lg-3 mb-3">
                  <button type="button" class="btn buttons colors-font" id="btn-add-pre-service" name="btn-add-pre-service" onclick="addPreService(1)"> 
                     <div id="spinner-btn-add-pre-service" class="spinner-border spinner-border-sm text-white" role="status" hidden>
                        <span class="visually-hidden">Loading...</span>
                     </div>
                     <span id="textbtn-btn-add-pre-service">Agregar servicio</span>
                  </button>
               </div>
               <div class="col-12 mb-3" id="all-services">
                  <div class="accordion" id="all-service-acc"></div>
               </div>
               <div class="col-12 p-3 border mb-3 bg-danger-subtle rounded" id="info-err-add-service-users" hidden></div>
               <div class="col-12 d-flex justify-content-end mb-3">
                  <button type="submit" class="btn buttons colors-font" id="btn-add-users" name="btn-add-users"> 
                     <div id="spinner-btn-add-users" class="spinner-border spinner-border-sm text-white" role="status" hidden>
                        <span class="visually-hidden">Loading...</span>
                     </div>
                     <span id="textbtn-btn-add-users">Ingresar</span>
                  </button>
               </div>
            </form>
         </div>
         `;

         res.groups.forEach(element => {
            document.getElementById('group-cod-service-add').innerHTML += /* html */ `
            <option value="${element.group_cod}">${element.nombre}</option>
            `;
         });

         res.tipoBs.forEach(element => {
            document.getElementById('tipo-cod-service-add').innerHTML += /* html */ `
            <option value="${element.tipo_cod}">${element.tipo}</option>
            `;
         });

         generateCloudPassword('cloud-password-service-add');

         btn_aprov.innerHTML += 'Formulario un Usuario';

         document.getElementById(`form-add-service-users-${res.dom_cod}`).addEventListener('submit', async e => {

            try {

               e.preventDefault();

               await spinnerOpen('btn-add-users');

               await addNewUserForm(parseInt(res.dom_cod), 0);

            } catch (error) {

               console.warn(`Error: ${error}`);

            } finally {

               await spinnerClose('btn-add-users', 'Ingresar');

            }

         });

         resolve(true);

      }, 300);

   });

}

// configuracion de menu csv
const addUserCSV = async (res, dom_cod) => {

   return new Promise((resolve, reject) => {

      setTimeout(() => {

         const btn_aprov = document.getElementById(`textbtn-btn-menu-aprov-${dom_cod}`);
         const content = document.getElementById(`content-add-users-bp-${dom_cod}`);

         btn_aprov.innerHTML = '';
         content.innerHTML = '';

         content.innerHTML += /*html */ `
         <div class="col-12">
            <form class="row" id="form-add-service-users-${res.dom_cod}" name="form-add-service-users-${res.dom_cod}" enctype="multipart/form-data">
               <div class="col-12">
                  <h5>Formulario Masivo</h5>
               </div>
               <div class="col-12 d-flex justify-content-between mb-3">
                  <span class="fs-5"><u>1. Adjuntar CSV.</u></span>
                  <a class="link-pointer text-primary" id="csv-${res.dom_cod}" name="csv-${res.dom_cod}" title="Formato de CSV">Descargar formato CSV</a>
               </div>
               <div class="col-lg-4 mb-3">
                  <label for="csv-file-add-service-users-${res.dom_cod}" class="form-label">Selecciona el archivo CSV para subir</label>
                  <input class="form-control" type="file" id="csv-file-add-service-users-${res.dom_cod}" name="files" title="CSV con Usuarios a subir" onchange="handleFileSelect(event, 1)" required>
                  <div id="csv-file-add-service-users-help" name="err-csv-add-service-users" class="form-text" hidden></div>
               </div>
               <div class="col-lg-4 mb-3">
                  <label for="group-cod-service-add" class="form-label">Contact Center:</label>
                  <select class="form-select" id="group-cod-service-add" aria-describedby="groud-cod-add" title="Grupo del Usuario">
                     <option value="0">Seleccion un Contact Center...</option>
                  </select>
                  <div id="group-cod-service-add-help" name="err-group-cod-service-add" class="form-text" hidden></div>
               </div>
               <hr>
               <div class="col-12 mb-3">
                  <span class="fs-5"><u>2. Servicios.</u></span><span class="fs-6">&nbsp;Opcional</span>
               </div>
               <div class="col-lg-3 mb-3">
                  <select class="form-select" id="tipo-cod-service-add" aria-describedby="tipo-cod-service-add-text" title="Tipo de Servicio">
                     <option value="0">Seleccion un tipo de servicio...</option>
                  </select>
                  <div id="tipo-cod-service-add-help" name="err-tipo-cod-service-add" class="form-text" hidden></div>
               </div>
               <div class="col-lg-3 mb-3">
                  <button type="button" class="btn buttons colors-font" id="btn-add-pre-service" name="btn-add-pre-service" onclick="addPreService(2)"> 
                     <div id="spinner-btn-add-pre-service" class="spinner-border spinner-border-sm text-white" role="status" hidden>
                        <span class="visually-hidden">Loading...</span>
                     </div>
                     <span id="textbtn-btn-add-pre-service">Agregar servicio</span>
                  </button>
               </div>
               <div class="col-12 mb-3" id="all-services">
                  <div class="accordion" id="all-service-acc"></div>
               </div>
               <div class="col-12 p-3 border mb-3 bg-danger-subtle rounded" id="info-err-add-service-users" hidden></div>
               <div class="col-12 d-flex justify-content-end mb-3">
                  <button type="submit" class="btn buttons colors-font" id="btn-add-users" name="btn-add-users"> 
                     <div id="spinner-btn-add-users" class="spinner-border spinner-border-sm text-white" role="status" hidden>
                        <span class="visually-hidden">Loading...</span>
                     </div>
                     <span id="textbtn-btn-add-users">Ingresar Usuarios</span>
                  </button>
               </div>
               <input type="hidden" id="status_csv" value="0">
            </form>
            <div class="row">
               <div id="outputservicecreate" class="table-responsive"></div>
            </div
         </div>
         `;

         res.groups.forEach(element => {
            document.getElementById('group-cod-service-add').innerHTML += /* html */ `
            <option value="${element.group_cod}">${element.nombre}</option>
            `;
         });

         res.tipoBs.forEach(element => {
            document.getElementById('tipo-cod-service-add').innerHTML += /* html */ `
            <option value="${element.tipo_cod}">${element.tipo}</option>
            `;
         });

         btn_aprov.innerHTML += 'Formulario masivo Usuarios .CSV';

         document.getElementById(`csv-${res.dom_cod}`).onclick = (e) => workingLib([['NOMBRE', 'CLOUD_USERNAME', 'CLOUD_PASSWORD', 'TELEFONO_USUARIO', 'CORREO']], res.dominio_usuario, 'APROVISIONAMIENTO_USUARIOS', 'Formato aprovisionamiento de usuarios');

         document.getElementById(`form-add-service-users-${res.dom_cod}`).addEventListener('submit', async e => {

            try {

               e.preventDefault();
               await spinnerOpen('btn-add-users');
               await addNewUserForm(parseInt(res.dom_cod), 1);

            } catch (error) {

               console.warn(`Error: ${error}`);

            } finally {

               await spinnerClose('btn-add-users', 'Ingresar');

            }

         });

         resolve(true);

      }, 300);

   });

}

// cambia formulario de aprovisionamiento CSV/UN USUARIO
const changeFormAddUsers = async (dom_cod, op = null) => {

   try {

      await spiner_menu_open(`spinner-menu-aprov-${dom_cod}`);

      const res = await dataDomain(dom_cod);

      if (res.status === 'error') {

         await showToastError(res.message);

      } else {

         document.getElementById('count-users-info').innerHTML = `${res.cantidad} / <span class="fw-semibold">${res.cantidad_usuario}</span>`;

         if (op !== null) {

            if (op == 0) {

               localStorage.removeItem(`menu_aprov_oper_${dom_cod}`);
               localStorage.setItem(`menu_aprov_oper_${dom_cod}`, 0);

               await addUserCSV(res, dom_cod);

            } else {

               localStorage.removeItem(`menu_aprov_oper_${dom_cod}`);
               localStorage.setItem(`menu_aprov_oper_${dom_cod}`, 1);

               await addOneUser(res, dom_cod);

            }

         } else {

            if (JSON.parse(localStorage.getItem(`menu_aprov_oper_${dom_cod}`)) == 0) {

               localStorage.removeItem(`menu_aprov_oper_${dom_cod}`);
               localStorage.setItem(`menu_aprov_oper_${dom_cod}`, 0);

               await addUserCSV(res, dom_cod);

            } else {

               localStorage.removeItem(`menu_aprov_oper_${dom_cod}`);
               localStorage.setItem(`menu_aprov_oper_${dom_cod}`, 1);

               await addOneUser(res, dom_cod);

            }

         }

      }

   } catch (error) {

      console.error(`Error: ${error}`);
      await showError(error_system);

   } finally {

      await spiner_menu_close(`spinner-menu-aprov-${dom_cod}`);

   }

}

// graficos de usuarios aprovisionados
const userctx = async (usua_cod) => {
   try {
      return new Promise((resolve, reject) => {
         setTimeout(() => {
            let menu = document.getElementById(`reports`);
            fetch(`${url_operator}/json/reportsJSON/json_users.php?usua_cod=${usua_cod}`, {
               method: 'GET',
               headers: {
                  'Content-Type': 'application/json'
               }
            })
               .then(response => response.json())
               .then(async (response) => {
                  if (response.status === 'error') {
                     await spiner_menu_close(`spinner-menu-title`);
                     menu.innerHTML = `${response.message}`;
                     reject(false);
                  } else {
                     resolve(response);
                  }
               }).catch(async (error) => {
                  await spiner_menu_close(`spinner-menu-title`);
                  menu.innerHTML = `${data_error}`;
                  console.error(`Error: ${error}`);
               });
         }, 200);
      });
   } catch (error) {
      console.error(`Error: ${error}`);
      showError(error_system);
   }
}

// graficos de usuarios aprovisionados
const alertctx = async (usua_cod, anio, tipoa_cod = 0, causa_cod = -1, deriv = -1) => {
   try {
      return new Promise((resolve, reject) => {
         setTimeout(() => {
            const menu = document.getElementById(`reports`);
            fetch(`${url_operator}/json/reportsJSON/json_alerts.php?usua_cod=${usua_cod}&anio=${anio}&tipoa_cod=${tipoa_cod}&causa_cod=${causa_cod}&deriv=${deriv}`, {
               method: 'GET',
               headers: {
                  'Content-Type': 'application/json'
               }
            })
               .then(response => response.json())
               .then(async (response) => {
                  if (response.status === 'error') {
                     await spiner_menu_close(`spinner-menu-title`);
                     menu.innerHTML = `${response.message}`;
                     reject(false);
                  } else {
                     resolve(response);
                  }
               }).catch(async (error) => {
                  await spiner_menu_close(`spinner-menu-title`);
                  menu.innerHTML = `${data_error}`;
                  console.error(`Error: ${error}`);
               });
         }, 200);
      });
   } catch (error) {
      console.error(`Error: ${error}`);
      showError(error_system);
   }
}

// obtiene informacion de una alerta
const dataOneAlert = async (alert_cod) => {
   try {
      return new Promise((resolve, reject) => {
         setTimeout(() => {
            fetch(`${url_operator}/json/reportsJSON/json_alert.php?alert_cod=${alert_cod}`, {
               method: 'GET',
               headers: {
                  'Content-Type': 'application/json'
               }
            })
               .then(response => resolve(response.json()))
               .catch(error => reject(error));
         }, 300);
      });
   } catch (error) {
      console.error(`Error: ${error}`);
      showError(error_system);
   }
}

// modal, mapa alerta
const viewAlertMap = async (data) => {
   try {
      return new Promise((resolve, reject) => {
         setTimeout(() => {
            const host = window.location.host;
            const createAt = new Date(data.fecha_creacion);
            const attentionAt = new Date(data.fecha_atencion);
            const coord = data.posicion.split(';');
            let info_deri = '';
            data.data_derivacion.forEach(element => info_deri += `${element.descripcion}<br>`)
            document.getElementById('modal-titlep').innerHTML = 'Detalle de la alerta';
            let html = /* html */ `
            <div class="container">
               <div class="row">
                  <div class="col-12 mb-2 pb-2 d-flex justify-content-start align-items-center border-bottom">
                     <span class="fs-4 fw-semibold">${data.nombre}</span>
                     <a id="report-alert-one" name="report-alert-one" class="fa-solid fa-file-excel fa-xl link-pointer text-decoration-none lh-1 color-incons ms-3" data-bs-toggle="tooltipInfoAlertOne" data-bs-html="true" data-bs-title="Reporte de la alerta" title="Reporte de la alerta"></a>
                  </div>
                  <div class="col-lg-5">
                     <div id="iframe-div-console" class="row" style="height:65vh; width:100%;">
                        <iframe src="https://${host}:9025/api/mapa?lat=${coord[0]}&lon=${coord[1]}" title="Dashboard" scrolling="no"></iframe>
                     </div>
                  </div>
                  <div class="col-lg-7">
                     <div class="row">
                        <div class="col-12">
                           <span class="fs-5 fw-semibold">Datos de creaci&oacute;n</span>
                        </div>
                        <div class="col-12">
                           <table class="table">
                              <tbody>
                                 <tr>
                                    <td class="py-2">Fecha</td>
                                    <td class="text-end py-2">${createAt.getDate()}-${(createAt.getMonth() + 1) < 10 ? `0${(createAt.getMonth() + 1)}` : (createAt.getMonth() + 1)}-${createAt.getFullYear()}</td>
                                 </tr>
                                 <tr>
                                    <td class="py-2">Hora</td>
                                    <td class="text-end py-2">${data.fecha_creacion.substring(11, 19)}</td>
                                 </tr>
                                 <tr>
                                    <td class="py-2">Direccion</td>
                                    <td class="text-end py-2">${data.address} ${data.amenity === '' ? '' : `-${data.amenity}`}</td>
                                 </tr>
                                 <tr>
                                    <td class="py-2">Estado</td>
                                    <td class="text-end py-2">${data.activa_desc}</td>
                                 </tr>
                              </tbody>
                           </table>
                        </div>
                        <div class="col-12">
                           <span class="fs-5 fw-semibold">Datos de atenci&oacute;n</span>
                        </div>
                        <div class="col-12">
                           ${data.activa === '0' ? data.activa_desc : `
                           <table class="table">
                              <tbody>
                                 <tr>
                                    <td class="py-2">Causa</td>
                                    <td class="text-end py-2">${data.causa}</td>
                                 </tr>
                                 <tr>
                                    <td class="py-2">Fecha</td>
                                    <td class="text-end py-2">${attentionAt.getDate()}-${(attentionAt.getMonth() + 1) < 10 ? `0${(attentionAt.getMonth() + 1)}` : (attentionAt.getMonth() + 1)}-${attentionAt.getFullYear()}</td>
                                 </tr>
                                 <tr>
                                    <td class="py-2">Hora</td>
                                    <td class="text-end py-2">${data.fecha_atencion.substring(11, 19)}</td>
                                 </tr>
                                 <tr>
                                    <td class="py-2">Derivaci&oacute;n</td>
                                    <td class="text-end py-2">${info_deri}</td>
                                 </tr>
                              </tbody>
                           </table>
                           `}
                        </div>
                        <div class="col-12">
                           <div class="form-check form-check-inline">
                           </div>
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                           <button type="button" class="btn buttons color-font" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            `;
            document.getElementById('modalp').innerHTML = html;
            document.getElementById('report-alert-one').onclick = () => reportSheet(data, document.getElementById('du').value, 'Detalle de alerta', 6);
            tooltipSystem('tooltipInfoAlertOne');
            setTimeout(() => {
               $('#modalopen').modal('show');
               resolve(data);
            }, 1000)

         }, 300);
      });
   } catch (error) {
      console.error(`Error: ${error}`);
      showError(error_system);
   }
}

// muestra modal de alerta
const viewModalChartAlert = async (alert_cod) => {
   try {
      await showLoadingSystem('');
      const data = await dataOneAlert(alert_cod);
      if (data.status === 'error') {
         showError(data.message);
      } else {
         await viewAlertMap(data.data_alert[0]);
      }
      await Swal.close();
   } catch (error) {
      console.error(`Error: ${error}`);
      showError(error_system);
   }
}

// informacion de alertas
const infoAlertsDiv = (data, month, monthdesc) => {
   try {
      setTimeout(() => {
         const div = document.getElementById('result-alert-chart');
         const result_alerts = () => {
            let alerts = '';
            data.forEach(element => {
               const createAt = new Date(element.fecha_creacion);
               const attentionAt = new Date(element.fecha_atencion);
               const attentionAtDesc = element.fecha_atencion === null ? '-' : `${attentionAt.getDate()}-${(attentionAt.getMonth() + 1) < 10 ? `0${(attentionAt.getMonth() + 1)}` : (attentionAt.getMonth() + 1)}-${attentionAt.getFullYear()} ${element.fecha_atencion.substring(11, 19)}`;
               if ((createAt.getMonth() + 1) === month) {
                  alerts += `
                  <tr>
                     <td>${element.alert_cod}</td>
                     <td>${element.activa_desc}</td>
                     <td>${element.tipo_alerta}</td>
                     <td>${element.cloud_username}@${element.cloud_username}</td>
                     <td class="text-center">${createAt.getDate()}-${(createAt.getMonth() + 1) < 10 ? `0${(createAt.getMonth() + 1)}` : (createAt.getMonth() + 1)}-${createAt.getFullYear()} ${element.fecha_creacion.substring(11, 19)}</td>
                     <td class="text-center">${attentionAtDesc}</td>
                     <td class="text-center">
                        <button id="btn-view-alert-chart" name="btn-view-alert-chart" title="Ver detalle de la alerta" class="btn btn-sm buttons colors-font" onclick="viewModalChartAlert(${element.alert_cod})">Ver</button>
                     </td>
                  </tr>
                  `;
               }
            });
            return alerts;
         }
         div.innerHTML = '';
         div.innerHTML += /* html */ `
         <div class="row border-bottom">
            <div class="col-12 mb-3">
               <span class="fs-4 fw-semibold">Mes de ${monthdesc}</span>
               <a id="report-alert-month" name="report-alert-month" class="fa-solid fa-file-excel fa-xl link-pointer text-decoration-none lh-1 color-incons ms-3" data-bs-toggle="tooltipInfoAlertMonth" data-bs-html="true" data-bs-title="Reporte mensual (${monthdesc}) de alertas" title="Reporte mensual (${monthdesc}) de alertas"></a>
            </div>
            <table class="col-12">
               <thead>
                  <tr>
                     <th>Id</th>
                     <th>Estado</th>
                     <th>Tipo</th>
                     <th>Usuario</th>
                     <th>Fecha creaci&oacute;n</th>
                     <th>Fecha atenci&oacute;n</th>
                     <th>Acci&oacute;n</th>
                  </tr>
               </thead>
               <tbody>
               ${result_alerts()}
               </tbody>
            </table>
         </div>`;
         document.getElementById('report-alert-month').onclick = () => reportSheet(data, document.getElementById('du').value, `Detalle de alertas mes ${monthdesc}`, 5, month);
         tooltipSystem('tooltipInfoAlertMonth');
      }, 300);
   } catch (error) {
      console.error(`Error: ${error}`);
      showError(error_system);
   }
}

// configuracion de menu de reportes
const confReportsMenu = async () => {
   try {
      return new Promise((resolve, reject) => {
         setTimeout(() => {
            let menu = document.getElementById(`reports`);
            menu.innerHTML = '';
            menu.innerHTML += `
            <div class="row">
               <div class="col-12">
                  <div class="d-flex justify-content-center" id="card-info-alerts"></div>
               </div>
            </div>
            <div class="row">
               <div class="col-lg-4">
                  <div class="d-flex justify-content-center" id="card-info-users"></div>
               </div>
               <div class="col-lg-4">
                  <div class="d-flex justify-content-center" id="card-info-ranking-alerts"></div>
               </div>
               <div class="col-lg-4">
                  <div class="d-flex justify-content-center" id="card-info-operators"></div>
               </div>
            </div>
            `;
            resolve(true);
         }, 200);
      });
   } catch (error) {
      console.error(`Error: ${error}`);
      showError(error_system);
   }
}

// reporte de alertas
const reportSheet = (res, du, subject, op, month = 0) => {
   try {
      setTimeout(() => {
         let data;                  // Data
         let aoa = [];              // Data parseada
         let i = 1;                 // Contador
         let attachmenttitle = '';  // Nombre del xlsx
         // Selector
         let select_user = parseInt(document.getElementById(`select-data-users`).value);
         let select_alert = parseInt(document.getElementById('select-date-filter').value);
         let select_operator = parseInt(document.getElementById('select-data-operators').value);
         /*
            1- USUARIOS
            2- ALERTAS RANKING
            3- OPERADORES
            4- ALERTAS ANUAL
         */
         switch (op) {
            case 1:
               // Filrado de usuarios
               data = res.filter((user) => select_user !== 0 ? parseInt(user.esta_cod) === select_user : user);
               // Cabecera - fila 1
               aoa.push(['N°', 'BUSUA_COD', 'CLOUD_USERNAME', 'USER_PHONE', 'NOMBRE', 'FECHA_CREACION', 'ESTADO']);
               data.forEach((user) => {
                  // manejo de estados
                  let estado = '';
                  switch (parseInt(user.esta_cod)) {
                     case 1:
                        estado = 'Activo';
                        break;
                     case 2:
                        estado = 'Inactivo';
                        break;
                     case 3:
                        estado = 'Eliminado';
                        break;
                     default:
                        estado = 'Err';
                        break;
                  }
                  // Data parseada
                  aoa.push([
                     i,
                     user.busua_cod,
                     user.cloud_username + '@' + du,
                     user.user_phone,
                     user.nombre,
                     user.fecha_creacion,
                     estado
                  ]);
                  i++;
               });
               switch (select_user) {
                  case 0:
                     attachmenttitle = 'USUARIOS_PBE';
                     break;
                  case 1:
                     attachmenttitle = 'USUARIOS_ACTIVOS_PBE';
                     break;
                  case 2:
                     attachmenttitle = 'USUARIOS_INACTIVOS_PBE';
                     break;
                  case 3:
                     attachmenttitle = 'USUARIOS_ELIMINADOS_PBE';
                     break;
                  default:
                     attachmenttitle = 'Err';
                     break;
               }
               break;
            case 2:
               // Filtrando alertas
               if (select_alert === 5) {
                  data = filterAlertRanking(res, select_alert, true);
               } else {
                  data = rankingAlert(res, select_alert, true);
               }
               // Cabecera - fila 1
               aoa.push(['N°', 'BUSUA_COD', 'CLOUD_USERNAME', 'USER_PHONE', 'NOMBRE', 'CANTIDAD_ALERTAS']);
               data.forEach((alert) => {
                  // Data parseada
                  aoa.push([
                     i,
                     alert.busua_cod,
                     alert.cloud_username + '@' + du,
                     alert.user_phone,
                     alert.nombre,
                     alert.count
                  ]);
                  i++;
               });
               attachmenttitle = 'RANKING_ALERTAS_USUARIOS';
               spiner_menu_close(`spinner-filter-alert`);
               break;
            case 3:
               // Filrado operadores
               data = res.filter((operator) => select_operator !== 0 ? parseInt(operator.esta_cod) === select_operator : operator);
               // Cabecera - fila 1
               aoa.push(['N°', 'OPER_COD', 'USERNAME', 'EMAIL', 'NOMBRE', 'FECHA_CREACION', 'ESTADO']);
               data.forEach((operator) => {
                  // manejo de estados
                  let estado = '';
                  switch (parseInt(operator.esta_cod)) {
                     case 1:
                        estado = 'Activo';
                        break;
                     case 2:
                        estado = 'Inactivo';
                        break;
                     case 3:
                        estado = 'Eliminado';
                        break;
                     default:
                        estado = 'Err';
                        break;
                  }
                  // Data parseada
                  aoa.push([
                     i,
                     operator.oper_cod,
                     operator.username + '@' + du,
                     operator.email,
                     operator.nombre,
                     operator.fecha_creacion,
                     estado
                  ]);
                  i++;
               });
               switch (select_operator) {
                  case 0:
                     attachmenttitle = 'OPERADORES_PBE';
                     break;
                  case 1:
                     attachmenttitle = 'OPERADORES_ACTIVOS_PBE';
                     break;
                  case 2:
                     attachmenttitle = 'OPERADORES_INACTIVOS_PBE';
                     break;
                  case 3:
                     attachmenttitle = 'OPERADORES_ELIMINADOS_PBE';
                     break;
                  default:
                     attachmenttitle = 'Err';
                     break;
               }
               break;
            case 4:
               // Filtrando alertas anual
               attachmenttitle = 'ALERTAS_ANUAL_FILTRO';
               aoa.push(['ALERT_COD', 'BUSUA_COD', 'USUARIO', 'NOMBRE', 'FECHA_CREACION', 'TIPO_ALERTA', 'ESTADO', 'FECHA_ATENCION', 'POSICION', 'CAUSA', 'DESCRIPCION', 'DERIVACION']);
               res.data_alert.forEach(alerta => {
                  const createAt = new Date(alerta.fecha_creacion);
                  const attentionAt = new Date(alerta.fecha_atencion);
                  const attentionAtDesc = alerta.fecha_atencion === null ? '' : `${attentionAt.getDate()}-${(attentionAt.getMonth() + 1) < 10 ? `0${(attentionAt.getMonth() + 1)}` : (attentionAt.getMonth() + 1)}-${attentionAt.getFullYear()} ${alerta.fecha_atencion.substring(11, 19)}`;
                  let infoderi = '';
                  alerta.data_derivacion.forEach((element) => {
                     infoderi += element.descripcion + ' | ';
                  });
                  // Data parseada
                  aoa.push([
                     alerta.alert_cod,
                     alerta.busua_cod,
                     alerta.cloud_username + '@' + du,
                     alerta.nombre,
                     `${createAt.getDate()}-${(createAt.getMonth() + 1) < 10 ? `0${(createAt.getMonth() + 1)}` : (createAt.getMonth() + 1)}-${createAt.getFullYear()} ${alerta.fecha_creacion.substring(11, 19)}`,
                     alerta.tipo_alerta,
                     alerta.activa_desc,
                     attentionAtDesc,
                     alerta.posicion,
                     alerta.causa_desc,
                     alerta.descripcion,
                     infoderi
                  ]);
               });
               break;
            case 5:
               // Filtrando alertas anual
               attachmenttitle = 'ALERTAS_MENSUAL_FILTRO';
               aoa.push(['ALERT_COD', 'BUSUA_COD', 'USUARIO', 'NOMBRE', 'FECHA_CREACION', 'TIPO_ALERTA', 'ESTADO', 'FECHA_ATENCION', 'POSICION', 'CAUSA', 'DESCRIPCION', 'DERIVACION']);
               res.forEach(alerta => {
                  const createAt = new Date(alerta.fecha_creacion);
                  if ((createAt.getMonth() + 1) === month) {
                     const attentionAt = new Date(alerta.fecha_atencion);
                     const attentionAtDesc = alerta.fecha_atencion === null ? '' : `${attentionAt.getDate()}-${(attentionAt.getMonth() + 1) < 10 ? `0${(attentionAt.getMonth() + 1)}` : (attentionAt.getMonth() + 1)}-${attentionAt.getFullYear()} ${alerta.fecha_atencion.substring(11, 19)}`;
                     let infoderi = '';
                     alerta.data_derivacion.forEach((element) => {
                        infoderi += element.descripcion + ' | ';
                     });
                     // Data parseada
                     aoa.push([
                        alerta.alert_cod,
                        alerta.busua_cod,
                        alerta.cloud_username + '@' + du,
                        alerta.nombre,
                        `${createAt.getDate()}-${(createAt.getMonth() + 1) < 10 ? `0${(createAt.getMonth() + 1)}` : (createAt.getMonth() + 1)}-${createAt.getFullYear()} ${alerta.fecha_creacion.substring(11, 19)}`,
                        alerta.tipo_alerta,
                        alerta.activa_desc,
                        attentionAtDesc,
                        alerta.posicion,
                        alerta.causa_desc,
                        alerta.descripcion,
                        infoderi
                     ]);
                  }
               });
               break;
            case 6:
               // Filtrando alertas anual
               attachmenttitle = 'ALERTA';
               aoa.push(['ALERT_COD', 'BUSUA_COD', 'USUARIO', 'NOMBRE', 'FECHA_CREACION', 'TIPO_ALERTA', 'ESTADO', 'FECHA_ATENCION', 'POSICION', 'CAUSA', 'DESCRIPCION', 'DERIVACION']);
               const createAt = new Date(res.fecha_creacion);
               const attentionAt = new Date(res.fecha_atencion);
               const attentionAtDesc = res.fecha_atencion === null ? '' : `${attentionAt.getDate()}-${(attentionAt.getMonth() + 1) < 10 ? `0${(attentionAt.getMonth() + 1)}` : (attentionAt.getMonth() + 1)}-${attentionAt.getFullYear()} ${res.fecha_atencion.substring(11, 19)}`;
               let infoderi = '';
               res.data_derivacion.forEach((element) => {
                  infoderi += element.descripcion + ' | ';
               });
               // Data parseada
               aoa.push([
                  res.alert_cod,
                  res.busua_cod,
                  res.cloud_username + '@' + du,
                  res.nombre,
                  `${createAt.getDate()}-${(createAt.getMonth() + 1) < 10 ? `0${(createAt.getMonth() + 1)}` : (createAt.getMonth() + 1)}-${createAt.getFullYear()} ${res.fecha_creacion.substring(11, 19)}`,
                  res.tipo_alerta,
                  res.activa_desc,
                  attentionAtDesc,
                  res.posicion,
                  res.causa_desc,
                  res.descripcion,
                  infoderi
               ]);
               break;
            default:
               break;
         }
         workingLib(aoa, du, attachmenttitle, subject, op);
      }, 300);
   } catch (error) {
      console.error(`Error: ${error}`);
      showToastError(error_system);
   }
}

// operatos status
const dataOperatorStatus = (data, du) => {
   try {
      const dataHTML = () => {
         let infoHTML = '';
         let i = 1;
         data.forEach((operator) => {
            let status = '';
            switch (parseInt(operator.esta_cod)) {
               case 1:
                  status = 'Activo';
                  break;
               case 2:
                  status = 'Inactivo';
                  break;
               case 3:
                  status = 'Eliminado';
                  break;
               default:
                  status = 'Error';
                  break;
            }
            infoHTML += `
            <tr>
               <td class="text-end">${i}</td>
               <td class="text-end">${operator.oper_cod}</td>
               <td>${operator.username}@${du}</td>
               <td>${operator.email}</td>
               <td>${operator.nombre}</td>
               <td>${operator.fecha_creacion}</td>
               <td>${status}</td>
            </tr>
            `;
            i++;
         });
         return infoHTML;
      }
      let html = /* html */ `
      <div class="container-fluid">
         <div class="row">
            <div class="col-12 text-start mb-3">
               <h4>Operadores activos</h4>
            </div>
            <div class="col-12 text-start table-responsive">
               <table id="users-active-data" name="users-active-data" class="w-100 pt-2">
                  <thead>
                     <tr>
                        <th class="px-1">N°</th>
                        <th class="px-1">OPER_COD</th>
                        <th class="px-1">USERNAME</th>
                        <th class="px-1">EMAIL</th>
                        <th class="px-1">NOMBRE</th>
                        <th class="px-1">FECHA_CREACION</th>
                        <th class="px-1">ESTADO</th>
                     </tr>
                  </thead>
                  <tbody>
                     ${dataHTML()}
                  </tbody>
               </table>
            </div>
         </div>
      </div>
      `;
      setTimeout(() => {
         showDataTbale('users-active-data');
      }, 300);
      Swal.fire({
         allowEscapeKey: false,
         allowOutsideClick: false,
         position: 'top',
         html,
         showCloseButton: true,
         showConfirmButton: false,
         width: 1400,
      });
   } catch (error) {
      console.error(`Error: ${error}`);
      showError(error_system);
   }
}

// informacion de alerta
const dataAlertsUser = (res, user) => {
   try {
      setTimeout(() => {
         const userf = user.split('@');
         const data = res.filter(user => user.cloud_username === userf[0]);
         const dataHTML = () => {
            let infoHTML = '';
            let i = 1;
            data[0].alerts.forEach((alert) => {
               infoHTML += `
               <tr>
                  <td class="text-end">${i}</td>
                  <td class="text-end">${alert.alert_cod}</td>
                  <td class="text-end">${data[0].busua_cod}</td>
                  <td>${alert.activa_desc}</td>
                  <td>${alert.tipo_alerta}</td>
                  <td>${alert.fecha_creacion}</td>
                  <td>${alert.fecha_atencion === null ? 'Sin registro' : alert.fecha_atencion}</td>
                  <td>${alert.posicion === null ? 'Sin registro' : alert.posicion}</td>
                  <td>${alert.descripcion === null ? 'Sin registro' : alert.descripcion}</td>
                  <td>${alert.causa_desc === null ? 'Sin registro' : alert.causa_desc}</td>
               </tr>
               `;
               i++;
            });
            return infoHTML;
         }
         let html = /* html */ `
         <div class="container-fluid">
            <div class="row">
               <div class="col-12 text-start mb-3">
                  <h4>Alertas de usuario: ${user}</h4>
                  <span></span>
               </div>
               <div class="col-12 text-start table-responsive">
                  <table id="alert-info-${userf[0]}" name="alert-info-${userf[0]}" class="w-100 pt-2">
                     <thead>
                        <tr>
                           <th class="px-1">N°</th>
                           <th class="px-1">ALERT_COD</th>
                           <th class="px-1">BUSUA_COD</th>
                           <th class="px-1">ESTADO</th>
                           <th class="px-1">TIPO_DE_ALERTA</th>
                           <th class="px-1">FECHA_CREACION</th>
                           <th class="px-1">FECHA_ATENCION</th>
                           <th class="px-1">POSICION</th>
                           <th class="px-1">DESCRIPCION</th>
                           <th class="px-1">CAUSA</th>
                        </tr>
                     </thead>
                     <tbody>
                     ${dataHTML()}
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
         `;
         Swal.fire({
            allowEscapeKey: false,
            allowOutsideClick: false,
            position: 'top',
            html,
            showCloseButton: true,
            showConfirmButton: false,
            width: 1400,
         });
      }, 300);
   } catch (error) {
      console.error(`Error: ${error}`);
      showError(error_system);
   }
}

// user estatus - view
const dataUserStatus = (data, du) => {
   try {
      const dataHTML = () => {
         let infoHTML = '';
         let i = 1;
         data.forEach((user) => {
            let status = '';
            switch (parseInt(user.esta_cod)) {
               case 1:
                  status = 'Activo';
                  break;
               case 2:
                  status = 'Inactivo';
                  break;
               case 3:
                  status = 'Eliminado';
                  break;
               default:
                  status = 'Error';
                  break;
            }
            infoHTML += `
            <tr>
               <td class="text-end">${i}</td>
               <td class="text-end">${user.busua_cod}</td>
               <td>${user.cloud_username}@${du}</td>
               <td class="text-end">${user.user_phone}</td>
               <td>${user.email}</td>
               <td>${user.nombre}</td>
               <td>${user.fecha_creacion}</td>
               <td>${status}</td>
            </tr>
            `;
            i++;
         });
         return infoHTML;
      }
      let html = /* html */ `
      <div class="container-fluid">
         <div class="row">
            <div class="col-12 text-start mb-3">
               <h4>Usuarios activos</h4>
            </div>
            <div class="col-12 text-start table-responsive">
               <table id="users-active-data" name="users-active-data" class="w-100 pt-2">
                  <thead>
                     <tr>
                        <th class="px-1">N°</th>
                        <th class="px-1">BUSUA_COD</th>
                        <th class="px-1">CLOUD_USERNAME</th>
                        <th class="px-1">USER_PHONE</th>
                        <th class="px-1">EMAIL</th>
                        <th class="px-1">NOMBRE</th>
                        <th class="px-1">FECHA_CREACION</th>
                        <th class="px-1">ESTADO</th>
                     </tr>
                  </thead>
                  <tbody>
                     ${dataHTML()}
                  </tbody>
               </table>
            </div>
         </div>
      </div>
      `;
      setTimeout(() => {
         showDataTbale('users-active-data');
      }, 300);
      Swal.fire({
         allowEscapeKey: false,
         allowOutsideClick: false,
         position: 'top',
         html,
         showCloseButton: true,
         showConfirmButton: false,
         width: 1400,
      });
   } catch (error) {
      console.error(`Error: ${error}`);
      showError(error_system);
   }
}

// card user
const cardDataUser = async (data) => {
   return new Promise((resolve, reject) => {
      setTimeout(() => {
         try {
            const div_user = document.getElementById(`card-info-users`);
            div_user.innerHTML = /* html */ `
            <div id="card-users" class="card mb-3" style="width: 100%; height: 200px;">
               <div class="card-header d-flex justify-content-between align-items-center">
                  <div>
                     <span class="fs-6 fw-semibold">Total usuarios: <span id="count-card-users"></span></span>
                  </div>
                  <div class="d-flex align-items-center">
                     <select class="form-select form-select-sm" id="select-data-users" name="select-data-users" title="Filtro por fecha">
                        <option value="0" selected>Todos</option>   
                        <option value="1">Activos</option>
                        <option value="2">Inactivos</option>
                        <option value="3">Eliminados</option>
                     </select>
                     <div class="ms-2">
                        <a id="report-users" name="report-users" class="fa-solid fa-file-excel fa-xl link-pointer text-decoration-none lh-1 color-incons" data-bs-toggle="tooltipInfoUser" data-bs-html="true" data-bs-title="Reporte de usuarios" title="Reporte de usuarios"></a>
                     </div>
                  </div>
               </div>
               <div class="card-body py-2">
                  <div id="users-chart-div"></div>
               </div>
            </div>
            `;
            document.getElementById('report-users').onclick = () => reportSheet(data.data_user, `${data.dominio_usuario}`, 'Detalle de usuarios', 1);
            tooltipSystem('tooltipInfoUser');
            resolve(true);
         } catch (error) {
            spiner_menu_close(`spinner-menu-title`);
            console.error(`Error: ${error}`);
            showToastError('Error obteniendo alertas');
         }
      }, 200);
   });
}

const dataAlertProcess = (data, op) => {
   try {
      let date_i = sumDays(op);
      let date_f = new Date(document.getElementById('fend-filter-alert').value);
      date_f.setDate(date_f.getDate() + 1);
      date_f.setHours(23, 59, 59);
      const userAlert = new Array();
      const resultAlerts = new Array();
      let total_alerts = 0;
      // obtiene las alertas de usuarios mayor que 0 y que cumpla con las fechas dadas
      data.data_user.forEach((user) => {
         const result = user.data_alert.filter(alert => {
            if (op === 4) {
               return alert.fecha_creacion;
            } else {
               let fecha_creacion = new Date(alert.fecha_creacion);
               return ((fecha_creacion >= date_i) && (fecha_creacion <= date_f));
            }
         });
         if (result.length > 0) {
            resultAlerts.push({
               'busua_cod': user.busua_cod,
               'cloud_username': user.cloud_username,
               'alerts': result
            });
            // total alertas
            total_alerts += result.length;
            userAlert.push({
               'busua_cod': user.busua_cod,
               'cloud_username': user.cloud_username,
               'user_phone': user.user_phone,
               'nombre': user.nombre,
               'fecha_creacion': user.fecha_creacion,
               'esta_cod': user.esta_cod,
               'dominio_usuario': data.dominio_usuario,
               'count': result.length
            })
         }
      });
      return {
         total_alerts, userAlert, resultAlerts
      };
   } catch (error) {
      spiner_menu_close(`spinner-menu-title`);
      spiner_menu_close(`spinner-filter-alert`);
      console.error(`Error: ${error}`);
      showToastError('Error obteniendo alertas');
   }
}

// manejo de Ranking de alertas
const rankingAlert = (data, op, booldata) => {
   try {
      let filter_date = document.getElementById('filter-alert');
      if (op === 5) {
         filter_date.classList.remove('d-none');
         let date = dateSet(0, 1);
         document.getElementById('fini-filter-alert').value = date;
         document.getElementById('fend-filter-alert').value = date;
         return false;
      } else {
         filter_date.classList.add('d-none');
      }
      spiner_menu_open(`spinner-filter-alert`);
      const { total_alerts, userAlert, resultAlerts } = dataAlertProcess(data, op);
      document.getElementById('count-card-ranking-alerts').innerHTML = total_alerts;
      if (booldata === true) {   // true para generar archivo
         return userAlert.sort((a, b) => a.count - b.count).reverse();
      } else {
         dataAlertChart(userAlert, data.dominio_usuario, resultAlerts);
         spiner_menu_close(`spinner-filter-alert`);
      }
   } catch (error) {
      spiner_menu_close(`spinner-menu-title`);
      spiner_menu_close(`spinner-filter-alert`);
      console.error(`Error: ${error}`);
      showToastError('Error obteniendo alertas');
   }
}

// filtra por fechas especificas
const filterAlertRanking = (data, op, booldata) => {
   try {
      spiner_menu_open(`spinner-filter-alert`);
      const { total_alerts, userAlert, resultAlerts } = dataAlertProcess(data, op);
      document.getElementById('count-card-ranking-alerts').innerHTML = total_alerts;
      if (booldata === true) {
         return userAlert.sort((a, b) => a.count - b.count).reverse();
      } else {
         dataAlertChart(userAlert, data.dominio_usuario, resultAlerts);
         spiner_menu_close(`spinner-filter-alert`);
      }
   } catch (error) {
      console.error(`Error: ${error}`);
      showToastError(error_system);
   }
}

// ver alertas 
const viewAlertsRaNking = (data, op, booldata) => {
   try {
      document.getElementById('modal-titlep').innerHTML = 'Alertas generadas por usuarios';
      let html = /* html */`
      <div class="container">
         <div class="row">
            <div class="col-12">
               <div id="spinner-filter-alert-all" class="spinner-border spinner-border-sm color-incons ms-2" role="status" hidden>
                  <span class="visually-hidden">Loading...</span>
               </div>
            </div>
            <div class="col-12" id="alerts-all-chart-div"></div>
         </div>
      </div>
      `;
      document.querySelectorAll('.d-none-alert-all').forEach((element) => {
         if (op === 5) {
            element.classList.remove('d-none');
         } else {
            element.classList.add('d-none');
         }
      });
      document.getElementById('modalp').innerHTML = html;
      spiner_menu_open(`spinner-filter-alert-all`);
      const { total_alerts, userAlert, resultAlerts } = dataAlertProcess(data, 4);
      dataAlertChartAll(userAlert, data.dominio_usuario, resultAlerts);
      $('#modalopen').modal('show');
   } catch (error) {
      console.error(`Error: ${error}`);
      showToastError(error_system);
   }
}

// alertas de usuarios
const cardDataRankingAlert = async (data) => {
   return new Promise((resolve, reject) => {
      setTimeout(() => {
         try {
            const div_alert = document.getElementById(`card-info-ranking-alerts`);
            let date = dateSet(0, 1);
            div_alert.innerHTML = /* html */ `
            <div id="card-ranking-alerts" class="card mb-3" style="width: 100%; height: 200px;">
               <div class="card-header d-flex justify-content-between align-items-center">
                  <div>
                     <span class="fs-6 fw-semibold">Total alertas: <span id="count-card-ranking-alerts"></span></span>
                  </div>
                  <div id="div-view-all-alert">
                     <a id="btn-alert-show-ranking" class="link-pointer text-primary" style="font-size: 10px;" titl="Ver todas la alertas">Ver todo</a>
                  </div>
                  <div class="d-flex align-items-center">
                     <div>
                        <div id="spinner-filter-alert" class="spinner-border spinner-border-sm color-incons me-2" role="status" hidden>
                           <span class="visually-hidden">Loading...</span>
                        </div>
                     </div>
                     <select class="form-select form-select-sm" id="select-date-filter" name="select-date-filter" title="Filtro por fecha">
                        <option value="1" selected>&Uacute;ltimas 2 semanas</option>
                        <option value="2">Ultimo mes</option>
                        <option value="3">Ultimos 2 meses</option>
                        <option value="4">Todo</option>
                        <option value="5">Filtrar</option>
                     </select>
                     <div class="ms-2">
                        <a id="report-ranking-alerts" name="report-ranking-alerts" class="fa-solid fa-file-excel fa-xl link-pointer text-decoration-none lh-1 color-incons" data-bs-toggle="tooltipInfoAlert" data-bs-html="true" data-bs-title="Reporte alertas de usuarios" title="Reporte alertas de usuarios"></a>
                     </div>
                  </div>
               </div>
               <div class="card-body py-2">
                  <div id="filter-alert" class="row align-items-center mb-1">
                     <div class="col-auto pe-1">
                        <label for="fini-filter-alert">Desde</label>
                     </div>
                     <div class="col-auto px-1">
                        <input class="form-control form-control-sm" type="date" id="fini-filter-alert" name="fini-filter-alert" value="${date}" max="${date}" title="Fecha de inicio" onchange="dateDes('fini-filter-alert', 'fend-filter-alert')">
                     </div>
                     <div class="col-auto px-1">
                        <label for="fend-filter-alert">Hasta</label>
                     </div>
                     <div class="col-auto px-1">
                        <input class="form-control form-control-sm" type="date" id="fend-filter-alert" name="fend-filter-alert" value="${date}" max="${date}" title="Fecha de termino" onchange="dateHas('fini-filter-alert', 'fend-filter-alert')">
                     </div>
                     <div class="col-auto px-1">
                        <button class="btn btn-sm buttons colors-font" id="button-filter-alert" name="button-filter-alert" title="Filtrar">Filtrar</button>
                     </div>
                  </div>
                  <div id="ranking-alerts-chart-div"></div>
               </div>
            </div>
            `;
            rankingAlert(data, 1, false);
            document.getElementById('select-date-filter').onchange = (e) => rankingAlert(data, parseInt(e.target.value), false);
            document.getElementById('button-filter-alert').onclick = () => filterAlertRanking(data, parseInt(document.getElementById('select-date-filter').value), false);
            document.getElementById('report-ranking-alerts').onclick = () => reportSheet(data, `${data.dominio_usuario}`, 'Detalle de alertas de usuario', 2);
            document.getElementById('btn-alert-show-ranking').onclick = (e) => viewAlertsRaNking(data, parseInt(document.getElementById('select-date-filter').value), false);
            tooltipSystem('tooltipInfoAlert');
            resolve(true);
         } catch (error) {
            spiner_menu_close(`spinner-menu-title`);
            console.error(`Error: ${error}`);
            showToastError('Error obteniendo alertas');
         }
      }, 200);
   });
}

// trae la informacion de los operadores
const cardDataOperator = async (data) => {
   return new Promise((resolve, reject) => {
      setTimeout(() => {
         try {
            const div_user = document.getElementById(`card-info-operators`);
            div_user.innerHTML = /* html */ `
            <div id="card-operators" class="card mb-3" style="width: 100%; height: 200px;">
               <div class="card-header d-flex justify-content-between align-items-center">
                  <div>
                     <span class="fs-6 fw-semibold">Total operadores: <span id="count-card-operators"></span></span>
                  </div>
                  <div class="d-flex align-items-center">
                     <select class="form-select form-select-sm" id="select-data-operators" name="select-data-operators" title="Filtro por fecha">
                        <option value="0" selected>Todos</option>   
                        <option value="1">Activos</option>
                        <option value="2">Inactivos</option>
                        <option value="3">Eliminados</option>
                     </select>
                     <div class="ms-2">
                        <a id="report-operators" name="report-operators" class="fa-solid fa-file-excel fa-xl link-pointer text-decoration-none lh-1 color-incons" data-bs-toggle="tooltipInfoOper" data-bs-html="true" data-bs-title="Reporte de operadores" title="Reporte de operadores"></a>
                     </div>
                  </div>
               </div>
               <div class="card-body py-2">
                  <div id="operators-chart-div"></div>
               </div>
            </div>
            `;
            document.getElementById('report-operators').onclick = () => reportSheet(data.data_oper, `${data.dominio_usuario}`, 'Detalle de operadores', 3);
            tooltipSystem('tooltipInfoOper');
            resolve(true);
         } catch (error) {
            spiner_menu_close(`spinner-menu-title`);
            console.error(`Error: ${error}`);
            showToastError('Error obteniendo operator');
         }
      }, 200);
   });
}

// alertas de usuarios
const cardDataAlertUser = async (data) => {
   return new Promise((resolve, reject) => {
      setTimeout(() => {
         try {
            const div_alert = document.getElementById(`card-info-alerts`);
            let date = dateSet(0, 1);
            div_alert.innerHTML = /* html */ `
            <div id="card-alerts" class="card mb-3" style="width: 100%; height: 320px;">
               <div class="card-header d-flex justify-content-between align-items-center">
                  <div>
                     <span class="fs-5 fw-bold">Total de alertas: <span id="count-card-alerts"></span></span>
                  </div>
                  <div class="d-flex align-items-center">
                     <div>
                        <div id="spinner-filter-alert" class="spinner-border spinner-border-sm color-incons me-2" role="status" hidden>
                           <span class="visually-hidden">Loading...</span>
                        </div>
                     </div>
                     <select class="form-select form-select-sm" id="select-date-filter" name="select-date-filter" title="Filtro por fecha">
                        <option value="1" selected>&Uacute;ltimas 2 semanas</option>
                        <option value="2">Ultimo mes</option>
                        <option value="3">Ultimos 2 meses</option>
                        <option value="4">Todo</option>
                        <option value="5">Filtrar</option>
                     </select>
                     <div class="ms-2">
                        <a id="report-alerts" name="report-alerts" class="fa-solid fa-file-excel fa-xl link-pointer text-decoration-none lh-1 color-incons" data-bs-toggle="tooltipInfoAlert" data-bs-html="true" data-bs-title="Reporte alertas de usuarios" title="Reporte alertas de usuarios"></a>
                     </div>
                  </div>
               </div>
               <div class="card-body">
                  <div id="filter-alert" class="row align-items-center mb-3">
                     <div class="col-auto pe-1">
                        <label for="fini-filter-alert">Desde</label>
                     </div>
                     <div class="col-auto px-1">
                        <input class="form-control form-control-sm" type="date" id="fini-filter-alert" name="fini-filter-alert" value="${date}" max="${date}" title="Fecha de inicio" onchange="dateDes('fini-filter-alert', 'fend-filter-alert')">
                     </div>
                     <div class="col-auto px-1">
                        <label for="fend-filter-alert">Hasta</label>
                     </div>
                     <div class="col-auto px-1">
                        <input class="form-control form-control-sm" type="date" id="fend-filter-alert" name="fend-filter-alert" value="${date}" max="${date}" title="Fecha de termino" onchange="dateHas('fini-filter-alert', 'fend-filter-alert')">
                     </div>
                     <div class="col-auto px-1">
                        <button class="btn btn-sm buttons colors-font" id="button-filter-alert" name="button-filter-alert">Filtrar</button>
                     </div>
                  </div>
                  <div id="alerts-chart-div"></div>
                  <div id="div-view-all-alert" class="col-12">
                     <div class="d-flex justify-content-end">
                        <a href="#" id="btn-alert-show-ranking" titl="Ver todas la alertas">Ver todo</a>
                     </div>
                  </div>
               </div>
            </div>
            `;
            rankingAlert(data, 1, false);
            document.getElementById('select-date-filter').onchange = (e) => rankingAlert(data, parseInt(e.target.value), false);
            document.getElementById('button-filter-alert').onclick = () => filterAlertRanking(data, false);
            document.getElementById('report-alerts').onclick = () => reportSheet(data, `${data.dominio_usuario}`, 'Detalle de alertas de usuario', 2);
            document.getElementById('btn-alert-show-ranking').onclick = (e) => viewAlertsRaNking(data, parseInt(document.getElementById('select-date-filter').value), false);
            tooltipSystem('tooltipInfoAlert');
            resolve(true);
         } catch (error) {
            spiner_menu_close(`spinner-menu-title`);
            console.error(`Error: ${error}`);
            showToastError('Error obteniendo alertas');
         }
      }, 300);
   });
}

// menu personal information
const operBP = async () => {
   try {
      const menu = document.getElementById(`personal-information`);
      const oper_cod = parseInt(document.getElementById('oper_cod').value);
      return await fetch(`${url_operator}/json/json_getDataOper.php?oper_cod=${oper_cod}`, {
         method: 'GET',
         headers: {
            'Content-Type': 'application/json'
         }
      })
         .then(response => response.json())
         .catch((error) => {
            menu.innerHTML = `${data_error}`;
            console.error(`Error: ${error}`);
         });
   } catch (error) {
      console.error(`Error: ${error}`);
      showToastError(error_system);
   }
}

// verifica si el formulario no tiene errores
const verifyFormUP = async () => {
   try {
      return new Promise((resolve, reject) => {
         setTimeout(() => {
            let retval = true;
            // data
            const names = document.getElementById('names-oper');
            const username = document.getElementById('username-oper');
            const password = document.getElementById('password');
            const statuspassword = parseInt(document.getElementById('err-status-pass-up').value);
            // validaciones
            // nombres operador
            if (names.value.trim().length === 0) {
               errorValid(names);
               retval = false;
            } else {
               succesValidUP(names);
            }
            if (statuspassword === 1) {
               errorValid(password);
               retval = false;
            } else {
               succesValidUP(password);
            }
            if (retval === true) {
               const data = {
                  'nombre': names.value.trim(),
                  'username': username.value.trim(),
                  'password': password.value.trim(),
               }
               resolve(data);
            } else {
               reject(false);
            }
         }, 300);
      });
   } catch (error) {
      console.error(`Error: ${error}`);
   }
}

// actualiza los datos del usuario
const updateDataOper = async (data) => {
   try {
      return await fetch(`${url_operator}/json/json_updateDataOper.php`, {
         method: 'POST',
         headers: {
            'Content-Type': 'application/json'
         },
         body: JSON.stringify(data)
      })
         .catch(error => showToastError(error))
         .then(response => response.json())
   } catch (error) {
      showToastError(error_system);
      console.error(`Error: ${error}`);
   }
}

// configuracion menu informacion personal
const confPersonalInfoMenu = async (data) => {
   try {
      return new Promise((resolve, reject) => {
         setTimeout(() => {
            if (data.status === 'error') {
               reject(data.message);
               return false;
            }
            const dataInfo = data.dataOper;
            let div = document.getElementById('personal-information');
            div.innerHTML = '';
            const html = /* html */ `
            <form id="form-up-personal-info" name="form-up-personal-info" class="row">
               <div class="col-12">
                  <h5>DATOS OPERADOR</h5>
               </div>
               <div class="col-lg-4 mb-3">
                  <label for="names-oper" class="form-label">(*) Nombre:</label>
                  <input type="text" class="form-control" id="names-oper" name="names-oper" aria-describedby="names-oper-desc" minlength="1" maxlength="40" pattern="${PATTERN_NAMES}" title="${TEXT_NAMES}" placeholder="Nombre operador" autocomplete="off" onkeydown="onlySpaceInput(event)" onkeyup="validateInputsText(event, 1, 40)" value="${dataInfo.names}">
                  <div class="invalid-feedback">
                     Ingrese nombre
                  </div>
               </div>
               <div class="col-12">
                  <h5>DATOS CUENTA</h5>
               </div>
               <div class="col-lg-4 mb-3">
                  <label class="form-label" for="username-oper">(*) Username:</label>
                  <div class="input-group mb-3">
                     <input type="text" class="form-control" id="username-oper" name="username-oper" aria-describedby="username-oper-account" title="Nombre de usuario operador" required placeholder="Nombre de usuario" autocomplete="off" value="${dataInfo.username}" disabled>
                     <span class="input-group-text">@${document.getElementById('du').value}</span>
                  </div>
               </div>
               <div class="col-lg-4 mb-3">
                  <label class="form-label" for="password">(*) Contrase&ntilde;a:</label>
                  <input type="password" class="form-control" id="password" name="password" aria-describedby="password-account" title="Contrase&ntilde;a del usuario" placeholder="Contrase&ntilde;a" autocomplete="off" onkeyup="verifyPasswordUP()" >
                  <div class="invalid-feedback">
                     Ingrese contrase&ntilde;a
                  </div>
               </div>
               <div class="col-12 d-flex justify-content-end">
                  <button type="submit" class="btn btn-sm buttons colors-font" id="btn-update-info-user" name="btn-update-info-user"> 
                     <div id="spinner-btn-update-info-user" class="spinner-border spinner-border-sm text-white" role="status" hidden>
                        <span class="visually-hidden">Loading...</span>
                     </div>
                     <span id="textbtn-btn-update-info-user">Modificar</span>
                  </button>
               </div>
               <input type="hidden" id="err-status-email-oper-up" value="2">
               <input type="hidden" id="err-status-pass-up" value="1">
            </form>
            `;
            div.innerHTML = html;
            document.getElementById('form-up-personal-info').addEventListener('submit', async e => {
               try {
                  e.preventDefault();
                  await spinnerOpen('btn-update-info-user');
                  const data = await verifyFormUP();
                  const result = await updateDataOper(data);
                  await spinnerClose('btn-update-info-user', 'Modificar');
                  if (result.status === 'success') {
                     const dataR = await operBP();
                     await confPersonalInfoMenu(dataR);
                     toast.fire({
                        icon: 'success',
                        title: result.message
                     });
                  } else {
                     showToastError(result.message);
                  }
               } catch (error) {
                  await spinnerClose('btn-update-info-user', 'Modificar');
                  console.error(`Error: ${error}`);
               }
            });
            resolve(true);
         }, 300);
      });
   } catch (error) {
      console.error(`Error: ${error}`);
   }
}

// cambia el anio del reporte de alertas
const updateAnioAlertChart = async (val, usua_cod) => {
   try {
      const tipoa_cod = document.getElementById('select-type-filter-alert').value;
      const causa_cod = document.getElementById('select-filter-causa-alert').value;
      const deriva = document.getElementById('select-filter-derivacion').value;
      const data = await alertctx(usua_cod, val, tipoa_cod, causa_cod, deriva);
      await cardDataAlertsChart(data);
      document.getElementById('report-alert-full').onclick = () => reportSheet(data, document.getElementById('du').value, 'Detalle de alertas', 4);
   } catch (error) {
      console.error(`Error: ${error}`);
      showToastError(error_system);
   }
}

// cambia el tipo alerta del reporte de alertas
const updateTipoAlertChart = async (val, usua_cod) => {
   try {
      const anio = document.getElementById('select-anio-filter-alert').value;
      const causa_cod = document.getElementById('select-filter-causa-alert').value;
      const deriva = document.getElementById('select-filter-derivacion').value;
      const data = await alertctx(usua_cod, anio, val, causa_cod, deriva);
      await cardDataAlertsChart(data);
      document.getElementById('report-alert-full').onclick = () => reportSheet(data, document.getElementById('du').value, 'Detalle de alertas', 4);
   } catch (error) {
      console.error(`Error: ${error}`);
      showToastError(error_system);
   }
}

// cambia el tipo alerta del reporte de alertas
const updateTypeCausaChart = async (val, usua_cod) => {
   try {
      const anio = document.getElementById('select-anio-filter-alert').value;
      const tipoa_cod = document.getElementById('select-type-filter-alert').value;
      const deriva = document.getElementById('select-filter-derivacion').value;
      const data = await alertctx(usua_cod, anio, tipoa_cod, val, deriva);
      await cardDataAlertsChart(data);
      document.getElementById('report-alert-full').onclick = () => reportSheet(data, document.getElementById('du').value, 'Detalle de alertas', 4);
   } catch (error) {
      console.error(`Error: ${error}`);
      showToastError(error_system);
   }
}

// cambia el estado de las alertas
const updateDerivaAlertChart = async (val, usua_cod) => {
   try {
      const anio = document.getElementById('select-anio-filter-alert').value;
      const tipoa_cod = document.getElementById('select-type-filter-alert').value;
      const causa_cod = document.getElementById('select-filter-causa-alert').value;
      const data = await alertctx(usua_cod, anio, tipoa_cod, causa_cod, val);
      await cardDataAlertsChart(data);
      document.getElementById('report-alert-full').onclick = () => reportSheet(data, document.getElementById('du').value, 'Detalle de alertas', 4);
   } catch (error) {
      console.error(`Error: ${error}`);
      showToastError(error_system);
   }
}

// card alerts
const cardDataAlerts = (data, anio, usua_cod, dataAlert) => {
   try {
      return new Promise((resolve, reject) => {
         setTimeout(() => {
            const div_user = document.getElementById(`card-info-alerts`);
            let optipoalert = '<option value="0">Todo</option>';
            data.data_talert.forEach((element) => {
               optipoalert += `<option value="${element.tipoa_cod}">${element.tipo_alerta}</option>`;
            });
            let optioncausa = '<option value="-1">Todo</option>';
            data.data_causa.forEach((element) => {
               optioncausa += `<option value="${element.causa_cod}">${element.descripcion}</option>`;
            });
            let optionderi = '<option value="-1">Todo</option>';
            data.data_deriv.forEach((element) => {
               optionderi += `<option value="${element.derv_cod}">${element.descripcion}</option>`;
            });
            div_user.innerHTML = /* html */ `
            <div id="card-alerts" class="card mb-3" style="width: 100%;">
               <div class="card-body py-2">
                  <div class="row">
                     <div class="col-lg-6">
                        <div class="row">
                           <div class="col-12 d-flex justify-content-start align-items-center">
                              <span class="fs-4 fw-semibold">Reportes de alertas</span>
                              <a id="report-alert-full" name="report-alert-full" class="fa-solid fa-file-excel fa-xl link-pointer text-decoration-none lh-1 color-incons ms-3" data-bs-toggle="tooltipInfoUser" data-bs-html="true" data-bs-title="Reporte anual de alertas" title="Reporte anual de alertas"></a>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-12">
                              <form class="row g-3 align-items-center">
                                 <div class="col-lg-3">
                                    <label for="select-type-filter-alert" class="col-form-label">Tipo de alerta:</label>
                                    <select class="form-select" id="select-type-filter-alert" name="select-type-filter-alert" title="Filtro por tipo de alerta" onchange="updateTipoAlertChart(this.value, ${usua_cod})">
                                       ${optipoalert}
                                    </select>
                                 </div>
                                 <div class="col-lg-3">
                                    <label for="select-filter-causa-alert" class="col-form-label">Causa:</label>
                                    <select class="form-select" id="select-filter-causa-alert" name="select-filter-causa-alert" title="Filtro por causa de la alerta" onchange="updateTypeCausaChart(this.value, ${usua_cod})">
                                       ${optioncausa}
                                    </select>
                                 </div>
                                 <div class="col-lg-3">
                                    <label for="select-filter-derivacion" class="col-form-label">Derivaci&oacute;n:</label>
                                    <select class="form-select" id="select-filter-derivacion" name="select-filter-derivacion" title="Filtro por derivaci&oacute;n de alerta" onchange="updateDerivaAlertChart(this.value, ${usua_cod})">
                                       ${optionderi}
                                    </select>
                                 </div>
                                 <div class="col-lg-3">
                                    <label for="select-anio-filter-alert" class="col-form-label">A&ntilde;o:</label>
                                    <select class="form-select" id="select-anio-filter-alert" name="select-anio-filter-alert" title="Filtro por a&ntilde;o" onchange="updateAnioAlertChart(this.value, ${usua_cod})">
                                       <option value="${anio}">${anio}</option>
                                       <option value="${(anio - 1)}">${(anio - 1)}</option>
                                       <option value="${(anio - 2)}">${(anio - 2)}</option>
                                       <option value="${(anio - 3)}">${(anio - 3)}</option>
                                       <option value="${(anio - 3)}">${(anio - 4)}</option>
                                    </select>
                                 </div>
                              </form>
                           </div>
                           <div id="alerts-chart-div" class="col-12"></div>
                        </div>
                     </div>
                     <div id="result-alert-chart" class="col-lg-6" style="overflow-y: scroll; height: 250px;"></div>
                  </div>
               </div>
            </div>
            `;
            document.getElementById('report-alert-full').onclick = () => reportSheet(dataAlert, `${data.dominio_usuario}`, 'Detalle de alertas', 4);
            tooltipSystem('tooltipInfoUser');
            resolve(true);
         }, 200);
      });
   } catch (error) {
      console.error(`Error: ${error}`);
      showToastError(error_system);
   }
}

// configuracion de menu iframe
const iframeConfigMenu = async (menu) => {

   const du = document.getElementById('du').value;
   const nowInternal3 = dateSet(0, 3);
   const host = window.location.host;
   const usua_cod = document.getElementById('usua_cod').value;
   const secc = document.getElementById(`session-id-${usua_cod}`).value;
   const div = menu === 3 ? document.getElementById('iframe-div-dashboard') : document.getElementById('iframe-div-console');

   let iframeDesc = '';

   switch (menu) {
      case 3:
         iframeDesc = 'dashboard';
         break;
      case 4:
         iframeDesc = 'consola';
         break;
   }

   if (menu === 3) {
      div.innerHTML = /* html */ `
      <iframe title="Dashboard" scrolling="no" src="https://${host}:9025/${iframeDesc}/${du}?inicio=${dateSet(-14, 3)}&fin=${nowInternal3}"></iframe>
      `;
   } else {
      div.innerHTML = /* html */ `
      <iframe title="Console" scrolling="no" src="https://${host}:9025/${iframeDesc}/${du}?inicio=${dateSet(-14, 3)}&fin=${nowInternal3}"></iframe>
      `;
   }

}

// sube plantilla de texto
const updatePlantilla = async (dom_cod, tipo_cod) => {
   const data = new FormData();
   data.append('dom_cod', dom_cod);
   data.append('tipo_cod', tipo_cod);
   data.append('file2upload', $('#csv_file').prop('files')[0]);
   const res = await fetch(`${url_operator}/json/json_updatePlantilla.php`, {
      method: 'POST',
      body: data
   });
   return res.json();
}

// configuracion previa de las plantillas
const configModalNotify = (dom_cod) => {

   try {

      return new Promise((resolve, reject) => {

         document.getElementById('info-modal-pbe').innerHTML = /* html */ `
         <div id="info-modal-pbe" class="modal-body">
            <form id="form-up-plantilla-${dom_cod}" name="form-up-plantilla-${dom_cod}" class="row">
               <div class="col-12 mb-3">
                  <h4>Formulario de plantillas de servicios</h4>
               </div>
               <div class="col-auto mb-3">
                  <label for="file2upload" class="form-label">Subir plantilla</label>
                  <div>
                     <input class="form-control" type="file" id="csv_file" name="files" required>
                     <input type="hidden" name="MAX_FILE_SIZE" value="102400">
                  </div>
               </div>
               <div class="col-auto mb-3">
                  <label for="file2upload" class="form-label">Seleccione servicio</label>
                  <div class="d-flex align-items-center">
                     <div id="spinner-tipo-cod-plantilla" class="spinner-border spinner-border-sm text-primary me-2" role="status" hidden>
                        <span class="visually-hidden">Loading...</span>
                     </div>
                     <select class="form-select" id="tipo-cod-plantilla" name="tipo-cod-plantilla" onchange="getPlantilla(1)" title="Tipo de servicio"></select>
                  </div>
               </div>
               <div class="col-auto d-flex align-items-end mb-3">
                  <button type="submit" class="btn buttons colors-font" id="btn-update-plantilla-${dom_cod}" name="btn-update-plantilla-${dom_cod}"> 
                     <div id="spinner-btn-update-plantilla-${dom_cod}" class="spinner-border spinner-border-sm text-white" role="status" hidden>
                        <span class="visually-hidden">Loading...</span>
                     </div>
                     <span id="textbtn-btn-update-plantilla-${dom_cod}">Subir Plantilla</span>
                  </button>
               </div>
            </form>
            <hr>
            <div class="row">
               <div class="col-12">
                  <h5>Plantilla actual del servicio</h5>
               </div>
               <div class="col-12" style="height: 500px;">
                  <textarea id="plantilla-email" class="form-control w-100" style="height: 100%;" disabled title="Detalle de plantilla"></textarea>
               </div>
            </div>
         </div>
         `;

         document.getElementById(`form-up-plantilla-${dom_cod}`).addEventListener('submit', async e => {

            try {
               e.preventDefault();

               await spinnerOpen(`btn-update-plantilla-${dom_cod}`);

               const tipo_cod = parseInt(document.getElementById('tipo-cod-plantilla').value);

               if (tipo_cod !== 0) {

                  const res = await updatePlantilla(dom_cod, tipo_cod);

                  if (res.status === 'success') {

                     const textarea = document.getElementById('plantilla-email');

                     textarea.classList.remove('text-danger');

                     textarea.innerHTML = /* html */ `${res.contenido}`;

                     document.getElementById('csv_file').value = '';

                     showToastSuccess(res.message);

                  }

               } else {

                  showToastError('Debe seleccionar <span class="text-primary">servicio</span>');

               }

            } finally {

               await spinnerClose(`btn-update-plantilla-${dom_cod}`, 'Subir Plantilla');

            }

         });

         resolve(true);

      });

   } catch (error) {

      console.error('Error', error);


   }

}

// selector de dominio para los servicios
const selectDomainNotiServices = async (data) => {

   // div select
   const div_select = document.getElementById('div-select-service');

   // tipo_cod (servicio)
   const select = div_select.querySelector('select[id="tipo-cod"]');

   // select plantilla
   const select_plantilla = document.getElementById('tipo-cod-plantilla');

   // table
   const table = $('#table-noti-services').DataTable();

   // check masivo
   const check = document.getElementById('check-masivo');

   // desactiva los inputs
   check.disabled = true;

   select.innerHTML = '<option value="0">Seleccione servicio</option>';
   select_plantilla.innerHTML = '<option value="0">Seleccione servicio</option>';

   // resultado de dataDomain
   if (data.status === 'err') {

      // limpiar los datos actuales
      table.clear().draw();

      // cierra spinner
      await spiner_menu_close('spinner-domain-services');

      // error toast
      await showToastError(data.message);

   } else {

      // inserta la informacion encontrada
      data.tipoBs.forEach(element => {

         select.innerHTML += /* html */ `
         <option value="${element.tipo_cod}">${element.tipo}</option>
         `;

         select_plantilla.innerHTML += /* html */ `
         <option value="${element.tipo_cod}">${element.tipo}</option>
         `;

      });

      // limpiar los datos actuales
      table.clear().draw();

   }

}

// muestra menu segun valor agregado
const main_menu = async (menu, dom_cod) => {

   // variables
   const menuf = parseInt(menu);
   const usua_cod = document.getElementById(`usua_cod`).value;

   let dateNow = new Date();

   await validatePermission(menuf);
   await spiner_menu_open(`spinner-menu-title`);

   // menu
   await dataMenu(menuf, dom_cod);

   // MENU USUARIO
   if (menuf === 1) {
      await confAdm(dom_cod);
      const data = await dataContactCenter(usua_cod);
      await menuContactCenter(data, dom_cod);
      await menuUsers(JSON.parse(localStorage.getItem(`group_cod_oper_${dom_cod}`)));
      await tooltipSystem(`tooltipGroup`);
      await tooltipSystem(`tooltipInfoUsers`);
      await tooltipSystem(`tooltipContactCenters`);
   }

   // MENU APROVISIONAMIENTO
   if (menuf === 2) {
      await confAprov(dom_cod);
      await changeFormAddUsers(dom_cod);
   }

   // MENU NOTIFICACIONES
   if (menuf === 3) {
      const data = await dataDomain(dom_cod);
      await configModalNotify(dom_cod);
      await selectDomainNotiServices(data);
   }

   // MENU DASHBOARD
   if (menuf === 4) {
      await iframeConfigMenu(3);
   }

   // MENU CONSOLA
   if (menuf === 5) {
      await iframeConfigMenu(4);
   }

   // MENU REPORTES
   if (menuf === 6) {
      // info
      const data = await userctx(usua_cod);
      const dataAlert = await alertctx(usua_cod, dateNow.getFullYear());

      await confReportsMenu();
      // alerts
      await cardDataAlerts(data, dateNow.getFullYear(), usua_cod, dataAlert);
      await cardDataAlertsChart(dataAlert);
      // usser
      await cardDataUser(data);
      await dataUserChart(data);
      // ranking alerts
      await cardDataRankingAlert(data);
      // operators
      await cardDataOperator(data);
      await dataOperatorChart(data);
   }

   // MENU DATOS PERSONALES
   if (menuf === 7) {
      const data = await operBP();
      await confPersonalInfoMenu(data);
   }

   //spinner de carga - close
   await spiner_menu_close(`spinner-menu-title`);

}

// view menus
const main = async (menu = 1) => {

   return new Promise((resolve, reject) => {

      setTimeout(() => {

         const dom_cod = document.getElementById(`dom_cod`).value;

         localStorage.removeItem('main_menu_oper');
         localStorage.setItem('main_menu_oper', JSON.stringify(menu));

         main_menu(JSON.parse(localStorage.getItem('main_menu_oper')), dom_cod);   // informacion de los menus

         resolve(true);

      }, 300);

   });

}

// sirve para dar click en los menu que se requiera
const menus = async () => {

   return new Promise((resolve, reject) => {

      setTimeout(() => {

         const links = document.getElementsByName('links-menu');

         for (let i = 0; i < links.length; i++) {

            const val = parseInt(links[i].getAttribute('data-value'));

            links[i].onclick = () => main(val);

         }

         resolve(true);

      }, 300);

   });

}

// load menus
document.addEventListener('DOMContentLoaded', async e => {

   try {

      const dom_cod = document.getElementById(`dom_cod`).value;

      await menus();
      await conf_menu(dom_cod);
      await main(JSON.parse(localStorage.getItem('main_menu_oper') !== null ? parseInt(localStorage.getItem('main_menu_oper')) : document.getElementsByName('links-menu').length !== 0 ? document.getElementsByName('links-menu')[0].getAttribute('data-value') : -1));

   } catch (error) {

      console.error(`Error: ${error}`);

   }

});