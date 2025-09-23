<?php
$errorMessage = '';
if (isset($_GET['error'])) {
    if ($_GET['error'] === 'empty') {
        $errorMessage = 'Por favor, complete todos los campos.';
    } elseif ($_GET['error'] === 'invalid') {
        $errorMessage = 'Nombre de usuario o contraseña incorrectos.';
    }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Iniciar Sesión - PETI</title>
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

  <!-- Card -->
  <div class="relative z-10 max-w-4xl w-full bg-white/80 dark:bg-slate-900/70 rounded-3xl shadow-2xl backdrop-blur-xl border border-slate-200/60 dark:border-white/10 overflow-hidden flex flex-col md:flex-row">
    
    <!-- Panel izquierdo -->
    <div class="w-full md:w-1/2 bg-gradient-to-br from-indigo-600 to-purple-600 text-white flex flex-col justify-center items-center p-10">
      <h2 class="text-4xl font-bold mb-4">¡Bienvenido!</h2>
      <p class="mb-6 text-lg text-indigo-100">¿No tienes una cuenta?</p>
      <a href="registro.php" class="px-6 py-3 rounded-xl bg-white text-indigo-700 font-semibold shadow-lg hover:scale-105 transition">
        Regístrate
      </a>
    </div>

    <!-- Panel derecho -->
    <div class="w-full md:w-1/2 p-10">
      <h3 class="text-3xl font-extrabold text-slate-900 dark:text-white mb-6">Iniciar Sesión</h3>
      
      <form action="../business/procesarLogin.php" method="POST" class="space-y-5">
        <!-- Usuario -->
        <div class="flex items-center space-x-4">
          <label for="username" class="w-1/3 text-sm font-medium text-slate-700 dark:text-slate-300 text-right">Usuario</label>
          <input type="text" name="username" id="username" placeholder="Tu usuario" required
            class="w-2/3 rounded-xl border border-slate-300 bg-white/70 px-4 py-2 text-slate-900 placeholder-slate-400 shadow-sm dark:border-slate-700 dark:bg-slate-800/70 dark:text-white">
        </div>
        
        <!-- Contraseña -->
        <div class="flex items-center space-x-4">
          <label for="password" class="w-1/3 text-sm font-medium text-slate-700 dark:text-slate-300 text-right">Contraseña</label>
          <input type="password" name="password" id="password" placeholder="********" required
            class="w-2/3 rounded-xl border border-slate-300 bg-white/70 px-4 py-2 text-slate-900 placeholder-slate-400 shadow-sm dark:border-slate-700 dark:bg-slate-800/70 dark:text-white">
        </div>

        <!-- Botón -->
        <div class="flex justify-end">
          <button type="submit"
            class="w-1/2 py-3 bg-indigo-600 text-white font-semibold rounded-2xl shadow-lg hover:bg-indigo-700 transition">
            🚀 Iniciar Sesión
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const error = "<?php echo $errorMessage; ?>";
      if (error) {
        // Modal usando Tailwind simple (puedes sustituir por Flowbite, SweetAlert, etc.)
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black/50 flex items-center justify-center z-50';
        modal.innerHTML = `
          <div class="bg-white rounded-xl shadow-xl max-w-sm w-full p-6 text-center space-y-4">
            <h2 class="text-xl font-bold text-red-600">Error</h2>
            <p class="text-gray-700">${error}</p>
            <button id="closeModal" class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
              Cerrar
            </button>
          </div>`;
        document.body.appendChild(modal);

        document.getElementById('closeModal').addEventListener('click', () => {
          modal.remove();
        });
      }
    });
  </script>
</body>
</html>
