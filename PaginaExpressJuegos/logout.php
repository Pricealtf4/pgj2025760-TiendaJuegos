<?php
session_start();

// Destruir la sesión
session_unset();
session_destroy();

// Enviar encabezados para evitar almacenamiento en caché
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Redirigir a la página de inicio de sesión
header("Location: index.html");
exit();
?>