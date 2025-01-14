<?php
/*Este es el archivo que contendrá todas las funciones necesarias para que funcione la web correctamente*/
require_once "./funciones.php";

/*Utilizamos una función personalizada para empezar la sesión, ya que a veces da eror de que ya se ha empezado la sesión*/
empezarSesion();

/*El token es la llave que permite que el usuario inicie sesión*/
$token=obtenerToken();

/*Si la cookie no está activa, se desactiva el login*/
if (!cookieActiva($token)) {
    desactivarLogin($token);
}

/*Si no está puesto la id de artículo en la sesión, la igualamos a -1, ya que esta variable es necesaria para determinar si el usuario está mirando un artículo*/
if (!isset($_SESSION['id_articulo'])) {
    $_SESSION['id_articulo'] = -1;
}
?>

<header id="header">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="header_top">
                <div class="header_top_left">
                    <!--Imprimimos un menú en función de si el ussuario está logado o no-->
                    <?php if (verificarLogin() && verificarExpiracion($token)): ?>
                        <ul class="top_nav">
                            <?php
                            /*Si está logado, se obtendrá su nombre para que salga en el perfil*/
                            if (isset($_SESSION['token'])) {
                                $nombre_usuario = htmlspecialchars(obtenerNombreUsuario($_SESSION['token']));
                            }
                            else {
                                $nombre_usuario = htmlspecialchars(obtenerNombreUsuario($_COOKIE['remember_token']));
                            }
                            ?>
                            <!--Este es el enlace al perfil-->
                            <li><a href="./perfil.php?id_usuario= <?php echo obtenerIdUsuario(obtenerToken())?>"><?php echo $nombre_usuario; ?></a></li>
                            <!--Este código verifica si el usuario es un admin, si lo es, saldrán enlaces relacionados con la gestión del blog-->
                            <?php
                                if (verificarRol($token)=="administrador") {
                                    echo "<li><a href='./administrar_articulos.php'>Administrar artículos</a></li>";
                                    echo "<li><a href='./administrar_categorias.php'>Administrar categorías</a></li>";
                                    echo "<li><a href='./publicar_articulo.php'>Publicar artículo</a></li>";
                                }
                                $articulo_actual=$_SESSION['id_articulo'];
                                /*Este código verifica que el usuario logado es un administrador y que la página actual es un artículo*/
                                if (verificarRol($token)=="administrador" && str_contains(obtenerUrlActual(), "mostrar_articulo.php") && $articulo_actual!=-1) {
                                    echo "<li><a href='./eliminar_articulo.php?id_articulo=$articulo_actual'>Eliminar artículo</a></li>";
                                    echo "<li><a href='./editar_articulo.php?id_articulo=$articulo_actual'>Editar artículo</a></li>";
                                }
                            ?>
                            <li><a href="./cerrar_sesion.php">Cerrar sesión</a></li>
                        </ul>
                    <!--Este es el menú que viene por defecto si el usuario no está logado-->
                    <?php else: ?>
                        <ul class="top_nav">
                            <li><a href="./index.php">Inicio</a></li>
                            <li><a href="./login.html">Iniciar Sesión</a></li>
                            <li><a href="./registro.html">Registrar cuenta</a></li>
                        </ul>
                    <?php endif; ?>
                </div>
                <!--Este parte representa el buscador de artículos-->
                <div class="header_top_right">
                    <form action="mostrar_resultados.php" class="search_form" method="get">
                        <input type="text" name="busqueda" placeholder="Buscar artículos">
                        <input type="submit" value="">
                    </form>
                </div>
                <div class="header_bottom">
                    <div class="header_bottom_left"><a class="logo" href="./index.php">visa<strong>Pages</strong> <span>Información de visados</span></a></div>
                </div>
            </div>
        </div>
    </div>
</header>
<div id="navarea">
    <nav class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav custom_nav">
                    <li class=""><a href="./index.php">Inicio</a></li>
                    <li><a href="./articulos.php?pagina=1">Artículos</a></li>

                    <!--Este código imprime las categorias en el header-->
                    <?php imprimirCategoriasHeader();?>
                </ul>
            </div>
        </div>
    </nav>
</div>
