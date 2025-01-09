<?
   require_once 'MOD_Error.php';
   require_once 'BConexion.php';
   require_once 'BUsuarioRV.php';
   require_once 'BGrupo.php';

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
      <form class="row g-3 needs-validation" method="post" action="creacion_usuario.php" OnSubmit="return VerificaClave()">
      <fieldset>
         <legend>DATOS USUARIO</legend>
         <div class="col-md-12">
            <label for="cloud_username" class="form-label">USERNAME (*)</label>
            <input type="text" class="form-control" id="cloud_username" name="cloud_username" maxlength=128 pattern="<?= Parameters::PATTERN_ALFANUMERICO ?>" value="" title="<?= Parameters::TEXT_ALFANUMERICO ?>" required>
         </div>
         <div class="col-md-12">
            <label class="form-label" for="password">Contraseña(*)</label>
            <input type="password" class="form-control" name="password" id="password" pattern="<?= Parameters::PATTERN_PASSWORD ?>" title="<?= Parameters::TEXT_PASSWORD ?>" required>
         </div>
         <div class="col-md-12">
            <label class="form-label" for="password_v">Verifique Contraseña(*)</label>
            <input type="password" class="form-control" name="password_v" id="password_v" pattern="<?= Parameters::PATTERN_PASSWORD ?>" title="<?= Parameters::TEXT_PASSWORD ?>" required>
            <div id="validationPassword" class="invalid-feedback">La contraseña de  verificación no coincide con la dada .</div>
         </div>
         <div class="col-md-12">
            <label for="user_phone" class="form-label">Teléfono:</label>
            <input type="text" class="form-control" id="user_phone" name="user_phone"  pattern="<?= Parameters::PATTERN_TELEFONO ?>" title="<?= Parameters::TEXT_TELEFONO ?>">
         </div>
         <div class="col-md-12">
            <label for="email" class="form-label">Correo Electrónico (*):</label>
            <input type="email" class="form-control" id="email" name="email"  title="<?= Parameters::TEXT_EMAIL ?>" required>
         </div>
         <div class="col-md-12">
            <label for="nombre" class="form-label">Nombre(*)</label>
            <input type="text" class="form-control" id="nombre" name="nombre"  pattern="<?= Parameters::PATTERN_NAMES ?>" title="<?= Parameters::TEXT_NAMES ?>" required>
         </div>
         <div class="col-md-12">
            <label for="group_cod" class="form-label">Grupo:</label>
            <select id="group_cod" name="group_cod" class="form-select" required>
               <option value = "">---SELECCIONAR</option>
                  <?
                     $Grupo   = new BGrupo;
                     $stat    = $Grupo->Primero($UsuarioRV->usua_cod, $DB);
                     while ($stat)
                     {
                  ?>
                        <option value="<?= $Grupo->group_cod ?>"><?= $Grupo->nombre ?></option>
                  <?
                        $stat = $Grupo->Siguiente($DB);
                     }
                  ?>
            </select>
         </div>
      </fieldset>
      <div class="col-md-12">
            <button type="submit" class="btn btn-primary">Inscribirse</button>
         </div>
      </form>
      </div>
   </body>
</html>