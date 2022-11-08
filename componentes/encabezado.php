<div class="container mt-4">
  <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
    <a href="#" class="d-flex align-items-center mb-2 mb-lg-0 text-dark text-decoration-none">
      <img style="width: 60px; padding: 0 8px;" role="img" src="../images/logotipo.png" />
    </a>

    <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
      <li><a href="principal.php" class="nav-link px-2 link-dark">Buscar</a></li>
      <li><a href="registros.php" class="nav-link px-2 link-dark">Registros</a></li>
      <li><a href="razas.php" class="nav-link px-2 link-dark">Razas de ganado</a></li>
    </ul>
    <div class="dropdown text-end">
      <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
        <?= $_SESSION['nombre_usuario']; ?>
      </a>
      <ul class="dropdown-menu text-small" aria-labelledby="dropdownUser1">
        <?php
        if ($nivel_usuario == 1) {
          echo '<li><a class="dropdown-item" href="../recursos/usuarios.php">Usuarios</a></li>';
        }
        ?>
        <li><a class="dropdown-item" href="../componentes/cerrar.php">Cerrar Sesión</a></li>
      </ul>
    </div>
  </div>
</div>
</div>

<!-- Esta linea es para cargar los iconos desde fontawesome -->
<!-- <script src="https://kit.fontawesome.com/bff9991502.js" crossorigin="anonymous"></script> -->