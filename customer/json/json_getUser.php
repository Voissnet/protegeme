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

   $busua_cod = isset($_GET['busua_cod']) ? intval($_GET['busua_cod']) : false;          // identificador del usuario

   if ($busua_cod === false) {
      $message = MOD_Error::Error('PBE_117');
      $error = true;
      goto result;
   }

   require_once 'BDominio.php';
   require_once 'BGrupo.php';
   require_once 'BUsuario.php';
   require_once 'BEstado.php';

   $Dominio    = new BDominio();
   $Grupo      = new BGrupo();
   $Usuario    = new BUsuario();
   $Estado     = new BEstado();

   $dataGroups;
   
   if ($Usuario->busca($busua_cod, $DB) === false) {
      $message = 'No se encuentra usuario';
      $error = true;
      goto result;
   }

   if ($Grupo->busca($Usuario->group_cod, $DB) === false) {
      $message = 'No se encuentra grupo del usuario';
      $error = true;
      goto result;
   }

   if ($Dominio->busca($Grupo->dom_cod, $DB) === false) {
      $message = 'No se encuentra dominio del usuario';
      $error = true;
      goto result;
   }

   // Busca el estado
   $Estado->busca($Usuario->esta_cod, $DB);

   // busca todos los grupos de un dominio especifico
   $stat = $Grupo->buscaDom($Grupo->dom_cod, $DB);
   $i = 0;

   while ($stat) {

      $dataGroups[$i] = [
         'group_cod' => $Grupo->group_cod,
         'nombre'    => $Grupo->nombre
      ];

      $i++;
      $stat = $Grupo->siguiente($DB);
   }

result:
   if ($error === true) {

      $data = array( 'status'  => 'error',
                     'message' => $message );
      echo json_encode($data);

   } else {

      $data = array( 'status'             => 'success',
                     'message'            => 'Datos encontrados',
                     'dominio'            => $Dominio->dominio,
                     'dominio_usuario'    => $Dominio->dominio_usuario,
                     'busua_cod'          => $Usuario->busua_cod,
                     'cloud_username'     => $Usuario->cloud_username,
                     'cloud_password'     => $Usuario->cloud_password,
                     'esta_cod'           => $Usuario->esta_cod,
                     'estado'             => $Estado->estado,
                     'dom_cod'            => $Grupo->dom_cod,
                     'group_cod'          => $Usuario->group_cod,
                     'user_phone'         => $Usuario->user_phone,
                     'email'              => $Usuario->email,
                     'nombre'             => $Usuario->nombre,
                     // grupos
                     'dataGroups'         => $dataGroups
                  );
      echo json_encode($data);
      
   }

   $DB->Logoff();
?>