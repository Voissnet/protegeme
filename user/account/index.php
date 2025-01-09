<?
   header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
   header('Expires: Sat, 1 Jul 2000 05:00:00 GMT'); // Fecha en el pasado

   require_once 'Parameters.php';
   require_once 'BConexion.php';
   require_once 'BLog.php';
   require_once 'BBoton.php';
   require_once 'BUsuario.php';
   require_once 'MOD_Error.php';
   require_once 'BDesign.php';

   $Log  = new BLog;
   $path_log = Parameters::PATH . '/log/sites.log';
   $Log->CreaLogTexto($path_log);

   $username         = isset($_POST['username']) ? $_POST['username'] : false;
   $cloud_password   = isset($_POST['cloud_password']) ? $_POST['cloud_password'] : false;

   if ($username === false || $cloud_password === false) {
      $Log->RegistraLinea('ERROR: No se puede conectar al sitio - cod: 001');
      MOD_Error::Error('PBE_101', 2);
      exit;
   }

   $parts            = explode('@', $username);
   $cloud_username   = trim(strtolower($parts[0]));
   $dominio_usuario  = trim(strtolower($parts[1]));

   $DB               = new BConexion();
   $Usuario          = new BUsuario();
   $Boton            = new BBoton();
   $Design           = new BDesign();

   if ($Usuario->autenticaUsuario($cloud_username, $cloud_password, $dominio_usuario, $DB) === false) {
      $Log->RegistraLinea('ERROR: No se puede autenticar usuario - cod: 002');
      $DB->Logoff();
      MOD_Error::Error('PBE_103', 2);
      exit;
   }

   if ($Boton->BuscaBoton($Usuario->busua_cod, $DB) === false) {
      $Log->RegistraLinea('ERROR: No se puede encontrar servicio del usuario - cod: 003');
      $DB->Logoff();
      MOD_Error::Error('PBE_110', 2);
      exit;
   } 

   if ($Design->busca($Usuario->dom_cod, $DB) === false) {
      $DB->Logoff();
      MOD_Error::Error('PBE_125');
      exit;
   }

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
         <title>PBE USER · Prot&eacute;geme</title>
         <meta name="title" content="Account Protegeme">
         
         <!-- Bootstrap CSS -->
         <link rel="stylesheet" href="<?= Parameters::WEB_PATH ?>/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
         
         <!-- papaparse -->
         <script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/js/papaparse.min.js"></script>

         <!-- sweetalert2 -->
         <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

         <script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/js/jquery-3.7.1.min.js"></script>

         <!-- DATA TABLE -->
         <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css">
         <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>

         <!-- SELECT2 -->
         <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
         <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

         <!-- STYLES css -->
         <style>
            :root {
               --background-web: <?= $Design->fondo_web ?>;
               --buttons-all-web: <?= $Design->botones_tablas_web ?>;
               --color-letter-web: <?= $Design->color_letra_web ?>;
               --font-size-web: <?= $Design->tamano_fuente_web ?>px;
            }
         </style>
         <link rel="stylesheet" href="<?= Parameters::WEB_PATH ?>/css/default.css?v<?= $v ?>">
         <link rel="stylesheet" href="<?= Parameters::WEB_PATH ?>/css/admClient.css?v<?= $v ?>">

      </head>
      <body>
         <section id="site-user">
            <nav id="main-nav" class="nav">
               <ul id="main-menu" class="main-menu h-100">
                  <div class="mb-3 main-menu__item logo-div-protegeme">
                     <img class="main-menu__icon logo-protegeme" src="<?= Parameters::WEB_PATH ?>/img/adm_img/img_<?= $Usuario->dom_cod ?>.png" width="80" height="80" title="Logo Protegeme">
                  </div>
                  <li class="main-menu__item" id="links-1">
                     <a id="adm" name="links" class="main-menu__link link-pointer">
                        <span><i class="fa-solid fa-users main-menu__icon" title="Contactos"></i></i>Contactos</span>
                        <div id="spinner-menu-1" class="spinner-border spinner-border-sm text-light ms-3" role="status" hidden>
                           <span class="visually-hidden">Loading...</span>
                        </div>
                     </a>
                  </li>
                  <li class="main-menu__item" id="links-2">
                     <a id="reporting" name="links" class="main-menu__link link-pointer">
                        <span><i class="fa fa-file-excel main-menu__icon" title="Reportes"></i>Reportes</span>
                        <div id="spinner-menu-2" class="spinner-border spinner-border-sm text-light ms-3" role="status" hidden>
                           <span class="visually-hidden">Loading...</span>
                        </div>
                     </a>
                  </li>
                  <div class="main-menu__item main-close_sesion h-100 m-0">
                     <a href="<?= Parameters::WEB_PATH ?>/user/login/logout.php" class="main-menu__close" onclick="localStorage.clear();">
                        <span><i class="fa fa-right-from-bracket btn-close-sesion" title="Cerrar Sesi&oacute;n"></i>&nbsp;&nbsp;Cerrar Sesi&oacute;n</span>
                     </a>
                  </div>
               </ul>
            </nav>
            <div id="root" class="main"></div>
            <input type="hidden" id="busua_cod" name="busua_cod" value="<?= $Usuario->busua_cod ?>">
            <input type="hidden" id="dom_cod" name="dom_cod" value="<?= $Usuario->dom_cod ?>">
            <input type="hidden" id="du" name="du" value="<?= $Usuario->dominio_usuario ?>">

            <input type="hidden" id="background-web-result" name="background-web-result" value="<?= $Design->fondo_web ?>">
            <input type="hidden" id="buttons-web-result" name="buttons-web-result" value="<?= $Design->botones_tablas_web ?>">
            <input type="hidden" id="color-letters-web-result" name="color-letters-web-result" value="<?= $Design->color_letra_web ?>">
            <input type="hidden" id="tamano-fuente-web-result" name="tamano-fuente-web-result" value="<?= $Design->tamano_fuente_web ?>">

         </section>
         <script src="https://kit.fontawesome.com/ebbdbffbad.js" crossorigin="anonymous"></script>
         <script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
         <script defer src="https://cdn.jsdelivr.net/npm/ua-parser-js@0/dist/ua-parser.min.js"></script>
         <script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/customer/js/screens.js?v<?= $v ?>"></script>
         <script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/user/js/user_functions.js?v<?= $v ?>"></script>
         <script type="text/javascript">
            const mainNav = document.getElementById('main-nav');

            const mainMenu = document.getElementById('main-menu');

            window.addEventListener('resize', () => {
               if (mainNav.classList.contains('nav--show')) {
                  mainNav.classList.remove('nav--show');
                  mainMenu.classList.remove('main-menu--show');
               }
            });
         </script>
      </body>
   </html>
   <?
   $DB->Logoff();
?>