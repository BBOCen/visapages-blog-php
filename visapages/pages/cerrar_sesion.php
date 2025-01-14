<?php
session_start();
/*Este es el código encargado de cerrar la sesión del usuario*/
include  "./funciones.php";
$token=obtenerToken();
if (obtenerToken()==null) {
    header('Location: index.php');
}
else {
    desactivarLogin($token);
    header("Location: ./index.php");
}
exit();
