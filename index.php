<?php

session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Inicio de sesión</title>
	<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
</head>

<body>
	<section class="h-100">
		<div class="container h-100">
			<div class="row justify-content-sm-center h-100">
				<div class="col-xxl-4 col-xl-5 col-lg-5 col-md-7 col-sm-9">
					<div class="text-center my-5">
						<img src="images/logotipo.png" style="width: 140px; margin: -10px;" class="rounded mx-auto d-block">
					</div>
					<div class="card shadow-lg">
						<div class="card-body p-5">

							<?php
							include_once "componentes/conexion.php";
							if (isset($_POST['btn-inicio-sesion'])) {

								$usuario = $_POST['txtuser']; // Se obtiene el nombre de usuario
								$password = $_POST['txtpassword']; // Se obtiene la contraseña
								$salt = "";

								// Consulta a la tabla usuarios en busca del patrón ingresado por el usuario
								$coincidencia_usuario = mysqli_query($conexion, "SELECT salt FROM usuarios WHERE nombre_usuario = '$usuario'");

								// Si se encuentra un patrón con lo que ingreso el usuario se obtiene la salt que sirve para "descifrar" la contraseña del usuario
								if (mysqli_num_rows($coincidencia_usuario) > 0) {
									while ($fila = mysqli_fetch_array($coincidencia_usuario)) {
										$salt = $fila['salt'];
									}
								}

								// Se obtiene la contraseña cifrada con la salt obtenida anteriormente
								$password_segura = sha1($password . $salt);

								// Se consulta a la tabla usuarios en busca del patrón ingresado por el usuario
								$datos_usuario = mysqli_query($conexion, "SELECT * FROM usuarios WHERE nombre_usuario = '$usuario' AND password = '$password_segura'");
								// Se obtiene el número de filas que coinciden con el patrón ingresado por el usuario
								$resultado_busqueda = mysqli_num_rows($datos_usuario);
								// Si se encuentra un patrón con lo que ingreso el usuario se inicia sesión
								if ($resultado_busqueda > 0) {
									while ($fila = mysqli_fetch_array($datos_usuario)) {
										$id_usuario = $fila['id']; // Obtenemos el id del usuario
										$nombre_usuario = $fila['nombre_usuario']; 	// Obtenemos el nombre de usuario
										$nivel = $fila['nivel']; // Obtenemos el nivel al que pertenece (usuario/superusuario)
									}
									// Agregamos las credenciales de acceso del usuario a las variables de sesión
									$_SESSION['id'] = $id_usuario;
									$_SESSION['nombre_usuario'] = $nombre_usuario;
									$_SESSION['nivel'] = $nivel;
									// Redireccionamos al usuario a la página de principal
									echo '<script type="text/javascript">window.location.href = "recursos/principal.php"; </script>';
								} else {
									// Si no se encuentra un patrón con lo que ingreso el usuario se muestra un mensaje de error
									echo "<p class='text-danger text-center'>* Usuario y/o contraseña incorrectos!!</p>";
								}
							}
							?>

							<h1 class="fs-4 card-title fw-bold mb-4">Iniciar Sesión</h1>
							<form method="POST" class="needs-validation" novalidate autocomplete="off">
								<div class="mb-3">
									<label class="mb-2 text-muted" for="txtuser">Usuario</label>
									<input type="text" id="txtuser" class="form-control" name="txtuser" required autofocus>
								</div>
								<div class="mb-3">
									<label class="mb-2 text-muted" for="txtpassword">Contraseña</label>
									<input type="password" id="txtpassword" class="form-control" name="txtpassword" required>
								</div>
								<div class="d-flex align-items-center">
									<button type="submit" id="btn-inicio-sesion" class="btn btn-primary ms-auto" name="btn-inicio-sesion">Iniciar Sesión</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<script src="js/bootstrap.bundle.js"></script>
</body>

</html>