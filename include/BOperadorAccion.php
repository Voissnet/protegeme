<?
   class BOperadorAccion
   {
      var $oper_cod;
      var $acci_cod;
      var $nivel;
      var $accion;

      // trae permisos de un operador
      function busca($oper_cod, &$DB)
      {
         $retval              = false;

         $valores['oper_cod'] = $oper_cod;

         $sql                 = "SELECT a.oper_cod,
                                       a.acci_cod,
                                       b.accion,
                                       a.nivel
                                 FROM BP.BP_OPERADOR_ACCION a,
                                 BP.BP_ACCION b
                                 WHERE a.acci_cod = b.acci_cod
                                 AND a.oper_cod = :oper_cod";

         if ($DB->Query($sql, $valores)) 
         {
            $this->oper_cod   = $DB->Value("OPER_COD");
            $this->acci_cod   = $DB->Value("ACCI_COD");
            $this->accion     = $DB->Value("ACCION");
            $this->nivel      = $DB->Value("NIVEL");
            $retval           = true;
         } else {
            $DB->Close();
         }
         return $retval;
      }

      // trae siguientes datos
      function siguiente(&$DB)
      {
         $retval              = false;

         if ($DB->Next())
         {
            $this->oper_cod   = $DB->Value("OPER_COD");
            $this->acci_cod   = $DB->Value("ACCI_COD");
            $this->accion     = $DB->Value("ACCION");
            $this->nivel      = $DB->Value("NIVEL");
            $retval           = true;
         } else {
            $DB->Close();
         }
         return $retval;
      }

      // busca permiso
      function buscaP($oper_cod, $acci_cod, &$DB)
      {
         $retval              = false;

         $valores['oper_cod'] = $oper_cod;
         $valores['acci_cod'] = $acci_cod;

         $sql                 = "SELECT a.oper_cod,
                                       a.acci_cod,
                                       a.nivel
                                 FROM BP.BP_OPERADOR_ACCION a
                                 WHERE a.oper_cod = :oper_cod
                                 AND a.acci_cod = :acci_cod";

         if($DB->Query($sql, $valores))
         {
            $this->oper_cod   = $DB->Value("OPER_COD");
            $this->acci_cod   = $DB->Value("ACCI_COD");
            $this->nivel      = $DB->Value("NIVEL");
            $retval           = true;
         }
         $DB->Close();
         return $retval;
      }

      // actualiza un permiso
      function actualiza($oper_cod, $acci_cod, $nivel, &$DB)
      {
         $retval              = false;

         $valores['oper_cod'] = $oper_cod;
         $valores['acci_cod'] = $acci_cod;
         $valores['nivel']    = $nivel;

         $sql                 = "UPDATE BP.BP_OPERADOR_ACCION a
                                 SET a.nivel = :nivel
                                 WHERE a.oper_cod = :oper_cod
                                 AND a.acci_cod = :acci_cod";

         if($DB->Execute($sql, $valores))
         {
            $retval           = true;
         }
         return $retval;
      }

      // crea un nuevo registro
      function insert($oper_cod, $acci_cod, &$DB)
      {
         $retval                 = false;

         $valores['oper_cod']    = $oper_cod;
         $valores['acci_cod']    = $acci_cod;

         $sql                    = "INSERT INTO BP.BP_OPERADOR_ACCION (oper_cod, acci_cod, nivel)
                                    VALUES (:oper_cod, :acci_cod, 0)";

         if($DB->Execute($sql, $valores))
         {
            $retval                 = true;
         }
         return $retval;
      }
   }
?>