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
    // Consultamos las usuarios disponibles
    $consulta_usuarios = "SELECT * FROM usuarios";
    // Ejecutamos la consulta
    $resultado_consulta_usuarios = mysqli_query($conexion, $consulta_usuarios);

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
                                <h3>Datos del nuevo usuario</h3>
                            </div>
                            <div class="card-body">
                                <form class="row g-3" method="POST" action="">
                                    <div class="col-md-6">
                                        <label class="form-label">Nombre de usuario</label>
                                        <input type="text" id="nombre" class="form-control mb-4" name="username" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Contraseña</label>
                                        <input type="password" id="pass" class="form-control mb-4" name="password" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Confirmar contraseña</label>
                                        <input type="password" id="confPass" class="form-control mb-4" name="confirmPassword" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Tipo de usuario</label>
                                        <div class="form-check">
                                            <label class="form-check-label" for="flexCheckDefault">Superusuario</label>
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                            <input type="hidden" id="tipoUser" class="form-control" name="tipoUser">
                                        </div>
                                    </div>
                                    <div class="col-md-12 text-center mb-3">
                                        <input type="submit" id="btn" class="btn btn-success" name="btn-registrar-usuario" value="Dar de alta" disabled>
                                        <input type="reset" class="btn btn-danger" id="btn-back" value="Regresar" onclick="history.go(-1);">
                                    </div>
                                </form>

                                <?php
                                if (isset($_POST['btn-registrar-usuario'])) {
                                    // Obtenemos los datos del formulario
                                    $nombre_usuario = $_POST['username'];
                                    $password = $_POST['password'];
                                    $confirmar_password = $_POST['confirmPassword'];
                                    $tipo_usuario = $_POST['tipoUser'];

                                    // Validamos que las contraseñas coincidan
                                    if ($password == $confirmar_password) {
                                        // Encriptamos la contraseña
                                        $salt = "ajfsg";
                                        $password_segura = sha1($password . $salt);
                                        // Consulta para insertar los datos a la tabla usuarios
                                        $consulta_insercion_usuario = "INSERT INTO usuarios VALUES (null, '$nombre_usuario', '$password_segura', '$salt', '$tipo_usuario')";
                                        // Ejecutamos la consulta
                                        $resultado_insercion_usuario = mysqli_query($conexion, $consulta_insercion_usuario);
                                        // Validamos que la inserción se haya realizado correctamente
                                        // en caso de que no se haya realizado correctamente se muestra un mensaje de error
                                        if ($resultado_insercion_usuario) {
                                            echo "<script>alert('Usuario registrado satisfactoriamente');
                                            window.location.replace('usuarios.php');</script>";
                                        } else {
                                            echo "<script>alert('Error al registrar el usuario');</script>";
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