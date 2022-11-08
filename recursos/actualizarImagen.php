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
        // Consulta para obtener la imagen y arete del bovino
        $consulta_ganado = "SELECT arete, imagen_bovino FROM registros WHERE arete='$id'";
        // Ejecutamos la consulta
        $resultado_consulta_ganado = mysqli_query($conexion, $consulta_ganado);
        // Almacenamos los datos en un arreglo
        while ($fila = mysqli_fetch_array($resultado_consulta_ganado)) {
            $numero_arete = $fila['arete'];
            $imagen    = $fila['imagen_bovino'];
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
        <header> <?php include '../componentes/encabezado.php'; ?></header>

        <main class="container" role="main">
            <div class="container container-fluid">
                <div class="row">
                    <div class="page-section">
                        <div class="card">
                            <div class="card-header">
                                <h3>Actualizar imagen</h3>
                            </div>
                            <div class="card-body">
                                <form class="row g-3" method="POST" action="" enctype="multipart/form-data">
                                    <div class="col-md-6">
                                        <label class="form-label">Seleccione una imagen</label>
                                        <input type="file" id="image" class="form-control" name="image" required>
                                    </div>

                                    <div class="container-fluid h-100">
                                        <div class="row w-100 align-items-center">
                                            <div class="col text-center">
                                                <button type="submit" id="btn-guardar" class="btn btn-success" name="btn-guardar">Guardar</button>
                                                <button type="reset" id="btn-cancelar" class="btn btn-danger" name="btn-cancelar" onclick="history.go(-1);">Cancelar</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <?php
                                function compressImage($source, $destination, $quality)
                                {
                                    // Obtenemos la información de la imagen
                                    $imgInfo = getimagesize($source);
                                    $mime = $imgInfo['mime'];

                                    // Creamos una imagen
                                    switch ($mime) {
                                        case 'image/jpeg':
                                            $image = imagecreatefromjpeg($source);
                                            break;
                                        case 'image/png':
                                            $image = imagecreatefrompng($source);
                                            break;
                                        case 'image/gif':
                                            $image = imagecreatefromgif($source);
                                            break;
                                        default:
                                            $image = imagecreatefromjpeg($source);
                                    }
                                    // Guardamos la imagen
                                    imagejpeg($image, $destination, $quality);
                                    // Devolvemos la imagen comprimida
                                    return $destination;
                                }

                                //Declaramos la ruta donde se guardara la imagen
                                $url = '../bovinos/';

                                //Ahora obtenemos los datos enviados por método POST al oprimir el boton de submit
                                if (isset($_POST['btn-guardar'])) {
                                    //Verificamos si la imagen se ha cargado
                                    if (!isset($_FILES["image"]) || $_FILES["image"]["error"] > 0) {
                                        echo "Ha ocurrido un error.";
                                    } else {
                                        $imagenRuta = basename($_FILES['image']['name']);
                                        $imagenBD = $_FILES['image']['name'];
                                        $imageUploadPath = $url . $imagenRuta;
                                        $fileType = pathinfo($imageUploadPath, PATHINFO_EXTENSION);

                                        $allowTypes = array('jpg', 'png', 'jpeg');
                                        if (in_array($fileType, $allowTypes)) {

                                            // Obtenemos la imagen temoporal
                                            $imageTemp = $_FILES["image"]["tmp_name"];

                                            // Comprimos el fichero
                                            $compressedImage = compressImage($imageTemp, $imageUploadPath, 15);

                                            if ($compressedImage) {
                                                // Consulta para actualizar la imagen
                                                $update = "UPDATE registros SET imagen_bovino = '$imagenBD' WHERE arete = '$numero_arete'";
                                                // Ejecutamos la consulta
                                                $resultado = mysqli_query($conexion, $update);
                                                // Verificamos si se actualizo la imagen en caso de que si se actualizo se redirecciona a la pagina de registros
                                                if ($resultado) {
                                                    echo '<script>alert("Imagen actualizada");</script>';
                                                } else {
                                                    echo "<script>alert('Ocurrió un error al actualizar la imagen');</script>";
                                                }
                                                echo "<script>window.location.replace('registros.php');</script>";
                                            }
                                        } else {
                                            echo "<script>alert('La extensión o el tamaño de los archivos no es correcta');</script>";
                                        }
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