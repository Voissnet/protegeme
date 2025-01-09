<?
   class BTracker
   {

      var $busua_cod;
      var $tipo_cod;
      var $gps_uid;
      var $causa;
      var $esta_cod;
      var $estado;

      // trae el primer registro
      function primero(&$DB)
      {
         $retval                 = false;

         $sql                    = "SELECT a.busua_cod,
                                          a.tipo_cod,
                                          a.gps_uid,
                                          a.causa,
                                          a.esta_cod
                                    FROM BP.BP_TRACKER a";
         
         if($DB->Query($sql))
         {
            $this->busua_cod     = $DB->Value("BUSUA_COD");
            $this->tipo_cod      = $DB->Value("TIPO_COD");
            $this->gps_uid       = $DB->Value("GPS_UID");
            $this->causa         = $DB->Value("CAUSA");
            $this->esta_cod      = $DB->Value("ESTA_COD");
            $retval              = true;
         }
         else
            $DB->Close();
         return $retval;
      }

      // trae el siguiente registro
      function siguiente(&$DB)
      {
         $retval                 = false;

         if($DB->Next())
         {
            $this->busua_cod     = $DB->Value("BUSUA_COD");
            $this->tipo_cod      = $DB->Value("TIPO_COD");
            $this->gps_uid       = $DB->Value("GPS_UID");
            $this->causa         = $DB->Value("CAUSA");
            $this->esta_cod      = $DB->Value("ESTA_COD");
            $retval              = true;
         }
         else
            $DB->Close();
         return $retval;
      }

      // trae registro segun usuario dado
      function busca($busua_cod, &$DB)
      {
         $retval                 = false;

         $valores['busua_cod']   = $busua_cod;

         $sql                    = "SELECT a.busua_cod,
                                          a.tipo_cod,
                                          a.gps_uid,
                                          a.causa,
                                          a.esta_cod,
                                          b.estado
                                    FROM BP.BP_TRACKER a,
                                    BP.BP_ESTADO b
                                    WHERE a.esta_cod = b.esta_cod
                                    AND a.busua_cod = :busua_cod";
      
         if ($DB->Query($sql, $valores))
         {
            $this->busua_cod     = $DB->Value("BUSUA_COD");
            $this->tipo_cod      = $DB->Value("TIPO_COD");
            $this->gps_uid       = $DB->Value("GPS_UID");
            $this->causa         = $DB->Value("CAUSA");
            $this->esta_cod      = $DB->Value("ESTA_COD");
            $this->estado        = $DB->Value("ESTADO");
            $retval              = true;
         }
         $DB->Close();
         return $retval;
      }

      // busca tipo de tracker de un usuario
      function buscaTipo($busua_cod, $tipo_cod, &$DB)
      {
         $retval                 = false;

         $valores['busua_cod']   = $busua_cod;
         $valores['tipo_cod']    = $tipo_cod;

         $sql                    = "SELECT a.busua_cod,
                                          a.tipo_cod,
                                          a.gps_uid,
                                          a.causa,
                                          a.esta_cod,
                                          b.estado
                                    FROM BP.BP_TRACKER a,
                                    BP.BP_ESTADO b
                                    WHERE a.esta_cod = b.esta_cod
                                    AND a.busua_cod = :busua_cod
                                    AND a.tipo_cod = :tipo_cod";

         if ($DB->Query($sql, $valores))
         {
            $this->busua_cod     = $DB->Value("BUSUA_COD");
            $this->tipo_cod      = $DB->Value("TIPO_COD");
            $this->gps_uid       = $DB->Value("GPS_UID");
            $this->causa         = $DB->Value("CAUSA");
            $this->esta_cod      = $DB->Value("ESTA_COD");
            $this->estado        = $DB->Value("ESTADO");
            $retval              = true;
         }
         $DB->Close();
         return $retval;
      }

      // inserta un nuevo registro
      function insert($busua_cod, $tipo_cod, $causa, &$DB)
      {
         $retval                 = false;

         $valores['busua_cod']   = $busua_cod;
         $valores['tipo_cod']    = $tipo_cod;
         $valores['causa']       = $causa;

         $sql                    = "INSERT INTO BP.BP_TRACKER (busua_cod, tipo_cod, causa)
                                    VALUES (:busua_cod, :tipo_cod, :causa)";
         
         if ($DB->Execute($sql, $valores))
         {
            $this->busua_cod        = $busua_cod;
            $this->tipo_cod         = $tipo_cod;
            $this->causa            = $causa;
            $retval                 = true;
         }
         return $retval;
      }

      // actualiza datos del tracker
      function actualiza($tipo_cod, $causa, &$DB)
      {
         $retval                 = false;

         $valores['busua_cod']   = $this->busua_cod;
         $valores['tipo_cod']    = $tipo_cod;
         $valores['causa']       = $causa;

         $sql                    = "UPDATE BP.BP_TRACKER a
                                    SET a.tipo_cod = :tipo_cod,
                                    a.causa = :causa
                                    WHERE a.busua_cod = :busua_cod";
         
         if ($DB->Execute($sql, $valores))
         {
            $this->tipo_cod         = $tipo_cod;
            $this->causa            = $causa;
            $retval                 = true;
         }
         return $retval;
      }

      // actualiza boton
      function actualizaEstado($esta_cod, &$DB)
      {
         $retval                 = true;

         $valores['busua_cod']   = $this->busua_cod;
         $valores['esta_cod']    = $esta_cod;

         $sql                    = "UPDATE BP.BP_TRACKER a
                                    SET a.esta_cod = :esta_cod
                                    WHERE a.busua_cod = :busua_cod";
         
         if ($DB->Execute($sql, $valores))
         {
            $retval                 = true;
         }
         return $retval;
      }

      // actualiza estado de un tipo de tracker de un usuario
      function actualizaEstadoTipo($esta_cod, &$DB)
      {
         $retval                 = true;

         $valores['busua_cod']   = $this->busua_cod;
         $valores['tipo_cod']    = $this->tipo_cod;
         $valores['esta_cod']    = $esta_cod;

         $sql                    = "UPDATE BP.BP_TRACKER a
                                    SET a.esta_cod = :esta_cod
                                    WHERE a.busua_cod = :busua_cod
                                    AND a.tipo_cod = :tipo_cod";
         
         if ($DB->Execute($sql, $valores))
         {
            $retval                 = true;
         }
         return $retval;
      }
      
   }
?>