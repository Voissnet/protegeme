<?
   require_once 'MOD_Error.php';
   require_once 'BConexion.php';
   require_once 'BUsuarioRV.php';
   require_once 'BUsuario.php';
   require_once 'BBoton.php';

   $Usuario = new BUsuarioRV;
   $DB      = new BConexion;
   $DB2     = new BConexion;

   $Usuario->sec_session_start();
   if ($Usuario->VerificaLogin($DB) === FALSE)
   {
      $DB->Logoff();
      MOD_Error::Error("PBE_101");
      exit;
   }
?>
<!DOCTYPE html>
<html lang="es">
   <head>
      <title>Reporte de Usuarios</title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
      <script src="<?= Parameters::WEB_PATH ?>/js/jquery-3.7.1.min.js"></script>
      <script src="<?= Parameters::WEB_PATH ?>/js/bootstrap.min.js"></script>
      <script src="<?= Parameters::WEB_PATH ?>/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
      <link href="<?= Parameters::WEB_PATH ?>/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
   </head>
   <body>
      <h2>Listado de Usuarios:</h2>
      <table>
         <thead>
            <tr>
               <th>BUSUA_COD</th>
               <th>CLOUD_USERNAME</th>
               <th>ESTADO</th>
               <th>TELÉFONO CONTACTO</th>
               <th>EMAIL CONTACTO</th>
               <th>NOMBRE</th>
               <th>GRUPO CONTACTO</th>
               <th>BOTON DE PÁNICO</th>
               <th>TRACKING</th>
            </tr>
         </thead>
         <tbody>
            <?
               $Boton = new BBoton;

               $User = new BUsuario;
               $stat = $User->listadoUsuarios($Usuario->usua_cod, $DB);
               while($stat)
               {
                  ($Boton->BuscaBoton($User->busua_cod, $DB2) === TRUE)? $asigna=FALSE: $asigna=TRUE;
            ?>
                  <tr>
                     <td><?= $User->busua_cod ?></td>
                     <td><?= $User->cloud_username ?></td>
                     <td><?= $User->estado ?></td>
                     <td><?= $User->user_phone ?></td>
                     <td><?= $User->email ?></td>
                     <td><?= $User->nombre ?></td>
                     <td><?= $User->grupo ?></td>
                     <td><a href="../provisioning/<?= $asigna ? "form_prov_boton.php?busua_cod=$User->busua_cod\">Asignar botón</a>" : "form_editar_boton.php?bot_cod=$Boton->bot_cod\">Editar botón</a>" ?></td>
                     <td><a href="../provisioning/form_prov_tracking.php?busua_cod=<?= $User->busua_cod ?>">Asignar tracking</a></td>
                  </tr>
            <?
                  $stat = $User->siguienteListadoUsuarios($DB);
               }
            ?>
         </tbody>
      </table>
   </body>
</html>
<?
   $DB->Logoff();
   $DB2->Logoff();