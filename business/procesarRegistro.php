<?php
session_start(); // Iniciar la sesión
require_once '../data/usuario.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Verificar que las contraseñas coinciden
    if ($password !== $confirmPassword) {
        echo "Las contraseñas no coinciden.";
        exit;
    }

    // Verificar si el usuario ya existe
    if (verificarUsuario($username)) {
        echo "El nombre de usuario ya está en uso.";
        exit;
    }

    // Registrar el nuevo usuario
    if (registrarUsuario($username, $password)) {
        // Obtener el id del usuario recién registrado para la sesión
        $user = verificarUsuario($username);

        // Verificar si se obtuvo el usuario
        if ($user) {
            // Iniciar la sesión automáticamente
            $_SESSION['idusuario'] = $user['idusuario'];
            $_SESSION['username'] = $username;

            // Redirigir al dashboard
            header("Location: ../presentation/dashboard.php");
            exit;
        } else {
            echo "Error al obtener información del usuario.";
            exit;
        }
    } else {
        echo "Error en el registro. Intenta de nuevo.";
    }
}
?>
