<?php
require '../conexion.php';
require '../phpqrcode/qrlib.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $proveedor = $_POST['proveedor'];
    $placas = $_POST['placas'];
    $modeloMarca = $_POST['modelo_marca'];
    $color = $_POST['color'];

    $fechaActual = new DateTime();
    $vencimiento = $fechaActual->modify('+3 days')->format('Y-m-d H:i:s');
    $contenidoQR = "$nombre - \n$proveedor - \n$placas - \n$modeloMarca - ($color) - Vence el: $vencimiento";
    $filename = "../img_qr/qr_" . $proveedor . ".png";

    QRcode::png($contenidoQR, $filename, QR_ECLEVEL_L, 4);

    $sql = "INSERT INTO proveedores (nombre_apellido, proveedor, placas_vehiculos, modelo_marca, color_vehiculo, qr_code) 
            VALUES ('$nombre', '$proveedor', '$placas', '$modeloMarca', '$color', '$filename')";

    if ($conn->query($sql)) {
        echo "<div class='text-center'><img src='$filename' alt='código QR'></div>";
        echo "<a href='$filename' download class='btn btn-success'>Descargar Código QR</a>";
    } else {
        echo "<div class='alert alert-danger text-center'>Error al registrar: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Proveedor</title>
</head>
<body>
    <h4>Registro de Proveedor</h4>
    <form action="Proveedores.php" method="POST">
        <!-- Formulario completo del proveedor... -->
        <button type="submit" class="btn btn-success">Registrar Proveedor</button>
    </form>
</body>
</html>
