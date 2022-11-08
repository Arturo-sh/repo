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
                                <h3>Historial de vacunas del bovino: <span id="arete"><?php echo $_GET["id"] ?></span></h3>
                                <button class="btn btn-warning" onclick="printDiv('card-body')">Imprimir historial</button>
                            </div>

                            <div class="card-body text-center">
                                <div id="card-body">
                                    <?php
                                    if (isset($_GET['id'])) {
                                        $arete_bovino = $_GET['id'];
                                        // Consulta para obtener el historial de vacunas del bovino
                                        $consulta_busqueda_vacunas = "SELECT * FROM vacunas WHERE codigo_ganado='$arete_bovino'";
                                        // Ejecucion de la consulta de busqueda de vacunas
                                        $resultado_busqueda_vacunas = mysqli_query($conexion, $consulta_busqueda_vacunas);
                                        // Verifica si hay resultados de vacunas
                                        $vacunas_aplicadas = mysqli_num_rows($resultado_busqueda_vacunas);

                                        // Si hay resultados de vacunas se muestran
                                        if ($vacunas_aplicadas <= 0) {
                                            echo "<h4 class='mt-5 mb-5 text-center text-danger'>Aun no existen registros de vacunas para este bovino</h4>";
                                        } else { ?>
                                            <table class="table table-hover" style="font-size:14px;">
                                                <thead>
                                                    <tr>
                                                        <th>Nombre de vacuna</th>
                                                        <th>Dosis aplicada</th>
                                                        <th>Fecha de vacunación</th>
                                                        <?php
                                                        if ($nivel_usuario == 1) {
                                                            echo "<th id='thOpciones'>Opciones</th>";
                                                        }
                                                        ?>
                                                    </tr>
                                                    <?php
                                                    // Muestra los resultados de la busqueda de vacunas en columnas
                                                    while ($fila = mysqli_fetch_array($resultado_busqueda_vacunas)) {
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $fila['nombre_vacuna']; ?></td>
                                                            <td><?php echo $fila['dosis_aplicada']; ?></td>
                                                            <td><?php echo $fila['fecha_aplicacion']; ?></td>
                                                            <?php
                                                            if ($nivel_usuario == 1) {
                                                                echo "<td class='tdOpciones'>
                                                                <a class='btn btn-primary btn-sm' href='actualizarVacuna.php?id=" . $fila['id_vacuna'] . "'><span class='icon-pencil'></span></a>
                                                                <a class='btn btn-danger btn-sm' onclick='Delete($fila[id_vacuna], $fila[codigo_ganado]);'><span class='icon-trashcan'></span></a>                                                                
                                                                </td>";
                                                            }
                                                            ?>
                                                        </tr>
                                                    <?php
                                                    }
                                                    // Se libera la memoria del resultado de la consulta
                                                    mysqli_free_result($resultado_busqueda_vacunas);
                                                    ?>
                                                </thead>
                                            </table>
                                    <?php
                                        }
                                    } ?>
                                </div>
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
            // Función para eliminar un registro de vacunación
            function Delete(id_vacuna, arete) {
                if (confirm('¿Esta seguro que desea eliminar este registro?')) {
                    document.location = 'historialVacunas.php?id=' + arete + '&idVacunaEliminar=' + id_vacuna;
                }
            }
        </script>

        <?php
        if ($nivel_usuario == 1) {
            if (isset($_GET['idVacunaEliminar'])) {
                // Se obtiene el arete y el id del registro de vacunación a eliminar
                $numero_arete = $_GET['id'];
                $id_vacuna = $_GET['idVacunaEliminar'];
                // Se crea la consulta para eliminar el registro
                $consulta_eliminacion_vacuna = "DELETE FROM vacunas WHERE id_vacuna = '$id_vacuna'";
                // Se ejecuta la consulta para eliminar el registro
                $resultado_eliminacion_vacuna = mysqli_query($conexion, $consulta_eliminacion_vacuna);
                // Si la consulta se ejecuta correctamente, muestra un mensaje de éxito y regresa al historial de vacunas,
                // en caso contrario, muestra un mensaje de error
                if ($resultado_eliminacion_vacuna) {
                    echo '<script>alert("Registro eliminado satisfactoriamente");</script>';
                } else {
                    echo '<script>alert("Ha ocurridó un error al intentar eliminar el registro!");</script>';
                }
                echo '<script>window.location.href = "historialVacunas.php?id=' . $numero_arete . '"</script>';
            }
        }
        ?>

        <?php include '../componentes/footer.php'; ?>
        <script src="../js/bootstrap.bundle.js"></script>
        <script src="../js/print.js"></script>
    </body>

    </html>
<?php
} else {
    header('Location:../index.php');
}
?>