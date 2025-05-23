<?

   class MOD_ReCaptcha
   {
      // Llaves de desarrollo
      const RECAPTCHA_PUBLIC_KEY = "6Lc_nQ8UAAAAAOO2tcPSopoV66nWoiv9FMSBc8Wm"; /*  LLAVE PUBLICA DE RECAPTCHA DE GOOGLE. CAMBIAR EN PRDDUCCION */
      const RECAPTCHA_SECRET_KEY = "6Lc_nQ8UAAAAAGd-cK7Loo16NCEGRWrDt2GMZ_bn"; /*  CLAVE SECRETA PARA RECAPTCHA DE GOOGLE. CAMBIAR EN PRODUCCION */

      // Llaves de produccion
      // const RECAPTCHA_PUBLIC_KEY = "6Lfk7goUAAAAABHZGLU-FaJFpt3xUUOHVC1GfDRB"; /*  LLAVE PUBLICA DE RECAPTCHA DE GOOGLE. CAMBIAR EN PRDDUCCION */
      // const RECAPTCHA_SECRET_KEY = "6Lfk7goUAAAAAG1fUB32tAaOa0Ls8IBvyyKzY64s"; /*  CLAVE SECRETA PARA RECAPTCHA DE GOOGLE. CAMBIAR EN PRODUCCION */
      const API_CAPTCHA          = "https://www.google.com/recaptcha/api.js";  /* Link de la api de captcha de google */

      public static function Valida($token)
      {
         $retval = FALSE;
         if(isset($token) && !empty($token))
         {
            $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='. self::RECAPTCHA_SECRET_KEY . '&response=' . $token);
            $responseData = json_decode($verifyResponse);
            if($responseData->success)
               $retval = TRUE;
         }
         return $retval;
      }
   }
