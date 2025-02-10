<?
session_start();

# verificar si hay una sesión o una cookie activa
if (isset($_SESSION['user'])) {
   # redirigir al área protegida
   header("Location: views/main.php");
   exit();
} elseif (isset($_COOKIE['user'])) {
   # crear una sesión automáticamente usando la cookie
   $_SESSION['user'] = $_COOKIE['user'];
   header("Location: views/main.php");
   exit();
} else {
   # redirigir al login si no hay sesión ni cookie
   header("Location: views/login.php");
   exit();
}
?>