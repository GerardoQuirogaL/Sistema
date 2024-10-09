<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Palladium</title>

    <!-- Enlace de Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        .offcanvas-header img {
            max-width: 100px; /* Ajusta el tamaño de la imagen de usuario */
            margin-top: 10px;
        }

        .offcanvas-body {
            display: flex;
            justify-content: center; /* Centrar horizontalmente */
            align-items: center; /* Centrar verticalmente */
            height: 100%; /* Asegura que el contenedor ocupe todo el espacio disponible */
        }

        .nav-tabs {
            justify-content: center; /* Asegurarse de que las pestañas también estén centradas */
        }
    </style>
</head>
<body>
    <nav class="navbar bg-body-tertiary fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header text-center flex-column w-100">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Bienvenido Palladium</h5>
                    <!-- Imagen de usuario debajo del texto -->
                    <img src="img/usuario.jpg" alt="Imagen de usuario" class="img-fluid rounded-circle">
                    <button type="button" class="btn-close position-absolute top-0 end-0" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>

                <div class="offcanvas-body">
                    <!-- Menú de navegación -->
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" href="./Controladores/actualizacionesempleado.php">Cátalago de Empleados </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="./Controladores/actualizacionesproveedor.php">Cátalago de Proveedores</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="./Controladores/actualizacionesinvitado.php">Cátalago de Invitados </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="./procesar_entrada_salida.php">Registro de Entrada y Salida </a>
                        </li>
                    </ul>
                </div>

                <div class="offcanvas-footer">
                    <a href="logout.php" class="btn btn-outline-danger w-100">Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Enlace de Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
