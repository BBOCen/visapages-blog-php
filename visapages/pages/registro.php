<?php
/*Esta es la página encargada de registrar al usuario*/
include "./funciones.php";
include "./funciones_paises/funciones_paises.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    /*Aquí se filtran los datos dados por el usuario*/
    $nombre = filter_var(trim($_POST['nombre']), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $contrasenia=filter_var(trim($_POST['contrasenia']), FILTER_SANITIZE_STRING);
    $contrasenia = password_hash($contrasenia, PASSWORD_DEFAULT);
    $ip=obtenerIP();
    $pais = obtenerPais($ip);

    /*Ahora se registra al usuario con los datos filtrados*/
    registrarUsuario($nombre, $email, $contrasenia, $pais, $ip);

    /*Después del registro, se manda al usuario a la página de login*/
    header("location: login.html");
}