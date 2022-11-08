<?php
include "../componentes/conexion.php";

session_start();
if (!isset($_SESSION['nombre_usuario'])) { // Si no existe la sesión, redirige al login
    header('Location:../index.php');
}

// Obtenemos el nivel del usuario
$nivel_usuario = $_SESSION['nivel'];
if ($nivel_usuario == 1 || $nivel_usuario == 2) {
    // Consulta para obtener los datos de la tabla razas
    $consulta_razas = "SELECT * FROM razas_ganado";
    // Ejecucion de la consulta de razas
    $resultado_consulta_razas = mysqli_query($conexion, $consulta_razas);
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
        <meta content="" name="description">
        <meta content="" name="author">
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
                                <h3 class="text-center">Razas de ganado</h3>
                                <?php
                                // Si el nivel de usuario es 1 habilitamos el boton para agregar una nueva raza
                                if ($nivel_usuario == '1') {
                                    echo '<a class="btn btn-success" href="agregarRaza.php">Agregar raza</a>';
                                }
                                ?>
                            </div>
                            <div class="card-body  table-responsive text-center">

                                <?php
                                // Contamos el numero de filas que nos regresa la consulta
                                $total_razas = mysqli_num_rows($resultado_consulta_razas);
                                if ($total_razas <= 0) {
                                    echo "<h4 class='mt-5 mb-5 text-center text-danger'>Aun no existen registros de razas</h4>";
                                } else {
                                ?>
                                    <table class="table table-hover">
                                        <thead>
                                            <tr class="text-center">
                                                <th>Nombre de raza</th>
                                                <th>Descripción</th>
                                                <?php
                                                // Si el nivel del usuario es 1, se muestra la columna de opciones
                                                if ($nivel_usuario == '1') {
                                                    echo '<th>Opciones</th>';
                                                }
                                                ?>
                                            </tr>

                                            <?php
                                            // Mientras haya registros, los muestra en la tabla
                                            while ($fila = mysqli_fetch_array($resultado_consulta_razas)) {
                                            ?>
                                                <tr>
                                                    <td><?php echo $fila['nombre_raza']; ?></td>
                                                    <td><?php echo $fila['descripcion']; ?></td>
                                                    <?php
                                                    // Si el nivel de usuario es 1 se muestran las opciones editar y eliminar
                                                    if ($nivel_usuario == '1') {
                                                        echo "<td>
                                                        <a class='btn btn-primary btn-sm' href='actualizarRaza.php?id=" . $fila['id_raza'] . "'><span class='icon-pencil'></span></a>
                                                        <a class='btn btn-danger btn-sm' onclick='Delete($fila[id_raza]);'><span class='icon-trashcan'></span></a>
                                                        </td>";
                                                    }
                                                    ?>
                                                </tr>
                                        <?php
                                            }
                                            // Se libera la memoria del resultado de la consulta
                                            mysqli_free_result($resultado_consulta_razas);
                                        }
                                        ?>
                                        </thead>
                                    </table>
                            </div>
                        </div>
                        <div class="container-fluid h-100 mt-4">
                            <div class="row w-100 align-items-center">
                                <div class="col text-center">
                                    <button type="reset" id="btn-cancelar" class="btn btn-danger" name="btn-cancelar" onclick="history.go(-1);">Regresar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <script>
            // Funcion para eliminar un registro
            function Delete(id_raza) {
                if (confirm('¿Esta seguro que desea eliminar este registro?')) {
                    document.location = 'razas.php?idRazaEliminar=' + id_raza;
                }
            }
        </script>

        <?php
        if ($nivel_usuario == 1) {
            if (isset($_GET['idRazaEliminar'])) {
                // Obtenemos el id de la raza a eliminar
                $id_raza = $_GET['idRazaEliminar'];
                // Creamos la consulta para eliminar el registro
                $consulta_eliminacion_raza = "DELETE FROM razas_ganado WHERE id_raza = '$id_raza'";
                // EJecutamos la consulta
                $resultado_eliminacion_raza = mysqli_query($conexion, $consulta_eliminacion_raza);
                // Si la consulta resulta exitosa se muestra un mensaje de exito y se redirecciona a la pagina de razas
                // En caso contrario se muestra un mensaje de error
                if ($resultado_eliminacion_raza) {
                    echo '<script>alert("Registro eliminado satisfactoriamente");</script>';
                } else {
                    echo '<script>alert("Ha ocurridó un error al intentar eliminar el registro!");</script>';
                }
                echo '<script>window.location.href = "razas.php"</script>';
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