<?php
require '../conexion.php';
require '../phpqrcode/qrlib.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $areaAsiste = $_POST['area_asiste'];
    $placas = $_POST['placas'];
    $modeloMarca = $_POST['modelo_marca'];
    $color = $_POST['color'];
    
    $fechaActual = new DateTime();
    $vencimiento = $fechaActual->modify('+3 days')->format('Y-m-d H:i:s');
    $contenidoQR = "$nombre - \n$areaAsiste - \n$placas - \n$modeloMarca - ($color) - Vence el: $vencimiento";
    $filename = "../img_qr/qr_" . $nombre . ".png";

    QRcode::png($contenidoQR, $filename, QR_ECLEVEL_L, 4);

    $sql = "INSERT INTO invitados (nombre_apellido, area_asistencia, placas_vehiculo, modelo_marca, color_vehiculo, qr_code) 
            VALUES ('$nombre', '$areaAsiste', '$placas', '$modeloMarca', '$color', '$filename')";

    if ($conn->query($sql)) {
        echo "<div class='text-center'><img src='$filename' alt='código QR'></div>";
        echo "<a href='$filename' download class='btn btn-primary'>Descargar Código QR</a>";
    } else {
        echo "<div class='alert alert-danger text-center'>Error al registrar: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Invitado</title>
</head>
<body>
    <h4>Registro de Invitado</h4>
    <form action="Invitado.php" method="POST">
        <!-- Formulario completo del invitado... -->
        <button type="submit" class="btn btn-secondary">Registrar Invitado</button>
    </form>
</body>
</html>
