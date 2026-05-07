<?php
// Inicia la sesión
session_start();

// Elimina la variable de sesión 'usuario'
unset($_SESSION['usuario']);

// Opcionalmente, destruye la sesión por completo
// session_destroy();

// Redirige al usuario a 'index.php'
header("Location: index.php");
exit();
?>
