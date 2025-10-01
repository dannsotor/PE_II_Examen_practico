<?php
require_once('neonConnection.php');

class AnalisisPest
{
    // Función para actualizar el análisis PEST en un plan existente
    public function actualizarPest($planId, $pestResultados)
    {
        $idusuario = $_SESSION['idusuario'];  // idusuario en la sesión

        $db = new Conexion();
        $conn = $db->getConnection();
    
        // Consulta SQL para actualizar el análisis PEST y las conclusiones en el plan
        $sql = "UPDATE plan SET pest = ? WHERE idplan = ? AND idusuario = ?";
    
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error al preparar la consulta SQL: " . $conn->errorInfo());
        }
    
        // Bind de los valores
        $stmt->bindValue(1, $pestResultados, PDO::PARAM_STR);
        $stmt->bindValue(2, $planId, PDO::PARAM_INT);
        $stmt->bindValue(3, $idusuario, PDO::PARAM_INT);
    
        // Ejecutar la consulta
        $resultado = $stmt->execute();
    
        // Verificar si la actualización fue exitosa
        if ($resultado) {
            return true; // Si la actualización fue exitosa
        } else {
            return false; // Si hubo un error
        }
    
        // Cerrar el cursor y la conexión
        $stmt->closeCursor();
        $conn = null;
    }

    // Función para obtener los resultados del análisis PEST de un plan
    public function obtenerPest($idPlan)
    {
        try {
            // Conexión a la base de datos
            $db = new Conexion();
            $conn = $db->getConnection();
    
            // Preparar la consulta SQL para obtener el autodiagnóstico del plan
            $query = "SELECT pest FROM plan WHERE idplan = :idPlan";
            $stmt = $conn->prepare($query);
    
            // Asignar el valor al parámetro
            $stmt->bindParam(':idPlan', $idPlan);
    
            // Ejecutar la consulta
            $stmt->execute();
    
            // Obtener el resultado
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // Retornar el autodiagnóstico si se encuentra, si no, retorna null
            return $resultado ? $resultado['pest'] : null;
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error: " . $e->getMessage();
            return null;
        }
    }
    
    public function guardarConclusiones($planId, $conclusiones)
    {
        try {
            // Obtener el ID de usuario de la sesión
            $idusuario = $_SESSION['idusuario'];  
    
            // Crear la conexión a la base de datos
            $db = new Conexion();
            $conn = $db->getConnection();
    
            // Consulta SQL para actualizar las conclusiones en la tabla `plan`
            $sql = "UPDATE plan 
                    SET conclusioneconomico = :conclusioneconomico,
                        conclusionpolitico = :conclusionpolitico,
                        conclusionsocial = :conclusionsocial,
                        conclusiontecnologico = :conclusiontecnologico,
                        conclusionambiental = :conclusionambiental
                    WHERE idplan = :idPlan AND idusuario = :idUsuario";
    
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                throw new Exception("Error al preparar la consulta SQL: " . implode(" ", $conn->errorInfo()));
            }
    
            // Bind de los parámetros con los valores proporcionados
            $stmt->bindValue(':conclusioneconomico', $conclusiones['conclusioneconomico'], PDO::PARAM_STR);
            $stmt->bindValue(':conclusionpolitico', $conclusiones['conclusionpolitico'], PDO::PARAM_STR);
            $stmt->bindValue(':conclusionsocial', $conclusiones['conclusionsocial'], PDO::PARAM_STR);
            $stmt->bindValue(':conclusiontecnologico', $conclusiones['conclusiontecnologico'], PDO::PARAM_STR);
            $stmt->bindValue(':conclusionambiental', $conclusiones['conclusionambiental'], PDO::PARAM_STR);
            $stmt->bindValue(':idPlan', $planId, PDO::PARAM_INT);
            $stmt->bindValue(':idUsuario', $idusuario, PDO::PARAM_INT);
    
            // Ejecutar la consulta
            $resultado = $stmt->execute();
    
            // Verificar si se actualizó al menos una fila
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false; // No se actualizó ningún registro
            }
        } catch (PDOException $e) {
            // Manejo de errores
            error_log("Error en guardarConclusiones: " . $e->getMessage());
            return false;
        } finally {
            // Cerrar el cursor y la conexión
            if (isset($stmt)) {
                $stmt->closeCursor();
            }
            $conn = null;
        }
    }
        // Función para guardar los puntajes en la tabla `plan`
        public function guardarPuntajes($planId, $puntajes)
        {
            try {
                // Obtener el ID de usuario de la sesión
                $idusuario = $_SESSION['idusuario'];  
            
                // Crear la conexión a la base de datos
                $db = new Conexion();
                $conn = $db->getConnection();
            
                // Consulta SQL para actualizar los puntajes en la tabla `plan`
                $sql = "UPDATE plan 
                        SET puntajesocial = :puntajesocial,
                            puntajeambiental = :puntajeambiental,
                            puntajepolitico = :puntajepolitico,
                            puntajeeconomico = :puntajeeconomico,
                            puntajetecnologico = :puntajetecnologico
                        WHERE idplan = :idPlan AND idusuario = :idUsuario";
            
                $stmt = $conn->prepare($sql);
                if ($stmt === false) {
                    throw new Exception("Error al preparar la consulta SQL: " . implode(" ", $conn->errorInfo()));
                }
            
                // Bind de los parámetros con los valores proporcionados
                $stmt->bindValue(':puntajesocial', $puntajes['puntajesocial'], PDO::PARAM_INT);
                $stmt->bindValue(':puntajeambiental', $puntajes['puntajeambiental'], PDO::PARAM_INT);
                $stmt->bindValue(':puntajepolitico', $puntajes['puntajepolitico'], PDO::PARAM_INT);
                $stmt->bindValue(':puntajeeconomico', $puntajes['puntajeeconomico'], PDO::PARAM_INT);
                $stmt->bindValue(':puntajetecnologico', $puntajes['puntajetecnologico'], PDO::PARAM_INT);
                $stmt->bindValue(':idPlan', $planId, PDO::PARAM_INT);
                $stmt->bindValue(':idUsuario', $idusuario, PDO::PARAM_INT);
            
                // Ejecutar la consulta
                $resultado = $stmt->execute();
            
                // Verificar si se actualizó al menos una fila
                if ($stmt->rowCount() > 0) {
                    return true;
                } else {
                    return false;
                }
            } catch (PDOException $e) {
                // Manejo de errores
                echo "Error: " . $e->getMessage();
                return false;
            }
        } 
        
    // Función para guardar las oportunidades y amenazas
    public function guardarOportunidadesAmenazas($idPlan, $oportunidades, $amenazas) {
        $db = new Conexion();
        $conn = $db->getConnection();

        $sql = "UPDATE plan SET oportunidades = ?, amenazas = ? WHERE idplan = ?";
        
        // Preparar la declaración
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error al preparar la consulta SQL: " . $conn->errorInfo());
        }
    
        // Vincular los parámetros
        $stmt->bindValue(1, $oportunidades, PDO::PARAM_STR);
        $stmt->bindValue(2, $amenazas, PDO::PARAM_STR);
        $stmt->bindValue(3, $idPlan, PDO::PARAM_INT);
    
        // Ejecutar la consulta
        $resultado = $stmt->execute();
        $stmt->closeCursor(); // Cerrar el cursor
        $conn = null; // Cerrar la conexión
    
        return $resultado; // Retornar el resultado de la ejecución
    }

    public function obtenerOportunidadesAmenazas($idPlan) {

        $db = new Conexion();
        $conn = $db->getConnection();

        $sql = "SELECT oportunidades, amenazas FROM plan WHERE idPlan = :idPlan";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':idPlan', $idPlan, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);  // Devuelve un array con 'oportunidades' y 'amenazas'
    }
}
?>