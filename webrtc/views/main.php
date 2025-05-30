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

      <!-- sweetalert2 -->
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


      <!-- Drive.js -->
      <script src="<?= Parameters::WEB_PATH ?>/webrtc/src/js/driver.js"></script>
      <link rel="stylesheet" href="<?= Parameters::WEB_PATH ?>/webrtc/src/css/driver.css"/>

      <!-- STYLES css -->
      <link rel="stylesheet" href="<?= Parameters::WEB_PATH ?>/webrtc/src/css/toastr.min.css">
      <link rel="stylesheet" href="<?= Parameters::WEB_PATH ?>/webrtc/src/css/all.min.css" type="text/css">
      <link rel="stylesheet" href="<?= Parameters::WEB_PATH ?>/css/bootstrap.min.css" type="text/css">
      <link rel="stylesheet" href="<?= Parameters::WEB_PATH ?>/css/stylesSite.css?v=20">
      <link rel="stylesheet" href="<?= Parameters::WEB_PATH ?>/webrtc/src/css/styles.css?v=<?= rand() ?>">
      
   </head>

   <body class="bg-dark d-flex align-items-center justify-content-center m-0 p-0" style="overflow: hidden;">

      <div class="container-fluid vh-100">

         <div class="row text-center mt-2">
            <div class="col d-flex justify-content-end">
               <img id="question1" src="<?= Parameters::WEB_PATH ?>/webrtc/src/img/icon_question.png" width="25" height="25" title="Uso de men&uacute;" onclick="use1(1)">
            </div>
         </div>

        <div class="row text-center">
            <div class="col">
               <img id="btn-emergency" src="<?= Parameters::WEB_PATH ?>/webrtc/src/img/logo_protegueme.png" width="180" height="180" title="Bot&oacute;n de Aviso de Emergencia" disabled>
            </div>
        </div>

        <div class="row text-center">
            <div class="col text-center">
                <span id="state-register" class="text-white hide"></span>
            </div>
        </div>

        <div id="videos" class="row my-2 d-flex justify-content-center hide">
            <div class="col w-75">
                <div class="card">
                    <div class="card-header text-center">
                        <span class="card-title">Remote UA</span>
                    </div>
                    <div class="card-body" id="videoright"></div>
                </div>
            </div>
        </div>

        <footer id="footerButtons">
         <div class="container">
            <div class="row">
               <div id="gap-footer" class="col-12">
                  <div id="div1" class="barra-indicador bg-dark"></div>
                  <div id="div2" class="barra-indicador bg-dark"></div>
                  <div id="div3" class="barra-indicador bg-dark"></div>
               </div>
            </div>
         </div>
         </footer>

      </div>

      <script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/webrtc/src/js/settings.js?v=<?= rand() ?>"></script>
      <script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/webrtc/src/js/janus.js?v=<?= rand() ?>"></script>
      <script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/webrtc/src/js/functions_janus.js?v=<?= rand() ?>"></script>
      <script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/webrtc/src/js/functions_webrtc.js?v=<?= rand() ?>"></script>
      
      <script type="text/javascript">
         document.addEventListener("DOMContentLoaded", async function(event) {

            // validacion de sesion
            const response = await fetchProtectedData();

            await loadJanus(response);

         });
      </script>

   </body>

</html>
