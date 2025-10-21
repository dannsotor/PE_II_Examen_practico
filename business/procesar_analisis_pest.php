<?php
// Iniciar sesión para obtener el idPlan
session_start();

// Verificar si se recibieron datos del formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Asegurarse de que idPlan esté en la sesión
    if (!isset($_SESSION['idPlan'])) {
        die("ID de plan no encontrado en la sesión.");
    }

    $idPlan = $_SESSION['idPlan'];

    // Incluir el archivo donde se encuentran las funciones de actualización
    require_once('../data/analisis_pest.php');

    // Instanciar la clase AnalisisPest
    $pest = new AnalisisPest();

    // Comprobar si se está guardando el análisis o las conclusiones
    if (isset($_POST['guardarAnalisis'])) {
        // Recoger las respuestas del formulario
        $respuestas = [];
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'respuesta_') === 0) {
                $respuestas[] = $value;
            }
        }

        // Generar el string para el análisis PEST
        $pestResultados = implode(',', $respuestas);

        // Llamar a la función que actualiza o inserta el análisis PEST
        $resultado = $pest->actualizarPest($idPlan, $pestResultados);

        // Verificar el resultado
        if ($resultado) {
            // Redirigir a la página de presentación de pest.php
            header("Location: ../presentation/pest.php");
            exit; // Asegúrate de terminar el script después de la redirección
        } else {
            // Manejar el error si algo salió mal
            echo "Error al actualizar el análisis PEST.";
        }
    } elseif (isset($_POST['guardarConclusiones'])) {
        // Recoger las conclusiones generadas en el formulario
        $conclusiones = [
            'conclusioneconomico' => $_POST['conclusion_economico'] ?? '',
            'conclusionpolitico' => $_POST['conclusion_politico'] ?? '',
            'conclusionsocial' => $_POST['conclusion_social'] ?? '',
            'conclusiontecnologico' => $_POST['conclusion_tecnologico'] ?? '',
            'conclusionambiental' => $_POST['conclusion_ambiental'] ?? ''
        ];

        // Llamar a la función que guarda las conclusiones en la tabla plan
        $resultado = $pest->guardarConclusiones($idPlan, $conclusiones);

        // Verificar el resultado
        if ($resultado) {
            // Redirigir a la página de presentación de pest.php
            header("Location: ../presentation/pest.php");
            exit; // Asegúrate de terminar el script después de la redirección
        } else {
            // Manejar el error si algo salió mal
            echo "Error al guardar las conclusiones.";
        }
    }

    elseif (isset($_POST['guardarOportunidadesAmenazas'])) {
        $oportunidades = $_POST['oportunidades'] ?? '';
        $amenazas = $_POST['amenazas'] ?? '';
    
        $resultado = $pest->guardarOportunidadesAmenazas($idPlan, $oportunidades, $amenazas);
    
        if ($resultado) {
            header("Location: ../presentation/pest.php");
            exit;
        } else {
            echo "Error al guardar las oportunidades y amenazas.";
        }
    }
    
    elseif (isset($_POST['guardarPuntajes'])) {
        // Recoger los puntajes generados en el formulario
        $puntajes = [
            'puntajesocial' => $_POST['puntaje_social'] ?? 0,
            'puntajeambiental' => $_POST['puntaje_ambiental'] ?? 0,
            'puntajepolitico' => $_POST['puntaje_politico'] ?? 0,
            'puntajeeconomico' => $_POST['puntaje_economico'] ?? 0,
            'puntajetecnologico' => $_POST['puntaje_tecnologico'] ?? 0
        ];

        // Llamar a la función que actualiza los puntajes en la tabla plan
        $resultado = $pest->guardarPuntajes($idPlan, $puntajes);

        // Verificar el resultado
        if ($resultado) {
            // Redirigir a la página de presentación de pest.php
            header("Location: ../presentation/pest.php");
            exit; // Asegúrate de terminar el script después de la redirección
        } else {
            // Manejar el error si algo salió mal
            echo "Error al guardar los puntajes.";
        }
    }   else {
        // Si no se reconoció ninguna acción, mostrar un error
        echo "Acción no reconocida.";
    }
} else {
    // Si el formulario no fue enviado, redirigir o mostrar un error
    echo "No se recibieron datos del formulario.";
}
?>
