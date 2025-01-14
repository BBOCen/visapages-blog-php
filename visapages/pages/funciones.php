<?php
/*Este archivo contiene todas las funciones necesarias para el funcionamiento del programa*/

/*Aquí se incluye la conexión a la bbdd*/
require "./conexion_bbdd.php";

/*Empezamos la sesión para usar $_SESSION*/
empezarSesion();

/*Aquí cacheamos estos datos para evitar hacer consultas innecesarias a la bbdd*/
$token_cacheada = null;
$id_usuario_cacheada = null;
$cookie_activa_cacheada = null;

/*Esta función empieza la sesión, solamente si no hay una ya comenzada*/
function empezarSesion() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

/*Esta función obtiene el token actual del usuario*/
function obtenerToken() {
    global $token_cacheada;
    /*El token será el de la sesión si esta existe*/
    if (isset($_SESSION["token"])) {
        $token_cacheada = $_SESSION["token"];
    }
    /*Si no será el de la cookie (por si el usuario quiere que el sistema se acuerde de él)*/
    elseif (isset($_COOKIE["remember_token"]) && cookieActiva($_COOKIE["remember_token"])) {
        $token_cacheada = $_COOKIE["remember_token"];
    }
    /*Si es null, el usuario no está logado*/
    else {
        $token_cacheada = null;
    }
    return $token_cacheada;
}

/*Esta función obtiene la id del usuario usando su token*/
function obtenerIdUsuario($token) {
    global $id_usuario_cacheada, $conn;
    if ($id_usuario_cacheada !== null) {
        return $id_usuario_cacheada;
    }

    $stmt = $conn->prepare("SELECT id_usuario FROM sesiones WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->bind_result($id_usuario);
    $stmt->fetch();
    $stmt->close();

    $id_usuario_cacheada = $id_usuario;
    return $id_usuario;
}

/*Esta función verifica si la cookie está activa en la bbdd*/
function cookieActiva($token) {
    global $cookie_activa_cacheada, $conn;
    $stmt = $conn->prepare("SELECT activo FROM sesiones WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->bind_result($activo);
    $stmt->fetch();
    $stmt->close();

    if ($activo==0) {
        $cookie_activa_cacheada = false;
    }
    else {
        $cookie_activa_cacheada = true;
    }

    return $cookie_activa_cacheada;
}

/*Esta función verifica si el usuario está logado*/
function verificarLogin() {
    $token = obtenerToken();
    if ($token === null) {
        return false;
    }

    if (!verificarExpiracion($token)) {
        return false;
    }

    return true;
}

/*Esta función verifica si la cookie está expirada*/
function verificarExpiracion($token) {
    global $conn;

    $stmt = $conn->prepare("SELECT fecha_expiracion FROM sesiones WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->bind_result($fecha_expiracion);
    $stmt->fetch();
    $stmt->close();

    if ($fecha_expiracion === null || strtotime($fecha_expiracion) <= time()) {
        desactivarLogin($token);
        return false;
    }
    return true;
}

/*Esta función cierra la sesión del usuario*/
function desactivarLogin($token) {
    desactivarCookie();
    desactivarToken($token);
    destruirSesion();
}

/*Esta función desactiva el token del usuario*/
function desactivarToken($token) {
    global $conn;

    $stmt = $conn->prepare("UPDATE sesiones SET activo = 0 WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->close();
}

/*Esta desactiva la cookie*/
function desactivarCookie() {
    global $conn;

    if (isset($_COOKIE["remember_token"])) {
        setcookie("remember_token", "", time() - 3600, "/visapages", "", false, true);

        $stmt = $conn->prepare("UPDATE sesiones SET activo = 0 WHERE token = ?");
        $stmt->bind_param("s", $_COOKIE["remember_token"]);
        $stmt->execute();
        $stmt->close();
    }
}

/*Esta "destruye" la sesión*/
function destruirSesion() {
    session_unset();
    session_destroy();
}

/*Esta actualiza los datos del usuario*/
function actualizarUsuario($id_usuario, $nombre, $email, $contrasenia, $codigo_pais) {
    global $conn;
    $stmt = $conn->prepare("UPDATE usuarios SET nombre = ?, email = ?, contrasenia = ?, codigo_pais = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $nombre, $email, $contrasenia, $codigo_pais, $id_usuario);
    $stmt->execute();
    $stmt->close();
}

/*Esta registra una cuenta nueva*/
function registrarUsuario($nombre, $email, $contrasenia, $pais, $ip) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, contrasenia, codigo_pais, ip) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nombre, $email, $contrasenia, $pais, $ip);
    $stmt->execute();
    $stmt->close();
}

/*Esta actualiza el email*/
function actualizarEmailUsuario($id_usuario, $nuevo_email) {
    global $conn;
    $stmt = $conn->prepare("UPDATE usuarios SET email = ? WHERE id = ?");
    $stmt->bind_param("si", $nuevo_email, $id_usuario);
    $stmt->execute();
    $stmt->close();
}

/*Esta la contraseña*/
function actualizarContraseniaUsuario($id_usuario, $hash_contrasenia) {
    global $conn;
    $stmt = $conn->prepare("UPDATE usuarios SET contrasenia = ? WHERE id = ?");
    $stmt->bind_param("si", $hash_contrasenia, $id_usuario);
    $stmt->execute();
    $stmt->close();
}

/*Esta obtiene nombre del usuario usando el token*/
function obtenerNombreUsuario($token) {
    global $conn;

    $stmt = $conn->prepare("SELECT usuarios.nombre FROM usuarios INNER JOIN sesiones ON usuarios.id = sesiones.id_usuario WHERE sesiones.token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->bind_result($nombre);
    $stmt->fetch();
    $stmt->close();

    return htmlspecialchars($nombre);
}

/*Igual que el anterior, pero usando la id*/
function obtenerNombreUsuarioId($id_usuario) {
    global $conn;

    $stmt = $conn->prepare("SELECT usuarios.nombre FROM usuarios INNER JOIN sesiones ON usuarios.id = sesiones.id_usuario WHERE sesiones.id_usuario = ?");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $stmt->bind_result($nombre);
    $stmt->fetch();
    $stmt->close();

    return htmlspecialchars($nombre);
}

/*Esta verifica si el usuario existe*/
function verificarUsuario($id_usuario) {
    global $conn;
    $stmt= $conn->prepare("SELECT nombre FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $stmt->bind_result($nombre);
    $stmt->fetch();
    $stmt->close();
    if ($nombre === null) {
        return false;
    }
    else {
        return true;
    }
}

/*Esta verifica el rol del usuario, útil para determinar si es admin o no*/
function verificarRol($token) {
    global $conn;
    $id_usuario=obtenerIdUsuario($token);
    $stmt= $conn->prepare("SELECT usuarios.rol FROM usuarios, sesiones WHERE sesiones.token = ? and usuarios.id = sesiones.id_usuario and sesiones.id_usuario = ?");
    $stmt->bind_param("ss", $token, $id_usuario);
    $stmt->execute();
    $stmt->bind_result($rol);
    $stmt->fetch();
    $stmt->close();
    return $rol;
}

/*Esta obtiene los datos del usuario*/
function obtenerUsuario($id_usuario) {
    global $conn;
    $stmt = $conn->prepare("SELECT usuarios.nombre, usuarios.email, usuarios.contrasenia, usuarios.creado_en, usuarios.rol, usuarios.ip, usuarios.codigo_pais FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $stmt->bind_result($nombre, $email, $contrasenia, $creado_en, $rol, $ip, $codigo_pais);
    $stmt->fetch();
    $stmt->close();

    return [
        "nombre" => $nombre,
        "email" => $email,
        "contrasenia" => $contrasenia,
        "creado_en" => $creado_en,
        "rol" => $rol,
        "ip" => $ip,
        "codigo_pais" => $codigo_pais,
        "id_usuario" => $id_usuario
    ];
}

/*Esta obtiene todas las categorías*/
function obtenerTodasLasCategorias() {
    global $conn;
    $categorias = [];
    $stmt = $conn->prepare("SELECT id, nombre, descripcion FROM categorias");
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $categorias[] = $row;
    }

    $stmt->close();
    return $categorias;
}

/*Esta crea una categoría*/
function crearCategoria($nombre, $descripcion) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO categorias (nombre, descripcion) VALUES (?, ?)");
    $stmt->bind_param("ss", $nombre, $descripcion);
    $stmt->execute();
    $stmt->close();
}

/*Esta actualiza una categoría*/
function actualizarCategoria($id_categoria, $nombre, $descripcion) {
    global $conn;
    $stmt = $conn->prepare("UPDATE categorias SET nombre = ?, descripcion = ? WHERE id = ?");
    $stmt->bind_param("ssi", $nombre, $descripcion, $id_categoria);
    $stmt->execute();
    $stmt->close();
}

/*Esta elimina una categoría*/
function eliminarCategoria($id_categoria) {
    global $conn;
    $stmt=$conn->prepare("DELETE FROM categorias WHERE id = ?");
    $stmt->bind_param("i", $id_categoria);
    $stmt->execute();
    $stmt->close();
}

/*Esta verifica si existe*/
function verificarCategoria($id_categoria) {
    global $conn;
    $existe = false;

    $stmt = $conn->prepare("SELECT id FROM categorias WHERE id = ?");
    $stmt->bind_param("i", $id_categoria);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $existe = true;
    }

    $stmt->close();
    return $existe;
}

/*Esta imprime todas las categorías en el footer*/
function imprimirCategoriasFooter() {
    global $conn;
    $stmt=$conn->prepare("SELECT id FROM categorias");
    $stmt->execute();
    $stmt->bind_result($categoria);

    $categorias=[];
    while ($stmt->fetch()) {
        $categorias[] = $categoria;
    }
    $stmt->close();
    foreach ($categorias as $id_categoria) {
        echo '<li><a href="mostrar_categoria.php?id_categoria='.$id_categoria.'">'.ucwords(obtenerCategoria($id_categoria)).'</a></li>';
    }
}

/*Esta obtiene la id de la categoría*/
function obtenerIdCategoria($categoria) {
    global $conn;
    $id_categoria=0;
    $stmt = $conn->prepare("SELECT id FROM categorias WHERE nombre=?");
    $stmt->bind_param("s", $categoria);
    $stmt->execute();
    $stmt->bind_result($id_categoria);
    $stmt->fetch();
    $stmt->close();
    return $id_categoria;
}

/*Esta publica el artículo*/
function publicarArticulo($titulo, $contenido, $imagen, $id_categoria, $id_usuario) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO articulos (titulo, contenido, imagen, fecha_creacion, id_categoria, id_usuario) VALUES (?, ?, ?, CURRENT_TIMESTAMP, ?, ?)");
    $stmt->bind_param("sssii", $titulo, $contenido, $imagen, $id_categoria, $id_usuario);
    // Execute and check for success
    if ($stmt->execute()) {
        echo "Artículo creado exitosamente.";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

/*Esta obtiene el contenido del artículo*/
function obtenerArticulo($id_articulo) {
    global $conn;
    $stmt = $conn->prepare("SELECT titulo, contenido, imagen, fecha_creacion, id_categoria, id_usuario FROM articulos WHERE id = ?");
    $stmt->bind_param("i", $id_articulo);
    $stmt->execute();
    $stmt->bind_result($titulo, $contenido, $imagen, $fecha_creacion, $id_categoria, $id_usuario);
    $stmt->fetch();
    $stmt->close();

    $autor=obtenerAutor($id_articulo);
    $categoria=obtenerCategoria($id_categoria);

    return [
        "titulo" => $titulo,
        "contenido" => $contenido,
        "imagen" => $imagen,
        "fecha_creacion" => $fecha_creacion,
        "autor" => $autor,
        "categoria" => $categoria,
        "id_articulo" => $id_articulo,
        "id_usuario" => $id_usuario,
        "id_categoria" => $id_categoria
    ];
}

/*Esta el nombre del autor*/
function obtenerAutor($id_articulo) {
    global $conn;
    $stmt = $conn->prepare("SELECT usuarios.nombre FROM usuarios, articulos WHERE articulos.id = ? and usuarios.id = articulos.id_usuario");
    $stmt->bind_param("i", $id_articulo);
    $stmt->execute();
    $stmt->bind_result($autor);
    $stmt->fetch();
    $stmt->close();
    return $autor;
}

/*Esta el nombre de la categoría*/
function obtenerCategoria($id_categoria) {
    global $conn;
    $stmt = $conn->prepare("SELECT nombre FROM categorias WHERE id = ?");
    $stmt->bind_param("i", $id_categoria);
    $stmt->execute();
    $stmt->bind_result($nombre);
    $stmt->fetch();
    $stmt->close();
    return $nombre;
}

/*Esta el contenido de la categoría*/
function obtenerContenidoCategoria($id_categoria) {
    global $conn;
    $stmt = $conn->prepare("SELECT nombre, descripcion FROM categorias WHERE id = ?");
    $stmt->bind_param("i", $id_categoria);
    $stmt->execute();
    $stmt->bind_result($nombre, $descripcion);
    $stmt->fetch();
    $stmt->close();
    return ["nombre" => $nombre, "descripcion" => $descripcion];
}

/*Esta función genera ocho imágenes para poner en el footer*/
function generarImagenesFooter() {
    global $conn;
    $stmt = $conn->prepare("SELECT id, imagen FROM articulos ORDER BY fecha_creacion DESC LIMIT 8");
    $stmt->execute();
    $result = $stmt->get_result();
    $articulos = [];
    while ($row = $result->fetch_assoc()) {
        $articulos[] = $row;
    }
    $stmt->close();
    foreach ($articulos as $articulo) {
        echo '<li><a href="./mostrar_articulo.php?id_articulo=' . $articulo['id'] . '"><img src="' . $articulo['imagen'] . '" alt="404"></a></li>';
    }
}

/*Esta obtiene el título del artículo*/
function obtenerTituloArticulo($id_articulo) {
    global $conn;
    $stmt = $conn->prepare("SELECT titulo FROM articulos WHERE id = ?");
    $stmt->bind_param("i", $id_articulo);
    $stmt->execute();
    $stmt->bind_result($titulo);
    $stmt->fetch();
    $stmt->close();
    return $titulo;
}

/*Aquí se obtiene el artículo posterior (por fecha de creación)*/
function obtenerIdArticuloAnterior($id_articulo) {
    global $conn;
    $stmt=$conn->prepare("SELECT id FROM articulos WHERE fecha_creacion < (SELECT fecha_creacion FROM articulos WHERE id = ?) ORDER BY fecha_creacion DESC LIMIT 1;");
    $stmt->bind_param("i", $id_articulo);
    $stmt->execute();
    $stmt->bind_result($id_articulo);
    $stmt->fetch();
    $stmt->close();
    return $id_articulo;
}

/*Aquí se obtiene el artículo posterior (por fecha de creación)*/
function obtenerIdArticuloPosterior($id_articulo) {
    global $conn;
    $stmt=$conn->prepare("SELECT id FROM articulos WHERE fecha_creacion > (SELECT fecha_creacion FROM articulos WHERE id = ?) ORDER BY fecha_creacion LIMIT 1;");
    $stmt->bind_param("i", $id_articulo);
    $stmt->execute();
    $stmt->bind_result($id_articulo);
    $stmt->fetch();
    $stmt->close();
    return $id_articulo;
}

/*Esta obtiene los artículos más recientes*/
function obtenerArticulosRecientes($cantidad) {
    global $conn;
    $stmt=$conn->prepare("SELECT id FROM articulos LIMIT ?");
    $stmt->bind_param("i", $cantidad);
    $stmt->execute();
    $articulos_recientes=[];
    $stmt->bind_result($id_articulo);
    while($stmt->fetch()){
        $articulos_recientes[]=$id_articulo;
    }
    $articulos=[];
    for ($i=0; $i <$cantidad; $i++) {
        $articulo=obtenerArticulo($articulos_recientes[$i]);
        array_push($articulos,$articulo);
    }
    return $articulos;
}

/*Esta los más populares (con más visitas)*/
function obtenerArticulosPopulares($cantidad) {
    global $conn;
    $stmt = $conn->prepare("SELECT id FROM articulos ORDER BY visitas DESC LIMIT ?");
    $stmt->bind_param("i", $cantidad);
    $stmt->execute();
    $stmt->bind_result($id_articulo);
    $ids = [];
    while ($stmt->fetch()) {
        $ids[] = $id_articulo;
    }
    return $ids;
}

/*Esta obtiene los más recientes y los imprime en el side*/
function obtenerArticulosRecientesSide() {
    $articulos = obtenerArticulosRecientes(3);

    foreach ($articulos as $i => $articulo) {
        $id = htmlspecialchars($articulo['id_articulo'], ENT_QUOTES, 'UTF-8');
        $titulo = htmlspecialchars($articulo['titulo'], ENT_QUOTES, 'UTF-8');
        $imagen = htmlspecialchars($articulo['imagen'], ENT_QUOTES, 'UTF-8');
        /*Ya que los artículos tienen caracteres html, como "<p>", se utilizan estas funciones para eliminarlos*/
        $contenido = html_entity_decode(strip_tags(htmlspecialchars($articulo['contenido'], ENT_QUOTES, 'UTF-8')));
        $contenido_corto = substr($contenido, 0, 140);

        echo '<li>
            <div class="media wow fadeInDown"> 
                <a href="mostrar_articulo.php?id_articulo=' . $id . '" class="media-left">
                    <img alt="" src="' . $imagen . '"> 
                </a>
                <div class="media-body">
                    <h4 class="media-heading">
                        <a href="mostrar_articulo.php?id_articulo=' . $id . '">' . $titulo . '</a>
                    </h4>
                    <p>' . $contenido_corto . '...</p>
                </div>
            </div>
        </li>';
    }
}

/*Igual que la anterior, pero los más populares*/
function obtenerArticulosPopularesSide() {
    $ids_articulos = obtenerArticulosPopulares(3);
    $articulos = [];
    for ($i = 0; $i < count($ids_articulos); $i++) {
        $articulos[$i] = obtenerArticulo($ids_articulos[$i]);
    }
    for ($i = 0; $i < count($articulos); $i++) {
        $id = htmlspecialchars($articulos[$i]['id_articulo'], ENT_QUOTES, 'UTF-8');
        $titulo = htmlspecialchars($articulos[$i]['titulo'], ENT_QUOTES, 'UTF-8');
        $imagen = htmlspecialchars($articulos[$i]['imagen'], ENT_QUOTES, 'UTF-8');
        /*Ya que los artículos tienen caracteres html, como "<p>", se utilizan estas funciones para eliminarlos*/
        $contenido = html_entity_decode(strip_tags(htmlspecialchars($articulos[$i]['contenido'], ENT_QUOTES, 'UTF-8')));
        $contenido_corto = substr($contenido, 0, 140);

        echo '<li>
                <div class="media wow fadeInDown"> <a class="media-left" href="mostrar_articulo.php?id_articulo=' . $id . '"><img src="' . $imagen . '" alt=""></a>
                    <div class="media-body">
                        <h4 class="media-heading"><a href="mostrar_articulo.php?id_articulo=' . $id . '">' . $titulo . '</a></h4>
                        <p>' . $contenido_corto . '... </p>
                    </div>
                </div>
            </li>';
    }
}

/*Esta obtiene los artículos que saldrán en el carousel del index*/
function obtenerArticulosCarouselIndex() {
    $ids_posibles=obtenerTodosLosArticulos();
    $ids_articulos=[];
    while (count($ids_articulos) != 3) {
        $aleatorio = $ids_posibles[array_rand($ids_posibles)]['id'];
        if (!in_array($aleatorio, $ids_articulos)) {
            array_push($ids_articulos, $aleatorio);
        }
    }
    for ($i = 0; $i < count($ids_articulos); $i++) {
        $articulo=obtenerArticulo($ids_articulos[$i]);
        $titulo = htmlspecialchars($articulo['titulo'], ENT_QUOTES, 'UTF-8');
        $imagen = htmlspecialchars($articulo['imagen'], ENT_QUOTES, 'UTF-8');
        /*Ya que los artículos tienen caracteres html, como "<p>", se utilizan estas funciones para eliminarlos*/
        $contenido = html_entity_decode(strip_tags(htmlspecialchars($articulo['contenido'], ENT_QUOTES, 'UTF-8')));
        $contenido_corto = substr($contenido, 0, 140);

        echo '<div class="single_iteam"><img src="'.$imagen.'" alt="">
                <h2><a class="slider_tittle" href="mostrar_articulo.php?id_articulo='.$ids_articulos[$i].'">'.$titulo.'</a></h2>
              </div>';
    }
}

/*Esta obtiene 3 artículos aleatorios*/
function obtenerArticulosAleatorios() {
    $ids_posibles=obtenerTodosLosArticulos();
    $ids_articulos=[];
    while (count($ids_articulos) != 3) {
        $aleatorio = $ids_posibles[array_rand($ids_posibles)]['id'];
        if (!in_array($aleatorio, $ids_articulos)) {
            array_push($ids_articulos, $aleatorio);
        }
    }

    $articulos = [];
    for ($i = 0; $i < count($ids_articulos); $i++) {
        $articulos[$i] = obtenerArticulo($ids_articulos[$i]);
    }
    for ($i = 0; $i < count($articulos); $i++) {
        $id = htmlspecialchars($articulos[$i]['id_articulo'], ENT_QUOTES, 'UTF-8');
        $titulo = htmlspecialchars($articulos[$i]['titulo'], ENT_QUOTES, 'UTF-8');
        $imagen = htmlspecialchars($articulos[$i]['imagen'], ENT_QUOTES, 'UTF-8');
        /*Ya que los artículos tienen caracteres html, como "<p>", se utilizan estas funciones para eliminarlos*/
        $contenido = html_entity_decode(strip_tags(htmlspecialchars($articulos[$i]['contenido'], ENT_QUOTES, 'UTF-8')));
        $contenido_corto = substr($contenido, 0, 140);

        echo '<li>
                <div class="media wow fadeInDown animated" style="visibility: visible; animation-name: fadeInDown;"> <a class="media-left related-img" href="mostrar_articulo.php?id_articulo=' . $id . '"><img src="' . $imagen . '" alt=""></a>
                    <div class="media-body">
                        <h4 class="media-heading"><a href="mostrar_articulo.php?id_articulo=' . $id . '">' . $titulo . '</a></h4>
                        <p>' . $contenido_corto . '...</p>
                    </div>
                </div>
            </li>';
    }
}

/*Esta actualiza las visitas*/
function actualizarVisitas($id_articulo) {
    global $conn;
    $stmt = $conn->prepare("UPDATE articulos SET visitas = visitas + 1 WHERE id = ?");
    $stmt->bind_param("i", $id_articulo);
    $stmt->execute();
    $stmt->close();
}

/*Esta consigue el artículo más reciente de una categoría*/
function obtenerArticuloRecienteCategoria($id_categoria) {
    global $conn;
    $stmt = $conn->prepare("SELECT id FROM articulos WHERE id_categoria = ? ORDER BY fecha_creacion DESC LIMIT 1");
    $stmt->bind_param("i", $id_categoria);
    $stmt->execute();
    $stmt->bind_result($id_articulo);

    if ($stmt->fetch()) {
        return $id_articulo;
    } else {
        return null;
    }
}

/*Esta obtiene artículos para el index*/
function obtenerArticuloIndex($id_categoria, $espacio_blanco) {
    $id_articulo = obtenerArticuloRecienteCategoria($id_categoria);
    $articulo=obtenerArticulo($id_articulo);
    $fecha_creacion=htmlspecialchars($articulo['fecha_creacion'], ENT_QUOTES, 'UTF-8');
    $id = htmlspecialchars($articulo['id_articulo'], ENT_QUOTES, 'UTF-8');
    $titulo = htmlspecialchars($articulo['titulo'], ENT_QUOTES, 'UTF-8');
    $imagen = htmlspecialchars($articulo['imagen'], ENT_QUOTES, 'UTF-8');
    /*Ya que los artículos tienen caracteres html, como "<p>", se utilizan estas funciones para eliminarlos*/
    $contenido = html_entity_decode(strip_tags(htmlspecialchars($articulo['contenido'], ENT_QUOTES, 'UTF-8')));
    $contenido_corto = substr($contenido, 0, 140);
    echo '<li>
              <div class="catgimg2_container"> <a href="mostrar_articulo.php?id_articulo=' . $id . '"><img alt="" src="' . $imagen . '"></a> </div>
              <h2 class="catg_titile"><a href="mostrar_articulo.php?id_articulo=' . $id . '">' . $titulo . '</a></h2>
              <div class="comments_box"> <span class="meta_date">'.$fecha_creacion.'</span> <span class="meta_comment"><a>'.contarComentarios($id).' Comentarios</a></span> <span class="meta_more"><a  href="mostrar_articulo.php?id_articulo=' . $id . '">Leer más...</a></span> </div>';
              /*Se pone este caracter "&nbsp;" para crear un espacio entre el artículo y la imagen en una de,
               de las partes del index, si no se pone, surgen problemas extraños con el espacio, se pone el
              booleano para controlar donde sale, ya que depende*/
              if ($espacio_blanco) {
              echo '&nbsp;<p>&nbsp;'.$contenido_corto.'...</p></li>';
              }
              else {
                  echo '<p>'.$contenido_corto.'...</p></li>';
              }
}

/*Esta obtiene todos los artículos de una categoría*/
function obtenerArticulosCategoria($id_categoria) {
    global $conn;
    $stmt = $conn->prepare("SELECT id FROM articulos WHERE id_categoria = ? ORDER BY fecha_creacion DESC LIMIT 4");
    $stmt->bind_param("i", $id_categoria);
    $stmt->execute();
    $stmt->bind_result($id_articulo);

    $ids = [];
    while ($stmt->fetch()) {
        $ids[] = $id_articulo;
    }
    /*Aquí eliminamos el primer id del array (el más reciente, ya que ha salido anteriormente)*/
    array_shift($ids);
    $stmt->close();
    return $ids;
}

/*Igual que el anterior, solo que se usa para otra parte del blog, por lo tanto, hacen falta todos los artículos de esa categoría*/
function obtenerArticulosCategoria2($id_categoria) {
    global $conn;
    $stmt = $conn->prepare("SELECT id FROM articulos WHERE id_categoria = ? ORDER BY fecha_creacion DESC");
    $stmt->bind_param("i", $id_categoria);
    $stmt->execute();
    $stmt->bind_result($id_articulo);

    $ids = [];
    while ($stmt->fetch()) {
        $ids[] = $id_articulo;
    }

    $stmt->close();
    return $ids;
}

/*Esta obtiene un artículo para el index*/
function obtenerArticuloPequenioIndex($id_categoria) {
    /*Vamos a obtener otros 3 articulos par aponerlos en el side, pero no el más reciente, ya que ese ya ha salido*/
    $ids_articulos = obtenerArticulosCategoria($id_categoria);

    for ($i=0; $i<count($ids_articulos); $i++) {
        $id_articulo=$ids_articulos[$i];
        $articulo=obtenerArticulo($id_articulo);
        $fecha_creacion=htmlspecialchars($articulo['fecha_creacion'], ENT_QUOTES, 'UTF-8');
        $id = htmlspecialchars($articulo['id_articulo'], ENT_QUOTES, 'UTF-8');
        $titulo = htmlspecialchars($articulo['titulo'], ENT_QUOTES, 'UTF-8');
        $imagen = htmlspecialchars($articulo['imagen'], ENT_QUOTES, 'UTF-8');
        /*Ya que los artículos tienen caracteres html, como "<p>", se utilizan estas funciones para eliminarlos*/
        $contenido = html_entity_decode(strip_tags(htmlspecialchars($articulo['contenido'], ENT_QUOTES, 'UTF-8')));
        $contenido_corto = substr($contenido, 0, 70);

        echo '<li>
          <div class="media wow fadeInDown"> <a class="media-left" href="mostrar_articulo.php?id_articulo=' . $id . '"><img src="' . $imagen . '" alt=""></a>
            <div class="media-body">
              <h4 class="media-heading"><a href="mostrar_articulo.php?id_articulo=' . $id . '">'.$titulo.'</a></h4>
              <div class="comments_box"> <span class="meta_date">'.$fecha_creacion.'</span> <span class="meta_comment"><a>'.contarComentarios($id).' Comentarios</a></span> </div>
              <p>'.$contenido_corto.'...</p>            
            </div>
            
          </div>
        </li>';
    }
}

/*Esta obtiene un artículo aleatorio para el index*/
function obtenerArticuloAleatorioIndex($categorias) {
    $ids_articulos = [];

    foreach ($categorias as $categoria) {
        $articulos_categorias = obtenerArticulosCategoria($categoria);
        $ids_articulos = array_merge($ids_articulos, $articulos_categorias);
    }

    $aleatorios = array_rand(array_flip($ids_articulos), 2);

    foreach ($aleatorios as $id_articulo) {
        $articulo = obtenerArticulo($id_articulo);
        $titulo = htmlspecialchars($articulo['titulo'], ENT_QUOTES, 'UTF-8');
        $imagen = htmlspecialchars($articulo['imagen'], ENT_QUOTES, 'UTF-8');

        echo '<li>
                <div class="catgimg_container">
                    <a href="mostrar_articulo.php?id_articulo=' . $id_articulo . '" class="catg1_img">
                        <img alt="" src="' . $imagen . '">
                    </a>
                </div>
                <h3 class="post_titile">
                    <a href="mostrar_articulo.php?id_articulo=' . $id_articulo . '">' . $titulo . '</a>
                </h3>
              </li>';
    }
}

/*Esta obtiene un articulo aleatorio en el carousel del index*/
function obtenerCarouselAleatorioIndex() {
    $ids_posibles=obtenerTodosLosArticulos();
    $ids_articulos=[];
    while (count($ids_articulos) != 4) {
        $aleatorio = $ids_posibles[array_rand($ids_posibles)]['id'];
        if (!in_array($aleatorio, $ids_articulos)) {
            array_push($ids_articulos, $aleatorio);
        }
    }

    for ($i = 0; $i < count($ids_articulos); $i++) {
        $articulo=obtenerArticulo($ids_articulos[$i]);
        $titulo = htmlspecialchars($articulo['titulo'], ENT_QUOTES, 'UTF-8');
        $imagen = htmlspecialchars($articulo['imagen'], ENT_QUOTES, 'UTF-8');
        /*Ya que los artículos tienen caracteres html, como "<p>", se utilizan estas funciones para eliminarlos*/
        $contenido = html_entity_decode(strip_tags(htmlspecialchars($articulo['contenido'], ENT_QUOTES, 'UTF-8')));
        echo '<li><img src="'.$imagen.'" alt="">
            <div class="title_caption"><a href="mostrar_articulo.php?id_articulo='.$ids_articulos[$i].'">'.$titulo.'</a></div>
          </li>';

    }
}

/*Esta obtiene más artículos aleatorios para el carousel grande del index*/
function obtenerCarouselAleatorioGrande() {
    $ids_posibles=obtenerTodosLosArticulos();
    $ids_articulos=[];
    while (count($ids_articulos) != 3) {
        $aleatorio = $ids_posibles[array_rand($ids_posibles)]['id'];
        if (!in_array($aleatorio, $ids_articulos)) {
            array_push($ids_articulos, $aleatorio);
        }
    }
    for ($i = 0; $i < count($ids_articulos); $i++) {
        $articulo=obtenerArticulo($ids_articulos[$i]);
        $titulo = htmlspecialchars($articulo['titulo'], ENT_QUOTES, 'UTF-8');
        $imagen = htmlspecialchars($articulo['imagen'], ENT_QUOTES, 'UTF-8');
        /*Ya que los artículos tienen caracteres html, como "<p>", se utilizan estas funciones para eliminarlos*/
        $contenido = html_entity_decode(strip_tags(htmlspecialchars($articulo['contenido'], ENT_QUOTES, 'UTF-8')));
        $contenido_corto = substr($contenido, 0, 200);
        echo '<div class="single_featured_slide"> <a href="mostrar_articulo.php?id_articulo='.$ids_articulos[$i].'"><img src="'.$imagen.'" alt=""></a>
              <h2><a href="mostrar_articulo.php?id_articulo='.$ids_articulos[$i].'">'.$titulo.'</a></h2>
              <p>'.$contenido_corto.'...</p>
            </div>';

    }
}

/*Esta imprime los artículos en otras páginas que no son el index*/
function obtenerArticulosSecundario($id_articulo) {
    $articulo=obtenerArticulo($id_articulo);
    $fecha_creacion=htmlspecialchars($articulo['fecha_creacion'], ENT_QUOTES, 'UTF-8');
    $id = htmlspecialchars($articulo['id_articulo'], ENT_QUOTES, 'UTF-8');
    $titulo = htmlspecialchars($articulo['titulo'], ENT_QUOTES, 'UTF-8');
    $imagen = htmlspecialchars($articulo['imagen'], ENT_QUOTES, 'UTF-8');
    /*Ya que los artículos tienen caracteres html, como "<p>", se utilizan estas funciones para eliminarlos*/
    $contenido = html_entity_decode(strip_tags(htmlspecialchars($articulo['contenido'], ENT_QUOTES, 'UTF-8')));
    $contenido_corto = substr($contenido, 0, 140);
    echo '<div class="business_category_left wow fadeInDown">
                <ul class="fashion_catgnav">
                  <li>
                    <div class="catgimg2_container"> <a href="mostrar_articulo.php?id_articulo=' . $id . '"><img alt="" src="' . $imagen . '"></a> </div>
                    <h2 class="catg_titile"><a href="mostrar_articulo.php?id_articulo=' . $id . '">' . $titulo . '</a></h2>
                    <div class="comments_box"> <span class="meta_date">'.$fecha_creacion.'</span> <span class="meta_comment"><a>'.contarComentarios($id).' Comentarios</a></span> <span class="meta_more"><a  href="mostrar_articulo.php?id_articulo=' . $id . '">Leer más...</a></span> </div>
                    <p>'.$contenido_corto.'...</p>
                  </li>
                </ul>
              </div>';
}

/*Esta obtiene las páginas de la página de resultados de una búsqueda*/
function obtenerPaginasResultados($max_paginas, $pagina, $busqueda) {
    $enlaces = '';
    for ($i = 1; $i <= $max_paginas; $i++) {
        if ($i == $pagina) {
            $enlaces .= "<li class='page-item active'><a class='page-link' href='?busqueda=$busqueda&pagina=$i'>$i</a></li>";
        } else {
            $enlaces .= "<li class='page-item'><a class='page-link' href='?busqueda=$busqueda&pagina=$i'>$i</a></li>";
        }
    }
    echo $enlaces;
}

/*Esta las páginas de la página de categorías*/
function obtenerPaginasCategoria($max_paginas, $pagina, $id_categoria) {
    $enlaces = '';
    for ($i = 1; $i <= $max_paginas; $i++) {
        if ($i == $pagina) {
            $enlaces .= "<li class='page-item active'><a class='page-link' href='?id_categoria=$id_categoria&pagina=$i'>$i</a></li>";
        } else {
            $enlaces .= "<li class='page-item'><a class='page-link' href='?id_categoria=$id_categoria&pagina=$i'>$i</a></li>";
        }
    }
    echo $enlaces;
}


/*Esta función verifica si un artículo existe*/
function verificarArticulo($id_articulo) {
    global $conn;
    $existe = false;

    $stmt = $conn->prepare("SELECT id FROM articulos WHERE id = ?");
    $stmt->bind_param("i", $id_articulo);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $existe = true;
    }

    $stmt->close();
    return $existe;
}

/*Esta función obtiene los comentarios de un artículo*/
function obtenerComentarios($id_articulo) {
    global $conn;
    $stmt = $conn->prepare("SELECT id_usuario, contenido, fecha FROM comentarios WHERE id_articulo = ? ORDER BY fecha DESC");
    $stmt->bind_param("i", $id_articulo);
    $stmt->execute();
    $result = $stmt->get_result();
    $comentarios = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $comentarios;
}

/*Esta cuenta los comentarios de un artñiculo*/
function contarComentarios ($id_articulo) {
    global $conn;
    $stmt = $conn->prepare("SELECT COUNT(id) FROM comentarios WHERE id_articulo = ?");
    $stmt->bind_param("i", $id_articulo);
    $stmt->execute();
    $stmt->bind_result($numero_comentarios);
    $stmt->fetch();
    $stmt->close();
    return $numero_comentarios;
}

/*Esta los guarda*/
function guardarComentario($id_articulo, $id_usuario, $contenido) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO comentarios (id_articulo, id_usuario, contenido) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $id_articulo, $id_usuario, $contenido);
    $stmt->execute();
    $stmt->close();
}

/*Esta obtiene los artículos con los comentarios más recientes*/
function obtenerArticulosComentarios($cantidad) {
    global $conn;
    $stmt = $conn->prepare("SELECT DISTINCT id_articulo FROM comentarios ORDER BY fecha DESC LIMIT ?");
    $stmt->bind_param("i", $cantidad);
    $stmt->execute();
    $stmt->bind_result($id_articulo);
    $ids = [];
    while ($stmt->fetch()) {
        $ids[] = $id_articulo;
    }
    return $ids;
}

/*Esta imprime los articulos de la funcion anterior*/
function imprimirArticulosComentarios() {
    $ids_articulos = obtenerArticulosComentarios(3);

    for ($i = 0; $i < count($ids_articulos); $i++) {
        $articulo=obtenerArticulo($ids_articulos[$i]);
        $id = htmlspecialchars($articulo['id_articulo'], ENT_QUOTES, 'UTF-8');
        $titulo = htmlspecialchars($articulo['titulo'], ENT_QUOTES, 'UTF-8');
        $imagen = htmlspecialchars($articulo['imagen'], ENT_QUOTES, 'UTF-8');
        /*Ya que los artículos tienen caracteres html, como "<p>", se utilizan estas funciones para eliminarlos*/
        $contenido = html_entity_decode(strip_tags(htmlspecialchars($articulo['contenido'], ENT_QUOTES, 'UTF-8')));
        $contenido_corto = substr($contenido, 0, 140);
        echo '<li>
                <div class="media wow fadeInDown"> <a class="media-left" href="mostrar_articulo.php?id_articulo=' . $id . '"><img src="' . $imagen . '" alt=""></a>
                    <div class="media-body">
                        <h4 class="media-heading"><a href="mostrar_articulo.php?id_articulo=' . $id . '">' . $titulo . '</a></h4>
                        <p>' . $contenido_corto . '... </p>
                    </div>
                </div>
            </li>';
    }
}

/*Esta función obtendrá la URL relativa actual*/
function obtenerUrlActual() {
    return $_SERVER['REQUEST_URI'];
}

/*Esta función obtendrá la url completa de la página que se visite*/
function obtenerUrlActualCompleta() {
    $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    $uri = $_SERVER['REQUEST_URI'];
    return $protocolo . $host . $uri;
}

/*Esta función elimina un artículo de la base de datos*/
function eliminarArticulo($id_articulo) {
    global $conn;
    $stmt=$conn->prepare("DELETE FROM articulos WHERE id = ?");
    $stmt->bind_param("i", $id_articulo);
    $stmt->execute();
    $stmt->close();
}

/*Esta función es la encargada de modificar los artículos*/
function modificarArticulo($id_articulo, $titulo, $contenido, $id_categoria, $imagen) {
    global $conn;
    $stmt=$conn->prepare("UPDATE articulos SET titulo = ?, contenido = ?, id_categoria = ?, imagen = ? WHERE id = ?");
    $stmt->bind_param("ssisi", $titulo, $contenido, $id_categoria, $imagen, $id_articulo);
    $stmt->execute();
    $stmt->close();
}

/*Esta función imprime todas las categorías*/
function imprimirCategorias() {
    global $conn;
    $stmt=$conn->prepare("SELECT nombre FROM categorias");
    $stmt->execute();
    $stmt->bind_result($categoria);

    $categorias=[];
    while ($stmt->fetch()) {
        $categorias[] = $categoria;
    }
    $stmt->close();
    foreach ($categorias as $categoria) {
        echo '<input type="radio" id="'.$categoria.'" name="categoria" value="'.$categoria.'" required>
        <label for="'.$categoria.'">'.$categoria.'</label><br>';
    }
}

/*Esta función devolverá la id del artículo más reciente*/
function obtenerArticuloMasReciente() {
    global $conn;
    $stmt=$conn->prepare("SELECT id FROM articulos ORDER BY fecha_creacion DESC LIMIT 1");
    $stmt->execute();
    $stmt->bind_result($id_articulo);
    $stmt->fetch();
    $stmt->close();
    return $id_articulo;
}

/*Esta obtiene todas las ids de las categorías*/
function obtenerCategorias() {
    global $conn;
    $stmt=$conn->prepare("SELECT id FROM categorias ORDER BY id ASC");
    $stmt->execute();
    $stmt->bind_result($id_categoria);
    $ids_categorias=[];
    while ($stmt->fetch()) {
        array_push($ids_categorias, $id_categoria);
    }
    $stmt->close();
    return $ids_categorias;
}

/*Esta lista todas las categorías en el header*/
function imprimirCategoriasHeader() {
    $ids_categorias=obtenerCategorias();
    for ($i = 0; $i < count($ids_categorias); $i++) {
        echo'<li><a href="mostrar_categoria.php?id_categoria='.$ids_categorias[$i].'&pagina=1">'.obtenerCategoria($ids_categorias[$i]).'</a></li>';
    }
}

/*Esta función para lista todos los artículos*/
function obtenerTodosLosArticulos() {
    global $conn;
    $stmt = $conn->prepare("SELECT id, titulo, fecha_creacion FROM articulos ORDER BY fecha_creacion DESC");
    $stmt->execute();
    $resultado = $stmt->get_result();

    $articulos = [];
    while ($fila = $resultado->fetch_assoc()) {
        $articulos[] = $fila;
    }

    $stmt->close();
    return $articulos;
}

/*Esta procesa la imagen del artículo*/
function procesarImagen($archivo, $url, $carpetaDestino) {
    /*Se verifica si se subió*/
    if (!empty($archivo['name'])) {
        /*Después se valida el tipo de archivo y que sea una imagen*/
        $tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif'];

        /*Si no es una imagen, se devuelve un error*/
        if (!in_array($archivo['type'], $tiposPermitidos)) {
            return ['error' => 'El archivo debe ser una imagen válida (JPG, PNG, GIF).'];
        }

        /*Ahora se genera un nombre único para evitar conflictos*/
        $nombreArchivo = uniqid('img_', true) . '.' . pathinfo($archivo['name'], PATHINFO_EXTENSION);

        /*Después, se mueve el archivo a la carpeta de destino*/
        if (move_uploaded_file($archivo['tmp_name'], $carpetaDestino . $nombreArchivo)) {
            return ['ruta' => $carpetaDestino . $nombreArchivo];
        } else {
            return ['error' => 'Error al mover el archivo subido.'];
        }
    }

    /*Si no se subió un archivo, se procesa una url*/
    if (!empty($url)) {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return ['ruta' => $url];
        } else {
            return ['error' => 'La URL proporcionada no es válida.'];
        }
    }

    /*Si no se proporcionó nada, se devuelve un error*/
    return ['error' => 'Debes subir un archivo o proporcionar una URL.'];
}

/*Esta función verifica si el email del usuario existe, si existe devuelven los datos necesarios para hacer el login*/
function loginEmail($email) {
    global $conn;
    $stmt = $conn->prepare("SELECT id, nombre, contrasenia FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($id_usuario, $nombre, $hash_contrasenia);

    /*Si hay resultados con el email del usuario, es porque existe*/
    if ($stmt->fetch()) {
        $stmt->close();
        return ['id_usuario' => $id_usuario, 'nombre' => $nombre, 'contrasenia' => $hash_contrasenia];
    }

    /*Si no, se devuelve falso*/
    else {
        $stmt->close();
        return false;
    }
}

/*Esta función guarda los datos de la sesión*/
function guardarSesion($id_usuario, $token, $fecha_expiracion_formateada, $activar) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO sesiones (id_usuario, token, fecha_expiracion, activo) VALUES (?, ?, ?, ?)
                                 ON DUPLICATE KEY UPDATE token = ?, fecha_expiracion = ?");
    $stmt->bind_param("ississ", $id_usuario, $token, $fecha_expiracion_formateada, $activar, $token, $fecha_expiracion_formateada);

    if ($stmt->execute()) {
        header("Location: ./index.php");
        $stmt->close();
        exit();
    }

    else {
        echo "Error al guardar el token: " . $stmt->error;
        $stmt->close();
    }
}

/*Esta función busca los artículos por título*/
function buscarArticulosTitulo($busqueda) {
    global $conn;
    $busqueda = '%' . $busqueda . '%';
    $ids = [];
    $stmt = $conn->prepare("SELECT id FROM articulos WHERE titulo LIKE ?");
    $stmt->bind_param("s", $busqueda);
    $stmt->execute();
    $stmt->bind_result($id_articulo);
    while ($stmt->fetch()) {
        array_push($ids, $id_articulo);
    }
    $stmt->close();
    return $ids;
}