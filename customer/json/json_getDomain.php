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
      $message = MOD_Error::Error('PBE_117');
      $error = true;
      goto result;
   }

   $dom_cod = isset($_GET['dom_cod']) ? $_GET['dom_cod'] : false;

   if ($dom_cod === false) {
      $message = MOD_Error::Error('PBE_117');
      $error = true;
      goto result;
   }

   require_once 'BDominio.php';
   require_once 'BGrupo.php';
   require_once 'BTipoBoton.php';
   require_once 'BTipoServicio.php';
   require_once 'BUsuarioRV.php';
   require_once 'BGateway.php';

   $Dominio       = new BDominio();
   $Grupo         = new BGrupo();
   $TipoBoton     = new BTipoBoton();
   $TipoServicio  = new BTipoServicio();
   $Cliente       = new BUsuarioRV();
   $Gateway       = new BGateway();

   $groups;
   $tipoBs;
   $tipoSs;
   $i = 0;
   $b = 0;
   $s = 0;

   if ($Dominio->busca($dom_cod, $DB) === false) {
      $message = 'Grupos no econtrados';
      $error = true;
      goto result;
   }

   if ($Dominio->cantidadUsuarios($DB) === false) {
      $message = 'Grupos no econtrados';
      $error = true;
      goto result;
   }

   $stat = $Grupo->buscaDom($Dominio->dom_cod, $DB);

   // busca los grupos del dominio
   while ($stat) {

      $groups[$i] = [
         'group_cod' => $Grupo->group_cod,
         'nombre'    => $Grupo->nombre,
         'dom_cod'   => $Grupo->dom_cod,
         'numeros'   => $Grupo->numeros
      ];

      $i++;
      $stat = $Grupo->siguiente($DB);
   }

   // traemos los tipos de botones
   $stat2 = $TipoBoton->primero($DB);

   while ($stat2) {

      $tipoBs[$b] = [
         'tipo_cod'  => $TipoBoton->tipo_cod,
         'tipo'      => $TipoBoton->tipo
      ];

      $b++;
      $stat2 = $TipoBoton->siguiente($DB);
   }

   // traemos los tipos de servicios
   $stat3 = $TipoServicio->primero($DB);

   while ($stat3) {

      $tipoSs[$s] = [
         'tipo_cod'        => $TipoServicio->tipo_cod,
         'tipo_servicio'   => $TipoServicio->tipo_servicio
      ];

      $s++;
      $stat3 = $TipoServicio->siguiente($DB);
   }

   if (count($groups) < 0) {
      $message = 'Grupos no econtrados';
      $error = true;
      goto result;
   }

   if ($Gateway->VerificarGateway($Dominio->gate_cod, $DB) === false) {                    // verifica que exista el dominio
      $message = 'Servicio no encontrado';
      $error = true;
      goto result;
   }

   if ($Cliente->Busca($Gateway->usua_cod, $DB) === false) {              // busca los datos del cliente redvoiss
      $message = 'Datos cliente no encontrados';
      $error = true;
      goto result;
   }

   if ($Gateway->VerificarTipoGateway($Gateway->gate_cod, $DB) === false) {               // verifica el tipo de adaptador que tiene
      $message = 'No se encontro tipo servicio';
      $error = true;
      goto result;
   }

result:
   if ($error === true) {

      $data = array( 'status'  => 'error',
                     'message' => $message );
      echo json_encode($data);

   } else {

      $data = array( 'status'             => 'success',
                     'message'            => 'Grupos encontrados',
                     'dom_cod'            => $Dominio->dom_cod,
                     'dominio'            => $Dominio->dominio,
                     'dominio_usuario'    => $Dominio->dominio_usuario,
                     'usua_cod'           => $Cliente->usua_cod,
                     'username'           => $Cliente->username,
                     'gate_cod'           => $Gateway->gate_cod,
                     'demo'               => $Dominio->demo,
                     'cantidad_usuario'   => $Dominio->cantidad_usuario,
                     'cantidad'           => $Dominio->cantidad,
                     'groups'             => $groups,
                     'tipoBs'             => $tipoBs,
                     'tipoSs'             => $tipoSs );
      echo json_encode($data);
      
   }

   $DB->Logoff();
?>