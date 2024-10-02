<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Registro QR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

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

    <!-- Contenedor Principal (50% de la pantalla) -->
    <div class="container w-50 mt-5 shadow p-5 bg-transparent rounded text-center">
        <!-- Logo -->
        <div class="text-center mb-4">
            <img src="img/Palladium.png" width="55" alt="">
            <img src="img/gp_hotels.png" width="55" alt="">
            <img src="img/TRS.png" width="55" alt="">
            <img src="img/ayre.png" width="55" alt="">
            <img src="img/fiesta.png" width="55" alt="">
            <img src="img/hardrock.jpg" width="55" alt="">
            <img src="img/oy-logo.png" width="55" alt="">
            <img src="img/ushuaia.png" width="55" alt="">
            <img src="img/pbh-logo.png" width="55" alt="">
        </div>

    <div class="container">
        <h2 class="text-center mb-4">Panel de Registro</h2>

        <!-- Selector de tipo de registro -->
        <div class="text-center mb-5">
            <h4>Seleccione el tipo de usuario a registrar:</h4>
            <div class="btn-group" role="group" aria-label="Opciones de Registro">
                <button class="btn btn-outline-primary" id="btnEmpleado">Registrar Empleado</button>
                <button class="btn btn-outline-secondary" id="btnInvitado">Registrar Invitado</button>
                <button class="btn btn-outline-success" id="btnProveedor">Registrar Proveedor</button>
            </div>
        </div>

        <!-- Formulario dinámico que se mostrará según la selección -->
        <div id="formContainer" class="p-4 shadow rounded bg-white"></div>
    </div>

    <!--aqui va ir una prueba-->
    <script>
        // Referencias a los botones
        const btnEmpleado = document.getElementById('btnEmpleado');
        const btnInvitado = document.getElementById('btnInvitado');
        const btnProveedor = document.getElementById('btnProveedor');
        const formContainer = document.getElementById('formContainer');

        // Función para limpiar el formulario actual
        function clearForm() {
            formContainer.innerHTML = '';
        }

        // Funciones para generar los formularios
        function formEmpleado() {
            clearForm();
            formContainer.innerHTML = `
                <h4>Registro de Empleado</h4>
                <form id="formEmpleado">
                    <div class="mb-3">
                        <label for="nombre_apellido" class="form-label">Nombre y Apellido</label>
                        <input type="text" class="form-control" id="nombre_apellido" required>
                    </div>
                    <div class="mb-3">
                        <label for="numero_colaborador" class="form-label">Número de Colaborador</label>
                        <input type="text" class="form-control" id="numero_colaborador" required>
                    </div>
                    <div class="mb-3">
                        <label for="area" class="form-label">Área</label>
                        <input type="text" class="form-control" id="area" required>
                    </div>
                    <div class="mb-3">
                        <label for="placas_vehiculo" class="form-label">Placas del Vehículo</label>
                        <input type="text" class="form-control" id="placas_vehiculo" required>
                    </div>
                    <div class="mb-3">
                        <label for="modelo_marca" class="form-label">Modelo y Marca</label>
                        <input type="text" class="form-control" id="modelo_marca" required>
                    </div>
                    <div class="mb-3">
                        <label for="color_vehiculo" class="form-label">Color del Vehículo</label>
                        <input type="text" class="form-control" id="color_vehiculo" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Registrar Empleado</button>
                </form>
            `;
        }

        function formInvitado() {
            clearForm();
            formContainer.innerHTML = `
                <h4>Registro de Invitado</h4>
                <form id="formInvitado">
                    <div class="mb-3">
                        <label for="nombre_apellido" class="form-label">Nombre y Apellido</label>
                        <input type="text" class="form-control" id="nombre_apellido" required>
                    </div>
                    <div class="mb-3">
                        <label for="area_asistencia" class="form-label">Área a la que Asiste</label>
                        <input type="text" class="form-control" id="area_asistencia" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="placas_vehiculo" class="form-label">Placas del Vehículo</label>
                        <input type="text" class="form-control" id="placas_vehiculo" required>
                    </div>
                    <div class="mb-3">
                        <label for="modelo_marca" class="form-label">Modelo y Marca</label>
                        <input type="text" class="form-control" id="modelo_marca" required>
                    </div>
                    <div class="mb-3">
                        <label for="color_vehiculo" class="form-label">Color del Vehículo</label>
                        <input type="text" class="form-control" id="color_vehiculo" required>
                    </div>
                    <button type="submit" class="btn btn-secondary">Registrar Invitado</button>
                </form>
            `;
        }

        function formProveedor() {
            clearForm();
            formContainer.innerHTML = `
                <h4>Registro de Proveedor</h4>
                <form id="formProveedor">
                    <div class="mb-3">
                        <label for="nombre_apellido" class="form-label">Nombre y Apellido</label>
                        <input type="text" class="form-control" id="nombre_apellido" required>
                    </div>
                    <div class="mb-3">
                        <label for="proveedor" class="form-label">Proveedor</label>
                        <input type="text" class="form-control" id="proveedor" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="placas_vehiculos" class="form-label">Placas del Vehículo</label>
                        <input type="text" class="form-control" id="placas_vehiculos" required>
                    </div>
                    <div class="mb-3">
                        <label for="modelo_marca" class="form-label">Modelo y Marca</label>
                        <input type="text" class="form-control" id="modelo_marca" required>
                    </div>
                    <div class="mb-3">
                        <label for="color_vehiculo" class="form-label">Color del Vehículo</label>
                        <input type="text" class="form-control" id="color_vehiculo" required>
                    </div>
                    <button type="submit" class="btn btn-success">Registrar Proveedor</button>
                </form>
            `;
        }

        // Asignar eventos a los botones
        btnEmpleado.addEventListener('click', formEmpleado);
        btnInvitado.addEventListener('click', formInvitado);
        btnProveedor.addEventListener('click', formProveedor);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
