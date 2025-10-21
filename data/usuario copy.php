<?php
require_once __DIR__ . '/../bootstrap.php'; 
require_once __DIR__ . '/neonConnection.php';


function verificarUsuario($username) {
    $conexion = new Conexion();
    $conn = $conexion->getConnection();

    $sql = "SELECT idusuario, password FROM usuario WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);

    if ($stmt->execute()) {
        return $stmt->fetch(PDO::FETCH_ASSOC); // Devuelve el usuario o false
    } else {
        return false; // Error en la consulta
    }
}

// Función para registrar un nuevo usuario
function registrarUsuario($username, $password) {
    $conexion = new Conexion();
    $conn = $conexion->getConnection();

    // Hash de la contraseña
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Preparar la consulta SQL
    $sql = "INSERT INTO usuario (username, password) VALUES (:username, :password)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);

    // Ejecutar la consulta y verificar
    if ($stmt->execute()) {
        return true; // Registro exitoso
    } else {
        return false; // Error en el registro
    }
}
?>
