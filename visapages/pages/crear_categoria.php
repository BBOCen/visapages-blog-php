<?php
/*Este código se encarga de crear la categoría, solo el admin puede crearla*/
require_once "./funciones.php";

if (!verificarLogin() || verificarRol(obtenerToken()) != "administrador") {
    header('Location: index.php');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    crearCategoria($_POST['nombre'], $_POST['descripcion']);
    header('Location: ./administrar_categorias.php');
    exit();
}