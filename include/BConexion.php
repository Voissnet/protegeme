<?
   /* conexion normal a desarrollo o estando este archivo en produccion queda pooled*/
   define("DBUSER","BP@FONO");
   define("DBPASS","BP");

   /* conexion a produccion desde desarrollo. Solo para consultas contra produccion. En produccion debe estar comentado  */
   //define("DBUSER" ,"WEB@FONO12C_PHP");
   //define("DBPASS" ,"WEB");

   define ("CHARSET","UTF8");

   class BConexion
   {
      private $conexion;
      private $stmt;
      private $campos;
      private $modo;


      function __construct($usuario = DBUSER, $password = DBPASS, $autoCommit = TRUE)
      {
         $datos = explode("@",$usuario);
         $this->conexion = oci_pconnect($datos[0], $password, $datos[1], CHARSET);
         if ($this->conexion)
         {
            if ($autoCommit)
               $this->modo = OCI_COMMIT_ON_SUCCESS;
            else
               $this->modo = OCI_DEFAULT;
	    oci_set_module_name($this->conexion, mb_substr($_SERVER["REQUEST_URI"],0,48));
            oci_set_client_info($this->conexion, mb_substr($_SERVER["REMOTE_ADDR"],0,64));
         }
      }



      function Init()
      {
         $this->campos = array();
      }


      function Query($consulta, &$valores = array())
      {
         $retval = FALSE;
         if($this->stmt = oci_parse($this->conexion, $consulta))
         {
            reset($valores);
            /*while( list($key,$value) = each($valores) )
            {
               oci_bind_by_name($this->stmt, $key, $valores[$key], strlen($valores[$key]));
            }*/
            foreach ($valores as $key => $value)
            {
               oci_bind_by_name($this->stmt, $key, $valores[$key], strlen($valores[$key]));
            }
            if(oci_execute($this->stmt, $this->modo))
               if ( ($this->campos = oci_fetch_array($this->stmt, ( OCI_ASSOC + OCI_RETURN_NULLS )) ) !== FALSE )
                  $retval = TRUE;
         }
         return $retval;
      }


      function Next()
      {
         $retval=FALSE;
         if ( ($this->campos = oci_fetch_array($this->stmt, ( OCI_ASSOC + OCI_RETURN_NULLS )) ) !== FALSE )
               $retval = TRUE;
         return $retval;
      }


      function Value($nombre)
      {
         return $this->campos[strtoupper($nombre)];
      }


      function Execute($instruccion, &$valores = array())
      {
         $retval=FALSE;
         if ($stmt=oci_parse($this->conexion, $instruccion))
         {
            reset($valores);
            foreach ($valores as $key => $value)
            {
               oci_bind_by_name($stmt, $key, $valores[$key], strlen($valores[$key]));
            }
            $retval=oci_execute($stmt, $this->modo);
            oci_free_statement($stmt);
         }
         return $retval;
      }


      function Close()
      {
         if ( $retval = oci_free_statement($this->stmt) )
            $this->stmt = NULL;
         return $retval;
      }


      function AutoCommit()
      {
         $this->modo = OCI_COMMIT_ON_SUCCESS;
         return TRUE;
      }


      function Logoff()
      {
         if ($this->stmt)
         {
            return oci_close($this->conexion);
         }
         else
            return FALSE;
      }


      function BeginTrans()
      {
         $this->modo = OCI_DEFAULT;
         return TRUE;
      }


      function Commit()
      {
         if(oci_commit($this->conexion)) return $this->AutoCommit();
            return FALSE;
      }


      function Rollback()
      {
         if(oci_rollback($this->conexion)) return $this->AutoCommit();
            return FALSE;
      }


      function PrintRecord()
      {
         print_r($this->campos);
      }


      function GetSequence($nombre = "SEQ_ENTITY")
      {
         $getSequenceValue=FALSE;
         $query="SELECT $nombre.NEXTVAL VALOR FROM DUAL";
         if ($stmt=oci_parse($this->conexion,$query))
         {
            if(oci_execute($stmt, $this->modo))
               if(oci_fetch($stmt))
                  $getSequenceValue = oci_result($stmt, "VALOR");
            oci_free_statement($stmt);
         }
         return $getSequenceValue;
      }


      function ExecuteReturning($query, &$valores = array())
      {
         $executeReturningValue = FALSE;

         $query = $query . " into :cod";
         if ($stmt = oci_parse($this->conexion,$query))
         {
            if (oci_bind_by_name($stmt, ":cod", $executeReturningValue, 30))
            {
               reset($valores);
               /*while( list($key,$value) = each($valores) )
               {
                  oci_bind_by_name($stmt, $key, $valores[$key], strlen($valores[$key]));
               }*/
               foreach ($valores as $key => $value)
               {
                  oci_bind_by_name($stmt, $key, $valores[$key], strlen($valores[$key]));
               }
               oci_execute($stmt, $this->modo);
            }
            oci_free_statement($stmt);
         }
         return $executeReturningValue;
      }


      function __destruct()
      {
         if ( $this->stmt ) $this->Close();
         if ( $this->conexion ) $this->Logoff();
      }

   }

