<?
   class BLog
   {
      var $fd;

      function CreaLogTexto($path)
      {
         $retval = FALSE;
         $this->fd = fopen($path, "a");
         if ($this->fd !== FALSE)
            $retval = TRUE;
         return $retval;
      }



      function RegistraLinea($texto)
      {
         $retval = FALSE;
         $num = fwrite($this->fd, date("Y-m-d H:i:s") . " " . $texto . "\n");
         if ($num !== FALSE)
            $retval = TRUE;
         return $retval;
      }


      function CierraLogTexto($texto = "---------------------------- EOT ---------------------------")
      {
         $retval = FALSE;
         $num = fwrite($this->fd, date("Y-m-d H:i:s") . " " . $texto . "\n");
         if ($num !== FALSE)
            $retval = fclose($this->fd);
         return $retval;
      }

   }