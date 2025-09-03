<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login Elegante</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <style>
    @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-12px)} }
    @keyframes spinSlow { from{transform:rotate(0)} to{transform:rotate(360deg)} }
  </style>
</head>
<body x-data="{ dark: window.matchMedia('(prefers-color-scheme: dark)').matches, show: false }" x-init="document.documentElement.classList.toggle('dark', dark)" class="min-h-screen bg-gradient-to-br from-indigo-50 via-sky-50 to-purple-50 dark:from-gray-900 dark:via-slate-900 dark:to-indigo-950 font-[Inter]">
  <!-- Fondo decorativo -->
  <div aria-hidden="true" class="pointer-events-none fixed inset-0 overflow-hidden">
    <div class="absolute -top-20 -left-20 h-72 w-72 rounded-full bg-indigo-300/40 blur-3xl dark:bg-indigo-600/30 animate-[float_8s_ease-in-out_infinite]"></div>
    <div class="absolute -bottom-24 -right-24 h-96 w-96 rounded-full bg-fuchsia-300/30 blur-3xl dark:bg-fuchsia-600/20 animate-[float_10s_ease-in-out_infinite]"></div>
    <div class="absolute left-1/2 top-1/2 -z-0 -translate-x-1/2 -translate-y-1/2 h-[42rem] w-[42rem] rounded-full border border-white/10 dark:border-white/5 animate-[spinSlow_40s_linear_infinite]"></div>
  </div>

  <main class="relative z-10 flex items-center justify-center px-4 py-12 sm:px-6 lg:px-8">
    <div class="mx-auto w-full max-w-md">
      <!-- Card -->
      <div class="relative rounded-3xl border border-slate-200/60 bg-white/70 p-8 shadow-2xl backdrop-blur-xl dark:border-white/10 dark:bg-slate-900/60">
        <!-- Toggle Dark Mode -->
        <button type="button" @click="dark=!dark;document.documentElement.classList.toggle('dark', dark)" class="absolute right-4 top-4 inline-flex items-center gap-2 rounded-full border border-slate-200/60 bg-white/60 px-3 py-1.5 text-xs font-medium text-slate-700 shadow-sm hover:bg-white dark:border-white/10 dark:bg-slate-800/60 dark:text-slate-200">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4">
            <path d="M12 3.1a9 9 0 1 0 9 9 .75.75 0 0 0-1.2-.6 7.5 7.5 0 0 1-9.9-9.9.75.75 0 0 0-.6-1.2z" />
          </svg>
          Modo
        </button>

        <!-- Título -->
        <h2 class="mb-6 text-center text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">Inicia Sesión</h2>

        <!-- Formulario -->
        <form class="space-y-6">
          <div>
            <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Correo electrónico</label>
            <input id="email" name="email" type="email" required placeholder="ejemplo@correo.com" class="mt-1 block w-full rounded-xl border border-slate-300 bg-white/70 px-4 py-2 text-slate-900 placeholder-slate-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-800/70 dark:text-white">
          </div>

          <div x-data="{ show: false }">
            <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Contraseña</label>
            <div class="relative mt-1">
              <input id="password" name="password" :type="show ? 'text' : 'password'" required placeholder="********" class="block w-full rounded-xl border border-slate-300 bg-white/70 px-4 py-2 pr-10 text-slate-900 placeholder-slate-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-800/70 dark:text-white">
              <button type="button" @click="show=!show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.269-2.943-9.543-7a9.956 9.956 0 012.36-3.993M9.88 9.88a3 3 0 104.24 4.24M6.1 6.1l11.8 11.8" />
                </svg>
              </button>
            </div>
          </div>

          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <input id="remember" name="remember" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700">
              <label for="remember" class="ml-2 block text-sm text-slate-700 dark:text-slate-300">Recuérdame</label>
            </div>
            <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">¿Olvidaste tu contraseña?</a>
          </div>

          <button type="submit" class="w-full rounded-xl bg-indigo-600 px-4 py-2 font-semibold text-white shadow-md transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Ingresar</button>
        </form>

        <!-- Registro -->
        <p class="mt-6 text-center text-sm text-slate-600 dark:text-slate-400">
          ¿No tienes cuenta? <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">Regístrate</a>
        </p>
      </div>
    </div>
  </main>
</body>
</html>
