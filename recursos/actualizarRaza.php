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
        $id = $_GET['id'];
        // Consulta para obtener las razas
        $consulta_id_raza = "SELECT * FROM razas_ganado WHERE id_raza='$id'";
        // Ejecutamos la consulta
        $resultado_consulta_id = mysqli_query($conexion, $consulta_id_raza);
        // Almacenamos los datos obtenidos en un arreglo
        while ($fila = mysqli_fetch_array($resultado_consulta_id)) {
            $id_raza = $fila['id_raza'];
            $nombre_raza = $fila['nombre_raza'];
            $descripcion = $fila['descripcion'];
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
                                <h3>Datos del bovino</h3>
                            </div>
                            <div class="card-body">
                                <form class="row g-3" method="POST" action="">
                                    <input type="hidden" id="txtid" name="txtid" value="<?= $id_raza; ?>">
                                    <div class="col-md-6">
                                        <label class="form-label">Nombre de la raza</label>
                                        <input type="text" id="txtraza" class="form-control" name="txtraza" value="<?php echo $nombre_raza; ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Descripción</label>
                                        <input type="text" id="txtdescripcion" class="form-control" name="txtdescripcion" value="<?php echo $descripcion; ?>" required>
                                    </div>
                                    <div class="container-fluid h-100">
                                        <div class="row w-100 align-items-center">
                                            <div class="col text-center">
                                                <button type="submit" id="btn-actualizar" class="btn btn-success" name="btn-actualizar">Guardar</button>
                                                <button type="reset" id="btn-cancelar" class="btn btn-danger" name="btn-cancelar" onclick="history.go(-1);">Cancelar</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <?php
                                if (isset($_POST['btn-actualizar'])) {
                                    // Obtenemos los datos del formulario
                                    $id_raza_ganado = $_POST['txtid'];
                                    $nueva_raza = $_POST['txtraza'];
                                    $nueva_descripcion = $_POST['txtdescripcion'];
                                    // Consulta para actualizar los datos de la raza
                                    $consulta_actualizar_raza = "UPDATE razas_ganado SET nombre_raza = '$nueva_raza', descripcion = '$nueva_descripcion' WHERE id_raza = '$id_raza_ganado'";
                                    // Ejecutamos la consulta
                                    $resultado_consulta_actualizar = mysqli_query($conexion, $consulta_actualizar_raza);
                                    // Si la consulta se ejecuto correctamente mostramos un mensaje de exito
                                    // y redireccionamos a la pagina de razas
                                    if ($resultado_consulta_actualizar) {
                                        echo '<script>alert("Datos actualizados satisfactoriamente");
                                        window.location.replace("razas.php");</script>";';
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