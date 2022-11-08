<?php
include "../componentes/conexion.php";

session_start();
if (!isset($_SESSION['nombre_usuario'])) { // Si no existe la sesión, redirige al login
    header('Location:../index.php');
}

// Obtenemos el nivel del usuario
$nivel_usuario = $_SESSION['nivel'];
if ($nivel_usuario == 1 || $nivel_usuario == 2) {
    // Consulta que une las tablas de razas y registros para obtener los datos de los bovinos con una llave foranea
    $consulta_registros = "SELECT * FROM registros INNER JOIN razas_ganado WHERE registros.id_raza=razas_ganado.id_raza";
    // Ejecuta la consulta
    $resultado_consulta_registros = mysqli_query($conexion, $consulta_registros);
    // obtiene el número de filas de la consulta (número de registros)
    $total_registros = mysqli_num_rows($resultado_consulta_registros);
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
                                <h3 class="text-center">Registros</h3>
                                <?php
                                // Si el nivel_usuario es 1, muestra el botón de agregar nuevo registro
                                if ($nivel_usuario == 1) {
                                    echo '<div class"text-center"><a class="btn btn-success" href="agregarRegistro.php">Nuevo registro</a></div>';
                                }
                                ?>
                                <!-- Muestra el total de registros en el sistema -->
                                <h6 class="text-end">Total de registros: <?php echo $total_registros; ?></h6>
                            </div>

                            <?php
                            // Si el total de registros es menor o igual a 0, muestra un mensaje
                            if ($total_registros <= 0) {
                                echo "<h4 class='mt-5 mb-5 text-center text-danger'>Aun no existen registros</h4>";
                            } else {
                            ?>
                                <div class="card-body table-responsive text-center" style="overflow:auto;">
                                    <table class="table table-hover border">
                                        <thead class="bg-primary">
                                            <tr>
                                                <th>Número de arete</th>
                                                <th>Tipo de raza</th>
                                                <th>Nombre</th>
                                                <th>Imagen</th>
                                                <th>Vacunas</th>
                                                <?php
                                                // Si el nivel_usuario es 1, muestra las la columna de acciones (editar y eliminar)
                                                if ($nivel_usuario == 1) {
                                                    echo '<th class="col-md-2">Opciones</th>';
                                                }
                                                ?>
                                            </tr>
                                            <?php
                                            $contador = 0;
                                            // Mientras haya registros, los muestra en la tabla
                                            while ($fila = mysqli_fetch_array($resultado_consulta_registros)) {
                                                // Alternar colores de las filas de la tabla
                                                if ($contador % 2 == 0) {
                                                    echo "<tr class='border' style='background: #bdd7ee;'>";
                                                } else {
                                                    echo "<tr class='border' style='background: #c8d2ca;'>";
                                                }
                                            ?>
                                                <!-- Muestra las columnas de datos para cada registro -->
                                                <td><?php echo $fila['arete']; ?></td>
                                                <td><?php echo $fila['nombre_raza']; ?></td>
                                                <td><?php echo $fila['nombre']; ?></td>

                                                <td>
                                                    <?php
                                                    // Si el nivel_usuario es 1, habilita el botón de editar imagen
                                                    if ($nivel_usuario == 1) {
                                                        echo "<a href='actualizarImagen.php?id=" . $fila['arete'] . "'><img src='../bovinos/" . $fila['imagen_bovino'] . "' width='100px' height='50px' />";
                                                    } else {
                                                        // Si el nivel_usuario es 2 solo muestra la imagen   
                                                        echo " <img src='../bovinos/" . $fila['imagen_bovino'] . "' width='100px' height='50px' />";
                                                    }
                                                    ?>
                                                </td>

                                                <td>
                                                    <?php
                                                    // Si el nivel_usuario es 1, habilita el botón de agregar vacunas
                                                    if ($nivel_usuario == 1) {
                                                        echo "<a class='btn btn-primary btn-sm' href='agregarVacuna.php?id=" . $fila["arete"] . "'><span class='icon-plus'></span></a>
                                                        <a class='btn btn-success btn-sm' href='historialVacunas.php?id=" . $fila["arete"] . "'><span class='icon-book'></span></a>";
                                                    } else {
                                                        // Si el nivel_usuario es 2, solo muestra el historial de las vacunas
                                                        echo "<a class='btn btn-success btn-sm' href='historialVacunas.php?id=" . $fila["arete"] . "'><span class='icon-book'></span></a>";
                                                    }
                                                    ?>
                                                </td>

                                                <?php
                                                // Si el nivel_usuario es 1, muestra las opciones de editar y eliminar
                                                if ($nivel_usuario == 1) {
                                                    echo "<td>
                                                    <a class='btn btn-primary btn-sm' href='actualizarRegistro.php?id=" . $fila['id_bovino'] . "'><span class='icon-pencil'></span></a>
                                                    <a class='btn btn-danger btn-sm' onclick='Delete($fila[id_bovino]);'><span class='icon-trashcan'></span></a>
                                                    </td>";
                                                }
                                                ?>
                                                </tr>
                                        <?php
                                                $contador += 1;
                                            }
                                            // Se libera la memoria del resultado de la consulta
                                            mysqli_free_result($resultado_consulta_registros);
                                        }
                                        ?>
                                        </thead>
                                    </table>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <script>
            // Función para eliminar un registro
            function Delete(id_bovino) {
                if (confirm('¿Esta seguro que desea eliminar este registro?')) {
                    document.location = 'registros.php?idBovinoEliminar=' + id_bovino;
                }
            }
        </script>

        <?php
        if ($nivel_usuario == 1) {
            if (isset($_GET['idBovinoEliminar'])) {
                // Se obtiene el id del registro a eliminar
                $id_bovino = $_GET['idBovinoEliminar'];
                // Se crea la consulta para eliminar el registro
                $consulta_eliminacion_registro = "DELETE FROM registros WHERE id_bovino= '$id_bovino'";
                // Se ejecuta la consulta para eliminar el registro
                $resultado_eliminacion_registro = mysqli_query($conexion, $consulta_eliminacion_registro);
                // Si la consulta se ejecuta correctamente, muestra un mensaje de éxito y regresa a la página de registros,
                // en caso contrario, muestra un mensaje de error
                if ($resultado_eliminacion_registro) {
                    echo '<script>alert("Registro eliminado satisfactoriamente");</script>';
                } else {
                    echo '<script>alert("Ha ocurridó un error al intentar eliminar el registro!");</script>';
                }
                echo '<script>window.location.href = "registros.php"</script>';
            }
        }
        ?>

        <?php include '../componentes/footer.php'; ?>
        <script src="../js/bootstrap.bundle.js"></script>
    </body>

    </html>

<?php
} else {
    header('Location:../index.php');
}
?>