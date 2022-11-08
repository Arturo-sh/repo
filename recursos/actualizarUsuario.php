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
        // Consulta para obtener los datos del usuario a modificar
        $consulta_usuarios = "SELECT * FROM usuarios WHERE id=" . $_GET["id"];
        // Ejecución de la consulta
        $datos_usuario = mysqli_query($conexion, $consulta_usuarios);
        // Almacenamos los datos del usuario en un arreglo
        while ($fila = mysqli_fetch_array($datos_usuario)) {
            $id_usuario = $fila['id'];
            $nombre = $fila['nombre_usuario'];
            $nivel = $fila['nivel'];
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
                                <h3>Datos del usuario</h3>
                            </div>
                            <div class="card-body">
                                <form class="row g-3" method="POST" action="">
                                    <input type="hidden" class="form-control mb-4" name="idUser" <?php if ($_GET) {
                                                                                                        echo "value='" . $id_usuario . "'";
                                                                                                    } ?>>
                                    <div class="col-md-6">
                                        <label class="form-label" for="">Nombre de usuario</label>
                                        <input type="text" id="nombre" class="form-control mb-4" name="username" value="<?php echo $nombre; ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="">Nueva contraseña</label>
                                        <input type="password" id="pass" class="form-control mb-4" name="password" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="">Confirmar contraseña</label>
                                        <input type="password" id="confPass" class="form-control mb-4" name="confirmPassword" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Tipo de usuario</label>
                                        <div class="form-check">
                                            <label class="form-check-label" for="flexCheckDefault">Superusuario</label>
                                            <?php
                                            // Si el usuario es de nivel 1 (administrador) se marca la casilla de nivel administrador
                                            if ($nivel == 1) {
                                                echo '<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" checked>';
                                                echo '<input class="form-control" type="hidden" name="tipoUser" id="tipoUser" value="1">';
                                            } else {
                                                echo '<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">';
                                                echo '<input class="form-control" type="hidden" name="tipoUser" id="tipoUser" value="2">';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-md-12 text-center">
                                        <input type="submit" id="btn" class="btn btn-success" name="btn-actualizar-usuario" value="Actualizar" disabled>
                                        <input type="reset" id="btn-back" class="btn btn-secondary" value="Regresar" onclick="history.go(-1);">
                                    </div>
                                </form>

                                <?php
                                if (isset($_POST['btn-actualizar-usuario'])) {
                                    // Obtenemos los datos actualizados del formulario
                                    $id = $_POST['idUser'];
                                    $nuevo_nombre = $_POST['username'];
                                    $nueva_password = $_POST['password'];
                                    $confirmar_password = $_POST['confirmPassword'];
                                    $typeUser = $_POST['tipoUser'];

                                    // Si los campos estan vacios muestra un mensaje y redirecciona hacia atras para rellenarlos
                                    if (empty($id) or empty($nuevo_nombre) or empty($nueva_password) or empty($typeUser)) {
                                        echo "<script>alert('Rellene todos los campos del formulario');
                                        window.history.go(-1);</script>";
                                    }

                                    // Validamos que las contraseñas coincidan
                                    if ($nueva_password == $confirmar_password) {
                                        // Encriptamos la contraseña
                                        $salt = "ajfsg";
                                        $password_segura = sha1($nueva_password . $salt);
                                        // Consulta para actualizar los datos a la tabla usuarios
                                        $consulta_actualizacion_usuario = "UPDATE usuarios SET nombre_usuario = '$nuevo_nombre', password = '$password_segura', salt = '$salt', nivel = '$typeUser' WHERE id = '$id'";
                                        // Ejecutamos la consulta
                                        $resultado_actualizacion_usuario = mysqli_query($conexion, $consulta_actualizacion_usuario);
                                        // Validamos que la actualización se haya realizado correctamente
                                        // en caso de que no se haya realizado correctamente se muestra un mensaje de error
                                        if ($resultado_actualizacion_usuario) {
                                            echo "<script>alert('Datos actualizados satisfactoriamente');
                                            window.location.replace('usuarios.php');</script>";
                                        } else {
                                            echo "<script>alert('Error al actualizar los datos');</script>";
                                        }
                                    } else {
                                        echo "<script>alert('Las contraseñas no coinciden');</script>";
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
        <script src="../js/main.js"></script>
    </body>

    </html>

<?php
} else {
    header('Location:../index.php');
}
?>