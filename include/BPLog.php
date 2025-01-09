<?
   class BPLog
   {
      var $id;
      var $busua_cod;
      var $fecha;
      var $coordenadas;
      var $plataforma;
      var $versionplataforma;
      var $device;

      // trae el primer registro
      function primero(&$DB)
      {
         $retval                       = false;

         $sql                          = "SELECT a.id,
                                                a.busua_cod,
                                                TO_CHAR(a.fecha, 'DD-MM-YYYY HH24:MI:SS') fecha,
                                                a.coordenadas,
                                                a.plataforma,
                                                a.versionplataforma,
                                                a.device
                                          FROM BP.BP_LOG a";

         if ($DB->Query($sql))
         {
            $this->id                  = $DB->Value("ID");
            $this->busua_cod           = $DB->Value("BUSUA_COD");
            $this->fecha               = $DB->Value("FECHA");
            $this->coordenadas         = $DB->Value("COORDENADAS");
            $this->plataforma          = $DB->Value("PLATAFORMA");
            $this->versionplataforma   = $DB->Value("VERSIONPLATAFORMA");
            $this->device              = $DB->Value("DEVICE");
            $retval                    = true;
         }
         else
            $DB->Close();
         return $retval;
      }

      // trae los ultimos 5 registro segun la fecha
      function buscaUltimo($busua_cod, &$DB)
      {
         $retval                       = false;

         $valores['busua_cod']         = $busua_cod;

         $sql                          = "SELECT a.id,
                                                a.busua_cod,
                                                TO_CHAR(a.fecha, 'DD-MM-YYYY HH24:MI:SS') fecha,
                                                a.coordenadas,
                                                a.plataforma,
                                                a.versionplataforma,
                                                a.device
                                          FROM BP.BP_LOG a
                                          WHERE a.busua_cod = :busua_cod
                                          ORDER BY a.fecha DESC
                                          FETCH FIRST 5 ROWS ONLY";

         if ($DB->Query($sql, $valores))
         {
            $this->id                  = $DB->Value("ID");
            $this->busua_cod           = $DB->Value("BUSUA_COD");
            $this->fecha               = $DB->Value("FECHA");
            $this->coordenadas         = $DB->Value("COORDENADAS");
            $this->plataforma          = $DB->Value("PLATAFORMA");
            $this->versionplataforma   = $DB->Value("VERSIONPLATAFORMA");
            $this->device              = $DB->Value("DEVICE");
            $retval                    = true;
         }
         else
            $DB->Close();
         return $retval;
      }

      // trae el siguiente registro
      function siguiente(&$DB)
      {
         $retval                       = false;

         if ($DB->Next())
         {
            $this->id                  = $DB->Value("ID");
            $this->busua_cod           = $DB->Value("BUSUA_COD");
            $this->fecha               = $DB->Value("FECHA");
            $this->coordenadas         = $DB->Value("COORDENADAS");
            $this->plataforma          = $DB->Value("PLATAFORMA");
            $this->versionplataforma   = $DB->Value("VERSIONPLATAFORMA");
            $this->device              = $DB->Value("DEVICE");
            $retval                    = true;
         }
         else
            $DB->Close();
         return $retval;
      }

      // inserta un nuevo registro
      function inserta($busua_cod, $coordenadas, $plataforma, $versionplataforma, $device, $appbuild, &$DB)
      {
         $retval                       = false;

         $valores['busua_cod']         = $busua_cod;
         $valores['coordenadas']       = $coordenadas;
         $valores['plataforma']        = $plataforma;
         $valores['versionplataforma'] = $versionplataforma;
         $valores['device']            = $device;
         $valores['appbuild']          = $appbuild;

         $sql                          = "INSERT INTO BP.BP_LOG (busua_cod, fecha, coordenadas, plataforma, versionplataforma, device, appbuild)
                                          VALUES (:busua_cod, SYSDATE, :coordenadas, :plataforma, :versionplataforma, :device, :appbuild)
                                          RETURNING id";

         if( ( $this->id = $DB->ExecuteReturning($sql, $valores)) !== false) {
            $retval                    = true;
         }
         return $retval;
      }

   }

?>