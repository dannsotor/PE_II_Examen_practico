<?php
session_start();

// Verificar que se ha enviado la ID del plann
if (isset($_POST['idPlan'])) {
    // Almacenar la ID del plan en la sesión
    $_SESSION['idPlan'] = $_POST['idPlan'];
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error']);
}
?>