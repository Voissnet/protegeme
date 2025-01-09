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

   $dom_cod       = isset($_POST['dom_cod']) ? intval($_POST['dom_cod']) : false;
   $tipo_cod      = isset($_POST['tipo_cod']) ? intval($_POST['tipo_cod']) : false;
   $file2upload   = isset($_FILES['file2upload']) ? $_FILES['file2upload'] : false;

   if ($dom_cod === false || $tipo_cod === false || $file2upload === false) {
      $message = MOD_Error::Error('PBE_116');
      $error = true;
      goto result;
   }

   # validando archivo .txt
   if ($file2upload['type'] !== 'text/plain') {
      $message = 'Tipo de archivo no permitido';
      $error = true;
      goto result;
   }

   # variable que contiene el nombre de archivo txt
   $servicio = Parameters::obtieneNombrePlantilla($tipo_cod);

   # ruta final del archivo dominio(cod)+servicio.txt
   $ruta = '/var/www/pbe.lanube.cl/htdocs/plantillas/' . $dom_cod . '/' . $servicio . '.txt';

   if (file_exists($ruta)) {
      unlink($ruta);
   }

   // respaldo del archivo
   if (is_uploaded_file($file2upload['tmp_name'])) {
      move_uploaded_file($file2upload['tmp_name'], $ruta);
   }

   $contenido = file_get_contents($ruta);

   require_once 'BOperadorLog.php';

   $Log = new BOperadorLog();
   
   $desc = 'SE MODIFICO PLANTILLA DEL DOMINIO | DOM_COD: ' . $dom_cod . ' | TIPO_COD CAMBIADO: ' . $tipo_cod;
   $Log->inserta($Operador->oper_cod, $desc, 'operator/json/json_updatePlantilla.php', $DB);

result:
   if ($error === true) {

      $data = array( 'status'  => 'error',
                     'message' => $message );
      echo json_encode($data);

   } else {

      $data = array( 'status'    => 'success',
                     'message'   => 'Plantilla modificada',
                     'contenido' => $contenido );
      echo json_encode($data);

   }

   $DB->Logoff();
?>