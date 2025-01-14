<?php
/*Este archivo se encarga de mostrar el perfil del usuario*/
require_once "./funciones.php";
require_once "./funciones_paises/funciones_paises.php";

empezarSesion();

$id_usuario = $_GET["id_usuario"] ?? null;

/*Se verifica que el usuario existe*/
if (verificarUsuario($id_usuario)) {
    $usuario = obtenerUsuario($id_usuario);
}

/*Si no se redirige al 404 de usuarios*/
else {
    header("Location: 404_usuario.php");
    exit();
}

/*Aquí se cambia el email*/
if (isset($_POST['cambiar_email']) && $usuario) {
    $nuevo_email = filter_var($_POST['nuevo_email'], FILTER_SANITIZE_EMAIL);
    $contrasenia_actual = filter_var($_POST['contrasenia_actual']);

    if (password_verify($contrasenia_actual, $usuario['contrasenia'])) {
        actualizarEmailUsuario($id_usuario, $nuevo_email);
        echo "Email cambiado correctamente.";
    } else {
        echo "Contraseña incorrecta.";
    }
}

/*Aquí la contraseña*/
if (isset($_POST['cambiar_contrasenia']) && $usuario) {
    $contrasenia_actual = filter_var($_POST['contrasenia_actual']);
    $nueva_contrasenia = filter_var($_POST['nueva_contrasenia']);

    if (password_verify($contrasenia_actual, $usuario['contrasenia'])) {
        $hash_contrasenia = password_hash($nueva_contrasenia, PASSWORD_DEFAULT);
        actualizarContraseniaUsuario($id_usuario, $hash_contrasenia);
        echo "Contraseña actualizada correctamente.";
    } else {
        echo "Contraseña actual incorrecta.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>visaPages | Perfil</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/animate.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/slick.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/theme.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/7.2.3/css/flag-icons.min.css">
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
        <div class="container mt-4">
            <h1 class="text-center">Perfil de Usuario</h1>
            <div class="card mx-auto" style="max-width: 600px;">
                <div class="card-body">
                    <!--Aquí se imprime toda la información del usuario-->
                    <h3 class="card-title">Información Personal</h3>
                    <p><strong>Nombre:</strong> <?php echo htmlspecialchars($usuario['nombre']);
                        echo ' <span class="fi fi-'.strtolower($usuario['codigo_pais']).'"></span>';
                    ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($usuario['email']); ?></p>
                    <p><strong>Rol:</strong> <?php echo htmlspecialchars($usuario['rol']); ?></p>
                    <p><strong>Usuario desde:</strong> <?php echo htmlspecialchars($usuario['creado_en']); ?></p>
                    <p><strong>IP Registrada:</strong> <?php echo htmlspecialchars($usuario['ip'] ?? 'No registrada'); ?></p>
                    <p><strong>País:</strong> <?php echo htmlspecialchars(obtenerNombrePais($usuario['codigo_pais']));
                    ?></p>
                    <!--Aquí sale la opción de editar el perfil, solo si el usuario logado es el admin-->
                    <?php if (verificarRol(obtenerToken())=="administrador") {
                        echo '<a href="editar_perfil.php?id_usuario='.$id_usuario.'" class="btn btn-primary">Editar Perfil</a>';
                    }?>
                    <!--Este código que permite cambiar el email y la contraseña solo sale para el usuario logado-->
                    <?php if (obtenerIdUsuario(obtenerToken())==$id_usuario) {
                        echo '<a href="cerrar_sesion.php" class="btn btn-danger">Cerrar Sesión</a>
                        <h3>Cambiar Correo Electrónico</h3>
                    <!--Este es el formulario para cambiar el email y la contraseña-->
                    <form method="post">
                        <label>Nuevo Email:</label>
                        <input type="email" name="nuevo_email" required>
                        <br>
                        <label>Contraseña Actual:</label>
                        <input type="password" name="contrasenia_actual" required>
                        <br>
                        <button type="submit" name="cambiar_email">Cambiar Email</button>
                    </form>

                    <h3>Cambiar Contraseña</h3>
                    <form method="post">
                        <label>Contraseña Actual:</label>
                        <input type="password" name="contrasenia_actual" required>
                        <br>
                        <label>Nueva Contraseña:</label>
                        <input type="password" name="nueva_contrasenia" required>
                        <br>
                        <button type="submit" name="cambiar_contrasenia">Cambiar Contraseña</button>
                    </form>';
                    }?>
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