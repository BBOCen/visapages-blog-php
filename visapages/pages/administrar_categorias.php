<?php
/*Esta página es la encargada de administrar las categorías*/
require_once "./funciones.php";
empezarSesion();

/*Se verifica que el usuario esté logado*/
if (!verificarLogin() || verificarRol(obtenerToken()) != "administrador") {
    header('Location: index.php');
    exit();
}

/*Si es así, se obtienen los datos de todas las categorías*/
$categorias = obtenerTodasLasCategorias();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>visaPages | Administrar Categorías</title>
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
                                <span class="title_text">Administrar Categorías</span>
                            </h2>
                            <div class="table-responsive">
                                <!--Se crea una tabla con todos los datos de las categorías y opciones para eliminarlas o modificarlas-->
                                <table class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Acciones</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if (!empty($categorias)) : ?>
                                        <?php foreach ($categorias as $categoria) : ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($categoria['id']); ?></td>
                                                <td><?php echo htmlspecialchars($categoria['nombre']); ?></td>
                                                <td><?php echo htmlspecialchars($categoria['descripcion']); ?></td>
                                                <td>
                                                    <a href="editar_categoria.php?id_categoria=<?php echo $categoria['id']; ?>" class="btn btn-sm btn-primary" title="Editar">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <a href="eliminar_categoria.php?id_categoria=<?php echo $categoria['id']; ?>" class="btn btn-sm btn-danger" title="Eliminar"">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <tr>
                                            <td colspan="4" class="text-center">No hay categorías disponibles.</td>
                                        </tr>
                                    <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!--Aquí se crea una opción para crear una categoría nueva-->
                            <div class="mt-4">
                                <h3>Crear Nueva Categoría</h3>
                                <form action="crear_categoria.php" method="POST">
                                    <div class="form-group">
                                        <label for="nombre">Nombre</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="descripcion">Descripción</label>
                                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-success">Crear</button>
                                </form>
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

