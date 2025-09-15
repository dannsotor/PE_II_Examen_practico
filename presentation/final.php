<?php
session_start(); // Iniciar la sesión

require_once '../data/plan.php'; // Asegúrate de incluir la clase que maneja los planes

// Verificar si el plan ha sido creado
if (!isset($_SESSION['idPlan'])) {
    // Redirigir al dashboard si no se ha creado un plan
    header("Location: ../presentation/dashboard.php");
    exit;
}

// Obtener el ID del plan desde la sesión
$idPlan = $_SESSION['idPlan'];

// Crear instancia para obtener los datos actuales del plan
$planData = new PlanData();
$plan = $planData->obtenerPlanPorIdMango($idPlan); // Método para obtener los datos del plan por su ID

// Manejo del formulario para actualizar los campos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $unidadesEstrategicas = $_POST['unidadesestrategicas'];
    $estrategia = $_POST['estrategia'];
    $accionesCompetitivas = $_POST['accionescompetitivas'];
    $conclusiones = $_POST['conclusiones'];

    // Actualizar los campos en la base de datos
    $resultado = $planData->actualizarCamposPlan($idPlan, [
        'unidadesestrategicas' => $unidadesEstrategicas,
        'estrategia' => $estrategia,
        'accionescompetitivas' => $accionesCompetitivas,
        'conclusiones' => $conclusiones
    ]);

    if ($resultado) {
        $mensaje = "Plan actualizado correctamente.";
        // Obtener nuevamente los datos del plan
        $plan = $planData->obtenerPlanPorIdMango($idPlan);
    } else {
        $mensaje = "Error al actualizar el plan.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Por Último</title>
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
            border-radius: 50px; /* Bordes más redondeados */
            transition: background-color 0.3s ease;
        }

        .btn-volver:hover, .btn-siguiente:hover, .btn-guardar:hover {
            background-color: #555; /* Cambia el color al pasar el ratón */
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .button-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
        }

        .footer img {
            max-width: 150px;
            display: block;
            margin: 0 auto 10px auto;
        }

        .footer p {
            margin: 0;
            font-size: 1.2em;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-content">
            <h1>Por último...</h1>
            <?php if (isset($mensaje)): ?>
                <p style="color: green;"><?= htmlspecialchars($mensaje) ?></p>
            <?php endif; ?>

            <form method="POST" action="">
    <div class="form-group">
        <label for="unidadesestrategicas">Unidades Estratégicas:</label>
        <textarea id="unidadesestrategicas" name="unidadesestrategicas" rows="4"><?= htmlspecialchars($plan['unidadesestrategicas'] ?? '') ?></textarea>
    </div>

    <div class="form-group">
        <label for="estrategia">Estrategia:</label>
        <textarea id="estrategia" name="estrategia" rows="4"><?= htmlspecialchars($plan['estrategia'] ?? '') ?></textarea>
    </div>

    <div class="form-group">
        <label for="accionescompetitivas">Acciones Competitivas:</label>
        <textarea id="accionescompetitivas" name="accionescompetitivas" rows="4"><?= htmlspecialchars($plan['accionescompetitivas'] ?? '') ?></textarea>
    </div>

    <div class="form-group">
        <label for="conclusiones">Conclusiones:</label>
        <textarea id="conclusiones" name="conclusiones" rows="4"><?= htmlspecialchars($plan['conclusiones'] ?? '') ?></textarea>
    </div>

    <div class="button-container">
        <button type="submit" class="btn-guardar">Guardar Cambios</button>
        <a href="dashboard.php" class="btn-volver">Volver al Dashboard</a>
    </div>
</form>

            <div class="footer">
                <img src="assets/images/logo.png" alt="StrategicPlan Logo">
                <p>StrategicPlan</p>
            </div>
        </div>
    </div>
</body>
</html>
