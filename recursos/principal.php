<?php
include "../componentes/conexion.php";

session_start();
if (!isset($_SESSION['nombre_usuario'])) { // Si no existe la sesión, redirige al login
    header('Location:../index.php');
}

// Obtenemos el nivel del usuario
$nivel_usuario = $_SESSION['nivel']; // Obtiene el nivel_usuario del usuario
if ($nivel_usuario == 1 || $nivel_usuario == 2) {
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
        <title>Sistema de registro de ganado</title>
        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <!-- Custom styles for this template -->
        <link rel="stylesheet" href="../css/style.css">
        <link rel="stylesheet" href="../css/icons.css">
    </head>

    <body>
        <header> <?php include '../componentes/encabezado.php'; ?> </header>

        <main class="container" role="main">
            <div class="container container-fluid">
                <div class="row">
                    <div class="page-section">
                        <div class="card">
                            <div class="card-header">
                                <h3>Busqueda de datos</h3>
                            </div>
                            <div class="card-body" style="overflow:auto;">
                                <form class="row justify-content-center" method="POST" action="">
                                    <div class="col-md-6 d-flex">
                                        <input type="text" id="txtarete" class="form-control" name="txtarete" placeholder="Número de arete" pattern="[0-9]{1,}" title="Campo exclusivo para números" required>
                                        <button type="submit" id="btn-buscar" class="btn btn-success" style="margin-left: 10px;" name="btn-buscar">Buscar</button>
                                    </div>
                                </form>
                            </div>

                            <?php
                            if (isset($_POST['btn-buscar'])) {
                                $numero_arete = $_POST['txtarete']; // Obtiene el numero de arete
                                $tamanio = strlen($numero_arete); // Obtiene el tamaño del numero de arete

                                if (empty($numero_arete)) { // Verifica que el campo no este vacio
                                    echo "<p class='text-danger mt-3 mb-3'>* Verifique la entrada</p>";
                                } else {
                                    // Verifica que el tamaño del numero de arete sea de 4 digitos, en caso de que no sea asi, se genera una consulta especial para la busqueda, 
                                    // en caso contrario se genera una consulta normal
                                    if ($tamanio == 4) {
                                        $consulta_busqueda = "SELECT * FROM razas_ganado INNER JOIN registros WHERE razas_ganado.id_raza = registros.id_raza AND arete LIKE '%$numero_arete'";
                                    } else {
                                        $consulta_busqueda = "SELECT * FROM razas_ganado INNER JOIN registros WHERE razas_ganado.id_raza = registros.id_raza AND arete = '$numero_arete'";
                                    }
                                    // Ejecuta la consulta
                                    $resultado_busqueda = mysqli_query($conexion, $consulta_busqueda);
                                    $numero_resultados_encontrados = mysqli_num_rows($resultado_busqueda);
                                    // Verifica que el numero de resultados encontrados sea mayor a 0, en caso de que sea asi, se muestran los resultados
                                    if ($numero_resultados_encontrados > 0) {
                                        echo '<h4 class="text-center mt-4">Resultados:</h4>';
                                        while ($fila = mysqli_fetch_array($resultado_busqueda)) {
                                            echo '<div class="row justify-content-center border p-4" style="margin: 1% 3%;">
                                            <div class="col-md-4">
                                            <p class=""><strong>Número de arete: </strong>' . $fila['arete'] . '</p>
                                            <p><strong>Tipo de raza: </strong>' . $fila['nombre_raza'] . '</p>
                                            
                                            <p><strong>Sexo: </strong>' . $fila['sexo'] . '</p>
                                            <p><strong>Peso: </strong>' . $fila['peso'] . '</p>
                                            <p><strong>Color: </strong>' . $fila['color'] . '</p>
                                            <p><strong>Edad: </strong>' . $fila['edad'] . '</p>
                                            <p><strong>Fecha de registro: </strong>' . $fila['fecha_registro'] . '</p>
                                            </div>
                                            <div class="col-md-4 text-center">
                                            <p><strong>Bovino</strong></p>
                                            <img src="../bovinos/' . $fila['imagen_bovino'] . '" width="90%" />
                                            <p><strong>Nombre: </strong>' . $fila['nombre'] . '</p>
                                            </div>
                                            </div>';
                                        }
                                    } else {
                                        echo "<h5 class='text-danger mt-3 mb-5 text-center'>No se encontraron coincidencias</h5>";
                                    }
                                }
                            }
                            ?>

                            <!-- Por si se desea implementar un enlace a la seccion de historial medico cuando apararezca un resultado de busqueda -->
                            <!-- <p> Historial medico <a class="btn btn-success btn-sm" href="historialVacunas.php?id=' . $fila['arete'] . '"><span class="icon-book"></span></a> </p> -->

                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php include '../componentes/footer.php'; ?>
        <script src="../js/bootstrap.bundle.js"></script>
    </body>

    </html>

<?php
} else {
    header('Location:../index.php');
}
?>