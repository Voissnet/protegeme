<?
   class BOperadorLog
   {
      var $log_cod;
      var $fecha;
      var $oper_cod;
      var $descripcion;
      var $url;

      // inserta un registro
      function inserta($oper_cod, $descripcion, $url, &$DB)
      {
         $retval                 = false;

         $valores['oper_cod']    = $oper_cod;
         $valores['descripcion'] = $descripcion;
         $valores['url']         = $url;

         $sql                    = "INSERT INTO BP.BP_OPERADOR_LOG (fecha, oper_cod, descripcion, url)
                                    VALUES (SYSDATE, :oper_cod, :descripcion, :url)
                                    RETURNING log_cod";

         if($DB->ExecuteReturning($sql, $valores))
         {
            $retval              = true;
         }
         return $retval;
      }
   }
?>