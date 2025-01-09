<?
   require_once 'Parameters.php';
   require_once 'BConexion.php';
   require_once 'BUsuarioRV.php';

   $UsuarioRV  = new BUsuarioRV;
   $DB         = new BConexion;

   $UsuarioRV->sec_session_start();
   if ($UsuarioRV->VerificaLogin($DB) === FALSE) {
      $DB->Logoff();
      header('Location: ' . Parameters::WEB_PATH . '/customer/login/');
      exit;
   }
   
   require_once 'BGateway.php';
   require_once 'BDominio.php';
   require_once 'BDesign.php';

   $Gateway = new BGateway();
   $Dominio = new BDominio();
   $Design  = new BDesign();

   if ($Gateway->buscaSOS($UsuarioRV->usua_cod, $DB) === false) {
      $DB->Logoff();
      MOD_Error::Error('PBE_125');
      exit;
   }
   if ($Dominio->verificaGateCod($Gateway->gate_cod, $DB) === false) {
      $DB->Logoff();
      MOD_Error::Error('PBE_125');
      exit;
   }

   if ($Design->busca($Dominio->dom_cod, $DB) === false) {
      $DB->Logoff();
      MOD_Error::Error('PBE_125');
      exit;
   }

   $v = rand();
   
?>
<!DOCTYPE html>
<html lang="es">
   <head>
   </head>
   <body>
   <form id="form_cartola" name="form_cartola" method="post" OnSubmit="return false">
            <fieldset>
               <legend>Seleccione método de pago disponible</legend>
               <p>
                  <a href="form_selec_monto.php?meto_cod=20"><img src="<?= Parameters::WEB_PATH ?>/img/pagos/Servipag.svg" style="vertical-align:middle" width=60></a> Inmediatamente, una vez aprobada la transación por Servipag y el Banco/Tarjeta correspondiente
               </p>
            </fieldset>
         </form>
   </body>
</html>
<?
   $DB->Logoff();
?>