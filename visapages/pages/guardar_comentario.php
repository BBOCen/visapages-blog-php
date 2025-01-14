<?php
/*Aquí se guarda el comentario publicado por un usuario*/
require_once "funciones.php";
empezarSesion();

/*Aquí se verifica que el usuario está logado si publuca un comentario*/
if ($_SERVER['REQUEST_METHOD'] === 'POST' && verificarLogin()) {

    /*Se filtra la entrada del usuario*/
    $id_articulo = filter_var($_POST['id_articulo'], FILTER_VALIDATE_INT);
    $contenido = filter_var($_POST['contenido'], FILTER_SANITIZE_STRING);
    $id_usuario = obtenerIdUsuario(obtenerToken());

    /*Si concuerda, se guarda*/
    if ($id_articulo && !empty($contenido)) {
        guardarComentario($id_articulo, $id_usuario, $contenido);
        header("Location: mostrar_articulo.php?id_articulo=$id_articulo");
        exit();
    } else {
        echo "Error: No se pudo guardar el comentario.";
    }
} else {
    header("Location: login.html");
    exit();
}
