<?
   class BAlerta
   {
      var $alert_cod;
      var $busua_cod;
      var $activa;
      var $tipoa_cod;
      var $tipo_alerta;
      var $fecha_creacion;
      var $link;
      var $fecha_atencion;
      var $posicion;
      var $descripcion;
      var $cantidad;
      var $causa_cod;

      // trae una alerta
      function busca($alert_cod, &$DB)
      {
         $retval                 = false;

         $valores['alert_cod']   = $alert_cod;

         $sql                    = "SELECT a.alert_cod,
                                          a.busua_cod,
                                          a.activa,
                                          a.tipoa_cod,
                                          c.tipo_alerta,
                                          TO_CHAR(a.fecha_creacion, 'MM-DD-YYYY HH24:MI:SS') fecha_creacion,
                                          a.link,
                                          TO_CHAR(a.fecha_atencion, 'MM-DD-YYYY HH24:MI:SS') fecha_atencion,
                                          a.posicion,
                                          a.descripcion,
                                          a.causa_cod
                                    FROM BP.BP_ALERTA a,
                                          BP.BP_USUARIO b,
                                          BP.BP_TIPO_ALERTA c
                                    WHERE a.busua_cod = b.busua_cod
                                    AND a.tipoa_cod = c.tipoa_cod
                                    AND b.esta_cod IN (1, 2)
                                    AND a.alert_cod = :alert_cod";

         if ($DB->Query($sql, $valores)) {
            $this->alert_cod     = $DB->Value("ALERT_COD");
            $this->busua_cod     = $DB->Value("BUSUA_COD");
            $this->activa        = $DB->Value("ACTIVA");
            $this->tipoa_cod     = $DB->Value("TIPOA_COD");
            $this->tipo_alerta   = $DB->Value("TIPO_ALERTA");
            $this->fecha_creacion= $DB->Value("FECHA_CREACION");
            $this->link          = $DB->Value("LINK");
            $this->fecha_atencion= $DB->Value("FECHA_ATENCION");
            $this->posicion      = $DB->Value("POSICION");
            $this->descripcion   = $DB->Value("DESCRIPCION");
            $this->causa_cod     = $DB->Value("CAUSA_COD");
            $retval              = true;
         }
         $DB->Close();
         return $retval;
      }

      // trae alertas de un usuario
      function buscaAlerta($busua_cod, &$DB)
      {
         $retval                 = false;

         $valores['busua_cod']   = $busua_cod;

         $sql                    = "SELECT a.alert_cod,
                                          a.busua_cod,
                                          a.activa,
                                          a.tipoa_cod,
                                          c.tipo_alerta,
                                          TO_CHAR(a.fecha_creacion, 'MM-DD-YYYY HH24:MI:SS') fecha_creacion,
                                          a.link,
                                          TO_CHAR(a.fecha_atencion, 'MM-DD-YYYY HH24:MI:SS') fecha_atencion,
                                          a.posicion,
                                          a.descripcion,
                                          a.causa_cod
                                    FROM BP.BP_ALERTA a,
                                          BP.BP_USUARIO b,
                                          BP.BP_TIPO_ALERTA c
                                    WHERE a.busua_cod = b.busua_cod
                                    AND a.tipoa_cod = c.tipoa_cod
                                    AND b.esta_cod IN (1, 2)
                                    AND a.busua_cod = :busua_cod
                                    ORDER BY a.fecha_creacion DESC";

         if ($DB->Query($sql, $valores)) {
            $this->alert_cod     = $DB->Value("ALERT_COD");
            $this->busua_cod     = $DB->Value("BUSUA_COD");
            $this->activa        = $DB->Value("ACTIVA");
            $this->tipoa_cod     = $DB->Value("TIPOA_COD");
            $this->tipo_alerta   = $DB->Value("TIPO_ALERTA");
            $this->fecha_creacion= $DB->Value("FECHA_CREACION");
            $this->link          = $DB->Value("LINK");
            $this->fecha_atencion= $DB->Value("FECHA_ATENCION");
            $this->posicion      = $DB->Value("POSICION");
            $this->descripcion   = $DB->Value("DESCRIPCION");
            $this->causa_cod     = $DB->Value("CAUSA_COD");
            $retval              = true;
         } else {
            $DB->Close();
         }
         return $retval;
      }

      // trae siguiente alerta del usuario
      function siguienteBuscaAlerta(&$DB)
      {
         $retval                 = false;

         if ($DB->Next()) {
            $this->alert_cod     = $DB->Value("ALERT_COD");
            $this->busua_cod     = $DB->Value("BUSUA_COD");
            $this->activa        = $DB->Value("ACTIVA");
            $this->tipoa_cod     = $DB->Value("TIPOA_COD");
            $this->tipo_alerta   = $DB->Value("TIPO_ALERTA");
            $this->fecha_creacion= $DB->Value("FECHA_CREACION");
            $this->link          = $DB->Value("LINK");
            $this->fecha_atencion= $DB->Value("FECHA_ATENCION");
            $this->posicion      = $DB->Value("POSICION");
            $this->descripcion   = $DB->Value("DESCRIPCION");
            $this->causa_cod     = $DB->Value("CAUSA_COD");
            $retval              = true;
         } else {
            $DB->Close();
         }
         return $retval;
      }

      // trae alertas de un usuario
      function buscaAlertaFiltro($busua_cod, $anio, $tipoa_cod, $causa_cod, $derv_cod, &$DB)
      {
         $retval                 = false;

         $valores['busua_cod']   = $busua_cod;
         $valores['fecha_ini']   = '01-01-' . $anio . ' 00:00:00';
         $valores['fecha_fin']   = '31-12-' . $anio . ' 23:59:59';

         $sql                    = "SELECT a.alert_cod,
                                          a.busua_cod,
                                          a.activa,
                                          a.tipoa_cod,
                                          c.tipo_alerta,
                                          TO_CHAR(a.fecha_creacion, 'MM-DD-YYYY HH24:MI:SS') fecha_creacion,
                                          a.link,
                                          TO_CHAR(a.fecha_atencion, 'MM-DD-YYYY HH24:MI:SS') fecha_atencion,
                                          a.posicion,
                                          a.descripcion,
                                          a.causa_cod
                                    FROM BP.BP_ALERTA a,
                                          BP.BP_USUARIO b, ";
         
         if ($derv_cod !== -1) {
            $sql                    .= " BP.BP_ALERTA_DERIVACION d, ";
         }

         $sql                       .= " BP.BP_TIPO_ALERTA c
                                    WHERE a.busua_cod = b.busua_cod
                                    AND a.tipoa_cod = c.tipoa_cod
                                    AND b.esta_cod IN (1, 2)
                                    AND a.busua_cod = :busua_cod
                                    AND a.fecha_creacion >= TO_DATE(:fecha_ini,'DD-MM-YYYY HH24:MI:SS')
                                    AND a.fecha_creacion <= TO_DATE(:fecha_fin,'DD-MM-YYYY HH24:MI:SS') ";

         if ($tipoa_cod !== 0) {
            $valores['tipoa_cod'] = $tipoa_cod;
            $sql                 .= " AND a.tipoa_cod = :tipoa_cod ";
         }

         if ($causa_cod !== -1) {
            $valores['causa_cod'] = $causa_cod;
            $sql                 .= " AND a.causa_cod = :causa_cod ";
         }

         if ($derv_cod !== -1) {
            $valores['derv_cod']   = $derv_cod;
            $sql                 .= " AND a.alert_cod = d.alert_cod
                                       AND d.derv_cod = :derv_cod ";
         }

         $sql                    .= " ORDER BY a.fecha_creacion DESC";

         if ($DB->Query($sql, $valores)) {
            $this->alert_cod     = $DB->Value("ALERT_COD");
            $this->busua_cod     = $DB->Value("BUSUA_COD");
            $this->activa        = $DB->Value("ACTIVA");
            $this->tipoa_cod     = $DB->Value("TIPOA_COD");
            $this->tipo_alerta   = $DB->Value("TIPO_ALERTA");
            $this->fecha_creacion= $DB->Value("FECHA_CREACION");
            $this->link          = $DB->Value("LINK");
            $this->fecha_atencion= $DB->Value("FECHA_ATENCION");
            $this->posicion      = $DB->Value("POSICION");
            $this->descripcion   = $DB->Value("DESCRIPCION");
            $this->causa_cod     = $DB->Value("CAUSA_COD");
            $retval              = true;
         } else {
            $DB->Close();
         }
         return $retval;
      }

      // trae siguiente alerta del usuario
      function siguienteBuscaAlertaFiltro(&$DB)
      {
         $retval                 = false;

         if ($DB->Next()) {
            $this->alert_cod     = $DB->Value("ALERT_COD");
            $this->busua_cod     = $DB->Value("BUSUA_COD");
            $this->activa        = $DB->Value("ACTIVA");
            $this->tipoa_cod     = $DB->Value("TIPOA_COD");
            $this->tipo_alerta   = $DB->Value("TIPO_ALERTA");
            $this->fecha_creacion= $DB->Value("FECHA_CREACION");
            $this->link          = $DB->Value("LINK");
            $this->fecha_atencion= $DB->Value("FECHA_ATENCION");
            $this->posicion      = $DB->Value("POSICION");
            $this->descripcion   = $DB->Value("DESCRIPCION");
            $this->causa_cod     = $DB->Value("CAUSA_COD");
            $retval              = true;
         } else {
            $DB->Close();
         }
         return $retval;
      }

      // total alertas por fecha
      function totalAlertaFilter($usua_cod, &$DB)
      {
         $retval                 = true;

         $valores['usua_cod']    = $usua_cod;

         $sql                    = "SELECT COUNT(a.alert_cod) cantidad
                                    FROM BP.BP_ALERTA a,
                                       BP.BP_USUARIO b,
                                       BP.BP_GRUPO c,
                                       BP.BP_DOMINIO d,
                                       FG.FC_GATEWAY e,
                                       FG.FC_USUARIO f
                                    WHERE a.busua_cod = b.busua_cod
                                    AND b.group_cod = c.group_cod
                                    AND c.dom_cod = d.dom_cod
                                    AND d.gate_cod = e.gate_cod
                                    AND e.usua_cod = f.usua_cod
                                    AND b.esta_cod IN (1, 2)
                                    AND f.usua_cod = :usua_cod
                                    ORDER BY COUNT(a.alert_cod) desc";
         
         if ($DB->Query($sql, $valores)) {
            $this->cantidad      = $DB->Value("CANTIDAD");
            $retval              = true;
         }
         $DB->Close();
         return $retval;
      }

      // trae alertas de un usuario
      function buscaUltimo($busua_cod, &$DB)
      {
         $retval                 = false;

         $valores['busua_cod']   = $busua_cod;

         $sql                    = "SELECT a.alert_cod,
                                          a.busua_cod,
                                          a.activa,
                                          a.tipoa_cod,
                                          TO_CHAR(a.fecha_creacion, 'DD-MM-YYYY HH24:MI:SS') fecha_creacion,
                                          a.link,
                                          TO_CHAR(a.fecha_atencion, 'DD-DD-YYYY HH24:MI:SS') fecha_atencion,
                                          a.posicion,
                                          a.descripcion
                                    FROM BP.BP_ALERTA a,
                                          BP.BP_USUARIO b
                                    WHERE a.busua_cod = b.busua_cod
                                    AND b.esta_cod IN (1, 2)
                                    AND a.busua_cod = :busua_cod
                                    ORDER BY a.fecha_creacion DESC
                                    FETCH FIRST 5 ROWS ONLY";

         if ($DB->Query($sql, $valores)) {
            $this->alert_cod     = $DB->Value("ALERT_COD");
            $this->busua_cod     = $DB->Value("BUSUA_COD");
            $this->activa        = $DB->Value("ACTIVA");
            $this->tipoa_cod     = $DB->Value("TIPOA_COD");
            $this->fecha_creacion= $DB->Value("FECHA_CREACION");
            $this->link          = $DB->Value("LINK");
            $this->fecha_atencion= $DB->Value("FECHA_ATENCION");
            $this->posicion      = $DB->Value("POSICION");
            $this->descripcion   = $DB->Value("DESCRIPCION");
            $retval              = true;
         } else {
            $DB->Close();
         }
         return $retval;
      }
      
      // trae siguiente alerta del usuario
      function siguienteUltimo(&$DB)
      {
         $retval                 = false;

         if ($DB->Next()) {
            $this->alert_cod     = $DB->Value("ALERT_COD");
            $this->busua_cod     = $DB->Value("BUSUA_COD");
            $this->activa        = $DB->Value("ACTIVA");
            $this->tipoa_cod     = $DB->Value("TIPOA_COD");
            $this->fecha_creacion= $DB->Value("FECHA_CREACION");
            $this->link          = $DB->Value("LINK");
            $this->fecha_atencion= $DB->Value("FECHA_ATENCION");
            $this->posicion      = $DB->Value("POSICION");
            $this->descripcion   = $DB->Value("DESCRIPCION");
            $retval              = true;
         } else {
            $DB->Close();
         }
         return $retval;
      }
   }
?>