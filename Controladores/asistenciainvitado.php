<?php
// Incluir el archivo de conexión
require '../conexion.php';

// Verificar si se ha enviado un término de búsqueda
$searchTerm = '';
if (isset($_POST['search'])) {
    $searchTerm = htmlspecialchars($_POST['search']); // Protección contra XSS
}

// Actualizar registro de asistencia
if (isset($_POST['actualizar'])) {
    $id_ = $_POST['id'];
    $nombre_apellido_ = $_POST['nombre_apellido'];
    $fecha_entrada_ = $_POST['fecha_entrada'];
    $fecha_salida_ = $_POST['fecha_salida'];

    // Actualizar datos en la base de datos
    $sql = "UPDATE asistencia_empleado SET nombre_apellido = ?, fecha_entrada = ?, fecha_salida = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt->execute([$nombre_apellido_, $fecha_entrada_, $fecha_salida_, $id_])) {
        echo "<div class='alert alert-success' role='alert'>Registro de asistencia actualizado correctamente</div>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error al actualizar el registro de asistencia</div>";
    }
}

// Eliminar registro de asistencia
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];

    $sql = "DELETE FROM asistencia_invitado WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt->execute([$id])) {
        echo "<div class='alert alert-success' role='alert'>Registro de asistencia eliminado correctamente</div>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error al eliminar el registro de asistencia</div>";
    }
}

// Obtener registros de asistencia (con búsqueda)
$sql = "SELECT id, nombre_apellido, fecha_entrada, fecha_salida FROM asistencia_invitado";
if (!empty ($searchTerm)) {
    $sql .= " WHERE nombre_apellido LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute(["%$searchTerm%"]);//busqueda por nombre apellido de invitado
} else {
    $stmt = $conn->query($sql);
}
$asistencias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Asistencia</title>
    <link rel="icon" href="../img/vista.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center mb-4">Gestión de Asistencia de Invitado</h1>

        <!-- Formulario de búsqueda -->
        <form method="POST" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Buscar por nombre apellido de invitado" value="<?php echo $searchTerm; ?>">
                <button class="btn btn-outline-secondary" type="submit">Buscar</button>
            </div>
        </form>

        <!-- Formulario de actualización -->
        <?php if (isset($_GET['id'])): 
            $id = $_GET['id'];
            $sql = "SELECT nombre_apellido, fecha_entrada, fecha_salida FROM asistencia_invitado WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$id]);
            $asistencia = $stmt->fetch(PDO::FETCH_ASSOC);
        ?>
        <form method="POST" class="mb-4">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="mb-3">
                <label for="nombre_apellido" class="form-label">Nombre y Apellido</label>
                <input type="text" class="form-control" id="nombre_apellido" name="nombre_apellido" value="<?php echo $asistencia['nombre_apellido']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="fecha_entrada" class="form-label">Fecha de Entrada</label>
                <input type="datetime-local" class="form-control" id="fecha_entrada" name="fecha_entrada" value="<?php echo $asistencia['fecha_entrada']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="fecha_salida" class="form-label">Fecha de Salida</label>
                <input type="datetime-local" class="form-control" id="fecha_salida" name="fecha_salida" value="<?php echo $asistencia['fecha_salida']; ?>" required>
            </div>
            <button type="submit" name="actualizar" class="btn btn-primary">Actualizar</button>
        </form>
        <?php endif; ?>

        <!-- Tabla de asistencia -->
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre y Apellido</th>
                    <th>Fecha de Entrada</th>
                    <th>Fecha de Salida</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($asistencias as $row): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['nombre_apellido']; ?></td>
                    <td><?php echo $row['fecha_entrada']; ?></td>
                    <td><?php echo $row['fecha_salida']; ?></td>
                    <td>
                        <a href="actualizacionesasistencia.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil-fill"></i> Editar
                        </a>
                        <a href="actualizacionesasistencia.php?eliminar=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este registro de asistencia?');">
                            <i class="bi bi-trash-fill"></i> Eliminar
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <!-- Contenedor para los botones -->
        <div class="text-center mt-4">
            <!-- Botón para volver -->
            <button class="btn btn-secondary me-2" onclick="history.back()">Volver</button>
            <!-- Botón para actualizar -->
            <button class="btn btn-primary" onclick="location.reload()">Actualizar</button>
        </div>

        <!-- Enlaces de Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </div>
</body>
</html>