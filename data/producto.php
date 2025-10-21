<?php
// Conexión a la base de datos
include 'db_connection.php';

class Producto {
    
    private $conn;
    
    public function __construct($db) {
        $this->conn = $db;
    }

    // Función para obtener todos los productos
    public function getProductos() {
        $query = "SELECT * FROM producto";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Función para obtener un producto por ID
    public function getProductoById($id) {
        $query = "SELECT * FROM producto WHERE idproducto = :idproducto";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':idproducto', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Función para crear un nuevo producto
    public function createProducto($data) {
        $query = "INSERT INTO producto (idplan, ventas, tsc1, tsc2, tsc3, tsc4, dgs1, dgs2, dgs3, dgs4, compe1, nombre) 
                  VALUES (:idplan, :ventas, :tsc1, :tsc2, :tsc3, :tsc4, :dgs1, :dgs2, :dgs3, :dgs4, :compe1, :nombre)";
        $stmt = $this->conn->prepare($query);
        
        // Enlace de parámetros
        $stmt->bindParam(':idplan', $data['idplan']);
        $stmt->bindParam(':ventas', $data['ventas']);
        $stmt->bindParam(':tsc1', $data['tsc1']);
        $stmt->bindParam(':tsc2', $data['tsc2']);
        $stmt->bindParam(':tsc3', $data['tsc3']);
        $stmt->bindParam(':tsc4', $data['tsc4']);
        $stmt->bindParam(':dgs1', $data['dgs1']);
        $stmt->bindParam(':dgs2', $data['dgs2']);
        $stmt->bindParam(':dgs3', $data['dgs3']);
        $stmt->bindParam(':dgs4', $data['dgs4']);
        $stmt->bindParam(':compe1', $data['compe1']);
        $stmt->bindParam(':nombre', $data['nombre']);
        
        // Ejecuta la consulta
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Función para actualizar un producto
    public function updateProducto($id, $data) {
        $query = "UPDATE producto 
                  SET idplan = :idplan, ventas = :ventas, tsc1 = :tsc1, tsc2 = :tsc2, tsc3 = :tsc3, tsc4 = :tsc4, 
                      dgs1 = :dgs1, dgs2 = :dgs2, dgs3 = :dgs3, dgs4 = :dgs4, compe1 = :compe1, nombre = :nombre 
                  WHERE idproducto = :idproducto";
        $stmt = $this->conn->prepare($query);
        
        // Enlace de parámetros
        $stmt->bindParam(':idproducto', $id);
        $stmt->bindParam(':idplan', $data['idplan']);
        $stmt->bindParam(':ventas', $data['ventas']);
        $stmt->bindParam(':tsc1', $data['tsc1']);
        $stmt->bindParam(':tsc2', $data['tsc2']);
        $stmt->bindParam(':tsc3', $data['tsc3']);
        $stmt->bindParam(':tsc4', $data['tsc4']);
        $stmt->bindParam(':dgs1', $data['dgs1']);
        $stmt->bindParam(':dgs2', $data['dgs2']);
        $stmt->bindParam(':dgs3', $data['dgs3']);
        $stmt->bindParam(':dgs4', $data['dgs4']);
        $stmt->bindParam(':compe1', $data['compe1']);
        $stmt->bindParam(':nombre', $data['nombre']);
        
        // Ejecuta la consulta
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Función para eliminar un producto
    public function deleteProducto($id) {
        $query = "DELETE FROM producto WHERE idproducto = :idproducto";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':idproducto', $id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}

// Uso del archivo
$database = new Database();
$db = $database->getConnection();

$producto = new Producto($db);

// Ejemplo de cómo obtener productos
$productos = $producto->getProductos();
while ($row = $productos->fetch(PDO::FETCH_ASSOC)){
    echo "Producto: " . $row['nombre'] . "<br>";
}

?>
