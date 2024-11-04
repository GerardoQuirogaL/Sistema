<?php
// Conectar a la base de datos
require 'conexion.php';

// Definir la zona horaria de Cancún
date_default_timezone_set('America/Cancun');
$fecha = date('Y-m-d H:i:s');

// Verificar si se ha escaneado el código QR y se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del QR y la acción
    $qrData = $_POST['qr_data'] ?? null; // Validar si existe el contenido del QR
    $action = $_POST['action'] ?? null;  // Validar si existe la acción (entrada o salida)

    // Verificar que los datos esenciales existan
    if ($qrData && $action && in_array($action, ['entrada', 'salida'])) {
        
        // Intentar descomponer el contenido del QR (si el formato es esperado)
        $qrParts = explode(' ', $qrData);
        
        // Verificar que el QR tenga el formato correcto (mínimo 1 parte: número de colaborador, proveedor o invitado)
        if (count($qrParts) > 1) {
            $tipo = trim($qrParts[0]); // Tipo de usuario
            $identificador = trim($qrParts[1]); // Número de colaborador, proveedor o nombre

            // Comenzar una transacción para garantizar que las operaciones se realicen de manera atómica
            $conn->beginTransaction();

            try {
                // --- Registro en tabla asistencia_empleados ---
                if ($tipo == 'empleado') {
                    // Buscar si ya existe un registro de entrada sin salida
                    if ($action == 'entrada') {
                        $sqlBuscar = "SELECT * FROM asistencia_empleado WHERE numero_colaborador = :identificador AND fecha_salida IS NULL";
                        $stmtBuscar = $conn->prepare($sqlBuscar);
                        $stmtBuscar->bindParam(':identificador', $identificador);
                        $stmtBuscar->execute();

                        // Si no existe registro de salida, registrar nueva entrada
                        if (!$stmtBuscar->fetch()) {
                            $sqlInsert = "INSERT INTO asistencia_empleado (numero_colaborador, fecha_entrada) VALUES (:identificador, :fecha)";
                            $stmtInsert = $conn->prepare($sqlInsert);
                            $stmtInsert->bindParam(':identificador', $identificador);
                            $stmtInsert->bindParam(':fecha', $fecha);
                            $stmtInsert->execute();
                        }
                        // Actualizar la fecha de salida
                    } elseif ($action == 'salida') {
                        $sqlSalida = "UPDATE asistencia_empleado SET fecha_salida = :fecha WHERE numero_colaborador = :identificador AND fecha_salida IS NULL";
                        $stmtSalida = $conn->prepare($sqlSalida);
                        $stmtSalida->bindParam(':fecha', $fecha);
                        $stmtSalida->bindParam(':identificador', $identificador);
                        $stmtSalida->execute();
                    }
                }

                // --- Registro en tabla asistencia_proveedores ---
                elseif ($tipo == 'proveedor') {
                    if ($action == 'entrada') {
                        $sqlBuscar = "SELECT * FROM asistencia_proveedor WHERE proveedor = :identificador AND fecha_salida IS NULL";
                        $stmtBuscar = $conn->prepare($sqlBuscar);
                        $stmtBuscar->bindParam(':identificador', $identificador);
                        $stmtBuscar->execute();

                        if (!$stmtBuscar->fetch()) {
                            $sqlInsert = "INSERT INTO asistencia_proveedor (proveedor, fecha_entrada) VALUES (:identificador, :fecha)";
                            $stmtInsert = $conn->prepare($sqlInsert);
                            $stmtInsert->bindParam(':identificador', $identificador);
                            $stmtInsert->bindParam(':fecha', $fecha);
                            $stmtInsert->execute();
                        }
                    } elseif ($action == 'salida') {
                        $sqlSalida = "UPDATE asistencia_proveedor SET fecha_salida = :fecha WHERE proveedor = :identificador AND fecha_salida IS NULL";
                        $stmtSalida = $conn->prepare($sqlSalida);
                        $stmtSalida->bindParam(':fecha', $fecha);
                        $stmtSalida->bindParam(':identificador', $identificador);
                        $stmtSalida->execute();
                    }
                }

                // --- Registro en tabla asistencia_invitados ---
                elseif ($tipo == 'invitado') {
                    if ($action == 'entrada') {
                        $sqlBuscar = "SELECT * FROM asistencia_invitado WHERE nombre_apellido = :identificador AND fecha_salida IS NULL";
                        $stmtBuscar = $conn->prepare($sqlBuscar);
                        $stmtBuscar->bindParam(':identificador', $identificador);
                        $stmtBuscar->execute();

                        if (!$stmtBuscar->fetch()) {
                            $sqlInsert = "INSERT INTO asistencia_invitado (nombre_apellido, fecha_entrada) VALUES (:identificador, :fecha)";
                            $stmtInsert = $conn->prepare($sqlInsert);
                            $stmtInsert->bindParam(':identificador', $identificador);
                            $stmtInsert->bindParam(':fecha', $fecha);
                            $stmtInsert->execute();
                        }
                    } elseif ($action == 'salida') {
                        $sqlSalida = "UPDATE asistencia_invitado SET fecha_salida = :fecha WHERE nombre_apellido = :identificador AND fecha_salida IS NULL";
                        $stmtSalida = $conn->prepare($sqlSalida);
                        $stmtSalida->bindParam(':fecha', $fecha);
                        $stmtSalida->bindParam(':identificador', $identificador);
                        $stmtSalida->execute();
                    }
                }

                // Si todas las operaciones fueron exitosas, confirmar la transacción
                $conn->commit();

                // Mensaje de éxito
                echo "<div class='alert alert-success text-center'>Registro actualizado correctamente.</div>";

            } catch (Exception $e) {
                // En caso de error, deshacer la transacción
                $conn->rollBack();
                echo "<div class='alert alert-danger text-center'>Ocurrió un error: " . $e->getMessage() . "</div>";
            }
        } else {
            echo "<div class='alert alert-danger text-center'>El formato del QR no es válido.</div>";
        }
    } else {
        echo "<div class='alert alert-danger text-center'>Datos incompletos.</div>";
    }
}
?>