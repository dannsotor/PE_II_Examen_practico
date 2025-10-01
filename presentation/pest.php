<?php

require_once('../data/analisis_pest.php');

session_start();
if (!isset($_SESSION['idPlan'])) {
    die("ID de plan no encontrado en la sesión.");
}

$idPlan = $_SESSION['idPlan'];

$pest = new AnalisisPest();

$respuestasGuardadas = $pest->obtenerPest($idPlan);
$respuestas = $respuestasGuardadas ? explode(",", $respuestasGuardadas) : array_fill(0, 25, 0);

    // Calcular los puntajes de cada factor basados en las respuestas
    $puntajesCalculados = [
        'social' => 0,
        'ambiental' => 0,
        'politico' => 0,
        'economico' => 0,
        'tecnologico' => 0
    ];
    
    // Asumiendo que las respuestas están ordenadas según los factores
    // Esto depende de cómo se organice la respuesta en el formulario
    $puntajesCalculados['social'] = (array_sum(array_slice($respuestas, 0, 5)) / 5) * 25; // Social: 5 respuestas
    $puntajesCalculados['ambiental'] = (array_sum(array_slice($respuestas, 5, 5)) / 5) * 25; // Ambiental: 5 respuestas
    $puntajesCalculados['politico'] = (array_sum(array_slice($respuestas, 10, 5)) / 5) * 25; // Político: 5 respuestas
    $puntajesCalculados['economico'] = (array_sum(array_slice($respuestas, 15, 5)) / 5) * 25; // Económico: 5 respuestas
    $puntajesCalculados['tecnologico'] = (array_sum(array_slice($respuestas, 20, 5)) / 5) * 25; // Tecnológico: 5 respuestas

// Enunciados del análisis PEST
$enunciados = [
    // Económico
    "Los cambios en la composición étnica de los consumidores de nuestro mercado está teniendo un notable impacto.",
    "El envejecimiento de la población tiene un importante impacto en la demanda.",
    "Las variaciones en el nivel de riqueza de la población impactan considerablemente en la demanda de los productos/servicios del sector.",
    "La legislación fiscal afecta muy considerablemente a la economía de las empresas del sector.",
    "Las expectativas de crecimiento económico generales afectan crucialmente al mercado.",
    // Político
    "La legislación laboral afecta muy considerablemente a la operativa del sector.",
    "Las subvenciones otorgadas por las Administraciones Públicas son claves en el desarrollo competitivo del mercado.",
    "El impacto que tiene la legislación de protección al consumidor es muy importante.",
    "La normativa autonómica tiene un impacto considerable en el funcionamiento del sector.",
    "Las Administraciones Públicas están incentivando el esfuerzo tecnológico de las empresas de nuestro sector.",
    // Social
    "Los nuevos estilos de vida y tendencias originan cambios en la oferta de nuestro sector.",
    "El envejecimiento de la población tiene un importante impacto en la oferta del sector donde operamos.",
    "La globalización permite a nuestra industria gozar de importantes oportunidades en nuevos mercados.",
    "La situación del empleo es fundamental para el desarrollo económico de nuestra empresa.",
    "Los clientes de nuestro mercado exigen que seamos socialmente responsables, en el plano medioambiental.",
    // Tecnológico
    "Internet, el comercio electrónico, el wireless y otras NTIC están impactando en la demanda de nuestros productos/servicios.",
    "El empleo de NTIC´s es generalizado en el sector.",
    "En nuestro sector, es de gran importancia ser pionero o referente en el empleo de aplicaciones tecnológicas.",
    "En el sector donde operamos, para ser competitivos, es condición innovar constantemente.",
    "Los recursos tecnológicos son una ventaja competitiva clave.",
    // Ambiental
    "La legislación medioambiental afecta al desarrollo de nuestro sector.",
    "En nuestro sector, las políticas medioambientales son una fuente de ventajas competitivas.",
    "La creciente preocupación social por el medio ambiente impacta notablemente en la demanda de productos/servicios.",
    "El factor ecológico es una fuente de diferenciación clara en el sector."
];

// Lógica para generar conclusiones
function generarConclusion($respuestas, $inicio, $cantidad) {
    $rangos = array_slice($respuestas, $inicio, $cantidad);

    $deAcuerdo = count(array_filter($rangos, fn($v) => $v == 2));
    $bastanteDeAcuerdo = count(array_filter($rangos, fn($v) => $v == 3));
    $enTotalAcuerdo = count(array_filter($rangos, fn($v) => $v == 4));

    // Evaluar conclusiones según las combinaciones especificadas
    if ($bastanteDeAcuerdo >= 3 || $enTotalAcuerdo >= 2) {
        return "HAY UN NOTABLE IMPACTO DEL FACTOR EN EL FUNCIONAMIENTO DE LA EMPRESA.";
    } elseif ($deAcuerdo >= 2 && $bastanteDeAcuerdo >= 2) {
        return "NO HAY UN NOTABLE IMPACTO DEL FACTOR EN EL FUNCIONAMIENTO DE LA EMPRESA.";
    } else {
        return "IMPACTO DEL FACTOR NO CLARAMENTE DEFINIDO.";
    }
}

// Generar conclusiones para cada factor
$conclusiones = [
    "Económico" => generarConclusion($respuestas, 0, 5),
    "Político" => generarConclusion($respuestas, 5, 5),
    "Social" => generarConclusion($respuestas, 10, 5),
    "Tecnológico" => generarConclusion($respuestas, 15, 5),
    "Ambiental" => generarConclusion($respuestas, 20, 5)
];
// Obtener los puntajes de los factores (si existen)
$puntajes = [
    'social' => $_POST['puntaje_social'] ?? 0,
    'ambiental' => $_POST['puntaje_ambiental'] ?? 0,
    'politico' => $_POST['puntaje_politico'] ?? 0,
    'economico' => $_POST['puntaje_economico'] ?? 0,
    'tecnologico' => $_POST['puntaje_tecnologico'] ?? 0
];

// Recuperar oportunidades y amenazas desde la base de datos
$oportunidades = "";  // Valor predeterminado vacío
$amenazas = "";  // Valor predeterminado vacío

// Método para obtener las oportunidades y amenazas de la base de datos
$oportunidadesAmenazas = $pest->obtenerOportunidadesAmenazas($idPlan);
if ($oportunidadesAmenazas) {
    $oportunidades = $oportunidadesAmenazas['oportunidades'];
    $amenazas = $oportunidadesAmenazas['amenazas'];
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Análisis PEST</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

<div class="form-content">
    <h2 class="text-3xl font-bold text-white mb-4 text-center">Análisis PEST</h2>

    <form method="POST" action="../business/procesar_analisis_pest.php">
        <input type="hidden" name="conclusion_economico" value="<?php echo $conclusiones['Económico']; ?>">
        <input type="hidden" name="conclusion_politico" value="<?php echo $conclusiones['Político']; ?>">
        <input type="hidden" name="conclusion_social" value="<?php echo $conclusiones['Social']; ?>">
        <input type="hidden" name="conclusion_tecnologico" value="<?php echo $conclusiones['Tecnológico']; ?>">
        <input type="hidden" name="conclusion_ambiental" value="<?php echo $conclusiones['Ambiental']; ?>">
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
                foreach ($enunciados as $index => $enunciado) {
                    $valorSeleccionado = isset($respuestas[$index]) ? $respuestas[$index] : 0;
                    echo "<tr>
                            <td class='px-4 py-3 border border-white text-white-800'>$enunciado</td>
                            <td class='border border-white'><input type='radio' name='respuesta_" . ($index + 1) . "' value='0' " . ($valorSeleccionado == 0 ? 'checked' : '') . "></td>
                            <td class='border border-white'><input type='radio' name='respuesta_" . ($index + 1) . "' value='1' " . ($valorSeleccionado == 1 ? 'checked' : '') . "></td>
                            <td class='border border-white'><input type='radio' name='respuesta_" . ($index + 1) . "' value='2' " . ($valorSeleccionado == 2 ? 'checked' : '') . "></td>
                            <td class='border border-white'><input type='radio' name='respuesta_" . ($index + 1) . "' value='3' " . ($valorSeleccionado == 3 ? 'checked' : '') . "></td>
                            <td class='border border-white'><input type='radio' name='respuesta_" . ($index + 1) . "' value='4' " . ($valorSeleccionado == 4 ? 'checked' : '') . "></td>
                        </tr>";
                }
                ?>
            </tbody>
        </table>

        <div class="flex justify-end mt-6">
            <button 
                type="submit" 
                name="guardarAnalisis" 
                class="px-6 py-3 text-white font-semibold text-lg rounded-xl 
                    bg-gradient-to-r from-indigo-600 to-purple-600 
                    shadow-lg hover:from-purple-600 hover:to-pink-500 
                    transform hover:scale-105 transition duration-300 ease-in-out">
                🚀 Realizar Análisis PEST
            </button>
        </div>
<h3 class="text-xl font-semibold text-indigo-300 text-center mb-3"> Conclusiones</h3>

<ul class="bg-gray-900 text-white rounded-lg shadow-md p-4 space-y-2 text-sm">
    <?php
    foreach ($conclusiones as $factor => $conclusion) {
        echo "
        <li class='border-b border-gray-700 pb-1 last:border-none'>
            <strong class='text-indigo-400'>$factor:</strong> 
            <span class='text-gray-300'>$conclusion</span>
        </li>";
    }
    ?>
</ul>

<div class="flex justify-end mt-4">
    <button 
        type="submit" 
        name="guardarConclusiones" 
        class="px-4 py-2 text-sm text-white font-medium rounded-lg 
               bg-gradient-to-r from-green-500 to-emerald-400 
               shadow-md hover:from-emerald-400 hover:to-green-300 
               transform hover:scale-105 transition duration-200 ease-in-out">
        💾 Guardar
    </button>
</div>
    
<div class="mt-6 bg-gray-900 rounded-xl shadow-lg p-6">
    <h4 class="text-lg font-semibold text-indigo-300 mb-4 text-center">
        📊 Impacto de los Factores Externos
    </h4>
    <div class="flex justify-center">
        <canvas id="impactoFactores" class="max-w-full h-64"></canvas>
    </div>
</div>

    <div class="mt-4">
    <h4>Oportunidades</h4>
        <textarea name="oportunidades" class="textarea" placeholder="Escribe las oportunidades aquí..."><?php echo htmlspecialchars($oportunidades); ?></textarea>
    </div>

    <div class="mt-4">
        <h4>Amenazas</h4>
        <textarea name="amenazas" class="textarea" placeholder="Escribe las amenazas aquí..."><?php echo htmlspecialchars($amenazas); ?></textarea>
    </div>

    <div class="center mt-4">
        <button type="submit" name="guardarOportunidadesAmenazas" class="px-6 py-2 bg-green-600 text-white font-semibold rounded-xl shadow-md hover:bg-green-700 transition">Guardar Oportunidades y Amenazas</button>
    </div>
</form>

<br>

    <div class="center">
        <div class="center" style="display: flex; justify-content: space-between; margin-top: 20px;">
            <a href="dashboard.php" class="btn-volver">Volver al Dashboard</a>
            <a href="identificacionEstrategias.php" class="btn-siguiente">Siguiente</a>
    </div>
</div>
 <div class="info-content">
            <?php include('aside.php'); ?>
        </div>
<script>
    // Crear el gráfico de barras con Chart.js
    var ctx = document.getElementById('impactoFactores').getContext('2d');
    var impactoFactores = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Social', 'Ambiental', 'Político', 'Económico', 'Tecnológico'],
            datasets: [{
                label: 'Nivel de Impacto de Factores Externos',
                data: [
                    <?= $puntajesCalculados['social'] ?>,
                    <?= $puntajesCalculados['ambiental'] ?>,
                    <?= $puntajesCalculados['politico'] ?>,
                    <?= $puntajesCalculados['economico'] ?>,
                    <?= $puntajesCalculados['tecnologico'] ?>
                ],
                backgroundColor: ['#007bff', '#28a745', '#dc3545', '#ffc107', '#6f42c1'],
                borderColor: ['#0056b3', '#218838', '#c82333', '#e0a800', '#5a3e93'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
</script>
</body>
</html>