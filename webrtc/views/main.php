<?

require_once 'Parameters.php';

session_start();

# Constantes
define('SESSION_TIMEOUT', 6 * 30 * 24 * 60 * 60); # 6 meses en segundos

# Verificar si la cookie y la sesión existen
if (!isset($_COOKIE['user_session'])) {
   # Redirigir al login si no hay sesión o cookie válida
   setcookie("user_session", "", time() - 3600, "/", "", true, true); # Eliminar la cookie
   session_unset(); # Eliminar variables de sesión
   session_destroy(); # Destruir sesión
   header("Location: login.php?error=session_expired1");
   exit();
}

# Verificar si la sesión ha expirado por inactividad
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT)) {
   # Destruir la sesión y cookie si ha expirado
   setcookie("user_session", "", time() - 3600, "/", "", true, true); # Eliminar la cookie
   session_unset(); # Eliminar variables de sesión
   session_destroy(); # Destruir sesión
   header("Location: login.php?error=session_expired2");
   exit();
}

# Actualizar el tiempo de última actividad
$_SESSION['last_activity'] = time(); # Registrar nueva actividad


# Leer los datos de la cookie JSON y decodificarlos
if (isset($_COOKIE['user_session'])) {
   $userDataJson = $_COOKIE['user_session'];
   $userData = json_decode($userDataJson, true);

   if ($userData === null) {
      // Si la decodificación falla, redirigir al login
      header("Location: login.php?error=invalid_session");
      exit();
   }

   // Acceder a los datos del usuario
   $user_code = Parameters::openCypher('decrypt', htmlspecialchars($userData['busua_cod']));
   $cloud_username = Parameters::openCypher('decrypt', htmlspecialchars($userData['cloud_username']));
   $domain_user = Parameters::openCypher('decrypt', htmlspecialchars($userData['domain_user']));
   $bot_code = Parameters::openCypher('decrypt', htmlspecialchars($userData['bot_cod']));
   $sip_username = Parameters::openCypher('decrypt', htmlspecialchars($userData['sip_username']));
   $sip_password = Parameters::openCypher('decrypt', htmlspecialchars($userData['sip_password']));

} else {
   
   // Si no hay cookie, redirigir al login
   header("Location: login.php?error=no_cookie");
   exit();
   
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Página Principal</title>

   <!-- Incluir el CSS de SweetAlert -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.0/dist/sweetalert2.min.css">

   <!-- Incluir el JS de SweetAlert -->
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.0/dist/sweetalert2.min.js"></script>

</head>

<body>
   <h1>Bienvenido</h1>
   <p>Usuario actual: <?php echo $cloud_username; ?></p>
   <p>Dominio: <?php echo $domain_user; ?></p>
   <p>Código de Botón: <?php echo $bot_code; ?></p>
   <p>Usuario SIP: <?php echo $sip_username; ?></p>
   <p>Password SIP: <?php echo $sip_password; ?></p>
   <!-- <button id="logoutBtn">Cerrar sesión</button> -->

   <!-- <script>
      document.getElementById('logoutBtn').addEventListener('click', function() {
         fetch('../auth/logout.php', {
               method: 'POST',
               headers: {
                  'Content-Type': 'application/json',
               },
            })
            .then(response => response.json())
            .then(data => {
               if (data.success) {
                  // Mostrar mensaje de éxito con SweetAlert
                  Swal.fire({
                     icon: 'success',
                     title: '¡Has cerrado sesión!',
                     text: data.message,
                     confirmButtonText: 'Aceptar',
                     confirmButtonColor: '#4CAF50', // Color verde en el botón
                     background: '#f0f4f7', // Fondo blanco suave
                     iconColor: '#4CAF50', // Icono verde
                     showClass: {
                        popup: 'animate__animated animate__fadeInDown' // Animación de entrada
                     },
                     hideClass: {
                        popup: 'animate__animated animate__fadeOutUp' // Animación de salida
                     }
                  }).then(() => {
                     window.location.href = "login.php"; // Redirigir a login
                  });
               } else {
                  Swal.fire({
                     icon: 'error',
                     title: 'Algo salió mal',
                     text: data.message,
                     confirmButtonText: 'Intentar nuevamente',
                     confirmButtonColor: '#F44336', // Color rojo en el botón
                     background: '#f9f9f9', // Fondo suave
                     iconColor: '#F44336', // Icono rojo
                     showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                     },
                     hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                     }
                  });
               }
            })
            .catch(error => {
               console.error('Error al cerrar sesión:', error);
               Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  text: 'Hubo un error inesperado. Por favor, intenta nuevamente.',
                  confirmButtonText: 'Aceptar',
                  confirmButtonColor: '#F44336',
                  background: '#f9f9f9',
                  iconColor: '#F44336',
                  showClass: {
                     popup: 'animate__animated animate__fadeInDown'
                  },
                  hideClass: {
                     popup: 'animate__animated animate__fadeOutUp'
                  }
               });
            });
      });
   </script> -->

</body>

</html>