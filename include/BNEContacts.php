<?
   class BNEContacts
   {
      var $numero_voz;
      var $numero_sms;

      function PrimeroNumeroVoz($sip_username, $dominio, &$DB)
      {
         $retval = FALSE;
         $valores["sip_username"]   = $sip_username;
         $valores["dominio"]        = $dominio;

         $sql = "SELECT a.numero
                  FROM  BP.BP_CONTACTOS_LLAMADA a,
                        BP.BP_USUARIO b,
                        BP.BP_BOTON c,
                        BP.BP_GRUPO d,
                        BP.BP_DOMINIO e
                  WHERE a.esta_cod = 1
                        AND b.esta_cod = 1
                        AND c.esta_cod = 1
                        AND c.sip_username = :sip_username
                        AND e.dominio = :dominio
                        AND a.busua_cod = b.busua_cod
                        AND b.busua_cod = c.busua_cod
                        AND b.group_cod = d.group_cod
                        AND d.dom_cod = e.dom_cod";

         if($DB->Query($sql, $valores))
         {
            $this->numero_voz = $DB->Value("NUMERO");
            $retval = TRUE;
         }
         else
            $DB->Close();
         return $retval;
      }


      function SiguienteNumeroVoz(&$DB)
      {
         $retval=FALSE;
         if($DB->Next())
         {
            $this->numero_voz = $DB->Value("NUMERO");
            $retval = TRUE;
         }
         else
            $DB->Close();
         return $retval;
      }


      function PrimeroNumeroSMS($sip_username, $dominio, &$DB)
      {
         $retval = FALSE;
         $valores["sip_username"]   = $sip_username;
         $valores["dominio"]        = $dominio;

         $sql = "SELECT a.numero
                  FROM  BP.BP_CONTACTOS_SMS a,
                        BP.BP_USUARIO b,
                        BP.BP_BOTON c,
                        BP.BP_GRUPO d,
                        BP.BP_DOMINIO e
                  WHERE a.esta_cod = 1
                        AND b.esta_cod = 1
                        AND c.esta_cod = 1
                        AND c.sip_username = :sip_username
                        AND e.dominio = :dominio
                        AND a.busua_cod = b.busua_cod
                        AND b.busua_cod = c.busua_cod
                        AND b.group_cod = d.group_cod
                        AND d.dom_cod = e.dom_cod";

         if($DB->Query($sql, $valores))
         {
            $this->numero_sms = $DB->Value("NUMERO");
            $retval = TRUE;
         }
         else
            $DB->Close();
         return $retval;
      }


      function SiguienteNumeroSMS(&$DB)
      {
         $retval=FALSE;
         if($DB->Next())
         {
            $this->numero_sms = $DB->Value("NUMERO");
            $retval = TRUE;
         }
         else
            $DB->Close();
         return $retval;
      }


   }
