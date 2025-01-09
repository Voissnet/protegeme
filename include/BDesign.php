<?
   class BDesign
   {
      var $dom_cod;
      var $fondo_web;
      var $fondo_app;
      var $botones_tablas_web;
      var $botones_tablas_app;
      var $color_letra_web;
      var $color_letra_app;
      var $tamano_fuente_web;

      // trae el primer registro
      function primero (&$DB)
      {
         $retval                       = false;

         $sql                          = "SELECT a.dom_cod,
                                                a.fondo_web,
                                                a.fondo_app,
                                                a.botones_tablas_web,
                                                a.botones_tablas_app,
                                                a.color_letra_web,
                                                a.color_letra_app,
                                                a.tamano_fuente_web
                                          FROM BP.BP_DESIGN a
                                          ORDER BY a.dom_cod ASC";

         if ($DB->Query($sql))
         {
            $this->dom_cod             = $DB->Value("DOM_COD");
            $this->fondo_web           = $DB->Value("FONDO_WEB");
            $this->fondo_app           = $DB->Value("FONDO_APP");
            $this->botones_tablas_web  = $DB->Value("BOTONES_TABLAS_WEB");
            $this->botones_tablas_app  = $DB->Value("BOTONES_TABLAS_APP");
            $this->color_letra_web     = $DB->Value("COLOR_LETRA_WEB");
            $this->color_letra_app     = $DB->Value("COLOR_LETRA_APP");
            $this->tamano_fuente_web   = $DB->Value("TAMANO_FUENTE_WEB");
            $retval                    = true;
         }
         else
            $DB->Close();
         return $retval;
      }

      // trae el siguiente dato
      function siguiente(&$DB)
      {
         $retval                       = false;

         if ($DB->Next())
         {
            $this->dom_cod             = $DB->Value("DOM_COD");
            $this->fondo_web           = $DB->Value("FONDO_WEB");
            $this->fondo_app           = $DB->Value("FONDO_APP");
            $this->botones_tablas_web  = $DB->Value("BOTONES_TABLAS_WEB");
            $this->botones_tablas_app  = $DB->Value("BOTONES_TABLAS_APP");
            $this->color_letra_web     = $DB->Value("COLOR_LETRA_WEB");
            $this->color_letra_app     = $DB->Value("COLOR_LETRA_APP");
            $this->tamano_fuente_web   = $DB->Value("TAMANO_FUENTE_WEB");
            $retval                    = true;
         }
         else
            $DB->Close();
         return $retval;
      }

      // busca un diseno especifico
      function busca ($dom_cod, &$DB)
      {
         $retval                       = false;

         $valores['dom_cod']           = $dom_cod;

         $sql                          = "SELECT a.dom_cod,
                                                a.fondo_web,
                                                a.fondo_app,
                                                a.botones_tablas_web,
                                                a.botones_tablas_app,
                                                a.color_letra_web,
                                                a.color_letra_app,
                                                a.tamano_fuente_web
                                          FROM BP.BP_DESIGN a
                                          WHERE a.dom_cod = :dom_cod";

         if ($DB->Query($sql, $valores))
         {
            $this->dom_cod             = $DB->Value("DOM_COD");
            $this->fondo_web           = $DB->Value("FONDO_WEB");
            $this->fondo_app           = $DB->Value("FONDO_APP");
            $this->botones_tablas_web  = $DB->Value("BOTONES_TABLAS_WEB");
            $this->botones_tablas_app  = $DB->Value("BOTONES_TABLAS_APP");
            $this->color_letra_web     = $DB->Value("COLOR_LETRA_WEB");
            $this->color_letra_app     = $DB->Value("COLOR_LETRA_APP");
            $this->tamano_fuente_web   = $DB->Value("TAMANO_FUENTE_WEB");
            $retval                    = true;
         }
         $DB->Close();
         return $retval;
      }

      // resgistra desing
      function inserta($dom_cod, &$DB)
      {
         $retval                          = false;

         $valores['dom_cod']              = $dom_cod;
         $valores['fondo_web']            = '#ff0400';
         $valores['fondo_app']            = '#000000';
         $valores['botones_tablas_web']   = '#cf0704';
         $valores['botones_tablas_app']   = '#cf0704';
         $valores['color_letra_web']      = '#ffffff';
         $valores['color_letra_app']      = '#ffffff';
         $valores['tamano_fuente_web']    = '12';

         $sql                             = "INSERT INTO FG.BP_DESIGN (dom_cod, fondo_web, fondo_app, botones_tablas_web, botones_tablas_app, color_letra_web, color_letra_app, tamano_fuente_web)
                                             VALUES (:dom_cod, :fondo_web, :fondo_app, :botones_tablas_web, :botones_tablas_app, :color_letra_web, :color_letra_app, :tamano_fuente_web)";

         if ($DB->Execute($sql, $valores)) 
         {
            $retval                       = true;
         }
         return $retval;

      }

      // actualiza solo parametros web
      function actualizaWEB ($dom_cod, $fondo_web, $botones_tablas_web, $color_letra_web, $tamano_fuente_web, &$DB)
      {
         $retval                          = false;

         $valores['dom_cod']              = $dom_cod;
         $valores['fondo_web']            = $fondo_web;
         $valores['botones_tablas_web']   = $botones_tablas_web;
         $valores['color_letra_web']      = $color_letra_web;
         $valores['tamano_fuente_web']    = $tamano_fuente_web;

         $sql                             = "UPDATE BP.BP_DESIGN a
                                             SET a.fondo_web = :fondo_web,
                                                a.botones_tablas_web = :botones_tablas_web,
                                                a.color_letra_web = :color_letra_web,
                                                a.tamano_fuente_web = :tamano_fuente_web
                                             WHERE a.dom_cod = :dom_cod";

         if ($DB->Execute($sql, $valores))
         {
            $this->dom_cod                = $dom_cod;
            $this->fondo_web              = $fondo_web;
            $this->botones_tablas_web     = $botones_tablas_web;
            $this->color_letra_web        = $color_letra_web;
            $this->tamano_fuente_web      = $tamano_fuente_web;
            $retval                       = true;
         }
         return $retval;
      }

      // actualiza solo parametros web
      function actualizaAPP ($dom_cod, $fondo_app, $botones_tablas_app, $color_letra_app, &$DB)
      {
         $retval                          = false;

         $valores['dom_cod']              = $dom_cod;
         $valores['fondo_app']            = $fondo_app;
         $valores['botones_tablas_app']   = $botones_tablas_app;
         $valores['color_letra_app']      = $color_letra_app;

         $sql                             = "UPDATE BP.BP_DESIGN a
                                             SET a.fondo_app = :fondo_app,
                                                a.botones_tablas_app = :botones_tablas_app,
                                                a.color_letra_app = :color_letra_app
                                             WHERE a.dom_cod = :dom_cod";

         if ($DB->Execute($sql, $valores))
         {
            $this->dom_cod                = $dom_cod;
            $this->fondo_app              = $fondo_app;
            $this->botones_tablas_app     = $botones_tablas_app;
            $this->color_letra_app        = $color_letra_app;
            $retval                       = true;
         }
         return $retval;
      }

   }
?>