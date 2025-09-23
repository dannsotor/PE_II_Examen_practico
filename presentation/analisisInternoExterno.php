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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Análisis Interno y Externo</title>
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
      padding: 12px;
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

    /* Modo oscuro jejeje*/
    @media (prefers-color-scheme: dark) {
      body { background-color: #0f172a; color: #e2e8f0; }
      h1 { color: #c7d2fe; }
      .form-content { background-color: #1e293b; border-color: #334155; }
      textarea { background-color: #1e293b; border-color: #334155; color: #f1f5f9; }
      .info-content a { background-color: #6366f1; }
      .info-content a:hover { background-color: #4f46e5; }
    }
      .objetivos-form {
    max-width: 800px;
    margin: 2rem auto;
    padding: 2rem;
    background-color: #ffffff;
    border-radius: 12px;
    border: 1px solid #d1d5db;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    font-family: 'Segoe UI', Tahoma, sans-serif;
    color: #1e293b;
  }

  .objetivos-form h2 {
    color: #4338ca;
    text-align: center;
    font-weight: 700;
    margin-bottom: 1.5rem;
  }

  .objetivos-form label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: #334155;
  }

  .objetivos-form textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    font-size: 15px;
    font-family: 'Segoe UI', sans-serif;
    resize: vertical;
    transition: border-color .2s ease, box-shadow .2s ease;
    background-color: #f8fafc;
  }

  .objetivos-form textarea:focus {
    border-color: #4338ca;
    box-shadow: 0 0 0 2px #c7d2fe;
    outline: none;
  }

  @media (prefers-color-scheme: dark) {
    .objetivos-form {
      background-color: #1e293b;
      border-color: #334155;
      color: #e2e8f0;
    }
    .objetivos-form label {
      color: #cbd5e1;
    }
    .objetivos-form textarea {
      background-color: #1e293b;
      border-color: #475569;
      color: #f1f5f9;
    }
    .form-content .image img {
  display: block;
  max-width: 100%;
  height: auto;
  margin: 1rem auto;
  border-radius: 8px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

  </style>
</head>
<body>

    <div class="container2">
        <div class="form-content">
            <h1 style="text-align: center;">Análisis Interno y Externo</h1>
            <div class="content">
                <p>
                    Fijados los objetivos estratégicos se debe analizar las distintas estrategias para lograrlos.
                    De esta forma, las estrategias son los caminos, vías o enfoques para alcanzar los objetivos. Responden a la pregunta <strong>¿cómo?</strong>.
                </p>
                <p>
                    Para determinar la estrategia, podríamos basarnos en el conjunto de estrategias genéricas y específicas que diferentes profesionales proponen al respecto.
                    Esta guía, lejos de rozar la teoría, propone llevar a cabo un análisis interno y externo de su empresa para obtener una matriz cruzada y identificar la estrategia más conveniente a seguir.
                    Este análisis le permitirá detectar por un lado los factores de éxito (fortalezas y oportunidades), y por otro lado, las debilidades y amenazas que una empresa debe gestionar.
                </p>

                <div class="image">
                    <img src="assets/images/analisisExterno.png" alt="Diagrama FODA" class="image-external">
                </div>

                <div class="image">
                    <img src="assets/images/analisisInterno.png" alt="Diagrama FODA">
                </div>

                <h3>Oportunidades:</h3>
                <p>
                    Aquellos aspectos que pueden presentar una posibilidad para mejorar la rentabilidad de la empresa, aumentar la cifra de negocio y fortalecer la ventaja competitiva.
                </p>
                <p>
                    <strong>Ejemplos:</strong> Fuerte crecimiento, desarrollo de la externalización, nuevas tecnologías, seguridad de la distribución, atender a grupos adicionales de clientes, crecimiento rápido del mercado, etc.
                </p>

                <h3>Amenazas:</h3>
                <p>
                    Son fuerzas y presiones del mercado-entorno que pueden impedir y dificultar el crecimiento de la empresa, la ejecución de la estrategia, reducir su eficacia o incrementar los riesgos en relación con el entorno y sector de actividad.
                </p>
                <p>
                    <strong>Ejemplos:</strong> Competencia en el mercado, aparición de nuevos competidores, reglamentación, monopolio en una materia prima, cambio en las necesidades de los consumidores, creciente poder de negociación de clientes y/o proveedores, etc.
                </p>

                <h3>Fortalezas:</h3>
                <p>
                    Son capacidades, recursos, posiciones alcanzadas, ventajas competitivas que posee la empresa y que le ayudarán a aprovechar las oportunidades del mercado.
                </p>
                <p>
                    <strong>Ejemplos:</strong> Buena implantación en el territorio, notoriedad de la marca, capacidad de innovación, recursos financieros adecuados, ventajas en costes, líder en el mercado, buena imagen entre los consumidores, etc.
                </p>

                <h3>Debilidades:</h3>
                <p>
                    Son todos aquellos aspectos que limitan o reducen la capacidad de desarrollo de la empresa. Constituyen dificultades para la organización y deben, por tanto, ser controladas y superadas.
                </p>
                <p>
                    <strong>Ejemplos:</strong> Precios elevados, productos en el final de su ciclo de vida, deficiente control de riesgos, recursos humanos poco cualificados, débil imagen en el mercado, red de distribución débil, no hay dirección estratégica clara, etc.
                </p>

                <h3>Análisis FODA</h3>
                <p>
                    Para elaborar el análisis FODA de su empresa, le proponemos que utilice distintos instrumentos para el análisis tanto interno como externo.
                </p>

                <div class="image">
                    <img src="assets/images/FODA.png" alt="Diagrama FODA">
                </div>
                
            </div>

            <!-- Contenedor de los botones -->
            <div class="button-container">
                <a href="dashboard.php" class="btn-volver">Volver al Dashboard</a>
                <a href="cadenaValor.php" class="btn-siguiente">Siguiente</a>
            </div>

        </div>

        <div class="info-content">
            <?php include('aside.php'); ?>
        </div>
        
    </div>

</body>
</html>
