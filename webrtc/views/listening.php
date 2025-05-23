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

      <!-- STYLES css -->
      <link rel="stylesheet" href="<?= Parameters::WEB_PATH ?>/webrtc/src/css/toastr.min.css">
      <link rel="stylesheet" href="<?= Parameters::WEB_PATH ?>/webrtc/src/css/all.min.css" type="text/css">
      <link rel="stylesheet" href="<?= Parameters::WEB_PATH ?>/css/bootstrap.min.css" type="text/css">
      <link rel="stylesheet" href="<?= Parameters::WEB_PATH ?>/css/stylesSite.css?v=20">
      <link rel="stylesheet" href="<?= Parameters::WEB_PATH ?>/webrtc/src/css/styles.css?v=20">
   </head>

   <body class="bg-dark d-flex align-items-center justify-content-center" >

      <div class="container-fluid">

        <div class="row text-center">
            <div class="col">
               <img id="btn-emergency" src="<?= Parameters::WEB_PATH ?>/webrtc/src/img/isotipo_prtgm.png" title="Botón de Aviso de Emergencia" disabled>
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

            const numcc = response.data['num_cc'].replace(/^.{2}/, '');
            const domainsip = response.data['domain_sip'];
            
            await doCall(numcc, domainsip);

         });
      </script>

   </body>

</html>