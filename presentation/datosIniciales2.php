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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardarPlan'])) {
    $nombreEmpresa = $_POST['nombreEmpresa'] ?? '';
    $fecha = $_POST['fecha'] ?? '';
    $promotores = $_POST['promotores'] ?? '';
    
    // Manejar la carga del logo
    $logo = null;
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == UPLOAD_ERR_OK) {
        $targetDir = __DIR__ . "/assets/uploads/"; 
        $logo = basename($_FILES["logo"]["name"]); // Solo el nombre del archivo
        $rutaDestino = $targetDir . $logo;

        if (!move_uploaded_file($_FILES["logo"]["tmp_name"], $rutaDestino)) {
            $mensaje = 'Error al subir el archivo.';
            $tipoMensaje = 'error';
        }
    }

    // Actualizar el plan en la base de datos
    if (empty($mensaje)) { // Solo si no hubo error con el archivo
        $resultado = $planData->actualizarPlan($idPlan, $nombreEmpresa, $fecha, $promotores, $logo);

        if ($resultado) {
            $mensaje = 'Plan actualizado exitosamente.';
            $tipoMensaje = 'success';
            $plan = $planData->obtenerPlanPorId($idPlan, $idusuario); // Refrescar plan
        } else {
            $mensaje = 'Error al actualizar el plan.';
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
  <title>Datos Iniciales</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; }
    @keyframes float {0%,100%{transform:translateY(0)}50%{transform:translateY(-8px)}}
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
  </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-50 via-sky-50 to-purple-50 dark:from-gray-900 dark:via-slate-900 dark:to-indigo-950">
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

  <!-- Card -->
  <div class="relative z-10 w-full max-w-2xl bg-white/80 dark:bg-slate-900/70 rounded-3xl shadow-2xl backdrop-blur-xl border border-slate-200/60 dark:border-white/10 p-10">
    <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white mb-8 text-center">
      Datos Iniciales del Plan
    </h1>

    <form action="" method="POST" enctype="multipart/form-data" class="space-y-6">
      <input type="hidden" name="idPlan" value="<?php echo htmlspecialchars($idPlan); ?>">

      <!-- Nombre de la empresa -->
      <div>
        <label for="nombreEmpresa" class="block mb-1 text-sm font-semibold text-indigo-700 dark:text-indigo-300">Nombre de la Empresa</label>
        <input type="text" id="nombreEmpresa" name="nombreEmpresa" value="<?php echo htmlspecialchars($plan['nombreempresa']); ?>" required
          class="w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white/70 dark:bg-slate-800/60 px-4 py-2 text-slate-900 dark:text-white placeholder-slate-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
      </div>

      <!-- Fecha -->
      <div>
        <label for="fecha" class="block mb-1 text-sm font-semibold text-indigo-700 dark:text-indigo-300">Fecha de Elaboración</label>
        <input type="date" id="fecha" name="fecha" value="<?php echo htmlspecialchars($plan['fecha']); ?>" required
          class="w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white/70 dark:bg-slate-800/60 px-4 py-2 text-slate-900 dark:text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
      </div>

      <!-- Promotores -->
      <div>
        <label for="promotores" class="block mb-1 text-sm font-semibold text-indigo-700 dark:text-indigo-300">Emprendedores / Promotores</label>
        <input type="text" id="promotores" name="promotores" value="<?php echo htmlspecialchars($plan['promotores']); ?>" required
          class="w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white/70 dark:bg-slate-800/60 px-4 py-2 text-slate-900 dark:text-white placeholder-slate-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
      </div>

      <!-- Logo -->
      <div>
        <label for="logo" class="block mb-1 text-sm font-semibold text-indigo-700 dark:text-indigo-300">Subir Logo</label>
        <input type="file" id="logo" name="logo" accept="image/*"
          class="w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white/70 dark:bg-slate-800/60 px-4 py-2 text-slate-900 dark:text-white shadow-sm">
      </div>

      <!-- Botones -->
      <div class="flex justify-between pt-4">
        <a href="dashboard.php"
          class="px-5 py-2 rounded-xl bg-slate-400 hover:bg-slate-500 text-white font-medium shadow-md transition">
          ⬅ Volver al Dashboard
        </a>
        <button type="submit" name="guardarPlan"
          class="px-6 py-2 rounded-xl bg-teal-500 hover:bg-teal-600 text-white font-semibold shadow-md transition">
          💾 Guardar Cambios
        </button>
        <a href="mision.php"
          class="px-5 py-2 rounded-xl bg-indigo-500 hover:bg-indigo-600 text-white font-medium shadow-md transition">
          Siguiente ➡
        </a>
      </div>
    </form>


  </div>
      <!-- Aside -->
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