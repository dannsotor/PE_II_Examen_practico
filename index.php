<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>PETI - Plan Estratégico de TI</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; }
    @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-12px)} }
  </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-indigo-50 via-sky-50 to-purple-50 dark:from-gray-900 dark:via-slate-900 dark:to-indigo-950">

  <!-- Fondo decorativo -->
  <div aria-hidden="true" class="pointer-events-none fixed inset-0 overflow-hidden">
    <div class="absolute -top-20 -left-20 h-72 w-72 rounded-full bg-indigo-300/40 blur-3xl animate-[float_8s_ease-in-out_infinite]"></div>
    <div class="absolute -bottom-24 -right-24 h-96 w-96 rounded-full bg-fuchsia-300/30 blur-3xl animate-[float_10s_ease-in-out_infinite]"></div>
  </div>

  <!-- Contenido principal -->
  <div class="relative z-10 flex items-center justify-center px-6 py-16">
    <div class="max-w-3xl w-full bg-white/80 dark:bg-slate-900/70 rounded-3xl shadow-2xl backdrop-blur-xl p-10 text-center border border-slate-200/60 dark:border-white/10">
      
      <!-- Logo -->
      <div class="flex justify-center mb-6">
        <img src="presentation/assets/images/logo.png" alt="Logo PETI" class="h-24 w-auto animate-[float_6s_ease-in-out_infinite]">
      </div>

      <!-- Título -->
      <h1 class="text-4xl sm:text-5xl font-extrabold text-indigo-700 dark:text-indigo-400 mb-6 leading-tight">
        Cómo elaborarr un <br> Plan Estratégico de TI (PETI)
      </h1>

      <!-- Introducción -->
      <p class="text-lg text-slate-700 dark:text-slate-300 leading-relaxed mb-6">
        El éxito de las organizaciones depende de la capacidad de sus directivos para ejecutar una estrategia clara y eficaz. 
        Este sistema te guiará paso a paso para definir tu <span class="font-semibold text-indigo-600">Misión</span>, 
        <span class="font-semibold text-indigo-600">Visión</span> y <span class="font-semibold text-indigo-600">Valores</span>, 
        además de realizar un análisis interno y externo que impulse la competitividad de tu empresa.
      </p>

      <!-- Secciones -->
      <div class="grid gap-6 sm:grid-cols-3 mt-8">
        <div class="p-6 bg-indigo-50 dark:bg-indigo-900/40 rounded-xl shadow-md">
          <h3 class="font-bold text-indigo-700 dark:text-indigo-300 mb-2">Define tu norte</h3>
          <p class="text-sm text-slate-600 dark:text-slate-400">Establece Misión, Visión y Valores que guíen tu empresa.</p>
        </div>
        <div class="p-6 bg-fuchsia-50 dark:bg-fuchsia-900/40 rounded-xl shadow-md">
          <h3 class="font-bold text-fuchsia-700 dark:text-fuchsia-300 mb-2">Analiza tu realidad</h3>
          <p class="text-sm text-slate-600 dark:text-slate-400">Conoce fortalezas y oportunidades con un análisis integral.</p>
        </div>
        <div class="p-6 bg-emerald-50 dark:bg-emerald-900/40 rounded-xl shadow-md">
          <h3 class="font-bold text-emerald-700 dark:text-emerald-300 mb-2">Traza el camino</h3>
          <p class="text-sm text-slate-600 dark:text-slate-400">Identifica estrategias y acciones clave para tu éxito.</p>
        </div>
      </div>

      <!-- Botón -->
      <div class="mt-10">
        <a href="presentation/login.php" 
           class="inline-block rounded-2xl bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-3 text-lg font-semibold text-white shadow-lg transition hover:scale-105 hover:shadow-2xl">
          🚀 ¡Crea tu plan ahora!
        </a>
      </div>
    </div>
  </div>
</body>
</html>