<?
   require_once 'MOD_Error.php';
   require_once 'BConexion.php';
   require_once 'BUsuarioRV.php';
   require_once 'BGrupo.php';
   require_once 'BTipoBoton.php';

   $UsuarioRV  = new BUsuarioRV;
   $DB         = new BConexion;

   $UsuarioRV->sec_session_start();
   if ($UsuarioRV->VerificaLogin($DB) === FALSE)
   {
      $DB->Logoff();
      MOD_Error::Error("PBE_101");
      exit;
   }
?>
<!DOCTYPE html>
<html lang="es">
   <header>
      <script src="<?= Parameters::WEB_PATH ?>/js/jquery-3.7.1.min.js"></script>
      <script src="<?= Parameters::WEB_PATH ?>/js/bootstrap.min.js"></script>
      <script src="<?= Parameters::WEB_PATH ?>/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
      <link href="<?= Parameters::WEB_PATH ?>/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
      <script src="<?= Parameters::WEB_PATH ?>/js/functions.js"></script>
   </header>
   <body>
      <div class="container-sm">
      <form class="row g-3 needs-validation" method="post" action="prov_boton.php" OnSubmit="return VerificaClave()">
      <fieldset>
         <legend>Asignación de Botón</legend>
         <div class="col-md-12">
            <label for="sip_username" class="form-label">SIP username (*)</label>
            <input type="number" class="form-control" id="sip_username" name="sip_username" maxlength=128 required>
         </div>
         <div class="col-md-12">
            <label class="form-label" for="password">SIP password(*)</label>
            <input type="password" class="form-control" name="password" id="password" pattern="<?= Parameters::PATTERN_PASSWORD ?>" title="<?= Parameters::TEXT_PASSWORD ?>" required>
         </div>
         <div class="col-md-12">
            <label class="form-label" for="password_v">Verifique SIP password(*)</label>
            <input type="password" class="form-control" name="password_v" id="password_v" pattern="<?= Parameters::PATTERN_PASSWORD ?>" title="<?= Parameters::TEXT_PASSWORD ?>" required>
            <div id="validationPassword" class="invalid-feedback">La contraseña de  verificación no coincide con la dada .</div>
         </div>
         <div class="col-md-12">
            <label for="sip_display_name" class="form-label">SIP display name:</label>
            <input type="text" class="form-control" id="sip_display_name" name="sip_display_name" maxlength=128>
         </div>
         <div class="col-md-12">
            <label for="tipo_cod" class="form-label">Tipo de Botón:</label>
            <select id="tipo_cod" name="tipo_cod" class="form-select" required>
               <option value = "">---SELECCIONAR</option>
                  <?
                     $Boton   = new BTipoBoton;
                     $stat    = $Boton->Primero($DB);
                     while ($stat)
                     {
                  ?>
                        <option value="<?= $Boton->tipo_cod ?>"><?= $Boton->tipo ?></option>
                  <?
                        $stat = $Boton->Siguiente($DB);
                     }
                  ?>
            </select>
         </div>
         <div class="col-md-12">
            <label for="localizacion" class="form-label">Localización:</label>
            <input type="text" class="form-control" id="localizacion" name="localizacion" maxlength=128>
            <input type=hidden name="busua_cod" value="<?= $_GET["busua_cod"] ?>"
         </div>
      </fieldset>
      <div class="col-md-12">
            <button type="submit" class="btn btn-primary">Inscribirse</button>
         </div>
      </form>
      </div>
   </body>
</html>