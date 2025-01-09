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

   if (!isset($_GET)) {
      $message = MOD_Error::Error('PBE_117');
      $error = true;
      goto result;
   }

   // identificador del usuario boton
   $busua_cod = isset($_GET['bu']) ? intval($_GET['bu']) : false;

   if ($busua_cod === false) {
      $message = MOD_Error::Error('PBE_116');
      $error = true;
      goto result;
   }

   require_once 'BPLog.php';
   require_once 'BAlerta.php';

   $Log = new BPLog();
   $Alert = new BAlerta();
   
   $dataLog = array();
   $dataAlert = array();

   $stat = $Log->buscaUltimo($busua_cod, $DB);

   while ($stat) {
      array_push($dataLog, [
         'id'           => $Log->id,
         'busua_cod'    => $Log->busua_cod,
         'fecha'        => $Log->fecha,
         'coordenadas'  => $Log->coordenadas,
         'plataforma'   => $Log->plataforma
      ]);
      $stat = $Log->siguiente($DB);
   }

   $stat2 = $Alert->buscaUltimo($busua_cod, $DB);

   while ($stat2) {
      $activa_desc = '';
      //0:Alerta No atendida / 1:Escalada  /2:Descartada /3:Llamada de prueba /4:Llamada por error /-1:Error por software
      switch ($Alert->activa) {
         case '0':
            $activa_desc = 'Alerta No atendida';
            break;
         case '1':
            $activa_desc = 'Escalada';
            break;
         case '2':
            $activa_desc = 'Descartada';
            break;
         case '3':
            $activa_desc = 'Llamada de prueba';
            break;
         case '4':
            $activa_desc = 'Llamada por error';
            break;
         default:
            $activa_desc = 'Error por software';
            break;
      }
      array_push($dataAlert, [
         'id'              => $Alert->alert_cod,
         'busua_cod'       => $Alert->busua_cod,
         'activa'          => $Alert->activa,
         'activa_desc'     => $activa_desc,
         'fecha_creacion'  => $Alert->fecha_creacion,
         'posicion'        => $Alert->posicion,
      ]);
      $stat2 = $Alert->siguienteBuscaAlerta($DB);
   }

result:
   if ($error === true) {
      $data = array( 'status'    => 'error',
                     'message'   => $message );
      echo json_encode($data);
   } else {
      $data = array( 'status'    => 'success',
                     'message'   => 'Ok',
                     'dataLog'   => $dataLog,
                     'dataAlert' => $dataAlert );
      echo json_encode($data);
   }
   $DB->Logoff();
?>