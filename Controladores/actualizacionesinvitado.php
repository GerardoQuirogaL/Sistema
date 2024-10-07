<?php
// Incluir el archivo de conexión
require '../conexion.php';
// Incluir la librería PHP QR Code
include('../phpqrcode/qrlib.php');

// Actualizar invitado
if (isset($_POST['actualizar'])) {
    $id_ = $_POST['id'];
    $nombre_apellido_ = $_POST['nombre_apellido'];
    $area_asistencia_ = $_POST['area_asistencia'];
    $placas_vehiculo_ = $_POST['placas_vehiculo'];
    $modelo_marca_ = $_POST['modelo_marca'];
    $color_vehiculo_ = $_POST['color_vehiculo'];

    // Generar el contenido del QR usando los datos actualizados
    $qrContent = "Nombre: $nombre_apellido_  \nasistencia: $area_asistencia_ \nPlacas: $placas_vehiculo_ \nVehículo: $modelo_marca_ ($color_vehiculo_)";

    // Ruta donde se guardará la imagen del código QR
    $qrFilePath = "../img_qr/qr_" . $nombre_apellido_ . ".png";

    // Generar el código QR
    QRcode::png($qrContent, $qrFilePath);

    // Guardar la ruta o el nombre del archivo QR en la base de datos
    $qr_code_ = $qrFilePath;

    $sql = "UPDATE invitados SET nombre_apellido = ?, area_asistencia = ?, placas_vehiculo = ?, modelo_marca = ?, color_vehiculo = ?, qr_code = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt->execute([$nombre_apellido_, $area_asistencia_, $placas_vehiculo_, $modelo_marca_, $color_vehiculo_, $qr_code_, $id_])) {
        echo "<div class='alert alert-success' role='alert'>Empleado y código QR actualizados correctamente</div>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error al actualizar el empleado</div>";
    }
}

// Eliminar invitado
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];

    $sql = "DELETE FROM invitados WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt->execute([$id])) {
        echo "<div class='alert alert-success' role='alert'>Invitados eliminado correctamente</div>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error al eliminar el Invitado</div>";
    }
}

// Obtener invitado
$sql = "SELECT id, nombre_apellido, area_asistencia, placas_vehiculo, modelo_marca, color_vehiculo, qr_code FROM invitados";
$stmt = $conn->query($sql);
$invitados = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Palladium Hotel Group</title>
    <link rel="icon" href="../img/vista.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center mb-4">Gestión de Invitados</h1>

        <!-- Formulario de actualización -->
        <?php if (isset($_GET['id'])): 
            $id = $_GET['id'];
            $sql = "SELECT nombre_apellido, area_asistencia, placas_vehiculo, modelo_marca, color_vehiculo, qr_code FROM invitados WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$id]);
            $invitado = $stmt->fetch(PDO::FETCH_ASSOC);
        ?>
        <form method="POST" class="mb-4">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="mb-3">
                <label for="nombre_apellido" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre_apellido" name="nombre_apellido" value="<?php echo $invitado['nombre_apellido']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="area_asistencia" class="form-label">Área de Asitencia</label>
                <input type="text" class="form-control" id="area_asistencia" name="area_asistencia" value="<?php echo $invitado['area_asistencia']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="placas_vehiculo" class="form-label">Placas del Vehículo</label>
                <input type="text" class="form-control" id="placas_vehiculo" name="placas_vehiculo" value="<?php echo $invitado['placas_vehiculo']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="modelo_marca" class="form-label">Modelo y Marca del Vehículo</label>
                <input type="text" class="form-control" id="modelo_marca" name="modelo_marca" value="<?php echo $invitado['modelo_marca']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="color_vehiculo" class="form-label">Color del Vehículo</label>
                <input type="text" class="form-control" id="color_vehiculo" name="color_vehiculo" value="<?php echo $invitado['color_vehiculo']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="qr_code" class="form-label">Codigo QR</label>
                <input type="text" class="form-control" id="qr_code" name="qr_code" value="<?php echo $invitado['qr_code']; ?>" required>
            </div>
            <button type="submit" name="actualizar" class="btn btn-primary">Actualizar</button>
        </form>
        <?php endif; ?>

        <!-- Tabla de invitado -->
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Área de Asistencia</th>
                    <th>Placas del Vehículo</th>
                    <th>Modelo y Marca</th>
                    <th>Color del Vehículo</th>
                    <th>QR</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($invitados as $row): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['nombre_apellido']; ?></td>
                    <td><?php echo $row['area_asistencia']; ?></td>
                    <td><?php echo $row['placas_vehiculo']; ?></td>
                    <td><?php echo $row['modelo_marca']; ?></td>
                    <td><?php echo $row['color_vehiculo']; ?></td>
                    <td><?php echo $row['qr_code']; ?></td>
                    <td>
                        <a href="actualizacionesinvitado.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil-fill"></i>
                        </a>
                        <a href="actualizacionesinvitado.php?eliminar=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este empleado?');">
                            <i class="bi bi-trash-fill"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Botón Volver -->
        <div class="text-center mt-4">
            <button class="btn btn-secondary" onclick="history.back()">Volver</button>
        </div>
    </div>

    <!-- Enlaces de Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>