<?php
/*Esta es la página encargada de listar todos los artículos*/
require_once "./funciones.php";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    /*Este es el código encargadado de obtener todos los artículos y definir cuántas páginas habrá*/
    $pagina = isset($_GET["pagina"]) ? (int)$_GET["pagina"] : 1;

    $articulos = obtenerTodosLosArticulos();

    /*Habrán 6 artículos por página*/
    $articulos_por_pagina = 6;
    $total_articulos = count($articulos);
    $total_paginas = (int)ceil($total_articulos / $articulos_por_pagina);

    if ($pagina < 1 || $pagina > $total_paginas) {
        header("Location: articulos.php?pagina=1");
        exit();
    }

    $inicio = ($pagina - 1) * $articulos_por_pagina;

    /*Este código crea el array con los artículos que saldrán en la página*/
    $articulos_pagina = array_slice($articulos, $inicio, $articulos_por_pagina);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>visaPages | Artículos</title>
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
        <div class="content_middle">
            <div class="col-lg-3 col-md-3 col-sm-3">
                <div class="content_middle_leftbar">
                    <div class="single_category wow fadeInDown">
                        <h2><span class="bold_line"><span></span></span>
                            <span class="solid_line"></span>
                            <a href="archive1.html" class="title_text">Descubre</a>
                        </h2>
                        <ul class="catg1_nav">
                            <?php obtenerArticuloAleatorioIndex([1, 2]); ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="content_middle_middle">
                    <div class="slick_slider2">
                        <?php obtenerCarouselAleatorioGrande(); ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3">
                <div class="content_middle_rightbar">
                    <div class="single_category wow fadeInDown">
                        <h2><span class="bold_line"><span></span></span>
                            <span class="solid_line"></span>
                            <a href="archive1.html" class="title_text">Explora</a>
                        </h2>
                        <ul class="catg1_nav">
                            <?php obtenerArticuloAleatorioIndex([3, 4]); ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="content_bottom">
            <div class="col-lg-8 col-md-8">
                <div class="content_bottom_left">
                    <div class="single_category wow fadeInDown">
                        <div class="archive_style_1">
                            <h2>
                                <span class="bold_line"><span></span></span>
                                <span class="solid_line"></span>
                                <span class="title_text">Explora todos los artículos</span>
                            </h2>
                            <?php
                            /*Aquí se listan todos los artículos del array de la página*/
                            foreach ($articulos_pagina as $articulo) {
                                obtenerArticulosSecundario($articulo['id']);
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="pagination_area">
                    <nav>
                        <ul class="pagination">
                            <!--Este es el código encargado de crear las páginas-->
                            <?php
                            for ($i = 1; $i <= $total_paginas; $i++) {
                                /*Aquí se compara la variable $i a la página, si es igual se asigna la clase css "active" al número de la página*/
                                /*Así la página actual aparece con un cuadro de otro estilo para que el usuario sepa en qué página está*/
                                $clase_activa_pagina = "";
                                if ($i==$pagina) {
                                    $clase_activa_pagina = "active";
                                }
                                echo "<li class='$clase_activa_pagina'><a href='articulos.php?pagina=$i'>$i</a></li>";
                            }
                            ?>
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
