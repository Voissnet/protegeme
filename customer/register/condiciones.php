<?
require_once 'Parameters.php';
$v = rand();
?>
<!DOCTYPE html>
<html lang="es">
<header>

   <!-- Required meta tags -->
   <meta http-equiv="Pragma" content="no-cache">
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">

   <!-- title -->
   <title>Condiciones · Prot&eacute;geme</title>
   <meta name="title" content="Register Protegeme">

   <!-- Bootstrap CSS -->
   <link rel="stylesheet" href="<?= Parameters::WEB_PATH ?>/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

   <!-- STYLES css -->
   <link rel="stylesheet" href="<?= Parameters::WEB_PATH ?>/css/default.css?v<?= $v ?>">
   <link rel="stylesheet" href="<?= Parameters::WEB_PATH ?>/css/stylesRegister.css?v<?= $v ?>">

   <style>
      .sangria {
         padding-left: 30px;
      }
   </style>

   <style type="text/css">
      @page {
         size: 8.5in 11in;
         margin-left: 1.18in;
         margin-right: 0.88in;
         margin-top: 0.49in;
         margin-bottom: 0.16in
      }

      p {
         margin-bottom: 0in;
         direction: ltr;
         line-height: 100%;
         text-align: justify;
         orphans: 2;
         widows: 2;
         background: transparent
      }

      p.western {
         font-family: "Times New Roman", serif;
         font-size: 10pt;
         so-language: es-CL
      }

      p.cjk {
         font-family: "Times New Roman";
         font-size: 10pt;
         so-language: es-ES
      }

      p.ctl {
         font-family: "Times New Roman";
         font-size: 10pt
      }

      h1 {
         margin-top: 0in;
         margin-bottom: 0in;
         direction: ltr;
         line-height: 100%;
         text-align: right;
         orphans: 2;
         widows: 2;
         background: transparent;
         page-break-after: avoid
      }

      h1.western {
         font-family: "Times New Roman", serif;
         font-size: 12pt;
         so-language: es-CL;
         font-weight: bold
      }

      h1.cjk {
         font-family: "Times New Roman";
         font-size: 12pt;
         so-language: es-ES;
         font-weight: bold
      }

      h1.ctl {
         font-family: "Times New Roman";
         font-size: 10pt
      }

      h2 {
         margin-top: 0in;
         margin-bottom: 0in;
         direction: ltr;
         line-height: 100%;
         text-align: left;
         orphans: 2;
         widows: 2;
         background: transparent;
         page-break-after: avoid
      }

      h2.western {
         font-family: "Arial", serif;
         font-size: 10pt;
         so-language: es-ES-u-co-trad;
         font-weight: bold
      }

      h2.cjk {
         font-family: "Times New Roman";
         font-size: 10pt;
         so-language: es-ES;
         font-weight: bold
      }

      h2.ctl {
         font-family: "Arial";
         font-size: 12pt;
         font-weight: bold
      }

      a:link {
         color: #0000ff;
         text-decoration: underline
      }

      a:visited {
         color: #800080;
         text-decoration: underline
      }
   </style>

</header>

<body>
   <section id="condition-client">
      <div class="container-lg">
         <div class="row logo">
            <div class="col-12 d-flex justify-content-center">
               <img src="<?= Parameters::WEB_PATH ?>/img/logo-protegeme.webp" width="150" height="120">
            </div>
         </div>
         <div class="row justify-content-center">
            <div id="div-form-register" class="col-12 border w-100 py-3 mb-3">
               <div title="header">
                  <p lang="es-ES" class="western" align="center" style="line-height: 115%; page-break-before: always">
                     <font size="2" style="font-size: 11pt"><b>CONTRATO DE PRESTACIÓN DE
                           SERVICIOS</b></font>
                  </p>
                  <h1 class="western" align="justify" style="margin-top: 0.17in; line-height: 115%">
                     <font size="2" style="font-size: 11pt"><span style="font-weight: normal">Entre
                        </span></font>
                     <font size="2" style="font-size: 11pt">VOISSNET CLOUD
                        SERVICES SpA (“VCS”)</font>
                     <font size="2" style="font-size: 11pt"><span style="font-weight: normal">,
                        </span></font>
                     <font size="2" style="font-size: 11pt"><span style="font-weight: normal">RUT
                           N° 76.768-946-2, domiciliada en calle Padre Mariano 82, Oficina 401,
                           comuna de Providencia, por una parte, y por la otra, </span></font>
                     <font size="2" style="font-size: 11pt"><span lang="es-ES"><span style="font-weight: normal">el
                              “</span></span></font>
                     <font size="2" style="font-size: 11pt"><span lang="es-ES">Cliente</span></font>
                     <font size="2" style="font-size: 11pt"><span lang="es-ES"><span style="font-weight: normal">”,
                              ya individualizado, se ha convenido el siguiente contrato</span></span></font>
                     <font size="2" style="font-size: 11pt"><span style="font-weight: normal">
                           de prestación de servicios (el “</span></font>
                     <font size="2" style="font-size: 11pt">Contrato</font>
                     <font size="2" style="font-size: 11pt"><span style="font-weight: normal">”).</span></font>
                  </h1>
                  <p class="western" style="margin-top: 0.17in; line-height: 115%">
                     <font size="2" style="font-size: 11pt"><u><b>PRIMERO</b></u></font>
                     <font size="2" style="font-size: 11pt"><b>:
                           DEFINICIONES. </b></font>
                  </p>
                  <p class="western" style="line-height: 115%">
                     <font size="2" style="font-size: 11pt">Para
                        los efectos del Contrato, los términos que a continuación se
                        definen tendrán el significado que en cada caso se expresa:</font>
                  </p>
                  <p lang="es-ES" align="justify" style="line-height: 115%">“<font size="2" style="font-size: 11pt"><span lang="es-CL"><b>Anexo(s)</b></span></font>
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">”:
                           Significa el o los documentos mencionados como tales en el texto del
                           presente instrumento, firmados o inicializados por las partes, que
                           forman parte integrante del mismo.</span></font>
                  </p>
                  <p lang="es-ES" align="justify" style="line-height: 115%">“<font size="2" style="font-size: 11pt"><b>Aplicación</b></font>
                     <font size="2" style="font-size: 11pt">”:
                        significa la aplicación para smartphones disponible para descarga en
                        App Store y Google Play consistente en un botón de emergencias,
                        diseñada por, y de propiedad de VCS, denominada “Protégeme” que
                        permite a los Usuarios conectarse a la Plataforma. </font>
                     <font size="2" style="font-size: 11pt">El
                        detalle y condiciones específicas del funcionamiento de Protégeme
                        se indican en el Anexo 1. </font>
                  </p>
                  <p lang="es-ES" align="justify" style="line-height: 115%">
                     <font size="2" style="font-size: 11pt"><b>&quot;Dispositivo(s)</b></font>
                     <font size="2" style="font-size: 11pt">”:
                        cualquier dispositivo portátil o fijo, tales como
                        intercomunicadores, equipos de alarma, botones físicos, etc.,
                        definido con anterioridad por VCS, que permita al Usuario acceder a
                        la Plataforma, y a utilizar los servicios de comunicación de alertas
                        provisto a través de ella. </font>
                  </p>
                  <p lang="es-ES" align="justify" style="line-height: 115%">“<font size="2" style="font-size: 11pt"><b>Organización</b></font>
                     <font size="2" style="font-size: 11pt">”:
                        ente comunitario que contrata los Servicios de VCS a efectos de
                        ponerlo a disposición de los miembros de su comunidad. </font>
                  </p>
                  <p lang="es-ES" align="justify" style="line-height: 115%">“<font size="2" style="font-size: 11pt"><b>Plataforma</b></font>
                     <font size="2" style="font-size: 11pt">”:
                        hardware y software en la nube, al que se puede acceder a través de
                        la Aplicación y/o el Dispositivo, que proporciona la infraestructura
                        tecnológica necesaria para realizar las comunicaciones de emergencia
                        y gestión de usuarios, reportería, consola de incidencias, entre
                        otros. </font>
                  </p>
                  <p lang="es-ES" align="justify" style="line-height: 115%">“<font size="2" style="font-size: 11pt"><b>Servicios</b></font>
                     <font size="2" style="font-size: 11pt">”:
                        corresponde a los servicios que proporcionará VCS con motivo de este
                        Contrato, consistentes en: (i) el diseño, customización y
                        especificaciones de la Aplicación conforme a los requerimientos del
                        Cliente.; (ii) la habilitación, mantenimiento y disposición de la
                        Plataforma; (iii) la capacitación del Cliente en el uso de la
                        Plataforma para la habilitación de Usuarios; (iv) el otorgamiento de
                        las claves de acceso para los Usuarios, el registro de la base de
                        datos; (v) permitir las comunicaciones que se originen por la
                        activación del botón de emergencia de la Aplicación y/o de los
                        Dispositivos, ya sea por mensajería de texto o voz, ya sea a través
                        de la red de telefonía, de voz sobre IP, otra red de datos privada,
                        o a través de la infraestructura de VCS para intercambiar
                        comunicaciones de datos; y (vi) soporte y mantención de la
                        Plataforma a efectos de mantener la continuidad operativa de ésta.
                        El detalle y condiciones específicas del funcionamiento de Protégeme
                        se indican en el Anexo Nº1.</font>
                  </p>
                  <p lang="es-ES" align="justify" style="line-height: 115%">“<font size="2" style="font-size: 11pt"><b>Usuario</b></font>
                     <font size="2" style="font-size: 11pt">”:
                        persona natural a quien el Cliente le ha entregado un usuario y clave
                        de acceso, o respecto de quien ha instruido a VCS para habilitar el
                        Dispositivo, para acceder a la Plataforma, a través de la Aplicación
                        y/o Dispositivo, y así estar habilitado para activar el botón de
                        emergencia de dicha Aplicación y/o Dispositivos. </font>
                  </p>
                  <p class="western" style="margin-top: 0.17in; line-height: 115%">
                     <font size="2" style="font-size: 11pt"><u><b>SEGUNDO</b></u></font>
                     <font size="2" style="font-size: 11pt"><b>:
                           ANTECEDENTES</b></font>
                  </p>
                  <h2 lang="es-ES" class="western" align="justify" style="font-weight: normal; line-height: 115%">
                     <br />

                  </h2>
                  <ol>
                     <ol>
                        <li>
                           <h2 lang="es-ES-u-co-trad" class="western" align="justify" style="line-height: 115%">
                              <font color="#000000">
                                 <font face="Times New Roman, serif">
                                    <font size="2" style="font-size: 11pt"><span lang="es-ES"><span style="font-weight: normal">[</span></span></font>
                                 </font>
                              </font>
                              <font color="#000000">
                                 <font face="Times New Roman, serif">
                                    <font size="2" style="font-size: 11pt"><span style="font-weight: normal">Resumen
                                          del Cliente</span></font>
                                 </font>
                              </font>
                              <font color="#000000">
                                 <font face="Times New Roman, serif">
                                    <font size="2" style="font-size: 11pt"><span lang="es-ES"><span style="font-weight: normal">]</span></span></font>
                                 </font>
                              </font>
                           </h2>
                     </ol>
                  </ol>
                  <h2 lang="es-ES-u-co-trad" class="western" align="justify" style="font-weight: normal; line-height: 115%">
                     <br />

                  </h2>
                  <ol>
                     <ol start="2">
                        <li>
                           <h2 lang="es-ES-u-co-trad" class="western" align="justify" style="line-height: 115%">
                              <font color="#000000">
                                 <font face="Times New Roman, serif">
                                    <font size="2" style="font-size: 11pt"><span lang="es-ES"><span style="font-weight: normal">VCS
                                             es una empresa dedicada al rubro de la tecnología de las
                                             telecomunicaciones e información, que presta a sus clientes
                                             servicios de comunicaciones a través de internet y centrales
                                             virtuales.</span></span></font>
                                 </font>
                              </font>
                           </h2>
                     </ol>
                  </ol>
                  <p lang="es-ES" class="western" align="justify"><br />

                  </p>
                  <ol>
                     <ol start="3">
                        <li>
                           <h2 lang="es-ES-u-co-trad" class="western" align="justify" style="line-height: 115%">
                              <font color="#000000">
                                 <font face="Times New Roman, serif">
                                    <font size="2" style="font-size: 11pt"><span lang="es-ES"><span style="font-weight: normal">VCS
                                             ha desarrollado la Plataforma con el objeto de proporcionar una
                                             herramienta a las empresas, localidades y comunidades, que permite
                                             una rápida conexión y facilita las comunicaciones de emergencia
                                             entre los Usuarios de una comunidad y los centros emergencias, a
                                             través de la activación del botón de emergencia disponible en la
                                             Aplicación o Dispositivo.</span></span></font>
                                 </font>
                              </font>
                           </h2>
                     </ol>
                  </ol>
                  <p lang="es-ES" class="western" align="justify"><br />

                  </p>
                  <ol>
                     <ol start="4">
                        <li>
                           <h2 lang="es-ES-u-co-trad" class="western" align="justify" style="line-height: 115%">
                              <font color="#000000">
                                 <font face="Times New Roman, serif">
                                    <font size="2" style="font-size: 11pt"><span lang="es-ES"><span style="font-weight: normal">La
                                             Plataforma tiene como objetivo fortalecer las redes de apoyo y
                                             seguridad a través del esfuerzo conjunto de los miembros de una
                                             Organización. En este sentido, VCS sólo ofrece la Plataforma a
                                             Organizaciones, quienes a su vez la ponen a disposición de las
                                             personas que conforman su comunidad, mediante la entrega de
                                             usuarios y claves de acceso a la Aplicación o de la habilitación
                                             de los Dispositivos, que les permiten acceder al botón de
                                             emergencia dispuesto en la Aplicación y en los Dispositivos. En
                                             consecuencia, la Plataforma no está disponible para usuarios
                                             individuales, sino sólo para Organizaciones, y son éstas quienes
                                             proveen a los integrantes de su comunidad la facultad para acceder
                                             a la Plataforma y utilizar los servicios. VCS es el proveedor de
                                             tecnología y plataforma para la Organización, mientras que ésta
                                             facilita la prestación de los servicios de comunicación de
                                             alertas a los Usuarios. </span></span></font>
                                 </font>
                              </font>
                           </h2>
                     </ol>
                  </ol>
                  <p lang="es-ES" class="western" align="justify"><br />

                  </p>
                  <ol>
                     <ol start="5">
                        <li>
                           <h2 lang="es-ES-u-co-trad" class="western" align="justify" style="line-height: 115%">
                              <font color="#000000">
                                 <font face="Times New Roman, serif">
                                    <font size="2" style="font-size: 11pt"><span style="font-weight: normal">En
                                          virtud de lo anterior, el Cliente ha solicitado a VCS la prestación
                                          de los Servicios con el fin de poner la Plataforma a disposición
                                          de los miembros de su comunidad, quienes podrán acceder a través
                                          de la Aplicación y/o Dispositivos; y utilizar las funciones
                                          ofrecidas en ella. Estos Servicios serán proporcionados de acuerdo
                                          con los detalles y términos que se indican en el presente
                                          Contrato.</span></font>
                                 </font>
                              </font>
                           </h2>
                     </ol>
                  </ol>
                  <p lang="es-ES-u-co-trad" class="western" align="justify"><br />

                  </p>
                  <ol>
                     <ol start="6">
                        <li>
                           <h2 lang="es-ES-u-co-trad" class="western" align="justify" style="line-height: 115%">
                              <font color="#000000">
                                 <font face="Times New Roman, serif">
                                    <font size="2" style="font-size: 11pt"><span style="font-weight: normal">VCS
                                          ha manifestado su disponibilidad a proveer los Servicios requeridos
                                          por el Cliente en conformidad a los términos que a continuación
                                          se indican.</span></font>
                                 </font>
                              </font>
                           </h2>
                     </ol>
                  </ol>
                  <p class="western" style="margin-top: 0.17in; line-height: 115%">
                     <font size="2" style="font-size: 11pt"><u><b>TERCERO</b></u></font>
                     <font size="2" style="font-size: 11pt"><b>:
                           OBJETO. </b></font>
                  </p>
                  <p class="western" style="margin-top: 0.17in; line-height: 115%">
                     <font color="#000000">
                        <font size="2" style="font-size: 11pt"><span lang="es-ES-u-co-trad">Por
                              el presente instrumento, las Partes vienen en celebrar un contrato de
                              prestación de servicios en virtud del cual el Cliente encarga a VCS,
                              quien acepta, la prestación de los Servicios, en los términos
                              definidos en este Contrato</span></font>
                     </font>
                     <font size="2" style="font-size: 11pt">.
                     </font>
                  </p>
                  <p class="western" style="margin-top: 0.17in">
                     <font size="2" style="font-size: 11pt">El
                        Cliente declara conocer la Plataforma y sus funcionalidades, y
                        aceptar los términos y condiciones de la Aplicación y Dispositivos,
                        las cuales se especifican en las siguientes URLs: </font>
                  </p>
                  <ul>
                     <li>
                        <p class="western" style="margin-top: 0.17in">
                           <font color="#0000ff"><u><a href="https://www.rvex.net/protegeme">
                                    <font size="2" style="font-size: 11pt">https://www.rvex.net/protegeme</font>
                                 </a></u></font>
                           <font size="2" style="font-size: 11pt">
                           </font>
                        </p>
                     <li>
                        <p class="western" style="margin-top: 0.17in">
                           <font color="#0000ff"><u><a href="https://pbe.redvoiss.net/customer/register/condiciones.php">
                                    <font size="2" style="font-size: 11pt">https://pbe.redvoiss.net/customer/register/condiciones.php</font>
                                 </a></u></font>
                        </p>
                  </ul>
                  <p class="western" style="margin-top: 0.17in; line-height: 115%">
                     <font size="2" style="font-size: 11pt">Respecto
                        a la provisión y prestación de los Servicios, se aplicarán las
                        siguientes reglas: </font>
                  </p>
                  <p lang="es-ES-u-co-trad" align="justify" style="margin-left: 0.25in; line-height: 115%">
                     <br />

                  </p>
                  <ol type="a">
                     <li>
                        <p lang="es-ES" align="justify" style="line-height: 115%">
                           <font size="2" style="font-size: 11pt">VCS
                              pondrá a disposición del Cliente la Plataforma, y específicamente
                              pondrá a disposición del Cliente: (i) la Aplicación previamente
                              customizada; y (ii) los Dispositivos, los cuales VCS entrega en este
                              acto en comodato / arriendo / propiedad, si corresponde. Una vez
                              puestas la Aplicación y/o los Dispositivos a disposición del
                              Cliente, éste podrá ponerlos a disposición de los miembros de su
                              comunidad mediante la entrega de las claves de acceso a la
                              Plataforma. Los miembros de la comunidad deberán a su vez utilizar
                              las claves provistas para registrarse como usuarios, estando
                              habilitados desde ese momento para utilizar las funcionalidades de
                              la Plataforma, sea a través de la Aplicación y/o a través de los
                              Dispositivos. </font>
                        </p>
                  </ol>
                  <p lang="es-ES-u-co-trad" align="justify" style="margin-left: 0.25in; line-height: 115%">
                     <br />

                  </p>
                  <ol type="a" start="2">
                     <li>
                        <p lang="es-ES" align="justify" style="line-height: 115%">
                           <font size="2" style="font-size: 11pt"><span lang="es-ES-u-co-trad">Se
                                 deja constancia que, en atención a que la Plataforma no está
                                 disponible para que personas naturales o usuarios finales accedan a
                                 ella directamente, VCS no tendrá relación alguna con los Usuarios
                                 a los que el Cliente hubiere entregado las claves de acceso, o que
                                 hubiera instruido a VCS para habilitarles los Dispositivos, según
                                 se señala en este Contrato. En este sentido, </span></font>
                           <font color="#000000">
                              <font size="2" style="font-size: 11pt">VCS
                                 no es responsable de la relación contractual ni de cualquier otra
                                 naturaleza entre la Organización y los Usuarios. </font>
                           </font>
                        </p>
                  </ol>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; line-height: 115%">
                     <br />

                  </p>
                  <ol type="a" start="3">
                     <li>
                        <p lang="es-ES" align="justify" style="line-height: 115%"><a name="_Hlk183183430"></a>
                           <font size="2" style="font-size: 11pt"><span lang="es-ES-u-co-trad">Una
                                 vez habilitada la Plataforma para el Cliente, éste podrá integrar
                                 a su costo y responsabilidad toda la información y datos personales
                                 de los Usuarios que considere relevante incluir para facilitar la
                                 respuesta ante emergencias, tales como información de
                                 identificación de los Usuarios, condiciones de salud, edad, etc. Se
                                 deja constancia que VCS nunca compartirá información con terceros;
                                 y sólo almacenará y/o hará manejo de los datos personales de los
                                 Usuarios de conformidad a lo señalado en la Política de Privacidad
                                 de la Aplicación y de los Dispositivos, disponible en
                                 https://www.rvex.net/protegeme, siendo el Cliente el único
                                 responsable de dicho almacenamiento y manejo. </span></font>
                           <font size="2" style="font-size: 11pt">Será
                              de responsabilidad del Cliente obtener de sus Usuarios las
                              autorizaciones necesarias para que VCS pueda efectuar el tratamiento
                              de datos personales necesarios para prestar el servicio de
                              comunicaciones de alertas de emergencia a través de la Plataforma.
                           </font>
                        </p>
                  </ol>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; line-height: 115%">
                     <br />

                  </p>
                  <ol type="a" start="4">
                     <li>
                        <p lang="es-ES" align="justify" style="line-height: 115%">
                           <font size="2" style="font-size: 11pt">Los
                              Servicios que prestará VCS no incluyen la gestión de la
                              emergencia, por lo que el Cliente deberá contar con un sistema de
                              recepción de llamadas para que, activado el botón de emergencia de
                              la Aplicación y/o Dispositivo, el Cliente pueda recibir y derivar
                              las llamadas y mensajes de texto a los contactos de emergencia
                              predeterminados por el Usuario, y a los servicios de emergencia que
                              correspondan: carabineros, seguridad ciudadana, ambulancia,
                              bomberos, etc. El Cliente declara conocer y aceptar que VCS no es
                              una empresa de seguridad ni de gestión de emergencias, por lo que
                              tras la activación del botón de emergencia, la emergencia debe ser
                              gestionada directamente por el Cliente o a través de los terceros
                              que éste contrate, siendo VCS sólo responsable de mantener la
                              Plataforma operativa y de permitir las comunicaciones que se origen
                              desde ella hacia el sistema de recepción de llamadas del Cliente, y
                              desde éste a los contactos de emergencia y centros de ayuda, todo
                              lo anterior sujeto a que el Cliente y los Usuarios cuenten con la
                              conexión de datos requerida y demás especificaciones técnicas
                              necesarias.</font>
                        </p>
                  </ol>
                  <p lang="es-ES" align="justify" style="margin-left: 0.5in"><br />

                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; line-height: 115%">
                     <font size="2" style="font-size: 11pt">El Cliente declara
                        expresamente contar con un sistema de recepción de llamadas</font>
                     <font size="2" style="font-size: 11pt"><i>
                        </i></font>
                     <font size="2" style="font-size: 11pt">de atención a
                        Usuarios, con protocolos de funcionamiento adecuados y con la
                        capacidad necesaria para verificar, atender, y derivar la emergencia
                        a los sistemas de ayuda pertinentes. El Cliente declara expresamente
                        que es de su exclusiva responsabilidad operar este sistema bajo
                        estrictos parámetros de seguridad, siendo de su responsabilidad
                        exclusiva la mantención y operación del mismo, debiendo mantener
                        indemne a VCS por cualquier reclamo u acción intentada contra ella
                        con ocasión de la deficiencia en la prestación de los Servicios
                        atribuible a un actuar negligente del Cliente y/o del sistema de
                        recepción de llamadas y/o de sus funcionarios. </font>
                  </p>
                  <p lang="es-ES-u-co-trad" align="justify" style="margin-left: 0.25in; line-height: 115%">
                     <br />

                  </p>
                  <ol type="a" start="5">
                     <li>
                        <p lang="es-ES" align="justify" style="line-height: 115%">
                           <font size="2" style="font-size: 11pt">Para
                              la adecuada prestación y provisión de los Servicios, el Cliente y
                              los Usuarios deberán mantener operativas conexiones de voz y datos,
                              tanto a Internet como a la red pública telefónica, ya sea fija y/o
                              móvil, habilitadas de acuerdo a las especificaciones técnicas
                              indicadas por VCS. Para lo anterior, será de cargo y exclusiva
                              responsabilidad del Cliente y de los Usuarios tener el o los
                              contratos con los respectivos proveedores de servicios de acceso a
                              Internet (“ISP”) y de telefonía fija y/o móvil. En
                              consecuencia, VCS no asume responsabilidad alguna por los servicios
                              de conexión a Internet ni a la red telefónica, ni por el
                              funcionamiento, operatividad, disponibilidad, calidad ni estabilidad
                              de éstos, así como por ninguna otra materia que corresponda
                              proveer al ISP del Cliente o al Cliente o Usuario mismo, incluyendo
                              pero no limitado a la Red Interna de comunicaciones, </font>
                           <font size="2" style="font-size: 11pt"><i>routers</i></font>
                           <font size="2" style="font-size: 11pt">
                              o </font>
                           <font size="2" style="font-size: 11pt"><i>firewalls</i></font>
                           <font size="2" style="font-size: 11pt">
                              y cualquier otro dispositivo de propiedad del Cliente o del Usuario
                              o en uso por parte de ellos. El Cliente declara conocer y aceptar
                              que para un adecuado funcionamiento y calidad de los Servicios que
                              contrata en este acto, debe mantener un ancho de banda o conexión a
                              una red de datos, según correspondiera, suficiente para hacer uso
                              de la Aplicación y/o Dispositivo, así como soportar el apropiado
                              transporte de la voz para hacer efectivo el servicio de
                              comunicaciones de voz sobre IP o transmisión de datos o
                              georreferenciación, para el caso que correspondiera. Para el uso de
                              la Aplicación, el ancho de banda mínimo que deberá tener
                              disponible es de 100 Kbps de subida y bajada por cada comunicación
                              telefónica. </font>
                        </p>
                  </ol>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; line-height: 115%">
                     <br />

                  </p>
                  <ol type="a" start="6">
                     <li>
                        <p lang="es-ES" align="justify" style="line-height: 115%">
                           <font size="2" style="font-size: 11pt">Para
                              la prestación de los Servicios, se requiere necesariamente de un
                              equipo que transmita la voz y/o datos a través de las redes de
                              datos o Internet. Es de exclusiva responsabilidad del Cliente y de
                              los Usuarios la obtención de los equipos necesarios para la
                              utilización de los Servicios. Los Dispositivos debidamente
                              habilitados permitirán al Usuario acceder a la Plataforma, y
                              utilizar los servicios de botón de emergencia y comunicación de
                              alertas.</font>
                        </p>
                  </ol>
                  <p lang="es-ES" align="left" style="margin-left: 0.5in"><br />

                  </p>
                  <ol type="a" start="7">
                     <li>
                        <p lang="es-ES" align="justify" style="line-height: 115%">
                           <font size="2" style="font-size: 11pt">De
                              los cambios de los términos y condiciones de los Servicios, según
                              estos se detallan en el presente Contrato y/o sitio web, se
                              informará al Cliente con al menos 30 días de anticipación, por
                              medio del envío de un correo al domicilio y/o correo electrónico
                              indicados en el Contrato. El Cliente puede rechazar la modificación
                              de los términos y condiciones de los Servicios, sin indemnización
                              alguna para VCS, mediante el envío de carta certificada en tal
                              sentido, dirigida a VCS y despachada con al menos 10 días de
                              anticipación a la fecha de cambio de los términos.</font>
                        </p>
                  </ol>
                  <p class="western" style="margin-top: 0.17in; margin-bottom: 0.17in; line-height: 115%">
                     <font size="2" style="font-size: 11pt"><u><b>TERCERO</b></u></font>
                     <font size="2" style="font-size: 11pt"><b>.
                           COBROS Y PRECIO DE LOS SERVICIOS: </b></font>
                  </p>
                  <p class="western" style="line-height: 115%">
                     <font size="2" style="font-size: 11pt">El
                        Precio que se cobrará por los Servicios contratados por el Cliente,
                        será aquel que se indica en el Anexo Nº2 (el “</font>
                     <font size="2" style="font-size: 11pt"><u>Precio</u></font>
                     <font size="2" style="font-size: 11pt">”)</font>
                     <font size="2" style="font-size: 11pt"><span lang="es-ES">,
                           y que se desglosa según se detalla a continuación: </span></font>
                  </p>
                  <ol>
                     <li>
                        <p style="margin-right: 0.04in; margin-top: 0.08in; margin-bottom: 0.14in; line-height: 0.22in">
                           <font color="#808080">
                              <font face="Calibri, serif">
                                 <font size="2" style="font-size: 11pt">
                                    <font color="#000000">
                                       <font face="Times New Roman, serif"><span lang="es-ES">Servicios
                                             Mensuales: Corresponde al cargo por las licencias de uso de la
                                             Aplicación, cuyo precio, dependerá de la cantidad de usuarios del
                                             sistema. </span></font>
                                    </font>
                                 </font>
                              </font>
                           </font>
                        </p>
                     <li>
                        <p style="margin-right: 0.04in; margin-top: 0.08in; margin-bottom: 0.14in; line-height: 0.22in">
                           <font color="#808080">
                              <font face="Calibri, serif">
                                 <font size="2" style="font-size: 11pt">
                                    <font color="#000000">
                                       <font face="Times New Roman, serif"><span lang="es-ES">Dispositivos
                                             de Botón Físico y Accesorios: Corresponde al valor de venta o al
                                             arriendo por Dispositivos y elementos accesorios incluidos en los
                                             Servicios.</span></font>
                                    </font>
                                 </font>
                              </font>
                           </font>
                        </p>
                     <li>
                        <p style="margin-right: 0.04in; margin-top: 0.08in; margin-bottom: 0.14in; line-height: 0.22in">
                           <font color="#808080">
                              <font face="Calibri, serif">
                                 <font size="2" style="font-size: 11pt">
                                    <font color="#000000">
                                       <font face="Times New Roman, serif"><span lang="es-ES">Configuración
                                             Inicial y/e Instalación: Cargo que se cobra una sola vez, por la
                                             configuración del sistema de emergencias, los Dispositivos y la
                                             Aplicación. Incluye capacitación básica de uso de los Servicios
                                             según se dispone en la Cláusula Octava siguiente. Este valor se
                                             cobrará en conjunto con la primera factura emitida correspondiente
                                             al mes de inicio de los Servicios.</span></font>
                                    </font>
                                 </font>
                              </font>
                           </font>
                        </p>
                  </ol>
                  <p lang="es-ES" align="justify" style="line-height: 115%">
                     <font size="2" style="font-size: 11pt">VCS
                        por todo el periodo de duración del Contrato, se reserva el derecho
                        de modificar el Precio de los Servicios. En todo caso, en ningún año
                        calendario podrá aumentar más de UF+ 5%. De los cambios de precios
                        de los Servicios se informará al Cliente con al menos 30 días de
                        anticipación. Estos cambios regirán siempre para el futuro y no
                        involucrarán revisión alguna de los cargos ya devengados y pagados
                        con anterioridad. El Cliente puede rechazar la modificación de las
                        tarifas y poner término al presente Contrato, sin indemnización
                        alguna, mediante el envío de carta certificada en tal sentido,
                        dirigida a VCS y despachada con al menos 10 días de anticipación a
                        la fecha de cambio de tarifa. </font>
                  </p>
                  <p lang="es-ES" align="justify" style="line-height: 115%"><br />

                  </p>
                  <p lang="es-ES" align="justify" style="line-height: 115%">
                     <font size="2" style="font-size: 11pt">El
                        término de Contrato no eximirá al Cliente de pagar todos y cada uno
                        de los importes que adeude a VCS con motivo de los Servicios
                        efectivamente prestados. El precio de los Servicios deberá ser
                        pagado por el Cliente hasta el día en que se notifique a VCS de su
                        intención de poner término, momento en el cual VCS procederá a la
                        desconexión total de los Servicios. En caso de que el Cliente
                        rechazara la modificación de la tarifa y notificara oportunamente el
                        término del Contrato, estará obligado a pagar la tarifa previamente
                        acordada hasta la desconexión total.</font>
                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.2in; text-indent: -0.2in; margin-top: 0.17in; margin-bottom: 0.17in; line-height: 115%">
                     <font size="2" style="font-size: 11pt"><u><b>CUARTO</b></u></font>
                     <font size="2" style="font-size: 11pt"><b>:
                           FACTURACIÓN </b></font>
                  </p>
                  <p lang="es-ES" align="justify" style="line-height: 115%">
                     <font size="2" style="font-size: 11pt">VCS
                        enviará dentro de los primeros 5 días de cada mes una factura al
                        email que el Cliente informe con antelación o al domicilio del
                        Cliente indicado en el Contrato. El Cliente estará obligado a pagar
                        la factura dentro del plazo de 30 días contados desde su fecha de
                        emisión. El no pago en tiempo y forma de las referidas facturas
                        facultará a VCS a suspender la prestación total o parcial de los
                        Servicios, hasta que se verifique el pago total e íntegro de los
                        montos adeudados a esa fecha. Además, VCS podrá cobrar las sumas
                        adeudadas, con el interés máximo convencional permitido por la ley,
                        además de los gastos de cobranza judicial y extrajudicial razonables
                        y las eventuales costas, si las hubiere.</font>
                  </p>
                  <p lang="es-ES" align="justify" style="line-height: 115%"><br />

                  </p>
                  <p lang="es-ES" align="justify" style="line-height: 115%">
                     <font size="2" style="font-size: 11pt">La
                        falta de registro o contabilización interna del Cliente de la
                        factura o documentos de pago correctamente emitidos por VCS, no
                        facultará al Cliente para no pagar dichos documentos.
                        Adicionalmente, podrá conocer el monto de los Servicios a pagar, o
                        requerir la factura u otros documentos de pago directamente en el
                        área comercial de VCS o mediante el envío de un correo electrónico
                        a </font>
                     <font color="#0000ff"><u><a href="mailto:comercial@redvoiss.net">
                              <font size="2" style="font-size: 11pt">comercial@redvoiss.net</font>
                           </a></u></font>
                     <font size="2" style="font-size: 11pt">.
                     </font>
                  </p>
                  <p lang="es-ES" align="justify" style="margin-top: 0.17in; margin-bottom: 0.08in; line-height: 115%">
                     <font size="2" style="font-size: 11pt"><u><b>QUINTO</b></u></font>
                     <font size="2" style="font-size: 11pt"><b>:
                           VIGENCIA DEL CONTRATO. </b></font>
                  </p>
                  <p lang="es-ES" class="western" align="justify" style="line-height: 115%">
                     <font size="2" style="font-size: 11pt">El presente Contrato se
                        entiende que comenzará a regir desde el [</font>
                     <font face="Wingdings, serif">
                        <font size="2" style="font-size: 11pt"></font>
                     </font>
                     <font size="2" style="font-size: 11pt">]
                        de [</font>
                     <font face="Wingdings, serif">
                        <font size="2" style="font-size: 11pt"></font>
                     </font>
                     <font size="2" style="font-size: 11pt">]
                        del año [</font>
                     <font face="Wingdings, serif">
                        <font size="2" style="font-size: 11pt"></font>
                     </font>
                     <font size="2" style="font-size: 11pt">],
                        y tendrá una vigencia de un año, es decir, hasta el [</font>
                     <font face="Wingdings, serif">
                        <font size="2" style="font-size: 11pt"></font>
                     </font>
                     <font size="2" style="font-size: 11pt">]
                        de [</font>
                     <font face="Wingdings, serif">
                        <font size="2" style="font-size: 11pt"></font>
                     </font>
                     <font size="2" style="font-size: 11pt">]
                        del año [</font>
                     <font face="Wingdings, serif">
                        <font size="2" style="font-size: 11pt"></font>
                     </font>
                     <font size="2" style="font-size: 11pt">].
                        Este plazo se prorrogará de manera automática por períodos iguales
                        de un año cada uno, salvo que cualquiera de las Partes notificare a
                        la otra de su intención de no renovar con a lo menos 60 días de
                        anticipación al vencimiento del plazo de vigencia del Contrato, o
                        bien de alguna de sus prórrogas. </font>
                  </p>
                  <p lang="es-ES" class="western" align="justify" style="line-height: 115%">
                     <br />

                  </p>
                  <p lang="es-ES" class="western" align="justify" style="line-height: 115%">
                     <font size="2" style="font-size: 11pt">Sin perjuicio de lo anterior,
                        cualquiera de las Partes podrá poner término al Contrato en
                        cualquier momento, dando aviso a la otra Parte mediante comunicación
                        enviada por carta certificada al domicilio de la otra Parte indicado
                        en el Contrato o al que hubiere informado la Parte conforme al
                        procedimiento indicado en la cláusula Décimo Tercera del Contrato,
                        despachada con, a lo menos, 30 días de anticipación a la fecha en
                        que desee ponerle término. Será exclusiva responsabilidad de cada
                        parte informar los cambios de domicilio que registren durante la
                        vigencia del presente Contrato, por lo que, de no mediar ninguna
                        comunicación de cambio de domicilio, se entenderá como válida la
                        comunicación enviada al domicilio indicado en el Contrato.</font>
                  </p>
                  <p lang="es-ES" align="justify" style="line-height: 115%"><br />

                  </p>
                  <p lang="es-ES" align="justify" style="margin-bottom: 0.08in; line-height: 115%">
                     <font size="2" style="font-size: 11pt">Los Servicios serán prestados
                        durante la vigencia del Contrato, salvo suspensión o terminación
                        anticipada de conformidad con el presente Contrato; y a los Términos
                        y Condiciones de la Aplicación y/o Dispositivo, según
                        correspondiera. </font>
                  </p>
                  <p lang="es-ES" align="justify" style="line-height: 115%">
                     <font size="2" style="font-size: 11pt">Las
                        disposiciones que continuarán en vigencia con posterioridad a la
                        terminación o el vencimiento del presente Contrato son aquellas
                        referidas a la limitación de responsabilidad, indemnización, pagos,
                        confidencialidad y otras que por su naturaleza se pretenden que
                        subsistan.</font>
                  </p>
                  <p lang="es-ES" align="justify" style="line-height: 115%"><br />

                  </p>
                  <p lang="es-ES" align="justify" style="line-height: 115%">
                     <font size="2" style="font-size: 11pt">La
                        terminación del Contrato, tanto por el Cliente como por VCS, no
                        libera al Cliente de la obligación de pagar todos los cargos por el
                        uso de los Servicios durante la vigencia del Contrato hasta la fecha
                        de terminación.</font>
                  </p>
                  <p lang="es-ES" align="justify" style="line-height: 115%"><br />

                  </p>
                  <p lang="es-ES" align="justify" style="line-height: 115%">
                     <font size="2" style="font-size: 11pt">VCS
                        podrá poner término inmediato al Contrato y sus Anexos, sin
                        requerimiento judicial ni arbitral alguno, y sin derecho a
                        indemnización o compensación alguna a favor del Cliente, por las
                        siguientes causas:</font>
                  </p>
                  <p lang="es-ES" align="justify" style="line-height: 115%"><br />

                  </p>
                  <ol type="a">
                     <li>
                        <p lang="es-ES" align="justify" style="line-height: 115%">
                           <font size="2" style="font-size: 11pt">Cuando
                              por acto o mandato de la autoridad administrativa o judicial se
                              impida o se limite, de cualquier forma, la prestación de los
                              Servicios por parte de VCS, en cuyo caso VCS deberá notificar al
                              Cliente inmediatamente y prestar el servicio durante el plazo que
                              disponga la respectiva autoridad.</font>
                        </p>
                     <li>
                        <p lang="es-ES" align="justify" style="line-height: 115%">
                           <font size="2" style="font-size: 11pt">Por
                              incumplimiento del Cliente de las obligaciones establecidas en el
                              presente Contrato, y especialmente, por el no pago por dos meses
                              consecutivos de las facturas por los Servicios contratados en tiempo
                              y forma convenidas. </font>
                        </p>
                     <li>
                        <p lang="es-ES" align="justify" style="line-height: 115%">
                           <font size="2" style="font-size: 11pt">Si
                              el Cliente hiciere, de cualquier forma, uso ilegal de los Servicios.</font>
                        </p>
                     <li>
                        <p lang="es-ES" align="justify" style="line-height: 115%">
                           <font size="2" style="font-size: 11pt">Por
                              extinción de la personalidad jurídica del Cliente.</font>
                        </p>
                     <li>
                        <p lang="es-ES" align="justify" style="line-height: 115%">
                           <font size="2" style="font-size: 11pt">Si
                              el Cliente solicitare su propia liquidación o reorganización, sea
                              voluntaria o forzosa, al amparo de lo establecido en la Ley 20.720 o
                              aquélla que la sustituya o reemplace.</font>
                        </p>
                     <li>
                        <p lang="es-ES" align="justify" style="line-height: 115%">
                           <font size="2" style="font-size: 11pt">Por
                              caso fortuito o fuerza mayor que, de cualquier modo, impida a VCS la
                              prestación de los Servicios en los términos convenidos; se
                              entenderá que constituye caso fortuito la indisponibilidad, por un
                              largo tiempo o en forma indefinida, de los medios que VCS requiere
                              para prestar los Servicios. Se incluye dentro de esta causal por
                              ejemplo las inhabilitaciones de la red Internet o cualesquiera otros
                              actos de terceros fuera del control de VCS. En caso de operar esta
                              causal de terminación, VCS lo notificará al Cliente. Durante el
                              lapso de la interrupción del servicio por parte de VCS, el Cliente
                              no estará obligado a su pago. </font>
                        </p>
                  </ol>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; line-height: 115%">
                     <br />

                  </p>
                  <p lang="es-ES" align="justify" style="line-height: 115%; orphans: 0; widows: 0">
                     <font size="2" style="font-size: 11pt">Ante la ocurrencia de uno o
                        más de los hechos antes descritos, VCS comunicará por escrito al
                        Cliente, en el domicilio señalado en el Contrato o al que tenga al
                        momento del envío de la comunicación, su voluntad de dar por
                        terminado este Contrato, indicando la fecha de término.</font>
                  </p>
                  <p lang="es-ES" align="justify" style="line-height: 115%; orphans: 0; widows: 0">
                     <br />

                  </p>
                  <p lang="es-ES" align="justify" style="line-height: 115%">
                     <font size="2" style="font-size: 11pt">La
                        terminación del presente Contrato, por el motivo que fuere, no
                        faculta al Cliente, en caso alguno, a solicitar la devolución de los
                        dineros pagados como contraprestación de la prestación de los
                        Servicios por parte de VCS, debiendo pagar los servicios hasta la
                        fecha de término efectiva. </font>
                  </p>
                  <p lang="es-ES" align="justify" style="margin-top: 0.17in; line-height: 115%; orphans: 0; widows: 0">
                     <font size="2" style="font-size: 11pt"><u><b>SEXTO</b></u></font>
                     <font size="2" style="font-size: 11pt"><b>:
                           RESPONSABILIDAD. </b></font>
                  </p>
                  <p lang="es-ES" align="justify" style="margin-top: 0.17in; line-height: 115%; orphans: 0; widows: 0">
                     <font size="2" style="font-size: 11pt">El Cliente declara
                        expresamente que, en conocimiento de las disposiciones de este
                        Contrato, acepta que el suministro de los Servicios está sujeto a
                        las condiciones y limitaciones que a continuación se expresan, las
                        cuales, dada su especial naturaleza, entiende y acepta como
                        intrínsecas y de la esencia en la prestación del mismo:</font>
                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; line-height: 115%">
                     <br />

                  </p>
                  <ol type="a">
                     <li>
                        <p lang="es-ES" align="justify" style="line-height: 115%">
                           <font size="2" style="font-size: 11pt">Que
                              está en conocimiento y acepta expresamente, que la calidad de los
                              Servicios, el uso de la Plataforma, la Aplicación y/o Dispositivos,
                              y los servicios de comunicaciones que se prestan a través de la
                              Plataforma, están determinados por la capacidad del equipo que se
                              utilice, su correcto uso, la conexión a Internet, o a otra red de
                              datos privada, o conexión análoga, de los servicios de terceros
                              como operadoras de telefonía móvil o fija, dependiendo del
                              mecanismo a través del cual se acceda a la Plataforma, sea la
                              Aplicación o Dispositivos.</font>
                        </p>
                  </ol>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; line-height: 115%">
                     <br />

                  </p>
                  <ol type="a" start="2">
                     <li>
                        <p lang="es-ES" align="justify" style="line-height: 115%">
                           <font size="2" style="font-size: 11pt">Que
                              está en pleno conocimiento y acepta que los Servicios, y el uso de
                              la Aplicación y/o Dispositivo, y los servicios que se prestan a
                              través de éstos a los Usuarios, pueden verse afectados por fallas
                              no imputables a VCS, tales como interrupciones atribuibles al
                              servicio de Internet, fallas en las redes de comunicaciones o en la
                              red interna del Cliente o equipamiento de su propiedad,
                              interrupciones en las comunicaciones, fallas en redes de operadores
                              de servicios públicos de telefonía, así como otras causas no
                              imputables o previsibles por VCS, y que se encuentran fuera de su
                              control.</font>
                        </p>
                  </ol>
                  <p lang="es-ES" align="justify" style="margin-left: 0.59in; text-indent: -0.2in; line-height: 115%">
                     <br />

                  </p>
                  <p class="western" style="line-height: 115%">
                     <font size="2" style="font-size: 11pt">Las
                        limitaciones y condiciones de los Servicios descritos anteriormente
                        se han tenido presente por las Partes para su contratación. Éstas,
                        de producirse, no constituyen deficiencias que afectan la finalidad o
                        utilidad del suministro de los Servicios, sino que corresponden a
                        características y condiciones propias o intrínsecas de esta clase
                        de servicios de aplicaciones móviles y comunicaciones, sea a través
                        de IP o telefonía. En consecuencia, en tales casos no se entenderá
                        que existe una falta o deficiencia en la prestación de los Servicios
                        por parte de VCS, renunciando el Cliente a cualquier acción o
                        derecho que pudiere ejercer en contra de VCS a consecuencia de lo
                        anterior. En este sentido, VCS limita únicamente su responsabilidad
                        a permitir al Cliente y a los Usuarios el acceso y utilización de la
                        Plataforma a través de la Aplicación y/o Dispositivos, y permitir
                        las comunicaciones que se originen con motivo de su uso, no siendo
                        responsable en caso alguno por el corte o interrupción de los
                        Servicios por causas no atribuibles a ella. </font>
                  </p>
                  <p class="western" style="line-height: 115%"><br />

                  </p>
                  <p class="western" style="line-height: 115%">
                     <font size="2" style="font-size: 11pt">Adicionalmente,
                        se deja constancia de que existen otros elementos esenciales para la
                        prestación de los Servicios que dependen exclusivamente del Cliente
                        y/o de los Usuarios que hagan uso de la Plataforma, como son la
                        disponibilidad de la conexión de datos o banda ancha, la
                        contratación de un proveedor de Acceso a Internet (“ISP”)
                        asociado o la participación de terceras personas configuradoras e
                        instaladoras de software y hardware. </font>
                  </p>
                  <p class="western" style="line-height: 115%"><br />

                  </p>
                  <p class="western" style="line-height: 115%">
                     <font size="2" style="font-size: 11pt">En
                        el mismo sentido, las partes dejan constancia que VCS no será
                        responsable por intervenciones, interrupciones, suspensiones o
                        alteraciones a los Servicios o a la calidad de éste o de la voz que
                        se transmite, por razones que sean imputables o tengan relación con
                        los elementos externos que fueran necesarios para la correcta
                        prestación de los Servicios, y que no son de responsabilidad de VCS.
                        Será de exclusiva responsabilidad del Cliente y de cada Usuario, la
                        protección de su red interna, para lo cual se obliga a tomar y
                        contratar las medidas de seguridad necesarias para el cumplimiento de
                        dichos fines.</font>
                  </p>
                  <p class="western" style="line-height: 115%"><br />

                  </p>
                  <p class="western" style="line-height: 115%">
                     <font size="2" style="font-size: 11pt">Sin
                        perjuicio de lo anterior, VCS se compromete a solucionar cualquier
                        falla en la operatividad de la Plataforma o de cualquier otro
                        software que se utilice para la prestación de los Servicios dentro
                        del menor plazo posible, realizando sus mejores esfuerzos para que la
                        falla afecte lo menos posible a la prestación de los Servicios. El
                        Cliente declara tener conocimiento y acepta expresamente que </font>
                     <font size="2" style="font-size: 11pt"><span lang="es-ES">podrían
                           existir costos adicionales asociados con la resolución de dichas
                           fallas, especialmente si son consecuencia de modificaciones no
                           autorizadas, mal uso de la Aplicación y/o Dispositivo, o la
                           necesidad de implementar soluciones específicas que impliquen
                           recursos adicionales. Dichos costos serán debidamente comunicados al
                           Cliente y serán cobrados en la factura a ser emitida en el mes
                           inmediatamente siguiente a la resolución o reparación requerida.</span></font>
                  </p>
                  <p class="western" style="line-height: 115%"><br />

                  </p>
                  <p class="western" style="line-height: 115%">
                     <font size="2" style="font-size: 11pt"><span lang="es-ES">A
                           mayor abundamiento, el Cliente declara conocer que la Plataforma
                           tiene como finalidad proporcionar una herramienta para agilizar los
                           tiempos de respuesta ante emergencias y situaciones de peligro que
                           puedan enfrentar los Usuarios. En este sentido, VCS ofrece un canal
                           de comunicación automático e inmediato entre el Usuario que activa
                           el botón de emergencia a través de la Aplicación y/o Dispositivo y
                           un sistema de recepción de llamadas que debe ser previamente
                           provisto y habilitado por el Cliente, cuyo(s) operador(es)
                           confirmarán la emergencia mediante protocolos implementados al
                           efecto y manejarán la emergencia según las políticas propias
                           correspondientes. Sin embargo, VCS no garantiza la efectividad ni la
                           eficiencia de los resultados, ya que estos exceden el alcance de los
                           Servicios contratados. Por lo tanto, VCS no será responsable del
                           funcionamiento ni del desempeño de los operadores del Cliente, de la
                           rapidez en el manejo de la emergencia, de la eficacia y/o de los
                           tiempos de respuesta por parte de los servicios de emergencia
                           contactados, ni de los resultados que puedan derivarse del uso de la
                           Aplicación y/o Dispositivos. La efectividad de las medidas adoptadas
                           y la obtención de los resultados deseados serán responsabilidad
                           exclusiva del Cliente y/o Usuario, así como de los servicios de
                           emergencia de terceros contactados en cada caso particular.</span></font>
                  </p>
                  <p lang="es-ES" class="western" style="line-height: 115%"><br />

                  </p>
                  <p class="western" style="line-height: 115%">
                     <font size="2" style="font-size: 11pt"><span lang="es-ES">Asimismo,
                           las partes declaran que es y será de exclusiva responsabilidad del
                           Cliente y de los Usuarios, mantener durante todo el periodo de
                           vigencia del presente Contrato, el acceso y navegación en Internet
                           para el uso de la Aplicación y/o el Dispositivo en los casos que
                           correspondiere, en condiciones que permitan la prestación de los
                           Servicios por parte de VCS. Además de lo anterior, el Cliente y/o
                           los Usuarios serán exclusivamente responsables de todos los ataques
                           de Hackers u otro tipo amenazas que puedan sufrir sus redes y
                           configuraciones de Internet y que puedan afectar la prestación de
                           los Servicios de manera idónea. Por </span></font>
                     <font size="2" style="font-size: 11pt">lo
                        anterior, y considerando además que VCS no tiene control ni
                        vigilancia en la conexión y acceso del Cliente ni de los Usuarios a
                        Internet, así como de la utilización que de los Servicios el
                        Cliente y/o los Usuarios efectúen, VCS no asumirá ninguna
                        responsabilidad por los problemas que pueda sufrir al Cliente
                        detallados en el presente párrafo. En consideración a lo anterior,
                     </font>
                     <font size="2" style="font-size: 11pt"><span lang="es-ES">el
                           Cliente reconoce y acepta expresamente que asume toda la
                           responsabilidad frente a los Usuarios, comprometiéndose a mantener
                           indemne a VCS ante cualquier reclamo o demanda que pudieran
                           interponer los Usuarios como consecuencia de la utilización de los
                           servicios de comunicación de alertas provistos a través de la
                           Plataforma. </span></font>
                  </p>
                  <p lang="es-ES" class="western" style="line-height: 115%"><br />

                  </p>
                  <p class="western" style="line-height: 115%">
                     <font size="2" style="font-size: 11pt">Sumado
                        a ello, se deja expresa constancia por las Partes que VCS no tendrá
                        responsabilidad alguna respecto del tratamiento de los datos
                        personales e información provista por los Usuarios en la utilización
                        de la Aplicación y/o Dispositivo, siendo el Cliente el único
                        responsable de su recopilación, custodia, uso y tratamiento, lo cual
                        se obliga a realizar en cumplimiento de las disposiciones de la Ley
                        19.628 sobre protección de la vida privada. El Cliente se obliga a
                        mantener indemne a VCS de todo daño ante cualquier reclamo, acción
                        o demanda intentada por los Usuarios o terceros como consecuencia del
                        incumplimiento de dicha normativa. </font>
                  </p>
                  <p lang="es-ES" class="western" style="line-height: 115%"><br />

                  </p>
                  <p class="western" style="line-height: 115%">
                     <font size="2" style="font-size: 11pt">De
                        esta manera, VCS sólo será responsable ante el Cliente por la
                        disponibilidad y calidad de los Servicios, por causas imputables a
                        ella, y por aquellas indisponibilidades y deficiente calidad,
                        derivadas de su culpa grave o dolo. Será de cargo del Cliente
                        acreditar la culpa y/o dolo de VCS en la prestación de los
                        Servicios. En todo caso, la mencionada responsabilidad de VCS por
                        cualquier daño o perjuicio que se pudiera haber causado al Cliente,
                        quedará limitada al monto de las sumas pagadas por el Cliente a VCS
                        con motivo del presente Contrato, por un máximo de 6 meses, contados
                        desde la fecha en que se produjo el hecho de responsabilidad de VCS.
                        Este monto máximo corresponderá, a título de </font>
                     <font size="2" style="font-size: 11pt"><span lang="es-ES">cláusula
                           penal, a una avaluación anticipada y convencional de todos los
                           perjuicios causados, sean estos directos e indirectos, previstos e
                           imprevistos, patrimoniales y morales, </span></font>
                     <font size="2" style="font-size: 11pt">moratorios
                        y compensatorios. Las partes dejan expresamente consignado que el
                        pago de esta multa extingue total y definitivamente la obligación
                        principal que se garantiza, esto es prestar los Servicios en tiempo y
                        forma debida y cualquier otra obligación que tenga su causa en la
                        celebración del Contrato. </font>
                  </p>
                  <p class="western" style="line-height: 115%"><br />

                  </p>
                  <p class="western" style="line-height: 115%">
                     <font size="2" style="font-size: 11pt">Igualmente,
                        el Cliente renuncia desde ya al derecho a elegir entre el pago de la
                        pena o de la indemnización de perjuicios indicado en el artículo
                        1.543 del Código Civil, pudiendo cobrar únicamente como
                        indemnización por cualquier y todo daño ocasionado por el
                        incumplimiento contractual, el monto máximo acordado en la cláusula
                        penal estipulada en este párrafo. </font>
                  </p>
                  <p class="western" style="line-height: 115%"><br />

                  </p>
                  <p class="western" style="line-height: 115%">
                     <font size="2" style="font-size: 11pt">Las
                        Partes no serán en caso alguno responsables del caso fortuito o
                        fuerza mayor.</font>
                  </p>
                  <p class="western" style="line-height: 115%"><br />

                  </p>
                  <p class="western" style="line-height: 115%">
                     <font size="2" style="font-size: 11pt">La
                        presente cláusula Sexta permanecerá en vigor aún después de la
                        terminación por cualquier causa del Contrato.</font>
                  </p>
                  <p class="western" style="margin-top: 0.17in; line-height: 115%">
                     <font size="2" style="font-size: 11pt"><u><b>SÉPTIMO</b></u></font>
                     <font size="2" style="font-size: 11pt"><b>:
                           TRATAMIENTO DE DATOS PERSONALES</b></font>
                  </p>
                  <p class="western" style="margin-top: 0.17in; line-height: 115%">
                     <font size="2" style="font-size: 11pt"><span lang="es-ES">Se
                           deja expresa constancia por las Partes que VCS, en su calidad de
                           proveedor de la Plataforma y con ocasión de la prestación de los
                           Servicios, no recoge datos personales, ni los procesa, trata o
                           utiliza en ningún momento, siendo dicha labor de responsabilidad
                           única y exclusiva del Cliente, quien será el único encargado y
                           responsable de recopilar, custodiar y utilizar datos personales en
                           cumplimiento de la normativa vigente. </span></font>
                  </p>
                  <p class="western" style="margin-top: 0.17in; line-height: 115%">
                     <font size="2" style="font-size: 11pt"><span lang="es-ES">Sin
                           perjuicio de ello, </span></font>
                     <font size="2" style="font-size: 11pt"><span lang="es-MX">para
                           el óptimo uso de las funcionalidades de Plataforma y la correcta
                           prestación de los servicios de comunicación de alertas provisto a
                           través de la Aplicación y/o Dispositivos; VCS tendrá acceso a los
                           datos personales de los Usuarios, los cuales deberán ser recopilados
                           y almacenados por el Cliente. </span></font>
                  </p>
                  <p class="western" style="margin-top: 0.17in; line-height: 115%">
                     <font size="2" style="font-size: 11pt"><span lang="es-MX">En
                           este sentido, s</span></font>
                     <font size="2" style="font-size: 11pt"><span lang="es-ES-u-co-trad">e
                           deja constancia que VCS nunca compartirá información con terceros;
                           y para la prestación de los servicios de comunicación de alertas,
                           sólo almacenará y/o hará manejo los datos personales de los
                           Usuarios de conformidad a lo señalado en la Política de Privacidad
                           de la Aplicación y/o Dispositivos disponible en
                           https://www.rvex.net/protegeme, siendo el Cliente el único
                           responsable de dicho almacenamiento y manejo.</span></font>
                  </p>
                  <p class="western" style="margin-top: 0.17in; line-height: 115%">
                     <font size="2" style="font-size: 11pt"><span lang="es-ES">El
                           Cliente acepta y reconoce expresamente que es el único responsable
                           del tratamiento de los datos personales proporcionados por los
                           Usuarios, y se compromete a cumplir con la ley y la normativa
                           referente al tratamiento de datos personales.</span></font>
                  </p>
                  <p class="western" style="margin-top: 0.17in; line-height: 115%">
                     <font size="2" style="font-size: 11pt"><span lang="es-ES">En
                           virtud de lo anterior, VCS queda exento de cualquier responsabilidad
                           relacionada con la recopilación, tratamiento o custodia de los datos
                           personales de los Usuarios, debiendo el Cliente mantener indemne a
                           VCS respecto de cualquier reclamación, acción o disputa al
                           respecto.</span></font>
                  </p>
                  <p class="western" style="margin-top: 0.17in; line-height: 115%">
                     <font size="2" style="font-size: 11pt"><u><b>OCTAVO</b></u></font>
                     <font size="2" style="font-size: 11pt"><b>:
                           CAPACITACIÓN Y ASISTENCIA TÉCNICA. </b></font>
                  </p>
                  <p class="western" style="margin-top: 0.17in; line-height: 115%">
                     <font size="2" style="font-size: 11pt">Durante
                        la vigencia del presente Contrato y por una sola vez, VCS por medio
                        de su personal especializado se compromete a capacitar al Cliente en
                        la utilización de los Servicios de la siguiente forma:</font>
                  </p>
                  <p class="western" style="margin-left: 0.25in; line-height: 115%"><br />

                  </p>
                  <p class="western" style="line-height: 115%">
                     <font size="2" style="font-size: 11pt">VCS
                        capacitará a tres personas designadas por el Cliente en la
                        utilización de los Servicios, en las instalaciones de VCS o
                        remotamente, a elección del Cliente. El Cliente podrá solicitar
                        capacitaciones adicionales cuyo valor será aquel que se determine
                        por las Partes al momento de solicitarse tales capacitaciones, a
                        determinar en su oportunidad. </font>
                  </p>
                  <p class="western" style="margin-left: 0.25in; line-height: 115%"><br />

                  </p>
                  <p lang="es-ES-u-co-trad" align="justify" style="line-height: 115%; orphans: 0; widows: 0">
                     <font face="Book Antiqua, serif">
                        <font face="Times New Roman, serif">
                           <font size="2" style="font-size: 11pt"><span lang="es-ES"><span style="font-weight: normal">Se
                                    deja constancia que VCS dispone de un Centro de Atención al Cliente
                                    con disponibilidad los 7 días de la semana y en cualquier horario
                                    para asistencia técnica, habilitada en el número (56) 2 24053000 y
                                    en el correo electrónico </span></span></font>
                        </font><b>
                           <font color="#0000ff"><u>
                                 <font face="Times New Roman, serif">
                                    <font size="2" style="font-size: 11pt"><span lang="es-ES"><a href="mailto:soporte@redvoiss.net">soporte@redvoiss.net</a>.</span></font>
                                 </font>
                              </u></font>
                        </b>
                        <font face="Times New Roman, serif">
                           <font size="2" style="font-size: 11pt"><span lang="es-ES"><span style="font-weight: normal">
                                 </span></span></font>
                        </font>
                     </font>
                  </p>
                  <p class="western" style="margin-top: 0.17in; line-height: 115%">
                     <font size="2" style="font-size: 11pt"><u><b>NOVENO</b></u></font>
                     <font size="2" style="font-size: 11pt"><b>:
                           CESIÓN. </b></font>
                  </p>
                  <p class="western" style="margin-top: 0.17in; line-height: 115%">
                     <font size="2" style="font-size: 11pt">Las
                        partes acuerdan que el Cliente no podrá ceder o traspasar el
                        Contrato, o los derechos y obligaciones emanados del mismo, salvo
                        autorización previa y por escrito de VCS, sin perjuicio de ofrecer
                        el acceso a la Plataforma a los miembros de su Organización, según
                        los términos señalados en el presente Contrato. </font>
                  </p>
                  <p lang="es-ES" align="justify" style="margin-top: 0.17in; line-height: 115%; orphans: 0; widows: 0">
                     <font size="1" style="font-size: 8pt">
                        <font size="2" style="font-size: 11pt"><u><b>DÉCIMO</b></u></font>
                        <font size="2" style="font-size: 11pt"><b>:
                              LEY APLICABLE Y DOMICILIO </b></font>
                     </font>
                  </p>
                  <p lang="es-ES" align="justify" style="margin-top: 0.17in; line-height: 115%; orphans: 0; widows: 0">
                     <font size="1" style="font-size: 8pt">
                        <font size="2" style="font-size: 11pt">Las
                           partes fijan para todos los efectos de este Contrato su domicilio en
                           la ciudad y comuna de Santiago de Chile.</font>
                     </font>
                  </p>
                  <p lang="es-ES" align="justify" style="margin-top: 0.17in; line-height: 115%; orphans: 0; widows: 0">
                     <font size="1" style="font-size: 8pt">
                        <font size="2" style="font-size: 11pt"><span lang="es-CL"><u><b>DÉCIMO
                                    PRIMERO</b></u></span></font>
                        <font size="2" style="font-size: 11pt"><span lang="es-CL"><b>.
                                 ARBITRAJE</b></span></font>
                     </font>
                  </p>
                  <p class="western" align="justify"><br />

                  </p>
                  <p lang="es-ES" class="western" align="justify">
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">Cualquier
                           dificultad o controversia que se produzca en relación con el
                           presente contrato, incluido cualquier asunto vinculado a su
                           aplicación, interpretación, duración, validez, ejecución o
                           terminación, será sometido a arbitraje conforme al Reglamento
                           Procesal de Arbitraje del Centro de Arbitraje y Mediación (</span></font>
                     <font size="2" style="font-size: 11pt"><span lang="es-CL"><u>CAM
                              Santiago</u></span></font>
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">)
                           de la Cámara de Comercio de Santiago A.G. vigente al momento de
                           solicitarlo.</span></font>
                  </p>
                  <p class="western" align="justify"><br />

                  </p>
                  <p lang="es-ES" class="western" align="justify">
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">Las
                           partes designarán de común acuerdo a un árbitro arbitrador en
                           cuanto al procedimiento y de derecho en cuanto al fallo. El CAM
                           Santiago podrá asistir a las partes en el proceso de designación.
                           En caso de no prosperar la designación de común acuerdo, las partes
                           confieren poder especial irrevocable a la Cámara de Comercio de
                           Santiago A.G., para que a petición escrita de cualquiera de ellas,
                           designe al árbitro de entre los integrantes del cuerpo arbitral del
                           CAM Santiago. Las Partes hacen expresa reserva de su derecho a vetar,
                           sin expresión de causa, hasta tres árbitros de los propuestos por
                           el CAM Santiago. Lo anterior, sin perjuicio de la facultad de las
                           Partes para alegar la inhabilidad del árbitro designado conforme a
                           las causas legales. </span></font>
                  </p>
                  <p class="western" align="justify"><br />

                  </p>
                  <p lang="es-ES" class="western" align="justify">
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">En
                           contra de las resoluciones del árbitro no procederá recurso alguno,
                           renunciando las partes expresamente a ellos. El árbitro queda
                           especialmente facultado para resolver todo asunto relacionado con su
                           competencia y/o jurisdicción.</span></font>
                  </p>
                  <p class="western" align="justify"><br />

                  </p>
                  <p lang="es-ES" align="justify" style="margin-top: 0.17in; line-height: 115%; orphans: 0; widows: 0">
                     <font size="1" style="font-size: 8pt">
                        <font size="2" style="font-size: 11pt"><span lang="es-CL"><u><b>DÉCIMO
                                    SEGUNDO</b></u></span></font>
                        <font size="2" style="font-size: 11pt"><span lang="es-CL"><b>:
                                 REPRESENTACIÓN LEGAL.</b></span></font>
                     </font>
                  </p>
                  <p lang="es-ES" align="justify" style="margin-top: 0.17in; line-height: 115%; orphans: 0; widows: 0">
                     <font size="1" style="font-size: 8pt">
                        <font size="2" style="font-size: 11pt"><span lang="es-CL">Los
                              representantes que suscriben el presente Contrato declaran tener
                              facultades suficientes para representar y obligar a la persona
                              jurídica que representa, asumiendo la responsabilidad legal según
                              corresponda, en caso que ello no sea efectivo o que tal
                              representación sea insuficiente. Esta cláusula se aplicará
                              respecto a todos los Anexos que se suscriban conforme al Contrato.</span></font>
                     </font>
                  </p>
                  <p lang="es-ES" align="justify" style="margin-top: 0.17in; line-height: 115%; orphans: 0; widows: 0">
                     <font size="1" style="font-size: 8pt">
                        <font size="2" style="font-size: 11pt"><u><b>DÉCIMO
                                 TERCERO</b></u></font>
                        <font size="2" style="font-size: 11pt"><b>:
                              AVISOS Y COMUNICACIONES. </b></font>
                     </font>
                  </p>
                  <p lang="es-ES" align="justify" style="margin-top: 0.17in; line-height: 115%; orphans: 0; widows: 0">
                     <font size="1" style="font-size: 8pt">
                        <font size="2" style="font-size: 11pt">Todas
                           las comunicaciones a realizarse entre VCS y el Cliente en virtud de
                           la ejecución del presente Contrato y sus Anexos, se realizarán vía
                           correo electrónico y se entenderán recibidas al día siguiente,
                           salvo aquellas notificaciones que según el presente Contrato
                           requieran de una forma distinta de notificación. </font>
                     </font>
                  </p>
                  <p lang="es-ES" align="justify" style="margin-top: 0.17in; line-height: 115%; orphans: 0; widows: 0">
                     <font size="1" style="font-size: 8pt">
                        <font size="2" style="font-size: 11pt">Los
                           avisos y notificaciones deben ir dirigidos a las siguientes personas,
                           domicilios y/o correos electrónicos, en relación a cada una de las
                           Partes: </font>
                     </font>
                  </p>
                  <p lang="es-ES" align="justify" style="margin-top: 0.17in; margin-bottom: 0.08in; line-height: 115%; orphans: 0; widows: 0">
                     <font size="1" style="font-size: 8pt">
                        <font size="2" style="font-size: 11pt"><b>VCS</b></font>
                        <font size="2" style="font-size: 11pt">:
                        </font>
                     </font>
                  </p>
                  <p lang="es-ES" align="justify" style="orphans: 0; widows: 0">
                     <font size="1" style="font-size: 8pt">
                        <font size="2" style="font-size: 11pt">Dirección :
                           Padre Mariano 82 oficina 401, comuna Providencia.</font>
                     </font>
                  </p>
                  <p lang="es-ES" align="justify" style="orphans: 0; widows: 0">
                     <font size="1" style="font-size: 8pt">
                        <font size="2" style="font-size: 11pt">Atención :
                           [</font>
                        <font face="Wingdings, serif">
                           <font size="2" style="font-size: 11pt"></font>
                        </font>
                        <font size="2" style="font-size: 11pt">]
                           ejecutivo</font>
                     </font>
                  </p>
                  <p lang="es-ES" align="justify" style="orphans: 0; widows: 0">
                     <font size="1" style="font-size: 8pt">
                        <font size="2" style="font-size: 11pt">Correo
                           electrónico : [</font>
                        <font face="Wingdings, serif">
                           <font size="2" style="font-size: 11pt"></font>
                        </font>
                        <font size="2" style="font-size: 11pt">]</font>
                     </font>
                  </p>
                  <p lang="es-ES" align="justify" style="margin-top: 0.17in; margin-bottom: 0.08in; line-height: 115%; orphans: 0; widows: 0">
                     <font size="1" style="font-size: 8pt">
                        <font size="2" style="font-size: 11pt">Con
                           copia a:</font>
                     </font>
                  </p>
                  <p lang="es-ES" align="justify" style="line-height: 115%; orphans: 0; widows: 0">
                     <font size="1" style="font-size: 8pt">
                        <font size="2" style="font-size: 11pt">Atención :
                           Soporte Redvoiss</font>
                     </font>
                  </p>
                  <p lang="es-ES" align="justify" style="line-height: 115%; orphans: 0; widows: 0">
                     <font size="1" style="font-size: 8pt">
                        <font size="2" style="font-size: 11pt">Correo
                           electrónico : soporte@redvoiss.net</font>
                     </font>
                  </p>
                  <p lang="es-ES" align="justify" style="margin-top: 0.17in; margin-bottom: 0.08in; line-height: 115%; orphans: 0; widows: 0">
                     <font size="1" style="font-size: 8pt">
                        <font size="2" style="font-size: 11pt"><b>Cliente</b></font>
                        <font size="2" style="font-size: 11pt">:
                        </font>
                     </font>
                  </p>
                  <p lang="es-ES" align="justify" style="orphans: 0; widows: 0">
                     <font size="1" style="font-size: 8pt">
                        <font size="2" style="font-size: 11pt">Dirección :
                           [</font>
                        <font face="Wingdings, serif">
                           <font size="2" style="font-size: 11pt"></font>
                        </font>
                        <font size="2" style="font-size: 11pt">]</font>
                     </font>
                  </p>
                  <p lang="es-ES" align="justify" style="orphans: 0; widows: 0">
                     <font size="1" style="font-size: 8pt">
                        <font size="2" style="font-size: 11pt">Atención :
                           [</font>
                        <font face="Wingdings, serif">
                           <font size="2" style="font-size: 11pt"></font>
                        </font>
                        <font size="2" style="font-size: 11pt">]</font>
                     </font>
                  </p>
                  <p lang="es-ES" align="justify" style="orphans: 0; widows: 0">
                     <font size="1" style="font-size: 8pt">
                        <font size="2" style="font-size: 11pt">Correo
                           electrónico : [</font>
                        <font face="Wingdings, serif">
                           <font size="2" style="font-size: 11pt"></font>
                        </font>
                        <font size="2" style="font-size: 11pt">]</font>
                     </font>
                  </p>
                  <p lang="es-ES" align="justify" style="margin-top: 0.17in; margin-bottom: 0.08in; line-height: 115%; orphans: 0; widows: 0">
                     <font size="1" style="font-size: 8pt">
                        <font size="2" style="font-size: 11pt">Con
                           copia a:</font>
                     </font>
                  </p>
                  <p lang="es-ES" align="justify" style="line-height: 115%; orphans: 0; widows: 0">
                     <font size="1" style="font-size: 8pt">
                        <font size="2" style="font-size: 11pt">Atención :
                           [</font>
                        <font face="Wingdings, serif">
                           <font size="2" style="font-size: 11pt"></font>
                        </font>
                        <font size="2" style="font-size: 11pt">]</font>
                     </font>
                  </p>
                  <p lang="es-ES" align="justify" style="line-height: 115%; orphans: 0; widows: 0">
                     <font size="1" style="font-size: 8pt">
                        <font size="2" style="font-size: 11pt">Correo
                           electrónico : [</font>
                        <font face="Wingdings, serif">
                           <font size="2" style="font-size: 11pt"></font>
                        </font>
                        <font size="2" style="font-size: 11pt">]</font>
                     </font>
                  </p>
                  <p lang="es-ES" align="justify" style="margin-top: 0.17in; margin-bottom: 0.08in; line-height: 115%; orphans: 0; widows: 0">
                     <font size="1" style="font-size: 8pt">
                        <font size="2" style="font-size: 11pt">Se
                           tendrá por notificada a la parte destinataria: (i) el día en que se
                           hubiese recibido la carta por el destinatario, constando lo anterior
                           con timbre de recibo o firma y nombre de la persona que recibió la
                           carta, (ii) el día en que se envía el correo electrónico a la(s)
                           dirección(es) señaladas en el presente Contrato; o (iii) el tercer
                           día hábil siguiente de su envió en caso de que se trate de una
                           carta certificada.</font>
                     </font>
                  </p>
                  <p lang="es-ES" align="justify" style="margin-top: 0.17in; line-height: 115%; orphans: 0; widows: 0">
                     <font size="1" style="font-size: 8pt">
                        <font size="2" style="font-size: 11pt">Las
                           Partes deberán informarse por escrito de cualquier cambio de
                           domicilio que tuvieren y, en caso de no hacerlo, los avisos y
                           notificaciones de cualquier índole que dirijan al último domicilio
                           indicado surtirán todos los efectos legales a que haya lugar. </font>
                     </font>
                  </p>
                  <p class="western" style="margin-top: 0.17in; line-height: 115%">
                     <font size="2" style="font-size: 11pt"><u><b>DÉCIMO
                              CUARTO</b></u></font>
                     <font size="2" style="font-size: 11pt"><b>:
                           ANEXOS</b></font>
                  </p>
                  <p class="western" style="margin-top: 0.17in; line-height: 115%">
                     <font size="2" style="font-size: 11pt">El
                        presente Contrato incluye los siguientes Anexos, los que se entienden
                        formar parte integrante del mismo para todos los efectos legales: </font>
                  </p>
                  <p class="western" style="line-height: 115%">
                     <font size="2" style="font-size: 11pt"><b>Anexo
                           Nº1</b></font>
                     <font size="2" style="font-size: 11pt">: Detalles y
                        condiciones específicas de los Servicios</font>
                  </p>
                  <p class="western" style="line-height: 115%">
                     <font size="2" style="font-size: 11pt"><b>Anexo
                           Nº2</b></font>
                     <font size="2" style="font-size: 11pt">: Precio de los
                        Servicios</font>
                  </p>
                  <p class="western" style="line-height: 115%">
                     <font size="2" style="font-size: 11pt"><b>Anexo
                           Nº3: </b></font>
                     <font size="2" style="font-size: 11pt"><span lang="es-ES">Arrendamiento
                           con opción de compra </span></font>
                  </p>
                  <p class="western" style="margin-top: 0.17in; line-height: 115%">
                     <font size="2" style="font-size: 11pt"><u><b>DÉCIMO
                              QUINTO</b></u></font>
                     <font size="2" style="font-size: 11pt"><b>:
                           EJEMPLARES. </b></font>
                  </p>
                  <p class="western" style="margin-top: 0.17in; line-height: 115%">
                     <font size="2" style="font-size: 11pt">El
                        presente Contrato se otorga en dos ejemplares quedando uno en poder
                        de cada parte.</font>
                  </p>
                  <p class="western" style="margin-top: 0.17in; line-height: 115%"><br />

                  </p>
                  <center>
                     <table width="619" cellpadding="5" cellspacing="0">
                        <col width="299" />

                        <col width="299" />

                        <tr valign="top">
                           <td width="299" style="border: none; padding: 0in">
                              <p class="western" align="center" style="orphans: 0; widows: 0">
                                 <br />

                              </p>
                              <p lang="en-US" class="western" align="center" style="orphans: 0; widows: 0">
                                 <font size="2" style="font-size: 11pt">___________________________________</font>
                              </p>
                              <p lang="es-ES" class="western" align="center" style="orphans: 0; widows: 0">
                                 <font size="2" style="font-size: 11pt"><span lang="en-US">[</span></font>
                                 <font face="Wingdings, serif">
                                    <font size="2" style="font-size: 11pt"></font>
                                 </font>
                                 <font size="2" style="font-size: 11pt"><span lang="en-US">]</span></font>
                              </p>
                              <p lang="en-US" class="western" align="center" style="orphans: 0; widows: 0">
                                 <font size="2" style="font-size: 11pt">pp. Voissnet Cloud
                                    Services SpA</font>
                              </p>
                              <p lang="en-US" class="western" align="center" style="orphans: 0; widows: 0">
                                 <br />

                              </p>
                           </td>
                           <td width="299" style="border: none; padding: 0in">
                              <p lang="en-US" class="western" align="center" style="orphans: 0; widows: 0">
                                 <br />

                              </p>
                              <p class="western" align="center" style="orphans: 0; widows: 0">
                                 <font size="2" style="font-size: 11pt">___________________________________</font>
                              </p>
                              <p lang="es-ES" class="western" align="center" style="orphans: 0; widows: 0">
                                 <font size="2" style="font-size: 11pt">[</font>
                                 <font face="Wingdings, serif">
                                    <font size="2" style="font-size: 11pt"></font>
                                 </font>
                                 <font size="2" style="font-size: 11pt">]</font>
                              </p>
                              <p lang="es-ES" class="western" align="center" style="orphans: 0; widows: 0">
                                 <font size="2" style="font-size: 11pt">pp. [Cliente]</font>
                              </p>
                              <p class="western" align="center" style="orphans: 0; widows: 0"><br />

                              </p>
                           </td>
                        </tr>
                     </table>
                  </center>
                  <p class="western" align="center" style="line-height: 115%"><br />

                  </p>
                  <p class="western" align="left" style="margin-bottom: 0.14in; line-height: 115%">
                     <br />
                     <br />

                  </p>
                  <p lang="es-ES" class="western" align="center" style="line-height: 115%; page-break-before: always">
                     <font size="2" style="font-size: 11pt"><span lang="es-CL"><b>ANEXO 1:
                              DETALLE Y CONDICIONES ESPECÍFICAS.</b></span></font>
                  </p>
                  <p class="western" align="justify" style="line-height: 115%"><br />

                  </p>
                  <ol>
                     <li>
                        <p lang="es-ES" align="justify" style="line-height: 115%">
                           <font size="2" style="font-size: 11pt"><span lang="es-CL"><b>Descripción
                                    y funcionamiento de “Protégeme” </b></span></font>
                        </p>
                  </ol>
                  <p class="western" align="justify" style="margin-left: 0.25in; line-height: 115%">
                     <br />

                  </p>
                  <p lang="es-ES" class="western" align="justify" style="margin-left: 0.25in; line-height: 115%">
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">Protégeme</span></font>
                     <font size="2" style="font-size: 11pt">
                        es un servicio para Organizaciones consistente en una plataforma
                     </font>
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">diseñada
                           para disminuir los tiempos de respuesta y aumentar las posibilidades
                           de acción ante situaciones de peligro y emergencias. Los servicios
                           provistos a través de la plataforma buscan fortalecer la respuesta
                           comunitaria, creando una red de apoyo colaborativa y efectiva entre
                           los miembros de una misma </span></font>
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">comunidad.</span></font>
                  </p>
                  <p lang="es-ES" class="western" align="justify" style="margin-left: 0.25in; line-height: 115%">
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">En este
                           sentido, Protégeme permite a un cliente (empresa, condominio y
                           organizaciones en general) entregar a las personas de su comunidad,
                           un medio de generar alarmas con localización, y establecer
                           simultáneamente una llamada a un centro de respuestas, provisto por
                           el cliente, </span></font>
                  </p>
                  <p style="margin-left: 0.3in; margin-right: 0.04in; margin-top: 0.08in; margin-bottom: 0.14in; line-height: 0.22in">
                     <font color="#808080">
                        <font face="Calibri, serif">
                           <font size="2" style="font-size: 11pt">
                              <font color="#000000">
                                 <font face="Times New Roman, serif">¿Cómo
                                    funciona? Cuando exista una emergencia y se presiona el botón de
                                    alarma (a través del Dispositivo o Aplicación) se genera una
                                    llamada automática la que será recibida en un sistema de recepción
                                    de llamadas, previamente habilitado por el Cliente, el que estará
                                    destinado a la atención de las emergencias y desde donde se
                                    coordinarán y realizarán las acciones que estas requieran.
                                    Simultáneamente, al ingresar la llamada a este sistema, se activarán
                                    de forma automática, las siguientes acciones, según defina el
                                    administrador:</font>
                              </font>
                           </font>
                        </font>
                     </font>
                  </p>
                  <p style="margin-left: 0.3in; margin-right: 0.04in; margin-top: 0.08in; margin-bottom: 0.14in; line-height: 0.22in">
                     <font color="#808080">
                        <font face="Calibri, serif">
                           <font size="2" style="font-size: 11pt">
                              <font color="#000000">
                                 <font face="Times New Roman, serif">1.
                                    Llamadas con un mensaje de voz pregrabado a contactos preestablecidos
                                    por el usuario informando que en el sitio (en caso de activarse el
                                    botón fijo) o la persona (en caso de activarse el botón por la
                                    Aplicación) tiene una emergencia.</font>
                              </font>
                           </font>
                        </font>
                     </font>
                  </p>
                  <p style="margin-left: 0.3in; margin-right: 0.04in; margin-top: 0.08in; margin-bottom: 0.14in; line-height: 0.22in">
                     <font color="#808080">
                        <font face="Calibri, serif">
                           <font size="2" style="font-size: 11pt">
                              <font color="#000000">
                                 <font face="Times New Roman, serif">2.
                                    A continuación, un contacto predefinido también por el Usuario
                                    puede escuchar la conversación telefónica que se generó entre el
                                    Usuario quien presionó el botón y el sistema de recepción de
                                    llamadas habilitado al efecto.</font>
                              </font>
                           </font>
                        </font>
                     </font>
                  </p>
                  <p style="margin-left: 0.3in; margin-right: 0.04in; margin-top: 0.08in; margin-bottom: 0.14in; line-height: 0.22in">
                     <font color="#808080">
                        <font face="Calibri, serif">
                           <font size="2" style="font-size: 11pt">
                              <font color="#000000">
                                 <font face="Times New Roman, serif">3.
                                    Envío automático de mensajes de texto a una lista de contactos
                                    preestablecidos por el Usuario, con un texto informando de la
                                    emergencia, el nombre del Usuario quien la gatilló y su ubicación
                                    al momento de la llamada. La ubicación del lugar de la emergencia se
                                    entrega en el mismo mensaje de texto a través de un link que lleva
                                    al mapa del lugar de la emergencia.</font>
                              </font>
                           </font>
                        </font>
                     </font>
                  </p>
                  <p style="margin-left: 0.3in; margin-right: 0.04in; margin-top: 0.08in; margin-bottom: 0.14in; line-height: 0.22in">
                     <font color="#808080">
                        <font face="Calibri, serif">
                           <font size="2" style="font-size: 11pt">
                              <font color="#000000">
                                 <font face="Times New Roman, serif">Las
                                    Partes, de mutuo acuerdo, podrán definir servicios adicionales o la
                                    incorporación de nuevos Dispositivos, ad hoc a requerimientos
                                    específicos necesarios y solicitados por el Cliente.</font>
                              </font>
                           </font>
                        </font>
                     </font>
                  </p>
                  <p align="justify" style="margin-left: 0.5in; line-height: 115%"><br />

                  </p>
                  <ol start="2">
                     <li>
                        <p lang="es-ES" align="justify" style="line-height: 115%">
                           <font size="2" style="font-size: 11pt"><b>Condiciones
                                 Especiales para la Prestación de los Servicios.</b></font>
                        </p>
                  </ol>
                  <p class="western" align="justify" style="line-height: 115%"><br />

                  </p>
                  <p lang="es-ES" class="western" align="justify" style="margin-left: 0.45in; line-height: 115%">
                     <font size="2" style="font-size: 11pt">El Cliente declara
                        expresamente que, en conocimiento de las disposiciones del Contrato
                        y, en especial, de lo establecido en este párrafo, acepta que el
                        suministro de los Servicios antes descritos está sujeto a las
                        condiciones y limitaciones que a continuación se expresan, las
                        cuales, dada su especial naturaleza, entiende y acepta como
                        intrínsecas y de la esencia en la prestación del mismo:</font>
                  </p>
                  <p lang="es-ES" class="western" align="justify" style="line-height: 115%">
                     <br />

                  </p>
                  <ol type="a">
                     <li>
                        <p lang="es-ES" class="western" align="justify" style="line-height: 115%">
                           <font size="2" style="font-size: 11pt">Dado que el Servicio supone
                              la utilización de canales de comunicación cuya propiedad,
                              operación y control están fuera del manejo de VCS, no se
                              garantiza, asegura, ni se obliga a que los Servicios y aquellos
                              servicios conexos prestados a través de la Aplicación y/o
                              Dispositivos, no sufran interrupciones, suspensiones o defectos
                              (tales como distorsiones de sonido, interferencias, variaciones en
                              la calidad de la voz), ni garantiza que éstos vayan a ser
                              corregidos.</font>
                        </p>
                  </ol>
                  <p lang="es-ES" class="western" align="justify" style="margin-left: 0.79in; line-height: 115%">
                     <br />

                  </p>
                  <ol type="a" start="2">
                     <li>
                        <p lang="es-ES" align="justify" style="line-height: 115%">
                           <font size="2" style="font-size: 11pt">VCS
                              no se hace responsable de la calidad de la comunicación ni de
                              desperfectos provocados, entre otros, por tiempos de latencia que se
                              produzcan por exceso de tráfico en la red Internet, deficiente
                              calidad de la voz o deficiencias de la conexión al ISP del Cliente.
                           </font>
                        </p>
                  </ol>
                  <p lang="es-ES" align="justify" style="margin-left: 0.5in"><br />

                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.5in"><br />

                  </p>
                  <ol type="a" start="3">
                     <li>
                        <p lang="es-ES" class="western" align="justify" style="line-height: 115%">
                           <font size="2" style="font-size: 11pt">Es de exclusiva
                              responsabilidad del Cliente y de los Usuarios la conexión,
                              configuración, administración y seguridad de sus redes y su acceso
                              a Internet. Además, para una correcta provisión de los Servicios y
                              de los servicios conexos prestados a través de la Plataforma, es
                              requisito indispensable que el Cliente y los Usuarios cuenten con
                              acceso a Internet, autoricen en sus Dispositivos la posibilidad de
                              compartir su ubicación georreferenciada, su agenda de contacto,
                              entre otros, según se detalla en los Términos y Condiciones de la
                              Aplicación y/o Dispositivos.</font>
                        </p>
                  </ol>
                  <p lang="es-ES" class="western" align="justify" style="margin-left: 0.79in; line-height: 115%">
                     <br />

                  </p>
                  <ol type="a" start="4">
                     <li>
                        <p lang="es-ES" class="western" align="justify" style="line-height: 115%">
                           <font size="2" style="font-size: 11pt">Para una adecuada prestación
                              de los Servicios el Cliente deberá contar previamente con un
                              sistema de recepción de llamadas</font>
                           <font size="2" style="font-size: 11pt"><i>
                              </i></font>
                           <font size="2" style="font-size: 11pt">para la atención
                              a Usuarios, el que deberá contar protocolos de funcionamiento
                              adecuado y con la capacidad necesaria para verificar, atender, y
                              derivar la emergencia a los sistemas de ayuda. </font>
                        </p>
                  </ol>
                  <p lang="es-ES" align="justify" style="margin-left: 0.5in"><br />

                  </p>
                  <ol type="a" start="5">
                     <li>
                        <p lang="es-ES" class="western" align="justify" style="line-height: 115%">
                           <font size="2" style="font-size: 11pt">El Cliente debe cumplir con
                              los requerimientos técnicos de la solución, para su correcto
                              despliegue y funcionamiento:</font>
                        </p>
                  </ol>
                  <p lang="es-ES" align="justify" style="margin-left: 0.5in"><br />

                  </p>
                  <p lang="es-ES" class="western" align="justify" style="line-height: 115%">
                     <br />

                  </p>
                  <ul>
                     <li>
                        <p lang="es-ES" align="justify" style="line-height: 115%">
                           <font size="2" style="font-size: 11pt">Requerimientos
                              de Red Física</font>
                        </p>
                  </ul>
                  <p lang="es-ES" class="western" align="justify" style="margin-left: 0.7in; line-height: 115%">
                     <br />

                  </p>
                  <p lang="es-ES" class="western" align="justify" style="margin-left: 0.45in; line-height: 115%">
                     <font size="2" style="font-size: 11pt">Se debe disponer de puntos
                        conectividad de red IP (privada o internet) para cada dispositivo
                        físico ya sea cableado o WiFi de calidad, de acuerdo a lo que exija
                        cada uno de ellos.</font>
                  </p>
                  <p lang="es-ES" class="western" align="justify" style="margin-left: 0.45in; line-height: 115%">
                     <br />

                  </p>
                  <p lang="es-ES" class="western" align="justify" style="margin-left: 0.45in; line-height: 115%">
                     <font size="2" style="font-size: 11pt">Los dispositivos físicos
                        requieren ser instalados en un lugar con espacio y facilidades de
                        acuerdo a su tipo.</font>
                  </p>
                  <p lang="es-ES" class="western" align="justify" style="margin-left: 0.45in; line-height: 115%">
                     <br />

                  </p>
                  <p lang="es-ES" class="western" align="justify" style="margin-left: 0.45in; line-height: 115%">
                     <font size="2" style="font-size: 11pt">Los equipos físicos requieren
                        de energía eléctrica en el punto de instalación y a no más de 1
                        metro en el equipamiento de interior. En el caso de sirenas IP,
                        además es necesario que tengan acceso Internet cableado.</font>
                  </p>
                  <p lang="es-ES" class="western" align="justify" style="margin-left: 0.45in; line-height: 115%">
                     <br />

                  </p>
                  <p lang="es-ES" class="western" align="justify" style="margin-left: 0.45in; line-height: 115%">
                     <font size="2" style="font-size: 11pt">Se debe contar con la
                        administración del router y/o firewall de la red LAN/WAN para poder
                        hacer los cambios necesarios y dar la conectividad necesaria.</font>
                  </p>
                  <p lang="es-ES" class="western" align="justify" style="margin-left: 0.45in; line-height: 115%">
                     <br />

                  </p>
                  <ul>
                     <li>
                        <p lang="es-ES" align="justify" style="line-height: 115%">
                           <font size="2" style="font-size: 11pt">Requerimientos
                              para el Botón Móvil</font>
                        </p>
                  </ul>
                  <p lang="es-ES" align="justify" style="margin-left: 0.95in; line-height: 115%">
                     <br />

                  </p>
                  <p lang="es-ES" class="western" align="justify" style="margin-left: 0.45in; line-height: 115%">
                     <font size="2" style="font-size: 11pt">La aplicación de Botón de
                        emergencia ofrecida por VCS opera en los siguientes sistemas
                        operativos </font>
                  </p>
                  <p lang="es-ES" class="western" align="justify" style="margin-left: 0.45in; line-height: 115%">
                     <font size="2" style="font-size: 11pt">iOS (Móvil): Versión 16 o
                        superior y que esté soportada por la aplicación </font>
                  </p>
                  <p lang="es-ES" class="western" align="justify" style="margin-left: 0.45in; line-height: 115%">
                     <font size="2" style="font-size: 11pt">Android (Móvil): Versión 7 o
                        superior y que esté soportada por la aplicación</font>
                  </p>
                  <p lang="es-ES" class="western" align="justify" style="margin-left: 0.45in; line-height: 115%">
                     <font size="2" style="font-size: 11pt">Se requiere una conexión a
                        internet constante (WiFi, 3G o 4G o 5G)</font>
                  </p>
                  <p lang="es-ES" class="western" align="justify" style="margin-left: 0.45in; line-height: 115%">
                     <br />

                  </p>
                  <p lang="es-ES" class="western" align="justify" style="margin-left: 0.45in; line-height: 115%">
                     <font size="2" style="font-size: 11pt">La solución ofrecida operará
                        sobre enlaces de internet inalámbrica (WIFI, 3G, 4G o 5G) en el
                        lugar donde se encuentre el usuario final. Estos enlaces están
                        supeditados a las fluctuaciones e inestabilidades propias de
                        internet. VCS no puede garantizar la calidad del servicio Internet.
                        Por lo tanto, se recomienda operar el servicio desde accesos estables
                        y con capacidades de transmisión que permitan la correcta operación
                        del servicio.</font>
                  </p>
                  <p class="western" align="justify" style="line-height: 115%"><br />

                  </p>
                  <ol start="3">
                     <li>
                        <p lang="es-ES" align="justify" style="line-height: 115%">
                           <font size="2" style="font-size: 11pt"><span lang="es-CL"><b>Prohibiciones
                                 </b></span></font>
                        </p>
                  </ol>
                  <p class="western" align="justify" style="line-height: 115%"><br />

                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.5in; line-height: 115%">
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">En caso que
                           el Cliente cuente con equipamiento en arriendo, éste no podrá
                           subarrendar los equipos sin autorización previa y por escrito de
                           VCS. Asimismo, el Cliente no podrá comercializar, vender ni exportar
                           el o los Equipos entregados.</span></font>
                  </p>
                  <p align="justify" style="margin-left: 0.5in; line-height: 115%"><br />

                  </p>
                  <p lang="es-ES" class="western" align="justify" style="line-height: 115%">
                     <br />

                  </p>
                  <p class="western" align="justify" style="line-height: 115%"><br />

                  </p>
                  <p class="western" align="justify" style="line-height: 115%"><br />

                  </p>
                  <p align="justify" style="margin-left: 0.75in; line-height: 115%"><br />

                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; line-height: 115%">
                     <br />

                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; line-height: 115%">
                     <br />

                  </p>
                  <p lang="es-ES" class="western" align="justify" style="margin-bottom: 0.14in; line-height: 115%">
                     <br />
                     <br />

                  </p>
                  <p align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in; line-height: 115%; page-break-before: always">
                     <br />
                     <br />

                  </p>
                  <p lang="es-ES" align="center" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in; line-height: 115%">
                     <font size="2" style="font-size: 11pt"><span lang="es-CL"><u><b>ANEXO
                                 2: PRECIO DE LOS SERVICIOS</b></u></span></font>
                  </p>
                  <p class="western" style="line-height: 115%">
                     <font size="2" style="font-size: 11pt">El
                        Precio se detalla según el cuadro a continuación:</font>
                  </p>
                  <p class="western" style="line-height: 115%"><br />

                  </p>
                  <center>
                     <table width="575" cellpadding="7" cellspacing="0">
                        <colgroup>
                           <col width="12" />

                        </colgroup>
                        <colgroup>
                           <col width="162" />

                           <col width="96" />

                           <col width="248" />

                        </colgroup>
                        <tr>
                           <td colspan="4" width="559" height="29" valign="top" bgcolor="#d9d9d9" style="background: #d9d9d9; border: 1px solid #000000; padding: 0in 0.08in">
                              <p lang="es-ES" class="western" align="justify" style="orphans: 2; widows: 2">
                                 <font size="2" style="font-size: 11pt"><b>SERVICIO CONTRATADO:<br />
                                    </b></font><br />

                              </p>
                           </td>
                        </tr>
                        <tr>
                           <td width="12" height="30" bgcolor="#ffffff" style="background: #ffffff; border: 1px solid #000000; padding: 0in 0.08in">
                              <p lang="es-ES" class="western" align="justify" style="margin-top: 0.13in; orphans: 2; widows: 2">
                                 <font color="#2f2b23">
                                    <font size="2" style="font-size: 11pt"><b>1</b></font>
                                 </font>
                              </p>
                           </td>
                           <td colspan="2" width="271" bgcolor="#ffffff" style="background: #ffffff; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: none; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0in">
                              <p lang="es-ES" class="western" align="justify" style="orphans: 2; widows: 2">
                                 <font size="2" style="font-size: 11pt">Habilitación y
                                    Configuración Inicial</font>
                              </p>
                           </td>
                           <td width="248" bgcolor="#ffffff" style="background: #ffffff; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: none; border-right: 1px solid #000000; padding-top: 0in; padding-bottom: 0in; padding-left: 0in; padding-right: 0.08in">
                              <p lang="es-ES" class="western" align="justify" style="orphans: 2; widows: 2">
                                 <font size="2" style="font-size: 11pt">Monto $.-</font>
                              </p>
                           </td>
                        </tr>
                        <tr>
                           <td width="12" height="46" bgcolor="#ffffff" style="background: #ffffff; border: 1px solid #000000; padding: 0in 0.08in">
                              <p lang="es-ES" class="western" align="justify" style="margin-top: 0.13in; orphans: 2; widows: 2">
                                 <font color="#2f2b23">
                                    <font size="2" style="font-size: 11pt"><b>2</b></font>
                                 </font>
                              </p>
                           </td>
                           <td width="162" bgcolor="#ffffff" style="background: #ffffff; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: none; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0in">
                              <p lang="es-ES" class="western" align="justify" style="orphans: 2; widows: 2">
                                 <font size="2" style="font-size: 11pt">Cargo Fijo mensual </font>
                              </p>
                           </td>
                           <td width="96" bgcolor="#ffffff" style="background: #ffffff; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: none; border-right: none; padding: 0in">
                              <p lang="es-ES" class="western" align="justify" style="orphans: 2; widows: 2">
                                 <img src="casilla.gif" name="Shape1" alt="Shape1" align="left" hspace="12" vspace="1">
                                 <br clear="left" />
                                 </img>
                                 <spacer type="block" align="left" width="58" height="39">
                                    <font size="2" style="font-size: 11pt">Nº
                                       Botones</font>
                              </p>
                           </td>
                           <td width="248" bgcolor="#ffffff" style="background: #ffffff; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: none; border-right: 1px solid #000000; padding-top: 0in; padding-bottom: 0in; padding-left: 0in; padding-right: 0.08in">
                              <p lang="es-ES" class="western" align="justify" style="orphans: 2; widows: 2">
                                 <font size="2" style="font-size: 11pt">Monto $.- </font>
                              </p>
                           </td>
                        </tr>
                        <tr>
                           <td width="12" height="29" bgcolor="#ffffff" style="background: #ffffff; border: 1px solid #000000; padding: 0in 0.08in">
                              <p lang="es-ES" class="western" align="justify" style="margin-top: 0.13in; orphans: 2; widows: 2">
                                 <font color="#2f2b23">
                                    <font size="2" style="font-size: 11pt"><b>3</b></font>
                                 </font>
                              </p>
                           </td>
                           <td width="162" bgcolor="#ffffff" style="background: #ffffff; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: none; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0in">
                              <p lang="es-ES" class="western" align="justify" style="orphans: 2; widows: 2">
                                 <font size="2" style="font-size: 11pt">Opcional: Arriendo de
                                    Equipamiento</font>
                              </p>
                           </td>
                           <td width="96" bgcolor="#ffffff" style="background: #ffffff; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: none; border-right: none; padding: 0in">
                              <p lang="es-ES" class="western" align="justify" style="orphans: 2; widows: 2">
                                 <br />

                              </p>
                           </td>
                           <td width="248" bgcolor="#ffffff" style="background: #ffffff; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: none; border-right: 1px solid #000000; padding-top: 0in; padding-bottom: 0in; padding-left: 0in; padding-right: 0.08in">
                              <p lang="es-ES" class="western" align="justify" style="orphans: 2; widows: 2">
                                 <font size="2" style="font-size: 11pt">Monto $_____________</font>
                              </p>
                           </td>
                        </tr>
                     </table>
                  </center>
                  <p class="western" style="line-height: 115%"><br />

                  </p>
                  <p class="western" style="line-height: 115%"><br />

                  </p>
                  <p class="western" style="line-height: 115%">
                     <font size="2" style="font-size: 11pt">Los
                        Precios no incluyen el Impuesto al Valor Agregado (I.V.A.). </font>
                  </p>
                  <p class="western" style="line-height: 115%"><br />

                  </p>
                  <p class="western" style="line-height: 115%">
                     <font size="2" style="font-size: 11pt">La
                        garantía de equipamiento por defectos de Fabricación: Sobre fallas
                        atribuibles a defectos de material o mano de obra en su fabricación.
                        Excluye fallas atribuibles a terceros producto de instalaciones
                        eléctricas defectuosas, sin malla de tierra y otros accesorios no
                        autorizados por la reglamentación eléctrica vigente, norma chilena
                        NCH 84 </font>
                  </p>
                  <p class="western" style="line-height: 115%"><br />

                  </p>
                  <p class="western" style="line-height: 115%">
                     <font size="2" style="font-size: 11pt">Conversión
                        de la Moneda: Si el precio estipulado está en Unidades de Fomento,
                        se convertirá en Pesos, moneda nacional, de acuerdo con el valor de
                        la UF publicado en el diario oficial a la fecha de emisión de la
                        factura. Si el precio estipulado está en Dólares Norteamericanos,
                        se convertirá en Pesos, moneda nacional, de acuerdo con el tipo de
                        cambio del Dólar Observado a la fecha de emisión de la factura.</font>
                  </p>
                  <p align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in; line-height: 115%">
                     <br />
                     <br />

                  </p>
                  <p class="western" align="justify" style="margin-bottom: 0.14in; line-height: 115%">
                     <br />
                     <br />

                  </p>
                  <p class="western" align="center" style="page-break-before: always"><br />

                  </p>
                  <p lang="es-ES" class="western" align="center">
                     <font size="2" style="font-size: 11pt"><span lang="es-CL"><b>ANEXO
                              N°3 : </b></span></font>
                     <font size="2" style="font-size: 11pt"><b>CONTRATO
                           DE ARRENDAMIENTO CON OPCIÓN DE COMPRA DE</b></font>
                     <font size="2" style="font-size: 11pt"><span lang="es-CL"><b>
                              DISPOSITIVOS</b></span></font>
                  </p>
                  <p class="western" align="justify"><br />

                  </p>
                  <p lang="es-ES" class="western" align="justify" style="margin-top: 0.17in; margin-bottom: 0.17in">
                     <font size="2" style="font-size: 11pt"><span lang="es-CL"><u><b>PRIMERO</b></u></span></font>
                     <font size="2" style="font-size: 11pt"><span lang="es-CL"><b>:
                              OBJETO. </b></span></font>
                  </p>
                  <p lang="es-ES" class="western" align="justify" style="margin-top: 0.17in; margin-bottom: 0.17in">
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">El Cliente
                           es suscriptor de los Servicios ofrecidos por VCS, según se señala
                           en el C</span></font>
                     <font size="2" style="font-size: 11pt">ontrato
                        de prestación de servicios. </font>
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">De
                           conformidad a lo establecido en dicho Contrato, y para la adecuada
                           prestación de los Servicios contratados, el Cliente ha solicitado a
                           VCS que le provea de los equipos necesarios para hacer uso de los
                           Servicios. </span></font>
                  </p>
                  <p lang="es-ES" class="western" align="justify" style="margin-top: 0.17in; margin-bottom: 0.17in">
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">Por lo
                           anterior, VCS</span></font>
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">
                           entrega en arrendamiento al</span></font>
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">
                           Cliente </span></font>
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">el
                           (los) equipo(s) de comunicaciones y accesorios que se lista(n) en el
                        </span></font>
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">Anexo
                           3-A (</span></font>
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">“</span></font>
                     <font size="2" style="font-size: 11pt"><span lang="es-CL"><u>Equipos</u></span></font>
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">”)
                           y cada uno de ellos en particular el “</span></font>
                     <font size="2" style="font-size: 11pt"><span lang="es-CL"><u>Equipo</u></span></font>
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">”.
                           El Anexo 3-A podrá ser modificado de tiempo en tiempo, a medida que
                           el Cliente solicite a VCS la contratación de nuevos Equipos o
                           requiera reemplazar Equipos ya contratados en caso de será aplicable
                           la garantía establecida en la cláusula novena. Para la contratación
                           y/o reemplazo de Equipos deberá suscribirse por las Partes un nuevo
                           Anexo 3-A. </span></font>
                  </p>
                  <p lang="es-ES" class="western" align="justify" style="margin-top: 0.17in; margin-bottom: 0.17in">
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">Las Partes
                           dejan constancia que los Equipos son de propiedad de VCS y son
                           entregados al Cliente en arriendo, exclusivamente para el uso de los
                           Servicios. El Cliente declara recibir a su entera conformidad los
                           Equipos, reconociendo que se trata de Equipos actualmente funcionando
                           en óptimas condiciones y que no presentan daño alguno.</span></font>
                  </p>
                  <p lang="es-ES" class="western" align="justify" style="margin-top: 0.17in; margin-bottom: 0.17in">
                     <font size="2" style="font-size: 11pt"><span lang="es-CL"><u><b>SEGUNDO</b></u></span></font>
                     <font size="2" style="font-size: 11pt"><span lang="es-CL"><b>:
                              DURACIÓN. </b></span></font>
                  </p>
                  <p lang="es-ES" class="western" align="justify" style="margin-top: 0.17in; margin-bottom: 0.17in">
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">El presente
                           contrato tendrá una duración indicada en el Anexo 3-A por cada uno
                           de los Equipos contratados. Transcurrido este plazo, el Cliente
                           tendrá la facultad de elegir una de las opciones señaladas en la
                           cláusula décima siguiente.</span></font>
                  </p>
                  <p lang="es-ES" class="western" align="justify" style="margin-top: 0.17in; margin-bottom: 0.17in">
                     <font size="2" style="font-size: 11pt"><span lang="es-CL"><u><b>TERCERO:</b></u></span></font>
                     <font size="2" style="font-size: 11pt"><span lang="es-CL"><b>
                              PRECIO. </b></span></font>
                  </p>
                  <p lang="es-ES" class="western" align="justify" style="margin-top: 0.17in; margin-bottom: 0.17in">
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">La renta
                           mensual de este contrato será la suma de las rentas definidas para
                           cada Equipo arrendado, a la que se agregará el correspondiente
                           Impuesto al Valor Agregado (“IVA”). </span></font>
                  </p>
                  <p lang="es-ES" class="western" align="justify" style="margin-top: 0.17in; margin-bottom: 0.17in">
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">El monto de
                           la renta mensual deberá ser pagada por el Cliente a VCS, dentro de
                           los 30 días siguientes a la emisión de la correspondiente factura,
                           la cual incluirá tanto la renta mensual de arrendamiento como los
                           cargos mensuales que puedan aplicar por los Servicios y se emitirá
                           dentro de los 5 primeros días de cada mes.</span></font>
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">
                           Las rentas de arrendamiento antes señaladas podrán ser modificadas
                           de tiempo en tiempo, según se vayan modificando los</span></font>
                     <font size="2" style="font-size: 11pt"><span lang="es-CL"><b>
                           </b></span></font>
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">Equipos.</span></font>
                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <font size="2" style="font-size: 11pt">Adicionalmente, en el caso en
                        que la renta mensual de los Equipos se encuentre en pesos, podrán
                        ser </font>
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">reajustados
                           semestralmente, los días 1° de Enero y 1° de Julio de cada año,
                           según la variación experimentada por el IPC en los seis meses
                           inmediatamente anteriores a la fecha del reajuste.</span></font>
                  </p>
                  <p align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <br />
                     <br />

                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">Sin
                           perjuicio de lo dispuesto en la cláusula décima, el Cliente en
                           cualquier momento durante la vigencia del presente contrato podrá
                           comprar cualquiera de los Equipos, para lo cual por este acto VCS le
                           formula una oferta irrevocable de venta. El precio de cada uno de los
                           Equipos en caso de ejercerse dicha opción, será equivalente al
                           monto de todas las rentas que se encuentren pendientes respecto de
                           ese Equipo hasta completar el período de vigencia en curso del
                           Contrato. En caso que se ejerza la opción de compra respecto de
                           algunos de los Equipos, el contrato se mantendrá vigente respecto
                           del resto de ellos.</span></font>
                  </p>
                  <p align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <br />
                     <br />

                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <font size="2" style="font-size: 11pt"><span lang="es-CL"><u><b>CUARTO</b></u></span></font>
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">:
                        </span></font>
                     <font size="2" style="font-size: 11pt"><span lang="es-CL"><b>TERMINACIÓN
                              ANTICIPADA DEL CONTRATO Y MULTA ASOCIADA</b></span></font>
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">.</span></font>
                  </p>
                  <p align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <br />
                     <br />

                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">VCS podrá
                           poner término anticipado contrato de arrendamiento de Equipos de
                           forma inmediata y sin necesidad de declaración judicial en caso de
                           que ocurra alguna de las siguientes situaciones: </span></font>
                  </p>
                  <p align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <br />
                     <br />

                  </p>
                  <ol type="a">
                     <li>
                        <p lang="es-ES" align="justify" style="margin-top: 0.17in; margin-bottom: 0.17in">
                           <font size="2" style="font-size: 11pt"><span lang="es-CL">Incumplimiento
                                 por parte del Cliente de cualquier obligación asumida en virtud del
                                 contrato de arrendamiento de Equipos.</span></font>
                        </p>
                     <li>
                        <p lang="es-ES" align="justify" style="margin-top: 0.17in; margin-bottom: 0.17in">
                           <font size="2" style="font-size: 11pt"><span lang="es-CL">Si el
                                 Cliente no tuviere fondos suficientes para cubrir los cargos
                                 efectuados por las rentas de arrendamiento por dos meses
                                 consecutivos, en el caso de la Modalidad Prepago, o si el Cliente no
                                 paga oportunamente dos facturas consecutivas emitidas por VCS, en el
                                 caso de la modalidad Postpago.</span></font>
                        </p>
                     <li>
                        <p lang="es-ES" align="justify" style="margin-top: 0.17in; margin-bottom: 0.17in">
                           <font size="2" style="font-size: 11pt"><span lang="es-CL">En caso de
                                 término del Contrato, cualquiera sea la causa de terminación.</span></font>
                        </p>
                  </ol>
                  <p align="justify" style="margin-left: 0.53in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <br />
                     <br />

                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">En caso que
                           VCS ponga término anticipado al Contrato por las causales indicadas
                           anteriormente, el Cliente deberá restituir los Equipos a más tardar
                           dentro de los 5 días hábiles siguientes. En caso que el Cliente
                           este ubicado fuera de la Región Metropolitana, dicho plazo se
                           extenderá a 10 días hábiles. </span></font>
                     <font size="2" style="font-size: 11pt">En
                        caso de incumplimiento de esta obligación por parte del Cliente, VCS
                        podrá retirar los Equipos del lugar en que se encuentren para
                        trasladarlos al lugar de su elección, renunciando el Cliente a
                        oponerse de cualquier forma. En caso que e</font>
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">l
                           Cliente no devolviera los equipos, deberá pagar una suma equivalente
                           al total de rentas mensuales que hubieren faltado para completar el
                           período de vigencia del presente contrato que se encontraba en curso
                           antes de su terminación. </span></font>
                  </p>
                  <p align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <br />
                     <br />

                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">Todos los
                           gastos asociados a la terminación anticipada del presente contrato
                           por las causales antes señaladas y de la entrega o retiro de los
                           Equipos en tales casos, serán de cargo del Cliente.</span></font>
                  </p>
                  <p align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <br />
                     <br />

                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">El término
                           anticipado del contrato no exime al Cliente de la obligación de pago
                           de las rentas que se adeudasen hasta la fecha de término de éste. </span></font>
                  </p>
                  <p align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <br />
                     <br />

                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <font size="2" style="font-size: 11pt"><span lang="es-CL"><u><b>QUINTO:</b></u></span></font>
                     <font size="2" style="font-size: 11pt"><span lang="es-CL"><b>
                              CONDICIONES DE USO.</b></span></font>
                  </p>
                  <p align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <br />
                     <br />

                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">El Cliente
                           se obliga a usar los Equipos de acuerdo a las siguientes condiciones:
                        </span></font>
                  </p>
                  <p align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <br />
                     <br />

                  </p>
                  <ol type="a">
                     <li>
                        <p lang="es-ES" align="justify" style="margin-top: 0.17in; margin-bottom: 0.17in">
                           <font size="2" style="font-size: 11pt"><span lang="es-CL">Los
                                 Equipos deberán ser utilizados exclusivamente para la función que
                                 naturalmente cumplen, de acuerdo a las especificaciones contenidas
                                 en su manual de uso, el cual se entrega al Cliente conjuntamente con
                                 los Equipos. </span></font>
                        </p>
                     <li>
                        <p lang="es-ES" align="justify" style="margin-top: 0.17in; margin-bottom: 0.17in">
                           <font size="2" style="font-size: 11pt"><span lang="es-CL">No podrá
                                 intervenirse en la programación de los referidos Equipos.</span></font>
                        </p>
                     <li>
                        <p lang="es-ES" align="justify" style="margin-top: 0.17in; margin-bottom: 0.17in">
                           <font size="2" style="font-size: 11pt">Los Equipos no podrán ser
                              transferidos, cedidos o traspasados por el Cliente, a ningún
                              título, salvo que cuente con la autorización previa, expresa y por
                              escrito de VCS.</font>
                        </p>
                     <li>
                        <p lang="es-ES" align="justify" style="margin-top: 0.17in; margin-bottom: 0.17in">
                           <font size="2" style="font-size: 11pt">El Cliente no podrá variar
                              el lugar y domicilio en el que se encuentren cualquiera de los
                              Equipos, según se le ha informado a VCS, salvo autorización previa
                              y por escrito de ésta.</font>
                        </p>
                  </ol>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">Los Equipos
                           entregados al Cliente en virtud del contrato de arrendamiento deberán
                           ser usados sólo por él para los servicios que sean prestados por
                           VCS, y para los servicios que sean prestados por otras empresas o
                           compañías, previamente autorizadas por VCS por escrito. Cualquier
                           otra utilización de los Equipos estará prohibida para el Cliente.</span></font>
                  </p>
                  <p align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <br />
                     <br />

                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <font size="2" style="font-size: 11pt"><span lang="es-CL"><u><b>SEXTO</b></u></span></font>
                     <font size="2" style="font-size: 11pt"><span lang="es-CL"><b>:
                              PROHIBICIONES. </b></span></font>
                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <br />
                     <br />

                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <font size="2" style="font-size: 11pt">El presente contrato y las
                        obligaciones y/o derechos derivados de él, no podrán ser
                        transferidos, cedidos o traspasados por el Cliente, a ningún título,
                        salvo que cuente con la autorización previa y por escrito de VCS.
                        El Cliente no podrá subarrendar los Equipos sin autorización previa
                        y por escrito de VCS. Asimismo, e</font>
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">l
                           Cliente no podrá comercializar, vender ni exportar el o los Equipos
                           entregados.</span></font>
                  </p>
                  <p align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <br />
                     <br />

                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <font size="2" style="font-size: 11pt"><span lang="es-CL"><u><b>SEPTIMO</b></u></span></font>
                     <font size="2" style="font-size: 11pt"><span lang="es-CL"><b>:
                              RESPONSABILIDADES: </b></span></font>
                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <br />
                     <br />

                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <font size="2" style="font-size: 11pt">El Cliente deberá emplear la
                        mayor diligencia y cuidado en la conservación de los equipos
                        entregados en arrendamiento por el presente contrato. Por tanto,
                        responderá de todo daño o pérdida que pudieren sufrir cualquiera
                        de los Equipos, incluso los provenientes del caso fortuito o fuerza
                        mayor, comprendidos en éstos el robo y el hurto. </font>
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">En
                           todo caso, el Cliente deberá comunicar por escrito a VCS todo daño
                           o deterioro, pérdida, extravío, robo o hurto de cualquiera de los
                           equipos dentro de los dos días hábiles siguientes a aquel en que se
                           produjeron los hechos. </span></font>
                     <font size="2" style="font-size: 11pt">Asimismo,
                        el Cliente será responsable de cualquier uso indebido o no
                        autorizado que se haga del servicio prestado por VCS, a través de
                        los Equipos entregados en arrendamiento.</font>
                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <br />
                     <br />

                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <font size="2" style="font-size: 11pt">En caso de que por su
                        destrucción o daño de los Equipos y el Cliente no pudiera restituir
                        cualquiera de los Equipos en los términos convenidos en el presente
                        contrato, deberá pagar por cada uno de ellos un monto equivalente </font>
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">al
                           total de las rentas mensuales que faltaren para completar el período
                           de vigencia del Contrato que se encuentre en curso. VCS estará
                           facultada en este caso, para cobrar por medio de una factura el monto
                           correspondiente a los Equipos dañados o destruidos antes señalado.</span></font>
                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">Sin
                           perjuicio de lo establecido anteriormente, en caso que cualquiera de
                           los Equipos presente daños parciales, el Cliente deberá enviarlo al
                           servicio técnico de VCS, quien determinará si procede la reposición
                           sin cargo en virtud de la garantía indicada en la cláusula
                           siguiente. En caso de que no proceda la garantía, el Cliente deberá
                           pagar por la reparación de los Equipos. </span></font>
                  </p>
                  <p align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <br />
                     <br />

                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <font size="2" style="font-size: 11pt"><span lang="es-CL"><u><b>OCTAVO</b></u></span></font>
                     <font size="2" style="font-size: 11pt"><span lang="es-CL"><b>:
                              MANTENIMIENTO Y SERVICIO TÉCNICO.</b></span></font>
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">
                        </span></font>
                  </p>
                  <p align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <br />
                     <br />

                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">VCS
                           otorgará al Cliente garantía de funcionamiento por defectos de
                           fabricación respecto de cualquiera de los Equipos por todo el
                           período de vigencia del presente contrato. Se deja constancia que
                           esta garantía no cubre los desperfectos que sobrevengan por mal uso
                           o manipulación de los Equipos que se deban a culpa del Cliente. Se
                           entenderá por mal uso de los Equipos, todos los desperfectos que
                           sean ocasionados por golpe, quemaduras, modificaciones o alteraciones
                           en sus partes, piezas o componentes, sometimiento a condiciones de
                           calor y frío extremo, humedad, agua, magnetismo. No procederá el
                           otorgamiento de ninguna garantía por parte de VCS, en el caso que
                           los sellos de protección de los Equipos se encuentren rotos,
                           abiertos o de cualquier forma alterada. </span></font>
                  </p>
                  <p align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <br />
                     <br />

                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">En caso que
                           los Equipos presenten fallas y/o problemas cubiertos por la garantía
                           de protección de los Equipos, el Cliente deberá enviarlo a las
                           dependencias de VCS.</span></font>
                  </p>
                  <p align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <br />
                     <br />

                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">VCS
                           determinará, luego de realizar una revisión técnica a los Equipos,
                           si estos requieren ser reemplazados o no. En caso de reemplazo, VCS
                           entregará al Cliente un Equipo de igual o similares características
                           al contratado por este, lo cual se materializará mediante la
                           suscripción de un nuevo anexo 3-A que deje constancia de los Equipos
                           reemplazados. </span></font>
                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <br />
                     <br />

                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <br />
                     <br />

                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <font size="2" style="font-size: 11pt"><u><b>NOVENO</b></u></font>
                     <font size="2" style="font-size: 11pt"><b>:</b></font>
                     <font size="2" style="font-size: 11pt"><span lang="es-CL"><b>
                              OPCIONES AL TÉRMINO DEL CONTRATO DE ARRENDAMIENTO.</b></span></font>
                  </p>
                  <p align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <br />
                     <br />

                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <font size="2" style="font-size: 11pt">Vencido el plazo del contrato
                        de arrendamiento, el Cliente podrá optar por lo siguiente:</font>
                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <br />
                     <br />

                  </p>
                  <ol type="a">
                     <li>
                        <p lang="es-ES" align="justify" style="margin-top: 0.17in; margin-bottom: 0.17in">
                           <font size="2" style="font-size: 11pt">Devolver los Equipos o alguno
                              de ellos a VCS, en cuyo caso deberá entregarlos dentro de los 5
                              días hábiles siguientes al vencimiento del plazo de duración del
                              presente contrato </font>
                           <font size="2" style="font-size: 11pt"><span lang="es-CL">En
                                 caso que el Cliente este ubicado fuera de la Región Metropolitana,
                                 dicho plazo se extenderá a 10 días hábiles</span></font>
                           <font size="2" style="font-size: 11pt">;</font>
                        </p>
                     <li>
                        <p lang="es-ES" align="justify" style="margin-top: 0.17in; margin-bottom: 0.17in">
                           <font size="2" style="font-size: 11pt">Continuar en el cumplimiento
                              del contrato respecto de algunos o todos los Equipos en los mismos
                              términos de que da cuenta el presente instrumento, sin perjuicio de
                              lo cual, la vigencia de este nuevo contrato se irá renovando en
                              forma automática y sucesiva por períodos de un mes cada uno;</font>
                        </p>
                     <li>
                        <p lang="es-ES" align="justify" style="margin-top: 0.17in; margin-bottom: 0.17in">
                           <font size="2" style="font-size: 11pt">Comprar todos o alguno de los
                              Equipos a VCS, en ejercicio de la opción de compra que se indica en
                              la cláusula quinta precedente. El precio de la compraventa
                              corresponderá al valor de la última renta mensual del contrato de
                              arrendamiento, el cual se facturará al momento de cobrarse la
                              última renta de arrendamiento de los Equipos y deberá ser pagado
                           </font>
                           <font size="2" style="font-size: 11pt"><span lang="es-CL">dentro
                                 de los 30 días siguientes a la emisión de la correspondiente
                                 factura</span></font>
                           <font size="2" style="font-size: 11pt">. En
                              caso de aceptación de la oferta, la venta se perfeccionará
                              mediante la suscripción de un instrumento privado en el que conste
                              el ejercicio de dicha opción y el pago del precio. Dicho
                              instrumento se suscribirá al mismo tiempo que el pago del precio.
                              La oferta caducará si el Cliente incurre en cualquier
                              incumplimiento de las obligaciones que le corresponden en virtud del
                              presente contrato o de cualquier otro que suscriba o haya suscrito
                              para con VCS.</font>
                        </p>
                  </ol>
                  <p lang="es-ES" align="justify" style="margin-left: 0.53in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <br />
                     <br />

                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <font size="2" style="font-size: 11pt">Para hacer uso de las opciones
                        individualizadas en las letras a) y c) precedentes, el Cliente deberá
                        estar al día en el pago de las rentas y haber cumplido todas sus
                        obligaciones derivadas del presente contrato.</font>
                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <font size="2" style="font-size: 11pt">Las opciones que anteceden
                        deberán ser ejercidas por el Cliente con una antelación mínima de
                        15 días al término del presente contrato de arrendamiento, mediante
                        comunicación escrita remitida a VCS. A falta de comunicación
                        escrita dentro del plazo referido, se entenderá que el Cliente ha
                        optado comprar el o los Equipos arrendados según lo señalado en la
                        letra c) anterior. Será de cargo del Cliente el pago de todos los
                        impuestos, gastos y gravámenes ocasionados por el ejercicio de su
                        opción. </font>
                  </p>
                  <p align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <br />
                     <br />

                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <font size="2" style="font-size: 11pt"><span lang="es-CL"><u><b>DÉCIMO</b></u></span></font>
                     <font size="2" style="font-size: 11pt"><span lang="es-CL"><b>:
                              COMPENSACIÓN.</b></span></font>
                     <font size="2" style="font-size: 11pt"><b>
                        </b></font>
                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <br />
                     <br />

                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <font size="2" style="font-size: 11pt">VCS siempre podrá</font>
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">
                           compensar y/o deducir los valores que el Cliente le adeude por
                           cualquier concepto, de las sumas de dinero que ésta deba al Cliente.</span></font>
                  </p>
                  <p align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <br />
                     <br />

                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <font size="2" style="font-size: 11pt"><span lang="es-CL"><u><b>DÉCIMO
                                 PRIMERO</b></u></span></font>
                     <font size="2" style="font-size: 11pt"><span lang="es-CL"><b>:
                              AUTORIZACIÓN</b></span></font>
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">.
                        </span></font>
                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <br />
                     <br />

                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <font size="2" style="font-size: 11pt">El Cliente autoriza
                        expresamente a VCS a tratar sus datos personales expuestos en este
                        contrato, única y exclusivamente para incorporarlos en las bases de
                        datos públicas o privadas que contengan morosidades de pago o
                        deudores de cualquier tipo, cuando el Cliente incurra en un retardo o
                        incumplimiento en el pago de las obligaciones de dinero para con VCS
                        establecidas en este contrato y sus anexos.</font>
                  </p>
                  <p align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <br />
                     <br />

                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <font size="2" style="font-size: 11pt"><span lang="es-CL"><u><b>DÉCIMO
                                 SEGUNDO</b></u></span></font>
                     <font size="2" style="font-size: 11pt"><span lang="es-CL"><b>:
                              DOMICILIO Y COMPETENCIA. </b></span></font>
                  </p>
                  <p align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <br />
                     <br />

                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">Las partes
                           fijan para todos los efectos de este Contrato su domicilio en la
                           ciudad y comuna de Santiago y se someten a la jurisdicción de sus
                           tribunales ordinarios de justicia.</span></font>
                  </p>
                  <p align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <br />
                     <br />

                  </p>
                  <p align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <br />
                     <br />

                  </p>
                  <p align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <br />
                     <br />

                  </p>
                  <p align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <br />
                     <br />

                  </p>
                  <p align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <br />
                     <br />

                  </p>
                  <p align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <br />
                     <br />

                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <font size="2" style="font-size: 11pt"><span lang="es-CL"><u><b>DÉCIMO
                                 TERCERO</b></u></span></font>
                     <font size="2" style="font-size: 11pt"><span lang="es-CL"><b>:
                              EJEMPLARES. </b></span></font>
                  </p>
                  <p lang="es-ES" align="justify" style="margin-left: 0.25in; margin-top: 0.17in; margin-bottom: 0.17in">
                     <font size="2" style="font-size: 11pt"><span lang="es-CL">El presente
                           Contrato se otorga en dos ejemplares, quedando uno en poder de cada
                           parte.</span></font>
                  </p>
                  <p lang="es-ES-u-co-trad" class="western" align="justify" style="margin-bottom: 0.14in; line-height: 115%">
                  <table dir="ltr" align="left" width="576" hspace="9" cellpadding="5" cellspacing="0">
                     <col width="274" />

                     <col width="283" />

                     <tr valign="top">
                        <td width="274" style="border: none; padding: 0in">
                           <p class="western" style="orphans: 0; widows: 0">
                              <font size="2" style="font-size: 11pt"><b>_________________________</b></font>
                           </p>
                        </td>
                        <td width="283" style="border: none; padding: 0in">
                           <p class="western" style="orphans: 0; widows: 0">
                              <font size="2" style="font-size: 11pt"><b>_______________________</b></font>
                           </p>
                        </td>
                     </tr>
                     <tr valign="top">
                        <td width="274" style="border: none; padding: 0in">
                           <p class="western" style="orphans: 0; widows: 0">
                              <font size="2" style="font-size: 11pt"><b>VCS </b></font>
                           </p>
                        </td>
                        <td width="283" style="border: none; padding: 0in">
                           <p class="western" style="orphans: 0; widows: 0">
                              <font size="2" style="font-size: 11pt"><b>CLIENTE</b></font>
                           </p>
                        </td>
                     </tr>
                  </table><br />
                  <br />

                  </p>
                  <p lang="es-ES" class="western" align="center" style="margin-left: 0.45in; page-break-before: always">
                     <font size="2" style="font-size: 11pt"><span lang="es-ES-u-co-trad"><b>ANEXO
                              3-A</b></span></font>
                  </p>
                  <p lang="es-ES" class="western" align="center" style="margin-left: 0.45in">
                     <font size="2" style="font-size: 11pt"><span lang="es-ES-u-co-trad"><b>IDENTIFICACIÓN
                              DE LOS EQUIPOS ARRENDADOS</b></span></font>
                  </p>
                  <p lang="es-ES" class="western" align="center"><br />

                  </p>
                  <p lang="es-ES" class="western" align="center">
                     <font size="2" style="font-size: 11pt"><b>Fecha
                           de suscripción: [XX-XX-XXXX]</b></font>
                  </p>
                  <p lang="es-ES" class="western" align="justify"><br />

                  </p>
                  <p lang="es-ES" class="western" align="justify"><br />

                  </p>
                  <center>
                     <table width="600" cellpadding="7" cellspacing="0">
                        <col width="107" />

                        <col width="109" />

                        <col width="119" />

                        <col width="118" />

                        <col width="75" />

                        <tr>
                           <td width="107" style="border: 1px solid #000000; padding: 0in 0.08in">
                              <p class="western" align="justify" style="orphans: 2; widows: 2">
                                 <font size="2" style="font-size: 11pt"><b>CODIGO</b></font>
                              </p>
                           </td>
                           <td width="109" style="border: 1px solid #000000; padding: 0in 0.08in">
                              <p class="western" align="justify" style="orphans: 2; widows: 2">
                                 <font size="2" style="font-size: 11pt"><b>Nº SERIE</b></font>
                              </p>
                           </td>
                           <td width="119" style="border: 1px solid #000000; padding: 0in 0.08in">
                              <p class="western" align="justify" style="orphans: 2; widows: 2">
                                 <font size="2" style="font-size: 11pt"><b>UBICACIÓN</b></font>
                              </p>
                           </td>
                           <td width="118" style="border: 1px solid #000000; padding: 0in 0.08in">
                              <p class="western" align="justify" style="orphans: 2; widows: 2">
                                 <font size="2" style="font-size: 11pt"><b>FECHA CONTRATACION</b></font>
                              </p>
                           </td>
                           <td width="75" style="border: 1px solid #000000; padding: 0in 0.08in">
                              <p class="western" align="justify" style="orphans: 2; widows: 2">
                                 <font size="2" style="font-size: 11pt"><b>PLAZO</b></font>
                              </p>
                              <p class="western" align="justify" style="orphans: 2; widows: 2">
                                 <font size="2" style="font-size: 11pt"><b>ARRIENDO</b></font>
                              </p>
                           </td>
                        </tr>
                        <tr valign="top">
                           <td width="107" style="border: 1px solid #000000; padding: 0in 0.08in">
                              <p class="western" align="justify" style="margin-top: 0.17in; orphans: 2; widows: 2">
                                 <br />

                              </p>
                           </td>
                           <td width="109" style="border: 1px solid #000000; padding: 0in 0.08in">
                              <p class="western" align="justify" style="margin-top: 0.17in; orphans: 2; widows: 2">
                                 <br />

                              </p>
                           </td>
                           <td width="119" style="border: 1px solid #000000; padding: 0in 0.08in">
                              <p class="western" align="justify" style="margin-top: 0.17in; orphans: 2; widows: 2">
                                 <br />

                              </p>
                           </td>
                           <td width="118" style="border: 1px solid #000000; padding: 0in 0.08in">
                              <p class="western" align="justify" style="margin-top: 0.17in; orphans: 2; widows: 2">
                                 <br />

                              </p>
                           </td>
                           <td width="75" style="border: 1px solid #000000; padding: 0in 0.08in">
                              <p class="western" align="justify" style="margin-top: 0.17in; orphans: 2; widows: 2">
                                 <br />

                              </p>
                           </td>
                        </tr>
                     </table>
                  </center>
                  <p class="western" align="justify"><br />

                  </p>
                  <p class="western" align="justify"><br />

                  </p>
                  <p class="western" align="justify"><br />

                  </p>
                  <ul>
                     <li>
                        <p lang="es-ES" align="justify">
                           <font size="2" style="font-size: 11pt">Los
                              valores mencionados en este Anexo están afectos al Impuesto al
                              Valor Agregado</font>
                        </p>
                     <li>
                        <p lang="es-ES" align="justify">
                           <font size="2" style="font-size: 11pt"><span lang="es-CL">El
                                 total de la renta por concepto de arrendamiento de equipos podrá
                                 ser modificada de tiempo en tiempo, según se vayan reemplazando o
                                 agregando nuevos Equipos al contrato, lo que materializará mediante
                                 la suscripción de un nuevo Anexo 3-A.</span></font>
                        </p>
                  </ul>
               </div>
            </div>
         </div>
   </section>
</body>

</html>