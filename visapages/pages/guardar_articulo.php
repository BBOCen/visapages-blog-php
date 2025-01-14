<?php
/*Este es el código encargado de guardar el artículo*/
require_once "./funciones.php";

if (isset($_POST['enviar'])) {
    /*Si el usuario modifica el artículo se le redirige a la página anterior, para que vea sus modificaciones*/
    $id_categoria = obtenerIdCategoria($_POST['categoria']);
    $id_articulo = 0;

    /*Aquí se procesa la imagen*/
    $archivo_imagen = $_FILES['imagen_archivo'] ?? null;
    $url_imagen = $_POST['imagen_url'] ?? null;
    
    /*En este if verificamos si el usuario no ha subido una imagen ni url, si es así, se le asigna una imagen por defecto
    la cual será la imagen que tenía anteriormente, si por lo que sea, esta no existe, se le asignará una imagen por defecto*/
    if (empty($archivo_imagen['tmp_name']) && empty($url_imagen)) {
        if (!isset($_POST['imagen_defecto'])) {
            $ruta_imagen="../imagenes/defecto.png";
        }
        else {
            $ruta_imagen = $_POST['imagen_defecto'];
        }
    }

    /*Aquí se maneja el resultado después de haber procesado la imagen*/
    else {
        $resultado_imagen = procesarImagen($archivo_imagen, $url_imagen, '../imagenes/');
        if (isset($resultado_imagen['error'])) {
            die('Error con la imagen: ' . $resultado_imagen['error']);
        }
        $ruta_imagen = $resultado_imagen['ruta'];
    }

    /*Como se utiliza este mismo archivo para modificar el artículo y publicarlo, se utiliza este if para manejar
    la lógica de guardarlo en la bbdd dependiendo de si se está editando o publicando un artículo */
    if ($_POST['accion'] == "editar" && isset($_GET['id_articulo'])) {
        $id_articulo = $_GET['id_articulo'];
        modificarArticulo($_POST['id_articulo'], $_POST['titulo'], $_POST['contenido'], $id_categoria, $ruta_imagen);
    } else if ($_POST['accion'] == "publicar") {
        $id_usuario = obtenerIdUsuario(obtenerToken());
        publicarArticulo($_POST['titulo'], $_POST['contenido'], $ruta_imagen, $id_categoria, $id_usuario);
        $id_articulo = obtenerArticuloMasReciente();
    }
    header('Location: ./mostrar_articulo.php?id_articulo=' . $id_articulo);
    exit();
} else {
    header('Location: ./index.php');
    exit();
}