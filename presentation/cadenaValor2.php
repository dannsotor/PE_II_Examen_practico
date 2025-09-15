<?php

require_once('../data/plan.php'); // Asegúrate de que esto apunte al archivo correcto donde tienes la clase PlanData

session_start();
if (!isset($_SESSION['idPlan'])) {
    die("ID de plan no encontrado en la sesión.");
}

$idPlan = $_SESSION['idPlan'];

// Instancia de la clase que maneja los planes
$planData = new PlanData();

// Obtener el autovalor guardado en la base de datos
$autovalorGuardado = $planData->obtenerAutovalorPorId($idPlan);

// Obtener las reflexiones guardadas en la base de datos
$reflexionesGuardadas = $planData->obtenerReflexionesPorId($idPlan);

// Obtener las fortalezas guardadas
$fortalezasGuardadas = $planData->obtenerFortalezasPorId($idPlan);

// Obtener las debilidades guardadas
$debilidadesGuardadas = $planData->obtenerDebilidadesPorId($idPlan);

// Convertimos el autovalor de cadena a un array
$autovalores = $autovalorGuardado ? explode(",", $autovalorGuardado) : array_fill(0, 25, 0); // Si no hay valor guardado, usamos 0 por defecto

// Mostrar el potencial de mejora si está disponible
$potencialMejora = isset($_SESSION['potencialMejora']) ? $_SESSION['potencialMejora'] : null;

// Array de preguntas obtenidas de la imagen
$preguntas = [
    "La empresa tiene una política sistematizada de cero defectos en la producción de productos/servicios.",
    "La empresa emplea los medios productivos tecnológicamente más avanzados de su sector.",
    "La empresa dispone de un sistema de información y control de gestión eficiente y eficaz.",
    "Los medios técnicos y tecnológicos de la empresa están preparados para competir en un futuro a corto, medio y largo plazo.",
    "La empresa es un referente en su sector en I+D+i.",
    "La excelencia de los procedimientos de la empresa (en ISO, etc.) es una principal fuente de ventaja competitiva.",
    "La empresa dispone de página web, y esta se emplea no sólo como escaparate virtual de productos/servicios, sino también para establecer relaciones con clientes y proveedores.",
    "Los productos/servicios que desarrolla nuestra empresa llevan incorporada una tecnología difícil de imitar.",
    "La empresa es referente en su sector en la optimización, en términos de coste, de su cadena de producción, siendo ésta una de sus principales ventajas competitivas.",
    "La informatización de la empresa es una fuente de ventaja competitiva clara respecto a sus competidores.",
    "Los canales de distribución de la empresa son una importante fuente de ventajas competitivas.",
    "Los productos/servicios de la empresa son altamente, y diferencialmente, valorados por el cliente respecto a nuestros competidores.",
    "La empresa dispone y ejecuta un sistemático plan de marketing y ventas.",
    "La empresa tiene optimizada su gestión financiera.",
    "La empresa busca continuamente el mejorar la relación con sus clientes cortando los plazos de ejecución, personalizando la oferta o mejorando las condiciones de entrega. Pero siempre partiendo de un plan previo",
    "La empresa es referente en su sector en el lanzamiento de innovadores productos y servicios de éxito demostrado en el mercado.",
    "Los Recursos Humanos son especialmente responsables del éxito de la empresa, considerándolos incluso como el principal activo estratégico.",
    "Se tiene una plantilla altamente motivada, que conoce con claridad las metas, objetivos y estrategias de la organización.",
    "La empresa siempre trabaja conforme a una estrategia y objetivos claros.",
    "La gestión del circulante está optimizada.",
    "Se tiene definido claramente el posicionamiento estratégico de todos los productos de la empresa.",
    "Se dispone de una política de marca basada en la reputación que la empresa genera, en la gestión de relación con el cliente y en el posicionamiento estratégico previamente definido.",
    "La cartera de clientes de nuestra empresa está altamente fidelizada, ya que tenemos como principal propósito el deleitarlos día a día.",
    "Nuestra política y equipo de ventas y marketing es una importante ventaja competitiva de nuestra empresa respecto al sector.",
    "El servicio al cliente que prestamos es uno de nuestras principales ventajas competitivas respecto a nuestros competidores."
];

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autodiagnóstico de la Cadena de Valor</title>
    <style>
        .btn-volver, .btn-siguiente {
            background-color: gray;
            color: white;
            border: none;
            padding: 10px 20px;
            text-decoration: none;
            cursor: pointer;
            border-radius: 25px;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }

        .btn-volver:hover, .btn-siguiente:hover {
            background-color: #555;
        }

        .btn-siguiente {
            background-color: #333;
        }

        .button-save {
            background-color: #ff4d4d; /* Color rojo como en la imagen */
            color: white;
            border: none;
            padding: 10px 20px; /* Más delgado que antes */
            text-align: center;
            font-size: 16px;
            cursor: pointer;
            border-radius: 8px; /* Bordes ligeramente redondeados */
            transition: background-color 0.3s ease;
            margin-top: 10px;
         
        }

        .button-save:hover {
            background-color: #d43f3f; /* Color rojo más oscuro en hover */
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: white;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            width: 100%;
            max-width: 800px;
            max-height: 80vh;
            overflow-y: auto;
            margin: 20px auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .center {
            text-align: center;
            margin-top: 20px;
        }
        .button {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 5px;
        }
        .result {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
        }
        .textarea-title {
            font-size: 16px;
            margin-top: 20px;
            font-weight: bold;
            color: #333;
        }
        .reflexion-textarea, .fortalezas-textarea, .debilidades-textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2 class="center">Autodiagnóstico de la Cadena de Valor</h2>

        

        <form method="POST" action="../business/autodiagnostico.php">
            <table>
                <thead>
                    <tr>
                        <th>Autodiagnóstico de la Cadena de Valor Interna</th>
                        <th>En total en desacuerdo</th>
                        <th>No está de acuerdo</th>
                        <th>Está de acuerdo</th>
                        <th>Está bastante de acuerdo</th>
                        <th>En total acuerdo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    for ($i = 0; $i < 25; $i++) {
                        // Obtener el valor seleccionado de autovalores
                        $valorSeleccionado = isset($autovalores[$i]) ? $autovalores[$i] : 0;
                        $pregunta = $preguntas[$i]; // Obtener la pregunta correspondiente

                        echo "<tr>
                            <td>$pregunta</td>
                            <td><input type='radio' name='punto_" . ($i + 1) . "' value='0' " . ($valorSeleccionado == 0 ? 'checked' : '') . "></td>
                            <td><input type='radio' name='punto_" . ($i + 1) . "' value='1' " . ($valorSeleccionado == 1 ? 'checked' : '') . "></td>
                            <td><input type='radio' name='punto_" . ($i + 1) . "' value='2' " . ($valorSeleccionado == 2 ? 'checked' : '') . "></td>
                            <td><input type='radio' name='punto_" . ($i + 1) . "' value='3' " . ($valorSeleccionado == 3 ? 'checked' : '') . "></td>
                            <td><input type='radio' name='punto_" . ($i + 1) . "' value='4' " . ($valorSeleccionado == 4 ? 'checked' : '') . "></td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>

            <!-- Mostrar el potencial de mejora si está disponible -->
            <?php if ($potencialMejora !== null): ?>
                <div class="result center">
                    <strong>POTENCIAL DE MEJORA DE LA CADENA DE VALOR INTERNA: <?php echo $potencialMejora; ?>%</strong>
                </div>
                <?php unset($_SESSION['potencialMejora']); // Limpiar el valor de la sesión ?>
            <?php endif; ?>
        
            <!-- Título y Cuadro de texto para reflexiones -->
            <div class="center">
                <label class="textarea-title">Reflexiones</label>
                <textarea class="reflexion-textarea" name="reflexion" placeholder="Escribe tus reflexiones sobre el autodiagnóstico..."><?php echo isset($reflexionesGuardadas) ? htmlspecialchars($reflexionesGuardadas) : ''; ?></textarea>
            </div>

            <!-- Título y Cuadro de texto para fortalezas -->
            <div class="center">
                <label class="textarea-title">Fortalezas</label>
                <textarea class="fortalezas-textarea" name="fortalezas" placeholder="Escribe las fortalezas..."><?php echo isset($fortalezasGuardadas) ? htmlspecialchars($fortalezasGuardadas) : ''; ?></textarea>
            </div>

            <!-- Título y Cuadro de texto para debilidades -->
            <div class="center">
                <label class="textarea-title">Debilidades</label>
                <textarea class="debilidades-textarea" name="debilidades" placeholder="Escribe las debilidades..."><?php echo isset($debilidadesGuardadas) ? htmlspecialchars($debilidadesGuardadas) : ''; ?></textarea>
            </div>

            <!-- Botón para guardar la autoevaluación -->
            <div class="center">
                <button type="submit" name="guardarAutoevaluacion" class="button-save">Realizar Autoevaluación</button>
                <button type="submit" name="guardarReflexion" class="button-save">Guardar Datos</button>
            </div>
        </form>

            <div class="center" style="display: flex; justify-content: space-between; margin-top: 20px;">
            <a href="dashboard.php" class="btn-volver">Volver al Dashboard</a>
            <a href="matriz1.php" class="btn-siguiente">Siguiente</a>
        </div>
    </div>

</body>
</html>
