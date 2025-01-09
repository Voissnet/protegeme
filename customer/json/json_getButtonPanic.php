<?
   require_once 'MOD_Error.php';
   require_once 'Parameters.php';
   require_once 'BConexion.php';
   require_once 'BUsuarioRV.php';

   $UsuarioRV  = new BUsuarioRV;
   $DB         = new BConexion;

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

   $message = '';
   $error = false;

   if (!isset($_GET)) {
      $message = MOD_Error::ErrorCode('PBE_117');
      $error = true;
      goto result;
   }

   $busua_cod = isset($_GET['busua_cod']) ? $_GET['busua_cod'] : false;          // identificador del usuario

   if ($busua_cod === false) {
      $message = MOD_Error::ErrorCode('PBE_117');
      $error = true;
      goto result;
   }

   require_once 'BUsuario.php';
   require_once 'BBoton.php';
   require_once 'BGrupo.php';
   require_once 'BDominio.php';

   $Usuario = new BUsuario();
   $Boton   = new BBoton();
   $Grupo   = new BGrupo();
   $Dominio = new BDominio();

   if ($Usuario->buscaUser($busua_cod, $DB) === false) {
      $message = 'Usuario no encontrado';
      $error = true;
      goto result;
   }

   if ($Grupo->busca($Usuario->group_cod, $DB) === false) {
      $message = 'Grupo no encontrado';
      $error = true;
      goto result;
   }

   if ($Dominio->busca($Grupo->dom_cod, $DB) === false) {
      $message = 'Dominio no encontrado';
      $error = true;
      goto result;
   }

   $dataBP = [];

   $stat = $Boton->buscaUserActivo($Usuario->busua_cod, $DB);

   if ($stat === false) {
      $error = true;
      $message = 'Error: Boton no encontrado - cod: 05';
      goto result;
   }

   while ($stat) {

      $estado = '';

      switch ($Boton->esta_cod) {
         case 1:
            $estado = 'Activo';
            break;
         case 2:
            $estado = 'Inactivo';
            break;
         case 3:
            $estado = 'Eliminado';
            break;
         default:
            $estado = 'Err';
            break;
      }

      array_push($dataBP, [
         'bot_cod'            => $Boton->bot_cod,
         'sip_username'       => $Boton->sip_username,
         'sip_password'       => $Boton->sip_password,
         'sip_display_name'   => $Boton->sip_display_name,
         'esta_cod'           => $Boton->esta_cod,
         'estado'             => $estado,
         'busua_cod'          => $Boton->busua_cod,
         'tipo_cod'           => $Boton->tipo_cod,
         'tipo'               => $Boton->tipo,
         'localizacion'       => $Boton->localizacion,
         'mac'                => $Boton->mac,
         'fecha_creacion'     => $Boton->fecha_creacion,
         'fecha_notificacion' => $Boton->fecha_notificacion
      ]);

      $stat = $Boton->siguienteUserActivo($DB);
   }

result:
   if ($error === true) {

      $data = array( 'status'  => 'err',
                     'message' => $message );
      echo json_encode($data);

   } else {

      $data = array( 'status'                => 'success',
                     'message'               => 'Datos encontrados',
                     'dom_cod'               => $Dominio->dom_cod,
                     'group_cod'             => $Grupo->group_cod,
                     'cloud_username'        => $Usuario->cloud_username,
                     'dominio_usuario'       => $Dominio->dominio_usuario,
                     'dataBP'                => $dataBP );
      echo json_encode($data);

   }

   $DB->Logoff();
?>