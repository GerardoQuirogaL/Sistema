<?php
ini_set('display_errors', 1);
error_reporting(E_ALL); // Para la depuración

require 'conexion.php';
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['email']) || !isset($input['password'])) {
    echo json_encode(['success' => false, 'message' => 'Por favor, completa todos los campos']);
    exit;
}

$email = $input['email'];
$password = $input['password'];

if (empty($email) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Por favor, completa todos los campos']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Correo electrónico inválido']);
    exit;
}

try {
    $stmt = $conn->prepare("SELECT * FROM usuario WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => false, 'message' => 'Este correo ya está registrado']);
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT); // Primero almacena el hash en una variable
$stmt = $conn->prepare("INSERT INTO usuario (email, password ) VALUES (:email, :password )"); 
$stmt->bindParam(':email', $email);
$stmt->bindParam(':password', $hashedPassword); // Luego pasa la variable

    
    if (!$stmt->execute()) {
        echo json_encode(['success' => false, 'message' => 'Error al registrar el usuario: ' . implode(" ", $stmt->errorInfo())]);
        exit;
    }

    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error al registrar el usuario: ' . $e->getMessage()]);
}
?>
