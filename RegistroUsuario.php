<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: aliceblue;
        }
    </style>

</head>
<body>
<?php
    // Incluir la conexión
    require 'conexion.php';
    ?>


    <div class="container w-50 mt-5 shadow p-5 bg-transparent rounded text-center">
        <h2 class="fw-bold text-center py-4">REGISTRO DE USUARIO</h2>

        <form class="mx-auto w-75">
            <div class="mb-3">
                <label for="email" class="form-label fw-bold">Correo Electrónico</label>
                <input type="email" class="form-control" id="emailRegistro" name="email">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-bold">Contraseña</label>
                <input type="password" class="form-control" id="passwordRegistro" name="password">
            </div>

            <div class="d-grid">
                <button type="button" onclick="registrar()" class="btn btn-success">Registrar</button>
            </div>

            <div class="my-3">
                <span>¿Ya tienes cuenta? <a href="index.php">Iniciar Sesión</a></span>
            </div>
        </form>
    </div>

    <script src="registro.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
