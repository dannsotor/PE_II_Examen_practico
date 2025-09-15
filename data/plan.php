<?php
require_once('neonConnection.php');

class PlanData
{
    public function crearPlan($nombreEmpresa, $fecha, $promotores, $logo)
    {
        // Iniciar la sesión para obtener el idusuario
        session_start();  
        $idusuario = $_SESSION['idusuario'];  // idusuario en la sesión
    
        $db = new Conexion();
        $conn = $db->getConnection();
    
        // Consulta SQL para insertar el nuevo plan
        $sql = "INSERT INTO plan (idusuario, nombreempresa, fecha, promotores, logo) VALUES (?, ?, ?, ?, ?)";
    
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error al preparar la consulta SQL: " . $conn->errorInfo());
        }
    
        // Bind de los valores
        $stmt->bindValue(1, $idusuario, PDO::PARAM_INT);
        $stmt->bindValue(2, $nombreEmpresa, PDO::PARAM_STR);
        $stmt->bindValue(3, $fecha, PDO::PARAM_STR);
        $stmt->bindValue(4, $promotores, PDO::PARAM_STR);
        $stmt->bindValue(5, $logo, PDO::PARAM_STR);
    
        // Ejecutar la consulta
        $resultado = $stmt->execute();
    
        // Obtener la ID del plan recién creado si la inserción fue exitosa
        if ($resultado) {
            // Devolver el ID del último plan insertado
            $lastId = $conn->lastInsertId();
        } else {
            $lastId = false; // Si no se inserta, devolver false
        }
    
        // Cerrar el cursor y la conexión
        $stmt->closeCursor();
        $conn = null;
    
        return $lastId; // Devuelve la ID del plan creado o false si falla
    }    

    function obtenerPlanesPorUsuario($idusuario) {
        // Crear una instancia de la conexión a la base de datos
        $conexion = new Conexion();
        $conn = $conexion->getConnection(); 
    
        // Preparar la consulta SQL para obtener los planes del usuario
        $sql = "SELECT idplan, nombreempresa, logo FROM plan WHERE idusuario = :idusuario";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':idusuario', $idusuario, PDO::PARAM_INT);
    
        // Ejecutar la consulta
        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    public function obtenerMisionPorId($idPlan) {
        $conexion = new Conexion();
        $conn = $conexion->getConnection();
    
        $sql = "SELECT mision FROM plan WHERE idPlan = :idPlan"; // Ajusta según tu tabla
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':idPlan', $idPlan, PDO::PARAM_INT);
    
        if ($stmt->execute()) {
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado ? $resultado['mision'] : null;
        }
        return null;
    }
    
    public function obtenerVisionPorId($idPlan) {
        $conexion = new Conexion();
        $conn = $conexion->getConnection();
    
        $sql = "SELECT vision FROM plan WHERE idPlan = :idPlan"; // Ajusta según tu tabla
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':idPlan', $idPlan, PDO::PARAM_INT);
    
        if ($stmt->execute()) {
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado ? $resultado['vision'] : null;
        }
        return null;
    }

    public function obtenerPlanPorId($idplan, $idusuario) {
        $conn = new Conexion();
        $db = $conn->getConnection();
    
        $sql = "SELECT * FROM plan WHERE idplan = ? AND idusuario = ?";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $idplan, PDO::PARAM_INT);
        $stmt->bindValue(2, $idusuario, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarMision($idPlan, $nuevaMision)
    {
        // Conexión a la base de datos
        $db = new Conexion();
        $conn = $db->getConnection();
    
        // Preparar la consulta SQL para actualizar la misión
        $sql = "UPDATE plan SET mision = ? WHERE idplan = ?";
    
        // Preparar la declaración
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error al preparar la consulta SQL: " . $conn->errorInfo());
        }
    
        // Vincular los parámetros
        $stmt->bindValue(1, $nuevaMision, PDO::PARAM_STR);
        $stmt->bindValue(2, $idPlan, PDO::PARAM_INT);
    
        // Ejecutar la consulta
        $resultado = $stmt->execute();
        $stmt->closeCursor(); // Cerrar el cursor
        $conn = null; // Cerrar la conexión
    
        return $resultado; // Retornar el resultado de la ejecución
    }

    public function actualizarVision($idPlan, $nuevaVision)
    {
        // Conexión a la base de datos
        $db = new Conexion();
        $conn = $db->getConnection();
    
        // Preparar la consulta SQL para actualizar la visión
        $sql = "UPDATE plan SET vision = ? WHERE idplan = ?";
    
        // Preparar la declaración
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error al preparar la consulta SQL: " . $conn->errorInfo());
        }
    
        // Vincular los parámetros
        $stmt->bindValue(1, $nuevaVision, PDO::PARAM_STR);
        $stmt->bindValue(2, $idPlan, PDO::PARAM_INT);
    
        // Ejecutar la consulta
        $resultado = $stmt->execute();
        $stmt->closeCursor(); // Cerrar el cursor
        $conn = null; // Cerrar la conexión
    
        return $resultado; // Retornar el resultado de la ejecución
    }

    public function actualizarPlan($idPlan, $nombreEmpresa, $fecha, $promotores, $logo = null)
    {
        $db = new Conexion();
        $conn = $db->getConnection();
        
        // Construir la consulta SQL
        $sql = "UPDATE plan SET nombreempresa = ?, fecha = ?, promotores = ?" . ($logo ? ", logo = ?" : "") . " WHERE idplan = ?";

        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error al preparar la consulta SQL: " . $conn->errorInfo());
        }

        // Asignar valores a los parámetros
        $stmt->bindValue(1, $nombreEmpresa, PDO::PARAM_STR);
        $stmt->bindValue(2, $fecha, PDO::PARAM_STR);
        $stmt->bindValue(3, $promotores, PDO::PARAM_STR);
        
        if ($logo) {
            $stmt->bindValue(4, $logo, PDO::PARAM_STR);
            $stmt->bindValue(5, $idPlan, PDO::PARAM_INT);
        } else {
            $stmt->bindValue(4, $idPlan, PDO::PARAM_INT);
        }

        // Ejecutar la consulta
        $resultado = $stmt->execute();
        $stmt->closeCursor();
        $conn = null;

        return $resultado;
    }

    public function actualizarValores($idPlan, $nuevosValores) {
        try {
            // Conexión a la base de datos
            $db = new Conexion();
            $conn = $db->getConnection();
    
            // Preparar la consulta SQL para actualizar los valores del plan
            $query = "UPDATE plan SET valores = :valores WHERE idplan = :idPlan";
    
            $stmt = $conn->prepare($query);
    
            // Asignar los valores a los parámetros
            $stmt->bindParam(':valores', $nuevosValores);
            $stmt->bindParam(':idPlan', $idPlan);
    
            // Ejecutar la consulta
            if ($stmt->execute()) {
                return true; // Actualización exitosa
            } else {
                return false; // Fallo en la actualización
            }
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    public function actualizarObjetivos($idPlan, $objetivosGenerales, $objetivosEspecificos) {
        try {
            // Conexión a la base de datos
            $db = new Conexion();
            $conn = $db->getConnection();
    
            // Preparar la consulta SQL para actualizar los objetivos del plan
            $query = "UPDATE plan SET objetivosgenerales = :objetivosGenerales, objetivosespecificos = :objetivosEspecificos WHERE idplan = :idPlan";
    
            $stmt = $conn->prepare($query);
    
            // Asignar los valores a los parámetros
            $stmt->bindParam(':objetivosGenerales', $objetivosGenerales);
            $stmt->bindParam(':objetivosEspecificos', $objetivosEspecificos);
            $stmt->bindParam(':idPlan', $idPlan);
    
            // Ejecutar la consulta
            if ($stmt->execute()) {
                return true; // Actualización exitosa
            } else {
                return false; // Fallo en la actualización
            }
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error: " . $e->getMessage();
            return false;
        }
    }    

    public function obtenerObjetivosPorId($idPlan) {
        try {
            // Conexión a la base de datos
            $db = new Conexion();
            $conn = $db->getConnection();
    
            // Preparar la consulta SQL para obtener los objetivos del plan
            $query = "SELECT objetivosgenerales, objetivosespecificos FROM plan WHERE idplan = :idPlan";
            $stmt = $conn->prepare($query);
    
            // Asignar el valor al parámetro
            $stmt->bindParam(':idPlan', $idPlan);
    
            // Ejecutar la consulta
            $stmt->execute();
    
            // Obtener el resultado
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // Retornar los objetivos como un arreglo si se encuentran, si no, retorna null
            return $resultado ? $resultado : null;
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error: " . $e->getMessage();
            return null;
        }
    }      

    public function obtenerValoresPorId($idPlan) {
        try {
            // Conexión a la base de datos
            $db = new Conexion();
            $conn = $db->getConnection();
    
            // Preparar la consulta SQL para obtener los valores del plan
            $query = "SELECT valores FROM plan WHERE idplan = :idPlan";
            $stmt = $conn->prepare($query);
    
            // Asignar el valor al parámetro
            $stmt->bindParam(':idPlan', $idPlan);
    
            // Ejecutar la consulta
            $stmt->execute();
    
            // Obtener el resultado
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // Retornar los valores si se encuentran, si no, retorna null
            return $resultado ? $resultado['valores'] : null;
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error: " . $e->getMessage();
            return null;
        }
    }
    
    public function obtenerPlanPorIdMango($idPlan) {
        try {
            $db = new Conexion();
            $conn = $db->getConnection();
    
            // Consulta actualizada para incluir todos los campos solicitados
            $query = "SELECT nombreEmpresa, fecha, promotores, logo, mision, vision, valores, 
                      objetivosGenerales, objetivosEspecificos, fortalezas, debilidades, 
                      oportunidades, amenazas, unidadesEstrategicas, estrategia, 
                      accionesCompetitivas, conclusiones 
                      FROM plan 
                      WHERE idplan = :idPlan";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':idPlan', $idPlan, PDO::PARAM_INT); // Asegurar que se pase como entero
            $stmt->execute();
    
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }      

    public function obtenerFortalezasPorId($idPlan) {
        try {
            // Conexión a la base de datos
            $db = new Conexion();
            $conn = $db->getConnection();
    
            // Preparar la consulta SQL para obtener las fortalezas del plan
            $query = "SELECT fortalezas FROM plan WHERE idplan = :idPlan";
            $stmt = $conn->prepare($query);
    
            // Asignar el valor al parámetro
            $stmt->bindParam(':idPlan', $idPlan);
    
            // Ejecutar la consulta
            $stmt->execute();
    
            // Obtener el resultado
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // Retornar las fortalezas si se encuentran, si no, retorna null
            return $resultado ? $resultado['fortalezas'] : null;
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public function actualizarFortalezas($idPlan, $nuevasFortalezas) {
        try {
            // Conexión a la base de datos
            $db = new Conexion();
            $conn = $db->getConnection();
    
            // Preparar la consulta SQL para actualizar las fortalezas del plan
            $query = "UPDATE plan SET fortalezas = :fortalezas WHERE idplan = :idPlan";
    
            $stmt = $conn->prepare($query);
    
            // Asignar los valores a los parámetros
            $stmt->bindParam(':fortalezas', $nuevasFortalezas);
            $stmt->bindParam(':idPlan', $idPlan);
    
            // Ejecutar la consulta
            if ($stmt->execute()) {
                return true; // Actualización exitosa
            } else {
                return false; // Fallo en la actualización
            }
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function obtenerDebilidadesPorId($idPlan) {
        try {
            // Conexión a la base de datos
            $db = new Conexion();
            $conn = $db->getConnection();
    
            // Preparar la consulta SQL para obtener las debilidades del plan
            $query = "SELECT debilidades FROM plan WHERE idplan = :idPlan";
            $stmt = $conn->prepare($query);
    
            // Asignar el valor al parámetro
            $stmt->bindParam(':idPlan', $idPlan);
    
            // Ejecutar la consulta
            $stmt->execute();
    
            // Obtener el resultado
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // Retornar las debilidades si se encuentran, si no, retorna null
            return $resultado ? $resultado['debilidades'] : null;
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public function actualizarDebilidades($idPlan, $nuevasDebilidades) {
        try {
            // Conexión a la base de datos
            $db = new Conexion();
            $conn = $db->getConnection();
    
            // Preparar la consulta SQL para actualizar las debilidades del plan
            $query = "UPDATE plan SET debilidades = :debilidades WHERE idplan = :idPlan";
    
            $stmt = $conn->prepare($query);
    
            // Asignar los valores a los parámetros
            $stmt->bindParam(':debilidades', $nuevasDebilidades);
            $stmt->bindParam(':idPlan', $idPlan);
    
            // Ejecutar la consulta
            if ($stmt->execute()) {
                return true; // Actualización exitosa
            } else {
                return false; // Fallo en la actualización
            }
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function obtenerAmenazasPorId($idPlan) {
        try {
            // Conexión a la base de datos
            $db = new Conexion();
            $conn = $db->getConnection();
    
            // Preparar la consulta SQL para obtener las amenazas del plan
            $query = "SELECT amenazas FROM plan WHERE idplan = :idPlan";
            $stmt = $conn->prepare($query);
    
            // Asignar el valor al parámetro
            $stmt->bindParam(':idPlan', $idPlan);
    
            // Ejecutar la consulta
            $stmt->execute();
    
            // Obtener el resultado
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // Retornar las amenazas si se encuentran, si no, retorna null
            return $resultado ? $resultado['amenazas'] : null;
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public function actualizarAmenazas($idPlan, $nuevasAmenazas) {
        try {
            // Conexión a la base de datos
            $db = new Conexion();
            $conn = $db->getConnection();
    
            // Preparar la consulta SQL para actualizar las amenazas del plan
            $query = "UPDATE plan SET amenazas = :amenazas WHERE idplan = :idPlan";
    
            $stmt = $conn->prepare($query);
    
            // Asignar los valores a los parámetros
            $stmt->bindParam(':amenazas', $nuevasAmenazas);
            $stmt->bindParam(':idPlan', $idPlan);
    
            // Ejecutar la consulta
            if ($stmt->execute()) {
                return true; // Actualización exitosa
            } else {
                return false; // Fallo en la actualización
            }
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function obtenerOportunidadesPorId($idPlan) {
        try {
            // Conexión a la base de datos
            $db = new Conexion();
            $conn = $db->getConnection();
    
            // Preparar la consulta SQL para obtener las oportunidades del plan
            $query = "SELECT oportunidades FROM plan WHERE idplan = :idPlan";
            $stmt = $conn->prepare($query);
    
            // Asignar el valor al parámetro
            $stmt->bindParam(':idPlan', $idPlan);
    
            // Ejecutar la consulta
            $stmt->execute();
    
            // Obtener el resultado
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // Retornar las oportunidades si se encuentran, si no, retorna null
            return $resultado ? $resultado['oportunidades'] : null;
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public function actualizarOportunidades($idPlan, $nuevasOportunidades) {
        try {
            // Conexión a la base de datos
            $db = new Conexion();
            $conn = $db->getConnection();
    
            // Preparar la consulta SQL para actualizar las oportunidades del plan
            $query = "UPDATE plan SET oportunidades = :oportunidades WHERE idplan = :idPlan";
    
            $stmt = $conn->prepare($query);
    
            // Asignar los valores a los parámetros
            $stmt->bindParam(':oportunidades', $nuevasOportunidades);
            $stmt->bindParam(':idPlan', $idPlan);
    
            // Ejecutar la consulta
            if ($stmt->execute()) {
                return true; // Actualización exitosa
            } else {
                return false; // Fallo en la actualización
            }
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error: " . $e->getMessage();
            return false;
        }
    }



    public function obtenerReflexionesPorId($idPlan) {
        try {
            // Conexión a la base de datos
            $db = new Conexion();
            $conn = $db->getConnection();
    
            // Preparar la consulta SQL para obtener las reflexiones del plan
            $query = "SELECT reflexion FROM plan WHERE idplan = :idPlan";
            $stmt = $conn->prepare($query);
    
            // Asignar el valor al parámetro
            $stmt->bindParam(':idPlan', $idPlan);
    
            // Ejecutar la consulta
            $stmt->execute();
    
            // Obtener el resultado
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // Retornar las reflexiones si se encuentran, si no, retorna null
            return $resultado ? $resultado['reflexion'] : null;
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public function actualizarReflexiones($idPlan, $nuevasReflexiones) {
        try {
            // Conexión a la base de datos
            $db = new Conexion();
            $conn = $db->getConnection();
    
            // Preparar la consulta SQL para actualizar las reflexiones del plan
            $query = "UPDATE plan SET reflexion = :reflexion WHERE idplan = :idPlan";
    
            $stmt = $conn->prepare($query);
    
            // Asignar los valores a los parámetros
            $stmt->bindParam(':reflexion', $nuevasReflexiones);
            $stmt->bindParam(':idPlan', $idPlan);
    
            // Ejecutar la consulta
            if ($stmt->execute()) {
                return true; // Actualización exitosa
            } else {
                return false; // Fallo en la actualización
            }
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error: " . $e->getMessage();
            return false;
        }
    }


    public function obtenerAutovalorPorId($idPlan) {
        try {
            // Conexión a la base de datos
            $db = new Conexion();
            $conn = $db->getConnection();
    
            // Preparar la consulta SQL para obtener el autodiagnóstico del plan
            $query = "SELECT autovalor FROM plan WHERE idplan = :idPlan";
            $stmt = $conn->prepare($query);
    
            // Asignar el valor al parámetro
            $stmt->bindParam(':idPlan', $idPlan);
    
            // Ejecutar la consulta
            $stmt->execute();
    
            // Obtener el resultado
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // Retornar el autodiagnóstico si se encuentra, si no, retorna null
            return $resultado ? $resultado['autovalor'] : null;
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error: " . $e->getMessage();
            return null;
        }
    }


    public function actualizarAutovalor($idPlan, $nuevoAutovalor) {
        try {
            // Conexión a la base de datos
            $db = new Conexion();
            $conn = $db->getConnection();
    
            // Preparar la consulta SQL para actualizar el autodiagnóstico del plan
            $query = "UPDATE plan SET autovalor = :autovalor WHERE idplan = :idPlan";
    
            $stmt = $conn->prepare($query);
    
            // Asignar los valores a los parámetros
            $stmt->bindParam(':autovalor', $nuevoAutovalor);
            $stmt->bindParam(':idPlan', $idPlan);
    
            // Ejecutar la consulta
            if ($stmt->execute()) {
                return true; // Actualización exitosa
            } else {
                return false; // Fallo en la actualización
            }
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function obtenerValorPorterPorId($idPlan) {
        try {
            // Conexión a la base de datos
            $db = new Conexion();
            $conn = $db->getConnection();
    
            // Preparar la consulta SQL para obtener el autodiagnóstico del plan
            $query = "SELECT valorPorter FROM plan WHERE idplan = :idPlan";
            $stmt = $conn->prepare($query);
    
            // Asignar el valor al parámetro
            $stmt->bindParam(':idPlan', $idPlan);
    
            // Ejecutar la consulta
            $stmt->execute();
    
            // Obtener el resultado
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // Retornar el autodiagnóstico si se encuentra, si no, retorna null
            return $resultado ? $resultado['valorporter'] : null;
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public function actualizarValorPorter($idPlan, $nuevovalorPorter) {
        try {
            // Conexión a la base de datos
            $db = new Conexion();
            $conn = $db->getConnection();
    
            // Preparar la consulta SQL para actualizar el autodiagnóstico del plan
            $query = "UPDATE plan SET valorporter = :valorporter WHERE idplan = :idPlan";
    
            $stmt = $conn->prepare($query);
    
            // Asignar los valores a los parámetros
            $stmt->bindParam(':valorporter', $nuevovalorPorter);
            $stmt->bindParam(':idPlan', $idPlan);
    
            // Ejecutar la consulta
            if ($stmt->execute()) {
                return true; // Actualización exitosa
            } else {
                return false; // Fallo en la actualización
            }
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function actualizarCamposPlan($idPlan, $campos)
    {
        try {
            $db = new Conexion();
            $conn = $db->getConnection();
    
            // Verificar que los campos no sean nulos o vacíos
            if (empty($campos['unidadesestrategicas']) || empty($campos['estrategia']) || empty($campos['accionescompetitivas']) || empty($campos['conclusiones'])) {
                throw new Exception("Uno o más campos están vacíos");
            }
    
            $sql = "UPDATE plan SET 
                        unidadesestrategicas = :unidadesestrategicas, 
                        estrategia = :estrategia, 
                        accionescompetitivas = :accionescompetitivas, 
                        conclusiones = :conclusiones 
                    WHERE idplan = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':unidadesestrategicas', $campos['unidadesestrategicas']);
            $stmt->bindParam(':estrategia', $campos['estrategia']);
            $stmt->bindParam(':accionescompetitivas', $campos['accionescompetitivas']);
            $stmt->bindParam(':conclusiones', $campos['conclusiones']);
            $stmt->bindParam(':id', $idPlan);
    
            // Ejecutar la consulta
            if ($stmt->execute()) {
                return true; // Si la actualización fue exitosa
            } else {
                throw new Exception("Error al ejecutar la consulta SQL");
            }
        } catch (PDOException $e) {
            // Capturar errores relacionados con PDO
            echo "Error de base de datos: " . $e->getMessage(); // Mostrar el error de PDO
            return false;
        } catch (Exception $e) {
            // Capturar errores generales y otros
            echo "Error: " . $e->getMessage(); // Mostrar el mensaje de error
            return false;
        }
    }    
}