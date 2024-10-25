<?php
require '../conexion.php';
require '../phpqrcode/qrlib.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $numeroColaborador = $_POST['numero_colaborador'];
    $area = $_POST['area'];
    $placas = $_POST['placas'];
    $modeloMarca = $_POST['modelo_marca'];
    $color = $_POST['color'];

    // Verificar si las placas ya están registradas en alguna de las tablas: empleados, invitados o proveedores
    $verificar_placas_empleados = $conn->prepare("SELECT * FROM empleados WHERE placas_vehiculo = :placas");
    $verificar_placas_invitados = $conn->prepare("SELECT * FROM invitados WHERE placas_vehiculo = :placas");
    $verificar_placas_proveedores = $conn->prepare("SELECT * FROM proveedores WHERE placas_vehiculos = :placas");
    
    // Vincular el parámetro de las placas
    $verificar_placas_empleados->bindParam(':placas', $placas);
    $verificar_placas_invitados->bindParam(':placas', $placas);
    $verificar_placas_proveedores->bindParam(':placas', $placas);

    // Ejecutar las consultas
    $verificar_placas_empleados->execute();
    $verificar_placas_invitados->execute();
    $verificar_placas_proveedores->execute();

    // Verificar si hay algún registro que coincida, se agrego un fetch para verificar el registro de placas
    $placa_existe_en_empleados = $verificar_placas_empleados->fetch(); 
    $placa_existe_en_invitados = $verificar_placas_invitados->fetch();
    $placa_existe_en_proveedores = $verificar_placas_proveedores->fetch();

    //Si las placas ya existen en alguna de las tablas, mostrar error

    if ($placa_existe_en_empleados || $placa_existe_en_invitados || $placa_existe_en_proveedores){
        echo "<div class='alert alert-danger text-center'>Error: Ya existe un registro con esas placas en empleados, invitados o proveedores.</div>";
    } //prueba de placas

    // Generar el contenido del código QR siempre y cuando no existan las placas
    $contenidoQR = "$nombre - \n$numeroColaborador - \n$area - \n$placas - \n$modeloMarca - ($color)";
    $filename = "../img_qr/qr_" . $numeroColaborador . ".png";

    QRcode::png($contenidoQR, $filename, QR_ECLEVEL_L, 4);

    // Insertar en la base de datos
    $sql = "INSERT INTO empleados (nombre_apellido, numero_colaborador, area, placas_vehiculo, modelo_marca, color_vehiculo, qr_code) 
            VALUES ('$nombre', '$numeroColaborador', '$area', '$placas', '$modeloMarca', '$color', '$filename')";

    if ($conn->query($sql)) {
        echo "<div class='text-center'><img src='$filename' alt='código QR'></div>";
        echo "<a href='$filename' download class='btn btn-primary'>Descargar Código QR</a>";
    } else {
        echo "<div class='alert alert-danger text-center'>Error al registrar: " . $conn->error  . "</div>";
    }
}


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Empleado</title>
</head>
<body>
    <h4>Registro de Empleado</h4>
    <form action="Empleado.php" method="POST">
        <input type="hidden" name="tipo" value="empleado">
        <!-- Formulario completo del empleado... -->
        <button type="submit" class="btn btn-primary">Registrar Empleado</button>
    </form>
</body>
</html>
