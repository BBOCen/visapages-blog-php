<?php
/*Este es el archivo con la conexión a la bbdd*/
/*Es muy útil para evitar repetir código*/
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'visapages';

$conn = new mysqli("p:{$host}", $user, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
