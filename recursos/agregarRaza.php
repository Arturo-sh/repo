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
                                <h3>Datos de la raza</h3>
                            </div>
                            <div class="card-body">
                                <form class="row g-3" method="POST" action="" enctype="multipart/form-data">
                                    <div class="col-md-6">
                                        <label class="form-label">Nombre de raza</label>
                                        <input type="text" id="txtraza" class="form-control" name="txtraza" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Descripción</label>
                                        <input type="text" id="txtdescripcion" class="form-control" name="txtdescripcion">
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
                                    // Obtenemos los datos del formulario
                                    $nombre_raza = $_POST['txtraza'];
                                    $descripcion = $_POST['txtdescripcion'];
                                    // Consulta para insertar los datos de la raza
                                    $consulta_insertar_raza = "INSERT INTO razas_ganado VALUES (null, '$nombre_raza', '$descripcion')";
                                    // Ejecutamos la consulta
                                    $consulta = mysqli_query($conexion, $consulta_insertar_raza);
                                    // Si la consulta se ejecuto correctamente mostramos un mensaje de exito
                                    // de lo contrario mostramos un mensaje de error
                                    if ($consulta_insertar_raza) {
                                        echo '<script>alert("Raza registrada satisfactoriamente");
                                        window.location.replace("razas.php");</script>';
                                    } else {
                                        echo '<script>alert("Ocurrió un error al realizar el registro");</script>';
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