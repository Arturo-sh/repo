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
    // Consulta para obtener datos de la tabla de usuarios
    $consulta_usuarios = "SELECT * FROM usuarios";
    // Ejecuta la consulta
    $resultado_consulta_usuarios = mysqli_query($conexion, $consulta_usuarios);
    // obtiene el número de filas de la consulta (número de usuarios)
    $total_usuarios = mysqli_num_rows($resultado_consulta_usuarios);
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
                                <h3 class="text-center">Usuarios</h3>
                                <?php
                                // Si el nivel_usuario es 1, muestra el botón de agregar nuevo registro
                                if ($nivel_usuario == 1) {
                                    echo '<div class"text-center"><a class="btn btn-success" href="agregarUsuario.php">Nuevo usuario</a></div>';
                                }
                                ?>
                            </div>

                            <?php
                            // Si el total de registros es menor o igual a 0, muestra un mensaje
                            if ($total_usuarios <= 0) {
                                echo "<h4 class='mt-5 mb-5 text-center text-danger'>Aun no hay usuarios dados de alta</h4>";
                            } else {
                            ?>
                                <div class="card-body table-responsive text-center" style="overflow:auto;">
                                    <table class="table table-hover border">
                                        <thead class="bg-primary">
                                            <tr>
                                                <th>Id de usuario</th>
                                                <th>Nombre de usuario</th>
                                                <th>Tipo de usuario</th>
                                                <th>Opciones</th>
                                            </tr>
                                            <?php
                                            $contador = 0;
                                            // Mientras haya registros, los muestra en la tabla
                                            while ($fila = mysqli_fetch_array($resultado_consulta_usuarios)) {
                                                // Alternar colores de las filas de la tabla
                                                if ($contador % 2 == 0) {
                                                    echo "<tr class='border' style='background: #bdd7ee;'>";
                                                } else {
                                                    echo "<tr class='border' style='background: #c8d2ca;'>";
                                                }
                                            ?>
                                                <!-- Muestra las columnas de datos para cada registro -->
                                                <td><?php echo $fila['id']; ?></td>
                                                <td><?php echo $fila['nombre_usuario']; ?></td>
                                                <?php
                                                if ($fila['nivel'] == 1) {
                                                    echo "<td>Administrador</td>";
                                                } else {
                                                    echo "<td>Usuario</td>";
                                                }
                                                ?>
                                                <td>
                                                    <?php
                                                    // Si el nivel_usuario es 1, muestra los botones de editar y eliminar
                                                    if ($nivel_usuario == 1) {
                                                        echo "<a class='btn btn-primary btn-sm' href='actualizarUsuario.php?id=" . $fila['id'] . "'><span class='icon-pencil'></span></a>
                                                        <a class='btn btn-danger btn-sm' onclick='Delete($fila[id]);'><span class='icon-trashcan'></span></a>";
                                                    }
                                                    ?>
                                                </td>
                                                </tr>
                                        <?php
                                                $contador += 1;
                                            }
                                            // Se libera la memoria del resultado de la consulta
                                            mysqli_free_result($resultado_consulta_usuarios);
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
            // Función para eliminar un usuario
            function Delete(id_usuario) {
                if (confirm('¿Esta seguro que desea eliminar este usuario?')) {
                    document.location = 'usuarios.php?idUsuarioEliminar=' + id_usuario;
                }
            }
        </script>

        <?php
        if ($nivel_usuario == 1) {
            if (isset($_GET['idUsuarioEliminar'])) {
                // Se obtiene el id del usuario a eliminar
                $id_usuario = $_GET['idUsuarioEliminar'];
                // Se crea la consulta para eliminar el usuario
                $consulta_eliminacion_usuario = "DELETE FROM usuarios WHERE id = '$id_usuario'";
                // Se ejecuta la consulta para eliminar el usuario
                $resultado_eliminacion_usuario = mysqli_query($conexion, $consulta_eliminacion_usuario);
                // Si la consulta se ejecuta correctamente, muestra un mensaje de éxito y regresa a la página de registros,
                // en caso contrario, muestra un mensaje de error
                if ($resultado_eliminacion_usuario) {
                    echo '<script>alert("Usuario eliminado satisfactoriamente");</script>';
                } else {
                    echo '<script>alert("Ha ocurridó un error al intentar eliminar el usuario!");</script>';
                }
                echo '<script>window.location.href = "usuarios.php"</script>';
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