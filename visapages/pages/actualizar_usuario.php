<?php
/*Este código es el encargado de actualizar el perfil del usuario por el admin*/
require_once "./funciones.php";

/*Si el usuario no es un admin, se le echa de la página*/
if (!verificarLogin() || verificarRol(obtenerToken()) != "administrador") {
    header('Location: index.php');
    exit();
}

/*Si no, se actualiza el usuario con los cambios realizados por el admin*/
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_usuario'])) {
    $id_usuario = $_POST['id_usuario'];
    if (verificarUsuario($id_usuario)) {
        $usuario = obtenerUsuario($id_usuario);
        $nombre = !empty($_POST['nombre']) ? trim($_POST['nombre']) : $usuario['nombre'];
        $email = !empty($_POST['email']) ? trim($_POST['email']) : $usuario['email'];
        $contrasenia = !empty($_POST['contrasenia']) ? password_hash(trim($_POST['contrasenia']), PASSWORD_DEFAULT) : $usuario['contrasenia'];
        $codigo_pais = !empty($_POST['codigo_pais']) ? trim($_POST['codigo_pais']) : $usuario['codigo_pais'];

        actualizarUsuario($id_usuario, $nombre, $email, $contrasenia, $codigo_pais);

        /*Después de actualizar al usuario se redirige a la página del perfil*/
        header('Location: ./perfil.php?id_usuario=' . $id_usuario);
    }
}