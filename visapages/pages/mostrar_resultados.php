<?php
require_once "funciones.php";
/*Este código muestra los tesultados de una búsqueda*/
$busqueda = $_GET['busqueda'];
$pagina = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

if ($_SERVER["REQUEST_METHOD"] == "GET" && $busqueda !== '') {
    $ids_articulos = buscarArticulosTitulo($busqueda);

    /*Aquí se maneja la cantidad de páginas que hay si hay más de 6 artículos*/
    $articulos_por_pagina = 6;
    $total_articulos = count($ids_articulos);
    $max_paginas = ceil($total_articulos / $articulos_por_pagina);

    if (empty($ids_articulos)) {
        $max_paginas = 1;
    }

    if ($pagina < 1 || $pagina > $max_paginas) {
        header("Location: mostrar_resultados.php?busqueda=$busqueda&pagina=1");
        exit();
    }

    $inicio = ($pagina - 1) * $articulos_por_pagina;
    $articulos_mostrar = array_slice($ids_articulos, $inicio, $articulos_por_pagina);
} else {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>visaPages | Mostrar Resultados</title>
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
            <div class="col-lg-8 col-md-8">
                <div class="content_bottom_left">
                    <div class="single_category wow fadeInDown">
                        <div class="archive_style_1">
                            <h2>
                                <span class="bold_line"><span></span></span>
                                <span class="solid_line"></span>
                                <span class="title_text">Resultados de la búsqueda: "<?php echo htmlspecialchars($busqueda); ?>"</span>
                            </h2>
                            <!--Aquí se muestran los resultados de la búsqueda-->
                            <?php if (!empty($articulos_mostrar)) : ?>
                                <?php foreach ($articulos_mostrar as $id_articulo) : ?>
                                    <?php obtenerArticulosSecundario($id_articulo); ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <p>No se encontraron artículos para esta búsqueda.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="pagination_area">
                    <nav>
                        <ul class="pagination">
                            <?php obtenerPaginasResultados($max_paginas, $pagina, $busqueda); ?>
                        </ul>
                    </nav>
                </div>
            </div>
            <?php require_once "./elementos_pagina/side.php"; ?>
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
