<?php
/*Esta página es la encargada de administrar los artículos*/
require_once "./funciones.php";
empezarSesion();

/*Se verifica que el usuario sea un admin*/
if (!verificarLogin() || verificarRol(obtenerToken()) != "administrador") {
    header('Location: index.php');
    exit();
}

/*Si los es, se obtienen todos los artículos*/
// Obtener los artículos de la base de datos
$articulos = obtenerTodosLosArticulos();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>visaPages | Administrar Artículos</title>
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
    <?php require_once "./elementos_pagina/header.php"; ?>
    <section id="mainContent">
        <div class="content_bottom">
            <div class="col-lg-12 col-md-12">
                <div class="content_bottom_left">
                    <div class="single_category wow fadeInDown">
                        <div class="archive_style_1">
                            <h2>
                                <span class="bold_line"><span></span></span>
                                <span class="solid_line"></span>
                                <span class="title_text">Administrar Artículos</span>
                            </h2>
                            <!--Se crea una tabla con todos los artículos, con opciones para modificarlos y/o eliminarlos-->
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Título</th>
                                        <th>Fecha de Creación</th>
                                        <th>Acciones</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if (!empty($articulos)) : ?>
                                        <?php foreach ($articulos as $articulo) : ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($articulo['id']); ?></td>
                                                <td><?php echo htmlspecialchars($articulo['titulo']); ?></td>
                                                <td><?php echo htmlspecialchars($articulo['fecha_creacion']); ?></td>
                                                <td>
                                                    <a href="editar_articulo.php?id_articulo=<?php echo $articulo['id']; ?>" class="btn btn-sm btn-primary" title="Editar">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <a href="eliminar_articulo.php?id_articulo=<?php echo $articulo['id']; ?>" class="btn btn-sm btn-danger" title="Eliminar"">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <tr>
                                            <td colspan="4" class="text-center">No hay artículos disponibles.</td>
                                        </tr>
                                    <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php require_once "./elementos_pagina/footer.php"; ?>
<script src="../assets/js/jquery.min.js"></script>
<script src="../assets/js/bootstrap.min.js"></script>
<script src="../assets/js/wow.min.js"></script>
<script src="../assets/js/slick.min.js"></script>
<script src="../assets/js/custom.js"></script>
</body>
</html>
