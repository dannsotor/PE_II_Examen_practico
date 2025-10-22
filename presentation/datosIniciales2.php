<?php
session_start();

// Verificar sesión
if (!isset($_SESSION['idusuario']) || !isset($_SESSION['idPlan'])) {
    header("Location: login.php");
    exit();
}

require_once '../data/plan.php';

$idusuario = $_SESSION['idusuario'];
$idPlan = $_SESSION['idPlan'];
$planData = new PlanData();
$plan = $planData->obtenerPlanPorId($idPlan, $idusuario);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardarPlan'])) {
    $nombreEmpresa = trim($_POST['nombreEmpresa'] ?? '');
    $fecha = $_POST['fecha'] ?? '';
    $promotores = trim($_POST['promotores'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $sector = trim($_POST['sector'] ?? '');

    // Manejar logo
    $logo = $plan['logo'] ?? null;
    if (!empty($_FILES['logo']['name'])) {
        $targetDir = __DIR__ . "/assets/uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $nombreArchivo = time() . "_" . basename($_FILES["logo"]["name"]);
        $rutaDestino = $targetDir . $nombreArchivo;
        if (move_uploaded_file($_FILES["logo"]["tmp_name"], $rutaDestino)) {
            $logo = $nombreArchivo;
        } else {
            $mensaje = 'Error al subir el archivo.';
            $tipoMensaje = 'error';
        }
    }

    // Actualizar en BD
    if (empty($mensaje)) {
        $resultado = $planData->actualizarPlanExtendido($idPlan, $nombreEmpresa, $fecha, $promotores, $descripcion, $sector, $logo);

        if ($resultado) {
            $mensaje = '✅ Plan actualizado correctamente.';
            $tipoMensaje = 'success';
            $plan = $planData->obtenerPlanPorId($idPlan, $idusuario);
        } else {
            $mensaje = '❌ Error al actualizar el plan.';
            $tipoMensaje = 'error';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Datos Iniciales del Plan</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; }
    @keyframes float {0%,100%{transform:translateY(0)}50%{transform:translateY(-8px)}}

    /* Barra inferior fija */
    .info-content {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      background-color: #4338ca;
      color: white;
      display: flex;
      justify-content: center;
      padding: 10px 0;
      border-top-left-radius: 15px;
      border-top-right-radius: 15px;
      box-shadow: 0 -2px 10px rgba(0,0,0,0.15);
      z-index: 999;
    }
    .info-content ul {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      align-items: center;
      list-style: none;
      margin: 0;
      padding: 0;
    }
    .info-content li {
      margin: 5px 6px;
    }
    .info-content a {
      text-decoration: none;
      color: #fff;
      font-weight: 500;
      background-color: #4f46e5;
      padding: 8px 14px;
      border-radius: 10px;
      transition: background 0.3s;
    }
    .info-content a:hover {
      background-color: #3730a3;
    }
  </style>
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-50 via-sky-50 to-purple-50 dark:from-gray-900 dark:via-slate-900 dark:to-indigo-950">

  <!-- Mensaje Toast -->
  <?php if (!empty($mensaje)): ?>
    <div id="toast" role="alert"
      class="fixed top-5 left-1/2 -translate-x-1/2 px-6 py-3 rounded-lg shadow-lg text-white 
      <?= $tipoMensaje === 'success' ? 'bg-green-500' : 'bg-red-500' ?> 
      transition transform opacity-0 scale-90">
      <?= htmlspecialchars($mensaje) ?>
    </div>
  <?php endif; ?>

  <!-- Fondo decorativo -->
  <div aria-hidden="true" class="fixed inset-0 overflow-hidden pointer-events-none">
    <div class="absolute -top-20 -left-20 h-72 w-72 rounded-full bg-indigo-300/40 blur-3xl animate-[float_6s_ease-in-out_infinite]"></div>
    <div class="absolute -bottom-24 -right-24 h-96 w-96 rounded-full bg-fuchsia-300/30 blur-3xl animate-[float_8s_ease-in-out_infinite]"></div>
  </div>

  <!-- CARD PRINCIPAL -->
  <div class="relative z-10 w-full max-w-2xl bg-white/80 dark:bg-slate-900/70 rounded-3xl shadow-2xl backdrop-blur-xl border border-slate-200/60 dark:border-white/10 p-10 mb-28">
    <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white mb-8 text-center">Datos Iniciales del Plan</h1>

    <form action="" method="POST" enctype="multipart/form-data" id="formPlan" class="space-y-6">
      <input type="hidden" name="idPlan" value="<?= htmlspecialchars($idPlan) ?>">

      <!-- Nombre -->
      <div>
        <label for="nombreEmpresa" class="block mb-1 text-sm font-semibold text-indigo-700 dark:text-indigo-300">Nombre de la Empresa</label>
        <input type="text" id="nombreEmpresa" name="nombreEmpresa" value="<?= htmlspecialchars($plan['nombreempresa']) ?>" required
          class="w-full rounded-xl border border-slate-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-indigo-400">
      </div>

      <!-- Fecha -->
      <div>
        <label for="fecha" class="block mb-1 text-sm font-semibold text-indigo-700 dark:text-indigo-300">Fecha de Elaboración</label>
        <input type="date" id="fecha" name="fecha" value="<?= htmlspecialchars($plan['fecha']) ?>" required
          class="w-full rounded-xl border border-slate-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-indigo-400">
      </div>

      <!-- Promotores -->
      <div>
        <label for="promotores" class="block mb-1 text-sm font-semibold text-indigo-700 dark:text-indigo-300">Emprendedores / Promotores</label>
        <input type="text" id="promotores" name="promotores" value="<?= htmlspecialchars($plan['promotores']) ?>" required
          class="w-full rounded-xl border border-slate-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-indigo-400">
      </div>

      <!-- Descripción -->
      <div>
        <label for="descripcion" class="block mb-1 text-sm font-semibold text-indigo-700 dark:text-indigo-300">Descripción del Negocio</label>
        <textarea id="descripcion" name="descripcion" rows="3" placeholder="Describe brevemente la naturaleza del negocio..."
          class="w-full rounded-xl border border-slate-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-indigo-400"><?= htmlspecialchars($plan['descripcion'] ?? '') ?></textarea>
      </div>

      <!-- Sector Económico -->
      <div>
        <label for="sector" class="block mb-1 text-sm font-semibold text-indigo-700 dark:text-indigo-300">Sector Económico</label>
        <select id="sector" name="sector" required
          class="w-full rounded-xl border border-slate-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-indigo-400">
          <option value="">Seleccione...</option>
          <?php
            $sectores = ['Tecnología','Educación','Salud','Comercio','Manufactura','Agroindustria','Turismo','Servicios'];
            foreach ($sectores as $s) {
              $selected = (isset($plan['sector']) && $plan['sector'] === $s) ? 'selected' : '';
              echo "<option value='$s' $selected>$s</option>";
            }
          ?>
        </select>
      </div>

      <!-- Logo -->
      <div>
        <label for="logo" class="block mb-1 text-sm font-semibold text-indigo-700 dark:text-indigo-300">Subir Logo</label>
        <input type="file" id="logo" name="logo" accept="image/*" class="w-full rounded-xl border border-slate-300 px-4 py-2 shadow-sm">
        <?php if (!empty($plan['logo'])): ?>
          <img id="previewLogo" src="assets/uploads/<?= htmlspecialchars($plan['logo']) ?>" alt="Logo actual" class="mt-3 w-28 rounded-lg shadow-md">
        <?php else: ?>
          <img id="previewLogo" src="#" alt="Vista previa" class="mt-3 w-28 rounded-lg shadow-md hidden">
        <?php endif; ?>
      </div>

      <!-- Botones -->
      <div class="flex justify-between pt-4">
        <a href="dashboard.php" class="px-5 py-2 rounded-xl bg-slate-400 hover:bg-slate-500 text-white font-medium shadow-md transition">⬅ Volver al Dashboard</a>
        <button type="submit" name="guardarPlan" class="px-6 py-2 rounded-xl bg-teal-500 hover:bg-teal-600 text-white font-semibold shadow-md transition">💾 Guardar Cambios</button>
        <a href="mision.php" class="px-5 py-2 rounded-xl bg-indigo-500 hover:bg-indigo-600 text-white font-medium shadow-md transition">Siguiente ➡</a>
      </div>
    </form>
  </div>

  <!-- Barra inferior -->
  <div class="info-content">
    <ul>
      <li><a href="datos_iniciales.php">🏠 Datos Iniciales</a></li>
      <li><a href="mision.php">🎯 Misión</a></li>
      <li><a href="vision.php">👁️ Visión</a></li>
      <li><a href="valores.php">💎 Valores</a></li>
      <li><a href="objetivos.php">📌 Objetivos</a></li>
      <li><a href="analisis_ie.php">📊 Análisis I/E</a></li>
      <li><a href="cadena_valor.php">🔗 Cadena Valor</a></li>
      <li><a href="diagnostico.php">🩺 Diagnóstico</a></li>
      <li><a href="matriz_participacion.php">🧭 Participación</a></li>
      <li><a href="matriz_porter.php">⚙️ Porter</a></li>
      <li><a href="pest.php">🌍 PEST</a></li>
      <li><a href="estrategias.php">🚀 Estrategias</a></li>
      <li><a href="matriz_came.php">🧩 CAME</a></li>
      <li><a href="ultimo.php">🏁 Último</a></li>
    </ul>
  </div>

  <script>
    // Animar Toast
    const toast = document.getElementById('toast');
    if (toast) {
      setTimeout(() => toast.classList.replace('opacity-0','opacity-100'), 100);
      setTimeout(() => toast.classList.replace('opacity-100','opacity-0'), 3000);
    }

    // Vista previa del logo
    const inputLogo = document.getElementById('logo');
    const preview = document.getElementById('previewLogo');
    inputLogo.addEventListener('change', () => {
      const file = inputLogo.files[0];
      if (file) {
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('hidden');
      }
    });

    // Validación simple
    document.getElementById('formPlan').addEventListener('submit', e => {
      const nombre = document.getElementById('nombreEmpresa').value.trim();
      if (nombre.length < 3) {
        alert('Por favor, ingresa un nombre válido.');
        e.preventDefault();
      }
    });
  </script>
</body>
</html>
