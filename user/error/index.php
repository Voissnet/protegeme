<?
   require_once 'MOD_Error.php';
   require_once 'Parameters.php';

   if (isset($_GET['err_cod']) === FALSE) {
      $_GET['err_cod'] = 'PBE_101';
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
      <title>Error · Prot&eacute;geme</title>
      <meta name="title" content="Error Protegeme">

      <!-- Bootstrap CSS -->
      <link rel="stylesheet" href="<?= Parameters::WEB_PATH ?>/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

      <!-- STYLES css -->
      <link rel="stylesheet" href="<?= Parameters::WEB_PATH ?>/css/default.css?v<?= $v ?>">
      <link rel="stylesheet" href="<?= Parameters::WEB_PATH ?>/css/stylesLogin.css?v<?= $v ?>">

      <style>
         h2 {
            color: #88c425;
            margin: 0 10px;
            font-size: 20px;
            text-align: center;
         }

         h2 span {
            color: #bbb;
            font-size: 40px;
         }
         footer{
            position: fixed !important;
         }
         .div-form-login{
            position: absolute;
            transform: translate(-50%, -50%);
            top: 50%;
         }
      </style>
   </head>

   <body>
      <section id="error-pbe">
         <div class="container">
            <div class="row logo mb-2">
                  <div class="col-12 d-flex justify-content-center">
                     <img src="<?= Parameters::WEB_PATH ?>/img/logo-protegeme.webp" width="150" height="120">
                  </div>
            </div>
            <div class="row justify-content-center">
                  <div class="col-12 div-form-login border p-3">
                     <div class="row">
                        <div class="d-flex justify-content-center mb-2">
                              <h1 class="title-login">ERROR</h1>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-12 d-flex justify-content-center mb-2">
                              <h2><span>Code: <?= $_GET['err_cod'] ?></span><br><br><?= (new MOD_Error)->ErrorCode($_GET['err_cod']); ?></h2>
                        </div>
                     </div>
                  </div>
            </div>
         </div>
         <footer class="d-flex justify-content-center">
            <div class="copyright align-self-center">
                  <span class="text-dark">&copy; REDVOISS. TODOS LOS DERECHOS RESERVADOS</span>
            </div>
         </footer>
      </section>
      <script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/js/jquery-3.7.1.min.js"></script>
      <script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
      <script defer src="https://cdn.jsdelivr.net/npm/ua-parser-js@0/dist/ua-parser.min.js"></script>
      <script type="text/javascript" src="<?= Parameters::WEB_PATH ?>/customer/js/screens.js"></script>
   </body>

</html>