<?
   $user       = 'kllanquel2@clinicalascondes.cl';
   $password   = '8Hus14g2';
?>

<head>

   <!-- Required meta  tags -->
   <meta http-equiv="Pragma" content="no-cache">
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

   <!-- title -->
   <title>authenticate</title>

</head>

<body>

   <script type="text/javascript">
      document.addEventListener("DOMContentLoaded", async function() {

         const user = '<?= $user ?>';
         const password = '<?= $password ?>';

         // Enviar datos al backend usando fetch
         const response = await fetch('../auth/authenticate.php', {
            method: 'POST',
            headers: {
               'Content-Type': 'application/json'
            },
            body: JSON.stringify({
               user,
               password
            })
         });

         const result = await response.json(); // Leer la respuesta en JSON

         localStorage.setItem('jwt', result.token);

         // Redirigir al area protegida si el login fue exitoso
         window.location.href = '../views/main.php';

      });
   </script>

</body>

</html>