<?
   class BTipoBoton
   {
      var $tipo_cod;
      var $tipo;
      var $visible;

      function Busca($tipo_cod, &$DB)
      {
         $retval              = false;

         $valores['tipo_cod'] = $tipo_cod;

         $sql = "SELECT tipo
                 FROM BP.BP_TIPO_BOTON
                 WHERE tipo_cod = :tipo_cod";
         
         if($DB->Query($sql, $valores))
         {
            $this->tipo_cod   = $tipo_cod;
            $this->tipo       = $DB->Value("TIPO");
            $retval = true;
         }
         $DB->Close();
         return $retval;
      }


      function Primero(&$DB)
      {
         $retval              = false;

         $sql                 = "SELECT a.tipo_cod,
                                        a.tipo,
                                        a.visible
                                 FROM BP.BP_TIPO_BOTON a
                                 WHERE a.visible = 1
                                 ORDER BY a.tipo ASC";

         if($DB->Query($sql))
         {
            $this->tipo_cod   = $DB->Value("TIPO_COD");
            $this->tipo       = $DB->Value("TIPO");
            $this->visible    = $DB->Value("VISIBLE");
            $retval = true;
         } else {
            $DB->Close();
         }
         return $retval;
      }

      
      function Siguiente(&$DB)
      {
         $retval              = false;

         if($DB->Next())
         {
            $this->tipo_cod   = $DB->Value("TIPO_COD");
            $this->tipo       = $DB->Value("TIPO");
            $this->visible    = $DB->Value("VISIBLE");
            $retval = true;
         } else {
            $DB->Close();
         }
         return $retval;
      }
   }