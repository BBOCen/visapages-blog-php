<?php
/*Este es el código encargado de publicar un artículo nuevo*/
require_once "./funciones.php";
/*Aquí verificamos si ya exitse una sesión generada por otro código, esto se usa para evitar el aviso que nos da si esto ocurre*/
empezarSesion();

/*Verificamos si el usuario está logado, si no es así, se le redirige a la página de inicio de sesión, el token será null si no está logado*/
$token=obtenerToken();
if ($token==null || verificarRol($token)!="administrador") {
    header("Location: login.html");
    exit();
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>visaPages | Publicar Artículo</title>
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
    <?php
    include "./elementos_pagina/header.php";
    ?>
    <section id="mainContent">
        <h1>Publicar Artículo</h1>
        <div class="content_bottom">
            <div class="col-lg-8 col-md-8">
                <div class="content_bottom_left">
                    <div class="single_page_area">
                        <!--Este es el formulario para publicar el artículo-->
                        <form id="editArticleForm" method="POST" action="guardar_articulo.php" enctype="multipart/form-data">
                            <input type="hidden" name="accion" value="publicar"
                            <div class="form-group">
                                <label for="titulo">Título:</label>
                                <input type="text" class="form-control" id="titulo" name="titulo" required>
                            </div>
                            <div class="form-group">
                                <label for="contenido">Contenido:</label>
                                <textarea class="form-control" id="contenido" name="contenido" rows="10" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="imagen_archivo">Subir Imagen (si no subes ninguna y no introduces una por URL, se asignará una imagen por defecto):</label>
                                <input type="file" class="form-control" id="imagen_archivo" name="imagen_archivo">
                                <small class="form-text text-muted">Puedes subir un archivo de imagen desde tu dispositivo.</small>
                            </div>
                            <div class="form-group">
                                <label for="imagen_url">O ingresa una URL de la Imagen:</label>
                                <input type="text" class="form-control" id="imagen_url" name="imagen_url">
                                <small class="form-text text-muted">Si no subes un archivo, puedes proporcionar una URL.</small>
                            </div>
                            <div class="form-group">
                                <label for="categoria">Categoría:</label>
                                <br>
                                <?php imprimirCategorias()?>
                            </div>
                            <button type="submit" name="enviar" class="btn btn-primary">Publicar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
include './elementos_pagina/footer.php';
?>
<script src="../assets/js/jquery.min.js"></script>
<script src="../assets/js/bootstrap.min.js"></script>
<script src="../assets/js/wow.min.js"></script>
<script src="../assets/js/slick.min.js"></script>
<script src="../assets/js/custom.js"></script>
</body>
</html>