<?
   require_once 'BConexion.php';
   require_once 'Parameters.php';
   require_once 'BLog.php';
   require_once 'BBoton.php';
   require_once 'BUsuario.php';
   require_once 'MOD_Error.php';
   require_once 'BDesign.php';

   $Log        = new BLog;
   $path_log   = Parameters::PATH . '/log/sites.log';
   $Log->CreaLogTexto($path_log);

   if (!isset($_GET['cloud_username']) || !isset($_GET['cloud_password'])) {
      $Log->RegistraLinea('ERROR: No se puede conectar al sitio');
      MOD_Error::Error('PBE_111', 3);
      exit;
   }

   $parts            = explode('@', $_GET['cloud_username']);
   $cloud_username   = trim(strtolower($parts[0]));
   $cloud_password   = $_GET['cloud_password'];
   $cloud_Id         = trim(strtolower($parts[1]));

   $DB               = new BConexion();
   $Boton            = new BBoton();
   $Usuario          = new BUsuario();
   $Design           = new BDesign();

   if ($Usuario->autenticaUsuario($cloud_username, $cloud_password, $cloud_Id, $DB) === false) {
      $Log->RegistraLinea('ERROR: No se puede autenticar usuario');
      $DB->Logoff();
      MOD_Error::Error('PBE_111', 3);
      exit;
   }

   $appversion = isset($_GET['appversion']) ? $_GET['appversion'] : 'n/a';
   $plataforma = isset($_GET['plataforma']) ? $_GET['plataforma'] : 'n/a';
   $device = isset($_GET['device']) ? $_GET['device'] : 'n/a';
   $appbuild = isset($_GET['appbuild']) ? $_GET['appbuild'] : 'n/a';

   if ($Boton->BuscaBoton($Usuario->busua_cod, $DB) === false) {
      $Log->RegistraLinea('ERROR: No se puede encontrar servicio del usuario');
      echo 'ERROR: No se puede encontrar servicio del usuario (botón no encontrado)';
      $DB->Logoff();
      exit;
   }

   if ($Design->busca($Usuario->dom_cod, $DB) === false) {
      $DB->Logoff();
      MOD_Error::Error('PBE_125');
      exit;
   }

   // if ($Usuario->busua_cod === '2000412' || $Usuario->busua_cod === '2000541' || $Usuario->busua_cod === '2000252') {
   //    header('Location: https://pbe.redvoiss.net/site-dev/index.php?cloud_username=' . $_GET['cloud_username'] . '&cloud_password=' . $cloud_password . '&appversion=' . $appversion . '&plataforma=' . $plataforma . '&device=' . $device . '&appbuild=' . $appbuild);
   //    $DB->Logoff();
   //    exit;
   // }

   $linkversion = $plataforma === 'iOS' ? 'https://apps.apple.com/cl/app/protegeme/id6477294642' : 'https://play.google.com/store/apps/details?id=net.redvoiss.protegeme.android&hl=es_419&pli=1';

   $v = '?' . rand();   // versionamiento
?>
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
      <title><?= $Usuario->dominio ?></title>
      <meta name="title" content="Site Protegeme">
   
      <!-- Bootstrap CSS -->
      <link rel="stylesheet" href="<?= Parameters::WEB_PATH ?>/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
      
      <!-- sweetalert2 -->
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

            <!-- STYLES css -->
      <style>
         :root {
            --background-app: <?= $Design->fondo_app ?>;
            --buttons-all-app: <?= $Design->botones_tablas_app ?>;
            --color-letter-app: <?= $Design->color_letra_app ?>;
         }
      </style>
      <link rel="stylesheet" href="<?= Parameters::WEB_PATH ?>/css/stylesSite.css<?= $v ?>">

   </head>
   <body>
      <section id="site-pbe">
         <div class="container-fluid p-0 m-0">
            <div id="spinner-bar"></div>
            <div class="row">
               <div class="d-flex align-items-center justify-content-end" id="icon-setting">
                  <div id="settings-div" class="link-pointer">
                     <img id="settings-img" src="<?= Parameters::WEB_PATH ?>/img/default/settings.png<?= $v ?>" title="icon settings">
                  </div>
               </div>
               <?
               if ($plataforma !== 'iOS') {
                  if ($appversion <= 1.9 ){ ?>
                     <div class="div-alert-version">
                        <div class="alert alert-warning alert-dismissible fade show p-2" role="alert">
                           Nueva <a href="<?= $linkversion ?>" target="_blank"><strong>versi&oacute;n</strong></a> disponible en su Store
                           <button type="button" class="btn-close pt-2" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                     </div>
                  <? 
                  }
               }
               if ($Boton->esta_cod > 1) {
                  $Log->RegistraLinea("ERROR: Servicio no se encuentra activo");
                  ?>
                  <img id="cloudid" name="clouid" src="<?= Parameters::WEB_PATH ?>/img/adm_img/img_<?= $Usuario->dom_cod ?>.png<?= $v ?>" onclick="alert('Servicio Deshabilitado')" title="fondo">
                  <?
                  $DB->Logoff();
                  exit;
               }
               ?>
               <div id="emergency-div" class="link-pointer">
               <?
                  if (file_exists('../img/adm_img/img_' . $Usuario->dom_cod . '.png')) {
                  ?>
                     <img id="cloudid" name="cloudid" src="<?= Parameters::WEB_PATH ?>/img/adm_img/img_<?= $Usuario->dom_cod ?>.png<?= $v ?>" title="icon btn">
                  <?   
                  } else {
                  ?>
                     <img id="cloudid" name="cloudid" src="<?= Parameters::WEB_PATH ?>/img/adm_img/default.png" title="icon btn">
                  <?
                  }
                  ?>
               </div>
            </div>
            <div class="row">
               <?
               if ($Usuario->contacto === '1') {
                  ?>
                  <div class="d-flex align-items-center justify-content-around m-0" id="icon-contact">
                     <div id="contacts-div" class="link-pointer">
                        <img id="contacts-img" class="img-incon" src="<?= Parameters::WEB_PATH ?>/img/default/contacts.png<?= $v ?>" title="icon contacts">
                     </div>
                  </div>
                  <?
               }
               ?>
               <div id="gpsubi" class="link-pointer text-white">
                  <span></span>
               </div>
            </div>
            <div id="data-contacts"></div>
         </div>
         <footer>
            <div class="copyright d-flex justify-content-center align-self-center w-100">
               <span class="text-white">&copy; By Redvoiss</span>
            </div>
         </footer>
         <input type="hidden" id="plataforma" value="<?= $plataforma ?>">
         <input type="hidden" id="appversion" value="<?= $appversion ?>">
         <input type="hidden" id="device" value="<?= $device ?>">
         <input type="hidden" id="appbuild" value="<?= $appbuild ?>">
         <input type="hidden" id="bu" value="<?= $Usuario->busua_cod ?>">
         <input type="hidden" id="cu" value="<?= $cloud_username ?>">
         <input type="hidden" id="cp" value="<?= $cloud_password ?>">
         <input type="hidden" id="du" value="<?= $cloud_Id ?>">
         <input type="hidden" id="pcontact" value="<?= $Usuario->contacto ?>">
         <input type="hidden" id="lat">
         <input type="hidden" id="lon">
      </section>
      <script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/js/jquery-3.7.1.min.js"></script>
      <script src="<?= Parameters::WEB_PATH ?>/js/bootstrap.min.js"></script>
      <script defer src="https://cdn.jsdelivr.net/npm/ua-parser-js@0/dist/ua-parser.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.15/lodash.min.js"></script>
      <script src="https://kit.fontawesome.com/ebbdbffbad.js" crossorigin="anonymous"></script>
      <script type="module" src="<?= Parameters::WEB_PATH ?>/site/js/site_functions.js<?= $v ?>"></script>
   </body>
</html>
   <?
   $DB->Logoff();
?>
