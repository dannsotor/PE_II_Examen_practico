<?php
// Iniciar sesión
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['idusuario'])) {
    header("Location: login.php");
    exit();  
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
  </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-50 via-sky-50 to-purple-50 dark:from-gray-900 dark:via-slate-900 dark:to-indigo-950">

  <!-- Fondo decorativo -->
  <div aria-hidden="true" class="fixed inset-0 overflow-hidden pointer-events-none">
    <div class="absolute -top-20 -left-20 h-72 w-72 rounded-full bg-indigo-300/40 blur-3xl animate-[float_6s_ease-in-out_infinite]"></div>
    <div class="absolute -bottom-24 -right-24 h-96 w-96 rounded-full bg-fuchsia-300/30 blur-3xl animate-[float_8s_ease-in-out_infinite]"></div>
  </div>

  <!-- Card -->
  <div class="relative z-10 w-full max-w-2xl bg-white/80 dark:bg-slate-900/70 rounded-3xl shadow-2xl backdrop-blur-xl border border-slate-200/60 dark:border-white/10 p-10">
    <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white mb-8 text-center">
      Crear un nuevo plan ejecutivo
    </h1>

    <form action="../business/planBusiness.php" method="POST" enctype="multipart/form-data" class="space-y-6">
      <!-- Nombre empresa -->
      <div>
        <label for="nombreEmpresa" class="block mb-1 text-sm font-semibold text-indigo-700 dark:text-indigo-300">Nombre de la Empresa</label>
        <input type="text" id="nombreEmpresa" name="nombreEmpresa" required
          class="w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white/70 dark:bg-slate-800/60 px-4 py-2 text-slate-900 dark:text-white placeholder-slate-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
      </div>

      <!-- Fecha -->
      <div>
        <label for="fecha" class="block mb-1 text-sm font-semibold text-indigo-700 dark:text-indigo-300">Fecha de Elaboración</label>
        <input type="date" id="fecha" name="fecha" required
          class="w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white/70 dark:bg-slate-800/60 px-4 py-2 text-slate-900 dark:text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
      </div>

      <!-- Promotores -->
      <div>
        <label for="promotores" class="block mb-1 text-sm font-semibold text-indigo-700 dark:text-indigo-300">Emprendedores / Promotores</label>
        <input type="text" id="promotores" name="promotores" required
          class="w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white/70 dark:bg-slate-800/60 px-4 py-2 text-slate-900 dark:text-white placeholder-slate-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
      </div>

      <!-- Logo -->
      <div>
        <label for="logo" class="block mb-1 text-sm font-semibold text-indigo-700 dark:text-indigo-300">Subir Logo</label>
        <input type="file" id="logo" name="logo" accept="image/*" required
          class="w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white/70 dark:bg-slate-800/60 px-4 py-2 text-slate-900 dark:text-white shadow-sm">
      </div>

      <!-- Botones -->
      <div class="flex justify-between pt-4">
        <a href="../presentation/dashboard.php"
          class="px-5 py-2 rounded-xl bg-slate-400 hover:bg-slate-500 text-white font-medium shadow-md transition">
          ⬅ Volver al Dashboard
        </a>
        <button type="submit" name="crearPlan"
          class="px-6 py-2 rounded-xl bg-teal-500 hover:bg-teal-600 text-white font-semibold shadow-md transition">
          🚀 Crear Plan
        </button>
      </div>
    </form>
  </div>
</body>
</html>