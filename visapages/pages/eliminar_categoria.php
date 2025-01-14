<?php
require_once "./funciones.php";

/*Esta es la página que confirma si el usuario quiere eliminar una categoría*/

if (!verificarLogin() || verificarRol(obtenerToken()) != "administrador") {
    header('Location: index.php');
    exit();
}

/*Este código maneja la opción del usuario*/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirmar'])) {
        eliminarCategoria($_GET['id_categoria']);
        header('Location: ./administrar_categorias.php');
        exit();
    } elseif (isset($_POST['cancelar'])) {
        header('Location: ./administrar_categorias.php');
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar Eliminación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<!--Al igual que antes, se usa un modal para determinar si se elimina el artículo-->
<div class="modal fade show" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true" style="display: block;">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar esta categoría? Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="cancelar" class="btn btn-secondary">Cancelar</button>
                    <button type="submit" name="confirmar" class="btn btn-danger">Sí, eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>