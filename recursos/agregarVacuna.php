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
                                        <p class="form-control"><?= "" . $_GET["id"] ?></p>
                                        <input class="form-control" type="hidden" id="txtarete" value="<?= "" . $_GET["id"] ?>" name="txtarete">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Nombre de la vacuna</label>
                                        <input type="text" id="txtvacuna" class="form-control" name="txtvacuna" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Dosis aplicada</label>
                                        <input type="text" id="txtdosis" class="form-control" name="txtdosis" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Fecha de vacunación</label>
                                        <input type="date" id="txtfecha" class="form-control" name="txtfecha" required>
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
                                    $numero_arete = $_POST['txtarete'];
                                    $nombre_vacuna   = $_POST['txtvacuna'];
                                    $dosis_aplicada = $_POST['txtdosis'];
                                    $fecha_aplicacion = $_POST['txtfecha'];

                                    // Consulta para insertar un registro de vacunacion
                                    $consulta_insercion_vacuna = "INSERT INTO vacunas VALUES (null, '$nombre_vacuna', '$dosis_aplicada', '$fecha_aplicacion', '$numero_arete')";
                                    // Ejecución de la consulta
                                    $resultado_insercion_vacuna = mysqli_query($conexion, $consulta_insercion_vacuna);
                                    // Si la inserción se realiza de manera correcta se muestra un mensaje de éxito 
                                    if ($resultado_insercion_vacuna) {
                                        echo '<script>alert("Datos registrados satisfactoriamente");
                                        window.location.replace("registros.php");</script>";';
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