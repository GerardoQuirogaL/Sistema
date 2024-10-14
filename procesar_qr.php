<?php
// Conectar a la base de datos
require 'conexion.php';

// Verificar si se ha escaneado el código QR
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del QR
    $qrData = $_POST['qr_code']; // Este sería el contenido del QR escaneado

    // Descomponer el contenido del QR (según el formato en el que hayas generado el QR)
    list($numero_colaborador, $proveedor, $nombre_apellido) = explode(' - ', $qrData);

    // Buscar si ya existe un registro de entrada sin salida
    $sqlBuscar = "SELECT * FROM asistencia WHERE numero_colaborador = :numero_colaborador AND fecha_salida IS NULL";
    $stmt = $conn->prepare($sqlBuscar);
    $stmt->bindParam(':numero_colaborador', $numero_colaborador);
    $stmt->execute();

    $registro = $stmt->fetch();

    if ($registro) {
        // Si existe un registro sin fecha de salida, actualizar con la fecha de salida
        $sqlSalida = "UPDATE asistencia SET fecha_salida = :fecha_salida WHERE id = :id";
        $stmtSalida = $conn->prepare($sqlSalida);
        $fechaSalida = date('Y-m-d H:i:s');
        $stmtSalida->bindParam(':fecha_salida', $fechaSalida);
        $stmtSalida->bindParam(':id', $registro['id']);

        if ($stmtSalida->execute()) {
            echo "<div class='alert alert-success text-center'>Salida registrada correctamente para el colaborador $numero_colaborador.</div>";
        } else {
            echo "<div class='alert alert-danger text-center'>Error al registrar la salida.</div>";
        }
    } else {
        // Si no existe, registrar como una nueva entrada
        $sqlEntrada = "INSERT INTO asistencia (numero_colaborador, proveedor, nombre_apellido, fecha_entrada) 
                        VALUES (:numero_colaborador, :proveedor, :nombre_apellido, :fecha_entrada)";
        $stmtEntrada = $conn->prepare($sqlEntrada);
        $fechaEntrada = date('Y-m-d H:i:s');
        $stmtEntrada->bindParam(':numero_colaborador', $numero_colaborador);
        $stmtEntrada->bindParam(':proveedor', $proveedor);
        $stmtEntrada->bindParam(':nombre_apellido', $nombre_apellido);
        $stmtEntrada->bindParam(':fecha_entrada', $fechaEntrada);

        if ($stmtEntrada->execute()) {
            echo "<div class='alert alert-success text-center'>Entrada registrada correctamente para el colaborador $numero_colaborador.</div>";
        } else {
            echo "<div class='alert alert-danger text-center'>Error al registrar la entrada.</div>";
        }
    }
}
?>
