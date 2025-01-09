<?
   require_once 'Parameters.php';
   require_once 'BConexion.php';
   require_once 'BOperador.php';

   $DB         = new BConexion();
   $Operador   = new BOperador();
   
   $Operador->sec_session_start();
   if ($Operador->VerificaLogin($DB) === FALSE) {
      $DB->Logoff();
      header('Location: ' . Parameters::WEB_PATH . '/operator/login/');
      exit;
   }

   require_once 'BUsuarioRV.php';
   require_once 'BGateway.php';
   require_once 'BDominio.php';
   require_once 'BDesign.php';
   require_once 'BOperadorAccion.php';

   $UsuarioRV  = new BUsuarioRV();
   $Gateway    = new BGateway();
   $Dominio    = new BDominio();
   $Design     = new BDesign();
   $Permiso    = new BOperadorAccion();

   if ($Dominio->busca($Operador->dom_cod, $DB) === false) {
      $DB->Logoff();
      MOD_Error::Error('PBE_132');
      exit;
   }
   if ($Gateway->verificaSOS($Dominio->gate_cod, $DB) === false) {
      $DB->Logoff();
      MOD_Error::Error('PBE_133');
      exit;
   }
   if ($Design->busca($Dominio->dom_cod, $DB) === false) {
      $DB->Logoff();
      MOD_Error::Error('PBE_117');
      exit;
   }
   if ($UsuarioRV->Busca($Gateway->usua_cod, $DB) === false) {
      $DB->Logoff();
      MOD_Error::Error('PBE_124');
      exit;
   }
   
   // PERMISOS
   // 1. Administración usuarios y sus servicios
   // 2. Aprovisionamiento de usuarios
   // 3. Notificación de servicios
   // 4. Dashboard o información gráfica
   // 5. Consola informativa
   // 6. Reportería alertas y usuarios

   $p1 = $Permiso->buscaP($Operador->oper_cod, 1, $DB) === false ? 0 : intval($Permiso->nivel);
   $p2 = $Permiso->buscaP($Operador->oper_cod, 2, $DB) === false ? 0 : intval($Permiso->nivel);
   $p3 = $Permiso->buscaP($Operador->oper_cod, 3, $DB) === false ? 0 : intval($Permiso->nivel);
   $p4 = $Permiso->buscaP($Operador->oper_cod, 4, $DB) === false ? 0 : intval($Permiso->nivel);
   $p5 = $Permiso->buscaP($Operador->oper_cod, 5, $DB) === false ? 0 : intval($Permiso->nivel);
   $p6 = $Permiso->buscaP($Operador->oper_cod, 6, $DB) === false ? 0 : intval($Permiso->nivel);

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
      <title>PBE ADM · Prot&eacute;geme</title>
      <meta name="title" content="Login Protegeme">
      
      <!-- Bootstrap CSS -->
      <link rel="stylesheet" href="<?= Parameters::WEB_PATH ?>/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

      <!-- sweetalert2 -->
      <script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/js/sweetalert2.js"></script>

      <!-- DATA TABLE -->
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css">

      <!-- SELECT2 -->
      <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
      <script src="<?= Parameters::WEB_PATH ?>/js/chart.umd.js"></script>

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
      <section id="site-adm-cliente">
         <nav id="main-nav" class="nav">
            <ul id="main-menu" class="main-menu h-100">
               <div class="mb-3 main-menu__item logo-div-protegeme">
                  <?
                  if (file_exists('../../img/adm_img/img_' . $Dominio->dom_cod . '.png')) {
                  ?>
                  <img class="main-menu__icon logo-protegeme" src="<?= Parameters::WEB_PATH ?>/img/adm_img/img_<?= $Dominio->dom_cod ?>.png?<?= $v ?>" width="80" height="80" title="Logo Protegeme">
                  <?   
                  } else {
                  ?>
                     <img class="main-menu__icon logo-protegeme" src="<?= Parameters::WEB_PATH ?>/img/adm_img/default.png" width="80" height="80" title="Logo Protegeme">
                  <?
                  }
                  ?>
               </div>
               <? if ($p1 > 0): ?>
               <li class="main-menu__item" id="links-1" name="links-menu" data-value="1">
                  <a id="adm" name="links" class="main-menu__link link-pointer" aria-label="Usuarios">
                     <span><i class="fa-solid fa-users main-menu__icon" title="Usuarios"></i>Usuarios</span>
                     <div id="spinner-menu-1" class="spinner-border spinner-border-sm text-light ms-3" role="status" hidden>
                        <span class="visually-hidden">Loading...</span>
                     </div>
                  </a>
               </li>
               <? endif; ?>
               <? if ($p2 > 0): ?>
               <li class="main-menu__item" id="links-2" name="links-menu" data-value="2">
                  <a id="aprov" name="links" class="main-menu__link link-pointer" aria-label="Aprovisionamiento">
                     <span><i class="fa fa-circle-dot main-menu__icon" title="Aprovisionamiento"></i>Aprovisionamiento</span>
                     <div id="spinner-menu-2" class="spinner-border spinner-border-sm text-light ms-3" role="status" hidden>
                        <span class="visually-hidden">Loading...</span>
                     </div>
                  </a>
               </li>
               <? endif; ?>
               <? if ($p3 > 0): ?>
               <li class="main-menu__item" id="links-3" name="links-menu" data-value="3">
                  <a id="noti" name="links" class="main-menu__link link-pointer" aria-label="Notificaciones">
                     <span><i class="fa-solid fa-envelope main-menu__icon"></i>Notificaciones</span>
                     <div id="spinner-menu-3" class="spinner-border spinner-border-sm text-light ms-3" role="status" hidden>
                        <span class="visually-hidden">Loading...</span>
                     </div>
                  </a>
               </li>
               <? endif; ?>
               <? if ($p4 > 0): ?>
               <li class="main-menu__item" id="links-4" name="links-menu" data-value="4">
                  <a id="dashboard" name="links" class="main-menu__link link-pointer" aria-label="Dashboard">
                     <span><i class="fa-solid fa-chart-line main-menu__icon" title="Dashboard"></i>Dashboard</span>
                     <div id="spinner-menu-4" class="spinner-border spinner-border-sm text-light ms-3" role="status" hidden>
                        <span class="visually-hidden">Loading...</span>
                     </div>
                  </a>
               </li>
               <? endif; ?>
               <? if ($p5 > 0): ?>
               <li class="main-menu__item" id="links-5" name="links-menu" data-value="5">
                  <a id="console" name="links" class="main-menu__link link-pointer" aria-label="Consola">
                     <span><i class="fa-solid fa-terminal main-menu__icon" title="Consola"></i>Consola</span>
                     <div id="spinner-menu-5" class="spinner-border spinner-border-sm text-light ms-3" role="status" hidden>
                        <span class="visually-hidden">Loading...</span>
                     </div>
                  </a>
               </li>
               <? endif; ?>
               <? if ($p6 > 0): ?>
               <li class="main-menu__item" id="links-6" name="links-menu" data-value="6">
                  <a id="reporting" name="links" class="main-menu__link link-pointer" aria-label="Reportes">
                     <span><i class="fa fa-file-excel main-menu__icon" title="Reportes"></i>Reportes</span>
                     <div id="spinner-menu-6" class="spinner-border spinner-border-sm text-light ms-3" role="status" hidden>
                        <span class="visually-hidden">Loading...</span>
                     </div>
                  </a>
               </li>
               <? endif; ?>
               <div class="main-menu__item main-close_sesion h-100 m-0">
                  <a href="<?= Parameters::WEB_PATH ?>/operator/login/logout.php" class="main-menu__close" onclick="localStorage.clear();">
                     <span><i class="fa fa-right-from-bracket btn-close-sesion" title="Cerrar Sesi&oacute;n"></i>&nbsp;&nbsp;Cerrar Sesi&oacute;n</span>
                  </a>
               </div>
            </ul>
         </nav>
         <div id="root" class="main"></div>
         <!--offcanvas-->
         <div class="offcanvas offcanvas-start" data-bs-backdrop="static" tabindex="-1" id="infoGeneral" aria-labelledby="infoGeneralLabel">
            <div class="offcanvas-header background-all-1 p-3 rounded-bottom d-flex align-items-center">
               <div class="d-flex align-items-center" id="infoGeneralLabel"></div>
               <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body p-0">
               <div id="data-result-adm-service"></div>
            </div>
         </div>
         <!---->
         <div id="modalopen" class="modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
               <div class="modal-content">
                  <div class="modal-header">
                     <h4 id="modal-titlep" class="fw-semibold" class="modal-title"></h4>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div id="modalp" class="modal-body"></div>
               </div>
            </div>
         </div>

         <div id="modal-pbe" class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
               <div class="modal-content">
                  <div class="modal-header">
                     <h3 id="title-modal-pbe" class="modal-title"></h3>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div id="info-modal-pbe" class="modal-body"></div>
               </div>
            </div>
         </div>

         <div class="modal fade" id="progressModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
               <div class="modal-content shadow-lg rounded">
                  <div class="modal-header">
                     <div class="d-flex">
                        <h3 class="modal-title" id="progressModalLabel">Notificaci&oacute;n Masiva</h3>
                        <div id="spinner-container" class="d-flex justify-content-center align-items-center ms-3">
                           <div id="spinner-container-progress" class="spinner-border text-primary" role="status" style="width: 1.3rem; height: 1.3rem;">
                              <span class="visually-hidden">Cargando...</span>
                           </div>
                           <div id="percentage-text" class="ms-2" style="font-size: 1.3rem; font-weight: bold;"></div>
                        </div>
                     </div>
                     <button id="btn-close-progress" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                     <div class="row">
                        <div class="col-12" id="info-progress-noty"></div>
                     </div>
                  </div>
               </div>
            </div>
         </div>

         <input type="hidden" id="gate_cod" name="gate_cod" value="<?= $Gateway->gate_cod ?>">
         <input type="hidden" id="usua_cod" name="usua_cod" value="<?= $Gateway->usua_cod ?>">
         <input type="hidden" id="dom_cod" name="dom_cod" value="<?= $Dominio->dom_cod ?>">
         <input type="hidden" id="du" name="du" value="<?= $Dominio->dominio_usuario ?>">
         <input type="hidden" id="oper_cod" name="oper_cod" value="<?= $Operador->oper_cod ?>">

         <input type="hidden" id="background-web-result" name="background-web-result" value="<?= $Design->fondo_web ?>">
         <input type="hidden" id="buttons-web-result" name="buttons-web-result" value="<?= $Design->botones_tablas_web ?>">
         <input type="hidden" id="color-letters-web-result" name="color-letters-web-result" value="<?= $Design->color_letra_web ?>">
         <input type="hidden" id="tamano-fuente-web-result" name="tamano-fuente-web-result" value="<?= $Design->tamano_fuente_web ?>">

         <input type="hidden" id="background-app-result" name="background-app-result" value="<?= $Design->fondo_app ?>">
         <input type="hidden" id="buttons-app-result" name="buttons-app-result" value="<?= $Design->botones_tablas_app ?>">
         <input type="hidden" id="color-letters-app-result" name="color-letters-app-result" value="<?= $Design->color_letra_app ?>">

         <input type="hidden" id="session-id-<?= $Gateway->usua_cod ?>" name="session-id-<?= $Gateway->usua_cod ?>" value="<?= session_id() ?>">

         <input type="hidden" id="p1" name="permissions" value="<?= $p1 ?>">
         <input type="hidden" id="p2" name="permissions" value="<?= $p2 ?>">
         <input type="hidden" id="p3" name="permissions" value="<?= $p3 ?>">
         <input type="hidden" id="p4" name="permissions" value="<?= $p4 ?>">
         <input type="hidden" id="p5" name="permissions" value="<?= $p5 ?>">
         <input type="hidden" id="p6" name="permissions" value="<?= $p6 ?>">

      </section>
      <script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/js/jquery-3.7.1.min.js"></script>
      <script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/js/jquery.rut.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
      <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
		<script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/js/papaparse.min.js"></script>
      <script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/js/xlsx.full.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.0/FileSaver.min.js" integrity="sha512-csNcFYJniKjJxRWRV1R7fvnXrycHP6qDR21mgz1ZP55xY5d+aHLfo9/FcGDQLfn2IfngbAHd8LdfsagcCqgTcQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
      <script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/js/moment.js"></script>
      <script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/js/kit.fontawesome.js"></script>
      <script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
      <script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/js/ua-parser.min.js"></script>
      <script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/customer/js/screens.js?<?= $v ?>"></script>
      <script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/js/functions.js?<?= $v ?>"></script>
      <script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/operator/js/pbe_funcions_operator.js?<?= $v ?>"></script>
      <script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/customer/js/pbe_chart.js?<?= $v ?>"></script>
   </body>
</html>
<?
   $DB->Logoff();
?>