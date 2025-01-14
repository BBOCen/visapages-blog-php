<?php
/*Esta página muestra el artículo*/

$articulo_existe=false;
require_once "funciones.php";
empezarSesion();
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id_articulo'])) {
    $id_articulo = $_GET['id_articulo'];
    $existe = verificarArticulo($id_articulo);
    /*Si existe, se obtiene su contenido*/
    if ($existe) {
        $articulo_existe=true;
        $contenido_articulo=obtenerArticulo($id_articulo);
        /*Se actualizan sus visitas*/
        actualizarVisitas($id_articulo);
        /*Se obtienen los comentarios*/
        $comentarios = obtenerComentarios($id_articulo);
        /*Aquí se va a guardar la id del artículo actual en la variable de sesión, se utiliza para luego verificar si la página actual es un artículo*/
        $_SESSION['id_articulo']=$id_articulo;
    }
    else {
        header('Location: 404_articulo.php');
        /*Esto significa que el articulo no se ha encontrado, por lo tanto, el id artiulo es -1*/
        $_SESSION['id_articulo']=-1;
        exit();
    }
}
else {
    header('Location: 404_articulo.php');
    exit();
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>visaPages | Artículo</title>
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
<!--<div id="preloader">
    <div id="status">&nbsp;</div>
</div>-->
<a class="scrollToTop" href="#"><i class="fa fa-angle-up"></i></a>
<div class="container">
    <?php
    require_once "./elementos_pagina/header.php";
    ?>
    <section id="mainContent">
        <div class="content_bottom">
            <div class="col-lg-8 col-md-8">
                <div class="content_bottom_left">
                    <div class="single_page_area">
                        <ol class="breadcrumb">
                            <li><a href="./index.php">Inicio</a></li>
                            <!--Aquí se muestra el contenido obtenido del artículo-->
                            <?php
                                echo "<li><a href='./categoria_articulo?categoria=" . urlencode($contenido_articulo["categoria"]) . "'>" . ucwords($contenido_articulo["categoria"]) . "</a></li>";
                            ?>
                            <li class="active"><?php echo $contenido_articulo["titulo"] ?></li>
                        </ol>
                        <h2 class="post_titile"><?php echo $contenido_articulo["titulo"] ?></h2>
                        <div class="single_page_content">
                            <div class="post_commentbox"> <a href="./perfil.php?id_usuario=<?php echo $contenido_articulo['id_usuario']?>"><i class="fa fa-user"></i><?php echo $contenido_articulo["autor"] ?></a> <span><i class="fa fa-calendar"></i><?php echo $contenido_articulo["fecha_creacion"] ?></span> <a href="./mostrar_categoria.php?id_categoria=<?php echo $contenido_articulo['id_categoria'];?>"><i class="fa fa-tags"></i><?php echo ucwords($contenido_articulo["categoria"])?></a> </div>
                            <img class="img-center" src="<?php echo $contenido_articulo["imagen"];?>" alt="404 Imagen no existe">
                            <?php
                                echo $contenido_articulo["contenido"];
                            ?>
                            <br><br>
                            <button class="btn btn-primary"><?php echo ucwords($contenido_articulo["categoria"])?></button>
                        </div>
                    </div>
                </div>
                <div class="post_pagination">
                    <!--Esto se encarga de obtener el artículo anterior a este-->
                        <?php
                            $id_articulo_nuevo_anterior=obtenerIdArticuloAnterior($id_articulo);
                            echo '<div class="prev"> <a class="angle_left" href="mostrar_articulo.php?id_articulo='.$id_articulo_nuevo_anterior.'"><i class="fa fa-angle-double-left"></i></a>';
                            echo '<div class="pagincontent"><span>Artículo anterior</span> <a href="./mostrar_articulo.php?id_articulo=' . $id_articulo_nuevo_anterior . '">' . substr(obtenerTituloArticulo($id_articulo_nuevo_anterior), 0, 70) . '...</a></div>';
                        ?>
                    </div>
                    <div class="next">
                        <!--Y esto el posterior-->
                        <?php
                            $id_articulo_nuevo_posterior=obtenerIdArticuloPosterior($id_articulo);
                            echo '<div class="pagincontent"><span>Artículo anterior</span> <a href="./mostrar_articulo.php?id_articulo=' . $id_articulo_nuevo_posterior . '">' . substr(obtenerTituloArticulo($id_articulo_nuevo_posterior), 0, 70) . '...</a></div>';
                            echo '<a class="angle_right" href="./mostrar_articulo.php?id_articulo='.$id_articulo_nuevo_posterior.'"><i class="fa fa-angle-double-right"></i></a> </div>';
                        ?>

                </div>
                <!--Estos son los links para que se comparta el artículo-->
                <?php
                echo '<div class="share_post"> 
                        <a class="facebook" href="https://www.facebook.com/sharer/sharer.php?u='.obtenerUrlActualCompleta().'"><i class="fa fa-facebook"></i>Facebook</a> 
                        <a class="twitter" href="https://twitter.com/intent/tweet?url='.obtenerUrlActualCompleta().'&text=¡Lee%20este%20articulo%20interesante!"><i class="fa fa-twitter"></i>Twitter</a> 
                        <a class="linkedin" href="https://www.linkedin.com/sharing/share-offsite/?url='.obtenerUrlActualCompleta().'"><i class="fa fa-linkedin"></i>LinkedIn</a> 
                        <a class="pinterest" href="https://pinterest.com/pin/create/button/?url='.obtenerUrlActualCompleta().'&media='.$contenido_articulo["imagen"].'"><i class="fa fa-pinterest"></i>Pinterest</a> 
                    </div>';
                ?>
                <!--Aquí se listan todos los comentarios, si hay-->
                <div class="comentarios">
                    <h3>Comentarios</h3>
                    <?php if (!empty($comentarios)): ?>
                        <?php foreach ($comentarios as $comentario): ?>
                            <div class="comentario">
                                <div class="single_page_content">
                                    <div class="post_commentbox"> <a href="./perfil.php?id_usuario=<?php echo $comentario['id_usuario']?>"><i class="fa fa-user"></i><?php echo obtenerNombreUsuarioId($comentario['id_usuario']) ?></a> <span><i class="fa fa-calendar"></i><?php echo $comentario['fecha'] ?></span> </div>
                                </div>
                                <p><?php echo htmlspecialchars($comentario['contenido']); ?></p>
                            </div>
                            <hr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No hay comentarios. ¡Sé el primero en comentar!</p>
                    <?php endif; ?>
                </div>
                <!--Solo se permite dejar uno si el usuario está logado-->
                <?php if (verificarLogin()): ?>
                    <h3>Deja un comentario</h3>
                    <form method="POST" action="guardar_comentario.php">
                        <input type="hidden" name="id_articulo" value="<?php echo $id_articulo; ?>">
                        <div class="form-group">
                            <textarea class="form-control" name="contenido" rows="4" placeholder="Escribe tu comentario..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </form>
                <?php else: ?>
                    <p><a href="login.html">Inicia sesión</a> para dejar un comentario.</p>
                <?php endif; ?>
                <div class="similar_post">
                    <h2>Quizás Te Gusta...<i class="fa fa-thumbs-o-up"></i></h2>
                    <ul class="small_catg similar_nav wow fadeInDown animated">
                        <?php
                            obtenerArticulosAleatorios();
                        ?>
                    </ul>
                </div>
            </div>
            <?php
            require_once "./elementos_pagina/side.php";
            ?>
        </div>
    </section>
</div>
<?php
    require_once './elementos_pagina/footer.php';
?>
<script src="../assets/js/jquery.min.js"></script>
<script src="../assets/js/bootstrap.min.js"></script>
<script src="../assets/js/wow.min.js"></script>
<script src="../assets/js/slick.min.js"></script>
<script src="../assets/js/custom.js"></script>
</body>
</html>