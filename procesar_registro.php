<?php
// Incluir la conexión a la base de datos
require 'conexion.php';

// Asegurarse de que la respuesta sea solo JSON
header('Content-Type: application/json');

// Obtener los datos JSON del cuerpo de la solicitud
$input = json_decode(file_get_contents('php://input'), true);

// Validar que se hayan enviado los datos
if (!isset($input['email']) || !isset($input['password'])) {
    echo json_encode(['success' => false, 'message' => 'Por favor, completa todos los campos']);
    exit;
}

$email = $input['email'];
$password = $input['password'];

// Validar que los campos no estén vacíos
if (empty($email) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Por favor, completa todos los campos']);
    exit;
}

try {
    // Verificar si el correo ya está registrado
    $stmt = $conn->prepare("SELECT * FROM usuario WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => false, 'message' => 'Este correo ya está registrado']);
        exit;
    }

    // Insertar el nuevo usuario en la base de datos
    $stmt = $conn->prepare("INSERT INTO usuario (email, password) VALUES (:email, :password)");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', password_hash($password, PASSWORD_BCRYPT));  // Encriptar la contraseña
    $stmt->execute();

    // Devolver una respuesta JSON exitosa
    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    // Devolver el mensaje de error en formato JSON
    echo json_encode(['success' => false, 'message' => 'Error al registrar el usuario: ' . $e->getMessage()]);
}
?>
