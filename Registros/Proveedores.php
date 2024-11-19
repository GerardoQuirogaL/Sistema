<?php
require '../conexion.php';
require '../phpqrcode/qrlib.php';
require '../Email/Exception.php';
require '../Email/PHPMailer.php';
require '../Email/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json'); // Configuración para respuesta JSON

$response = [
    "status" => "error",
    "message" => "Ocurrió un error desconocido."
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $proveedor = $_POST['proveedor'];
    $placas = $_POST['placas'];
    $modeloMarca = $_POST['modelo_marca'];
    $color = $_POST['color'];
    $duracion = intval($_POST['duracion']); // Capturamos la duración seleccionada en el formulario (en días)
    $email = $_POST['email']; // Correo electrónico al que se enviará el QR

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
    if ($placa_existe_en_empleados || $placa_existe_en_invitados || $placa_existe_en_proveedores) {
        $response['message'] = "Error: Ya existe un registro con esas placas en empleados, invitados o proveedores.";
        echo json_encode($response);
        exit;
    }

    // Generar fecha de vencimiento para el QR (1, 3 o 5 días a partir de la fecha actual)
    $fechaActual = new DateTime("now", new DateTimeZone("America/Cancun"));
    $fechaExpiracion = $fechaActual->modify("+$duracion days")->format('Y-m-d H:i:s');

    // Generar el contenido del código QR
    $contenidoQR = "proveedor $proveedor \n$nombre \n$placas \n$modeloMarca ($color) \nVence el: $fechaExpiracion";
    $filename = "../img_qr/qr_" . $proveedor . ".png";

    QRcode::png($contenidoQR, $filename, QR_ECLEVEL_L, 4);

    // Preparar la respuesta con la URL del código QR para mostrar y descargar
    $response = [
        "status" => "success",
        "message" => "Proveedor registrado con éxito.",
        "qr_code_url" => "img_qr/qr_" . $proveedor . ".png"
    ];

    // Insertar en la base de datos
    $sql = "INSERT INTO proveedores (nombre_apellido, proveedor, placas_vehiculos, modelo_marca, color_vehiculo, qr_code, fecha_expiracion) 
            VALUES (:nombre, :proveedor, :placas, :modeloMarca, :color, :qr_code, :fechaExpiracion)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':proveedor', $proveedor);
    $stmt->bindParam(':placas', $placas);
    $stmt->bindParam(':modeloMarca', $modeloMarca);
    $stmt->bindParam(':color', $color);
    $stmt->bindParam(':qr_code', $filename);
    $stmt->bindParam(':fechaExpiracion', $fechaExpiracion); // Nueva columna para almacenar la fecha de expiración

    if ($stmt->execute()) {
        // Enviar el correo con el QR adjunto
        $mail = new PHPMailer(true);
        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'isc20350250@gmail.com';  // Tu correo
            $mail->Password   = 'bwfzbxzrscmwgzmx';             // Contraseña de aplicación
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Remitente y destinatario
            $mail->setFrom('isc20350250@gmail.com', 'Yuliana del Carmen Altamirano Montes');
            $mail->addAddress($email, $proveedor);

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Tu Codigo QR de Acceso';
            $mail->Body    = "Estimado/a $proveedor,<br><br>A continuación, encontrarás tu código QR para acceder a las instalaciones.";
            $mail->AltBody = "Estimado/a $proveedor, adjunto encontrarás tu código QR para acceder a las instalaciones.";

            // Adjuntar el código QR
            $mail->addAttachment($filename,'CodigoQR.png');

            // Enviar el correo
            $mail->send();

            $response["status"] = "success";
            $response["message"] = "Proveedor registrado con éxito. El QR ha sido enviado por correo.";
            $response["qr_code_url"] = "img_qr/qr_" . $proveedor . ".png";
        } catch (Exception $e) {
            $response['message'] = 'No se pudo enviar el correo. Error: ' . $mail->ErrorInfo;
        }
    } else {
        $response['message'] = "Error al registrar: " . implode(" - ", $stmt->errorInfo());
    }
}

echo json_encode($response);
?>