<?
   require_once 'BConexion.php';

   /* crea objeto conexion*/
   $DB = new BConexion;

   /* Un regitro dado el tipo_cod = 1*/
   $valores1['tipo_cod'] = 1;

   echo "Consulta 1 registro con bind variable tipo_cod = 1<br>";
   $sql = "SELECT tipo_cod,
                  tipo_servicio
           FROM BP.BP_TIPO_SERVICIO
           WHERE tipo_cod = :tipo_cod";

   if($DB->Query($sql, $valores1) === TRUE)
   {
      echo "TIPO_COD=" . $valores1['tipo_cod'] . "<br>";
      echo "TIPO_SERVICIO=" . $DB->Value("TIPO_SERVICIO") . "<br>";
   }
   else
   {
      echo "Error en la consulta<br>";
   }

   echo "<br><br>";
   echo "Consulta toda una tabla sin where<br>";
   $sql = "SELECT tipo_cod,
                  tipo_servicio
           FROM BP.BP_TIPO_SERVICIO";

   $stat = $DB->Query($sql);
   while($stat)
   {
      echo "TIPO_COD=" . $DB->Value("TIPO_COD") . "<br>";
      echo "TIPO_SERVICIO=" . $DB->Value("TIPO_SERVICIO") . "<br><br>";
      $stat = $DB->Next();
   }


   $valores2["dominio"]       = 'miboton2.lanube.cl';
   $valores2["sip_username"]  = 10001;

   $sql2 = "SELECT c.sip_username,
                   c.sip_display_name,
                   c.sip_password
            FROM BP_USUARIO a,
                 BP_DOMINIO b,
                 BP_BOTON c
            WHERE c.sip_username = :sip_username
                  AND b.dominio = :dominio
                  AND a.esta_cod = 1
                  AND c.esta_cod = 1
                  AND a.dom_cod = b.dom_cod
                  AND c.busua_cod = a.busua_cod";

   if($DB->Query($sql2, $valores2) === TRUE)
   {
      echo "sip_username="       . $DB->Value("SIP_USERNAME")     . "<br>";
      echo "sip_display_name="   . $DB->Value("SIP_DISPLAY_NAME") . "<br>";
      echo "sip-password="       . $DB->Value("SIP_PASSWORD")     . "<br>";
   }
   else
   {
      echo "Error en la consulta<br>";
   }

   echo "<br><br><br>";
   echo "Contactos llamadas<br><br>";
   $valores3["dominio"]       = 'miboton1.lanube.cl';
   $valores3["sip_username"]  = 10002;

   $sql3 = "SELECT a.numero
            FROM BP_CONTACTOS_LLAMADA a,
                 BP_USUARIO b,
                 BP_BOTON c,
                 BP_DOMINIO d
            WHERE c.sip_username = :sip_username
                  AND d.dominio = :dominio
                  AND a.esta_cod = 1
                  AND b.esta_cod < 3
                  AND c.esta_cod < 3
                  AND a.busua_cod = b.busua_cod
                  AND b.busua_cod = c.busua_cod
                  AND b.dom_cod = d.dom_cod";

   $stat = $DB->Query($sql3, $valores3);
   echo "Contactos voz para " . $valores3["sip_username"] . " con dominio "  . $valores3["dominio"] . "<br>";
   while($stat)
   {
      echo $DB->Value("NUMERO") . "<br>";
      $stat = $DB->Next();
   }
   echo "<br><br>";

   echo "Contactos SMS<br><br>";
   $valores4["dominio"]       = 'miboton1.lanube.cl';
   $valores4["sip_username"]  = 10002;

   $sql4 = "SELECT a.numero
            FROM BP_CONTACTOS_SMS a,
                 BP_USUARIO b,
                 BP_BOTON c,
                 BP_DOMINIO d
            WHERE c.sip_username = :sip_username
                  AND d.dominio = :dominio
                  AND a.esta_cod = 1
                  AND b.esta_cod < 3
                  AND c.esta_cod < 3
                  AND a.busua_cod = b.busua_cod
                  AND b.busua_cod = c.busua_cod
                  AND b.dom_cod = d.dom_cod";

   $stat = $DB->Query($sql4, $valores4);
   echo "Contactos voz para " . $valores4["sip_username"] . " con dominio "  . $valores4["dominio"] . "<br>";
   while($stat)
   {
      echo $DB->Value("NUMERO") . "<br>";
      $stat = $DB->Next();
   }
   echo "<br><br>";

   $valores5['dominio'] = 'miboton1.lanube.cl';
   $sql5 = "select 
        d.username,
        d.password
	from bp_usuario a,
     		bp_dominio b,
      		fg.fc_gateway c,
     		fg.fc_usuario d
	where a.cloud_username = 'username1'
	and b.dominio = :dominio
	and a.dom_cod = b.dom_cod
	and b.gate_cod = c.gate_cod
	and c.usua_cod = d.usua_cod";

    $stat = $DB->Query($sql5, $valores5);
    echo "API SMS ".$DB->Value("USERNAME"). "<br>";
    echo "API SMS ".$DB->Value("PASSWORD"). "<br>";


   /* cierra cursor*/
   $DB->Close();
   
   /* cierra sesion */
   $DB->Logoff();
