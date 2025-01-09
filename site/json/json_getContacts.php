<?
   require_once 'Parameters.php';
   require_once 'BConexion.php';
   require_once 'MOD_Error.php';

   $DB = new BConexion();
   $DB2 = new BConexion();

   $message = '';
   $error = false;

   if (!isset($_GET)) {
      $message = MOD_Error::Error('PBE_117');
      $error = true;
      goto result;
   }

   // identificador del usuario
   $busua_cod = isset($_GET['bu']) ? intval($_GET['bu']) : false;

   if ($busua_cod === false) {
      $message = MOD_Error::ErrorCode('PBE_117');
      $error = true;
      goto result;
   }

   require_once 'BUsuario.php';
   require_once 'BContactosLlamada.php';
   require_once 'BContactosSMS.php';

   $Usuario    = new BUsuario();
   $Llamada    = new BContactosLlamada();
   $Llamada2   = new BContactosLlamada();
   $SMS        = new BContactosSMS();

   $contacts      = [];

   // verificar que exista el usuario
   if ($Usuario->busca($busua_cod, $DB) === false) {
      $message = MOD_Error::ErrorCode('PBE_117');
      $error = true;
      goto result;
   }

   if ($Usuario->esta_cod === '3') {
      $message = MOD_Error::ErrorCode('PBE_120');
      $error = true;
      goto result;
   }
   
   // recorre usuario de emrgencia llamadas
   $stat = $Llamada->buscaContactos($Usuario->busua_cod, $DB);

   // si es false, es por que no tiene datos
   if ($stat === false) {
      $message = 'Usuario sin contactos de emergencia';
      $error = true;
      goto result;
   }

   $i = 0;

   while ($stat) {

      $statusCall    = true;
      $statusSMS     = true;

      if ($Llamada2->busca($Usuario->busua_cod, $Llamada->numero, $DB2) === false) {
         $statusCall = false;
      } 
      
      if ($SMS->Busca($Usuario->busua_cod, $Llamada->numero, $DB2) === false) {
         $statusSMS = false;
      } 

      array_push($contacts, [
         'numero'          => $Llamada->numero,
         'nombre'          => ($statusCall === true ? $Llamada2->nombre : null),
         'esta_cod_call'   => $Llamada2->esta_cod,
         'listen_call'     => $Llamada2->escucha,
         'esta_cod_sms'    => $SMS->esta_cod,
         'statuscall'      => $statusCall,
         'statusSMS'       => $statusSMS
      ]);

      $stat = $Llamada->siguienteContacto($DB);
   }

result:
   if ($error === true) {

         $data = array( 'status'  => 'error',
                        'message' => $message );
         echo json_encode($data);

   } else {

         $data = array( 'status'       => 'success',
                        'message'      => 'Contactos encontrados',
                        'busua_cod'    => $busua_cod,
                        'contacts'     => $contacts );
         echo json_encode($data);
         
   }
   $DB->Logoff();
   $DB2->Logoff();
?>