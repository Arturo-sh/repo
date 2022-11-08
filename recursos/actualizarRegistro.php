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
    $consulta_razas = "SELECT id_raza, nombre_raza FROM razas_ganado";
    $datos_razas = mysqli_query($conexion, $consulta_razas);

    if (isset($_GET['id'])) {
        // Consulta para obtener los datos de un bovino
        $consulta_registro = "SELECT * FROM registros WHERE id_bovino=" . $_GET["id"];
        // Ejecución de la consulta
        $datos_bovino = mysqli_query($conexion, $consulta_registro);
        // Almacenamos los datos del bovino en un arreglo
        while ($fila = mysqli_fetch_array($datos_bovino)) {
            $id_bovino = $fila['id_bovino'];
            $numero_arete = $fila['arete'];
            $id_raza = $fila['id_raza'];
            $nombre = $fila['nombre'];
            $foto = $fila['imagen_bovino'];
            $sexo = $fila['sexo'];
            $peso = $fila['peso'];
            $edad = $fila['edad'];
            $fecha_registro = $fila['fecha_registro'];
            $color = $fila['color'];
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
                                    <input type="hidden" name="txtid" id="txtid" value="<?= $id_bovino; ?>">
                                    <div class="col-md-6">
                                        <label class="form-label">Arete del animal</label>
                                        <input type="text" id="txtarete" class="form-control" name="txtarete" value="<?php echo $numero_arete; ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Raza</label>
                                        <select id="txtraza" class="form-select" name="txtraza">
                                            <?php
                                            // Se recorren las razas y se muestran en el select de acorde al resultado de la consulta a la base de datos
                                            while ($fila = mysqli_fetch_array($datos_razas)) {
                                                if ($fila['id_raza'] == $id_raza) {
                                                    echo '<option value="' . $fila['id_raza'] . '" selected>' . $fila['nombre_raza'] . ' </option>';
                                                } else {
                                                    echo '<option value="' . $fila['id_raza'] . '">' . $fila['nombre_raza'] . ' </option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Nombre</label>
                                        <input type="text" id="txtnombre" class="form-control" name="txtnombre" value="<?php echo $nombre; ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Peso</label>
                                        <input type="text" id="txtpeso" class="form-control" name="txtpeso" value="<?php echo $peso; ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Color</label>
                                        <input type="text" id="txtcolor" class="form-control" name="txtcolor" value="<?php echo $color; ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Edad</label>
                                        <input type="text" id="txtedad" class="form-control" name="txtedad" value="<?php echo $edad; ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Fecha registro</label>
                                        <input type="date" id="txtregistro" class="form-control" name="txtregistro" value="<?php echo $fecha_registro; ?>" required>
                                    </div>

                                    <div class="col-md-12 text-center">
                                        <label class="form-label">Imagen del bovino</label>
                                        <center><img src="../bovinos/<?php echo $foto; ?>" width="40%" /></center>
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
                                    // Se obtienen los datos del formulario (Incluso si no se modifican)
                                    $id_ganado = $_POST['txtid'];
                                    $nuevo_arete = $_POST['txtarete'];
                                    $nueva_raza = $_POST['txtraza'];
                                    $nuevo_nombre = $_POST['txtnombre'];
                                    $nuevo_peso = $_POST['txtpeso'];
                                    $nuevo_color = $_POST['txtcolor'];
                                    $nueva_edad = $_POST['txtedad'];
                                    $nueva_fecha = $_POST['txtregistro'];
                                    echo "Esta es la nueva fecha: " . $nueva_fecha;
                                    // Consulta para actualizar los datos del bovino
                                    $consulta_actualizar = "UPDATE registros SET arete = '$nuevo_arete', id_raza = '$nueva_raza', nombre = '$nuevo_nombre', peso = '$nuevo_peso',
                                    edad = '$nueva_edad', fecha_registro = '$nueva_fecha', color = '$nuevo_color' WHERE id_bovino = '$id_ganado'";
                                    // Se ejecuta la consulta
                                    $resultado_consulta_actualizar = mysqli_query($conexion, $consulta_actualizar);
                                    // Si la consulta se ejecuta correctamente se muestra un mensaje de confirmación
                                    // y se redirecciona a la página de registros
                                    if ($resultado_consulta_actualizar) {
                                        echo '<script>alert("Datos actualizados satisfactoriamente");
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