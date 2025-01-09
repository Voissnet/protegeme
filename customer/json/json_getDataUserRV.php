<?
   require_once 'Parameters.php';
   require_once 'MOD_Error.php';
   require_once 'BConexion.php';
   require_once 'BUsuarioRV.php';

   $UsuarioRV  = new BUsuarioRV();
   $DB         = new BConexion();

   if ($UsuarioRV->sec_session_start_ajax() === false) {
      $DB->Logoff();
      MOD_Error::ErrorJSON('PBE_129');
      exit;
   }

   if ($UsuarioRV->VerificaLogin($DB) === false) {
      $DB->Logoff();
      MOD_Error::ErrorJSON('PBE_130');
      exit;
   }

   $message = '';                   // mensaje para los errores
   $error   = false;                // variable para identificar errores

   // preguntamos si viene algo por GET
   if (!isset($_GET)) {
      $message = 'No se registran datos - cod: 00';
      $error = true;
      goto result;
   }

   // usua_cod
   $usua_cod = isset($_GET['usua_cod']) ? $_GET['usua_cod'] : false;

   if ($UsuarioRV->usua_cod !== $usua_cod) {
      $message = 'No se registran datos - cod: 01';
      $error = true;
      goto result;
   }

   // clases
   require_once 'BMedidaEmpresa.php';
   require_once 'BRubro.php';
   require_once 'BPais.php';

   $Tamano = new BMedidaEmpresa();
   $Rubro = new BRubro();
   $Pais = new BPais();

   $dataUserRV = [];
   $dataTamano = [];
   $dataRubro = [];
   $dataPais = [];

   array_push($dataUserRV, [
      'usua_cod'        => $UsuarioRV->usua_cod,
      'enterprise'      => $UsuarioRV->empresa,
      'reason_social'   => $UsuarioRV->razon_social,
      'rut_enterprise'  => $UsuarioRV->rut_empresa,
      'med_cod'         => $UsuarioRV->med_cod,
      'rub_cod'         => $UsuarioRV->rub_cod,
      'names'           => $UsuarioRV->nombre,
      'last_names'      => $UsuarioRV->apellidos,
      'rut'             => $UsuarioRV->rut,
      'post'            => $UsuarioRV->cargo,
      'phone'           => $UsuarioRV->telefono_celular,
      'telephone'       => $UsuarioRV->telefono_fijo === null ? '' : $UsuarioRV->telefono_fijo,
      'email'           => $UsuarioRV->email,
      'pais_cod'        => $UsuarioRV->pais_cod,
      'username'        => $UsuarioRV->username,
   ]);

   $stat = $Tamano->Primero($DB);

   while ($stat) {
      array_push($dataTamano, [
         'med_cod' => $Tamano->med_cod,
         'descripcion' => $Tamano->descripcion
      ]);
      $stat = $Tamano->Siguiente($DB);
   }

   $stat2 = $Rubro->Primero($DB);

   while ($stat2) {
      array_push($dataRubro, [
         'rub_cod' => $Rubro->rub_cod,
         'descripcion' => $Rubro->descripcion
      ]);
      $stat2 = $Rubro->Siguiente($DB);
   }

   $stat3 = $Pais->primero($DB);
   
   while ($stat3) {
      array_push($dataPais, [
         'pais_cod' => $Pais->pais_cod,
         'des_espanol' => $Pais->des_espanol
      ]);
      $stat3 = $Pais->siguiente($DB);
   }

result:
   if ($error === true) {
      $data = array( 'status'  => 'error',
                     'message' => $message );
      echo json_encode($data);
   } else {
      $data = array( 'status'       => 'success',
                     'message'      => 'OK',
                     'dataURV'      => $dataUserRV,
                     'dataTamano'   => $dataTamano,
                     'dataRubro'    => $dataRubro,
                     'dataPais'     => $dataPais );
      echo json_encode($data);
   }
   $DB->Logoff();
?>