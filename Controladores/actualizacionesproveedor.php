<?php
// Incluir el archivo de conexión
require '../conexion.php';
// Incluir la librería PHP QR Code
include('../phpqrcode/qrlib.php');

// Actualizar proveedor
if (isset($_POST['actualizar'])) {
    $id_ = $_POST['id'];
    $nombre_apellido_ = $_POST['nombre_apellido'];
    $proveedor_ = $_POST['proveedor'];
    $placas_vehiculos_ = $_POST['placas_vehiculos'];
    $modelo_marca_ = $_POST['modelo_marca'];
    $color_vehiculo_ = $_POST['color_vehiculo'];

    // Generar el contenido del QR usando los datos actualizados
    $qrContent = "Nombre: $nombre_apellido_  \nproveedor: $proveedor_ \nPlacas: $placas_vehiculos_ \nVehículo: $modelo_marca_ ($color_vehiculo_)";

    // Ruta donde se guardará la imagen del código QR
    $qrFilePath = "../img_qr/qr_" . $proveedor_ . ".png";

    // Generar el código QR
    QRcode::png($qrContent, $qrFilePath);

    // Guardar la ruta o el nombre del archivo QR en la base de datos
    $qr_code_ = $qrFilePath;

    $sql = "UPDATE proveedores SET nombre_apellido = ?, proveedor = ?, placas_vehiculos = ?, modelo_marca = ?, color_vehiculo = ?, qr_code = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt->execute([$nombre_apellido_, $proveedor_, $placas_vehiculos_, $modelo_marca_, $color_vehiculo_, $qr_code_, $id_])) {
        echo "<div class='alert alert-success' role='alert'>Empleado y código QR actualizados correctamente</div>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error al actualizar el empleado</div>";
    }
}

// Eliminar proveedor
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];

    $sql = "DELETE FROM proveedores WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt->execute([$id])) {
        echo "<div class='alert alert-success' role='alert'>Proveedor eliminado correctamente</div>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error al eliminar el Proveedor</div>";
    }
}

// Obtener proveedor
$sql = "SELECT id, nombre_apellido, proveedor, placas_vehiculos, modelo_marca, color_vehiculo, qr_code FROM proveedores";
$stmt = $conn->query($sql);
$proveedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        <h1 class="text-center mb-4">Gestión de Proveedores</h1>

        <!-- Formulario de actualización -->
        <?php if (isset($_GET['id'])): 
            $id = $_GET['id'];
            $sql = "SELECT nombre_apellido, proveedor, placas_vehiculos, modelo_marca, color_vehiculo, qr_code FROM proveedores WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$id]);
            $proveedor = $stmt->fetch(PDO::FETCH_ASSOC);
        ?>
        <form method="POST" class="mb-4">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="mb-3">
                <label for="nombre_apellido" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre_apellido" name="nombre_apellido" value="<?php echo $proveedor['nombre_apellido']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="proveedor" class="form-label">Proveedor</label>
                <input type="text" class="form-control" id="proveedor" name="proveedor" value="<?php echo $proveedor['proveedor']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="placas_vehiculos" class="form-label">Placas del Vehículo</label>
                <input type="text" class="form-control" id="placas_vehiculos" name="placas_vehiculos" value="<?php echo $proveedor['placas_vehiculos']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="modelo_marca" class="form-label">Modelo y Marca del Vehículo</label>
                <input type="text" class="form-control" id="modelo_marca" name="modelo_marca" value="<?php echo $proveedor['modelo_marca']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="color_vehiculo" class="form-label">Color del Vehículo</label>
                <input type="text" class="form-control" id="color_vehiculo" name="color_vehiculo" value="<?php echo $proveedor['color_vehiculo']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="qr_code" class="form-label">Codigo QR</label>
                <input type="text" class="form-control" id="qr_code" name="qr_code" value="<?php echo $proveedor['qr_code']; ?>" required>
            </div>
            <button type="submit" name="actualizar" class="btn btn-primary">Actualizar</button>
        </form>
        <?php endif; ?>

        <!-- Tabla de proveedor -->
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Proveedor</th>
                    <th>Placas del Vehículo</th>
                    <th>Modelo y Marca</th>
                    <th>Color del Vehículo</th>
                    <th>QR</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($proveedores as $row): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['nombre_apellido']; ?></td>
                    <td><?php echo $row['proveedor']; ?></td>
                    <td><?php echo $row['placas_vehiculos']; ?></td>
                    <td><?php echo $row['modelo_marca']; ?></td>
                    <td><?php echo $row['color_vehiculo']; ?></td>
                    <td><?php echo $row['qr_code']; ?></td>
                    <td>
                        <a href="actualizacionesproveedor.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil-fill"></i>
                        </a>
                        <a href="actualizacionesproveedor.php?eliminar=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este empleado?');">
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