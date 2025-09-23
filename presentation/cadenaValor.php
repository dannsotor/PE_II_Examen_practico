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
    <title>Cadena de Valor</title>
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
    .form-content p {
    text-align: justify;
    }

    /* Si también quieres justificar el texto dentro de .content u otros contenedores */
    .content p {
    text-align: justify;
    }

    /* Opcional: justificar listas o títulos */
    .form-content li {
    text-align: justify;
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
            <h1 style="text-align: center;">Cadena de Valor</h1>
            <div class="content">
                <p>
                    Todas las actividades de una empresa forman la cadena de valor.
                </p>
                <p>
                    La Cadena de Valor es una herramienta que permite a la empresa identificar aquellas actividades o fases que pueden aportarle un mayor valor añadido al producto final. Intenta buscar fuentes de ventaja competitiva.
                </p>
                <p>
                    La empresa está formada por una secuencia de actividades diseñadas para añadir valor al producto o servicio según las distintas fases, hasta que se llega al cliente final.
                </p>
                <p>
                    Una cadena de valor genérica está constituida por tres elementos básicos:
                </p>

                <div class="image">
                    <img src="assets/images/cadenaValor.png" alt="Cadena de Valor">
                </div>

                <p><strong>Las Actividades Primarias</strong> son aquellas que tienen que ver con el producto/servicio, su producción, logística, comercialización, etc.</p>

                <ul>
                    <li> Logística de entrada (recepción, almacenamiento, manipulación de materiales, inspección interna, devoluciones, inventarios, etc.)</li>
                    <li> Operaciones (proceso de fabricación, ensamblaje, mantenimiento de equipos, mecanización, embalaje, etc.)</li>
                    <li> Logística de salida (gestión de pedidos, almacenamiento de producto terminado, transporte, etc.)</li>
                    <li> Marketing y ventas (comercialización, selección del canal de distribución, publicidad, promoción, política de precios, etc.)</li>
                    <li> Servicios (reparación de productos, instalación, mantenimiento, servicios postventa, reclamaciones, reajustes del producto, etc.)</li>
                </ul>

                <p><strong>Las Actividades de Soporte</strong> o apoyo a las actividades primarias son:</p>

                <ul>
                    <li> Infraestructura empresarial (administración, finanzas, contabilidad, calidad, relaciones públicas, asesoría legal, gerencia, etc.)</li>
                    <li> Gestión de los recursos humanos (selección, contratación, formación, incentivos, etc.)</li>
                    <li> Desarrollo tecnológico (telecomunicaciones, automatización, desarrollo de procesos e ingeniería, diseño, etc.)</li>
                    <li> Abastecimiento (compras de materias primas, consumibles, equipos, servicios, etc.)</li>
                </ul>

                <p><strong>El Margen</strong> es la diferencia entre el valor total obtenido y los costes incurridos por la empresa para desempeñar las actividades generadoras de valor.</p>

                <!-- Diagrama del margen -->
                <div class="image">
                    <img src="assets/images/margen.png" alt="Diagrama del Margen">
                </div>

                <p>Cada eslabón de la cadena puede ser fuente de ventaja competitiva, ya sea porque se optimice (excelencia en la ejecución de una actividad) y/o mejore su coordinación con otra actividad.</p>
            
                <!-- Contenedor de los botones -->
                <div class="button-container">
                    <a href="dashboard.php" class="btn-volver">Volver al Dashboard</a>
                    <a href="cadenaValor2.php" class="btn-siguiente">Siguiente</a>
                </div>

            </div>
        </div>

    </div>

    
        <div class="info-content">
            <?php include('aside.php'); ?>
        </div>
</body>
</html>
