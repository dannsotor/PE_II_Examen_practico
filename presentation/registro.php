<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Registro - PETI</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; }
    @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-10px)} }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-50 via-sky-50 to-purple-50 dark:from-gray-900 dark:via-slate-900 dark:to-indigo-950">

  <!-- Fondo decorativo -->
  <div aria-hidden="true" class="pointer-events-none fixed inset-0 overflow-hidden">
    <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-indigo-300/40 blur-3xl animate-[float_6s_ease-in-out_infinite]"></div>
    <div class="absolute -bottom-32 -right-32 h-96 w-96 rounded-full bg-fuchsia-300/30 blur-3xl animate-[float_8s_ease-in-out_infinite]"></div>
  </div>

  <!-- Card principal -->
  <div class="relative z-10 max-w-3xl w-full bg-white/80 dark:bg-slate-900/70 rounded-3xl shadow-2xl backdrop-blur-xl border border-slate-200/60 dark:border-white/10 overflow-hidden flex flex-col md:flex-row">

    <!-- Panel decorativo -->
    <div class="w-full md:w-1/2 bg-gradient-to-br from-indigo-600 to-purple-600 text-white flex flex-col justify-center items-center p-10">
      <h2 class="text-4xl font-bold mb-4">¡Bienvenido!</h2>
      <p class="mb-6 text-lg text-indigo-100">¿Ya tienes una cuenta?</p>
      <a href="login.php" class="px-6 py-3 rounded-xl bg-white text-indigo-700 font-semibold shadow-lg hover:scale-105 transition">
        Iniciar Sesión
      </a>
    </div>

    <!-- Panel formulario -->
    <div class="w-full md:w-1/2 p-10 flex flex-col justify-center">
      <h3 class="text-3xl font-extrabold text-slate-900 dark:text-white mb-6 text-center">Crea tu cuenta</h3>

      <form action="../business/procesarRegistro.php" method="POST" class="space-y-5">
        <!-- Usuario -->
        <div>
          <label for="username" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nombre de Usuario</label>
          <input type="text" name="username" id="username" placeholder="Nombre de Usuario" required
            class="w-full rounded-xl border border-slate-300 bg-white/70 px-4 py-2 text-slate-900 placeholder-slate-400 shadow-sm dark:border-slate-700 dark:bg-slate-800/70 dark:text-white">
        </div>

        <!-- Contraseña -->
        <div>
          <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Contraseña</label>
          <input type="password" name="password" id="password" placeholder="********" required
            class="w-full rounded-xl border border-slate-300 bg-white/70 px-4 py-2 text-slate-900 placeholder-slate-400 shadow-sm dark:border-slate-700 dark:bg-slate-800/70 dark:text-white">
        </div>

        <!-- Confirmar Contraseña -->
        <div>
          <label for="confirm_password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Confirmar Contraseña</label>
          <input type="password" name="confirm_password" id="confirm_password" placeholder="********" required
            class="w-full rounded-xl border border-slate-300 bg-white/70 px-4 py-2 text-slate-900 placeholder-slate-400 shadow-sm dark:border-slate-700 dark:bg-slate-800/70 dark:text-white">
        </div>

        <!-- Botón -->
        <div class="flex justify-center">
          <button type="submit"
            class="w-2/3 py-3 bg-indigo-600 text-white font-semibold rounded-2xl shadow-lg hover:bg-indigo-700 transition">
            🚀 Registrar
          </button>
        </div>
      </form>
    </div>

  </div>
</body>
</html>
