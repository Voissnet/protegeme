'use strict'

const url = `${document.location.origin}/customer`;
const url_operator = `${document.location.origin}/operator`;
const urluser = `${document.location.origin}/user`;
const url_img = `${document.location.origin}/img`;
const url_map = `https://www.openstreetmap.org/?`;

const error_system = `Error, por favor intentar mas tarde`;
const data_error = `Error: Data read failed`;

const formatEmail = /^[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*@[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,5}/;
const formatDomainSip = /^[a-z0-9]+\.[a-z0-9]+\.[a-z 0-9'.]{2,60}$/;
const formatGroup = /^[a-zA-Z0-9À-ÿ ]{1,60}$/;
const formatNum = /^[0-9]{3,15}$/;
const formatGeneral = /^[a-zA-Z0-9À-ÿ .]{3,120}$/;
const formMac = /^([0-9A-Fa-f]){12}$/;
const formatPassword = /^(?=.*\d)(?=.*[a-zA-Z]).{8,60}$/;

// parents
const PATTERN_ALFANUMERICO = "^[a-zA-Z0-9ñáéíóúÁÉÍÓÚÑ][a-z A-Z0-9ñáéíóúÁÉÍÓÚÑ'.]{2,60}$";                /*  EXPRESION REGULAR PARA CAMPOS DE TEXTO LARGOS */
const PATTERN_ALFANUMERICO_200 = "^[a-zA-Z0-9ñáéíóúÁÉÍÓÚÑ][a-z A-Z0-9ñáéíóúÁÉÍÓÚÑ&'.]{5,200}$";              /*  EXPRESION REGULAR PARA CAMPOS DE TEXTO DE EMPRESAS MUY LARGAS & 200 caracteres (datos facturacion)*/
const PATTERN_NAMES = "^[a-zA-ZñáéíóúÁÉÍÓÚÑ][a-z A-ZñáéíóúÁÉÍÓÚÑ]{1,40}$";                        /*  EXPRESION REGULAR PARA FORMULARIOS CON CAMPOS NOMBRE APELLIDOS */
const PATTERN_TELEFONO = "^[0-9+]{9,14}$";                                                           /*  EXPRESION REGULAR PARA TELEFONOS +56994193746 */
const PATTERN_USERNAME = "^[a-zA-Z0-9][a-zA-Z0-9\.@]{7,40}$";                                        /*  EXPRESION REGULAR PARA USERNAMES */
const PATTERN_PASSWORD = "(?=.*\d)(?=.*[a-zA-Z]).{8,60}";                                            /*  EXPRESION REGULAR PARA FORMULARIOS QUE SOLICITAN PASSWORD */

const TEXT_ALFANUMERICO = "Por favor ingrese solo constras y números";
const TEXT_NAMES = "Por favor ingrese sólo constras y máximo 40 caracteres";
const TEXT_TELEFONO = "Por favor ingrese sólo números, mínimo 9 caracteres";
const TEXT_EMAIL = "Debe ingresar una cuenta email válida";
const TEXT_USERNAME = "Ingrese un texto de mínimo 8 caracteres";
const TEXT_PASSWORD = "Debe contener al menos un número, una letra, y 8 caracteres como mínimo";


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
const showError = async (text) => {
   await Swal.fire({
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

// mensaje de existo
const showRespuesta = (data) => {
   Swal.fire({
      position: 'bottom-end',
      icon: 'success',
      html: data,
      showConfirmButton: false,
      timer: 10000
   });
};

// prepara pregunta
const questionSweetAlert = async (text, width = 'auto') => {
   return await Swal.mixin({
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

// error success
const showToastSuccess = async (title, width = 'auto', timer = 5000) => {
   await toast.fire({
      icon: 'success',
      title,
      width,
      timer
   });
}

// error toast
const showToastError = async (title, width = 'auto', timer = 5000) => {
   await toast.fire({
      icon: 'error',
      title,
      width,
      timer
   });
}

// Warning toast
const showToastWarning = (text, width = 'auto', timer = 5000) => {
   toast.fire({
      icon: 'warning',
      text,
      width,
      timer
   });
}


// res con mensaje personalizado
const showResP = (html) => {
   Swal.fire({
      allowEscapeKey: false,
      allowOutsideClick: false,
      position: 'center',
      html,
      showCloseButton: true,
      showConfirmButton: false,
   });
}

// suma/resta dias
const sumDays = (op) => {
   try {
      let date = op === 5 ? new Date(document.getElementById('fini-filter-alert').value) : new Date();
      switch (op) {
         case 1:
            date.setDate(date.getDate() + (-14));
            break;
         case 2:
            date.setMonth(date.getMonth() - 1);
            break;
         case 3:
            date.setMonth(date.getMonth() - 2);
            break;
         case 4:
            break;
         case 5:
            date.setDate(date.getDate() + 1);
            break;
         default:
            date.setDate(date.getDate() + (-14));
            break;
      }
      date.setHours(0, 0, 0);
      return date;
   } catch (error) {
      console.error(`Error: ${error}`);
      showError(error_system);
   }
}

// cambia fecha segun los dias ingresados y da el formato 
const dateSet = (days, format) => {
   try {
      let date = new Date();
      if (days !== 0) {
         date.setDate(days);
      }
      let month = (date.getMonth() + 1) < 10 ? `0${(date.getMonth() + 1)}` : (date.getMonth() + 1);
      let result;
      if (format === 1) {
         // YYYY-MM-DD
         result = `${date.getFullYear()}-${month}-${date.getDate() < 10 ? `0${date.getDate()}` : date.getDate()}`;
      } else if (format === 2) {
         // DD-MM-YYYY
         result = `${date.getDate() < 10 ? `0${date.getDate()}` : date.getDate()}-${month}-${date.getFullYear()}`;
      } else if (format === 3) {
         // DD-MM-YYYY
         result = `${date.getDate() < 10 ? `0${date.getDate()}` : date.getDate()}/${month}/${date.getFullYear()}`;
      }
      return result;
   } catch (error) {
      console.error(`Error: ${error}`);
      showError(error_system);
   }
}

// maneja fecha ini
const dateDes = (idini, idend) => {
   try {
      // inputs
      let fini = document.getElementById(idini);
      let fend = document.getElementById(idend);
      // fecha ayer
      let last_day = new Date();
      last_day.setMonth(last_day.getMonth());
      last_day.setDate(last_day.getDate() - 1);
      let fdes = new Date(fini.value.substring(0, 4), fini.value.substring(5, 7), fini.value.substring(8));
      let fhas = new Date(fend.value.substring(0, 4), fend.value.substring(5, 7), fend.value.substring(8));
      fdes.setMonth(fdes.getMonth() - 1);
      fhas.setMonth(fhas.getMonth() - 1);
      // if (fhas > last_day && fhas > fdes) {
      //    fend.value = `${last_day.getFullYear()}-${(last_day.getMonth() + 1) < 10 ? `0${(last_day.getMonth() + 1)}` : (last_day.getMonth() + 1)}-${last_day.getDate() < 10 ? `0${last_day.getDate()}` : last_day.getDate()}`;
      //    return true;
      // }
      if (fdes > fhas) {
         fend.value = `${fdes.getFullYear()}-${(fdes.getMonth() + 1) < 10 ? `0${(fdes.getMonth() + 1)}` : (fdes.getMonth() + 1)}-${fdes.getDate() < 10 ? `0${fdes.getDate()}` : fdes.getDate()}`;
         return true;
      }
   } catch (error) {
      console.error(error);
      showError(error_system);
   }
}

// maneja fecha end
const dateHas = (idini, idend) => {
   try {
      // inputs
      let fini = document.getElementById(idini);
      let fend = document.getElementById(idend);
      // fecha ahora
      let now = new Date();
      // fecha de ayer
      let last_day = new Date();
      last_day.setMonth(last_day.getMonth());
      last_day.setDate(last_day.getDate() - 1);
      let fdes = new Date(fini.value.substring(0, 4), fini.value.substring(5, 7), fini.value.substring(8));
      let fhas = new Date(fend.value.substring(0, 4), fend.value.substring(5, 7), fend.value.substring(8));
      fdes.setMonth(fdes.getMonth() - 1);
      fhas.setMonth(fhas.getMonth() - 1);
      // if (fhas > last_day) {
      //    fini.value = `${now.getFullYear()}-${(now.getMonth() + 1) < 10 ? `0${(now.getMonth() + 1)}` : (now.getMonth() + 1)}-${now.getDate() < 10 ? `0${now.getDate()}` : now.getDate()}`;
      //    return true;
      // }
      if (fhas < fdes) {
         fini.value = `${fhas.getFullYear()}-${(now.getMonth() + 1) < 10 ? `0${(now.getMonth() + 1)}` : (now.getMonth() + 1)}-${fhas.getDate() < 10 ? `0${fhas.getDate()}` : fhas.getDate()}`;
         return true;
      }
   } catch (error) {
      console.error(error);
      showError(error_system);
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

// abre un offcanvas 
const openOffCanvas = async (idCanvas) => {
   setTimeout(() => {
      let offcanvasElement = document.getElementById(idCanvas);
      let offcanvas = new bootstrap.Offcanvas(offcanvasElement);
      Swal.close();
      return offcanvas.toggle();
   }, 100);
}

/* maneja el mensaje, el tipo y el gate que es opcional */
const showRespuestaPerso = async (msj) => {
   await Swal.fire({
      allowEscapeKey: false,
      allowOutsideClick: false,
      html: runMSJSystem(msj),
      icon: 'success',
      title: 'DETALLE',
      width: 700,
   }).then((result) => {
      if (result.isConfirmed) {
         swal.close();
      }
   });
}

// genera clave cloud_password
const generateCloudPassword = (id) => {
   let chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
   let password2Length = 7;
   let password2 = '';
   for (let i = 0; i <= password2Length; i++) {
      let randomNumber = Math.floor(Math.random() * chars.length);
      password2 += chars.substring(randomNumber, randomNumber + 1);
   }
   document.getElementById(id).value = password2;
}


// recorre los mensajes para mostrar por pantalla
const runMSJSystem = (elements) => {
   // inicializo la variablbe que guardara los mensajes
   let text = '';
   // compruebo si es un array
   if (Array.isArray(elements)) {
      // recorro los mensajes
      elements.forEach((element, i) => {
         // guardo los mensajes con sus respectivos saltos de lineas
         text += '<span>Paso[' + (i + 1) + '].</span> ' + element + ' <br>';
      });
   } else {
      text += elements;
   }
   // retorno el texo
   return text;
}

/* muentra una previsualizacion en un excel */
const printPapaObject = (op, papa) => {
   let id = '';                                                                     // id que cambia dependiendo de la opcion
   switch (op) {
      case 1:                                                                       // creacion de dominio (principal)
         id = 'table-users-bp-create';
         break;
      case 2:                                                                       // creacon de usuarios masivo
         break;
   }
   let header = '';                                                                 // cabecera
   let tbody = '';                                                                  // cuerpo
   let contador = 1;
   header += '<th>N</th>';                                                         // creamos la primera columna
   for (let p in papa.meta.fields) {                                                // se inserta las siguientes columnas
      header += /* html */ `
      <th>${papa.meta.fields[p]}</th>
      `;
   }
   for (let i = 0; i < papa.data.length - 1; i++) {                                     // insertamos cada fila
      let row = '';
      if (papa.data[i].NOMBRE !== null) {
         row += /* html */ `
         <td class="text-end">${contador}</td>
         `;
         contador++;
      }
      for (let z in papa.data[i]) {
         if (papa.data[i][z] !== null) {
            row += /* html */ `
            <td>${papa.data[i][z]}</td>
            `;
         } else {
            row += /* html */ `
            <td></td>
            `;
         }
      }
      tbody += /* html */ `
      <tr>${row}</tr>
      `;
   }
   //build a table
   document.getElementById('outputservicecreate').innerHTML = /* html */ `
   <table id="${id}" class="table p-2">
      <thead class="bg-success-subtle">
         ${header}
      </thead>
      <tbody>
         ${tbody}
      </tbody>
   </table>
   `;
}

// imprime csv previo a subir
const handleFileSelect = (evt, op) => {
   if (op === 1) {
      document.getElementById('status_csv').value = '1';
   }
   if (!(evt.target && evt.target.files && evt.target.files[0])) {
      return;
   }
   let file = evt.target.files[0];
   Papa.parse(file, {
      header: true,
      dynamicTyping: true,
      complete: function (results) {
         printPapaObject(1, results);
      }
   });
}

// maneja del div de error
const statusMSJ = async (id, msj, status, time = true) => {
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

// validaMac
const validateMac = (e, bot_cod) => {
   let mac = e.target.value;
   if (bot_cod === 0) {
      cleanInputError(`err-mac-service-add`);
      if (!formMac.test(mac)) {
         statusMSJ(document.getElementsByName(`err-mac-service-add`)[0], 'Formato Mac inv&aacute;lido.', false, false);
      }
   } else {
      cleanInputError(`err-mac-udp-${bot_cod}`);
      if (!formMac.test(mac)) {
         statusMSJ(document.getElementsByName(`err-mac-udp-${bot_cod}`)[0], 'Formato Mac inv&aacute;lido.', false, false);
      }
   }
}

// solo numeros
const onlyNumbers = (e, bu) => {
   let cods = [8, 13, 17, 86, 37, 39, 46, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 96, 97, 98, 99, 100, 101, 102, 103, 104, 105];
   const key = window.e ? e.which : e.keyCode;
   if (!cods.includes(key)) {
      e.preventDefault();
   }
}

// funcion para procesar un spinner en los botones
const spinnerOpenBtn = async (id, text = 'Procesando') => {
   try {
      return new Promise((resolve, reject) => {
         setTimeout(() => {
            let spinner = document.getElementById(id);
            spinner.disabled = true;
            spinner.querySelector('div').hidden = false;
            spinner.querySelectorAll('span')[1].innerHTML = text;
            resolve(true);
         }, 300);
      });
   } catch (error) {
      console.error(`Error: ${error}`);
   }
}

// funcion para finalizar un spinner en los botones
const spinnerCloseBtn = async (id, text) => {
   try {
      return new Promise((resolve, reject) => {
         setTimeout(() => {
            let spinner = document.getElementById(id);
            spinner.disabled = false;
            spinner.querySelector('div').hidden = true;
            spinner.querySelectorAll('span')[1].innerHTML = text;
            resolve(true);
         }, 300);
      });
   } catch (error) {
      console.error(`Error: ${error}`);
   }
}

// funcion para procesar un spinner en los botones personalizados
const spinnerOpen = async (id) => {
   try {
      return new Promise((resolve, reject) => {
         setTimeout(() => {
            let spinner = document.getElementById(id);
            spinner.classList.add('disabledp');
            spinner.querySelector('div').hidden = false;
            spinner.querySelectorAll('span')[1].innerHTML = 'Procesando';
            resolve(true);
         }, 200);
      });
   } catch (error) {
      console.error(`Error: ${error}`);
   }
}

// funcion para finalizar un spinner en los botones
const spinnerClose = async (id, text) => {
   try {
      return new Promise((resolve, reject) => {
         setTimeout(() => {
            let spinner = document.getElementById(id);
            spinner.classList.remove('disabledp');
            spinner.querySelector('div').hidden = true;
            spinner.querySelectorAll('span')[1].innerHTML = text;
            resolve(true);
         }, 200);
      });
   } catch (error) {
      console.error(`Error: ${error}`);
   }
}

// abre spinner
const spiner_menu_open = async (id) => {
   return new Promise((resolve, reject) => {
      setTimeout(() => {
         document.getElementById(id).hidden = false;
         resolve(true);
      }, 100);
   });
}

//cierra spinner
const spiner_menu_close = async (id) => {
   return new Promise((resolve, reject) => {
      setTimeout(() => {
         document.getElementById(id).hidden = true;
         resolve(true);
      }, 1000);
   });
}

// activa un tooltip
const tooltipSystem = async (id) => {
   return new Promise((resolve, reject) => {
      setTimeout(() => {
         const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="' + id + '"]');
         const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
         resolve(true);
      }, 300);

   })
}

// actualiza un tooltip
const tooltipUpdateSystem = async (id, text) => {
   document.getElementById(id).setAttribute('data-bs-title', text)
   const tooltip = bootstrap.Tooltip.getInstance('#' + id);
   tooltip.setContent({ '.tooltip-inner': text });
}

// limpia errores de los inpust
const cleanInputError = (id) => {
   let div_err = document.getElementsByName(id)[0];
   div_err.classList.remove('text-success', 'text-danger');
   div_err.hidden = true;
   div_err.innerHTML = '';
}

const showDataTbale = (id) => {
   $(`#${id}`).DataTable({
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
      pageLength: -1,
      order: [
         [1, 'asc']
      ]
   });
}

// spinner de carga para sistemas
const showLoadingSystem = async (html = '<span class="fw-bold">Procesando...</span>') => {
   Swal.fire({
      html,
      allowEscapeKey: false,
      allowOutsideClick: false,
      showConfirmButton: false,
   });
   Swal.showLoading();
}

// no acepta espacios
const onlySpace = (e) => {
   let cods = [32];
   const key = window.e ? e.which : e.keyCode;
   if (cods.includes(key)) {
      e.preventDefault();
   }
}

// no acepta espacios
const onlyCloudUsername = (e) => {
   let cods = [32];
   const key = window.e ? e.which : e.keyCode;
   if (cods.includes(key) || e.key === '@') {
      e.preventDefault();
   }
}

// tranforma todo a minusculas
const onlyToLowerCase = (val) => {
   val.value = val.value.toLowerCase().replaceAll(' ', '');
}

// No acepta espacios al inicio
const onlySpaceInput = (e) => {
   try {
      if (e.target.value.length === 0) {
         let cods = [32];
         const key = window.e ? e.which : e.keyCode;
         if (cods.includes(key)) {
            e.preventDefault();
         }
      }
   } catch (error) {
      console.error(`Error: ${error}`);
   }
}

// busca usuario valido
const buscaUsuarioValido = (username) => {
   try {
      setTimeout(() => {
         let status = document.getElementById('err-status-username');
         let usernameInput = document.getElementById('username');
         if (username.replaceAll(' ', '').length === 0) {
            status.value = 1;
            usernameInput.classList.remove('is-valid');
            usernameInput.classList.add('is-invalid');
            return false;
         }
         fetch(`${url}/register/ajax/json_comprueba_usuario.php?username=${username.replaceAll(' ', '')}`, {
            method: 'GET',
            headers: {
               'Content-Type': 'application/json'
            }
         })
            .then(response => response.json())
            .then((response) => {
               if (response.status === 'error') {
                  status.value = 1;
                  usernameInput.classList.remove('is-valid');
                  usernameInput.classList.add('is-invalid');
               } else {
                  status.value = 2;
                  usernameInput.classList.remove('is-invalid');
                  usernameInput.classList.add('is-valid');
               }
            })
            .catch((error) => {
               status.value = 1;
               console.error(`Error: ${error}`);
            });
      }, 300)
   } catch (error) {
      console.error(`Error: ${error}`);
      toast.fire({
         icon: 'error',
         title: error_system
      });
   }
}

// error de valdiacion
const errorValid = (input) => {
   try {
      input.classList.remove('is-valid');
      input.classList.add('is-invalid');
   } catch (error) {
      console.error(`Error: ${error}`);
   }
}

// validado
const succesValid = (input) => {
   try {
      input.classList.remove('is-invalid');
      input.classList.add('is-valid');
   } catch (error) {
      console.error(`Error: ${error}`);
   }
}

// validado UPdate
const succesValidUP = (input) => {
   try {
      input.classList.remove('is-invalid');
      input.classList.remove('is-valid');
   } catch (error) {
      console.error(`Error: ${error}`);
   }
}

// valida campos de tipo tetxo con un minimo y maxmo de largo
const validateInputsText = (input, min, max) => {
   try {
      if (input.target.value.length < min || input.target.value.length > max) {
         errorValid(input.target);
      } else {
         succesValid(input.target);
      }
   } catch (error) {
      console.error(`Error: ${error}`);
   }
}

// valida campos de tipo tetxo con un minimo y maxmo de largo
const validateInputSelect = (input) => {
   try {
      if (parseInt(input.value) === -1) {
         errorValid(input);
      } else {
         succesValid(input);
      }
   } catch (error) {
      console.error(`Error: ${error}`);
   }
}

// verifica clave
const verifyPassword = () => {
   try {
      let password = document.getElementById('password');
      let password_v = document.getElementById('password_v');
      let status = document.getElementById('err-status-pass');
      let statusv = document.getElementById('err-status-pass-v');
      if (!formatPassword.test(password.value)) {
         errorValid(password);
         password_v.classList.remove('is-valid');
         password_v.classList.remove('is-invalid');
         status.value = 1;
         statusv.value = 1;
         return false;
      } else {
         status.value = 2;
         password.classList.remove('is-invalid');
         password.classList.add('is-valid');
      }
      if (password.value.replaceAll(' ', '').length === 0 && password_v.value.replaceAll(' ', '').length === 0) {
         errorValid(password);
         password_v.classList.remove('is-valid');
         password_v.classList.remove('is-invalid');
         status.value = 1;
         statusv.value = 1;
         return false;
      } else if (password.value.replaceAll(' ', '').length === 0 && password_v.value.replaceAll(' ', '').length > 0) {
         errorValid(password_v);
         status.value = 1;
         statusv.value = 1;
         return false;
      }
      if (password.value !== password_v.value) {
         errorValid(password_v);
         statusv.value = 1;
         return false;
      } else {
         password_v.classList.remove('is-invalid');
         password_v.classList.add('is-valid');
         status.value = 2;
         statusv.value = 2;
      }
   } catch (error) {
      console.error(`Error: ${error}`);
   }
}

const verifyPasswordUP = () => {
   try {
      let password = document.getElementById('password');
      let status = document.getElementById('err-status-pass-up');
      if (password.value.replaceAll(' ', '').length === 0) {
         errorValid(password);
         status.value = 1;
      } else {
         succesValidUP(password);
         status.value = 2;
      }
   } catch (error) {
      console.error(`Error: ${error}`);
   }
}

// valida formulario reset password
const validateFormPasswordReset = async () => {
   try {
      return new Promise((resolve, reject) => {
         setTimeout(() => {
            const password = document.getElementById('password').value;
            const passwordv = document.getElementById('password_v').value;
            const statuspass = document.getElementById('err-status-pass').value;
            const statuspassv = document.getElementById('err-status-pass-v').value;
            if (statuspass !== '2' || statuspassv !== '2') {
               showToastError('Error, verifique las credenciales')
               reject(false);
            } else {
               const data = {
                  'password': password,
                  'password_v': passwordv,
                  'token': document.getElementById('token').value,
                  'iv': document.getElementById('iv').value,
                  'uc_crypt': document.getElementById('uc_crypt').value
               }
               resolve(data);
            }
         }, 300);
      });
   } catch (error) {
      console.error(`Error: ${error}`);
      showToastError(error_system);
   }
}

// actualiza el progreso
const progressRegister = (form, formup, button, progress) => {
   try {
      setTimeout(() => {
         document.getElementsByName(form).forEach(div => div.classList.add('d-none'));
         document.getElementsByName(formup).forEach(div => div.classList.remove('d-none'));
         document.getElementById('progress-register').style.width = `${progress}%`;
         button.classList.remove('btn-secondary');
         button.classList.add('btn-danger');
         document.getElementById('form-act').value = formup;
         document.getElementById('btn-before').setAttribute('data-value', form);
      }, 300);
   } catch (error) {
      console.error(`Error: ${error}`);
      showToastError(error_system);
   }
}

// volver al formulario anterior
const beforeForm = () => {
   try {
      setTimeout(() => {
         const formup = document.getElementById('btn-before');
         const form = document.getElementById('form-act');
         const progress = document.getElementById('progress-register');
         const button = document.getElementById(`btn-register-${form.value}`);
         document.getElementsByName(form.value).forEach(div => div.classList.add('d-none'));
         document.getElementsByName(formup.getAttribute('data-value')).forEach(div => div.classList.remove('d-none'));
         form.value = `${formup.getAttribute('data-value')}`;
         formup.getAttribute('data-value') === 'op1' ? document.getElementById('div-before').classList.add('d-none') : '';
         switch (formup.getAttribute('data-value')) {
            case 'op1':
               progress.style.width = `0%`;
               button.classList.remove('btn-danger');
               button.classList.add('btn-secondary');
               break;
            case 'op2':
               progress.style.width = `33%`;
               button.classList.remove('btn-danger');
               button.classList.add('btn-secondary');
               formup.setAttribute('data-value', 'op1');
               break;
            case 'op3':
               progress.style.width = `66%`;
               button.classList.remove('btn-danger');
               button.classList.add('btn-secondary');
               formup.setAttribute('data-value', 'op2');
               break;
            default:
               break;
         }
      }, 300);
   } catch (error) {
      console.error(`Error: ${error}`);
      showToastError(error_system);
   }
}

// valida primer formulario de registro
const verifyForm1 = async () => {
   try {
      return new Promise((resolve, reject) => {
         spinnerOpenBtn('btn-op1');
         setTimeout(() => {
            let retval = true;
            // data enterprise
            const enterprise = document.getElementById('enterprise');
            const reason_social = document.getElementById('reason_social');
            const rut_enterprise = document.getElementById('rut_enterprise');
            const med_cod = document.getElementById('med_cod');
            const rub_cod = document.getElementById('rub_cod');
            // status
            const statusrutenterprise = parseInt(document.getElementById('err-status-rut-empresa').value);
            // validaciones
            // empresa
            if (enterprise.value.trim().length === 0) {
               errorValid(enterprise);
               retval = false;
            } else {
               if (enterprise.value.trim().length < 2 || enterprise.value.trim().length > 60) {
                  errorValid(enterprise);
                  retval = false;
               } else {
                  succesValid(enterprise);
               }
            }
            // razon social
            if (reason_social.value.trim().length === 0) {
               errorValid(reason_social);
               retval = false;
            } else {
               if (reason_social.value.trim().length < 5 || reason_social.value.trim().length > 200) {
                  errorValid(reason_social);
                  retval = false;
               } else {
                  succesValid(reason_social);
               }
            }
            // rut empresa
            if (statusrutenterprise === 1) {
               errorValid(rut_enterprise);
               retval = false;
            } else {
               succesValid(rut_enterprise);
            }
            // tamano
            if (parseInt(med_cod.value) === -1) {
               errorValid(med_cod);
               retval = false;
            } else {
               succesValid(med_cod);
            }
            // rubro
            if (parseInt(rub_cod.value) === -1) {
               errorValid(rub_cod);
               retval = false;
            } else {
               succesValid(rub_cod);
            }
            spinnerCloseBtn('btn-op1', 'Siguiente');
            if (retval === true) {
               progressRegister('op1', 'op2', document.getElementById('btn-register-op2'), '33');
               document.getElementById('div-before').classList.remove('d-none');
               enterprise.classList.remove('is-valid');
               reason_social.classList.remove('is-valid');
               rut_enterprise.classList.remove('is-valid');
               med_cod.classList.remove('is-valid');
               rub_cod.classList.remove('is-valid');
               resolve(true);
            } else {
               showToastWarning('Formulario no validado, revisar.');
               reject(`Error`);
            }
         }, 300);
      });
   } catch (error) {
      console.error(`Error: ${error}`);
   }
}

// valida formulario 2
const verifyForm2 = () => {
   try {
      return new Promise((resolve, reject) => {
         spinnerOpenBtn('btn-op2');
         setTimeout(() => {
            let retval = true;

            // data contact
            const names = document.getElementById('names');
            const last_names = document.getElementById('last_names');
            const rutcontact = document.getElementById('rut');
            const post = document.getElementById('post');
            const phone = document.getElementById('phone');
            const telephone = document.getElementById('telephone');
            const email = document.getElementById('email');

            const statusrutcontact = parseInt(document.getElementById('err-status-rut-contact').value);
            const statusemail = parseInt(document.getElementById('err-status-email-contact').value);

            // nombres contacto
            if (names.value.trim().length === 0) {
               errorValid(names);
               retval = false;
            } else {
               succesValid(names);
            }

            // apellidos contacto
            if (last_names.value.trim().length === 0) {
               errorValid(last_names);
               retval = false;
            } else {
               succesValid(last_names);
            }

            // rut contacto
            if (statusrutcontact === 1) {
               errorValid(rutcontact);
               retval = false;
            } else {
               succesValid(rutcontact);
            }

            // cargo contacto
            if (post.value.trim().length === 0) {
               errorValid(post);
               retval = false;
            } else {
               if (post.value.trim().length < 2 || post.value.trim().length > 60) {
                  errorValid(post);
                  retval = false;
               } else {
                  succesValid(post);
               }
            }

            // celulcar contacto
            if (phone.value.trim().length <= 7) {
               errorValid(phone);
               retval = false;
            } else {
               succesValid(phone);
            }

            if (statusemail === 1) {
               errorValid(email);
               retval = false;
            } else {
               succesValid(email);
            }
            spinnerCloseBtn('btn-op2', 'Siguiente');
            if (retval === true) {
               progressRegister('op2', 'op3', document.getElementById('btn-register-op3'), '66');
               document.getElementById('div-before').classList.remove('d-none');
               names.classList.remove('is-valid');
               last_names.classList.remove('is-valid');
               rutcontact.classList.remove('is-valid');
               post.classList.remove('is-valid');
               phone.classList.remove('is-valid');
               telephone.classList.remove('is-valid');
               email.classList.remove('is-valid');
               resolve(true);
            } else {
               showToastWarning('Formulario no validado, revisar.');
               reject(`Error`);
            }
         }, 300);
      });
   } catch (error) {
      console.error(`Error: ${error}`);
   }
}

// valida formulario 3
const verifyForm3 = async () => {
   try {
      return new Promise((resolve, reject) => {
         spinnerOpenBtn('btn-op3');
         setTimeout(() => {
            let retval = true;

            // data account
            const username = document.getElementById('username');
            const password = document.getElementById('password');
            const passwordv = document.getElementById('password_v');

            const statususer = parseInt(document.getElementById('err-status-username').value);
            const statuspassword = parseInt(document.getElementById('err-status-pass').value);
            const statuspasswordv = parseInt(document.getElementById('err-status-pass-v').value);

            if (statususer === 1) {
               errorValid(username);
               retval = false;
            } else {
               succesValid(username);
            }

            if (statuspassword === 1) {
               errorValid(password);
               retval = false;
            } else {
               succesValid(password);
            }

            if (statuspasswordv === 1) {
               errorValid(passwordv);
               retval = false;
            } else {
               succesValid(passwordv);
            }

            spinnerCloseBtn('btn-op3', 'Siguiente');
            if (retval === true) {
               progressRegister('op3', 'op4', document.getElementById('btn-register-op4'), '100');
               document.getElementById('div-before').classList.remove('d-none');
               username.classList.remove('is-valid');
               password.classList.remove('is-valid');
               passwordv.classList.remove('is-valid');
               grecaptcha.reset();
               resolve(true);
            } else {
               showToastWarning('Formulario no validado, revisar.');
               reject(`Error`);
            }
         }, 300);
      });
   } catch (error) {
      console.error(`Error: ${error}`);
   }
}

// valida formulario 4
const verifyForm4 = async () => {
   try {
      return new Promise((resolve, reject) => {
         setTimeout(() => {
            let retval = true;

            // data conditions
            const conditions = document.getElementById('conditions');

            // status
            const statuscaptcha = document.getElementById('cap-reset-register');
            const responseCap = grecaptcha.getResponse();

            if (responseCap.length == 0) {
               errorValid(statuscaptcha);
               retval = false;
            } else {
               succesValid(statuscaptcha);
            }

            if (retval === true) {
               if (conditions.checked === true) {
                  document.getElementById('div-before').classList.remove('d-none');
                  statuscaptcha.classList.remove('is-valid');
                  resolve(true);
               } else {
                  conditions.focus();
                  toast.fire({
                     icon: 'warning',
                     title: `Debe aceptar el acuerdo para crear cuenta`,
                     timer: 10000
                  });
                  reject(false);
               }
            } else {
               showToastWarning('Formulario no validado, revisar.');
               reject(`Error`);
            }
         }, 300);
      });
   } catch (error) {
      console.error(`Error: ${error}`);
   }
}

// verifica si el formulario no tiene errores
const verifyForm = async () => {
   try {
      return new Promise((resolve, reject) => {
         setTimeout(() => {
            let retval = true;
            // data enterprise
            const enterprise = document.getElementById('enterprise');
            const reason_social = document.getElementById('reason_social');
            const rut_enterprise = document.getElementById('rut_enterprise');
            const med_cod = document.getElementById('med_cod');
            const med_cod_desc = document.querySelector(`#med_cod option[value='${med_cod.value}']`).innerHTML;
            const rub_cod = document.getElementById('rub_cod');
            const rub_cod_desc = document.querySelector(`#rub_cod option[value='${rub_cod.value}']`).innerHTML;

            // data contact
            const names = document.getElementById('names');
            const last_names = document.getElementById('last_names');
            const rutcontact = document.getElementById('rut');
            const post = document.getElementById('post');
            const phone = document.getElementById('phone');
            const telephone = document.getElementById('telephone');
            const email = document.getElementById('email');

            // data account
            const username = document.getElementById('username');
            const password = document.getElementById('password');
            const passwordv = document.getElementById('password_v');

            // data conditions
            const conditions = document.getElementById('conditions');
            const sendemail = document.getElementById('send_email').checked === true ? 1 : 0;

            const responseCap = grecaptcha.getResponse();

            if (retval === true) {
               if (conditions.checked === true) {
                  const data = {
                     'empresa': enterprise.value.trim(),
                     'razon_social': reason_social.value.trim(),
                     'rut_empresa': rut_enterprise.value,
                     'med_cod': parseInt(med_cod.value),
                     'med_cod_desc': med_cod_desc,
                     'rub_cod': parseInt(rub_cod.value),
                     'rub_cod_desc': rub_cod_desc,
                     'nombre': names.value.trim(),
                     'apellidos': last_names.value.trim(),
                     'rut': rutcontact.value,
                     'cargo': post.value.trim(),
                     'telefono_celular': phone.value.trim(),
                     'telefono_fijo': telephone.value.trim(),
                     'email': email.value.trim(),
                     'username': username.value.trim(),
                     'password': password.value.trim(),
                     'password_v': passwordv.value.trim(),
                     'enviar_email': sendemail,
                  }
                  resolve({ data, responseCap });
               } else {
                  conditions.focus();
                  toast.fire({
                     icon: 'warning',
                     title: `Debe aceptar el acuerdo para crear cuenta`,
                     timer: 10000
                  });
                  reject(false);
               }
            } else {
               showToastWarning('Formulario no validado, revisar.');
               reject(false);
            }
         }, 300);
      });
   } catch (error) {
      console.error(`Error: ${error}`);
   }
}

// register new user
const registerUser = async (data, recaptcha) => {
   try {
      await fetch(`${url}/register/ajax/json_createNewUserRV.php?recaptcha=${recaptcha}`, {
         method: 'POST',
         headers: {
            'Content-Type': 'application/json'
         },
         body: JSON.stringify(data)
      })
         .catch(error => showToastError(error))
         .then(response => response.json())
         .then(response => {
            if (response.status === 'error') {
               grecaptcha.reset();
               showToastError(response.message);
            } else {
               const html = /* html */ `
               <setcion id="modal-user-rv">
                  <div class="container-fluid">
                     <div class="row">
                        <div class="d-flex justify-content-center mb-3">
                           <h3>Inscripci&oacute;n exitosa</h3>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-12 d-flex justify-content-center mb-3">
                           Estimado usuario:<br>
                           Para concluir exitosamente su inscripci&oacute;n deber&aacute; activar su cuenta siguiendo las instrucciones que hemos enviado a su correo electr&oacute;nico.<br><br>
                           Atte:  Soporte Redvoiss.
                        </div>
                        <div class="col-12 d-flex justify-content-center mb-3">
                           <a type="button" class="btn btn-sm btn-danger w-100" id="home" name="home" href="${url}/login/index.php">Login</a>
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
   } catch (error) {
      grecaptcha.reset();
      showToastError(error_system);
      console.error(`Error: ${error}`);
   }
}

// valida formato de email
const validateEmailUser = (email, id = 'email-reset', idstatus = 'status-email') => {
   let emailerror = document.getElementById(id);
   let status = document.getElementById(idstatus);
   if (email.length === 0) {
      emailerror.classList.remove('is-valid');
      emailerror.classList.remove('is-invalid');
      status.value = 1;
      return false;
   }
   if (!formatEmail.test(email)) {
      emailerror.classList.remove('is-valid');
      emailerror.classList.add('is-invalid');
      status.value = 1;
   } else {
      emailerror.classList.remove('is-invalid');
      emailerror.classList.add('is-valid');
      status.value = 2;
   }
}

// valida formato rut
const validateRut = (e, status) => {
   try {
      let id = e.target.id;
      let inputrut = $(`#${id}`);
      const cods = [32];
      const key = window.e ? e.which : e.keyCode;
      if (cods.includes(key)) {
         e.preventDefault();
      }
      inputrut.rut({ formatOn: 'keyup', validateOn: 'keyup' })
         .on('rutInvalido', function () {
            (id === 'rut_enterprise' ? rut_enterprise : rut).setCustomValidity('RUT Inválido');
            id === 'rut_enterprise'
            inputrut.removeClass('is-valid');
            inputrut.addClass('is-invalid');
            document.getElementById(status).value = 1;
         })
         .on('rutValido', function () {
            inputrut.removeClass('is-invalid');
            inputrut.addClass('is-valid');
            (id === 'rut_enterprise' ? rut_enterprise : rut).setCustomValidity('')
            document.getElementById(status).value = 2;
         });
   } catch (error) {
      console.error(`Error: ${error}`);
   }
}

// valida numero celularr
const validatePhone = (e) => {
   try {
      const cods = [8, 9, 13, 17, 86, 37, 39, 46, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 96, 97, 98, 99, 100, 101, 102, 103, 104, 105];
      const key = window.e ? e.which : e.keyCode;
      if (!cods.includes(key)) {
         e.preventDefault();
      }
   } catch (error) {
      console.error(`Error: ${error}`);
   }
}

// valida el largo de un input dado
const largePhone = (e, count) => {
   try {
      const value = e.target.value;
      if (value.length < count) {
         e.target.classList.remove('is-valid');
         e.target.classList.add('is-invalid');
      } else {
         e.target.classList.remove('is-invalid');
         e.target.classList.add('is-valid');
      }
      if (e.target.id === 'telephone') {
         if (value.length === 0) {
            e.target.classList.remove('is-invalid');
            e.target.classList.remove('is-valid');
         }
      }
   } catch (error) {
      console.error(`Error: ${error}`);
   }
}

// validacion form reset password
const validateSoliFormReset = () => {
   try {
      return new Promise((resolve, reject) => {
         setTimeout(() => {
            let username = document.getElementById('username-reset');
            let email = document.getElementById('email-reset');
            let responseCap = grecaptcha.getResponse();
            let statusemail = parseInt(document.getElementById('status-email').value);
            let statuscaptcha = document.getElementById('cap-reset');
            let error = false;
            if (username.value.length === 0) {
               username.classList.remove('is-valid');
               username.classList.add('is-invalid');
               username.focus();
               error = true;
            } else {
               username.classList.remove('is-valid');
               username.classList.remove('is-invalid');
            }
            if (statusemail === 1) {
               email.classList.remove('is-valid');
               email.classList.add('is-invalid');
               email.focus();
               error = true;
            } else {
               email.classList.remove('is-invalid');
               email.classList.add('is-valid');
            }
            if (responseCap.length == 0) {
               statuscaptcha.classList.remove('is-valid');
               statuscaptcha.classList.add('is-invalid');
               error = true;
            } else {
               statuscaptcha.classList.remove('is-invalid');
               statuscaptcha.classList.add('is-valid');
            }
            if (error === true) {
               reject(false);
               return false;
            }
            resolve(true);
         }, 300);
      });
   } catch (error) {
      toast.fire({
         icon: 'error',
         title: error_system
      });
      console.error(`Error: ${error}`);
   }
}

// resetea password user RV
const resetPasswordUser = async (data) => {
   try {
      return fetch(`${url}/register/ajax/json_resetPasswordUserRV.php?recaptcha=${grecaptcha.getResponse()}`, {
         method: 'POST',
         headers: {
            'Content-Type': 'application/json'
         },
         body: JSON.stringify(data)
      })
         .catch(error => console.error(`Error: ${error}`))
         .then(response => response.json())
   } catch (error) {
      console.error(`Error: ${error}`);
      showError(error_system);
   }
}

// resetea password operator
const resetPasswordOper = async (data) => {
   try {
      return fetch(`${url_operator}/register/ajax/json_resetPasswordOper.php?recaptcha=${grecaptcha.getResponse()}`, {
         method: 'POST',
         headers: {
            'Content-Type': 'application/json'
         },
         body: JSON.stringify(data)
      })
         .catch(error => console.error(`Error: ${error}`))
         .then(response => response.json())
   } catch (error) {
      console.error(`Error: ${error}`);
      showError(error_system);
   }
}


// envia email
const sendEmailResetPassword = () => {
   try {
      return new Promise((resolve, reject) => {
         setTimeout(() => {
            const data = {
               'username-reset': document.getElementById('username-reset').value,
               'email-reset': document.getElementById('email-reset').value
            }
            fetch(`${url}/json/json_send_email_user.php`, {
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
                     let email = document.getElementById('email-reset');
                     document.getElementById('username-reset').value = '';
                     document.getElementById('status-email').value = 1;
                     email.value = '';
                     email.classList.remove('is-valid');
                     email.classList.remove('is-invalid');
                     toast.fire({
                        icon: 'success',
                        title: response.message
                     });
                     resolve(true);
                  }
               })
               .catch((error) => {
                  toast.fire({
                     icon: 'error',
                     title: error_system
                  });
                  console.error(`Error: ${error}`);
                  reject(`Error: ${error}`);
               });
         }, 300);
      });
   } catch (error) {
      toast.fire({
         icon: 'error',
         title: error_system
      });
      reject(`Error: ${error}`);
      console.error(`Error: ${error}`);
   }
}

// valida password
const validateFormReset = () => {
   try {
      return new Promise((resolve, reject) => {
         setTimeout(() => {
            let error = false;
            let statuscaptcha = document.getElementById('cap-reset');
            let password = document.getElementById(`new-password`);
            let password_v = document.getElementById(`new-password-v`);
            let responseCap = grecaptcha.getResponse();
            if (password.value.trim().length === 0 || password_v.value.trim().length === 0) {
               toast.fire({
                  icon: 'error',
                  title: 'Debe ingresar contrase&ntilde;as'
               });
               error = true;
               reject(error);
               return false;
            }
            if (password.value.trim() !== password_v.value.trim()) {
               password_v.classList.remove('is-valid');
               password_v.classList.add('is-invalid');
               error = true;
            } else {
               password_v.classList.remove('is-invalid');
               password_v.classList.remove('is-valid');
            }

            if (responseCap.length == 0) {
               statuscaptcha.classList.remove('is-valid');
               statuscaptcha.classList.add('is-invalid');
               error = true;
            } else {
               statuscaptcha.classList.remove('is-invalid');
               statuscaptcha.classList.add('is-valid');
            }

            if (error === true) {
               reject(false);
               return false;
            }
            resolve(true);
         }, 300);

      });

   } catch (error) {
      console.error(`Error: ${error}`);
   }
}

// update password user
const updatePasswordUser = () => {
   try {
      return new Promise((resolve, reject) => {
         setTimeout(() => {
            const data = {
               'new_password': document.getElementById('new-password').value,
               'new_password_v': document.getElementById('new-password-v').value,
               'bu': document.getElementById('bu').value
            }
            fetch(`${url}/json/json_update_password_user.php`, {
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
                     toast.fire({
                        icon: 'success',
                        title: response.message
                     });
                     setTimeout(() => {
                        window.location.href = `${urluser}/login/index.php`;
                     }, 3000)
                     resolve(true);
                  }
               }).catch((error) => {
                  toast.fire({
                     icon: 'error',
                     title: error_system
                  });
                  console.error(`Error: ${error}`);
                  reject(`Error: ${error}`);
               });
         }, 300);
      });
   } catch (error) {
      console.error(`Error: ${error}`);
   }
}

// valida formulario para recuperar contraseña adm
const validateFormLoginAdm = async () => {
   try {
      return new Promise((resolve, reject) => {
         setTimeout(() => {
            let username = document.getElementById(`username`);
            let email = document.getElementById(`email-reset`);
            let statuscaptcha = document.getElementById('captcha-recu-password');
            let statusemail = parseInt(document.getElementById('status-email').value);
            let responseCap = grecaptcha.getResponse();
            let error = false;
            if (username.value.trim().length === 0) {
               username.classList.remove('is-valid');
               username.classList.add('is-invalid');
               error = true;
            } else {
               username.classList.remove('is-valid');
               username.classList.remove('is-invalid');
            }
            if (statusemail === 1) {
               email.classList.remove('is-valid');
               email.classList.add('is-invalid');
               error = true;
            } else {
               email.classList.remove('is-invalid');
               email.classList.add('is-valid');
            }
            if (responseCap.length == 0) {
               statuscaptcha.classList.remove('is-valid');
               statuscaptcha.classList.add('is-invalid');
               error = true;
            } else {
               statuscaptcha.classList.remove('is-invalid');
               statuscaptcha.classList.add('is-valid');
            }
            if (error === true) {
               reject('Formulario incompleto');
            } else {
               const data = {
                  'username': username.value.trim(),
                  'email': email.value.trim().toUpperCase()
               }
               resolve(data);
            }
         });
      }, 300);
   } catch (error) {
      console.error(error);
   }
}

// valida formulario
const validateFormLogin = () => {
   try {
      let username = document.getElementById(`username`);
      let password = document.getElementById(`password`);
      let statuscaptcha = document.getElementById('captcha-login');
      let responseCap = grecaptcha.getResponse();
      let error = false;
      if (username.value.trim().length === 0) {
         username.classList.remove('is-valid');
         username.classList.add('is-invalid');
         error = true;
      } else {
         username.classList.remove('is-valid');
         username.classList.remove('is-invalid');
      }
      if (password.value.trim().length === 0) {
         password.classList.remove('is-valid');
         password.classList.add('is-invalid');
         error = true;
      } else {
         password.classList.remove('is-valid');
         password.classList.remove('is-invalid');
      }
      if (responseCap.length == 0) {
         statuscaptcha.classList.remove('is-valid');
         statuscaptcha.classList.add('is-invalid');
         error = true;
      } else {
         statuscaptcha.classList.remove('is-invalid');
         statuscaptcha.classList.add('is-valid');
      }
      if (error === true) {
         return false;
      }
   } catch (error) {
      console.error(`Error: ${error}`);
      showToastError(error_system);
      return false;
   }
}

// valida formulario recuperar contraseña
const validateFormRecuPassword = async (data) => {
   try {
      return await fetch(`${url}/register/ajax/json_solicita_new_password.php?recaptcha=${grecaptcha.getResponse()}`, {
         method: 'POST',
         headers: {
            'Content-Type': 'application/json'
         },
         body: JSON.stringify(data)
      })
         .then(response => response.json())
         .catch(error => {
            console.error(`Error: ${error}`);
            showToastError(error_system);
         });
   } catch (error) {
      console.error(`Error: ${error}`);
   }
}

// valida formulario recuperar contraseña
const validateFormRecuPasswordOper = async (data) => {
   try {
      return await fetch(`${url_operator}/register/ajax/json_solicita_new_password.php?recaptcha=${grecaptcha.getResponse()}`, {
         method: 'POST',
         headers: {
            'Content-Type': 'application/json'
         },
         body: JSON.stringify(data)
      })
         .then(response => response.json())
         .catch(error => {
            console.error(`Error: ${error}`);
            showToastError(error_system);
         });
   } catch (error) {
      console.error(`Error: ${error}`);
   }
}

// inicio de sesion adm
const iniSession = async () => {
   try {
      const data = {
         'us': document.getElementById('username').value.replaceAll(' ', ''),
         'pw': document.getElementById('password').value
      }
      return await fetch(`${url}/register/ajax/json_iniSession.php?recaptcha=${grecaptcha.getResponse()}`, {
         method: 'POST',
         headers: {
            'Content-Type': 'application/json'
         },
         body: JSON.stringify(data)
      })
         .then(response => response.json())
         .then(response => {
            return response;
         })
         .catch(error => {
            console.error(`Error: ${error}`);
            showToastError(error_system);
         });
   } catch (error) {
      showToastError(error_system);
   }
}

// inicio de sesion oper
const iniSessionOper = async () => {
   try {
      const data = {
         'us': document.getElementById('username').value.trim(),
         'pw': document.getElementById('password').value
      }
      return await fetch(`${url_operator}/register/ajax/json_iniSessionOper.php?recaptcha=${grecaptcha.getResponse()}`, {
         method: 'POST',
         headers: {
            'Content-Type': 'application/json'
         },
         body: JSON.stringify(data)
      })
         .then(response => response.json())
         .then(response => {
            return response;
         })
         .catch(error => {
            console.error(`Error: ${error}`);
            showToastError(error_system);
         });
   } catch (error) {
      showToastError(error_system);
   }
}

// valida la session
const validateSession = async (status) => {
   return new Promise((resolve, reject) => {
      setTimeout(() => {
         let text_error = document.getElementById('r-error');
         if (status.status === 'error') {
            grecaptcha.reset();
            text_error.classList.remove('d-none');
            text_error.querySelectorAll('span')[0].innerHTML = status.message;
            text_error.querySelectorAll('span')[1].innerHTML = `COD: ${status.cod}`;
            reject(false);
         } else {
            localStorage.clear();
            resolve(true);
         }
      }, 300);
   });
}

// activa user
const activateUser = async () => {
   try {
      const data = {
         'us': document.getElementById('username').value.trim(),
         'pw': document.getElementById('password').value,
         'sesion_check': document.getElementById('sesion_check').value
      }
      return await fetch(`${url}/register/ajax/json_activateUser.php?recaptcha=${grecaptcha.getResponse()}`, {
         method: 'POST',
         headers: {
            'Content-Type': 'application/json'
         },
         body: JSON.stringify(data)
      })
         .then(response => response.json())
         .then(response => {
            return response;
         })
         .catch(error => {
            console.error(`Error: ${error}`);
            showToastError(error_system);
         });
   } catch (error) {
      showToastError(error_system);
   }
}

// valida la session
const validateActivateUser = async (status) => {
   return new Promise((resolve, reject) => {
      setTimeout(() => {
         let text_error = document.getElementById('r-error-activate');
         if (status.status === 'error') {
            grecaptcha.reset();
            text_error.classList.remove('d-none');
            text_error.querySelectorAll('span')[0].innerHTML = status.message;
            text_error.querySelectorAll('span')[1].innerHTML = `COD: ${status.cod}`;
            reject(false);
         } else {
            resolve(true);
         }
      }, 300);
   });
}

// libro de trabajo
const workingLib = (data, title, attachment, subject) => {
   let wb = XLSX.utils.book_new();
   wb.Props = {
      Title: title,
      Subject: subject,
      Author: 'Redvoiss',
      CreatedDate: new Date()
   };
   wb.SheetNames.push(title);
   let ws = XLSX.utils.aoa_to_sheet(data);
   wb.Sheets[title] = ws;
   let wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'binary' });
   function s2ab(s) {
      let buf = new ArrayBuffer(s.length);
      let view = new Uint8Array(buf);
      for (let i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
      return buf;
   }
   saveAs(new Blob([s2ab(wbout)], { type: 'application/octet-stream' }), `${attachment}.xlsx`);
}

// fecha formateada
const dateFormat = (fecha) => {

   const format = fecha.toLocaleDateString('es-ES', {
      weekday: 'long', // Día de la semana completo (ej. lunes)
      day: 'numeric',  // Día del mes (ej. 5)
      month: 'long',   // Mes completo (ej. diciembre)
      year: 'numeric'  // Año (ej. 2024)
   });

   return format;

}

// hora formateada
const horaFormateada = (fecha) => {

   const format = fecha.toLocaleTimeString('es-ES', {
      hour: 'numeric',      // Hora (ej. 10)
      minute: 'numeric',    // Minutos (ej. 30)
      second: 'numeric',    // Segundos (ej. 45)
      hour12: false         // Si quieres formato de 24 horas. Ponlo en `true` para AM/PM
   });

   return format;

}