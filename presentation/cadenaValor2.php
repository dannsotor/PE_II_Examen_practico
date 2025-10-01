<?php

require_once('../data/plan.php'); // Asegúrate de que esto apunte al archivo correcto donde tienes la clase PlanData

session_start();
if (!isset($_SESSION['idPlan'])) {
    die("ID de plan no encontrado en la sesión.");
}

$idPlan = $_SESSION['idPlan'];

// Instancia de la clase que maneja los planes
$planData = new PlanData();
// Variables de mensajes
$mensaje = '';
$tipoMensaje = ''; // 'success' o 'error'

// Ejemplo: podrías setear el mensaje después de una acción
if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje']['texto'];
    $tipoMensaje = $_SESSION['mensaje']['tipo']; // success o error
    unset($_SESSION['mensaje']); // limpiar después de mostrarlo
}
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
              <script src="https://cdn.tailwindcss.com"></script>

  <style>
    body {
      background: #f8fafc;
      color: #1e293b;
      font-family: 'Segoe UI', Tahoma, sans-serif;
      margin: 0;
      padding-bottom: 80px;
      transition: background-color .3s ease, color .3s ease;
    }
    h1 {
      color: #4338ca; /* Indigo */
      text-align: center;
      font-weight: 700;
    }

    .form-content {
      max-width: 800px;
      margin: 2rem auto;
      background-color: #ffffff;
      padding: 2rem;
      border-radius: 12px;
      border: 1px solid #d1d5db;
      box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    }

    textarea {
      width: 100%;
      padding: 16px !important;
      border: 1px solid #cbd5e1;
      background-color: #ffffff;
      resize: vertical;
      font-family: 'Segoe UI', sans-serif;
      font-size: 15px;
      border-radius: 8px;
      transition: border-color .2s ease, box-shadow .2s ease;
    }
    textarea:focus {
      border-color: #4338ca;
      outline: none;
      box-shadow: 0 0 0 2px #c7d2fe;
    }

    .btn-volver, .btn-siguiente, .btn-guardar {
      padding: 10px 22px;
      border-radius: 8px;
      border: none;
      cursor: pointer;
      font-weight: 600;
      transition: background-color .3s ease, transform .2s ease;
    }
    .btn-guardar {
      background-color: #14b8a6; /* Teal */
      color: #fff;
    }
    .btn-guardar:hover {
      background-color: #0d9488;
      transform: translateY(-2px);
    }
    .btn-volver {
      background-color: #4338ca; /* Indigo */
      color: #fff;
    }
    .btn-volver:hover {
      background-color: #3730a3;
      transform: translateY(-2px);
    }
    .btn-siguiente {
      background-color: #facc15; /* Amarillo cálido */
      color: #1e293b;
    }
    .btn-siguiente:hover {
      background-color: #eab308;
      transform: translateY(-2px);
    }

    .button-container {
      display: flex;
      justify-content: space-between;
      margin-top: 18px;
    }
    .button-container {
  display: flex;
  justify-content: space-between;
  margin-top: 15px;
  gap: 10px;
}

/* Botones generales */
.button-container a {
  flex: 1;
  text-align: center;
  padding: 12px 20px;
  font-weight: 500;
  border-radius: 8px;
  border: 1px solid #4338ca;
  color: #fff;
  background-color: #4338ca; /* Indigo */
  text-decoration: none;
  box-shadow: 0 2px 6px rgba(0,0,0,0.25);
  transition: all 0.3s ease;
}

/* Hover efecto suave */
.button-container a:hover {
  background-color: #3730a3; /* Indigo más oscuro */
  box-shadow: 0 4px 10px rgba(0,0,0,0.35);
  transform: translateY(-2px);
}

/* Ajustes específicos en modo oscuro */
@media (prefers-color-scheme: dark) {
  .button-container a {
    background-color: #3730a3; /* Indigo oscuro por defecto */
    border-color: #4f46e5;
    color: #e0e7ff;
  }
  .button-container a:hover {
    background-color: #4f46e5; /* Indigo más brillante */
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(79,70,229,0.6);
  }
}

    /* Barra inferior tipo pestañas */
    .info-content {
      position: fixed;
      bottom: 0; left: 0; right: 0;
      display: flex;
      justify-content: center;
      padding: 0;
      overflow-x: auto;
      z-index: 1000;
    }
    .info-content ul {
      display: flex;
      margin: 0;
      padding: 0;
      list-style: none;
      align-items: flex-end;
    }
    .info-content a {
      display: block;
      color: #fff;
      font-weight: 500;
      text-decoration: none;
      padding: 12px 24px 8px;
      background-color: #4338ca; /* Indigo tabs */
      margin: 0 2px;
      border-top-left-radius: 8px;
      border-top-right-radius: 8px;
      box-shadow: 0 -2px 4px rgba(0,0,0,0.1);
      transition: all 0.2s ease;
    }
    .info-content a:hover {
      background-color: #3730a3;
      padding-top: 14px;
      margin-bottom: -2px;
    }

    /* Modo oscuro */
    @media (prefers-color-scheme: dark) {
      body { background-color: #0f172a; color: #e2e8f0; }
      h1 { color: #c7d2fe; }
      .form-content { background-color: #1e293b; border-color: #334155; }
      textarea { background-color: #1e293b; border-color: #334155; color: #f1f5f9; }
      .info-content a { background-color: #6366f1; }
      .info-content a:hover { background-color: #4f46e5; }
    }
  </style>
</head>
<body>
      <?php if (!empty($mensaje)): ?>
    <div id="toast" role="alert"
        class="fixed top-5 left-1/2 -translate-x-1/2 px-6 py-3 rounded-lg shadow-lg text-white 
        <?= $tipoMensaje === 'success' ? 'bg-green-500' : 'bg-red-500' ?> 
        transition transform opacity-0 scale-90">
    <?= htmlspecialchars($mensaje) ?>
    </div>
  <?php endif; ?>

    <div class="form-content">
        <h2 class="text-3xl font-bold text-white mb-4 text-center">Autodiagnóstico de la Cadena de Valor</h2>

        <br>

        <form method="POST" action="../business/autodiagnostico.php">
<table class="w-full border border-white text-center text-sm" cellspacing="0" cellpadding="10">
    <thead>
        <tr class="bg-indigo-600 text-white">
            <th class="px-4 py-3 text-left border border-white">Enunciado</th>
            <th class="px-3 py-2 border border-white">En total en desacuerdo</th>
            <th class="px-3 py-2 border border-white">No está de acuerdo</th>
            <th class="px-3 py-2 border border-white">Está de acuerdo</th>
            <th class="px-3 py-2 border border-white">Está bastante de acuerdo</th>
            <th class="px-3 py-2 border border-white">En total acuerdo</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        for ($i = 0; $i < 25; $i++) {
            $valorSeleccionado = isset($autovalores[$i]) ? $autovalores[$i] : 0;
            $pregunta = $preguntas[$i];

            echo "<tr>
                <td class='px-4 py-3 border border-white text-white-800'>$pregunta</td>
                <td class='border border-white'><input type='radio' name='punto_" . ($i + 1) . "' value='0' " . ($valorSeleccionado == 0 ? 'checked' : '') . "></td>
                <td class='border border-white'><input type='radio' name='punto_" . ($i + 1) . "' value='1' " . ($valorSeleccionado == 1 ? 'checked' : '') . "></td>
                <td class='border border-white'><input type='radio' name='punto_" . ($i + 1) . "' value='2' " . ($valorSeleccionado == 2 ? 'checked' : '') . "></td>
                <td class='border border-white'><input type='radio' name='punto_" . ($i + 1) . "' value='3' " . ($valorSeleccionado == 3 ? 'checked' : '') . "></td>
                <td class='border border-white'><input type='radio' name='punto_" . ($i + 1) . "' value='4' " . ($valorSeleccionado == 4 ? 'checked' : '') . "></td>
            </tr>";
        }
        ?>
    </tbody>
</table>

            <!-- Mostrar el potencial de mejora si está disponible -->
<?php if ($potencialMejora !== null): ?>
    <div class="flex justify-center mt-6">
        <div class="bg-indigo-50 border border-indigo-300 rounded-xl shadow-md px-6 py-4 text-center max-w-md">
            <strong class="text-indigo-700 text-lg">
                POTENCIAL DE MEJORA DE LA CADENA DE VALOR INTERNA:
            </strong>
            <p class="text-2xl font-bold text-indigo-900 mt-2">
                <?php echo $potencialMejora; ?>%
            </p>
        </div>
    </div>
    <?php unset($_SESSION['potencialMejora']); // Limpiar el valor de la sesión ?>
<?php endif; ?>
        <br>
        <br>
            <!-- Título y Cuadro de texto para reflexiones -->
            <div class="center">
                <label class="block text-lg font-semibold text-white-700 mb-2 border-b-2 border-indigo-300 pb-1">Reflexiones</label>
                <textarea class="reflexion-textarea" name="reflexion" placeholder="Escribe tus reflexiones sobre el autodiagnóstico..."><?php echo isset($reflexionesGuardadas) ? htmlspecialchars($reflexionesGuardadas) : ''; ?></textarea>
            </div>

            <!-- Título y Cuadro de texto para fortalezas -->
            <div class="center">
                <label class="block text-lg font-semibold text-white-700 mb-2 border-b-2 border-indigo-300 pb-1">Fortalezas</label>
                <textarea class="fortalezas-textarea" name="fortalezas" placeholder="Escribe las fortalezas..."><?php echo isset($fortalezasGuardadas) ? htmlspecialchars($fortalezasGuardadas) : ''; ?></textarea>
            </div>

            <!-- Título y Cuadro de texto para debilidades -->
            <div class="center">
                <label class="block text-lg font-semibold text-white-700 mb-2 border-b-2 border-indigo-300 pb-1">Debilidades</label>
                <textarea class="debilidades-textarea" name="debilidades" placeholder="Escribe las debilidades..."><?php echo isset($debilidadesGuardadas) ? htmlspecialchars($debilidadesGuardadas) : ''; ?></textarea>
            </div>

            <!-- Botón para guardar la autoevaluación -->
         <div class="flex flex-col items-center space-y-4 mt-4">
  <button type="submit" name="guardarAutoevaluacion" 
      class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-xl shadow-md hover:bg-blue-700 transition">
      Realizar Autoevaluación
  </button>

  <button type="submit" name="guardarReflexion" 
      class="px-6 py-2 bg-green-600 text-white font-semibold rounded-xl shadow-md hover:bg-green-700 transition">
      Guardar Datos
  </button>
</div>
        </form>

            <div class="center" style="display: flex; justify-content: space-between; margin-top: 20px;">
            <a href="dashboard.php" class="btn-volver">Volver al Dashboard</a>
            <a href="matriz1.php" class="btn-siguiente">Siguiente</a>
        </div>
    </div>
 <div class="info-content">
            <?php include('aside.php'); ?>
        </div>
</body>
</html>
  <script>
    // Animar el toast si existe
    const toast = document.getElementById('toast');
    if (toast) {
      setTimeout(() => {
        toast.classList.remove('opacity-0', 'scale-90');
        toast.classList.add('opacity-100', 'scale-100');
      }, 100); // Aparece suavemente

      // Desaparecer después de 3 segundos
      setTimeout(() => {
        toast.classList.remove('opacity-100', 'scale-100');
        toast.classList.add('opacity-0', 'scale-90');
      }, 3000);
    }
  </script>