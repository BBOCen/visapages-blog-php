<?php
/*Este archivo maneja la lógica del login*/
require_once "./funciones.php";

empezarSesion();

require './conexion_bbdd.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['contrasenia'];

    /*Se valida el email*/
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Email no válido.");
    }

    /*Aquí se verifica si el usuario existe*/
    $datos_usuario=loginEmail($email);
    if (!$datos_usuario) {
        die("El email no existe.");
    }
    
    /*Ahora se verifica la contraseña*/
    if (password_verify($password, $datos_usuario['contrasenia'])) {
        /*Aquí se genera un token aleatorio*/
        $token = bin2hex(random_bytes(32));
        $fecha_expiracion = time() + 30 * 24 * 60 * 60;  // 30 days in seconds
        $fecha_expiracion_formateada = date('Y-m-d H:i:s', $fecha_expiracion);

        /* Si el usuario quiere que sea recordado por la pagina, vamos a usar una cookie */
        if (isset($_POST['recordar_usuario'])) {
            setcookie("remember_token", $token, $fecha_expiracion, "/visapages", "", false, true);
        }

        /* Si no, se usará la sesión para guardar el token */
        else {
            $_SESSION['token'] = $token;
        }

        /*Aquí se guarda el token en la bbdd*/
        $activar = 1;
        guardarSesion($datos_usuario['id_usuario'], $token, $fecha_expiracion_formateada, $activar);

    } else {
        die("Contraseña incorrecta.");
    }
}
