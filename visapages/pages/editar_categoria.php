<?php
/*Este código es el encargado de editar las categorías, solo un admin puede editarlas*/
require_once "./funciones.php";
empezarSesion();

/*Se verifica que el ussuario es un admin*/
if (!verificarLogin() || verificarRol(obtenerToken()) != "administrador") {
    header('Location: index.php');
    exit();
}

$categoria_existe = false;
$id_categoria = $_GET['id_categoria'] ?? null;

/*Aquí se determina si existe la categoría a editar*/
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($id_categoria)) {
    $existe = verificarCategoria($id_categoria);
    if ($existe) {
        $articulo_existe = true;
        $categoria = obtenerContenidoCategoria($id_categoria);
    } else {
        header('Location: 404.php');
        exit();
    }
} else {
    header('Location: 404.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>visaPages | Editar Categoría</title>
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
        <h1>Editar Categoría</h1>
        <div class="content_bottom">
            <div class="col-lg-8 col-md-8">
                <div class="content_bottom_left">
                    <div class="single_page_area">

                        <h2 class="post_titile"><?php echo $categoria['nombre']; ?></h2>
                        <!--Aquí está el formulario para editar la categoría-->
                        <form method="POST" action="./actualizar_categoria.php">
                            <input type="hidden" name="id_categoria" value="<?php echo $id_categoria; ?>">
                            <div class="form-group">
                                <label for="titulo">Nombre:</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($categoria['nombre']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="descripcion">Descripción:</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="10" required><?php echo htmlspecialchars($categoria['descripcion']); ?></textarea>
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