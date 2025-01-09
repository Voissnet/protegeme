<?
   class BHistoriaCambiaClaveOperador
   {
      var $oper_cod;
      var $token;
      var $fecha;
      var $esta_cod;
      var $iv;
      var $uc_crypt;

      // busca operador
      function BuscaValido($oper_cod, $token, $DB)
      {
         $retval              = false;

         $valores['oper_cod'] = $oper_cod;
         $valores['token']    = $token;

         $sql                 = "SELECT a.fecha,
                                       a.esta_cod,
                                       a.iv,
                                       a.uc_crypt
                                 FROM BP.BP_HISTORIA_CAMBIA_CLAVE_OPERADOR a
                                 WHERE a.oper_cod = :oper_cod
                                 AND a.token = :token
                                 AND a.fecha >= SYSDATE - 1
                                 AND a.esta_cod = 1";

         if($DB->Query($sql, $valores))
         {
            $this->oper_cod   = $oper_cod;
            $this->token      = $token;
            $this->fecha      = $DB->Value("FECHA");
            $this->esta_cod   = $DB->Value("ESTA_COD");
            $this->iv         = $DB->Value("IV");
            $this->uc_crypt   = $DB->Value("UC_CRYPT");
            $retval           = true;
         }
         $DB->Close();
         return $retval;
      }

      // genera un token para el cambio de clave de un usuario
      function GeneraTokenCambioClave($oper_cod, &$DB)
      {
         $retval              = false;
         // hash encriptado
         $token               = hash('sha512', 'tok_' . $oper_cod . date('Y-m-d h:i:s') );
         
         // oper_cod encriptado con iv
         $iv                  = openssl_random_pseudo_bytes(16);
         $uc_crypt            = openssl_encrypt($oper_cod, 'aes-256-cbc', 'jdhg567yhjd389kjd45j5j4kmdhnr45k', 0, $iv);

         $valores['oper_cod'] = $oper_cod;
         $valores['token']    = $token;
         $valores['iv']       = str_replace(['+','/','='], ['-','_',''], base64_encode($iv));
         $valores['uc_crypt'] = $uc_crypt;

         $sql                 = "INSERT INTO BP.BP_HISTORIA_CAMBIA_CLAVE_OPERADOR (oper_cod, token, iv, uc_crypt)
                                 VALUES (:oper_cod, :token, :iv, :uc_crypt)";

         if ($DB->Execute($sql, $valores))
         {
            $this->oper_cod   = $valores['oper_cod'];
            $this->token      = $valores['token'];
            $this->esta_cod   = 1;
            $this->iv         = $valores['iv'];
            $this->uc_crypt   = $valores['uc_crypt'];
            $retval           = true;
         }

         return $retval;
      }

      // Modifica el estado del token 
      function DesactivaToken($DB)
      {
         $retval              = false;

         $valores['oper_cod'] = $this->oper_cod;
         $valores['token']    = $this->token;

         $sql                 = "UPDATE BP.BP_HISTORIA_CAMBIA_CLAVE_OPERADOR a
                                 SET a.esta_cod = 0
                                 WHERE a.oper_cod = :oper_cod
                                 AND a.token = :token";

         if($DB->Execute($sql, $valores))
         {
           $this->esta_cod    = '0';
           $retval            = true;
         }
         return $retval;
      }

   }
?>