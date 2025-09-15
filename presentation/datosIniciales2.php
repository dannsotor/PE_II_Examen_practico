<?php
// Iniciar sesión
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['idusuario']) || !isset($_SESSION['idPlan'])) {
    // Redirigir al usuario a la página de inicio de sesión
    header("Location: login.php");
    exit();
}

include_once '../data/plan.php';

// Obtener el idusuario de la sesión
$idusuario = $_SESSION['idusuario'];

// Obtener la id del plan de la sesión
$idPlan = $_SESSION['idPlan'];

// Crear una instancia de PlanData
$planData = new PlanData();

// Obtener el plan utilizando ambos IDs
$plan = $planData->obtenerPlanPorId($idPlan, $idusuario);

// Manejo de la actualización del plan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardarPlan'])) {
    $nombreEmpresa = $_POST['nombreEmpresa'] ?? '';
    $fecha = $_POST['fecha'] ?? '';
    $promotores = $_POST['promotores'] ?? '';
    
    // Manejar la carga del logo
    $logo = null;
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == UPLOAD_ERR_OK) {
        $targetDir = "../uploads/"; // Cambia esto a tu directorio de destino
        $logo = $targetDir . basename($_FILES["logo"]["name"]);
        move_uploaded_file($_FILES["logo"]["tmp_name"], $logo);
    }

    // Actualizar el plan en la base de datos
    $resultado = $planData->actualizarPlan($idPlan, $nombreEmpresa, $fecha, $promotores, $logo);

    if ($resultado) {
        echo "<script>alert('Plan actualizado exitosamente.');</script>";
        $plan = $planData->obtenerPlanPorId($idPlan, $idusuario); // Volver a obtener el plan actualizado
    } else {
        echo "<script>alert('Error al actualizar el plan.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos Iniciales</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        .btn-volver, .btn-siguiente, .btn-guardar {
            background-color: gray;
            color: white;
            border: none;
            padding: 10px 20px;
            text-decoration: none;
            cursor: pointer;
            margin-top: 10px;
            border-radius: 25px;
            transition: background-color 0.3s ease;
        }
        .btn-volver:hover, .btn-siguiente:hover, .btn-guardar:hover {
            background-color: #555; /* Cambia el color al pasar el ratón */
        }
        .btn-siguiente {
            background-color: #333; /* Color más oscuro para el botón "Siguiente" */
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-content">
            <h1>Datos iniciales</h1>
            <form action="" method="POST" enctype="multipart/form-data"> <!-- Cambiado a "" para enviar a la misma página -->
                <input type="hidden" name="idPlan" value="<?php echo htmlspecialchars($idPlan); ?>">

                <label for="nombreEmpresa">Nombre de la Empresa:</label>
                <input type="text" id="nombreEmpresa" name="nombreEmpresa" value="<?php echo htmlspecialchars($plan['nombreempresa']); ?>" required>

                <label for="fecha">Fecha de Elaboración:</label>
                <input type="date" id="fecha" name="fecha" value="<?php echo htmlspecialchars($plan['fecha']); ?>" required>

                <label for="promotores">Emprendedores / Promotores:</label>
                <input type="text" id="promotores" name="promotores" value="<?php echo htmlspecialchars($plan['promotores']); ?>" required>

                <label for="logo">Subir Logo:</label>
                <input type="file" id="logo" name="logo" accept="image/*">

                <input type="submit" name="guardarPlan" value="Guardar" class="btn-guardar">
            </form>
            <br>
            <div style="display: flex; justify-content: space-between; margin-top: 10px;">
                <a href="dashboard.php" class="btn-volver">Volver al Dashboard</a>
                <a href="mision.php" class="btn-siguiente">Siguiente</a>
            </div>
        </div>

        <div class="info-content">
            <?php include('aside.php'); ?>
        </div>
    </div>
</body>
</html>
