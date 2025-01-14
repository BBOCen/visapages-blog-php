<!--Esta es la página de 404 no encontrado del usuario -->
<!DOCTYPE html>
<html>
<head>
    <title>visaPages | 404</title>
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
    require_once "./elementos_pagina/header.php";
    ?>
    <section id="mainContent">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="error_page_content">
                    <h1>404</h1>
                    <h2>Lo sentimos :(</h2>
                    <h3>Ese usuario no existe.</h3>
                    <p class="wow fadeInLeftBig">Haz clic este enlace para ir al <a href="./index.php">inicio</a></p>
                </div>
            </div>
        </div>
    </section>
</div>
<?php
require_once "./elementos_pagina/footer.php";
?>
<script src="../assets/js/jquery.min.js"></script>
<script src="../assets/js/bootstrap.min.js"></script>
<script src="../assets/js/wow.min.js"></script>
<script src="../assets/js/slick.min.js"></script>
<script src="../assets/js/custom.js"></script>
</body>
</html>