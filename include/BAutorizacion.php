<?
   class BAutorizacion
   {
      var $sip_username;
      var $sip_password;
      var $sip_display_name;
      var $coordenadas;
      var $busua_cod;
      var $cloud_username;
      var $dominio;
      var $username;
      var $password;
      var $nombre;
      var $gate_cod;
      var $numeros;

      function Autoriza($sip_username, $dominio, &$DB)
      {
         $retval = FALSE;
         $valores["sip_username"] = $sip_username;
         $valores["dominio"] = $dominio;

         $sql = "SELECT a.sip_username,
                        a.sip_password,
                        a.sip_display_name,
                        a.coordenadas,
                        b.busua_cod,
                        b.cloud_username,
                        b.nombre,
                        c.numeros,
                        d.dominio,
                        d.gate_cod
                  FROM  BP.BP_BOTON a,
                        BP.BP_USUARIO b,
                        BP.BP_GRUPO c,
                        BP.BP_DOMINIO d
                  WHERE a.sip_username = :sip_username
                        AND d.dominio = :dominio
                        AND a.esta_cod = 1
                        AND b.esta_cod = 1
                        AND a.busua_cod = b.busua_cod
                        AND b.group_cod = c.group_cod
                        AND c.dom_cod = d.dom_cod";
         
         if($DB->Query($sql, $valores) === TRUE)
         {
            $this->sip_username     = $DB->Value("SIP_USERNAME");
            $this->sip_password     = $DB->Value("SIP_PASSWORD");
            $this->sip_display_name = $DB->Value("SIP_DISPLAY_NAME");
            $this->coordenadas      = $DB->Value("COORDENADAS");
            $this->busua_cod        = $DB->Value("BUSUA_COD");
            $this->cloud_username   = $DB->Value("CLOUD_USERNAME");
            $this->nombre           = $DB->Value("NOMBRE");
            $this->numeros          = $DB->Value("NUMEROS");
            $this->dominio          = $DB->Value("DOMINIO");
            $this->gate_cod         = $DB->Value("GATE_COD");
            $retval = TRUE;
         }
         $DB->Close();
         return $retval;
      }


      function ObtieneCredencialesRV($cloud_username, $dominio, &$DB)
      {
         $retval = FALSE;
         $valores["cloud_username"] = $cloud_username;
         $valores["dominio"]        = $dominio;

         $sql = "SELECT a.username,
                        a.password,
                        e.busua_cod,
                        e.nombre,
                        c.gate_cod,
                        d.numeros
                  FROM  FG.FC_USUARIO a,
                        FG.FC_GATEWAY b,
                        BP.BP_DOMINIO c,
                        BP.BP_GRUPO d,
                        BP.BP_USUARIO e
                  WHERE  a.esta_cod = 1
                        AND b.admin_esta_cod = 1
                        AND b.user_esta_cod = 1
                        AND e.esta_cod = 1
                        AND e.cloud_username = :cloud_username
                        AND c.dominio = :dominio
                        AND b.usua_cod = a.usua_cod
                        AND c.gate_cod = b.gate_cod
                        AND c.dom_cod = d.dom_cod
                        AND d.group_cod = e.group_cod";

         if($DB->Query($sql, $valores) === TRUE)
         {
            $this->username   = $DB->Value("USERNAME");
            $this->password   = $DB->Value("PASSWORD");
            $this->busua_cod  = $DB->Value("BUSUA_COD");
            $this->nombre     = $DB->Value("NOMBRE");
            $this->gate_cod   = $DB->Value("GATE_COD");
            $this->numeros    = $DB->Value("NUMEROS");

            $retval = TRUE;
         }
         $DB->Close();
         return $retval;
      }

      function GetXML_OK()
      {
         $hashpassword = md5($this->sip_username . ":" . $this->dominio . ":" . $this->sip_password);

         require_once 'BContactosSMS.php';
         require_once 'BContactosLlamada.php';
         require_once 'BConexion.php';

         $DB2 = new BConexion;
         $ContactosSMS = new BContactosSMS;
         $ContactosLlamada = new BContactosLlamada;
         $stringContactosSMS = "";
         $stringContactosLlamada = "";

         $stat = $ContactosSMS->primero($this->busua_cod, $DB2 );
         while ($stat)
         {
            $stringContactosSMS = $stringContactosSMS . $ContactosSMS->numero . ";";
            $stat = $ContactosSMS->siguiente($DB2);
         }

         $stat = $ContactosLlamada->primero($this->busua_cod, $DB2 );
         while ($stat)
         {
            if ($ContactosLlamada->escucha == '1')
               $escucha = "e-";
            else
               $escucha = "";
            $stringContactosLlamada = $stringContactosLlamada . $escucha . $ContactosLlamada->numero . ";";
            $stat = $ContactosLlamada->siguiente($DB2);
         }

         $DB2->Logoff();

         $retval = '
         <document type="freeswitch/xml">
            <section name="directory">
               <domain name="' . $this->dominio . '">
                  <params>
                     <param name="dial-string" value="{^^:sip_invite_domain=${dialed_domain}:presence_id=${dialed_user}@${dialed_domain}}${sofia_contact(*/${dialed_user}@${dialed_domain})},${verto_contact(${dialed_user}@${dialed_domain})}"/>
                  </params>
                  <groups>
                     <group name="default">
                        <users>
                           <user id="' . $this->sip_username . '">
                              <params>
                                 <param name="a1-hash" value="'. $hashpassword . '"/>
                              </params>
                              <variables>
                                 <variable name="cloud_username" value="' . $this->cloud_username . '"/>
                                 <variable name="accountcode" value="' . $this->sip_username . '"/>
                                 <variable name="smsusername" value="' . $this->username . '"/>
                                 <variable name="smspassword" value="' . $this->password . '"/>
                                 <variable name="nombre" value="' . $this->nombre . '"/>
                                 <variable name="contactossms" value="' . $stringContactosSMS . '"/>
                                 <variable name="contactosec" value="' . $stringContactosLlamada . '"/>
                                 <variable name="user_context" value="public"/>
                                 <variable name="effective_caller_id_name" value="' . $this->sip_display_name . '"/>
                                 <variable name="effective_caller_id_number" value="' . $this->sip_username . '"/>
                                 <variable name="outbound_caller_id_name" value="' . $this->sip_display_name . '"/>
                                 <variable name="gate_cod" value="'. $this->gate_cod . '"/>
                                 <variable name="numero_destino" value="'. $this->numeros . '"/>
                                 <variable name="coordenadas" value="'. $this->coordenadas . '"/>
                                 <variable name="outbound_caller_id_number" value="' . $this->sip_username . '"/>
                                 <variable name="sip-force-contact" value="NDLB-connectile-dysfunction"/>
                              </variables>
                           </user>
                        </users>
                     </group>
                  </groups>
               </domain>
            </section>
         </document>';
         return $retval;
      }

      function GetXML_FAIL()
      {
         $retval = '<document type="freeswitch/xml">
                       <section name="result">
                          <result status="not found" />
                       </section>
                    </document>';
         return $retval;
      }
   }
