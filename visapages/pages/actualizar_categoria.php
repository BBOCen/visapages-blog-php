<?php
/*Este es el código que se encarga de actualizar la categoría*/
require_once "./funciones.php";
/*Si el usuario no es un admin, se le devuelve al index*/
if (!verificarLogin() || verificarRol(obtenerToken()) != "administrador") {
    header('Location: index.php');
    exit();
}
/*Si es un admin, se actualiza la categoría*/
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    actualizarCategoria($_POST['id_categoria'], $_POST['nombre'], $_POST['descripcion']);
    header('Location: ./administrar_categorias.php');
    exit();
}