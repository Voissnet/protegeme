<?
   require_once 'BConexion.php';
   require_once 'Parameters.php';
   require_once 'BLog.php';
   require_once 'BBoton.php';
   require_once 'BUsuario.php';
 
   $DB   = new BConexion;
   $Log  = new BLog;
   $path_log = Parameters::PATH . "/log/physical_requires.log";
   $Log->CreaLogTexto($path_log);

   if ( isset($_GET['mac']) === FALSE )
   {
      $Log->CierraLogTexto("ERROR: MAC no viene en URL");
      echo Parameters::ErrorXML("MAC inválida" );
      $DB->Logoff();
      exit;
   }

   $Boton = new BBoton;
   if ($Boton->CompruebaMac(strtoupper($_GET["mac"]), $DB) === FALSE)
   {
      $Log->CierraLogTexto("ERROR: MAC " . $_GET["mac"]. " no puede ser comprobada");
      echo Parameters::ErrorXML("MAC no puede ser comprobada" );
      $DB->Logoff();
      exit;
   }
   $filename = strtoupper($_GET["mac"]) . ".cfg";
   header("Content-Description: File Transfer");
   header("Content-Disposition: attachment; filename=$filename");
   header("Content-Type: text/plain"); 
   header("Content-Transfer-Encoding: binary");
   
   /* URL aprovisionamiento */
   $url = Parameters::WEB_PATH . "/provisioning/prov.php?mac=" . strtoupper($_GET["mac"]);

   echo <<<EOT
   <<VOIP CONFIG FILE>>Version:2.0000000000

   <SIP CONFIG MODULE>
   SIP  Port          :5060
   STUN Server        :
   STUN Port          :3478
   STUN Refresh Time  :50
   SIP Wait Stun Time :800
   Extern NAT Addrs   :
   Reg Fail Interval  :32
   Strict BranchPrefix:0
   Video Mute Attr    :0
   Enable Group Backup:0
   Enable RFC4475     :1
   Strict UA Match    :0
   CSTA Enable        :0
   Notify Reboot      :1
   SMS direct Enabled :0
   SMS Save Enabled   :1
   SMS Ring Enabled   :1
   --SIP Line List--  :
   SIP1 Phone Number       :$Boton->sip_username
   SIP1 Display Name       :$Boton->sip_username
   SIP1 Sip Name           :BPSERVER
   SIP1 Register Addr      :$Boton->dominio
   SIP1 Register Port      :5060
   SIP1 Register User      :$Boton->sip_username
   SIP1 Register Pswd      :$Boton->sip_password
   SIP1 Register TTL       :3600
   SIP1 Need Reg On        :0
   SIP1 Backup Addr        :
   SIP1 Backup Port        :5060
   SIP1 Backup Transport   :0
   SIP1 Backup TTL         :3600
   SIP1 Backup Need Reg On :0
   SIP1 Enable Reg         :0
   SIP1 Backup Mode        :0
   SIP1 Proxy Addr         :
   SIP1 Proxy Port         :5060
   SIP1 Proxy User         :
   SIP1 Proxy Pswd         :
   SIP1 Proxy Need Reg On  :0
   SIP1 BakProxy Addr      :
   SIP1 BakProxy Port      :5060
   SIP1 BakProxy Need Reg On:0
   SIP1 Enable Failback    :1
   SIP1 Failback Interval  :1800
   SIP1 Signal Failback    :0
   SIP1 Signal Retry Counts:3
   SIP1 SigCrypto Key      :
   SIP1 Enable OSRTP       :0
   SIP1 Media Crypto       :0
   SIP1 MedCrypto Key      :
   SIP1 SRTP Auth-Tag      :0
   SIP1 Enable RFC5939     :0
   SIP1 Local Domain       :$Boton->dominio
   SIP1 Always FWD         :0
   SIP1 Busy FWD           :0
   SIP1 No Answer FWD      :0
   SIP1 Always FWD Num     :
   SIP1 Busy FWD Num       :
   SIP1 NoAnswer FWD Num   :
   SIP1 FWD Timer          :5
   SIP1 Hotline Num        :
   SIP1 Enable Hotline     :0
   SIP1 WarmLine Time      :0
   SIP1 Pickup Num         :
   SIP1 Join Num           :
   SIP1 Intercom Num       :
   SIP1 Ring Type          :Default
   SIP1 NAT UDPUpdate      :2
   SIP1 UDPUpdate TTL      :30
   SIP1 UDPUpdate Try Times:3
   SIP1 Server Type        :0
   SIP1 User Agent         :FANA10W-BP
   SIP1 PRACK              :0
   SIP1 Keep AUTH          :0
   SIP1 Session Timer      :0
   SIP1 S Timer Expires    :1800
   SIP1 Enable GRUU        :0
   SIP1 DTMF Mode          :3
   SIP1 DTMF Info Mode     :0
   SIP1 NAT Type           :0
   SIP1 Enable Rport       :1
   SIP1 Subscribe          :0
   SIP1 Sub Expire         :3600
   SIP1 Single Codec       :0
   SIP1 CLIR               :0
   SIP1 Strict Proxy       :1
   SIP1 Direct Contact     :0
   SIP1 History Info       :0
   SIP1 DNS SRV            :0
   SIP1 DNS Mode           :0
   SIP1 XFER Expire        :0
   SIP1 Ban Anonymous      :0
   SIP1 Dial Off Line      :1
   SIP1 Quota Name         :0
   SIP1 Presence Mode      :0
   SIP1 RFC Ver            :1
   SIP1 Phone Port         :0
   SIP1 Signal Port        :5060
   SIP1 Transport          :1
   SIP1 Use SRV Mixer      :0
   SIP1 SRV Mixer Uri      :
   SIP1 Long Contact       :0
   SIP1 Auto TCP           :0
   SIP1 Uri Escaped        :1
   SIP1 Click to Talk      :0
   SIP1 MWI Num            :
   SIP1 CallPark Num       :
   SIP1 Retrieve Num       :
   SIP1 Retrieve Type      :0
   SIP1 MSRPHelp Num       :
   SIP1 User Is Phone      :0
   SIP1 Auto Answer        :0
   SIP1 NoAnswerTime       :0
   SIP1 MissedCallLog      :1
   SIP1 SvcCode Mode       :0
   SIP1 DNDOn SvcCode      :
   SIP1 DNDOff SvcCode     :
   SIP1 CFUOn SvcCode      :
   SIP1 CFUOff SvcCode     :
   SIP1 CFBOn SvcCode      :
   SIP1 CFBOff SvcCode     :
   SIP1 CFNOn SvcCode      :
   SIP1 CFNOff SvcCode     :
   SIP1 ANCOn SvcCode      :
   SIP1 ANCOff SvcCode     :
   SIP1 Send ANOn Code     :
   SIP1 Send ANOffCode     :
   SIP1 CW On Code         :
   SIP1 CW Off Code        :
   SIP1 VoiceCodecMap      :PCMU,PCMA,G729
   SIP1 VideoCodecMap      :
   SIP1 BLFList Uri        :
   SIP1 BLF Server         :
   SIP1 Respond 182        :0
   SIP1 Enable BLFList     :0
   SIP1 Caller Id Type     :4
   SIP1 Keep Higher Caller ID:0
   SIP1 Syn Clock Time     :0
   SIP1 Use VPN            :0
   SIP1 Enable DND         :0
   SIP1 Inactive Hold      :0
   SIP1 Req With Port      :1
   SIP1 Update Reg Expire  :1
   SIP1 Enable SCA         :0
   SIP1 Sub CallPark       :0
   SIP1 Sub CC Status      :0
   SIP1 Feature Sync       :0
   SIP1 Enable XferBack    :0
   SIP1 XferBack Time      :35
   SIP1 Use Tel Call       :0
   SIP1 Enable Preview     :0
   SIP1 Preview Mode       :1
   SIP1 TLS Version        :2
   SIP1 CSTA Number        :
   SIP1 Enable ChgPort     :0
   SIP1 VQ Name            :
   SIP1 VQ Server          :
   SIP1 VQ Server Port     :5060
   SIP1 VQ HTTP Server     :
   SIP1 Flash Mode         :0
   SIP1 Content Type       :
   SIP1 Content Body       :
   SIP1 Unregister On Boot :0
   SIP1 Enable MAC Header  :1
   SIP1 Enable Register MAC:0
   SIP1 Record Start       :Record:on
   SIP1 Record Stop        :Record:off
   SIP1 BLF Dialog Match   :1
   SIP1 Ptime              :0
   SIP1 Enable Deal 180    :1
   SIP1 Keep Single Contact:0
   SIP1 Session Timer T1   :500
   SIP1 Session Timer T2   :4000
   SIP1 Session Timer T4   :5000
   SIP1 Unavailable Mode   :0
   SIP1 TCP Use Retry Timer:0
   SIP1 Call-ID Format     :\$id@\$ip
   SIP1 GB28181 Mode       :0
   SIP1 Proxy Require      :
   SIP1 Block RTP When Alerting:0
   
   
   <DSSKEY CONFIG MODULE>
   Select DsskeyAction:0
   Memory Key to BXfer:3
   FuncKey Page Num   :1
   SideKey Page Num   :1
   DSS Home Page      :0
   DSS Timeout To Home  :90
   Display Parked Info:0
   DSS DIAL Switch Mode :0
   First Call Wait Time :16
   First Num Start Time :360
   First Num End Time   :1080
   DSS Long Press Action:1
   Auto BLF List        :1
   Extern1 Page Belong :0
   Extern2 Page Belong :0
   Extern3 Page Belong :0
   Extern4 Page Belong :0
   Extern5 Page Belong :0
   DSS Extend1 MAC     :
   DSS Extend1 IP      :
   DSS Extend2 MAC     :
   DSS Extend2 IP      :
   DSS Extend3 MAC     :
   DSS Extend3 IP      :
   DSS Extend4 MAC     :
   DSS Extend4 IP      :
   DSS Extend5 MAC     :
   DSS Extend5 IP      :
   --SoftDss Config-- :
   Fkey1 Type               :1
   Fkey1 Value              :$Boton->numero@1/f
   Fkey1 Title              :KEY1
   Fkey1 ICON               :Green
   
   
   <AUTOUPDATE CONFIG MODULE>
   Default Username   :
   Default Password   :
   Input Cfg File Name:
   Device Cfg File Key:
   Common Cfg File Key:
   Download CommonConf:1
   Save Provision Info:0
   Check FailTimes    :1
   Flash Server IP    :$url
   Flash File Name    :
   Flash Protocol     :5
   Flash Mode         :1
   Flash Interval     :1
   update PB Interval :720
   AP Config Priority :0
   --Sip Pnp List--   :
   PNP Enable         :0
   PNP IP             :224.0.1.75
   PNP Port           :5060
   PNP Transport      :0
   PNP Interval       :1
   --Net Option--     :
   DHCP Option        :0
   DHCPv6 Option      :0
   Dhcp Option 120    :0
   Save DHCP Opion    :0
   Dhcp Renew Upgrade :1
   DHCP Option ACS    :0
   
   <<END OF FILE>>
   
   EOT;
   $Log->CierraLogTexto("URL $url solicitada.");
   $DB->Logoff();