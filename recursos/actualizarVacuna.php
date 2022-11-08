<?php
include "../componentes/conexion.php";

session_start();
if (!isset($_SESSION['nombre_usuario'])) { // Si no existe la sesión, redirige al login
    header('Location:../index.php');
}

// Obtenemos el nivel del usuario
$nivel_usuario = $_SESSION['nivel'];
if ($nivel_usuario == 2) {
    // Si el usuario es de nivel 2 se redirecciona de regreso, solo el administrador (nivel 1) puede consultar esta pagina
    echo "<script>window.history.go(-1);</script>";
} else if ($nivel_usuario == 1) {
    if (isset($_GET['id'])) {
        $id_vacuna = $_GET['id'];
        $consulta_vacunas = "SELECT * FROM vacunas WHERE id_vacuna='$id_vacuna'";
        $datos_vacunas = mysqli_query($conexion, $consulta_vacunas);
        while ($fila = mysqli_fetch_array($datos_vacunas)) {
            $id = $fila['id_vacuna'];
            $nombre_vacuna = $fila['nombre_vacuna'];
            $dosis_aplicada = $fila['dosis_aplicada'];
            $fecha_aplicacion = $fila['fecha_aplicacion'];
            $codigo_ganado = $fila['codigo_ganado'];
        }
    }
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
    </head>

    <body>
        <header> <?php include '../componentes/encabezado.php'; ?> </header>

        <main class="container" role="main">
            <div class="container container-fluid">
                <div class="row">
                    <div class="page-section">
                        <div class="card">
                            <div class="card-header">
                                <h3>Datos de vacuna</h3>
                            </div>
                            <div class="card-body">
                                <form class="row g-3" method="POST" action="">
                                    <div class="col-md-6">
                                        <label class="form-label">Arete del animal</label>
                                        <p class="form-control"><?= "" . $codigo_ganado ?></p>
                                        <input class="form-control" type="hidden" id="txtid" value="<?= "" . $id ?>" name="txtid">
                                        <input class="form-control" type="hidden" id="txtarete" value="<?= "" . $codigo_ganado ?>" name="txtarete">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Nombre de la vacuna</label>
                                        <input type="text" id="txtvacuna" class="form-control" name="txtvacuna" value="<?php echo $nombre_vacuna; ?>" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Dosis aplicada</label>
                                        <input type="text" id="txtdosis" class="form-control" name="txtdosis" value="<?php echo $dosis_aplicada; ?>" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Fecha de vacunación</label>
                                        <input type="date" id="txtfecha" class="form-control" name="txtfecha" value="<?php echo $fecha_aplicacion; ?>" required>
                                    </div>

                                    <div class="container-fluid h-100 mt-4">
                                        <div class="row w-100 align-items-center">
                                            <div class="col text-center">
                                                <button type="submit" id="btn-guardar" class="btn btn-success" name="btn-guardar">Guardar</button>
                                                <button type="reset" id="btn-cancelar" class="btn btn-danger" name="btn-cancelar" onclick="history.go(-1);">Cancelar</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <?php
                                if (isset($_POST['btn-guardar'])) {
                                    // Recolección de datos del formulario
                                    $id_vacuna = $_POST['txtid'];
                                    $numero_arete = $_POST['txtarete'];
                                    $nueva_vacuna = $_POST['txtvacuna'];
                                    $nueva_dosis   = $_POST['txtdosis'];
                                    $nueva_fecha = $_POST['txtfecha'];

                                    // Consulta para actualizar un registro de vacunación
                                    $consulta_actualizacion_vacuna = "UPDATE vacunas SET nombre_vacuna = '$nueva_vacuna', dosis_aplicada = '$nueva_dosis', fecha_aplicacion = '$nueva_fecha' WHERE id_vacuna = '$id_vacuna'";
                                    // Ejecución de la consulta
                                    $resultado_actualizacion_vacuna = mysqli_query($conexion, $consulta_actualizacion_vacuna);
                                    // Si la actualización se realiza de manera correcta se muestra un mensaje de éxito 
                                    if ($resultado_actualizacion_vacuna) {
                                        echo '<script>alert("Datos actualizados satisfactoriamente");
                                        window.location.replace("historialVacunas.php?id=' . $numero_arete . '");</script>";';
                                    }
                                }
                                ?>
                            </div>
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