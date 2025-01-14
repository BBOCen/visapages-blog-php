<?php
/*Esta es la página encargada de editar un artículo, solo un admin puede hacerlo*/
require_once "./funciones.php";
empezarSesion();

/*Se verifica que es un admin el que accede a esta página*/
if (!verificarLogin() || verificarRol(obtenerToken()) != "administrador") {
    header('Location: index.php');
    exit();
}

$articulo_existe = false;
$id_articulo = $_GET['id_articulo'] ?? null;

/*Aquí se determina si el artículo existe o no, si no existe, se manda al usuario a la página 404*/
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($id_articulo)) {
    $existe = verificarArticulo($id_articulo);
    if ($existe) {
        $articulo_existe = true;
        $contenido_articulo = obtenerArticulo($id_articulo);

    } else {
        header('Location: 404_articulo.php');
        exit();
    }
} else {
    header('Location: 404_articulo.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>visaPages | Editar Artículo</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/animate.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/slick.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/theme.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <!--[if lt IE 9]>
    <script src="../assets/js/html5shiv.min.js"></script>
    <script src="../assets/js/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div id="preloader">
    <div id="status">&nbsp;</div>
</div>

<a class="scrollToTop" href="#"><i class="fa fa-angle-up"></i></a>

<div class="container">
    <?php include "./elementos_pagina/header.php"; ?>

    <section id="mainContent">
        <h1>Editar Artículo</h1>
        <div class="content_bottom">
            <div class="col-lg-8 col-md-8">
                <div class="content_bottom_left">
                    <div class="single_page_area">
                        <ol class="breadcrumb">
                            <!--Este es el título del artículo que se va a editar-->
                            <li><a href="./index.php">Inicio</a></li>
                            <li>
                                <a href="./categoria_articulo?categoria=<?php echo urlencode($contenido_articulo['categoria']); ?>">
                                    <?php echo ucwords($contenido_articulo['categoria']); ?>
                                </a>
                            </li>
                            <li class="active"><?php echo $contenido_articulo['titulo']; ?></li>
                        </ol>

                        <h2 class="post_titile"><?php echo $contenido_articulo['titulo']; ?></h2>
                        <!--Este es el formulario para editar el artículo-->
                        <form enctype="multipart/form-data" method="POST" action="./guardar_articulo.php?id_articulo=<?php echo $id_articulo?>">
                            <input type="hidden" name="id_articulo" value="<?php echo $id_articulo; ?>">
                            <input type="hidden" name="imagen_defecto" value="<?php echo htmlspecialchars($contenido_articulo['imagen']);?>">
                            <input type="hidden" name="accion" value="editar">
                            <div class="form-group">
                                <label for="titulo">Título:</label>
                                <input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo htmlspecialchars($contenido_articulo['titulo']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="contenido">Contenido:</label>
                                <textarea class="form-control" id="contenido" name="contenido" rows="10" required><?php echo htmlspecialchars($contenido_articulo['contenido']); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="imagen_archivo">Subir Imagen (si no subes ninguna y no introduces una por URL, se asignará una imagen por defecto):</label>
                                <input type="file" class="form-control" id="imagen_archivo" name="imagen_archivo">
                                <small class="form-text text-muted">Puedes subir una imagen desde tu dispositivo.</small>
                            </div>
                            <div class="form-group">
                                <label for="imagen_url">O ingresa una URL de la Imagen:</label>
                                <input type="text" class="form-control" id="imagen_url" name="imagen_url">
                                <small class="form-text text-muted">Si no subes un archivo, puedes escribir una URL.</small>
                            </div>
                            <div class="form-group">
                                <label for="categoria">Categoría:</label>
                                <br>
                                <!--Aquí se imprimen todas las categorías que existen en la bbdd-->
                                <?php imprimirCategorias()?>
                            </div>
                            <button type="submit" name="enviar" class="btn btn-primary">Guardar Cambios</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include './elementos_pagina/footer.php'; ?>

<script src="../assets/js/jquery.min.js"></script>
<script src="../assets/js/bootstrap.min.js"></script>
<script src="../assets/js/wow.min.js"></script>
<script src="../assets/js/slick.min.js"></script>
<script src="../assets/js/custom.js"></script>
</body>
</html>
