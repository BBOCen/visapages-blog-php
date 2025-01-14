<?php
/*Este es la página donde el admin puede cambiar el perfil de un usuario*/
require_once "./funciones.php";
require_once "./funciones_paises/funciones_paises.php";

empezarSesion();

if (!verificarLogin()) {
    header("Location: login.html");
    exit();
}

if (verificarRol(obtenerToken()) != "administrador") {
    header('Location: index.php');
    exit();
}

$usuario = null;
$id_usuario = null;
/*Si no existe el usuario, se redirige a la página de 404*/
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id_usuario"])) {
    $id_usuario = $_GET["id_usuario"];
    if (verificarUsuario($id_usuario)) {
        $usuario = obtenerUsuario($id_usuario);
    } else {
        header('Location: 404_usuario.php');
        exit();
    }
}

/*Si no se introduce ningún id de usuario, por defecto saldrá el perfil del usuario que realiza los cambios*/
if (!isset($_GET["id_usuario"])) {
    $id_usuario=obtenerIdUsuario(obtenerToken());
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>visaPages | Editar Perfil</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/animate.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/slick.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/theme.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
</head>
<body>
<div id="preloader">
    <div id="status">&nbsp;</div>
</div>
<a class="scrollToTop" href="#"><i class="fa fa-angle-up"></i></a>
<div class="container">
    <?php include "./elementos_pagina/header.php"; ?>
    <section id="mainContent">
        <div class="container mt-4">
            <h1 class="text-center">Perfil de Usuario</h1>
            <div class="card mx-auto" style="max-width: 600px;">
                <div class="card-body">
                    <!--Este es el formulario para editar el perfil del usuario-->
                    <h3 class="card-title">Editar Perfil Usuario</h3>
                    <form method="post" action="./actualizar_usuario.php">
                        <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($id_usuario) ?>">
                        <label>ID del usuario: </label> <?= htmlspecialchars($id_usuario) ?>
                        <br>
                        <label for="nombre">Nombre:</label><br>
                        <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($usuario['nombre'] ?? '') ?>"><br><br>

                        <label for="email">Email:</label><br>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario['email'] ?? '') ?>"><br><br>

                        <label for="contrasenia">Nueva Contraseña (dejar vacío para no cambiar):</label><br>
                        <input type="password" id="contrasenia" name="contrasenia"><br><br>

                        <label for="codigo_pais">Código del País (opcional):</label><br>
                        <input type="text" id="codigo_pais" name="codigo_pais" value="<?= htmlspecialchars($usuario['codigo_pais'] ?? '') ?>"><br><br>

                        <button type="submit">Actualizar Perfil</button>
                    </form>
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
