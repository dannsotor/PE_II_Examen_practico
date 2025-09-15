<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['idusuario'])) {
    header("Location: login.php");
    exit();
}

include_once '../data/plan.php';

$idusuario = $_SESSION['idusuario'];
$planData = new PlanData();
$planes = $planData->obtenerPlanesPorUsuario($idusuario);
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard de Planes - PETI</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <style>
    body { font-family: 'Inter', sans-serif; }
    @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-6px)} }
  </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-indigo-50 via-sky-50 to-purple-50 dark:from-gray-900 dark:via-slate-900 dark:to-indigo-950">

  <!-- Fondo decorativo -->
  <div aria-hidden="true" class="pointer-events-none fixed inset-0 overflow-hidden">
    <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-indigo-300/40 blur-3xl animate-[float_6s_ease-in-out_infinite]"></div>
    <div class="absolute -bottom-32 -right-32 h-96 w-96 rounded-full bg-fuchsia-300/30 blur-3xl animate-[float_8s_ease-in-out_infinite]"></div>
  </div>

  <!-- Contenedor principal -->
  <div class="relative z-10 min-h-screen flex flex-col items-center p-6">

    <!-- Header -->
    <div class="w-full max-w-6xl flex justify-between items-center mb-8">
      <h1 class="text-3xl font-extrabold text-indigo-700 dark:text-indigo-400">Dashboard de Planes</h1>
      <button onclick="logout()"
        class="px-5 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-xl shadow-lg transition">
        Cerrar Sesión
      </button>
    </div>

    <!-- Botón Crear Plan -->
    <div class="w-full max-w-6xl mb-6 flex justify-end">
      <button onclick="window.location.href='datosIniciales.php'"
        class="px-6 py-3 bg-rose-500 hover:bg-rose-600 text-white font-semibold rounded-2xl shadow-lg transition flex items-center gap-2">
        <i class="fas fa-plus"></i> Crear Plan
      </button>
    </div>

    <!-- Tabla de planes -->
    <div class="w-full max-w-6xl overflow-x-auto">
    <?php if (count($planes) > 0): ?>
    <table class="min-w-full bg-white/90 dark:bg-slate-800/70 rounded-xl shadow-lg border border-slate-200/40 dark:border-white/20 overflow-hidden">
        <thead class="bg-indigo-100/80 dark:bg-indigo-900/40">
        <tr>
            <th class="py-3 px-6 text-left text-indigo-700 dark:text-indigo-200 font-semibold">Logo</th>
            <th class="py-3 px-6 text-left text-indigo-700 dark:text-indigo-200 font-semibold">Nombre del Plan</th>
            <th class="py-3 px-6 text-center text-indigo-700 dark:text-indigo-200 font-semibold">Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($planes as $plan): ?>
        <tr class="bg-white/80 dark:bg-slate-700/50 hover:bg-indigo-50 dark:hover:bg-indigo-700/30 transition-colors">
            <td class="py-3 px-6">
            <?php if (!empty($plan['logo'])): ?>
            <img src="assets/uploads/<?php echo htmlspecialchars($plan['logo']); ?>" alt="Logo del plan"
                class="h-16 w-16 object-contain border border-slate-300 dark:border-slate-600 rounded-lg p-1">
            <?php else: ?>
            <span class="text-slate-500 dark:text-slate-400">No Image</span>
            <?php endif; ?>
            </td>
            <td class="py-3 px-6 text-slate-900 dark:text-white"><?php echo htmlspecialchars($plan['nombreempresa']); ?></td>
            <td class="py-3 px-6 flex justify-center gap-3">
            <button onclick="redirectToDatosIniciales(<?php echo $plan['idplan']; ?>)"
                class="px-4 py-2 bg-teal-500 hover:bg-teal-600 text-white rounded-xl shadow transition flex items-center gap-1">
                <i class="fas fa-eye"></i> Ver
            </button>
            <button onclick="window.location.href='../business/descargarPDF.php?id=<?php echo $plan['idplan']; ?>'"
                class="px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-slate-900 rounded-xl shadow transition flex items-center gap-1">
                <i class="fas fa-download"></i> PDF
            </button>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p class="text-slate-700 dark:text-slate-300 mt-10 text-center text-lg">No hay planes creados.</p>
    <?php endif; ?>
    </div>

  </div>

  <script>
    function logout() {
      window.location.href = '../business/cerrarSesion.php';
    }

    function redirectToDatosIniciales(idPlan) {
      const xhr = new XMLHttpRequest();
      xhr.open('POST', '../business/almacenarIdPlan.php', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
          window.location.href = 'datosIniciales2.php';
        }
      };
      xhr.send('idPlan=' + encodeURIComponent(idPlan));
    }
  </script>

</body>
</html>