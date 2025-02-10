<?php
session_start();

// Eliminar la cookie de sesión
setcookie("user_session", "", time() - 3600, "/", "", true, true);

// Destruir las variables de sesión
session_unset(); 

// Destruir la sesión
session_destroy();

// Respuesta JSON para AJAX
$response = [
    "success" => true,
    "message" => "Has cerrado sesión correctamente."
];

echo json_encode($response);
exit();
?>