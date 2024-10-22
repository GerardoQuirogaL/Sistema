<?php
session_start();
$rol = $_SESSION['rol']; // Asegúrate de guardar el rol cuando el usuario inicie sesión

if ($rol === 'admin') {
    // Mostrar las opciones de agregar, actualizar y eliminar
    echo '<a href="panelqr.php">Agregar</a>';
    echo '<a href="actualizacionesempleado.php">Actualizar Empleado</a>';
    echo '<a href="actualizacionesinvitado.php">Actualizar Invitado</a>';
    echo '<a href="actualizacionesproveedor.php">Actualizar Proveedor</a>';
    echo '<a href="actualizacionesusuario.php">Actualizar Usuario</a>';
} elseif ($rol === 'usuario') {
    // Mostrar solo la opción de agregar
    echo '<a href="panelqr.php">Agregar</a>';
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Palladium Hotel Group</title>
    <link rel="icon" href="img/vista.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-color: aliceblue;
        }
    </style>
</head>
<body>
    <?php
    // Incluir la conexión a la base de datos
    require 'conexion.php';
    require 'phpqrcode/qrlib.php'; // Librería PHP QR Code
    require './Perfil/navbar.php';
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

        <!-- Script para manejar los formularios -->
        <script>
            const btnEmpleado = document.getElementById('btnEmpleado');
            const btnInvitado = document.getElementById('btnInvitado');
            const btnProveedor = document.getElementById('btnProveedor');
            const formContainer = document.getElementById('formContainer');

            // Limpiar el contenedor del formulario
            function clearForm() {
                formContainer.innerHTML = '';
            }

            // Formulario de registro de empleados
            function formEmpleado() {
                clearForm();
                formContainer.innerHTML = `
                    <h4>Registro de Empleado</h4>
                    <form action="PanelQr.php" method="POST">
                    <input type="hidden" name="tipo" value="empleado">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre y Apellido <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="numero_colaborador" class="form-label">Número de Colaborador <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="numero_colaborador" name="numero_colaborador" required>
                        </div>
                        <div class="mb-3">
                            <label for="area" class="form-label">Departamento</label>
                            <select class="form-select" id="area" name="area" required>
                            <option value="" disabled selected>Selecciona un Departamento</option>
                            <option value="RRHH">RRHH</option>
                            <option value="Mantenimiento">Mantenimiento</option>
                            <option value="Lavanderia">Lavanderia</option>
                            <option value="Roperia">Roperia</option>
                        </select>
                        </div>

                        <div class="mb-3">
                        <label for="tipo_Qr" class="form-label">Tipo de Qr <span class="text-danger">*</span></label>
                                <select class="form-control" id="tipo_Qr" name="tipo_Qr" required>
                            <option value="" disabled selected>Selecciona una opción</option>
                            <option value="permanente">Permanente</option>
                            <option value="temporal">Temporal</option>
                                </select>
                        </div>

                        <div class="mb-3">
                            <label for="placas" class="form-label">Placas del Vehículo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="placas" name="placas" required>
                        </div>
                        <div class="mb-3">
                            <label for="modelo_marca" class="form-label">Modelo y Marca <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="modelo_marca" name="modelo_marca" required>
                        </div>
                        <div class="mb-3">
                            <label for="color" class="form-label">Color del Vehículo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="color" name="color" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Registrar Empleado</button>
                    </form>
                `;
            }

            // Formulario de registro de invitados
            function formInvitado() {
                clearForm();
                formContainer.innerHTML = `
                    <h4>Registro de Invitado</h4>
                    <form action="PanelQr.php" method="POST">
                        <input type="hidden" name="tipo" value="invitado">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre y Apellido <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="area_asiste" class="form-label">Área a la que Asiste <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="area_asiste" name="area_asiste" required>
                        </div>

                        <div class="mb-3">
                        <label for="tipo_Qr" class="form-label">Tipo de Qr <span class="text-danger">*</span></label>
                                <select class="form-control" id="tipo_Qr" name="tipo_Qr" required>
                            <option value="" disabled selected>Selecciona una opción</option>
                            <option value="permanente">Permanente</option>
                            <option value="temporal">Temporal</option>
                                </select>
                        </div>

                        <div class="mb-3">
                            <label for="placas" class="form-label">Placas del Vehículo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="placas" name="placas" required>
                        </div>
                        <div class="mb-3">
                            <label for="modelo_marca" class="form-label">Modelo y Marca <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="modelo_marca" name="modelo_marca" required>
                        </div>
                        <div class="mb-3">
                            <label for="color" class="form-label">Color del Vehículo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="color" name="color" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Registrar Invitado</button>
                    </form>
                `;
            }

            // Formulario de registro de proveedores
            function formProveedor() {
                clearForm();
                formContainer.innerHTML = `
                    <h4>Registro de Proveedor</h4>
                    <form action="PanelQr.php" method="POST">
                        <input type="hidden" name="tipo" value="proveedor">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre y Apellido <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="proveedor" class="form-label">Nombre del Proveedor <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="proveedor" name="proveedor" required>
                        </div>

                        <div class="mb-3">
                        <label for="tipo_Qr" class="form-label">Tipo de Qr <span class="text-danger">*</span></label>
                                <select class="form-control" id="tipo_Qr" name="tipo_Qr" required>
                            <option value="" disabled selected>Selecciona una opción</option>
                            <option value="permanente">Permanente</option>
                            <option value="temporal">Temporal</option>
                                </select>
                        </div>

                        <div class="mb-3">
                            <label for="placas" class="form-label">Placas del Vehículo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="placas" name="placas" required>
                        </div>
                        <div class="mb-3">
                            <label for="modelo_marca" class="form-label">Modelo y Marca <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="modelo_marca" name="modelo_marca" required>
                        </div>
                        <div class="mb-3">
                            <label for="color" class="form-label">Color del Vehículo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="color" name="color" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Registrar Proveedor</button>
                    </form>
                `;
            }

            btnEmpleado.onclick = formEmpleado;
            btnInvitado.onclick = formInvitado;
            btnProveedor.onclick = formProveedor;
        </script>
    </div>
</body>
</html>
