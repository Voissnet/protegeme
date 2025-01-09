<?
   require_once 'MOD_Error.php';
   require_once 'Parameters.php';
   require_once 'BConexion.php';
   require_once 'BOperador.php';

   $Operador   = new BOperador();
   $DB         = new BConexion();

   if ($Operador->sec_session_start_ajax() === false) {
      $DB->Logoff();
      MOD_Error::ErrorJSON('PBE_129');
      exit;
   }

   if ($Operador->VerificaLogin($DB) === false) {
      $DB->Logoff();
      MOD_Error::ErrorJSON('PBE_130');
      exit;
   }
   
   $message = '';
   $error = false;

   if (!isset($_POST)) {
      $message = MOD_Error::Error('PBE_116');
      $error = true;
      goto result;
   }

   $data          = json_decode(file_get_contents('php://input'), true);
   $busua_cod     = isset($data['busua_cod']) ? intval($data['busua_cod']) : false;
   $localizacion  = isset($data['localizacion']) ? $data['localizacion'] : false;
   $coordenadas   = Parameters::obtieneCoordenadas($localizacion);

   if ($busua_cod === false || $localizacion === false) {
      $message = 'Error: No se registran datos - cod: 001';
      $error   = true;
      goto result;
   }

   // clases
   require_once 'BUsuario.php';
   require_once 'BBoton.php';
   require_once 'BTipoBoton.php';
   require_once 'BOperadorLog.php';

   $Usuario = new BUsuario();
   $Boton   = new BBoton();
   $Tipo    = new BTipoBoton();
   $Log     = new BOperadorLog();

   if ($Usuario->buscaUser($busua_cod, $DB) === false) {
      $message = 'Error: No se registran usuario - cod: 002';
      $error   = true;
      goto result;
   }

   if ($Boton->BuscaBoton($busua_cod, $DB) === false) {
      $message = 'Error: No se registra servicio - cod: 003';
      $error   = true;
      goto result;
   }

   if ($Boton->actualizaLocalizacion($localizacion, $coordenadas, $DB) === false) {
      $message = 'Error: No se pudo modificar la localización - cod: 004';
      $error   = true;
      goto result;
   }

result:
      if ($error === true) {

         $data = array( 'status'       => 'error',
                        'message'      => $message );
         echo json_encode($data);

      } else {

         $Boton->BuscaBoton($busua_cod, $DB);
         $Tipo->Busca($Boton->tipo_cod, $DB);   

         $desc = 'LOCALIZACION SERVICIO DE BP MODIFICADO | BUSUA_COD: ' . $Usuario->busua_cod . ' | TIPO_COD: ' . $Boton->tipo_cod . ' | DESC_TIPO: ' . $Tipo->tipo . ' | LOCALIZACION NUEVA: ' . $Boton->localizacion . ' | COORDENADAS NUEVAS: ' . $Boton->coordenadas;
         $Log->inserta($Operador->oper_cod, $desc, 'operator/json/json_updateLocalizacion.php', $DB);
   
         $data = array( 'status'       => 'success',
                        'message'      => '<span class="text-primary">Localizacion</span> actualizada',
                        'busua_cod'    => $busua_cod,
                        'localizacion' => $localizacion );
         echo json_encode($data);

      }
   $DB->Logoff();
?>