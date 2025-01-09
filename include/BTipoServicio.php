<?
   class BTipoServicio
   {

      var $tipo_cod;
      var $tipo_servicio;

      // trae el primer registro
      function primero(&$DB)
      {
         $retval                 = false;

         $sql                    = "SELECT a.tipo_cod,
                                          a.tipo_servicio
                                    FROM BP.BP_TIPO_SERVICIO a";

         if($DB->Query($sql))
         {
            $this->tipo_cod      = $DB->Value("TIPO_COD");
            $this->tipo_servicio = $DB->Value("TIPO_SERVICIO");
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
            $this->tipo_cod      = $DB->Value("TIPO_COD");
            $this->tipo_servicio = $DB->Value("TIPO_SERVICIO");
            $retval              = true;
         }
         else
            $DB->Close();
         return $retval;
      }

      // busca tipo segun cod dado
      function busca($tipo_cod, &$DB)
      {
         $retval                 = false;

         $valores['tipo_cod']    = $tipo_cod;

         $sql                    = "SELECT a.tipo_cod,
                                          a.tipo_servicio
                                    FROM BP.BP_TIPO_SERVICIO a
                                    WHERE a.tipo_cod = :tipo_cod";
         
         if ($DB->Query($sql, $valores))
         {
            $this->tipo_cod      = $DB->Value("TIPO_COD");
            $this->tipo_servicio = $DB->Value("TIPO_SERVICIO");
            $retval              = true;
         }
         $DB->Close();
         return $retval;
      }

   }
?>