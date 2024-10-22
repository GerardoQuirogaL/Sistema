<?php
// Conectar a la base de datos
require 'conexion.php';

// Verificar si se ha escaneado el código QR y se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del QR y la acción
    $qrData = $_POST['qr_data'] ?? null; // Validar si existe el contenido del QR
    $action = $_POST['action'] ?? null;  // Validar si existe la acción (entrada o salida)

    // Verificar que los datos esenciales existan
    if ($qrData && $action && in_array($action, ['entrada', 'salida'])) {
        
        // Intentar descomponer el contenido del QR (si el formato es esperado)
        $qrParts = explode(' - ', $qrData);
        
        // Verificar que el QR tenga el formato correcto (mínimo 1 parte: número de colaborador, proveedor o invitado)
        if (count($qrParts) > 0) {
            $numero_colaborador = $qrParts[0] ?? null;
            $proveedor = $qrParts[1] ?? null;
            $nombre_apellido = $qrParts[2] ?? null;

            // Comenzar una transacción para garantizar que las operaciones se realicen de manera atómica
            $conn->beginTransaction();

            try {
                // --- Registro en tabla asistencia_empleados ---
                if (!empty($numero_colaborador)) {
                    if ($action == 'entrada') {
                        // Buscar si ya existe un registro de entrada sin salida
                        $sqlBuscarEmpleado = "SELECT * FROM asistencia_empleados WHERE numero_colaborador = :numero_colaborador AND fecha_salida IS NULL";
                        $stmtEmpleado = $conn->prepare($sqlBuscarEmpleado);
                        $stmtEmpleado->bindParam(':numero_colaborador', $numero_colaborador);
                        $stmtEmpleado->execute();
                        $registroEmpleado = $stmtEmpleado->fetch();

                        if (!$registroEmpleado) {
                            // Si no existe registro de salida, registrar nueva entrada
                            $sqlEntradaEmpleado = "INSERT INTO asistencia_empleados (numero_colaborador, fecha_entrada) VALUES (:numero_colaborador, :fecha_entrada)";
                            $stmtEntradaEmpleado = $conn->prepare($sqlEntradaEmpleado);
                            $fechaEntrada = date('Y-m-d H:i:s');
                            $stmtEntradaEmpleado->bindParam(':numero_colaborador', $numero_colaborador);
                            $stmtEntradaEmpleado->bindParam(':fecha_entrada', $fechaEntrada);
                            $stmtEntradaEmpleado->execute();
                        }
                    } elseif ($action == 'salida') {
                        // Actualizar la fecha de salida
                        $sqlSalidaEmpleado = "UPDATE asistencia_empleados SET fecha_salida = :fecha_salida WHERE numero_colaborador = :numero_colaborador AND fecha_salida IS NULL";
                        $stmtSalidaEmpleado = $conn->prepare($sqlSalidaEmpleado);
                        $fechaSalida = date('Y-m-d H:i:s');
                        $stmtSalidaEmpleado->bindParam(':fecha_salida', $fechaSalida);
                        $stmtSalidaEmpleado->bindParam(':numero_colaborador', $numero_colaborador);
                        $stmtSalidaEmpleado->execute();
                    }
                }

                // --- Registro en tabla asistencia_proveedores ---
                elseif (!empty($proveedor)) {
                    if ($action == 'entrada') {
                        $sqlBuscarProveedor = "SELECT * FROM asistencia_proveedores WHERE proveedor = :proveedor AND fecha_salida IS NULL";
                        $stmtProveedor = $conn->prepare($sqlBuscarProveedor);
                        $stmtProveedor->bindParam(':proveedor', $proveedor);
                        $stmtProveedor->execute();
                        $registroProveedor = $stmtProveedor->fetch();

                        if (!$registroProveedor) {
                            $sqlEntradaProveedor = "INSERT INTO asistencia_proveedores (proveedor, fecha_entrada) VALUES (:proveedor, :fecha_entrada)";
                            $stmtEntradaProveedor = $conn->prepare($sqlEntradaProveedor);
                            $fechaEntrada = date('Y-m-d H:i:s');
                            $stmtEntradaProveedor->bindParam(':proveedor', $proveedor);
                            $stmtEntradaProveedor->bindParam(':fecha_entrada', $fechaEntrada);
                            $stmtEntradaProveedor->execute();
                        }
                    } elseif ($action == 'salida') {
                        $sqlSalidaProveedor = "UPDATE asistencia_proveedores SET fecha_salida = :fecha_salida WHERE proveedor = :proveedor AND fecha_salida IS NULL";
                        $stmtSalidaProveedor = $conn->prepare($sqlSalidaProveedor);
                        $fechaSalida = date('Y-m-d H:i:s');
                        $stmtSalidaProveedor->bindParam(':fecha_salida', $fechaSalida);
                        $stmtSalidaProveedor->bindParam(':proveedor', $proveedor);
                        $stmtSalidaProveedor->execute();
                    }
                }

                // --- Registro en tabla asistencia_invitados ---
                elseif (!empty($nombre_apellido)) {
                    if ($action == 'entrada') {
                        $sqlBuscarInvitado = "SELECT * FROM asistencia_invitados WHERE nombre_apellido = :nombre_apellido AND fecha_salida IS NULL";
                        $stmtInvitado = $conn->prepare($sqlBuscarInvitado);
                        $stmtInvitado->bindParam(':nombre_apellido', $nombre_apellido);
                        $stmtInvitado->execute();
                        $registroInvitado = $stmtInvitado->fetch();

                        if (!$registroInvitado) {
                            $sqlEntradaInvitado = "INSERT INTO asistencia_invitados (nombre_apellido, fecha_entrada) VALUES (:nombre_apellido, :fecha_entrada)";
                            $stmtEntradaInvitado = $conn->prepare($sqlEntradaInvitado);
                            $fechaEntrada = date('Y-m-d H:i:s');
                            $stmtEntradaInvitado->bindParam(':nombre_apellido', $nombre_apellido);
                            $stmtEntradaInvitado->bindParam(':fecha_entrada', $fechaEntrada);
                            $stmtEntradaInvitado->execute();
                        }
                    } elseif ($action == 'salida') {
                        $sqlSalidaInvitado = "UPDATE asistencia_invitados SET fecha_salida = :fecha_salida WHERE nombre_apellido = :nombre_apellido AND fecha_salida IS NULL";
                        $stmtSalidaInvitado = $conn->prepare($sqlSalidaInvitado);
                        $fechaSalida = date('Y-m-d H:i:s');
                        $stmtSalidaInvitado->bindParam(':fecha_salida', $fechaSalida);
                        $stmtSalidaInvitado->bindParam(':nombre_apellido', $nombre_apellido);
                        $stmtSalidaInvitado->execute();
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