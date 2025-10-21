<?php
    session_start();
    require_once __DIR__ . '/../data/neonConnection.php'; // Conexión a la base de datos

    // Instancia de la conexión
    $conexion = new Conexion();
    $pdo = $conexion->getConnection();
    // Obtener idusuario e idplan
    $idusuario = $_SESSION['idusuario'] ?? null;
    $idplan = $_SESSION['idPlan'] ?? null;

    // Inicializar productos si no están definidos en la sesión
    if (!isset($_SESSION['productos']) || empty($_SESSION['productos'])) {
        // Cargar productos desde la base de datos y guardarlos en la sesión
        $_SESSION['productos'] = cargarProductosDesdeBD($pdo, $idplan);
    }

    // Ahora, usamos los productos desde la sesión para mostrarlos en la página.
    $productos = $_SESSION['productos'];



    // Limpiar productos de la sesión
    if (isset($_POST['limpiarSesion'])) {
        $_SESSION['productos'] = [];
    }

    function obtenerVentas($pdo, $nombre, $idplan) {
        $stmt = $pdo->prepare("SELECT ventas FROM producto WHERE nombre = :nombre AND idplan = :idplan");
        $stmt->execute([':nombre' => $nombre, ':idplan' => $idplan]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (int) $result['ventas'] : 0;
    }

    // Obtener ventas de los productos en la sesión
    $ventas = [];
    $totalVentas = 0;

    foreach ($_SESSION['productos'] as $index => $producto) {
        $ventas[$index] = obtenerVentas($pdo, $producto['nombre'], $idplan);
        $totalVentas += $ventas[$index];
    }

    // Guardar ventas si se envía el formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ventas'])) {
        guardarVentas($pdo, $_POST['ventas'], $idplan);
    }

    // Función para guardar ventas
    function guardarVentas($pdo, $ventas, $idplan) {
        try {
            foreach ($ventas as $index => $venta) {
                $producto = $_SESSION['productos'][$index]['nombre'];
                $stmt = $pdo->prepare("UPDATE producto SET ventas = :ventas WHERE nombre = :nombre AND idplan = :idplan");
                $stmt->execute([':ventas' => $venta, ':nombre' => $producto, ':idplan' => $idplan]);
                echo "<script>alert('Ventas guardadas correctamente.');</script>";
            }
            
            // JavaScript para recargar la página
            echo "<script type='text/javascript'>window.location.href = window.location.href;</script>";
        } catch (PDOException $e) {
            echo "<script>alert('Error al guardar ventas: " . addslashes($e->getMessage()) . "');</script>";
        }
    }    

    // Agregar producto
    if (isset($_POST['agregarProducto'])) {
        $producto = trim($_POST['producto']);
        agregarProducto($pdo, $producto, $idplan);
    }

    function agregarProducto($pdo, $producto, $idplan) {
        try {
            // Verificar si el producto ya existe en la base de datos
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM producto WHERE nombre = :nombre AND idplan = :idplan");
            $stmt->execute([':nombre' => $producto, ':idplan' => $idplan]);
            $productoExiste = $stmt->fetchColumn();
    
            if ($productoExiste > 0) {
                echo "<script>alert('El producto ya existe en la base de datos.');</script>";
                return;
            }
    
            // Insertar el producto si no existe
            $stmt = $pdo->prepare("INSERT INTO producto (nombre, idplan) VALUES (:nombre, :idplan)");
            if ($stmt->execute([':nombre' => $producto, ':idplan' => $idplan])) {
                $_SESSION['productos'][] = ['nombre' => $producto, 'idplan' => $idplan];
                echo "<script>alert('Producto agregado correctamente.');</script>";
            }
        } catch (PDOException $e) {
            echo "<script>alert('Error al agregar producto: " . addslashes($e->getMessage()) . "');</script>";

        }
    }

    // Eliminar producto
    if (isset($_POST['eliminarProducto'])) {
        $index = $_POST['index'];
        eliminarProducto($pdo, $index);
    }

    function eliminarProducto($pdo, $index) {
        $productoData = $_SESSION['productos'][$index];
        try {
            $stmt = $pdo->prepare("DELETE FROM producto WHERE nombre = :nombre AND idplan = :idplan");
            $stmt->execute([':nombre' => $productoData['nombre'], ':idplan' => $productoData['idplan']]);

            // Eliminar de la sesión y reindexar
            unset($_SESSION['productos'][$index]);
            $_SESSION['productos'] = array_values($_SESSION['productos']);
            echo "<script>alert('Producto eliminado correctamente.');</script>";
        } catch (PDOException $e) {
            echo "<script>alert('Error al eliminar producto: " . addslashes($e->getMessage()) . "');</script>";
        }
    }

    // Función para guardar Tasas de Crecimiento del Mercado (TCM)
    function guardarTcm($pdo, $tsc, $idplan) {
        try {
            foreach ($tsc as $index => $tasa) {
                $producto = $_SESSION['productos'][$index]['nombre'];
                // Actualiza los campos tsc1, tsc2, tsc3, tsc4 según el índice
                $stmt = $pdo->prepare("UPDATE producto SET tsc1 = :tsc1, tsc2 = :tsc2, tsc3 = :tsc3, tsc4 = :tsc4 WHERE nombre = :nombre AND idplan = :idplan");
                $stmt->execute([
                    ':tsc1' => $tasa[0],
                    ':tsc2' => $tasa[1],
                    ':tsc3' => $tasa[2],
                    ':tsc4' => $tasa[3],
                    ':nombre' => $producto,
                    ':idplan' => $idplan
                ]);
            }
            echo "<script>alert('Tasas de crecimiento guardadas correctamente.');</script>";
        } catch (PDOException $e) {
            echo "<script>alert('Error al guardar tasas de crecimiento: " . addslashes($e->getMessage()) . "');</script>";
        }
    }

    // Guardar TCM si se envía el formulario

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardarTcm'])) {
        $tcm = [];
        for ($i = 0; $i < count($_SESSION['productos']); $i++) {
            $tcm[$i] = [
                $_POST['tsc1'][$i] ?? 0, // TCM para 2019-2020
                $_POST['tsc2'][$i] ?? 0, // TCM para 2020-2021
                $_POST['tsc3'][$i] ?? 0, // TCM para 2021-2022
                $_POST['tsc4'][$i] ?? 0  // TCM para 2022-2023
            ];
        }
        guardarTcm($pdo, $tcm, $idplan);
    }

    function cargarProductosDesdeBD($pdo, $idplan) {
        $stmt = $pdo->prepare("SELECT * FROM producto WHERE idplan = :idplan");
        $stmt->execute([':idplan' => $idplan]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Función para guardar Demanda Global del Sector (DGS)
    function guardarDgs($pdo, $dgs, $idplan) {
        try {
            foreach ($dgs as $index => $demanda) {
                $producto = $_SESSION['productos'][$index]['nombre'];
                // Actualiza los campos dgs1, dgs2, dgs3, dgs4, dgs5 según el índice
                $stmt = $pdo->prepare("UPDATE producto SET dgs1 = :dgs1, dgs2 = :dgs2, dgs3 = :dgs3, dgs4 = :dgs4, dgs5 = :dgs5 WHERE nombre = :nombre AND idplan = :idplan");
                $stmt->execute([
                    ':dgs1' => $demanda[0], // Demanda Global para 2019
                    ':dgs2' => $demanda[1], // Demanda Global para 2020
                    ':dgs3' => $demanda[2], // Demanda Global para 2021
                    ':dgs4' => $demanda[3], // Demanda Global para 2022
                    ':dgs5' => $demanda[4], // Demanda Global para 2023
                    ':nombre' => $producto,
                    ':idplan' => $idplan
                ]);
            }
            echo "<script>alert('Demanda global guardada correctamente.');</script>";
        } catch (PDOException $e) {
            echo "Error al guardar la demanda global del sector: " . $e->getMessage();
        }
    }

    // Guardar DGS si se envía el formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardarDgs'])) {
        $dgs = [];
        for ($i = 0; $i < count($_SESSION['productos']); $i++) {
            $dgs[$i] = [
                $_POST['dgs1'][$i] ?? 0, // Demanda Global para 2019
                $_POST['dgs2'][$i] ?? 0, // Demanda Global para 2020
                $_POST['dgs3'][$i] ?? 0, // Demanda Global para 2021
                $_POST['dgs4'][$i] ?? 0, // Demanda Global para 2022
                $_POST['dgs5'][$i] ?? 0  // Demanda Global para 2023
            ];
        }
        guardarDgs($pdo, $dgs, $idplan);
    }

    // Función para guardar Niveles de Competencia (compe)
    function guardarCompetencia($pdo, $competencia, $idplan) {
        try {
            foreach ($competencia as $index => $data) {
                $niveles = $data['niveles'];
                $mayor = $data['mayor'];
                $producto = $_SESSION['productos'][$index]['nombre'];
                
                // Actualiza los campos compe1, compe2, ..., compe9 y el campo "mayor"
                $stmt = $pdo->prepare("
                    UPDATE producto 
                    SET compe1 = :compe1, compe2 = :compe2, compe3 = :compe3, compe4 = :compe4, 
                        compe5 = :compe5, compe6 = :compe6, compe7 = :compe7, compe8 = :compe8, compe9 = :compe9, 
                        mayor = :mayor
                    WHERE nombre = :nombre AND idplan = :idplan
                ");
                $stmt->execute([
                    ':compe1' => $niveles[0],
                    ':compe2' => $niveles[1],
                    ':compe3' => $niveles[2],
                    ':compe4' => $niveles[3],
                    ':compe5' => $niveles[4],
                    ':compe6' => $niveles[5],
                    ':compe7' => $niveles[6],
                    ':compe8' => $niveles[7],
                    ':compe9' => $niveles[8],
                    ':mayor'  => $mayor,
                    ':nombre' => $producto,
                    ':idplan' => $idplan
                ]);
            }
            echo "<script>alert('Niveles de competencia y valor mayor guardados correctamente.');</script>";
        } catch (PDOException $e) {
            echo "Error al guardar niveles de competencia: " . $e->getMessage();
        }
    }

    // Guardar competencia si se envía el formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardarCompetencia'])) {
        $competencia = [];
        for ($i = 0; $i < count($_SESSION['productos']); $i++) {
            // Recolectar los niveles de ventas de los competidores
            $nivelesVentas = [
                $_POST['niveles_ventas'][$i]['CP1'] ?? 0, // Nivel de ventas para CP1
                $_POST['niveles_ventas'][$i]['CP2'] ?? 0, // Nivel de ventas para CP2
                $_POST['niveles_ventas'][$i]['CP3'] ?? 0, // Nivel de ventas para CP3
                $_POST['niveles_ventas'][$i]['CP4'] ?? 0, // Nivel de ventas para CP4
                $_POST['niveles_ventas'][$i]['CP5'] ?? 0, // Nivel de ventas para CP5
                $_POST['niveles_ventas'][$i]['CP6'] ?? 0, // Nivel de ventas para CP6
                $_POST['niveles_ventas'][$i]['CP7'] ?? 0, // Nivel de ventas para CP7
                $_POST['niveles_ventas'][$i]['CP8'] ?? 0, // Nivel de ventas para CP8
                $_POST['niveles_ventas'][$i]['CP9'] ?? 0  // Nivel de ventas para CP9
            ];

            // Calcular el valor "mayor" (el máximo nivel de ventas)
            $mayor = max($nivelesVentas);

            // Almacenar tanto los niveles de competencia como el valor "mayor" en el array $competencia
            $competencia[$i] = [
                'niveles' => $nivelesVentas,
                'mayor' => $mayor
            ];
        }

        // Guardar los niveles de competencia y el valor "mayor" en la base de datos
        guardarCompetencia($pdo, $competencia, $idplan);
    }

    // Función para clasificar productos en la matriz BCG basada únicamente en la Demanda Global
    // Función para clasificar productos en la matriz BCG

    // echo "cuota".$cuotaMercado." ; ";
    // echo "crecimiento".$crecimientoMercado." ; ";
    // Función para clasificar productos en la matriz BCG
    function generarMatrizBCG($pdo, $idplan) {
        $productos = $_SESSION['productos'];
        $clasificacion = [];
        $decisiones = []; // Array para almacenar las decisiones estratégicas

        foreach ($productos as $index => $producto) {
            // Obtener ventas y competidores
            $stmt = $pdo->prepare("
                SELECT ventas, compe1, compe2, compe3, compe4, compe5, compe6, compe7, compe8, compe9, tsc1, tsc2, tsc3, tsc4
                FROM producto 
                WHERE nombre = :nombre AND idplan = :idplan
            ");
            $stmt->execute([':nombre' => $producto['nombre'], ':idplan' => $idplan]);
            $datos = $stmt->fetch(PDO::FETCH_ASSOC);

            // Cálculo de la cuota de mercado
            $ventas = $datos['ventas'];
            $ventasCompetidores = $datos['compe1'] + $datos['compe2'] + $datos['compe3'] + $datos['compe4'] + 
                                $datos['compe5'] + $datos['compe6'] + $datos['compe7'] + 
                                $datos['compe8'] + $datos['compe9'];
            $cuotaMercado = ($ventas / ($ventas + $ventasCompetidores)) * 100; // Expresado como porcentaje

            // Cálculo del crecimiento del mercado
            $crecimientoMercado = ($datos['tsc1'] + $datos['tsc2'] + $datos['tsc3'] + $datos['tsc4']) / 4; // Promedio de tsc

            // Clasificar el producto en la matriz BCG y asignar decisión estratégica
            if ($cuotaMercado > 50) { // Cuota de mercado alta
                if ($crecimientoMercado > 50) { // Alto crecimiento
                    $clasificacion[$index] = 'Estrella'; // Potenciar
                    $decisiones[$index] = 'Potenciar';
                } else { // Bajo crecimiento
                    $clasificacion[$index] = 'Vaca'; // Mantener
                    $decisiones[$index] = 'Mantener';
                }
            } else { // Cuota de mercado baja
                if ($crecimientoMercado > 50) { // Alto crecimiento
                    $clasificacion[$index] = 'Incógnita'; // Evaluar
                    $decisiones[$index] = 'Evaluar';
                } else { // Bajo crecimiento
                    $clasificacion[$index] = 'Perro'; // Reestructurar o desinvertir
                    $decisiones[$index] = 'Reestructurar ';
                }
            }
        }

        return ['clasificacion' => $clasificacion, 'decisiones' => $decisiones]; // Retorna ambas clasificaciones y decisiones
    }



    // Llamada a la función para mostrar la tabla de la matriz BCG
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generarMatrizBCG'])) {
        $resultados = generarMatrizBCG($pdo, $idplan);
        $clasificacion = $resultados['clasificacion'];
        $decisiones = $resultados['decisiones']; // Agregar la obtención de decisiones
        echo "<script>alert('La matriz BCG se ha generado correctamente.');</script>";
    }

    // Función para cargar fortalezas y debilidades
    function cargarFortalezasYDebilidades($pdo, $idplan) {
        try {
            // Consultar fortalezas
            $stmtFortalezas = $pdo->prepare("SELECT fortalezas FROM plan WHERE idplan = :idplan");
            $stmtFortalezas->execute([':idplan' => $idplan]);
            $fortalezas = $stmtFortalezas->fetchColumn();

            // Consultar debilidades
            $stmtDebilidades = $pdo->prepare("SELECT debilidades FROM plan WHERE idplan = :idplan");
            $stmtDebilidades->execute([':idplan' => $idplan]);
            $debilidades = $stmtDebilidades->fetchColumn();

            return [
                'fortalezas' => $fortalezas ?: '',
                'debilidades' => $debilidades ?: ''
            ];
        } catch (PDOException $e) {
            echo "<script>alert('Error al cargar fortalezas y debilidades: " . addslashes($e->getMessage()) . "');</script>";
            return [
                'fortalezas' => '',
                'debilidades' => ''
            ];
        }
    }
?>