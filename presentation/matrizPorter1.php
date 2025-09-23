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
    <title>Analisis Externo Microentorno</title>
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
    .btn-volver,
.btn-siguiente {
  text-decoration: none; /* Quita el subrayado */
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
            <h1 style="text-align: center;">Analisis Externo Microentorno: Matriz de Porter</h1>
            <div class="content">
                <p>
                El Modelo de las 5 Fuerzas de Porter estudia un determinado negocio en función de la amenaza de nuevos competidores y productos sustituivos, así como el poder de negociación de los proveedores y clientes, teniendo en cuenta el grado de competencia del sector. Esto proporciona una clara imagen de la situación competitiva de un mercado en concreto. El conjunto de las cinco fuerzas determina la intensidad competitiva, la rentabilidad del sector y, de forma derivada, las posibilidades futuras de éste. Por ejemplo, si un sector está obteniendo rendimientos escasos, es dudoso que disponga de recursos para financiar el desarrollo de productos sustitutivos dentro del mismo sector. 
                </p>

                <div class="image">
                    <img src="assets/images/modeloPorter.png" alt="Modelo Porter" class="image-external">
                </div>

                <p>
                Pasemos a repasar de forma abreviada como funciona cada una de las cinco fuerzas.
                </p>


                <h3>Amenaza de nuevos entrantesr</h3>
                <p>
                La aparición de nuevas empresas en el sector supone un incremento de recursos, de capacidad y, en principio, un intento de obtener una participación en el mercado a costa de otros que ya la tenían. La posibilidad de entrar en un sector depende fundamentalmente de dos factores: la capacidad de reacción de las empresas que ya están (tecnológica, financiera, productiva, etc.) y las denominadas barreras de entrada (obstáculos para el ingreso). Entre las barreras de entrada, las más características son:
 Economía de escala. Reducción de costes unitarios debido al volumen (vinculada a menudo a reducciones por efecto experiencia), como por ejemplo, coches, aviones…
 Grado de diferenciación del producto/servicio. La fidelidad de los clientes obliga a realizar inversiones muy grandes (y arriesgadas) para desalojar al suministrador tradicional. Crítico en los mercados en los que la confianza es fundamental (bancos, farmacéuticas, etc.)
 Necesidades de capital. Las necesidades de capital, especialmente cuando éste tiene que ser desembolsado inicialmente o su recuperación, en caso de fallo, es difícil, constituye una barrera muy importante (coches, acero, etc.)
 Costes de cambio. Existen multitud de productos y servicios en los que el comprador tiene que asumir un coste extra si quiere cambiar de proveedor, principalmente por aspectos logísticos (entrenamiento, repuestos, almacenes, etc.)                                                                                                             Acceso a los canales de distribución. El control de los canales de distribución puede dificultar seriamente el acceso a un mercado. El canal puede cargar sobreprecios y los competidores bajar los suyos.
Otros factores. Dentro de este apartado podemos incluir las patentes, el acceso privilegiado a materias primas, la ubicación, las ayudas gubernamentales, etc.
                </p>
                

                <h3>Rivalidad de los competidores</h3>
                <p>
                La rivalidad aparece cuando uno o varios competidores sienten la presión o ven la oportunidad de mejorar. El grado de rivalidad depende de una serie de factores estructurales, entre los que podemos destacar:
 Gran número de competidores, o competidores muy equilibrados.
 Crecimiento lento en el mercado. Cuando los mercados se estancan, la única forma de mejorar los resultados propios es arrebatar cuota a la competencia
 Costes fijos o de almacenamiento elevados. Al darse esa situación, es necesario hacer un gran esfuerzo para operar a plena capacidad, o al menos por encima del punto muerto.
 Baja diferenciación de productos. El consumidor se ve atraído por el precio, y los competidores tenderán a bajarlo.
 Intereses estratégicos. En determinados mercados, puede ocurrir que varias empresas importantes intenten, de forma simultánea, establecer una posición sólida y utilicen para ello recursos desproporcionados.
 Barreras de salida. Cuando los competidores tienen dificultades para salir de un mercado que ha perdido interés, mantendrán una intensidad competitiva alta, si las barreras de salida son importantes. Entre las barreras de salida podemos destacar los activos especializados, los costes fijos de salida, las restricciones sociales o las barreras emocionales.
                </p>

                <h3>Presión de los productos sustitutivos</h3>
                <p>
                El nivel de precio/calidad de los productos sustitutivos limita el nivel de precios de la industria. Los productos sustitutivos pueden ser fabricados por empresas pertenecientes o ajenas al sector (situación peligrosa). Las empresas del sector pueden reaccionar en bloque, no hacerlo en absoluto, o cambiar de necesidad satisfecha adaptando el producto (un crucero no puede competir con el avión en el transporte de viajeros, pero es un medio de vacaciones de lujo inigualable). Desde la óptica estratégica, hay que prestar mucha atención a los “sustitutivos no evidentes” (ejemplo, videoconferencia contra hotel más avión).
                </p>
                <p>
                    <strong>Ejemplos:</strong> Buena implantación en el territorio, notoriedad de la marca, capacidad de innovación, recursos financieros adecuados, ventajas en costes, líder en el mercado, buena imagen entre los consumidores, etc.
                </p>

                <h3>Poder de negociación de los compradores/clientes</h3>
                <p>
                Los compradores fuerzan los precios a la baja y la calidad al alza, en perjuicio del beneficio de la industria. Su poder aumenta si:
Están concentrados, o compran grandes volúmenes relativos
El coste de la materia prima es importante
Los productos no son diferenciados
El coste de cambiar de proveedor es pequeño
No hay amenaza de integración
Tienen información total
La calidad no es importante
                </p>
                

                <h3>Poder de negociación de los proveedores</h3>
                <p>
                 Los proveedores poderosos pueden amenazar con subir los precios y/o disminuir la calidad. Las empresas del sector pueden ver disminuidos sus beneficios si no consiguen repercutir los incrementos al consumidor final. Su poder aumenta si:
Está más concentrado que el sector que compra
No están obligados a competir con sustitutivos
El comprador no es cliente importante
El producto es importante para el comprador
El producto está diferenciado
Representan una amenaza de integración
                </p>

                <p>
                    <strong>Según Porter, estas fuerzas se encuentran en interacción y cambio permanente. Nuestro objetivo será situar a nuestra empresa en una posición en la que se pueda defender de las amenazas que las fuerzas competitivas plantean.
                    </strong> 
                </p>

                
            </div>

            <!-- Contenedor de los botones -->
            <div class="button-container">
                <a href="dashboard.php" class="btn-volver">Volver al Dashboard</a>
                <a href="matrizPorter2.php" class="btn-siguiente">Siguiente</a>
            </div>

        </div>

        <div class="info-content">
            <?php include('aside.php'); ?>
        </div>
        
    </div>

</body>
</html>
