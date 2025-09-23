<?php
session_start();
require_once __DIR__ . '/../bootstrap.php'; 
require_once '../data/usuario.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        header("Location: ../presentation/login.php?error=empty");
        exit;
    }

    $user = verificarUsuario($username);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['idusuario'] = $user['idusuario'];
        $_SESSION['username'] = $username;
        header("Location: ../presentation/dashboard.php");
        exit;
    } else {
        header("Location: ../presentation/login.php?error=invalid");
        exit;
    }
}
?>
