<?
   class BHistoriaCambiaClave
   {
      var $usua_cod;
      var $token;
      var $fecha;
      var $esta_cod;
      var $iv;
      var $uc_crypt;


      function BuscaValido($usua_cod, $token, $DB)
      {
         $retval = FALSE;

         $valores["usua_cod"] = $usua_cod;
         $valores["token"]    = $token;

         $sql = "SELECT a.fecha,
                        a.esta_cod,
                        a.iv,
                        a.uc_crypt
                 FROM FG.FC_HISTORIA_CAMBIA_CLAVE a
                 WHERE usua_cod = :usua_cod
                       AND token = :token
                       AND fecha >= SYSDATE - 1
                       AND esta_cod = 1";

         if($DB->Query($sql, $valores))
         {
            $this->usua_cod   = $usua_cod;
            $this->token      = $token;
            $this->fecha      = $DB->Value("FECHA");
            $this->esta_cod   = $DB->Value("ESTA_COD");
            $this->iv         = $DB->Value("IV");
            $this->uc_crypt   = $DB->Value("UC_CRYPT");
            $retval           = TRUE;
         }
         $DB->Close();
         return $retval;
      }




      /* genera un token para el cambio de clave de un usuario*/
      function GeneraTokenCambioClave($usua_cod, &$DB)
      {
         $retval = FALSE;
         /* hash encriptado */
         $token = hash('sha512', "tok_" . $usua_cod . date("Y-m-d h:i:s") );
         
         /* usua_cod encriptado con iv */
         $iv       = openssl_random_pseudo_bytes(16);
         $uc_crypt = openssl_encrypt($usua_cod, "aes-256-cbc", "jdhg567yhjd389kjd45j5j4kmdhnr45k", 0, $iv);

         $valores["usua_cod"] = $usua_cod;
         $valores["token"]    = $token;
         $valores["iv"]       = str_replace(['+','/','='], ['-','_',''], base64_encode($iv));
         $valores["uc_crypt"] = $uc_crypt;

         $sql = "INSERT INTO FG.FC_HISTORIA_CAMBIA_CLAVE (usua_cod, token, iv, uc_crypt)
                 VALUES (:usua_cod, :token, :iv, :uc_crypt)";
         if ($DB->Execute($sql, $valores))
         {
            $this->usua_cod   = $valores["usua_cod"];
            $this->token      = $valores["token"];
            $this->esta_cod   = 1;
            $this->iv         = $valores["iv"];
            $this->uc_crypt   = $valores["uc_crypt"];
            $retval = TRUE;
         }

         return $retval;
      }



      /* Modifica el estado del token */
      function DesactivaToken($DB)
      {
         $retval = FALSE;

         $valores["usua_cod"] = $this->usua_cod;
         $valores["token"]    = $this->token;

         $sql = "UPDATE FG.FC_HISTORIA_CAMBIA_CLAVE
                 SET esta_cod = 0
                 WHERE usua_cod = :usua_cod
                       AND token = :token";

         if($DB->Execute($sql, $valores))
         {
           $this->esta_cod = '0';
           $retval = TRUE;
         }
         return $retval;
      }


   }