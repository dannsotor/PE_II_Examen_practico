<?php
session_start(); // Iniciar la sesión
require_once __DIR__ . '/../bootstrap.php'; 

require_once '../data/usuario.php'; // Archivo que contiene la consulta

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validar que los campos no estén vacíos
    if (empty($username) || empty($password)) {
        echo "El nombre de usuario o la contraseña no pueden estar vacíos.";
        exit;
    }

    // Llamar a la función que verifica el login en el archivo de consultas
    $user = verificarUsuario($username);

    // Si se encuentra un usuario, proceder a verificar la contraseña
    if ($user) {
        // Verificar la contraseña con el hash almacenado
        if (password_verify($password, $user['password'])) {
            // Contraseña correcta, iniciar sesión
            $_SESSION['idusuario'] = $user['idusuario'];
            $_SESSION['username'] = $username;

            // Redirigir a la página de inicio (dashboard)
            header("Location: ../presentation/dashboard.php");
            exit;
        } else {
            echo "Nombre de usuario o contraseña incorrectos.";
        }
    } else {
        echo "Nombre de usuario o contraseña incorrectos.";
    }
}
?>
