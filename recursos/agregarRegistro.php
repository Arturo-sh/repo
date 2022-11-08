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
    // Consultamos las razas disponibles
    $consulta_razas = "SELECT * FROM razas_ganado";
    // Ejecutamos la consulta
    $resultado_consulta_razas = mysqli_query($conexion, $consulta_razas);

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
                                <h3>Datos de registro</h3>
                            </div>
                            <div class="card-body">
                                <form class="row g-3" method="POST" action="" enctype="multipart/form-data">
                                    <div class="col-md-6">
                                        <label class="form-label">Arete del bovino</label>
                                        <input type="text" id="txtarete" class="form-control" name="txtarete" pattern="[0-9]{1,}" title="Campo exclusivo para números" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Tipo de raza</label>
                                        <select id="txtraza" class="form-select" name="txtraza" required>
                                            <?php
                                            // Mostramos las razas en el campo select
                                            while ($fila = mysqli_fetch_array($resultado_consulta_razas)) {
                                                echo '<option value="' . $fila['id_raza'] . '">' . $fila['nombre_raza'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Nombre</label>
                                        <input type="text" id="txtnombre" class="form-control" name="txtnombre" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Imagen</label>
                                        <input type="file" id="image" class="form-control" name="image" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Sexo</label>
                                        <select id="txtsexo" class="form-select" name="txtsexo">
                                            <option value="Macho">Macho</option>
                                            <option value="Hembra">Hembra</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Peso</label>
                                        <input type="text" id="txtpeso" class="form-control" name="txtpeso" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Color</label>
                                        <input type="text" id="txtcolor" class="form-control" name="txtcolor" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Edad</label>
                                        <input type="text" id="txtedad" class="form-control" name="txtedad" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Fecha registro</label>
                                        <input type="date" id="txtregistro" class="form-control" name="txtregistro" required>
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

                                            //Si la compresión se hizo correctamente ejecuta el query y la imagen se guarda
                                            //en la ruta antes declara junto con el nombre de la imagen y muestra un alert
                                            //de exito y si hay error, muestra un alert de error y redirecciona a la pagina principal
                                            $arete = $_POST['txtarete'];
                                            $id_raza = $_POST['txtraza'];
                                            $nombre = $_POST['txtnombre'];
                                            $archivo = $_FILES['image']['name'];
                                            $sexo = $_POST['txtsexo'];
                                            $peso = $_POST['txtpeso'];
                                            $edad = $_POST['txtedad'];
                                            $fecha = $_POST['txtregistro'];
                                            $color = $_POST['txtcolor'];
                                            if ($compressedImage) {
                                                // Consulta para insertar un registro en la tabla registros
                                                $consulta_insercion_registro = "INSERT INTO registros VALUES (null, '$arete', '$id_raza', '$nombre', '$archivo', '$sexo', '$peso', '$edad', '$fecha', '$color')";
                                                // Ejecución de la consulta para insertar registros
                                                $resultado_insercion_registro = mysqli_query($conexion, $consulta_insercion_registro);
                                                // Si la insercion se realiza de manera exitosa muestra un mensaje de exito
                                                // en caso contrario muestra un mensaje de error y redirecciona al formulario
                                                if ($resultado_insercion_registro) {
                                                    echo '<script>alert("Registro realizado satisfactoriamente");
                                                    window.location.replace("registros.php");</script>";';
                                                }
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