<?php

require_once('../data/plan.php'); // Asegúrsdate de que esto apunte al archivo correcto donde tienes la clase PlanData

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    
    if (!isset($_SESSION['idPlan'])) {
        die("ID de plan no encontrado en la sesión.");
    }

    $idPlan = $_SESSION['idPlan'];

    // Instancia de la clase que maneja los planes
    $planData = new PlanData();

    // Procesar autovalores si se hace clic en "Realizar Autoevaluación"
    if (isset($_POST['guardarEvaluacionPorter'])) {
        $valoresporter = [];
        $suma = 0; // Variable para sumar todos los valores

        // Recorremos los 17 puntos de evaluación
        for ($i = 1; $i <= 17; $i++) {
            if (isset($_POST["punto_$i"])) {
                // Almacenamos el valor seleccionado (0, 1, 2, 3 o 4)
                $valor = $_POST["punto_$i"];
                $valoresporter[] = $valor;
                $suma += $valor; // Sumar el valor seleccionado
            } else {
                // En caso de que no se haya seleccionado nada, almacenamos 0 por defecto
                $valoresporter[] = 0;
            }
        }

        // Convertir el array de autovalores en una cadena separada por comas
        $nuevovalorPorter = implode(",", $valoresporter);

        // Llamar a la función para actualizar el valor en la base de datos
        $resultado = $planData->actualizarValorPorter($idPlan, $nuevovalorPorter);
        $oportunidades = $_POST['oportunidades'];
        $amenazas = $_POST['amenazas'];

        // Llamar a las funciones para actualizar reflexiones, oportunidades y debilidades
        $resultadoOportunidades = $planData->actualizarOportunidades($idPlan, $oportunidades);
        $resultadoAmenazas = $planData->actualizarAmenazas($idPlan, $amenazas);
        
        if ($resultado) {
            header("Location: ../presentation/matrizPorter2.php");
            exit;
        } else {
            echo "Error al actualizar la evaluación porter.";
        }
    }

    
} else {
    die("Solicitud no válida.");
}
