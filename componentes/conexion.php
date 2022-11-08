<?php

$server = "localhost";
$username = "root";
$password = "";
$database = "proyecto_residencia";

$conexion = new mysqli($server, $username, $password, $database);

if ($conexion -> connect_errno) {
    echo "Fallo al conectar a MySQL (" . $conexion->connect_errno . ") " . $conexion-> connect_error;
}
// else {
//     echo "Conexion exitosa";
// }
